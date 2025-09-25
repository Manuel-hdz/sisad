<?php
	/**
	  * Nombre del Módulo: Unidadd e Salud Ocupacional
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:05/Octubre/2012
	  * Descripción: Este archivo contiene funciones para consultar la información relacionada con el formulario de donde se Generan los Planes de Contingencia
	**/


	//Funcion que muestra los registros en la bitácora de Radiografias
	function mostrarResultadosExamenes($fechaIni,$fechaFin){
		//Convertimos las fechas en formato aaa-mm-dd ya que son como se guardan en la BD.
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		$titulo = "";
		//Volvemos a convertir las fechas definidas anteriormente para colocar el titulo dentro d ela tabla que mostrara los resultados 
		$fechaIni=modFecha($fechaI,1);
		$fechaFin=modFecha($fechaF,1);
		
		$conn=conecta("bd_clinica");
		
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT historial_clinico_id_historial, nom_empleado, fecha_exp, resultado, recomendacion, imss, tipo_clasificacion 
			FROM historial_clinico JOIN resultados_historiales ON historial_clinico.id_historial = resultados_historiales.historial_clinico_id_historial 
			WHERE fecha_exp BETWEEN  '$fechaI' AND '$fechaF' AND tipo_clasificacion = 'INTERNO'  ORDER BY fecha_exp";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Resultados de Ex&aacute;menes Periodicos $fechaIni al $fechaFin";

		//Ejecutar la sentencia SQL				
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosRepCC'>
				<caption class='titulo_etiqueta'>$titulo</caption>
				<thead>
					<tr>
        				<td class='nombres_columnas' align='center'>SELECCIONAR</td>				
						<th class='nombres_columnas' align='center'>CLAVE HISTORIAL</th>
						<th class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</th>
						<th class='nombres_columnas' align='center'>FECHA</th>
        				<th class='nombres_columnas' align='center'>RESULTADO</th>
        				<th class='nombres_columnas' align='center'>RECOMENDACI&Oacute;N</th>
        				<td class='nombres_columnas' align='center'>IMSS</td>										
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{
				echo "	<tr>
							</td>
								<td class='$nom_clase' align='center'>
									<input type='checkBox' id='ckb_resRep$cont' name='ckb_resRep$cont' value='$datos[historial_clinico_id_historial]'
									 onclick='activarCkbRep(this,$cont);'/>
								</td>					 
								<td class='$nom_clase' align='center'>$datos[historial_clinico_id_historial]</td>
								<td class='$nom_clase' align='center'>$datos[nom_empleado]</td>
								<td class='$nom_clase' align='center'>".modFecha($datos["fecha_exp"],1)."</td>
								
								<td class='$nom_clase' align='center'>
									<textarea id='txa_resultado$cont' name='txa_resultado$cont' cols='15' rows='2' disabled='disabled' >$datos[resultado]</textarea></td>
								
								<td class='$nom_clase' align='center'>
									<textarea id='txa_recomendacion$cont' name='txa_recomendacion$cont' cols='15' rows='2'  disabled='disabled' >$datos[recomendacion]</textarea>
								</td>										

								<td class='$nom_clase' align='center'>
									<textarea  id='txa_imss$cont' name='txa_imss$cont' cols='15' rows='2' disabled='disabled'>$datos[imss]</textarea></td>";?><?php 
									
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";					
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";
			echo "<input type='hidden' name='hdn_cantCkb' id='hdn_cantCkb' value='".--$cont."' />";
		}
		else{
			echo "<meta http-equiv='refresh' content='0;url=frm_reporteResultadosExamenes.php?noResults'>";
		}
		mysql_close($conn);
	}
	
	
	 /*Esta funcion genera el id de  los registros en la BD y los cuales pueden ser modificados por el usuario*/
	function obtenerIdResultadosExa(){
		$id="";
		//Crear la sentencia para obtener la clave de la Empresa
		$stm_sql = "SELECT MAX(id_resultado) AS cant FROM resultados_historiales";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
				$id .= $cant;
		}
		return $id;
	}//Fin de la function obtenerIdPresupuesto()
	
	
	 //Esta funcion se encarga de registrar los resultados de examenes que se tienen registrados dentro del sistema
	  function regResultadosExaPeriodicos(){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$band = 0;
		$historiales = array();
		foreach($_POST as $ind =>  $value ){
			//Verificar cada una de los registros que contengan y antepongan el prefijo ckb
			if(substr($ind,0,3)=="ckb"){
				//Mandamos llamar la funcion que obtien el id de los registro y se coloca dentro de una variable para su manejo
				$idResultado=obtenerIdResultadosExa();
				$num = str_replace("ckb_resRep","",$ind);
				//Recuperar la informacion del post		
				$resultado = strtoupper($_POST['txa_resultado'.$num]);
				$recomendacion = strtoupper($_POST['txa_recomendacion'.$num]);
				$imss = strtoupper($_POST['txa_imss'.$num]);
				//
				if(verificarHC($value)){
					//Realizamos la consulta que insertara los datos del historial familiar en la tabla antecendentes familiares
					$stm_sql = "INSERT INTO resultados_historiales (id_resultado, historial_clinico_id_historial, resultado, recomendacion, imss) 
					VALUES('$idResultado', '$value', '$resultado', '$recomendacion', '$imss')";
				}
				else{
					$stm_sql="UPDATE resultados_historiales SET  resultado = '$resultado' , recomendacion = '$recomendacion', imss = '$imss' 
					WHERE historial_clinico_id_historial='$value'";	
				}
				//Ejecutamos la sentencia previamante creada
				$rs = mysql_query($stm_sql);	
				//Si la consulta regresa false, activara la bandera en 1 y rompera el ciclo
				if(!$rs){
					$band==1;
					break;
				}
				$historiales[]=$value;
			}	
		}
		if($band==0){
			mysql_close($conn);
			$cont = 0;
			do{
				registrarOperacion("bd_clinica","$historiales[$cont]","RegResultadosHC",$_SESSION['usr_reg']);
				$cont++;
			}while($cont<count($historiales));//Mientras la variable que recorere el arreglo sea menor que count(funcion que permite obtener el tamaño del arreglo) y se pasa como parametro el nombre del arreglo
			//Redireccionamos a la pantalla de éxito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();	
			mysql_close($conn);		
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}	
	
	
	
	function verificarHC($idHistorial){
		$rs = mysql_query("SELECT id_resultado FROM resultados_historiales WHERE historial_clinico_id_historial = '$idHistorial'");
		if($existe = mysql_fetch_array($rs))
			return false;
		else
			return true;	
	}


//Si el boton "sbr_eliminar" se encuentra definido que mande llamra la funcion que elimina el registro o registros seleccionados
if(isset($_POST["sbt_eliminar"])){
	eliminarRegistroRepHC();
}


	function eliminarRegistroRepHC(){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		//Variable que nos permitira conocer si hubo errores en el registro
		$band = 0;
		$historiales = array();
		foreach($_POST as $ind =>  $value ){
			//Verificar cada una de los registros que contengan y antepongan el prefijo ckb
			if(substr($ind,0,3)=="ckb"){
				//Mandamos llamar la funcion que obtien el id de los registro y se coloca dentro de una variable para su manejo
				$idResultado=obtenerIdResultadosExa();
				$num = str_replace("ckb_resRep","",$ind);
				//Realizamos la consulta que insertara los datos del historial familiar en la tabla antecendentes familiares
				$stm_sql = "DELETE FROM resultados_historiales WHERE historial_clinico_id_historial='$value'";
		
				//Ejecutamos la sentencia previamante creada
				$rs = mysql_query($stm_sql);	
				if(!$rs){
					$band==1;
					break;
				}
				$historiales[]=$value;
			}	
		}
		if($band==0){
			mysql_close($conn);
			//Declaramos la variable en 0 ya que esta variable sera utilizada para recorrer el arreglo $historiales
			$cont = 0;
			//Declaramos un ciclo para recorrer el arreglo de acuerdo al tamaño del mismo y incrementamos la variable 
			do{
				registrarOperacion("bd_clinica","$historiales[$cont]","ElimResultadosHC",$_SESSION['usr_reg']);
				$cont++;
			}while($cont<count($historiales));//Mientras la variable que recorere el arreglo sea menor que count(funcion que permite obtener el tamaño del arreglo) y se pasa como parametro el nombre del arreglo
			//Redireccionamos a la pantalla de éxito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error = mysql_error();	
			mysql_close($conn);		
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	
	}

	function borrarHistorial(){
		 //Esta función elimina los graficos generados durante las consultas y se presione un boton de cancelar
		$h=opendir('tmp');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				unlink('tmp/'.$file);
			}
		}
		closedir($h);
	}
?>
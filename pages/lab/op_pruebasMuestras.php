<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 22/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Plan de Pruebas en la BD
	**/
	
	//Funcion que se encarga de desplegar las mezclas en el rango de fechas
	function mostrarMuestras(){

		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Si viene sbt_consultarMF la buqueda de las mezclas proviene de un rango de fechas
		if(isset($_POST["sbt_consultarMF"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM muestras WHERE fecha_colado BETWEEN '$f1' AND '$f2' ORDER BY fecha_colado";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Muestras en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Muestra Registrada en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}
		
		//Si viene sbt_consultarMC la buqueda de la mezcla proviene el combo box
		else if(isset($_POST["sbt_consultarMC"])){
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM muestras WHERE id_muestra = '$_POST[cmb_idMuestra]'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de la Muestra <em><u> $_POST[cmb_idMuestra]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Muestra</label>";										
		}

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='150%'>				
				<caption class='titulo_etiqueta'>$msg</caption>
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>ID MUESTRA</td>
					<td class='nombres_columnas' align='center'>MEZCLA</td>
					<td class='nombres_columnas' align='center'>NO. MUESTRA</td>
					<td class='nombres_columnas' align='center'>TIPO PRUEBA</td>
					<td class='nombres_columnas' align='center'>CODIGO/LOCALIZACION</td>
					<td class='nombres_columnas' align='center'>FECHA COLADO</td>
					<td class='nombres_columnas' align='center'>REVENIMIENTO</td>
					<td class='nombres_columnas' align='center'>F' C PROYECTO</td>
					<td class='nombres_columnas' align='center'>DIAMETRO</td>
					<td class='nombres_columnas' align='center'>AREA</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb_idMuestra' value='$datos[id_muestra]' /></td>
						<td class='$nom_clase'>$datos[id_muestra]</td>
						<td class='$nom_clase'>$datos[mezclas_id_mezcla]</td>
						<td class='$nom_clase'>$datos[num_muestra]</td>
						<td class='$nom_clase'>$datos[tipo_prueba]</td>
						<td class='$nom_clase'>$datos[codigo_localizacion]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_colado'],1)."</td>
						<td class='$nom_clase'>$datos[revenimiento] CM</td>
						<td class='$nom_clase'>$datos[fprimac_proyecto] KG./CM&sup2;</td>
						<td class='$nom_clase'>$datos[diametro] CM</td>
						<td class='$nom_clase'>$datos[area] CM&sup2;</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	} // fin de la function mostrarMuestras()


	//Funcion para guardar las pruebas agregadas
	function guardarPruebasMuestras(){
		//Recuperar el id de la mezcla almacendao en el arreglo de session
		$claveMuestra = $_SESSION['datosMuestra']['idMuestra'];
							
		//Recorrer el arreglo que contiene las pruebas
		foreach($_SESSION['pruebas'] as $ind => $concepto){
			
			//Obtener el id del plan de pruebas deacuerdo a los registrados en la BD
			$idPlanPrueba = obtenerIdPlanPruebas();

			//Conectar se a la Base de Datos
			$conn = conecta("bd_laboratorio");
								
			//Cambiar el formato de la fecha para almacenarlo en la bd
			$fechaProg = modFecha($concepto['fechaProg'],3);

			//Crear la Sentencia SQL para Alamcenar las pruebas agregadas 
			$stm_sql = "INSERT INTO plan_pruebas (id_plan_prueba, muestras_id_muestra, fecha_programada)
			VALUES ('$idPlanPrueba','$claveMuestra','$fechaProg' )";
			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs)
				$band=1;
			else
				$band=0;
		}// Fin foreach($_SESSION['materiales'] as $ind => $concepto)

		if($band==1){
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$idPlanPrueba,"RegPruebasMezcla",$_SESSION['usr_reg']);			
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		if($band!=1){
			//Recuperar el error marcado y mostrarlo
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}		
	}// Fin function guardarPruebasMuestras()
	
	
	//Esta función se encarga de generar el Id del plan de pruebas deacuerdo a los registros existentes en la BD
	function obtenerIdPlanPruebas(){
		//Realizar la conexion a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Definir las  letras en la Id del plan de pruebas
		$id_cadena = "PBC";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener el id de plan de pruebas del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de Estimaciónes registradas 
		$stm_sql = "SELECT COUNT(id_plan_prueba) AS cant FROM plan_pruebas WHERE id_plan_prueba LIKE 'PBC$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id_cadena;
	}//Fin de la Funcion obtenerIdPlanPruebas()
	
	
	//Funcion que se encarga de desplegar las pruebas agregados
	function mostrarPruebasAdd(){
		echo "<table cellpadding='5' width='100%'>";
		echo "  
			<tr>
				<td class='nombres_columnas' align='center'>CLAVE MUESTRA</td>
			    <td class='nombres_columnas' align='center'>FECHA PROGRAMADA PARA PRUEBA</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;

		foreach ($_SESSION['pruebas'] as $ind => $pruebas) {
						
			echo "<tr>			
					<td class='$nom_clase' align='center'>".$_SESSION['datosMuestra']['idMuestra']."</td>
					<td class='$nom_clase' align='center'>$pruebas[fechaProg]</td>
			</tr>";			
			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";
	}//Fin de la funcion mostrarPruebasAdd()	
?>

<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 24s/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario frm_registrarPruebasMuestras.php
	**/
	
	
	///****************************************************************************************************///
	///**************************  FORMULARIO frm_registrarPruebasMuestras  ********************************///
	///****************************************************************************************************///
		
	//Funcion que se encarga de desplegar las muestras en el rango de fechas
	function mostrarMuestras(){

		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Si viene sbt_consultar la buqueda de las muestras proviene de un rango de fechas
		if(isset($_POST["sbt_consultar"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM muestras WHERE fecha_colado BETWEEN '$f1' AND '$f2' ORDER BY id_muestra";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Muestras en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>	$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Muestra en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";	
			
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_fechaIni" value="<?php echo $_POST['txt_fechaIni'] ?>"/>
			<input type="hidden" name="hdn_fechaFin" value="<?php echo $_POST['txt_fechaFin'] ?>"/>
			<input type="hidden" name="hdn_consultar" value="<?php echo $_POST['sbt_consultar'] ?>"/><?php
		}
		
		//Si viene sbt_consultar2 la buqueda de la muestra proviene el combo box
		else if(isset($_POST["sbt_consultar2"])){
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM muestras WHERE id_muestra = '$_POST[cmb_idMuestra]'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de la Muestra <em><u> $_POST[cmb_idMuestra]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Muestra</label>";	
			
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_idMezcla" value="<?php echo $_POST['cmb_idMuestra'] ?>"/>
			<input type="hidden" name="hdn_consultar2" value="<?php echo $_POST['sbt_consultar2'] ?>"/><?php
		}

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='150%'>				
				<tr>
					<td colspan='11' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='50'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center' width='140'>ID MUESTRA</td>
					<td class='nombres_columnas' align='center' width=''>MEZCLA</td>
					<td class='nombres_columnas' align='center' width='110'>NO. MUESTRA</td>
					<td class='nombres_columnas' align='center' width='110'>TIPO PRUEBA</td>
					<td class='nombres_columnas' align='center' width='100'>C&Oacute;DIGO/LOCALIZACI&Oacute;N</td>
					<td class='nombres_columnas' align='center' width='100'>FECHA COLADO</td>
					<td class='nombres_columnas' align='center' width='100'>REVENIMIENTO</td>
					<td class='nombres_columnas' align='center' width='100'>F' PROYECTO</td>
					<td class='nombres_columnas' align='center' width='50'>DI&Aacute;METRO</td>
					<td class='nombres_columnas' align='center' width='50'>&Aacute;REA</td>										
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb_idMuestra' value='$datos[id_muestra]' />
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
	}
	
	
	///****************************************************************************************************///
	///**************************  FORMULARIO frm_registrarPruebasMuestras2  *******************************///
	///****************************************************************************************************///
	
	//funcion que almacena los id de prueba calidad, id mezcla para posteriormente guardar el resultado de las pruebas
	function guardarRegPruebas(){
	
		//conectar a la bd 
		$conn = conecta('bd_laboratorio');
		
		$idPruebaCalidad = $_SESSION['idCarpeta'];
		$idMuestra = $_SESSION['idMuestraSel'];
		$rs = false;
		
		//Verificar si ya hay un registro existente de la prueba .... 
		$result = obtenerDato("bd_laboratorio","prueba_calidad","muestras_id_muestra","muestras_id_muestra",$idMuestra);
		if($result==""){
			//crear la sentencia
			$stm_sql="INSERT INTO prueba_calidad (id_prueba_calidad, muestras_id_muestra) VALUES ('$idPruebaCalidad','$idMuestra')";
			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
		}//FIN if(result=="")
		//Verificar Resultado
		if ($rs==true || $result!=""){
			if(isset($_SESSION['memoriaFoto'])){
				// recorrer el arreglo de fotos memoria_fotografica
				foreach($_SESSION['memoriaFoto'] as $ind => $value){	
									
					$stm_sql="INSERT INTO memoria_fotografica (prueba_calidad_id_prueba_calidad, etapa, edad, nom_foto) 
					VALUES ('$idPruebaCalidad', '$value[etapa]', '$value[edad]', '$value[foto]')";
					
					//Ejecutar la Sentencia 
					$rs=mysql_query($stm_sql);
				
					//Verificar Resultado
					if ($rs){
						$band=1;
						unset($_SESSION['memoriaFoto']);
					}							
					else
						$band=0;
				}//FIN foreach($_SESSION['memoriaFoto'] as $ind => $concepto)
			}//FIN	if(isset($_SESSION['memoriaFoto']))[
			else{ 
				//Si no entro al ciclo anterir quiere decir que no se han cargado fotografías al arreglo de session, entonces pasar a registrar el detalle
				$band=1;
			}
			
			if($band==1){
				//llamar a la funcion que alamcenara el detalle
				guardarRegPruebas2();
			}
			
			if($band==0){
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='1;url=error.php?err=$error'>";
				//liberar los datos del arreglo de sesion
				unset($_SESSION['resPruebas']);
				unset($_SESSION['idCarpeta']);
				//unset($_SESSION['idMezclaSel']);
			}
		}//FIN if ($rs==true || $result!=""){
		
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='1;url=error.php?err=$error'>";
			//liberar los datos del arreglo de sesion
			unset($_SESSION['resPruebas']);
			unset($_SESSION['idCarpeta']);
			//unset($_SESSION['idMezclaSel']);
		}
				
	}//FIN function guardarRegPruebas()
	
	
	//funcion que almacena el resultado de las pruebas realizadas
	function guardarRegPruebas2(){
		//conectar a la bd
		$conn = conecta('bd_laboratorio');
		
		$id_plan_prueba=$_SESSION['idCarpeta'];	
				
		//Recorrer el arreglo que contiene el resultado de las pruebas
		foreach($_SESSION['resPruebas'] as $ind => $concepto){
			$cargaRuptura = str_replace(",","",$concepto['cargaRuptura']);
			$kgCm = str_replace(",","",$concepto['kgCm']);
			$porcentaje = str_replace(",","",$concepto['porcentaje']);
			$diametro = str_replace(",","",$concepto['diametro']);
			$area = str_replace(",","",$concepto['area']);
			
			$idCarpeta = $_SESSION['idCarpeta'];
			//convertir la fecha a formato para almacenar en la bd;
			$fecha_ruptura = modFecha($concepto['fechaRuptura'],3);
			//crear la sentencia
			$stm_sql="INSERT INTO detalle_prueba_calidad (prueba_calidad_id_prueba_calidad, edad, fecha_ruptura, fprima_c, carga_ruptura, kg_cm2, porcentaje,
			observaciones) VALUES ('$idCarpeta','$concepto[edad]', '$fecha_ruptura', '$concepto[fc]', '$cargaRuptura', '$kgCm','$porcentaje',
			 '$concepto[observaciones]')";

			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs){	
				//verificar si el arreglo el el cual estan almacenadas las pruebas ejecutadas
				$idMuestra = obtenerDato("bd_laboratorio", "prueba_calidad", "muestras_id_muestra", "id_prueba_calidad", $idCarpeta);
				//Insertar el la tabla de mezclas los datos que faltan para completarla que son  el diamento y el area
				
				$sql_stmUp = "UPDATE muestras SET diametro = '$diametro', area = '$area'  WHERE id_muestra = '$idMuestra'";
				//Ejecutar la sentencia de actualización de area y diametro
				$rsUp = mysql_query($sql_stmUp);
			
				//Funcion que permite actualizar el estado de la prueba
				$comprobar = evaluarAlerta($idMuestra,$fecha_ruptura);
				//Variable para almacenar el tiempo de actualizacion del pagina
				$tiempo=0;
				if($comprobar==0){
					$estado = adelantarPrueba($idMuestra,$fecha_ruptura);
					if ($estado==1)
						$tiempo=10;	
				}	
				if(isset($_SESSION['pruebasEjecutadas'])){
					foreach($_SESSION['pruebasEjecutadas'] as $ind => $concepto){
					
						//crear la sentencia
						$stm_sql="INSERT INTO pruebas_realizadas (prueba_calidad_id_prueba_calidad, pruebas_agregados_id_pruebas_agregados, 
								rendimiento_id_registro_rendimiento, catalogo_pruebas_id_prueba) 
								VALUES ('$idCarpeta','N/A',0,'$concepto')";
				
						//Ejecutar la Sentencia 
						$rs=mysql_query($stm_sql);
						
					}// FIN foreach($_SESSION['pruebasEjecutadas'] as $ind => $concepto)
				}//FIN if(isset($_SESSION['pruebasEjecutadas']))
			$band=1;
			}
			else{
				$band=0;
			} 
		}// Fin foreach($_SESSION['resPruebas'] as $ind => $concepto)
		
		if($band==1){
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$_SESSION['resPruebas'][0]['idMuestra'],"ResultadoResistencia",$_SESSION['usr_reg']);
			$conn = conecta("bd_laboratorio");
			echo "<meta http-equiv='refresh' content='$tiempo;url=exito.php'>";		
			//unset($_SESSION['resPruebas']);
			unset($_SESSION['idCatalogoPruebas']);
			unset($_SESSION['datosMezcla']);
			unset($_SESSION['idCarpeta']);
			unset($_SESSION['pruebasEjecutadas']);
		}
		
		if($band==0){
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";
			//liberar los datos del arreglo de sesion
			unset($_SESSION['resPruebas']);
			unset($_SESSION['idCatalogoPruebas']);
			unset($_SESSION['datosMezcla']);
			unset($_SESSION['idCarpeta']);
			unset($_SESSION['pruebasEjecutadas']);
		}
		
		mysql_close($conn);	
	}//FIN function guardarRegPruebas2()
	

	//funcion que carga una imagen
	function cargarFotosLab($etapa,$edad){
		//Incluir el archivo de operaciones para redimensionar la Foto
		include_once("../../includes/op_operacionesBD.php");
		//Variable Bandera para controlar si se ha cargado o no la fotografia
		$flag=0;
		//verificar que el archivo que se esta intentando subir sea el adecuado es decir que sea una imagen
		if((substr($_FILES['txt_fotografia'.$etapa]['type'],0,5) != 'image')){
			exit('S&oacute;lo se Permiten Im&aacute;genes');
			//si se carga un archivo que no es imágen retornar -1 
			$flag=-1;
		}
		//crear la variabe que sera la ruta de almacenamiento
		$Ruta='';
		//crear el nombre de la carpeta contenedora de la fotografia cargada
		$carpeta="documentos/".$_SESSION['idCarpeta'];
		if($edad<10)
			$edad="0".$edad;
		$dir = opendir($Ruta); 
		//verificar si el archivo ha sido almacenado en la carpeta temporal
		if (is_uploaded_file($_FILES['txt_fotografia'.$etapa]['tmp_name'])) { 
			//veririfcar si el nombre de la carpeta exite de lo contrario crearla
			if (!file_exists($carpeta."/"))
				mkdir($carpeta."/", 0777);
			//Verificar si ya existe una fotografia con el mismo nombre	
			if (!file_exists($carpeta."/".$etapa.$edad.$_FILES['txt_fotografia'.$etapa]['name'])){
				//Mover la fotografia de la carpteta temporal a la que le hemos indicado					
				move_uploaded_file($_FILES['txt_fotografia'.$etapa]['tmp_name'], $carpeta."/".$_FILES['txt_fotografia'.$etapa]['name']);
				//llamar la funcion que se encarga de reducir el peso de la fotografia 
				redimensionarFoto($carpeta."/".$_FILES['txt_fotografia'.$etapa]['name'],$_FILES['txt_fotografia'.$etapa]['name'],$carpeta."/",100,100);
				rename($carpeta."/".$_FILES['txt_fotografia'.$etapa]['name'], $carpeta."/".$etapa.$edad.$_FILES['txt_fotografia'.$etapa]['name']);
				
				$_SESSION['fotosPruebas'][]= $carpeta."/".$etapa.$edad.$_FILES['txt_fotografia'.$etapa]['name'];
				//retornar 1 en caso de que la operacion se haya realizado con éxito
				$flag=1;
			}
			else{
				//retornar 2 en caso de que el archivo ya exita
				$flag=2;				
			}
		}
		return $flag;
	}//FIN 	function cargarImagen()	
	
		
	//Esta funcion Elimina las fotos 
	function borrarFotosLab(){
		foreach ($_SESSION["fotosPruebas"] as $ind => $foto){
			//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
			@unlink($foto);
		}
		//rmdir(substr($_SESSION["fotosPruebas"][0],11,10));
	}//Fin de la funcion borrarArchivos() 
	
	//Borrar fotos laboratorio extremo 
	function borrarFotosExtremoLab(){
		foreach ($_SESSION["fotosPruebas"] as $ind => $foto){
			//Instruccion que borra el archivo de la carpeta donde se esta trabajando segun el ID del Equipo
			@unlink('lab/'.$foto);
		}
	}//Fin de la funcion borrarArchivos()
	
	//Esta función se encarga de generar el Id de la Prueba de Calidad de acurdo a los registros existentes en la BD
	function obtenerIdPruebaCalidad(){
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		//Definir las  letras en la Id de la Prueba
		$id_cadena = "PBM";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las Pruebas Calidad del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de Pruebas Calidad registradas 
		$stm_sql = "SELECT MAX(id_prueba_calidad) AS clave FROM prueba_calidad WHERE id_prueba_calidad LIKE 'PBM$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
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
	}//Fin de la Funcion obtenerIdPruebaCalidad()			


	///****************************************************************************************************///
	///**************************  FORMULARIO frm_registrarPruebasAgregados  *******************************///
	///****************************************************************************************************///
	
	//Esta función se encarga de desplegar el o los agregados buscados
	function mostrarAgregados(){
		//Realizar la conexion a la BD de almacen
		$conn = conecta("bd_almacen");
		
		//Si viene sbt_consultar la buqueda proviene de un rango de fechas
		if(isset($_POST["sbt_consultar"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT  nom_material, fecha_alta FROM materiales WHERE fecha_alta>='$f1' AND fecha_alta<='$f2' AND linea_articulo='AGREGADO'
			ORDER BY nom_material";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Agregados del Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Agregado en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}
		
		//Si viene sbt_consultar2 la buqueda  proviene el combo box
		else if(isset($_POST["sbt_consultar2"])){
			//Crear sentencia SQL
			$sql_stm = "SELECT  nom_material, fecha_alta FROM materiales WHERE nom_material = '$_POST[cmb_agregado]'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Datos del Agregado: <em><u>$_POST[cmb_agregado]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Agregado</label>";										
		}

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='5%'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>NOMBRE AGREGADO</td>
					<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>					
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb_nomMat' id='rdb_nomMat' value='$datos[nom_material]'/></td>
						<td class='$nom_clase'>$datos[nom_material]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_alta'],1)."</td>						
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
	}//FIN 	function mostrarAgregados()
	
	
	///****************************************************************************************************///
	///*******************************  FORMULARIO frm_registrarAgregados2  *******************************///
	///****************************************************************************************************///
	
	//Funcion que se encarga de desplegar los materiales agregados
	function mostrarResultados(){
		echo "<table cellpadding='5' width='900'>";
		echo "  
			<tr>
				<td class='nombres_columnas' align='center'>NO</td>
        		<td class='nombres_columnas' align='center'>CONCEPTO</td>
				<td class='nombres_columnas' align='center'>L&Iacute;MITE SUPERIOR</td>
			    <td class='nombres_columnas' align='center'>L&Iacute;MITE INFERIOR</td>
        		<td class='nombres_columnas' align='center'>RETENIDO</td>
        		<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
				<td class='nombres_columnas' align='center'>BORRAR</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$valor=0;
		$aux="";
		foreach ($_SESSION['pruebas'] as $ind => $datospPrueba) {
			echo "<tr>			
					<td class='nombres_filas'>$cont </td>
					<td class='$nom_clase' align='center'>$datospPrueba[concepto]</td>
					<td class='$nom_clase' align='center'>".number_format($datospPrueba['limSuperior'], 2,".",",")."</td>
					<td class='$nom_clase' align='center'>".number_format($datospPrueba['limInferior'], 2,".",",")."</td>
					<td class='$nom_clase' align='center'>".number_format($datospPrueba['retenido'], 2,".",",")."</td>
					<td class='$nom_clase' align='center'>$datospPrueba[observaciones]</td>	";
					$count=count($_SESSION['pruebas']);
						if($cont==$count){?>
							<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
							border="0" title="Borrar Registro" 
							onclick="location.href='frm_registrarAgregados2.php?noRegistro=<?php echo $valor;?>'"/>
							</td><?php 
							$valor=$aux;
						}
						else{?>
							<td class="<?php echo $nom_clase;?>" align="center"	><?php echo "N/A"; ?></td><?php 
						}
						$valor++;			
			echo "</tr>";			
			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "</table>";
	}//Fin de la funcion mostrarMatAdd()
	
	//Funcion que se encarga de guardar toda la información
	function guardarPruebasAgregados(){
		
		$nomAgregado= $_SESSION['nomAgregado'];	
		//Recuperar datos
		$idPruebaAgregados = $_SESSION['infoAgregado']['idPruebaAgregados'];
		$origenMat =  $_SESSION['infoAgregado']['origenMat'];
		$pvss = $_SESSION['infoAgregado']['pvss'];
		$pvsc = $_SESSION['infoAgregado']['pvsc'];
		$densidad = $_SESSION['infoAgregado']['densidad'];
		$absorcion = $_SESSION['infoAgregado']['absorcion'];	
		$finura = $_SESSION['infoAgregado']['finura'];
		$fecha = modFecha($_SESSION['infoAgregado']['fecha'],3);
		$granulometria = $_SESSION['infoAgregado']['granulometria'];
		$wmPvss = $_SESSION['infoAgregado']['wmPvss'];
		$wmPvsc = $_SESSION['infoAgregado']['wmPvsc'];
		$msssDensidad = $_SESSION['infoAgregado']['msssDensidad'];
		$msssAbsorcion = $_SESSION['infoAgregado']['msssAbsorcion'];
		$vmPvss = $_SESSION['infoAgregado']['vmPvss'];
		$vmPvsc = $_SESSION['infoAgregado']['vmPvsc'];
		$va = $_SESSION['infoAgregado']['va'];
		$ws = $_SESSION['infoAgregado']['ws'];
		$pl = $_SESSION['infoAgregado']['pl'];
		$wsc = $_SESSION['infoAgregado']['wsc'];
		$wspl = $_SESSION['infoAgregado']['wspl'];
		$cmb_pruebaEjecutada = $_SESSION['infoAgregado']['cmb_pruebaEjecutada'];
		$hora = date("H:i");
		
		//Conectar se a la Base de Datos
		$connA = conecta("bd_almacen");
		$stm_sql1= "SELECT id_material FROM  materiales WHERE nom_material='$nomAgregado' AND grupo= 'PLANTA'";
		$rs=mysql_query($stm_sql1);
		$datos=mysql_fetch_array($rs);
		mysql_close($connA);
		
		//Conectar se a la Base de Datos
		$conn = conecta("bd_laboratorio");

		//Crear la Sentencia SQL para Alamcenar los resultados agregados 
		$stm_sql= "INSERT INTO pruebas_agregados (id_pruebas_agregados, catalogo_materiales_id_material, origen_material, pvss_wm,  pvss_vm,  pvsc_wm,  pvsc_vm,
		densidad_msss, densidad_va, absorcion_msss, absorcion_ws, granulometria, modulo_finura,pl_wsc, pl_ws,hora, fecha)
		VALUES ('$idPruebaAgregados','$datos[id_material]','$origenMat','$wmPvss','$vmPvss','$wmPvsc','$vmPvsc','$msssDensidad','$va','$msssAbsorcion', '$ws',
		'$granulometria','$finura','$wsc','$wspl','$hora','$fecha')";
		
		//Ejecutar la Sentencia 
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//llamar la funcion que guarda los datos generales de la mezcla
			guardarDetalleAgregado();
		}
		
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//liberar los datos del arreglo de sesion
			unset($_SESSION['nomAgregado']);
			unset($_SESSION['infoAgregado']);
			unset($_SESSION['pruebas']);
		}			
	} // FIN guardarPruebasAgregados();


	//Funcion que se encarga de guardar toda la información
	function guardarDetalleAgregado(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_laboratorio");
		//obtener el idPruebaAgregados
		$idPruebaAgregados=	$_SESSION['infoAgregado']['idPruebaAgregados'];	
		//obtener el nombre de la prueba ejecutada para luego obrener su id
		$cmb_pruebaEjecutada=$_SESSION['infoAgregado']['cmb_pruebaEjecutada'];
		$idPruebaEjecutada=obtenerDato('bd_laboratorio', 'catalogo_pruebas', 'id_prueba','nombre ', $cmb_pruebaEjecutada);

		
		//Recorrer el arreglo que contiene los datos
		foreach($_SESSION['pruebas'] as $ind => $concepto){
			
			//Crear la Sentencia SQL para Alamcenar los resultados agregados 
			$stm_sql= "INSERT INTO detalle_prueba_agregados (pruebas_agregados_id_pruebas_agregados, numero, concepto, limite_superior,  limite_inferior,
			retenido, observaciones)
			VALUES ('$idPruebaAgregados', '$concepto[numero]','$concepto[concepto]','$concepto[limSuperior]','$concepto[limInferior]','$concepto[retenido]',
			'$concepto[observaciones]')";
			
			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs){
				//crear la sentencia
				$stm_sql="INSERT INTO pruebas_realizadas (prueba_calidad_id_prueba_calidad, pruebas_agregados_id_pruebas_agregados, 
						rendimiento_id_registro_rendimiento, catalogo_pruebas_id_prueba) 
						VALUES ('N/A', '$idPruebaAgregados', 0, '$idPruebaEjecutada')";
			
				//Ejecutar la Sentencia 
				$rs=mysql_query($stm_sql);			
				$band=1;
			}
			else{
				$band=0;
			}
		}// Fin foreach($_SESSION['materiales'] as $ind => $concepto)
		if($band==1){
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$idPruebaAgregados,"RegPruebaAgregado",$_SESSION['usr_reg']);
			$conn = conecta("bd_laboratorio");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";

		}		
	
		if($band!=1){
				$error = mysql_error();
				//echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				//liberar los datos del arreglo de sesion
				unset($_SESSION['nomAgregado']);
				unset($_SESSION['infoAgregado']);
				unset($_SESSION['pruebas']); 
		}		
	
	} // FIN guardarDetalleAgregado();

	//Esta función se encarga de generar el Id de  Prueba Agregados de acuerdo a los registros existentes en la BD
	function obtenerIdPruebaAgregados(){
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Definir las  letras en la Id de la Prueba
		$id_cadena = "PBA";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las Pruebas Calidad del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de Pruebas agregados
		$stm_sql = "SELECT COUNT(id_pruebas_agregados) AS cant FROM pruebas_agregados WHERE id_pruebas_agregados LIKE 'PBA$mes$anio%'";
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
	}//Fin de la Funcion obtenerIdPruebaCalidad()	
	
	
	//Funcion que se encarga de cambiar el estado de una alerta $idMezcla=id de la mezcla y $fechaR igual a la fecha de Ruptura 
	function evaluarAlerta($idMuestra,$fechaR){
		$res=0;
		//Creamos la sentencia sql para seleccionar las fechas programasd que sean menores de la fecha de ruptura
		$stm_sql = "SELECT fecha_programada FROM plan_pruebas WHERE estado='0' AND muestras_id_muestra = '$idMuestra' AND fecha_programada<='$fechaR'";		
		//Ejecutamos la consulta
		$rs=mysql_query($stm_sql);
		$band=mysql_num_rows($rs);
		//En caso de haber generado resultados actualizar el estado en el plan de pruebas
		if($datos=mysql_fetch_array($rs) &&$band>0){
			$sql_stm="UPDATE plan_pruebas SET estado = '1' WHERE estado = '0' AND muestras_id_muestra='$idMuestra' AND fecha_programada<='$fechaR'";
			//Ejecutar la sentencia de actualización de estado
			$rs2=mysql_query($sql_stm);
			if($rs2)
				$res=1;
		}		
		return $res;
	}		
		
	function adelantarPrueba($idMuestra,$fechaR){
		$res=0;
		$sql_stm="SELECT fecha_programada FROM plan_pruebas WHERE estado='0' AND muestras_id_muestra='$idMuestra' AND fecha_programada>'$fechaR'";
		$rs=mysql_query($sql_stm);
		$flag=mysql_num_rows($rs);
		if($flag>0){
			?>
				<script type="text/javascript" language="javascript">
					setTimeout("adelantarPrueba();",1000);
					
					function adelantarPrueba(){
						if (confirm("Esta Prueba no Cumple con la Programación. ¿Es una Prueba Adelantada?"))
							window.open('verPruebasProgramadas.php?idMuestra=<?php echo $idMuestra;?>&fechaR=<?php echo $fechaR;?>',
							'_blank','top=100, left=100, width=700, height=300, status=no, menubar=no, resizable=no, scrollbars=no, toolbar=no, location=no, directories=no');
					}
				</script>
			<?php
			$res=1;
		}
		return $res;
	}
?>
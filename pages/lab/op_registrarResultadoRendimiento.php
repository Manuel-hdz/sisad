<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 24s/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario frm_registrarResultadoRendimiento.php
	**/
	
	
	///****************************************************************************************************///
	///************************  FORMULARIO frm_registrarResultadoRendimiento  ***************************///
	///****************************************************************************************************///
		
	//Funcion que se encarga de desplegar las mezclas en el rango de fechas
	function mostrarMezclas(){

		//Conectar a la BD de laboratorio
		$conn = conecta("bd_laboratorio");
		
		//Si viene sbt_consultar la buqueda de las mezclas proviene de un rango de fechas
		if(isset($_POST["sbt_consultar"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM mezclas WHERE fecha_registro BETWEEN '$f1' AND '$f2' AND estado='1' ORDER BY id_mezcla";
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Mezclas en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";
			
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada
			echo "<input type='hidden' name='hdn_fechaIni' value='$_POST[txt_fechaIni]'/>";
			echo "<input type='hidden' name='hdn_fechaFin' value='$_POST[txt_fechaFin]'/>";
			echo "<input type='hidden' name='hdn_consultar' value='$_POST[sbt_consultar]'/>";
		}		
		//Si viene sbt_consultar2 la buqueda de la mezcla proviene el combo box
		else if(isset($_POST["sbt_consultar2"])){
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM mezclas WHERE id_mezcla = '$_POST[cmb_claveMezcla]' AND estado='1'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de la Mezcla <em><u> $_POST[cmb_claveMezcla]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla </label>";	
			
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada
			echo "<input type='hidden' name='hdn_idMezcla' value='<$_POST[cmb_claveMezcla]'/>";
			echo "<input type= 'hidden' name='hdn_consultar2' value='$_POST[sbt_consultar2]'/>";
		}

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='6' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>ID MEZCLA</td>
					<td class='nombres_columnas' align='center'>NOMBRE MEZCLA</td>
					<td class='nombres_columnas' align='center'>EXPEDIENTE</td>
					<td class='nombres_columnas' align='center'>EQUIPO MEZCLADO</td>
					<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>					
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb_idMezcla' value='$datos[id_mezcla]'/>
						<td class='$nom_clase'>$datos[id_mezcla]</td>
						<td class='$nom_clase'>$datos[nombre]</td>
						<td class='$nom_clase'>$datos[expediente]</td>
						<td class='$nom_clase'>$datos[equipo_mezclado]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_registro'],1)."</td>
						
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
	///**************************  FORMULARIO frm_registrarResultadosRendimiento2**************************///
	///****************************************************************************************************///
		
	//Funcion que permite guardar el registro del Rendimiento
	function guardarRegRendimiento(){
		//Archivo que permite modificar la fecha segun el formato requerido
		include_once("../../includes/func_fechas.php");
		
		//conectar a la bd
		$conn = conecta('bd_laboratorio');
		
		//Obtenemos el id del Rendimiento; el cual es generado por la funcion obtenerIdRendimiento();
		$idRend = obtenerIdRendimiento();
		if($idRend==0){
			$idRend=1;
		}
				
		//Obtenemos los datos de la sesion
		$idMezcla = $_SESSION['rendimiento']['idMezcla'];
		$localizacion = $_SESSION['rendimiento']['lugar'];
		$noMuestra = $_SESSION['rendimiento']['numMuestra'];
		$revenimiento = $_SESSION['rendimiento']['revenimiento'];
		$temperatura = $_SESSION['rendimiento']['temperatura'];
		$hora = $_SESSION['rendimiento']['hora'];
		$fecha = modFecha($_SESSION['rendimiento']['fecha'],3);
		$observaciones = $_SESSION['rendimiento']['observaciones'];
		$notas = $_SESSION['rendimiento']['notas'];
		
		//Crear la sentencia que permite guardar los registros en la tabla de rendimiento
		$stm_sql="INSERT INTO rendimiento (id_registro_rendimiento, mezclas_id_mezcla, num_muestra, localizacion, revenimiento, temperatura, hora, fecha_registro, 
					observaciones, comentarios) 
				 	VALUES ($idRend, '$idMezcla', '$noMuestra', '$localizacion', '$revenimiento', '$temperatura', '$hora', '$fecha', '$observaciones','$notas')";

		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		
		//Verificar Resultado y generar la siguiente consulta
		if($rs){
			//Obtenemos los datos del post y retiramos las comas para evitar errores en la inserción de los datos
			$pvolBruto = str_replace(",","",$_POST['txt_pvolBruto']);	
			$pvolMolde = str_replace(",","",$_POST['txt_pvolMolde']);	
			$pvolUnitario = str_replace(",","",$_POST['txt_pvolUnitario']);	
			$factorRec = str_replace(",","",$_POST['txt_factorRec']);	
			$pvolTeoricoRend = str_replace(",","",$_POST['txt_pvolTeoricoRend']);	
			$volRend = str_replace(",","",$_POST['txt_volRend']);	
			$pvolTeoricoAire = str_replace(",","",$_POST['txt_pvolTeoricoAire']);	
			$pvolAire = str_replace(",","",$_POST['txt_pvolAire']);	
			$cb = str_replace(",","",$_POST['txt_cb']);	
			$r = str_replace(",","",$_POST['txt_r']);	
			$caireReal = str_replace(",","",$_POST['txt_caireReal']);
			
			//Creamos la consulta para almacenar los registros
			$stm_sqlDetalle="INSERT INTO detalle_rendimiento (rendimiento_id_registro_rendimiento, pvol_bruto, pvol_molde, pvol_unit, factor_recipiente,
							pvol_teorico_rend, pvol_rend,pvol_teorico_caire,pvol_caire, cb, r, caire_real) 
							VALUES ($idRend, $pvolBruto, $pvolMolde, $pvolUnitario, $factorRec, $pvolTeoricoRend, $volRend, $pvolTeoricoAire,
							$pvolAire, $cb, $r, $caireReal)";			
				
			//Ejecutar la Sentencia SQL para guardar el detalle del Rendimiento de la mezcla
			$rsDetalles = mysql_query($stm_sqlDetalle);
			
			
			//Guardar las pruebas seleccionadas para el registro del Rendimiento
			if(isset($_SESSION["pruebasEjecutadas"])){
				foreach($_SESSION["pruebasEjecutadas"] as $ind => $idPrueba){
					//Crear la Sentencia SQL para almacenar los datos de las pruebas realziadas
					$stm_sql = "INSERT INTO pruebas_realizadas (prueba_calidad_id_prueba_calidad, pruebas_agregados_id_pruebas_agregados, 
								rendimiento_id_registro_rendimiento, catalogo_pruebas_id_prueba) 
								VALUES ('N/A', 'N/A', $idRend, $idPrueba)";
					//Ejecutar la Sentencia		
					mysql_query($stm_sql);
					
					echo mysql_error()."\n";							
				}
			}//Cierre if(isset($_SESSION["pruebasEjecutadas"]))
			
			
			//Guardar el Diseño de la Mezcla en el caso que haya sido modificado
			if($_POST['hdn_disenioMod']=="si"){
				foreach($_SESSION['datosDisenio'] as $ind => $datoMat){
					//Crear la Sentencia SQL para almacenar los datos del Diseño Modificado
					$sql_stm = "INSERT INTO cambios_disenio_mezcla (rendimiento_id_registro_rendimiento, mezclas_id_mezcla, catalogo_materiales_id_material, cantidad,
								unidad_medida, volumen) VALUES($idRend,'$idMezcla','$datoMat[idMat]',$datoMat[cantMat],'$datoMat[unidad]',1)";
					//Ejecutar la Sentencia para guardar los datos
					mysql_query($sql_stm);
					
					echo mysql_error()."\n";					
				}
			}
			else if($_POST['hdn_disenioMod']=="no"){
				//Verificar si existe el arreglo datosDisenio en la SESSION para quitarlo
				if(isset($_SESSION['datosDisenio']))
					unset($_SESSION['datosDisenio']);
			}
			
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$idMezcla,"RegRendimiento",$_SESSION['usr_reg']);
			$conn = conecta("bd_laboratorio");
			echo "<meta http-equiv='refresh' content='0 ;url=exito.php'>";		
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD
			mysql_close($conn);	
		}
		
		
	}//FIN function guardarRegRendimiento()
	
	
	//Esta funcion genera la Clave del de acuerdo a los registros en la BD
	function obtenerIdRendimiento(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_registro_rendimiento)+1 AS cant FROM rendimiento";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 
			if($datos['cant']>0 && $datos['cant']<10)
				$id_cadena .= "00".$datos['cant'];
			if($datos['cant']>9 && $datos['cant']<100)
				$id_cadena .= "0".$datos['cant'];
			if($datos['cant']>=100)
				$id_cadena .= $datos['cant'];
		}
				
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()	
?>
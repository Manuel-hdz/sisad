<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 20/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Eliminar Mezcla en la BD
	**/
	
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
			$sql_stm ="SELECT * FROM mezclas WHERE fecha_registro BETWEEN '$f1' AND '$f2' AND estado = '1' ORDER BY id_mezcla";
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Mezclas Registradas en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";
		}
		
		//Si viene sbt_consultar2 la buqueda de la mezcla proviene el combo box
		else if(isset($_POST["sbt_consultar2"])){
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM mezclas WHERE id_mezcla = '$_POST[cmb_claveMezcla]' AND estado='1'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de la Mezcla <em><u> $_POST[cmb_claveMezcla]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Mezcla </label>";										
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
					<td class='nombres_columnas' align='center' width='10%'>ELIMINAR</td>
					<td class='nombres_columnas' align='center' width='15%'>ID MEZCLA</td>
					<td class='nombres_columnas' align='center' width='25%'>NOMBRE MEZCLA</td>
					<td class='nombres_columnas' align='center' width='10%'>EXPEDIENTE</td>
					<td class='nombres_columnas' align='center' width='25%'>EQUIPO MEZCLADO</td>
					<td class='nombres_columnas' align='center' width='15%'>FECHA REGISTRO</td>					
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb' value='$datos[id_mezcla]' />
						</td>
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
	
	//Funcion que se encarga de eliminar la mezcla seleccionada
	function eliminarMezclaSeleccionada(){
		
		//Abrimos la conexion con la Base de datos
		$conn = conecta("bd_laboratorio");
		
		//Creamos la sentencia SQL para modificar el estado de la mezcla en lugar de borrarla de la bd
		$stm_sql="UPDATE mezclas SET estado='0' WHERE id_mezcla = '$_POST[rdb]'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);									
									
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_laboratorio",$_POST['rdb'],"EliminarMezcla",$_SESSION['usr_reg']);	
																					
			//Direccionar a la pantalla de exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error=mysql_error();
			//Cerrar la conexion con la BD de Laboratorio
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Fin de la funcion eliminarMezclaSeleccionada
	
?>
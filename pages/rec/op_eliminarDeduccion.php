<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 19/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Eliminar Deducción en la BD
	**/
	

	//Funcion que se encarga de desplegar las deducciones del empleado seleccionado
	function mostrarDeducciones(){

		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
	
		//Obtener nombre Recursos Humanos
		$nombre=obtenerNombreEmpleado($_POST['txt_RFCEmpleado']);
		
		if(isset($_POST["sbt_consultar"])){
			//Crear sentencia SQL
			$sql_stm = "SELECT empleados_rfc_empleado, id_deduccion, nom_deduccion, total FROM deducciones WHERE empleados_rfc_empleado= '$_POST[txt_RFCEmpleado]' AND id_deduccion NOT LIKE 'CLF%' AND estado='ACTIVO'";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Pr&eacute;stamos Registrados el Empleado <em><u> $nombre  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>El Empleado $nombre no Tiene Pr&eacute;stamos Registrados </label>";										
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
					<td class='nombres_columnas' align='center'>ELIMINAR</td>
					<td class='nombres_columnas' align='center'>NOMBRE DEL TRABAJADOR</td>
					<td class='nombres_columnas' align='center'>ID PR&Eacute;STAMO</td>
					<td class='nombres_columnas' align='center'>NOMBRE DEL PR&Eacute;STAMO</td>
					<td class='nombres_columnas' align='center'>CANTIDAD PRESTADA</td>
					<td class='nombres_columnas' align='center'>SALDO ACTUAL</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Consulta en la cual obtenemos el saldo actual de la deducción
				$stm_sql= "SELECT MIN(saldo_final) AS  saldo_final FROM detalle_abonos WHERE deducciones_id_deduccion = '$datos[id_deduccion]'";
				$rs2 = mysql_query($stm_sql);									
				$datos2=mysql_fetch_array($rs2);
			
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb_idDecuccion' value= $datos[id_deduccion] />
						</td>
						<td class='$nom_clase'>$nombre</td>
						<td class='$nom_clase'>$datos[id_deduccion]</td>
						<td class='$nom_clase'>$datos[nom_deduccion]</td>
						<td class='$nom_clase'>$".number_format($datos['total'],2,",",".")."</td>
						<td class='$nom_clase'>$".number_format($datos2['saldo_final'],2,",",".")."</td>
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
	
	//Funcion que se encarga de eliminar la deducción seleccionada
	function eliminarDeduccionSeleccionada(){
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		
		//Creamos la sentencia SQL para cambiar el estado al prestamo pasar de estado activo a cancelado cuando se borra
		$stm_sql="UPDATE deducciones SET estado='CANCELADO', justificacion='$_POST[txt_justificacion]' WHERE id_deduccion = '$_POST[rdb_idDecuccion]'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);									
									
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs){
			//Guardar el registro de movimientos
			registrarOperacion("bd_recursos",$_POST['rdb_idDecuccion'],"EliminaDeduccion",$_SESSION['usr_reg']);
			$conn = conecta("bd_recursos");
		}
		else{
			$error=mysql_error();
			//Cerrar la conexion con la BD de Recursos Humanos
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Fin de la funcion eliminarDeduccionSeleccionada
?>
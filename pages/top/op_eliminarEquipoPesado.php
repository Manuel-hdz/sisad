<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 15/Agosto/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Eliminar Registros de Equipos en la BD
	**/
	
	//Funcion que se encarga de desplegar los traspaleos según el criterio de busqueda utilizado
	function mostrarRegistros(){
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM bitacora_eq_pesado JOIN equipo_pesado ON id_registro=equipo_pesado_id_registro WHERE fam_equipo='$_POST[cmb_tipoObraEqP]' AND id_registro='$_POST[cmb_nomObraEq]'";
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro con Equipo Pesado de <em><u>$_POST[cmb_tipoObraEqP]</u></em> en la Obra Seleccionada";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>Registros de <em><u>$_POST[cmb_tipoObraEqP]</u></em> de la Obra <em><u>$datos[concepto]</u></em></td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>ELIMINAR</td>
					<td class='nombres_columnas' align='center'>TIPO EQUIPO</td>
					<td class='nombres_columnas' align='center'>NOMBRE OBRA EQUIPO</td>
					<td class='nombres_columnas' align='center'>QUINCENA</td>
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				$cantidad=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM detalle_eq_pesado WHERE bitacora_eq_pesado_idbitacora='$datos[idbitacora]'"));
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb_idRegistro' id='rdb_idRegistro' value='$datos[idbitacora]'></td>
						<td class='$nom_clase' align='center'>$datos[fam_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[concepto]</td>
						<td class='$nom_clase' align='center'>$datos[no_quincena]</td>
						<td class='$nom_clase' align='center'>$cantidad[total]</td>
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
			echo "<br><br><br><br><br><br><br><br><br><br><p align='center'>".$msg_error."</p>";
			return 0;		
		}
	}
	
	//Funcion que se encarga de eliminar el registro seleccionado
	function eliminarRegistro($idBitacora){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_topografia");
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query("DELETE FROM bitacora_eq_pesado WHERE idbitacora = '$idBitacora'");
		$rs2 = mysql_query("DELETE FROM detalle_eq_pesado WHERE bitacora_eq_pesado_idbitacora = '$idBitacora'");
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs && $rs2){
			//Guardar la operacion realizada
			registrarOperacion("bd_topografia",$idBitacora,"EliminarRegEquipoPesado",$_SESSION['usr_reg']);
			?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('¡Registro Borrado Exitosamente!')",1000);
				</script>
			<?php
		}
		else{
			$error=mysql_error();
			//Cerrar la conexion con la BD de Topografia
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		} 
	}//Fin de la funcion eliminarTraspaleoSeleccionado
?>
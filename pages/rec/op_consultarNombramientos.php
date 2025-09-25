<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 13/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de consultar nombramientos en la BD
	**/

	//Funcion que muestra los nombramientos registrados en la BD
	function mostrarNombramientos(){
		
		//Realizar la conexion a la BD de Recuros Humanos
		$conn = conecta("bd_recursos");
		
		//Realizar la consulta para obtener los nombramientos
		$stm_sql = "SELECT  id, empleados_rfc_empleado, area, puesto, fecha FROM nombramientos ORDER BY fecha; ";
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Nombramientos Registrados en la Base de Datos";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Nombramiento Registrado en la Base de Datos </label>";										

		//Ejecutar la consulta y dibujar la tabla 
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "					
			<table cellpadding='5' id='consultarNombramientos' width='100%'>
				<caption align='center' class='titulo_etiqueta'>$msg</caption>
				<thead>
				<tr>
					<th class='nombres_columnas' align='center'>NOMBRE</th>						
					<th class='nombres_columnas' align='center'>&Aacute;REA</th>
					<th class='nombres_columnas' align='center'>PUESTO</th>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>NOMBRAMIENTO</td>				
					</tr></thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{
				//Obtener nombre Recursos Humanos
				$nombre=obtenerNombreEmpleado($datos['empleados_rfc_empleado']);

				echo "<tr>		
						<td class='nombres_filas'>$nombre</td>	
						<td class='$nom_clase'>$datos[area]</td>	
						<td class='$nom_clase'>$datos[puesto]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha'],2)."</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_nombramiento" class="botones" value="Nombramiento" onMouseOver="window.estatus='';return true" 
                            title="Ver Nombramiento del Empleado <?php echo $datos['id'];?>" onclick="window.open('../../includes/generadorPDF/nombramiento.php?id=<?php echo $datos['id']; ?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>							
						</td>	
					</tr><?php
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody></table><br><br><br>";
		}
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
		}

		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
	?>
<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 20/Abril/2011
	  * Descripción: Este archivo contiene funciones para consultar la información relacionada con el formulario de consultar deducciones en la BD
	**/

	//Funcion que se encarga de desplegar las deducciones en el rango de fechas
	function mostrarDeducciones(){

		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
	
		if(isset($_POST["sbt_consultarFecha"])){?>	
			<input type="hidden" name="hdn_tipo" value="fechas" />					
			<input type="hidden" name="sbt_consultarFecha" value="" />
			<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni']; ?>" />
			<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin']; ?>" /><?php

			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM deducciones WHERE fecha_alta >='$f1' AND fecha_alta<='$f2' AND id_deduccion NOT LIKE 'CLF%' ORDER BY fecha_alta";	
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Deducciones Registradas en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;na Deducci&oacute;n en las Fechas del 
			<em><u>$_POST[txt_fechaIni]	</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}

		else if(isset($_POST["sbt_consultarTodo"])){?>
			<input type="hidden" name="hdn_tipo" value="todas" />
			<input type="hidden" name="sbt_consultarTodo" value="" /><?php
		
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM deducciones WHERE id_deduccion NOT LIKE 'CLF%' ORDER BY fecha_alta";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Deducciones Registradas en la Base de Datos";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;na Deducci&oacute;n Registrada en la Base de Datos</label>";										
		}

		else if(isset($_POST["sbt_consultarNombre"])){?>
			<input type="hidden" name="hdn_tipo" value="empleado" />
			<input type="hidden" name="sbt_consultarNombre" value="" />
			<input type="hidden" name="cmb_area" value="<?php echo $_POST['cmb_area']; ?>" />
			<input type="hidden" name="txt_RFCEmpleado" value="<?php echo $_POST['txt_RFCEmpleado']; ?>" /><?php
			//Obtener nombre Recursos Humanos
			$nombre=obtenerNombreEmpleado($_POST['txt_RFCEmpleado']);

			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM deducciones WHERE empleados_rfc_empleado= '$_POST[txt_RFCEmpleado]' AND id_deduccion NOT LIKE 'CLF%' ORDER BY fecha_alta";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Deducciones Registradas del Empleado <em><u> $nombre  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>El Empleado $nombre no Tiene Deducciones Registradas </label>";										
		}
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if(  $datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>

					<td class='nombres_columnas' align='center'>VER DETALLE</td>
					<td class='nombres_columnas' align='center'>NOMBRE DEL TRABAJADOR</td>
					<td class='nombres_columnas' align='center'>ID DEDUCCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>NOMBRE DE LA DEDUCCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>TOTAL A PAGAR</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>ESTADO</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
	
			do{	
				if(isset($_POST["sbt_consultarFecha"])){
				//Obtener nombre Recursos Humanos
				$nombre=obtenerNombreEmpleado($datos['empleados_rfc_empleado']);
				}
				else if(isset($_POST["sbt_consultarTodo"])){
					//Obtener nombre Recursos Humanos
					$nombre=obtenerNombreEmpleado($datos['empleados_rfc_empleado']);
				}

				$total= $datos['total'];
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas'>
							<input type='checkbox' name='ckb_idDeduccion' value='$datos[id_deduccion]' onclick='document.frm_verDetalleDeduccion.submit();'>
						</td>					
						<td class='$nom_clase'>$nombre</td>
						<td class='$nom_clase'>$datos[id_deduccion]</td>
						<td class='$nom_clase'>$datos[nom_deduccion]</td>
						<td class='$nom_clase'>$".number_format($datos['total'],2,".",",")."</td>
						<td class='$nom_clase'>$datos[descripcion]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_alta'],1)."</td>
						<td class='$nom_clase'>$datos[estado]</td>
					</tr>";
				$cant_total += $datos['total'];				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "					
					<tr>
						<td>&nbsp;</td><td></td><td></td>
						<td class='nombres_columnas'>TOTAL DEDUCCIONES</td>
						<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>
					</tr>
				</table>";
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;
		}
	}
	
	//Funcion que se encarga de mostrar el detalle de la deduccion Seleccionada
	function verDetalleDeduccion(){		
		//Obtener el Id de la deduccion Seleccionada
		$idDeduccion = $_POST['ckb_idDeduccion'];	
		
		//Obtener el RFC asociado al Id de la deduccion
		$rfcEmpleado = obtenerDato("bd_recursos", "deducciones", "empleados_rfc_empleado", "id_deduccion", $idDeduccion);
		
		//Obtener nombre completo del Empleado		
		$nombre = obtenerNombreEmpleado($rfcEmpleado);		
		
		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
										
		//Crear sentencia SQL
		$sql_stm = "SELECT * FROM detalle_abonos WHERE deducciones_id_deduccion = '$idDeduccion' ORDER BY fecha_abono";
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Detalle de la Deducci&oacute;n Asignado al Empleado <em><u>$nombre</u></em>";				
				
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito y desplegar los datos.
		if($datos=mysql_fetch_array($rs)){			
			//Desplegar los resultados de las deducciones a Empleado encontrados
			echo "				
			<table cellpadding='5' width='80%'>				
				<tr>
					<td colspan='5' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>			
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>SALDO INICIAL</td>
					<td class='nombres_columnas' align='center'>ABONO</td>
					<td class='nombres_columnas' align='center'>SALDO FINAL</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;
			$suma_abonos = 0;
	
			do{					
				//Mostrar cada Prestamo encontrado con los parametros seleccionados por el Usuario
				echo "
					<tr>						
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_abono'],1)."</td>
						<td class='$nom_clase'>$".number_format($datos['saldo_inicial'],2,",",".")."</td>
						<td class='$nom_clase'>$".number_format($datos['abono'],2,",",".")."</td>
						<td class='$nom_clase'>$".number_format($datos['saldo_final'],2,",",".")."</td>
					</tr>";
				
				$suma_abonos += $datos['abono'];				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "					
					<tr>
						<td colspan='2'>&nbsp;</td>
						<td class='nombres_columnas'>TOTAL ABONADO</td>
						<td class='nombres_columnas'>$".number_format($suma_abonos,2,".",",")."</td>
					</tr>
				</table>";
		}//Fin if($datos=mysql_fetch_array($rs))		
	}
	
?>
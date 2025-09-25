<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 27/Abril/2011
	  * Descripción: Este archivo contiene funciones para consultar los datos de los prestamos otorgados en la Empresa
	**/

	
	//Funcion que se encarga de desplegar los Prestamos de acuerdo a los parametros seleccionados por el Usuario
	function mostrarPrestamos(){
		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
	
		//Mostrar los Prestamos por Rango de Fechas seleccionadas 
		if(isset($_POST["sbt_consultarFecha"])){
			//Repostear las variables para realizar nuevamente esta consulta cuando se venga de la pagina de ver detalle de un prestamo seleccionado?>
			<input type="hidden" name="hdn_tipoReporte" value="fechas" />
			<input type="hidden" name="sbt_consultarFecha" value="" />
			<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni']; ?>" />
			<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin']; ?>" /><?php
			
						
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$fechaIni = modFecha($_POST['txt_fechaIni'],3);
			$fechaFin = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM deducciones WHERE fecha_alta>='$fechaIni' AND fecha_alta<='$fechaFin' AND id_deduccion LIKE 'PRE%' ORDER BY id_deduccion";	
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Prestamos Registrados en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Bono en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}
		//Mostrar los prestamos por Area de Empleados Seleccionada
		else if(isset($_POST["sbt_consultarArea"])){		
			//Repostear las variables para realizar nuevamente esta consulta cuando se venga de la pagina de ver detalle de un prestamo seleccionado?>
			<input type="hidden" name="hdn_tipoReporte" value="area" />
			<input type="hidden" name="sbt_consultarArea" value="" />
			<input type="hidden" name="cmb_area" value="<?php echo $_POST['cmb_area']; ?>" /><?php
			
			
			//Crear sentencia SQL
			$sql_stm = "SELECT deducciones.* FROM deducciones JOIN empleados ON empleados_rfc_empleado = rfc_empleado WHERE area= '$_POST[cmb_area]' ORDER BY id_deduccion";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Prestamos Registrados en el &Aacute;rea de <em><u>$_POST[cmb_area]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Prestamo Registrado en el &Aacute;rea de <em><u>$_POST[cmb_area]
			</u></em></label>";										
		}
		//Mostrar los prestamos por Empleado Seleccionado 
		else if(isset($_POST["sbt_consultarNombre"])){
			//Repostear las variables para realizar nuevamente esta consulta cuando se venga de la pagina de ver detalle de un prestamo seleccionado?>
			<input type="hidden" name="hdn_tipoReporte" value="empleado" />
			<input type="hidden" name="sbt_consultarNombre" value="" />
			<input type="hidden" name="txt_RFCEmpleado" value="<?php echo $_POST['txt_RFCEmpleado']; ?>" /><?php
			
			//Obtener nombre completo del empleado seleccionado
			$nombre = obtenerNombreEmpleado($_POST['txt_RFCEmpleado']);

			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM deducciones WHERE empleados_rfc_empleado = '$_POST[txt_RFCEmpleado]' ORDER BY id_deduccion";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Prestamos Registrados para el Empleado <em><u>$nombre</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>El Empleado $nombre no Tiene Prestamos Registrados</label>";										
		}
		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito y desplegar los datos.
		if($datos=mysql_fetch_array($rs)){			
			//Desplegar los resultados de los prestamos a Empleado encontrados
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='9' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>VER DETALLE</td>
					<td class='nombres_columnas' align='center'>NOMBRE DEL TRABAJADOR</td>
					<td class='nombres_columnas' align='center'>ID PRESTAMO</td>
					<td class='nombres_columnas' align='center'>NOMBRE DEL PRESTAMO</td>
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
					<td class='nombres_columnas' align='center'>AUTORIZ&Oacute;</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>ESTADO DEL PRESTAMO</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
	
			do{	
				if(isset($_POST["sbt_consultarFecha"])){
					//Obtener nombre completo del Empleado
					$nombre = obtenerNombreEmpleado($datos['empleados_rfc_empleado']);
				}
				else if(isset($_POST["sbt_consultarArea"])){
					//Obtener nombre completo del Empleado
					$nombre = obtenerNombreEmpleado($datos['empleados_rfc_empleado']);
				}
				//Mostrar cada Prestamo encontrado con los parametros seleccionados por el Usuario
				echo "
					<tr>
						<td class='nombres_filas'>
							<input type='checkbox' name='ckb_idPrestamo' value='$datos[id_deduccion]' onclick='document.frm_verDetallePrestamo.submit();'>
						</td>
						<td class='$nom_clase'>$nombre</td>
						<td class='$nom_clase'>$datos[id_deduccion]</td>
						<td class='$nom_clase'>$datos[nom_deduccion]</td>
						<td class='$nom_clase'>$".number_format($datos['total'],2,",",".")."</td>
						<td class='$nom_clase'>$datos[autorizo]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_alta'],1)."</td>
						<td class='$nom_clase'>$datos[descripcion]</td>
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
						<td colspan='3'>&nbsp;</td>
						<td class='nombres_columnas'>TOTAL</td>
						<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>
					</tr>
				</table>";
			
		}//Fin if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
		}
	}
		
	
	//Funcion que se encarga de mostrar el detalle del Prestamo Seleccionado
	function verDetallePrestamo(){		
		//Obtener el Id del Prestamo Seleccionado
		$idPrestamo = $_POST['ckb_idPrestamo'];	
		
		//Obtener el RFC asociado al Id del Prestamo
		$rfcEmpleado = obtenerDato("bd_recursos", "deducciones", "empleados_rfc_empleado", "id_deduccion", $idPrestamo);
		
		//Obtener nombre completo del Empleado		
		$nombre = obtenerNombreEmpleado($rfcEmpleado);		
		
		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
										
		//Crear sentencia SQL
		$sql_stm = "SELECT * FROM detalle_abonos WHERE deducciones_id_deduccion = '$idPrestamo' ORDER BY fecha_abono";
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Detalle del Prestamo Asignado al Empleado <em><u>$nombre</u></em><br>No. Prestamo <em><u>$idPrestamo</u></em>";
				
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito y desplegar los datos.
		if($datos=mysql_fetch_array($rs)){			
			//Desplegar los resultados de los prestamos a Empleado encontrados
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
				
			
			//Retornar 1 para indicar que si existen datos para exportar
			return 1;
			
		}//Fin if($datos=mysql_fetch_array($rs))
		else{
			//Emitir un mensaje para indicar que no hay movimientos registrados
			echo "<label class='msje_correcto' align='center'>No hay Abonos Regitrados para <em><u>$nombre</u></em><br>No. Prestamo <em><u>$idPrestamo</u></em></label>";
			
			//Retornar 0 para indicar que si existen datos para exportar
			return 0;			
		}
				
		
	}//Cierre function verDetallePrestamo()
	
	
?>
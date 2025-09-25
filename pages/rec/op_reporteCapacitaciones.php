<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 19/Abril/2011
	  * Descripción: Permite generar reportes de Capacitaciones de los empleados 
	**/
	
	//Función que permite mostrar el reporte de Capacitaciones
	function reporteCapacitaciones(){		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Variable para saber el tipo de consulta
		$origenConsulta= "";
		
		//Variable para verificar si la consulta genero datos
		$flag=0;
		
		if(isset($_POST["txt_fechaIni"]) && isset($_POST["txt_fechaFin"])){
			//Tomamos las fechas del post y las convertimos a formato necesario para la consulta		
			$fechaIni=modFecha($_POST["txt_fechaIni"],3);
			$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		}
				
		//Verificamos si viene definido el combo area; esto para ver cual sera la consulta a ejecutar
		if(isset($_POST["cmb_area"])){
			//VAriable para saber el tipo de consulta
			$origenConsulta = "areas";
			
			//Tomamos el area del post
			$area=$_POST["cmb_area"];
			
			//Crear la consulta
			$stm_sql = "SELECT empleados_rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, nom_capacitacion, hrs_capacitacion,
						descripcion, fecha_inicio, fecha_fin, instructor FROM ((capacitaciones JOIN empleados_reciben_capacitaciones ON
						id_capacitacion=capacitaciones_id_capacitacion) JOIN empleados ON rfc_empleado=empleados_rfc_empleado) 
						WHERE fecha_inicio>='$fechaIni' AND fecha_inicio<='$fechaFin' AND area='$area' ORDER BY area";	
						
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "Reporte de Capacitaciones &Aacute;REA <em><u>$area</u></em> de: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados  del &Aacute;REA <em><u>$area</u></em> De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";			
		}
		else if(isset($_POST["txt_fechaIni"]) && isset($_POST["txt_fechaFin"])&& !isset($_POST["cmb_capacitaciones"])) {
			//Variable para saber el tipo de consulta
			$origenConsulta = "fechas";
			//Crear la consulta 
			$stm_sql = "SELECT empleados_rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, nom_capacitacion, hrs_capacitacion, 
						descripcion, fecha_inicio, fecha_fin, instructor FROM ((capacitaciones JOIN empleados_reciben_capacitaciones 
						ON id_capacitacion=capacitaciones_id_capacitacion) JOIN empleados ON rfc_empleado=empleados_rfc_empleado)
						WHERE fecha_inicio>='$fechaIni' AND fecha_inicio<='$fechaFin' ORDER BY fecha_inicio,nom_capacitacion";
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "Reporte de Capacitaciones de: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados  De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";		
		}
		//Seleccionamos el tipo de consulta mediante lo que viene en el post	
		if(isset($_POST["cmb_capacitaciones"])){
			//Tomamos la capacitacion del post
			$capacitaciones = $_POST["cmb_capacitaciones"];
			//Crear la consulta
			$stm_sql = " SELECT empleados_rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, nom_capacitacion, hrs_capacitacion, 
						descripcion, fecha_inicio, fecha_fin, instructor FROM ((capacitaciones JOIN empleados_reciben_capacitaciones 
						ON id_capacitacion=capacitaciones_id_capacitacion) JOIN empleados ON rfc_empleado=empleados_rfc_empleado)
						WHERE id_capacitacion='$capacitaciones' ORDER BY id_capacitacion,area";	
			$nomCapacitacion=obtenerDato("bd_recursos","capacitaciones","nom_capacitacion","id_capacitacion",$capacitaciones);
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "Reporte de la Capacitaci&oacute;n <em><u>$nomCapacitacion</u></em>";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados de la Capacitaci&oacute;n <em><u>$capacitaciones</u></em>";			
		}
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
		
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "								
				<table align='center'  class='tabla-frm' cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>					
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>ASISTENTE</td>
						<td align='center' class='nombres_columnas'>NOMBRE CAPACITACI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>HORAS CAPACITACI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>FECHA INICIO</td>
						<td align='center' class='nombres_columnas'>FECHA FIN</td>
						<td align='center' class='nombres_columnas'>INSTRUCTOR</td>
					</tr>";
										
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				//Ejecutamos la consulta para obtener el numero la asistencia del empleado
				echo "	
					<tr>
						<td align='center' class='$nom_clase'>$cont</td>		
						<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[nom_capacitacion]</td>
						<td align='center' class='$nom_clase'>$datos[hrs_capacitacion]</td>
						<td align='center' class='$nom_clase'>$datos[descripcion]</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_inicio'],1)."</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_fin'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[instructor]</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
						
			}while($datos=mysql_fetch_array($rs));
			echo "	
			</table>";
		
		}//Cierre if($datos = mysql_fetch_array($rs))
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $msg_error;?>			
		</div>
		<div id="btns-regpdf" align="center">
		<table width="17%" cellpadding="12">
			<tr>
				<td width="28%" align="center">
				  	<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reporte Capacitaciones" 
                  	onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_reporteCapacitaciones.php'" />
			  </td><?php 
				if($flag==1){
					//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
					<td width="72%" align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<?php if(isset($_POST["cmb_area"])){?>
								<input name="hdn_nomReporte" type="hidden" 
								value="Reporte_Capacitaciones_<?php echo $area;?>_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
							<?php }else{ ?>
								<input name="hdn_nomReporte" type="hidden" 
								value="Reporte_Capacitaciones_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
							<?php } ?>
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input name="hdn_origen" type="hidden" value="reporteCapacitaciones" />	
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
							title="Exportar a Excel los Datos de la Consulta Realizada" 
							onMouseOver="window.estatus='';return true"  />
						</form>
			  </td><?php 
				}?>
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion 
?>
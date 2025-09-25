<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 19/Abril/2011
	  * Descripción: Permite generar reportes de Pago de Seguro Social de los empleados 
	**/
	
	//Función que permite mostrar el reporte de  PSS
	function reporteSeguroSocial(){		
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Variable para verificar si la consulta genero datos
		$flag=0;
		
		//Tomamos las variables del post	
		$anio = $_POST["cmb_anio"];
		$mes = $_POST["cmb_mes"];	
		$semana = $_POST["cmb_semana"];

		//Crear la consulta
		$stm_sql = "SELECT rfc_trabajador, nombre_trabajador, anio_insercion, mes, semana, retencion_imss, neto_pagar FROM nomina_bancaria WHERE anio_insercion='$anio' AND mes='$mes' AND semana='$semana' ";  
	
		//Declaramos el titulo para mostrar en el encabezado
		$msg_titulo="Monto del Pago del Seguro Social Semana <u><em>".$_POST["cmb_semana"]."</u></em> Mes <u><em>".$_POST["cmb_mes"]."</u></em> A&ntilde;o <u><em>".$_POST["cmb_anio"]."</u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados de la Semana: 
			<u><em>".$_POST["cmb_semana"]."</u></em> Mes <u><em>".$_POST["cmb_mes"]."</u>	</em> A&ntilde;o <u><em>".$_POST["cmb_anio"]."</u></em>";
			
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
		
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "								
				<table align='center' class='tabla_frm' cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>					
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE TRABAJADOR</td>
						<td align='center' class='nombres_columnas'>SEMANA</td>
						<td align='center' class='nombres_columnas'>MES</td>
						<td align='center' class='nombres_columnas'>A&Ntilde;O INSERCI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>RETENCI&Oacute;N IMSS</td>
						<td align='center' class='nombres_columnas'>NETO A PAGAR</td>								
					</tr>";
										
			$nom_clase = "renglon_gris";
			$cont = 1;
			$total_imss = 0;
			$total_neto = 0;
			do{	
				//Ejecutamos la consulta para obtener el numero la asistencia del empleado
				echo "	
					<tr>
						<td align='center' class='$nom_clase'>$cont</td>		
						<td align='center' class='$nom_clase'>$datos[rfc_trabajador]</td>
						<td align='center' class='$nom_clase'>$datos[nombre_trabajador]</td>
						<td align='center' class='$nom_clase'>$datos[semana]</td>
						<td align='center' class='$nom_clase'>$datos[mes]</td>
						<td align='center' class='$nom_clase'>$datos[anio_insercion]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["retencion_imss"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["neto_pagar"],2,".",",")."</td>
					</tr>";
				//Sumar el monto de la nomina para obtener el total
				$total_imss += $datos['retencion_imss'];
				$total_neto += $datos['neto_pagar'];
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
						
			}while($datos=mysql_fetch_array($rs));
			echo "
				<tr>
					<td colspan='6'>&nbsp;</td>
					<td align='center' class='nombres_columnas' height='40' width='20' >Total IMSS $".number_format($total_imss,2,".",",")."</td>
					<td align='center' class='nombres_columnas' height='40' width='20'>Neto a Pagar $".number_format($total_neto,2,".",",")."</td>
				</tr>

			</table>";
		
		}//Cierre if($datos = mysql_fetch_array($rs))
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $msg_error;?>			
		</div>
		<div id="btns-regpdf" align="center">
		<table width="35%">
			<tr>
				<td width="50%" align="center">
				  	<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reporte Pago Seguro Social" 
                  	onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_reportePagoSeguroSocial.php'" />
				</td>
					<?php 
					if($flag==1){
						//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
						<td width="50%" align="center">
							<form action="guardar_reporte.php" method="post">
								<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
								<input name="hdn_nomReporte" type="hidden" 
								value="Reporte_PagoSS_<?php echo $_POST["cmb_anio"];?> A <?php echo $_POST["cmb_mes"];?> <?php echo $_POST["cmb_semana"]  ?>" />
								<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
								<input name="hdn_origen" type="hidden" value="reportePagoSS" />	
								<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
								title="Exportar a Excel los Datos de la Consulta Realizada" 
								onMouseOver="window.estatus='';return true"  />
							</form>
						</td>
					<?php 
					}?>
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion 
?>
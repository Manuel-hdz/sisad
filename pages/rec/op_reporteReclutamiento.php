<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 19/Abril/2011
	  * Descripción: Permite generar reportes de Reclutamiento de los aspirantes a empleo 
	**/
	
	//Función que permite mostrar el reporte de Reclutamiento
	function reporteReclutamiento(){		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Variable para verificar si la consulta genero datos
		$flag=0;
		

		//Tomamos las fechas del post y las convertimos a formato necesario para la consulta		
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Variable para saber el tipo de consulta
		$origenConsulta= "";
		
		//Verificamos si viene definido el combo area; esto para ver cual sera la consulta a ejecutar
		if(isset($_POST["cmb_area"])){
		
			//VAriable para saber el tipo de consulta
			$origenConsulta = "areas";
			
			//Tomamos el area del post
			$area=$_POST["cmb_area"];
			
			
			//Consulta General para el reporte en formato excel
			$stm_sql2 = "SELECT DISTINCT folio_aspirante,CONCAT(nombre,' ', ap_paterno,' ', ap_materno) AS nombre, puesto, estado_civil, 	
						telefono, edad, experiencia_laboral FROM(bolsa_trabajo JOIN area_puesto ON folio_aspirante=bolsa_trabajo_folio_aspirante)
						WHERE fecha_solicitud>='$fechaIni' AND fecha_solicitud<='$fechaFin' AND area='$area' ORDER BY area";
						
		
			//Crear la consulta
			$stm_sql = "SELECT DISTINCT folio_aspirante,CONCAT(nombre,' ', ap_paterno,' ', ap_materno) AS nombre, estado_civil, 	
						telefono, edad, experiencia_laboral FROM(bolsa_trabajo JOIN area_puesto ON folio_aspirante=bolsa_trabajo_folio_aspirante)
						WHERE fecha_solicitud>='$fechaIni' AND fecha_solicitud<='$fechaFin' AND area='$area' ORDER BY area";
			
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "Reporte de Reclutamiento &Aacute;REA <em><u>$area</u></em> De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados  del &Aacute;REA <em><u>$area</u></em> De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";			
		}
		else{
		
		//Consulta General para el reporte en formato excel
			$stm_sql2 = "SELECT DISTINCT folio_aspirante,CONCAT(nombre,' ', ap_paterno,' ', ap_materno) AS nombre, puesto, estado_civil, 	
						telefono, edad, experiencia_laboral FROM(bolsa_trabajo JOIN area_puesto ON folio_aspirante=bolsa_trabajo_folio_aspirante)
						WHERE fecha_solicitud>='$fechaIni' AND fecha_solicitud<='$fechaFin'";
						
			//Variable para saber el tipo de consulta
			$origenConsulta = "fechas";
			
			//Crear la consulta 
			$stm_sql = "SELECT DISTINCT folio_aspirante,CONCAT(nombre,' ', ap_paterno,' ', ap_materno) AS nombre, estado_civil, 	
						telefono, edad, experiencia_laboral FROM(bolsa_trabajo JOIN area_puesto ON folio_aspirante=bolsa_trabajo_folio_aspirante)
						WHERE fecha_solicitud>='$fechaIni' AND fecha_solicitud<='$fechaFin' ORDER BY area";
						
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "Reporte de Reclutamiento De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados  De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";		
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
						<td align='center' class='nombres_columnas'>FOLIO ASPIRANTE</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>ESTADO CIVIL</td>
						<td align='center' class='nombres_columnas'>TEL&Eacute;FONO</td>
						<td align='center' class='nombres_columnas'>EDAD</td>
						<td align='center' class='nombres_columnas'>EXPERIENCIA LABORAL</td>
						<td align='center' class='nombres_columnas'> VER PUESTOS</td>
					</tr>";
										
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				echo "	
					<tr>
						<td align='center' class='$nom_clase'>$cont</td>		
						<td align='center' class='$nom_clase'>$datos[folio_aspirante]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[estado_civil]</td>
						<td align='center' class='$nom_clase'>$datos[telefono]</td>
						<td align='center' class='$nom_clase'>$datos[edad]</td>
						<td align='center' class='$nom_clase'>$datos[experiencia_laboral]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verPuesto" class="botones" value="Ver Puestos" onMouseOver="window.estatus='';return true" 
							title="Ver Puesto del Aspirante <?php echo $datos['folio_aspirante'];?>" 
							onClick="javascript:window.open('verPuestosAspirante.php?id_aspirante=<?php echo $datos['folio_aspirante'];?>&fechaIni=<?php echo $fechaIni;?>&fechaFin=<?php echo $fechaFin;?>',
							'_blank','top=50, left=50, width=400, height=350, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
			<?php echo "</tr>";
										
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
				<td width="30%" align="center">
		  	  		<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reporte Reclutamiento" 
                  	onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_reporteReclutamiento.php'" />				</td><?php 
				if($flag==1){
					//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
					<td width="70%" align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql2; ?>" />
							<?php if(isset($_POST["cmb_area"])){?>
								<input name="hdn_nomReporte" type="hidden" 
								value="Reporte_Reclutamiento_<?php echo $area;?>_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
							<?php }else{ ?>
								<input name="hdn_nomReporte" type="hidden" 
								value="Reporte_Reclutamiento_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
							<?php } ?>
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input name="hdn_origen" type="hidden" value="reporteReclutamiento" />	
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
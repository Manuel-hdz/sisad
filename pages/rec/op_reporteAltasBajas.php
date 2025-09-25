<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 19/Abril/2011
	  * Descripción: Permite generar reportes de Altas y Bajas de los empleados 
	**/
	
	//Función que permite mostrar el reporte de Altas
	function reporteAltas(){		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Variable para verificar si la consulta genero datos
		$flag=0;
				
		//Tomamos las fechas del post y las convertimos a formato necesario para la consulta		
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Crear la consulta
		$stm_sql = "SELECT rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, fecha_ingreso, area, puesto, observaciones FROM empleados 
					WHERE fecha_ingreso>='$fechaIni'  AND fecha_ingreso<='$fechaFin' ORDER BY area";
		
		//Creamos el Msj para la gráfica
			$msg_grafica= "Grafica de Altas vs Bajas de ".modFecha($fechaIni,2)." a ".modFecha($fechaFin,2)."";	
					
		//Mensaje para desplegar en el titulo de la tabla
		$msg_titulo = " De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron Altas de Empleados De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em><label>";			
			
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
		
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "								
				<table align='center' class='tabla_frm' cellpadding='5'>
					<caption class='titulo_etiqueta'>Reporte Altas de personal $msg_titulo</caption>					
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>FECHA INGRESO</td>
						<td align='center' class='nombres_columnas'>&Aacute;REA</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>
						<td align='center' class='nombres_columnas'>OBSERVACIONES</td>
					</tr>";
										
			$nom_clase = "renglon_gris";
			$cont = 1;
						
			$arrAreas = array();			
			//Declarar la Primera Area como indice del Arreglo y colocarle el valor de 0
			$arrAreas[$datos['area']] = 0;
			$areaActual = $datos['area'];
			
			do{				
				echo "	
					<tr>
						<td align='center' class='$nom_clase'>$cont</td>		
						<td align='center' class='$nom_clase'>$datos[rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_ingreso'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[area]</td>
						<td align='center' class='$nom_clase'>$datos[puesto]</td>
						<td align='center' class='$nom_clase'>$datos[observaciones]</td>
					</tr>";
				//Acumular la cantidad de Altas por Area								
				if($areaActual==$datos["area"]){					
					$arrAreas[$areaActual] += 1; 
				}
				else{				
					$arrAreas[$datos["area"]] = 0; 	
					$areaActual = $datos["area"];	
					$arrAreas[$areaActual] += 1;
				}
			
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
		else{//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $msg_error;
		}	?>						
		</div>
		<div id="btns-regpdf" align="center">
		<table width="22%" cellpadding="12" class="tabla_frm">
			<tr>
				<td width="18%" align="center">
				  	<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reporte Altas VS Bajas" 
                  	onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_reporteAltasBajas.php'" />
			  </td><?php 
				if($flag==1){				
					$stm_sqlBajas="SELECT empleados_rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, fecha_baja,
								  area, puesto, observaciones FROM bajas_modificaciones WHERE  fecha_baja>='$fechaIni' AND fecha_baja<='$fechaFin' AND fecha_baja!='0000-00-00' AND fecha_mod_puesto='0000-00-00'";
					//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
					<td width="69%" align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_consultaBajas" type="hidden" value="<?php echo $stm_sqlBajas; ?>" />
							<input name="hdn_nomReporte" type="hidden" 
							value="Reporte_AltasBajas_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input name="hdn_origen" type="hidden" value="reporteAltasBajas" />	
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
							title="Exportar a Excel los Datos de la Consulta Realizada" 
							onMouseOver="window.estatus='';return true"  />
						</form>
			  </td><?php 
				}
				if($flag==1){ ?>
					<td width="13%" align="center"><?php 						
						$datosGrapAltas = array("hdn_msg"=>$msg_grafica,"arrAreas"=>$arrAreas);
						$_SESSION['datosGrapAltas'] = $datosGrapAltas;?>					
			  </td>
		<?php } ?>				
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion 
	
	
	//Función que permite mostrar el reporte de Bajas del personal
	function reporteBajas(){		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Variable para verificar si la consulta genero datos
		$flag=0;
		
		
		//Tomamos las fechas del post y las convertimos a formato necesario para la consulta		
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Crear la consulta
		$stm_sql = "SELECT empleados_rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, fecha_baja, area, puesto, observaciones FROM bajas_modificaciones
					WHERE  fecha_baja>='$fechaIni' AND fecha_baja<='$fechaFin' AND fecha_baja!='0000-00-00' AND fecha_mod_puesto='0000-00-00' ORDER BY area";
					
		//Mensaje para desplegar en el titulo de la tabla
		$msg_titulo = "De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron Bajas de Empleados De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em></label>";			
			
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
		
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "								
				<table align='center'  class='tabla_frm' cellpadding='5'>
					<caption class='titulo_etiqueta'>Reporte Bajas de personal $msg_titulo</caption>					
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>FECHA BAJA</td>
						<td align='center' class='nombres_columnas'>&Aacute;REA</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>
						<td align='center' class='nombres_columnas'>OBSERVACIONES</td>
					</tr>";
										
			$nom_clase = "renglon_gris";
			$cont = 1;
			
			$arrAreas = array();			
			//Declarar la Primera Area como indice del Arreglo y colocarle el valor de 0
			$arrAreas[$datos['area']] = 0;
			$areaActual = $datos['area'];
			do{	
				echo "	
					<tr>
						<td align='center' class='$nom_clase'>$cont</td>		
						<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_baja'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[area]</td>
						<td align='center' class='$nom_clase'>$datos[puesto]</td>
						<td align='center' class='$nom_clase'>$datos[observaciones]</td>
					</tr>";
				//Acumular la cantidad de Altas por Area								
				if($areaActual==$datos["area"]){					
					$arrAreas[$areaActual] += 1; 
				}
				else{				
					$arrAreas[$datos["area"]] = 0; 	
					$areaActual = $datos["area"];	
					$arrAreas[$areaActual] += 1;
				}	
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
						
			}while($datos=mysql_fetch_array($rs));
			echo "	
			</table>";
			if($flag==1){ 			
				$_SESSION['datosGrapBajas'] = $arrAreas;
			}		
		}//Cierre if($datos = mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $msg_error;
		}?>			
		</div><?php
		
											
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion 
						
?>
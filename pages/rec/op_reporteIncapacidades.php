<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 19/Abril/2011
	  * Descripción: Permite generar reportes de Incapacidades de los empleados 
	**/
	
	//Función que permite mostrar el reporte de Incapacidades
	function reporteIncapacidades(){		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Variable para verificar si la consulta genero datos
		$flag=0;
		
		//Arreglo para guardar las Incapacidades de cada trabajador por Area
		$Incapacidades = 0;
		
		//Arreglo para guardar las incapacidades por area
		$arrIncapacidadesAreas = array();

		
		//Tomamos las fechas del post y las convertimos a formato necesario para la consulta		
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
		$seccFechaIni = split("/",$_POST["txt_fechaIni"]);
		$seccFechaFin = split("/",$_POST["txt_fechaFin"]);
		$fechaIni_enDias=gregoriantojd ($seccFechaIni[1], $seccFechaIni[0], $seccFechaIni[2]);
		$fechaFin_enDias=gregoriantojd ($seccFechaFin[1], $seccFechaFin[0], $seccFechaFin[2]);
		$diferencia=$fechaFin_enDias-$fechaIni_enDias + 1;
		$diferencia=$diferencia-$_POST["domingos"];
		
		//Variable para saber el tipo de consulta
		$origenConsulta= "";
		
		//Verificamos si viene definido el combo area; esto para ver cual sera la consulta a ejecutar
		if(isset($_POST["cmb_area"])){
		
			//VAriable para saber el tipo de consulta
			$origenConsulta = "areas";
			
			//Tomamos el area del post
			$area=$_POST["cmb_area"];
			
			//Crear la consulta
			 $stm_sql = "SELECT DISTINCT empleados_rfc_empleado,CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre,  area, puesto 
						FROM (checadas JOIN empleados ON rfc_empleado=empleados_rfc_empleado) WHERE fecha_checada>='$fechaIni' 
				 		AND fecha_checada<='$fechaFin' AND (checadas.estado='E' OR checadas.estado='RT' OR checadas.estado='T') AND area='$area' ORDER BY area";	
						
			//Creamos el Msj para la gráfica
			$msg_grafica= "Gr&aacute;fica de Incapacidades de ".modFecha($fechaIni,2)." a ".modFecha($fechaFin,2)."";
						
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "Reporte de Incapacidades &Aacute;rea <em><u>$area</u></em> De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontraron resultados  del &Aacute;REA <em><u>$area</u></em> De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";			
		}
		else{
			//Variable para saber el tipo de consulta
			$origenConsulta = "fechas";
			
			//Crear la consulta 
			$stm_sql = "SELECT DISTINCT empleados_rfc_empleado,CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, area, puesto 
						FROM (checadas JOIN empleados ON rfc_empleado=empleados_rfc_empleado) WHERE fecha_checada>='$fechaIni'
						AND fecha_checada<='fechaFin' AND (checadas.estado='E' OR checadas.estado='RT' OR checadas.estado='T') ORDER BY area";
						
			//Creamos el Msj para la gráfica
			$msg_grafica= "Gr&aacute;fica de Incapacidades de ".modFecha($fechaIni,2)." a ".modFecha($fechaFin,2)."";
						
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "Reporte de Incapacidades De: <em><u>".modFecha($fechaIni,2)."</u></em> A: <em><u>".modFecha($fechaFin,2)."</u></em>";
			
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
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>INCAPACIDADES</td>
						<td align='center' class='nombres_columnas'>ASISTENCIAS A CUMPLIR</td>
						<td align='center' class='nombres_columnas'>&Aacute;rea</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>
						<td align='center' class='nombres_columnas'>KARDEX</td>

					</tr>";
			$areaActual = "";							
			$nom_clase = "renglon_gris";
			$cont = 1;
			$stm_sql2="";
			//Si el origen es igual a fechas 
			if($origenConsulta=="fechas"){
				$arrIncapacidadesaAreas[$datos["area"]] = array(); 			
				$areaActual=$datos["area"];
			}
			do{	
				//Ejecutamos la consulta para obtener el numero de incapacidades del empleado
					$stm_sql2="SELECT COUNT(estado) AS incapacidades FROM checadas WHERE empleados_rfc_empleado = '$datos[empleados_rfc_empleado]' 
							AND (checadas.estado='E' OR checadas.estado='RT' OR checadas.estado='T') AND fecha_checada>='$fechaIni' AND fecha_checada<='$fechaFin'";
					$rs2 = mysql_query($stm_sql2);
					$incap=mysql_fetch_array($rs2);
				echo "	
					<tr>
						<td align='center' class='$nom_clase'>$cont</td>		
						<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>";
				echo "<td align='center' class='$nom_clase'>$incap[incapacidades]</td>";
				echo "	<td align='center' class='$nom_clase'>$diferencia</td>
						<td align='center' class='$nom_clase'>$datos[area]</td>
						<td align='center' class='$nom_clase'>$datos[puesto]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verKardex" class="botones" value="Ver Kardex" onMouseOver="window.estatus='';return true" 
							title="Ver Kardex del Empleado <?php echo $datos['empleados_rfc_empleado'];?>" 
							onClick="javascript:window.open('verKardexIncapacidades.php?id_empleado=<?php echo $datos['empleados_rfc_empleado'];?>&fechaIni=<?php echo $fechaIni;?>&fechaFin=<?php echo $fechaFin;?>',
							'_blank','top=50, left=50, width=400, height=350, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
			<?php echo "</tr>";
					//Cuando el Origen de la Consulta es por fechas, Agrupar las incapacidades de cada empleado por Area, acumulando la incapacidades de C/U
					//en el indice con el nombre del area correspondiente					
					if($origenConsulta=="fechas"){												
						if($areaActual==$datos["area"])
							$arrIncapacidadesAreas[$areaActual][] = $incap["incapacidades"]; 			
						else{
							$arrIncapacidadesAreas[$datos["area"]] = array(); 	
							$areaActual=$datos["area"];	
							$arrIncapacidadesAreas[$areaActual][] = $incap["incapacidades"]; 			
						}
					}									
					else if($origenConsulta=="areas"){
						//Guardar la incapacidades de cada trabajador por area	
						$Incapacidades += $incap['incapacidades'];
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
			//Calcular el Promedio de incapacidades de los trabajadores de cada Area
			$IncapacidadesArea = array();
			if($origenConsulta=="fechas"){
				foreach($arrIncapacidadesAreas as $key => $IncapacidadesXArea)
					$IncapacidadesArea[$key] = floatval(array_sum($IncapacidadesXArea) / count($IncapacidadesXArea));								
			}
			//Calcular el Promedio de incapacidades de los trabajadores en una Area determinada
			else if($origenConsulta=="areas"){
				$Incapacidades = floatval($Incapacidades/($cont-1)); 
			}
		
		}//Cierre if($datos = mysql_fetch_array($rs))
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $msg_error;?>			
		</div>
		<div id="btns-regpdf" align="center">
		<table width="19%" cellpadding="12">
			<tr>
				<td width="50%" align="center">
				  	<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reporte Incapacidades" 
                  	onMouseOver="window.estatus='';return true" 
				  	onclick="location.href='frm_reporteIncapacidades.php'" />
				</td><?php 
				if($flag==1){
					//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
					<td width="50%" align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<?php if(isset($_POST["cmb_area"])){?>
								<input name="hdn_nomReporte" type="hidden" 
								value="Reporte_Incapacidades_<?php echo $area;?>_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
							<?php }else{ ?>
								<input name="hdn_nomReporte" type="hidden" 
								value="Reporte_Incapacidades_<?php echo modFecha($fechaIni,1);?> A <?php echo modFecha($fechaFin,1);?>" />
							<?php } ?>
							<input type="hidden"  name="hdn_fechaIni" value="<?php echo $fechaIni;?>"/>
							<input type="hidden"  name="hdn_fechaFin" value="<?php echo $fechaFin;?>"/>
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input name="hdn_origen" type="hidden" value="reporteIncapacidades" />	
							<input name="hdn_fecha" type="hidden" value="<?php echo $diferencia;?>" />						
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
							title="Exportar a Excel los Datos de la Consulta Realizada" 
							onMouseOver="window.estatus='';return true"  />
						</form>
					</td><?php 
				}
				if($flag==1&&isset($_POST["cmb_area"])){ ?>
					<td  width="19%"align="center"><?php 
						$datosGrapIncapacidades= array("Incapacidades"=>$Incapacidades, "hdn_msg"=>$msg_grafica, "diferencia"=>$diferencia,"area"=>$area);
						$_SESSION['datosGrapIncapacidades'] = $datosGrapIncapacidades;?>						
						<input type="button" name="btn_verGrafica" class="botones" value="Ver Grafica" title="Ver Gr&aacute;fica de Incapacidades" 
						onClick="javascript:window.open('verGraficas.php?graph=incapacidadesArea',
						'_blank','top=100, left=250, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />
					</td>
		<?php } else if($flag==1 && !isset($_POST["cmb_area"])){?>				
				<td  width="19%"align="center">                  
				<?php 
					$datosGrapIncapacidades = array("Incapacidades"=>$IncapacidadesArea, "hdn_msg"=>$msg_grafica, "diferencia"=>$diferencia);
					$_SESSION['datosGrapIncapacidades'] = $datosGrapIncapacidades;?>						
					<input type="button" name="btn_verGrafica" class="botones" value="Ver Grafica" title="Ver Gr&aacute;fica de Incapacidades" 
					onClick="javascript:window.open('verGraficas.php?graph=incapacidadesFecha','_blank','top=100, left=250, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />
			  </td>
				<?php }?>
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion 
?>
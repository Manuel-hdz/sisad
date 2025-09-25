<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 02/Marzo/2011                                      			
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Mantenimientos Preventivos/Correctivos
	  **/
	  
	function generarReporte(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		$band = "";
		
		//Arreglos necesarios para guardar los datos obtenidos para generar las graficas correspondientes
		$arrCantidades = array("concreto"=>array(),"mina"=>array());
		$arrCostos = array("concreto"=>array(),"mina"=>array());
	
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$f1 = modFecha($_POST['txt_fechaIni'],3);
		$f2 = modFecha($_POST['txt_fechaFin'],3);
		
		//Crear la consulta para obtener los MTTO PREVENTIVOS de Concreto
		$stm_sql_prevConcreto = "SELECT area, COUNT(tipo_mtto) AS cant_mtto, SUM(costo_mtto)  AS costo_mtto FROM equipos JOIN bitacora_mtto ON id_equipo=equipos_id_equipo 
								 WHERE tipo_mtto='PREVENTIVO' AND area='CONCRETO' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2'";
		//Crear la consulta para obtener los MTTO PREVENTIVOS de Mina
		$stm_sql_prevMina = "SELECT area, COUNT(tipo_mtto) AS cant_mtto, SUM(costo_mtto) AS costo_mtto FROM equipos JOIN bitacora_mtto ON id_equipo=equipos_id_equipo 
							 WHERE  tipo_mtto='PREVENTIVO' AND area='MINA' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2'";
								 
		//Ejecutar las Consultas y Extraer los datos en los Arreglos Correspondientes		
		$datos_prevConcreto = mysql_fetch_array(mysql_query($stm_sql_prevConcreto));
		$datos_prevMina = mysql_fetch_array(mysql_query($stm_sql_prevMina));
		
		//Verificar si existen datos para Mostrar
		if($datos_prevConcreto['cant_mtto']!=0 || $datos_prevMina['cant_mtto']!=0){
			$band=1;			
			//Manejo de Mensajes para la Tabla y la Grafica
			$msg_titulo = "Mantenimientos Preventivos/Correctivos del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em> <br>";			
			$msg_grafica = "Mtto. Preventivos/Correctivos entre $_POST[txt_fechaIni] al $_POST[txt_fechaFin]";																			
			
			echo  " <label class='titulo_etiqueta'>$msg_titulo</label> ";			
			echo "<br>
				<div id='tabla-preventiva'><br><br><br>
				<table cellpadding='5'>		
					<caption class='titulo_etiqueta'>Mantenimientos Preventivos</caption>					
					<tr>						
						<td class='nombres_columnas'>&Aacute;REA</td>
						<td class='nombres_columnas'>TOTAL MTTO.</td>
						<td class='nombres_columnas'>COSTO MTTO.</td>							
					</tr>";			
			
			//Colocar el Renglon de los Mantenimientos Preventivos de Concreto												
			$cant_total = 0;
			if($datos_prevConcreto['cant_mtto']!=0){
				echo"	
					<tr>
						<td class='renglon_gris' align='center'>$datos_prevConcreto[area]</td>
						<td class='renglon_gris' align='center'>$datos_prevConcreto[cant_mtto]</td>
						<td class='renglon_gris'>$".number_format($datos_prevConcreto['costo_mtto'],2,".",",")."</td>
					</tr>";	
				//Guardar la Cantidad y el Costo de los Mantenimientos Preventivos de Concreto
				$arrCantidades['concreto']['preventivo'] = $datos_prevConcreto['cant_mtto'];
				$arrCostos['concreto']['preventivo'] = $datos_prevConcreto['costo_mtto'];																				
				
				//Acumular el Total
				$cant_total += $datos_prevConcreto['costo_mtto'];	
			}
			
			//Colocar el Renglon de los Mantenimientos Preventivos de Mina
			if($datos_prevMina['cant_mtto']!=0){
				echo"	
					<tr>
						<td class='renglon_blanco' align='center'>$datos_prevMina[area]</td>
						<td class='renglon_blanco' align='center'>$datos_prevMina[cant_mtto]</td>
						<td class='renglon_blanco'>$".number_format($datos_prevMina['costo_mtto'],2,".",",")."</td>
					</tr>";	
					
				//Guardar la Cantidad y el Costo de los Mantenimientos Preventivos de Mina
				$arrCantidades['mina']['preventivo'] = $datos_prevMina['cant_mtto'];
				$arrCostos['mina']['preventivo'] = $datos_prevMina['costo_mtto'];
				
				//Acumular el Total
				$cant_total += $datos_prevMina['costo_mtto'];	
			}											
				
			echo "	<tr><td colspan='2'>&nbsp;</td><td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td></tr>
				</table> 
				</div>";
		}//Cierre if($datos_prevMina['cant_mtto']!=0 || $datos_prevConcreto['cant_mtto']!=0)
				
			
			
			
				
				
		//Crear la consulta para obtener los MTTO CORRECTIVO de Concreto		
		$stm_sql_corrConcreto = " SELECT area, COUNT(tipo_mtto) AS cant_mtto, SUM(costo_mtto) AS costo_mtto 
									FROM equipos JOIN bitacora_mtto ON id_equipo=equipos_id_equipo 
										WHERE tipo_mtto='CORRECTIVO' AND area='CONCRETO' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2'";		
				
		//Crear la consulta para obtener los MTTO CORRECTIVO de Mina			
		$stm_sql_corrMina = "SELECT area, COUNT(tipo_mtto) AS cant_mtto, SUM(costo_mtto) AS costo_mtto 
									FROM equipos JOIN bitacora_mtto ON id_equipo=equipos_id_equipo 
										WHERE tipo_mtto='CORRECTIVO' AND area='MINA' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2'";	
				
		//Ejecutar las Consultas y Extraer los datos en los Arreglos Correspondientes		
		$datos_corrConcreto = mysql_fetch_array(mysql_query($stm_sql_corrConcreto));
		$datos_corrMina = mysql_fetch_array(mysql_query($stm_sql_corrMina));
					
		//Verificar si existen datos para Mostrar
		if($datos_corrConcreto['cant_mtto']!=0 || $datos_corrMina['cant_mtto']!=0){		
			$msg_grafica = "Mtto. Preventivos/Correctivos entre $_POST[txt_fechaIni] al $_POST[txt_fechaFin]";		
			$band=1;
			echo"<br>
					<div id='tabla-correctiva'><br><br><br>
					<table cellpadding='5'>
						<caption class='titulo_etiqueta'>Mantenimientos Correctivos</caption>					
						<tr>
							<td class='nombres_columnas'>&Aacute;REA</td>
							<td class='nombres_columnas'>TOTAL MTTO.</td>
							<td class='nombres_columnas'>COSTO MTTO.</td>							
						</tr>";
			//Colocar el Renglon de los Mantenimientos Correctivos de Concreto												
			$cant_total = 0;
			if($datos_corrConcreto['cant_mtto']!=0){
				echo"	
					<tr>
						<td class='renglon_gris' align='center'>$datos_corrConcreto[area]</td>
						<td class='renglon_gris' align='center'>$datos_corrConcreto[cant_mtto]</td>
						<td class='renglon_gris'>$".number_format($datos_corrConcreto['costo_mtto'],2,".",",")."</td>
					</tr>";	
					
				//Guardar la Cantidad y el Costo de los Mantenimientos Correctivos de Concreto
				$arrCantidades['concreto']['correctivo'] = $datos_corrConcreto['cant_mtto'];
				$arrCostos['concreto']['correctivo'] = $datos_corrConcreto['costo_mtto'];
				
				//Acumular el Total
				$cant_total += $datos_corrConcreto['costo_mtto'];	
			}
			
			//Colocar el Renglon de los Mantenimientos Correctivos de Mina
			if($datos_corrMina['cant_mtto']!=0){
				echo"	
					<tr>
						<td class='renglon_blanco' align='center'>$datos_corrMina[area]</td>
						<td class='renglon_blanco' align='center'>$datos_corrMina[cant_mtto]</td>
						<td class='renglon_blanco'>$".number_format($datos_corrMina['costo_mtto'],2,".",",")."</td>
					</tr>";	
					
				//Guardar la Cantidad y el Costo de los Mantenimientos Correctivos de Mina
				$arrCantidades['mina']['correctivo'] = $datos_corrMina['cant_mtto'];
				$arrCostos['mina']['correctivo'] = $datos_corrMina['costo_mtto'];
				
				//Acumular el Total
				$cant_total += $datos_corrMina['costo_mtto'];	
			}											
				
			echo "	<tr><td colspan='2'>&nbsp;</td><td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td></tr>
				</table> 
				</div>";
		}//Cierre if($datos_corrConcreto['cant_mtto']!=0 || $datos_corrMina['cant_mtto']!=0)
		
		if($datos_prevConcreto['cant_mtto']==0){
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			echo $msg_error= "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Mtto Preventivo de Concreto 
					 <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em><br></label>";
		}
		if($datos_prevMina['cant_mtto']==0){
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		 	echo $msg_error= "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Mtto Preventivo de Mina 
						 <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em><br></label>";
		}
		if($datos_corrConcreto['cant_mtto']==0){
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		 	echo $msg_error= "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Mtto Correctivo de Concreto 
						 <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em><br></label>";
		}
		if($datos_corrMina['cant_mtto']==0){
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		 	echo $msg_error= "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Mtto Correctivo de Mina 
						 <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em><br></label>";
		}
		
		//La declaración de esta capa(div) que se esta cerrando se encuentar en el archivo frm_reportePreventivoCorrectivo antes de mandar llamar la funcion generarReporte()?>
		</div>
		<div id="btns-rpt">
		<table width="341" align="center">
			<tr>
				<td width="131" align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Mtto.Preventivos/Correctivos" onclick="location.href='frm_reportePreventivoCorrectivo.php'" />
				</td>
				<?php if($band==1){ ?> 
				<td width="131" align="center">        
					<?php 
						//Guadar consultas en la SESSION para ser obtenidas en el archivo verGrafica.php
						$datosGrafica = array("arrCantidades"=>$arrCantidades,"arrCostos"=>$arrCostos,"hdn_msg"=>$msg_grafica);
						$_SESSION['datosGrafica'] = $datosGrafica;
					?>						
					<input type="button" name="btn_verGrafica" class="botones" value="Ver Grafica" title="Ver Gr&aacute;fica Mtto. Preventivo/Correctivo" 
					onClick="javascript:window.open('verGraficasMtto.php?graph=preventivoCorrectivo',
					'_blank','top=100, left=250, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />
				</td>
				<?php } ?>
			</tr>
		</table>
		</div><?php											
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
?>
<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 22/Febrero/2011                                      			
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Mantenimientos Preventivos
	  **/
	function generarReporte($tipo_rpt){
		?><div id="reporte" align="center" class="borde_seccion2"><?php
		
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		//Variable para verificar si la consulta ejecutada arrojo resultados
		$flag = 0;		
						
		switch($tipo_rpt){
			case 1://Reporte por Area
				//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
				$f1 = modFecha($_POST['txt_fechaIni'],3);
				$f2 = modFecha($_POST['txt_fechaFin'],3);
				
				//Crear la consulta
				$stm_sql ="SELECT id_bitacora, area,familia,id_equipo,fecha_mtto,tipo_mtto,horometro,odometro,costo_mtto 
							FROM equipos JOIN bitacora_mtto ON id_equipo=bitacora_mtto.equipos_id_equipo 
						   	WHERE area='$_POST[cmb_area]' AND tipo_mtto='PREVENTIVO' AND fecha_mtto>='$f1' AND fecha_mtto<='$f2' ORDER BY fecha_mtto";
				
					//Mensaje que desplegara el titulo de la tabla
				$msg_titulo = "Reporte de Mantenimientos Preventivos del &Aacute;rea de <em><u>$_POST[cmb_area]</u></em>&nbsp;
								<br>En el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
				
				if($_POST['cmb_area']=="CONCRETO")
					$msg_grafica = "Mantenimientos Preventivos del Área de Concreto";
				else
					$msg_grafica = "Mantenimientos Preventivos del Área de Mina";
				
				//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
				$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Mantenimientos Preventivos del &Aacute;rea <em><u>
				$_POST[cmb_area]</u></em>&nbsp;
								<br>En las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";	
						
				//Definir datos del reporte de mantenimientos preventivos en la SESSION
				$datosPreventivos = array("cmb_area"=>$_POST['cmb_area'],"txt_fechaIni"=>$_POST['txt_fechaIni'],"txt_fechaFin"=>$_POST['txt_fechaFin']);
				$_SESSION['datosRptPreventivos'] = $datosPreventivos;
			break;
			
			case 2://Reporte por Familia
				//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
				$f3 = modFecha($_POST['txt_fechaIni'],3);
				$f4 = modFecha($_POST['txt_fechaFin'],3);
				$area = $_POST['hdn_area'];
				
				//Crear la consulta
				$stm_sql ="SELECT id_bitacora,area,familia,id_equipo,fecha_mtto,tipo_mtto,horometro,odometro,costo_mtto 
							FROM equipos JOIN bitacora_mtto ON id_equipo=bitacora_mtto.equipos_id_equipo 
						   	WHERE familia='$_POST[cmb_familia]' AND area='$area' AND tipo_mtto='PREVENTIVO' AND fecha_mtto>='$f3' AND fecha_mtto<='$f4' ORDER BY fecha_mtto";
				
				//Mensaje que desplegara el titulo de la tabla				
				$msg_titulo = "	Reporte de Mantenimientos Preventivos Correspondiente a la Familia: <em><u>$_POST[cmb_familia]</u></em> 
									<br>En el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
				$msg_grafica = "Mantenimientos Preventivos a la Familia: $_POST[cmb_familia]";
				
				//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
				$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Mantenimientos Preventivos de la Familia <em><u>
				$_POST[cmb_familia]</u></em>&nbsp;
								<br>En las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";	
						
				//Definir datos del reporte de mantenimientos preventivos en la SESSION
				$datosPreventivos = array("cmb_familia"=>$_POST['cmb_familia'],"hdn_area"=>$_POST['hdn_area'],"txt_fechaIni"=>$_POST['txt_fechaIni'],
											"txt_fechaFin"=>$_POST['txt_fechaFin']);
				$_SESSION['datosRptPreventivos'] = $datosPreventivos;
			break;
			
			case 3://Reporte por Equipo																														 
				$id_equipo = $_POST['cmb_equipo'];
								
				//Crear la consulta
				$stm_sql ="SELECT id_bitacora, area,familia,id_equipo,fecha_mtto,tipo_mtto,horometro,odometro,costo_mtto 
							FROM equipos JOIN bitacora_mtto ON id_equipo=bitacora_mtto.equipos_id_equipo 
						   	WHERE tipo_mtto='PREVENTIVO' AND id_equipo='$id_equipo' AND fecha_mtto!='0000-00-00' ORDER BY fecha_mtto";
				
				//Mensaje que desplegara el titulo de la tabla				
				$msg_titulo = "	Reporte de Mantenimientos Preventivos Correspondiente al Equipo: <em><u>$id_equipo</u></em>";
				$msg_grafica = "Mantenimientos Preventivos del Equipo: $id_equipo";
				
				//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
				$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Mantenimientos Preventivos del Equipo 
							  <em><u>$id_equipo</u></em>";	
						
				//Definir datos del reporte de mantenimientos preventivos en la SESSION
				$datosPreventivos = array("cmb_familia"=>$_POST['cmb_familia'],"cmb_equipo"=>$_POST['cmb_equipo']);
				$_SESSION['datosRptPreventivos'] = $datosPreventivos;
			break;
			
			case 4://Reporte Por Costos				
				//Quitar la coma en el costo mayor y menor, para poder realizar la operaciones requeridas.
				$costo_menor = str_replace(",","",$_POST['txt_nivelInf']);
				$costo_mayor = str_replace(",","",$_POST['txt_nivelSup']);
				
				/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/				
				$paramExtra = "";				
				if($_SESSION['depto']=="MttoConcreto")
					$paramExtra = "AND area='CONCRETO'";									
				else if($_SESSION['depto']=="MttoMina")
					$paramExtra = "AND area='MINA'";
				
				//Crear la consulta 
				$stm_sql ="SELECT id_bitacora,area,familia,id_equipo,fecha_mtto,tipo_mtto,horometro,odometro,costo_mtto 
							FROM equipos JOIN bitacora_mtto ON id_equipo=bitacora_mtto.equipos_id_equipo
						   	WHERE tipo_mtto='PREVENTIVO' AND costo_mtto>=($costo_menor-0.01) AND costo_mtto<=($costo_mayor+0.01) AND fecha_mtto!='0000-00-00' ".$paramExtra." 
							ORDER BY id_bitacora"; 
						
				//Mensaje para desplegar en el titulo de la tabla
				$msg_titulo = "Reporte de Mantenimientos Preventivos Correspondiente al Rango de <em><u>$ $_POST[txt_nivelInf]</u></em> y <em><u>$ $_POST[txt_nivelSup]</u></em>";
				$msg_grafica = "Mantenimientos Preventivos entre $ $costo_menor y $ $costo_mayor";
				
				//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
				$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado con el Rango de Cantidades 
								<em><u>$ $_POST[txt_nivelInf]</u></em> y <em><u>$ $_POST[txt_nivelSup]</u></em></label>";												
								
				//Definir datos del reporte de compras en la SESSION
				$datosPreventivos = array("txt_nivelInf"=>$_POST['txt_nivelInf'],"txt_nivelSup"=>$_POST['txt_nivelSup']);
				$_SESSION['datosRptPreventivos'] = $datosPreventivos;
			break;	
		}			
																			
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){		
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			echo "				
			<table cellpadding='7'>
				<caption class='titulo_etiqueta'>$msg_titulo</caption>					
				<tr>";
			//if($tipo_rpt==4){
				echo "<td class='nombres_columnas'>VER DETALLE</td>
					  <td class='nombres_columnas'>CLAVE BITACORA</td>";		
			//}
			echo "	<td class='nombres_columnas'>&Aacute;REA</td>
					<td class='nombres_columnas'>FAMILIA</td>
					<td class='nombres_columnas'>CLAVE EQUIPO</td>
					<td class='nombres_columnas'>FECHA MTTO.</td>
					<td class='nombres_columnas'>TIPO MTTO.</td>
					<td class='nombres_columnas'>HOROMETRO/ODOMETRO</td>
					<td class='nombres_columnas'>COSTO</td>
				</tr>
				
				<form name='frm_mostrarDetalleRP' method='post' action='frm_reportePreventivo.php'>
				<input type='hidden' name='verDetalle' value='si' />
				<input type='hidden' name='no_reporte' value='$tipo_rpt' />";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$cant_total = 0;
			do{
				echo "	
				<tr>";
				//if($tipo_rpt==4){
					echo"								
					<td class='nombres_filas'>
						<input type='checkbox' name='RP$cont' value='$datos[id_bitacora]' onClick='javascript:document.frm_mostrarDetalleRP.submit();'/>						
					</td>
					<td class='$nom_clase'>$datos[id_bitacora]</td>";
				//}	
				
				//Determinar el Valor de la Metrica
				$metrica = 0;
				if($datos['horometro']!=0)
					$metrica = $datos['horometro']." Hrs.";
				else if($datos['odometro']!=0)
					$metrica = $datos['odometro']." Kms.";
					
																		
				echo"
					<td class='$nom_clase'>$datos[area]</td>
					<td class='$nom_clase'>$datos[familia]</td>
					<td class='$nom_clase'>$datos[id_equipo]</td>
					<td class='$nom_clase'>".modFecha($datos['fecha_mtto'],1)."</td>					
					<td class='$nom_clase' align='left'>$datos[tipo_mtto]</td>
					<td class='$nom_clase'>$metrica</td>					
					<td class='$nom_clase'>$".number_format($datos['costo_mtto'],2,".",",")."</td>
				</tr>";	
						
				$cant_total += $datos['costo_mtto'];	
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			$renglones = 6;
			if($tipo_rpt!=5) $renglones = 8;
			echo "</form>
					<tr><td colspan='$renglones'>&nbsp;</td><td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td></tr>";
			echo "</table>";
		}//Cierre if($datos=mysql_fetch_array($rs))
	 	else
			echo $msg_error;?>			
		</div><!--Cierre del Layer "reporte" -->	
			
		
		<div id="btns-rpt">
		<table width="100%">
			<tr>
				<td align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Mantenimientos" 
					onclick="location.href='frm_reportePreventivo.php'" />
				</td>
				<?php if($flag==1) { ?>              
					<td align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Consulta Mtto Preventivos " />                  		
							<input name="hdn_origen" type="hidden" value="mttoPreventivo" />
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
							onmouseover="window.estatus='';return true"  />
						</form>
					</td>              
				<?php }
				if($flag==1){ ?>
					<td align="center">                  
						<?php 
						$datosGrapPreventivos = array("hdn_consulta"=>$stm_sql, "hdn_msg"=>$msg_grafica);
						$_SESSION['datosGrapPreventivos'] = $datosGrapPreventivos;
						?>						
						<input type="button" name="btn_verGrafica" class="botones" value="Ver Grafica" title="Ver Gr&aacute;fica de Mantenimientos Preventivos" 
						onClick="javascript:window.open('verGraficas.php?graph=mttoPreventivo',
						'_blank','top=100, left=250, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />
					</td>
				<?php } ?>
			</tr>
		</table>
		</div><?php
						
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
	
	/*Esta funcion muestra el detalle del reporte de costos*/
	function mostrarDetalleRP($clave,$no_reporte){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		//Realizar la conexion a la BD de Mantenimeinto
		$conn = conecta("bd_mantenimiento");
		//Sentencia para verificar las actividades correctivas aplicadas al Mtto
		$stm_sql = "SELECT sistema,aplicacion,descripcion FROM actividades WHERE id_actividad=ANY(SELECT actividades_id_actividad FROM gama_actividades WHERE gama_id_gama=
					ANY(SELECT gama_id_gama FROM actividades_ot WHERE orden_trabajo_id_orden_trabajo=
					ANY(SELECT orden_trabajo_id_orden_trabajo FROM bitacora_mtto WHERE id_bitacora='$clave')))";
		//Ejecutar la consulta pata obtener los resultados para el detalle del mantenimiento preventivo
		$rs = mysql_query($stm_sql);
		if($datosActGama=mysql_fetch_array($rs)){
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>ACTIVIDADES DE LAS GAMAS REGISTRADAS PARA LA BIT&Aacute;CORA <em><u>$clave</u></em></caption>					
				<tr>
					<td class='nombres_columnas'>SERVICIO</td>
					<td class='nombres_columnas'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas'>ACTIVIDAD</td>					
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo "<tr>		
						<td class='$nom_clase'>$datosActGama[sistema]</td>	
						<td class='$nom_clase'>$datosActGama[aplicacion]</td>
						<td class='$nom_clase'>$datosActGama[descripcion]</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datosActGama=mysql_fetch_array($rs));
			echo "</table>";
		}
		else
			echo "<p class='msje_correcto' align='center'>No Hay Actividades Cargadas a las Gamas Aplicadas</p>";
		
		echo "<br>";
		//Sentencia para verificar las actividades correctivas aplicadas al Mtto
		$stm_sql = "SELECT sistema,aplicacion,descripcion FROM actividades_correctivas WHERE bitacora_mtto_id_bitacora ='$clave' ORDER BY sistema,aplicacion,descripcion";
		//Ejecutar la consulta pata obtener los resultados para el detalle del mantenimiento preventivo
		$rs = mysql_query($stm_sql);
		if($datosActCorr=mysql_fetch_array($rs)){
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>ACTIVIDADES CORRECTIVAS EN <em><u>$clave</u></em></caption>					
				<tr>
					<td class='nombres_columnas'>SERVICIO</td>
					<td class='nombres_columnas'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas'>ACTIVIDAD</td>					
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo "<tr>		
						<td class='$nom_clase'>$datosActCorr[sistema]</td>	
						<td class='$nom_clase'>$datosActCorr[aplicacion]</td>
						<td class='$nom_clase'>$datosActCorr[descripcion]</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datosActCorr=mysql_fetch_array($rs));
			echo "</table>";
		}
		else
			echo "<p class='msje_correcto' align='center'>No Hay Actividades Correctivas en el Presente Registro de Bit&aacute;cora</p>";
		
		echo "<br>";
		//Sentencia SQL para extraer los materiales empleados en el registro de la bitacora
		$stm_sql = "SELECT id_vale, materiales_id_material, cant_salida, bd_almacen.materiales.costo_unidad, bd_almacen.materiales.nom_material, 
					costo_mtto, bd_almacen.detalle_salidas.costo_total 
					FROM ((((bd_almacen.detalle_salidas JOIN bd_almacen.salidas ON salidas_id_salida=id_salida)JOIN bd_mantenimiento.materiales_mtto ON no_vale = id_vale) 
					JOIN bitacora_mtto ON id_bitacora = bitacora_mtto_id_bitacora) 
					JOIN bd_almacen.materiales ON id_material = materiales_id_material) WHERE bitacora_mtto_id_bitacora ='$clave' ORDER BY bitacora_mtto_id_bitacora";	

		//Ejecutar la consulta pata obtener los resultados para el detalle del mantenimietno preventivo
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>DETALLE MTTO PREVENTIVO <em><u>$clave</u></em></caption>					
				<tr>
					<td class='nombres_columnas'>CLAVE VALE</td>
					<td class='nombres_columnas'>CLAVE MATERIAL</td>
					<td class='nombres_columnas'>NOMBRE MATERIAL</td>
					<td class='nombres_columnas'>CANTIDAD SALIDA</td>
					<td class='nombres_columnas'>COSTO UNITARIO</td>
					<td class='nombres_columnas'>COSTO TOTAL</td>					
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			$costo_total =0;	
			do{
				echo "<tr>		
						<td class='$nom_clase'>$datos[id_vale]</td>	
						<td class='$nom_clase'>$datos[materiales_id_material]</td>
						<td class='$nom_clase'>$datos[nom_material]</td>
						<td class='$nom_clase'>$datos[cant_salida]</td>	
						<td class='$nom_clase'>$".number_format($datos['costo_unidad'],2,".",",")."</td>																								
						<td class='$nom_clase'>$".number_format($datos['costo_total'],2,".",",")."</td>																																									
					</tr>";
					$costo_total = $datos['costo_total'] + $costo_total;
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				$costo_mat= ($datos['costo_mtto']);
			}while($datos=mysql_fetch_array($rs));
			//obtener el costo de la mano de obra
			$mano_obra= ($costo_mat-$costo_total);
			
			echo "
				<td class='$nom_clase' colspan ='5' align='right' >MANO DE OBRA</td>
				<td class='$nom_clase'>$".number_format($mano_obra,2,".",",")."</td>
			<tr>
				<td></td><td></td><td></td><td></td><td></td><td class='nombres_columnas'>$".number_format(($costo_total+$mano_obra),2,".",",")."</td>
			</tr>	
			</table>";
							
		}
		else
			echo "<p class='msje_correcto' align='center'>No Hay Materiales Utilizados en el Presente Registro de Bit&aacute;cora</p>";
		
		//Sentencia para verificar las actividades correctivas segun las Órdenes de Trabajo Externas aplicadas al Mtto
		$stm_sql = "SELECT id_orden,sistema,aplicacion,descripcion,nom_proveedor,costo_total FROM actividades_realizadas JOIN orden_servicios_externos ON orden_servicios_externos_id_orden=id_orden
					WHERE orden_servicios_externos_id_orden=(SELECT orden_servicios_externos_id_orden FROM bitacora_mtto WHERE id_bitacora='$clave')";
		$rs=mysql_query($stm_sql);
		if($datosExternos=mysql_fetch_array($rs)){
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>ACTIVIDADES CORRECTIVAS EN <em><u>$clave</u></em> CON LA &Oacute;RDEN <em><u>$datosExternos[id_orden]</u></em> ASOCIADA</caption>
				<tr>
					<td class='nombres_columnas'>PROVEEDOR</td>
					<td class='renglon_gris' colspan='2'>$datosExternos[nom_proveedor]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>COSTO DEL SERVICIO</td>
					<td class='renglon_gris' colspan='2'>$".number_format($datosExternos["costo_total"],2,".",",")."</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>SERVICIO</td>
					<td class='nombres_columnas'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas'>ACTIVIDAD</td>					
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo "<tr>		
						<td class='$nom_clase'>$datosExternos[sistema]</td>	
						<td class='$nom_clase'>$datosExternos[aplicacion]</td>
						<td class='$nom_clase'>$datosExternos[descripcion]</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datosExternos=mysql_fetch_array($rs));
			echo "</table>";
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
		?></div> 
		<div id="btns-rpt">
			<table align="center">
				<tr>
					<td>
					<form action="frm_reportePreventivo.php" method="post">
						<input name="hdn_tipoRpt" type="hidden" value="<?php echo $no_reporte; ?>" />
						<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte Mantenimientos Preventivos" 
						onmouseover="window.estatus='';return true" id="sbt_regresar" />
					</form>
					</td>
				</tr>
			</table>
		</div>
	 <?php	
	}


?>
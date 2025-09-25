<?php
	/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 20/Enero/2011                                      			
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Ventas 
	  **/

		function generarReporte($tipo_rpt){
			?><div id="reporte" align="center" class="borde_seccion2"><?php
		
			//Realizar la conexion a la BD de Compras
			$conn = conecta("bd_compras");
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 0;		
						
			switch($tipo_rpt){
				case 1:					 											
					//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
					$f1 = modFecha($_POST['txt_fechaIni'],3);
					$f2 = modFecha($_POST['txt_fechaFin'],3);						
					//Crear la consulta
					if(isset($_POST["ckb_publicoGral"])) {						
						$stm_sql = "SELECT id_venta,fecha,nom_cliente AS razon_social,direccion,subtotal,iva,total,factura,vendio,autorizador,ventas.comentarios
									FROM ventas WHERE clientes_rfc='PUBLICOGRAL' AND fecha>='$f1' AND fecha<='$f2' ORDER BY id_venta";
						//Mensaje para desplegar en el titulo de la tabla
						$msg_titulo = "	Reporte de Ventas Correspondiente al <em><u>Publico en General</u></em> 
										<br>En el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
						$msg_grafica = "Ventas al Publico en General";
					}
					else {
						$stm_sql = "SELECT id_venta,fecha,razon_social,CONCAT(calle,' ',numero_ext,' ',colonia,' ',municipio,' ',estado) AS direccion,
						subtotal,iva,total,factura,vendio,autorizador,ventas.comentarios FROM ventas JOIN clientes ON clientes_rfc=rfc 
						WHERE razon_social='$_POST[txt_cliente]' AND fecha>='$f1' AND fecha<='$f2' ORDER BY id_venta";
						
						//Mensaje para desplegar en el titulo de la tabla
						$msg_titulo = "	Reporte de Ventas Correspondiente al Cliente: <em><u>$_POST[txt_cliente]</u></em> 
										<br>En el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
						$msg_grafica = "Ventas al Cliente: $_POST[txt_cliente]";
					}
										
					
					//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
					$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado con: <em><u>$_POST[txt_cliente]</u></em>
									<br>En las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";												
					
					
					//Definir datos del reporte de compras en la SESSION
					$datosVentas = array("txt_cliente"=>$_POST['txt_cliente'],"txt_fechaIni"=>$_POST['txt_fechaIni'],"txt_fechaFin"=>$_POST['txt_fechaFin']);
					$_SESSION['datosRptVentas'] = $datosVentas;
				break;
				
				case 2:
					//Quitar la coma en el costo mayor y menor, para poder realziar la operaciones requeridas.
					$costo_menor = str_replace(",","",$_POST['txt_nivelInf']);
					$costo_mayor = str_replace(",","",$_POST['txt_nivelSup']);
					
					//Crear la consulta 
					$stm_sql = "SELECT id_venta,fecha,razon_social, CONCAT(calle,' ',numero_ext,' ',colonia,' ',municipio,' ',estado) AS direccion,
						subtotal,iva,total,factura,vendio,autorizador,ventas.comentarios FROM ventas JOIN clientes ON clientes_rfc=rfc
						WHERE total>=$costo_menor AND total<=$costo_mayor UNION ALL SELECT id_venta,fecha,nom_cliente AS razon_social,direccion,subtotal,
						iva,total,factura,vendio,autorizador,ventas.comentarios	FROM ventas WHERE clientes_rfc='PUBLICOGRAL' ORDER BY id_venta" ;
					
					//Mensaje para desplegar en el titulo de la tabla
					$msg_titulo = "Reporte de Ventas Correspondiente la Rango de <em><u>$ $_POST[txt_nivelInf]</u></em> y <em><u>$ $_POST[txt_nivelSup]</u></em>";
					$msg_grafica = "Ventas entre $ $costo_menor y $ $costo_mayor";
					
					//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
					$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado con el Rango de Cantidades 
									<em><u>$ $_POST[txt_nivelInf]</u></em> y <em><u>$ $_POST[txt_nivelSup]</u></em></label>";												
									
					//Definir datos del reporte de compras en la SESSION
					$datosVentas = array("txt_nivelInf"=>$_POST['txt_nivelInf'],"txt_nivelSup"=>$_POST['txt_nivelSup']);
					$_SESSION['datosRptVentas'] = $datosVentas;
				break;
				case 3:
					//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
					$f1 = modFecha($_POST['txt_fechaIni'],3);
					$f2 = modFecha($_POST['txt_fechaFin'],3);
					//Crear la consulta 
					$stm_sql = "SELECT id_venta,fecha,razon_social,CONCAT(calle,' ',numero_ext,' ',colonia,' ',municipio,' ',estado) AS direccion,
						subtotal,iva,total,factura,vendio,autorizador,ventas.comentarios FROM ventas JOIN clientes ON clientes_rfc=rfc
						WHERE fecha>='$f1' AND fecha<='$f2' UNION ALL SELECT id_venta,fecha,nom_cliente AS razon_social,direccion,subtotal,
						iva,total,factura,vendio,autorizador,ventas.comentarios	FROM ventas WHERE clientes_rfc='PUBLICOGRAL' AND fecha>='$f1' AND fecha<='$f2' ORDER BY id_venta";
					
					//Mensaje para desplegar en el titulo de la tabla
					$msg_titulo = "	Reporte de Ventas Correspondiente al Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
					$msg_grafica = "Ventas del $_POST[txt_fechaIni] y $_POST[txt_fechaFin]";
					
					//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
					$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado Entre las Fechas del <em><u>
					$_POST[txt_fechaIni]
					</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";				
														
					
					//Definir datos del reporte de compras en la SESSION
					$datosVentas = array("txt_fechaIni"=>$_POST['txt_fechaIni'],"txt_fechaFin"=>$_POST['txt_fechaFin']);
					$_SESSION['datosRptVentas'] = $datosVentas;
				break;									
				case 4:
				//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
					$f3 = modFecha($_POST['txt_fechaIni'],3);
					$f4 = modFecha($_POST['txt_fechaFin'],3);
					
					//Crear la consulta
					$stm_sql = "SELECT id_venta,fecha,razon_social, CONCAT(calle,' ',numero_ext,' ',colonia,' ',municipio,' ',estado) AS direccion,
						subtotal,iva,total,factura,vendio,autorizador,ventas.comentarios FROM ventas JOIN clientes ON clientes_rfc=rfc 
						WHERE fecha>='$f3' AND fecha<='$f4' AND factura='$_POST[cmb_factura]' UNION ALL SELECT id_venta,fecha,nom_cliente AS razon_social,direccion,
						subtotal,iva,total, factura,vendio,autorizador,ventas.comentarios FROM ventas WHERE clientes_rfc='PUBLICOGRAL' AND fecha>='$f3' 
						AND fecha<='$f4' AND factura='$_POST[cmb_factura]' ORDER BY id_venta";
					
					//Mensaje que desplegara el titulo de la tabla
					$msg_titulo = "Reporte de Ventas que  <em><u>$_POST[cmb_factura]</u></em>&nbsp;Fueron <em><u>Facturadas</em></u>
									<br>En el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
					
					if($_POST['cmb_factura']=="SI")
						$msg_grafica = "Ventas que han sido Facturadas";
					else
						$msg_grafica = "Ventas que NO han sido Facturadas";
					
					//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
					$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado de Ventas  <em><u>
					$_POST[cmb_factura]</u></em>&nbsp;Facturadas
									<br>En las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";	
							
					//Definir datos del reporte de compras en la SESSION
					$datosVentas = array("cmb_factura"=>$_POST['cmb_factura'],"txt_fechaIni"=>$_POST['txt_fechaIni'],"txt_fechaFin"=>$_POST['txt_fechaFin']);
					$_SESSION['datosRptVentas'] = $datosVentas;
				break;			
			}
																		
			//Ejecutar la consulta
			$rs = mysql_query($stm_sql);
			//Mostrar los resultados obtenidos
			if($datos = mysql_fetch_array($rs)){		
				//Variable para verificar si la consulta ejecutada arrojo resultados
				$flag = 1;
				echo "				
					<table cellpadding='5'>
						<caption class='titulo_etiqueta'>$msg_titulo</caption>					
						<tr>
							<td class='nombres_columnas'>VER DETALLE</td>
							<td class='nombres_columnas'>NO.</td>
							<td class='nombres_columnas'>FECHA</td>
							<td class='nombres_columnas'>CLIENTE</td>
							<td class='nombres_columnas'>DIRECCION</td>
							<td class='nombres_columnas'>VENDI&Oacute;</td>
							<td class='nombres_columnas'>FACTURA</td>
							<td class='nombres_columnas'>AUTORIZACI&OacuteN</td>
							<td class='nombres_columnas'>SUBTOTAL</td>
							<td class='nombres_columnas'>IVA</td>
							<td class='nombres_columnas'>TOTAL</td>
						</tr>
						
						<form name='frm_mostrarDetalleRV' method='post' action='frm_reporteVentas.php'>
						<input type='hidden' name='verDetalle' value='si' />
						<input type='hidden' name='no_reporte' value='$tipo_rpt' />";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				$cant_total = 0;
				do{									
					echo "	
						<tr>					
							<td class='nombres_filas'><input type='checkbox' name='RV$cont' value='$datos[id_venta]' 
							onClick='javascript:document.frm_mostrarDetalleRV.submit();'/></td>
							<td class='$nom_clase'>$datos[id_venta]</td>					
							<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase' align='left'>$datos[razon_social]</td>					
							<td class='$nom_clase' align='left'>$datos[direccion]</td>					
							<td class='$nom_clase'>$datos[vendio]</td>	
							<td class='$nom_clase'>$datos[factura]</td>					
							<td class='$nom_clase'>$datos[autorizador]</td>	
							<td class='$nom_clase'>$".number_format($datos['subtotal'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['iva'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['total'],2,".",",")."</td>
						</tr>";										
												
					$cant_total += $datos['total'];
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
							
				}while($datos=mysql_fetch_array($rs));
				echo "	</form>
						<tr><td colspan='10'>&nbsp;</td><td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td></tr>";
				echo "</table>";
			}
			else//Si no se encuentra ningun resultado desplegar un mensaje					
				echo $msg_error;?>
			</div><!--Cierre del Layer "reporte" -->		
			
					
			<div id="btns-regpdf">
			<table width="100%">
				<tr>
					<td align="center">
						<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Ventas" onclick=
						"location.href='frm_reporteVentas.php'" />
					</td>
					<?php if($flag==1) { ?>              
						<td align="center">
							<form action="guardar_reporte.php" method="post">
								<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
								<input name="hdn_nomReporte" type="hidden" value="Consulta de Ventas" />                  		
								<input name="hdn_origen" type="hidden" value="ventas" />
								<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
								<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta 
								Realizada" onmouseover="window.estatus='';return true"  />
							</form>
						</td>              
					<?php }
					if($flag==1){ ?>
						<td align="center">                  
							<?php 
							$datosGrapVentas = array("hdn_consulta"=>$stm_sql, "hdn_msg"=>$msg_grafica);
							$_SESSION['datosGrapVentas'] = $datosGrapVentas;
							?>						
							<input type="button" name="btn_verGrafica" class="botones" value="Ver Grafica" title="Ver Gr&aacute;fica de Ventas" 
							onClick="javascript:window.open('verGraficas.php?graph=Venta',
							'_blank','top=0, left=0, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />
						</td>
					<?php } ?>
				</tr>
			</table>
			</div><?php
									
			//Cerrar la conexion con la BD
			mysql_close($conn);
	}
	
	
	//Esta función se encarga de mostrar el detalle de la Venta seleccionada
	function mostrarDetalleRV($clave,$no_reporte){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		
		$stm_sql = "SELECT partida, unidad, cantidad, descripcion, precio_unitario, importe FROM (detalles_venta JOIN ventas ON ventas_id_venta=id_venta) WHERE 
		ventas_id_venta = '$clave'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			echo "								
			<table cellpadding='6'>
				<caption class='titulo_etiqueta'>DETALLE DE LA VENTA <em><u>$clave</u></em></caption>					
				<tr>
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>UNIDAD</td>
					<td class='nombres_columnas'>CANTIDAD</td>
					<td class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas'>PRECIO UNITARIO</td>
					<td class='nombres_columnas'>IMPORTE</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$datos[partida]</td>
						<td class='$nom_clase'>$datos[unidad]</td>	
						<td class='$nom_clase'>$datos[cantidad]</td>	
						<td class='$nom_clase'>$datos[descripcion]</td>	
						<td class='$nom_clase'>$ ".number_format($datos['precio_unitario'],2,".",",")."</td>
						<td class='$nom_clase'>$ ".number_format($datos['importe'],2,".",",")."</td>
					</tr>";
					
				//Determinar el color del siguiente renlon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";?>
			</div><!--Cierre del Layer "reporte"-->				            
			
			<div id="btns-regpdf" align="center">
			<table width="100%">
				<tr>	
					<td align="center">
						<form action="frm_reporteVentas.php" method="post">
							<input name="hdn_tipoRpt" type="hidden" value="<?php echo $no_reporte; ?>" />
							<?php if($no_reporte==1 && $_SESSION['datosRptVentas']['txt_cliente']==""){ ?>
								<input name="ckb_publicoGral" type="hidden" value="" />
							<?php } ?>	
					    	<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Ventas" 
							onmouseover="window.estatus='';return true" id="sbt_regresar"  />
				  		</form>
					</td>			
				</tr>
			</table>			
			</div>
			
		<?php }//Cierre if($datos=mysql_fetch_array($rs))
						
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre funcion mostrarDetalleRV($clave,$no_reporte)
		
?>
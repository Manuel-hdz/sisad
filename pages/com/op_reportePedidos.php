<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Armando Ayala Alvarado
	  * Fecha: 12/Junio/2012
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Consumo de Aceites 
	  **/

	function reportePedidos(){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		$flag = 0;
		$msg_grafica = "";
		$fecha_i = modFecha($_POST["txt_fecha_ini"],3);
		$fecha_f = modFecha($_POST["txt_fecha_fin"],3);
		$msg_titulo = "Reporte de Pedidos del $_POST[txt_fecha_ini] al $_POST[txt_fecha_fin]";
		$conn=conecta("bd_compras");
		
		$stm_sql = "SELECT DISTINCT T1.`id_pedido` , T3.`razon_social` , T1.`fecha` , T1.`solicitor` , T1.`revisor` , T1.`depto_solicitor` , T1.`total` , T1.`tipo_moneda` 
					FROM  `pedido` AS T1
					JOIN  `detalles_pedido` AS T2 ON T2.`pedido_id_pedido` = T1.`id_pedido` 
					JOIN  `proveedores` AS T3 ON T1.`proveedores_rfc` = T3.`rfc` 
					LEFT JOIN  `bd_recursos`.`control_costos` AS T4
					USING (  `id_control_costos` ) 
					LEFT JOIN  `bd_recursos`.`cuentas` AS T5
					USING (  `id_cuentas` ) 
					LEFT JOIN  `bd_recursos`.`subcuentas` AS T6
					USING (  `id_subcuentas` ) 
					WHERE  `T1`.`fecha` 
					BETWEEN  '$fecha_i'
					AND  '$fecha_f'";
		
		if($_POST["cmb_cc"] != ""){
			$stm_sql .= " AND id_control_costos = '$_POST[cmb_cc]'";
			//$msg_titulo .= " Área $_POST[cmb_cc]";
		}
		if($_POST["cmb_cuenta"] != ""){
			$stm_sql .= " AND id_cuentas = '$_POST[cmb_cuenta]'";
			//$msg_titulo .= " Familia $_POST[cmb_cuenta]";
		}
		if($_POST["cmb_subcuenta"] != ""){
			$stm_sql .= " AND id_subcuentas = '$_POST[cmb_subcuenta]'";
			//$msg_titulo .= " Equipo $_POST[cmb_subcuenta]";
		}
		
		$stm_sql .= " ORDER BY  `T1`.`fecha` ASC";
		
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
			$flag = 1;
			$total_consumo = 0;
			echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>
					<tr>
						<td class='nombres_columnas'>VER DETALLE</td>
						<td class='nombres_columnas'>PEDIDO</td>
						<td class='nombres_columnas'>PROVEEDOR</td>
						<td class='nombres_columnas'>FECHA</td>
						<td class='nombres_columnas'>SOLICIT&Oacute;</td>
						<td class='nombres_columnas'>REALIZ&Oacute;</td>
						<td class='nombres_columnas'>DEPARTAMENTO</td>
						<td class='nombres_columnas'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TOTAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						<td class='nombres_columnas'>MONEDA</td>
					</tr>";
					echo "<form name='frm_mostrarDetalleRE' method='post' action='frm_reportePedidos.php'>
					<tr>
						<td class='nombres_filas'><input type='checkbox' name='RE' value='todos' 	
						onClick='javascript:document.frm_mostrarDetalleRE.submit();' title='Ver El Detalle de Todos los Pedidos'/></td>
						<td class='nombres_filas' colspan='8' align='left'><img src='../../images/arrow.png' height='20' width='30'> Ver Detalle de Todo</td>	
						<input type='hidden' name='verDetalle' value='si' />
						<input name='fecha_inicial' id='fecha_inicial' type='hidden' value='".modFecha($_POST["txt_fecha_ini"],3)."'/>
						<input name='fecha_final' id='fecha_final' type='hidden' value='".modFecha($_POST["txt_fecha_fin"],3)."'/>
						<input name='select_cc' id='select_cc' type='hidden' value='$_POST[cmb_cc]'/>
						<input name='select_cuenta' id='select_cuenta' type='hidden' value='$_POST[cmb_cuenta]'/>
						<input name='select_subcuenta' id='select_subcuenta' type='hidden' value='$_POST[cmb_subcuenta]'/>
					</tr>";
			
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				//$consumo_aceite = consumoAceite($datos["id_equipo"],modFecha($_POST["txt_fecha_ini"],3),modFecha($_POST["txt_fecha_fin"],3),"3");
				//$rendimiento = rendimientoAceite($datos["id_equipo"],modFecha($_POST["txt_fecha_ini"],3),modFecha($_POST["txt_fecha_fin"],3),"3");
				echo "	
					<tr>	
						<td class='nombres_filas'><input type='checkbox' name='RE' value='$datos[id_pedido]'
						onClick='javascript:document.frm_mostrarDetalleRE.submit();'/></td>
						<td class='$nom_clase'>$datos[id_pedido]</td>
						<td class='$nom_clase'>$datos[razon_social]</td>
						<td class='$nom_clase'>$datos[fecha]</td>
						<td class='$nom_clase'>$datos[solicitor]</td>
						<td class='$nom_clase'>$datos[revisor]</td>
						<td class='$nom_clase'>$datos[depto_solicitor]</td>
						<td class='$nom_clase'>$ ".number_format($datos["total"],2,".",",")."</td>
						<td class='$nom_clase'>$datos[tipo_moneda]</td>
					</tr>";
					$total_consumo += $datos["total"];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo 	"<tr>
						<td colspan='6'></td>
						<td class='nombres_columnas'>TOTAL:</td>
						<td class='nombres_columnas'>$ ".number_format($total_consumo,2,".",",")."</td>
					</tr>";
			echo "</form></table>";
		}
		else{
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Pedidos con esos parametros de busqueda</p>";
		}
		?></div>
		<div id="btns-regpdf">
		<table width="100%">
			<tr>
				<?php if($flag==1) { ?>			
				<td align="center">
					<form action="guardar_reporte.php" method="post">
						<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
						<input name="hdn_nomReporte" type="hidden" value="Reporte de Pedidos" />
						<input name="hdn_origen" type="hidden" value="reporte_pedidos" />		
						<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
						<input name="hdn_fecha_ini" type="hidden" value="<?php echo modFecha($_POST["txt_fecha_ini"],3); ?>" />
						<input name="hdn_fecha_fin" type="hidden" value="<?php echo modFecha($_POST["txt_fecha_fin"],3); ?>" />
						<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
                        onMouseOver="window.estatus='';return true"  />
					</form>
				</td>
				<?php }
				
				/*if($flag==1){ ?>
				<td align="center">
					<?php 
						$datosGrapCompras = array("hdn_consulta"=>$stm_sql, "hdn_msg"=>$msg_grafica);
						$_SESSION['datosGrapCompras'] = $datosGrapCompras;
					?>						
					<input type="button" name="btn_verGrafica" class="botones" value="Ver Gr&aacute;fica" title="Ver Gr&aacute;fica de Compras" 
					onClick="javascript:window.open('verGraficas.php?graph=Compra',
					'_blank','top=0, left=0, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />	
				</td>
				<?php }*/ ?>
				<td align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Pedidos" onclick=
                    "location.href='frm_reportePedidos.php'" />
				</td>	
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
	//Esta función se encarga de mostrar el detalle del Pedido seleccionado
	function mostrarDetalleRE($clave){
		$msg_titulo = "";
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		if ($clave!="todos"){
			$stm_sql = "SELECT T2.`pedido_id_pedido` , T1.`fecha` , T2.`descripcion` AS material, T2.`cantidad_real`, T2.`precio_unitario` , T2.`importe` , T4.`descripcion` AS cc, T5.`descripcion` AS cuenta, T6.`descripcion` AS subcuenta, T2.`equipo`
						FROM  `pedido` AS T1
						JOIN  `detalles_pedido` AS T2 ON T2.`pedido_id_pedido` = T1.`id_pedido` 
						JOIN  `proveedores` AS T3 ON T1.`proveedores_rfc` = T3.`rfc` 
						LEFT JOIN  `bd_recursos`.`control_costos` AS T4
						USING (  `id_control_costos` ) 
						LEFT JOIN  `bd_recursos`.`cuentas` AS T5
						USING (  `id_cuentas` ) 
						LEFT JOIN  `bd_recursos`.`subcuentas` AS T6
						USING (  `id_subcuentas` ) 
						WHERE T2.`pedido_id_pedido` = '$clave'
						ORDER BY  `T1`.`fecha` ASC";
			
			$rs = mysql_query($stm_sql);
			
			if($datos=mysql_fetch_array($rs)){
				$msg_titulo = "DETALLE DEL PEDIDO <em><u>$clave</u></em>";
				echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>DETALLE DEL PEDIDO <em><u>$clave</u></em></caption>			
					<tr>
						<td class='nombres_columnas' align='center'>PEDIDO</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>EQUIPO</td>
						<td class='nombres_columnas' align='center'>MATERIAL</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>COSTO UNITARIO</td>
						<td class='nombres_columnas' align='center'>IMPORTE</td>
						<td class='nombres_columnas' align='center'>CENTRO DE COSTOS</td>
						<td class='nombres_columnas' align='center'>CUENTA</td>
						<td class='nombres_columnas' align='center'>SUBCUENTA</td>
					</tr>
					";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				do{
					echo "<tr>		
							<td class='$nom_clase'>$datos[pedido_id_pedido]</td>
							<td class='$nom_clase'>$datos[fecha]</td>
							<td class='$nom_clase'>$datos[equipo]</td>
							<td class='$nom_clase'>$datos[material]</td>
							<td class='$nom_clase'>$datos[cantidad_real]</td>
							<td class='$nom_clase'>$ ".number_format($datos['precio_unitario'],2,".",",")."</td>
							<td class='$nom_clase'>$ ".number_format($datos['importe'],2,".",",")."</td>
							<td class='$nom_clase'>$datos[cc]</td>
							<td class='$nom_clase'>$datos[cuenta]</td>
							<td class='$nom_clase'>$datos[subcuenta]</td>
						</tr>";									
						
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				echo "</table>";
			}
			?>
			</div><!--Cierre del Layer "reporte"-->
			<div id="btns-regpdf" align="center">
			<table width="100%">
				<tr>	
					<td align="center">
						<form action="frm_reportePedidos.php" method="post" name="frm_reportePedidos">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Reporte Detalle de Pedidos" />
							<input name="hdn_origen" type="hidden" value="reporte_detallepedidos" />		
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input type="hidden" name="txt_fecha_ini" id="txt_fecha_ini" value="<?php echo modFecha($_POST["fecha_inicial"],1); ?>" /> 
							<input type="hidden" name="txt_fecha_fin" id="txt_fecha_fin" value="<?php echo modFecha($_POST["fecha_final"],1); ?>" />
							<input type="hidden" name="cmb_cc" id="cmb_cc" value="<?php echo $_POST["select_cc"]; ?>" />
							<input type="hidden" name="cmb_cuenta" id="cmb_cuenta" value="<?php echo $_POST["select_cuenta"]; ?>" />
							<input type="hidden" name="cmb_subcuenta" id="cmb_subcuenta" value="<?php echo $_POST["select_subcuenta"]; ?>" />
							<input name="btn_exportar" type="button" class="botones" value="Exportar a Excel" title="Exportar el Reporte a Excel" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reportePedidos.action='guardar_reporte.php';document.frm_reportePedidos.submit();"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="sbt_consultarConsumo" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Compras" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reportePedidos.action='frm_reportePedidos.php';"/>
						</form>
					</td>								
				</tr>
			</table>			
			</div>
			<?php
		}
		if ($clave=="todos"){
			$fecha_ini=$_POST["fecha_inicial"];
			$fecha_fin=$_POST["fecha_final"];
	$stm_sql = "SELECT T2.`pedido_id_pedido` , T1.`fecha` , T2.`descripcion`  AS material, T2.`cantidad_real` , T2.`precio_unitario` , T2.`importe` , T4.`descripcion` AS cc, T5.`descripcion` AS cuenta, T6.`descripcion` AS subcuenta, T2.`equipo`
						FROM  `pedido` AS T1
						JOIN  `detalles_pedido` AS T2 ON T2.`pedido_id_pedido` = T1.`id_pedido` 
						JOIN  `proveedores` AS T3 ON T1.`proveedores_rfc` = T3.`rfc` 
						LEFT JOIN  `bd_recursos`.`control_costos` AS T4
						USING (  `id_control_costos` ) 
						LEFT JOIN  `bd_recursos`.`cuentas` AS T5
						USING (  `id_cuentas` ) 
						LEFT JOIN  `bd_recursos`.`subcuentas` AS T6
						USING (  `id_subcuentas` ) 
						WHERE T1.`fecha` BETWEEN '$fecha_ini'
						AND '$fecha_fin'";
			
			if($_POST["select_cc"] != ""){
				$stm_sql .= " AND id_control_costos = '$_POST[select_cc]'";
				//$msg_titulo .= " Área $_POST[cmb_cc]";
			}
			if($_POST["select_cuenta"] != ""){
				$stm_sql .= " AND id_cuentas = '$_POST[select_cuenta]'";
				//$msg_titulo .= " Familia $_POST[cmb_cuenta]";
			}
			if($_POST["select_subcuenta"] != ""){
				$stm_sql .= " AND id_subcuentas = '$_POST[select_subcuenta]'";
				//$msg_titulo .= " Equipo $_POST[cmb_subcuenta]";
			}
			
			$stm_sql .= " ORDER BY  `T1`.`fecha`, T2.`pedido_id_pedido` ASC";
			$msg_titulo="DETALLE DE LOS PEDIDOS DEL <em><u>".modFecha($_POST['fecha_inicial'],1)."</u></em> AL <em><u>".modFecha($_POST['fecha_final'],1)."</u></em>";
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg_titulo</caption>
					<thead>						
					<tr>
						<td class='nombres_columnas' align='center'>PEDIDO</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>EQUIPO</td>
						<td class='nombres_columnas' align='center'>MATERIAL</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>COSTO UNITARIO</td>
						<td class='nombres_columnas' align='center'>IMPORTE</td>
						<td class='nombres_columnas' align='center'>CENTRO DE COSTOS</td>
						<td class='nombres_columnas' align='center'>CUENTA</td>
						<td class='nombres_columnas' align='center'>SUBCUENTA</td>
					</tr>
					</thead>
				";
				$nom_clase = "renglon_gris";
				$cont = 1;
				echo "<tbody>";	
				do{
					
					echo "<tr>		
							<td class='$nom_clase'>$datos[pedido_id_pedido]</td>
							<td class='$nom_clase'>$datos[fecha]</td>
							<td class='$nom_clase'>$datos[equipo]</td>
							<td class='$nom_clase'>$datos[material]</td>
							<td class='$nom_clase'>$datos[cantidad_real]</td>
							<td class='$nom_clase'>$ ".number_format($datos['precio_unitario'],2,".",",")."</td>
							<td class='$nom_clase'>$ ".number_format($datos['importe'],2,".",",")."</td>
							<td class='$nom_clase'>$datos[cc]</td>
							<td class='$nom_clase'>$datos[cuenta]</td>
							<td class='$nom_clase'>$datos[subcuenta]</td>
						</tr>";									
						
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				echo "</tbody>";
				echo "</table>";
			}
			?>
			</div><!--Cierre del Layer "reporte"-->
			<div id="btns-regpdf" align="center">
			<table width="100%">
				<tr>	
					<td align="center">
						<form action="frm_reportePedidos.php" method="post" name="frm_reportePedidos">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Reporte Detalle de Pedidos" />
							<input name="hdn_origen" type="hidden" value="reporte_detallepedidos" />		
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input type="hidden" name="txt_fecha_ini" id="txt_fecha_ini" value="<?php echo modFecha($_POST["fecha_inicial"],1); ?>" /> 
							<input type="hidden" name="txt_fecha_fin" id="txt_fecha_fin" value="<?php echo modFecha($_POST["fecha_final"],1); ?>" />
							<input type="hidden" name="cmb_cc" id="cmb_cc" value="<?php echo $_POST["select_cc"]; ?>" />
							<input type="hidden" name="cmb_cuenta" id="cmb_cuenta" value="<?php echo $_POST["select_cuenta"]; ?>" />
							<input type="hidden" name="cmb_subcuenta" id="cmb_subcuenta" value="<?php echo $_POST["select_subcuenta"]; ?>" />
							<input name="btn_exportar" type="button" class="botones" value="Exportar a Excel" title="Exportar el Reporte a Excel" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reportePedidos.action='guardar_reporte.php';document.frm_reportePedidos.submit();"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="sbt_consultarConsumo" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Compras" 
							onMouseOver="window.estatus='';return true" onclick="document.frm_reportePedidos.action='frm_reportePedidos.php';"/>
						</form>
					</td>								
				</tr>
			</table>			
			</div>
			<?php
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalleRE($clave)
?>
<?php
	/**
	  * Nombre del M�dulo: Compras                                              
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 05/Enero/2010                                      			
	  * Descripci�n: Este archivo contiene funciones para generar el Reporte de Compras 
	  **/
	  
	/*Esta funci�n muestra los pedidos realizados de acuerdo a los parametros seleccionados*/
	function generarReporte($tipo_rpt){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		
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
				$stm_sql = "SELECT id_pedido,fecha,fecha_pago,fecha_entrega,pedido.estado,razon_social,total,tipo_moneda FROM pedido JOIN proveedores ON proveedores_rfc=rfc 
							WHERE razon_social = '$_POST[txt_razon]' AND fecha>='$f1' AND fecha<='$f2'";
				
				//Mensaje para desplegar en el titulo de la tabla y de la Grafica
				$msg_titulo = "	Reporte de Compras Correspondiente al Proveedor <em><u>$_POST[txt_razon]</u></em> 
								<br>En el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
				$msg_grafica = "Compras al Proveedor $_POST[txt_razon]";
				
				//Crear el Mensaje en caso de que la consulta no arroje ning�n resultado
				$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Resultado con: <em><u>$_POST[txt_razon]</u></em>
								<br>En las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
												
				//Definir datos del reporte de compras en la SESSION
				$datosCompras = array("txt_razon"=>$_POST['txt_razon'],"txt_fechaIni"=>$_POST['txt_fechaIni'],"txt_fechaFin"=>$_POST['txt_fechaFin']);
				$_SESSION['datosRptCompras'] = $datosCompras;
			break;
			case 2:
				//Quitar la coma en el costo mayor y menor, para poder realziar la operaciones requeridas.
				$costo_menor = str_replace(",","",$_POST['txt_nivelInf']);
				$costo_mayor = str_replace(",","",$_POST['txt_nivelSup']);
				
				//Crear la consulta 
				$stm_sql = "SELECT id_pedido,fecha,fecha_pago,fecha_entrega,pedido.estado,razon_social,total,tipo_moneda FROM pedido JOIN proveedores ON proveedores_rfc=rfc 
							WHERE total>=$costo_menor AND total<=$costo_mayor";
				
				//Mensaje para desplegar en el titulo de la tabla y de la Grafica
				$msg_titulo = "Reporte de Compras Correspondiente al Rango de <em><u>$ $_POST[txt_nivelInf]</u></em> y <em><u>$ $_POST[txt_nivelSup]</u></em>";
				//$msg_grafica = "Compras Entre $ $_POST[txt_nivelInf] y $ $_POST[txt_nivelSup]";
				$msg_grafica = "Compras Entre $ $costo_menor y $ $costo_mayor";
				
				//Crear el Mensaje en caso de que la consulta no arroje ning�n resultado
				$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado con el Rango de Cantidades 
								<em><u>$ $costo_menor</u></em> y <em><u>$ $costo_mayor</u></em></label>";								
								
				//Definir datos del reporte de compras en la SESSION
				$datosCompras = array("txt_nivelInf"=>$_POST['txt_nivelInf'],"txt_nivelSup"=>$_POST['txt_nivelSup']);
				$_SESSION['datosRptCompras'] = $datosCompras;
			break;
			case 3:
				//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
				$f1 = modFecha($_POST['txt_fechaIni'],3);
				$f2 = modFecha($_POST['txt_fechaFin'],3);
				//Crear la consulta 
				$stm_sql = "SELECT id_pedido,fecha,fecha_pago,fecha_entrega,pedido.estado,razon_social,total,tipo_moneda FROM pedido JOIN proveedores ON proveedores_rfc=rfc 
							WHERE fecha>='$f1' AND fecha<='$f2'";
				
				//Mensaje para desplegar en el titulo de la tabla y de la Grafica
				$msg_titulo = "Reporte de Compras Correspondiente al Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
				$msg_grafica = "Compras del $_POST[txt_fechaIni] al $_POST[txt_fechaFin]";
				
				//Crear el Mensaje en caso de que la consulta no arroje ning�n resultado
				$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado Entre las Fechas del <em><u>$_POST[txt_fechaIni]
				</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";				
								
				//Definir datos del reporte de compras en la SESSION
				$datosCompras = array("txt_fechaIni"=>$_POST['txt_fechaIni'],"txt_fechaFin"=>$_POST['txt_fechaFin']);
				$_SESSION['datosRptCompras'] = $datosCompras;
			break;
			case 4:
				$depto = $_POST['cmb_departamento'];
				//Crear la consulta 
				$stm_sql = "SELECT id_pedido,fecha,fecha_pago,fecha_entrega,pedido.estado,razon_social,total,tipo_moneda FROM pedido JOIN proveedores ON proveedores_rfc=rfc
							WHERE depto_solicitor='$depto'";
				
				//Mensaje para desplegar en el titulo de la tabla y de la Grafica
				$msg_titulo = "Reporte de Compras Correspondiente al Departamento de <em><u>$depto</u></em>";
				$msg_grafica = "Compras del Departamento de $depto";
				
				//Crear el Mensaje en caso de que la consulta no arroje ning�n resultado
				$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado con el Departamento: <em><u>$depto</u></em>";																								
				
				//Definir datos del reporte de compras en la SESSION
				$datosCompras = array("cmb_departamento"=>$_POST['cmb_departamento']);
				$_SESSION['datosRptCompras'] = $datosCompras;
			break;
			case 5:
				$equipo = $_POST['cmb_equipos'];
				//Crear la consulta 
				$stm_sql = "SELECT id_pedido,fecha,fecha_pago,fecha_entrega,pedido.estado,razon_social,importe AS total,tipo_moneda FROM pedido JOIN proveedores ON proveedores_rfc=rfc
							JOIN detalles_pedido ON id_pedido=pedido_id_pedido WHERE equipo='$equipo'";
				//Mensaje para desplegar en el titulo de la tabla y de la Grafica
				$msg_titulo = "Reporte de Compras Correspondiente al Equipo <em><u>$equipo</u></em>";
				$msg_grafica = "Compras del Equipo $equipo";
				//Crear el Mensaje en caso de que la consulta no arroje ning�n resultado
				$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado con el Equipo: <em><u>$equipo</u></em>";																								
				//Definir datos del reporte de compras en la SESSION
				$datosCompras = array("cmb_equipo"=>$_POST['cmb_equipos']);
				$_SESSION['datosRptCompras'] = $datosCompras;
			break;
			case 6:
				$familia=$_POST["cmb_familia"];
				//Crear la consulta 
				$stm_sql = "SELECT id_pedido,fecha,fecha_pago,fecha_entrega,pedido.estado,razon_social,importe AS total,tipo_moneda FROM pedido JOIN proveedores ON proveedores_rfc=rfc 
							JOIN detalles_pedido ON id_pedido=pedido_id_pedido JOIN bd_mantenimiento.equipos ON bd_mantenimiento.equipos.id_equipo=equipo 
							WHERE bd_mantenimiento.equipos.familia='$familia'";
				//Mensaje para desplegar en el titulo de la tabla y de la Grafica
				$msg_titulo = "Reporte de Compras Correspondiente a la Familia <em><u>$familia</u></em>";
				$msg_grafica = "Compras de la Familia $familia";
				//Crear el Mensaje en caso de que la consulta no arroje ning�n resultado
				$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado con la Familia: <em><u>$familia</u></em>";																								
				//Definir datos del reporte de compras en la SESSION
				$datosCompras = array("cmb_familia"=>$_POST['cmb_familia'],"ckb_todo"=>$_POST["ckb_todo"]);
				$_SESSION['datosRptCompras'] = $datosCompras;
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
						<td class='nombres_columnas'>FECHA CREACION</td>
						<td class='nombres_columnas'>FECHA PAGO</td>
						<td class='nombres_columnas'>FECHA ENTREGA</td>
						<td class='nombres_columnas'>ESTADO PAGO</td>
						<td class='nombres_columnas'>PROVEEDOR</td>
						<td class='nombres_columnas'>TIPO MONEDA</td>
						<td class='nombres_columnas'>TOTAL</td>
					</tr>
					<form name='frm_mostrarDetalleRC' method='post' action='frm_reporteCompras.php'>
					<input type='hidden' name='verDetalle' value='si' />
					<input type='hidden' name='no_reporte' value='$tipo_rpt'/>
					<tr>
						<td class='nombres_filas'><input type='checkbox' name='RC' value='todos' 	
						onClick='javascript:document.frm_mostrarDetalleRC.submit();' title='Ver El Detalle de Todos los Pedidos'/></td>
						<td class='nombres_filas' colspan='8' align='left'><img src='../../images/arrow.png' height='20' width='30'> Ver Detalle de Todo</td>	
					</tr>
					";
					if (isset($_POST["txt_razon"]))
						echo "<input type='hidden' name='hdn_rsocial' value='$_POST[txt_razon]'/>";
					if (isset($_POST["cmb_departamento"]))
						echo "<input type='hidden' name='hdn_depto' value='$_POST[cmb_departamento]'/>";
					if (isset($_POST["cmb_familia"]))
						echo "<input type='hidden' name='hdn_familia' value='$_POST[cmb_familia]'/>";
					if (!isset($_POST["ckb_todo"]) && isset($_POST["cmb_equipos"]))
						echo "<input type='hidden' name='hdn_equipo' value='$_POST[cmb_equipos]'/>";
					if (isset($_POST["txt_fechaIni"]) && isset($_POST["txt_fechaFin"])){
						echo "
							<input type='hidden' name='hdn_fechaI' value='$_POST[txt_fechaIni]'/>
							<input type='hidden' name='hdn_fechaF' value='$_POST[txt_fechaFin]'/>
						";
					}
					if (isset($_POST["txt_nivelInf"]) && isset($_POST["txt_nivelSup"])){
						echo "
							<input type='hidden' name='hdn_costoMenor' value='$costo_menor'/>
							<input type='hidden' name='hdn_costoMayor' value='$costo_mayor'/>
						";
					}
			$nom_clase = "renglon_gris";
			$cont = 1;	
			//Cantidad Total Pesos
			$cant_totalP = 0;
			//Cantidad Total Dolares
			$cant_totalD = 0;
			//Cantidad Total de Pesos y Dolares CANCELADOS
			$cant_totalPCancel = 0;
			$cant_totalDCancel = 0;
			do{
				$fecha_pago='NO PAGADO';
				$fecha_entrega='NO ENTREGADO';
				if($datos['fecha_pago']!="")
					$fecha_pago=modFecha($datos['fecha_pago'],1);									
				if($datos['fecha_entrega']!="")
					$fecha_entrega=modFecha($datos['fecha_entrega'],1);
				
				echo "	
					<tr>	
						<td class='nombres_filas'><input type='checkbox' name='RC' value='$datos[id_pedido]'
						onClick='javascript:document.frm_mostrarDetalleRC.submit();'/></td>
						<td class='$nom_clase'>$datos[id_pedido]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
						<td class='$nom_clase'>".$fecha_pago."</td>
						<td class='$nom_clase'>".$fecha_entrega."</td>
						<td class='$nom_clase'>$datos[estado]</td>
						<td class='$nom_clase' align='left'>$datos[razon_social]</td>
						<td class='$nom_clase'>$datos[tipo_moneda]</td>
						<td class='$nom_clase'>$".number_format($datos['total'],2,".",",")."</td>
					</tr>";
				if($datos["estado"]!="CANCELADO"){
					if($datos["tipo_moneda"]=="PESOS")
						$cant_totalP += $datos['total'];
					else
						$cant_totalD += $datos['total'];
				}
				else{
					if($datos["tipo_moneda"]=="PESOS")
						$cant_totalPCancel += $datos['total'];
					else
						$cant_totalDCancel += $datos['total'];
				}
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
						
			}while($datos=mysql_fetch_array($rs));
			echo "	</form>
					<tr><td colspan='7'></td><td class='nombres_columnas' align='center'>TOTAL M.N.</td><td class='nombres_columnas'>$".number_format($cant_totalP,2,".",",")."</td></tr>
					<tr><td colspan='7'></td><td class='nombres_columnas' align='center'>TOTAL USD</td><td class='nombres_columnas'>$".number_format($cant_totalD,2,".",",")."</td></tr>
					<tr><td colspan='7'></td><td class='nombres_columnas' align='center'>TOTAL CANCELADO M.N.</td><td class='nombres_columnas'>$".number_format($cant_totalPCancel,2,".",",")."</td></tr>
					<tr><td colspan='7'></td><td class='nombres_columnas' align='center'>TOTAL CANCELADO USD</td><td class='nombres_columnas'>$".number_format($cant_totalDCancel,2,".",",")."</td></tr>
			</table>";
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<br><br><br><br><br><br><br><br><br><br>".$msg_error;?>			
		</div><!--Cierre del Layer "reporte"-->				            
		
		
		<div id="btns-regpdf">
		<table width="100%">
			<tr>
				<td align="center">
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Compras" onclick=
                    "location.href='frm_reporteCompras.php'" />
				</td>				
				<?php if($flag==1) { ?>			
				<td align="center">
					<form action="guardar_reporte.php" method="post">
						<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
						<input name="hdn_nomReporte" type="hidden" value="Consulta de Compras" />
						<input name="hdn_origen" type="hidden" value="compras" />		
						<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />							
						<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
                        onMouseOver="window.estatus='';return true"  />
					</form>
				</td>
				<?php }
				if($flag==1){ ?>
				<td align="center">
					<?php 
						$datosGrapCompras = array("hdn_consulta"=>$stm_sql, "hdn_msg"=>$msg_grafica);
						$_SESSION['datosGrapCompras'] = $datosGrapCompras;
					?>						
					<input type="button" name="btn_verGrafica" class="botones" value="Ver Gr&aacute;fica" title="Ver Gr&aacute;fica de Compras" 
					onClick="javascript:window.open('verGraficas.php?graph=Compra',
					'_blank','top=0, left=0, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />	
				</td>
				<?php } ?>
			</tr>
		</table>			
		</div><?php
										
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion generarReporte($tipo_rpt)
	
	
	//Esta funci�n se encarga de mostrar el detalle del Pedido seleccionado
	function mostrarDetalleRC($clave,$no_reporte){
		?><div id="reporte" class="borde_seccion2" align="center"><?php
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		if ($clave!="todos"){
			$stm_sql = "SELECT * FROM detalles_pedido WHERE pedido_id_pedido = '$clave'";
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){						
				echo "								
				<table cellpadding='5'>
					<caption class='titulo_etiqueta'>DETALLE DEL PEDIDO <em><u>$clave</u></em></caption>			
					<tr>
						<td class='nombres_columnas'>NO.</td>
						<td class='nombres_columnas'>UNIDAD</td>
						<td class='nombres_columnas'>CANTIDAD</td>
						<td class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas'>EQUIPO</td>
						<td class='nombres_columnas'>PRECIO UNITARIO</td>
						<td class='nombres_columnas'>IMPORTE</td>
						<td class='nombres_columnas'>CONVENIO</td>
						<td class='nombres_columnas'>NO CONVENIO</td>
					</tr>
					";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				do{
					$equipo="N/D";
					if ($datos["equipo"]!="")
						$equipo=$datos["equipo"];
					echo "<tr>		
							<td class='nombres_filas'>$datos[partida]</td>
							<td class='$nom_clase'>$datos[unidad]</td>
							<td class='$nom_clase'>$datos[cantidad_real]</td>
							<td class='$nom_clase' align='left'>$datos[descripcion]</td>
							<td class='$nom_clase' align='center'>$equipo</td>
							<td class='$nom_clase'>$ ".number_format($datos['precio_unitario'],2,".",",")."</td>
							<td class='$nom_clase'>$ ".number_format($datos['importe'],2,".",",")."</td>
							<td class='$nom_clase'>$datos[articulo_convenio]</td>
							<td class='$nom_clase'>$datos[id_convenio]</td>
						</tr>";									
						
					//Determinar el color del siguiente renglon a dibujar
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
							<form action="frm_reporteCompras.php" method="post">
								<input name="hdn_tipoRpt" type="hidden" value="<?php echo $no_reporte; ?>" />							
								<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Compras" 
								onMouseOver="window.estatus='';return true"  />
							</form>
						</td>								
					</tr>
				</table>			
				</div><?php
			}				
		}
		if ($clave=="todos"){
			if ($no_reporte==1){
				$fecha_ini=modFecha($_POST["hdn_fechaI"],3);
				$fecha_fin=modFecha($_POST["hdn_fechaF"],3);
				$razon_soc=$_POST["hdn_rsocial"];
				$stm_sql = "SELECT id_pedido,razon_social,requisiciones_id_requisicion,fecha,solicitor,unidad,cantidad,descripcion,precio_unitario,importe,equipo,tipo_moneda,cantidad_real,comentarios,cond_pago
							FROM pedido JOIN proveedores ON proveedores_rfc=rfc JOIN detalles_pedido ON pedido_id_pedido=id_pedido
							WHERE razon_social = '$razon_soc' AND fecha>='$fecha_ini' AND fecha<='$fecha_fin' AND pedido.estado != 'CANCELADO' ORDER BY fecha";
				$msg="DETALLE DE LOS PEDIDOS DE <em><u>$razon_soc</u></em> DEL <em><u>$_POST[hdn_fechaI]</u></em> AL <em><u>$_POST[hdn_fechaF]</u></em>";
			}
			if ($no_reporte==2){
				$costoInf=$_POST["hdn_costoMenor"];
				$costoSup=$_POST["hdn_costoMayor"];
				$stm_sql = "SELECT id_pedido,razon_social,requisiciones_id_requisicion,fecha,solicitor,unidad,cantidad,descripcion,precio_unitario,importe,equipo,tipo_moneda,cantidad_real,comentarios,cond_pago
							FROM pedido JOIN proveedores ON proveedores_rfc=rfc JOIN detalles_pedido ON pedido_id_pedido=id_pedido
							WHERE total>='$costoInf' AND total<='$costoSup' AND pedido.estado != 'CANCELADO' ORDER BY fecha";
				$costoInf=number_format($costoInf,2,".",",");
				$costoSup=number_format($costoSup,2,".",",");
				$msg="DETALLE DE LOS PEDIDOS DESDE <em><u>$ $costoInf</u></em> HASTA <em><u>$ $costoSup</u></em>";
			}
			if ($no_reporte==3){
				$fecha_ini=modFecha($_POST["hdn_fechaI"],3);
				$fecha_fin=modFecha($_POST["hdn_fechaF"],3);
				$stm_sql = "SELECT id_pedido,razon_social,requisiciones_id_requisicion,fecha,solicitor,unidad,cantidad,descripcion,precio_unitario,importe,equipo,tipo_moneda,cantidad_real,comentarios,cond_pago
							FROM pedido JOIN proveedores ON proveedores_rfc=rfc JOIN detalles_pedido ON pedido_id_pedido=id_pedido
							WHERE fecha>='$fecha_ini' AND fecha<='$fecha_fin' AND pedido.estado != 'CANCELADO' ORDER BY fecha";
				$msg="DETALLE DE LOS PEDIDOS DEL <em><u>$_POST[hdn_fechaI]</u></em> AL <em><u>$_POST[hdn_fechaF]</u></em>";
			}
			if ($no_reporte==4){
				$depto=$_POST["hdn_depto"];
				$stm_sql = "SELECT id_pedido,razon_social,requisiciones_id_requisicion,fecha,solicitor,unidad,cantidad,descripcion,precio_unitario,importe,equipo,tipo_moneda,cantidad_real,comentarios,cond_pago
							FROM pedido JOIN proveedores ON proveedores_rfc=rfc JOIN detalles_pedido ON pedido_id_pedido=id_pedido
							WHERE depto_solicitor='$depto' AND pedido.estado != 'CANCELADO' ORDER BY fecha";
				$msg="DETALLE DE LOS PEDIDOS DEL DEPARTAMENTO <em><u>$depto</u></em>";
			}
			if ($no_reporte==5){
				$equipo=$_POST["hdn_equipo"];
				$stm_sql = "SELECT id_pedido,razon_social,requisiciones_id_requisicion,fecha,solicitor,unidad,cantidad,descripcion,precio_unitario,importe,equipo,tipo_moneda,cantidad_real,comentarios,cond_pago
							FROM pedido JOIN proveedores ON proveedores_rfc=rfc JOIN detalles_pedido ON pedido_id_pedido=id_pedido
							WHERE equipo='$equipo' AND pedido.estado != 'CANCELADO' ORDER BY fecha";
				$msg="DETALLE DE LOS PEDIDOS DEL EQUIPO <em><u>$equipo</u></em>";
			}
			if ($no_reporte==6){
				$familia=$_POST["hdn_familia"];
				$stm_sql = "SELECT id_pedido,razon_social,requisiciones_id_requisicion,fecha,solicitor,unidad,cantidad,detalles_pedido.descripcion,
							detalles_pedido.precio_unitario,detalles_pedido.importe,detalles_pedido.equipo,tipo_moneda,cantidad_real,comentarios,cond_pago
							FROM pedido JOIN proveedores ON proveedores_rfc=rfc JOIN detalles_pedido ON pedido_id_pedido=id_pedido 
							JOIN bd_mantenimiento.equipos ON bd_mantenimiento.equipos.id_equipo=equipo WHERE bd_mantenimiento.equipos.familia='$familia' AND pedido.estado != 'CANCELADO' ORDER BY fecha";
				$msg="DETALLE DE LOS PEDIDOS DE LA FAMILIA <em><u>$familia</u></em>";
			}
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){						
				echo "								
				<table cellpadding='5' id='tabla-detallePedido' width='120%'>
					<caption class='titulo_etiqueta'>$msg</caption>
					<thead>						
					<tr>
						<th class='nombres_columnas'>ID PEDIDO</th>
						<th class='nombres_columnas'>PROVEEDOR</th>
						<td class='nombres_columnas'>REQUISICI&Oacute;N</td>
						<td class='nombres_columnas'>FECHA</td>
						<td class='nombres_columnas'>SOLICITANTE</td>
						<td class='nombres_columnas'>UNIDAD</td>
						<td class='nombres_columnas'>CANTIDAD</td>
						<td class='nombres_columnas' width='10%'>PRECIO UNITARIO</td>
						<td class='nombres_columnas' width='10%'>IMPORTE</td>
						<td class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas'>EQUIPO</td>
						<td class='nombres_columnas'>COMENTARIOS</td>
						<td class='nombres_columnas'>FORMA DE PAGO</td>
					</tr>
					</thead>
				";
				$nom_clase = "renglon_gris";
				$cont = 1;
				echo "<tbody>";	
				do{
					$equipo="N/D";
					if ($datos["equipo"]!="")
						$equipo=$datos["equipo"];
					//Identificar el Tipo de Moneda para resaltarlo segun corresponda
					//Ademas de asignar el prefijo de la moneda empleada
					if ($datos["tipo_moneda"]=="PESOS"){
						$tipoMoneda="M.N.";
						$colorFondo="#FFEB9C";
						$colorLetra="#9C6500";
					}
					else{
						$tipoMoneda="USD";
						$colorFondo="#C6EFCE";
						$colorLetra="#006100";
					}
					echo "<tr>		
							<td class='nombres_filas'>$datos[id_pedido]</td>
							<td class='$nom_clase'>$datos[razon_social]</td>
							<td class='$nom_clase'>$datos[requisiciones_id_requisicion]</td>
							<td class='$nom_clase' align='left'>".modFecha($datos["fecha"],1)."</td>
							<td class='$nom_clase'>$datos[solicitor]</td>
							<td class='$nom_clase'>$datos[unidad]</td>
							<td class='$nom_clase'>$datos[cantidad_real]</td>
							<td class='$nom_clase' style='background-color:$colorFondo;color:$colorLetra'><strong>$tipoMoneda $".number_format($datos["precio_unitario"],2,".",",")."</strong></td>
							<td class='$nom_clase' style='background-color:$colorFondo;color:$colorLetra'><strong>$tipoMoneda $".number_format($datos["importe"],2,".",",")."</strong></td>
							<td class='$nom_clase'>$datos[descripcion]</td>
							<td class='$nom_clase' align='center'>$equipo</td>
							<td class='$nom_clase' align='center'>$datos[comentarios]</td>
							<td class='$nom_clase' align='center'>$datos[cond_pago]</td>
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
				?>
				</div><!--Cierre del Layer "reporte"-->				            
				
				
				<div id="btns-regpdf" align="center">
				<table width="100%">
					<tr>	
						<td align="center">
							<form action="frm_reporteCompras.php" method="post" name="frm_reporteCompras">
								<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"/>
								<input name="hdn_origen" type="hidden" value="detalle_compras"  />	
								<input name="hdn_nomReporte" type="hidden" value="Detalle Compras"/>
								<input name="hdn_msg" type="hidden" value="<?php echo $msg; ?>"  />	
								<input name="hdn_tipoRpt" type="hidden" value="<?php echo $no_reporte; ?>" />							
								<input name="btn_exportar" type="button" class="botones" value="Exportar a Excel" title="Exportar el Reporte a Excel" 
								onMouseOver="window.estatus='';return true" onclick="document.frm_reporteCompras.action='guardar_reporte.php';document.frm_reporteCompras.submit();"/>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla del Reporte de Compras" 
								onMouseOver="window.estatus='';return true" onclick="document.frm_reporteCompras.action='frm_reporteCompras.php';"/>
							</form>
						</td>								
					</tr>
				</table>			
				</div><?php
			}
		
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	mostrarDetalleRC($clave,$no_reporte)				
	
?>
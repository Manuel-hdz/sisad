<?php
	/*Esta funcion genera la Clave de recibidos de acuerdo a los registros en la BD*/
	function obtenerIdRecibidos(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Variable para almacenar el ID de Recibidos
		$id_cadena = "";
			
		//Iniciar a crear la clave
		$id_cadena = "REC";
			
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Orden de Trabajo Reciente acorde a la fecha
		$stm_sql = "SELECT MAX( CAST( SUBSTR( id_recibidos, 8 ) AS UNSIGNED ) ) AS cant
					FROM pedidos_recibidos
					WHERE id_recibidos LIKE  'REC$mes$anio%'";
		//Ejecutar la Sentencia
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la orden de trabajo registrada en la BD y sumarle 1
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "00".$cant;
			if($cant>99 && $cant<1000)
				$id_cadena .= "0".$cant;
			if($cant>=1000)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		
		return $id_cadena;
	}
	
	function recibirPedidos(){
		$id_rec = obtenerIdRecibidos();
		$conn = conecta("bd_compras");
		
		$id_ped = strtoupper($_POST['txt_pedido']);
		$factura = $_POST['txt_factura'];
		$factura_recibida = $_POST['txt_factura_rec'];
		$forma_pago = $_POST['cmb_formaPago'];
		$fechaP = modFecha($_POST['txt_fechaP'],3);
		$cantidad = str_replace(",","",$_POST['txt_costoTotal']);
		
		$stm_sql = "INSERT INTO pedidos_recibidos (id_recibidos, id_pedido, factura, forma_pago, fecha_pago, cantidad, factura_recibida) VALUES ('$id_rec','$id_ped','$factura','$forma_pago','$fechaP',$cantidad,'$factura_recibida')";
		$rs = mysql_query($stm_sql);
		if($rs){
			mysql_close($conn);
			registrarOperacion("bd_compras",$id_rec,"RecibirPedidos",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout(function(){ alert("El registro se realizo correctamente"); }, 1000);
			</script>
			<?php
		} else {
				
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error								
			$error = mysql_error();
			//Cerrar la Conexion con la BD
			mysql_close($conn);
			?>
			<script>
				setTimeout(function(){ alert("Hubo problemas al momento de realizar el registro"); }, 1000);
			</script>
			<?php
		}
	}
	
	function mostrarPedidos(){
		$conn=conecta("bd_compras");
		if(isset($_POST["txt_pedido"])){
			$tipo = 1;
			$pedido = strtoupper($_POST["txt_pedido"]);
			if(substr($pedido,0,3) == "PED"){
				$stm_sql = "SELECT T1.id_pedido, T1.proveedores_rfc, T2.razon_social, T3.no_factura, T3.id_entrada, T1.cond_pago, T1.fecha, T1.subtotal, T1.iva, T1.total, T1.tipo_moneda, T1.revisor, T1.estado, T4.fecha_pago, T3.fecha_entrada, T4.factura_recibida
							FROM  `pedido` AS T1
							JOIN proveedores AS T2 ON T1.proveedores_rfc = T2.rfc
							JOIN bd_almacen.entradas AS T3 ON T3.requisiciones_id_requisicion = T1.id_pedido
							LEFT JOIN pedidos_recibidos AS T4 ON T4.id_pedido = T1.id_pedido
							AND T4.factura = T3.no_factura
							WHERE T1.id_pedido =  '$pedido'";
			} else {
				$stm_sql = "SELECT T1.id_orden AS id_pedido, T2.rfc AS proveedores_rfc, T3.proveedor AS razon_social, T3.no_factura, T3.id_entrada, T1.clasificacion AS cond_pago, T1.fecha_entrega AS fecha, ROUND( (
							T1.costo_total / 1.16
							), 2 ) AS subtotal, ROUND( T1.costo_total - ( T1.costo_total / 1.16 ) , 2 ) AS iva, T1.costo_total AS total, T1.moneda AS tipo_moneda, T1.encargado_compras AS revisor,  'NO PAGADO' AS estado, T4.fecha_pago, T3.fecha_entrada, T4.factura_recibida
							FROM bd_mantenimiento.orden_servicios_externos AS T1
							JOIN bd_almacen.entradas AS T3 ON T3.comp_directa = T1.id_orden
							LEFT JOIN bd_compras.proveedores AS T2 ON T2.razon_social = T3.proveedor
							LEFT JOIN bd_compras.pedidos_recibidos AS T4 ON T4.id_pedido = T1.id_orden
							AND T4.factura = T3.no_factura
							WHERE id_orden = '$pedido' 
							AND complementada =  'SI'";
			}
		} else if(isset($_POST["txt_fechaIni"])){
			$tipo = 2;
			$fini = modFecha($_POST["txt_fechaIni"],3);
			$ffin = modFecha($_POST["txt_fechaFin"],3);
			$stm_sql = "SELECT T1.id_pedido, T1.proveedores_rfc, T2.razon_social, T3.no_factura, T3.id_entrada, T1.cond_pago, T1.fecha, T1.subtotal, T1.iva, T1.total, T1.tipo_moneda, T1.revisor, T1.estado, T4.fecha_pago, T3.fecha_entrada, T4.factura_recibida
						FROM  `pedido` AS T1
						JOIN proveedores AS T2 ON T1.proveedores_rfc = T2.rfc
						JOIN bd_almacen.entradas AS T3 ON T3.requisiciones_id_requisicion = T1.id_pedido
						LEFT JOIN pedidos_recibidos AS T4 ON T4.id_pedido = T1.id_pedido
						AND T4.factura = T3.no_factura
						WHERE T1.fecha
						BETWEEN  '$fini'
						AND  '$ffin'";
			
			$stm_sql2 = "SELECT T1.id_orden AS id_pedido, T2.rfc AS proveedores_rfc, T3.proveedor AS razon_social, T3.no_factura, T3.id_entrada, T1.clasificacion AS cond_pago, T1.fecha_entrega AS fecha, ROUND( (
						T1.costo_total / 1.16
						), 2 ) AS subtotal, ROUND( T1.costo_total - ( T1.costo_total / 1.16 ) , 2 ) AS iva, T1.costo_total AS total, T1.moneda AS tipo_moneda, T1.encargado_compras AS revisor,  'NO PAGADO' AS estado, T4.fecha_pago, T3.fecha_entrada, T4.factura_recibida
						FROM bd_mantenimiento.orden_servicios_externos AS T1
						JOIN bd_almacen.entradas AS T3 ON T3.comp_directa = T1.id_orden
						LEFT JOIN bd_compras.proveedores AS T2 ON T2.razon_social = T3.proveedor
						LEFT JOIN bd_compras.pedidos_recibidos AS T4 ON T4.id_pedido = T1.id_orden
						AND T4.factura = T3.no_factura
						WHERE fecha_entrega
						BETWEEN  '$fini'
						AND  '$ffin'
						AND complementada =  'SI'";
		}
		$cont = 1;
		for($i=0; $i<$tipo; $i++){
			if($i==0){
				$rs = mysql_query($stm_sql);
			} else {
				$rs = mysql_query($stm_sql2);
			}
			if($datos = mysql_fetch_array($rs)){
				echo 
				"<table cellpadding='5' width='100%' align='center' id='tabla-resultadosPedidos' style='table-layout:fixed;'>";
				
				echo "
					<thead>
						<tr>
							<th class='nombres_columnas' align='center' width=90px>SELECCIONAR</th>
							<th class='nombres_columnas' align='center' width=90px>N° PEDIDO</th>
							<th class='nombres_columnas' align='center' width=120px>RFC PROVEEDOR</th>
							<th class='nombres_columnas' align='center' width=300px>PROVEEDOR</th>
							<th class='nombres_columnas' align='center' width=120px>FACTURA/REMISION ALMAC&Eacute;N</th>
							<th class='nombres_columnas' align='center' width=100px>FACTURA RECIBIDA</th>
							<th class='nombres_columnas' align='center' width=100px>CONDICIONES PAGO</th>
							<th class='nombres_columnas' align='center' width=100px>SUBTOTAL</th>
							<th class='nombres_columnas' align='center' width=100px>IVA</th>
							<th class='nombres_columnas' align='center' width=100px>TOTAL</th>
							<th class='nombres_columnas' align='center' width=70px>MONEDA</th>
							<th class='nombres_columnas' align='center' width=210px>REVIS&Oacute;</th>
							<th class='nombres_columnas' align='center' width=80px>ESTADO</th>
							<th class='nombres_columnas' align='center' width=160px>FECHA PEDIDO</th>
							<th class='nombres_columnas' align='center' width=160px>FECHA ENTREGA</th>
							<th class='nombres_columnas' align='center' width=160px>FECHA RECIBIDO</th>
						</tr>
					</thead>";
				$nom_clase = "renglon_gris";
				echo 
					"<tbody>";	
				do{
					$estado = estadoRecibidos($datos["id_pedido"],$datos["no_factura"]);
					if($datos["estado"] == "CANCELADO"){
						$color = "#FF0000";
						$estado = "CANCELADO";
					}
					else if($estado == "RECIBIDO"){
						$color = "#00FF88";
					}
					else
						$color = "";
					?>
					<form id="frm_pedidos<?php echo $cont; ?>" name="frm_pedidos<?php echo $cont; ?>" method="post" action="frm_complementarPedidoFacturas.php">
					<?php
					echo "	
					<tr>
						<td class='$nom_clase' align='center' style='background-color:$color;'>";
						if($datos["estado"] != "CANCELADO" && $estado != "RECIBIDO"){
						?>
							<input type="radio" name="rdb_idPedido" id="rdb_idPedido<?php echo $cont;?>" value="<?php echo $datos["id_pedido"]?>" onclick="document.getElementById('frm_pedidos<?php echo $cont; ?>').submit();"/>
						<?php 
						echo $color;
						}
						echo "</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[id_pedido]</td>					
						<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[proveedores_rfc]</td>					
						<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[razon_social]</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[no_factura]</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[factura_recibida]</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[cond_pago]</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$".number_format($datos["subtotal"],2,".",",")."</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$".number_format($datos["iva"],2,".",",")."</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$".number_format($datos["total"],2,".",",")."</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[tipo_moneda]</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$datos[revisor]</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>$estado</td>
						<td class='$nom_clase' align='center' style='background-color:$color;'>".modFecha($datos["fecha"],2)."</td>";
						if ($datos["fecha_entrada"]!="")
							echo"<td class='$nom_clase' align='center' style='background-color:$color;'>".modFecha($datos["fecha_entrada"],2)."</td>";
						else
							echo"<td class='$nom_clase' align='center' style='background-color:$color;'>".$datos["fecha_entrada"]."</td>";
						
						if ($datos["fecha_pago"]!="")
							echo"<td class='$nom_clase' align='center' style='background-color:$color;'>".modFecha($datos["fecha_pago"],2)."</td>";
						else
							echo"<td class='$nom_clase' align='center' style='background-color:$color;'>".$datos["fecha_pago"]."</td>";
						echo"<input type='hidden' id='txt_factura' name='txt_factura' value='$datos[no_factura]' />";
						echo"<input type='hidden' id='txt_idEntrada' name='txt_idEntrada' value='$datos[id_entrada]' />";
								
					echo 
					"</tr>";
					?>
					</form>
					<?php
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
						
				}while($datos=mysql_fetch_array($rs));
				echo 
					"</tbody>
				</table>";
			}
		}
	}
	
	function estadoRecibidos($pedido, $entrada){
		$estado = "NO RECIBIDO";
		$conn = conecta("bd_compras");
		$stm_sql = "SELECT * FROM pedidos_recibidos WHERE id_pedido='$pedido' AND factura='$entrada'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if(mysql_fetch_array($rs))
				$estado = "RECIBIDO";
		}
		return $estado;
	}
	
	function obtenerDatoEntrada($columna,$factura,$pedido){
		$valor = "";
		$conn = conecta("bd_almacen");
		if(substr($pedido,0,3) == "PED")
			$stm_sql = "SELECT $columna FROM entradas WHERE no_factura = '$factura' AND requisiciones_id_requisicion = '$pedido'";
		else
			$stm_sql = "SELECT $columna FROM entradas WHERE no_factura = '$factura' AND comp_directa = '$pedido'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$valor = $datos[0];
			}
		}
		return $valor;
	}
	
	function tieneIVA($pedido){
		$valor = false;
		$conn = conecta("bd_compras");
		$stm_sql = "SELECT * 
					FROM  `pedido` 
					WHERE  `id_pedido` LIKE  '$pedido'
					AND  `iva` >0";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$valor = true;
			}
		}
		return $valor;
	}
	
	function obtenerTotal($iva, $factura, $pedido){
		$valor = 0;
		$conn = conecta("bd_almacen");
		$stm_sql = "SELECT SUM( T2.costo_total ) AS total
					FROM entradas AS T1
					JOIN detalle_entradas AS T2 ON id_entrada = entradas_id_entrada
					WHERE no_factura =  '$factura'
					AND requisiciones_id_requisicion =  '$pedido'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				if($iva)
					$valor = $datos[0] * 1.16;
				else
					$valor = $datos[0];
			}
		}
		return $valor;
	}
?>
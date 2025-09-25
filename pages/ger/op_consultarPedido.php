<?php

	/**
	  * Nombre del Módulo: Gerencia Tecnica
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 20/Julio/2011
	  * Descripción: Este archivo contiene funciones desplegar la informacion de los pedidos registrados
	  **/
	
	//Funcion para mostrar los pedidos realizados en GT
	function mostrarPedidos($fechaIni,$fechaFin){
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Obtener la fecha limite de los pedidos y convertirla al formato leido por MySQL
		$fecha_ini = modFecha($fechaIni,3);
		$fecha_fin = modFecha($fechaFin,3);
		
		//Crear sentencia SQL
		$stm_sql="SELECT id_pedido,requisiciones_id_requisicion,plazo_entrega,fecha,subtotal,iva,total,estado FROM pedido WHERE fecha>='$fecha_ini' AND fecha<='$fecha_fin' AND requisiciones_id_requisicion LIKE 'GER%' ORDER BY id_pedido";
		
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){	
			echo "<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>PEDIDOS REGISTRADOS DE GERENCIA T&Eacute;CNICA DEL <em><u>$fechaIni</em></u> AL <em><u>$fechaFin</em></u></caption></br>";
			echo "<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>N° PEDIDO</td>
						<td class='nombres_columnas' align='center'>REQUISICI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>PLAZO ENTREGA</td>
						<td class='nombres_columnas' align='center'>FECHA PEDIDO</td>
						<td class='nombres_columnas' align='center'>SUBTOTAL</td>
						<td class='nombres_columnas' align='center'>IVA</td>
						<td class='nombres_columnas' align='center'>TOTAL</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	<tr>
						<td class='$nom_clase' align='center'>";
						?><input type="checkbox" name="ckb_idPedido" id="ckb_idPedido" value="<?php echo $datos["id_pedido"]?>" onclick="document.frm_pedidos.submit();"/>
						<?php 
						echo "</td>					
						<td class='$nom_clase' align='center'>$datos[id_pedido]</td>					
						<td class='$nom_clase' align='center'>$datos[requisiciones_id_requisicion]</td>
						<td class='$nom_clase' align='center'>$datos[plazo_entrega]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["subtotal"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["iva"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["total"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[estado]</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
			return true;
		}
		else{
			echo "<p align='center' class='msje_correcto'>No se Encontraron Pedidos del $fechaIni al $fechaFin</p>";
			return false;
			}
		//Cerar conexion a BD
		mysql_close($conn);
	}
	
	
	function mostrarDetallePedido(){
		$pedido=$_POST["ckb_idPedido"];
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		$stm_sql="SELECT partida,unidad,cantidad,descripcion,precio_unitario,importe FROM detalles_pedido WHERE pedido_id_pedido='$pedido' ORDER BY partida";
		//Variable que acumula el total del Precio Unitario
		$cu=0;
		//Variable que acumula el total de los Importes
		$ct=0;
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){	
			echo "<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>DETALLES DEL PEDIDO ".$pedido."</caption></br>";
			echo "<tr>
						<td class='nombres_columnas' align='center'>PARTIDA</td>
						<td class='nombres_columnas' align='center'>UNIDAD</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
						<td class='nombres_columnas' align='center'>IMPORTE</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	<tr>					
						<td class='$nom_clase' align='center'>$datos[partida]</td>					
						<td class='$nom_clase' align='center'>$datos[unidad]</td>
						<td class='$nom_clase' align='center'>$datos[cantidad]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["precio_unitario"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["importe"],2,".",",")."</td>
						</tr>";				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
				$cu+=$datos["precio_unitario"];
				$ct+=$datos["importe"];
					
			}while($datos=mysql_fetch_array($rs));
			//Obtener el total del pedido registrado
			$stm_sql="SELECT subtotal,iva,total FROM pedido WHERE id_pedido= '$pedido'";
			//Ejecutar la consulta
			$rs = mysql_query($stm_sql);		            						
			$datos_pedido=mysql_fetch_array($rs);
			$porcentaje_iva = round( ((intval($datos_pedido['iva'])/intval($datos_pedido['subtotal'])) * 100),0);
			echo "</tr>
				<tr>
					<td colspan='5' align='right'><strong>SUBTOTAL</strong></td>
					<td align='center'>$".number_format($datos_pedido['subtotal'],2,".",",")."</td>
				</tr>
				<tr>
					<td colspan='5' align='right'><strong>IVA $porcentaje_iva%</strong></td>
					<td align='center'>$".number_format($datos_pedido['iva'],2,".",",")."</td>
													
				</tr>
				<tr>
					<td colspan='5' align='right'><strong>TOTAL</strong></td>
					<td class='nombres_columnas' align='center'>$".number_format($datos_pedido['total'],2,".",",")."</td>
				</tr>
			</table>";
			return $pedido;
		}
		else{
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		//Cerar conexion a BD
		mysql_close($conn);
	}
?>
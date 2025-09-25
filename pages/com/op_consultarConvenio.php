<?php

	/**
	  * Nombre del Módulo: Compras
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 10/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones desplegar la informacion de un proveedor dado
	  **/
	
	//Funcion para mostrar los convenios registrados de los proveedores
	function mostrarConvenioDetalle(){
		$convenio=$_POST["cmb_convenios"];
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		//$stm_sql="SELECT razon_social, fecha_inicio, fecha_fin, fecha_elaboracion, convenios.estado, responsable, autorizador, comentarios, numero, unidad, cantidad, 
		//material_servicio, precio_unitario, importe FROM convenios JOIN detalles_convenio ON id_convenio=convenios_id_convenio JOIN proveedores ON proveedores_rfc=rfc
		// WHERE id_convenio='$convenio'";
		$stm_sql="SELECT numero, unidad, cantidad, material_servicio, precio_unitario, importe FROM detalles_convenio WHERE convenios_id_convenio='$convenio' ORDER BY numero";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);	            						
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='850' align='center'> 
				<caption class='titulo_etiqueta'>Detalles de Convenio ".$_POST["cmb_convenios"]."</caption></br>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>T&Eacute;RMINOS</td>
						<td class='nombres_columnas' align='center'>UNIDAD</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>MATERIAL/SERVICIO</td>
						<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
						<td class='nombres_columnas' align='center'>IMPORTE</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	<tr>					
						<td class='$nom_clase' align='center'>$datos[numero]</td>					
						<td class='$nom_clase' align='center'>$datos[unidad]</td>
						<td class='$nom_clase' align='center'>$datos[cantidad]</td>
						<td class='$nom_clase' align='center'>$datos[material_servicio]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["precio_unitario"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["importe"],2,".",",")."</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));
			//Obtener el total del convenio registrado
			$stm_sql="SELECT subtotal, iva, total FROM convenios WHERE id_convenio='$convenio'";
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
			</table>	
			</form>";
		}
		else{
			echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>EL PROVEEDOR <u>".$_POST["txt_nombre"]."</u> 
			NO TIENE NINGÚN CONVENIO REGISTRADO</p>";
		}
		//Cerar conexion a BD
		mysql_close($conn);
	}
?>
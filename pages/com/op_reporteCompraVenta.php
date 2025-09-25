<?php
	/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 07/Enero/2010                                      			
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Compra/Venta
	  **/
	  

	function generarReporte(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Variables para almacenar los datos de las graficas
		//Ocupar la posicion 0 de los arreglos, ya que la funcion array_search considera igual los valores de (Falso==>0==>"")
		$fechas_compras = array(0=>"fechaInicial"); $cant_compras = array(0=>"cantInicial"); $msg_compras = ""; $total_compras = 0; $max_compras = 0;
		$fechas_ventas = array(0=>"fechaInicial"); $cant_ventas = array(0=>"cantInicial"); $msg_ventas = ""; $total_ventas = 0; $max_ventas = 0;
		$band = 0;
		
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$f1 = modFecha($_POST['txt_fechaIni'],3);
		$f2 = modFecha($_POST['txt_fechaFin'],3);
				
		//Crear la consulta para obtener las ****COMPRAS****
		$stm_sql_compras = "SELECT fecha_entrega,total,estado FROM pedido WHERE fecha_entrega>='$f1' AND fecha_entrega<='$f2' ORDER BY fecha_entrega";								
		//Ejecutar la consulta
		$rs_compras = mysql_query($stm_sql_compras);				
		//Agrupar los resultados en los arreglos de Cantidades y Fechas de Compras
		if($datos_compras=mysql_fetch_array($rs_compras)){							
			do{
				//Cuando el arreglo este vacio agregar el primer registro directamente
				if(count($fechas_compras)==1){
					//Solo agregar los pedidos que esten entregados y pagados
					if($datos_compras['estado']=="PAGADO"){
						$fechas_compras[] = modFecha($datos_compras['fecha_entrega'],1);
						$cant_compras[] = intval($datos_compras['total']);					
					}
				}
				else{
					//Solo agregar los pedidos que esten entregados y pagados
					if($datos_compras['estado']=="PAGADO"){
						//Verificar que la fecha no este repetida en el arreglo
						$pos = array_search(modFecha($datos_compras['fecha_entrega'],1),$fechas_compras);					
						if($pos==""){//Si no esta repetida agregar el registro al arreglo													
							$fechas_compras[] = modFecha($datos_compras['fecha_entrega'],1);
							$cant_compras[] = intval($datos_compras['total']);												
						}
						else{//Sumar la cantidad de la fecha repetida al registro previo de la misma fecha					
							$cant_compras[$pos] += intval($datos_compras['total']);						
						}
					}//Cierre if($datos_compras['estado']=="PAGADO")
				}				
				$total_compras += $datos_compras['total'];//Obtener el total de las compras consultadas																				
			}while($datos_compras=mysql_fetch_array($rs_compras));
									
			//Construir la Tabla de Compras
			$msg_compras = "COMPRAS DEL $_POST[txt_fechaIni] AL $_POST[txt_fechaFin]";
			$ren_fechas = "";
			$ren_cant = ""; 
			foreach($fechas_compras as $key => $value){
				if($value!="fechaInicial"){
					$ren_fechas .= "<td class='nombres_columnas'>$value</td>";
					$ren_cant .= "<td class='renglon_blanco'>$".number_format($cant_compras[$key],2,".",",")."</td>";	
				}
			}																					
			//Colocar el total de las compras en el final de la tabla
			$ren_fechas .= "<td class='nombres_columnas'><strong>TOTAL</strong></td>";
			$ren_cant .= "<td class='renglon_blanco'><strong>$".number_format($total_compras,2,".",",")."</strong></td>";								
			//Dibujar la Tabla de Compras
			echo "
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'>COMPRAS</caption>
				<tr>
					$ren_fechas
				</tr>
				<tr>
					$ren_cant
				</tr>			
			</table>";
			
			//Obtener el valor maximo de compras
			$max_compras = max($cant_compras);						
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			$band += 1;			
		
				
		//Crear la consulta para obtener las ****VENTAS****
		$stm_sql_ventas = "SELECT fecha,total FROM ventas WHERE fecha>='$f1' AND fecha<='$f2' ORDER BY fecha";
		//Ejecutar la consulta
		$rs_ventas = mysql_query($stm_sql_ventas);		
		//Mostrar los resultados obtenidos				
		if($datos_ventas=mysql_fetch_array($rs_ventas)){						
			do{
				//Cuando el arreglo este vacio agregar el primer registro directamente
				if(count($fechas_ventas)==0){
					$fechas_ventas[] = modFecha($datos_ventas['fecha'],1);
					$cant_ventas[] = intval($datos_ventas['total']);
				}
				else{
					//Verificar que la fecha no este repetida en el arreglo
					$pos = array_search(modFecha($datos_ventas['fecha'],1),$fechas_ventas);			
					if($pos==""){//Si no esta repetida agregar el registro al arreglo						
						$fechas_ventas[] = modFecha($datos_ventas['fecha'],1);
						$cant_ventas[] = intval($datos_ventas['total']);
					}
					else{//Sumar la cantidad de la fecha repetida al registro previo de la misma fecha					
						$cant_ventas[$pos] += intval($datos_ventas['total']);
					}
				}
				$total_ventas += $datos_ventas['total'];//Obtener el total de las ventas consultadas																								
			}while($datos_ventas=mysql_fetch_array($rs_ventas));
			
			//Contruir los Renglones
			$msg_ventas = "VENTAS DEL $_POST[txt_fechaIni] AL $_POST[txt_fechaFin]";			
			$ren_fecha = "";
			$ren_cant = "";	
			foreach($fechas_ventas as $key => $value){
				if($value!="fechaInicial"){
					$ren_fecha .= "<td class='nombres_columnas'>$value</td>";
					$ren_cant .= "<td class='renglon_gris'>$".number_format($cant_ventas[$key],2,".",",")."</td>";
				}
			}
			//Colocar el total de las ventas consultadas en el final de la tabla
			$ren_fecha .= "<td class='nombres_columnas'><strong>TOTAL</strong></td>";
			$ren_cant .= "<td class='renglon_gris'><strong>$".number_format($total_ventas,2,".",",")."</strong></td>";
			
			//Imprimir la Tabla de Ventas
			echo "
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'>VENTAS</caption>
				<tr>
					$ren_fecha
				</tr>
				<tr>
					$ren_cant
				</tr>
			</table>";		
			//Obtener el valor maximo de compras			
			$max_ventas = max($cant_ventas);
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
			$band += 1;
		
		
		if($band==2){
			echo "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado en las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al 
			<em><u>$_POST[txt_fechaFin]</u></em></label>";
			?><br><br><?php
		}
		else{			
			//Antes de comparar el tamanio de los arreglos, quitar la posicion 0 de cada uno
			unset($fechas_compras[0]); $fechas_compras = array_values($fechas_compras); //Vaciar la posicion 0 y Rectificar los indices
			unset($cant_compras[0]); $cant_compras = array_values($cant_compras); //Vaciar la posicion 0 y Rectificar los indices
			unset($fechas_ventas[0]); $fechas_ventas = array_values($fechas_ventas); //Vaciar la posicion 0 y Rectificar los indices
			unset($cant_ventas[0]); $cant_ventas = array_values($cant_ventas); //Vaciar la posicion 0 y Rectificar los indices					
			
			//Guardar los datos para generar la Grafica en la SESSION
			$_SESSION['datosGrafica'] = array("fechas_compras"=>$fechas_compras,"cant_compras"=>$cant_compras,"max_compras"=>$max_compras,"msg_compras"=>$msg_compras,
											  "fechas_ventas"=>$fechas_ventas,"cant_ventas"=>$cant_ventas,"max_ventas"=>$max_ventas,"msg_ventas"=>$msg_ventas);
		}						
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
?>
<?php
	/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 22/Febrero/2012                                      			
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Pedidos y consultar los Tiempos de Entrega
	  **/

		function generarReporte(){
		
			//Realizar la conexion a la BD de Compras
			$conn = conecta("bd_compras");
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 0;		
									 											
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);						
			
			//Crear la consulta
			$stm_sql = "SELECT * FROM pedido WHERE  fecha>='$f1' AND fecha<='$f2' ORDER BY id_pedido";
						
			//Mensaje para desplegar en el titulo de la tabla
			$msg_titulo = "	Reporte de Tiemposde Entrega En el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Resultado 
							<br>En las Fechas del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";													
																		
			//Ejecutar la consulta
			$rs = mysql_query($stm_sql);
			//Mostrar los resultados obtenidos
			if($datos = mysql_fetch_array($rs)){		
				//Variable para verificar si la consulta ejecutada arrojo resultados
				$flag = 1;
				echo "				
					<table cellpadding='5' width='100%'>
						<caption class='titulo_etiqueta'>$msg_titulo</caption>					
						<tr>
							<td class='nombres_columnas'>CLAVE PEDIDO</td>
							<td class='nombres_columnas'>PROVEEDOR</td>
							<td class='nombres_columnas'>PLAZO ENTREGA</td>
							<td class='nombres_columnas'>FECHA</td>
							<td class='nombres_columnas'>SOLICITO</td>
							<td class='nombres_columnas'>REVISO</td>
							<td class='nombres_columnas'>AUTORIZO</td>
							<td class='nombres_columnas'>COMENTARIOS</td>
							<td class='nombres_columnas'>FECHA ENTREGA</td>
							<td class='nombres_columnas'>HORA ENTREGA</td>
							<td class='nombres_columnas'>DEPTO SOLICITANTE</td>
						</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				$cant_total = 0;
				$fechaEntrega=0;
				$horaEntrega=0;
				do{				
					//Obtenemos el nombre del proveedor
					$proveedor = obtenerDato("bd_compras", "proveedores", "razon_social", "rfc", $datos['proveedores_rfc']);					
					echo "	
						<tr>					
							<td class='$nom_clase' align='left'>$datos[id_pedido]</td>
							<td class='$nom_clase' align='left'>$proveedor</td>
							<td class='$nom_clase' align='left'>$datos[plazo_entrega]</td>
							<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase' align='left'>$datos[solicitor]</td>
							<td class='$nom_clase' align='left'>$datos[revisor]</td>
							<td class='$nom_clase' align='left'>$datos[autorizador]</td>					
							<td class='$nom_clase' align='left'>$datos[comentarios]</td>";
							if($datos['fecha_entrega']==""){
								$fechaEntrega = "PEDIDO <strong><em><u>NO</u><em></strong> COMPLEMENTADO POR COMPRAS";
							}
							else{
								$fechaEntrega = $datos['fecha_entrega'];
							}					
					 echo  "<td class='$nom_clase'>$fechaEntrega</td>";
							if($datos['hora_entrega']==""){
								$horaEntrega = "PEDIDO <strong><em><u>NO</u><em></strong> COMPLEMENTADO POR COMPRAS";
							}
							else{
								$horaEntrega = $datos['hora_entrega'];
							}		
					echo   "<td class='$nom_clase'>$horaEntrega</td>					
							<td class='$nom_clase'>$datos[depto_solicitor]</td>	
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
			else//Si no se encuentra ningun resultado desplegar un mensaje					
				echo $msg_error;			
			//Cerrar la conexion con la BD
			mysql_close($conn);
	}
?>
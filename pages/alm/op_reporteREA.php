<?php
	/**
	  * Nombre del M�dulo: Almac�n                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas                            
	  * Fecha: 27/Octubre/2010                                      			
	  * Descripci�n: Este archivo contiene funciones para Mostrar la informaci�n seleccionada en el formulario de Reporte REA
	  **/	 		  	  	
	

	function obtenerIdREA(){	
		//Definir las tres letras de la ID 
		$id_cadena = "REA";
	
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el a�o actual para ser agregado en la consulta y asi obtener los Reportes REA del mes en curso del a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de INV registradas en la BD
		$stm_sql = "SELECT COUNT(id_reporte_rea) AS cant FROM reporte_rea WHERE id_reporte_rea LIKE 'REA$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		return $id_cadena;
	}//Fin de la Funcion obtenerIdREA()
	
	
	//Esta funcion desplega las entradas registradas en el sistema	
	function mostrarEntradas($txt_fechaInicio,$txt_fechaCierre){
		//Realizar la conexion a la BD de Almacen			
		$conn = conecta('bd_almacen');
				
		//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
		$f1 = modFecha($txt_fechaInicio,3);
		$f2 = modFecha($txt_fechaCierre,3);

		//Crear la sentencia para mostrar el Reporte de Orden de Compra dadas las fechas escritas
		$stm_sql = "SELECT * FROM entradas WHERE fecha_entrada>='$f1' AND fecha_entrada<='$f2'";
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);		
		$msg = "Reporte de Entradas generadas del <strong><u>$txt_fechaInicio</u></strong> al <strong><u>$txt_fechaCierre</u></strong>";

		if($row = mysql_fetch_array($rs)){			
			echo "
			<table class='tabla_frm' cellpadding='5' width='1000'>
			<tr>
				<td colspan='11' align='center' class='titulo_etiqueta'>$msg</td>
  			</tr>
			<tr>
				<td class='nombres_columnas'>Ver Detalle</td>
				<td class='nombres_columnas'>Clave</td>
				<td class='nombres_columnas'>Origen</td>
				<td class='nombres_columnas'>No. Origen</td>
				<td class='nombres_columnas'>Proveedor</td>
				<td class='nombres_columnas'>No. Factura</td>
				<td class='nombres_columnas'>Costo Entrada</td>
				<td class='nombres_columnas'>Fecha</td>
				<td class='nombres_columnas'>Hora</td>
				<td class='nombres_columnas'>Aceptado</td>
				<td class='nombres_columnas'>Comentarios</td>
			</tr>";			
			//Mostramos los registros
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cont_req = 0;
			$cont_oc = 0;
			$cont_cd = 0;
			$cont_ped = 0;
			/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida  */
			$cant_total = 0;
					
			echo "<form name='frm_mostrarDetalleEM' method='post' action='frm_reporteREA.php'>
					<input type='hidden' name='fecha_ini' value='$txt_fechaInicio' />
					<input type='hidden' name='fecha_end' value='$txt_fechaCierre' />";
			
			//Declarar arreglo para guardar las claves de las entradas contenidas en las fechas seleccionadas por el usuario
			$clavesEntradas = array();
			do{								
				//Establecer el Origen y el No. de Origen
				if($row['requisiciones_id_requisicion']!=""){
					$noOrigen = $row['requisiciones_id_requisicion'];
					//Verificar si la requisicion tiene las iniciales PED, esto determina un Pedido, no una Requisicion
					if(substr($noOrigen,0,3)=="PED"){
						$origen="Pedido";
						$cont_ped++;
					}
					else{
						$origen = "Requisicion";
						$cont_req++;
					}
				}
				if($row['orden_compra_id_orden_compra']!=""){
					$origen = "Orden de Compra";
					$noOrigen = $row['orden_compra_id_orden_compra'];
					$cont_oc++;
				}
				if($row['comp_directa']!=""){
					$origen = "Compra Directa";
					$noOrigen = $row['comp_directa'];
					$cont_cd++;
				}
				$clavesEntradas[] = $row['id_entrada'];	
				echo "
					<tr>
						<td class='nombres_filas'><input type='checkbox' name='EM$cont' value='$row[id_entrada]' onClick='javascript:document.frm_mostrarDetalleEM.submit();'/></td>
						<td class='$nom_clase'>$row[id_entrada]</td>
						<td class='$nom_clase'>$origen</td>
						<td class='$nom_clase'>$noOrigen</td>
						<td class='$nom_clase'>$row[proveedor]</td>
						<td class='$nom_clase'>$row[no_factura]</td>
						<td class='$nom_clase'>$ ".number_format($row['costo_total'],2,".",",")."</td>
						<td class='$nom_clase'>".modFecha($row['fecha_entrada'],1)."</td>
						<td class='$nom_clase'>$row[hora_entrada]</td>
						<td class='$nom_clase'>$row[aceptado]</td>
						<td class='$nom_clase'>$row[comentarios]</td>
					</tr>";			
				/*Operaci�n que mostrara el total del costo de los materiales   */ 				
				$cant_total += $row['costo_total'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";						
			}while($row = mysql_fetch_array($rs));
			//Guardar las claves en el arreglo de SESSION claves_entradas
			$_SESSION['claves_entrada'] = $clavesEntradas;
			
			echo "
				<tr>
					<td class='nombres_filas'>
						<input type='checkbox' name='EM' value='todos' onClick='javascript:document.frm_mostrarDetalleEM.submit();'/>						
					</td>
					<td class='$nom_clase' colspan='4' align='left'>Ver Detalle de Todo</td>
					<td class='nombres_columnas'>TOTAL</td>
					<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>						
				</tr>
				<tr>
					<td class='nombres_filas' colspan='11' align='center'>
						Reporte Generado a partir de: <strong><u>$cont_ped</u></strong> Pedido(s),
						<strong><u>$cont_req</u></strong> Requisicione(s), 						
						<strong><u>$cont_oc</u></strong> &Oacute;rden(es) de Compra y
						<strong><u>$cont_cd</u></strong> Compra(s) Directa(s)
					</td>
				</tr>												
			</form>
			</table>";	?>
			</div>
			</br>
			<div id="btns-regpdf" align="center">
			<table width="30%" cellpadding="12">
				<tr>
					<td colspan="11" align="center">
						<form method="post" action="guardar_reporte.php">
							<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="<?php echo $stm_sql; ?>"/>
							<input type="hidden" name="hdn_msg" id="hdn_msg" value="<?php echo $msg; ?>"/>
							<input type="hidden" name="hdn_tipoReporte" id="hdn_tipoReporte" value="reporteEntradas"/>
							<input type="hidden" name="hdn_nomReporte" id="hdn_nomReporte" value="reporteEntradas"/>
							<input type="submit" value="Exportar a Excel" name="sbt_exportar" id="sbt_exportar" class="botones" title="Exportar el Reporte a Excel" onmouseover="window.status='';return true;"/>
							&nbsp;&nbsp;
							<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Seleccionar Otro Rango de Fechas" onclick="location.href='frm_reporteREA.php'" />
						</form>
					</td>
				</tr>
			</table>
			</div><?php
		}
		else
			echo "<meta http-equiv='refresh' content='0;url=advertencia.php'>";
	}//Cierre de la funcion mostrarOrdenesCompra($theDate,$theDate2)

	 
	 //Mostrar el detalle de los materiales de acuerdo a los parametros seleccionados
	 function mostrarDetalleEM($clave,$fecha_ini,$fecha_end){ 
	 	//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");				
		
		//Convertir las fechas del formaro dd/mm/aaa al formato aaaa-mm-dd	
		$fecha1= modFecha($fecha_ini,3);
		$fecha2= modFecha($fecha_end,3);

							
		//Verificar si el reporte REA contiene una o mas entradas
		if($clave=="todos"){			
			//Crear la consulta para mostrar el detalle de todas las entradas seleccionadas			
			$stm_sql = "SELECT materiales_id_material, nom_material, linea_material, cant_entrada, costo_unidad, detalle_entradas.costo_total, proveedor, no_factura, unidad_material, requisiciones_id_requisicion, orden_compra_id_orden_compra, comp_directa, aceptado, entradas.comentarios, tipo_moneda, partida_pedido
						FROM entradas
						JOIN detalle_entradas ON id_entrada = entradas_id_entrada
						WHERE fecha_entrada
						BETWEEN '$fecha1'
						AND '$fecha2'";
			//Crear el mensaje a mostrar encima de la tabla que contiene el detalle de la entrada
			$msg = "Detalle de las Entradas del <strong><u>$fecha_ini</u></strong> al <strong><u>$fecha_end</u></strong>";						
		}
		else{
			//Obtener el tipo de Entrada verificando el campo de nom_material en el detalle de Entradas, se comprueba solo un Material, por la forma en esta el Sistema ahora 2012-05-11 12:21 p.m.
			$material=obtenerdato("bd_almacen","detalle_entradas","materiales_id_material","entradas_id_entrada",$clave);
			//Verificar que Material sea diferente de �NOVALE, para aplicar la sentenci de la forma como corresponde
			if($material!="�NOVALE")
				//Crear la sentencia para mostrar el detalle de la entrada seleccionada
				$stm_sql = "SELECT materiales_id_material, nom_material, linea_material, cant_entrada, costo_unidad, detalle_entradas.costo_total, proveedor, no_factura, unidad_material, requisiciones_id_requisicion, orden_compra_id_orden_compra, comp_directa, aceptado, entradas.comentarios, tipo_moneda, partida_pedido
							FROM entradas
							JOIN detalle_entradas ON id_entrada = entradas_id_entrada
							WHERE id_entrada = '$clave'";
			else
				//Crear la sentencia para mostrar el detalle de la entrada seleccionada
				$stm_sql = "SELECT 'N/A' AS materiales_id_material, nom_material, linea_material, cant_entrada, costo_unidad, detalle_entradas.costo_total, proveedor, no_factura, unidad_material, requisiciones_id_requisicion, orden_compra_id_orden_compra, comp_directa, aceptado, entradas.comentarios, tipo_moneda, partida_pedido
							FROM entradas
							JOIN detalle_entradas ON id_entrada = entradas_id_entrada
							WHERE id_entrada = '$clave'";
			//Obtener la fecha de la entrada
			$fecha = obtenerDato("bd_almacen","entradas","fecha_entrada","id_entrada",$clave);
			//Obtener la Hora en que se registro la entrada
			$hora = obtenerDato("bd_almacen","entradas","hora_entrada","id_entrada",$clave);
			
			//Crear el mensaje a mostrar encima de la tabla que contiene el detalle de la entrada
			$msg = "Detalle de la Entrada No. $clave con Fecha ".modFecha($fecha,1).", Hora: ".modHora($hora);			
			//Decalarar arreglos que almacenara las claves de las entradas que contendra el reporte REA
			$arrEntradas = array($clave);
			//Guardar en una variable de Sesion las claves de Entrada para almacenar los detalles del reporte REA
			$_SESSION['claves_entrada'] = $arrEntradas;
		}
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);						
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='1650'>
					<tr>
					    <td colspan='10' align='center' class='titulo_etiqueta'>$msg</td>
  					</tr>      			
					<tr>					
						<td class='nombres_columnas'>CLAVE</td>
        				<td class='nombres_columnas'>NOMBRE (DESCRIPCION)</td>
				        <td class='nombres_columnas'>UNIDAD DE MEDIDA</td>
        				<td class='nombres_columnas'>LINEA DEL ARTICULO (CATEGORIA)</td>
        				<td class='nombres_columnas'>UBICACION</td>
						<th class='nombres_columnas'>CANTIDAD ENTRADA</th>
						<td class='nombres_columnas'>COSTO UNITARIO</td>

						<td class='nombres_columnas'>SUBTOTAL</td>
						<td class='nombres_columnas'>MONEDA</td>
						
        				<td class='nombres_columnas'>PROVEEDOR</td>
						<td class='nombres_columnas'>NO. FACTURA</td>
						<td class='nombres_columnas'>OR&Iacute;GEN</td>
        				<td class='nombres_columnas'>NO. OR&Iacute;GEN</td>
        				<td class='nombres_columnas'>ACEPTADO</td>
						<td class='nombres_columnas'>COMENTARIOS</td>
						<td class='nombres_columnas'>CENTRO DE COSTOS</td>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			
			/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida  */
			$cant_total = 0;
			$pesosTotal=0; $dolaresTotal=0; $eurosTotal=0; $naTotal=0;
			do{
				//Establecer el Origen y el No. de Origen
				if($datos['requisiciones_id_requisicion']!=""){
					$origen = "Requisicion";
					$no_origen = $datos['requisiciones_id_requisicion'];
				}
				if($datos['orden_compra_id_orden_compra']!=""){
					$origen = "Orden de Compra";
					$no_origen = $datos['orden_compra_id_orden_compra'];
				}
				if($datos['comp_directa']!=""){
					$origen = "Compra Directa";
					$no_origen = $datos['comp_directa'];				
				}	

				$area = obtenerCentroCostos($no_origen,$datos['partida_pedido']);

				echo "	
					<tr>";
				if($datos["materiales_id_material"]=="�NOVALE"){
					echo "<td class='nombres_filas'>NO APLICA</td>";
					$ubicacion = "NO APLICA";
				}
				else{
					echo "<td class='nombres_filas'>$datos[materiales_id_material]</td>";
					$ubicacion=obtenerdato("bd_almacen","materiales","ubicacion","id_material",$datos['materiales_id_material']);
				}
				echo "
						<td class='$nom_clase' align='left'>$datos[nom_material]</td>
						<td class='$nom_clase'>$datos[unidad_material]</td>
						<td class='$nom_clase'>$datos[linea_material]</td>
						<td class='$nom_clase'>$ubicacion</td>
						<td class='$nom_clase'>$datos[cant_entrada]</td>
						<td class='$nom_clase'>$".number_format($datos['costo_unidad'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['costo_total'],2,".",",")."</td>
						<td class='$nom_clase'>$datos[tipo_moneda]</td>
						<td class='$nom_clase'>$datos[proveedor]</td>
						<td class='$nom_clase'>$datos[no_factura]</td>
						<td class='$nom_clase'>$origen</td>
						<td class='$nom_clase'>$no_origen</td>
						<td class='$nom_clase'>$datos[aceptado]</td>
						<td class='$nom_clase'>$datos[comentarios]</td>
						<td class='$nom_clase'>$area</td>
					</tr>";						
				/*Operaci�n que mostrara el total del costo de los materiales   */ 				
				$cant_total += $datos['costo_total'];
									
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				
				if($datos["tipo_moneda"] == "PESOS")
					$pesosTotal += $datos['costo_total'];
				
				else if($datos["tipo_moneda"] == "DOLARES")
					$dolaresTotal += $datos['costo_total'];
				
				else if($datos["tipo_moneda"] == "EUROS")
					$eurosTotal += $datos['costo_total'];
				
				else
					$naTotal += $datos['costo_total'];
				
			}while($datos=mysql_fetch_array($rs)); 
			if($pesosTotal > 0){ ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">$&nbsp;<?php echo number_format($pesosTotal,2,".",",")?></td>
				<td class="nombres_columnas">PESOS</td>
			</tr>
			<?php }
			if($dolaresTotal > 0){ ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">$&nbsp;<?php echo number_format($dolaresTotal,2,".",",")?></td>
				<td class="nombres_columnas">DOLARES</td>
			</tr>
			<?php }
			if($eurosTotal > 0){ ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">&euro;&nbsp;<?php echo number_format($eurosTotal,2,".",",")?></td>
				<td class="nombres_columnas">EUROS</td>
			</tr>
			<?php }
			if($naTotal > 0){ ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">$&nbsp;<?php echo number_format($naTotal,2,".",",")?></td>
				<td class="nombres_columnas">N/A</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="6">&nbsp;</td>
				<td class="nombres_columnas" align="right">$&nbsp;<?php echo number_format($cant_total,2,".",",")?></td>
				<td class="nombres_columnas">TOTAL</td>
			</tr> <?php
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";			
			$conn=conecta("bd_almacen");																																											
			//Generar clave de REA
			$clave_rea=obtenerIdREA();			
			?>
			</div>
			</br>
			<div id="btns-regpdf" align="center">
			<table cellpadding="5" cellspacing="5" class="tabla_frm" width="900" >
				<tr>
					<td align="right">
						<form action="frm_reporteREA.php" method="post">
							<input type="hidden" name="txt_fechaInicio" value="<?php echo $fecha_ini; ?>" />
							<input type="hidden" name="txt_fechaCierre" value="<?php echo $fecha_end; ?>" />				
							<input name="sbt_regresar" type="submit" value="Regresar" class="botones" title="Seleccionar Otra Entrada" onmouseover="window.status='';return true" />				
						</form>
					</td>
					<td width="180" align="center">
						<form action="publicar_reporteREA.php" method="post">							
							<input name="hdn_clave" type="hidden" value="<?php echo $clave_rea; ?>"  />
							<input name="hdn_hora" type="hidden" value="<?php echo date("H:i:s"); ?>"  />
							<input name="hdn_fechaCrea" type="hidden" value="<?php echo date("Y:m:d"); ?>"  />
							<input name="hdn_fechaIni" type="hidden" value="<?php echo $fecha1; ?>"  />
							<input name="hdn_fechaFin" type="hidden" value="<?php echo $fecha2; ?>"  />							
							<input name="sbt_exportar" type="submit" class="botones_largos" value="Publicar Reporte REA" title="Publicar Reporte REA" onmouseover="window.status='';return true"  />
						</form>
					</td>
					<td align="left">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"  />
							<input name="hdn_nomReporte" type="hidden" value="Reporte REA"  />	
							<input name="hdn_tipoReporte" type="hidden" value="rea"  />
							<input name="hdn_msg" type="hidden" value="<?php echo $msg; ?>"  />							
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar Datos de la Entrada Seleccionada a Excel" onmouseover="window.status='';return true"  />
							&nbsp;&nbsp;&nbsp;&nbsp;
							<?php //Si este ?>
							<?php if (!isset($_POST["EM"])) {?>
							<input name="btn_verPDF" type="button" class="botones_largos" value="Generar Comprobante" title="Generar Comprobante de Entrada en PDF" onmouseover="window.status='';return true" 
							onclick="window.open('../../includes/generadorPDF/entradaMaterial.php?id=<?php echo $clave; ?>', 
							'_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')" />
							<?php }?>
						</form>
					</td>
				</tr>
			</table>
			</div>							
			<?php
		}
		else{
			//La ventana se redirecciona a la ventana de advertencia indicando que la consulta no gener� resultados
			//echo "<meta http-equiv='refresh' content='0;url=advertencia.php'>";
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion dibujarDetalle($campo,$valBuscar)

	function obtenerCentroCostos($valor,$partida){
		$busq = substr($valor,0,3);
		$base = '';

		if( $busq == "PED" ){
			$stm_sql = "SELECT T2.descripcion
						FROM `detalles_pedido` AS T1
						JOIN bd_recursos.control_costos AS T2 ON T1.id_control_costos = T2.id_control_costos
						WHERE `pedido_id_pedido` LIKE '$valor'
						AND `partida` =$partida";
			$base = "bd_compras";
		} else if( $busq == "SEM" || $busq == "SEC" ){
			$stm_sql = "SELECT T2.descripcion
						FROM `orden_servicios_externos` AS T1
						JOIN bd_recursos.control_costos AS T2 ON T1.id_control_costos = T2.id_control_costos
						WHERE `id_orden` LIKE '$valor'";
			$base = "bd_mantenimiento";
		} else {
			return "";
		}

		$conn_cc = conecta("$base");

		$rs_cc = mysql_query($stm_sql);

		if( $rs_cc ){
			$datos_cc = mysql_fetch_array( $rs_cc );
			return $datos_cc['descripcion'];
		} else {
			return "";
		}
	}
?>
 
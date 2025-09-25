<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 27/Enero/2010                                      			
	  * Descripción: Este archivo contiene funciones para Mostrar la información seleccionada en el formulario de Reportes de Salidas
	  **/	 		  	  	
	

	//Esta funcion desplega las entradas registradas en el sistema	
	function mostrarSalidas($txt_fechaInicio,$txt_fechaCierre,$orden){
		//Realizar la conexion a la BD de Almacen			
		$conn = conecta('bd_almacen');
				
		//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
		$f1 = modFecha($txt_fechaInicio,3);
		$f2 = modFecha($txt_fechaCierre,3);

		//Crear la sentencia para mostrar el Reporte de Orden de Compra dadas las fechas escritas
		$stm_sql = "SELECT * FROM salidas WHERE fecha_salida>='$f1' AND fecha_salida<='$f2'";
		
		//Si se selecciono un criterio de ordenación, adjuntarlo a la sentencia SQL
		if($orden!="")
			$stm_sql.= " ORDER BY $orden";
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);		
		$msg = "Reporte de Salidas generadas del <strong><u>$txt_fechaInicio</u></strong> al <strong><u>$txt_fechaCierre</u></strong>";

		if($row = mysql_fetch_array($rs)){			
			echo "
			<table class='tabla_frm' cellpadding='5' width='100%'>
			<caption class='titulo_etiqueta'>$msg</caption>
			<tr>
				<th class='nombres_columnas'>Ver Detalle</th>
				<th class='nombres_columnas'>Clave</th>
				<th class='nombres_columnas'>Departamento Solicitante</th>
				<th class='nombres_columnas'>Solicit&oacute;</th>
				<th class='nombres_columnas'>Turno</th>
				<th class='nombres_columnas'>Destino</th>
				<th class='nombres_columnas'>Fecha</th>
				<td class='nombres_columnas'>Costo Total</td>
				<td class='nombres_columnas'>No. Vale</td>
			</tr>
			";			
			//Mostramos los registros
			$nom_clase = "renglon_gris";
			$cont = 1;
			
			/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida  */
			$cant_total = 0;
			echo "<form name='frm_mostrarDetalleSM' method='post' action='frm_reporteSalidas.php'>
					<input type='hidden' name='fecha_ini' value='$txt_fechaInicio' />
					<input type='hidden' name='fecha_end' value='$txt_fechaCierre' />
					<input type='hidden' name='ordenar_por' value='$orden' />";
			do{									
				echo "
					<tr>
						<td class='nombres_filas'>
							<input type='checkbox' name='SM$cont' value='$row[id_salida]' onClick='javascript:document.frm_mostrarDetalleSM.submit();'/>
						</td>
						<td class='$nom_clase'>$row[id_salida]</td>
						<td class='$nom_clase'>$row[depto_solicitante]</td>
						<td class='$nom_clase'>$row[solicitante]</td>
						<td class='$nom_clase'>$row[turno]</td>
						<td class='$nom_clase'>$row[destino]</td>
						<td class='$nom_clase'>".modFecha($row['fecha_salida'],1)."</td>
						<td class='$nom_clase'>$ ".number_format($row['costo_total'],2,".",",")."</td>
						<td class='$nom_clase'>$row[no_vale]</td>
					</tr>";
					
					
				/*Operación que mostrara el total del costo de los materiales   */ 				
				$cant_total += $row['costo_total'];
				
									
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";						
			}while($row = mysql_fetch_array($rs));
			//Colocar el Check para cuando se selecciona VER TODO
			echo "
				<tr>
					<td class='nombres_filas'>
						<input type='checkbox' name='SM' value='todos' onClick='javascript:document.frm_mostrarDetalleSM.submit();'/>						
					</td>
					<td class='$nom_clase' colspan='5' align='left'>Ver Detalle de Todo</td>	
					<td class='nombres_columnas'>TOTAL</td>
					<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>									
				</tr>					

			</form>";
			?>
			</table><?php
		}
		else{
			$f1=modFecha($f1,2);
			$f2=modFecha($f2,2);
			echo "</br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>Del <strong><u>$f1</u></strong> al 
					<strong><u>$f2</u></strong> NO se registraron Salidas</p>";
		}?>
		
		</div>
			</br>
			<div id="btns-regpdf" align="center">
			<table>
			<table width="30%" cellpadding="12">
				<tr><td>
					<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Seleccionar Otro Rango de Fechas" 
					onclick="location.href='frm_reporteSalidas.php'" />
				</td></tr>
			</table>
		</div><?php
	}//Cierre de la funcion mostrarOrdenesCompra($theDate,$theDate2)

	 
	//Mostrar el detalle de los materiales de acuerdo a los parametros seleccionados
	function mostrarDetalleSM($clave,$fecha_ini,$fecha_end,$ordenar_por){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");				
		
		//Convertir las fechas del formaro dd/mm/aaa al formato aaaa-mm-dd	
		$fecha1= modFecha($fecha_ini,3);
		$fecha2= modFecha($fecha_end,3);
							
							
		if($clave=="todos"){//Consulta  que muestra la información inicial para obtener las salidas
			$stm_sql = "SELECT materiales_id_material, nom_material, unidad_material, cant_salida, costo_unidad, detalle_salidas.costo_total, 
						no_vale, id_equipo_destino, solicitante, destino, depto_solicitante, turno
						FROM detalle_salidas JOIN salidas ON salidas_id_salida=id_salida WHERE fecha_salida>='$fecha1' AND fecha_salida<='$fecha2'";
	
			//Crear el mensaje a mostrar encima de la tabla que contiene el detalle de la entrada
			$msg = "Detalle de Salida de Materiales con Fecha del  ".modFecha($fecha1,1)." al ".modFecha($fecha2,1);

		}
		
		else{//Crear la sentencia para mostrar el detalle de la salida seleccionada
			$stm_sql = "SELECT materiales_id_material, nom_material, unidad_material, cant_salida, costo_unidad, costo_total, id_equipo_destino 
				FROM detalle_salidas WHERE salidas_id_salida ='$clave'";
			
			
			//Obtener la fecha de la salida
			$fecha = obtenerDato("bd_almacen","salidas","fecha_salida","id_salida",$clave);
			
			//Crear el mensaje a mostrar encima de la tabla que contiene el detalle de la salida
			$msg = "Detalle de la Salida No. $clave";


		}
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);						
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%'>
					<tr>
					    <td colspan='10' align='center' class='titulo_etiqueta'>$msg</td>
  					</tr>      			
					<tr>					
						<td class='nombres_columnas'>CLAVE</td>
        				<td class='nombres_columnas'>MATERIAL</td>
						<td class='nombres_columnas'>UNIDAD DE MEDIDA</td>
				        <td class='nombres_columnas'>CANTIDAD SALIDA</td>						
        				<td class='nombres_columnas'>COSTO UNITARIO</td>
						<th class='nombres_columnas'>SUBTOTAL</th>
						<th class='nombres_columnas'>ID EQUIPO</th>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			
			
			/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida  */
			$cant_total = 0;
			
					
			do{
				$idEquipo = "N/A";
				if($datos['id_equipo_destino']!="")
					$idEquipo = $datos['id_equipo_destino'];
					
				echo "	
					<tr>";
				if($datos["materiales_id_material"]=="¬NOVALE")
					echo "<td class='nombres_filas'>NO APLICA</td>";
				else
					echo "<td class='nombres_filas'>$datos[materiales_id_material]</td>";
				echo "
						<td class='$nom_clase' align='left'>$datos[nom_material]</td>
						<td class='$nom_clase'>$datos[unidad_material]</td>
						<td class='$nom_clase'>$datos[cant_salida]</td>						
						<td class='$nom_clase'>".number_format($datos['costo_unidad'],2,".",",")."</td>
						<td class='$nom_clase'>".number_format($datos['costo_total'],2,".",",")."</td>
						<td class='$nom_clase'>$idEquipo</td>
					</tr>";	
					
					
					/*Operación que mostrara el total del costo de los materiales   */ 				
				$cant_total += $datos['costo_total'];
				
				
																
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datos=mysql_fetch_array($rs)); 
			
			
		echo "<tr>
				<td class='$nom_clase' colspan='4' align='left'></td>
				<td class='nombres_columnas'>TOTAL</td>
				<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>
			</tr>";	
			
			
						
			echo "</table>";		
			?>
			</div>
			</br>
			<div id="btns-regpdf" align="center">
			<table width="30%" cellpadding="12">
				<tr>
					<td align="right" width="33%">
						<input name="btn_verPDF" type="button" class="botones" value="Generar Vale" title="Generar Vale de la Salida" onmouseover="window.status='';return true" 
						onclick="window.open('../../includes/generadorPDF/valeSalida.php?id=<?php echo $clave;?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')" />
					</td>
					<td align="right" width="33%">
						<form action="frm_reporteSalidas.php" method="post">
							<input type="hidden" name="txt_fechaInicio" value="<?php echo $fecha_ini; ?>" />
							<input type="hidden" name="txt_fechaCierre" value="<?php echo $fecha_end; ?>" />
							<input type="hidden" name="cmb_orden" value="<?php echo $fecha_end; ?>" />
							<input name="sbt_regresar" type="submit" value="Regresar" class="botones" title="Seleccionar Otra Entrada" onmouseover="window.status='';return true" />				
						</form>
					</td>
					<td align="left" width="33%">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>"  />
							<input name="hdn_nomReporte" type="hidden" value="Reporte Salidas"  />	
							<input name="hdn_tipoReporte" type="hidden" value="salidas"  />
							<input name="hdn_msg" type="hidden" value="<?php echo $msg; ?>"  />							
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar Datos de la Salida seleccionada a Excel" 
							onmouseover="window.status='';return true"  />
						</form>
					</td>
				</tr>
			</table>
			</div>							
			<?php
			return 1;
		}
		else{
			//La ventana se redirecciona a la ventana de advertencia indicando que la consulta no generó resultados
			echo "<meta http-equiv='refresh' content='5;url=advertencia.php'>";
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	 }//Fin de la funcion dibujarDetalle($campo,$valBuscar)
	 
	 
	 //Esta funcion desplega llos materiales registrados en las salidas del sistema
	function mostrarSalidas2($fechaI,$fechaC,$orden){
		//Variable de retorno de datos para resultados encontrados
		$band=0;
		$cadena =  obtenerCentroCostos('ZARPEO');
		//Realizar la conexion a la BD de Almacen
		$conn = conecta('bd_almacen');

		//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
		$f1 = modFecha($fechaI,3);
		$f2 = modFecha($fechaC,3);
		
		$filtro_centro_costo = $_POST["cmb_cc"];
		$filtro_cuentas = $_POST["cmb_cuenta"];
		$filtro_subcuentas = $_POST["cmb_subcuenta"];
		
		//Crear la sentencia para mostrar el Reporte de Orden de Compra dadas las fechas escritas
		$stm_sql = "SELECT id_salida,fecha_salida,solicitante,destino,depto_solicitante,turno,no_vale,cuentas,subcuentas,moneda FROM salidas WHERE fecha_salida BETWEEN '$f1' AND '$f2'";
		
		if($filtro_centro_costo!="")
			$stm_sql.= " AND destino = '$filtro_centro_costo'";
		else
			$stm_sql.= $cadena;
		
		if($filtro_cuentas!="")
			$stm_sql.= " AND cuentas = '$filtro_cuentas'";
		
		if($filtro_subcuentas!="")
			$stm_sql.= " AND subcuentas = '$filtro_subcuentas'";
		
		//Si se selecciono un criterio de ordenación, adjuntarlo a la sentencia SQL
		if($orden!="")
			$stm_sql.= " ORDER BY $orden,fecha_salida";
		else
			$stm_sql.= " ORDER BY fecha_salida";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql) or die(mysql_error());
		$msg = "Reporte de Salidas generadas del <strong><u>$fechaI</u></strong> al <strong><u>$fechaC</u></strong>";

		if($row = mysql_fetch_array($rs)){
			echo "
			<table class='tabla_frm' cellpadding='5' width='100%'>
			<caption class='titulo_etiqueta'>$msg</caption>
			<tr>
				<td class='nombres_columnas'>PEDIDO</td>
				<td class='nombres_columnas'>FECHA PEDIDO</td>
				
				<td class='nombres_columnas'>DEPARTAMENTO</td>
				<td class='nombres_columnas'>CENTRO COSTOS</td>
				<td class='nombres_columnas'>CUENTA</td>
				<td class='nombres_columnas'>SUBCUENTA</td>
				
				<td class='nombres_columnas'>CLAVE MATERIAL</td>
				<td class='nombres_columnas'>NOMBRE MATERIAL</td>
				<td class='nombres_columnas'>UNIDAD MEDIDA</td>
				<td class='nombres_columnas'>EXISTENCIA EN STOCK</td>
				
				<td class='nombres_columnas'>CLAVE ENTRADA</td>
				<td class='nombres_columnas'>FECHA ENTRADA</td>
				<td class='nombres_columnas'>CANTIDAD ENTRADA</td>
				
				<th class='nombres_columnas'>CLAVE SALIDA</th>
				<th class='nombres_columnas'>FECHA SALIDA</th>
				<th class='nombres_columnas'>DEPARTAMENTO SOLICITANTE</th>
				<th class='nombres_columnas'>SOLICITANTE</th>
				<th class='nombres_columnas'>DESTINO</th>
				<th class='nombres_columnas'>TURNO</th>
				<td class='nombres_columnas'>NO. VALE</td>
				
				<td class='nombres_columnas'>EQUIPO DESTINO</td>
				<td class='nombres_columnas'>CANTIDAD SALIDA</td>
				<td class='nombres_columnas'>COSTO UNITARIO</td>
				<td class='nombres_columnas'>COSTO TOTAL</td>
				<td class='nombres_columnas'>TIPO MONEDA</td>
			</tr>
			";			
			//Mostramos los registros
			$nom_clase = "renglon_gris";
			$cont = 1;
			
			/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida*/
			$cant_total_pesos = 0;
			$cant_total_dolares = 0;
			$cant_total_euros = 0;
			$cant_total = 0;
			do{
				$id_salida=$row["id_salida"];
				mysql_close($conn);
				
				$centro_costos = obtenerDatosCentroCostos($row["destino"],"control_costos","id_control_costos");
				$cuenta = obtenerDatosCentroCostos($row["cuentas"],"cuentas","id_cuentas");
				$subcuenta = obtenerDatosCentroCostos($row["subcuentas"],"subcuentas","id_subcuentas");
				
				$conn = conecta('bd_almacen');
				$sql_detalle="SELECT materiales_id_material,nom_material,unidad_material,cant_salida,costo_unidad,costo_total,id_equipo_destino,moneda FROM detalle_salidas WHERE salidas_id_salida='$id_salida'";
				/*$sql_detalle = "SELECT DISTINCT T1.materiales_id_material, T1.nom_material, T1.unidad_material, T1.cant_salida, T1.costo_unidad, T1.costo_total, T1.id_equipo_destino, T2.destino
								FROM detalle_salidas AS T1
								JOIN detalle_es AS T2 ON T1.`salidas_id_salida` = T2.`no_vale` 
								WHERE salidas_id_salida =  '$id_salida'";*/
				$rs_detalle=mysql_query($sql_detalle);
				$reg=mysql_num_rows($rs_detalle);
				if($detalle=mysql_fetch_array($rs_detalle)){
					$ctrl=0;
					do{
						$idEntrada=obtenerDatosEntrada($detalle["materiales_id_material"]);
						$fechaEntrada="<label title='No Aplica al No Haber una Entrada Relacionada'>NA</label>";
						$cantidadEntrada="<label title='No Aplica al No Haber una Entrada Relacionada'>NA</label>";
						$existencia=obtenerDato("bd_almacen", "materiales", "existencia","id_material",$detalle["materiales_id_material"]);
						$req="ND";
						$fechaPedido="ND";
						
						if($idEntrada!="ND"){
							$fechaEntrada=obtenerDato("bd_almacen", "entradas", "fecha_entrada","id_entrada",$idEntrada);
							$fechaEntrada=modFecha($fechaEntrada,1);
							$cantidadEntrada=obtenerDatoBicondicional("bd_almacen","detalle_entradas","cant_entrada","entradas_id_entrada",$idEntrada,"materiales_id_material",$detalle["materiales_id_material"]);
							//Obtener el ID de la Requisicion o del Pedido
							$req=obtenerDato("bd_almacen", "entradas", "requisiciones_id_requisicion","id_entrada",$idEntrada);
							if ($req!=""){
								if (substr($req,0,3)=="PED")
									$fechaPedido=obtenerDato("bd_compras", "pedido", "fecha","id_pedido",$req);
								else{
									$fechaPedido=obtenerDato("bd_compras", "pedido", "fecha","requisiciones_id_requisicion",$req);
									if ($fechaPedido!="")
										$fechaPedido=modFecha($fechaPedido,1);
									$req=obtenerDato("bd_compras", "pedido", "id_pedido","requisiciones_id_requisicion",$req);
								}
								if ($req=="")
									$req="ND";
								if ($fechaPedido=="")
									$fechaPedido="ND";
								//Reabrir la conexion a la BD de almacen
								$conn=conecta("bd_almacen");
							}
							else
								$req="ND";
						}
						echo "
							<tr>
							<td class='$nom_clase' title='Clave del Pedido'>$req</td>
							<td class='$nom_clase' title='Fecha del Pedido'>$fechaPedido</td>
							
							<td class='$nom_clase' title='Departamento'>$row[depto_solicitante]</td>
							<td class='$nom_clase' title='Centro de Costos'>$centro_costos</td>
							<td class='$nom_clase' title='Cuenta'>$cuenta</td>
							<td class='$nom_clase' title='SubCuenta'>$subcuenta</td>";
							
						if($detalle["materiales_id_material"]=="¬NOVALE")
							echo "<td class='$nom_clase'>NO APLICA</td>";
						else
							echo "<td class='$nom_clase'>$detalle[materiales_id_material]</td>";
							
						echo "
							<td class='$nom_clase' title='Nombre del Material'>$detalle[nom_material]</td>
							<td class='$nom_clase' title='Unidad de Medida del Material'>$detalle[unidad_material]</td>
							<td class='$nom_clase' title='Existencia en Stock del Material'>$existencia</td>
							<td class='$nom_clase' title='Clave de la &Uacute;ltima Entrada de $detalle[nom_material]'>$idEntrada</td>
							<td class='$nom_clase' title='Fecha de la &Uacute;ltima Entrada de $detalle[nom_material]'>$fechaEntrada</td>
							<td class='$nom_clase' title='Cantidad Entrada de $detalle[nom_material]'>$cantidadEntrada</td>";
						
						if ($ctrl==0){
							echo "
								<td class='$nom_clase' rowspan='$reg' title='N&uacute;mero de Salida'>$row[id_salida]</td>
								<td class='$nom_clase' rowspan='$reg' title='Fecha de Salida'>".modFecha($row['fecha_salida'],1)."</td>
								<td class='$nom_clase' rowspan='$reg' title='Departamento Solicitor'>$row[depto_solicitante]</td>
								<td class='$nom_clase' rowspan='$reg' title='Solicitante'>$row[solicitante]</td>";
								if($centro_costos != "N/A"){
									echo "<td class='$nom_clase' rowspan='$reg' title='Destino del Material'>$centro_costos</td>";
								} else {
									echo "<td class='$nom_clase' rowspan='$reg' title='Destino del Material'>$row[destino]</td>";
								}
								echo "<td class='$nom_clase' rowspan='$reg' title='Turno en el que el Material Sali&oacute;'>$row[turno]</td>
								<td class='$nom_clase' rowspan='$reg' title='N&uacute;mero de Vale'>$row[no_vale]</td>";
						}
								
						echo "	
							<td class='$nom_clase' title='Equipo al Que Va Destinado el Material'>$detalle[id_equipo_destino]</td>
							<td class='$nom_clase' title='Cantidad de Salida del Material'>$detalle[cant_salida]</td>
							<td class='$nom_clase' title='Costo Unitario del Material'>$".number_format($detalle["costo_unidad"],2,".",",")."</td>
							<td class='$nom_clase' title='Costo Total del Material'>$".number_format($detalle["costo_total"],2,".",",")."</td>
							<td class='$nom_clase' title='Tipo Moneda'>$detalle[moneda]</td>
						</tr>";
						$ctrl++;
						//Operación que mostrara el total del costo de los materiales
						if($detalle["moneda"] == "PESOS")
							$cant_total_pesos += $detalle['costo_total'];
						else if($detalle["moneda"] == "DOLARES")
							$cant_total_dolares += $detalle['costo_total'];
						else if($detalle["moneda"] == "EUROS")
							$cant_total_euros += $detalle['costo_total'];
						$cant_total += $detalle['costo_total'];
					}while($detalle=mysql_fetch_array($rs_detalle));
				}	
									
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";						
			}while($row = mysql_fetch_array($rs));

			//Colocar el Check para cuando se selecciona VER TODO
			echo "
				<tr>
					<td colspan='22'>&nbsp;</td>
					<td class='nombres_columnas' rowspan='3'>TOTAL</td>
					<td class='nombres_columnas'>$".number_format($cant_total_pesos,2,".",",")."</td>
					<td class='nombres_columnas'>PESOS</td>
				</tr>
				<tr>
					<td colspan='22'>&nbsp;</td>
					<td class='nombres_columnas'>$".number_format($cant_total_dolares,2,".",",")."</td>
					<td class='nombres_columnas'>DOLARES</td>
				</tr>
				<tr>
					<td colspan='22'>&nbsp;</td>
					<td class='nombres_columnas'>$".number_format($cant_total_euros,2,".",",")."</td>
					<td class='nombres_columnas'>EUROS</td>
				</tr>
				<tr>
					<td colspan='23'>&nbsp;</td>
					<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>
				</tr>";
			?>
			</table><?php
			$band=1;
		}
		else{
			echo "</br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>Del <strong><u>$fechaI</u></strong> al 
					<strong><u>$fechaC</u></strong> NO se registraron Salidas</p>";
		}?>
		<?php
		return $band;
	}//Cierre de la funcion mostrarOrdenesCompra($theDate,$theDate2)
	
	function obtenerDatosEntrada($valor){
		$conn=conecta("bd_almacen");
		$dato="ND";
		$sql_stm="SELECT MAX(entradas_id_entrada) AS id_entrada FROM detalle_entradas WHERE materiales_id_material='$valor'";
		$rs=mysql_query($sql_stm);
		$datos=mysql_fetch_array($rs);
		if ($datos[0]!="")
			$dato=$datos[0];
		return $dato;
	}
	
	function obtenerDatosCentroCostos($valor,$tabla,$busq){
		$conn_rec=conecta("bd_recursos");
		$dato="N/A";
		$sql_stm_rec="SELECT descripcion FROM $tabla WHERE $busq='$valor'";
		$rs_rec=mysql_query($sql_stm_rec);
		$datos_rec=mysql_fetch_array($rs_rec);
		if ($datos_rec[0]!="")
			$dato=$datos_rec[0];
		return $dato;
		mysql_close($conn_rec);
	}
	
	function obtenerCentroCostos($valor){
		$conn_rec=conecta("bd_recursos");
		$dato=" AND (";
		$sql_stm_rec="SELECT * FROM control_costos WHERE descripcion LIKE '%$valor%'";
		$rs_rec=mysql_query($sql_stm_rec);
		$num_reg = mysql_num_rows($rs_rec);
		
		$datos_rec=mysql_fetch_array($rs_rec);
		if ($datos_rec){
			$aux = 1;
			do{
				$dato .= "destino = '$datos_rec[id_control_costos]'";
				
				if($aux != $num_reg){
					$dato .= " OR ";
				}
				$aux++;
			}while($datos_rec=mysql_fetch_array($rs_rec));
			$dato .= ")";
		}
		mysql_close($conn_rec);
		return $dato;
	}
?>
 
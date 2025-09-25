<?php
	//Esta funcion desplega las entradas registradas en el sistema	
	function mostrarSalidas($txt_fechaInicio,$txt_fechaCierre,$orden){
		//Realizar la conexion a la BD de Almacen			
		$conn = conecta('bd_almacen');
				
		//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
		$f1 = modFecha($txt_fechaInicio,3);
		$f2 = modFecha($txt_fechaCierre,3);

		//Crear la sentencia para mostrar el Reporte de Orden de Compra dadas las fechas escritas
		$stm_sql = "SELECT * FROM salidas WHERE fecha_salida>='$f1' AND fecha_salida<='$f2'";
		
		//Si se selecciono un criterio de ordenaci�n, adjuntarlo a la sentencia SQL
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
					
					
				/*Operaci�n que mostrara el total del costo de los materiales   */ 				
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
							
							
		if($clave=="todos"){//Consulta  que muestra la informaci�n inicial para obtener las salidas
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
				if($datos["materiales_id_material"]=="�NOVALE")
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
					
					
					/*Operaci�n que mostrara el total del costo de los materiales   */ 				
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
			//La ventana se redirecciona a la ventana de advertencia indicando que la consulta no gener� resultados
			echo "<meta http-equiv='refresh' content='5;url=advertencia.php'>";
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	 }//Fin de la funcion dibujarDetalle($campo,$valBuscar)
	 
	 
	 //Esta funcion desplega llos materiales registrados en las salidas del sistema
	function mostrarSalidas2($fechaI,$fechaC){
		//Variable de retorno de datos para resultados encontrados
		$band=0;
		//Realizar la conexion a la BD de Almacen
		$conn = conecta('bd_almacen');

		//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
		$f1 = modFecha($fechaI,3);
		$f2 = modFecha($fechaC,3);
		
		$filtro_centro_costo = $_POST["cmb_cc"];
		$filtro_cuentas = $_POST["cmb_cuenta"];
		
		//$stm_sql.= " ORDER BY T1.id_salida ASC";
		
		//Ejecutar la sentencia previamente creada
		$msg = "Reporte de Salidas generadas del <strong><u>$fechaI</u></strong> al <strong><u>$fechaC</u></strong>";
		
		$vuelta = 1;
		$reporte = false;
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		
		/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida*/
		$cant_total_pesos = 0;
		$cant_total_dolares = 0;
		$cant_total_euros = 0;
		$cant_total = 0;
		
		do{
			if($vuelta == 1){
				echo "
				<table class='tabla_frm' cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$msg</caption>
				<tr>
					<th class='nombres_columnas'>SOLICITANTE</th>
					<th class='nombres_columnas'>EQUIPO</th>
					<th class='nombres_columnas'>CENTRO DE COSTOS</th>
					<th class='nombres_columnas'>CUENTA</th>
					<th class='nombres_columnas'>FECHA SALIDA</th>
					<th class='nombres_columnas'>TURNO</th>
					
					<th class='nombres_columnas'>MATERIAL</th>
					<th class='nombres_columnas'>CANTIDAD</th>
					<th class='nombres_columnas'>UNIDAD MEDIDA</th>
					
					<th class='nombres_columnas'>COSTO UNITARIO</th>
					<th class='nombres_columnas'>COSTO TOTAL</th>
					<th class='nombres_columnas'>MONEDA</th>
				</tr>
				";
				
				$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle, 
											T3.id_control_costos, T3.id_cuentas
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											JOIN bd_mantenimiento.equipos AS T3 ON T2.id_equipo_destino = T3.id_equipo
											WHERE T1.fecha_salida
											BETWEEN  '$f1'
											AND  '$f2'
											AND T3.id_control_costos = '$filtro_centro_costo'";
				
				/*							
				$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle, T1.tipo
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											WHERE T1.fecha_salida
											BETWEEN  '$f1'
											AND  '$f2'
											AND T1.destino =  '$filtro_centro_costo'
											ORDER BY  `T2`.`id_equipo_destino` ASC ";
				*/
			}
			else if($vuelta == 2){
				$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle 
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											WHERE T1.fecha_salida
											BETWEEN  '$f1'
											AND  '$f2'
											AND T1.destino = '$filtro_centro_costo'
											AND T2.id_equipo_destino = 'N/A'";
			}
			else if($vuelta == 3){
				$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda AS moneda_detalle, T1.tipo,
											T4.id_control_costos, T4.id_cuentas 
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											JOIN detalle_es AS T3 ON T2.salidas_id_salida = T3.no_vale
											JOIN bd_recursos.empleados AS T4 ON T3.empleados_rfc_empleado = T4.rfc_empleado
											WHERE T1.fecha_salida
											BETWEEN  '$f1'
											AND  '$f2'
											AND T1.destino = 'EPP'
											AND T4.id_control_costos =  '$filtro_centro_costo'";
			}
			//echo "<br><br><br>".$stm_sql;
			$rs = mysql_query($stm_sql);
			if($row = mysql_fetch_array($rs)){
				$reporte = true;
				//Mostramos los registros
				
				do{
					$id_salida=$row["id_salida"];
					mysql_close($conn);
					
					if($vuelta == 1 || $vuelta == 3){
						$centro_costos = obtenerDatosCentroCostos($row["id_control_costos"],"control_costos","id_control_costos");
						$cuenta = obtenerDatosCentroCostos($row["id_cuentas"],"cuentas","id_cuentas");
					}
					else if($vuelta == 2){
						$centro_costos = obtenerDatosCentroCostos($row["destino"],"control_costos","id_control_costos");
						$cuenta = obtenerDatosCentroCostos($row["cuentas"],"cuentas","id_cuentas");
					}
					$moneda = $row["moneda_detalle"];
					if($moneda == "")
						$moneda = obtenerDatoMaterial($row["materiales_id_material"],"materiales","moneda","id_material");
					
					$group_material = obtenerDatoMaterial($row["materiales_id_material"],"materiales","grupo","id_material");
					
					$conn = conecta('bd_almacen');
					
					echo "
						<tr>
							<td class='$nom_clase' title='Empleado al que se entrego el material'>$row[solicitante]</td>
							<td class='$nom_clase' title='Equipo al Que Va Destinado el Material'>$row[id_equipo_destino]</td>
							<td class='$nom_clase' title='Centro de Costos'>$centro_costos</td>
							<td class='$nom_clase' title='Cuenta'>$cuenta</td>
							<td class='$nom_clase' title='Fecha de Salida'>".modFecha($row['fecha_salida'],1)."</td>
							<td class='$nom_clase' title='Turno en el que el Material Sali&oacute;'>$row[turno]</td>
							<td class='$nom_clase' title='Material'>$row[nom_material]</td>
							<td class='$nom_clase' title='Cantidad'>$row[cant_salida]</td>
							<td class='$nom_clase' title='Unidad de Medida'>$row[unidad_material]</td>
							<td class='$nom_clase' title='Costo Unitario'>$".number_format($row["costo_unidad"],2,".",",")."</td>
							<td class='$nom_clase' title='Costo Total'>$".number_format($row["costo_total"],2,".",",")."</td>
							<td class='$nom_clase' title='Moneda'>$moneda</td>
						</tr>
						";
					//Operaci�n que mostrara el total del costo de los materiales
					if($moneda == "PESOS")
						$cant_total_pesos += $row['costo_total'];
					else if($moneda == "DOLARES")
						$cant_total_dolares += $row['costo_total'];
					else if($moneda == "EUROS")
						$cant_total_euros += $row['costo_total'];
					
					$cant_total += $row['costo_total'];
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";						
				}while($row = mysql_fetch_array($rs));
			}
			$vuelta++;
		}while($vuelta <= 3);
		
		if($reporte){
			//Colocar el Check para cuando se selecciona VER TODO
			echo "
				<tr>
					<td colspan='9'>&nbsp;</td>
					<td class='nombres_columnas' rowspan='3'>TOTAL</td>
					<td class='nombres_columnas'>$".number_format($cant_total_pesos,2,".",",")."</td>
					<td class='nombres_columnas'>PESOS</td>
				</tr>
				<tr>
					<td colspan='9'>&nbsp;</td>
					<td class='nombres_columnas'>$".number_format($cant_total_dolares,2,".",",")."</td>
					<td class='nombres_columnas'>DOLARES</td>
				</tr>
				<tr>
					<td colspan='9'>&nbsp;</td>
					<td class='nombres_columnas'>$".number_format($cant_total_euros,2,".",",")."</td>
					<td class='nombres_columnas'>EUROS</td>
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
	
	function obtenerRFCEmpleadoSalida($id_salida){
		$conn_rec=conecta("bd_almacen");
		$dato="N/A";
		$sql_stm_rfc = "SELECT empleados_rfc_empleado 
						FROM  `detalle_es` 
						WHERE  `no_vale` LIKE  '$id_salida'";
		$rs_rfc=mysql_query($sql_stm_rfc);
		$datos_rfc=mysql_fetch_array($rs_rfc);
		if ($datos_rfc[0]!="")
			$dato=$datos_rfc[0];
		return $dato;
		mysql_close($conn_rec);
	}
	
	function obtenerDatosEmpleado($campo,$busq,$comp){
		$conn_rec=conecta("bd_recursos");
		$dato="N/A";
		$sql_stm_rfc = "SELECT $campo 
						FROM  `empleados` 
						WHERE  `$comp` = '$busq'";
		$rs_rfc=mysql_query($sql_stm_rfc);
		$datos_rfc=mysql_fetch_array($rs_rfc);
		if ($datos_rfc[0]!="")
			$dato=$datos_rfc[0];
		return $dato;
		mysql_close($conn_rec);
	}
	
	function obtenerNumeroRenglones($id,$cc,$cuenta){
		$conn_mat=conecta("bd_almacen");
		$dato=0;
		$sql_stm_num = "SELECT COUNT( * ) 
						FROM  `detalle_salidas` AS T1
						LEFT JOIN bd_mantenimiento.equipos AS T2
						ON T2.id_equipo = T1.id_equipo_destino
						WHERE T1.salidas_id_salida =  '$id'";
		
		if($cc!="" && $cc!="N/A" && $cc!="EPP")
			$sql_stm_num.= " AND T2.id_control_costos = '$cc'";
		
		if($cuenta!="" && $cuenta!="N/A")
			$sql_stm_num.= " AND T2.id_cuentas = '$cuenta'";
		
		$rs_num=mysql_query($sql_stm_num);
		$datos_num=mysql_fetch_array($rs_num);
		if ($datos_num[0]!="")
			$dato=$datos_num[0];
		return $dato;
		mysql_close($conn_mat);
	}
	
	function obtenerDatoMaterial($clave,$tabla,$valor,$busq){
		$conn_mat=conecta("bd_almacen");
		$dato=0;
		
		$sql_stm_num = "SELECT  $valor
						FROM  `$tabla` 
						WHERE  $busq =  '$clave'";
		
		$rs_num=mysql_query($sql_stm_num);
		if($datos_num=mysql_fetch_array($rs_num)){
			$dato=$datos_num[0];
		}
		
		return $dato;
		mysql_close($conn_mat);
	}
	
	function mostrasSalidasAuditoria(){
		$cont = 1;
		$vuelta = 1;
		$bandera = 0;
		$nom_clase = "renglon_gris";
		$cc = $_POST["cmb_cc"];
		$desc_cc = obtenerDatosCentroCostos($cc,"control_costos","id_control_costos");
		if($cc == "TODOS"){
			$msg = "Reporte de Salidas generadas del <strong><u>".$_POST['txt_fechaInicio']."</u></strong> al <strong><u>".$_POST['txt_fechaCierre']."</u></strong>";
		} else {
			$msg = "Reporte de Salidas $desc_cc generadas del <strong><u>".$_POST['txt_fechaInicio']."</u></strong> al <strong><u>".$_POST['txt_fechaCierre']."</u></strong>";
		}
		$fecha_ini = modFecha($_POST["txt_fechaInicio"],3);
		$fecha_fin = modFecha($_POST["txt_fechaCierre"],3);
		?>
		<table class='tabla_frm' cellpadding='5' width='100%'>
			<caption class='titulo_etiqueta'><?php echo $msg; ?></caption>
			<tr>
				<th class='nombres_columnas'>SOLICITANTE</th>
				<th class='nombres_columnas'>EQUIPO</th>
				<th class='nombres_columnas'>CENTRO DE COSTOS</th>
				<th class='nombres_columnas'>CUENTA</th>
				
				<th class='nombres_columnas'>MATERIAL</th>
				<th class='nombres_columnas'>CANTIDAD</th>
				<th class='nombres_columnas'>UNIDAD MEDIDA</th>
				
				<th class='nombres_columnas'>COSTO UNITARIO</th>
				<th class='nombres_columnas'>COSTO TOTAL</th>
				<th class='nombres_columnas'>MONEDA</th>
				<th class='nombres_columnas'>ESTATUS</th>
				
				<th class='nombres_columnas'>ENTRADAS</th>
				<th class='nombres_columnas'>SALIDA</th>
				<th class='nombres_columnas'>FECHA SALIDA</th>
				<th class='nombres_columnas'>TURNO</th>
				
			</tr>
			<?php 
			do{
				$conn = conecta('bd_almacen');
				if($cc == "TODOS"){
					if($vuelta == 1){
						$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda, 
											T3.id_control_costos, T3.id_cuentas, T2.entradas
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											JOIN bd_mantenimiento.equipos AS T3 ON T2.id_equipo_destino = T3.id_equipo
											WHERE T1.fecha_salida
											BETWEEN  '$fecha_ini'
											AND  '$fecha_fin'
											ORDER BY T2.id_equipo_destino";
						$stm_sql_1 = $stm_sql;
					} else if($vuelta == 2){
						$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, T2.entradas,
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda 
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											WHERE T1.fecha_salida
											BETWEEN  '$fecha_ini'
											AND  '$fecha_fin'
											AND T1.destino != 'EPP'
											AND T2.id_equipo_destino = 'N/A'";
						$stm_sql_2 = $stm_sql;
					} else if($vuelta == 3){
						$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda, T1.tipo,
											T4.id_control_costos, T4.id_cuentas, T2.entradas 
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											JOIN detalle_es AS T3 ON T2.salidas_id_salida = T3.no_vale
											JOIN bd_recursos.empleados AS T4 ON T3.empleados_rfc_empleado = T4.rfc_empleado
											WHERE T1.fecha_salida
											BETWEEN  '$fecha_ini'
											AND  '$fecha_fin'
											AND T1.destino = 'EPP'";
						$stm_sql_3 = $stm_sql;
					}
				} else {
					if($vuelta == 1){
						$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda, 
											T3.id_control_costos, T3.id_cuentas, T2.entradas
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											JOIN bd_mantenimiento.equipos AS T3 ON T2.id_equipo_destino = T3.id_equipo
											WHERE T1.fecha_salida
											BETWEEN  '$fecha_ini'
											AND  '$fecha_fin'
											AND T3.id_control_costos = '$cc'";
						$stm_sql_1 = $stm_sql;
					} else if($vuelta == 2){
						$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, T2.entradas,
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda 
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											WHERE T1.fecha_salida
											BETWEEN  '$fecha_ini'
											AND  '$fecha_fin'
											AND T1.destino = '$cc'
											AND T2.id_equipo_destino = 'N/A'";
						$stm_sql_2 = $stm_sql;
					} else if($vuelta == 3){
						$stm_sql = "SELECT DISTINCT T1.id_salida, T1.fecha_salida, T1.solicitante, T1.destino, T1.turno, T1.cuentas, 
											T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, T2.costo_total, T2.id_equipo_destino, T2.moneda, T1.tipo,
											T4.id_control_costos, T4.id_cuentas, T2.entradas 
											FROM salidas AS T1
											JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
											JOIN detalle_es AS T3 ON T2.salidas_id_salida = T3.no_vale
											JOIN bd_recursos.empleados AS T4 ON T3.empleados_rfc_empleado = T4.rfc_empleado
											WHERE T1.fecha_salida
											BETWEEN  '$fecha_ini'
											AND  '$fecha_fin'
											AND T1.destino = 'EPP'
											AND T4.id_control_costos =  '$cc'";
						$stm_sql_3 = $stm_sql;
					}
				}
				$rs = mysql_query($stm_sql);
				if($rs){
					$temp = 0;
					while($row = mysql_fetch_array($rs)){
						
						if($temp == 0){
							$renglones = obtenerRenglones($row['id_salida']);
						}
						
						$ent_ped = obtenerPedEntradas($row["entradas"]);
						$desc_cc = obtenerDatosCentroCostos($row['destino'],"control_costos","id_control_costos");
						$desc_cuenta = obtenerDatosCentroCostos($row['cuentas'],"cuentas","id_cuentas");
						
						if($vuelta == 1 || $vuelta == 3){
							$desc_cc = obtenerDatosCentroCostos($row["id_control_costos"],"control_costos","id_control_costos");
							$desc_cuenta = obtenerDatosCentroCostos($row["id_cuentas"],"cuentas","id_cuentas");
						}
						
						$total_mat = $row["cant_salida"] * $row["costo_unidad"];
						$moneda = $row["moneda"];
						$estatus = obtenerDatoMaterial($row["materiales_id_material"],"materiales","estatus","id_material");
						if($moneda == "")
							$moneda = obtenerDatoMaterial($row["materiales_id_material"],"materiales","moneda","id_material");
						?>
						<tr>
							<?php
							if($temp == 0){
							?>
								<td class='<?php echo $nom_clase; ?>' rowspan="<?php echo $renglones; ?>"><?php echo $row['solicitante']; ?></td>
							<?php
							}
							?>
							<td class='<?php echo $nom_clase; ?>'><?php echo $row['id_equipo_destino']; ?></td>
							<td class='<?php echo $nom_clase; ?>'><?php echo $desc_cc; ?></td>
							<td class='<?php echo $nom_clase; ?>'><?php echo $desc_cuenta; ?></td>
							<td class='<?php echo $nom_clase; ?>'><?php echo $row['nom_material']; ?></td>
							<td class='<?php echo $nom_clase; ?>'><?php echo $row['cant_salida']; ?></td>
							<td class='<?php echo $nom_clase; ?>'><?php echo $row['unidad_material']; ?></td>
							<td class='<?php echo $nom_clase; ?>'><?php echo $row['costo_unidad']; ?></td>
							<td class='<?php echo $nom_clase; ?>'><?php echo $total_mat; ?></td>
							<td class='<?php echo $nom_clase; ?>'><?php echo $moneda; ?></td>
							<td class='<?php echo $nom_clase; ?>'><?php echo $estatus; ?></td>
							<td class='<?php echo $nom_clase; ?>' style="white-space: nowrap;"><?php echo $ent_ped; ?></td>
							<?php
							if($temp == 0){
							?>
								<td class='<?php echo $nom_clase; ?>' rowspan="<?php echo $renglones; ?>"><?php echo $row['id_salida']; ?></td>
								<td class='<?php echo $nom_clase; ?>' rowspan="<?php echo $renglones; ?>"><?php echo modFecha($row['fecha_salida'],1); ?></td>
								<td class='<?php echo $nom_clase; ?>' rowspan="<?php echo $renglones; ?>"><?php echo $row['turno']; ?></td>
							<?php
							}
							?>
						</tr>
						<?php
						$cont++;
						$temp++;
						
						if($temp == $renglones)
							$temp = 0;
						
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}
					$bandera = 1;
				} else {
					$bandera = 0;
					echo mysql_error()."<br>";
				}
				$vuelta++;
				mysql_close($conn);
			}while($vuelta <= 3);
			?>
		</table>
		<?php
		return array($bandera,$stm_sql_1,$stm_sql_2,$stm_sql_3,$msg);
	}
	
	function obtenerRenglones($id){
		$conn_mat=conecta("bd_almacen");
		$dato=0;
		$sql_stm_num = "SELECT COUNT( * ) 
						FROM  `detalle_salidas` 
						WHERE salidas_id_salida =  '$id'";
		
		$rs_num=mysql_query($sql_stm_num);
		
		$datos_num=mysql_fetch_array($rs_num);
		if ($datos_num[0]!="")
			$dato=$datos_num[0];
		
		return $dato;
		mysql_close($conn_mat);
	}
	
	function obtenerPedEntradas($entradas){
		$dato = "";
		$arreglo_ent = explode(",",$entradas);
		
		for($i=0; $i < count($arreglo_ent); $i++){
			$con_entradas = conecta("bd_almacen");
			$stm_sql_entradas = "SELECT  `requisiciones_id_requisicion` ,  `comp_directa` 
								 FROM  `entradas`
								 WHERE  `id_entrada` LIKE  '$arreglo_ent[$i]'";
			$rs_entradas = mysql_query($stm_sql_entradas);
			if(!$rs_entradas){
				echo mysql_error();
			}
			$dato_entradas = mysql_fetch_array($rs_entradas);
			if($dato_entradas["comp_directa"] == "N/A"){
				$pedido = "COMPRA DIRECTA";
				$dato .= $pedido."->".$arreglo_ent[$i]."<br>";
			} else {
				$pedido = $dato_entradas["requisiciones_id_requisicion"];
				$requi = obtenerReqPedido($pedido);
				$dato .= $requi."->".$pedido."->".$arreglo_ent[$i]."<br>";
			}
		}
		return $dato;
	}
	
	function obtenerReqPedido($pedido){
		$dato = "N/A";
		
		$con_pedido = conecta("bd_compras");
		
		$stm_sql_pedidos = "SELECT  `requisiciones_id_requisicion` 
							FROM  `pedido` 
							WHERE  `id_pedido` LIKE  '$pedido'";
		$rs_pedidos = mysql_query($stm_sql_pedidos);
		if(!$rs_pedidos){
			echo mysql_error();
		}
		$dato_pedidos = mysql_fetch_array($rs_pedidos);
		
		$dato = $dato_pedidos[0];
		
		return $dato;
	}
	
	function mostrarSalidasCat($fechaI,$fechaC){
		$band = 0;
		$f1 = modFecha($fechaI,3);
		$f2 = modFecha($fechaC,3);
		
		$filtro_cc = $_POST["cmb_cc"];
		$filtro_cuenta = $_POST["cmb_cuenta"];
		$filtro_cat = $_POST["cmb_cat"];
		$filtro_equipo = $_POST["cmb_equipo"];
		$dolar = $_POST["txt_dolar"];
		$euro = $_POST["txt_euro"];
		?>
		<table cellpadding='5' width='100%'>
			<tr>
			    <td colspan='10' align='center' class='titulo_etiqueta'>
					REPORTE DE SALIDAS DEL <?php echo $fechaI; ?> AL <?php echo $fechaC; ?> <br> CAMBIO MONEDA <br> DOLAR: <?php echo $dolar; ?> <br> EURO: <?php echo $euro; ?>
				</td>
  			</tr>      			
			<tr>					
				<td class='nombres_columnas'>ID&nbsp;SALIDA</td>
        		<td class='nombres_columnas'>FECHA</td>
				<td class='nombres_columnas'>SOLICITANTE</td>
		        <td class='nombres_columnas'>CENTRO&nbsp;DE&nbsp;COSTO</td>						
        		<td class='nombres_columnas'>CUENTA</td>
				<th class='nombres_columnas'>SUBCUENTA</th>
				<th class='nombres_columnas'>TURNO</th>
				<th class='nombres_columnas'>CLAVE&nbsp;MATERIAL</th>
				<th class='nombres_columnas'>MATERIAL</th>
				<th class='nombres_columnas'>UNIDAD&nbsp;MEDIDA</th>
				<th class='nombres_columnas'>CANTIDAD&nbsp;SALIDA</th>
				<th class='nombres_columnas'>COSTO&nbsp;UNITARIO</th>
				<th class='nombres_columnas'>COSTO&nbsp;TOTAL</th>
				<th class='nombres_columnas'>MONEDA</th>
				<th class='nombres_columnas'>EQUIPO</th>
				<th class='nombres_columnas'>CATEGORIA</th>
      		</tr>
			<?php
			if($filtro_cc == "DESARROLLO"){
				$arr_cc = array(0=>"CONT002",1=>"CONT010",2=>"CONT015");
			}
			else if($filtro_cc == "ZARPEO"){
				$arr_cc = array(0=>"CONT016",1=>"CONT003",2=>"CONT004",3=>"CONT005",4=>"CONT014");
			}
			else if($filtro_cc == "PLANTAS"){
				$arr_cc = array(0=>"CONT012",1=>"CONT020",2=>"CONT021");
			} else {
				$arr_cc = array(0=>$filtro_cc);
			}
			for($i=0; $i<count($arr_cc); $i++){
				$conn = conecta('bd_almacen');
				$band = verDetalleSalidasCat($f1,$f2,$arr_cc[$i],$filtro_cuenta,$filtro_cat,$filtro_equipo,$dolar,$euro,$band);
				mysql_close($conn);
			}
		?>
		</table>
		<?php
		return $band;
	}
	
	function verDetalleSalidasCat($f1,$f2,$id_cc,$id_cuenta,$id_cat,$id_equipo,$dolar,$euro,$band){
		$stm_sql_salidas = "SELECT DISTINCT T2.salidas_id_salida, T1.fecha_salida, T1.solicitante, IF( T1.destino =  'EPP', T4.id_control_costos, T1.destino ) AS centro_costo, IF( T1.destino =  'EPP', T4.id_cuentas, T1.cuentas ) AS cuenta, T1.subcuentas, T1.turno, T2.materiales_id_material, T2.nom_material, T2.unidad_material, T2.cant_salida, T2.costo_unidad, IF( T2.moneda =  '', T5.moneda, T2.moneda ) AS moneda, T2.id_equipo_destino, T5.categoria
							FROM salidas AS T1
							JOIN detalle_salidas AS T2 ON T1.id_salida = T2.salidas_id_salida
							LEFT JOIN detalle_es AS T3 ON T1.no_vale = T3.no_vale
							AND T2.materiales_id_material = T3.materiales_id_material
							LEFT JOIN bd_recursos.empleados AS T4 ON T3.empleados_rfc_empleado = T4.rfc_empleado
							JOIN materiales AS T5 ON T5.id_material = T2.materiales_id_material
							WHERE fecha_salida
							BETWEEN  '$f1'
							AND  '$f2'";
		
		if($id_cc != "TODOS"){
			$stm_sql_salidas .= " AND (
									T1.destino =  '$id_cc'
									OR (
										T1.destino =  'EPP'
										AND T4.id_control_costos =  '$id_cc'
									)
								)";
		}
		if($id_cuenta != "TODAS"){
			$stm_sql_salidas .= " AND (
									T1.cuentas =  '$id_cuenta'
									OR (
										T1.destino =  'EPP'
										AND T4.id_cuentas =  '$id_cuenta'
									)
								)";
		}
		if($id_equipo != "TODOS"){
			$stm_sql_salidas .= " AND T2.id_equipo_destino =  '$id_equipo'";
		}
		if($id_cat != "TODAS"){
			$stm_sql_salidas .= " AND T5.categoria = '$id_cat'";
		}
		$stm_sql_salidas .= " ORDER BY cuenta, categoria, centro_costo";
		$rs_salidas = mysql_query($stm_sql_salidas);
		if($rs_salidas){
			$cont = 0;
			$nom_clase = "renglon_blanco";
			if($dato_salidas = mysql_fetch_array($rs_salidas)){
				do{
					$categoria = strtoupper(obtenerDatoTabla('categorias_mat','id_categoria',$dato_salidas['categoria'],"bd_almacen"));
					$conrol_costos = strtoupper(obtenerDatoTabla('control_costos','id_control_costos',$dato_salidas['centro_costo'],"bd_recursos"));
					$cuenta = strtoupper(obtenerDatoTabla('cuentas','id_cuentas',$dato_salidas['cuenta'],"bd_recursos"));
					$subcuenta = strtoupper(obtenerDatoTabla('subcuentas','id_subcuentas',$dato_salidas['subcuentas'],"bd_recursos"));
					$costo_unit = $dato_salidas["costo_unidad"];
					if($dato_salidas["moneda"] == "DOLARES"){
						$costo_unit = $costo_unit * $dolar;
					}
					else if($dato_salidas["moneda"] == "EUROS"){
						$costo_unit = $costo_unit * $euro;
					}
					$costo_total = $dato_salidas['cant_salida'] * $costo_unit;
					?>
					<tr>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['salidas_id_salida']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo modFecha($dato_salidas['fecha_salida'],1); ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['solicitante']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $conrol_costos; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $cuenta; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $subcuenta; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['turno']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['materiales_id_material']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['nom_material']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['unidad_material']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['cant_salida']; ?></td>
						<td class="<?php echo $nom_clase; ?>">$<?php echo number_format($costo_unit,2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>">$<?php echo number_format($costo_total,2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>">PESOS</td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $dato_salidas['id_equipo_destino']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo $categoria; ?></td>
					</tr>
					<?php
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($dato_salidas = mysql_fetch_array($rs_salidas));
				$band = 1;
			}
		}
		return $band;
	}
	
	function obtenerDatoTabla($tabla,$busq,$valor,$bd){
		$dat = "N/A"; 
		$con = conecta("$bd");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		return $dat;
	}
?>
 
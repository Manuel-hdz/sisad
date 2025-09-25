<?php
	
	function mostrarBitacoras(){
		$id_ubicacion= $_POST['cmb_ubicacion'];
		$ubicacion= $_POST['txt_cuadrilla'];
		$id_presupuesto=$_POST['cmb_periodo'];
		$fecha_ini = modFecha($_POST['txt_fechaIni'],3);
		$fecha_fin = modFecha($_POST['txt_fechaFin'],3);
		$presupuesto = obtenerDatosTabla("presupuesto","periodo","id_presupuesto",$id_presupuesto,"bd_gerencia");
		
		$conn = conecta("bd_gerencia");
		
		$stm_sql = "SELECT * 
					FROM bitacora AS T1
					JOIN detalle_bitacora AS T2
					USING ( id_bitacora ) 
					JOIN bd_recursos.control_costos AS T3
					USING ( id_control_costos ) 
					WHERE id_control_costos LIKE  '$id_ubicacion'
					AND id_presupuesto LIKE  '$id_presupuesto'
					AND fecha
					BETWEEN  '$fecha_ini'
					AND  '$fecha_fin'
					AND puesto LIKE  'lanzador'";
		
		$msg= "Bitacoras de <em><u> $ubicacion </u></em> del Periodo <em><u> $presupuesto </u></em> entre las Fechas <em><u> ".$_POST['txt_fechaIni']." </u></em> al <em><u> ".$_POST['txt_fechaFin']." </u></em>";
		$msg_error= "<label class='msje_correcto' align='center'>No se Encontr&oacute;n Bitacoras de <em><u> $ubicacion </u></em> del Periodo <em><u> $presupuesto </u></em> entre las Fechas <em><u> ".$_POST['txt_fechaIni']." </u></em> al <em><u> ".$_POST['txt_fechaFin']." </u></em>";
		
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$nom_clase = "renglon_gris";
				$cont = 1;
				?>
				<table cellpadding='5' width='100%'>				
					<caption class='titulo_etiqueta'><?php echo $msg; ?></caption>
					<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>DESTINO</td>
						<td class='nombres_columnas' align='center'>CUADRILLA</td>
						<td class='nombres_columnas' align='center'>LANZADOR</td>
						<td class='nombres_columnas' align='center'>AVANCE</td>
					</tr>
					<?php
					do{
						echo "
						<tr>
							<td class='nombres_filas' width='5%' align='center'>
								<input type='radio' name='rdb_idBitacora' value='$datos[id_bitacora]' required='required'/>
								<input type='hidden' name='cmb_ubicacion' id='cmb_ubicacion' value='$_POST[cmb_ubicacion]'/>
								<input type='hidden' name='txt_cuadrilla' id='txt_cuadrilla' value='$_POST[txt_cuadrilla]'/>
								<input type='hidden' name='cmb_periodo' id='cmb_periodo' value='$_POST[cmb_periodo]'/>
								<input type='hidden' name='txt_fechaIni' id='txt_fechaIni' value='$_POST[txt_fechaIni]'/>
								<input type='hidden' name='txt_fechaFin' id='txt_fechaFin' value='$_POST[txt_fechaFin]'/>
							</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase' align='center'>$datos[descripcion]</td>
							<td class='$nom_clase' align='center'>$datos[id_cuadrilla]</td>
							<td class='$nom_clase' align='center'>$datos[nombre_emp]</td>
							<td class='$nom_clase' align='center'>".number_format($datos['avance'],2,".",",")."</td>
						</tr>";
						
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}while($datos = mysql_fetch_array($rs));
					?>
				</table>
		<?php
			} else {
				echo "<br><br><br><br><br><br><br><br><br><br><br>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;".$msg_error;
			}
		} else {
			echo "<br><br><br><br><br><br><br><br><br><br><br>&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;".$msg_error;
		}
	}
	
	function obtenerDatosTabla($tabla,$retorno,$comp,$valor,$bd){
		$dato = "";
		$conec = conecta($bd);
		$stm_sql = "SELECT  `$retorno` 
					FROM  `$tabla` 
					WHERE  `$comp` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dato = $datos[0];
			}
		}
		mysql_close($conec);
		return $dato;
	}
?>
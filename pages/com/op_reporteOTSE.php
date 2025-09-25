<?php
	function mostrarOTSE(){
		$conn = conecta("bd_mantenimiento");
		
		$area = $_POST["cmb_area"];
		$completada = $_POST["cmb_estado"];
		$proveedor = $_POST["txt_nomProveedor"];
		$fecha_ini = modFecha($_POST["txt_fechaIni"],3);
		$fecha_fin = modFecha($_POST["txt_fechaFin"],3);
		$msg_titulo = "Reporte de OTSE Correspondiente al Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		
		$stm_sql = "SELECT * 
					FROM orden_servicios_externos
					JOIN actividades_realizadas ON id_orden = orden_servicios_externos_id_orden
					WHERE fecha_entrega
					BETWEEN  '$fecha_ini'
					AND  '$fecha_fin'";
		
		if($area != ""){
			$stm_sql .= " AND depto LIKE '$area'";
			$msg_titulo .= " del &Aacute;rea de <u><em>$area</em></u>";
		}
		if($completada != ""){
			$stm_sql .= " AND complementada LIKE '$completada'";
			
			$estado = "COMPLEMENTADAS";
			if($completada=="NO")
				$estado = "NO COMPLEMENTADAS";	
			$msg_titulo .= ", <u><em>$estado</em></u>";
		}
		if($proveedor != ""){
			$stm_sql .= " AND nom_proveedor LIKE '$proveedor'";
			$msg_titulo .= " del Proveedor <u><em>$proveedor</em></u>";
		}
		
		$stm_sql .= " ORDER BY depto, fecha_entrega, orden_servicios_externos_id_orden";
		
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			?>
			<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td colspan="14" align="center" class="titulo_etiqueta"><?php echo $msg_titulo; ?></td>
				</tr>
				<tr>
					<td class="nombres_columnas" align="center">ID ORDEN</td>
					<td class="nombres_columnas" align="center">FECHA</td>
					<td class="nombres_columnas" align="center">CLASIFICACION</td>
					<td class="nombres_columnas" align="center">PROVEEDOR</td>
					<td class="nombres_columnas" align="center">REALIZO</td>
					<td class="nombres_columnas" align="center">SOLICITO</td>
					<td class="nombres_columnas" align="center">AUTORIZO</td>
					<td class="nombres_columnas" align="center">DEPARTAMENTO</td>
					<td class="nombres_columnas" align="center">COMPLEMENTADA</td>
					<td class="nombres_columnas" align="center">SISTEMA</td>
					<td class="nombres_columnas" align="center">APLICACION</td>
					<td class="nombres_columnas" align="center">DESCRIPCION</td>
					<td class="nombres_columnas" align="center">EQUIPO</td>
					<td class="nombres_columnas" align="center">COSTO</td>
				</tr>
				<?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				$crear = true;
				$avance = 0;
				do{
					$rows = obtenerNumRowsOTSE($datos["orden_servicios_externos_id_orden"]);
					?>
					<tr>
						<?php
						if($crear){
						?>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan="<?php echo $rows; ?>">
								<?php echo $datos["orden_servicios_externos_id_orden"]; ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan="<?php echo $rows; ?>">
								<?php echo modFecha($datos["fecha_entrega"],1); ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan="<?php echo $rows; ?>">
								<?php echo $datos["clasificacion"]; ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan="<?php echo $rows; ?>">
								<?php echo $datos["nom_proveedor"]; ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan="<?php echo $rows; ?>">
								<?php echo $datos["encargado_compras"]; ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan="<?php echo $rows; ?>">
								<?php echo $datos["solicito"]; ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan="<?php echo $rows; ?>">
								<?php echo $datos["autorizo"]; ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan="<?php echo $rows; ?>">
								<?php echo $datos["depto"]; ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan="<?php echo $rows; ?>">
								<?php echo $datos["complementada"]; ?>
							</td>
						<?php
						}
						?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<?php echo $datos["sistema"]; ?>
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<?php echo $datos["aplicacion"]; ?>
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<?php echo $datos["descripcion"]; ?>
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<?php echo $datos["equipo"]; ?>
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<?php echo "$".number_format($datos["costo_actividad"],2,".",","); ?>
						</td>
					</tr>
					<?php
					$avance++;
					if($rows - $avance > 0){
						$crear = false;
					} else {
						$avance = 0;
						$crear = true;
					}
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
			
		}
		return array($stm_sql, $msg_titulo);
	}
	
	function obtenerNumRowsOTSE($id_otse){
		$stm_sql_nr = "SELECT * 
					FROM  `actividades_realizadas` 
					WHERE  `orden_servicios_externos_id_orden` LIKE  '$id_otse'";
		$rs_nr = mysql_query($stm_sql_nr);
		$renglones = mysql_num_rows($rs_nr);
		
		return $renglones;
	}
?>
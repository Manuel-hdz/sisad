<?php
	function mostrarGastos(){
		$band = 0;
		
		$conn = conecta("bd_compras");
		$fecha_ini = modFecha($_POST["txt_fechaIni"],3);
		$fecha_fin = modFecha($_POST["txt_fechaFin"],3);
		$msg_titulo = "Reporte de Pagos Correspondiente al Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		$stm_sql = "SELECT * 
					FROM  `gastos` 
					WHERE  `fecha_gasto` BETWEEN  '$fecha_ini' 
					AND '$fecha_fin' 
					ORDER BY fecha_gasto";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				?>
				<table cellpadding='5' width='100%'>
					<tr>
						<td colspan='3' align='center' class='titulo_etiqueta'><?php echo $msg_titulo; ?></td>
					</tr>
					<tr>
						<td class='nombres_columnas' align="center">FECHA</td>
						<td class='nombres_columnas' align="center">FACTURA</td>
						<td class='nombres_columnas' align="center">DESCRIPCION</td>
						<td class='nombres_columnas' align="center">IMPORTE</td>
					</tr>
					<?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{
						?>
						<tr>
							<td class='<?php echo $nom_clase; ?>' align="center"><?php echo modFecha($datos['fecha_gasto'],2); ?></td>
							<td class='<?php echo $nom_clase; ?>' align="center"><?php echo $datos['factura']; ?></td>
							<td class='<?php echo $nom_clase; ?>' align="center"><?php echo $datos['descripcion']; ?></td>
							<td class='<?php echo $nom_clase; ?>' align="center"><?php echo "$".number_format($datos['importe'],2,".",","); ?></td>
						</tr>
						<?php
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}while($datos = mysql_fetch_array($rs));
					?>
				</table>
				<?php
				$band = 1;
			} else {
				?>
				<table cellpadding='5' width='100%'>
					<tr>
						<td align="center">
							<br><br><br><br><br><br><br><br><br><br><br><br><br>
							<label class='msje_correcto'>NO HAY REGISTROS ENTRE LAS FECHAS SELECCIONADAS</label>
						</td>
					</tr>
				</table>
				<?php
			}
		} else {
			?>
			<table cellpadding='5' width='100%'>
				<tr>
					<td align="center">
						<br><br><br><br><br><br><br><br><br><br><br><br><br>
						<label class='msje_correcto'>NO HAY REGISTROS ENTRE LAS FECHAS SELECCIONADAS</label>
					</td>
				</tr>
			</table>
			<?php
		}
		mysql_close($conn);
		return array($band,$msg_titulo,$stm_sql);
	}
?>
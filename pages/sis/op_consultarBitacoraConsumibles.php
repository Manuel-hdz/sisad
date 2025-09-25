<?php
	function consultarBitacoraConsumibles(){
		?>
		<div class="borde_seccion2" id="tabla-consumibles" align="center">
		<?php
		$conec = conecta("bd_sistemas");
		$id_cons = $_POST["cmb_consumibles"];
		$fecha_ini = modFecha($_POST['txt_fechaIni'],3);
		$fecha_fin = modFecha($_POST['txt_fechaFin'],3);
		if(isset($_POST["ckb_incluirFechas"]))
			$fechas = $_POST["ckb_incluirFechas"];
		else 
			$fechas = "NO";
		
		if($id_cons != "TODOS"){
			$desc_titulo = obtenerDatoConsumible($id_cons,"descripcion")." ".obtenerDatoConsumible($id_cons,"color");
			$msg = "BITACORA DEL CONSUMIBLE: ".$desc_titulo;
			$stm_sql = "SELECT T2.descripcion, T2.color, T1.fecha, T1.cantidad, T1.tipo, T1.departamento, T1.empleado
						FROM bitacora_consumibles AS T1
						JOIN consumibles AS T2
						USING ( id_consumibles ) 
						WHERE T1.id_consumibles = '$id_cons'";
			if(isset($_POST["ckb_incluirFechas"])){
				$msg .= "<br>DEL ".modFecha($fecha_ini,7)." al ".modFecha($fecha_fin,7);
				$stm_sql .= " AND T1.fecha BETWEEN '$fecha_ini' AND '$fecha_fin'";
			}
			$stm_sql .= " ORDER BY T2.descripcion,T2.color,T1.fecha";
		}
		else {
			$msg = "BITACORA DEL CONSUMIBLE: TODOS";
			$stm_sql = "SELECT T2.descripcion, T2.color, T1.fecha, T1.cantidad, T1.tipo, T1.departamento, T1.empleado
						FROM bitacora_consumibles AS T1
						JOIN consumibles AS T2
						USING ( id_consumibles )";
			if(isset($_POST["ckb_incluirFechas"])){
				$msg .= "<br>DEL ".modFecha($fecha_ini,7)." al ".modFecha($fecha_fin,7);
				$stm_sql .= " WHERE T1.fecha BETWEEN '$fecha_ini' AND '$fecha_fin'";
			}
			$stm_sql .= " ORDER BY T2.descripcion,T2.color,T1.fecha";
		}
		$rs = mysql_query($stm_sql);
		if($rs){
			echo "<table cellpadding='5' width='100%'>";
			echo "<caption><p><strong>$msg</strong></p></caption>";
			echo "      			
				<tr>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
					<td class='nombres_columnas' align='center'>TIPO</td>
					<td class='nombres_columnas' align='center'>DEPARTAMENTO</td>
					<td class='nombres_columnas' align='center'>EMPLEADO</td>
				</tr>";
			
			$nom_clase = "renglon_gris";
			$cont = 1;
			
			while($datos = mysql_fetch_array($rs)){
				echo "<tr>
						<td class='$nom_clase'>$datos[descripcion] $datos[color]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
						<td class='$nom_clase'>$datos[cantidad]</td>";
				if($datos["tipo"] == "E")
					echo "<td class='$nom_clase'>ENTRADA</td>";
				else
					echo "<td class='$nom_clase'>SALIDA</td>";
				echo 	"<td class='$nom_clase'>$datos[departamento]</td>
						 <td class='$nom_clase'>$datos[empleado]</td>
					  </tr>";
				
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}
			echo "</table>";
		} else {
			echo "<p class='msje_correcto'><strong>No Hay Bitacoras con los Parametros de Busqueda Seleccionados</strong></p>";
		}
		?>
		</div>
		<div id="botones_pdf">
			<table width="100%">
				<tr>						
					<td align="center">
						<form action="guardar_reporte.php" method="post">
							<input name="hdn_consulta" type="hidden" value="<?php echo $stm_sql; ?>" />
							<input name="hdn_nomReporte" type="hidden" value="Bitacora de consumibles de impresion"/>
							<input name="hdn_origen" type="hidden" value="BitCons" />		
							<input name="hdn_msg" type="hidden" value="<?php echo $msg; ?>" />							
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
							onMouseOver="window.estatus='';return true"  />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="sbt_verPDF" type="button" value="Ver PDF" class="botones" 
							onclick="window.open('../../includes/generadorPDF/bitacoraConsumibles.php?consumible=<?php echo $id_cons; ?>&fecI=<?php echo $fecha_ini; ?>&fecF=<?php echo $fecha_fin; ?>&fec=<?php echo $fechas; ?>&','_blank',
							'top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; Bitacora de Consumibles" onclick="location.href='frm_consultarBitacoraConsumibles.php'"/>
						</form>
					</td>
				</tr>
			</table>			
		</div>
		<?php
	}
	
	function obtenerDatoConsumible($id,$busqueda){
		$conec = conecta("bd_sistemas");
		$stm_sql = "SELECT $busqueda FROM consumibles WHERE id_consumibles = '$id'";
		
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				return $datos[0];
			} else {
				return "Sin Dato";
			}
		} else {
			return "Sin Dato";
		}
	}
?>
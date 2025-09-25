<?php
	function mostrarBitacoras(){
		$fecha_ini = modFecha($_POST['txt_fechaIni'],3);
		$fecha_fin = modFecha($_POST['txt_fechaFin'],3);
		$estado = $_POST['cmb_estado'];
		$pagado = $_POST['cmb_pag'];
		$turno = $_POST['cmb_turno'];
		$conn=conecta("bd_comaro");
		
		$titulo_tabla = "Bitacoras del $_POST[txt_fechaIni] al $_POST[txt_fechaFin]";
		$stm_sql = "SELECT T1.id_bitacora , T1.id_empleados_empresa , CONCAT( T2.nombre,  ' ', T2.ape_pat,  ' ', T2.ape_mat ) AS nombre_empl, 
					T1.fecha_registo , T1.turno , T3.descripcion, T1.estado , T1.pagado, T3.costo_unit, T1.descuento
					FROM  bitacora_comensal AS T1
					JOIN bd_recursos.empleados AS T2
					USING (  id_empleados_empresa ) 
					JOIN menu AS T3
					USING (  id_menu ) 
					WHERE  fecha_registo 
					BETWEEN  '$fecha_ini'
					AND  '$fecha_fin'";
		if($estado != ""){
			$stm_sql .= " AND T1.estado = '$estado'";
			if($estado == "A")
				$titulo_tabla .= ", ESTADO: APARTADO";
			if($estado == "E")
				$titulo_tabla .= ", ESTADO: ENTREGADO";
		}
		if($pagado != ""){
			$stm_sql .= " AND T1.pagado = '$pagado'";
			$titulo_tabla .= ", PAGADO: $pagado";
		}
		if($turno != ""){
			$stm_sql .= " AND T1.turno = '$turno'";
			$titulo_tabla .= ", TURNO: $turno";
		}
		
		$rs=mysql_query($stm_sql);
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>$titulo_tabla</caption>";
			echo "	<tr>
						<td class='nombres_columnas_comaro' align='center'>ID EMPLEADO</td>
						<td class='nombres_columnas_comaro' align='center'>NOMBRE EMPLEADO</td>
						<td class='nombres_columnas_comaro' align='center'>FECHA</td>
						<td class='nombres_columnas_comaro' align='center'>TURNO</td>
						<td class='nombres_columnas_comaro' align='center'>PLATILLO</td>
						<td class='nombres_columnas_comaro' align='center'>ESTADO</td>
						<td class='nombres_columnas_comaro' align='center'>PAGADO</td>
						<td class='nombres_columnas_comaro' align='center'>COSTO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total_pesos = 0;
			do{	
				$costo = $datos['costo_unit'] * ($datos['descuento'] / 100);
				$costo = $datos['costo_unit'] - $costo;
				echo "<tr>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_empl]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_registo'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>";
				if($datos['estado'] == 'A')
					echo "<td class='$nom_clase' align='center'>APARTADO</td>";
				if($datos['estado'] == 'E')
					echo "<td class='$nom_clase' align='center'>ENTREGADO</td>";
					echo "<td class='$nom_clase' align='center'>$datos[pagado]</td>
						  <td class='$nom_clase' align='center'>$ ".number_format($costo,2,".",",")."</td>
					  </tr>";			
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				$cant_total_pesos += $costo;
			}while($datos=mysql_fetch_array($rs)); 
			echo "
				<tr>
					<td colspan='6'>&nbsp;</td>
					<td class='nombres_columnas_comaro'>TOTAL</td>
					<td class='nombres_columnas_comaro' align='center'>$".number_format($cant_total_pesos,2,".",",")."</td>
				</tr>";
			echo "</table>";
		} else {
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Bitacoras Registradas con los Parametros Seleccionados</p>";
		}
		mysql_close($conn);
	}
?>
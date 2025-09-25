<?php
	function sumarDiasFechaNomina($fecha,$dias){
		list($day,$mon,$year) = explode('/',$fecha);
		return date('d/m/Y',mktime(0,0,0,$mon,$day+$dias,$year));
	}
	
	function mostrarNominaBD(){
		$band="false";
		$id_nomina = $_POST["cmb_nomina"];
		$conn = conecta("bd_gerencia");
		$stm_sql_nomina  = "SELECT T1 . * , T2.descripcion
							FROM  `nominas` AS T1
							JOIN bd_recursos.control_costos AS T2
							USING ( id_control_costos ) 
							WHERE  `id_nomina` LIKE  '$id_nomina'";
		$rs_nomina = mysql_query($stm_sql_nomina);
		
		if($datos_nomina = mysql_fetch_array($rs_nomina)){
			$msje = "<strong>N&oacute;mina de <u><em>".$datos_nomina['descripcion']."</u></em> del <u><em>".modFecha($datos_nomina['fecha_inicio'],1)."</em></u> al <u><em>".modFecha($datos_nomina['fecha_fin'],1)."</em></u></strong>";
			?>
			<table cellpadding="5" width="100%" id="tabla-resultadosNomina"> 
				<caption><p class="msje_correcto"><?php echo $msje; ?></p></caption>
				<thead>
					<tr>
						<th class="nombres_columnas">NOMBRE</th>
						<?php
						for($i = 0; $i < $datos_nomina["num_dias"]; $i++){
							$fecha_enc = sumarDiasFechaNomina(modFecha($datos_nomina["fecha_inicio"],1),$i);
							echo "<th class='nombres_columnas'>$fecha_enc</th>";
						}
						?>
						<th class="nombres_columnas">SUELDO DIARIO</th>
						<th class="nombres_columnas">HORAS EXTRAS</th>
						<th class="nombres_columnas">GUARDIA</th>
						<th class="nombres_columnas">BONIFICACION</th>
						<th class="nombres_columnas">TOTAL</th>
						<th class="nombres_columnas">COMENTARIOS</th>
					</tr>
				</thead>
				<?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				?>
				<tbody>
					<?php
					$stm_sql_detalle_nominas = "SELECT T1. * , T2.ape_pat, T2.ape_mat, T2.nombre
												FROM detalle_nominas AS T1
												JOIN bd_recursos.empleados AS T2 ON T1.rfc_trabajador = T2.rfc_empleado
												WHERE id_nomina LIKE  '$id_nomina'";
					$rs_detalle_nominas = mysql_query($stm_sql_detalle_nominas);
					while($datos_detalle_nominas = mysql_fetch_array($rs_detalle_nominas)){
						?>
						<tr>
							<td class="<?php echo $nom_clase; ?>" align='center'>
								<?php echo $datos_detalle_nominas["ape_pat"]." ".$datos_detalle_nominas["ape_mat"]." ".$datos_detalle_nominas["nombre"]; ?>
							</td>
							<?php
							for($i = 0; $i < $datos_nomina["num_dias"]; $i++){
								$fecha_enc = sumarDiasFechaNomina(modFecha($datos_nomina["fecha_inicio"],1),$i);
								$fecha_enc = modFecha($fecha_enc,3);
								$stm_sql_asistencias = "SELECT asistencia
														FROM asistencia_empl_nom
														WHERE id_nomina LIKE  '$id_nomina'
														AND rfc_trabajador LIKE  '$datos_detalle_nominas[rfc_trabajador]'
														AND fecha_asistencia =  '$fecha_enc'";
								$rs_asistencia = mysql_query($stm_sql_asistencias);
								$datos_asistencia = mysql_fetch_array($rs_asistencia);
								if($datos_asistencia["asistencia"] == "FALTA"){
									$asistencia = "F";
									$fondo = "#999999";
								} else if($datos_asistencia["asistencia"] == "ASISTENCIA"){
									$asistencia = "A";
									$fondo = "#00802b";
								} else if($datos_asistencia["asistencia"] == "DESCANSO"){
									$asistencia = "D";
									$fondo = "#f9f906";
								} else if($datos_asistencia["asistencia"] == "INCAPACIDAD"){
									$asistencia = "I";
									$fondo = "#0033cc";
								} else if($datos_asistencia["asistencia"] == "ALCOHOLIMETRIA"){
									$asistencia = "AL";
									$fondo = "#cc0000";
								}
								?>
								<td class="<?php echo $nom_clase; ?>" align='center' style="background-color:<?php echo $fondo; ?>">
									<?php echo $asistencia; ?>
								</td>
								<?php
							}
							?>
							<td class="<?php echo $nom_clase; ?>" align='center'>
								$<?php echo number_format($datos_detalle_nominas["sueldo_diario"],2,".",","); ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align='center'>
								<?php echo $datos_detalle_nominas["horas_extra"]; ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align='center'>
								<?php echo $datos_detalle_nominas["guardia"]; ?> horas
							</td>
							<td class="<?php echo $nom_clase; ?>" align='center'>
								$<?php echo number_format($datos_detalle_nominas["bonificacion_empl"],2,".",","); ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align='center'>
								$<?php echo number_format($datos_detalle_nominas["total_pagado"],2,".",","); ?>
							</td>
							<td class="<?php echo $nom_clase; ?>" align='center'>
								<?php echo $datos_detalle_nominas["comentarios"]; ?>
							</td>
						</tr>
						<?php
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}
					?>
				</tbody>
			</table>
			<?php
			$band="true";
		} else {
			echo "<br><br><br><br><br><br><br><br><br><br><br><p class='msje_correcto' align='center'>No Hay Registros de N&oacute;mina</p>";
		}
		
		mysql_close($conn);
		if ($band=="false")
			return $band;
		else
			return $stm_sql_nomina."<br>".$stm_sql_detalle_nominas."<br>".$id_nomina."<br>".$msje;
	}
?>
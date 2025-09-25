<?php 
	include("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	
	if(isset($_POST['sbt_excel'])){
		if(isset($_POST['hdn_consulta'])){
			define("HOST", $_SERVER['HTTP_HOST']);
			$raiz = explode("/",$_SERVER['PHP_SELF']);
			define("SISAD",$raiz[1]);
			
			switch($hdn_tipoReporte){
				case "exportarNomina":
					exportarNomina($hdn_consulta,$hdn_consulta2,$id_nomina,$hdn_msje);
			}				
		}
	}
	
	function sumarDiasFechaNomina($fecha,$dias){
		list($day,$mon,$year) = explode('/',$fecha);
		return date('d/m/Y',mktime(0,0,0,$mon,$day+$dias,$year));
	}
	
	function exportarNomina($hdn_consulta,$hdn_consulta2,$id_nomina,$hdn_msje){
		include_once("../../includes/func_fechas.php");
		
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Nomina_Zarpeo.xls");
		$conn = conecta("bd_gerencia");
		$rs_nomina = mysql_query($hdn_consulta);
			
		if($datos_nomina = mysql_fetch_array($rs_nomina)){
			?>
			<head>
				<style>
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
										
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; }
					
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					
					.texto_totales {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #FFFFFF; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle;
					}
					.encabezados {
						font-family: Calibri; font-size: 16px; color: #000000; background-color: #E7E7E7; font-weight: bold;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					.celdas {
						font-family: Calibri; font-size: 16px; color: #000000; font-weight: normal;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					-->
				</style>
			</head>
			<body>
				<div id="tabla">
					<table width="1100">
						<tr></tr>
						<tr>
							<td></td>
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="250" height="100"/>
						</tr>
						<tr>
							<td></td><td></td>
							<td colspan="<?php echo $datos_nomina["num_dias"] + 2; ?>" rowspan="5" class="celdas" style="font-size: 25px; border-style: none; font-weight: bold;">
								NOMINA DE <?php echo $datos_nomina['descripcion']; ?>
							</td>
							<td class="celdas" rowspan="5" style="border-style: none; font-weight: bold;">
								Semana:
							</td>
							<td style="border-bottom-style: solid; border-bottom-width: 1px;" colspan="3" rowspan="3" align="center">
								<?php echo modFecha($datos_nomina['fecha_inicio'],1)." al ".modFecha($datos_nomina['fecha_fin'],1) ?>
							</td>
						</tr>
						<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
						<tr>
							<td></td>
							<th class="encabezados">NOMBRE</th>
							<?php
							for($i = 0; $i < $datos_nomina["num_dias"]; $i++){
								$fecha_enc = sumarDiasFechaNomina(modFecha($datos_nomina["fecha_inicio"],1),$i);
								echo "<th class='encabezados'>$fecha_enc</th>";
							}
							?>
							<th class="encabezados">SUELDO DIARIO</th>
							<th class="encabezados">HORAS EXTRAS</th>
							<th class="encabezados">GUARDIA (HRS)</th>
							<th class="encabezados">BONIFICACION</th>
							<th class="encabezados">TOTAL</th>
							<th class="encabezados">COMENTARIOS</th>
						</tr>
						<?php
						$total_sueldoD = 0;
						$total_bonos = 0;
						$total = 0;
						$rs_detalle_nominas = mysql_query($hdn_consulta2);
						while($datos_detalle_nominas = mysql_fetch_array($rs_detalle_nominas)){
							?>
							<tr>
								<td></td>
								<td class="celdas" style="font-weight: bold; background-color:yellow;">
									<u>
										<?php echo $datos_detalle_nominas["ape_pat"]." ".$datos_detalle_nominas["ape_mat"]." ".$datos_detalle_nominas["nombre"]; ?>
									</u>
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
									<td class="celdas" align='center' style="background-color:<?php echo $fondo; ?>">
										<?php echo $asistencia; ?>
									</td>
									<?php
								}
								?>
								<td class="celdas" style="mso-number-format:'Currency';font-weight: bold;">
									<u>
										<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>
									</u>
								</td>
								<td class="celdas">
									<?php 
									if($datos_detalle_nominas["horas_extra"] != 0) 
										echo $datos_detalle_nominas["horas_extra"]; 
									else 
										echo "-";
									?>
								</td>
								<td class="celdas">
									<?php 
									if($datos_detalle_nominas["guardia"] != 0) 
										echo $datos_detalle_nominas["guardia"]; 
									else 
										echo "-";
									?>
								</td>
								<td class="celdas" style="mso-number-format:'Currency';">
									<?php echo $datos_detalle_nominas["bonificacion_empl"]; ?>
								</td>
								<td class="celdas" style="mso-number-format:'Currency'; background-color:yellow;">
									<?php echo $datos_detalle_nominas["total_pagado"]; ?>
								</td>
								<td class="celdas">
									<?php echo $datos_detalle_nominas["comentarios"]; ?>
								</td>
							</tr>
							<?php
							$total_sueldoD += $datos_detalle_nominas["sueldo_diario"];
							$total_bonos += $datos_detalle_nominas["bonificacion_empl"];
							$total += $datos_detalle_nominas["total_pagado"];
						}
						?>
						<tr>
							<td></td><td></td>
							<td colspan=<?php echo $datos_nomina["num_dias"]; ?> class="celdas" style="font-weight: bold;">
								<u>TOTAL</u>
							</td>
							<td class="celdas" style="font-weight: bold; mso-number-format:'Currency';">
								<u><?php echo $total_sueldoD; ?></u>
							</td>
							<td class="celdas"></td>
							<td class="celdas"></td>
							<td class="celdas" style="font-weight: bold; mso-number-format:'Currency';">
								<u><?php echo $total_bonos; ?></u>
							</td>
							<td class="celdas" style="background-color:#33CC66; font-weight: bold; mso-number-format:'Currency';">
								<u><?php echo $total; ?></u>
							</td>
						</tr>
					</table>
				</div>
			</body>
			<?php
		}
		mysql_close($conn);
	}
?>
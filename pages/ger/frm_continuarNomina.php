<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarNomina.php");?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
			<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
			<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
			<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
			<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
			<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
			<script type="text/javascript" src="includes/ajax/cargarCatalogoSueldos.js"></script>
			<script src="../../includes/StickyTableHeaders/js/jquery.min.js"></script>
			<script src="../../includes/StickyTableHeaders/js/jquery.ba-throttle-debounce.min.js"></script>
			<script src="../../includes/StickyTableHeaders/js/jquery.stickyheader.js"></script>
			
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css"/>
			<link href="../../includes/StickyTableHeaders/css/component.css" rel="stylesheet" type="text/css"/>
			
			<style type="text/css">
				<!--
				#titulo-detalle {position:absolute;left:30px;top:146px;width:500px;height:23px;z-index:11;}
				#tabla-pedido-detalles{position:absolute;left:30px;top:525px;width:900px;height:150px;z-index:12; overflow:scroll;}
				#botones{position:absolute;left:30px;top:680px;width:900px;height:37px;z-index:13;}		
				#detalles_pedido{position:absolute;left:30px;top:150px;width:970px;height:460px;z-index:15; overflow:none;}
				#tabla-nomina {position:absolute;left:30px;top:190px;width:900px;height:308px;z-index:16;}		
				#lista-proveedores { position:absolute; left:540px; top:210px; width:321px; height:104px; z-index:17; }
				-->
			</style>
		</head>
		<body>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-detalle">N&oacute;mina de <?php echo $_POST["txt_ubicacion_con"]; ?></div>
			<?php
			if(!isset($_POST["btn_continuar"]) && !isset($_POST["btn_avance"])){
				$id_nom = $_POST["cmb_nomina"];
				$conn = conecta("bd_gerencia");
				$stm_sql_nominas = "SELECT * 
									FROM  `nominas` 
									WHERE  `id_nomina` LIKE  '$id_nom'";
				$rs_nominas = mysql_query($stm_sql_nominas);
				$datos_nominas = mysql_fetch_array($rs_nominas);
			?>
				<form name="continuar-Nomina" action="frm_continuarNomina.php" method="post">
					<div id="detalles_pedido" class="border_seccion">
						<table class="overflow-y">
							<thead>
								<tr>
									<th class="nombres_columnas">NOMBRE</th>
									<?php
									for($i = 0; $i < $datos_nominas["num_dias"]; $i++){
										$fecha_enc = sumarDiasFechaNomina(modFecha($datos_nominas["fecha_inicio"],1),$i);
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
							<tbody>
								<?php
								$cont = 1;
								$stm_sql_detalle_nominas = "SELECT T1. * , T2.ape_pat, T2.ape_mat, T2.nombre
															FROM detalle_nominas AS T1
															JOIN bd_recursos.empleados AS T2 ON T1.rfc_trabajador = T2.rfc_empleado
															WHERE id_nomina LIKE  '$id_nom'";
								$rs_detalle_nominas = mysql_query($stm_sql_detalle_nominas);
								while($datos_detalle_nominas = mysql_fetch_array($rs_detalle_nominas)){
								?>
									<tr>
										<th class="nombres_columnas"><?php echo $datos_detalle_nominas["ape_pat"]." ".$datos_detalle_nominas["ape_mat"]." ".$datos_detalle_nominas["nombre"]; ?></th>
										<input type="hidden" name="rfc_empl<?php echo $cont; ?>" id="rfc_empl<?php echo $cont; ?>" value="<?php echo $datos_detalle_nominas["rfc_trabajador"]; ?>"/>
										<?php
										for($i = 0; $i < $datos_nominas["num_dias"]; $i++){
											$fecha_enc = sumarDiasFechaNomina(modFecha($datos_nominas["fecha_inicio"],1),$i);
											$fecha_enc = modFecha($fecha_enc,3);
											$stm_sql_asistencias = "SELECT asistencia
																	FROM asistencia_empl_nom
																	WHERE id_nomina LIKE  '$id_nom'
																	AND rfc_trabajador LIKE  '$datos_detalle_nominas[rfc_trabajador]'
																	AND fecha_asistencia =  '$fecha_enc'";
											$rs_asistencia = mysql_query($stm_sql_asistencias);
											$datos_asistencia = mysql_fetch_array($rs_asistencia);
											?>
											<td class="fija">
												<input type="hidden" name="fecha_dias<?php echo $cont."_".$i; ?>" id="fecha_dias<?php echo $cont."_".$i; ?>" value="<?php echo $fecha_enc; ?>"/>
												
												<input type="checkbox" name="chk_asistencia_<?php echo $cont."_".$i; ?>" id="chk_asistencia_<?php echo $cont."_".$i; ?>" value="A" <?php if($datos_asistencia["asistencia"] == "ASISTENCIA") echo "checked"; ?> 
												onChange="checarAsistencia(this,<?php echo $cont; ?>,<?php echo $i; ?>);calcularTotalNomina('txt_total<?php echo $cont; ?>',<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>,<?php echo $datos_nominas["num_dias"]; ?>,'txt_he<?php echo $cont; ?>','txt_bono<?php echo $cont; ?>',<?php echo $cont; ?>)"/>A
												<br>
												<input type="checkbox" name="chk_incapacidad_<?php echo $cont."_".$i; ?>" id="chk_incapacidad_<?php echo $cont."_".$i; ?>" value="I"  <?php if($datos_asistencia["asistencia"] == "INCAPACIDAD") echo "checked"; ?> 
												onChange="checarAsistencia(this,<?php echo $cont; ?>,<?php echo $i; ?>);calcularTotalNomina('txt_total<?php echo $cont; ?>',<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>,<?php echo $datos_nominas["num_dias"]; ?>,'txt_he<?php echo $cont; ?>','txt_bono<?php echo $cont; ?>',<?php echo $cont; ?>)"/>I
												<br>
												<input type="checkbox" name="chk_descanso_<?php echo $cont."_".$i; ?>" id="chk_descanso_<?php echo $cont."_".$i; ?>" value="D"  <?php if($datos_asistencia["asistencia"] == "DESCANSO") echo "checked"; ?> 
												onChange="checarAsistencia(this,<?php echo $cont; ?>,<?php echo $i; ?>);calcularTotalNomina('txt_total<?php echo $cont; ?>',<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>,<?php echo $datos_nominas["num_dias"]; ?>,'txt_he<?php echo $cont; ?>','txt_bono<?php echo $cont; ?>',<?php echo $cont; ?>)"/>D
												<br>
												<input type="checkbox" name="chk_alcohol_<?php echo $cont."_".$i; ?>" id="chk_alcohol_<?php echo $cont."_".$i; ?>" value="AL"  <?php if($datos_asistencia["asistencia"] == "ALCOHOLIMETRIA") echo "checked"; ?> 
												onChange="checarAsistencia(this,<?php echo $cont; ?>,<?php echo $i; ?>);calcularTotalNomina('txt_total<?php echo $cont; ?>',<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>,<?php echo $datos_nominas["num_dias"]; ?>,'txt_he<?php echo $cont; ?>','txt_bono<?php echo $cont; ?>',<?php echo $cont; ?>)"/>AL
											</td>
											<?php
										}
										?>
										<td class="fija">
											<input type="hidden" name="sueldo_diario<?php echo $cont; ?>" id="sueldo_diario<?php echo $cont; ?>" value="<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>">
											$<?php echo number_format($datos_detalle_nominas["sueldo_diario"],2,".",","); ?>
										</td>
										<td class="fija">
											<input type="text" name="txt_he<?php echo $cont; ?>" id="txt_he<?php echo $cont; ?>" size="5" value="<?php echo $datos_detalle_nominas["horas_extra"]; ?>" class="caja_de_num" maxlength="2" onkeypress="return permite(event,'num');" required="required" 
											onChange="calcularTotalNomina('txt_total<?php echo $cont; ?>',<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>,<?php echo $datos_nominas["num_dias"]; ?>,'txt_he<?php echo $cont; ?>','txt_bono<?php echo $cont; ?>',<?php echo $cont; ?>)"/>
										</td>
										<td>
											<input type="checkbox" name="chk_8hrs_<?php echo $cont; ?>" id="chk_8hrs_<?php echo $cont; ?>" value="8" <?php if($datos_detalle_nominas["guardia"] == "8") echo "checked"; ?>
											onChange="checarGuardia(this,<?php echo $cont; ?>);calcularTotalNomina('txt_total<?php echo $cont; ?>',<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>,<?php echo $datos_nominas["num_dias"]; ?>,'txt_he<?php echo $cont; ?>','txt_bono<?php echo $cont; ?>',<?php echo $cont; ?>)"/>8Hrs
											<br>
											<input type="checkbox" name="chk_12hrs_<?php echo $cont; ?>" id="chk_12hrs_<?php echo $cont; ?>" value="12" <?php if($datos_detalle_nominas["guardia"] == "12") echo "checked"; ?>
											onChange="checarGuardia(this,<?php echo $cont; ?>);calcularTotalNomina('txt_total<?php echo $cont; ?>',<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>,<?php echo $datos_nominas["num_dias"]; ?>,'txt_he<?php echo $cont; ?>','txt_bono<?php echo $cont; ?>',<?php echo $cont; ?>)"/>12Hrs
										</td>
										<td class="fija">
											<input type="text" name="txt_bono<?php echo $cont; ?>" id="txt_bono<?php echo $cont; ?>" value="<?php echo number_format($datos_detalle_nominas["bonificacion_empl"],2,".",","); ?>" maxlength="10" class="caja_de_num" required="required" size="12" onkeypress="return permite(event,'num',2);" 
											onChange="formatCurrency(this.value.replace(/,/g,''),this.name);calcularTotalNomina('txt_total<?php echo $cont; ?>',<?php echo $datos_detalle_nominas["sueldo_diario"]; ?>,<?php echo $datos_nominas["num_dias"]; ?>,'txt_he<?php echo $cont; ?>','txt_bono<?php echo $cont; ?>',<?php echo $cont; ?>)"/>
										</td>
										<td class="fija">
											<input type="text" name="txt_total<?php echo $cont; ?>" id="txt_total<?php echo $cont; ?>" size="12" class="caja_de_num" 
											readonly="readonly" required="required" value="<?php echo number_format($datos_detalle_nominas["total_pagado"],2,".",","); ?>"/>
										</td>
										<td class="fija">
											<textarea name="txt_comentario<?php echo $cont; ?>" id="txt_comentario<?php echo $cont; ?>" rows="3" cols="60" maxlength="140" style="resize: none;"><?php echo $datos_detalle_nominas["comentarios"]; ?></textarea>
										</td>
									</tr>
								<?php
									$cont++;
								}
								?>
							</tbody>
						</table>
					</div>
					<div id='botones' align='center'>
						<input type="hidden" name="hdn_dias" id="hdn_dias" value="<?php echo $datos_nominas["num_dias"]; ?>"/>
						<input type="hidden" name="hdn_cont" id="hdn_cont" value="<?php echo $cont - 1; ?>"/>
						<input type='hidden' name='txt_ubicacion_con' value='<?php echo $_POST["txt_ubicacion_con"]; ?>'/>
						<input type='hidden' name='cmb_nomina' value='<?php echo $_POST["cmb_nomina"]; ?>'/>
						<input type='hidden' name='cmb_ubicacion_con' value='<?php echo $_POST["cmb_ubicacion_con"]; ?>'/>
						<?php
						if($cont-1 > 0){
						?>
							<input name="btn_continuar" type="submit" class="botones" value="Finalizar Nomina" onmouseover="window.status='';return true;" title="Registrar Nomina"/>
							<input name="btn_avance" type="submit" class="botones" value="Guardar Avance" onmouseover="window.status='';return true;" title="Registrar Nomina"/>
						<?php
						}
						?>
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar Registro de Nomina" onclick="location.href='frm_registrarNomina.php'"/>
					</div>
				</form>
			<?php
			} else {
				continuarNomina();
			}
			?>
		</body>
	<?php
	}
	?>
</html>
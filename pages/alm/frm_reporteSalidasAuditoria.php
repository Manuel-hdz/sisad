DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">
	<?php
	include ("../seguridad.php"); 
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	} else {
		include ("head_menu.php");
		include ("op_reporteSalidas.php");
		?>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
			<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
			<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
			<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
			<style type="text/css">
				<!--
				#titulo-reporteSalidaAud { position:absolute; left:30px; top:146px; width:236px; height:19px; z-index:11; }
				#form-datos-reporte {position:absolute;	left:30px; top:190px; width:400px; height:207px; z-index:13; }
				#calendario_repInicio { position:absolute; left:285px; top:228px; width:29px; height:24px; z-index:14; }
				#calendario_repCierre { position:absolute; left:285px; top:265px; width:30px; height:26px; z-index:15; }
				#tabla-reporteSalidas {	position:absolute;	left:30px; top:190px; width:940px; height:440px; z-index:12; overflow:auto; }
				#btns-regpdf { position: absolute; left:30px; top:680px; width:945px; height:40px; z-index:23; }
				-->
			</style>
		</head>
		<body>
			<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-reporteSalidaAud">Reporte de Salidas de Material </div>
			<?php
			if(isset($_POST["sbt_continuar"])){
				?>
				<div id="tabla-reporteSalidas" align="center" class="borde_seccion2">
					<?php
					$res = mostrasSalidasAuditoria();
					?>
				</div>
				<div id="btns-regpdf" align="center">
					<table width="50%" cellpadding="12">
						<form method="post" action="guardar_reporte.php">
							<?php
							if($res[0] == 1){
								?>
								<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="<?php echo $res[1]; ?>"/>
								<input type="hidden" name="hdn_consulta2" id="hdn_consulta2" value="<?php echo $res[2]; ?>"/>
								<input type="hidden" name="hdn_consulta3" id="hdn_consulta3" value="<?php echo $res[3]; ?>"/>
								<input type="hidden" name="hdn_msg" id="hdn_msg" value="<?php echo $res[4]; ?>"/>
								<input type="hidden" name="hdn_tipoReporte" id="hdn_tipoReporte" value="salidasAuditoria"/>
								<input type="submit" value="Exportar a Excel" name="sbt_exportar" id="sbt_exportar" class="botones" title="Exportar el Reporte a Excel" onmouseover="window.status='';return true;"/>
								&nbsp;&nbsp;
								<?php
							}
							?>
							<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Seleccionar Otro Rango de Fechas" onclick="location.href='frm_reporteSalidasAuditoria.php'" />
						</form>
					</table>
				</div>
				<?php
			} else {
				?>
				<fieldset id="form-datos-reporte" class="borde_seccion">
					<legend class="titulo_etiqueta">Reporte por Fechas</legend>
					<br>
					<form name="frm_datosReporteSalidas" action="frm_reporteSalidasAuditoria.php" method="post" >
						<table border="0" align="center" cellpadding="5" width="100%" cellspacing="5" class="tabla_frm">
							<tr>
								<td>
									<div align="right">Fecha de Inicio</div>
								</td>
								<td>
									<input name="txt_fechaInicio" type="text" value=<?php echo date("d/m/Y",strtotime("-7 day")); ?> size="10" maxlength="15" readonly=true width="50">
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>
									<div align="right">Fecha de Cierre</div>
								</td>
								<td>
									<input name="txt_fechaCierre" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="50">
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3">
									<div align="center">Filtrar Por</div>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right">Centro de Costos:</div>
								</td>
								<td>
									<?php
									$conn = conecta("bd_recursos");
									$rs = mysql_query("SELECT * FROM control_costos ORDER BY descripcion");
									if($rs){
										$row=mysql_fetch_array($rs)
										?>
										<select name="cmb_cc" id="cmb_cc" size="1" class="combo_box" required="required">
											<option value="">Centro de Costos</option>
											<option value="TODOS">TODOS</option>
											<?php 
											do{
												echo "<option value='$row[id_control_costos]'>$row[descripcion]</option>";
											}while($row=mysql_fetch_array($rs));
											?>
										</select>
										<?php
									} else { 
										?>
										<label class="msje_correcto"><u><strong>NO</strong></u> Hay Centros de Costo Registrados</label>
										<?php
									}
									?>
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td>
									<div align="center">
										<input name="sbt_continuar" type="submit" class="botones" value="Ver Reporte" onMouseOver="window.status='';return true" title="Ver Salidas Registradas de Material"  />
									</div>
								</td>
								<td>
									<div align="center">
										<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Reportes" onClick="location.href='menu_reportes.php'" />
									</div>
								</td>
							</tr>
						</table>
					</form>
				</fieldset>
				
				<div id="calendario_repInicio">
					<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_datosReporteSalidas.txt_fechaInicio,'dd/mm/yyyy',this)" 
					onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
				</div>
				
				<div id="calendario_repCierre">
					<input name="calendario_cieRep" type="image" id="calendario_cieRep" onclick="displayCalendar(document.frm_datosReporteSalidas.txt_fechaCierre,'dd/mm/yyyy',this)" 
					onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
				</div>
				<?php
			}
			?>
		</body>
	<?php
	}
	?>
</
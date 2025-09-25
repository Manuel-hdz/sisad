<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

<?php
	include ("../seguridad.php"); 
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include("head_menu.php");
		include("op_consultarGastos.php");
	?>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
			<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
			<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
			<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
			<style type="text/css">
				<!--
				#titulo-consultar {position:absolute; left:30px; top:146px; width:154px; height:22px; z-index:11;}
				#tabla-consultarGasto {position:absolute; left:21px; top:181px; width:400px; height:130px;z-index:12;}
				#tabla-gastos { position:absolute; left:20px; top:180px; width:955px; height:450px; z-index:11; overflow:auto; }
				#calendar-uno { position:absolute; left:293px; top:204px; width:30px; height:26px; z-index:17; }
				#calendar-dos { position:absolute; left:293px; top:237px; width:30px; height:26px; z-index:17; }
				#btns-regpdf {position:absolute; left:20px; width:990px; top:680px; z-index:12; }
				-->
			</style>
		</head>
		<body>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-consultar">Consultar Pago</div>
			<?php 
			if(isset($_POST["sbt_consultar"])){
				?>
				<div id="tabla-gastos" class="borde_seccion2">
					<?php
					$resultado = mostrarGastos();
					$report = $resultado[0];
					$mensaje = $resultado[1];
					$consulta = $resultado[2];
					?>
				</div>
				<div id="btns-regpdf" align="center">
					<table width="100%">
						<tr>						
							<td align="center">
								<form action="guardar_reporte.php" method="post">
									<input name="hdn_consulta" type="hidden" value="<?php echo $consulta; ?>" />
									<input name="hdn_origen" type="hidden" value="ReporteGastos" />		
									<input name="hdn_msg" type="hidden" value="<?php echo $mensaje; ?>" />							
									<?php
									if($report == 1){
									?>
										<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada" 
										onMouseOver="window.estatus='';return true"  />
										&nbsp;&nbsp;&nbsp;&nbsp;
									<?php
									}
									?>
									<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" title="Cancelar el Registro" 
									class="botones" onclick="location.href='frm_consultarGastos.php'"/>
								</form>
							</td>
						</tr>
					</table>			
				</div>
				<?php
			} else {
			?>
				<fieldset class="borde_seccion" id="tabla-consultarGasto" name="tabla-consultarGasto">
					<legend class="titulo_etiqueta">Seleccionar Pagos</legend>
					<form name="frm_consultarGasto" method="post">
						<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
							<tr>
								<td>
									<div align="right">Fecha Inicio</div>
								</td>
								<td>
									<input name="txt_fechaIni" type="text" id="txt_fechaIni" value="<?php echo date("d/m/Y", strtotime("-1 WEEK")); ?>" size="10" maxlength="15" readonly="true"/>
								</td>
							</tr>
							<tr>
								<td>
									<div align="right">Fecha Fin</div>
								</td>
								<td>
									<input name="txt_fechaFin" type="text" id="txt_fechaFin" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" readonly="true"/>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center">
									<input type="submit" name="sbt_consultar" id="sbt_consultar" onmouseover="window.status='';return true;" 
									value="Consultar" title="Consultar Pagos" class="botones" />
									&nbsp;&nbsp;&nbsp;&nbsp;
									<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" title="Cancelar el Registro" 
									class="botones" onclick="location.href='menu_gastos.php'"/>
								</td>
							</tr>
						</table>
					</form>
				</fieldset>
				
				<div id="calendar-uno">
					<input type="image" name="fechaIniGasto" id="fechaIniGasto" src="../../images/calendar.png" onclick="displayCalendar(document.frm_consultarGasto.txt_fechaIni,'dd/mm/yyyy',this)" 
					onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
				</div>
				<div id="calendar-dos">
					<input type="image" name="fechaFinGasto" id="fechaFinGasto" src="../../images/calendar.png" onclick="displayCalendar(document.frm_consultarGasto.txt_fechaFin,'dd/mm/yyyy',this)" 
					onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
				</div>
				<?php
			}
			?>
		</body>
	<?php 
	}
	?>
</html>
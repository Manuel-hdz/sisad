<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion <html xmlns="http://www.w3.org/1999/xhtml">
	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que incluye la operaci�n de consultar Empleado
		include ("op_reporteOTSE.php");?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
		<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112"
			media="screen">
		</link>
		<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
		<script type="text/javascript" src="../../includes/validacionCompras.js"></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
		<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
		<style type="text/css">
			#titulo-reporteOTSE {
				position: absolute;
				left: 30px;
				top: 146px;
				width: 419px;
				height: 25px;
				z-index: 11;
			}

			#form-consulta {
				position: absolute;
				left: 30px;
				top: 191px;
				width: 538px;
				height: 170px;
				z-index: 12;
			}

			#calendar-uno {
				position: absolute;
				left: 220px;
				top: 228px;
				width: 30px;
				height: 26px;
				z-index: 16;
			}

			#calendar-dos {
				position: absolute;
				left: 428px;
				top: 228px;
				width: 30px;
				height: 26px;
				z-index: 17;
			}

			#tabla-ordenesConsultadas {
				position: absolute;
				left: 30px;
				top: 390px;
				width: 940px;
				height: 264px;
				z-index: 10;
				overflow: auto;
			}

			#btns-regpdf {
				position: absolute;
				left: 450px;
				top: 680px;
				z-index: 12;
			}

			#lista-proveedores {
				position: absolute;
				width: 10px;
				height: 10px;
				z-index: 19;
			}
		</style>
	</head>

	<body>
		<?php
			if(isset($_POST['sbt_consultar'])){
				?>
		<form name="frm_tablaOTSE" id="frm_tablaOTSE" method="post" action="guardar_reporte.php">
			<div class="borde_seccion" id="tabla-ordenesConsultadas" align="center">
				<?php
						$consulta = mostrarOTSE();
						?>
				<input name="hdn_consulta" type="hidden" value="<?php echo $consulta[0]; ?>" />
				<input name="hdn_nomReporte" type="hidden" value="Reporte OTSE" />
				<input name="hdn_origen" type="hidden" value="reporte_OTSE" />
				<input name="hdn_msg" type="hidden" value="<?php echo $consulta[1]; ?>" />
			</div>
			<div id="btns-regpdf">
				<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel"
					title="Exportar a Excel los Datos de la Consulta Realizada"
					onMouseOver="window.estatus='';return true" />
			</div>
		</form>
		<?php
			}
			
			$fechaIni = date("d/m/Y", strtotime("-7 day")); $fechaFin = date("d/m/Y");
			?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-reporteOTSE">Reporte Ordenes de Trabajo Para Servicios Externos </div>
		<fieldset class="borde_seccion" id="form-consulta" name="form-consulta">
			<legend class="titulo_etiqueta">Ordenes de Trabajo para Servicios Externos</legend>
			<br>
			<form name="frm_reporteOTSE" id="frm_reporteOTSE" method="post" action="frm_reporteOTSE.php">
				<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td align="right">Fecha Inicio</td>
						<td>
							<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text"
								value="<?php echo $fechaIni; ?>" size="10" maxlength="15" />
						</td>
						<td align="right">Fecha Fin</td>
						<td>
							<input name="txt_fechaFin" id="txt_fechaFin" readonly="readonly"
								value="<?php echo $fechaFin; ?>" size="10" maxlength="15" width="90" />
						</td>
					</tr>
					<tr>
						<td align="right">Proveedor</td>
						<td colspan="3">
							<input type="text" name="txt_nomProveedor" id="txt_nomProveedor"
								onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');" value="" size="50"
								maxlength="80" onkeypress="return permite(event,'num_car', 0);" autocomplete="off" />
							<div id="lista-proveedores">
								<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
									<img src="../../images/upArrow.png"
										style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
									<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td align="right">&Aacute;rea</td>
						<td>
							<select name="cmb_area" id="cmb_area" class="combo_box">
								<option value="">TODAS</option>
								<option value="MttoConcreto">CONCRETO</option>
								<option value="MttoMina">MINA</option>
							</select>
						</td>
						<td align="right">Estado</td>
						<td>
							<select name="cmb_estado" id="cmb_estado">
								<option value="">TODOS</option>
								<option value="SI">COMPLEMENTADAS</option>
								<option value="NO">NO COMPLEMENTADAS</option>
							</select>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="4">
							<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar"
								value="Consultar" onmouseover="window.status='';return true;"
								title="Consultar Ordenes de Trabajo para Servicios Externos" />
							&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Regresar"
								title="Regresar al Men&uacute; Inicio" onMouseOver="window.status='';return true"
								onclick="location.href='menu_reportes.php'" />
						</td>
					</tr>
				</table>
			</form>
		</fieldset>

		<div id="calendar-uno">
			<input name="fechaIni" type="image" id="fechaIni"
				onclick="displayCalendar(document.frm_reporteOTSE.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25"
				height="25" border="0" />
		</div>

		<div id="calendar-dos">
			<input name="fechaFin" id="fechaFin" type="image"
				onclick="displayCalendar(document.frm_reporteOTSE.txt_fechaFin,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" width="25" height="25"
				border="0" align="absbottom" />
		</div>
	</body>
	<?php 
	}
	?>

	</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

<?php
	include ("../seguridad.php"); 
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include ("head_menu.php");
		include ("op_consultarRequisicion.php");
		?>
		
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
			<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
			<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
			<script type="text/javascript" src="../../includes/maxLength.js" ></script>
			<script type="text/javascript" language="javascript">
				function habilitarFiltro(combo){
					if(combo!="NA")
						document.getElementById("txa_filtro").readOnly=false;
					else{
						document.getElementById("txa_filtro").value="";
						document.getElementById("txa_filtro").readOnly=true;
					}
				}
			</script>
			
			<style type="text/css">
				<!--
				#titulo-requisicion {position:absolute;left:25px;top:146px;width:293px;height:22px;z-index:11;}
				#tabla-fechas { position:absolute; left:25px; top:190px; width:455px; height:170px; z-index:12; }
				#tabla-resultados{position:absolute; left:30px; top:191px; width:930px; height:450px; z-index:15; overflow: auto;}
				#botones-resultados{position:absolute; left:30px; top:674px; width:930px; height:40px; z-index:15; overflow: auto;}
				#tabla-requisiciones {position:absolute;left:30px;top:190px;width:900px;height:180px;z-index:12;}
				#detalle_Req{position:absolute;overflow:auto;left:30px;top:400px;width:955px;height:235px;z-index:13;}
				#botones{position:absolute;left:30px;top:680px;width:900px;height:37px;z-index:13;}
				#calendar-uno { position:absolute; left:173px; top:219px; width:30px; height:26px; z-index:16; }
				#calendar-dos { position:absolute; left:385px; top:219px; width:30px; height:26px; z-index:17; }
				-->
			</style>
		</head>
		<body>
			<div id="barra"><img src="../../images/title-bar-bg-comaro.png" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-requisicion">Autorizar Requisiciones</div>
			<?php
			if(isset($_POST["sbt_consultarReq"])){
				?>
				<form name="frm_consultarRequisiciones" method="post" action="">
					<div id="tabla-resultados" class="borde_seccion">
						<?php
						$requis = mostrarRequisiciones();
						?>
					</div>
					<div align="center" id="botones-resultados">
						<?php 
						if($requis == 1){
						?>
							<input type="submit" class="botones" name="sbt_consultarDetalleReq" value="Consultar" title="Consultar Detalle de Requisicion" onmouseover="window.status='';return true;"/>
							&nbsp;&nbsp;&nbsp;
						<?php 
						}
						?>
						<input type="button" class="botones" name="btn_regresar" value="Cancelar" title="Regresar a Filtros" onmouseover="window.status='';return true;"
						onclick="location.href='frm_consultarRequisicion.php'"/>
					</div>
				</form>
				<?php
			} else if(isset($_POST["sbt_consultarDetalleReq"])) {
				$cve_req = $_POST["rdb_req"];
				?>
				<form method='post' name='frm_detallesRequisicion' id='frm_detallesRequisicion'>
					<fieldset id='tabla-requisiciones' class='borde_seccion'>
						<legend class='titulo_etiqueta'>Requisici&oacute;n <?php echo $cve_req; ?></legend>
						<?php
						mostrarRequisicionDetalle($cve_req);
						?>
					</fieldset>
					<div id='detalle_Req' class='borde_seccion'>
						<?php
						dibujarDetalle($cve_req);
						?>
					</div>
					<div id='botones' align="center">
						<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" title="Ver Archivo PDF de la Requisición Seleccionada" onmouseover="window.status='';return true"
						onclick="window.open('../../includes/generadorPDF/requisicion.php?id=<?php echo $cve_req; ?>','_blank',
						'top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no,location=no, directories=no')" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" class="botones" name="sbt_consultarReq" value="Cancelar" title="Regresar a Requisiciones" onmouseover="window.status='';return true;"/>
					</div>
				</form>
				<?php
			} else {
			?>
				<fieldset id="tabla-fechas" class="borde_seccion">
					<legend class="titulo_etiqueta">Seleccionar Fechas</legend>
					<form name="frm_buscarRequisiciones" action="frm_consultarRequisicion.php" method="post" onsubmit="return valFormReqFecha(this);">
						<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
							<tr>
								<td>
									<div align="right">Fecha Inicio</div>
								</td>
								<td>
									<input type="text" size="10" name="txt_fechaIni" id="txt_fechaIni" class="caja_de_texto" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" readonly="readonly" onchange="comprobarFecha()"/>
								</td>
								<td>
									<div align="right">Fecha Fin</div>
								</td>
								<td>
									<input type="text" size="10" name="txt_fechaFin" id="txt_fechaFin" class="caja_de_texto" value="<?php echo date("d/m/Y"); ?>" readonly="readonly" onchange="comprobarFecha()"/>
								</td>
							</tr>
							<tr>
								<td valign="top">
									<div align="right">Filtro</div>
								</td>
								<td valign="top">
									<select name="cmb_filtro" onchange="habilitarFiltro(this.value);">
										<option value="NA">Filtro</option>
										<option value="descripcion">MATERIAL</option>
										<option value="aplicacion">APLICACI&Oacute;N</option>
										<option value="justificacion_tec">JUSTIFICACI&Oacute;N</option>
									</select>
								</td>
								<td valign="top">
									<div align="right">Concepto</div>
								</td>
								<td valign="top">
									<textarea name='txa_filtro' id="txa_filtro" maxlength='120' onkeypress="return permite(event,'num_car', 0);" onkeyup='return ismaxlength(this)'
									onclick="value='';" rows='3' cols='30' class='caja_de_texto' readonly="readonly" style="resize: none;"></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<input type="submit" class="botones" name="sbt_consultarReq" value="Consultar" title="Consultar Requisiciones entre las Fechas proporcionadas" onmouseover="window.status='';return true;"/>
									&nbsp;&nbsp;&nbsp;
									<input type="button" class="botones" name="btn_regresar" value="Cancelar" title="Regresar al Men&uacute; de Requisiciones" onmouseover="window.status='';return true;"
									onclick="location.href='menu_requisiciones.php'"/>
								</td>
							</tr>
						</table>
					</form>
				</fieldset>
				<div id="calendar-uno">
					<input type="image" name="iniRepClientes" id="iniRepClientes" src="../../images/calendar.png"
					onclick="displayCalendar(document.frm_buscarRequisiciones.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
					width="25" height="25" border="0" align="absbottom" />
				</div>
				<div id="calendar-dos">
					<input type="image" name="finRepClientes" id="finRepClientes" src="../../images/calendar.png"
					onclick="displayCalendar(document.frm_buscarRequisiciones.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
					width="25" height="25" border="0" align="absbottom" />
				</div>
			<?php
			}
			?>
		</body>
	<?php 
	}
	?>
</html>
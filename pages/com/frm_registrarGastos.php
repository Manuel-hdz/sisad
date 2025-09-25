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
		include("op_registrarGastos.php");
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
				#titulo-registrar {position:absolute; left:30px; top:146px; width:154px; height:22px; z-index:11;}
				#tabla-registrarGasto {position:absolute; left:21px; top:181px; width:400px; height:180px;z-index:12;}
				#calendar-uno { position:absolute; left:208px; top:204px; width:30px; height:26px; z-index:17; }
				-->
			</style>
		</head>
		<body>
			<?php 
			if(isset($_POST["sbt_registrar"])){
				registrarGasto();
			}
			?>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-registrar">Registrar Pago</div>
			<fieldset class="borde_seccion" id="tabla-registrarGasto" name="tabla-registrarGasto">
				<legend class="titulo_etiqueta">Registar Pagos</legend>
				<form name="frm_registrarGasto" method="post">
					<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
						<tr>
							<td>
								<div align="right">Fecha</div>
							</td>
							<td>
								<input name="txt_fecha" type="text" id="txt_fecha" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" readonly="true"/>
							</td>
						</tr>
						<tr>
							<td>
								<div align="right">Descripcion</div>
							</td>
							<td>
								<input type="text" name="txt_descripcion" id="txt_descripcion" size="60" maxlength="100" required="required" autocomplete="off" />
							</td>
						</tr>
						<tr>
							<td>
								<div align="right">Importe</div>
							</td>
							<td>
								<input name="txt_importe" id="txt_importe" type="text" class="caja_de_num" required="required" autocomplete="off" 
								onchange="formatCurrency(value,'txt_importe');" onkeypress="return permite(event,'num', 2)" size="15" maxlength="20"/>
							</td>
						</tr>
						<tr>
							<td>
								<div align="right">Factura</div>
							</td>
							<td>
								<input type="text" name="txt_factura" id="txt_factura" maxlength="20" required="required" autocomplete="off" />
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center">
								<input type="submit" name="sbt_registrar" id="sbt_registrar" onmouseover="window.status='';return true;" 
								value="Guardar" title="Guardar el Registro" class="botones" />
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" title="Cancelar el Registro" 
								class="botones" onclick="location.href='menu_gastos.php'"/>
							</td>
						</tr>
					</table>
				</form>
			</fieldset>
			
			<div id="calendar-uno">
				<input type="image" name="fechaGasto" id="fechaGasto" src="../../images/calendar.png" onclick="displayCalendar(document.frm_registrarGasto.txt_fecha,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
			</div>
		</body>
	<?php 
	}
	?>
</html>
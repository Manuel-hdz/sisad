<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion
html xmlns="http://www.w3.org/1999/xhtml">
<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarTiempoVidaES.php");?>	
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_equipoSeguridad.js"></script>
	<script type="text/javascript" src="includes/ajax/verificarTipoRegistroES.js"></script>

	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:390px;	height:20px;	z-index:11;}
			#tabla-vidaUtil {position:absolute;left:16px;top:179px;width:911px;height:196px;z-index:14;padding:15px;padding-top:0px;}
			#periodo1{position:absolute; left:200px; top:312px; width:30px; height:26px; z-index:16; }	
			#res-spider {position:absolute;z-index:19;}
		-->
    </style>
</head>
<body>

<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Tiempo de Vida &Uacute;til del Equipo de Seguridad</div>

	<form onsubmit="return valFormTiempoVidaUtilEquipoSeguridad(this);" name="frm_tiempoVidaES" method="post"  id="frm_tiempoVidaES" >
		<fieldset class="borde_seccion" id="tabla-vidaUtil" name="tabla-vidaUtil">
		<legend class="titulo_etiqueta">Ingresar la Información del Equipo de Seguridad</legend>
		<br >
			<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
				<tr>
					<td width="108"><div align="right">*Nombre Material</div></td>
					<td>
						<input type="text" name="txt_nomMaterial" id="txt_nomMaterial" onkeyup="lookup(this,'materiales','1');" 
						value="" size="60" maxlength="80" onkeypress="return permite(event,'car',1);" tabindex="1" autocomplete="off" />
						<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
						<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
						</div>
					</td>
					<td><div align="right">*Clave Material</div></td>
					<td>
						<input type="text" name="txt_claveMaterial" id="txt_claveMaterial" maxlength="10" size="10" class="caja_de_texto" 
						value="" readonly="readonly" />
					</td>
				</tr>				
				<tr>
					<td><div align="right">*Tiempo Vida &Uacute;til</div></td>
					<td>
						<input type="text" name="txt_tiempoVida" id="txt_tiempoVida" maxlength="5" size="5" class="caja_de_texto" 
						value="" onkeypress="return permite(event,'num',0);"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select name="cmb_tipoTiempo" id="cmb_tipoTiempo" class="combo_box" >
							<option selected="selected" value="">Seleccionar</option>
							<option value="DIAS">DIAS</option>
							<option value="SEMANAS">SEMANAS</option>
							<option value="MESES">MESES</option>
						</select>	
					</td>
					<td rowspan="2"><div align="right">Observaciones </div></td>
					<td rowspan="2">
						<textarea name="txa_observaciones" cols="35" rows="3" class="caja_de_texto" id="txa_observaciones"  
						onkeypress="return permite(event,'num_car',0);" maxlength="80" onkeyup="return ismaxlength(this)" ></textarea>
					</td>  			 
				</tr>
				<tr>
					<td><div align="right">Fecha Registro</div></td>
					<td><input name="txt_fechaReg" id="txt_fechaReg" readonly="readonly" type="text" value="<?php echo date("d/m/Y")?>" size="10"  width="90"/></td>													
				</tr>
				<tr>
					<td colspan="6"><div align="center">						
						<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" 
						title="Guardar el Registro del Tiempo de Vida Útil del Equipo de Seguridad" 
						onmouseover="window.status='';return true"  disabled="disabled"  />
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_modificar" type="submit" class="botones" id="sbt_modificar" value="Modificar" 
						title="Modificar el Registro del Tiempo de Vida Útil del Equipo de Seguridad Seleccionado" disabled="disabled"
						onmouseover="window.status='';return true" >
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
						onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
						title="Regresar al Men&uacute; de Seguridad" onmouseover="window.status='';return true" 
						onclick="confirmarSalida('menu_seguridad.php');" />
					</div>
					</td>
				</tr>
			</table>
	  </fieldset>
	</form>
	<!--<div id="periodo1">
		<input name="fechaReg" type="image" id="fechaReg" onclick="displayCalendar(document.frm_tiempoVidaES.txt_fechaReg,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"  title="Seleccionar Fecha"
		width="25" height="25" border="0" />
	</div>-->

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>


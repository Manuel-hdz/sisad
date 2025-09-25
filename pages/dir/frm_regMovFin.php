<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	//if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
	//	echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	//}
	//else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
    <link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/regMovFin.js"></script>
	<script type="text/javascript" src="../../includes/validacionDireccion.js"></script>
	
	<script type="text/javascript" language="javascript">
		setTimeout("mostrarHistorialMovFin();document.getElementById('cmb_tipoMov').focus();",1000);
		
	</script>

    <style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#form-movimientoFin {position:absolute;left:30px;top:190px;width:880px;height:170px;z-index:14;}
		#resultados{position:absolute;left:30px;top:400px;width:944px; height:260px;;z-index:14;overflow:scroll;}
		#calendario_repInicio { position:absolute; left:490px; top:233px; width:29px; height:24px; z-index:14; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Registrar Movimiento Financiero</div>
	
	<?php 
	$ppto=obtenerDato("bd_direccion","finanzas","presupuesto","id_pto",$_GET["id_pto"]);
	$ppto=number_format($ppto,2,".",",");
	?>
	
	<fieldset class="borde_seccion" id="form-movimientoFin" name="form-movimientoFin">
	<legend class="titulo_etiqueta" style="color:#FFFFFF">Registro Financiero de <?php echo obtenerDato("bd_direccion","finanzas","clasificacion","id_pto",$_GET["id_pto"]);?></legend>	
	<br>	
	<form name="frm_regMovFin">
	<table width="103%" border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  <td width="17%" style="color:#FFFFFF"><div align="right">*Tipo de Movimiento</div></td>
			<td width="13%">
				<select name="cmb_tipoMov" id="cmb_tipoMov" class="combo_box" tabindex="1">
					<option value="" selected="selected">Movimiento</option>
					<option value="INGRESO">INGRESO</option>
					<option value="EGRESO">EGRESO</option>
				</select>
		  </td>
		  <td width="8%" style="color:#FFFFFF"><div align="right">Fecha</div></td>
			<td width="22%" >
				<input name="txt_fecha" id="txt_fecha" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly="readonly" onchange="mostrarHistorialMovFin();">
		  </td>
		  <td width="14%" style="color:#FFFFFF"><div align="right">Monto Disponible</div></td>
			<td width="26%">
				<label style="color:#FFFFFF">$</label><input type="text" name="txt_monto" id="txt_monto" class="caja_de_num" readonly="readonly" size="10" value="<?php echo $ppto;?>"/>
		  </td>
		</tr>
		<tr>
			<td style="color:#FFFFFF" valign="top"><div align="right">*Cantidad</div></td>
			<td valign="top">
				<label style="color:#FFFFFF">$</label><input type="text" class="caja_de_num" name="txt_cantidad" id="txt_cantidad" size="10" maxlength="10" 
														onkeypress="return permite(event,'num',2);" onchange="formatCurrency(value,'txt_cantidad');" tabindex="3"/>
			</td>
			<td style="color:#FFFFFF" valign="top"><div align="right">*Concepto</div></td>
			<td>
				<textarea name="txa_concepto" id="txa_concepto" onkeypress="return permite(event,'num_car', 0);" cols="30" rows="3"
				onkeyup="return ismaxlength(this)" class="caja_de_texto" maxlength="240" tabindex="4"></textarea>
			</td>
			<td style="color:#FFFFFF" valign="top"><div align="right">*Responsable</div></td>
			<td valign="top">
				<input type="text" class="caja_de_texto" name="txt_responsable" id="txt_responsable" size="35" maxlength="75" 
				onkeypress="return permite(event,'car',0);" tabindex="5"/>
			</td>
		</tr>
		<tr>
			<td colspan="6" align="center">
				<input type="hidden" name="hdn_clasificacion" id="hdn_clasificacion" value="<?php echo $_GET["id_pto"]?>"/>
				<input name="btn_guardar" id="btn_guardar" type="button" class="botones" value="Guardar" onMouseOver="window.status='';return true" title="Guardar Movimiento" 
				onclick="guardarRegMovFin();" tabindex="6"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_limpiar" id="btn_limpiar" class="botones" value="Limpiar" title="Restablecer el Formulario" 
				onclick="restablecerFormulario();mostrarHistorialMovFin();document.getElementById('cmb_tipoMov').focus();" tabindex="7"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute;" onClick="borrarHistorial();location.href='menu_financiero.php'" tabindex="8"/>
			</td>
		</tr>
	</table>    
	</form>    			 	
</fieldset>
		
	<div id="calendario_repInicio">
		<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_regMovFin.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" tabindex="2"/>
</div>
	
	<div id="resultados" class="borde_seccion2" align="center"></div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
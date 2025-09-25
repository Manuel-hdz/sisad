<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarRecordatorio.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionAseguramiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-registrarRecordatorio {position:absolute;left:30px;top:190px;width:812px;height:246px;z-index:12;}
		#calendario{position:absolute;left:685px;top:300px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar Recordatorios </div>
	<fieldset class="borde_seccion" id="tabla-registrarRecordatorio" name="tabla-registrarRecordatorio">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Recordatorio </legend>	
	<br>
	
	<form onsubmit="return valFormRegRec(this);" name="frm_registrarRecordatorio"  id="frm_registrarRecordatorio" method="post" action="op_registrarRecordatorio.php">
	<table width="806" height="187"  cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td height="47"><div align="right">*Clave Recordatorio </div></td>
			<td width="204">
			<input name="txt_idRecordatorio" id="txt_idRecordatorio" type="text" class="caja_de_texto" size="15" maxlength="15" value="<?php echo obtenerIdReg();?>"
				readonly="readonly"/>			</td>
			<td width="144"><div align="right">*Descripci&oacute;n</div></td>
			<td width="222">
			<textarea name="txa_descripcion" id="txa_descripcion" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea>			</td>
		</tr>
		<tr>
			<td width="169" height="31"><div align="right">*Tipo Recordatorio </div></td>
			<td>
				<select name="cmb_tipoAler" id="cmb_tipoAler" size="1" class="combo_box" onchange="activarCamposRegRec();">
					<option value="">Tipo</option>
					<option value="INTERNA">INTERNA</option>
					<option value="EXTERNA">EXTERNA</option>
				</select>			</td>
			<td><div align="right">*Fecha Programada</div></td>
			<td><input name="txt_fechaProg" type="text" id="txt_fechaProg" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
		</tr>
	  <tr>
	    <td><div align="right" id="div_agrDep">*Agregar Departamentos </div></td>
	    <td><input name="txt_ubicacion" id="txt_ubicacion" type="text" class="caja_de_texto" size="40" readonly="readonly" 
				onclick="window.open('verDepartamentos.php','_blank','top=50, left=50, width=380, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar Departamentos"/></td>
	    <td><div align="right" id="div_agrArc">Agregar Archivos </div></td>
	    <td><input name="txt_archivos" id="txt_archivos" type="text" class="caja_de_texto" size="40" readonly="readonly" 
				onclick="window.open('verArchivos.php','_blank','top=50, left=50, width=680, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar Archivos"/></td>
	  </tr>
		<tr><td colspan="5"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
		<tr>
			<td colspan="6">
				<div align="center">
					<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Recordatorio"
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Recordatorios" 
					onmouseover="window.status='';return true"  onclick="confirmarSalida('menu_recordatorio.php')" />
				</div>			 </td>
		</tr>
    </table>
	</form>
</fieldset>
<div id="calendario">
		<input name="calendario" type="image" id="calendario2" onclick="displayCalendar (document.frm_registrarRecordatorio.txt_fechaProg,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0"/>						
</div>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
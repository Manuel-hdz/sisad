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
		include ("op_seleccionarPermiso.php");
		?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/verificarComplementoPerAlturas.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:24px;	top:146px;	width:253px;	height:20px;	z-index:11;}
			#tabla-seleccionarRegistro {position:absolute;left:30px;top:190px;width:450px;height:149px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-permisoAlturas {position:absolute;left:16px;top:179px;width:950px;height:399px;z-index:12;padding:15px;padding-top:0px;}
			#periodo1{position:absolute; left:843px; top:204px; width:30px; height:26px; z-index:18; }	
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8; overflow:scroll}
			#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
			#boton-exp {position:absolute;left:162px;top:541px;width:677px;height:19px;z-index:12;padding:15px;padding-top:0px;}
		-->
    </style>
</head>
<body>
<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Generar Permisos de Alturas</div>
<?php
	//verificar que este definido el ID del tipo de reporte a mostrar
	if (isset($_GET["id_tipoPer"])){
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente, es decir las variables que vienen en la url
		$idPermiso = $_GET["id_tipoPer"];
	}?>
		
	<fieldset class="borde_seccion" id="tabla-permisoAlturas" name="tabla-permisoAlturas">
	<legend class="titulo_etiqueta">Ingresar los Datos del Permiso para Trabajos de Altura</legend>
	<form name="frm_permisoTrabAlturas" method="post"  id="frm_permisoTrabAlturas" onsubmit="return valFormPermisoTrabAlturas(this);" action="frm_generacionPermisos2.php">
		<table width="100%" cellpadding="5" class="tabla_frm">
			<tr>
			  <td width="17%"><div align="right">Clave Permiso</div></td>
				<td width="6%"><input type="text" name="txt_idPermisoAlt" id="txt_idPermisoAlt" maxlength="10" size="10" class="caja_de_texto" 
					value="<?php echo obtenerIdPermisoAlturas();?>" onkeypress="return permite(event,'num',1);" readonly="readonly"/></td>
				<td width="19%"><div align="right">Tipo Permiso</div></td>
				<td width="17%">
					<input name="txt_tipoPermiso" type="text" class="caja_de_texto" id="txt_tipoPermiso" 
					onkeypress="return permite(event,'num',1);" value="<?php echo $idPermiso; ?>" size="25" readonly="readonly"/>
				</td>
				<td width="14%"><div align="right">Fecha</div></td>
				<td width="27%">
					<input name="txt_fechaReg" id="txt_fechaReg" readonly="readonly" type="text" size="10"  width="90" 
					value="<?php echo date("d/m/Y"); ?>" />
				</td>
			</tr>
			<tr>
				<td><div align="right">* Nombre  Quien Realiza  Trabajo </div></td>
				<td colspan="2">
					<input type="text" name="txt_nomTrabajador" id="txt_nomTrabajador" maxlength="80" size="40" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>
				</td>
				<td><div align="right">*Nombre Autoriza Trabajo</div></td>
				<td colspan="2"><input type="text" name="txt_nomAutoriza" id="txt_nomAutoriza" maxlength="100" size="40" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" /></td>
			</tr>
			<tr>
				<td><div align="right">*Nombre L&iacute;der  &Aacute;rea Operativa </div></td>
				<td colspan="2"><input type="text" name="txt_liderOper" id="txt_liderOper" maxlength="100" size="40" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/></td>
				<td><div align="right">*Trabajo a Realizar</div></td>
				<td colspan="2"><textarea name="txa_trabRealizar" cols="35" rows="3" class="caja_de_texto" id="txa_trabRealizar"  
					onkeypress="return permite(event,'num_car',0);"  maxlength="100" onkeyup="return ismaxlength(this)"></textarea></td>
			</tr>
			<tr>
				<td><div align="right">*Lugar</div></td>
				<td colspan="2"><input type="text" name="txt_lugar" id="txt_lugar" maxlength="70" size="40" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/></td>
				<td><div align="right">*Descripci&oacute;n Trabajo:<br />
				</div></td>
				<td colspan="2"><textarea name="txa_desTrabajo" cols="45" rows="4" class="caja_de_texto" id="txa_desTrabajo"  
					onkeypress="return permite(event,'num_car',0);"  maxlength="180" onkeyup="return ismaxlength(this)"></textarea></td>
			</tr>
			<tr>
				<td colspan="6"><div align="center">
					<strong>*¿CU&Aacute;LES SON LOS RIESGOS QUE EL COLABORADOR VA ENCONTRAR EN EL DESARROLLO DE SU TRABAJO Y COMO EVITARLOS?</strong><br />
					Caída a Desnivel y Golpeado Por:
				</div>
				</td>			
			</tr>
			<tr>
				<td align="center" colspan="6">
					<textarea  name="txa_riesgosTrab" cols="125" rows="3" class="caja_de_texto" id="txa_riesgosTrab"  
					onkeypress="return permite(event,'num_car',0);"  maxlength="250" onkeyup="return ismaxlength(this)"></textarea>
				</td>
			</tr>
	  		<tr> 
				<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  		</tr>
			<tr>
				<td colspan="6"><div align="center">
				<input type="hidden" id="hdn_boton" name="hdn_boton" value="no"/>
								
				<input name="btn_regCondicionesSeg" type="button" class="botones_largos" id="btn_regCondicionesSeg"  value="Complementar Reporte" 
					title="Complementar las Condiciones de Seguridad del Permiso de Alturas" onmouseover="window.status='';return true" 
					onclick="permisoAlturas();desabilitarVentana();"  /><?php //desabilitarVentana(); ?>	
					&nbsp;&nbsp;&nbsp;
				<input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar"  value="Continuar" title="Continuar con el Registro del  Permiso de Trabajos Alturas" 
					onmouseover="window.status='';return true"  onclick="verificarComplementoPerAlturas('<?php echo obtenerIdPermisoAlturas();?>');"/>
					&nbsp;&nbsp;&nbsp;
				<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
					onmouseover="window.status='';return true" /><?php //btn_detalles.style.visibility='hidden';?>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					title="Regresar para Generar otro Tipo de Permiso" onmouseover="window.status='';return true" onclick="confirmarSalida('frm_seleccionarPermiso.php')" />
				</div></td>
			</tr>
	  </table>
	</form>
</fieldset>	




<div id="periodo1">
	<input name="fechaReg" type="image" id="fechaReg" onclick="displayCalendar(document.frm_permisoTrabAlturas.txt_fechaReg,'dd/mm/yyyy',this)"
	onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"  title="Seleccionar Fecha"
	width="25" height="25" border="0" />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
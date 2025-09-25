<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarActaSeguridadHigiene.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:298px;height:20px;z-index:11;}
		#tabla-agregarActa {position:absolute;left:30px;top:190px;width:950px;height:435px;z-index:12;}
		#calendario{position:absolute;left:458px;top:218px;width:30px;height:26px;z-index:13;}
		#calendario2{position:absolute;left:741px;top:218px;width:30px;height:26px;z-index:14;}
		#calendario3{position:absolute;left:902px;top:218px;width:30px;height:26px;z-index:15;}
		#calendario4{position:absolute;left:737px;top:427px;width:30px;height:26px;z-index:16;}
		-->
    </style>
</head>
<body>
	<?php if(isset($_POST['sbt_guardar'])){
		registrarActaGral();
	}else{?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-registrar">Registrar Acta Seguridad e Higiene  </div>
		<fieldset class="borde_seccion" id="tabla-agregarActa" name="tabla-agregarActa">
		<legend class="titulo_etiqueta">Ingresar Informaci&oacute;n General del Acta de Seguridad e Higiene </legend>	
		<form  onsubmit="return valFormRegActaSH(this);"name="frm_agregarActa" id="frm_agregarActa" method="post" action="frm_registrarActaSeguridadHigiene.php">
			<table width="953" height="402"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">*Clave Acta </div></td>
					<td width="94">
						<input name="txt_idActa" id="txt_idActa" type="text" class="caja_de_texto" size="10" maxlength="10" 
						value="<?php echo obtenerIdRegBitacoraSH();?>" readonly="readonly"/>				
					</td>
					<td width="105"><div align="right">*Fecha Registro </div></td>
					<td width="97">
						<input name="txt_fechaRegistro" type="text" id="txt_fechaRegistro" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
						readonly="readonly" class="caja_de_texto"/>				
					</td>
					<td width="159" colspan="2"><div align="right">*Periodo Verificaci&oacute;n de:</div></td>
					<td width="92">
						<div align="left">
							<input name="txt_periodoVer" type="text" id="txt_periodoVer" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
							readonly="readonly" class="caja_de_texto"/>
						</div>				</td>
					<td width="38"><div align="right">Al:</div></td>
					<td width="168">
						<input name="txt_al" type="text" id="txt_al" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly" 
						class="caja_de_texto"/>				
					</td>
				</tr>
				<tr>
					<td width="73" rowspan="3"><div align="right">*Descripci&oacute;n</div></td>
					<td colspan="3" rowspan="3">
						<textarea name="txa_descripcion" id="txa_descripcion" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="4"
						cols="60" onkeypress="return permite(event,'num_car', 0);" ></textarea>				</td>
					<td colspan="5"><div align="left">*Tipo Verificaci&oacute;n </div></td>
				</tr>
				<tr>
					<td colspan="2"><input name="rbd_tipoVer" id="rbd_tipoVer" type="radio" value="ordinaria"  onclick="activarExtraOrdinario(this.value);"/>
					Ordinaria </td>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td width="159" colspan="2">
						<input name="rbd_tipoVer" type="radio"  id="rdv_tipoVer" value="extraordinaria" 
						onclick="activarExtraOrdinario(this.value);"/>Extraordinaria Por </td>
					<td colspan="4">
						<input name="txt_extraordinariaPor" id="txt_extraordinariaPor" type="text" class="caja_de_texto" size="60" maxlength="80" value="" 
						readonly="readonly" onkeypress="return permite(event,'num_car', 0);"/>				
					</td>
				</tr>
				<tr>
					<td>
						<div align="right">
							<input type="checkbox" name="ckb_nomPuestoAsist" id="ckb_nomPuestoAsist" value="ckb_nomPuestoAsist" 
							onclick="abrirRegNombrePuestoAsistentes();" title="Dar Click para Agregar Nombre y Puesto de los Asistentes"/>
						</div>				
					</td>
					<td colspan="3">Ingresar Nombre y Puesto de los Asistentes </td>
					<td><div align="right">* Hora Inicio </div></td>
					<td colspan="4">
						<input name="txt_horaInicio" id="txt_horaInicio" type="text" class="caja_de_texto" size="5" maxlength="5" value="" 
						onchange="formatHora(this,'cmb_horaInicio');" onkeypress="return permite(event,'num', 5);"/>
						<label>
						  <select name="cmb_horaInicio" id="cmb_horaInicio" class="combo_box">
							<option value="AM">a.m.</option>
							<option value="PM">p.m.</option>
						  </select>
						</label>				
					</td>
				</tr>
				<tr>
					<td>
						<div align="right">						
						<input type="checkbox" name="ckb_puntosAgenda" id="ckb_puntosAgenda" value="ckb_puntosAgenda" onclick="abrirRegPuntosAgenda();" />
						</div>			
					</td>
					<td colspan="3">Ingresar Puntos Tratados en la Agenda </td>
					<td><div align="right">*Hora Terminaci&oacute;n </div></td>
					<td colspan="4">
						<input name="txt_horaTerminacion" id="txt_horaTerminacion" type="text" class="caja_de_texto" size="5" maxlength="5" value=""
						onchange="formatHora(this,'cmb_horaTerminacion');" onkeypress="return permite(event,'num', 5);"/>
						<select name="cmb_horaTerminacion" id="cmb_horaTerminacion" class="combo_box">
							<option value="AM">a.m.</option>
							<option value="PM">p.m.</option>
						</select>			
					</td>
				</tr>	
				<tr>
					<td>
						<div align="right">
							<input type="checkbox" name="ckb_areasVisitadas" id="ckb_areasVisitadas" value="ckb_areasVisitadas" onclick="abrirRegAreasVisitadas();"/>
						</div>			
					</td>
					<td colspan="3">Ingresar &Aacute;reas Visitadas </td>
					<td><div align="right">*Pr&oacute;xima Reuni&oacute;n </div></td>
					<td colspan="4">
						<input name="txt_proxReunion" type="text" id="txt_proxReunion" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"
						class="caja_de_texto"/>			</td>
				</tr>
				<tr>
					<td>
						<div align="right">
							<input type="checkbox" name="ckb_accidentes" id="ckb_accidentes" value="ckb_accidentes" onclick="abrirRegAccidentes();"/>
						</div>			
					</td>
					<td colspan="3">Ingresar Accidentes Investigados </td>
					<td><div align="right">*Representante </div></td>
					<td colspan="4">
						<input name="txt_representante" id="txt_representante" type="text" class="caja_de_texto" size="60" maxlength="60" value=""
				 		onkeypress="return permite(event,'car', 1);"/>
					</td>
			</tr>
			<tr>
				<td>
					<div align="right">
						<input type="checkbox" name="ckb_recVer" id="ckb_recVer" value="ckb_recVer" onclick="abrirRegRecorridosVer();" />
					</div>			
				</td>
				<td colspan="3">Ingresar Informaci&oacute;n del Recorrido de Verificaci&oacute;n </td>
				<td><div align="right">*Gerente General </div></td>
				<td colspan="4">
					<input name="txt_gteGral" id="txt_gteGral" type="text" class="caja_de_texto" size="60" maxlength="60" value=""
					onkeypress="return permite(event,'car', 1);"/>
				</td>
			</tr>
			<tr><td colspan="7"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
			<tr>
				<td height="45" colspan="9">
					<div align="center">
						<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar" value="Guardar" title="Guardar Acta Seguridad Higiene"
						onmouseover="window.status='';return true" disabled="disabled"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Seguridad e Higiene" 
						onmouseover="window.status='';return true"  onclick="confirmarSalida('menu_actaSeguridadHigiene.php')" />
						<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
					</div>		
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		<div id="calendario">
			<input name="calendario" type="image" id="calendario5" onclick="displayCalendar(document.frm_agregarActa.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>
		</div>
		<div id="calendario2">
			<input name="calendario2" type="image" id="calendario22" onclick="displayCalendar(document.frm_agregarActa.txt_periodoVer,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>
		</div>
		<div id="calendario3">
			<input name="calendario3" type="image" id="calendario32" onclick="displayCalendar(document.frm_agregarActa.txt_al,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>
		</div>
		<div id="calendario4">
			<input name="calendario4" type="image" id="calendario42" onclick="displayCalendar(document.frm_agregarActa.txt_proxReunion,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>					
		</div>
	<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
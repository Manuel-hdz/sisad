<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include("head_menu.php");
		
		include("op_regAvance.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link> 
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="includes/ajax/fallasConsumosTNT.js" ></script>
	<script type="text/javascript" language="javascript">
		//Al Cargar la Pagina colocar el foco en el CoboBox que contiene las ubicaciones
		setTimeout("if(document.frm_regBitAvance.cmb_lugar.type!='hidden'){ document.frm_regBitAvance.cmb_lugar.focus(); }",500);
	</script>
    <style type="text/css">
		<!--
		#titulo-regRezagado {position:absolute; left:30px; top:146px; width:350px; height:20px; z-index:11; }		
		#tabla-registroRezagado {position:absolute; left:30px; top:190px; width:940px; height:190px; z-index:12; }
		#pregunta { position:absolute; left:30px; top:190px; width:940px; height:200px; z-index:14; }
		#calendario { position:absolute; left:690px; top:215px; width:30px; height:27px; z-index:15; }
		-->
    </style>
</head>
<body><?php

	//Para mostrar el formulario de captura de datos, verificar que ninguno de los botones de esta ventana haya sido seleccionado
	if(!isset($_POST['sbt_guardar'])){
		
		//Definir en la SESSION el arreglo que indicará cuales bitácoras han sido registradas
		if(!isset($_SESSION['bitsAgregadas'])){
			$_SESSION['bitsAgregadas'] = array("bitBarrenacion"=>0,"bitBarrenacionMP"=>0,"bitVoladura"=>0,"bitRezagado"=>0);
		}
												
		//Manipular la Activación y Desactivación de los botones de las Bitacoras
		$atrbBotonBarr = ""; $atrbBotonVol = ""; $atrbBotonRez = "";
		$msgBarr = "Registrar Bit&aacute;cora de Barrenaci&oacute;n"; $msgVol = "Registrar Bit&aacute;cora de Voladura"; $msgRez = "Registrar Bit&aacute;cora de Rezagado";
		//if($_SESSION['bitsAgregadas']['bitBarrenacion']==1 && $_SESSION['bitsAgregadas']['bitBarrenacionMP']==1){
		if($_SESSION['bitsAgregadas']['bitBarrenacion']==1){
			$atrbBotonBarr = "disabled='disabled'";
			$msgBarr = "La Bit&aacute;cora de Barrenaci&oacute; ya ha Sido Registrada";
		}
		if($_SESSION['bitsAgregadas']['bitVoladura']==1){
			$atrbBotonVol = "disabled='disabled'";
			$msgVol = "La Bit&aacute;cora de Voladura ya ha Sido Registrada";
		}
		if($_SESSION['bitsAgregadas']['bitRezagado']==1){
			$atrbBotonRez = "disabled='disabled'";
			$msgRez = "La Bit&aacute;cora de Rezagado ya ha Sido Registrada";
		}
	
			
	
		//Obtener el Id de la Bitacora de Avance para poder registrar la Bitacora de Fallas y los Consumos
		$idBitAvance = obtenerIdBitAvance();?>
	
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>		   
		<div id="titulo-regRezagado" class="titulo_barra">Agregar Registro a la Bit&aacute;cora de Avance</div>
						
		<fieldset class="borde_seccion" id="tabla-registroRezagado" name="tabla-registroRezagado">
		<legend class="titulo_etiqueta">Ingresar la informaci&oacute;n del Registro de Avance</legend><?php 
		
		/* El valor del atributo action del formulario estara dado por la funcion direccionarPagina definica en la sección de Bitácora de Avance en el archivo
		 * validacionDesarrollo.js */ ?>
		 
		<form onsubmit="return valFormRegBitAvance(this)" name="frm_regBitAvance" method="post" action="">
		<table class="tabla_frm" width="100%" cellpadding="5" cellspacing="5">			
			<tr>
				<td align="right">*Lugar</td>
				<td colspan="3"><?php	
					$resultado = cargarComboTotal("cmb_lugar","obra","id_ubicacion","catalogo_ubicaciones","bd_desarrollo","Origen","","","id_ubicacion","","");
					if($resultado==0){?>
						<span class="msje_correcto">No Hay Lugares Registradas</span>
						<input type="hidden" name="cmb_lugar" id="cmb_lugar" value="" /><?php
					}?>				
				</td>
				<td>&nbsp;&nbsp;</td>
				<td>
					*Fecha Registro&nbsp;					
				    <input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" readonly="readonly" size="10" value="<?php echo date("d/m/Y"); ?>" />
				</td>
				<td align="right">*Avance</td>
				<td>
					<input type="text" name="txt_avance" id="txt_avance" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);"
					onchange="formatCurrency(this.value,'txt_avance');" />&nbsp;ml				
				</td>	
			</tr>
			<!-- <tr>
				<td width="10%" align="right">Machote</td>
				<td width="30%">
					<input type="text" name="txt_machote" id="txt_machote" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					onchange="formatCurrency(this.value,'txt_machote');" onblur="calcularAvance();" />				
				</td>
				<td width="10%" align="right">Medida</td>
				<td width="20%">
					<input type="text" name="txt_medida" id="txt_medida" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					onchange="formatCurrency(this.value,'txt_medida');" onblur="calcularAvance();" />				
				</td>-->
				<input type="hidden" name="txt_machote" id="txt_machote" value="1" />
				<input type="hidden" name="txt_medida" id="txt_medida" value="1" />
				<!--<td width="10%" align="right">*Avance</td>
				<td width="20%">
					<input type="text" name="txt_avance" id="txt_avance" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);"
					onchange="formatCurrency(this.value,'txt_avance');" />&nbsp;ml				
				</td>				
			</tr> -->
			<tr>
				<td align="right">Observaciones</td>
				<td colspan="5">
					<textarea name="txa_observaciones" id="txa_observaciones" onkeyup="return ismaxlength(this)" maxlength="120" class="caja_de_texto" rows="3" cols="45"
           	 		onkeypress="return permite(event,'num_car', 0);" ></textarea>				
				</td>			
			</tr>
			<tr><td colspan="8">&nbsp;</td></tr>
			<tr>
				<td colspan="8" align="center">		
					<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $idBitAvance; ?>" />
					<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="bitAvance" />
					<input type="hidden" name="hdn_btnClick" id="hdn_btnClick" value="" /> 	
					
					<input type="submit" name="sbt_guardar" class="botones" value="Guardar" title="Guardar Registro en la Bit&aacute;cora de Avance" 
					onmouseover="window.status='';return true" onclick="hdn_btnClick.value='guardar'; direccionarPagina(this.name);" 
					tabindex="10" />
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_regBarrenacion" class="botones" value="Barrenaci&oacute;n" title="<?php echo $msgBarr; ?>" 
					onmouseover="window.status='';return true" <?php echo $atrbBotonBarr; ?> onclick="hdn_btnClick.value='barrenacion'; direccionarPagina(this.name);" 
					tabindex="6" />
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_regVoladura" class="botones" value="Voladura" title="<?php echo $msgVol; ?>" onmouseover="window.status='';return true"
					<?php echo $atrbBotonVol; ?> onclick="hdn_btnClick.value='voladura'; direccionarPagina(this.name);" tabindex="7" />
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_regRezagado" class="botones" value="Rezagado" title="<?php echo $msgRez; ?>" onmouseover="window.status='';return true" 
					<?php echo $atrbBotonRez; ?> onclick="hdn_btnClick.value='rezagado'; direccionarPagina(this.name);" tabindex="8" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" class="botones" value="Cancelar" title="Cancelar el Registro de la Bit&aacute;cora de Avance" 
					onclick="salirRegBitacora(hdn_idBitacora.value,hdn_tipoBitacora.value,'menu_bitAvance.php')" tabindex="9" />
				</td>
			</tr>			
		</table>		
		</form>
		</fieldset>
		
		
		<div id="calendario">
			<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_regBitAvance.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Registro" tabindex="3" /> 
		</div><?php
	} 
	else{
		//Guardar el Registro en la Bitacora de Avance		
		guardarBitAvance();		
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
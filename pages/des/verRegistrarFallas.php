<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 25/Octubre/2011
	  * Descripción: En este archivo se guardan las fallas de los equipos
	  **/ 

	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("op_bitFallasConsumosTNT.php");?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>	
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js"></script>
	<script type="text/javascript" src="includes/ajax/fallasConsumosTNT.js"></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		
		setTimeout("definirCombo();",500);//Llamar a la funcion que definira el ComboBox de tipo de registro segun la bitacora que esta siendo registrada
		setTimeout("document.getElementById('cmb_tipo').focus();",600);
		//-->
	</script>
	
	<style type="text/css">
		<!--
		#form-registro {position:absolute; left:20px; top:40px; width:780px; height:227px; z-index:1; }		
		#ver-registrosFallas { position:absolute; left:20px; top:310px; width:780px; height:270px; z-index:2; overflow:scroll; }
		-->
    </style>
</head>
<body><?php

	//Cuando sea seleccionado el boton guardar, guardar los datos de la falla en la BD
	$msg_agregarRegistro = "";
	if(isset($_POST['sbt_guardar'])){		
		$msg_agregarRegistro = guardarRegistroFalla();
	}
	
	//La declaración del DIV se encuentra dentro de la funcion		
	$datosMostrados = verRegistroFallas($_GET['idBitacora'], $_GET['tipoBitacora'],$_GET['tipoRegistro']);?>	

	
	<fieldset class="borde_seccion" id="form-registro" name="form-registro">
	<legend class="titulo_etiqueta">Ingresar los Detalles de la Falla</legend>
	<br /><br />
	<form onSubmit="return valFormRegBitFallas(this);" name="frm_regBitFallas" method="post" 
	action="verRegistrarFallas.php?idBitacora=<?php echo $_GET['idBitacora'];?>&tipoBitacora=<?php echo $_GET['tipoBitacora'];?>&tipoRegistro=<?php echo $_GET['tipoRegistro'];?>">
	<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td width="15%" align="right">*Tipo</td>
			<td width="20%">
				<select name="cmb_tipo" id="cmb_tipo" class="combo_box" tabindex="1">
					<option value="">Tipo</option>
				</select>
			</td>
			<td width="15%" align="right">*Descripci&oacute;n</td>
			<td width="20%">
				<textarea name="txa_observaciones" onkeyup="return ismaxlength(this)" maxlength="120" class="caja_de_texto" rows="3" cols="30" 
				onkeypress="return permite(event,'num_car',0);" tabindex="2" ></textarea>
			</td>
			<td width="15%" align="right">*Tiempo Gastado</td>
			<td width="15%">
				<input type="text" name="txt_tiempoHrs" id="txt_tiempoHrs" class="caja_de_texto" size="5" maxlength="10" onkeypress="return permite(event,'num',2);" tabindex="3" />&nbsp;Hrs.
			</td>
		</tr>
		<tr>			
			<td align="right">*Equipo</td>
			<td colspan="5">
				<input type="text" name="txt_equipo" id="txt_equipo" class="caja_de_texto" size="20" readonly="readonly" value="" />				
				<input type="hidden" name="hdn_claveEquipo" id="hdn_claveEquipo" value="" />
			</td>
		</tr>
		<tr>
			<td colspan="8" class="msje_incorrecto" align="center"><?php
				//El mensaje se muestre sobre los botones del formulario
				echo $msg_agregarRegistro;?>		
			</td>
		</tr>
		<tr>
			<td colspan="8" align="center">
				<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $_GET['idBitacora']; ?>" />
				<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="<?php echo $_GET['tipoBitacora']; ?>" />
				<input type="hidden" name="hdn_tipoRegistro" id="hdn_tipoRegistro" value="<?php echo $_GET['tipoRegistro']; ?>" /><?php
				
				
				if($datosMostrados==1){?>
					<input type="button" name="btn_finalizar" value="Finalizar" class="botones" title="Finalizar Registro de Fallas" 
					onclick="finalizar('fallas');" tabindex="6" /><?php
				}?>					
				&nbsp;&nbsp;
				<input type="submit" name="sbt_guardar" value="Guardar" class="botones" title="Guardar Registro" onmouseover="window.status='';return true" tabindex="4" />
				&nbsp;&nbsp;<?php //Cuando se cancele el registro, realizar una peticion asincrona para borrar los registros guardados para la bitacora actual?>				
				<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Cancelar el Registro de Fallas" onclick="cerrarVentana('fallas');" tabindex="5" />				
		  	</td>
		</tr>
	</table>	
	</form>
</fieldset>			
</body>
</html>
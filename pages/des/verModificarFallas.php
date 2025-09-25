<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 08/Noviembre/2011
	  * Descripción: En este archivo se modifican las fallas de los equipos
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
		
		//Llamar a la funcion que definira el ComboBox de tipo de registro segun la bitacora que esta siendo modificada
		setTimeout("definirCombo();",500);
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

	//Variable para almacenar el mensaje regresado por las opciones de Guardar, Modificar y Borrar los datos de las Fallas
	$msg_resOper = "";
	
	//Cuando sea seleccionado el boton Guardar, guardar los datos de la falla en la BD	
	if(isset($_POST['sbt_guardar'])){		
		$msg_resOper = guardarRegistroFalla();
	}
	
	//Cuando sea seleccionado el boton Modificar, modificar el registro de falla seleccionado
	if(isset($_POST['sbt_modificar'])){		
		$msg_resOper = modificarRegistroFalla();//DESARROLLAR ESTA FUNCION
	}
	
	//Cuando sea seleccionado el boton Borrar, borrar el registro seleccionado
	if(isset($_POST['sbt_borrar'])){		
		$msg_resOper = borrarRegistroFalla();//DESARROLLAR ESTA FUNICION
	}?>


	
	<form onSubmit="return valFormRegBitFallas(this);" name="frm_regBitFallas" method="post" 
	action="verModificarFallas.php?idBitacora=<?php echo $_GET['idBitacora'];?>&tipoBitacora=<?php echo $_GET['tipoBitacora'];?>&tipoRegistro=<?php echo $_GET['tipoRegistro'];?>">
	
	
	<div id="ver-registrosFallas" align="center" class="borde_seccion2"><?php		
		$datosMostrados = seleccionarRegistroFallas();?>	
	</div>

	
	<fieldset class="borde_seccion" id="form-registro" name="form-registro">
	<legend class="titulo_etiqueta">Modificar los Detalles de la Falla</legend>
	<br /><br />
	
	<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td width="15%" align="right">*Tipo</td>
			<td width="20%">
				<select name="cmb_tipo" id="cmb_tipo" class="combo_box">
					<option value="">Tipo</option>
				</select>
			</td>
			<td width="15%" align="right">*Descripci&oacute;n</td>
			<td width="20%">
				<textarea name="txa_observaciones" id="txa_observaciones" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="3" cols="30" 
				onkeypress="return permite(event,'num_car',0);" ></textarea>
			</td>
			<td width="15%" align="right">*Tiempo Gastado</td>
			<td width="15%">
				<input type="text" name="txt_tiempoHrs" id="txt_tiempoHrs" class="caja_de_texto" size="5" maxlength="10" onkeypress="return permite(event,'num',2);" />&nbsp;Hrs.
			</td>
		</tr>
		<tr>			
			<td align="right">*Equipo</td>
			<td colspan="5">
				<input type="text" name="txt_equipo" id="txt_equipo" class="caja_de_texto" size="20" readonly="readonly" value="" />				
			</td>
		</tr>
		<tr>
			<td colspan="8" class="msje_incorrecto" align="center"><?php
				//El mensaje se muestre sobre los botones del formulario
				echo $msg_resOper;?>		
			</td>
		</tr>
		<tr>
			<td colspan="8" align="center">				
				<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $_GET['idBitacora']; ?>" />
				<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="<?php echo $_GET['tipoBitacora']; ?>" />
				<input type="hidden" name="hdn_tipoRegistro" id="hdn_tipoRegistro" value="<?php echo $_GET['tipoRegistro']; ?>" /><?php
				
				
				if($datosMostrados==1){?>
					<input type="button" name="btn_finalizar" value="Finalizar" class="botones" title="Finalizar Registro de Fallas" 
					onclick="window.close();" /><?php
				}?>					
				&nbsp;&nbsp;
				<input type="submit" name="sbt_guardar" id="sbt_guardar" value="Guardar" class="botones" title="Guardar Registro" onmouseover="window.status='';return true" />
				&nbsp;&nbsp;
				<input type="submit" name="sbt_modificar" id="sbt_modificar" value="Modificar" class="botones" title="Seleccionar un Registro para Modificar" 
				onmouseover="window.status='';return true" disabled="disabled" />
				&nbsp;&nbsp;
				<input type="submit" name="sbt_borrar" id="sbt_borrar" value="Borrar" class="botones" title="Seleccionar un Registro para Borrar" 
				onmouseover="window.status='';return true" disabled="disabled" />							
				&nbsp;&nbsp;
				<input type="reset" name="rst_restablecer" value="Restablecer" class="botones" title="Restablecer los Campos de Formulario" 
				onclick="restBotones('fallas'); setTimeout('definirCombo();',500);" />
		  	</td>
		</tr>
	</table>		
	</fieldset>			
	
	
	</form>
</body>
</html>
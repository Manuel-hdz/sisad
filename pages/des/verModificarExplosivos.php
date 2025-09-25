<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 23/Marzo/2012
	  * Descripción: En este archivo estan las funciones para modificar los registros de explosivos
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
	<script type="text/javascript" src="../../includes/formatoNumeros.js"></script>
	<script type="text/javascript" src="includes/ajax/fallasConsumosTNT.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
				
		//-->
	</script>
	
	<style type="text/css">
		<!--
		#form-registro {position:absolute; left:20px; top:40px; width:780px; height:209px; z-index:1; }		
		#ver-registrosExplosivos { position:absolute; left:20px; top:310px; width:780px; height:270px; z-index:2; overflow:scroll; }
		-->
    </style>
</head>
<body><?php

	//Variable para almacenar el mensaje regresado por las opciones de Guardar, Modificar y Borrar los datos de Explosivos
	$msg_resOper = "";

	//Cuando sea seleccionado el boton Guardar, guardar los datos de la falla en la BD	
	if(isset($_POST['sbt_guardar'])){		
		$msg_resOper = guardarRegistroExplosivos();
	}
	
	//Cuando sea seleccionado el boton Modificar, modificar el registro de consumo seleccionado
	if(isset($_POST['sbt_modificar'])){		
		$msg_resOper = modificarRegistroExplosivo();
	}
	
	//Cuando sea seleccionado el boton Borrar, borrar el registro seleccionado
	if(isset($_POST['sbt_borrar'])){		
		$msg_resOper = borrarRegistroExplosivo();
		
	}?>
	
	
	<form onSubmit="return valFormRegBitExplosivos(this);" name="frm_regBitExplosivos" method="post" 
	action="verModificarExplosivos.php?idBitacora=<?php echo $_GET['idBitacora']; ?>">
	
	
	<div id="ver-registrosExplosivos" align="center" class="borde_seccion2"><?php
		$datosMostrados = seleccionarRegistroExplosivos();?>	
	</div>

	
	<fieldset class="borde_seccion" id="form-registro" name="form-registro">
	<legend class="titulo_etiqueta">Explosivos Consumidos</legend>
	<br /><br />
	
	<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td width="20%" align="right">*Material</td>
			<td width="40%"><?php
				cargarComboConId("cmb_explosivo","nombre","id_explosivos","catalogo_explosivos","bd_desarrollo","Explosivo","","cargarDatosExplosivos(this.value);");?></td>
			<td width="10%" align="right">Categoria</td>
			<td width="30%"><input name="txt_categoria" type="text" class="caja_de_texto" id="txt_categoria" value="" size="40" readonly="readonly" /></td>
		</tr>
		<tr>						
			<td align="right">*Cantidad Utilizada</td>
			<td>
				<input type="text" name="txt_cantidad" id="txt_cantidad" class="caja_de_texto" size="10" maxlength="15" onkeypress="return permite(event,'num',2);"
			 	onchange="formatCurrency(this.value,'txt_cantidad');"/>
			</td>
			<td align="right">Medida</td>
			<td><input type="text" name="txt_medida" id="txt_medida" class="caja_de_texto" readonly="readonly" value="" /></td>
		</tr>
		<tr>
			<td colspan="4" class="msje_incorrecto"><?php
				//El mensaje se muestre sobre los botones del formulario
				echo $msg_resOper;?>			
			</td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $_GET['idBitacora']; ?>" />
				<input type="hidden" name="hdn_botonSelect" id="hdn_botonSelect" value="" /><?php
								
				if($datosMostrados==1){?>
					<input type="button" name="btn_finalizar" value="Finalizar" class="botones" title="Finalizar Registro de Explosivos" 
					onclick="finalizar('explosivos');" /><?php
				}?>								
				&nbsp;&nbsp;
				<input type="submit" name="sbt_guardar" id="sbt_guardar" value="Guardar" class="botones" title="Guardar Registro" onmouseover="window.status='';return true" 
				onclick="hdn_botonSelect.value='guardar'" />
				&nbsp;&nbsp;
				<input type="submit" name="sbt_modificar" id="sbt_modificar" value="Modificar" class="botones" title="Seleccionar un Registro para Modificar" 
				onmouseover="window.status='';return true" disabled="disabled" onclick="hdn_botonSelect.value='modificar'" />
				&nbsp;&nbsp;
				<input type="submit" name="sbt_borrar" id="sbt_borrar" value="Borrar" class="botones" title="Seleccionar un Registro para Borrar" 
				onmouseover="window.status='';return true" disabled="disabled" onclick="hdn_botonSelect.value='borrar'" />
				&nbsp;&nbsp;
				<input type="reset" name="rst_restablecer" value="Restablecer" class="botones" title="Restablecer los Campos de Formulario" 
				onclick="restBotones('explosivos'); cmb_explosivo.disabled=false;" />						
			</td>
		</tr>
	</table>		
	</fieldset>	
				
</form>
	
</body>
</html>
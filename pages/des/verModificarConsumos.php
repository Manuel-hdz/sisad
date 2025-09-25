<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 15/Marzo/2012
	  * Descripción: En este archivo estan las funciones para modificar los consumos
	  **/ 

	include("../../includes/conexion.inc");
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
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		
		//Obtener el nombre del Equipo de la Pagina de Registro de la Bitacora de Rezagado
		setTimeout("obtenerNomEquipo()",500);										
		
		function obtenerNomEquipo(){			
			//Obtener el nombre del equipo de la pagina que abre la ventana donde se registran los consumos
			equipo = window.opener.document.getElementById("cmb_equipo").value; 
			
			//Asignar el equipo a la caja de texto que lo mostrara en la pagina de Registrar Consumos			
			document.getElementById("txt_equipo").value = equipo;
			
			//Asignarle el No. de Indexación al combo de Categoria
			document.getElementById("cmb_categoria").tabIndex = 1;
			
			//Colocar el foco en el ComboBox de Categoria
			document.getElementById("cmb_categoria").focus();
		}
		//-->
	</script>
	
	<style type="text/css">
		<!--
		#form-registro {position:absolute; left:20px; top:40px; width:780px; height:250px; z-index:1; }		
		#ver-registrosConsumos { position:absolute; left:20px; top:320px; width:780px; height:270px; z-index:2; overflow:scroll; }
		-->
    </style>
</head>
<body><?php

	//Variable para almacenar el mensaje regresado por las opciones de Guardar, Modificar y Borrar los datos Consumos
	$msg_resOper = "";
	
	//Cuando sea seleccionado el boton Guardar, guardar los datos de la falla en la BD	
	if(isset($_POST['sbt_guardar'])){		
		$msg_resOper = guardarRegistroConsumo();
	}
	
	//Cuando sea seleccionado el boton Modificar, modificar el registro de consumo seleccionado
	if(isset($_POST['sbt_modificar'])){		
		$msg_resOper = modificarRegistroConsumo();
	}
	
	//Cuando sea seleccionado el boton Borrar, borrar el registro seleccionado
	if(isset($_POST['sbt_borrar'])){		
		$msg_resOper = borrarRegistroConsumo();
	}?>
	
	
	<form onSubmit="return valFormRegBitConsumos(this);" name="frm_regBitConsumos" method="post" 
	action="verModificarConsumos.php?idBitacora=<?php echo $_GET['idBitacora'];?>&tipoBitacora=<?php echo $_GET['tipoBitacora'];?>&tipoRegistro=<?php echo $_GET['tipoRegistro'];?>">


	<div id="ver-registrosConsumos" align="center" class="borde_seccion2"><?php				
		$datosMostrados = seleccionarRegistroConsumos(); ?>	
	</div>

	
	<fieldset class="borde_seccion" id="form-registro" name="form-registro">
	<legend class="titulo_etiqueta">Materiales Consumidos</legend>
	<br /><br />
	
	<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td align="right">*Categoria</td>
			<td colspan="4"><?php
				cargarComboConId("cmb_categoria","linea_articulo","linea_articulo","materiales","bd_almacen","Categoria","",
								 "cargarComboConId(this.value,'bd_almacen','materiales','nom_material','id_material','linea_articulo','cmb_idMaterial','Material','')");?>
				<input type="checkbox" name="chk_nvoMaterial" id="chk_nvoMaterial" value="" onclick="activarCamposConsumos(this);" tabindex="2" />Agregar Nuevo Material
			</td>						
		</tr>
		<tr>
			<td align="right">*Material</td>
			<td colspan="2">
				<select name="cmb_idMaterial" id="cmb_idMaterial" class="combo_box" onchange="hdn_nomMaterial.value = this.options[this.selectedIndex].text" tabindex="3">
              		<option value="">Material</option>
            	</select>
		  		<input type="hidden" name="hdn_nomMaterial" dir="hdn_nomMaterial" value="" />			
			</td>
			<td align="right">*Material </td>
			<td>
				<input type="text" name="txt_material" id="txt_material" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',0);" 
				readonly="readonly" />
			</td>		
		</tr>
		<tr>						
			<td align="right" width="15%">*Cantidad</td>
			<td width="15%">
				<input type="text" name="txt_cantidad" id="txt_cantidad" class="caja_de_texto" size="5" maxlength="10" onkeypress="return permite(event,'num',2);" tabindex="4"
				onchange="formatCurrency(this.value,'txt_cantidad')" />
			</td>
			<td align="right" width="15%">&nbsp;</td>
			<td width="15%" align="right">*Unidad de Medida </td>
			<td width="15%">
				<input type="text" name="txt_unidadMedida" id="txt_unidadMedida" class="caja_de_texto" size="10" maxlength="15" onkeypress="return permite(event,'num_car',0);"
				readonly="readonly" />
			</td>			
		</tr>
		<tr>
			<td align="right">*Equipo</td>
			<td colspan="2"><input type="text" name="txt_equipo" id="txt_equipo" class="caja_de_texto" size="20" readonly="readonly" value="" /></td>
			<td align="right">*Cantidad</td>
			<td>
				<input type="text" name="txt_cant" id="txt_cant" class="caja_de_texto" size="10" maxlength="15" onkeypress="return permite(event,'num',2);" 
				readonly="readonly" onchange="formatCurrency(this.value,'txt_cant')" />
			</td>
		</tr>
		<tr>
			<td colspan="5"  class="msje_incorrecto" align="center"><?php
				//El mensaje se muestre sobre los botones del formulario
				echo $msg_resOper;?>			
			</td>
		</tr>
		<tr>
			<td colspan="5" align="center">
				<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $_GET['idBitacora']; ?>" />
				<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="<?php echo $_GET['tipoBitacora']; ?>" />
				<input type="hidden" name="hdn_tipoRegistro" id="hdn_tipoRegistro" value="<?php echo $_GET['tipoRegistro']; ?>" />
				<input type="hidden" name="hdn_botonSelect" id="hdn_botonSelect" value="" /><?php
				
				
				if($datosMostrados==1){?>
					<input type="button" name="btn_finalizar" value="Finalizar" class="botones" title="Finalizar Registro de Consumos" 
					onclick="finalizar('consumos');" /><?php
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
				onclick="restBotones('consumos'); setTimeout('obtenerNomEquipo()',500);" />
			</td>
		</tr>
	</table>		
	</fieldset>			
	
	</form>
	
</body>
</html>
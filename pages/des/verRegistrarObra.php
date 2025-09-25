<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 01/Marzo/2011
	  * Descripción: Este archivo contiene el formulario para registrar una Obra
	  **/ 

	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("op_gestionarObras.php");?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />	
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		
		
		//Agregar la Opcion de agregar nuevo elemento a la listas desplegables de Area y Bloque con un retraso de medio segundo
		setTimeout("agregarNvasOpciones();",500);
		//-->
	</script>	
	
	<style type="text/css">
		<!--
		#form-registro {position:absolute; left:20px; top:10px; width:780px; height:210px; z-index:1; }		
		#resultado-opr { position:absolute; left:20px; top:280px; width:780px; height:320px; z-index:2; }
		-->
    </style>
</head>
<body><?php

	//Cuando sea seleccionado el boton guardar, guardar los datos de la obra en la BD	
	if(isset($_POST['sbt_guardar'])){?>
		<div id="resultado-opr" align="center"><?php
			guardarObra();?>
		</div><?php
	}	
	
	//Obtener el ID de la obra que será registrada
	$idObra = obtenerIdObra();?>	
	
	
	<fieldset class="borde_seccion" id="form-registro" name="form-registro">
	<legend class="titulo_etiqueta">Registrar Nuevas Obras</legend>
	<form onSubmit="return valFormRegObra(this);" name="frm_regObra" method="post" action="verRegistrarObra.php">
	<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td align="right">ID Obra</td>
			<td><input type="text" name="txt_idObra" class="caja_de_texto" size="10" readonly="readonly" value="<?php echo $idObra; ?>" /></td>						
			<td align="right">Cliente</td>
			<td colspan="2"><?php	
				$resultado = cargarComboTotal("cmb_idCliente","nom_cliente","id_cliente","catalogo_clientes","bd_desarrollo","Cliente","","","id_cliente","","");
				if($resultado==0){?>
					<span class="msje_correcto">No Hay Clientes Registradas</span>
					<input type="hidden" name="cmb_idCliente" id="cmb_idCliente" value="" /><?php
				}?>			
			</td>
		</tr>
		<tr>
			<td align="right">*Area</td>
			<td colspan="2"><?php	
				$resultado = cargarComboConId("cmb_area","area","area","catalogo_ubicaciones","bd_desarollo","&Aacute;rea","","agregarNvaOpcion(this);");
				if($resultado==0){?>
					<select name="cmb_area" id="cmb_area" class="combo_box" onchange="agregarNvaOpcion(this);">
						<option value="">&Aacute;rea</option>
					</select><?php
				}?>			
			</td>
			<td align="right">*Bloque</td>
			<td><?php	
				$resultado = cargarComboConId("cmb_bloque","bloque","bloque","catalogo_ubicaciones","bd_desarollo","Bloque","","agregarNvaOpcion(this);");
				if($resultado==0){?>
					<select name="cmb_bloque" id="cmb_bloque" class="combo_box" onchange="agregarNvaOpcion(this);">
						<option value="">Bloque</option>
					</select><?php
				}?>
			</td>		
		</tr>
		<tr>						
			<td align="right">*Obra</td>
			<td colspan="2">
				<input type="text" name="txt_nomObra" id="txt_nomObra" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car',0);" 
				onchange="obtenerDatoBD(this.value,'bd_desarrollo','catalogo_ubicaciones','id_ubicacion','obra','hdn_claveValida');" />
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>		
		<tr>
			<td width="15%">&nbsp;</td>
			<td width="15%">&nbsp;</td>
			<td width="15%">&nbsp;</td>
			<td width="15%">&nbsp;</td>
			<td width="15%">&nbsp;</td>
		</tr>		
		<tr>
			<td colspan="5" align="center">								
				<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="" />
				<input type="submit" name="sbt_guardar" value="Guardar" class="botones" title="Guardar Registro" onmouseover="window.status='';return true"/>
				&nbsp;&nbsp;
				<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Limpiar los Campos del Formulario"  />
				&nbsp;&nbsp;
				<input type="button" name="btn_cerrar" value="Cerrar" class="botones" title="Cerrar Ventana de Registro" onclick="window.close();" />
			</td>
		</tr>
	</table>	
	</form>
	</fieldset>
	
</body>
</html>
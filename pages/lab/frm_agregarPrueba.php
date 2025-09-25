<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-agregarPrueba {position:absolute;left:30px;top:190px;width:850px;height:350px;z-index:12;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Pruebas</div>
	
	<fieldset class="borde_seccion" id="tabla-agregarPrueba">
	<legend class="titulo_etiqueta">Agregar Prueba</legend>	
	<br>
	<form name="frm_agregarPrueba" method="post" action="op_agregarPruebas.php" onsubmit="return valFormAgregarPrueba(this);">
	<table width="900" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="125"><div align="right">**Norma de Prueba</div></td>
			<td width="213"><input name="txt_norma" id="txt_norma" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car', 0);"/></td>
			<td><div align="right">*Nombre de Prueba</div></td>
			<td><input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car', 0);"/></tr>
		<tr>
			<td><div align="right">*Tipo de Prueba</div></td>
			<td><?php 
				$grupo=cargarComboEspecifico("cmb_tipo","tipo","catalogo_pruebas","bd_laboratorio","1","estado","Tipo",""); 
				if($grupo==0){ 
					echo "<label class='msje_correcto'>Es Necesario Agregar Nuevo Tipo</label>";?>
					<input type="hidden" name="cmb_tipo" id="cmb_tipo" />
				<?php }?>
			</td>
			<td><div align="right"><input type="checkbox" name="ckb_nuevoTipo" id="ckb_nuevoTipo" onclick="nuevoTipo();"/>Agregar Tipo</div></td>
			<td><input name="txt_nuevoTipo" id="txt_nuevoTipo" type="text" class="caja_de_texto" size="40" disabled="disabled"/>
			</td>
		</tr>
		<tr>
			<td><div align="right">Descripci&oacute;n</div></td>
			<td colspan="4"><textarea name="txa_descripcion" id="txa_descripcion" class="caja_de_texto" cols="60" rows="3" maxlength="120" onkeyup="return ismaxlength(this)"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong><br />
				<strong>**Se recomienda asignar la Norma de Referencia de la Prueba, en caso de no asignarla, el sistema asignará <u>N/A</u> autom&aacute;ticamente.</strong>
			</td></tr>
		<tr>
			<td colspan="6">
				<div align="center">       	    	
					<input name="sbt_guardar" type="submit" class="botones"  value="Guardar" title="Guardar los Datos" onMouseOver="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Reestablecer Formulario" 
					onMouseOver="window.status='';return true" onclick="cmb_tipo.disabled=false;ckb_nuevoTipo.checked=false;txt_nuevoTipo.disabled=true;txt_nuevoTipo.readOnly=false;"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Pruebas" 
                    onMouseOver="window.status='';return true" onclick="confirmarSalida('menu_pruebas.php')" />
                </div>
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
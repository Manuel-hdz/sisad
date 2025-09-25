<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Manejo de la funciones para Registrar los datos del Aspirante en la BD 
		include ("op_modificarAspirante.php");?>	

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute; left:30px; top:146px; width:267px; height:20px; z-index:11; }
		#tabla-modificarContactoAspirantes { position:absolute; left:20px; top:190px; width:954px; height:308px; z-index:12; padding:15px; padding-top:0px;}
		#resultados-modificarContactoAspirante { position:absolute; left:110px; top:544px; width:769px; height:130px; z-index:22; overflow:scroll; }		
		-->
    </style>
</head>
<body><?php 	
	
	//Desplegar los Registros de los Contactos Asociados al Aspirante cuando al menos uno haya sido agregado a la SESSION
	if(isset($_SESSION['datosContactoAspirante'])){?>
		<div id="resultados-modificarContactoAspirante" class='borde_seccion2' align="center"><?php
			mostrarContactosAspirante();?>
		</div><?php 
	} ?>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar los Contacto del Aspirante</div>
	
	<fieldset class="borde_seccion" id="tabla-modificarContactoAspirantes">
	<legend class="titulo_etiqueta">Modificar Contactos del Aspirante</legend>	
	<br>
	<form onSubmit="return valFormModificarContactoAspirante(this);" name="frm_modificarContactoAspirante" method="post" action="frm_modificarContactoAspirante.php" > 
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="20%"><div align="right">Folio Aspirante</div></td>
			<td width="28%">
				<?php $folioAspirante = $_SESSION['datosAspirante']['folio'];?>
				<input name="txt_folioAspirante" id="txt_folioAspirante" type="text" class="caja_de_texto" size="10" maxlength="10" 
					onkeypress="return permite(event,'num_car', 3);" value="<?php echo $folioAspirante;?>"  readonly="readonly" />		 
			</td>
			<td width="18%"><div align="right">Nombre del Aspirante</div></td>
			<td width="34%">
				<?php //Variable para almacenar el nombre del aspirante concatenado y que por medio de la SESSION me envie a este formulario el nombre completo del aspirantes(nombre concatenado) ?>
				<?php $nomAspirante = $_SESSION['datosAspirante']['nombre']." ".$_SESSION['datosAspirante']['apePat']." ".$_SESSION['datosAspirante']['apeMat'];?>
				<input name="txt_nombreAspirante" id="txt_nombreAspirante" type="text" class="caja_de_texto" readonly="readonly" size="60" maxlength="60" value="<?php echo $nomAspirante; ?> "/>
			</td>
		</tr>
		<tr>
			<td><div align="right">*Nombre del Contacto </div></td>
			<td><input name="txt_nombreCont" id="txt_nombreCont" type="text" class="caja_de_texto" size="50" maxlength="60" onkeypress="return permite(event,'car',0);" /></td>
			<td><div align="right">*Colonia</div></td>
			<td><input name="txt_colonia" id="txt_colonia" type="text" class="caja_de_texto" size="40" maxlength="60" onkeypress="return permite(event,'num_car',1);" /></td>
		</tr>
		<tr>
			<td><div align="right">*Calle</div></td>
			<td><input name="txt_calle" id="txt_calle" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car',1);" /></td>
			<td><div align="right">*Estado</div></td>
			<td><input name="txt_estado" id="txt_estado" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',1);" /></td>
		</tr>
		<tr>
			<td><div align="right">*N&uacute;mero Ext.</div></td>
			<td align="left">	
				<input name="txt_numExt" type="text" class="caja_de_texto" size="5" maxlength="10" />&nbsp;&nbsp;</td>
			<td><div align="right">*Pa&iacute;s</div></td>
			<td><input type="text" name="txt_pais" id="txt_pais" size="20" maxlength="20" onkeypress="return permite(event,'num_car',1);" class="caja_de_texto" /></td>
		</tr>
		<tr>
			<td><div align="right">N&uacute;mero Int.</div></td>
			<td><input name="txt_numInt" type="text" class="caja_de_texto" size="5" maxlength="10" /></td>
			<td><div align="right">*Telefono</div></td>
			<td><input name="txt_tel" id="txt_tel" type="text" class="caja_de_texto" size="20" maxlength="15" onkeypress="return permite(event,'num',3);" onblur="validarTelefono(this);" /></td>
		</tr>
		<tr>
		   <td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
   		   <td colspan="2" class="msje_correcto" align="right"><strong><?php echo $msgContactoAspirante; ?></strong></td>
		</tr>
		<tr>
			<td colspan="4">
				<div align="center">
				<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
				<?php if(isset($_SESSION['datosContactoAspirante'])){//Si al menos un puesto ha sido agregado, mostrar el boton de Ginalizar ?>
					<input name="sbt_finalizarModificacionContacto" type="submit" class="botones"  value="Finalizar" title="Finalizar Modificación de los Contactos del Aspirante" 
					onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='finalizarRegistro';" />
				<?php } ?>
				&nbsp;&nbsp;&nbsp;
				<input name="sbt_registrarContactoAspirante" type="submit" class="botones_largos" id="sbt_registrarContactoAspirante" title="Agregar los Contactos del Aspirante Registrado" 
				onMouseOver="window.status='';return true"  value="Agregar Contactos" onclick="hdn_botonSeleccionado.value='registrarContacto';"  />				
				&nbsp;&nbsp;&nbsp;
				<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/> 
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar el Registro del Aspirante" 
				onMouseOver="window.status='';return true"  onclick="confirmarSalida('menu_bolsaTrabajo.php');" />
				</div>
			</td>
		</tr>
	</table>
	</form>
</fieldset>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
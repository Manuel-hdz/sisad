<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php


	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
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
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#titulo-paginaGestionar {position:absolute;left:30px;top:146px;width:538px;height:20px;z-index:11;}
		#tabla-seleccionarOperacion {position:absolute;left:30px;top:190px;width:258px;height:160px;z-index:12;padding:15px;padding-top:0px;}
		-->
    </style>
    
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-gomar.png" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-paginaGestionar">Gestionar Ordenes de Trabajo para Servicios Externos</div>
			 
	<fieldset class="borde_seccion" id="tabla-seleccionarOperacion" name="tabla-seleccionarOperacion">
		<legend class="titulo_etiqueta">Seleccionar Operaci&oacute;n a Realizar</legend>	
		<br />
		<?php //Este formulario no requiere la propiedad onSubmit, el atributo actio tomara su valor de acuerdo a la opción seleccionada en el combo ?>
		<form name="frm_seleccionarOperacion" method="post" action="">
			<table width="100%" border="0" class="tabla_frm" cellpadding="5" cellspacing="5">
				<tr>
					<td width="50%" align="right">Operaci&oacute;n</td>
					<td width="50%">
						<select name="cmb_operacion" id="cmb_operacion" class="combo_box" onchange="direccionarPagina(this.value);">
							<option value="">Operaci&oacute;n</option>
							<option value="REGISTRAR" title="Registrar Orden de Trabajo para Servicio Externo">REGISTRAR</option>
							<option value="CONSULTAR" title="Consultar Ordenes de Trabajo para Servicios Externos">CONSULTAR</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="button" name="btn_regrear" class="botones" title="Regresar al Men&uacute; de Orden de Trabajo" onclick="location.href='menu_ordenTrabajo.php'"
						value="Regresar" />
					</td>
				</tr>
			</table>
		</form>       
</fieldset>  
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
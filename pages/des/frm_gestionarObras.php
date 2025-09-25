<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php

	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		
		include ("op_gestionarObras.php");
		?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js"></script>
	<script type="text/javascript" src="includes/ajax/gestObras.js"></script>
	<script type="text/javascript" language="javascript">
		var vntRegObra = "";//Esta variable guardará la referencia de la página de Registrar Obra para detectar cuando ésta sea crerrada.
		var vntModObra = "";//Esta variable guardará la referencia de la página de Modificar Obra para detectar cuando ésta sea crerrada.
		//Despues de medio segundo (500 Milisegundos) cargar el combo de Obras
		setTimeout("cargarComboObras()",500);
	</script>
    <style type="text/css">
		<!--
		#titulo-gestionarObras {position:absolute; left:30px; top:146px; width:350px; height:20px; z-index:11; }		
		#datos-obra { position:absolute; left:30px; top:190px; width:557px; height:160px; z-index:12; }
		#consulta-datosObra { position:absolute; left:30px; top:400px; width:940px; height:220px; z-index:13; overflow:scroll; }
		-->
    </style>
</head>
<?php //Cuando las ventanas de Registrar y Modificar Obra sean cerradas se procede a recargar esta página ?>
<body onfocus="if(vntRegObra.closed){ cargarComboObras(); } if(vntModObra.closed){ cargarComboObras(); }">
	
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>		   
	<div id="titulo-gestionarObras" class="titulo_barra">Gestionar Obras Desarrollo</div>
	
	
	
    <fieldset class="borde_seccion" id="datos-obra" name="datos-obra">
	<legend class="titulo_etiqueta">Seleccionar Operaci&oacute;n a Realizar</legend>
	<br />
	<?php //La validación del formulario se hace en la funcion de Javascript => identificarOperacion() ?>
	<form name="frm_gestionarObras" method="post" action="frm_gestionarObras.php"> 
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td align="right">Obra</td>
			<td>
				<select name="cmb_obra" id="cmb_obra" class="combo_box">
					<option value="">Obra</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="button" name="btn_registrar" id="btn_registrar" class="botones" value="Registrar" title="Registrar Nueva Obra" 
				onclick="identificarOperacion('registrar');" />
				&nbsp;&nbsp;
				<input type="button" name="btn_modificar" id="btn_modificar" class="botones" value="Modificar" title="Modificar Obra Seleccionada" 
				onclick="identificarOperacion('modificar');" />
				&nbsp;&nbsp;
				<input type="button" name="btn_consultar" id="btn_consultar" class="botones" value="Consultar" title="Consultar Obra Seleccionada" 
				onclick="identificarOperacion('consultar');" />
				&nbsp;&nbsp;
				<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar al Men&uacute; de Bit&aacute;coras" 
				onclick="location.href='menu_bitacora.php'" />
			</td>
		</tr>		
	</table>
	</form>
	</fieldset>		
    
	<div class="borde_seccion2" id="consulta-datosObra" align="center" style="visibility:hidden;"></div>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
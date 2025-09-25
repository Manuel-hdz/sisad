	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_seleccionarPermiso.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-registrar {	position:absolute;	left:30px;	top:146px;	width:384px;	height:20px;	z-index:11;}
			#tabla-seleccionarRegistro {position:absolute;left:29px;top:192px;width:338px;height:149px;z-index:12;padding:15px;padding-top:0px;}
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8; overflow:scroll}
		-->
    </style>
</head>
<body>
<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-registrar">Generar Permisos para Realizar Trabajos Peligrosos</div>
	<fieldset class="borde_seccion" id="tabla-seleccionarRegistro" name="tabla-seleccionarRegistro">
		<legend class="titulo_etiqueta">Seleccionar el Tipo de Pemiso a Generar</legend>	
        <br>
		<form name="frm_selPermiso"  id="frm_selPermiso" method="get" >
			<table cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">Tipo Permiso</div></td>
                	<td>
						<select name="cmb_tipoPermiso" id="cmb_tipoPermiso" class="combo_box"  onchange="javascript:document.frm_selPermiso.submit();" >
							<option selected="selected" value="">PERMISOS</option>
							<option value="TRABAJOS PELIGROSOS">TRABAJOS PELIGROSOS</option>
							<option value="TRABAJOS FLAMA ABIERTA">TRABAJO FLAMA ABIERTA</option>
							<option value="TRABAJOS ALTURAS">TRABAJO ALTURAS</option>
						</select>		
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<div align="center">       	    	
							<input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
							title="Regresar al Men&uacute; de Permiso"
							onmouseover="window.status='';return true" onclick="location.href='menu_permisos.php';"/>					
						</div>				
					</td>
				</tr>
			</table>
		</form>
</fieldset>

<?php

if(isset($_GET['cmb_tipoPermiso'])){
	if($_GET["cmb_tipoPermiso"]=="TRABAJOS PELIGROSOS"){
		echo "<meta http-equiv='refresh' content='0;url=frm_permisoPeligroso.php?id_tipoPer=TRABAJOS PELIGROSOS'>";			
	
	}
	if($_GET["cmb_tipoPermiso"]=="TRABAJOS FLAMA ABIERTA"){
		echo "<meta http-equiv='refresh' content='0;url=frm_permisoFlama.php?id_tipoPer=TRABAJOS FLAMA ABIERTA'>";
				
	}
	if ($_GET["cmb_tipoPermiso"]=="TRABAJOS ALTURAS"){
		echo "<meta http-equiv='refresh' content='0;url=frm_permisoAlturas.php?id_tipoPer=TRABAJOS ALTURAS'>";
	
	}
}
?>

</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
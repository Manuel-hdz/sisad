<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
	/*
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
	*/
		include("op_modificarPermisos.php");
		//Modulo de conexion con la base de datos
		include("../../includes/conexion.inc");	
		//Manejo de operaciones que consultan datos en la BD y los regresan en el elemento de formulario undicado en los parametros de las funciones
		include("../../includes/op_operacionesBD.php");
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionCPanel.js"></script>
	<?php /*cargarCombo que se usa para Panel de Control solamente*/?>
	<script type="text/javascript" src="includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/consultarPass.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		<!--
		#titulo-permisos {position:absolute;left:30px;top:35px;width:298px;height:20px;z-index:11;}
		#tablaconsultarUsuario {position:absolute;left:30px;top:80px;width:400px;height:140px;z-index:12;}
		#tabla-resultados {position:absolute;left:30px;top:260px; width:90%; height:340px;z-index:14;}
		#botones {position:absolute;left:30px;top:620px; width:90%; height:20px;z-index:15;}
		-->
	</style>
	</head>
	<body>
	<div id="barraCP"><img src="../../images/title-bar-bg.gif" width="100%" height="30"/></div>
	<div class="titulo_barra" id="titulo-permisos">Consultar Datos Usuario</div>
	
	<fieldset class="borde_seccion" id="tablaconsultarUsuario" name="tabla-consultarUsuario">
		<legend class="titulo_etiqueta">Autentif&iacute;quese como Administrador del Panel de Control</legend>
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td colspan="2" align="justify">
					Es necesario Auntentificarse como Usuario Administrador del Panel de Control
				</td>
			</tr>
			<tr>
				<td width="35%"><div align="right">Contrase&ntilde;a</div></td>
				<td width="65%">
				<input type="password" size="20" id="txt_passPC" name="txt_passPC"/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div align="center">
						<input name="btn_continuar" type="button" class="botones" value="Continuar" title="Verificar datos del Usuario" 
						onmouseover="window.status='';return true" onclick="verificarPasswordCP(txt_passPC);"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Inicio" onclick="location.href='main.php';"/>
					</div>
				</td>
			</tr>
		</table>
	</fieldset>
	
	<div id="tabla-resultados" class="borde_seccion2" style="visibility:hidden" align="center"></div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
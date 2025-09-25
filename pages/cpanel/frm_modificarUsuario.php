<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include("op_modificarUsuario.php");
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionCPanel.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<style type="text/css">
		<!--
		#titulo-modificar{position:absolute;left:30px;top:35px;width:298px;height:20px;z-index:11;}
		#tabla-modificarUsuario {position:absolute;left:30px;top:80px; width:90%; height:490px;z-index:12;overflow:scroll;}
		#botones {position:absolute;left:30px;top:620px; width:90%; height:20px;z-index:13;}
		#tabla-datos{position:absolute;left:30px;top:80px;width:742px;height:245px;z-index:14;}
		#res-spider {position:absolute;z-index:15;}
		-->
	</style>
	</head>
	<body>

		<div id="barraCP"><img src="../../images/title-bar-bg.gif" width="100%" height="30"/></div>
		<div class="titulo_barra" id="titulo-modificar">Modificar Usuarios</div>
		
	<?php
	
	if (isset($_POST["sbt_continuar"])){
		mostrarUsuario($_POST["rdb_usuario"]);
	}
	else{
		if(isset($_POST["sbt_modificar"])){
			modificarCredencial($_POST["hdn_usuario"],$_POST["txt_nombre"]);
			modificarUsuario($_POST["hdn_usuario"],$_POST["txt_pass"]);
		}
	?>
		<form name="frm_modificarUsuario" method="post" action="frm_modificarUsuario.php" onsubmit="return valFormModificarUsuarios(this);">
		<div class="borde_seccion2" id="tabla-modificarUsuario" name="tabla-modificarUsuario">
			<?php mostrarUsuarios();?>
		</div>
		
		<div id="botones" align="center">
			<input type="submit" name="sbt_continuar" id="sbt_continuar" title="Modificar la Contrase&ntilde;a del Usuario Seleccionado" value="Continuar" class="botones" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Inicio" 
			onMouseOver="window.status='';return true" onclick="location.href='main.php';" />
		</div>
		</form>
	<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
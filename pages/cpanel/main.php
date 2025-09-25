<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Panel de Control
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Principal</title>
<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
</head>

<body>
	<div id="parrilla-menu1">
		<div align="center">
		<br><br><br><br><br>
		<p><img src="images/logo-inicio.png" width="440" height="320" /><br/><br/>
		<img src="../../images/bienvenido.png" width="449" height="54" /></p>
		</div>
	</div>
</body>
<?php }?>
</html>
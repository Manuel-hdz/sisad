<?php
//Las lineas comentadas evaluan el Explorador WEB
//if (!strstr($_SERVER["HTTP_USER_AGENT"], "MSIE"))
//	echo "<script>alert('Se ha detectado un explorador Web incompatible con el sistema, abrirlo con Internet Explorer 8');</script>";
//else{
	$fllo = session_name("fllo");
	session_start();
	if(!session_is_registered('ctrl')){
		session_register('ctrl');
?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<Sistema de Gestion Empresarial, Produccion y Operacion

		html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<script type="text/javascript" language="javascript">
				<!--
				function cerrar(){
					window.open('','_self','');
					window.close();
				}
				setTimeout("window.open('#', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no'); cerrar();",0000);
				-->
			</script>
		</head>
		</html>

<?php
	}
	else {
		//session_start();
		if(session_is_registered('ctrl'))
			session_destroy();
?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<Sistema de Gestion Empresarial, Produccion y Operacion

		html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<link rel="shortcut icon" href="images/SisadWin.ico">
		</head>
			<frameset rows="*">
					<?php //Si el sistema esta en Mtto, comentar estar linea y decomentar la siguiente ?>
    				<frame src="pages/login.php?usr_sts=accinc">
					<?php //Si el NO sistema esta en Mtto, decomentar estar linea y comentar la anterior ?>
				<!--<frame src="mantenimiento.php">-->
			</frameset>
		</html>
<?php
	}
//}
?>
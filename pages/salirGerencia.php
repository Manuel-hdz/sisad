<?php
	//Aqui se da de baja la variables de sesion que se registraron cuando el usuario inicio sesion	
	session_start();
	//Borrar Graficas Generadas a traves del modulo de Direccion General
	include_once("dir/op_borrarHistorial.php");
	borrarHistorial();
	if(session_is_registered('usr_reg')){		
		session_destroy();
	} else {
		header("Location: loginGerencia.php?usr_sts=unr");	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<!-- Aqui se redirecciona a la pagina de index.html al momento de salir -->
	<meta http-equiv="refresh" content="5;url=loginGerencia.php?usr_sts=accinc"> 
	<style type="text/css">
		<!--
		body { background-image: url(dir/images/bk2.jpg);}
		.style12 {font-family: Arial, Helvetica, sans-serif; color: #000000; }
		.style15 {font-family: MicrogrammaDMedExt; color: #33761B; font-size: 13px; font-weight: bold; }
		.cerrar-sesion {font-size: 18px; font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #FFFFFF; }
		#fondo-titulo {position:absolute; left:0px; top:0; width:1035px; height:54px; z-index:1 }
		#titulo {position:absolute; left:180px; top:19px; width:658px; height:25px; z-index:2 }
		#fondo-login {position:absolute; left:290px; top:225px; width:378px; height:225px; z-index:3 }
		#logo {position:absolute; left:395px; top:248px; width:203px; height:103px; z-index:4 }
		#cerrar-sesion {position:absolute; left:426px; top:360px; width:174px; height:31px; z-index:5; }		
		-->
	</style>
	<script language="JavaScript" type="text/JavaScript">
		<!--							
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
</head>
<body>
	<div id="fondo-titulo"><img src="../images/dock-bg2.gif" width="1035" height="51" /></div>
	<div id="titulo" align="center"><span class="style15">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</span></div>
	<div id="cerrar-sesion" align="center">  
  		<p><span class="cerrar-sesion">Cerrando Sesi&oacute;n...</span></p>
  		<p><img src="../images/cargando2.gif" width="169" height="28" /></p>
</div>   
	<div id="fondo-login"><img src="dir/images/login.png" width="451" height="211" /></div>
    <div id="logo"><img src="../images/logo.png" width="230" height="100" /></div>
</body>
</html>
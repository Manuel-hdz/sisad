<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
	include ("head_menu.php");
	
	//Verificamos que el arreglo de documentos no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["documentos"])){
		unset($_SESSION["documentos"]);
	}
	//Verificamos que el arreglo de documentoTemporal no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["docTemporal"])){
		unset($_SESSION["docTemporal"]);
	}
	//Verificamos que el arreglo de actividades no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["actividades"])){
		unset($_SESSION["actividades"]);
	}
	//Verificamos que el arreglo de mecanicos no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["mecanicos"])){
		unset($_SESSION["mecanicos"]);
	}
	//Verificamos que el arreglo de materialesMtto no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["materialesMtto"])){
		unset($_SESSION["materialesMtto"]);
	}
	//Verificamos que el arreglo de bitacoraPrev no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["bitacoraPrev"])){
		unset($_SESSION["bitacoraPrev"]);
	}
	//Verificamos que el arreglo de bitacoraCorr no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["bitacoraCorr"])){
		unset($_SESSION["bitacoraCorr"]);
	}
	//Verificamos que el arreglo de fotos no este declarado, en caso de ser asi, vaciarlo
	if (isset($_SESSION["fotos"])){
		unset($_SESSION["fotos"]);
	}		
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<meta http-equiv="refresh" content="2;url=inicio_mantenimiento.php">
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	
	<style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:30px; top:160px; width:940px; height:400px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
	<div align="center">
    	<p><img src="../../images/ok.png" width="376" height="369" /></p>
  	</div>
</div>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
	include ("head_menu.php");
	//Sesiones necesarias en el registro de los recorridos de seguridad
	if(isset($_SESSION['registroFotografico'])){
		unset($_SESSION['registroFotografico']);
	}
	//Damos de baja las sesiones que implican el registro de la acta de incidentes accidentes
	if(isset($_SESSION['actaIncAcc'])){
		unset($_SESSION['actaIncAcc']);
	}
	if(isset($_SESSION['accionesPrevCorr'])){
		unset($_SESSION['accionesPrevCorr']);
	}
	//Damos de baja las sesiones que implican el registro de la acta de seguridad e Higiene
	if(isset($_SESSION['accidentes'])){
		unset($_SESSION['accidentes']);
	}
	if(isset($_SESSION['visitas'])){
		unset($_SESSION['visitas']);
	}
	if(isset($_SESSION['asistentes'])){
		unset($_SESSION['asistentes']);
	}
	if(isset($_SESSION['agenda'])){
		unset($_SESSION['agenda']);
	}
	if(isset($_SESSION['recorridos'])){
		unset($_SESSION['recorridos']);
	}
	//Liberar arreglos de session utilizados en las requisiciones
	if(isset($_SESSION['datosRequisicion']))
		unset($_SESSION['datosRequisicion']);
	if(isset($_SESSION['comentario']))
		unset($_SESSION['comentario']);
	//Fotografias en las requisiciones
	if(isset($_SESSION["fotosReq"]))
		unset($_SESSION['fotosReq']);
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<meta http-equiv="refresh" content="2;url=inicio_seguridad.php">
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
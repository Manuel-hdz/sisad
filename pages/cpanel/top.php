<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Top</title>

<?php
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	//Modulo de conexion con la base de datos, el cual estara diponible para todas la paginas a través de este archivo (head_menu.php) 
	include("../../includes/conexion.inc");	
	//Manejo de operaciones que consultan datos en la BD y los regresan en el elemento de formulario undicado en los parametros de las funciones
	include("../../includes/op_operacionesBD.php");
?>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>

<style type="text/css">
	<!--		
	.titulo-pagina {font-family: MicrogrammaDMedExt; color: #33761B; font-size: 13px; font-weight: bold; }
	.usr-reg { color: #FFFFFF; font-weight: bold; font-family: MicrogrammaDMedExt; font-size: 12px; }
	#modulo {position:absolute; left:6px; top:56px; width:169px; height:17px; z-index:8; }
	#titulo {position:absolute; left:198px; top:19px; width:488px; height:22px; z-index:3;}
	#fecha {position:absolute; left:721px; top:35px; width:276px; height:18px; z-index:9; }
	.fecha {font-family: MicrogrammaDMedExt; font-size: 12px; color: #000000; }
	#dock-new { position:absolute; width:275px; height:50px; z-index:124; left: 0; top: 0; }
	-->
</style>

</head>

<body>

<div id="fondo-title" style="position:absolute; left:0px; top:0px; width:656px; height:52px; z-index:1">
	<img src="../../images/dock/dock-bg2.gif" width="1035" height="50"  />
</div>
<div id="logo" style="position:absolute; left:10px; top:0px; width:154px; height:54px; z-index:4">
	<a href="http://www.concretolanzadodefresnillo.com" target="_blank" title="Ir a la Página Web de Concreto Lanzado de Fresnillo S.A. de C.V."><img src="../../images/logo.png" width="151" height="56" border="0" /></a></div>

<div id="titulo" align="center"><span class="titulo-pagina">PANEL DE CONTROL<br/>
Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</span></div>
	<div id="log-off" style="position:absolute; left:1001px; top:24px; width:33px; height:34px; z-index:5;">	
	<form action="../salir.php" target="_parent">
		<input type="image" src="../../images/close.png" width="31" height="31" border="0" title="Cerrar Sesi&oacute;n" onMouseOver="window.estatus='';return true" />
	</form>	
	</div>
	<div id="fecha" class="fecha" align="right"><?php echo verFecha(1);?></div>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadGerencia.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	//if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
	//	echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	//}
	//else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include("head_menu.php");
		include("op_inicio.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />
	<script type="text/javascript" src="includes/ajax/mostrarAvances.js" ></script>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:286px; height:25px; z-index:11; }
		#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
		#tabla-resultadosGT{position:absolute; left:30px; top:190px; width:430px; height:450px; z-index:12; padding:15px; padding-top:0px; overflow:scroll;}
		#tabla-resultadosDes{position:absolute; left:520px; top:190px; width:430px; height:450px; z-index:12; padding:15px; padding-top:0px; overflow:scroll;}
		#cuerpoCalis{position:absolute; left:1px; top:1px; width:998px; height:668px; z-index:10; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Avances en Desarrollo y Zarpeo</div>
	
	<?php
		borrarGraficos();
		mostrarAvances();
	?>	
</body><?php
// }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
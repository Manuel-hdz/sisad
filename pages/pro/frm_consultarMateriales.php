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
		//Archivo con la operacion 
		include ("op_consultasExternas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
			$("#tabla-resultadosMateriales").dataTable({
				"sPaginationType": "scrolling"
			});
	});
	</script>
	
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-consultar { position:absolute; left:30px; top:146px; width:283px; height:20px; z-index:11; }
		#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
		#tabla-consultarMateriales{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; padding:15px; padding-top:0px; overflow:scroll;}
		-->
    </style>
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Consultar Materiales de Almac&eacute;n</div>
	
	<div class="borde_seccion2" id="tabla-consultarMateriales" >
	<p class="titulo_etiqueta" align="left">Lista de Materiales</p><?php 
			//Mostrar la lista de Todos los Materiales
			mostrarMateriales();?>
	</div>
	
	<div id="botones" align="center">
		<input type="button" name="btn_regresar" title="Regresar al Inicio" onclick="location.href='inicio_produccion.php';" value="Regresar" class="botones"
         onmouseover="window.status='';return true;"/>
	</div>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
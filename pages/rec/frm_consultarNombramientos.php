<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include ("op_consultarNombramientos.php")?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
			$("#consultarNombramientos").dataTable({
				"sPaginationType": "scrolling"
			});
	});
	</script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-consultar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-consultarNombramientos { position:absolute; left:30px; top:190px; width:908px; height:403px; z-index:12; padding:15px; padding-top:0px;}
		#nombramientos {position:absolute;left:30px;top:190px;width:815px;height:400px;z-index:21;overflow: scroll}
		#btn_regresar {position:absolute;left:33px;top:650px;width:860px;height:40px;z-index:25;}
		-->
    </style>
	
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
    
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Consultar Nombramientos</div><?php
    
	if (isset($_GET['btn_consultar'])) {?>
     	<div id="nombramientos" class="borde_seccion2"><?php 
			mostrarNombramientos();?>
		</div>
        <div id="btn_regresar" align="center">
       	  <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Formulario Generar Nombramiento" 
             onmouseover="window.status='';return true" onclick="location.href='frm_generarNombramiento.php?'"/>
        </div>
     	<?php 
	}?>     
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
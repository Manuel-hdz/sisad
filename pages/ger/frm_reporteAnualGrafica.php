<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php

	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
	//Este archivo proporciona el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reporteAnual.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-reporte {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-mostrarGraficaAnio {position:absolute;left:30px;top:179px;width:888px;height:451px;z-index:14; overflow:scroll}
		#btns{position:absolute;left:42px;top:680px;width:900px;height:38px;z-index:14;}
		-->
    </style>
</head>
<body>		

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-reporte">Gr&aacute;fica Anual</div><?php
	
	if(isset($_GET['btn_verGrafica']) && isset($_GET['anio']) && isset($_GET['nomGrafica'])){
		$anio= $_GET['anio'];
		$grafica= $_GET['nomGrafica'];?>
		<form action="" method="post">
			<div id="tabla-mostrarGraficaAnio" class="borde_seccion2">
				 
				 <img src="<?php echo $grafica; ?>" width="100%" height="100%" border="0" 
                onclick="window.open('verGraficaAnual.php?nombre=<?php echo $grafica; ?>', '_blank','top=50, left=50, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=no, toolbar=no, location=no, directories=no')" title="Clic para Ampliar la Im&aacute;gen"/>			</div>
			<div id="btns" align="center">
				<input name="btn_regresar" type="button" class="botones" value="Regresar" 
				onclick="location.href='frm_reporteAnual.php?cmb_anio=<?php echo $_GET['anio']; ?>';"
				title="Regresar a la Pantalla Anterior"/>
				
		  </div>
		</form><?php			
	}//FIN if(isset($_GET['btn_verGrafica']))?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
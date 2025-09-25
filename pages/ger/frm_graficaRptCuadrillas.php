<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{	
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");		
		include ("op_reporteCuadrillas.php");?>
	
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#titulo-reporteCuadrillas {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#ver-grafica { position:absolute; left:30px; top:190px; width:940; height:450px; z-index:12; }		
		#btn-regresar { position:absolute; left:371px; top:671px; width:200px; height:50px; z-index:13; }
		-->
    </style>
</head>
<body>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-reporteCuadrillas">Grafica del Reporte de Producci&oacute;n Mensual </div>
	
    <div id="ver-grafica" align="center"><?php
		//Cosntantes para indicar el Nombre del Host y el Nombre del Sistema
		define("HOST", $_SERVER['HTTP_HOST']);
		define("SISAD","Sisad-v0.01-alfa");
		
		//Dibujar la grafica del Reporte Mensual de Produccion con la información proporcionada de cada Ubicacion
		foreach($_SESSION['ubicacionesGrafica'] as $ubicacion => $datosUbicacion){
			//Recuperar los datos necesarios para generar la Grafica
			$msgGrafica = $datosUbicacion['msgGraficaRptCuadrillas'];
			$presupuesto = $datosUbicacion['presupuesto'];
			$avance = $datosUbicacion['avanceReal'];
			
			$grafica = dibujarGrafica($msgGrafica,$presupuesto,$avance); ?>
			<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/ger/<?php echo $grafica;?>" width="940" height="450" />
			<br /><br /><br /><?php
		}//Cierre foreach($_SESSION['ubicacionesGrafica'] as $ubicacion => $datosUbicacion) ?>			
		<br /><br /><br />
		<form name="frm_regresar" method="post" action="frm_reporteCuadrillas.php">
			<input type="hidden" name="cmb_periodo" value="<?php echo $_SESSION['periodoSeleccionado']; ?>" />
			<input type="submit" name="sbt_consultar" class="botones" value="Regresar" title="Regresar a la P&aacute;gina Anterior" onmouseover="window.status='';return true" />
		</form>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
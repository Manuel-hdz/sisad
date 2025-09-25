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
//		Este archivo proporciona el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reporteAnual.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-reporte {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-seleccionarAnio {position:absolute;left:30px;top:190px;width:306px;height:120px;z-index:14;}
		#tabla-mostrarReporteAnio {position:absolute;left:30px;top:190px;width:888px;height:438px;z-index:14; overflow:scroll}
		#btns{position:absolute;left:30px;top:681px;width:900px;height:38px;z-index:14;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-reporte">Reporte Anual</div><?php
		
	if(isset($_POST['sbt_consultarAnio']) || isset($_GET['cmb_anio'])){
		if (isset($_POST['sbt_consultarAnio']))
			$anio=$_POST['cmb_anios'];
		else if (isset($_GET['cmb_anio']))
			$anio=$_GET['cmb_anio'];?>
		
		<form action="guardar_reporte.php" method="post">
			<div id="tabla-mostrarReporteAnio" class="borde_seccion2"><?php
				 $arreglo_Inf=mostrarReporteAnual($anio);?>
			</div>
			<div id="btns" align="center">
			   <input name="btn_verGrafica" type="button" class="botones" value="Ver Gráfica" 
				title="Ver Gráfica" onmouseover="window.estatus='';return true" 
				onclick="location.href='frm_reporteAnualGrafica.php?btn_verGrafica&anio=<?php echo $anio;?>&nomGrafica=<?php echo $arreglo_Inf[2];?>'"/>
				&nbsp;&nbsp;&nbsp;
				<input name="hdn_tipoReporte" type="hidden" value="reporteAnual"/>
				<input name="hdn_msg" type="hidden" value="<?php echo $arreglo_Inf[0];?>"/> 
				<input name="hdn_anio" type="hidden" value="<?php echo $arreglo_Inf[1];?>"/>
				<input name="hdn_nomGrafica" type="hidden" value="<?php echo $arreglo_Inf[2];?>"/>
				<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
				title="Exportar los Datos de la Consulta Realizada" onmouseover="window.estatus='';return true"/>
				&nbsp;&nbsp;&nbsp;
				<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='frm_reporteAnual.php';"
				title="Regresar a la Pantalla Anterior"/>
		  </div>
		</form><?php
	}//FIN 	if(isset($_POST['sbt_consultarAnio'])	
	
	else if(!isset($_POST['sbt_consultarAnio'])){
		?>
		<fieldset class="borde_seccion" id="tabla-seleccionarAnio" name="tabla-seleccionarAnio">
		<legend class="titulo_etiqueta">Seleccionar A&ntilde;o de Trabajo</legend>	
		<br>
		<form onSubmit="return valFormSelAnioTrab(this);" name="frm_reporteAnual" method="post" action="frm_reporteAnual.php">
		<table width="304" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="51"><div align="right">A&ntilde;o</div></td>
				<td width="189"><?php cargarAniosDisponibles(); ?></td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<input name="sbt_consultarAnio" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
					title="Consultar"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_reportes.php';"
					title="Regresar al men&uacute; de Reportes"/>
				</td>
			</tr>
		</table>
		</form>
</fieldset><?php
	}//FIN if(!isset($_POST['sbt_consultarAnio'])?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Este archivo contiene las funciones para Generar el Reporte de Ventas de Acuerdo a los Parametros Seleccionados
		include ("op_reporteTiemposEntrega.php");	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:362px; height:24px; z-index:11; }
		#rpt-fecha { position:absolute; left:30px; top:186px; width:307px; height:161px; z-index:14; }
		#calendar-tres { position:absolute; left:284px; top:228px; width:30px; height:26px; z-index:18; }
		#calendar-cuatro { position:absolute; left:283px; top:265px; width:30px; height:26px; z-index:19; }
		#reporte{ position:absolute;  left:30px; top:186px; width:921px; height:350px; z-index:21; overflow: scroll }
		#boton-cancelar { position:absolute; left:459px; top:600px; width:119px; height:34px; z-index:22; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Consultar Compras - Tiempos Entrega </div>
	
	
	<?php if(!isset($_POST['sbt_generarReporte'])){?>
	<fieldset class="borde_seccion" id="rpt-fecha" name="rpt-fecha">
	<legend class="titulo_etiqueta">Reporte por Fecha</legend>	
	<br/>	
	<form name="frm_rptFecha" action="frm_reporteTiemposEntrega.php" method="post">
		<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Fecha Inicio</div></td>
				<td width="140">
					<input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15"
             		readonly=true width="90"/>
				</td>
        	</tr>
        	<tr>
          		<td><div align="right">Fecha Fin </div></td>
          		<td><input name="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
			</tr>
				<td>
					<div align="center">
			    		<input name="sbt_generarReporte" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
             			title="Generar Reporte Por Fecha" />
			    	</div>
				</td>
			    <td width="140">
					<div align="center">
			    		<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a Seleccionar Otra Consulta"
						onclick="location.href='frm_seleccionarConsulta.php'" />
			      	</div>
				</td>
        	</tr>
		</table>
	</form>
	</fieldset>

	<div id="calendar-tres">
		<input name="iniRptFecha" id="iniRptFecha" type="image" src="../../images/calendar.png" 
        onclick="displayCalendar(document.frm_rptFecha.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />
	</div>
    <div id="calendar-cuatro">
		<input name="finRptFecha" id="finRptFecha" type="image" src="../../images/calendar.png" 
        onclick="displayCalendar(document.frm_rptFecha.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />	
	</div>
	<?php }
	if(isset($_POST['sbt_generarReporte'])){
		?><div id="reporte" align="center" class="borde_seccion2"><?php
			generarReporte();
		?></div>
		<div id="boton-cancelar">
			<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a Seleccionar Otra Consulta"
			onclick="location.href='frm_reporteTiemposEntrega.php'" />
		</div><?php 
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
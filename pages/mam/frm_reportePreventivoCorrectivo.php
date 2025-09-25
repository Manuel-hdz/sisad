<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">


<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reportePreventivoCorrectivo.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:362px; height:24px; z-index:11; }
		#rpt-fechaPrevCorrec { position:absolute; left:30px; top:190px; width:430px; height:187px; z-index:12; }
		#calendar-uno { position:absolute; left:343px; top:216px; width:30px; height:26px; z-index:13; }
		#calendar-dos { position:absolute; left:342px; top:254px; width:30px; height:26px; z-index:14; }
		#resultados { position:absolute; left:30px; top:190px; width:921px; height:430px; z-index:15; overflow:scroll; }
		#tabla-preventiva { position:absolute; left:113px; top:58px; width:300px; height:200px; z-index:15;}
		#tabla-correctiva { position:absolute; left:505px; top:58px; width:300px; height:200px; z-index:17;}
		#btns-rpt { position: absolute; left:50px; top:669px; width:928px; height:40px; z-index:16; }								  
		-->
    </style>	
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte  Mantenimientos Preventivos/Correctivos </div>
<?php 
	$band = 0;
	if(isset($_POST['txt_fechaIni'])){?>				
		<div id="resultados" align="center" class="borde_seccion2"><?php
			//Quitar los datos de la grafica de la SESSION, antes de entrar a generar el nuevo reporte, en el caso de que exista uno previo
			unset($_SESSION['datosGrafica']);
			$band = 1;		
			generarReporte();
			
		//</div> El cierre de esta capa se hace dentro de la fucnion generarReporte antes de desplegar la capa que contiene los botones de regresar y generar grafica?>		
<?php		
	}		
		    
	if($band==0){ 
?>
	<fieldset class="borde_seccion" id="rpt-fechaPrevCorrec" name="rpt-fechaPrevCorrec">	
		<legend class="titulo_etiqueta">Reporte por Fechas</legend>
		<form onsubmit="return verFormReportePreventivoCorrectivo(this);" name="frm_rptFecha" action="frm_reportePreventivoCorrectivo.php" method="post" >
		<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Fecha Inicio</div></td>
				<td><input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15" readonly=true width="90" /></td>
			</tr>
			<tr>
				<td><div align="right">Fecha Fin </div></td>
		        <td><input name="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_generar" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true" title="Generar Reporte por Fecha"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Reportes" onclick="location.href='menu_reportes.php'"/>
				</td>
			</tr>
		</table>
		</form>
	</fieldset>


<div id="calendar-uno">
	<input name="iniRptFecha" id="iniRptFecha" type="image" src="../../images/calendar.png" onclick="displayCalendar(document.frm_rptFecha.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />
</div>

<div id="calendar-dos">
	<input name="finRptFecha" id="finRptFecha" type="image" src="../../images/calendar.png" onclick="displayCalendar(document.frm_rptFecha.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />	
</div>

<?php
	 }//Cierre if($band==0) ?>	   	
	<div id="btns-rpt"></div> 
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
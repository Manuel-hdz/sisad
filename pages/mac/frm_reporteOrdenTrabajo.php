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
		include ("op_reporteOrdenTrabajo.php");
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
		#titulo-barra { position:absolute; left:30px; top:146px; width:200px; height:24px; z-index:11; }
		#rpt-ordenTrabajo { position:absolute; left:30px; top:190px; width:430px; height:213px; z-index:12; }
		#rpt-servicio {  position:absolute; left:533px; top:188px; width:434px; height:215px; z-index:16; }		
		#calendar-uno { position:absolute; left:344px; top:266px; width:30px; height:26px; z-index:13; }
		#calendar-dos { position:absolute; left:344px; top:301px; width:30px; height:26px; z-index:14; }
		#calendar-tres { position:absolute; left:819px; top:266px; width:30px; height:26px; z-index:17; }
		#calendar-cuatro { position:absolute; left:820px; top:299px; width:30px; height:26px; z-index:18; }	
		#resultados {position:absolute; left:30px; top:190px; width:921px; height:430px; z-index:20; overflow:scroll;  }
		#btns-rpt { position: absolute; left:48px; top:670px; width:928px; height:40px; z-index:19; }
		#boton-cancelar { position:absolute; left:456px; top:443px; width:119px; height:34px; z-index:23; }								  
		-->
    </style>	
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Orden de Trabajo </div><?php 
	
	if(isset($_POST['hdn_tipoRpt'])){
		switch($_POST['hdn_tipoRpt']){
			case 1:
				$_POST['txt_fechaIni'] = $_SESSION['datosRptOT']['txt_fechaIni'];
				$_POST['txt_fechaFin'] = $_SESSION['datosRptOT']['txt_fechaFin'];
				unset($_SESSION['datosRptOT']);//Quitar los datos de la SESSION
			break;
			case 2:
				$_POST['cmb_servicio'] = $_SESSION['datosRptOT']['cmb_servicio'];
				$_POST['txt_fechaIni'] = $_SESSION['datosRptOT']['txt_fechaIni'];
				$_POST['txt_fechaFin'] = $_SESSION['datosRptOT']['txt_fechaFin'];
				unset($_SESSION['datosRptOT']);//Quitar los datos de la SESSION
			break;
		}
	}
	$band=0;
	unset($_SESSION['datosRptOT']);
		
	if(isset($_POST['txt_fechaIni']) && isset($_POST['txt_fechaFin']) && !isset($_POST['cmb_servicio'])){
		$band = 1;		
		generarReporte(1);		
	}	
	if(isset($_POST['cmb_servicio'])){
		$band = 1;		
		generarReporte(2);		
	}	
		
	if($band==0){?>
		<fieldset class="borde_seccion" id="rpt-ordenTrabajo" >	
		<legend class="titulo_etiqueta">Reporte por Fechas</legend>
		<br />
		<form onsubmit="return verFormReportesOrdenTrabajo(this,1);" name="frm_rptFecha" action="frm_reporteOrdenTrabajo.php" method="post" >
		<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  	<td>&nbsp;</td>
			  	<td>&nbsp;</td>
			</tr>
			<tr>
			  	<td width="106"><div align="right">Fecha Inicio</div></td>
			  	<td width="146">
			  		<input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15"
					readonly=true width="90" />
				</td>
			</tr>
			<tr>
				<td><div align="right">Fecha Fin </div></td>
				<td><input name="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_generar" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
					title="Generar Reporte por Fecha" />
					&nbsp;&nbsp;&nbsp;
				  	<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />				  
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
	
		<div id="calendar-uno">
			<input name="iniRptFecha" id="iniRptFecha" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_rptFecha.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />
		</div>
		<div id="calendar-dos">
			<input name="finRptFecha" id="finRptFecha" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_rptFecha.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />	
		</div>
	
		<fieldset class="borde_seccion" id="rpt-servicio">
		<legend class="titulo_etiqueta">Reporte por Servicio</legend>
		<br />
		<form onsubmit="return verFormReportesOrdenTrabajo(this,2);" name="frm_rptServicio" action="frm_reporteOrdenTrabajo.php" method="post">
		<table width="395" border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  	<td width="124"><div align="right">Tipo de Servicio </div></td>
				<td colspan="2">
					<select name="cmb_servicio" class="combo_box" id="cmb_servicio">
				  		<option value="">Tipo de Servicio</option>
				  		<option value="INTERNO">INTERNO</option>
				  		<option value="EXTERNO">EXTERNO</option>
					</select>
				</td>
			</tr>
			<tr>
			  	<td><div align="right">Fecha Inicio</div></td>
			  	<td>
					<input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15"
					readonly=true width="90" />
				</td>
			</tr>
			<tr>
				<td><div align="right">Fecha Fin </div></td>
				<td><input name="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
			</tr>
			<tr>
				<td colspan="3" align="center">
					<input name="btn_generarReporte" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
				 	title="Generar Reporte Por Tipo de Servicio" />
					&nbsp; &nbsp; &nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
				</td>			          
			</tr>
		</table>
		</form>
		</fieldset>
	
		<div id="calendar-tres">
			<input name="iniRptServicio" id="iniRptServicio" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_rptServicio.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />
		</div>
		<div id="calendar-cuatro">
			<input name="finRptServicio" id="finRptServicio" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_rptServicio.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />		
		</div>
	
		<div id="boton-cancelar">
			<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Reportes" onclick="location.href='menu_reportes.php'" />
		</div><?php
	 }//Cierre if($band==0) ?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
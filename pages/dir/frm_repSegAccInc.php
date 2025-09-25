<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	//if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
	//	echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	//}
	//else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
    <link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/reportesSeguridad.js"></script>

<style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#form-selecPeriodo {position:absolute;left:30px;top:190px;width:300px;height:150px;z-index:12;}
		#calendario_repInicio { position:absolute; left:265px; top:230px; width:29px; height:24px; z-index:13; }
		#calendario_repCierre { position:absolute; left:265px; top:270px; width:30px; height:26px; z-index:14; }
		#resultados{position:absolute;left:380px;top:191px;width:628px; height:399px;;z-index:15;overflow:hidden;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Accidentes e Incidentes </div>
	
	<fieldset class="borde_seccion" id="form-selecPeriodo" name="form-selecPeriodo">
	<legend class="titulo_etiqueta" style="color:#FFFFFF">Seleccionar Fechas</legend>	
	<br>	
	<form name="frm_reporteRecSeg">
	<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="40%" style="color:#FFFFFF"><div align="right">Fecha de Inicio</div></td>
		  	<td width="60%">
				<input name="txt_fechaInicio" id="txt_fechaInicio" type="text" value=<?php echo date("d/m/Y",strtotime("-7 day")); ?> size="10" maxlength="15" readonly="true" width="50"/>
			</td>
		</tr>
		<tr>
			<td style="color:#FFFFFF"><div align="right">Fecha de Cierre</div></td>
		  	<td>
				<input name="txt_fechaCierre" id="txt_fechaCierre" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly="true" width="50"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input name="btn_reporte" type="button" class="botones" value="Ver Reporte" onMouseOver="window.status='';return true" title="Ver Reporte de Accidentes e Incidentes" onclick="mostrarReporteAccInc(3,txt_fechaInicio.value,txt_fechaCierre.value);"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Reportes de Seguridad" onClick="borrarHistorial();location.href='submenu_seguridad.php'" />
			</td>
		</tr>
	</table>    
	</form>    			 	
	</fieldset>
		
	<div id="calendario_repInicio">
		<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_reporteRecSeg.txt_fechaInicio,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>
		
	<div id="calendario_repCierre">
		<input name="calendario_cieRep" type="image" id="calendario_cieRep" onclick="displayCalendar(document.frm_reporteRecSeg.txt_fechaCierre,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>
	
	<div id="resultados"></div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
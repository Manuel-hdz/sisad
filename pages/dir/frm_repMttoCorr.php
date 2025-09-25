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
	<script type="text/javascript" src="includes/ajax/reportesMan.js"></script>	

<style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:486px;height:20px;z-index:11;}
		#form-selecPeriodo {position:absolute;left:30px;top:190px;width:300px;height:175px;z-index:14;}
		#resultadoGrafico{position:absolute;left:380px;top:191px;width:628px; height:399px;;z-index:14;}
		#calendario_repInicio { position:absolute; left:265px; top:230px; width:29px; height:24px; z-index:14; }
		#calendario_repCierre { position:absolute; left:265px; top:270px; width:30px; height:26px; z-index:15; }
		#consultarDetalle{position:absolute; left:223px; top:461px; width:130px; height:110px; z-index:16;visibility:hidden;}
		-->
    </style>
</head>
<body>

	<?php 
	//Se envia en el GET el parametro "tipo" para poder emplear la misma funcionalidad para mostrar Reportes
	$tipo=$_GET["tipo"];
	?>
	
	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Servicios Mantenimiento Correctivo</div>
	
	<fieldset class="borde_seccion" id="form-selecPeriodo" name="form-selecPeriodo">
	<legend class="titulo_etiqueta" style="color:#FFFFFF">Seleccionar Fechas</legend>	
	<br>	
	<form name="frm_reporteMttoCorr">
	<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="40%" style="color:#FFFFFF"><div align="right">Fecha de Inicio</div></td>
		  	<td width="60%">
				<input name="txt_fechaInicio" type="text" value=<?php echo date("d/m/Y",strtotime("-7 day")); ?> size="10" maxlength="15" readonly=true width="50">	  
			</td>
		</tr>
		<tr>
			<td style="color:#FFFFFF"><div align="right">Fecha de Cierre</div></td>
		  	<td>
				<input name="txt_fechaCierre" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="50">
			</td>
		</tr>
		<tr>
			<td style="color:#FFFFFF"><div align="right">Agrupar Por</div></td>
		  	<td>
				<select name="cmb_orden" id="cmb_orden" class="combo_box">
					<option value="fecha_mtto">Fecha</option>
					<option value="equipos_id_equipo">Equipo</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="hidden" name="hdn_combo" id="hdn_combo" />
				<input name="btn_reporte" type="button" class="botones" value="Ver Reporte" onMouseOver="window.status='';return true" title="Ver Mantenimientos Correctivos" onclick="mostrarReporteCorrectivo(2,txt_fechaInicio.value,txt_fechaCierre.value,cmb_orden.value,'<?php echo substr($tipo,0,1) ?>');"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Reportes de Mantenimiento" onClick="borrarHistorial();location.href='submenu_mtto.php?tipo=<?php echo $tipo;?>'"/>
			</td>
		</tr>
	</table>    
	</form>    			 	
	</fieldset>
		
	<div id="calendario_repInicio">
		<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_reporteMttoCorr.txt_fechaInicio,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>
		
	<div id="calendario_repCierre">
		<input name="calendario_cieRep" type="image" id="calendario_cieRep" onclick="displayCalendar(document.frm_reporteMttoCorr.txt_fechaCierre,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>

	<div id="resultadoGrafico"></div>
	<div id="imagenes" style="visibility:hidden;width:1px;height:1px;overflow:hidden"></div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
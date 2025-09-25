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
		//include ("op_selTipoConsultaLaboratorio.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-registrar {	position:absolute;	left:30px;	top:146px;	width:384px;	height:20px;	z-index:11;}
			#tabla-seleccionarReporte {position:absolute;left:29px;top:192px;width:338px;height:149px;z-index:12;padding:15px;padding-top:0px;}
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8; overflow:scroll}
		-->
    </style>
</head>
<body>
<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-registrar">Seleccionar Reporte de Laboratorio</div>
	<fieldset class="borde_seccion" id="tabla-seleccionarReporte" name="tabla-seleccionarReporte">
		<legend class="titulo_etiqueta">Seleccionar el Tipo de Reporte a Generar</legend>	
        <br>
		<form name="frm_selReporteLab"  id="frm_selReporteLab" method="get" >
			<table cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">Reportes Laboratorio</div></td>
                	<td>
						<select name="cmb_tipoReporteLab" id="cmb_tipoReporteLab" class="combo_box"  onchange="selTipoReporteLab();" >
							<option selected="selected" value="">TIPO REPORTE</option>
							<option value="AGREGADOS">REPORTE DE AGREGADOS</option>
							<option value="RENDIMIENTO">REPORTE DE RENDIMIENTO</option>
							<option value="RESISTENCIA">REPORTE DE RESISTENCIA</option>
						</select>		
					</td>
					
				</tr>
				<tr>
					<td colspan="4">
						<div align="center">       	    	
							<input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
							title="Regresar al Inicio de Gerencia"
							onmouseover="window.status='';return true" onclick="location.href='inicio_gerencia.php';"/>					
						</div>				
					</td>
				</tr>
			</table>
		</form>
</fieldset>

</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		include ("op_reporteCuadrillas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-reporteCuadrillas {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#form-selecPeriodo {position:absolute;left:30px;top:190px;width:373px;height:187px;z-index:14;}
		#ver-reporteMensual {position:absolute; left:30px; top:190px; width:940px; height:442px; z-index:15; overflow:scroll; }
		#ver-botones {position:absolute; left:270px; top:668px; width:480px; height:50px; z-index:16; }
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-reporteCuadrillas">Reporte de Producci&oacute;n por Cuadrillas </div><?php
	
	if(!isset($_POST['sbt_consultar'])){
		
		//Liberar los datos de la SESSION en caso de que Existan
		if(isset($_SESSION['ubicacionesGrafica'])){
			unset($_SESSION['periodoSeleccionado']);
			unset($_SESSION['ubicacionesGrafica']);
		}?>
					
					
		<fieldset class="borde_seccion" id="form-selecPeriodo" name="form-selecPeriodo">
		<legend class="titulo_etiqueta">Seleccionar Periodo</legend>	
		<br>	
		<form onSubmit="return valFormPeriodoRptMensual(this);" name="frm_periodoRptMensual" method="post" action="frm_reporteCuadrillas.php" >
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="40%" align="right">Periodo</td>
				<td width="60%"><?php 
					$res = cargarComboTotal("cmb_periodo","periodo","periodo","presupuesto","bd_gerencia","Periodo","","","fecha_inicio","",""); 
					if($res==0){?>
						<label class="msje_correcto">No Hay Periodos Registrados</label>
						<input type="hidden" name="cmb_periodo" value="" /><?php
					}?>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>	
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="sbt_consultar" value="Consultar" class="botones" onmouseover="window.status='';return true" title="Consultar el Periodo Seleccionado" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" onclick="location.href='menu_reportes.php'" title="Regresar al Men&uacute; de Reportes" />
				</td>
			</tr>		
		</table>
		</form>
		</fieldset><?php
	}//Cierre if(!isset($_POST['sbt_consultar']))
	else if(isset($_POST['sbt_consultar'])){?>
		<div id="ver-reporteMensual" class="borde_seccion2" align="center"><?php
			$sumaPorDiaZarpeo = verReporteMensual($_POST['cmb_periodo']);
			
			//El transporte ya fue integrado con el reporte de Zarpeo, desde la funcion de verReporteMensual se mostraran los datos de Zarpeo y Transporte
			//$sumaPorDiaTransporte = verReporteTransporte($_POST['cmb_periodo'],$sumaPorDiaZarpeo); ?>				
		</div>
		<div id="ver-botones" align="center">
			<table class="tabla_frm" cellpadding="5" cellspacing="5">
				<tr><?php
				if($sumaPorDiaZarpeo!=""){?>				
					<td>
						<form name="frm_exportarDatos" method="post" action="guardar_reporte.php">
							<input name="hdn_msg" type="hidden" value="Reporte de Cuadrillas en el Periodo <?php echo $_POST['cmb_periodo']; ?>"/>
							<input type="hidden" name="hdn_tipoReporte" value="reporteCuadrillas" />
							<input name="hdn_periodo" type="hidden" value="<?php echo $_POST['cmb_periodo']; ?>"/>
							<input type="hidden" name="hdn_nomReporte" value="ReporteMensual_<?php echo $_POST['cmb_periodo']; ?>"/>							
							
							<input name="sbt_exportar" id="sbt_exportar" type="submit" class="botones" value="Exportar Datos" title="Exportar Datos" 
							onmouseover="window.estatus='';return true" />
						</form>
					</td><?php				
				}
				if(isset($_SESSION['ubicacionesGrafica'])){?>				
					<td>
						<?php //La Grafica solo contempla los datos del Presupuesto y de la Produccion de la Primera Ubicación Desplegada?>
						<input type="button" name="btn_verGrafica" class="botones" value="Ver Grafica" title="Ver Grafica de la Producci&oacute;n del Mes" onclick="location.href='frm_graficaRptCuadrillas.php'" />
					</td><?php				
				}?>
				<td>
					<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar a la P&aacute;gina Anterior" onclick="location.href='frm_reporteCuadrillas.php'" />
				</td>
				</tr>
			</table>
</div><?php		
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que muestra los registros de Trabajo
		include ("op_reporteNomina.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="includes/ajax/cargarFechasTxt.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
			$("#tabla-resultadosNomina").dataTable({
				"sPaginationType": "scrolling"
			});
	});
	</script>

    <style type="text/css">
		<!--
			#titulo-consultar{position:absolute;left:30px;top:146px; width:256px;height:20px;z-index:11;}
			#tabla-consultarFechas{position:absolute;left:30px;top:190px;width:425px;height:145px;z-index:12;}
			#calendario-uno{position:absolute;left:230px;top:230px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:445px;top:230px;width:30px;height:26px;z-index:13;}
			#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:15; padding:15px; padding-top:0px; overflow:scroll;}
			#botones{position:absolute;left:30px;top:640px;width:945px;height:40px;z-index:16;}
		-->
    </style>
	
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>

</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Reporte N&oacute;mina Mantenimiento Mina</div>

	<?php
	if (!isset($_POST["sbt_consultar"])){
	?>
		<fieldset class="borde_seccion" id="tabla-consultarFechas" name="tabla-consultarFechas">
		<legend class="titulo_etiqueta">Seleccione Fechas de Trabajo</legend>	
		<br>
		<form name="frm_reporteNomina" method="post" action="frm_consultarNominaMam.php" onsubmit="return valSeleccionarNomina(this);">
			<table width="415"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="200"><div align="right">Fecha Inicio</div></td>
					<td width="276">
						<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" 
						value="<?php echo date("d/m/Y", strtotime("-7 day"));?>" readonly="readonly"
						onchange="cargarCmbNomina('cmb_nomina',this.value,txt_fechaFin.value,'bd_recursos','MANTENIMIENTO MINA','1')"/>
					</td>
				
					<td width="150"><div align="right">Fecha Fin</div></td>
					<td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15"
					value="<?php echo date("d/m/Y");?>" readonly="readonly"
					onchange="cargarCmbNomina('cmb_nomina',txt_fechaIni.value,this.value,'bd_recursos','MANTENIMIENTO MINA','1')"/></td>
				</tr>
				<tr>
					<td width="200"><div align="right">N&oacute;mina</div></td>
					<td colspan="4">
						<span id="datosNomina">
							<select name="cmb_nomina" id="cmb_nomina" size="1" class="combo_box">
								<option value="">N&oacute;mina</option>
							</select>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="5">
						<div align="center">
							<input name="sbt_consultar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Consultar N&oacute;mina Mantenimiento Mina"
							onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;
							<input name="btn_reset" type="reset" class="botones" id="btn_reset" value="Restablecer" title="Restablecer el Formulario"/>
							&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otro Reporte" 
							onmouseover="window.status='';return true" onclick="location.href='menu_reporte_nominaInterna.php'" />
						</div>
					</td>
				</tr>
				<input type="hidden" name="hdn_bd" id="hdn_bd" value="bd_recursos"/>
				<input type="hidden" name="hdn_area" id="hdn_area" value="MANTENIMIENTO MINA"/>
		  </table>
		</form>
		</fieldset>
		
		<div id="calendario-uno">
			<input name="calendario_uno" type="image" id="calendario_uno" onclick="displayCalendar (document.frm_reporteNomina.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
			title="Seleccione Fecha de Inicio" />
		</div>
		<div id="calendario-dos">
			<input name="calendario_dos" type="image" id="calendario_dos" onclick="displayCalendar (document.frm_reporteNomina.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
		</div>
	
	<?php
	}
	else{
	?>
		<div id="tabla-resultados" class="borde_seccion2">
			<?php
			$sql=mostrarNominaMamBD();
			?>
		</div>
		<div id="botones" align="center">
			<form name="frm_consultarResultados" action="guardar_reporte2.php" method="post">
				<?php 
				if($sql!="false"){
					//Extraer la sentencia SQL y el msje usando como separador el <br>
					$datos=split("<br>",$sql);
				?>
					<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="<?php echo $datos[0];?>"/>
					<input type="hidden" name="fecha_ini" id="fecha_ini" value="<?php echo $datos[2];?>"/>
					<input type="hidden" name="fecha_fin" id="fecha_fin" value="<?php echo $datos[3];?>"/>
					<input type="hidden" name="hdn_msje" id="hdn_msje" value="<?php echo $datos[1];?>"/>
					<input type="hidden" name="hdn_bd" id="hdn_bd" value="bd_recursos"/>
					<input type="hidden" name="hdn_tipoReporte" id="hdn_tipoReporte" value="exportarNominaMam"/>
					<input name="sbt_excel" type="submit" class="botones" id= "sbt_excel" value="Exportar a Excel" title="Exportar a Excel"
					onMouseOver="window.status='';return true"/>
				<?php 
				}
				else{?>
					<input name="sbt_excel" type="submit" class="botones" id= "sbt_excel" value="Exportar a Excel" title="No Hay N&oacute;mina para Exportar"
					onMouseOver="window.status='';return true" disabled="disabled"/>
				<?php
				}
				?>
				&nbsp;&nbsp;
			<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otros Datos para Reporte" 
			onmouseover="window.status='';return true" onclick="location.href='frm_consultarNominaMam.php'" />
			</form>
		</div>
	<?php
	}
	?>
	
	<script type="text/javascript">
		cargarCmbNomina('cmb_nomina',txt_fechaIni.value,txt_fechaFin.value,'bd_recursos','MANTENIMIENTO MINA','1');
	</script>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
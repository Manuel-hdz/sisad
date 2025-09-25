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
		include ("op_reporteStatus.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:525px; height:24px; z-index:11; }
		#rpt-FMU { position:absolute; left:30px; top:190px; width:430px; height:260px; z-index:12; }
		#calendar-uno { position:absolute; left:242px; top:218px; width:30px; height:26px; z-index:13; }
		#calendar-dos { position:absolute; left:242px; top:254px; width:30px; height:26px; z-index:14; }
		#resultados { position:absolute; left:30px; top:190px; width:921px; height:430px; z-index:15; overflow:scroll; }
		#btns-rpt { position: absolute; left:50px; top:669px; width:928px; height:40px; z-index:16; }								  
		-->
    </style>	
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte de Status de Equipos</div>
   
    <?php 
	if(isset($_GET["noResults"])){
		?>
		<script type="text/javascript" language="javascript">
		setTimeout("alert('No se Encontraron Resultados con los Criterios Proporcionados');",1000);
		</script>
		<?php
	}
   
   if(!isset($_POST["sbt_generar"])){?>
	<fieldset class="borde_seccion" id="rpt-FMU" name="rpt-FMU">	
		<legend class="titulo_etiqueta">Reporte por Fechas</legend>
		<form onsubmit="return verFormReportePreventivoCorrectivo(this);" name="frm_rptFecha" action="frm_reporteStatus.php" method="post" >
		<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td width="79"><div align="right">Fecha Inicio</div></td>
				<td width="301"><input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-5 day")); ?> size="10" maxlength="15" readonly=true width="90" /></td>
			</tr>
			<tr>
				<td><div align="right">Fecha Fin </div></td>
		        <td><input name="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
			</tr>
			<tr><td colspan="2"><strong>Filtrar por Turno</strong></td></tr>
			<tr>
			  <td width="62"><div align="right">Equipo</div></td>
				<td width="222">
					<select name="cmb_turno" id="cmb_turno" class="combo_box" tabindex="3" title="Seleccionar un Turno para Filtrar la Informaci&oacute;n">
						<option value="">Turno</option>
						<option value="TURNO DE PRIMERA">TURNO DE PRIMERA</option>
						<option value="TURNO DE SEGUNDA">TURNO DE SEGUNDA</option>
						<option value="TURNO DE TERCERA">TURNO DE TERCERA</option>
					</select>
			  </td>
			</tr>
			<tr><td colspan="2"><strong>Filtrar por Equipo</strong></td></tr>
			<tr>
			  <td width="62"><div align="right">Equipo</div></td>
				<td width="222">
					<?php
					//Conectarse a la BD de Mantenimiento
					$conn = conecta("bd_mantenimiento");
					$rs_equipos = mysql_query("SELECT DISTINCT id_equipo FROM equipos WHERE area = 'MINA' AND estado='ACTIVO' ORDER BY familia,id_equipo");
					if($equipos=mysql_fetch_array($rs_equipos)){?>
						<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="4">
							<option value="">Equipo</option><?php
						do{
							echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
						}while($equipos=mysql_fetch_array($rs_equipos));
						?></select><?php
					}
					else{
						echo "<label class='msje_correcto'> No hay Equipos Registrados</label>
						<input type='hidden' name='cmb_equipo' id='cmb_equipo'/>";
					}
				?>
			  </td>
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

	<?php }
	else{
		$turno=$_POST["cmb_turno"];
		$equipo=$_POST["cmb_equipo"];		
		echo "<div id='resultados' class='borde_seccion2'>";
			reporteEstatus();
		echo "</div>";
	?>

		<div id="btns-rpt" align="center">
			<input type="button" name="btn_exportarExcel" id="btn_exportarExcel" value="Exportar a Excel" class="botones" title="Exportar a Excel el Reporte"
			onclick="location.href='guardar_reporte.php?tipoRep=RepEstatus&fechaI=<?php echo $_POST["txt_fechaIni"];?>&fechaF=<?php echo $_POST["txt_fechaFin"];?>&turno=<?php echo $turno?>&equipo=<?php echo $equipo?>'"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" name="btn_regresar" id="btn_regresar" value="Regresar" class="botones" title="Regresar a Seleccionar otros Criterios" onclick="location.href='frm_reporteStatus.php'" />
		</div> 
	<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
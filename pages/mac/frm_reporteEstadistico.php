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
		include ("op_reporteSistemas.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="includes/ajax/borrarHistorial.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboMesesBit.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquiposBit.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--	
			#titulo-consultar-sistemas {position:absolute; left:30px; top:146px; width:388px; height:25px; z-index:11;}
			#tabla-consultar-sistemas {position:absolute; left:30px; top:190px; width:315px; height:210px; z-index:14;}
			#tabla-graficas { position:absolute; left:30px; top:190px; width:945px; height:430px; z-index:21;overflow:scroll;}
			#botones{position:absolute;left:30px;top:670px;width:950px;height:37px;z-index:16;}

			#tabla-consultar-sistemas2{position:absolute; left:430px; top:190px; width:400px; height:210px; z-index:14;}
			#calendar-uno {position:absolute; left:635px; top:232px; width:30px; height:26px; z-index:18;}
			#calendar-dos {position:absolute; left:825px; top:232px; width:30px; height:26px; z-index:18;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-sistemas">Reporte Estad&iacute;stico de Fallas</div>
	<?php 
	//Verificamos si viene definido en el post el boton consultar
	if(isset($_POST["sbt_consultar"]) || isset($_POST["sbt_consultar2"])){
	?>
		<div align="center" id="botones">
			<form action="guardar_reporte.php" method="post" id="frm_exportarDiv">
				<?php if(isset($_POST["sbt_consultar2"])){?>
				<input type="hidden" id="hdn_divExpRepEstadistico" name="hdn_divExpRepEstadistico" />
				<input type="button" id="btn_exportar" name="btn_exportar" class='botones' value="Exportar a Excel" title="Exportar a Excel"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<?php }?>
				<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Seleccionar otras Fechas" 
				onMouseOver="window.status='';return true" onclick="borrarHistorial();location.href='frm_reporteEstadistico.php'"/>
			</form>
		</div>
		
		<?php 
		if(isset($_POST["sbt_consultar"])){
			?>
			<?php //Funciones de LightBox?>
			<link rel="stylesheet" href="../../includes/lightbox/css/lightbox.css" type="text/css" media="screen" />
			<script src="../../includes/lightbox/js/prototype.js" type="text/javascript"></script>
			<script src="../../includes/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
			<script src="../../includes/lightbox/js/lightbox.js" type="text/javascript"></script>
			<?php
			reporteSistemas();
		}
		if(isset($_POST["sbt_consultar2"])){
			//Script que envia el contenido de un DIV a imprimir en Excel
			?>
			<script type="text/javascript" src="../../includes/jquery-1.5.1.js" ></script>
			<script language="javascript">
				$(document).ready(function() {
					$("#btn_exportar").click(function(event) {
						$("#hdn_divExpRepEstadistico").val( $("<div>").append( $("#tabla-rpt-fallas").eq(0).clone()).html());
					$("#frm_exportarDiv").submit();
					});
				});
			</script>
			<?php
			echo "<div align='center' id='tabla-graficas' class='borde_seccion2'>";
				reporteFallas();
			echo "</div>";
		}
	 } 
	 else{?>
	 	<script type="text/javascript" language="javascript">
			var foco="document.getElementById('cmb_anios').focus()";
			setTimeout(foco,100);
		</script>
		<fieldset class="borde_seccion" id="tabla-consultar-sistemas">
		<legend class="titulo_etiqueta">Reporte de Estad&iacute;stica Mensual</legend>	
		<br>
		<form onSubmit="return valFormRepEstadistico(this);" method="post" name="frm_reporteEstadistico" id="frm_reporteEstadistico" action="frm_reporteEstadistico.php">
		<table width="319" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">A&ntilde;o</div></td>
				<td width="222">
					<?php
					//conectar a gerencia
					$conn = conecta('bd_mantenimiento');
					$rs = mysql_query("SELECT DISTINCT(SUBSTRING(fecha_mtto,1,4)) AS anio FROM bitacora_mtto WHERE SUBSTRING(fecha_mtto,1,4)!='0000' 
									AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='CONCRETO') ORDER BY anio");
					if(mysql_num_rows($rs)>0){
						?>
						<select name="cmb_anios" id="cmb_anios" class="combo_box" onchange="cargarComboMesesMttoC(this.value,'cmb_meses',1);" tabindex="1">
							<option value="">A&ntilde;o</option>
						<?php
						while($datos=mysql_fetch_array($rs)){
							?>
							<option value="<?php echo $datos["anio"];?>"><?php echo $datos["anio"];?></option>
							<?php
						}
					?>
						</select>
						<?php
						//cerrar conexion
						mysql_close($conn);
					}
					else
						echo "<label class='msje_correcto'>NO HAY DATOS</label>";
					?>
			  </td>
			</tr>
			<tr>
			  <td width="62"><div align="right">Mes</div></td>
				<td width="222">
					<select name="cmb_meses" id="cmb_meses" class="combo_box" tabindex="2" onchange="cargarComboEquipos(cmb_anios.value,this.value,'cmb_equipo')">
						<option value="">Mes</option>
					</select>
			  </td>
			</tr>
			<tr><td colspan="2"><strong>Filtrar por Equipo</strong></td></tr>
			<tr>
			  <td width="62"><div align="right">Equipo</div></td>
				<td width="222">
					<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="3" title="Seleccionar un Equipo para Filtrar la Informaci&oacute;n">
						<option value="">Equipo</option>
					</select>
			  </td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_consultar" type="submit"  class="botones" id="sbt_consultar" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte Esta&iacute;stico de Fallas" tabindex="4"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" tabindex="5"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-consultar-sistemas2">
		<legend class="titulo_etiqueta">Reporte para Exportar</legend>
		<br>
		<form method="post" name="frm_reporteEstadistico2" id="frm_reporteEstadistico2" action="frm_reporteEstadistico.php" onsubmit="return valFormRepEstadisticoDetalle(this);">
		<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Fecha Inicio</div></td>
				<td width="94">
			  <input name="txt_fechaIni" id="txt_fechaIni" type="text" value="<?php echo date("d/m/Y", strtotime("-7 day")); ?>" size="10"
					maxlength="15" readonly="true" width="90" />			  </td>
			  <td width="66"><div align="right">Fecha Fin</div></td>
				<td width="98">
					<input name="txt_fechaFin" id="txt_fechaFin" type="text" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" 
					readonly="true" width="90" />			  </td>
			</tr>
			<tr>
			  <td width="77"><div align="right">Familia</div></td>
				<td colspan="3">
				<?php
					//Conectarse a la BD de Mantenimiento
					$conn = conecta("bd_mantenimiento");
					$rs_familia = mysql_query("SELECT DISTINCT familia FROM equipos WHERE area = 'CONCRETO' AND metrica='HOROMETRO' ORDER BY familia");
					if($familias=mysql_fetch_array($rs_familia)){?>
						<select name="cmb_familia" id="cmb_familia" class="combo_box"
						onchange="cargarComboBiCondicional(this.value,'bd_mantenimiento','equipos','id_equipo','familia','area','CONCRETO','disponibilidad','ACTIVO','cmb_equipo2','Equipo','');" tabindex="3">
							<option value="">Familia</option><?php
						do{
							echo "<option value='$familias[familia]'>$familias[familia]</option>";
						}while($familias=mysql_fetch_array($rs_familia));
						?></select><?php
					}
					else{
						echo "<label class='msje_correcto'> No hay Familias Registradas, Agregue una nueva</label>
						<input type='hidden' name='cmb_familia' id='cmb_familia'/>";
					}
				?>
				</td>
			</tr>
			<tr><td colspan="4"><strong>Filtrar por Equipo</strong></td></tr>
			<tr>
			  <td width="77"><div align="right">Equipo</div></td>
				<td  colspan="3">
					<select name="cmb_equipo2" id="cmb_equipo2" class="combo_box" tabindex="4" title="Seleccionar un Equipo para Filtrar la Informaci&oacute;n">
						<option value="">Equipo</option>
					</select>			  </td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input name="sbt_consultar2" type="submit"  class="botones" id="sbt_consultar2" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte Esta&iacute;stico de Fallas Exportable" tabindex="4"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" tabindex="5"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<div id="calendar-uno">
			<input name="finRptEquipo" id="iniRptArea" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_reporteEstadistico2.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" tabindex="7"/>
		</div>
		<div id="calendar-dos">
			<input name="finRptEquipo" id="finRptArea" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_reporteEstadistico2.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" tabindex="8"/>	
		</div>
	<?php 
		if(isset($_GET["noResults"])){
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('No se Encontraron Resultados con los datos Proporcionados');",500);
			</script>
			<?php
		}
	}
	?>
</body><?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
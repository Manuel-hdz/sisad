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
		include ("op_reporteDisponibilidad.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<?php //Funciones de LightBox?>
	<link rel="stylesheet" href="../../includes/lightbox/css/lightbox.css" type="text/css" media="screen" />
	<script src="../../includes/lightbox/js/prototype.js" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/lightbox.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="includes/ajax/cargarComboMesesBit.js"></script>
	
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="includes/ajax/borrarHistorial.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--	
			#titulo-consultar-sistemas {position:absolute; left:30px; top:146px; width:388px; height:25px; z-index:11; }
			#tabla-consultar-disponibilidad {position:absolute; left:30px; top:190px; width:315px; height:270px; z-index:14;}
			#tabla-consultar-disponibilidad2 {position:absolute; left:450px; top:190px; width:315px; height:144px; z-index:14;}
			#tabla-graficas { position:absolute; left:30px; top:190px; width:945px; height:430px; z-index:21;overflow:scroll;}
			#tabla-datos { position:absolute; left:30px; top:190px; width:945px; height:430px; z-index:21;overflow:scroll;}
			#botones{position:absolute;left:30px;top:670px;width:950px;height:37px;z-index:16;}
			#calendar-uno {position:absolute; left:650px; top:234px; width:30px; height:26px; z-index:18; }
			#calendar-dos {position:absolute; left:650px; top:270px; width:30px; height:26px; z-index:18; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-sistemas">Reporte Disponibilidad de Equipos</div>
	<?php 
	//Verificamos si viene definido en el post el boton consultar
	if(isset($_POST["sbt_consultar"]) || isset($_POST["sbt_consultar2"]) || isset($_POST["sbt_repDisp2"])){
		if(isset($_POST["sbt_consultar"])){
			$res=reporteDisponibilidad();
			if($res){
				?>
				<script type="text/javascript" language="javascript">
					function alternarVista(opc){
						switch(opc){
							case 1:
								document.getElementById("tabla-graficas").style.visibility="hidden";
								document.getElementById("tabla-datos").style.visibility="visible";
							break;
							case 2:
								document.getElementById("tabla-graficas").style.visibility="visible";
								document.getElementById("tabla-datos").style.visibility="hidden";
							break;
						}
					}
				</script>
				<div align="center" id="botones">
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Seleccionar otras Fechas" 
					onMouseOver="window.status='';return true" onclick="borrarHistorial();location.href='frm_reporteDisponibilidad.php'"/>
				</div>	
				<?php 
			}
		}//Fin de if(isset($_POST["sbt_consultar"]))
		if(isset($_POST["sbt_consultar2"])){
			echo "<form name='frm_seleccionarEquipoFiltro' method='POST' onsubmit='return valFormSelEquipoFiltro(this)';>";
			echo "<div id='tabla-datos' class='borde_seccion2' align='center'>";
				mostrarEquiposMttoC();
			echo "</div>";
			?>
			<div align="center" id="botones">
				<input type="hidden" name="hdn_fechaI" id="hdn_fechaI" value="<?php echo $_POST["txt_fechaIni"]?>"/>
				<input type="hidden" name="hdn_fechaF" id="hdn_fechaF" value="<?php echo $_POST["txt_fechaFin"]?>"/>
				<input name="sbt_repDisp2" id="sbt_repDisp2" type="submit" class="botones" value="Generar Reporte" title="Generar Reporte de Disponibilidad por Fechas" onMouseOver="window.status='';return true"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Seleccionar otras Fechas" 
				onMouseOver="window.status='';return true" onclick="borrarHistorial();location.href='frm_reporteDisponibilidad.php'"/>
			</div>
			<?php
			echo "</form>";
		}//Fin de if(isset($_POST["sbt_consultar"]))
		if(isset($_POST["sbt_repDisp2"])){
			echo "<div id='tabla-datos' class='borde_seccion2' align='center'>";
				reporteDisponibilidadFecha();
			echo "</div>";
			?>
			<div align="center" id="botones">
				<?php
				//Los nombres asignados permiten regresar po nivel en la secuencia de ventanas y contenido
				?>
				<form method="post" action="frm_reporteDisponibilidad.php">
					<input type="hidden" name="txt_fechaIni" id="txt_fechaIni" value="<?php echo $_POST["hdn_fechaI"]?>"/>
					<input type="hidden" name="txt_fechaFin" id="txt_fechaFin" value="<?php echo $_POST["hdn_fechaF"]?>"/>
					<input name="sbt_consultar2" type="submit" class="botones" value="Regresar" title="Seleccionar otros Equipos" 
					onMouseOver="window.status='';return true" onclick="borrarHistorial();location.href='frm_reporteDisponibilidad.php'"/>
				</form>
			</div>
			<?php
		}
	 } 
	 else{
	 ?>
	 	<script type="text/javascript" language="javascript">
			var foco="document.getElementById('cmb_anios').focus()";
			setTimeout(foco,100);
		</script>
		<fieldset class="borde_seccion" id="tabla-consultar-disponibilidad" name="tabla-consultar-disponibilidad">
		<legend class="titulo_etiqueta">Reporte de Disponibilidad Mensual</legend>	
		<br>
		<form method="post" name="frm_reporteDisponibilidad" id="frm_reporteDisponibilidad" action="frm_reporteDisponibilidad.php" onsubmit="return valFormRepDisponibilidad(this);">
		<table width="319" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">A&ntilde;o</div></td>
				<td width="222">
					<?php
					//conectar a gerencia
					$conn = conecta('bd_mantenimiento');
					$rs = mysql_query("SELECT DISTINCT(SUBSTRING(fecha,1,4)) AS anio FROM horometro_odometro WHERE SUBSTRING(fecha,1,4)!='0000' 
									AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='CONCRETO') ORDER BY anio");
					if(mysql_num_rows($rs)>0){
						?>
						<select name="cmb_anios" id="cmb_anios" class="combo_box" onchange="cargarComboMesesMttoC(this.value,'cmb_meses',2);" tabindex="1">
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
					<select name="cmb_meses" id="cmb_meses" class="combo_box" tabindex="2">
						<option value="">Mes</option>
					</select>
			  </td>
			</tr>
			<tr><td colspan="2"><strong>Filtrar por Familia</strong></td></tr>
			<tr>
				<td width="62"><div align="right">Familia</div></td>
				<td>
				<?php
					//Conectarse a la BD de Mantenimiento
					$conn = conecta("bd_mantenimiento");
					$rs_familia = mysql_query("SELECT DISTINCT familia FROM equipos WHERE area = 'CONCRETO' AND metrica='HOROMETRO' ORDER BY familia");
					if($familias=mysql_fetch_array($rs_familia)){?>
						<select name="cmb_familia" id="cmb_familia" class="combo_box"
						onchange="cargarComboBiCondicional(this.value,'bd_mantenimiento','equipos','id_equipo','familia','area','CONCRETO','disponibilidad','ACTIVO','cmb_equipo','Equipo','');" tabindex="3">
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
			<tr><td colspan="2"><strong>Filtrar por Equipo</strong></td></tr>
			<tr>
			  <td width="62"><div align="right">Equipo</div></td>
				<td width="222">
					<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="4" title="Seleccionar un Equipo para Filtrar la Informaci&oacute;n">
						<option value="">Equipo</option>
					</select>
			  </td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_consultar" type="submit"  class="botones" id="sbt_consultar" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte de Disponibilidad" tabindex="4"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" tabindex="5"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-consultar-disponibilidad2" name="tabla-consultar-disponibilidad2">
		<legend class="titulo_etiqueta">Reporte de Disponibilidad por Fechas</legend>	
		<br>
		<form method="post" name="frm_reporteDisponibilidad2" id="frm_reporteDisponibilidad2" action="frm_reporteDisponibilidad.php">
		<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
			<tr>
				<td><div align="right">Fecha Inicio</div></td>
				<td width="225">
					<input name="txt_fechaIni" id="txt_fechaIni" type="text" value="<?php echo date("d/m/Y", strtotime("-7 day")); ?>" size="10"
					maxlength="15" readonly="true" width="90" />
			  </td>
			</tr>
			<tr>
				<td width="78"><div align="right">Fecha Fin</div></td>
				<td width="225">
					<input name="txt_fechaFin" id="txt_fechaFin" type="text" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" 
					readonly="true" width="90" />
			  </td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_consultar2" type="submit"  class="botones" id="sbt_consultar2" value="Continuar"
					onmouseover="window.status='';return true;" title="Continuar a Seleccionar los Equipos" tabindex="9"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" tabindex="10"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<div id="calendar-uno">
			<input name="finRptEquipo" id="iniRptArea" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_reporteDisponibilidad2.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" tabindex="7"/>
		</div>
		<div id="calendar-dos">
			<input name="finRptEquipo" id="finRptArea" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_reporteDisponibilidad2.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" tabindex="8"/>	
		</div>
	<?php 
	}
	?>
</body><?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarFechasTxt.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	
	
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
			#tabla-consultarFechas{position:absolute;left:30px;top:190px;width:425px;height:160px;z-index:12;}
			#calendario-uno{position:absolute;left:180px;top:233px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:180px;top:278px;width:30px;height:26px;z-index:14;}
			#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:460px; z-index:15; padding:15px; padding-top:0px; overflow:scroll;}
			#botones{position:absolute;left:30px;top:680px;width:945px;height:40px;z-index:16;}
		-->
    </style>
	
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>

</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Reporte N&oacute;mina</div>

	<?php
	if (!isset($_POST["sbt_consultar"])){
	?>
		<fieldset class="borde_seccion" id="tabla-consultarFechas" name="tabla-consultarFechas">
		<legend class="titulo_etiqueta">Seleccione Fechas de Trabajo</legend>	
		<br>
		<form name="frm_reporteNomina" method="post" action="frm_reporteNomina.php" onsubmit="return valGenerarNomina(this);">
			<table width="415"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="104"><div align="right">Fecha Inicio</div></td>
					<td width="276">
						<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" value="<?php echo date("d/m/Y", strtotime("-7 day"));?>" readonly="readonly" 
						onchange="comprobarFechas('txt_fechaIni','txt_fechaFin'); cargarCmbNomina('cmb_nomina',txt_fechaIni.value,txt_fechaFin.value,'bd_gerencia',cmb_ubicacion_con.value,'1');"/>
					</td>
					<td width="128">
						<div align="right">*Ubicaci&oacute;n</div>
					</td>
					<td width="57">
						<select name="cmb_ubicacion_con" id="cmb_ubicacion_con" size="1" class="combo_box"  required="required"
						onchange="document.getElementById('txt_ubicacion_con').value=this.options[this.selectedIndex].text; cargarCmbNomina('cmb_nomina',txt_fechaIni.value,txt_fechaFin.value,'bd_gerencia',cmb_ubicacion_con.value,'1');">
							<option value="">Ubicaci&oacute;n</option>
							<?php
							$cmb_ubicacion="";				
							$conn = conecta("bd_recursos");
							$result = mysql_query ("SELECT * 
													FROM  `control_costos` 
													WHERE (
														`descripcion` LIKE  '%zarpeo%'
														OR  `descripcion` LIKE  '%alcantarillado%'
														OR  `descripcion` LIKE  '%obra civil%'
													)
													AND  `habilitado` =  'SI'");				 
							while ($row=mysql_fetch_array($result)){
								echo "<option value='$row[id_control_costos]'>$row[descripcion]</option>";
							}
							mysql_close($conn);
							?>
						</select>
						<input type="hidden" id="txt_ubicacion_con" name="txt_ubicacion_con" value=""/>
					</td>
				</tr>
				<tr>
					<td width="104"><div align="right">Fecha Fin</div></td>
					<td>
						<input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly" 
						onchange="comprobarFechas('txt_fechaIni','txt_fechaFin'); cargarCmbNomina('cmb_nomina',txt_fechaIni.value,txt_fechaFin.value,'bd_gerencia',cmb_ubicacion_con.value,'1');"/>
					</td>
					<td width="128">
						<div align="right">*Nomina</div>
					</td>
					<td width="57">
						<span id="datosNomina">
							<select name="cmb_nomina" id="cmb_nomina" class="combo_box" required="required">
								<option value="">Nomina</option>
							</select>
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<div align="center">
							<input name="sbt_consultar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Consultar N&oacute;mina"
							onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otro Reporte" 
							onmouseover="window.status='';return true" onclick="location.href='menu_reportes.php'" />
						</div>
					</td>
				</tr>
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
	} else {
	?>
		<div id="tabla-resultados" class="borde_seccion">
			<?php
			$sql=mostrarNominaBD();
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
					<input type="hidden" name="hdn_consulta2" id="hdn_consulta2" value="<?php echo $datos[1];?>"/>
					<input type="hidden" name="id_nomina" id="id_nomina" value="<?php echo $datos[2];?>"/>
					<input type="hidden" name="hdn_msje" id="hdn_msje" value="<?php echo $datos[3];?>"/>
					<input type="hidden" name="hdn_tipoReporte" id="hdn_tipoReporte" value="exportarNomina"/>
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
			onmouseover="window.status='';return true" onclick="location.href='frm_reporteNomina.php'" />
			</form>
		</div>
	<?php
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
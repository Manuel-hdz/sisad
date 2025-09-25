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
		include ("op_reporteAceites.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<?php //Funciones de LightBox?>
	<link rel="stylesheet" href="../../includes/lightbox/css/lightbox.css" type="text/css" media="screen" />
	<script src="../../includes/lightbox/js/prototype.js" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/lightbox.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="includes/ajax/borrarHistorial.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboMesesBit.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquiposBit.js"></script>
	
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>
    <style type="text/css">
		<!--	
			#titulo-reporte {position:absolute; left:30px; top:146px; width:388px; height:25px; z-index:11; }
			#tabla-consultar {position:absolute; left:30px; top:190px; width:504px; height:147px; z-index:14;}
			#tabla-graficas { position:absolute; left:30px; top:190px; width:945px; height:430px; z-index:21;overflow:scroll;}
			#botones{position:absolute;left:30px;top:670px;width:950px;height:37px;z-index:16;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-reporte">Reporte de Bit&aacute;cora de Aceites</div>
	<?php 
	//Verificamos si viene definido en el post el boton consultar
	if(!isset($_POST["sbt_consultarConsumo"])){
		if(isset($_GET["noResults"])){
			echo "
				<script type='text/javascript' language='javascript'>
					setTimeout(\"alert('NO se Encontraron Resultados con los Criterios Seleccionados');\",500);
				</script>
			";
		}
		?>
	 	<script type="text/javascript" language="javascript">
			if(document.getElementById('cmb_anios')!=undefined){
				var foco="document.getElementById('cmb_anios').focus()";
				setTimeout(foco,100);
			}
		</script>
		<fieldset class="borde_seccion" id="tabla-consultar">
		<legend class="titulo_etiqueta">Reporte Mensual de Aceite</legend>	
		<br>
		<form method="post" name="frm_reporteAceites" id="frm_reporteAceites" onsubmit="return valFormRepAceites(this);">
		<table width="108%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td><div align="right">A&ntilde;o</div></td>
				<td width="94">
					<?php
					//conectar a gerencia
					$conn = conecta('bd_mantenimiento');
					$rs = mysql_query("SELECT DISTINCT(SUBSTRING(fecha,1,4)) AS anio FROM bitacora_aceite ORDER BY anio");
					if(mysql_num_rows($rs)>0){
						?>
						<select name="cmb_anios" id="cmb_anios" class="combo_box" onchange="cargarComboMesesMttoAceites(this.value,'cmb_meses');" tabindex="1">
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
			  <td><div align="right">Tipo de Reporte</div></td>
			  <td width="237">
					<select name="cmb_tipo" id="cmb_tipo" class="combo_box" tabindex="3" title="Seleccionar el tipo de Reporte de Aceites" onchange="valMostrarEquipos(this.value);">
						<option value="" selected="selected">Tipo</option>
						<option value="I" title="Reporte de Aceites Agregados durante el Periodo Seleccionado">NUEVOS ACEITES</option>
						<option value="E" title="Reporte de Incrementos Registrados en el Cat&aacute;logo de Aceites">INCREMENTO ACEITES</option>
						<option value="S" title="Reporte de Aceite Consumido">CONSUMO ACEITES</option>
					</select>
			  </td>
			</tr>
			<tr>
			  <td width="44"><div align="right">Mes</div></td>
				<td width="94">
					<select name="cmb_meses" id="cmb_meses" class="combo_box" tabindex="2" onchange="cargarComboEquiposBitAceite(cmb_anios.value,this.value,'cmb_equipo')">
						<option value="">Mes</option>
					</select>
			  </td>
			  <td width="104"><div align="right" id="labelEquipo" style="visibility:hidden">Equipo</div></td>
				<td width="237">
					<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="3" title="Seleccionar un Equipo para Filtrar la Informaci&oacute;n" style="visibility:hidden">
						<option value="">Equipo</option>
					</select>
			  </td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input name="sbt_consultarConsumo" type="submit"  class="botones" id="sbt_consultarConsumo" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte de Consumo de Aceite" tabindex="4"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" tabindex="5"/>
				</td>
			</tr>
		</table>
		</form>
</fieldset>
	<?php 
	}
	else{
		if(isset($_POST["sbt_consultarConsumo"]))
			reporteAceites();
		?>
		<div align="center" id="botones">
			<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Seleccionar otras Fechas" 
			onMouseOver="window.status='';return true" onclick="borrarHistorial();location.href='frm_reporteAceites.php'"/>
		</div>
		<?php 
	}?>
	
</body><?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
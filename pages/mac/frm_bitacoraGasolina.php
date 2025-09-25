<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">


<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento Concreto
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para Generar el Reporte de Mnatenimientos Correctivos de Acuerdo a los Parametros Seleccionados
		include ("op_gestionAceites.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js"></script>
	<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	
	<script type="text/javascript" language="javascript">
		setTimeout("document.getElementById('txt_equipo').focus();",100);
	</script>
	
    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:264px; height:24px; z-index:11; }
		#bitacoraGasolina { position:absolute; left:30px; top:190px; width:405px; height:250px; z-index:12; }
		#calendario { position:absolute; left:220px; top:233px; width:30px; height:26px; z-index:13; }
		#equipos { position:absolute; left:30px; top:190px; width:921px; height:450px; z-index:22; overflow: scroll; z-index:14;}
		#botones{position:absolute;left:30px;top:675px;width:950px;height:37px;z-index:15;}
		#res-spider3 { position:absolute; width:10px; height:10px; z-index:13; }
		#calendario { position:absolute; left:250px; top:230px; width:30px; height:26px; z-index:13; }
		-->
    </style>	
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Bit&aacute;cora de Consumo de Gasolina</div>
	
	<fieldset class="borde_seccion" id="bitacoraGasolina" name="bitacoraGasolina">	
	<legend class="titulo_etiqueta">Registro de Bitacora de Gasolina</legend>
	<br />
	<form name="frm_bitacoraGasolina" action="frm_bitacoraGasolina.php" method="post" onsubmit="return valFormBitacoraGasolina(this);">
		<table width="100%" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
				<td><div align="right">Fecha </div></td>
				<td width="115">
					<input name="txt_fecha" id="txt_fecha" type="text" value=<?php echo date("d/m/Y");?> size="10" maxlength="15" readonly="true" tabindex="1"/>
				</td>
				<td width="93">&nbsp;</td>
				<!-- <td width="136"><div align="right">*Turno</div></td>
				<td colspan="2">
					<select name="cmb_turno" id="cmb_turno" class="combo_box" title="Seleccionar el Turno del Registro" tabindex="2">
						<option value="">Turno</option>
						<option value="PRIMERA">PRIMERA</option>
						<option value="SEGUNDA">SEGUNDA</option>
						<option value="TERCERA">TERCERA</option>
					</select>
				</td> -->
				<td><div align="right">*Equipo</div></td>
				<td colspan="2">
					<!-- <select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="3">
						<option value="">Equipo</option>
						<?php 
						/*$area="";
						//Obtener los Sistemas Registrados en la BD
						$conn = conecta("bd_mantenimiento");
						$rs_equipos = mysql_query("SELECT id_equipo FROM equipos WHERE area!='$area' AND estado='ACTIVO' ORDER BY familia,id_equipo");
						if($equipos=mysql_fetch_array($rs_equipos)){
							do{
								echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
							}while($equipos=mysql_fetch_array($rs_equipos));
						}
						//Cerrar la conexion con la BD
						mysql_close($conn);*/
						?>
					</select> -->
					<input type="text" name="txt_equipo" id="txt_equipo" class="caja_de_texto" size="10" maxlength="20" onfocus="this.oldvalue = this.value;" onchange="extraerInfoEquipo(this.value,this.oldvalue);"  tabindex="3"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*LTS</div></td>
				<td valign="top" colspan="2">
					<input name="txt_litros" id="txt_litros" type="text" class="caja_de_num" size="10" maxlength="10" 
					onkeypress="return permite(event,'num',2);" onchange="formatCurrency(value,'txt_litros');" tabindex="4"/>
				</td>
				<td><div align="right" title="Od&oacute;metro">*Od&oacute;metro</div></td>
				<td colspan="2">
					<input type="text" class="caja_de_num" id="txt_metrica" name="txt_metrica" size="10" maxlength="10" value="" onkeypress="return permite(event,'num',2);" 
					onchange="formatCurrency(value,'txt_metrica');" tabindex="5"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Costo</div></td>
				<td valign="top" colspan="2">
					<input name="txt_costo" id="txt_costo" type="text" class="caja_de_num" size="10" maxlength="10" 
					onkeypress="return permite(event,'num',2);" onchange="formatCurrency(value,'txt_costo');" tabindex="6"/>
				</td>
				<td><div align="right" title="No. Vale">*No. Vale</div></td>
				<td colspan="2">
					<input type="text" class="caja_de_num" id="txt_noVale" name="txt_noVale" size="20" maxlength="20" value="" tabindex="7" onchange="extraerInfoValeGasolina(this.value);"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Responsable</div></td>
				<td valign="top" colspan="5">
					<!-- <select name="cmb_responsable" id="cmb_responsable" class="combo_box" tabindex="6">
						<option value="">Responsable</option>
						<?php 
						//Obtener los Sistemas Registrados en la BD
						/*$conn = conecta("bd_recursos");
						$rs = mysql_query("SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombreEmpleado,puesto FROM empleados WHERE id_cuentas='CUEN002' ORDER BY nombreEmpleado");
						if($datos=mysql_fetch_array($rs)){
							do{
								echo "<option value='$datos[nombreEmpleado]' title='$datos[puesto]'>$datos[nombreEmpleado]</option>";
							}while($datos=mysql_fetch_array($rs));
						}
						//Cerrar la conexion con la BD
						mysql_close($conn);*/
						?>
					</select> -->
					<input name="txt_nombre" type="text" class="caja_de_texto"  size="62" id="txt_nombre"
							value="" onkeyup="lookup(this,'3');" tabindex="8"/>
					<div id="res-spider3">
						<div align="left" class="suggestionsBox" id="suggestions3" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList3">&nbsp;</div>
						</div>
					</div>
				</td>
				<input name="txt_rfc" type="hidden" class="caja_de_texto"  size="40" id="txt_rfc"/>
			</tr>
			<tr><td colspan="6"><hr /></td></tr>
			<tr>
				<td colspan="6" align="center">
					<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Guardar" title="Guardar Bitacora Gasolina" onmouseover="window.status='';return true;" tabindex="9"/>
					&nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" tabindex="14" onclick="txt_equipo.focus();"/>
					&nbsp;
					<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar al Men&uacute; de Llantas" onclick="location.href='menu_aceites.php'" tabindex="15"/>
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
	<div id="calendario">
		<input name="fechaRegistro" id="fechaRegistro" type="image" src="../../images/calendar.png" title="Seleccionar la Fecha de Registro de Consumo de Gasolina"
		onclick="displayCalendar(document.frm_bitacoraGasolina.txt_fecha,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />
	</div>
	<?php
	if(isset($_POST["sbt_guardar"])){
		guardarRegistroGasolina();
	}
	if(isset($_POST["sbt_continuar"])){
		echo "<form method='post' action='frm_bitacoraAceites.php' name='frm_regBitAceite' onsubmit='return valFormGastoAceite(this)';>";
		echo "<div id='equipos' class='borde_seccion' align='center'/>";
			mostrarEquipos($txt_fecha,$cmb_familia);
		echo "</div>";
		?>
		<div id="botones" align="center">
			<input type="submit" class="botones" value="Guardar" title="Guardar Registros de Aceites" onmouseover="window.status='';return true" name="sbt_guardar"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="reset" class="botones" value="Limpiar" title="Limpiar los datos del Formulario" name="btn_reset" onclick="restablecerBitacoraAceite();"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" class="botones" value="Cancelar" title="Cancelar y volver a Seleccionar otra Familia" onclick="location.href='frm_bitacoraAceites.php'" name="btn_cancelar"/>
		</div>
		<?php
		echo "</form>";
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
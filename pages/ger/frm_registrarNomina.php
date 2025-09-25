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
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarFechasTxt.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-consultar{position:absolute;left:30px;top:146px; width:256px;height:20px;z-index:11;}
			#tabla-consultarFechas{position:absolute;left:30px;top:190px;width:425px;height:145px;z-index:12;}
			#tabla-consultarFechas-Con{position:absolute;left:30px;top:190px;width:425px;height:155px;z-index:12;}
			#calendario-uno{position:absolute;left:180px;top:233px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:180px;top:278px;width:30px;height:26px;z-index:13;}
			#calendario-tres{position:absolute;left:180px;top:443px;width:30px;height:26px;z-index:13;}
			#calendario-cuatro{position:absolute;left:180px;top:488px;width:30px;height:26px;z-index:13;}
			#botones{position:absolute;left:30px;top:640px;width:945px;height:40px;z-index:15;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Registrar N&oacute;mina de Zarpeo</div>

	<fieldset class="borde_seccion" id="tabla-consultarFechas" name="tabla-consultarFechas" style="height:160px">
	<legend class="titulo_etiqueta">Registrar Nomina</legend>	
	<br>
	<form name="frm_registrarNomina" method="post" action="frm_registrarNominaBonoEspecial2.php" onsubmit="">
		<table width="415"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="104"><div align="right">Fecha Inicio</div></td>
				<td width="276">
					<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" 
					value="<?php echo date("d/m/Y", strtotime("-7 day"));?>" readonly="readonly" onchange="comprobarFechas('txt_fechaIni','txt_fechaFin');"/>
				</td>
				<td width="128">
					<div align="right">*Ubicaci&oacute;n</div>
				</td>
				<td width="57">
					<select name="cmb_ubicacion" id="cmb_ubicacion" size="1" class="combo_box" 
					onchange="document.getElementById('txt_ubicacion').value=this.options[this.selectedIndex].text" required="required">
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
					<input type="hidden" id="txt_ubicacion" name="txt_ubicacion" value=""/>
				</td>
			</tr>
				<td width="104"><div align="right">Fecha Fin</div></td>
				<td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" 
					value="<?php echo date("d/m/Y");?>" readonly="readonly" onchange="comprobarFechas('txt_fechaIni','txt_fechaFin');"/></td>
			</tr>
			<tr>
				<td colspan="4">
					<div align="center">
						<input name="sbt_consultar" type="submit" class="botones" id= "sbt_consultar" value="Continuar" title="Continuar a Registrar N&oacute;mina de Zarpeo"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Sueldos" 
						onmouseover="window.status='';return true" onclick="location.href='menu_sueldos.php'" />
					</div>
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
	
	<fieldset class="borde_seccion" id="tabla-consultarFechas-Con" name="tabla-consultarFechas-Con" style="top:400px">
		<legend class="titulo_etiqueta">Continuar Nomina</legend>	
		<br>
		<form name="frm_continuarNomina" method="post" action="frm_continuarNomina.php" onsubmit="">
			<table width="415"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="104"><div align="right">Fecha Inicio</div></td>
					<td width="276">
						<input name="txt_fechaIni_con" type="text" id="txt_fechaIni_con" size="10" maxlength="15" value="<?php echo date("d/m/Y", strtotime("-7 day"));?>" readonly="readonly" 
						onchange="comprobarFechas('txt_fechaIni_con','txt_fechaFin_con'); cargarCmbNomina('cmb_nomina',txt_fechaIni_con.value,txt_fechaFin_con.value,'bd_gerencia',cmb_ubicacion_con.value,'0');"/>
					</td>
					<td width="128">
						<div align="right">*Ubicaci&oacute;n</div>
					</td>
					<td width="57">
						<select name="cmb_ubicacion_con" id="cmb_ubicacion_con" size="1" class="combo_box"  required="required"
						onchange="document.getElementById('txt_ubicacion_con').value=this.options[this.selectedIndex].text; cargarCmbNomina('cmb_nomina',txt_fechaIni_con.value,txt_fechaFin_con.value,'bd_gerencia',cmb_ubicacion_con.value,'0');">
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
						<input name="txt_fechaFin_con" type="text" id="txt_fechaFin_con" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly" 
						onchange="comprobarFechas('txt_fechaIni_con','txt_fechaFin_con'); cargarCmbNomina('cmb_nomina',txt_fechaIni_con.value,txt_fechaFin_con.value,'bd_gerencia',cmb_ubicacion_con.value,'0');"/>
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
							<input name="sbt_continuar" type="submit" class="botones" id= "sbt_continuar" value="Continuar" title="Continuar a Registrar N&oacute;mina de Zarpeo"
							onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Sueldos" 
							onmouseover="window.status='';return true" onclick="location.href='menu_sueldos.php'" />
						</div>
					</td>
				</tr>
			</table>
		</form>
	</fieldset>
	
	<div id="calendario-uno">
		<input name="calendario_uno" type="image" id="calendario_uno" onclick="displayCalendar (document.frm_registrarNomina.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
		title="Seleccione Fecha de Inicio" />
	</div>
	<div id="calendario-dos">
		<input name="calendario_dos" type="image" id="calendario_dos" onclick="displayCalendar (document.frm_registrarNomina.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
	</div>
	<div id="calendario-tres">
		<input name="calendario_tres" type="image" id="calendario_tres" onclick="displayCalendar (document.frm_continuarNomina.txt_fechaIni_con,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
		title="Seleccione Fecha de Inicio" />
	</div>
	<div id="calendario-cuatro">
		<input name="calendario_cuatro" type="image" id="calendario_cuatro" onclick="displayCalendar (document.frm_continuarNomina.txt_fechaFin_con,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
	</div>
	
	<!--
	<script type="text/javascript">
		setTimeout("cargarProdFechas('1',txt_fechaIni.value,txt_fechaFin.value)",200);
		cargarCmbNomina('cmb_nomina',txt_fechaIni_Con.value,txt_fechaFin_Con.value,'bd_gerencia','ZARPEO FRESNILLO, ZARPEO SAUCITO','0');
	</script>
	-->
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_agregarPresupuesto.php");?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<script type="text/javascript" src="../../includes/maxLength.js" ></script>
			<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
			<script type="text/javascript" src="includes/ajax/verificarRangoFechas.js" ></script>
			<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			
			<style type="text/css">
				<!--
				#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
				#tabla-registrarPresupuesto {position:absolute;left:30px;top:190px;width:742px;height:260px;z-index:14;}
				#calendario-Ini {position:absolute;left:300px;top:262px;width:30px;height:26px;z-index:14;}
				#calendario-Fin {position:absolute;left:300px;top:297px;width:30px;height:26px;z-index:14;}
				-->
			</style>
		</head>
		<body>
			
			<?php
			if(isset($_POST['sbt_guardar']))
				guardarPresupuesto();
			?>
			
			<script type="text/javascript" language="javascript">
				setTimeout("sumarDiasMes(); calcularDomingos();",500);		
			</script>
			
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-registrar">Ingresar Presupuesto Mensual</div>
			<fieldset class="borde_seccion" id="tabla-registrarPresupuesto" name="tabla-registrarPresupuesto">
				<legend class="titulo_etiqueta">Ingresar Datos del Presupuesto Mensual</legend>	
				<br>
				<form onSubmit="return valFormRegPresupuesto(this);" name="frm_registrarPresupuesto" method="post" action="frm_agregarPresupuesto.php">
					<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
						<tr>
							<td width="128">
								<div align="right">*Ubicaci&oacute;n</div>
							</td>
							<td width="57">
								<select name="cmb_ubicacion" id="cmb_ubicacion" size="1" class="combo_box" required="required" 
								onchange="document.getElementById('txt_ubicacion').value=this.options[this.selectedIndex].value; 
										  verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,txt_ubicacion.value);">
									<option value="">Ubicaci&oacute;n</option>
									<?php
									$cmb_ubicacion="";				
									$conn = conecta("bd_gerencia");
									$result = mysql_query ("SELECT T1.id_control_costos, T2.descripcion
															FROM  `presupuesto` AS T1
															JOIN bd_recursos.control_costos AS T2
															USING ( id_control_costos ) 
															WHERE T2.habilitado =  'SI'
															GROUP BY T2.descripcion
															ORDER BY T2.descripcion");				 
									while ($row=mysql_fetch_array($result)){
										echo "<option value='$row[id_control_costos]'>$row[descripcion]</option>";
									}
									mysql_close($conn);
									?>
								</select>
							</td>
							<td width="231">
								<div align="right">
									<input type="checkbox" name="ckb_nuevaUbicacion" id="ckb_nuevaUbicacion" 
									onclick="agregarAreaCuadrilla(this, document.getElementById('cmb_nuevaUbicacion'), document.getElementById('cmb_ubicacion'));" 
									title="Activar seleccion de nuevas ubicaciones" />
									Agregar Ubicaci&oacute;n
								</div>
							</td>
							<td width="258" colspan="2">
								<select name="cmb_nuevaUbicacion" id="cmb_nuevaUbicacion" size="1" class="combo_box" disabled=true 
								onchange="document.getElementById('txt_ubicacion').value=this.options[this.selectedIndex].value;
										  verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,txt_ubicacion.value);">
									<option value="">Ubicaci&oacute;n</option>
									<?php
									$conn = conecta("bd_recursos");
									$result=mysql_query("SELECT * FROM control_costos WHERE habilitado = 'SI' ORDER BY descripcion");				 
									while ($row=mysql_fetch_array($result)){
										echo "<option value='$row[id_control_costos]'>$row[descripcion]</option>";
									} 				
									mysql_close($conn);
									?>
								</select>
								<input type="hidden" id="txt_ubicacion" name="txt_ubicacion" value=""/>
							</td>
						</tr>
						<tr>
							<td width="164">
								<div align="right">Fecha Inicio</div>
							</td>
							<td width="115">
								<input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y"); ?>" 
								onchange="sumarDiasMes(); calcularDomingos(); verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,txt_ubicacion.value);"
								readonly="readonly" />
							</td>
							<td width="182" colspan="">
								<div align="right">D&iacute;as Laborables</div>
							</td>
							<td colspan="3">
								<input name="txt_diasLaborales" type="text" class="caja_de_texto" id="txt_diasLaborales" onchange="calcularPptoDiario(); formatCero();" 
								value="" size="3" maxlength="3" onkeypress="return permite(event,'num',3);" readonly="readonly"/>
							</td>
						</tr>
						<tr>
							<td width="164">
								<div align="right">Fecha Fin</div>
							</td>
							<td>
								<input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y", strtotime("+1 month")); ?>" readonly="readonly" 
								onchange="if(calcularDomingos()){ verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,txt_ubicacion.value); }" />
							</td>
							<td width="182">
								<div align="right">Domingos</div>
							</td>
							<td colspan="3">
								<input type="text" class="caja_de_texto" value="" name="txt_domingos" id="txt_domingos" size="4" readonly="readonly"/>
							</td>
						</tr>
						<tr>
							<td width="164">
								<div align="right">*Volumen Presupuestado</div>
							</td>
							<td>
								<input type="text" name="txt_volPresupuestado" id="txt_volPresupuestado" maxlength="10" size="10" class="caja_de_texto" required="required"
								onkeypress="return permite(event,'num',2);" value="" onchange="formatCurrency(this.value,'txt_volPresupuestado'); calcularPptoDiario(); 
								verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,txt_ubicacion.value);" />m&sup3;
							</td>
							<td>
								<div align="right">*Volumen Diario</div>
							</td>
							<td colspan="3">
								<input type="text" name="txt_presupuestoDiario" id="txt_presupuestoDiario" value="" maxlength="10" size="10" class="caja_de_texto"
								onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_presupuestoDiario')" required="required"/>
							</td>
						</tr>
						<tr>
							<td colspan="5">
								<strong>* Datos marcados con asterisco son <u>obligatorios</u></strong>
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<div align="center">
									<input type="hidden" name="hdn_fechas" id="hdn_fechas" value="0"/>
									<input type="hidden" name="hdn_band" id="hdn_band" value="si"/>
									<input type="hidden" name="hdn_claveDefinida" id="hdn_claveDefinida" value=""/>
									<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Presupuesto Mensual" 
									onmouseover="window.status='';return true"/>
									&nbsp;&nbsp;&nbsp;
									<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute;  Planeación" 
									onMouseOver="window.status='';return true" onclick="location.href='menu_presupuesto.php';" />
								</div>
							</td>
						</tr>
					</table>
				</form>
			</fieldset>
			
			<div id="calendario-Ini">
				<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_registrarPresupuesto.txt_fechaIni,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha de Inicio"/> 
			</div>
			
			<div id="calendario-Fin">
				<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_registrarPresupuesto.txt_fechaFin,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha de Fin"/>
			</div>
			
		</body>
	<?php 
	}
	?>
</html>
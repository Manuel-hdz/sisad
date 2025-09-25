<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	include ("../seguridad.php"); 
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include ("head_menu.php");
		include ("op_agregarCuadrilla.php");?>
		
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
			<script type="text/javascript" src="includes/ajax/calcularID.js" ></script>
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			
			<style type="text/css">
				<!--
				#titulo-registrar {position:absolute; left:30px; top:146px; width:298px; height:20px; z-index:11;}
				#tabla-agregarCuadrilla {position:absolute;left:30px;top:190px;width:742px;height:230px;z-index:14;}
				-->
			</style>
		</head>
		
		<body>
			<?php 
			if(isset($_SESSION["cuadrilla"])){
				unset($_SESSION["cuadrilla"]);
				if (isset($_SESSION["personalCuadrilla"]))
					unset($_SESSION["personalCuadrilla"]);
			}
			?>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-registrar">Agregar Cuadrilla </div>
			
			<fieldset class="borde_seccion" id="tabla-agregarCuadrilla" name="tabla-agregarCuadrilla">
				<legend class="titulo_etiqueta">Ingresar Informaci&oacute;n de la Cuadrilla</legend>
				<form name="frm_agregarCuadrilla" method="post" action="frm_agregarCuadrilla2.php" onsubmit="return valFormCuadrilla(this)">
					<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
						<tr>
							<td width="100px"> 
								<div align="right">ID Cuadrilla</div>
							</td>
							<td colspan="3">
								<input name="txt_IDCuadrilla" id="txt_IDCuadrilla" type="text" class="caja_de_texto" size="15" value="" readonly="readonly"/>
							</td>
						</tr>
						<tr>
							<td width="128">
								<div align="right">*Ubicaci&oacute;n</div>
							</td>
							<td width="57">
								<select name="cmb_ubicacion" id="cmb_ubicacion" size="1" class="combo_box" 
								onchange="obtenerIdCuadrilla(this,document.getElementById('txt_IDCuadrilla')); document.getElementById('txt_ubicacion').value=this.options[this.selectedIndex].text" required="required">
									<option value="">Ubicaci&oacute;n</option>
									<?php
									$cmb_ubicacion="";				
									$conn = conecta("bd_gerencia");
									$result = mysql_query ("SELECT T2 . * 
															FROM cuadrillas AS T1
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
								<select name="cmb_nuevaUbicacion" id="cmb_nuevaUbicacion" size="1" class="combo_box" 
								onchange="obtenerIdCuadrilla(this,document.getElementById('txt_IDCuadrilla')); document.getElementById('txt_ubicacion').value=this.options[this.selectedIndex].text" disabled=true>
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
							<td width="128">
								<div align="right">Comentarios</div>
							</td>
							<td>
								<textarea name="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" cols="30" rows="4" class="caja_de_texto" 
								id="txa_comentarios" style="resize: none;" onkeypress="return permite(event,'num_car',0);"></textarea>
							</td>
							<td align="right">
								*Aplicaci&oacute;n
							</td>
							<td>
								<input type="checkbox" name="ckb_zarpeoViaSeca" id="ckb_zarpeoViaSeca" value="ZARPEO VIA SECA" />Zarpeo V&iacute;a Seca
								<br />
								<input type="checkbox" name="ckb_zarpeoViaHumeda" id="ckb_zarpeoViaHumeda" value="ZARPEO VIA HUMEDA" />Zarpeo V&iacute;a H&uacute;meda
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
									<input name="sbt_continuar" type="submit" class="botones" value="Continuar" title="Continuar a Guardar el Personal de la Cuadrilla" 
									onmouseover="window.status='';return true"/>
									&nbsp;&nbsp;&nbsp;
									<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Cuadrillas" 
									onMouseOver="window.status='';return true" onclick="location.href='menu_cuadrillas.php';" />
								</div>
							</td>
						</tr>
					</table>
				</form>
			</fieldset>
		</body>
	<?php
	}
	?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de la Unidad de Salud Ocupacional
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	} else {
		include ("head_menu.php");?>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<script type="text/javascript" src="../../includes/validacionClinica.js"></script>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>
			<script type="text/javascript" src="../../includes/maxLength.js"></script>
			<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
			<script language="javascript" type="text/javascript" src="../../includes/formatoNumeros.js"></script>
			<?php 
			include_once("../../includes/func_fechas.php");
			?>
			<style type="text/css">
				<!--
				#titulo-registrar {position:absolute;left:30px;top:146px;	width:298px;height:20px;z-index:11;}
				#titulo-agregar-registros { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
				#tabla-agregarRegistro {position:absolute;left:30px;top:180px;width:912px;height:480px;z-index:12;}
				#botonesModificar { position:absolute; left:30px; top:630px; width:945px; height:40px; z-index:25;}
				#tabla-mostrarRegistros {position:absolute;left:30px;top:220;width:715px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
				#img-imc {position:absolute;left:497px;top:106px;width:492px;height:271px;z-index:14;}
				.Estilo1 {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 12px;
				}
				-->
			</style>
			<body>
				<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
				<div class="titulo_barra" id="titulo-registrar">Modificar Antecedentes Familiares</div>
				
				<form method="post" name="frm_modificarHistorialFamiliar" id="frm_modificarHistorialFamiliar" action="frm_consultarHistorialClinico2.php">
					<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
						<?php
						$claveSecc=explode(".",$_POST['rdb_id']);
						$clave=$claveSecc[0];
						$conn = conecta("bd_clinica");
						$stm_sql = "SELECT * 
									FROM  `antecedentes_fam` 
									WHERE  `historial_clinico_id_historial` LIKE  '$clave'";
						$rs = mysql_query($stm_sql);
						if($datos = mysql_fetch_array($rs)){
							$existe = true;
						} else {
							$existe = false;
						}
						?>
						
						<legend class="titulo_etiqueta">Ingresar el Historial Familiar del Trabajador</legend>
						<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
						<input type="hidden" name="tipo" value="<?php echo $_POST['tipo']; ?>"/>
						<input type="hidden" name="existe" value="<?php echo $existe ?>"/>
						<table cellpadding="2" cellspacing="2" class="tabla_frm">
							<tr>
								<td width="63"><div align="right">*Peso</div></td>
								<td width="60">
									<input type="text" name="txt_peso" id="txt_peso" value="<?php if($datos['peso_kg'] != '') echo $datos['peso_kg']; ?>" 
									onKeyPress="return permite(event,'num',2);" class="caja_de_num"  onchange="calcularIMC();" size="5" required="required"/>			
								</td>
								<td width="260"><div align="right">*Talla</div></td>
								<td width="497">
									<input type="text" name="txt_talla" id="txt_talla" value="<?php if($datos['talla_mts'] != '') echo $datos['talla_mts']; ?>" size="10" 
									onKeyPress="return permite(event,'num',2);" class="caja_de_num" onchange="calcularIMC();" required="required"/>
								</td>
							</tr>
							<tr>
								<td colspan="2"><div align="right">Diam A.P.</div></td>
								<td>
									<input name="txt_diamAP" type="text" class="caja_de_num" id="txt_diamAP" onKeyPress="return permite(event,'num',2);" 
									value="<?php if($datos['torax_diam_ap'] != '') echo $datos['torax_diam_ap']; ?>" size="5" maxlength="5"/>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*Historial Familiar
								</td>
								<td>
									<textarea name="txa_hisFam" id="txa_hisFam" onKeyUp="return ismaxlength(this)" class="caja_de_texto" rows="3" cols="80" 
									onKeyPress="return permite(event,'num_car', 0);" required="required" style="resize: initial;"><?php 
										if($datos['historia_familiar'] != '')
											echo $datos['historia_familiar'];
										else 
											echo "PADRE DE AÑOS, MADRE DE AÑOS, HEMANOS, HIJOS, ESPOSA DE AÑOS, APARENTEMENTE SANOS";
										?>
									</textarea>
								</td>
							</tr>
							<tr>
								<td colspan="2"><div align="right">Diam LAT.</div></td>
								<td>
									<input name="txt_diamLAT" type="text" class="caja_de_num" id="txt_diamLAT" onKeyPress="return permite(event,'num',2);" 
									value="<?php if($datos['torax_diam_lat'] != '') echo $datos['torax_diam_lat']; ?>" size="5" maxlength="5"/>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Antecedetes
								</td>
								<td colspan="2">
									<textarea name="txa_ant" id="txa_ant" onKeyUp="return ismaxlength(this)" class="caja_de_texto" rows="3" style="resize: initial;"
									cols="80" onKeyPress="return permite(event,'num_car', 0);" ><?php if($datos['antecedentes'] != '') echo $datos['antecedentes']; ?></textarea>
								</td>
							</tr>
							<tr>
								<td colspan="2"><div align="right">Circ. EXP</div></td>
								<td>
									<input name="txt_circEXP" type="text" class="caja_de_num" id="txt_circEXP" onKeyPress="return permite(event,'num',2);" 
									value="<?php if($datos['torax_circ_exp'] != '') echo $datos['torax_circ_exp']; ?>" size="5" maxlength="5"/>
									&nbsp;&nbsp;&nbsp;&nbsp;*Historial Medica Ant
								</td>
								<td>
									<textarea name="txa_hisMedicaAnt" id="txa_hisMedicaAnt" onKeyUp="return ismaxlength(this)" class="caja_de_texto" 
									rows="3" cols="80" onKeyPress="return permite(event,'num_car', 0);" required="required" style="resize: initial;"><?php 
										if($datos['historia_medica_ant'] != '') 
											echo $datos['historia_medica_ant']; 
										else 
											echo "NIEGA ENFERMEDADES CRONICODEGENERATIVAS, ALERGICOS, QUIRURGICOS Y FRACTURAS, HERNIAS, TUBERCULOSIS, HEPATITIS, TABAQUISMO, ALCOHOLISMO, NIEGA DROGAS DURAS."; ?>
									</textarea>
								</td>
							</tr>
							<tr>
								<td colspan="2"><div align="right">Circ. INSP</div></td>
								<td>
									<input name="txt_circINSP" type="text" class="caja_de_num" id="txt_circINSP" onKeyPress="return permite(event,'num',2);" 
									value="<?php if($datos['torax_circ_insp'] != '') echo $datos['torax_circ_insp']; ?>" size="5" maxlength="5"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Pulso</div></td>
								<td>
									<input name="txt_pulso" type="text" class="caja_de_num" id="txt_pulso" onKeyPress="return permite(event,'num',2);" 
									value="<?php if($datos['pulso'] != '') echo $datos['pulso']; ?>" size="10" maxlength="11" required="required"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Resp</div></td>
								<td>
									<input name="txt_resp" type="text" class="caja_de_num" id="txt_resp" onKeyPress="return permite(event,'num',2);" 
									value="<?php if($datos['respiracion'] != '') echo $datos['respiracion']; ?>" size="10" maxlength="11" required="required"/>
								</td>
								<td><div align="right">*Antecedentes P.P.</div></td>
								<td rowspan="2">
									<textarea name="txa_antPP" id="txa_antPP" onKeyUp="return ismaxlength(this)" class="caja_de_texto" rows="3" style="resize: initial;"
									cols="70" onKeyPress="return permite(event,'num_car', 0);" required="required"><?php if($datos['antecedentes_pp'] != '') echo $datos['antecedentes_pp']; ?></textarea>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Temp</div></td>
								<td>
									<input name="txt_temp" type="text" class="caja_de_num" id="txt_temp" onKeyPress="return permite(event,'num',2);" 
									value="<?php if($datos['temp'] != '') echo $datos['temp']; ?>" size="10" maxlength="11" required="required"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Pres Art.</div></td>
								<td>
									<input name="txt_presArt" type="text" class="caja_de_num" id="txt_presArt" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['pres_arterial'] != '') echo $datos['pres_arterial']; ?>" size="10" maxlength="11" required="required"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*IMC</div></td>
								<td>
									<input type="text" name="txt_imc" id="txt_imc" value="<?php if($datos['imc'] != '') echo $datos['imc']; ?>" 
									size="10" class="caja_de_num" readonly="readonly" required="required"/>
								</td>
								<td><div align="right">*Enf. Prof. y / o Secuelas</div></td>
								<td>
									<input name="txt_secuelas" type="text" class="caja_de_texto" id="txt_secuelas" onKeyPress="return permite(event,'num_car',0);" 
									value="<?php if($datos['enf_prof_secuelas'] != '') echo $datos['enf_prof_secuelas']; ?>" size="70" maxlength="200" required="required"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*%SpO&sup2;</div></td>
								<td>
									<input name="txt_spo2" type="text" class="caja_de_num"  id="txt_spo2" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['spo2'] != '') echo $datos['spo2']; ?>" size="10" maxlength="10" required="required"/>
								</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="4">
									<div align="center">
										<input name="sbt_guardarHisFam" type="submit" class="botones" id= "sbt_guardarHisFam" value="Guardar" 
										title="Guardar los Registros de los Antecedentes Familiares" onMouseOver="window.status='';return true" />
										&nbsp;&nbsp;&nbsp;
										<input name="sbt_guiaIMC" type="button" class="botones" id= "sbt_guiaIMC" value="Guía IMC" 
										title="Guardar los Registros de los Antecedentes Familiares" onMouseOver="window.status='';return true" 
										onClick="javascript:window.open('images/clasificacion_imc.png','_blank','top=0, left=0, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes,toolbar=no, location=no,directories=no');"/>
										&nbsp;&nbsp;&nbsp;
										<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
									</div>
								</td>
							</tr>
						</table>
					</fieldset>
				</form>
				<div id="botonesModificar" align="center">
					<form action="frm_consultarHistorialClinico2.php" name="frm_regresar" method="post">
						<?php
						$claveSecc=explode(".",$_POST['rdb_id']);
						$clave=$claveSecc[0];
						?>
						<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
						<input type="submit" class="botones" value="Regresar" title="Modificar al Historial Clinico <?php echo $clave; ?>" onMouseOver="window.status='';return true"/>
					</form>
				</div>
			</body>
		</head>
	<?php
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de la Unidad de Salud Ocupacional
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
				#tabla-agregarRegistro {position:absolute; left:30px; top:180px; width:900px; height:580px; z-index:12;}
				#botonesModificar { position:absolute; left:30px; top:730px; width:930px; height:40px; z-index:25;}
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
				<div class="titulo_barra" id="titulo-registrar">Modificar Prueba de Laboratorio</div>
				
				<form method="post" name="frm_modificarHistorialFamiliar" id="frm_modificarHistorialFamiliar" action="frm_consultarHistorialClinico2.php">
					<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
						<?php
						$claveSecc=explode(".",$_POST['rdb_id']);
						$clave=$claveSecc[0];
						$conn = conecta("bd_clinica");
						$stm_sql = "SELECT * 
									FROM  `laboratorio` 
									WHERE  `historial_clinico_id_historial` LIKE  '$clave'";
						$rs = mysql_query($stm_sql);
						if($datos = mysql_fetch_array($rs)){
							$existe = true;
						} else {
							$existe = false;
						}
						?>
						<legend class="titulo_etiqueta">Ingresar los Datos de Prueba de Laboratorio</legend>
						<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
						<input type="hidden" name="tipo" value="<?php echo $_POST['tipo']; ?>"/>
						<input type="hidden" name="existe" value="<?php echo $existe ?>"/>
						
						<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
							<tr>
								<td><div align="right">VDRL</div></td>
								<td>
									<input name="txt_vdrl" type="text" class="caja_de_num" id="txt_vdrl"  onKeyPress="return permite(event,'num_car',8);" 
									size="15" maxlength="15" value="<?php if($datos['vdrl']!='') echo $datos['vdrl']; ?>"/>
								</td>
								<td><div align="right">B.H.</div></td>
								<td>
									<input name="txt_bh" type="text" class="caja_de_texto" id="txt_bh"  onKeyPress="return permite(event,'num_car',8);" size="15" 
									maxlength="15" value="<?php if($datos['bh']!='') echo $datos['bh']; ?>"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Glicemia</div></td>
								<td>
									<input name="txt_glicemia" type="text" class="caja_de_texto" id="txt_glicemia"  onKeyPress="return permite(event,'num_car',8);" 
									size="10" maxlength="10" required="required" value="<?php if($datos['glicemia']!='') echo $datos['glicemia']; ?>"/>
								</td>
								<td><div align="right">PIE</div></td>
								<td>
									<input name="txt_pie" type="text" class="caja_de_texto" id="txt_pie" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['pie']!='') echo $datos['pie']; ?>" size="15" maxlength="15"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">Gral. Orina</div></td>
								<td>
									<input name="txt_gralOrina" type="text" class="caja_de_texto" id="txt_gralOrina" onKeyPress="return permite(event,'num_car',8);" 
									size="15" maxlength="15" value="<?php if($datos['gral_orina']!='') echo $datos['gral_orina']; ?>"/>
								</td>
								<td><div align="right">PB en Sang</div></td>
								<td>
									<input name="txt_pbSang" type="text" class="caja_de_texto" id="txt_pbSang" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['pb_sang']!='') echo $datos['pb_sang']; ?>" size="15" maxlength="15"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">HIV</div></td>
								<td>
									<input name="txt_hiv" type="text" class="caja_de_texto" id="txt_hiv" onKeyPress="return permite(event,'num_car',8);" 
									size="15" maxlength="15" value="<?php if($datos['hiv']!='') echo $datos['hiv']; ?>"/>
								</td>
								<td><div align="right">Cadmio</div></td>
								<td colspan="4">
									<input name="txt_cadmio" type="text" class="caja_de_texto" id="txt_cadmio" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['cadmio']!='') echo $datos['cadmio']; ?>" size="15" maxlength="15" />
								</td>
							</tr>
							<tr>
								<td><div align="right">Fosfata &Aacute;cida</div></td>
								<td>
									<input name="txt_fosAcida" type="text" class="caja_de_texto" id="txt_fosAcida" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['fosfata_acida']!='') echo $datos['fosfata_acida']; ?>" size="15" maxlength="15" />
								</td>
								<td><div align="right">*TG</div></td>
								<td>
									<input name="txt_tg" type="text" class="caja_de_texto" id="txt_tg"   onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['tg']!='') echo $datos['tg']; ?>" size="10" maxlength="10" required="required"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">Fosfata Alcalina</div></td>
								<td>
									<input name="txt_fosAlcalina" type="text" class="caja_de_texto" id="txt_fosAlcalina"   onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['fosfata_alcalina']!='') echo $datos['fosfata_alcalina']; ?>" size="15" maxlength="15" />
								</td>
								<td><div align="right">*Colesterol</div></td>
								<td>
									<input name="txt_colesterol" type="text" class="caja_de_texto" id="txt_colesterol" required="required" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['colesterol']!='') echo $datos['colesterol']; ?>" size="10" maxlength="10" align="absmiddle"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">Espirometria</div></td>
								<td>
									<input name="txt_espirometria" type="text" class="caja_de_texto" id="txt_espirometria"   onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['espirometria']!='') echo $datos['espirometria']; ?>" size="25" maxlength="60" />
								</td>
								<td><div align="right">Tipo Sanguineo</div></td>
								<td>
									<input name="txt_tipoSanguineo" type="text" class="caja_de_texto" id="txt_tipoSanguineo" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['tipo_sanguineo']!='') echo $datos['tipo_sanguineo']; ?>" size="10" maxlength="10" align="absmiddle"/>
								</td>
								<td><div align="right">B Mglobulin</div></td>
								<td>
									<input name="txt_bMglobulin" type="text" class="caja_de_texto" id="txt_bMglobulin" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['b_mglobulin']!='') echo $datos['b_mglobulin']; ?>" size="15" maxlength="15"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">FCR</div></td>
								<td>
									<input name="txt_fcr" type="text" class="caja_de_texto" id="txt_fcr"   onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['fcr']!='') echo $datos['fcr']; ?>" size="25" maxlength="40" />
								</td>
								<td><div align="right">*Diag. Laboratorio</div></td>
								<td>
									<input name="txt_diagLab" type="text" class="caja_de_texto" id="txt_diagLab" required="required" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['diag_laboratorio']!='') echo $datos['diag_laboratorio']; ?>" size="30" maxlength="60" align="absmiddle"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Rx. T&oacute;rax</div></td>
								<td>
									<input name="txt_rxTorax" type="text" class="caja_de_texto" id="txt_rxTorax" onKeyPress="return permite(event,'num_car',8);" 
									size="25" maxlength="40" required="required" value="<?php if($datos['rx_torax']!='') echo $datos['rx_torax']; ?>"/>
								</td>
								<td><div align="right">*Alcohol&iacute;metro</div></td>
								<td colspan="4">
									<input name="txt_alcoholimetro" type="text" class="caja_de_texto" id="txt_alcoholimetro" onKeyPress="return permite(event,'num_car',8);" 
									size="25" maxlength="40" required="required" value="<?php if($datos['alcoholimetro']!='') echo $datos['alcoholimetro']; ?>"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">% Silicosis</div></td>
								<td>
									<input name="txt_silicosis" type="text" class="caja_de_texto" id="txt_silicosis" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['porcentaje_silicosis']!='') echo $datos['porcentaje_silicosis']; ?>" size="10" maxlength="10" />
								</td>
								<td><div align="right">Fracc.</div></td>
								<td colspan="4">
									<input name="txt_fracc" type="text" class="caja_de_texto" id="txt_fracc" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['fracc']!='') echo $datos['fracc']; ?>" size="10" maxlength="10" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Col. Lumbosacra</div></td>
								<td>
									<input name="txt_colLum" type="text" class="caja_de_texto" id="txt_colLum" onKeyPress="return permite(event,'num_car',8);" 
									size="25" maxlength="300" required="required" value="<?php if($datos['col_lumbrosaca']!='') echo $datos['col_lumbrosaca']; ?>"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">Romberg</div></td>
								<td>
									<input name="txt_romberg" type="text" class="caja_de_texto" id="txt_romberg" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['romberg']!='') echo $datos['romberg']; ?>" size="25" maxlength="40" />
								</td>
								<td><div align="right">Babinsky Weil</div></td>
								<td colspan="4">
									<input name="txt_weil" type="text" class="caja_de_texto" id="txt_weil" onKeyPress="return permite(event,'num_car',8);" 
									size="25" maxlength="40" value="<?php if($datos['babinsky_weil']!='') echo $datos['babinsky_weil']; ?>"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Diagnostico</div></td>
								<td colspan="4">
									<input name="txt_diagnostico" type="text" class="caja_de_texto" id="txt_diagnostico" onKeyPress="return permite(event,'num_car',8);" 
									size="60" maxlength="300" required="required" value="<?php if($datos['diagnostico']!='') echo $datos['diagnostico']; ?>"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Conclusiones</div></td>
								<td colspan="4">
									<input name="txt_conclusiones" type="text" class="caja_de_texto" id="txt_conclusiones" onKeyPress="return permite(event,'num_car',8);" 
									size="60" maxlength="300" required="required" value="<?php if($datos['conclusiones']!='') echo $datos['conclusiones']; ?>"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Edo. Salud</div></td>
								<td colspan="4">
									<input name="txt_edoSalud" type="text" class="caja_de_texto" id="txt_edoSalud" onKeyPress="return permite(event,'num_car',8);"  
									size="40" maxlength="40" required="required" value="<?php if($datos['edo_salud']!='') echo $datos['edo_salud']; ?>"/>
								</td>
							</tr>
							<tr>
								<td colspan="8">
									<div align="center">
										<input name="sbt_guardarHisFam" type="submit" class="botones" id= "sbt_guardarHisFam" value="Guardar" 
										title="Guardar los Registros de Aspectos Generales 2" onMouseOver="window.status='';return true" />
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
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
				#tabla-agregarRegistro {position:absolute;left:30px;top:180px;width:912px;height:390px;z-index:12;}
				#botonesModificar { position:absolute; left:30px; top:530px; width:945px; height:40px; z-index:25;}
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
				<div class="titulo_barra" id="titulo-registrar">Modificar Aspectos Generales 1</div>
				
				<form method="post" name="frm_modificarHistorialFamiliar" id="frm_modificarHistorialFamiliar" action="frm_consultarHistorialClinico2.php">
					<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
						<?php
						$claveSecc=explode(".",$_POST['rdb_id']);
						$clave=$claveSecc[0];
						$conn = conecta("bd_clinica");
						$stm_sql = "SELECT * 
									FROM  `aspectos_grales_1` 
									WHERE  `historial_clinico_id_historial` LIKE  '$clave'";
						$rs = mysql_query($stm_sql);
						if($datos = mysql_fetch_array($rs)){
							$existe = true;
						} else {
							$existe = false;
						}
						?>
						<legend class="titulo_etiqueta">Ingresar los Aspectos Generales del Trabajador /1</legend>
						<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
						<input type="hidden" name="tipo" value="<?php echo $_POST['tipo']; ?>"/>
						<input type="hidden" name="existe" value="<?php echo $existe ?>"/>
						
						<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
							<tr>
								<td><div align="right">*Tipo</div></td>
								<td>
									<input name="txt_tipoGral" type="text" class="caja_de_texto" id="txt_tipoGral"  onKeyPress="return permite(event,'num_car',8);" 
									size="15" maxlength="20" required="required" value="<?php 
										if($datos['tipo_gral'] != '')
											echo $datos['tipo_gral'];
										else 
											echo "NORMOLINEO";
										?>" />
								</td>
								<td><div align="right">*Nutrici&oacute;n</div></td>
								<td>
									<input name="txt_nutricion" type="text" class="caja_de_texto" id="txt_nutricion"  onKeyPress="return permite(event,'num_car',8);" size="10" 
									maxlength="15" required="required" value="<?php 
										if($datos['nutricion'] != '')
											echo $datos['nutricion'];
										else 
											echo "REGULAR";
										?>" />
								</td>
								<td><div align="right">*Piel</div></td>
								<td>
									<input name="txt_piel" type="text" class="caja_de_texto" id="txt_piel" onKeyPress="return permite(event,'num_car',8);" size="10" maxlength="15" required="required" value="<?php 
										if($datos['piel'] != '')
											echo $datos['piel'];
										else 
											echo "NORMAL";
										?>" />
								</td>
								<td><div align="right">*Lentes</div></td>
								<td>
									<select name="cmb_lentes" class="combo_box" id="cmb_lentes" required="required">
										<option value="" selected="selected">Lentes</option>
										<option <?php if($datos['lentes'] == 'SI') echo "selected"; ?> value="SI">SI</option>
										<option <?php if($datos['lentes'] == 'NO') echo "selected"; ?> value="NO">NO</option>
									</select>
								</td>
							</tr>
							<tr>
								<td rowspan="4"><div align="right"><strong>*OJOS</strong></div></td>	
								<td height="37">&nbsp;</td>
								<td><div align="center">DER</div></td>
								<td><div align="center">*IZQ</div></td>
								<td>&nbsp;</td>
								<td><div align="center">*DER</div></td>
								<td><div align="center">*IZQ</div></td>
							</tr>
							<tr>
								<td><div align="right">*Visi&oacute;n</div></td>
								<td>
									<input name="txt_visionDer" type="text" class="caja_de_num" id="txt_visionDer" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['ojo_der_vision'] != '') echo $datos['ojo_der_vision']; ?>" size="10" maxlength="10" required="required"/>
								</td>
								<td>
									<input name="txt_visionIzq" type="text" class="caja_de_num" id="txt_visionIzq" required="required" onKeyPress="return permite(event,'num_car',8);" 
									value="<?php if($datos['ojo_izq_vision'] != '') echo $datos['ojo_izq_vision']; ?>" size="10" maxlength="10" align="absmiddle"/>
								</td>
								<td><div align="right">*Reflejos</div></td>
								<td>
									<input name="txt_refDer" type="text" class="caja_de_texto" id="txt_refDer" required="required"
									onKeyPress="return permite(event,'num_car',1);" size="10" maxlength="10" value="<?php 
										if($datos['ojo_der_reflejos'] != '')
											echo $datos['ojo_der_reflejos'];
										else 
											echo "NORMALES";
										?>" />
								</td>
								<td>
									<input name="txt_refIzq" type="text" class="caja_de_texto" id="txt_refIzq" required="required" 
									onKeyPress="return permite(event,'num_car',1);" size="10" maxlength="10" value="<?php 
										if($datos['ojo_izq_reflejos'] != '')
											echo $datos['ojo_izq_reflejos'];
										else 
											echo "NORMALES";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Pterygiones</div></td>
								<td>
									<input name="txt_pterDer" type="text" class="caja_de_texto" id="txt_pterDer"  onKeyPress="return permite(event,'num_car',1);" 
									size="10" maxlength="10" required="required" value="<?php 
										if($datos['ojo_der_pterygiones'] != '')
											echo $datos['ojo_der_pterygiones'];
										else 
											echo "NEGATIVO";
										?>" />
								</td>
								<td>
									<input name="txt_pterIzq" type="text" class="caja_de_texto" id="txt_pterIzq" required="required" 
									onKeyPress="return permite(event,'num_car',1);" size="10" maxlength="10" value="<?php 
										if($datos['ojo_izq_pterygiones'] != '')
											echo $datos['ojo_izq_pterygiones'];
										else 
											echo "NEGATIVO";
										?>" />
								</td>
								<td><div align="right">*Otros</div></td>
								<td>
									<input name="txt_otrosDer" type="text" class="caja_de_texto" id="txt_otrosDer" required="required" 
									onKeyPress="return permite(event,'num_car',1);" size="10" maxlength="10" value="<?php 
										if($datos['ojo_der_otros'] != '')
											echo $datos['ojo_der_otros'];
										else 
											echo "NO";
										?>" />
								</td>
								<td>
									<input name="txt_otrosIzq" type="text" class="caja_de_texto" id="txt_otrosIzq" required="required" 
									onKeyPress="return permite(event,'num_car',1);" size="10" maxlength="10" value="<?php 
										if($datos['ojo_izq_otros'] != '')
											echo $datos['ojo_izq_otros'];
										else 
											echo "NO";
										?>" />
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>												
							</tr>
							<tr>
								<td rowspan="4"><div align="right"><strong>*OIDOS</strong></div></td>
							</tr>
							<tr>
								<td><div align="right">*Audici&oacute;n</div></td>
								<td>
									<input name="txt_audDer" type="text" class="caja_de_num" id="txt_audDer"  onKeyPress="return permite(event,'num',2);"
									size="10" maxlength="10" required="required" value="<?php if($datos['oido_der_audicion'] != '') echo $datos['oido_der_audicion']; ?>" />%
								</td>
								<td>
									<input name="txt_audIzq" type="text" class="caja_de_num" id="txt_audIzq" onKeyPress="return permite(event,'num',2);" 
									size="10" maxlength="10" required="required" value="<?php if($datos['oido_izq_audicion'] != '') echo $datos['oido_izq_audicion']; ?>" />%
								</td>
								<td><div align="right">*Canal</div></td>
								<td>
									<input name="txt_canalDer" type="text" class="caja_de_texto" id="txt_canalDer" required="required" 
									onKeyPress="return permite(event,'num_car',1);" size="10" maxlength="10" value="<?php 
										if($datos['oido_der_canal'] != '')
											echo $datos['oido_der_canal'];
										else 
											echo "LIBRES";
										?>" />
								</td>
								<td>
									<input name="txt_canalIzq" type="text" class="caja_de_texto" id="txt_canalIzq" required="required" 
									onKeyPress="return permite(event,'num_car',1);" size="10" maxlength="10" value="<?php 
										if($datos['oido_izq_canal'] != '')
											echo $datos['oido_izq_canal'];
										else 
											echo "LIBRES";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Membrana</div></td>
								<td>
									<input name="txt_memDer" type="text" class="caja_de_texto" id="txt_memDer" required="required" 
									onKeyPress="return permite(event,'num_car',1);" size="10" maxlength="15" value="<?php 
										if($datos['membrana_der'] != '')
											echo $datos['membrana_der'];
										else 
											echo "INTEGRA";
										?>" />
								</td>
								<td>
									<input name="txt_memIzq" type="text" class="caja_de_texto" id="txt_memIzq" required="required" 
									onKeyPress="return permite(event,'num_car',1);" size="10" maxlength="15" value="<?php 
										if($datos['membrana_izq'] != '')
											echo $datos['membrana_izq'];
										else 
											echo "INTEGRA";
										?>" />
								</td>
								<tr>
									<td><div align="right">*HBC</div></td>
									<td>
										<input name="txt_hbc" type="text" class="caja_de_num" id="txt_hbc" size="5" maxlength="5" required="required" 
										value="<?php if($datos['porciento_hbc'] != '') echo $datos['porciento_hbc']; ?>" />%
									</td>
									<td><div align="right">*Tipo</div></td>
									<td>
										<select name="cmb_tipo" class="combo_box" id="cmb_tipo" required="required">
											<option value="" selected="selected">Tipo</option>
											<option <?php if($datos['tipo'] == 'NORMAL') echo "selected"; ?> value="NORMAL">NORMAL</option>
											<option <?php if($datos['tipo'] == 'SI PROFESIONAL') echo "selected"; ?> value="SI PROFESIONAL">SI PROFESIONAL</option>
											<option <?php if($datos['tipo'] == 'NO PROFESIONAL') echo "selected"; ?> value="NO PROFESIONAL">NO PROFESIONAL</option>
											<option <?php if($datos['tipo'] == 'MIXTA') echo "selected"; ?> value="MIXTA">MIXTA</option>
										</select>
									</td>
									<td><div align="right">*% IPP</div></td>
									<td>
										<input name="txt_ipp" type="text" class="caja_de_num" id="txt_ipp" onKeyPress="return permite(event,'num',2);" 
										value="<?php if($datos['porciento_ipp'] != '') echo $datos['porciento_ipp']; else echo "0"; ?>"size="5" maxlength="5" required="required"/>%
									</td>
								</tr>
								<tr>
									<td colspan="8">
										<div align="center">
											<input name="sbt_guardarHisFam" type="submit" class="botones" id= "sbt_guardarHisFam" value="Guardar" 
											title="Guardar los Registros de Aspectos Generales 1" onMouseOver="window.status='';return true" />
											&nbsp;&nbsp;&nbsp;
											<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
										</div>
									</td>
								</tr>
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
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
				#tabla-agregarRegistro {position:absolute;left:30px;top:180px;width:912px;height:600px;z-index:12;}
				#botonesModificar { position:absolute; left:30px; top:750px; width:990px; height:40px; z-index:25;}
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
				<div class="titulo_barra" id="titulo-registrar">Modificar Aspectos Generales 2</div>
				
				<form method="post" name="frm_modificarHistorialFamiliar" id="frm_modificarHistorialFamiliar" action="frm_consultarHistorialClinico2.php">
					<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
						<?php
						$claveSecc=explode(".",$_POST['rdb_id']);
						$clave=$claveSecc[0];
						$conn = conecta("bd_clinica");
						$stm_sql = "SELECT * 
									FROM  `aspectos_grales_2` 
									WHERE  `historial_clinico_id_historial` LIKE  '$clave'";
						$rs = mysql_query($stm_sql);
						if($datos = mysql_fetch_array($rs)){
							$existe = true;
						} else {
							$existe = false;
						}
						?>
						<legend class="titulo_etiqueta">Ingresar los Aspectos Generales del Trabajador /2</legend>
						<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
						<input type="hidden" name="tipo" value="<?php echo $_POST['tipo']; ?>"/>
						<input type="hidden" name="existe" value="<?php echo $existe ?>"/>
						
						<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
							<tr>
								<td width="93"><div align="right">*Nariz</div></td>
								<td width="240">
									<input name="txt_nariz" type="text" class="caja_de_texto" id="txt_nariz" 
									onKeyPress="return permite(event,'num_car',8);" size="28" maxlength="40" required="required" value="<?php 
										if($datos['nariz'] != '')
											echo $datos['nariz'];
										else 
											echo "NORMAL, SIN DESVIACIÓN";
										?>" />
								</td>
								<td width="82"><div align="right">*Obstrucci&oacute;n</div></td>
								<td width="180">
									<input name="txt_obstruccion" type="text" class="caja_de_texto" id="txt_obstruccion"  
									onKeyPress="return permite(event,'num_car',8);" size="30" maxlength="30" required="required" value="<?php 
										if($datos['obstruccion'] != '')
											echo $datos['obstruccion'];
										else 
											echo "NO";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Boca y Garganta</div></td>
								<td>
									<input name="txt_boca" type="text" class="caja_de_texto" id="txt_boca" 
									onKeyPress="return permite(event,'num_car',8);" size="35" maxlength="35" required="required" value="<?php 
										if($datos['boca_garganta'] != '')
											echo $datos['boca_garganta'];
										else 
											echo "DE CARACTERISTICAS NORMALES";
										?>" />
								</td>
								<td><div align="right">*Encias</div></td>
								<td>
									<input name="txt_encias" type="text" class="caja_de_texto" id="txt_encias" required="required" 
									onKeyPress="return permite(event,'num_car',8);" size="25" maxlength="25" value="<?php 
										if($datos['encias'] != '')
											echo $datos['encias'];
										else 
											echo "NORMAL";
										?>" />
								</td>
								<td width="73"><div align="right">*Dientes</div></td>
								<td width="173">
									<input name="txt_dientes" type="text" class="caja_de_texto" id="txt_dientes" required="required" 
									onKeyPress="return permite(event,'num_car',8);" size="25" maxlength="25" value="<?php 
										if($datos['dientes'] != '')
											echo $datos['dientes'];
										else 
											echo "COMPLETOS";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Cuello</div></td>
								<td>
									<input name="txt_cuello" type="text" class="caja_de_texto" id="txt_cuello" 
									onKeyPress="return permite(event,'num_car',8);" size="25" maxlength="25" required="required" value="<?php 
										if($datos['cuello'] != '')
											echo $datos['cuello'];
										else 
											echo "NORMAL";
										?>" />
								</td>
								<td><div align="right">*Linfaticos</div></td>
								<td>
									<input name="txt_linfaticos" type="text" class="caja_de_texto" id="txt_linfaticos" required="required" 
									onKeyPress="return permite(event,'num_car',8);" size="30" maxlength="30" value="<?php 
										if($datos['linfaticos'] != '')
											echo $datos['linfaticos'];
										else 
											echo "NO SE PALPAN ADENOPATIAS";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Torax</div></td>
								<td colspan="4">
									<input name="txt_torax" type="text" class="caja_de_texto" id="txt_torax" 
									onKeyPress="return permite(event,'num_car',8);" size="65" maxlength="65" required="required" value="<?php 
										if($datos['torax'] != '')
											echo $datos['torax'];
										else 
											echo "DE FORMA VOLUMEN Y ESTADO DE SUPERFICIE NORMAL";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Coraz&oacute;n</div></td>
								<td colspan="4">
									<input name="txt_corazon" type="text" class="caja_de_texto" id="txt_corazon" 
									onKeyPress="return permite(event,'num_car',8);" size="70" maxlength="70" required="required" value="<?php 
										if($datos['corazon'] != '')
											echo $datos['corazon'];
										else 
											echo "ÁREA Y RUIDOS CARDIACOS DE CARACTERISTICAS NORMALES";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Pulmones</div></td>
								<td colspan="4">
									<input name="txt_pulmones" type="text" class="caja_de_texto" id="txt_pulmones" 
									onKeyPress="return permite(event,'num_car',8);" size="60" maxlength="60" required="required" value="<?php 
										if($datos['pulmones'] != '')
											echo $datos['pulmones'];
										else 
											echo "BIEN VENTILADOS SIN RUIDOS AGREGADOS";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Abdomen</div></td>
									<td><input name="txt_abdomen" type="text" class="caja_de_texto" id="txt_abdomen" 
									onKeyPress="return permite(event,'num_car',8);" size="40" maxlength="60" required="required" value="<?php 
										if($datos['abdomen'] != '')
											echo $datos['abdomen'];
										else 
											echo "BLANDO, DEPRESIBLE NO DOLOROSO";
										?>" />
								</td>
								<td><div align="right">*Higado</div></td>
								<td>
									<input name="txt_higado" type="text" class="caja_de_texto" id="txt_higado" 
									onKeyPress="return permite(event,'num_car',8);" size="15" maxlength="15" align="absmiddle" required="required" value="<?php 
										if($datos['higado'] != '')
											echo $datos['higado'];
										else 
											echo "NO PALPABLE";
										?>" />
								</td>
								<td><div align="right">*Bazo</div></td>
								<td>
									<input name="txt_bazo" type="text" class="caja_de_texto" id="txt_bazo" 
									onKeyPress="return permite(event,'num_car',8);" size="15" maxlength="15" required="required" value="<?php 
										if($datos['bazo'] != '')
											echo $datos['bazo'];
										else 
											echo "NO PALPABLE";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Pared Abdominal</div></td>
								<td>
									<input name="txt_pared" type="text" class="caja_de_texto" id="txt_pared" 
									onKeyPress="return permite(event,'num_car',8);" size="25" maxlength="60" required="required" value="<?php 
										if($datos['pared_abdominal'] != '')
											echo $datos['pared_abdominal'];
										else 
											echo "INTEGRA";
										?>" />
								</td>
								<td><div align="right">*Anillos</div></td>
								<td>
									<input name="txt_anillos" type="text" class="caja_de_texto" id="txt_anillos" 
									onKeyPress="return permite(event,'num_car',8);" size="10" maxlength="10" align="absmiddle" required="required" value="<?php 
										if($datos['anillo'] != '')
											echo $datos['anillo'];
										else 
											echo "LIBRES";
										?>" />
								</td>
								<td><div align="right">*Hernias</div></td>
								<td>
									<input name="txt_hernias" type="text" class="caja_de_texto" id="txt_hernias" 
									onKeyPress="return permite(event,'num_car',8);" size="10" maxlength="10" required="required" value="<?php 
										if($datos['hernias'] != '')
											echo $datos['hernias'];
										else 
											echo "NO";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Gen Uri.</div></td>
								<td>
									<input name="txt_genUri" type="text" class="caja_de_texto" id="txt_genUri" 
									onKeyPress="return permite(event,'num_car',8);" size="40" maxlength="60" required="required" value="<?php 
										if($datos['gen_uri'] != '')
											echo $datos['gen_uri'];
										else 
											echo "DE CARACTERISTICAS NORMALES";
										?>" />
								</td>
								<td><div align="right">*Hidrocele</div></td>
								<td>
									<input name="txt_hidro" type="text" class="caja_de_texto" id="txt_hidro" 
									onKeyPress="return permite(event,'num_car',8);" size="10" maxlength="10" align="absmiddle" required="required" value="<?php 
										if($datos['hidrocele'] != '')
											echo $datos['hidrocele'];
										else 
											echo "NO";
										?>" />
								</td>
								<td><div align="right">*Varicocele</div></td>
								<td>
									<input name="txt_vari" type="text" class="caja_de_texto" id="txt_vari" 
									onKeyPress="return permite(event,'num_car',8);" size="10" maxlength="10" required="required" value="<?php 
										if($datos['varicocele'] != '')
											echo $datos['varicocele'];
										else 
											echo "NO";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Hemorroides</div></td>
								<td colspan="4">
									<input name="txt_hemo" type="text" class="caja_de_texto" id="txt_hemo" 
									onKeyPress="return permite(event,'num_car',8);" size="60" maxlength="60" required="required" value="<?php 
										if($datos['hemorroides'] != '')
											echo $datos['hemorroides'];
										else 
											echo "NEGATIVOS";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Extr. Suprs.</div></td>
								<td colspan="4">
									<input name="txt_extSup" type="text" class="caja_de_texto" id="txt_extSup" 
									onKeyPress="return permite(event,'num_car',8);" size="70" maxlength="70" required="required" value="<?php 
										if($datos['extr_suprs'] != '')
											echo $datos['extr_suprs'];
										else 
											echo "INTEGRAS, SIMETRICAS Y ARCOS DE MOVILIDAD PALPABLES";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Extr. Infrs.</div></td>
								<td colspan="4">
									<input name="txt_extInf" type="text" class="caja_de_texto" id="txt_extInf" 
									onKeyPress="return permite(event,'num_car',8);" size="70" maxlength="70" required="required" value="<?php 
										if($datos['extr_infrs'] != '')
											echo $datos['extr_infrs'];
										else 
											echo "INTEGRAS, SIMETRICAS Y ARCOS DE MOVILIDAD PALPABLES";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Reflejos O.T.</div></td>
								<td>
									<input name="txt_reflejos" type="text" class="caja_de_texto" id="txt_reflejos" 
									onKeyPress="return permite(event,'num_car',8);" size="25" maxlength="40" required="required" value="<?php 
										if($datos['reflejos_ot'] != '')
											echo $datos['reflejos_ot'];
										else 
											echo "NORMORREFLEXICOS";
										?>" />
								</td>
								<td><div align="right">*Psiquismo</div></td>
								<td>
									<input name="txt_psiquismo" type="text" class="caja_de_texto" id="txt_psiquismo" 
									onKeyPress="return permite(event,'num_car',8);" size="30" maxlength="40" required="required" value="<?php 
										if($datos['psiquismo'] != '')
											echo $datos['psiquismo'];
										else 
											echo "ESTABLE";
										?>" />
								</td>
							</tr>
							<tr>
								<td><div align="right">*Sintomat. Actual</div></td>
								<td colspan="4">
									<input name="txt_sintoma" type="text" class="caja_de_texto" id="txt_sintoma" 
									onKeyPress="return permite(event,'num_car',8);" size="60" maxlength="60" required="required" value="<?php 
										if($datos['sintoma_actual'] != '')
											echo $datos['sintoma_actual'];
										else 
											echo "ASINTOMATICO";
										?>" />
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
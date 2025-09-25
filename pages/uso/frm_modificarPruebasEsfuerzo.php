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
				#tabla-agregarRegistro {position:absolute; left:30px; top:180px; width:500px; height:250px; z-index:12;}
				#botonesModificar { position:absolute; left:30px; top:400px; width:535px; height:40px; z-index:25;}
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
				<div class="titulo_barra" id="titulo-registrar">Modificar Prueba de Esfuerzo</div>
				
				<form method="post" name="frm_modificarHistorialFamiliar" id="frm_modificarHistorialFamiliar" action="frm_consultarHistorialClinico2.php">
					<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
						<?php
						$claveSecc=explode(".",$_POST['rdb_id']);
						$clave=$claveSecc[0];
						$conn = conecta("bd_clinica");
						$stm_sql = "SELECT * 
									FROM  `prueba_esfuerzo` 
									WHERE  `historial_clinico_id_historial` LIKE  '$clave'";
						$rs = mysql_query($stm_sql);
						if($datos = mysql_fetch_array($rs)){
							$existe = true;
						} else {
							$existe = false;
						}
						?>
						<legend class="titulo_etiqueta">Ingresar Prueba de Esfuerzo</legend>
						<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
						<input type="hidden" name="tipo" value="<?php echo $_POST['tipo']; ?>"/>
						<input type="hidden" name="existe" value="<?php echo $existe ?>"/>
						
						<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
							<tr>
								<td width="32%">&nbsp;</td>
								<td width="33%"><div align="rigth"><strong>*Pulso</strong></div></td>
								<td width="35%"><div align="rigth"><strong>*Respiraci&oacute;n</strong></div></td>
							</tr>
							<tr>
								<td><div align="right">*En Reposo</div></td>
								<td>
									<input name="txt_pulsoRep" type="text" class="caja_de_num" id="txt_pulsoRep" onKeyPress="return permite(event,'num',2);" 
									value="<?php if($datos['pulso_reposo'] != '') echo $datos['pulso_reposo']; ?>" size="10" maxlength="10" required="required"/>
								</td>
								<td>
									<input name="txt_respRep" type="text" class="caja_de_num" id="txt_respRep" required="required" 
									onKeyPress="return permite(event,'num',2);" value="<?php if($datos['resp_reposo'] != '') echo $datos['resp_reposo']; ?>" size="10" maxlength="10" align="absmiddle"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Inm. Desp Esfzo.</div></td>
								<td>
									<input name="txt_pulsoInm" type="text" class="caja_de_num" id="txt_pulsoInm" required="required" 
									onKeyPress="return permite(event,'num',2);" value="<?php if($datos['pulso_inm_desp_esfzo'] != '') echo $datos['pulso_inm_desp_esfzo']; ?>" size="10" maxlength="10"/>
								</td>
								<td>
									<input name="txt_respInm" type="text" class="caja_de_num" id="txt_respInm" required="required" 
									onKeyPress="return permite(event,'num',2);" value="<?php if($datos['resp_inm_desp_esfzo'] != '') echo $datos['resp_inm_desp_esfzo']; ?>" size="10" maxlength="10"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*1 Min. Despu&eacute;s</div></td>
								<td>
									<input name="txt_pulso1Desp" type="text" class="caja_de_num" id="txt_pulso1Desp" required="required" 
									onKeyPress="return permite(event,'num',2);" value="<?php if($datos['pulso_un_min_desp'] != '') echo $datos['pulso_un_min_desp']; ?>" size="10" maxlength="10"/>
								</td>
								<td>
									<input name="txt_resp1Desp" type="text" class="caja_de_num" id="txt_resp1Desp" required="required" 
									onKeyPress="return permite(event,'num',2);" value="<?php if($datos['resp_un_min_desp'] != '') echo $datos['resp_un_min_desp']; ?>" size="10" maxlength="10"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*2 Min. Despu&eacute;s</div></td>
								<td>
									<input name="txt_pulso2Desp" type="text" class="caja_de_num" id="txt_pulso2Desp" required="required" 
									onKeyPress="return permite(event,'num',2);" value="<?php if($datos['pulso_dos_min_desp'] != '') echo $datos['pulso_dos_min_desp']; ?>" size="10" maxlength="10"/>
								</td>
								<td>
									<input name="txt_resp2Desp" type="text" class="caja_de_num" id="txt_resp2Desp" required="required" 
									onKeyPress="return permite(event,'num',2);" value="<?php if($datos['resp_dos_min_desp'] != '') echo $datos['resp_dos_min_desp']; ?>" size="10" maxlength="10"/>
								</td>
							</tr>
							<tr>
								<td colspan="4">
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
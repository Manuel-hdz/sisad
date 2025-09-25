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
				#tabla-agregarRegistro {position:absolute; left:30px; top:180px; width:600px; height:155px; z-index:12;}
				#botonesModificar { position:absolute; left:30px; top:310px; width:635px; height:40px; z-index:25;}
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
				<div class="titulo_barra" id="titulo-registrar">Modificar Antecedentes Patologicos</div>
				
				<form method="post" name="frm_modificarHistorialFamiliar" id="frm_modificarHistorialFamiliar" action="frm_consultarHistorialClinico2.php">
					<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
						<?php
						$claveSecc=explode(".",$_POST['rdb_id']);
						$clave=$claveSecc[0];
						$conn = conecta("bd_clinica");
						$stm_sql = "SELECT * 
									FROM  `ant_no_patologicos` 
									WHERE  `historial_clinico_id_historial` LIKE  '$clave'";
						$rs = mysql_query($stm_sql);
						if($datos = mysql_fetch_array($rs)){
							$existe = true;
						} else {
							$existe = false;
						}
						?>
						<legend class="titulo_etiqueta">Ingresar los Antecedentes Patologicos</legend>
						<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
						<input type="hidden" name="tipo" value="<?php echo $_POST['tipo']; ?>"/>
						<input type="hidden" name="existe" value="<?php echo $existe ?>"/>
						
						<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
							<tr>
								<td><div align="right">*Actividad</div></td>
								<td>
									<select name="cmb_actividad" class="combo_box" id="cmb_actividad" required="required">
										<option value="" selected="selected">Actividad</option>
										<option <?php if($datos["actividad"] == "SEDENTARIO") echo "selected" ?> value="SEDENTARIO">SEDENTARIO</option>
										<option <?php if($datos["actividad"] == "BAJA") echo "selected"; ?> value="BAJA">BAJA</option>
										<option <?php if($datos["actividad"] == "MEDIA") echo "selected"; ?> value="MEDIA">MEDIA</option>
										<option <?php if($datos["actividad"] == "ALTA") echo "selected"; ?> value="ALTA">ALTA</option>
										<option <?php if($datos["actividad"] == "ALTO RENDIMIENTO") echo "selected"; ?> value="ALTO RENDIMIENTO">ALTO RENDIMIENTO</option>
									</select>
								</td>
								<td><div align="right">*Tabaquismo</div></td>
								<td>
									<select name="cmb_tabaquismo" class="combo_box" id="cmb_tabaquismo" required="required">
										<option value="" selected="selected">Tabaquismo</option>
										<option <?php if($datos["tabaquismo"] == "NEGATIVO") echo "selected"; ?> value="NEGATIVO">NEGATIVO</option>
										<option <?php if($datos["tabaquismo"] == "GRADO I") echo "selected"; ?> value="GRADO I">GRADO I</option>
										<option <?php if($datos["tabaquismo"] == "GRADO II") echo "selected"; ?> value="GRADO II">GRADO II</option>
										<option <?php if($datos["tabaquismo"] == "GRADO III") echo "selected"; ?> value="GRADO III">GRADO III</option>
										<option <?php if($datos["tabaquismo"] == "GRADO IV") echo "selected"; ?> value="GRADO IV">GRADO IV</option>
									</select>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Etilismo</div></td>
								<td>
									<select name="cmb_etilismo" class="combo_box" id="cmb_etilismo" required="required">
										<option value="" selected="selected">Etilismo</option>
										<option <?php if($datos["etilismo"] == "NEGATIVO") echo "selected"; ?> value="NEGATIVO">NEGATIVO</option>
										<option <?php if($datos["etilismo"] == "GRADO I") echo "selected"; ?> value="GRADO I">GRADO I</option>
										<option <?php if($datos["etilismo"] == "GRADO II") echo "selected"; ?> value="GRADO II">GRADO II</option>
										<option <?php if($datos["etilismo"] == "GRADO III") echo "selected"; ?> value="GRADO III">GRADO III</option>
										<option <?php if($datos["etilismo"] == "GRADO IV") echo "selected"; ?> value="GRADO IV">GRADO IV</option>
									</select>
								</td>
								<td><div align="right">*Otras Adicciones</div></td>
								<td>
									<input name="txt_otrasAdicciones" type="text" class="caja_de_texto" id="txt_otrasAdicciones" onKeyPress="return permite(event,'num_car',8);" 
									size="30" maxlength="70" required="required" value="<?php if($datos["otras_adicc"] != '') echo $datos["otras_adicc"]; ?>" />
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<div align="center">
										<input name="sbt_guardarHisFam" type="submit" class="botones" id= "sbt_guardarHisFam" value="Guardar" 
										title="Guardar los Registros de Aspectos Generales 1" onMouseOver="window.status='';return true" />
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
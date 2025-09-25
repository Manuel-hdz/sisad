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
		include ("head_menu.php");
		include ("op_modificarHistorialClinico.php");?>
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
				#tabla-agregarRegistro {position:absolute; left:30px; top:180px; width:600px; height:230px; z-index:12;}
				#botonesModificar { position:absolute; left:30px; top:390px; width:835px; height:40px; z-index:25;}
				#tabla-mostrarRegistros {position:absolute;left:23px;top:440px;width:829px;height:250px;z-index:16;padding:15px;padding-top:0px; overflow:auto;}
				#img-imc {position:absolute;left:497px;top:106px;width:492px;height:271px;z-index:14;}
				.Estilo1 {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 12px;
				}
				-->
			</style>
			<body>
				<?php
				if(isset($_POST["noRegistro"])){
					unset($_SESSION["HisTrabajo"][$_POST["noRegistro"]]);
					if(isset($_SESSION["HisTrabajo"]) && isset($_POST["noRegistro"]))
						$_SESSION['HisTrabajo'] = array_values($_SESSION['HisTrabajo']);
					if(isset($_SESSION["HisTrabajo"])){
						if(count($_SESSION["HisTrabajo"])==0){
							unset($_SESSION["HisTrabajo"]);
						}
					}
				}
				
				else if(isset($_POST["sbt_agregar"])){
					$repetido = 0;
					if(isset($_SESSION['HisTrabajo'])){
						foreach($_SESSION["HisTrabajo"] as $ind => $value){
							if(strtoupper($_POST["txt_lugar"])==$value["lugar"]){
								$repetido = 1;
								break;
							}
						}
					}
					if($repetido!=1){
						$condEsp1 = "";
						if(isset($_POST['ckb_ergonomia']))
							$condEsp1 = $_POST['ckb_ergonomia'];
						$condEsp2 ="";
						if(isset($_POST['ckb_luz']))
							$condEsp2 = $_POST['ckb_luz'];
						$condEsp3 ="";
						if(isset($_POST['ckb_polvo']))
							$condEsp3 = $_POST['ckb_polvo'];
						$condEsp4 ="";
						if(isset($_POST['ckb_ruido']))
							$condEsp4 = $_POST['ckb_ruido'];
						$condEsp5 ="";
						if(isset($_POST['ckb_sedentarismo']))
							$condEsp5 = $_POST['ckb_sedentarismo'];
						$condEsp6 ="";
						if(isset($_POST['ckb_vibracion']))
							$condEsp6 = $_POST['ckb_vibracion'];
						
						if(isset($_SESSION['HisTrabajo'])){
							$HisTrabajo[] = array("lugar"=>strtoupper($_POST['txt_lugar']), "tipoTrab"=>strtoupper($_POST['txt_tipoTrab']), "tiempo"=>strtoupper($_POST['txt_tiempo']), 
							"condEsp1"=>strtoupper($condEsp1), "condEsp2"=>strtoupper($condEsp2), "condEsp3"=>strtoupper($condEsp3),
							"condEsp4"=>strtoupper($condEsp4), "condEsp5"=>strtoupper($condEsp5), "condEsp6"=>strtoupper($condEsp6));
						} else {
							$cont=0;
							$HisTrabajo = array(array("lugar"=>strtoupper($_POST['txt_lugar']), "tipoTrab"=>strtoupper($_POST['txt_tipoTrab']), "tiempo"=>strtoupper($_POST['txt_tiempo']), 
							"condEsp1"=>strtoupper($condEsp1), "condEsp2"=>strtoupper($condEsp2), "condEsp3"=>strtoupper($condEsp3),
							"condEsp4"=>strtoupper($condEsp4), "condEsp5"=>strtoupper($condEsp5), "condEsp6"=>strtoupper($condEsp6)));
							$_SESSION['HisTrabajo'] = $HisTrabajo;
						}
					} else {
						?>
						<script type="text/javascript" language="javascript">
							setTimeout("alert('El Lugar de Trabajo ya se encuentra Registrado')", 500);
						</script>
						<?php
					}
				} else {
					if(isset($_SESSION['HisTrabajo'])){
						unset($_SESSION['HisTrabajo']);
					}
					$claveSecc=explode(".",$_POST['rdb_id']);
					$clave=$claveSecc[0];
					$conn = conecta("bd_clinica");
					$stm_sql = "SELECT * 
								FROM  `historial_trabajo` 
								WHERE  `historial_clinico_id_historial` LIKE  '$clave'";
					$rs = mysql_query($stm_sql);
					if($datos=mysql_fetch_array($rs)){
						do{
							$condiciones = explode(",",$datos['cond_especiales']);
							
							$condEsp1 = "";
							$condEsp2 = "";
							$condEsp3 = "";
							$condEsp4 = "";
							$condEsp5 = "";
							$condEsp6 = "";
							
							for($i=0; $i<count($condiciones); $i++){
								if($condiciones[$i] == "ERGONOMIA")
									$condEsp1 = $condiciones[$i];
								
								if(trim($condiciones[$i]) == "LUZ INTENSA")
									$condEsp2 = trim($condiciones[$i]);
								
								if(trim($condiciones[$i]) == "POLVO")
									$condEsp3 = trim($condiciones[$i]);
								
								if(trim($condiciones[$i]) == "RUIDO")
									$condEsp4 = trim($condiciones[$i]);
								
								if(trim($condiciones[$i]) == "SEDENTARISMO")
									$condEsp5 = trim($condiciones[$i]);
								
								if(trim($condiciones[$i]) == "VIBRACION")
									$condEsp6 = trim($condiciones[$i]);
							}
							
							if(isset($_SESSION['HisTrabajo'])){
								$HisTrabajo[] = array(
													"lugar"=>strtoupper($datos['lugar']), 
													"tipoTrab"=>strtoupper($datos['tipo_trabajo']), 
													"tiempo"=>strtoupper($datos['tiempo']), 
													"condEsp1"=>strtoupper($condEsp1), 
													"condEsp2"=>strtoupper($condEsp2), 
													"condEsp3"=>strtoupper($condEsp3), 
													"condEsp4"=>strtoupper($condEsp4), 
													"condEsp5"=>strtoupper($condEsp5), 
													"condEsp6"=>strtoupper($condEsp6)
												);
								$_SESSION['HisTrabajo'] = $HisTrabajo;
							} else {
								$cont=0;
								$HisTrabajo = 	array(
													array(
														"lugar"=>strtoupper($datos['lugar']), 
														"tipoTrab"=>strtoupper($datos['tipo_trabajo']), 
														"tiempo"=>strtoupper($datos['tiempo']), 
														"condEsp1"=>strtoupper($condEsp1), 
														"condEsp2"=>strtoupper($condEsp2), 
														"condEsp3"=>strtoupper($condEsp3), 
														"condEsp4"=>strtoupper($condEsp4), 
														"condEsp5"=>strtoupper($condEsp5), 
														"condEsp6"=>strtoupper($condEsp6)
													)
												);
								$_SESSION['HisTrabajo'] = $HisTrabajo;
							}
						}while($datos=mysql_fetch_array($rs));
					}
				}
				
				if (isset($_SESSION["HisTrabajo"])){
					echo "<div id='tabla-mostrarRegistros' class='borde_seccion'>";
						mostrarRegistrosHisTrabajo($HisTrabajo);
					echo "</div>";
				}?>
				<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
				<div class="titulo_barra" id="titulo-registrar">Modificar Historial de Trabajo</div>
				
				<form method="post" name="frm_modificarHistorialFamiliar" id="frm_modificarHistorialFamiliar" action="frm_modificarHistorialTrabajo.php" onSubmit="return valFormModificarHistorialTrabajo(this);">
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
						<legend class="titulo_etiqueta">Ingresar Historial de Trabajo</legend>
						<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
						<input type="hidden" name="tipo" value="<?php echo $_POST['tipo']; ?>"/>
						<input type="hidden" name="existe" value="<?php echo $existe ?>"/>
						
						<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
							<tr>
								<td><div align="right">*Lugar</div></td>
								<td>
									<input name="txt_lugar" type="text" class="caja_de_texto" id="txt_lugar" onKeyPress="return permite(event,'num_car',8);" value=""  size="50" maxlength="70"/>
								</td>
								<td width="153"><div align="right">*Tipo Trabajo</div></td>
								<td width="296">
									<input name="txt_tipoTrab" type="text" class="caja_de_texto" id="txt_tipoTrab" 
									onKeyPress="return permite(event,'num_car',8);" value="" size="50" maxlength="60"/>
								</td>
							</tr>
							<tr>
								<td><div align="right">*Tiempo</div></td>
								<td>
									<input name="txt_tiempo" type="text" class="caja_de_texto" id="txt_tiempo" onKeyPress="return permite(event,'num_car',8);" value="" size="20" maxlength="20"/>
								</td>
								<td colspan="2">
									<table width="100%" border="0" cellpadding="3" cellspacing="3" cols="4" class="tabla_frm">
										<caption align="center" style="border:medium"  class='titulo_etiqueta'>
											*Condiciones de Trabajo
										</caption>
										<tr>
											<td align="center"class='nombres_columnas'>Ergonomia</td>
											<td align="center" class='nombres_columnas'>Luz Intensa</td>
											<td align="center" class='nombres_columnas'>Polvo</td>
											<td align="center" class='nombres_columnas'>Ruido</td>
											<td align="center" class='nombres_columnas'>Sedentarismo</td>
											<td align="center" class='nombres_columnas'>Vibraciones</td>
										</tr>
										<tr>
											<td class='nombres_filas' align='center'>
												<input type="checkbox" id="ckb_ergonomia" name="ckb_ergonomia" value="Ergonomia"/>
											</td>
											<td class='nombres_filas' align='center'>
												<input type="checkbox" id="ckb_luz" name="ckb_luz" value="Luz Intensa"/>
											</td>
											<td class='nombres_filas' align='center'>
												<input type="checkbox" id="ckb_polvo" name="ckb_polvo" value="Polvo"/>
											</td>
											<td class='nombres_filas' align='center'>
												<input type="checkbox" id="ckb_ruido" name="ckb_ruido" value="Ruido"/>
											</td>
											<td class='nombres_filas' align='center'>
												<input type="checkbox" id="ckb_sedentarismo" name="ckb_sedentarismo" value="Sedentarismo"/>
											</td>
											<td class='nombres_filas' align='center'>
												<input type="checkbox" id="ckb_vibracion" name="ckb_vibracion" value="Vibracion"/>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan="4">
									<div align="center">
										<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value=""/>
										<?php
										if(isset($_SESSION["HisTrabajo"])){
											?>
											<input name="sbt_guardarHisFam" type="submit" class="botones" id= "sbt_guardarHisFam" value="Guardar" title="Guardar los Registros de Historial de Trabajo" 
											onMouseOver="window.status='';return true" onClick="document.getElementById('frm_modificarHistorialFamiliar').action='frm_consultarHistorialClinico2.php';hdn_botonSeleccionado.value=''"/>
											&nbsp;&nbsp;&nbsp;
											<?php
										}
										?>
										<input name="sbt_agregar" type="submit" class="botones" id= "sbt_agregar" value="Agregar" 
										title="Agregar Historial de Trabaajo" onMouseOver="window.status='';return true" onClick="hdn_botonSeleccionado.value='agregar'"/>
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
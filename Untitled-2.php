<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Almac�n
	if (!verificarPermiso($usr_reg, $_SERVER['PHP_SELF'])) {
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	} else {
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include("head_menu.php");
		//Manejo de la funciones para registrar la Salida de Materiales en la BD
		include("op_salidaMaterial.php");


		//Liberar los valores de la SESSION para Salida de Material, en el caso que se haya dado click en boton de Cancelar en la pagina donde se pide la informaci�n complementaria de la Salida
		if (isset($_GET['lmp']) && $_GET['lmp'] == "si") {
			unset($_SESSION['datosSalida']);
			unset($_SESSION['id_salida']);
		}
	?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<script type="text/javascript" src="../../includes/validacionAlmacen.js"></script>
			<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
			<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
			<script type="text/javascript" src="includes/ajax/cargarDatosMateriales.js"></script>
			<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
			<script type="text/javascript" src="includes/ajax/busq_spider_material.js"></script>

			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

			<style type="text/css">
				#titulo-salida {
					position: absolute;
					left: 30px;
					top: 146px;
					width: 141px;
					height: 19px;
					z-index: 11;
				}

				#form-salida {
					position: absolute;
					left: 4px;
					top: 190px;
					width: 467px;
					height: 240px;
					z-index: 13;
				}

				#foto-empleado {
					position: absolute;
					left: 515px;
					top: 196px;
					width: 160px;
					height: 230px;
					z-index: 13;
					border: solid;
				}

				#material-agregado {
					position: absolute;
					left: 5px;
					top: 455px;
					width: 990px;
					height: 240px;
					z-index: 11;
					overflow: auto;
				}

				#boton-terminar {
					position: absolute;
					left: 173px;
					top: 462px;
					width: 141px;
					height: 37px;
					z-index: 15;
				}

				#form-seguridad {
					position: absolute;
					left: 680px;
					top: 190px;
					width: 300px;
					height: 82px;
					z-index: 18;
				}

				#res-spider {
					position: fixed;
					left: 70px;
					z-index: 30;
				}
			</style>
		</head>

		<body>
			<?php
			if (isset($_POST["es_epp"])) {
				if ($_POST["es_epp"] == 1) {
					$id_empl = explode(",", $_POST["vale_kiosco"]);
					echo "
			<form name='frm_temp_epp' id='frm_temp_epp' action='frm_equipoSeguridad.php' method='post'>
				<input type='hidden' id='id_empl' name='id_empl' value='$id_empl[1]'/>
				<input type='hidden' id='id_kiosco' name='id_kiosco' value='$id_empl[0]'/>
			</form>
			";
			?>
					<script>
						document.getElementById("frm_temp_epp").submit();
					</script>
				<?php
				}
			}
			if (isset($_POST["id_empl"])) {
				if (isset($_POST["vale_kiosco"])) {
					$id_empl = explode(",", $_POST["vale_kiosco"]);
				?>
					<div id="foto-empleado">
						<img src="verImagenEmpl.php?id_empleado=<?php echo $id_empl[1]; ?>" width="100%" height="100%" />
					</div>
				<?php
				} else {
					$id_empl = $_POST["id_empl"];
				?>
					<div id="foto-empleado">
						<img src="verImagenEmpl.php?id_empleado=<?php echo $id_empl; ?>" width="100%" height="100%" />
					</div>
			<?php
				}
			}
			?>
			<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-salida">Salida Material </div>

			<fieldset class="borde_seccion" id="form-salida" name="form-salida">
				<?php
				if (isset($_POST["vale_kiosco"])) {
					$id_vale_kiosco = explode(",", $_POST["vale_kiosco"]);
					partidasSalidaKiosco($id_vale_kiosco[0]);
				}
				?>
				<legend class="titulo_etiqueta">Seleccionar Material para Registrar en la Salida</legend>
				<br>
				<form onSubmit="return valFormSalidaDetalle(this);" name="frm_salidaDetalle" action="frm_salidaMaterial.php"
					method="post">
					<table border="0" cellpadding="5" class="tabla_frm" width="100%">
						<!--
		<tr>
		  <td width="22%"><div align="right">Categor&iacute;a</div></td>
			<td width="78%"><?php
							$cantMat = 0;
							if (isset($_POST['btn_agregarOtro']))
								$cantMat = 1;
							$res = cargarComboConId(
								"cmb_categoria",
								"linea_articulo",
								"linea_articulo",
								"materiales",
								"bd_almacen",
								"Categor&iacute;a",
								"",
								"limpiarCamposEntrada2(this,'S');verificarEqSeg(this.value,$cantMat);cargarComboIdNombreOrd(this.value,'bd_almacen','materiales','nom_material','id_material','linea_articulo','cmb_material','Material','nom_material','');cmb_material.value='';cmb_material.onkeyup()"
							);
							if ($res == 0) { ?>
					<label class="msje_correcto"> No hay Categor&iacute;as Registradas</label>
					<input type="hidden" name="cmb_categoria" id="cmb_categoria"/><?php
																				} ?>
		  </td>
		</tr>	
		-->
						<tr>
							<td>
								<div align="right">Material</div>
							</td>
							<td>
								<input type="text" name="cmb_material" id="cmb_material" class="caja_de_texto" size="60"
									onkeyup="lookup2(this,'1');" value="" maxlength="60" autocomplete="off" />
								<div id="res-spider">
									<div align="left" class="suggestionsBox" id="suggestions1"
										style="display: none; width:380px;">
										<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
									</div>
								</div>
							</td>
						</tr>
					</table>

					<table width="100%" border="0" cellpadding="5" class="tabla_frm">
						<tr>
							<td>
								<div align="center">Clave</div>
							</td>
							<td>
								<div align="center">Existencia</div>
							</td>
							<td>
								<div align="center">Unidad de Medida </div>
							</td>
						</tr>
						<tr>
							<td>
								<div align="center">
									<input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="10"
										maxlength="15" onchange="return buscarMaterialBD(this,2);"
										onkeypress="return permite(event,'num_car');" value="" />
								</div>
							</td>
							<td>
								<div align="center">
									<input name="txt_existencia" type="text" readonly="readonly" class="caja_de_num"
										id="txt_existencia" size="15" maxlength="20" value="" />
								</div>
							</td>
							<td>
								<div align="center">
									<input name="txt_unidadMedida" type="text" readonly="readonly" class="caja_de_num"
										id="txt_unidadMedida" size="15" maxlength="20" value="" />
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div align="center">Cant. Salida</div>
							</td>
							<!--<td><div align="center">Costo Unidad</div></td>-->
							<td>
								<div align="center">Id Equipo</div>
							</td>
						</tr>
						<tr>
							<td align="center"><input name="txt_cantSalida" type="text" class="caja_de_num"
									id="txt_cantSalida" onkeypress="return permite(event,'num');" size="15"
									maxlength="20" /></td>
							<input type="hidden" name="txt_costoUnidad" id="txt_costoUnidad" value="0" />
							<td>
								<div align="center">
									<?php $conn_mtto = conecta("bd_mantenimiento"); //Conectarse a la BD de Mantenimiento
									?>
									<select name="cmb_idEquipo" id="cmb_idEquipo" size="1" class="combo_box"
										title="Seleccionar Id del Equipo al que va Destinado el Material">
										<option value="" title="Seleccionar Id del Equipo">Id Equipo</option>
										<?php $result_mtto = mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE  `estado` =  'ACTIVO' ORDER BY id_equipo");
										$band = 0;
										echo "<option value='N/A' title='Material que no Aplica para un Equipo'>NO APLICA</option>";
										while ($datos_equipos = mysql_fetch_array($result_mtto)) {
											echo "<option value='$datos_equipos[id_equipo]' title='$datos_equipos[nom_equipo]'>$datos_equipos[id_equipo]</option>";
										}
										?>
									</select><?php
												//Cerrar la conexion con la BD		
												mysql_close($conn_mtto); ?>
								</div>
							</td>
						</tr>
						<input type="hidden" name="cmb_tipoMoneda" id="cmb_tipoMoneda" value="PESOS" />
						<tr>
							<td colspan="3"><span id="mensaje" class="msje_correcto" style="visibility:hidden;">No Se
									Encontr&oacute; Ning&uacute;n Material</span></td>
						</tr>
						<?php
						if (isset($_POST["id_empl"])) {
							if (isset($_POST["vale_kiosco"])) {
								$id_empl = explode(",", $_POST["vale_kiosco"]);
								echo "<input type='hidden' id='id_empl' name='id_empl' value='$id_empl[1]'/>";
								echo "<input type='hidden' id='id_kiosco' name='id_kiosco' value='$id_empl[0]'/>";
							} else {
								$id_empl = $_POST["id_empl"];
								$id_kiosco = $_POST["id_kiosco"];
								echo "<input type='hidden' id='id_empl' name='id_empl' value='$id_empl'/>";
								echo "<input type='hidden' id='id_kiosco' name='id_kiosco' value='$id_kiosco'/>";
							}
						}
						?>
						<tr>
							<td colspan="3" align="center">
								<input type="hidden" id="hdn_validar" name="hdn_validar" value="1" />
								<input type="submit" name="btn_agregarOtro" class="botones" value="Agregar Otro"
									onMouseOver="window.status='';return true"
									title="Agregar Material al Registro de la Entrada"
									onclick="hdn_validar.value=1;frm_salidaDetalle.action='frm_salidaMaterial.php';" />
								&nbsp;&nbsp;&nbsp;&nbsp;
								<?php if (isset($_SESSION['datosSalida']) || isset($_POST["btn_agregarOtro"])) { ?>
									<input name="sbt_terminar" type="submit" value="Continuar" class="botones"
										title="Registrar Datos Complementarios de la Salida"
										onmouseover="window.status='';return true"
										onclick="hdn_validar.value=0;frm_salidaDetalle.action='frm_salidaMaterial2.php';" />
									&nbsp;&nbsp;&nbsp;&nbsp;
								<?php } ?>
								<input name="btn_Cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar"
									title="Regresar al Men&uacute; de Salida de Material"
									onclick="location.href='menu_entrada_salida.php'" />
							</td>
						</tr>
					</table>
			</fieldset>

			<?php
			//Si las siguientes variables se encuentran definidas en el arreglo POST, procesder a guardar los datos en el arreglo datosSalida 			
			if (isset($_POST['btn_agregarOtro'])) {
				//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
				$txt_clave = strtoupper($txt_clave);
				$conexion = conecta("bd_almacen");
				$stm_sql_ent = "SELECT T1.`fecha_entrada` , T2.`materiales_id_material` , T2.`nom_material` , T2.`unidad_material` , T2.`costo_unidad` , T2.`tipo_moneda` , ROUND( SUM(  `cant_restante` ) , 2 ) AS cantidad_existente, GROUP_CONCAT( CAST(  `entradas_id_entrada` AS CHAR ) ) AS entradas, GROUP_CONCAT( CAST(  ROUND(`cant_restante`,2) AS CHAR ) ) AS cantidades_restantes
						FROM  `entradas` AS T1
						JOIN  `detalle_entradas` AS T2 ON  `id_entrada` =  `entradas_id_entrada` 
						WHERE  `materiales_id_material` =  '$txt_clave'
						AND ROUND(  `cant_restante` , 2 ) >0
						GROUP BY  `tipo_moneda` ,  `costo_unidad` ,  `unidad_material` 
						ORDER BY  `T1`.`fecha_entrada` DESC ,  `T2`.`cant_restante` DESC ";
				$rs_ent = mysql_query($stm_sql_ent);
				if ($rs_ent) {
					while ($datos_ent = mysql_fetch_array($rs_ent)) {
						$cant_intro = 0;
						if ($datos_ent['cantidad_existente'] <= $txt_cantSalida) {
							$cant_intro = $datos_ent['cantidad_existente'];
							$txt_cantSalida -= $cant_intro;
						} else {
							$cant_intro = $txt_cantSalida;
							$txt_cantSalida = 0;
						}
						if ($cant_intro > 0) {
							if (isset($_SESSION['datosSalida'])) {
								//Obtener el nombre del material para agregarlo al arreglo
								$nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $txt_clave, "costo_unidad");
								$categoria = obtenerDato("bd_almacen", "materiales", "categoria", "id_material", $txt_clave);
								//Verificar que las cantidads registradas de salidad de un solo material no excedan su existencia, en el caso de que no se alcance a cubrir la demanda, no se agrega el registro y se manda un msg de alerta
								if (revExistenciaMaterial($datosSalida, "clave", $txt_clave, $cant_intro, $nombre)) {
									$band = 0;
									$cont = 0;
									foreach ($_SESSION['datosSalida'] as $ind => $materiales) {
										if ($materiales["clave"] == $txt_clave && $materiales["tipoMoneda"] == $datos_ent['tipo_moneda'] && $materiales["costoUnidad"] == $datos_ent['costo_unidad'] && $materiales["idEquipo"] == $cmb_idEquipo) {
											$band = 1;
											if ($datos_ent['cantidad_existente'] != $_SESSION['datosSalida'][$ind]["cantSalida"]) {
												$txt_cantSalida += $cant_intro - ($datos_ent['cantidad_existente'] - $_SESSION['datosSalida'][$ind]["cantSalida"]);
												if ($cant_intro + $_SESSION['datosSalida'][$ind]["cantSalida"] > $datos_ent['cantidad_existente'])
													$cant_intro = $datos_ent['cantidad_existente'] - $_SESSION['datosSalida'][$ind]["cantSalida"];
												$_SESSION['datosSalida'][$ind]["cantSalida"] = $cant_intro + $_SESSION['datosSalida'][$ind]["cantSalida"];
												$_SESSION['datosSalida'][$ind]["costoTotal"] = number_format($_SESSION['datosSalida'][$ind]["cantSalida"] * $_SESSION['datosSalida'][$ind]["costoUnidad"], 2);
											} else {
												$txt_cantSalida += $cant_intro;
											}
										}
										$cont++;
									}
									if ($band == 0) {
										//Guardar los datos en el arreglo
										$datosSalida[] = array(
											"clave" => $txt_clave,
											"nombre" => $nombre,
											"existencia" => $txt_existencia,
											"cantSalida" => $cant_intro,
											"costoUnidad" => number_format($datos_ent['costo_unidad'], 2),
											"costoTotal" => number_format(($cant_intro * $datos_ent['costo_unidad']), 2),
											"idEquipo" => $cmb_idEquipo,
											"catMaterial" => $categoria,
											"tipoMoneda" => $datos_ent['tipo_moneda'],
											"cantRestante" => $datos_ent['cantidad_existente'],
											"idEntradas" => $datos_ent['entradas'],
											"cantidadEntradas" => $datos_ent['cantidades_restantes']
										);
										$_SESSION['datosSalida'] = $datosSalida;
									}
								}
							}
							//Si no esta definido el arreglo $datosEntrada definirlo y agregar el primer registro
							else {
								//Obtener el nombre del material para agregarlo al arreglo
								$nombre = obtenerDato("bd_almacen", "materiales", "nom_material", "id_material", $txt_clave, "costo_unidad");
								$categoria = obtenerDato("bd_almacen", "materiales", "categoria", "id_material", $txt_clave);
								$datosSalida = array(array(
									"clave" => $txt_clave,
									"nombre" => $nombre,
									"existencia" => $txt_existencia,
									"cantSalida" => $cant_intro,
									"costoUnidad" => number_format($datos_ent['costo_unidad'], 2),
									"costoTotal" => number_format(($cant_intro * $datos_ent['costo_unidad']), 2),
									"idEquipo" => $cmb_idEquipo,
									"catMaterial" => $categoria,
									"tipoMoneda" => $datos_ent['tipo_moneda'],
									"cantRestante" => $datos_ent['cantidad_existente'],
									"idEntradas" => $datos_ent['entradas'],
									"cantidadEntradas" => $datos_ent['cantidades_restantes']
								));
								$_SESSION['datosSalida'] = $datosSalida;
								//Crear el ID de la Entrada de Material
								$_SESSION['id_salida'] = obtenerIdSalida();
							}
						}
					}
				}
			}

			if ((isset($_SESSION['datosSalida']) && count($_SESSION['datosSalida']) > 0)) {
			?><div id="material-agregado" class="borde_seccion" align="center">
					<!-- <p align="center" class="titulo_etiqueta">Registro de la Salida de Material No. <?php echo $_SESSION['id_salida']; ?></p> -->
					<p align="center" class="titulo_etiqueta">Registro de la Salida de Material</p><?php
																									modificarCategoriaMat($_SESSION["datosSalida"]);
																									mostrarRegistros($_SESSION['datosSalida'], 1);
																									?>
				</div><?php
					} ?>

			<input type="hidden" name="num_mat" id="num_mat" value="<?php echo count($_SESSION['datosSalida']); ?>" />
			</form>

			<fieldset class="borde_seccion" id="form-seguridad" name="form-seguridad">
				<legend class="titulo_etiqueta">Registrar Salida de Equipo de Seguridad</legend>
				<br>
				<table align="center">
					<tr>
						<td align="center">
							<input type="button" name="btn_registrar" onclick="location.href='frm_equipoSeguridad.php'"
								class="botones" value="Registrar" title="Registrar Material de Seguridad Entregado" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						</td>
						<td align="center">
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" name="btn_consultar"
								onclick="location.href='frm_consultarEquipoSeguridad.php'" class="botones" value="Consultar"
								title="Consultar Material de Seguridad Entregado" />
						</td>
					</tr>
				</table>
			</fieldset>

		</body>
	<?php } //Cierre del Else donde se comprueba el usuario que esta registrado 
	?>

	</html>
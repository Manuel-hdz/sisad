<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
include("../seguridad.php");

if (!verificarPermiso($usr_reg, $_SERVER['PHP_SELF'])) {
	echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
} else {
	include("head_menu.php");
	include("op_salidaMaterial.php");

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
			/* Conserva tus estilos originales */
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

			/* Modal Excel */
			#modal_excel {
				display: none;
				position: fixed;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				background: #fff;
				border: 2px solid #000;
				z-index: 50;
				padding: 20px;
				width: 600px;
				max-height: 400px;
				overflow: auto;
			}

			#modal_excel table {
				width: 100%;
				border-collapse: collapse;
			}

			#modal_excel table th,
			#modal_excel table td {
				border: 1px solid #ccc;
				padding: 5px;
				text-align: center;
			}

			#modal_excel button {
				margin-top: 10px;
				margin-right: 10px;
			}
		</style>
	</head>

	<body>
		<?php
		if (isset($_POST["es_epp"]) && $_POST["es_epp"] == 1) {
			$id_empl = explode(",", $_POST["vale_kiosco"]);
			echo "<form name='frm_temp_epp' id='frm_temp_epp' action='frm_equipoSeguridad.php' method='post'>
            <input type='hidden' id='id_empl' name='id_empl' value='$id_empl[1]'/>
            <input type='hidden' id='id_kiosco' name='id_kiosco' value='$id_empl[0]'/>
          </form>
          <script>document.getElementById('frm_temp_epp').submit();</script>";
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
				$id_kiosco = $_POST["id_kiosco"];
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
			<legend class="titulo_etiqueta">Seleccionar Material para Registrar en la Salida</legend>
			<br>
			<form onSubmit="return valFormSalidaDetalle(this);" name="frm_salidaDetalle" action="frm_salidaMaterial.php" method="post">
				<table border="0" cellpadding="5" class="tabla_frm" width="100%">
					<tr>
						<td>
							<div align="right">Material</div>
						</td>
						<td>
							<input type="text" name="cmb_material" id="cmb_material" class="caja_de_texto" size="60" onkeyup="lookup2(this,'1');" value="" maxlength="60" autocomplete="off" />
							<div id="res-spider">
								<div align="left" class="suggestionsBox" id="suggestions1" style="display: none; width:380px;">
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
								<input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="10" maxlength="15" onchange="return buscarMaterialBD(this,2);" onkeypress="return permite(event,'num_car');" value="" />
							</div>
						</td>
						<td>
							<div align="center">
								<input name="txt_existencia" type="text" readonly="readonly" class="caja_de_num" id="txt_existencia" size="15" maxlength="20" value="" />
							</div>
						</td>
						<td>
							<div align="center">
								<input name="txt_unidadMedida" type="text" readonly="readonly" class="caja_de_num" id="txt_unidadMedida" size="15" maxlength="20" value="" />
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div align="center">Cant. Salida</div>
						</td>
						<td>
							<div align="center">Id Equipo</div>
						</td>
					</tr>
					<tr>
						<td align="center"><input name="txt_cantSalida" type="text" class="caja_de_num" id="txt_cantSalida" onkeypress="return permite(event,'num');" size="15" maxlength="20" /></td>
						<input type="hidden" name="txt_costoUnidad" id="txt_costoUnidad" value="0" />
						<td>
							<div align="center">
								<?php $conn_mtto = conecta("bd_mantenimiento"); ?>
								<select name="cmb_idEquipo" id="cmb_idEquipo" size="1" class="combo_box" title="Seleccionar Id del Equipo al que va Destinado el Material">
									<option value="" title="Seleccionar Id del Equipo">Id Equipo</option>
									<?php
									$result_mtto = mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE `estado`='ACTIVO' ORDER BY id_equipo");
									echo "<option selected value='N/A' title='Material que no Aplica para un Equipo'>NO APLICA</option>";
									while ($datos_equipos = mysql_fetch_array($result_mtto)) {
										echo "<option value='$datos_equipos[id_equipo]' title='$datos_equipos[nom_equipo]'>$datos_equipos[id_equipo]</option>";
									}
									mysql_close($conn_mtto);
									?>
								</select>
							</div>
						</td>
					</tr>
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

							&nbsp;&nbsp;&nbsp;&nbsp;
							<!-- Botón Cargar CSV -->
							<input type="file" id="file_excel" accept=".csv" style="display:inline-block;" />
							<small>Solo archivos CSV (Clave, Nombre, Cantidad, Id Equipo)</small>
						</td>
					</tr>
				</table>
			</form>
		</fieldset>

		<!-- Modal Excel -->
		<div id="modal_excel">
			<h3>Previsualizar Materiales desde Excel</h3>
			<table id="tabla_preview">
				<thead>
					<tr>
						<th>Clave</th>
						<th>Nombre</th>
						<th>Cantidad</th>
						<th>Id Equipo</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
			<button id="btn_confirmar">Confirmar</button>
			<button id="btn_cancelar_modal">Cancelar</button>
		</div>

		<?php
		//Si las siguientes variables se encuentran definidas en el arreglo POST, procesder a guardar los datos en el arreglo datosSalida 			
		if (isset($_POST['btn_agregarOtro'])) {
			agregarMaterialASalida($_POST['txt_clave'], $_POST['txt_cantSalida'], $_POST['cmb_idEquipo'], $_POST['txt_existencia'], $_POST['cmb_tipoMoneda']);
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

		<script>
			document.getElementById('file_excel').addEventListener('change', function() {
				var file = this.files[0];
				if (!file) return;

				// Validar extensión
				var ext = file.name.split('.').pop().toLowerCase();
				if (ext !== 'csv') {
					alert('Solo se permiten archivos CSV');
					this.value = ''; // limpiar input
					return;
				}

				var formData = new FormData();
				formData.append('archivo_excel', file);

				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'procesar_carga_excel.php', true);
				xhr.onload = function() {
					if (xhr.status === 200) {
						try {
							var res = JSON.parse(xhr.responseText);
							if (res.success) {
								var tbody = document.querySelector('#tabla_preview tbody');
								tbody.innerHTML = '';
								res.materiales.forEach(function(m) {
									var tr = document.createElement('tr');
									tr.innerHTML = '<td>' + m.clave + '</td><td>' + m.nombre + '</td><td>' + m.cantidad + '</td><td>' + m.idEquipo + '</td>';
									tbody.appendChild(tr);
								});
								document.getElementById('modal_excel').style.display = 'block';
							} else {
								alert(res.error);
							}
						} catch (e) {
							alert('Error procesando el archivo CSV');
						}
					} else {
						alert('Error en la comunicación con el servidor');
					}
				};
				xhr.send(formData);
			});


			document.getElementById('file_excel').addEventListener('change', function() {
				var file = this.files[0];
				if (!file) return;

				var formData = new FormData();
				formData.append('archivo_excel', file);

				var xhr = new XMLHttpRequest();
				xhr.open('POST', 'procesar_carga_excel.php', true);
				xhr.onload = function() {
					if (xhr.status === 200) {
						var res = JSON.parse(xhr.responseText);
						if (res.success) {
							var tbody = document.querySelector('#tabla_preview tbody');
							tbody.innerHTML = '';
							res.materiales.forEach(function(m) {
								var tr = document.createElement('tr');
								tr.innerHTML = '<td>' + m.clave + '</td><td>' + m.nombre + '</td><td>' + m.cantidad + '</td><td>' + m.idEquipo + '</td>';
								tbody.appendChild(tr);
							});
							document.getElementById('modal_excel').style.display = 'block';
						} else {
							alert(res.error);
						}
					}
				};
				xhr.send(formData);
			});

			document.getElementById('btn_cancelar_modal').addEventListener('click', function() {
				document.getElementById('modal_excel').style.display = 'none';
			});

			document.getElementById('btn_confirmar').addEventListener('click', function() {
				var tbody = document.querySelectorAll('#tabla_preview tbody tr');
				var materiales = [];
				tbody.forEach(function(tr) {
					var tds = tr.querySelectorAll('td');
					materiales.push({
						clave: tds[0].innerText.trim(),
						nombre: tds[1].innerText.trim(),
						cantidad: tds[2].innerText.trim(),
						idEquipo: tds[3].innerText.trim()
					});
				});

			materiales.forEach(material => {
    		return buscarMaterialBD(material.nombre,2);
    		agregarMaterialASalida($_POST['txt_clave'], material.cantidad, $_POST['cmb_idEquipo'], $_POST['txt_existencia'], $_POST['cmb_tipoMoneda']);
				});
				/* 
				materiales.forEach(function(mat){
					buscarMaterialBD(mat.nombre,2)

					agregarMaterialASalida($_POST['txt_clave'], mat.cantidad, $_POST['cmb_idEquipo'], $_POST['txt_existencia'], $_POST['cmb_tipoMoneda']);
				} */
				
				/* 
				    // Usamos POST "tradicional" con x-www-form-urlencoded para PHP 5
				    var xhr = new XMLHttpRequest();
				    xhr.open('POST', 'procesar_confirmar_excel.php', true);
				    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				    xhr.onload = function() {
				        if(xhr.status===200){
				            try {
				                var res = JSON.parse(xhr.responseText);
				                if(res.success){
				                    location.reload();
				                } else {
				                    alert(res.error);
				                }
				            } catch(e){
				                alert('Error procesando la respuesta del servidor');
				            }
				        }
				    }; */
				// Enviamos el JSON como string con encodeURIComponent
				//xhr.send('materiales_excel=' + encodeURIComponent(JSON.stringify(materiales)));
			});
		</script>
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
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Almac�n
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Manejo de la funciones para registrar la Salida de Materiales en la BD
		include ("op_salidaMaterial.php");
	
?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<script type="text/javascript" src="../../includes/validacionAlmacen.js"></script>
		<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
		<script type="text/javascript" src="includes/ajax/cargarComboPersonalRH.js"></script>
		<!-- se anexa este archivo para el control de costos -->
		<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js"></script>
		<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112"
			media="screen">
		</LINK>
		<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
		<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
		<style type="text/css">
			#titulo-salida {
				position: absolute;
				left: 25px;
				top: 146px;
				width: 141px;
				height: 19px;
				z-index: 11;
			}

			#form-datos-salida {
				position: absolute;
				left: 10px;
				top: 190px;
				width: 970px;
				height: 230px;
				z-index: 12;
			}

			#registro-material {
				position: absolute;
				left: 10px;
				top: 450px;
				width: 970px;
				height: 240px;
				z-index: 14;
				overflow: auto;
			}

			#ver-calendario {
				position: absolute;
				left: 202px;
				top: 363px;
				width: 38px;
				height: 31px;
				z-index: 15;
			}

			#procesando {
				position: absolute;
				left: 406px;
				top: 274px;
				width: 133px;
				height: 86px;
				z-index: 17;
			}
		</style>
	</head>

	<body>
		<audio id="sonido_alertas_correcto" preload>
			<source src="includes/sounds/correct.mp3" type="audio/mpeg" />
		</audio>
		<audio id="sonido_alertas_incorrecto" preload>
			<source src="includes/sounds/wrong.mp3" type="audio/mpeg" />
		</audio>
		<?php
	if(isset($_GET["cb"])){ 
		$procedencia = 1;
	} else {
		$procedencia = 0;
	}
	if( (isset($_SESSION['datosSalida']) && count($_SESSION['datosSalida'])>0) ){
		modificarCategoriaMat($_SESSION["datosSalida"]);
	}
	?>
		<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-salida">Salida Material</div>

		<?php //Si la variable $txt_deptoSolicitante no esta definida en el arreglo $_POST, entonces mostrar el formulario que solicita los datos de Salida del Material
	if(!isset($_POST['txt_deptoSolicitante'])){ ?>

		<script type="text/javascript" language="javascript">
			setTimeout("document.getElementById('txt_codBarTrabajador').focus();", 500); 
			<?php
			if (isset($_POST["id_empl"])) {
				$id_empleado = $_POST["id_empl"]; 
			?>
				setTimeout("document.getElementById('txt_codBarTrabajador').value='<?php echo $id_empleado; ?>';", 100);
				setTimeout("document.getElementById('txt_codBarTrabajador').onchange();", 200); 
				<?php
			} ?>
		</script>

		<fieldset id="form-datos-salida" class="borde_seccion">
			<legend class="titulo_etiqueta">Completar Informaci&oacute;n de la Salida de Material</legend>
			<br>
			<form name="frm_datosSalida" id="frm_datosSalida" action="frm_salidaMaterial2.php" method="post"
				onSubmit="return verContFormDatosSalida(this);">
				<table border="0" width="100%" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td width="112">
							<div align="right">C&oacute;digo Trabajador</div>
						</td>
						<td>
							<input type="text" name="txt_codBarTrabajador" id="txt_codBarTrabajador"
								class="caja_de_texto" size="10" maxlength="20"
								onchange="extraerInfoEmpCB(this,<?php echo $procedencia; ?>);"
								onkeypress="return permiteCB(event,'cmb_subcuenta');" tabindex="1" />
						</td>
					</tr>
					<tr>
						<td width="112">
							<div align="right">Depto. Solicitante </div>
						</td>
						<td width="286">
							<?php 
				$conn = conecta("bd_recursos");		
				$stm_sql = "SELECT DISTINCT area FROM empleados WHERE estado_actual = 'ALTA' ORDER BY area";
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
				if($datos = mysql_fetch_array($rs)){?>
							<select name="txt_deptoSolicitante" id="txt_deptoSolicitante" class="combo_box"
								onchange="cargarPersonalRHArea(this.value,'txt_solicitante')" tabindex="2">
								<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
					echo "<option value=''>Departamento</option>";
					do{
						echo "<option value='$datos[area]'>$datos[area]</option>";
					}while($datos = mysql_fetch_array($rs));?>
								<option value="OTRO">OTRO</option>
							</select><?php
				}
				else{
					echo "<label class='msje_correcto'> No hay &Aacute;reas Registradas</label>
						<input type='hidden' name='cmb_area' id='cmb_area'/>";
				}
				//Cerrar la conexion con la BD		
				mysql_close($conn);	
				?>
						</td>

						<td>
							<div align="right">Solicitante</div>
						</td>
						<td colspan="3">
							<span id="datosSolicitante">
								<!--<input name="txt_solicitante" type="text" class="caja_de_texto" size="30" maxlength="60" onkeypress="return permite(event,'num_car');" />-->
								<select name="txt_solicitante" id="txt_solicitante" class="combo_box" tabindex="3">
									<option value="">Solicitante</option>
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<div align="right">Control de Costos</div>
						</td>
						<td>
							<?php 
					$conn = conecta("bd_recursos");		
					$stm_sql = "SELECT * FROM control_costos WHERE habilitado = 'SI' ORDER BY descripcion";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
							<select name="cmb_con_cos" id="cmb_con_cos" class="combo_box"
								onchange="cargarCuentas(this.value,'cmb_cuenta')">
								<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value=''>Control de Costos</option>";
							do{
								echo "<option value='$datos[id_control_costos]'>$datos[descripcion]</option>";
							}while($datos = mysql_fetch_array($rs));?>
							</select>
							<?php
					}
					else{
						echo "<label class='msje_correcto'> No actualmente control de costos</label>
							<input type='hidden' name='cmb_area' id='cmb_area'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);
				?>
						</td>
						<td width="15%">
							<div align="right">Cuenta</div>
						</td>
						<td width="40%">
							<span id="datosCuenta">
								<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box"
									onchange="cargarSubCuentas(cmb_con_cos.value,this.value,'cmb_subcuenta')">
									<option value="">Cuentas</option>
								</select>
							</span>
						</td>
						<?php
			if(!isset($_GET["cb"])){
				?>
						<td width="15%">
							<div align="right">Subcuenta</div>
						</td>
						<td width="40%">
							<span id="datosSubCuenta">
								<select name="cmb_subcuenta" id="cmb_subcuenta" class="combo_box"
									onchange="hdn_subcuenta.value = this.value">
									<option value="">SubCuentas</option>
								</select>
							</span>
						</td>
						<input type="hidden" name="hdn_subcuenta" id="hdn_subcuenta" value="" />
						<?php 
			}
			
			if(isset($_GET["cb"])){
				?>
						<td width="15%">
							<div align="right">Subcuenta</div>
						</td>
						<td width="40%">
							<input type="text" name="cmb_subcuenta" id="cmb_subcuenta"
								onfocus="this.oldvalue = this.value;"
								onchange="extraerInfoSubCuenta(this.value,this.oldvalue,cmb_cuenta.value,cmb_con_cos.value,<?php echo $procedencia; ?>);" />
							<input type="hidden" name="hdn_subcuenta" id="hdn_subcuenta" />
						</td>
						<?php 
			} ?>
					</tr>

					<tr>
						<td>
							<div align="right">Fecha</div>
						</td>
						<td>
							<input name="txt_fechaSalida" id="txt_fechaSalida" type="text" class="caja_de_texto"
								value="<?php echo verFecha(4);?>" readonly="readonly" size="10" maxlength="10"
								onchange="verificarVale(txt_noVale.value,this.value);" />
						</td>
						<td>
							<div align="right">Turno</div>
						</td>
						<td>
							<?php
					$horaActual=date("H");
				?>
							<select name="cmb_turno" class="combo_box">
								<option value="">Seleccionar Turno</option>
								<option value="PRIMERA"
									<?php if($horaActual>=6 && $horaActual<14) echo " selected='selected'";?>>Turno de
									Primera</option>
								<option value="SEGUNDA"
									<?php if($horaActual>=14 && $horaActual<22) echo " selected='selected'";?>>Turno de
									Segunda</option>
								<option value="TERCERA"
									<?php if($horaActual>=22 || $horaActual<6) echo " selected='selected'";?>>Turno de
									Tercera</option>
							</select>
						</td>
					</tr>

					<input name="txt_costoTotal" type="hidden" class="caja_de_num"
						value="<?php echo number_format($costoTotalSalida,2,".",",");?>" disabled="disabled" size="15"
						maxlength="20" />
					<input name="txt_noVale" id="txt_noVale" type="hidden" class="caja_de_texto" size="15"
						maxlength="10" onchange="verificarVale(this.value,txt_fechaSalida.value);"
						value="<?php echo $id_salida; ?>" />

					<tr>
						<?php
			if(isset($_POST["id_empl"])){
				$id_empleado = $_POST['id_empl'];
				$id_kiosco = $_POST['id_kiosco'];
				echo "<input type='hidden' id='id_empl' name='id_empl' value='$id_empleado'/>";
				echo "<input type='hidden' id='id_kiosco' name='id_kiosco' value='$id_kiosco'/>";
			}
			?>
						<td colspan="6" align="center">
							<?php
				if(!isset($_GET["cb"])){
					?>
							<input name="sbt_registrar" type="submit" class="botones" value="Registrar"
								onmouseover="window.status='';return true" title="Registrar Salida de Material"
								tabindex="5" />
							&nbsp;&nbsp;
							<input name="btn_limpiar" type="reset" class="botones" value="Limpiar"
								onmouseover="window.status='';return true" title="Limpiar Formulario" tabindex="6"
								onclick="restablecerComboSalida();txt_codBarTrabajador.focus();" />
							&nbsp;&nbsp;
							<?php 
					if(!isset($_GET["cb"])){?>
							<input name="btn_cancelar" type="button" class="botones" value="Regresar"
								title="Regresar a la P&aacute;gina de Salida de Material" tabindex="7"
								onClick="document.getElementById('frm_datosSalida').action='frm_salidaMaterial.php'; submit();" />
							<?php
					}
					else{
					?>
							<input name="btn_cancelar" type="button" class="botones" value="Regresar"
								title="Regresar a la P&aacute;gina de Salida de Material" tabindex="7"
								onClick="location.href='frm_salidaMaterialBC.php'" />
							<?php
					}
				} 
				?>
						</td>
					</tr>
				</table>
			</form>
		</fieldset>

		<?php 
	//El DIV del calendario se comenta ya que se puede volver a requerir en algun otro momento
	?>

		<div id="ver-calendario">
			<input type="image" onclick="displayCalendar(document.frm_datosSalida.txt_fechaSalida,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25"
				height="25" border="0" />
		</div>


		<div id="registro-material" class="borde_seccion" align="center">
			<?php
		//Verificar que el arreglo de datos haya sido definido
		if(isset($_SESSION['datosSalida'])){
			?>
			<!-- <p align="center" class="titulo_etiqueta">Registro de la Salida de Material No. <?php echo $id_salida; ?></p> -->
			<p align="center" class="titulo_etiqueta">Registro de la Salida de Material</p>
			<?php mostrarRegistros($datosSalida,2);
		}		
		
	}//Cierre del if(!isset($_POST['txt_deptoSolicitante']))
	else{
		//Guardar los datos de la salida de material
		//guardarCambios($txt_deptoSolicitante,$txt_solicitante,$txt_destino,$txt_fechaSalida,$cmb_turno,$txt_noVale);
		guardarCambios($txt_deptoSolicitante,$txt_solicitante,$cmb_con_cos,$txt_fechaSalida,$cmb_turno,$txt_noVale,$cmb_cuenta,$hdn_subcuenta,$cmb_tipoMoneda);
		?>
			<div class="titulo_etiqueta" id="procesando">
				<div align="center">
					<p><img src="../../images/loading.gif" width="70" height="70" /></p>
					<p>Procesando...</p>
				</div>
			</div>
			<?php
	}?>
		</div>
	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>

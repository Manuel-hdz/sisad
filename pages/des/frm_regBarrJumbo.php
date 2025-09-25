<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
	
		include("op_gestionarBitacoras.php");?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

		<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
		<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
		<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
		<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
		<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112"
			media="screen">
		<script type="text/javascript" src="../../includes/validacionDesarrollo.js"></script>
		<script type="text/javascript" src="includes/ajax/fallasConsumosTNT.js"></script>
		<script type="text/javascript" language="javascript">
			//Esta variable guardar� la referencia de la p�gina de Modificar Registro Fallas/Consumos para detectar cuando �sta sea crerrada.
			var vtnAbierta = "";
			//Al cargar la pagina colocar el foco la caja de texto donde ira el nombre de Jumbero
			setTimeout("document.frm_barrenacionJumbo.txt_jumbero.focus();", 500);
		</script>

		<style type="text/css">
			#titulo-barrJumbo {
				position: absolute;
				left: 30px;
				top: 146px;
				width: 350px;
				height: 20px;
				z-index: 11;
			}

			#form-registrarDatos {
				position: absolute;
				left: 30px;
				top: 190px;
				width: 940px;
				height: 430px;
				z-index: 12;
			}

			#res-spider1 {
				position: absolute;
				left: 100px;
				top: 40px;
				width: 10px;
				height: 183px;
				z-index: 13;
			}

			#res-spider2 {
				position: absolute;
				left: 100px;
				top: 70px;
				width: 10px;
				height: 183px;
				z-index: 14;
			}

			#res-spider3 {
				position: absolute;
				left: 100px;
				top: 95px;
				width: 10px;
				height: 183px;
				z-index: 14;
			}

			#calendario {
				position: absolute;
				left: 920px;
				top: 212px;
				width: 30px;
				height: 27px;
				z-index: 15;
			}
		</style>


	</head>

	<body onfocus="verificarCierreVtn();">

		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div id="titulo-barrJumbo" class="titulo_barra">Registro de Barrenaci&oacute;n con Jumbo</div>
		<?php
	
	if(!isset($_POST['sbt_guardar'])){?>

		<fieldset class="borde_seccion" id="form-registrarDatos" name="form-registrarDatos" style="height:650px;">
			<legend class="titulo_etiqueta">Ingresar la Informaci&oacute;n del Registro de Barrenaci&oacute;n con Jumbo
			</legend>
			<form onsubmit="return valFormBarrenacionJumbo(this);" name="frm_barrenacionJumbo" method="post"
				action="frm_regBarrJumbo.php">
				<table width="100%" cellspacing="5">
					<tr>
						<td align="right">*Jumbero</td>
						<td colspan="3">
							<input name="txt_jumbero" type="text" class="caja_de_texto" id="txt_jumbero" tabindex="1"
								onkeypress="return permite(event,'car',0);"
								onkeyup="lookup(this,'empleados','1','OP. JUMBO');" value="" size="50" maxlength="80" />
							<div id="res-spider1">
								<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
									<img src="../../images/upArrow.png"
										style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
									<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
								</div>
							</div>
							<?php //Esta variable 'hdn_rfc' guarda el RFC del empleado seleccionado en la Busqueda Sphider ?>
							<input type="hidden" name="hdn_rfc" id="hdn_rfc" value="" />
							<input type="hidden" name="hdn_idEmp" id="hdn_idEmp" value="" />
						</td>
						<td align="right">*Turno</td>
						<td colspan="2">
							<select name="cmb_turno" id="cmb_turno" class="combo_box" tabindex="2">
								<option value="">Turno</option>
								<option value="PRIMERA">PRIMERA</option>
								<option value="SEGUNDA">SEGUNDA</option>
								<option value="TERCERA">TERCERA</option>
							</select>
						</td>
						<td align="right">Fecha Registro</td>
						<td>
							<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto"
								readonly="readonly" size="10" value="<?php echo date(" d/m/Y"); ?>" />
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="right">*Ayudante</td>
						<td colspan="3">
							<input name="txt_ayudante" type="text" class="caja_de_texto" id="txt_ayudante" tabindex="4"
								onkeypress="return permite(event,'car',0);" onkeyup="lookup(this,'empleados','2','');"
								value="" size="50" maxlength="80" />
							<div id="res-spider2">
								<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
									<img src="../../images/upArrow.png"
										style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
									<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
								</div>
							</div>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="right">
							<input type="checkbox" name="ckb_ayudante" id="ckb_ayudante" value="activo"
								onclick="activarCamposForm(this,'txt_ayudante2');" tabindex="10" />*Ayudante
						</td>
						<td colspan="3">
							<input name="txt_ayudante2" type="text" class="caja_de_texto" id="txt_ayudante2"
								tabindex="4" onkeypress="return permite(event,'car',0);"
								onkeyup="lookup(this,'empleados','3','');" value="" size="50" maxlength="80"
								readonly="readonly" />
							<div id="res-spider3">
								<div align="left" class="suggestionsBox" id="suggestions3" style="display: none;">
									<img src="../../images/upArrow.png"
										style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
									<div class="suggestionList" id="autoSuggestionsList3">&nbsp;</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td align="right">*Equipo</td>
						<td>
							<?php								
					$conn = conecta("bd_mantenimiento");//Conectarse a la Base de Datos
					$result=mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE familia = 'JUMBOS' AND disponibilidad = 'ACTIVO' ORDER BY id_equipo");
						
					if($registro=mysql_fetch_array($result)){?>
							<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="5">
								<option value="">Equipo</option>
								<?php															 
							do{?>
								<option value="<?php echo $registro['id_equipo']; ?>"
									title="<?php echo $registro['id_equipo']; ?>">
									<?php echo $registro['id_equipo']; ?>
								</option>
								<?php
							}while($registro=mysql_fetch_array($result))?>
							</select>
							<?php
					} else {?>
							<span class="msje_correcto">No Hay Equipos Registrados</span>
							<input type="hidden" name="cmb_equipo" id="cmb_equipo" value="" />
							<?php
					}
					mysql_close($conn);//Cerrar la conexion con la BD ?>
						</td>
						<td colspan="1" align="right">*Hor&oacute;metro Inicial</td>
						<td>
							<input type="text" name="txt_HIEquipo" id="txt_HIEquipo" class="caja_de_texto" size="9"
								maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="6"
								onblur="calcularHorasTotales('txt_HIEquipo','txt_HFEquipo','txt_HTEquipo');"
								onchange="formatCurrency(this.value,'txt_HIEquipo');" />
						</td>
						<td colspan="2" align="right">*Hor&oacute;metro Final</td>
						<td>
							<input type="text" name="txt_HFEquipo" id="txt_HFEquipo" class="caja_de_texto" size="9"
								maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="7"
								onblur="calcularHorasTotales('txt_HIEquipo','txt_HFEquipo','txt_HTEquipo');"
								onchange="formatCurrency(this.value,'txt_HFEquipo');" />
						</td>
						<td align="right">Hrs. Totales</td>
						<td><input type="text" name="txt_HTEquipo" id="txt_HTEquipo" class="caja_de_texto" size="9"
								readonly="readonly" /></td>
					</tr>
					<tr>
						<td align="right">Brazo 1</td>
						<td>
							*HI<input type="text" name="txt_HIB1" id="txt_HIB1" class="caja_de_texto" size="9"
								maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="8"
								onblur="calcularHorasTotales('txt_HIB1','txt_HFB1','txt_HTB1');"
								onchange="formatCurrency(this.value,'txt_HIB1');" />
						</td>
						<td>
							*HF<input type="text" name="txt_HFB1" id="txt_HFB1" class="caja_de_texto" size="9"
								maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="9"
								onblur="calcularHorasTotales('txt_HIB1','txt_HFB1','txt_HTB1');"
								onchange="formatCurrency(this.value,'txt_HFB1');" />
						</td>
						<td>HT<input type="text" name="txt_HTB1" id="txt_HTB1" class="caja_de_texto" size="9"
								readonly="readonly" /></td>
						<td>&nbsp;</td>
						<td align="right">
							<input type="checkbox" name="ckb_brazo2" id="ckb_brazo2" value="activo"
								onclick="activarCampos(this);" tabindex="10" />Brazo
							2
						</td>
						<td>
							**HI<input type="text" name="txt_HIB2" id="txt_HIB2" class="caja_de_texto" size="9"
								maxlength="15" onblur="calcularHorasTotales('txt_HIB2','txt_HFB2','txt_HTB2');"
								onkeypress="return permite(event,'num',2);" readonly="readonly"
								onchange="formatCurrency(this.value,'txt_HIB2');" />
						</td>
						<td>
							**HF<input type="text" name="txt_HFB2" id="txt_HFB2" class="caja_de_texto" size="9"
								maxlength="15" onblur="calcularHorasTotales('txt_HIB2','txt_HFB2','txt_HTB2');"
								onkeypress="return permite(event,'num',2);" readonly="readonly"
								onchange="formatCurrency(this.value,'txt_HFB2');" />
						</td>
						<td>HT<input type="text" name="txt_HTB2" id="txt_HTB2" class="caja_de_texto" size="9"
								readonly="readonly" /></td>
						<td>&nbsp;</td>
					</tr>
					<?php for($i=0;$i<2;$i++){ ?>
					<tr>
						<td colspan="8" align="left"><span class="titulo_etiqueta">Barrenacion
								<?php echo $i+1;?></span><input type="checkbox"
								title="Activelo para que el Registro de Voladura sea guardado"
								name="ckb_activarBarr<?php echo $i;?>" id="ckb_activarBarr<?php echo $i;?>" /></td>
					</tr>
					<tr>
						<td align="right">*Barrenos Dados </td>
						<td>
							<input type="text" name="txt_barrDados<?php echo $i;?>" id="txt_barrDados<?php echo $i;?>"
								class="caja_de_texto" size="9" maxlength="15"
								onkeypress="return permite(event,'num',2);" tabindex="13" />
						</td>
						<td align="right">*Barrenos Desborde </td>
						<td>
							<input type="text" name="txt_barrDesborde<?php echo $i;?>"
								id="txt_barrDesborde<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15"
								onkeypress="return permite(event,'num',2);" tabindex="16" />
						</td>
						<td align="right">*Barrenos Encapille </td>
						<td>
							<input type="text" name="txt_barrEncapille<?php echo $i;?>"
								id="txt_barrEncapille<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15"
								onkeypress="return permite(event,'num',2);" tabindex="17" />
						</td>
						<td align="right">*Barrenos Despate </td>
						<td>
							<input type="text" name="txt_barrDespate<?php echo $i;?>"
								id="txt_barrDespate<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15"
								onkeypress="return permite(event,'num',2);" tabindex="18" />
						</td>
						<!--<td align="right">*Disparos</td>
				<td>
					<input type="text" name="txt_disparos<?php echo $i;?>" id="txt_disparos<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="14" />
				</td>
				<td align="right">*Longitud</td>
				<td>
					<input type="text" name="txt_longitud<?php echo $i;?>" id="txt_longitud<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="15" />
				</td>-->
						<input type="hidden" name="txt_disparos<?php echo $i;?>" id="txt_disparos<?php echo $i;?>"
							value="0" />
						<input type="hidden" name="txt_longitud<?php echo $i;?>" id="txt_longitud<?php echo $i;?>"
							value="0" />
						<input type="hidden" name="txt_coples<?php echo $i;?>" id="txt_coples<?php echo $i;?>"
							value="0" />
						<input type="hidden" name="txt_zancos<?php echo $i;?>" id="txt_zancos<?php echo $i;?>"
							value="0" />
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="right">*Reanclaje</td>
						<td>
							<input type="text" name="txt_reanclaje<?php echo $i;?>" id="txt_reanclaje<?php echo $i;?>"
								class="caja_de_texto" size="9" maxlength="15"
								onkeypress="return permite(event,'num',2);" tabindex="19" />
						</td>
						<td width="10%" align="right">*Anclas</td>
						<td width="10%">
							<input type="text" name="txt_anclas<?php echo $i;?>" id="txt_anclas<?php echo $i;?>"
								class="caja_de_texto" size="9" maxlength="15"
								onkeypress="return permite(event,'num',2);" tabindex="22" />
						</td>
						<td width="10%" align="right">*Escareado</td>
						<td width="10%">
							<input type="text" name="txt_escareado<?php echo $i;?>" id="txt_escareado<?php echo $i;?>"
								class="caja_de_texto" size="9" maxlength="15"
								onkeypress="return permite(event,'num',2);" tabindex="23" />
						</td>
						<td width="10%" align="right">*Topes Barrenados</td>
						<td width="10%">
							<input type="text" name="txt_topesBarrenados<?php echo $i;?>"
								id="txt_topesBarrenados<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15"
								onkeypress="return permite(event,'num',2);" tabindex="24" />
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<!--<td width="10%" align="right">*Coples</td>
				<td width="10%">
					<input type="text" name="txt_coples<?php echo $i;?>" id="txt_coples<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="20" />
				</td>
				<td width="10%" align="right">*Zancos</td>
				<td width="10%">
					<input type="text" name="txt_zancos<?php echo $i;?>" id="txt_zancos<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="21" />
				</td>-->

					</tr>
					<tr>
						<td align="right">Observaciones</td>
						<td colspan="3">
							<textarea name="txa_observaciones<?php echo $i;?>" onkeyup="return ismaxlength(this)"
								maxlength="120" class="caja_de_texto" rows="3" cols="35"
								onkeypress="return permite(event,'num_car',0);" tabindex="25"></textarea>
						</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td colspan="9"><strong>*Los datos marcados con asterisco (*) son obligatorios.</strong></td>
					</tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td colspan="9"><strong>**Los datos marcados con doble asterisco (**) son obligatorios
								s&oacute;alo si el Jumbo
								tiene 2 Brazos</strong></td>
					</tr>
					<?php } ?>
					<tr>
						<td align="right">&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td align="center" colspan="10">
							<?php /*Estas variables ayudan a identificar cual de las Bit�coras (Avance y Retro-Bull) ser� registrada en las Bitacoras de Fallas y Consumos
					asi como el tipo de registro (Bitacora de Barrenaci�n, Voladura y Rezagado*/ ?>
							<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora"
								value="<?php echo $_POST['hdn_idBitacora']; ?>" />
							<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora"
								value="<?php echo $_POST['hdn_tipoBitacora']; ?>" />
							<input type="hidden" name="hdn_tipoRegistro" id="hdn_tipoRegistro" value="BARRENACION" />
							<?php //Esta variable ayudara a determinar el tipo de Falla que sera registrada en la Bitacora de Fallas?>
							<input type="hidden" name="hdn_tipoEquipo" id="hdn_tipoEquipo" value="JUMBO" />

							<?php //Esta variable indica si fueron agregados los cosumos del equipo, en el caso que no se notifica al usuario sobre este hecho?>
							<input type="hidden" name="hdn_regBitConsumos" id="hdn_regBitConsumos" value="no" />
							<?php //Esta variable indica sobre cual equipo se registraron fallas y ayuda a que el usuario no cambie el equipo seleccionado antes de guardar?>
							<input type="hidden" name="hdn_fallasEquipo" id="hdn_fallasEquipo" value="" />


							<input type="submit" name="sbt_guardar" value="Guardar" class="botones"
								title="Guardar Datos en la Bit&aacute;cora" onmouseover="window.status='';return true"
								tabindex="26" />
							&nbsp;&nbsp;
							<input name="btn_regFallas" id="btn_regFallas" type="button" class="botones_largos"
								value="Registrar Fallas" title="Registrar Fallas de los Equipos"
								onmouseover="window.status='';return true" onclick="abrirVentana('fallas','agregar');"
								tabindex="27" />
							&nbsp;&nbsp;
							<input name="btn_regConsumos" id="btn_regConsumos" type="button" class="botones_largos"
								value="Registrar Consumos" title="Registrar Consumos Realizados"
								onmouseover="window.status='';return true" onclick="abrirVentana('consumos','agregar');"
								tabindex="28" />
							&nbsp;&nbsp;
							<input type="reset" name="rst_limpiar" value="Limpiar" class="botones"
								title="Limpiar los Campos del Formulario" tabindex="29" />
							&nbsp;&nbsp;
							<input type="button" name="btn_cancelar" value="Cancelar" class="botones"
								title="Regresar al Registro de la Bit&aacute;cora de Avance"
								onclick="cancelarOperacion(hdn_idBitacora.value,hdn_tipoBitacora.value,hdn_tipoRegistro.value,'frm_regAvance.php');"
								tabindex="30" />
						</td>
					</tr>
				</table>
			</form>
		</fieldset>

		<div id="calendario">
			<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_barrenacionJumbo.txt_fechaRegistro,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom"
				title="Seleccionar Fecha de Registro" tabindex="3" />
		</div>
		<?php
	}//Cierre if(!isset($_POST['sbt_guardar'])) 
	else{
		//Guardar los datos de la Bit�cora en la Base de Datos
		guardarBitBarrenacion();
	}?>

	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
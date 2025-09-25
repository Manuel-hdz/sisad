<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Gerencia T�cnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include("op_agregarUsuario.php");
	?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<script type="text/javascript" src="../../includes/validacionCPanel.js"></script>
		<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
		<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
		<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

		<style type="text/css">
			#titulo-registrar {
				position: absolute;
				left: 30px;
				top: 35px;
				width: 298px;
				height: 20px;
				z-index: 11;
			}

			#tabla-registrarUsuario {
				position: absolute;
				left: 30px;
				top: 80px;
				width: 742px;
				height: 245px;
				z-index: 12;
			}

			#usuarios {
				position: absolute;
				left: 30px;
				top: 350px;
				width: 742px;
				height: 290px;
				z-index: 11;
				overflow: scroll;
			}

			#res-spider {
				position: absolute;
				z-index: 15;
			}
		</style>
	</head>

	<body>

		<div id="barraCP"><img src="../../images/title-bar-bg.gif" width="100%" height="30" /></div>
		<div class="titulo_barra" id="titulo-registrar">Agregar Usuarios</div>

		<fieldset class="borde_seccion" id="tabla-registrarUsuario" name="tabla-registrarUsuario">
			<legend class="titulo_etiqueta">Ingresar Informaci&oacute;n del Usuario</legend>
			<form name="frm_agregarUsuario" method="post" action="frm_agregarUsuario.php"
				onsubmit="return valFormUsuarios(this);">
				<table width="741" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td>
							<div align="right">*Trabajador</div>
						</td>
						<td colspan="3">
							<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados','1');"
								value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);"
								tabindex="1" />
							<div id="res-spider">
								<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
									<img src="../../images/upArrow.png"
										style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
									<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td width="128">
							<div align="right">**Nombre de Usuario</div>
						</td>
						<td>
							<input name="txt_usuario" id="txt_usuario" type="text" class="caja_de_texto" size="15"
								value=""
								onblur="return verificarDatoBD(this,'bd_usuarios','usuarios','usuario','depto');" />
							<span id="error" class="msj_error">Usuario Duplicado</span>
						</td>
						<td width="146">
							<div align="right">**Contrase&ntilde;a</div>
						</td>
						<td><input type="password" class="caja_de_texto" name="txt_pass" id="txt_pass" size="15"
								value="" onchange="validarFortaleza(this);txt_passConfirm.value='';" /><label
								id="fortaleza"></label></td>
					</tr>
					<tr>
						<td width="128">
							<div align="right">*Tipo de Usuario</div>
						</td>
						<td width="237">
							<select name="cmb_tipo" id="cmb_tipo" size="1" class="combo_box">
								<option value="" selected="selected">Tipo</option>
								<option value="administrador">ADMINISTRADOR</option>
								<option value="auxiliar">AUXILIAR</option>
								<option value="externo">EXTERNO</option>
							</select>
						</td>
						<td width="146">
							<div align="right">**Confirmar Contrase&ntilde;a</div>
						</td>
						<td width="163" colspan="2">
							<input type="password" class="caja_de_texto" name="txt_passConfirm" id="txt_passConfirm"
								size="15" value="" onchange="validarPass(txt_pass,txt_passConfirm);" />
						</td>
					</tr>
					<tr>
						<td width="128">
							<div align="right">*Departamento</div>
						</td>
						<td colspan="3">
							<select name="cmb_depto" id="cmb_depto" size="1" class="combo_box">
								<option value="" selected="selected">Departamento</option>
								<option value="Almacen">ALMACEN</option>
								<option value="Compras">COMPRAS</option>
								<option value="Contabilidad">Contabilidad</option>
								<option value="MttoConcreto">MANTENIMIENTO CONCRETO</option>
								<option value="MttoMina">MANTENIMIENTO MINA</option>
								<option value="MttoElectrico">MANTENIMIENTO EL&Eacute;CTRICO</option>
								<option value="RecursosHumanos">RECURSOS HUMANOS</option>
								<option value="Topografia">TOPOGRAFIA</option>
								<option value="Laboratorio">LABORATORIO</option>
								<option value="Lampisteria">LAMPISTERIA</option>
								<option value="Produccion">PRODUCCION</option>
								<option value="GerenciaTecnica">GERENCIA TECNICA</option>
								<option value="Desarrollo">DESARROLLO</option>
								<option value="Calidad">ASEGURAMIENTO CALIDAD</option>
								<option value="SeguridadIndustrial">SEGURIDAD INDUSTRIAL</option>
								<option value="SeguridadAmbiental">SEGURIDAD AMBIENTAL</option>
								<option value="Clinica">UNIDAD SALUD OCUPACIONAL</option>
								<option value="Comaro">COMARO</option>
								<option value="Sistemas">SISTEMAS</option>
								<option value="SupervisionDes">SUPERVISION DESARROLLO</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="5">
							<strong>
								* Datos marcados con asterisco son <u>obligatorios</u><br>
								** Datos <u>Obligatorios</u> y Sensibles a <u>May&uacute;sculas</u> y
								<u>Min&uacute;sculas</u>
							</strong>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<div align="center">
								<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
								<input type="hidden" name="hdn_fortaleza" id="hdn_fortaleza" value="" />
								<input name="sbt_guardar" type="submit" class="botones" value="Guardar"
									title="Guardar al Usuario Registrado" onmouseover="window.status='';return true" />
								&nbsp;&nbsp;&nbsp;
								<input type="reset" name="btn_borrar" class="botones" value="Limpiar"
									title="Reestablecer el Formulario"
									onclick="error.style.visibility='hidden';fortaleza.innerHTML =''" />
								&nbsp;&nbsp;&nbsp;
								<input name="btn_regresar" type="button" class="botones" value="Regresar"
									title="Regresar al Inicio" onMouseOver="window.status='';return true"
									onclick="location.href='main.php';" />
							</div>
						</td>
					</tr>
				</table>
			</form>
		</fieldset>

		<?php 
		//Si esta definido el boton de Guardar, almacenar la informacion
		//del nuevo Usuario en la BD
		if (isset($_POST["sbt_guardar"])){
			$estado=agregarUsuario();
			if ($estado==1){
				?>
		<script type="text/javascript" language="javascript">
			setTimeout("mensaje()", 500);

			function mensaje() {
				alert("Usuario Agregado a la Base de Datos");
			}
		</script>
		<?php
			}
			else{
				?>
		<script type="text/javascript" language="javascript">
			setTimeout("mensaje()", 500);

			function mensaje() {
				alert("Ocurri� el Siguiente Error: <?php echo $estado;?>");
			}
		</script>
		<?php
			}
		}
		//Mostrar a los Usuarios Registrados
		echo "<div class='borde_seccion' id='usuarios'>";
		mostrarUsuarios();
		echo "</div>";
	?>
	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
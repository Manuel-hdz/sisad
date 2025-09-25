<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include("op_modificarPassword.php");
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionCPanel.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="includes/ajax/validarPassword.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<style type="text/css">
		<!--
		#titulo-password {position:absolute;left:30px;top:35px;width:298px;height:20px;z-index:11;}
		#tabla-ingresarPassword {position:absolute;left:30px;top:80px;width:742px;height:220px;z-index:12;}
		-->
	</style>
	</head>
	<body>

	<div id="barraCP"><img src="../../images/title-bar-bg.gif" width="100%" height="30"/></div>
	<div class="titulo_barra" id="titulo-password">Modificar Contrase&ntilde;a</div>
	
	<?php
		if (isset($_POST["sbt_modificar"])){
			$res=modificarPassword($_POST["txt_pass"]);
			if ($res==1){
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("mensaje()",500);
					function mensaje(){
						alert("¡Contraseña Modificada con Éxito!");
					}
				</script>
				<?php
			}
			else{
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("mensaje()",500);
					function mensaje(){
						alert("Ocurrió el Siguiente Error: <?php echo $estado;?>");
					}
				</script>
				<?php
			}
		}
	?>	
	
	<fieldset class="borde_seccion" id="tabla-ingresarPassword" name="tabla-ingresarPassword">
	<legend class="titulo_etiqueta">Ingresar Datos de Contrase&ntilde;a</legend>
	<br>
	<form name="frm_modificarPassword" method="post" action="frm_modificarPassword.php" onsubmit="return valFormPassword(this);">
	<table width="741" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="180"><div align="right">*Contrase&ntilde;a Actual</div></td>
			<td><input type="password" class="caja_de_texto" name="txt_passAct" id="txt_passAct" size="15" value="" onchange="verificarPassword(this.value);"/><span id="error" class="msj_error">Clave Incorrecta</span></td>
		</tr>
		<tr>
		<td width="180"><div align="right">*Contrase&ntilde;a</div></td>
			<td><input type="password" class="caja_de_texto" name="txt_pass" id="txt_pass" size="15" value="" 
				onchange="validarFortaleza(this);txt_passConfirm.value='';"/><label id="fortaleza"></label></td>
		</tr>     
		<tr>
			<td width="180"><div align="right">*Confirmar Contrase&ntilde;a</div></td>
		  <td width="524"><input type="password" class="caja_de_texto" name="txt_passConfirm" id="txt_passConfirm" size="15" value="" onchange="validarPass(txt_pass,txt_passConfirm);"/></td>
		</tr>
		<tr>
		<td colspan="2">
				<strong>* Datos marcados con asterisco son <u>obligatorios</u></strong>
			</td>
		</tr>
		<tr>
			<td colspan="6">
				<div align="center">
					<input type="hidden" name="hdn_fortaleza" id="hdn_fortaleza" value=""/>
					<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value=""/>
					<input name="sbt_modificar" type="submit" class="botones" value="Modificar" title="Modificar la Contrase&ntilde;a del Usuario" 
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_borrar" class="botones" value="Limpiar" title="Reestablecer el Formulario" onclick="error.style.visibility='hidden';fortaleza.innerHTML =''"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Inicio" 
					onMouseOver="window.status='';return true" onclick="location.href='main.php';" />
				</div>			</td>
		</tr>
	</table>
	</form>
	</fieldset>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
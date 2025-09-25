<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	include ("op_agregarConsumibles.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionSistemas.js" ></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#agregar-consumibles {position:absolute;left:30px;top:190px;width:600px;height:140px;z-index:12;}
		#tabla-consumibles {position:absolute;left:30px;top:360px;width:920px;height:310px;z-index:12; overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Agregar Consumibles de Impresoras</div>
		
	<?php
	//Verificar si se debe guardar un registro
	if(isset($_POST["sbt_guardar"])){
		agregarConsumibles();
	}
	?>
	<fieldset class="borde_seccion" id="agregar-consumibles" name="agregar-consumibles">
	<legend class="titulo_etiqueta">Ingresar Datos del Toner o Cartucho</legend>	
	<br>
	<form name="frm_aregarConsumibles" method="post" action="frm_aregarConsumibles.php" onsubmit="valFormAgregarConsumibles(this)">
		<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<tr>
			<td><div align="right">*Descripcion</div></td>
			<td>
				<input type='text' name='txt_descripcion' id='txt_descripcion' class='caja_de_texto' size='30' maxlength="30" required="required"/>
			</td>
			<td><div align="right">*Color</div></td>
			<td>
				<input type='text' name='txt_color' id='txt_color' class='caja_de_texto' size='10' maxlength="10" required="required"/>
			</td>
			<td><div align="right">*Tipo</div></td>
			<td>
				<select name="txt_tipo" id="txt_tipo" class="combo_box" required="required">
					<option value="">Tipo</option>
					<option value="TONER">Toner</option>
					<option value="CARTUCHO">Cartucho</option>
					<option value="RIBBON">Ribbon</option>
					<option value="TARJETA">Tarjeta</option>
					<option value="ALMACENAMIENTO">Almacenamiento</option>
				</select>
			</td>
			<td><div align="right">*Impresora</div></td>
			<td>
				<input type='text' name='txt_impresora' id='txt_impresora' class='caja_de_texto' size='30' maxlength="30" required="required"/>
			</td>
		</tr>
		<tr>
			<td colspan="8"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
		</tr>
		<tr>
			<td colspan="8" align="center">
				<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Guardar" title="Registrar el Consumible;" onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; de Consumibles" onclick="location.href='menu_consumibles.php'"/>
			</td>
		</tr>
		</table>
	</form>
	</fieldset>
	<div id="tabla-consumibles" class="borde_seccion2" align="center"> 
		<?php
		mostrarConsumibles();
		?>
	</div>
	<script>txt_descripcion.focus();</script>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
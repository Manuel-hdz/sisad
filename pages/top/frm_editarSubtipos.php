<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
//	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
//		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
//	}
//	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_gestionSubtipos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:210px;height:20px;z-index:11;}
		#tabla-agregarPlano {position:absolute;left:30px;top:190px;width:650px;height:190px;z-index:12;}
		#resultados{position:absolute;left:30px;top:410px;width:890px;height:220px;z-index:13; overflow:scroll;}
		#boton{position:absolute;left:30px;top:680px;width:930px;height:40px;z-index:14;}
		-->
    </style>
	
</head>
<body>
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Editar Lista de Categor&iacute;as</div>
	
	<?php
	if(isset($_POST["sbt_guardar"]))
		agregarSubtipo();
	if(isset($_POST["sbt_modificar"]))
		modificarSubtipos();
	?>
	
	<fieldset class="borde_seccion" id="tabla-agregarPlano" name="tabla-agregarPlano">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Subtipo</legend>	
	<br>
	<form onsubmit="return valFormEditarSubtipos(this);" name="frm_editarSubtipos" method="post" action="frm_editarSubtipos.php">
	<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
	<tr>
		<td width="122"><div align="right">&Oacute;rden</div></td>
		<td>
			<input name="txt_orden" id="txt_orden" type="text" class="caja_de_num" size="10" value="<?php echo obtenerOrden()?>" readonly="readonly" />
		</td>
		<td><div align="right">*Nombre Subtipo</div></td>
		<td>
			<input name="txt_nombreSubtipo" type="text" class="caja_de_texto" id="txt_nombreSubtipo" onkeypress="return permite(event,'num_car',0);" value="" size="20" 
			maxlength="20" tabindex="1"/>
		</td>
	</tr>
	<tr>
		<td><div align="right">*Precio Unitario M.N. Estimaci&oacute;n</div></td>
		<td width="165">$
			<input name="txt_precioEstimacionMN" id="txt_precioEstimacionMN" type="text" class="caja_de_texto" onkeypress="return permite(event,'num',2);" 
			value="0.00" onchange="formatCurrency(value,'txt_precioEstimacionMN')" tabindex="2" /></td>
		<td width="132"><div align="right">*Precio Unitario USD Estimaci&oacute;n</div></td>
		<td width="194">$
			<input name="txt_precioEstimacionUSD" id="txt_precioEstimacionUSD" type="text" class="caja_de_texto" 
			onkeypress="return permite(event,'num',2);" value="0.00" onchange="formatCurrency(value,'txt_precioEstimacionUSD')" tabindex="3" /></td>
	</tr>
	<tr>
		<td valign="top"><div align="right">Secci&oacute;n</div></td>
		<td>
			<input name="txt_seccion" id="txt_seccion" type="text" class="caja_de_texto" size="10" maxlength="10" value="" onkeypress="return permite(event,'num',6);" 
			onchange="calcularArea();" tabindex="4" />
		</td>
		<td><div align="right">&Aacute;rea</div></td>
		<td><input name="txt_area" id="txt_area" type="text" class="caja_de_texto" value="" readonly="readonly"/></td>
	</tr>
	<tr>
		<td colspan="4">
			<div align="center"> 
				<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Plano"
				onMouseOver="window.status='';return true" tabindex="5"/>
				&nbsp;&nbsp;&nbsp;
				<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true" tabindex="6"/> 
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a Registrar Obras" 
				onmouseover="window.status='';return true" onclick="location.href='frm_registrarObra.php'" tabindex="7"/>
			</div>
		</td>
	</tr>
	</table>
	</form>
	</fieldset>
	
	<form name="frm_actualizarSubtipos" onsubmit="return valFormSubtipos(this)" action="frm_editarSubtipos.php" method="post">
	<div id="resultados" align="center" class="borde_seccion2">
		<?php $res=mostrarSubtipos();?>
	</div>
	
	<?php
	if($res==1){
	?>
	<div id="boton" align="center">
		<input type="submit" name="sbt_modificar" id="sbt_modificar" value="Actualizar" title="Actualizar los datos de los Subtipos" class="botones" 
		onmouseover="window.status='';return true"/>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="reset" name="btn_limpiar" id="btn_limpiar" value="Restablecer" title="Restablece los Datos Siempre y Cuando NO se Hayan Guardado A&uacute;n" class="botones" onclick="restablecerFormSubtipos()"/>
	</div>
	<?php
	}
	?>
	</form>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
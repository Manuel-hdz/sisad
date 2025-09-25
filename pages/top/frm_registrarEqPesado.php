<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarEquipo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:252px;height:20px;z-index:11;}
		#tabla-registrarObra {position:absolute;left:30px;top:190px;width:723px;height:263px;z-index:12;}
		#calendarioObra {position:absolute;left:670px;top:232px;width:30px;height:26px;z-index:13;}
		-->
    </style>
	
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Registrar Obra de Equipo Pesado</div>
	<?php
	//Obtener el id de la estimación según el registro correspondiente en la BD
	$txt_idObra = obtenerIdObraEq();

	$txt_fechaRegistro= date("d/m/Y");?>
	    		
		
	<fieldset class="borde_seccion" id="tabla-registrarObra" name="tabla-registrarObra">
	<legend class="titulo_etiqueta">Ingrese Informaci&oacute;n del Registro </legend>	
	<br>
	<form onSubmit="return valFormRegObraEP(this);" name="frm_registrarObraEP" method="post" action="op_registrarEquipo.php">
		<table width="731" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  	<td width="123"><div align="right">Id Registro</div></td>
			  	<td width="207"><input name="txt_idObra" id="txt_idObra" type="text" class="caja_de_texto" size="10" maxlength="10" value="<?php echo $txt_idObra;?>"  readonly="readonly"/></td>
			  	<td width="154"><div align="right">Fecha Registro</div></td>
			  	<td width="180">
					<input name="txt_fechaRegistro" id="txt_fechaRegistro" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly"
					value="<?php echo $txt_fechaRegistro; ?>" />				</td>
			</tr>
			<tr>
			  <td><div align="right">*Tipo Equipo</div></td>
			  <td>
				<?php $result = cargarComboEspecifico("cmb_familia","familia","equipos","bd_mantenimiento","MINA","area","Equipo","");
				if($result==0){ ?>
				<select name="cmb_familia" id="cmb_familia" class="combo_box">
						<option value="">Equipo</option>
				</select>
				<?php }?>
			  </td>
			  <td><div align="right">*Unidad</div></td>
				<td><input name="txt_unidad" id="txt_unidad" type="text" class="caja_de_texto" value="HRS" onkeypress="return permite(event,'num_car',2);" tabindex="2" /></td>
			</tr>
			<tr>
			  <td><div align="right">*Precio Unitario M.N. Estimaci&oacute;n</div></td>
			  <td>$
			    <input name="txt_precioEstimacionMN" id="txt_precioEstimacionMN" type="text" class="caja_de_texto" onkeypress="return permite(event,'num',2);" 
					value="0.00" onchange="formatCurrency(value,'txt_precioEstimacionMN')" tabindex="4" />              </td>
				<td><div align="right">*Precio Unitario USD Estimaci&oacute;n</div></td>
				<td>$
				  <input name="txt_precioEstimacionUSD" id="txt_precioEstimacionUSD" type="text" class="caja_de_texto" 
					onkeypress="return permite(event,'num',2);" value="0.00" onchange="formatCurrency(value,'txt_precioEstimacionUSD')" tabindex="6" />                </td>
			</tr>
			<tr>
			  <td><div align="right">*Nombre de la Obra de Equipo Pesado </div></td>
			  <td colspan="3"><input name="txt_nombreObraEqP" type="text" class="caja_de_texto" id="txt_nombreObraEqP" onkeypress="return permite(event,'num_car',0);" value="" size="80" 
					maxlength="80" tabindex="7" onblur="verificarDatoBD(this,'bd_topografia','equipo_pesado','concepto','id_registro');" />
                  <span id="error" class="msj_error">Nombre Ya Registrado</span>
			  </td>
			</tr>
			<tr>
				<td colspan="4"><div align="left"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></div></td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/>
					<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Obra de Equipo Pesado" 
					onmouseover="window.status='';return true" tabindex="9" />
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
					onmouseover="window.status='';return true" tabindex="10" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Obras con Equipo Pesado" 
					onmouseover="window.status='';return true" onclick="confirmarSalida('menu_equipos.php');" tabindex="11"/>
				</td>
		  	</tr>
	  	</table>
	</form>
	</fieldset>
	
	<?php
	//Calendario  para la fecha de Registro de la Obra ?>
	<div id="calendarioObra">
		<input type="image" name="txt_fechaRegistro" id="txt_fechaRegistro" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_registrarObraEP.txt_fechaRegistro,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
		title="Seleccionar la Fecha de Registro de la Obra"/> 
	</div>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
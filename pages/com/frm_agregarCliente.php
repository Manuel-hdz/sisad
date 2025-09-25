<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
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
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-agregarC { position:absolute; left:30px; top:190px; width:890px; height:505px; z-index:12; padding:15px; padding-top:0px;}
		#calendario { position:absolute; left:885px; top:269px; width:30px; height:26px; z-index:13; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30"/></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Cliente</div>
	
	<fieldset class="borde_seccion" id="tabla-agregarC" name="tabla-agregarC">
	<legend class="titulo_etiqueta">Agregar Cliente</legend>	
	<br>
	<form onSubmit="return verContFormAgregarCliente(this);" name="frm_agregarCliente" method="post" action="op_agregarCliente.php">
    <table width="891" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
   	  	  <td width="167"><div align="right">&iquest;No Facturable? <input type="checkbox" name="ckb_factura" id="ckb_factura" onclick="validarFacturable(this);" value="SI" title="Seleccionar para Indicar el Cliente No Es Facturable"/> *RFC</div></td>
       	  	<td width="286">
		  		<input name="txt_rfc" id="txt_rfc" type="text" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car', 3);" 
				onblur="return verificarDatoBD(this,'bd_compras','clientes','rfc','razon_social');" />
  		  <span id="error" class="msj_error">RFC Duplicado</span></td>
       	    <td width="127"><div align="right">Id Fiscal (Folio) </div></td>
       	  <td colspan="3"><input type="text" name="txt_idFiscal" id="txt_idFiscal" size="10" maxlength="8" onkeypress="return permite(event,'num_car',3);" class="caja_de_texto" /></td>
		</tr>
   	    <tr>
       	   	<td><div align="right">*Raz&oacute;n Social </div></td>
           	<td><input name="txt_razon" id="txt_razon" type="text" class="caja_de_texto" size="50" maxlength="80" onkeypress="return permite(event,'num_car', 4);" /></td>
           	<td><div align="right">C&oacute;digo Postal </div></td>
       	  <td width="52"><input type="text" name="txt_cp" size="5" maxlength="5" onkeypress="return permite(event,'num',3);" class="caja_de_texto" /></td>
			<td width="36">Fecha</td>
          <td width="126"><input type="text" name="txt_fecha" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo date("d/m/Y"); ?>"/></td>
   	    </tr>
        <tr>
           	<td><div align="right">Calle</div></td>
           	<td><input name="txt_calle" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car', 0);" /></td>
           	<td><div align="right">*Estado</div></td>
           	<td colspan="3"><input name="txt_estado" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" /></td>
        </tr>
        <tr>
           	<td><div align="right">N&uacute;mero Externo </div></td>
       	  	<td>
            	<input name="txt_numeroExt" type="text" class="caja_de_texto" id="txt_numeroExt" size="6" maxlength="10" 
          	 	onkeypress="return permite(event,'num_car', 1);" /> 
           		N&uacute;mero Int.  
       	      	<input name="txt_numeroInt" type="text" class="caja_de_texto" id="txt_numeroInt2" size="6" maxlength="10"
                onkeypress="return permite(event,'num_car',1);" />            </td>
           	<td><div align="right">Tel&eacute;fono</div></td>
          	<td colspan="3">
            	<input name="txt_telefono" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num',3);" 
                onblur="validarTelefono(this);" /></td>
        </tr>
        <tr>
           	<td><div align="right">Colonia </div></td>
           	<td><input name="txt_colonia" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',0);" /></td>
           	<td><div align="right">Tel&eacute;fono 2 </div></td>
           	<td colspan="3">
            	<input name="txt_telefono2" id="txt_telefono2" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num', 3);" 
                onblur="validarTelefono(this);" /></td>
        </tr>
        <tr>
           	<td><div align="right">Ciudad</div></td>
           	<td><input name="txt_ciudad" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" /></td>
           	<td><div align="right">Fax</div></td>
           	<td colspan="3">
            	<input name="txt_fax" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num',3);"
            	onblur="validarTelefono(this);"  /></td>
        </tr>       	
		<tr>
		 	<td><div align="right">Municipio</div></td>
		  	<td><input name="txt_municipio" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" /></td>
		  	<td><div align="right">Correo</div></td>
		  	<td colspan="3"><input name="txt_correo" type="text" class="caja_de_texto" size="40" maxlength="40" onblur="validarCorreo(this);" /></td>
	  	</tr>
		<tr>
		   <td colspan="6"><strong>Complementar Datos del Contacto</u></strong></td>
		</tr>
		<tr>
			<td align="right">Apellido Paterno</td>
			<td><input name="txt_apPat" id="txt_apPat" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car',3);" /></td>
			<td align="right">Apellido Materno</td>
			<td colspan="3"><input name="txt_apMat" id="txt_apMat" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car',3);" /></td>
		</tr>
		<tr>
			<td align="right">Nombre(s)</td>
			<td><input name="txt_nomContacto" id="txt_nomContacto" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car',3);" /></td>
			<td align="right">CURP</td>
			<td colspan="3"><input name="txt_curp" id="txt_curp" type="text" class="caja_de_texto" size="20" maxlength="18" onkeypress="return permite(event,'num_car', 3);" /></td>
		</tr>
		<tr>
			<td><div align="right">Referencia</div></td>
			<td>
            	<textarea name="txa_referencia" id="txa_referencia" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30" 
            	onkeypress="return permite(event,'num_car',2);" ></textarea></td>
			<td><div align="right">Observaciones</div></td>
			<td colspan="3">
            	<textarea name="txa_observaciones" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
                onkeypress="return permite(event,'num_car', 0);" ></textarea></td>
		</tr>
		<tr>
		   <td colspan="6"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>
       	<tr>
       	  	<td colspan="6"><div align="center">       	    	
				<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
				
				<input name="btn_agregar" type="submit" class="botones"  value="Agregar" title="Agregar los Datos del Cliente" 
                onMouseOver="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;
       	     	<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true" onclick="restablecerFormularioClientes();"/> 
				&nbsp;&nbsp;&nbsp;
       	     	<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Clientes" 
                onMouseOver="window.status='';return true" onclick="location.href='menu_clientes.php'" />
     	    </div></td>
		</tr>
    </table>
  	</form>
</fieldset>

	<div id="calendario">
		<input type="image" name="fechaCliente" id="fechaCliente" src="../../images/calendar.png" onclick="displayCalendar(document.frm_agregarCliente.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
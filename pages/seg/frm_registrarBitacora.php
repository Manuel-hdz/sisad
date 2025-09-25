<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Manejo de la funciones para registrar los residuos peligrosos dentro de la bitacora en la BD de Seguridad
		include ("op_registrarBitacora.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/calcularID.js"></script>

    <style type="text/css">
		<!--
		#titulo-regBitacora { position:absolute; left:30px; top:146px; width:268px; height:19px; z-index:11; }
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		#tabla-almacenamientoTemp { position:absolute; left:532px; top:193px; width:438px; height:151px; z-index:14; }
		#tabla-faseAlmacenamiento { position:absolute; left:14px; top:363px; width:465px; height:252px; z-index:14; }
		#tabla-faseResguardo { position:absolute; left:532px; top:365px; width:441px; height:250px; z-index:14; }
		#tabla-faseCreti { position:absolute; left:494px; top:-10px; width:237px; height:105px; z-index:14; }
		#tabla-almacenamiento { position:absolute; left:13px; top:192px; width:463px;	height:147px; z-index:16; }
		#fechaIngreso { position:absolute; left:766px; top:293px; width:30px; height:26px; z-index:14; }
		#fechaSalida { position:absolute; left:973px; top:293px; width:30px; height:26px; z-index:14; }
		#botonesBit {position:absolute;left:-405px;top:273px;width:716px;height:37px;z-index:14;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-regBitacora">Registrar Bitacora de Residuos</div>

	<form onsubmit="return valFormRegBitacora(this);" name="frm_regRegBitacora" method="post" action="op_registrarBitacora.php">
	<fieldset id="tabla-almacenamiento" class="borde_seccion">
	<legend class="titulo_etiqueta">Almacenamiento Temporal de Residuos Peligrosos</legend>	
	<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
	  	  <td width="25%"><div align="right">*Tipo Residuo</div></td>
		 	<td width="25%">
			<select name="cmb_residuo" id="cmb_residuo" class="combo_box" onchange="calcularID(this.value);">
				<option value="" selected="selected">Seleccione</option>
				<option value="ACEITE">ACEITE</option>				
				<option value="SOLIDOS">SOLIDOS</option>
			</select>
			</td>
		  <td width="25%"><div align="right">*Clasificaci&oacute;n Solido</div></td>
		  <td width="25%"><input name="txt_clasificacionSol" class="caja_de_texto" id="txt_clasificacionSol"  size="20" maxlength="60"  
		  onkeypress="return permite(event,'num_car',1);"  type="text"/></td>
		</tr>
		<tr>
			<td><div align="right">Clave Bitacora</div></td>
			<td><input name="txt_claveBitacora" class="caja_de_texto" id="txt_claveBitacora"  size="10" maxlength="10" 
			value="" readonly="readonly"  type="text"  />
			</td>
			<td><div align="right">*&Aacute;rea</div></td>
			<td><input name="txt_area" class="caja_de_texto" id="txt_area"  size="20" maxlength="30"  onkeypress="return permite(event,'num_car',1);"
			  type="text"  /></td>
		</tr>
		<tr>
			<td><div align="right">*Cantidad</div></td>
			<td>
				<input name="txt_cantGenerada" class="caja_de_texto" id="txt_cantGenerada"  size="10" maxlength="10" type="text" 
				onkeypress="return permite(event,'num_car',2);"/>
			</td>
			<td><div align="right">*Equivalente a:</div></td>
			<td>
				<input name="txt_unidad" class="caja_de_texto" id="txt_unidad"  size="10" maxlength="10"
			  	type="text" onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_unidad');" />
			 	<span id="unidad"></span>
		  </td>
		</tr>
	</table>
</fieldset>
	
	<fieldset id="tabla-almacenamientoTemp" class="borde_seccion">
	<legend class="titulo_etiqueta">Almac&eacute;n Temporal de Residuos</legend>
 	<table  width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  <td><div align="right">*Nombre Entrega </div></td>
			<td colspan="3"><input name="txt_nomEntrega" class="caja_de_texto" id="txt_nomEntrega" size="40" maxlength="60"  
			onkeypress="return permite(event,'car',3);"  type="text" /></td>
		</tr>	
      	<tr>
		  <td><div align="right">*Nombre Recibe</div></td>
			<td colspan="3"><input name="txt_nomRecibe" class="caja_de_texto" id="txt_nomRecibe" size="40" maxlength="60" 
			 onkeypress="return permite(event,'car',3);"  type="text" /></td>
		</tr>
		<tr>
		  <td><div align="right">*Fecha Ingreso</div></td>
			<td><input name="txt_fechaIng" id="txt_fechaIng" class="caja_de_texto" size="10"
            	value="<?php echo date("d/m/Y"); ?>" 
    	        readonly="readonly"  type="text"  /></td>
				<td><div align="right">*Fecha Salida</div></td>
				<td><input name="txt_fechaSal" id="txt_fechaSal" class="caja_de_texto" size="10" 
				value="<?php echo date("d/m/Y"); ?>" 
            	readonly="readonly"  type="text"  /></td>
		</tr>     
	</table>
</fieldset>

<fieldset id="tabla-faseAlmacenamiento" class="borde_seccion">
	<legend class="titulo_etiqueta">Fase de Almacenamiento Siguiente a la Salida-Prestador de Servicios</legend>
 	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  <td><div align="right">*Raz&oacute;n Social</div></td>
		  <td colspan="2"><input name="txt_razSocial" class="caja_de_texto" id="txt_razSocial" size="40" maxlength="80"  
		  onkeypress="return permite(event,'num_car',4);"  type="text" /></td>
		</tr>	
      	<tr>
		  <td><div align="right">*N&uacute;mero de Manifiesto</div></td>
			<td colspan="2"><input name="txt_numManifiesto"  class="caja_de_texto" id="txt_numManifiesto"  size="10" maxlength="10"
			 onkeypress="return permite(event,'num',3);"  type="text" /></td>
		</tr>
		<tr>
	      <td><div align="right">*N&uacute;mero de Autorizaci&oacute;n</div></td>
			<td><input name="txt_numAutorizacion"  class="caja_de_texto" id="txt_numAutorizacion" size="10" maxlength="20"  
			onkeypress="return permite(event,'num_car',7);"  type="text" /></td>
		</tr>     
		<tr>
	      <td><div align="right">*Nombre Transportista</div></td>
			<td><input name="txt_nomTransportista"  class="caja_de_texto" id="txt_nomTransportista" size="40" maxlength="50"
			 onkeypress="return permite(event,'num_car',4);"   type="text"/></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
		</tr>	
		<tr>
			<td colspan="2"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>	
	</table>
</fieldset>


<fieldset id="tabla-faseResguardo" class="borde_seccion">
	<legend class="titulo_etiqueta">Fase de Transferencia &oacute; Resguardo</legend>
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">*Manejo del Residuo</div></td>
				<td colspan="2"><textarea name="txa_descripcion"  cols="50" rows="3" class="caja_de_texto" id="txa_descripcion" maxlength="120" 
				onkeypress="return permite(event,'num_car',0);" onkeyup="return ismaxlength(this)" type="text" ></textarea></td>
			</tr>	
			<tr>
				<td><div align="right">*Responsable</div></td>
				<td><input name="txt_responsableBit"  class="caja_de_texto" id="txt_responsableBit" 
				size="40" maxlength="60"  onkeypress="return permite(event,'car',3);"  type="text" />
				</td>
			</tr>			
			<tr>	
				<td colspan="2">
					<table width="100%" border="0" cellpadding="5" cellspacing="5" cols="4" class="tabla_frm">
					  <caption align="center" style="border:medium"  class='titulo_etiqueta'>
					  *Caracteristicas de Peligrosidad del Residuo
					  </caption>					
						<tr>
							<td align="center" width="20" class='nombres_columnas'>C</td>
							<td align="center" width="20" class='nombres_columnas'>R</td>
							<td align="center" width="20" class='nombres_columnas'>E</td>
							<td align="center" width="20" class='nombres_columnas'>T</td>
							<td align="center" width="20" class='nombres_columnas'>I</td>
						</tr>
						<tr>
							<td class='nombres_filas' align='center'>
								<input type="checkbox" id="ckb_peligrosidadC" name="ckb_peligrosidadC"/></td>	
							<td class='nombres_filas' align='center'>
								<input type="checkbox" id="ckb_peligrosidadR" name="ckb_peligrosidadR"/></td>
							<td class='nombres_filas' align='center'>
								<input type="checkbox" id="ckb_peligrosidadE" name="ckb_peligrosidadE"/></td>
							<td class='nombres_filas' align='center'>
								<input type="checkbox" id="ckb_peligrosidadT" name="ckb_peligrosidadT"/></td>
							<td class='nombres_filas' align='center'>
								<input type="checkbox" id="ckb_peligrosidadI" name="ckb_peligrosidadI"/></td>	
					  </tr>
				  </table>
			</tr>
	</table>
<div align="center" id="botonesBit">
		<tr>
            <td colspan="4">
                <div align="center">
                    <input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Registro en la Bitacora de Residuos Peligroso"
					 onmouseover="window.status='';return true" />
                    &nbsp;&nbsp;&nbsp;
					<input name="btn_limpiar" type="reset" class="botones" value="Limpiar" id="btn_limpiar" title="Limpia el Formulario" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Bit&aacute;cora" 
					onclick="location.href='menu_bitacora.php';" onmouseover="window.status='';return true" />
                </div>			
			</td>
		</tr>
</div>
</fieldset>	
</form>


<div id="fechaIngreso">
        <input type="image" name="txt_fechaIng" id="txt_fechaIng" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_regRegBitacora.txt_fechaIng,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Ingreso"/> 
</div>
    <div id="fechaSalida">
        <input type="image" name="txt_fechaSal" id="txt_fechaSal" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_regRegBitacora.txt_fechaSal,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar la Fecha de Salida"/> 
</div>

</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
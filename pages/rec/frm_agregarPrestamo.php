<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include("op_agregarPrestamo.php");?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
    <script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>		
	<script type="text/javascript" src="includes/ajax/validarEmpleado.js"></script>
	<script type="text/javascript" src="includes/ajax/validarPagosPeriodo.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;width:270px;height:20px;z-index:11;}
		#tabla-agregarPrestamo {position:absolute;left:30px;top:190px;width:900px;height:415px;z-index:12;padding:15px;padding-top:0px;}
		#calendarioIni {position:absolute;left:791px;top:376px;width:30px;height:26px;z-index:13;}
		#res-spider{position:absolute; z-index:15;}				
		#procesando {position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:16; }
		-->
    </style>
</head>
<body>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Prestamos a Empleados</div><?php 
	
	
	//Controlar la Aparicion del Formulario de Registrar Prestamo
	$ctrl_aparicion = 1;
	if(isset($_POST["sbt_guardar"])){	
		guardarPrestamo();?>
		<div class="titulo_etiqueta" id="procesando" align="center">
			<p><img src="../../images/loading.gif" width="70" height="70"></p>
			<p>Procesando...</p>
        </div><?php		
		$ctrl_aparicion = 0;//Dejar de mostrar el formulario de Agregar Prestamo		
	}//Cierre if(isset($_POST["sbt_guardar"]))
	
	
	//Controlar la Aparición del Formulario para agregar un Prestamo
	if($ctrl_aparicion==1){ ?>
		<fieldset class="borde_seccion" id="tabla-agregarPrestamo" name="tabla-agregarPrestamo">
		<legend class="titulo_etiqueta">Agregar Pr&eacute;stamo</legend>	
		<br>
		<form onSubmit="return valFormAgregarPrestamo(this);" name="frm_agregarPrestamo" method="post" action="frm_agregarPrestamo.php">
		<table width="100%"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="18%" align="right">*&Aacute;rea</td>
				<td width="32%"><?php 
					$res = cargarComboConId("cmb_area","area","area","empleados","bd_recursos","&Aacute;rea","",		
									 		"txt_nomEmpleado.value='';lookup(txt_nomEmpleado,'empleados',cmb_area.value,'1');");
					if($res==0){?>
						<label class="msje_correcto"><u><strong>NO</strong></u> Hay &Aacute;reas Registradas</label>
						<input type="hidden" name="cmb_area" id="cmb_area" value="" /><?php 
					}?>				
				</td>
				<td width="22%" align="right">*Cantidad Prestamo </td>
				<td width="28%">$
			    	<input name="txt_cantidadPrestamo" id="txt_cantidadPrestamo" type="text" class="caja_de_texto" size="10" maxlength="10" 
					onkeypress="return permite(event,'num',2);" onchange="confirmarCantPrestamo(this); validarPeriodoPagos();" />
				</td>
			</tr>
			<tr>
				<td align="right">*Nombre del Empleado</td>
				<td>
					<input type="text" name="txt_nomEmpleado" id="txt_nomEmpleado" onkeyup="obtenerEmpleadoValidarEstado(this,'empleados',cmb_area.value,'1');" 
					value="" size="50" maxlength="70" onkeypress="return permite(event,'car',0);" class="caja_de_texto" />
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
						  <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>				
				</td>  
				<td align="right">Periodo de pago </td>
				<td>
					<select name="cmb_periodo" id="cmb_periodo" class="combo_box" onchange="validarPeriodoPagos();">
						<option value="">Periodo</option>
						<option value="SEMANAL">SEMANAL</option>
						<option value="QUINCENAL">QUINCENAL</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">RFC del Empleado</td>
				<td><input name="txt_RFCEmpleado" id="txt_RFCEmpleado" type="text" class="caja_de_texto" size="20" maxlength="60" readonly="readonly"/></td>
				<td align="right">Cantidad a Pagar por Periodo </td>
				<td>$
					<input type="text" name="txt_pagoPorPeriodo" id="txt_pagoPorPeriodo" class="caja_de_texto" size="10" onkeypress="return permite(event,'num',2);" 
					onchange="formatCurrency(this.value,'txt_pagoPorPeriodo'); validarPeriodoPagos();" />
					<input type="text" name="txt_cantPagos" id="txt_cantPagos" class="caja_de_texto" size="5" onkeypress="return permite(event,'num',2);" readonly="readonly" /> 
					&nbsp;Pagos
					<span class="caja_de_texto" id="lbl_ultimoPago"></span>
				</td>
			</tr>    
			<tr>
				<td align="right">*Nombre Pr&eacute;stamo</td>
				<td><?php
					$cmb_nomPrestamo="";
					$conn = conecta("bd_recursos");
					$rs = mysql_query("SELECT DISTINCT nom_deduccion FROM deducciones WHERE id_deduccion LIKE 'PRE%' ORDER BY nom_deduccion");
					if($row=mysql_fetch_array($rs)){?>
						<select name="cmb_nomPrestamo" id="cmb_nomPrestamo" class="combo_box" 
						onchange="obtenerDatoBD(this.value,'bd_recursos','deducciones','descripcion','nom_deduccion','txa_descripcion');">
							<option value="">Categor&iacute;a</option><?php 
							do{
								echo "<option value='$row[nom_deduccion]'>$row[nom_deduccion]</option>";                            
							}while($row=mysql_fetch_array($rs));?>
						</select><?php
					}
					else{
						/* Indicar al Usuario que no hay Prestamos registrados y prevenir que la validación falle por falta del Combo, 
						 * por eso colocamos un elemento hidden con el mismo nombre */ ?>
						<label class="msje_correcto"><u><strong>NO</strong></u> Hay Prestamos Registrados</label>
						<input type="hidden" name="cmb_nomPrestamo" id="cmb_nomPrestamo" value="" /><?php
					}?>
				</td>
				<td align="right">
					<input type="checkbox" name="ckb_nuevoPrestamo" id="ckb_nuevoPrestamo" onclick="agregarNuevoPrestamo(this, 'txt_nuevoPrestamo', 'cmb_nomPrestamo');" 
					title="Seleccione para Escribir el Nombre de un Préstamo que no Exista"/>
					Agregar Nuevo Pr&eacute;stamo
				</td>
			  	<td>
					<input name="txt_nuevoPrestamo" id="txt_nuevoPrestamo" type="text" class="caja_de_texto" size="20" maxlength="20" 
					onkeypress="return permite(event,'num',0);" readonly="readonly" />
				</td>
			</tr>  
			<tr>
				<td align="right">Id Pr&eacute;stamo</td>
				<td>
					<input name="txt_idPrestamo" id="txt_idPrestamo" type="text" class="caja_de_texto" size="11" maxlength="11" 
					onkeypress="return permite(event,'num_car',3);" readonly="readonly" value="<?php echo obtenerIdPrestamo(); ?>"/>
				</td>
				<td align="right">Fecha</td>
				<td>
					<input name="txt_fechaRegistro" id="txt_fechaRegistro" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y");?>" readonly="readonly" 
					onchange="validarPeriodoPagos();" />
				</td>
			</tr>
			<tr>       	  	
				<td align="right">*Autoriz&oacute;</td>
				<td>
					<input name="txt_autorizo" id="txt_autorizo" type="text" class="caja_de_texto" size="45" maxlength="60" 
					onkeypress="return permite(event,'num_car',3);" value="ING. GUILLERMO MARTÍNEZ ROMÁN" ondblclick="this.value='';" />
				</td>
				<td align="right">Descripci&oacute;n</td>
				<td>
					<textarea name="txa_descripcion" id="txa_descripcion"  maxlength="120" onkeyup="return ismaxlength(this)" 
					class="caja_de_texto" rows="2" cols="37" onkeypress="return permite(event,'num_car', 0);" ></textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2"><span id="error" class="msje_incorrecto" style="visibility:hidden;">No se le Puede Registrar un Prestamo al Empleado Actual</span></td>
				<td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>  
			<tr>
				<td colspan="4"><div align="center">
					<input type="hidden" name="hdn_prestamoAutorizado" id="hdn_prestamoAutorizado" value="si" />
					
					
					<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Préstamo" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
					onmouseover="window.status='';return true" onclick="document.getElementById('error').style.visibility = 'hidden';"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Préstamos " 
					onmouseover="window.status='';return true" onclick="confirmarSalida('menu_prestamos.php');"/></div>				
				</td>   	
			</tr>        
		</table>
		</form>
		</fieldset>
		
		<div id="calendarioIni">
			<input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_agregarPrestamo.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar la Fecha del Bono"/> 
		</div><?php		
	}//Cierre if($ctrl_aparicion==1)?>
	
		                
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
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
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link> 
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="includes/ajax/fallasConsumosTNT.js"></script>
	<script type="text/javascript" language="javascript">
		//Esta variable guardará la referencia de la página de Modificar Registro Fallas/Explosivos para detectar cuando ésta sea crerrada.
		var vtnAbierta = "";
		//Al cargar la pagina colocar el foco la caja de texto donde ira el nombre de Jumbero
		setTimeout("document.frm_voladura.txt_volador.focus();",500);
	</script>
    <style type="text/css">
		<!--
		#titulo-voladura { position:absolute; left:30px; top:146px; width:350px; height:20px; z-index:11; }
		#form-registrarDatos { position:absolute; left:30px; top:190px; width:827px; height:350px; z-index:12; }		
		#res-spider1 { position:absolute; left:110px; top:50px; width:10px; height:183px; z-index:13; }
		#res-spider2 { position:absolute; left:110px; top:86px; width:10px; height:183px; z-index:14; }
		#res-spider3 { position:absolute; left:110px; top:120px; width:10px; height:183px; z-index:14; }
		#calendario { position:absolute; left:721px; top:253px; width:30px; height:27px; z-index:15; }
		-->
    </style>
</head>
<body onfocus="verificarCierreVtn();">

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>	
	<div id="titulo-voladura" class="titulo_barra">Registro de Voladura</div><?php
	
	if(!isset($_POST['sbt_guardar'])){?>
		
		<fieldset class="borde_seccion" id="form-registrarDatos" name="form-registrarDatos" style="height:580px;">
			<legend class="titulo_etiqueta">Ingresar la informaci&oacute;n del Registro de Voladura</legend>
			<form onsubmit="return valFormVoladura(this);" name="frm_voladura" method="post" action="frm_regVoladura.php">
			<table width="100%" cellspacing="5" cellpadding="5">          
			  <tr>
				<td align="right">*Op. Voladura </td>
				<td colspan="2">
					<input name="txt_volador" type="text" class="caja_de_texto" id="txt_volador" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookup(this,'empleados','1','VOLADURAS');" value="" size="50" maxlength="80" autocomplete="off"/>
					<div id="res-spider1">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
			  	  </div>
					<?php //Esta variable 'hdn_rfc' guarda el RFC del empleado seleccionado en la Busqueda Sphider ?>
					<input type="hidden" name="hdn_rfc" id="hdn_rfc" value="" />
					<input type="hidden" name="hdn_idEmp" id="hdn_idEmp" value="" />
				</td>
				<td align="right">*Turno</td>
				<td>
					<select name="cmb_turno" id="cmb_turno" class="combo_box">
                  		<option value="">Turno</option>
                  		<option value="PRIMERA">PRIMERA</option>
                  		<option value="SEGUNDA">SEGUNDA</option>
                  		<option value="TERCERA">TERCERA</option>
                	</select>				
				</td>
				<td>&nbsp;</td>				
			  </tr>
			  <tr>
				<td align="right">*Ayudante</td>
				<td colspan="2">
					<input name="txt_ayudante" type="text" class="caja_de_texto" id="txt_ayudante" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookup(this,'empleados','2','AYUDANTE VOLADURAS');" value="" size="50" maxlength="80" autocomplete="off"/>
					<div id="res-spider2">
						<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
						</div>
			  	  </div>				
				</td>
				<td align="right">*Fecha Registro</td>
				<td>
					<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" readonly="readonly" size="10" 
					value="<?php echo date("d/m/Y"); ?>" />
				</td>
				<td>&nbsp;</td>		
			  </tr>
			  <tr>
				<td align="right">
					<input type="checkbox" name="ckb_ayudante" id="ckb_ayudante" value="activo" onclick="activarCamposForm(this,'txt_ayudante2');" />*Ayudante
				</td>
				<td colspan="3">
					<input name="txt_ayudante2" type="text" class="caja_de_texto" id="txt_ayudante2" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookup(this,'empleados','3','AYUDANTE VOLADURAS');" value="" size="50" maxlength="80" readonly="readonly" autocomplete="off"/>
					<div id="res-spider3">
						<div align="left" class="suggestionsBox" id="suggestions3" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList3">&nbsp;</div>
						</div>
				  </div>			
				</td>
			  </tr>
			  <!-- <tr>
				<td align="right">Equipo</td>
				<td><?php								
					/*$conn = conecta("bd_mantenimiento");//Conectarse a la Base de Datos
					$result=mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE familia = 'TRACTORES' AND disponibilidad = 'ACTIVO' ORDER BY id_equipo");
						
					if($registro=mysql_fetch_array($result)){?>				
						<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="5" onchange="habilitarCajaTxt(this);">
							<option value="">Equipo</option><?php															 
							do{?>
								<option value="<?php echo $registro['id_equipo']; ?>" title="<?php echo $registro['id_equipo']; ?>"><?php 
									echo $registro['id_equipo']; ?>
								</option><?php
							}while($registro=mysql_fetch_array($result))?>
						</select><?php
					} else {?>
						<span class="msje_correcto">No Hay Equipos Registrados</span>
						<input type="hidden" name="cmb_equipo" id="cmb_equipo" value="" /><?php
					}
					mysql_close($conn);//Cerrar la conexion con la BD */?>				
				</td>
			  </tr>
			  <tr>
				<td align="right">*Hor&oacute;metro Inicial</td>
				<td>
					<input type="text" name="txt_HIEquipo" id="txt_HIEquipo" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="8"
					onblur="calcularHorasTotales('txt_HIEquipo','txt_HFEquipo','txt_HTEquipo');" readonly="readonly" onchange="formatCurrency(this.value,'txt_HIEquipo');" />
				</td>
				<td align="right">*Hor&oacute;metro Final</td>
				<td>
					<input type="text" name="txt_HFEquipo" id="txt_HFEquipo" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="9"
					onblur="calcularHorasTotales('txt_HIEquipo','txt_HFEquipo','txt_HTEquipo');" readonly="readonly" onchange="formatCurrency(this.value,'txt_HFEquipo');" />
				</td>
				<td align="right">*Hrs. Totales</td>
				<td><input type="text" name="txt_HTEquipo" id="txt_HTEquipo" class="caja_de_texto" size="9" readonly="readonly" /></td>		
			  </tr>-->
			  
			  <input type="hidden" name="cmb_equipo" id="cmb_equipo" value="0" />
			  <input type="hidden" name="txt_HIEquipo" id="txt_HIEquipo" value="0" />
			  <input type="hidden" name="txt_HFEquipo" id="txt_HFEquipo" value="0" />
			  <input type="hidden" name="txt_HTEquipo" id="txt_HTEquipo" value="0" />
			  
			  <?php for($i=0;$i<2;$i++){ ?>
			  <tr>
				<td colspan="8" align="left"><span class="titulo_etiqueta">Voladura <?php echo $i+1;?></span><input type="checkbox" title="Activelo para que el Registro de Voladura sea guardado" name="ckb_activarVol<?php echo $i;?>" id="ckb_activarVol<?php echo $i;?>"/></td>
			  </tr>
			  <!--<tr>
				<td align="right">*Longitud Barreno Cargado</td>            
				<td>
					<input type="text" name="txt_longBarreno<?php echo $i;?>" id="txt_longBarreno<?php echo $i;?>" class="caja_de_texto" size="6" maxlength="15" onkeypress="return permite(event,'num',2);"
					tabindex="6" />
				</td>
				<td align="right">*Factor de Carga </td>
				<td>
					<input type="text" name="txt_factorCarga<?php echo $i;?>" id="txt_factorCarga<?php echo $i;?>" class="caja_de_texto" size="6" maxlength="15" onkeypress="return permite(event,'num',2);"
					tabindex="7" />Kg.
				</td>
			  </tr>-->
			  <tr>
				<td align="right">*Disparos</td>            
				<td>
					<input type="text" name="txt_disparos<?php echo $i;?>" id="txt_disparos<?php echo $i;?>" class="caja_de_texto" size="6" maxlength="15" onkeypress="return permite(event,'num',2);" />
				</td>
				<td align="right">*Disparos Nicho</td>
				<td>
					<input type="text" name="txt_disparosNicho<?php echo $i;?>" id="txt_disparosNicho<?php echo $i;?>" class="caja_de_texto" size="6" maxlength="15" onkeypress="return permite(event,'num',2);" />
				</td>
			  </tr>
			  <input type="hidden" name="txt_longBarreno<?php echo $i;?>" id="txt_longBarreno<?php echo $i;?>" value="1" />
			  <input type="hidden" name="txt_factorCarga<?php echo $i;?>" id="txt_factorCarga<?php echo $i;?>" value="1" />
			  <tr>
				<td align="right">*Topes Cargados</td>
				<td><input type="text" name="txt_TopesCarg<?php echo $i;?>" id="txt_TopesCarg<?php echo $i;?>" class="caja_de_texto" size="9" onkeypress="return permite(event,'num',2);"/></td>
				<td align="right">Observaciones</td>
				<td colspan="3">
					<textarea name="txa_observaciones<?php echo $i;?>" onkeyup="return ismaxlength(this)" maxlength="120" class="caja_de_texto" rows="3" cols="35" 
					onkeypress="return permite(event,'num_car',0);" ></textarea>				
				</td>
			  </tr>
			  <tr>
				<td align="right">&nbsp;</td>
				<td colspan="5"><strong>*Los  datos marcados con asterisco (*) son obligatorios.</strong></td>
			  </tr>
			  <?php } ?>
			  <tr>
				<td width="15%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="20%">&nbsp;</td>
				<td width="15%">&nbsp;</td>
				<td width="15%">&nbsp;</td>
				<td width="15%">&nbsp;</td>		
			  </tr>
			  <tr>
				<td align="center" colspan="6">
					<?php /*Estas variables ayudan a identificar cual de las Bitácoras (Avance y Retro-Bull) será registrada en las Bitacoras de Fallas y Consumos
					asi como el tipo de registro (Bitacora de Barrenación, Voladura y Rezagado*/ ?>
					<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $_POST['hdn_idBitacora']; ?>" />
					<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="<?php echo $_POST['hdn_tipoBitacora']; ?>" />
					<input type="hidden" name="hdn_tipoRegistro" id="hdn_tipoRegistro" value="VOLADURAS" />
					<?php //Esta variable ayudara a determinar el tipo de Falla que sera registrada en la Bitacora de Fallas?>
					<input type="hidden" name="hdn_tipoEquipo" id="hdn_tipoEquipo" value="VOLADURAS" />
					
					<?php //Esta variable indica si fueron agregados los cosumos del equipo, en el caso que no se notifica al usuario sobre este hecho?>
					<input type="hidden" name="hdn_regBitExplosivos" id="hdn_regBitExplosivos" value="no" />
					<?php //Esta variable indica sobre cual equipo se registraron fallas y ayuda a que el usuario no cambie el equipo seleccionado antes de guardar?>
					<input type="hidden" name="hdn_fallasEquipo" id="hdn_fallasEquipo" value="" />
					
					
					<input type="submit" name="sbt_guardar" value="Guardar" class="botones" title="Guardar Datos en la Bit&aacute;cora" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;
					<input name="btn_regFallas" id="btn_regFallas" type="button" class="botones_largos" value="Registrar Fallas" 
					title="Registrar Fallas de los Equipos" onmouseover="window.status='';return true" onclick="abrirVentana('fallas','agregar');" />
					&nbsp;&nbsp;
					<input name="btn_regExplosivos" id="btn_regExplosivos" type="button" class="botones_largos" value="Explosivos Empleados" 
					title="Registrar Explosivos Utilizados" onmouseover="window.status='';return true" onclick="abrirVentanaTNT('registrar');" />
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Limpiar los Campos del Formulario" />
					&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar al Registro de la Bit&aacute;cora de Avance" 
					onclick="cancelarOperacion(hdn_idBitacora.value,hdn_tipoBitacora.value,hdn_tipoRegistro.value,'frm_regAvance.php');" />				
				</td>         
			  </tr>
			</table>	
			</form>
		</fieldset>
		
		<div id="calendario">
			<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_voladura.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Registro" /> 
</div><?php
	}//Cierre if(!isset($_POST['sbt_guardar'])) 
	else{
		//Guardar los datos de la Bitácora en la Base de Datos
		guardarBitVoladura();
	}?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
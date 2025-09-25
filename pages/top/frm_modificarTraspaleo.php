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
		include("op_modificarTraspaleo.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Documento sin t&iacute;tulo</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="includes/ajax/verificarQuincena.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboQuincena.js"></script>
	
    <style type="text/css">
		<!--
		#titulo-modificarTraspaleo { position:absolute; left:30px; top:146px; width:295px; height:20px; z-index:11; }		
		#seleccionar-obra { position:absolute; left:30px; top:190px; width:450px; height:217px; z-index:12; }
		#modificar-traspaleo { position:absolute; left:30px; top:190px; width:798px; height:260px; z-index:13; }
		f
		-->
    </style>
</head>
<body>


	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>    
    <div id="titulo-modificarTraspaleo" class="titulo_barra">Modificar Registros de Traspaleo</div><?php
	
	
		
	//Seleccionar el Registro de Traspaleo de la Obra que va a ser Modificada
	if(!isset($_POST['sbt_seleccionar']) && !isset($_POST['sbt_continuar'])){ 
		//Liberar datos de la SESSION utilizados en la Modificación del Traspaleo en el caso de que Existan
		if(isset($_SESSION['datosTraspaleoMod']))		
			unset($_SESSION['datosTraspaleoMod']);?>
			
			
		<fieldset class="borde_seccion" id="seleccionar-obra" name="seleccionar-obra">
		<legend class="titulo_etiqueta">Seleccionar Obra</legend>
		<form onsubmit="return valFormSeleccionarQuincena(this);" name="frm_seleccionarQuincena" method="post" action="frm_modificarTraspaleo.php">
		<table width="100%" border="0" cellspacing="5" cellpadding="5" class="tabla_frm">
			<tr>
			  	<td width="40%" align="right">Tipo Obra </td>
			  	<td width="60%"><?php 
					$res = cargarComboConId("cmb_tipoObra","tipo_obra","tipo_obra","obras","bd_topografia","Tipo Obra","",
											"cargarComboConId(this.value,'bd_topografia','obras','nombre_obra','id_obra','tipo_obra','cmb_idObra','Obras','');"); 
					if($res==0){?>
						<label class="msje_correcto">No Hay Datos Registrados</label>
						<input type="hidden" name="cmb_tipoObra" value="" /><?php
					}?>
			  	</td>
			</tr>
			<tr>
			  	<td height="37" align="right">Nombre de la Obra</td>
		  	  <td>
					<select name="cmb_idObra" id="cmb_idObra" class="combo_box" onchange="cargarComboQuincena(this.value,'traspaleos','cmb_numQuincena');">
						<option value="">Obras</option>
					</select>					
			  </td>
			</tr>
			<tr>
			  	<td align="right">No. Quincena</td>
			  	<td>
					<select name="cmb_numQuincena" id="cmb_numQuincena" class="combo_box">
						<option value="">No. Quincena</option>
					</select>
			  	</td>
			</tr>
			<tr>
			  	<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
			  	<td colspan="2" align="center">
					<input type="submit" name="sbt_seleccionar" value="Seleccionar" class="botones" title="Seleccionar Quincena Para Modificar Registro" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Men&uacute; de Traspaleo" 
					onclick="location.href='menu_traspaleo.php'" />
			  	</td>
			</tr>
		</table>
		</form>
		</fieldset><?php
	}//Cierre 					
	
	
	
	
	//Mostrar el Formulario con los datos de la Obra y el Traspeleo seleccionados para ser modificados
	if(isset($_POST['sbt_seleccionar'])){ ?>
		<fieldset class="borde_seccion" id="modificar-traspaleo" name="modificar-traspaleo">
		<legend class="titulo_etiqueta">Registrar Datos de Traspaleo</legend><br /><?php 
		
				
		//Obtener los datos de la Obra para mostrarlos en el formulario de captura de Datos
		$conn = conecta("bd_topografia");		
		$rs_obra = mysql_query("SELECT * FROM obras JOIN traspaleos ON id_obra=obras_id_obra WHERE id_obra = '$cmb_idObra' AND no_quincena = '$cmb_numQuincena'");						
		
		if($datosObra=mysql_fetch_array($rs_obra)){
			//Separar los datos del Numero de Quincena
			$num = substr($datosObra['no_quincena'],0,1);
			$mes = substr($datosObra['no_quincena'],2,(strlen($datosObra['no_quincena'])-7));
			$anio = substr($datosObra['no_quincena'],(strlen($datosObra['no_quincena'])-4),4);?>
						
			<form onsubmit="return valFormModificarDatosTraspaleo(this);" name="frm_modificarDatosTraspaleo" method="post" action="frm_modificarTraspaleo.php">
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="20%" align="right">Tipo Obra</td>
				  <td width="30%"><input type="text" name="txt_tipoObra" class="caja_de_texto" value="<?php echo $datosObra['tipo_obra']; ?>" 
				  readonly="readonly" size="30" maxlength="30" /></td>
					<td width="20%" align="right">Secci&oacute;n</td>
				  <td width="30%"><input name="txt_seccion" type="text" class="caja_de_texto" id="txt_seccion" value="<?php echo $datosObra['seccion']; ?>" 
				  size="10" maxlength="15" readonly="readonly" /></td>						
				</tr>	
				<tr>
					<td align="right">Obra</td>
					<td>
						<input type="text" name="txt_nombreObra" id="txt_nombreObra" class="caja_de_texto" value="<?php echo $datosObra['nombre_obra']; ?>" readonly="readonly" 
						size="40" maxlength="40" />
						<input type="hidden" name="hdn_idObra" id="hdn_idObra" value="<?php echo $datosObra['id_obra'];?>" />
						<input type="hidden" name="hdn_idTraspaleo" value="<?php echo $datosObra['id_traspaleo'];?>" />						
					</td>
					<td align="right">&Aacute;rea</td>
					<td>
						<input type="text" name="txt_area" id="txt_area" class="caja_de_texto" value="<?php echo number_format($datosObra['area'],2,".",","); ?>" 
						readonly="readonly" size="10" maxlength="15" />
					</td>
				</tr>
				<tr>
					<td align="right">*Acumulado Quincena</td>
					<td>
						<input type="text" name="txt_acumuladoQuincena" id="txt_acumuladoQuincena" class="caja_de_texto" size="10" maxlength="15" 
						onkeypress="return permite(event,'num',2);"
						onchange="formatCurrency(this.value,'txt_acumuladoQuincena'); calcularVolumen(this);" 
						value="<?php echo number_format($datosObra['acumulado_quincena'],2,".",","); ?>" />
						<input type="hidden" name="hdn_deafaultAcumulado" id="hdn_deafaultAcumulado" value="<?php echo $datosObra['acumulado_quincena']; ?>" />
					</td>
					<td align="right">Vol. M&sup3;</td>
					<td>
						<input type="text" name="txt_volumen" id="txt_volumen" class="caja_de_texto" readonly="readonly" size="10" maxlength="15" 
						value="<?php echo number_format($datosObra['volumen'],2,".",","); ?>" />
					</td>
				</tr>
				<tr>
					<td align="right">*Tasa de Cambio </td>
					<td>
						<input type="text" name="txt_tasaCambio" id="txt_tasaCambio" class="caja_de_texto" size="10" maxlength="15" 
						onkeypress="return permite(event,'num',2);"
						onchange=" formatTasaCambio(this.value,'txt_tasaCambio');  if(!validarEntero(this.value.replace(/,/g,''),'La Tasa de Cambio')){ this.value = ''; }" 
						value="<?php echo number_format($datosObra['t_cambio'],4,".",","); ?>" />						
						<input type="hidden" name="hdn_deafaultTipoCambio" id="hdn_deafaultTipoCambio" value="<?php echo $datosObra['t_cambio']; ?>" />
					</td>					
					<td align="right">*No. Quincena</td>
					<td>
						<select name="cmb_noQuincena" id="cmb_noQuincena" class="combo_box" onchange="verificarQuincena('TRASPALEO');">
							<option value="">Num.</option>
							<option value="1" <?php if($num==1){?> selected="selected" <?php } ?>>1</option>
							<option value="2" <?php if($num==2){?> selected="selected" <?php } ?>>2</option>
						</select>
						<input type="hidden" name="hdn_deafaultNoQuincena" id="hdn_deafaultNoQuincena" value="<?php echo $num; ?>" />
						
						<select name="cmb_Mes" id="cmb_Mes" class="combo_box" onchange="verificarQuincena('TRASPALEO');">
							<option value="">Mes</option>
							<option value="ENERO" <?php if($mes=="ENERO"){?> selected="selected" <?php } ?>>Enero</option>
							<option value="FEBRERO" <?php if($mes=="FEBRERO"){?> selected="selected" <?php } ?>>Febrero</option>
							<option value="MARZO" <?php if($mes=="MARZO"){?> selected="selected" <?php } ?>>Marzo</option>
							<option value="ABRIL" <?php if($mes=="ABRIL"){?> selected="selected" <?php } ?>>Abril</option>
							<option value="MAYO" <?php if($mes=="MAYO"){?> selected="selected" <?php } ?>>Mayo</option>
							<option value="JUNIO" <?php if($mes=="JUNIO"){?> selected="selected" <?php } ?>>Junio</option>
							<option value="JULIO" <?php if($mes=="JULIO"){?> selected="selected" <?php } ?>>Julio</option>
							<option value="AGOSTO" <?php if($mes=="AGOSTO"){?> selected="selected" <?php } ?>>Agosto</option>
							<option value="SEPTIEMBRE" <?php if($mes=="SEPTIEMBRE"){?> selected="selected" <?php } ?>>Septiembre</option>
							<option value="OCTUBRE" <?php if($mes=="OCTUBRE"){?> selected="selected" <?php } ?>>Octrube</option>
							<option value="NOVIEMBRE" <?php if($mes=="NOVIEMBRE"){?> selected="selected" <?php } ?>>Noviembre</option>
							<option value="DICIEMBRE" <?php if($mes=="DICIEMBRE"){?> selected="selected" <?php } ?>>Diciembre</option>
						</select>
						<input type="hidden" name="hdn_defaultMes" id="hdn_defaultMes" value="<?php echo $mes; ?>" />
						
						<select name="cmb_Anio" id="cmb_Anio" class="combo_box" onchange="verificarQuincena('TRASPALEO');">
							<option value="">A&ntilde;o</option><?php
							//Obtener el Año Actual
							$anioInicio = intval(date("Y")) - 10;
							for($i=0;$i<21;$i++){
								if($anio==$anioInicio)
									echo "<option value='$anioInicio' selected='selected'>$anioInicio</option>";
								else
									echo "<option value='$anioInicio'>$anioInicio</option>";
									
								$anioInicio++;
							}?>							
						</select>
						<input type="hidden" name="hdn_dafultAnio" id="hdn_dafultAnio" value="<?php echo $anio; ?>" />
						
					</td>				
				</tr>
				<tr><td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
				<tr>
					<td colspan="4" align="center">
						<input type="hidden" name="hdn_cambioVolumen" value="no" />
						<input type="hidden" name="hdn_cambioTipoCambio" value="no" />
						
						<input type="submit" name="sbt_continuar" value="Continuar" class="botones" title="Guardar Datos y Modificar Detalle del Traspaleo" 
						onmouseover="window.status='';return true" />
						&nbsp;&nbsp;
						<input type="reset" name="rst_limpiar" value="Restablecer" class="botones" title="Restablecer los Datos del Formulario" />
						&nbsp;&nbsp;
						<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar a la Selecci&oacute;n de Obra" 
						onclick="confirmarSalida('frm_modificarTraspaleo.php')" />						
					</td>
				</tr>					
			</table>
			</form><?php	
				
			//Cerrar la Conexion con la Base de Datos
			mysql_close($conn);
		}
		else{ ?>
			<p class="msje_correcto" align="center">No Hay Datos Registrados para la Obra <?php echo $cmb_nomObra; ?></p><?php
		}?>			
		</fieldset><?php
	}//Cierre if(isset($_POST['sbt_registrar']))
	
	
	if(isset($_POST['sbt_continuar'])){
		subirDatosModTraspaleo();?>
		<meta http-equiv="refresh" content="0;url=frm_modificarDetalleTraspaleo.php"><?php
	}?>
						
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
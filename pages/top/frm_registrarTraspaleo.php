<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php

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
		include ("op_registrarTraspaleo.php");
		include ("op_registrarObra.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>	
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/obtenerPrecioTraspaleo.js"></script>
	<script type="text/javascript" src="includes/ajax/verificarQuincena.js"></script>
	<script type="text/javascript" src="includes/ajax/obtenerDatosObras.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#titulo-Traspaleo { position:absolute; left:30px; top:146px; width:210px; height:20px; z-index:11; }
		#seleccionar-obra { position:absolute; left:30px; top:190px; width:495px; height:170px; z-index:12; }		
		#registrar-traspaleo { position:absolute; left:30px; top:190px; width:798px; height:260px; z-index:13; }
		#registrar-detalle { position:absolute; left:30px; top:190px; width:940px; height:340px; z-index:14; }
		#calendarioElaboracion { position:absolute; left:659px; top:309px; width:30px; height:26px; z-index:15; }
		#ver-detalle { position:absolute; left:30px; top:560px; width:940px; height:117px; z-index:16; overflow:scroll; }
		-->
    </style>
</head>
<body>
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div id="titulo-Traspaleo" class="titulo_barra">Registrar Traspaleo</div><?php 
	
	
	
	//Desplegar el Formulario para SELECCIONAR LA OBRA para registrar el Traspaleo
	if(!isset($_POST['sbt_seleccionar']) && !isset($_POST['sbt_registrarDatos']) && !isset($_POST['sbt_registrarDetalle']) && !isset($_POST['sbt_guardarTraspaleo'])){ 
		//Liberar datos de la SESSION utilizados en el Registro de Traspaleo cuando se entra por primera vez a esta pagina y los datos existen en la SESSION
		unset($_SESSION['datosTraspaleo']);
		unset($_SESSION['registrosTraspaleo']);?>
		
		
		<fieldset id="seleccionar-obra" class="borde_seccion">
		<legend class="titulo_etiqueta">Seleccionar Obra</legend>
		<form onsubmit="return valFormElegirObraTraspaleo(this);" name="frm_elegirObraTraspaleo" method="post" action="frm_registrarTraspaleo.php">
		<table class="tabla_frm" width="100%" cellpadding="5" cellspacing="5">
			<tr>
				<td width="20%" align="right">Tipo Obra</td>
				<td width="70%">
					<script type="text/javascript" language="javascript">
						setTimeout("agregarOpcionCombo();",500);
					</script>
					
					<select name="cmb_tipoObra" id="cmb_tipoObra" class="combo_box" 
					onchange="cargarComboConId(this.value,'bd_topografia','obras','nombre_obra','id_obra','tipo_obra','cmb_nomObra','Obras','');mostrarElementos(this.value);">
						<option value="">Tipo Obra</option>
							<?php 
							$conn = conecta("bd_topografia");//Conectarse con la BD de Topograf&iacute;a
							//Ejecutar la Sentencia para Obtener los tipos de Obra registrados en la BD de Topograf&iacute;a
							$rs_tipos = mysql_query("SELECT DISTINCT tipo_obra FROM obras WHERE tipo_obra NOT LIKE '%ANCLA%' ORDER BY tipo_obra");
							if($tiposObra=mysql_fetch_array($rs_tipos)){
								//Colocar los lugares encontrados
								do{
									echo "<option value='$tiposObra[tipo_obra]'>$tiposObra[tipo_obra]</option>";							
								}while($tiposObra=mysql_fetch_array($rs_tipos));
							}					
							mysql_close($conn);
							?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right"><label id="etq_obra">Obra</label></td>
				<td>
					<select name="cmb_nomObra" id="cmb_nomObra" class="combo_box" onchange="verificarAnclas(cmb_tipoObra.value,this.value);">
						<option value="">Obras</option>
					</select>
					<input type="hidden" name="hdn_seccion" id="hdn_seccion" value=""/>
				</td>
			</tr>
			<tr>
				<td align="right"><label id="etq_categoria" style="visibility:hidden;">Categor&iacute;a</label></td>
				<td>
					<select name="cmb_categoriaObra" id="cmb_categoriaObra" class="combo_box" style="visibility:hidden;">
						<option value="">Categor&iacute;a</option>
						<option value="COSTOS">COSTOS</option>
						<option value="AMORTIZABLE">AMORTIZABLE</option>
					</select>
				</td>
			</tr>			
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="sbt_seleccionar" value="Seleccionar" class="botones" title="Registrar Traspaleo" onmouseover="window.status='';return true" />
					&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Men&uacute; de Traspaleo" 
					onclick="location.href='menu_traspaleo.php'" />
				</td>
			</tr>
		</table>
		</form>
		</fieldset><?php
	}//Cierre if(!isset($_POST['sbt_registrar']) && !isset($_POST['sbt_registrarDatos']))
	
	//Mostrar el Formulario para registrar los DATOS GENERALES DEL TRASPALEO de la Obra seleccionada
	if(isset($_POST['sbt_seleccionar'])){?>
		<fieldset class="borde_seccion" id="registrar-traspaleo" name="registrar-traspaleo">
		<legend class="titulo_etiqueta">Registrar Datos de Traspaleo</legend><br /><?php 
		
		//Declarar los atributos de los campos que cambian segun el tipo de obra seleccionado
		$atrObra = "";
		$atrAcumQuin = "";
		$atrVol = "";
		//Arreglo que contendra los datos generales de la Obra seleccionada
		$datosObra = array();
		//Definir datos No Aplicables para cuando la Obra no esta Registrada
		if($_POST['cmb_tipoObra']=="OBRA_NR" || $_POST['cmb_tipoObra']=="TEMP" || $_POST["hdn_seccion"]=="N/A"){
			$datosObra['seccion'] = "N/A";
			$datosObra['id_obra'] = "OBRA_NR";
			$datosObra['area'] = "0";			
			//Definir los atributos de los campos requeridos para las obras no registtradas (Nombre de la Obra y el Volumen)
			$atrObra = "value=''";
			$atrAcumQuin = "readonly='readonly' value='0.00'";
			$atrVol = "value=''";
			
			if($_POST['cmb_tipoObra']=="TEMP" || $_POST["hdn_seccion"]=="N/A"){
				$datosObra["nombre_obra"] = obtenerDato("bd_topografia","obras","nombre_obra","id_obra",$cmb_nomObra);
				//Definir los atributos para los campos requeridos para las obras registradas en el catalogo
				$atrObra = "readonly='readonly' value='$datosObra[nombre_obra]'";
				$datosObra['id_obra'] = $cmb_nomObra;
			}
			
			//Guardar el la SESSION la categoría de la Obra no registrada seleccionada
			$_SESSION['categoriaObra'] = $_POST['cmb_categoriaObra'];
		}
		//Recuperar los datos de la Obras seleccionada del catalogo de obras
		else if($_POST['cmb_tipoObra']!="" && $_POST['cmb_tipoObra']!="OBRA_NR"){				
			//Obtener los datos de la Obra para mostrarlos en el formulario de captura de Datos
			$conn = conecta("bd_topografia");
			$stm_sql = "SELECT * FROM obras WHERE id_obra = '$cmb_nomObra'";			
			$rs = mysql_query($stm_sql);
			$datosObra=mysql_fetch_array($rs);
			//Definir los atributos para los campos requeridos para las obras registradas en el catalogo
			$atrObra = "readonly='readonly' value='$datosObra[nombre_obra]'";
			$atrAcumQuin = "";
			$atrVol = "readonly='readonly'";
		}?>
		
		
		<script type="text/javascript" language="javascript">
			<?php //Definir el Orden de los campos segun el tipo de obra seleccionado ?>
			setTimeout("colocarOrdenCampos();",500);
		</script>
						
						
		<form onsubmit="return valFormRegistrarDatosTraspaleo(this);" name="frm_registrarDatosTraspaleo" method="post" action="frm_registrarTraspaleo.php">
		<input type="hidden" name="hdn_seccion" id="hdn_seccion" value="<?php echo $_POST["hdn_seccion"]?>"/>
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="20%" align="right">Tipo Obra</td>
				<td width="30%">
					<input type="text" name="txt_tipoObra" id="txt_tipoObra" class="caja_de_texto" value="<?php echo $cmb_tipoObra; ?>" readonly="readonly" 
					size="30" maxlength="30" />
				</td>
				<td width="20%" align="right">Secci&oacute;n</td>
				<td width="30%">
					<input name="txt_seccion" type="text" class="caja_de_texto" id="txt_seccion" value="<?php echo $datosObra['seccion']; ?>" 
					size="10" maxlength="15" readonly="readonly" />
				</td>
			</tr>	
			<tr>
				<td align="right">Obra</td>
				<td>
					<input type="text" name="txt_nombreObra" id="txt_nombreObra" class="caja_de_texto" <?php echo $atrObra; ?> size="40" maxlength="40" />
					<input type="hidden" name="hdn_idObra" id="hdn_idObra" value="<?php echo $datosObra['id_obra'];?>" />						
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
					onchange="formatCurrency(this.value,'txt_acumuladoQuincena'); calcularVolumen(this);" onkeypress="return permite(event,'num',2);"
					<?php echo $atrAcumQuin; ?> />
				</td>
				<td align="right">Vol. M&sup3;</td>
				<td>
					<input type="text" name="txt_volumen" id="txt_volumen" class="caja_de_texto" <?php echo $atrVol;?> 
					onchange="formatCurrency(this.value,'txt_volumen');" size="10" maxlength="15" />
				</td>
			</tr>
			<tr>
				<td align="right">*Tasa de Cambio </td>
				<?php $tCambio=obtenerdato("bd_topografia","tasa_cambio","t_cambio","id",1);?>
				<td>
					<input type="text" name="txt_tasaCambio" id="txt_tasaCambio" class="caja_de_texto" size="10" maxlength="15" value="<?php echo $tCambio?>"
					onchange="formatTasaCambio(this.value,'txt_tasaCambio'); if(!validarEntero(this.value.replace(/,/g,''),'La Tasa de Cambio')){this.value = ''; }" 
					onkeypress="return permite(event,'num',2);" />
				</td>					
				<td align="right">*No. Quincena</td>
				<td>
					<select name="cmb_noQuincena" id="cmb_noQuincena" class="combo_box" onchange="verificarQuincena('TRASPALEO');">
						<option value="">Num.</option>
						<option value="1">1</option>
						<option value="2">2</option>
					</select>						
					<select name="cmb_Mes" id="cmb_Mes" class="combo_box" onchange="verificarQuincena('TRASPALEO');">
						<option value="">Mes</option>
						<option value="ENERO">Enero</option>
						<option value="FEBRERO">Febrero</option>
						<option value="MARZO">Marzo</option>
						<option value="ABRIL">Abril</option>
						<option value="MAYO">Mayo</option>
						<option value="JUNIO">Junio</option>
						<option value="JULIO">Julio</option>
						<option value="AGOSTO">Agosto</option>
						<option value="SEPTIEMBRE">Septiembre</option>
						<option value="OCTUBRE">Octubre</option>
						<option value="NOVIEMBRE">Noviembre</option>
						<option value="DICIEMBRE">Diciembre</option>
					</select>
					<select name="cmb_Anio" id="cmb_Anio" class="combo_box" onchange="verificarQuincena('TRASPALEO');">
						<option value="">A&ntilde;o</option><?php
						//Obtener el Año Actual
						$anioInicio = intval(date("Y")) - 10;
						for($i=0;$i<21;$i++){
							echo "<option value='$anioInicio'>$anioInicio</option>";
							$anioInicio++;
						}?>							
					</select>
				</td>				
			</tr>
			<tr><td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
			<tr>
				<td colspan="4" align="center">
					<input type="submit" name="sbt_registrarDatos" id="sbt_registrarDatos" value="Registrar" class="botones" title="Registrar Datos de Traspaleo" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" id="rst_limpiar" value="Limpiar" class="botones" title="Limpiar Datos del Formulario" />
					&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" class="botones" title="Regresar a la Selecci&oacute;n de Obra" 
					onclick="confirmarSalida('frm_registrarTraspaleo.php')" />						
				</td>
			</tr>					
		</table>
		</form>			
		</fieldset><?php
	}//Cierre if(isset($_POST['sbt_registrar']))
	
	

									
	//Mostrar el Formulario para Registrar el DETALLE DE LOS MOVIMEINTOS en el Traspaleo
	if(isset($_POST['sbt_registrarDatos']) || isset($_POST['sbt_registrarDetalle'])){
		
		//Cargar los Datos Generales del Traspaleo a al SESSION
		if(!isset($_SESSION['datosTraspaleo']))
			subirDatosSession();
		
		//Cargar los Registros del Traspaleo a la SESSION
		if(isset($_POST['sbt_registrarDetalle']))
			cargarRegistrosTraspaleo();?>
			
		<script type="text/javascript" language="javascript">
			<?php //Definir el Orden de los campos segun el tipo de obra seleccionado ?>
			setTimeout("colocarOrdenCampos2();",500);
		</script>
		
		
		<fieldset class="borde_seccion" id="registrar-detalle" name="registrar-detalle">
		<legend class="titulo_etiqueta">Registrar Detalle de Movimientos en Traspaleo</legend>
		<br />
		<form onsubmit="return valFormRegistrarDetalleTraspaleo(this);" name="frm_registrarDetalleTraspaleo" method="post" action="frm_registrarTraspaleo.php">
		<input type="hidden" name="hdn_seccion" id="hdn_seccion" value="<?php echo $_POST["hdn_seccion"]?>"/>
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="18%" align="right">Tipo Obra</td>
				<td width="20%">
					<input name="txt_tipoObra2" type="text" class="caja_de_texto" id="txt_tipoObra2" value="<?php echo $_SESSION['datosTraspaleo']['tipoObra']; ?>" 
					size="30" maxlength="30" readonly="readonly" />				
				</td>
				<td width="15%" align="right">Vol. M&sup3;</td>
				<td width="15%">
					<input type="text" name="txt_volumen2" id="txt_volumen2" class="caja_de_texto" readonly="readonly" size="10" maxlength="15" 
					value="<?php echo $_SESSION['datosTraspaleo']['volumen']; ?>"/>				
				</td>
				<td width="17%" align="right">Precio Unitario M.N.</td>
				<td width="15%"><input type="text" name="txt_pumn" id="txt_pumn" class="caja_de_texto" value="" readonly="readonly" size="10" maxlength="15" /></td>
			</tr>	
			<tr>
				<td align="right">Obra</td>
				<td>
					<input name="txt_nombreObra2" type="text" class="caja_de_texto" id="txt_nombreObra2" value="<?php echo $_SESSION['datosTraspaleo']['nomObra']; ?>" 
					size="40" maxlength="40" readonly="readonly" />				
				</td>
				<td align="right">No. Quincena</td>
			  	<td><?php echo $_SESSION['datosTraspaleo']['noQuincena'];?></td>
			  	<td align="right">Precio Unitario USD</td>
				<td><input type="text" name="txt_puusd" id="txt_puusd" class="caja_de_texto" value="" readonly="readonly" size="10" maxlength="15" /></td>
			</tr>
			<tr>
				<td align="right">Acumulado Quincena</td>
				<td>
					<input type="text" name="txt_acumuladoQuincena2" id="txt_acumuladoQuincena2" class="caja_de_texto" size="10" maxlength="15" 
					value="<?php echo $_SESSION['datosTraspaleo']['acumQuincena'];?>" readonly="readonly" />				
				</td>
				<td align="right">Fecha de Registro</td>
				<td>
					<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" value="<?php echo date("d/m/Y");?>" 
					readonly="readonly" size="10" maxlength="10" />
				</td>
				<td align="right">Total M.N.</td>
				<td><input type="text" name="txt_totalMN" id="txt_totalMN" class="caja_de_texto" readonly="readonly" size="10" maxlength="15" /></td>
			</tr>
			<tr>
				<td align="right">Tasa de Cambio</td>
				<td>
					<input type="text" name="txt_tasaCambio2" id="txt_tasaCambio2" class="caja_de_texto" size="10" maxlength="15"
					value="<?php echo $_SESSION['datosTraspaleo']['tasaCambio']; ?>" readonly="readonly" />
				</td>
				<td align="right">*Origen</td>
				<td>
					<script type="text/javascript" language="javascript">
						setTimeout("document.getElementById('txt_origen').focus();",500);
					</script>
					<input type="text" name="txt_origen" id="txt_origen" class="caja_de_texto" size="25" maxlength="30" onkeypress="return permite(event,'num_car',0);" />
				</td>
				<td align="right">Total USD</td>
				<td><input type="text" name="txt_totalUSD" id="txt_totalUSD" class="caja_de_texto" readonly="readonly" size="10" maxlength="15" /></td>
			</tr>
			<tr>
				<td align="right">Secci&oacute;n</td>
				<td><input name="txt_seccion2" type="text" class="caja_de_texto" id="txt_seccion2" value="<?php echo $_SESSION['datosTraspaleo']['seccion']; ?>" 
					size="10" maxlength="15" readonly="readonly" /></td>
				<td align="right">*Destino</td>
				<td>
					<input type="text" name="txt_destino" id="txt_destino" class="caja_de_texto" size="25" maxlength="30" onkeypress="return permite(event,'num_car',0);" 
					onchange="obtenerPrecio(txt_distancia.value,'<?php echo $_SESSION['datosTraspaleo']['idObra']; ?>','','hdn_incluirPrecio');" />
				</td>
				<td align="right">Importe Total</td>
				<td><input type="text" name="txt_importeTotal" id="txt_importeTotal" class="caja_de_texto" readonly="readonly" size="10" maxlength="15" /></td>
			</tr>
			<tr>
				<td align="right">&Aacute;rea</td>
				<td><input type="text" name="txt_area2" id="txt_area2" class="caja_de_texto" value="<?php echo number_format($_SESSION['datosTraspaleo']['area'],2,".",","); ?>" 
					readonly="readonly" size="10" maxlength="15" /></td>
				<td align="right">*Distancia</td>
				<td>
					<input type="text" name="txt_distancia" id="txt_distancia" class="caja_de_texto" size="10" maxlength="15" onkeypress="return permite(event,'num',2);"
					onchange="validarCampoNumerico(this,'La Distancia'); validarDistancia(this); obtenerPrecio(this.value,'<?php echo $_SESSION['datosTraspaleo']['idObra']; ?>','','hdn_incluirPrecio');" />
					&nbsp;Mts.
				</td><?php
				//Colocar un Combo con la Lista de Precios cuando la obra no este registrada
				if($_SESSION['datosTraspaleo']['tipoObra']=="OBRA_NR" || $_SESSION['datosTraspaleo']['tipoObra']=="TEMP" || $_POST["hdn_seccion"]="N/A"){?>
					<td align="right">*Lista Precio</td>
					<td><?php 
						//Recuperar el valor de la Lista de precios seleccionada por primera vez
						$listaPrecios = ""; 
						if(isset($_POST['cmb_listaPrecios']))
							$listaPrecios = $_POST['cmb_listaPrecios'];
							
						$res = cargarComboConId("cmb_listaPrecios","tipo","tipo","precios_traspaleo","bd_topografia","Listas","$listaPrecios",
						"obtenerPrecio(txt_distancia.value,'".$_SESSION['datosTraspaleo']['idObra']."','','hdn_incluirPrecio');");	
						if($res==0){?>
							<span class="msje_correcto">No Hay Listas Registradas</span><?php
						}?>
					</td><?php
				}else{?>
					<td>&nbsp;</td>
					<td>&nbsp;</td><?php
				}?>
			</tr>
			<tr>
				<td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
				<td>&nbsp;</td>
				<td colspan="2">
					<input type="checkbox" name="ckb_regConCosto" id="ckb_regConCosto" onclick="activarCamposPrecios(this);" />
					<input type="hidden" name="hdn_incluirPrecio" id="hdn_incluirPrecio" value="no" />
					<label id="msj_etiqueta">Incluir Costo en Primer Registro</label>				
				</td>				
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input type="hidden" name="hdn_botonSeleccionado" value="" /><?php
					if(isset($_SESSION['registrosTraspaleo'])){?>
						<input type="submit" name="sbt_guardarTraspaleo" id="sbt_guardarTraspaleo" value="Finalizar" class="botones" title="Guardar Registros de Traspaleo" 
						onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='FINALIZAR'" />
						&nbsp;&nbsp;<?php
					}?>
					<input type="submit" name="sbt_registrarDetalle" id="sbt_registrarDetalle" value="Agregar Registro" class="botones" title="Agregar Registro de Traspaleo" 
					onmouseover="window.status='';return true"
					onclick="hdn_botonSeleccionado.value='AGREGAR'" />
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Limpiar Datos del Formulario" tabindex="7" />
					&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" class="botones" title="Regresar a la Selecci&oacute;n de Obra" 
					onclick="confirmarSalida('frm_registrarTraspaleo.php')" />
				</td>				
			</tr>					
		</table>
		</form>						
		</fieldset>
		
		<div id="calendarioElaboracion">
			<input type="image" name="txt_fechaElaborado" id="txt_fechaElaborado" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_registrarDetalleTraspaleo.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar la Fecha del Registro de Traspaleo"/> 
		</div><?php
		
		
		
		//Si el Arreglo $_SESSION['registrosTraspaleo'] no esta definido, incluirel Checkbox para Agregar Precio al Primer Registro 
		if(!isset($_SESSION['registrosTraspaleo'])){?>
			<script type="text/javascript" language="javascript">
				colocarLeyenda();
			</script><?php			
		}
		else if(isset($_SESSION['registrosTraspaleo'])){?>
			<script type="text/javascript" language="javascript">
				document.getElementById("ckb_regConCosto").style.visibility="hidden";
				document.getElementById("msj_etiqueta").style.visibility="hidden";
				document.getElementById("hdn_incluirPrecio").value="si";
			</script><?php
		}
		
		
		
		//Mostrar los Registros Existentes del Traspaleo
		if(isset($_SESSION['registrosTraspaleo'])){?>
			<div id="ver-detalle" class="borde_seccion2" align="center"><?php
				mostrarRegistrosTraspaleo();?>			
			</div><?php			
		}
		
		
		
	}//Cierre if(isset($_POST['sbt_registrar']))
		
	//Guardar la Infomación y los registros de Traspaleo																				
	if(isset($_POST['sbt_guardarTraspaleo'])){
		guardarRegistrosTraspaleo();
	}
	?>						
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
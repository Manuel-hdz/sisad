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
		include("head_menu.php");
		include("op_modificarEquipoPesado.php");
		
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>

	<style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:362px;height:20px;z-index:11;}
		#tabla-seleccionarObra {position:absolute;left:30px;top:190px;width:720px;height:330px;z-index:12;}
		#calendarioElaboracion { position:absolute; left:583px; top:233px; width:30px; height:26px; z-index:15; }
		#detalle_traspaleo {position:absolute;left:30px;top:190px;width:900px;height:420px;z-index:13;overflow:scroll;}
		#btn-regresar {position:absolute;left:30px;top:660px;width:930px;height:40px;z-index:14;}
		-->
    </style>
	
	<script type="text/javascript" language="javascript">
		function abrirEquiposTop(boton,idBitacora){
			window.open('verEquiposBit.php?btn='+boton+'&idBit='+idBitacora,'_blank','top=100, left=100, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=no, toolbar=no, location=no, directories=no');
		}
	</script>
</head>
<body>	
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Modificar Obras con Equipo Pesado</div>

	<?php
	if(!isset($_POST["sbt_modificarReg"])){
		$idBitacora=$_POST["rdb_idRegistro"];
		$idRegistro=obtenerDato("bd_topografia","bitacora_eq_pesado","equipo_pesado_id_registro","idbitacora",$idBitacora);
		$tipoObra=obtenerDato("bd_topografia","equipo_pesado","fam_equipo","id_registro",$idRegistro);
		$fecha=modFecha(obtenerDato("bd_topografia","bitacora_eq_pesado","fecha_registro","idbitacora",$idBitacora),1);
		$concepto=obtenerDato("bd_topografia","equipo_pesado","concepto","id_registro",$idRegistro);
		$pumn=obtenerDato("bd_topografia","equipo_pesado","pumn_estimacion","id_registro",$idRegistro);
		$puusd=obtenerDato("bd_topografia","equipo_pesado","puusd_estimacion","id_registro",$idRegistro);
		$tasaCambio=obtenerDato("bd_topografia","bitacora_eq_pesado","t_cambio","idbitacora",$idBitacora);
		$noQuincena=explode(" ",obtenerDato("bd_topografia","bitacora_eq_pesado","no_quincena","idbitacora",$idBitacora));
		
		$conn=conecta("bd_topografia");
		$cantAvance=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM detalle_eq_pesado WHERE bitacora_eq_pesado_idbitacora='$idBitacora'"));
		$cantTotal=$cantAvance["total"];
		mysql_close($conn);
		?>
		
		<fieldset class="borde_seccion" id="tabla-seleccionarObra" name="tabla-seleccionarObra">
		<legend class="titulo_etiqueta">Complementar los Datos</legend>	
		<br>
		<form name="frm_guardarBitEquipo" method="post" action="frm_modificarEquipoPesado2.php" onsubmit="return valFormRegBitEquipo(this);">
		<input type="hidden" name="hdn_idReg" id="hdn_idReg" value="<?php echo $idRegistro?>"/>
		<input type="hidden" name="hdn_idBit" id="hdn_idBit" value="<?php echo $idBitacora?>"/>
		<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
				<td width="20%" align="right">Tipo Equipo Obra</td>
				<td width="24%">
					<input type="text" name="txt_tipoObra" id="txt_tipoObra" class="caja_de_texto" value="<?php echo $tipoObra; ?>" readonly="readonly" 
					size="30" maxlength="30" />
				</td>
				<td width="16%" align="right">Fecha</td>
			  <td width="40%"><input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" value="<?php echo $fecha;?>" 
					readonly="readonly" size="10" maxlength="10" />		  </td>
			</tr>	
			<tr>
				<td align="right">Obra</td>
				<td colspan="3">
					<input type="text" name="txt_nombreObra" id="txt_nombreObra" class="caja_de_texto" value="<?php echo $concepto; ?>" size="80" readonly="readonly"/>
				</td>
			</tr>
			<tr>
				<td align="right">Precio Unitario M.N.</td>
				<td>$
				  <input type="text" name="txt_precioUMN" id="txt_precioUMN" class="caja_de_texto" value="<?php echo number_format($pumn,2,".",",");?>" size="10" readonly="readonly"/></td>
				<td align="right">Precio Unitario USD</td>
				<td>$
				  <input type="text" name="txt_precioUUSD" id="txt_precioUUSD" class="caja_de_texto" value="<?php echo number_format($puusd,2,".",",");?>" size="10" readonly="readonly"/></td>
			</tr>
			<tr>
				<td align="right">Cantidad Total</td>
				<td colspan="3">
				  <input type="text" name="txt_cantidadTotal" id="txt_cantidadTotal" class="caja_de_texto" value="<?php echo number_format($cantTotal,2,".",","); ?>" size="10" readonly="readonly"/></td>
			</tr>
			<tr>
				<td align="right">Tasa de Cambio </td>
				<td>
					<input type="text" name="txt_tasaCambio" id="txt_tasaCambio" class="caja_de_texto" size="10" maxlength="15"
					onchange="formatTasaCambio(this.value,'txt_tasaCambio'); if(!validarEnteroConCero(this.value.replace(/,/g,''),'La Tasa de Cambio')){this.value = ''; };obtenerTotalUSD(this.value);" 
					onkeypress="return permite(event,'num',2);" value="<?php if($tasaCambio!=0) echo $tasaCambio; else echo "0.0000";?>"/></td>					
				<td align="right">*No. Quincena</td>
				<td><select name="cmb_noQuincena" id="cmb_noQuincena" class="combo_box" onchange="">
				  <option value="">Num.</option>
				  <option value="1"<?php if($noQuincena[0]==1) echo " selected='selected'"?>>1</option>
				  <option value="2"<?php if($noQuincena[0]==2) echo " selected='selected'"?>>2</option>
				</select>
				  <select name="cmb_Mes" id="cmb_Mes" class="combo_box" onchange="">
					<option value="">Mes</option>
					<option value="ENERO"<?php if($noQuincena[1]=="ENERO") echo " selected='selected'"?>>Enero</option>
					<option value="FEBRERO"<?php if($noQuincena[1]=="FEBRERO") echo " selected='selected'"?>>Febrero</option>
					<option value="MARZO"<?php if($noQuincena[1]=="MARZO") echo " selected='selected'"?>>Marzo</option>
					<option value="ABRIL"<?php if($noQuincena[1]=="ABRIL") echo " selected='selected'"?>>Abril</option>
					<option value="MAYO"<?php if($noQuincena[1]=="MAYO") echo " selected='selected'"?>>Mayo</option>
					<option value="JUNIO"<?php if($noQuincena[1]=="JUNIO") echo " selected='selected'"?>>Junio</option>
					<option value="JULIO"<?php if($noQuincena[1]=="JULIO") echo " selected='selected'"?>>Julio</option>
					<option value="AGOSTO"<?php if($noQuincena[1]=="AGOSTO") echo " selected='selected'"?>>Agosto</option>
					<option value="SEPTIEMBRE"<?php if($noQuincena[1]=="SEPTIEMBRE") echo " selected='selected'"?>>Septiembre</option>
					<option value="OCTUBRE"<?php if($noQuincena[1]=="OCTUBRE") echo " selected='selected'"?>>Octubre</option>
					<option value="NOVIEMBRE"<?php if($noQuincena[1]=="NOVIEMBRE") echo " selected='selected'"?>>Noviembre</option>
					<option value="DICIEMBRE"<?php if($noQuincena[1]=="DICIEMBRE") echo " selected='selected'"?>>Diciembre</option>
				  </select>
				  <select name="cmb_Anio" id="cmb_Anio" class="combo_box" onchange="">
					<option value="">A&ntilde;o</option>
					<?php
						//Obtener el A&ntilde;o Actual
						$anioInicio = intval(date("Y")) - 10;
						for($i=0;$i<21;$i++){
							if($anioInicio==$noQuincena[2])
								echo "<option value='$anioInicio' selected='selected'>$anioInicio</option>";
							else
								echo "<option value='$anioInicio'>$anioInicio</option>";
							$anioInicio++;
						}?>
				  </select></td>
			<tr>
				<td colspan="4" align="center">
					Total M.N.
					$<input type="text" name="txt_totalMN" id="txt_totalMN" class="caja_de_texto" value="<?php echo number_format(($cantTotal*$pumn),2,".",",");?>" size="10" readonly="readonly"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					Total USD
					$<input type="text" name="txt_totalUSD" id="txt_totalUSD" class="caja_de_texto" value="<?php echo number_format(($cantTotal*$puusd*$tasaCambio),2,".",",");?>" size="10" readonly="readonly"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					Importe Total
					$<input type="text" name="txt_totalImporte" id="txt_totalImporte" class="caja_de_texto" 
					value="<?php echo number_format(($cantTotal*$pumn)+($cantTotal*$puusd*$tasaCambio),2,".",",");?>" size="10" readonly="readonly"/>
				</td>
			</tr>
			<tr><td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
			<tr>
				<td colspan="4" align="center">
					<input type="button" name="btn_horasEquipo" id="btn_horasEquipo" value="Registro Equipos" class="botones" title="Abrir Ventana para Readecuar los tiempos de los Equipos"
					onclick="abrirEquiposTop(this.name,'<?php echo $idBitacora?>');"/>
					&nbsp;&nbsp;
					<input type="submit" name="sbt_modificarReg" id="sbt_modificarReg" value="Guardar" class="botones" title="Guardar el Registro Modificado" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" id="rst_limpiar" value="Restablecer" class="botones" title="Restablecer Datos del Formulario" />
					&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" class="botones" title="Regresar a la Selecci&oacute;n de Obra" 
					onclick="location.href='frm_modificarEquipoPesado.php'"/>
				</td>
			</tr>					
		</table>
		</form>
		</fieldset>
	
		<div id="calendarioElaboracion">
			<input type="image" name="txt_fechaElaborado" id="txt_fechaElaborado" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_guardarBitEquipo.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar la Fecha del Registro de Equipo Pesado"/> 
		</div>
	<?php
	}
	else{
		modificarRegistro();
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
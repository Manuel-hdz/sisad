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
		include("op_registrarBitEquipo.php");
		
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>

	<style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:362px;height:20px;z-index:11;}
		#tabla-seleccionarObra {position:absolute;left:30px;top:190px;width:720px;height:330px;z-index:12;}
		#calendarioObra {position:absolute;left:733px;top:268px;width:30px;height:26px;z-index:13;}
		#calendarioElaboracion { position:absolute; left:583px; top:233px; width:30px; height:26px; z-index:15; }
		-->
    </style>
</head>
<body>	
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Registrar Obras con Equipo Pesado</div>
	
	<?php
	
	if(isset($_POST["sbt_guardarRegEqP"]))
		guardarRegistro();
	else{	
		$tipoObra=$_GET["familia"];
		$concepto=obtenerDato("bd_topografia","equipo_pesado","concepto","id_registro",$_GET["idReg"]);
		$pumn=number_format(obtenerDato("bd_topografia","equipo_pesado","pumn_estimacion","id_registro",$_GET["idReg"]),2,".",",");
		$puusd=number_format(obtenerDato("bd_topografia","equipo_pesado","puusd_estimacion","id_registro",$_GET["idReg"]),2,".",",");

		$tCambio=obtenerdato("bd_topografia","tasa_cambio","t_cambio","id",1);
		
		$cantTotal=array_sum($_SESSION["registroEquipos"]);
		?>
		<fieldset class="borde_seccion" id="tabla-seleccionarObra" name="tabla-seleccionarObra">
		<legend class="titulo_etiqueta">Complementar los Datos</legend>	
		<br>
		<form name="frm_guardarBitEquipo" method="post" action="frm_registrarEquipoPesado2.php" onsubmit="return valFormRegBitEquipo(this);">
		<input type="hidden" name="hdn_idReg" id="hdn_idReg" value="<?php echo $_GET["idReg"]?>"/>
		<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
				<td width="20%" align="right">Tipo Equipo Obra</td>
				<td width="24%">
					<input type="text" name="txt_tipoObra" id="txt_tipoObra" class="caja_de_texto" value="<?php echo $tipoObra; ?>" readonly="readonly" 
					size="30" maxlength="30" />
				</td>
				<td width="16%" align="right">Fecha</td>
			  <td width="40%"><input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" value="<?php echo date("d/m/Y");?>" 
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
				  <input type="text" name="txt_precioUMN" id="txt_precioUMN" class="caja_de_texto" value="<?php echo $pumn; ?>" size="10" readonly="readonly"/></td>
				<td align="right">Precio Unitario USD</td>
				<td>$
				  <input type="text" name="txt_precioUUSD" id="txt_precioUUSD" class="caja_de_texto" value="<?php echo $puusd; ?>" size="10" readonly="readonly"/></td>
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
					onkeypress="return permite(event,'num',2);" value="<?php echo $tCambio?>"/></td>					
				<td align="right">*No. Quincena</td>
				<td><select name="cmb_noQuincena" id="cmb_noQuincena" class="combo_box" onchange="">
				  <option value="">Num.</option>
				  <option value="1">1</option>
				  <option value="2">2</option>
				</select>
				  <select name="cmb_Mes" id="cmb_Mes" class="combo_box" onchange="">
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
				  <select name="cmb_Anio" id="cmb_Anio" class="combo_box" onchange="">
					<option value="">A&ntilde;o</option>
					<?php
						//Obtener el A&ntilde;o Actual
						$anioInicio = intval(date("Y")) - 10;
						for($i=0;$i<21;$i++){
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
					$<input type="text" name="txt_totalUSD" id="txt_totalUSD" class="caja_de_texto" value="<?php echo number_format(($cantTotal*$puusd*$tCambio),2,".",",");?>" size="10" readonly="readonly"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					Importe Total
					$<input type="text" name="txt_totalImporte" id="txt_totalImporte" class="caja_de_texto" value="<?php echo number_format(($cantTotal*$pumn)+(($cantTotal*$puusd*$tCambio)),2,".",",");?>" 
					size="10" readonly="readonly"/>
				</td>
			</tr>
			<tr><td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
			<tr>
				<td colspan="4" align="center">
					<input type="submit" name="sbt_guardarRegEqP" id="sbt_guardarRegEqP" value="Guardar" class="botones" title="Guardar Registro de Equipos" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" id="rst_limpiar" value="Limpiar" class="botones" title="Limpiar Datos del Formulario" />
					&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" class="botones" title="Regresar a la Selecci&oacute;n de Obra" 
					onclick="confirmarSalida('frm_registrarEquipoPesado.php?familia=<?php echo $_GET["familia"]?>&idReg=<?php echo $_GET["idReg"]?>')" />
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
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
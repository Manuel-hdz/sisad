<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo USO
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo guarda la informacion del registro de Radiografias en la bitacora
		include ("op_bitacoraRadiografias.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	
    <style type="text/css">
		<!--
		#titulo-generar{ position:absolute; left:30px; top:146px; width:285px; height:19px; z-index:11;}
		#tabla-clasificacion{ position:absolute; left:30px; top:190px; width:499px;	height:147px; z-index:12; }
		#tabla-complementar-radiografia{ position:absolute; left:30px; top:190px; width:850px;	height:304px; z-index:13; }
		#calendario { position:absolute; left:737px; top:233px; width:30px; height:26px; z-index:14; }
		#res-spider {position:absolute;z-index:15;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Registrar Bit&aacute;cora de Radiograf&iacute;as</div>

	<?php 
	//Si esta definido el boton de cancelar, ppara borrar el posible arreglo de Session
	if(isset($_GET["cancel"])){
		if(isset($_SESSION["radiografias"]))
			unset($_SESSION["radiografias"]);
	}
	
	//Si esta definido el boton de Guardar, guardar el registro en la BD
	if(isset($_POST["sbt_guardar"])){
		guardarRegBitRadiografias();
	}
		
	if(!isset($_POST["sbt_continuar"])){?>
		<fieldset id="tabla-clasificacion" class="borde_seccion">
		<legend class="titulo_etiqueta">Seleccionar el Tipo de Registro a Realizar </legend>
		<br>	
		<form onsubmit="return valFormSelRegBitRadio(this);" name="frm_seleccionarRegBitRadio" method="post" action="frm_regBitacoraRadiografias.php" >
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td width="127"><div align="right">Tipo Clasificaci&oacute;n</div></td>
				<td width="337">
					<select name="cmb_clasificacion" class="combo_box" id="cmb_clasificacion" onchange="filtroBitacoraRadiografia(this.value);">
						<option value="" selected="selected">Clasificaci&oacute;n</option>
						<option value="EXTERNO">EXTERNO</option>
						<option value="INGRESO">INGRESO</option>
						<option value="INTERNO">INTERNO</option>
					</select>
			  </td>			
			</tr>
			<tr>
				<td><div align="right"><span id="etiqueta">&nbsp;</span></div></td>
				<td><span id="componenteHTML">&nbsp;</span></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="sbt_continuar" id="sbt_continuar" class="botones" title="Continuar a Registrar las Radiograf&iacute;as" value="Continuar" onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" title="Limpiar los Datos" value="Limpiar" onclick="limpiarBitRadiografia();"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Volver al Men&uacute; de Radiograf&iacute;as" value="Regresar" onclick="location.href='menu_bitacoraRadiografias.php'"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
	<?php }
	if(isset($_POST["sbt_continuar"])){
		//Obtener el Id de la Bitácora
		$idBitacora=obtenerIdBitRadio();
		$empleado="";
		$num="";
		$area="";
		$puesto="";
		$empresa="";
		//Verificar el tipo de clasificacion
		if($_POST["cmb_clasificacion"]!="EXTERNO"){
			$empleado=strtoupper($_POST["txt_nombre"]);
			if($_POST["cmb_clasificacion"]=="INTERNO"){
				$num=obtenerDatoEmpleadoPorNombre("id_empleados_empresa",$empleado);
				$area=obtenerDatoEmpleadoPorNombre("area",$empleado);
				$puesto=obtenerDatoEmpleadoPorNombre("puesto",$empleado);
			}
		}
		else
			$empresa=$_POST["cmb_empresas"];
		?>
		<fieldset id="tabla-complementar-radiografia" class="borde_seccion">
		<legend class="titulo_etiqueta">Ingresar la Informaci&oacute;n del Trabajador</legend>
		<br>
		<form name="frm_registrarRadiografia" method="post" onsubmit="return valFormGuardarRadio(this);">
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td><div align="right">Id Bit&aacute;cora</div></td>
				<td>
					<input type="text" name="txt_idBit" id="txt_idBit" value="<?php echo $idBitacora;?>" size="10" maxlength="10" readonly="readonly" class="caja_de_texto"/>
				</td>
				<td><div align="right">Fecha</div></td>
				<td>
					<input name="txt_fecha" type="text" id="txt_fecha" value=<?php echo date("d/m/Y");?> size="10" maxlength="15" readonly="readonly" class="caja_de_texto"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre Empleado</div></td>
				<td>
					<input type="text" name="txt_nombre" id="txt_nombre" value="<?php echo $empleado;?>" size="50" maxlength="75" onkeypress="return permite(event,'car',0);" class="caja_de_texto"/>
				</td>
				<td><div align="right">*Lugar donde se Practic&oacute;</div></td>
				<td>
					<input type="text" name="txt_lugar" id="txt_lugar" value="UNIDAD DE SALUD OCUPACIONAL" size="35" maxlength="30" onkeypress="return permite(event,'car',0);" class="caja_de_texto"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*N&uacute;mero Empleado</div></td>
				<td>
					<input type="text" name="txt_numE" id="txt_numE" value="<?php echo $num;?>" size="10" maxlength="10" onkeypress="return permite(event,'num',0);" class="caja_de_num"/>
				</td>
				<td><div align="right">*Cantidad de Proyecciones</div></td>
				<td>
					<input type="text" name="txt_cantProy" id="txt_cantProy" value="0" size="5" readonly="readonly" class="caja_de_num"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*&Aacute;rea</div></td>
				<td>
					<input type="text" name="txt_area" id="txt_area" value="<?php echo $area;?>" size="20" maxlength="20" onkeypress="return permite(event,'num_car',0);" class="caja_de_texto"/>
				</td>
				<td><div align="right">*Nombre Solicitante</div></td>
				<td>
					<input type="text" name="txt_nomSolicitante" id="txt_nomSolicitante" value="" size="40" maxlength="75" onkeypress="return permite(event,'car',0);" class="caja_de_texto"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Puesto</div></td>
				<td>
					<input type="text" name="txt_puesto" id="txt_puesto" value="<?php echo $puesto;?>" size="30" maxlength="30" onkeypress="return permite(event,'num_car',0);" class="caja_de_texto"/>
				</td>
				<td><div align="right">*Nombre Responsable</div></td>
				<td>
					<input type="text" name="txt_nomResponsable" id="txt_nomResponsable" value="DR. MALCO OBED GARC&Iacute;A BORJ&Oacute;N" size="40" maxlength="75" onkeypress="return permite(event,'car',0);" class="caja_de_texto"/>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<strong>*Datos marcados con asterisco (*) son obligatorios</strong>
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="hidden" name="hdn_categoria" id="hdn_categoria" value="<?php echo $_POST["cmb_clasificacion"]?>"/>
					<input type="hidden" name="hdn_empresa" id="hdn_empresa" value="<?php echo $empresa?>"/>
					<input type="submit" name="sbt_guardar" id="sbt_guardar" value="Guardar" class="botones" title="Guardar el Registro" onmouseover="window.status='';return true;" disabled="disabled"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regRadiografias" id="btn_regRadiografias" value="Registrar Radiograf&iacute;as" class="botones_largos" 
					title="Registrar las Radiograf&iacute;as realizadas" onclick="abrirVentanaRadiografias(this);"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_limpiar" id="btn_limpiar" value="Limpiar" class="botones" title="Limpiar el Formulario" onclick="restablecerFormularioBitRadio();"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" class="botones" title="Cancela el Guardado y Regresa a la Secci&oacute;n Anterior" 
					onclick="location.href='frm_regBitacoraRadiografias.php?cancel'"/>
				</td>
			</tr>
		</table>
		</form>
</fieldset>
		
		<div id="calendario">
			<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_registrarRadiografia.txt_fecha,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<?php
	}
	?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
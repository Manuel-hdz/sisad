<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Almac�n
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Manejo de la funciones para registrar la Entrada de Materiales en la BD 
		include ("op_entradaMaterial.php");	
		
		//Eliminar los materiales registrados, cuando el proceso de regitro no se haya finalizado correctamente
		if(isset($_SESSION['procesoRegistroMat']) && $_SESSION['procesoRegistroMat']=="NoTerminado"){ 			
			deshacerCambios($_SESSION['clavesRegistradasMat']);						
		}
		//Si en el GET esta definido el parametro lmp y vale SI, verificar si existe el arreglo de Session
		//con los materiales a darles entrada
		if(isset($_GET["lmp"]) && $_GET["lmp"]=="si"){
			//Si el arreglo de Sesion esta definido, borrarlo
			if(isset($_SESSION["datosEntrada"]))
				unset($_SESSION["datosEntrada"]);
			//Si esta definido el origen, tambien lo esta el numero del mismo, ya que se declaran casi simultaneamente, borrar ambos en caso de 
			//Llegar a esta seccion
			if(isset($_SESSION["origen"])){
				unset($_SESSION['origen']);
				unset($_SESSION['no_origen']);
				unset($_SESSION["bd"]);
			}
			if(isset($_SESSION["nomMaterialesPedido"]))
				unset($_SESSION["nomMaterialesPedido"]);
		}
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>	
	<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarOrigenEntradas.js"></script>
	
	<script type="text/javascript" language="javascript">
		
	</script>
    <style type="text/css">
		<!--
		#titulo-entrada { position:absolute; left:30px; top:146px; width:200px; height:21px; z-index:11; }
		#form-entrada-material { position:absolute; left:4px; top:190px; width:467px; height:311px; z-index:13; }
		#form-origen-material { position:absolute; left:30px; top:190px; width:540px; height:200px; z-index:12; }
		#material-agregado { position:absolute; left:512px; top:195px; width:398px; height:350px; z-index:16; }
		#boton-terminar { position:absolute; left:186px; top:445px; width:141px; height:37px; z-index:15; }
		#cargar-datos { position:absolute; left:7px; top:190px; width:913px; height:472px; z-index:17; }
		#devolucion-equipo {position:absolute;left:8px;top:545px;width:457px;height:82px;z-index:18;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-entrada">Entrada Material</div>
	
	<fieldset class="borde_seccion" id="form-origen-material" name="form-origen-material" style="height:250px;">
	<legend class="titulo_etiqueta">Seleccionar Origen de la Entrada</legend>
	<br>
	<form name="frm_cargarInfo" method="post" action="frm_entradaMaterial2A.php" onsubmit="return valFormEntradaMaterialV2(this);">	
	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="86" align="right">Origen:</td>
			<td width="300">					    					
				<select name="cmb_param" id="cmb_param" size="1" onChange="seleccionarCriterio(this.value);" title="Seleccionar Categor&iacute;a" class="combo_box">
					<option value="">Origen</option>
					<option value="compra_directa">Compra Directa</option>
					<!--<option value="id_orden_compra">Orden de Compra</option>-->
					<!--<option value="id_requisicion">Requisici&oacute;n</option>-->
					<option value="pedido">Pedido</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">
				<span id="etiquetaCriterio2" style="visibility:hidden">Pedido</span>
			</td>
			<td >
				<input type="text" name="txt_pedido" id="txt_pedido" class="caja_de_texto" size="20" maxlength="30" style="visibility:hidden" onchange="extraerInfoPedido(this.value);verificarOpcion(cmb_param.value,this);" onkeypress="return permite(event,'num_car');"/>
			</td>
		</tr>
		<tr>
			<td align="right">
				<span id="etiquetaCriterio" style="visibility:hidden">Seleccionar</span>
			</td>
			<td >
				<select name="cmb_opciones" id="cmb_opciones" onchange="verificarOpcion(cmb_param.value,this);" title="Seleccionar la Opci&oacute;n de Entrada" class="combo_box" style="visibility:hidden">
					<option value="">Seleccionar</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">
				<span id="etiquetaNumero" style="visibility:hidden">N&uacute;mero</span>
			</td>
			<!-- <td>
				<select name="cmb_numero" id="cmb_numero" title="Seleccionar el N&uacute;mero de Requisici&oacute;n" class="combo_box" style="visibility:hidden;">
					<option value="">Seleccionar</option>
				</select>
			</td> -->
			<td>
				<input type="text" name="txt_req" id="txt_req" class="caja_de_texto" size="20" maxlength="30" style="visibility:hidden" onchange="extraerInfoReq(this.value,cmb_opciones.value);verificarOpcion(cmb_param.value,this);" onkeypress="return permite(event,'num_car');"/>
			</td>
		</tr>
		<tr>
			<td align="right">
				<span id="etiquetaNumero" style="visibility:hidden">N&uacute;mero</span>
			</td>
			<td>
				<select name="cmb_numero" id="cmb_numero" title="Seleccionar el N&uacute;mero de Requisici&oacute;n" class="combo_box" style="visibility:hidden;">
					<option value="">Seleccionar</option>
				</select>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<input type="hidden" name="hdn_valPal" id="hdn_valPal" value="stock"/>
				<input type="submit" name="sbt_entradaMateriales" id="sbt_entradaMateriales" value="Continuar" title="Continuar con el Proceso de Entrada" class="botones" onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Entradas/Salidas" 
				onClick="location.href='menu_entrada_salida.php'" />
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
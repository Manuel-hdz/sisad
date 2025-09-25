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
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y
		//da acceso al archivo op_operacionesBD.php
		include("head_menu.php");
		//Archivo de Operacion sobre el Registro de Pagos
		include("op_gestionarPagos.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<?php //Busqueda Sphider?>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<?php //Busqueda Sphider?>
	<script type="text/javascript" src="includes/ajax/validarNumPedido.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personalBajas.js"></script>


	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute; left:30px; top:146px; width:154px; height:22px; z-index:11;}
		#tabla-registrarPago {position:absolute; left:21px; top:181px; width:931px; height:316px;z-index:12;}
		#calendario{position:absolute;left:215px;top:207px; width:30px;	height:26px;z-index:14;}
		#res-spider{position:absolute;z-index:15;}
		#resultados{position:absolute; left:22px; top:526px; width:931px; height:154px;z-index:16;overflow:scroll;}
		-->
    </style>
</head>
<body>	

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Registrar Pago</div>

	<?php
	//Si el boton de finalizar esta en el POST, guardar el Pago
	if(isset($_POST["sbt_finalizar"]))
		guardarPago();
	else{
		if(isset($_POST["sbt_agregar"])){
			//Agregar Registro al Arreglo de Sesion de los detalles del Pago
			$partida=$_POST["txt_partida"];
			$factura=strtoupper($_POST["txt_factura"]);
			$statusFactura=$_POST["cmb_estado"];
			$pedido=strtoupper($_POST["txt_pedido"]);
			$responsable=strtoupper($_POST["txt_responsable"]);
			$formaPago=$_POST["cmb_viaPago"];
			$subtotal=str_replace(",","",$_POST["txt_subtotal"]);
			$iva=str_replace(",","",$_POST["txt_iva"]);
			$total=str_replace(",","",$_POST["txt_total"]);
			$aplicacion=strtoupper($_POST["txt_aplicacion"]);
			$concepto=strtoupper($_POST["txt_concepto"]);

			//Si ya esta definido el arreglo de Sesion de los detalles del Pago, entonces agregar el siguiente registro a el
			if(isset($_SESSION['detallesPago'])){	
				//Guardar los datos en el arreglo
				$detallesPago[] = array("partida"=>$partida,"factura"=>$factura,"statusFactura"=>$statusFactura,"pedido"=>$pedido,"responsable"=>$responsable,"formaPago"=>$formaPago,"subtotal"=>$subtotal,
										"iva"=>$iva,"total"=>$total,"aplicacion"=>$aplicacion,"concepto"=>$concepto);
			}
			//Si no esta definido el arreglo de Sesion de los detalles del Pago definirlo y agregar el primer registro
			else{		
				$detallesPago = array(array("partida"=>$partida,"factura"=>$factura,"statusFactura"=>$statusFactura,"pedido"=>$pedido,"responsable"=>$responsable,"formaPago"=>$formaPago,"subtotal"=>$subtotal,
											"iva"=>$iva,"total"=>$total,"aplicacion"=>$aplicacion,"concepto"=>$concepto));
				$_SESSION['detallesPago'] = $detallesPago;
			}
			//Fin de Agregar Registro Bono en Arreglo de Nomina
		}
		
		if(!isset($_POST["txt_partida"])){
			$partida=1;
			$atrib="";
			$prov="";
			$fecha=date("d/m/Y");
			$filtro="";
		}
		else{
			$partida=$_POST["txt_partida"]+1;
			$atrib=" readonly='readonly'";
			$prov=$_POST["txt_nombre"];
			$fecha=$_POST["txt_fecha"];
			$filtro=$_POST["cmb_tipo"];
		}
		?>
	
		<fieldset class="borde_seccion" id="tabla-registrarPago" name="tabla-registrarPago">
		<legend class="titulo_etiqueta">Registar Pagos</legend>
		<form name="frm_registrarPago" method="post" onsubmit="return valFormRegPagos(this);">
		<table width="102%" cellpadding="4" cellspacing="4" class="tabla_frm">
			<tr>
		  	  <td width="61"><div align="right">Fecha</div></td>
				<td width="150">
			  		<input name="txt_fecha" type="text" id="txt_fecha" value=<?php echo $fecha;?> size="10" maxlength="15" readonly="true" width="90"/>			  	
			  </td>
			  <td width="92"><div align="right">Filtrar Por</div></td>
			  	<td width="240">
					<?php if($filtro==""){?>
					<select id="cmb_tipo" name="cmb_tipo" class="combo_box" onchange="filtroRegPago(this.value);pedidoPagos(this.value);">
                      <option value="">Seleccionar</option>
                      <option value="PROVEEDOR">Proveedor</option>
                      <option value="TRABAJADOR">Trabajador</option>
                      <option value="BAJAS">Bajas</option>
                    </select>
					<?php }
					else{?>
					<input type="text" name="cmb_tipo" id="cmb_tipo" class="caja_de_texto" size="10" readonly="readonly" value="<?php echo $filtro;?>"/>
					<?php }?>				
			
				<?php if($filtro==""){?>
	  	  	  <td width="70"><span id="etiqueta">&nbsp;</span></td>
				<td width="240" colspan="3"><span id="componenteHTML"></span>
				<?php }
				else{?>
			  		<input type="text" name="txt_nombre" id="txt_nombre" value="<?php echo $prov?>" size="40" maxlength="80" tabindex="1"<?php echo $atrib;?>/>
				<?php }?>			  
			  </td>
			</tr>
			<tr>
				<td colspan="2"><label class="titulo_etiqueta">Detalle del Pago</label></td>
				 <td><div align="right">Concepto</div></td>
				<td colspan="3">
					<input name="txt_concepto" type="text" class="caja_de_texto" id="txt_concepto"  value="" size="40" maxlength="80"/>				
				</td>
			</tr>
			<tr>
				<td><div align="right">Partida</div></td>
				<td>
					<input type="text" name="txt_partida" id="txt_partida" size="5" class="caja_de_num" readonly="readonly" value="<?php echo $partida?>"/>				
				</td>
				<td><div align="right">Factura/Cheque</div></td>
				<td>
					<input type="text" name="txt_factura" id="txt_factura" class="caja_de_texto" onkeypress="return permite(event,'num_car', 0);"
					size="50" maxlength="200" tabindex="2"/>				
				</td>
				<td><div align="right">*Estado Factura</div></td>
				<td><select name="cmb_estado" id="cmb_estado" class="combo_box" tabindex="3">
					  <option value="PENDIENTE" selected="selected">PENDIENTE</option>
					  <option value="ENTREGADA">ENTREGADA</option>
					</select>				
				</td>
			</tr>
			<tr>		
				<td><div align="right">Pedido</div></td>
				<td>
				<?php 
					if(isset($_POST['cmb_tipo']) && $_POST['cmb_tipo']=="PROVEEDOR"){
						$atrPedido = "";
						$valPedido = "PED";
					}
					else{
						$atrPedido = "readonly='readonly'";
						$valPedido = "";
					}
				?>
					<input type="text" name="txt_pedido" id="txt_pedido" value="<?php echo $valPedido;?>" maxlength="10" size="10" class="caja_de_texto" tabindex="4" 
					onchange="verificarPedidoExistente(this.value);" <?php echo $atrPedido;?>/>
					  <img src="../../images/lupa.png" id="img_verPedido" width="15" height="15" title="Consultar Pedido Seleccionado" 
					style="cursor:pointer;visibility:hidden;" onclick="consultarPedido(txt_pedido.value);"/>
				</td>		
				<td><div align="right">*Responsable</div></td>
				<td>
					<input type="text" name="txt_responsable" id="txt_responsable" value="" size="40" maxlength="75" tabindex="5"/>				
				</td>
				<td><div align="right">*Forma Pago</div></td>
				<td>
					<select name="cmb_viaPago" id="cmb_viaPago" class="combo_box" tabindex="6">
					  <option value="VIA ELECTRONICA" selected="selected">VIA ELECTRONICA</option>
					  <option value="CHEQUE">CHEQUE</option>
					  <option value="EFECTIVO">EFECTIVO</option>
					</select>				
				</td>
			</tr>
			<tr>
				<td><div align="right">*Total</div></td>
				<td>
				$<input name="txt_total" type="text" class="caja_de_texto" id="txt_total" 
					onchange="formatCurrency(value,'txt_total');calculoIvaPago(txt_total.value,txt_tasaIva.value);"
					onkeypress="return permite(event,'num', 2)" size="15" maxlength="20" tabindex="7" value="0.00"/>				
				</td>
				<td><div align="right">IVA</div></td>
				<td>
					$<input name="txt_iva" type="text" class="caja_de_texto" id="txt_iva" 
					onchange="formatCurrency(value,'txt_iva');" readonly="readonly"
					onkeypress="return permite(event,'num', 2)" size="15" maxlength="20" value="0.00"/>
					&nbsp;
					Tasa IVA<input type="text" name="txt_tasaIva" id="txt_tasaIva" class="caja_de_num" 
					onchange="formatCurrency(value,'txt_tasaIva');calculoIvaPago(txt_total.value,txt_tasaIva.value);" 
					value="16" onkeypress="return permite(event,'num', 2)" size="2" maxlength="2" tabindex="8"/>%				</td>
				<td><div align="right">Subtotal</div></td>
				<td>
					$<input name="txt_subtotal"type="text" class="caja_de_texto" id="txt_subtotal" readonly="readonly" 
					onkeypress="return permite(event,'num', 2)" size="15" maxlength="20" value="0.00"/>				</td>
			</tr>
			<tr>
				<td><div align="right">Aplicacion</div></td>
				<td colspan="3">
					<input type="text" name="txt_aplicacion" id="txt_aplicacion" value="" maxlength="60" size="60" class="caja_de_texto" tabindex="9"/>				</td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input type="hidden" name="hdn_validar" id="hdn_validar" value="si"/>
					<?php 
					if(isset($_POST["sbt_agregar"])){
					?>
						<input type="submit" name="sbt_finalizar" id="sbt_finalizar" onmouseover="window.status='';return true;" value="Finalizar" 
						title="Finalizar y Guardar el Registro" class="botones" tabindex="11" onclick="hdn_validar.value='no'"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
					}
					?>
					<input type="submit" name="sbt_agregar" id="sbt_agregar" onmouseover="window.status='';return true;" value="Agregar" title="Agregar el Registro"
					 class="botones" tabindex="10"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Restablecer el Formulario" class="botones" 
					onclick="img_verPedido.style.visibility='hidden'" tabindex="12"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" value="Cancelar" title="Cancelar el Proceso de Guardado" class="botones" 
					onclick="location.href='menu_egresos.php'" tabindex="13"/>				</td>
			</tr>
		</table>
		</form>
</fieldset>
		
	<?php if(!isset($_SESSION["detallesPago"])){?>
			<div id="calendario">
				<input name="fechaPago" type="image" id="fechaPago" onclick="displayCalendar(document.frm_registrarPago.txt_fecha,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccionar Fecha de Pago"/>
</div>
		<?php }?>
		
		<?php
		if(isset($_SESSION["detallesPago"])){
		?>
			<div id="resultados" class="borde_seccion2">
				<?php
				mostrarDetallesPago($_SESSION["detallesPago"]);
				?>
			</div>
		<?php
		}
	}//Cierre del ELSE para guardar los datos
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
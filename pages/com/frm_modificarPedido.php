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
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//La llamada a este archivo es para almacenar el pedido en la BD y obtener datos del detalle de Pedido
		include ("op_modificarPedido.php");	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/actualizarIVA.js"></script><?php 
	
	if(!isset($_POST["btn_registrarDetalle"])){?>
		<script type="text/javascript" language="javascript">
			//Esta linea colocara el cursor en el campo de desripción cada vez que se cargue la pagina
			setTimeout("document.getElementById('txt_plazo').focus()",500);
		</script><?php 
	}?>

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:25px; top:146px; width:227px; height:20px; z-index:10; }
		#tabla-registro { position:absolute; left:30px;	top:190px; width:850px; height:486px; z-index:11; }
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		#editar-iva {position:absolute;left:630px;top:249px;width:35px;height:30px;z-index:12;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Modificar Pedido </div><?php 
	
	
	if(!isset($_POST["btn_modificarPedido"])){
		
		//Si esta definido el boton 'btn_continuar' el pedido es Generado a partir de una Requisición
		if (isset($_POST["btn_continuarModificacion"])){
			$subtotal = $_POST["txt_subtotal"];
			$id_pedido = $_POST["hdn_pedido"];
			if(strlen($subtotal)>6)
				$subtotal=str_replace(",","",$subtotal);
		}
		
		//Si el IVA viene en un POST, viene desde una REQ donde solo se coloca el precio, el equipo de destino por partida y el proveedor
		$iva_inc = "";
		if(isset($_POST["hdn_iva"]))
			$iva_inc = $_POST["hdn_iva"];
		//Si el IVA viene en el GET, viene desde la pagina donde se registrar Manualmente cada partidad del pedido
		if(isset($_GET["hdn_iva"]))
			$iva_inc = $_GET["hdn_iva"];
		
		//Verificar si los precios vienen con o sin IVA, para realizar el Calculo del SUBTOTAL, IVA y TOTAL
		if ($iva_inc=="NO"){
			$iva = ($subtotal * $_SESSION['porcentajeIVA'])/100;
			$total = $subtotal+$iva;	
		}
		else{
			//Si el IVA esta incluido, el subtotal se convierte en el TOTAL
			$total = $subtotal;
			$subtotal = $total / (1 + ($_SESSION['porcentajeIVA']/100) ) ;
			$iva = $total - $subtotal;
		}?>
		
		

		<fieldset class="borde_seccion" id="tabla-registro" name="tabla-registro">
		<legend class="titulo_etiqueta">Modificar Pedido</legend>
		<form onSubmit="return valFormRegistrarPedido(this);" name="frm_modificarPedido" id="frm_modificarPedido" method="post" action=""><?php
		if (isset($_POST["btn_continuarModificacion"]))
			//Colocar las cantidades de cada partida y el equipo asociado cuando el pedido se genera a partir de una requisición
			obtenerPrecioUnitReq();
			$id_pedido = $_POST["hdn_pedido"];
			?>
			<input type="hidden" name="hdn_descto" value="<?php echo $_POST["txt_descto"]?>"/>
			<table cellpadding="5" cellspacing="5">
				<tr>
					<td><div align="right">Pedido</div></td>
					<td>
						<input name="txt_noPedido" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly" value="<?php echo $id_pedido;?>"/>
				  	</td>
					<td width="120" align="center"><div align="right">Subtotal </div></td>
					<td>$
						<input name="txt_subtotal" id="txt_subtotal" type="text" class="caja_de_texto" readonly="readonly"
						onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" 
						value="<?php echo number_format($subtotal,2,".",",");?>"/>
					</td>
				</tr>
				<tr>
					<!--<td><div align="right">Requisici&oacute;n</div></td>
					<td>
						<input name="txt_noReq" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly" 
						onkeypress="return permite(event,'num_car', 3);" 
						value="<?php echo $requisicion;?>"/>
					</td>-->
					<td></td>
					<td></td>
					<td><div align="right">IVA </div></td>
					<td>$
						<input name="txt_iva" id="txt_iva" type="text" class="caja_de_texto" readonly="readonly" onkeypress="return permite(event,'num',2);" 
						size="15" maxlength="20" 
						value="<?php echo number_format($iva,2,".",",");?>"/>
						<input type="text" name="txt_lblIVA" id="txt_lblIVA" class="caja_de_num" onclick="alert ('IVA calculado en base al '+this.value);" 
						value="<?php echo $_SESSION['porcentajeIVA'];?>%" size="4" maxlength="10" readonly="true" />
					</td>
				</tr>
				<?php $fecha = modFecha(obtenerDatoPedido($id_pedido,"fecha","pedido","id_pedido"),1); ?>
				<tr>
					<td><div align="right">Fecha</div></td>
					<td><input name="txt_fecha" type="text" readonly="readonly" class="caja_de_texto" value="<?php echo $fecha; ?>" size="18" maxlength="25"/></td>
					<td><div align="right">Total </div></td>
					<td>$
						<input name="txt_total" id="txt_total" type="text" class="caja_de_texto" onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" 
						readonly="readonly" value="<?php echo number_format($total,2,".",",");?>"/>
					</td>
				</tr>
				<?php 
				$plazo_entrega = obtenerDatoPedido($id_pedido,"plazo_entrega","pedido","id_pedido");
				$plazo_entrega = explode(" ",$plazo_entrega);
				$plazo = $plazo_entrega[0];
				$tiempo = $plazo_entrega[1];
				?>
				<tr>
					<td><div align="right">*Plazo Entrega </div></td>
					<td>
						<input name="txt_plazo" id="txt_plazo" type="text" class="caja_de_texto" size="4" maxlength="10" value="<?php echo $plazo; ?>" onkeypress="return permite(event,'num', 3);" tabindex="1" autocomplete="off"/>
						<select name="cmb_plazo" class="combo_box" tabindex="2" >
							<option value="">Seleccionar</option>
						  	<option <?php if($tiempo == "HORAS") echo "selected=selected"; ?> value="HORAS">HORAS</option>
						  	<option <?php if($tiempo == "DIAS") echo "selected=selected"; ?> value="DIAS">DIAS</option>
							<option <?php if($tiempo == "SEMANAS") echo "selected=selected"; ?> value="SEMANAS">SEMANAS</option>
						  	<option <?php if($tiempo == "MESES") echo "selected=selected"; ?> value="MESES">MESES</option>
						</select>
					</td>
					<?php $solicitor = obtenerDatoPedido($id_pedido,"solicitor","pedido","id_pedido"); ?>
					<td><div align="right">*Solicit&oacute;</div></td>
					<td><?php 
						$cmb_proveedor="";
						$conn = conecta("bd_recursos");
						$result=mysql_query("SELECT departamento,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS administrador 
											FROM empleados JOIN organigrama ON rfc_empleado=empleados_rfc_empleado ORDER BY departamento");?>
						<select name="cmb_solicito" size="1" class="combo_box" tabindex="3">
							<option value="">Solicit&oacute;</option><?php 
							while ($row=mysql_fetch_array($result)){
								if ($row['administrador'] == $cmb_proveedor){
									if($solicitor == $row['administrador'])
										echo "<option value='$row[departamento]-$row[administrador]' selected='selected'>$row[departamento] - $row[administrador]</option>";
									else
										echo "<option value='$row[departamento]-$row[administrador]' selected='selected'>$row[departamento] - $row[administrador]</option>";
								}
								else{
									if($solicitor == $row['administrador'])
										echo "<option value='$row[departamento]-$row[administrador]' selected='selected'>$row[departamento] - $row[administrador]</option>";
									else
										echo "<option value='$row[departamento]-$row[administrador]'>$row[departamento] - $row[administrador]</option>";
								}
							} 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
						</select>
					</td>
				</tr>
				<tr>
					<?php $cond_entre = obtenerDatoPedido($id_pedido,"cond_entrega","pedido","id_pedido"); ?>
					<td><div align="right">*Condiciones Entrega </div></td>
					<td>
						<textarea name="txa_condEnt" class="caja_de_texto" id="txa_condEnt" rows="2" cols="30" maxlength="60" 
						onkeypress="return permite(event,'num_car', 0);"
						onkeyup="return ismaxlength(this);" tabindex="4" ondblclick="this.value=''"><?php echo $cond_entre; ?></textarea>
					</td>
					<?php $reviso = obtenerDatoPedido($id_pedido,"revisor","pedido","id_pedido"); ?>
					<td><div align="right">*Revis&oacute;</div></td>
					<td><?php
						$rfc=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento","ASEGURAMIENTO DE CALIDAD");
						//Obtener nombre Recursos Humanos
						$nombre=obtenerNombreEmpleado($rfc);?>
						
						<input name="txt_reviso" type="text" class="caja_de_texto" id="txt_reviso" size="40" maxlength="60" 
						onkeypress="return permite(event,'num_car', 2);" ondblclick="txt_reviso.value='';" value="<?php echo $reviso; ?>" tabindex="5"/>
					</td>
			  	</tr>
				<tr>
					<?php $condPago = obtenerDatoPedido($id_pedido,"cond_pago","pedido","id_pedido"); ?>
					<td><div align="right">*Condiciones Pago </div></td>
					<td>
						<select name="cmb_condPago" class="combo_box" tabindex="6" onchange="agregarDescripcion(this);" >
							<option value="">Seleccionar</option>
						  	<option <?php if($condPago == "CREDITO") echo "selected=selected"; ?> value="CREDITO">CREDITO</option>
						  	<option <?php if($condPago == "CONTADO") echo "selected=selected"; ?> value="CONTADO">CONTADO</option>					
							<option value="NUEVA">Agregar Condici&oacute;n Pago</option>
						</select>
					</td>
					<?php $autorizo = obtenerDatoPedido($id_pedido,"autorizador","pedido","id_pedido"); ?>
					<td><div align="right">*Autoriz&oacute;</div></td><?php
						$rfc=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento","DIRECCION GENERAL");
						//Obtener nombre Recursos Humanos
						$nombre=obtenerNombreEmpleado($rfc);?>
					<td>
						<input name="txt_autorizo" type="text" class="caja_de_texto" size="40" maxlength="60" onkeypress="return permite(event,'num_car', 2);" 
						ondblclick="txt_autorizo.value='';" value="<?php echo $autorizo; ?>" tabindex="7" />
					</td>
				</tr>
				<tr>
					<?php $via_pedido = obtenerDatoPedido($id_pedido,"via_pedido","pedido","id_pedido"); ?>
					<td><div align="right">*V&iacute;a del Pedido </div></td>
					<td>
						<select name="cmb_viaPed" class="combo_box" tabindex="8">
							<option value="">Seleccionar</option>
							<option <?php if($via_pedido == "ELECTRONICA") echo "selected=selected"; ?> value="ELECTRONICA">ELECTR&Oacute;NICA</option>
							<option <?php if($via_pedido == "TELEFONICA") echo "selected=selected"; ?> value="TELEFONICA">TELEFONICA</option>
							<option <?php if($via_pedido == "PRESENCIAL") echo "selected=selected"; ?> value="PRESENCIAL">PRESENCIAL</option>
						</select>
					</td>
					<?php $comentarios = obtenerDatoPedido($id_pedido,"comentarios","pedido","id_pedido"); ?>
					<td><div align="right">Comentarios</div></td>
					<td>
						<textarea name="txa_comentarios" class="caja_de_texto" id="txa_comentarios" cols="30" rows="2" onkeypress="return permite(event,'num_car', 0);"
						onkeyup="return ismaxlength(this);" tabindex="9" maxlength="120"><?php echo $comentarios; ?></textarea>
					</td>
				</tr>
				<tr>
					<?php $rfc = obtenerDatoPedido($id_pedido,"proveedores_rfc","pedido","id_pedido"); ?>
					<td><div align="right">RFC</div></td>
					<td>
						<input name="txt_rfc" id="txt_rfc" type="text" class="caja_de_texto" readonly="readonly" size="20" maxlength="20" 
						value="<?php echo $rfc; ?>" title="Seleccionar un Proveedor Para Mostrar su RFC" />
					</td>
					<?php $tipo_moneda = obtenerDatoPedido($id_pedido,"tipo_moneda","pedido","id_pedido"); ?>
					<td align="right">Tipo Moneda</td>
					<td>
						<select name="cmb_tipoMoneda" id="cmb_tipoMoneda" class="combo_box" tabindex="10">
							<option value="">Tipo Moneda</option>
							<option <?php if($tipo_moneda == "PESOS") echo "selected=selected"; ?> value="PESOS">PESOS</option>
							<option <?php if($tipo_moneda == "DOLARES") echo "selected=selected"; ?> value="DOLARES">DOLARES</option>
							<option <?php if($tipo_moneda == "EUROS") echo "selected=selected"; ?> value="EUROS">EUROS</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><div align="right">*Proveedor</div></td>
					<td colspan="3"><?php 
						//Recuperar el Proveedor seleccionado en las Paginas Anteriores, Registrar partida por partida o de la pagina donde solo se introducen los precios
						$cmb_proveedor="";
						if(isset($_POST['txt_nomProveedor'])){
							$cmb_proveedor = $_POST['txt_nomProveedor'];
							//Recuperar el RFC del Proveedor para ser colocado en la Caja de Texto que muestra el RFC del Proveedor
							$rfcProveedor = obtenerDato("bd_compras", "proveedores", "rfc", "razon_social", $cmb_proveedor);?>
							<script type="text/javascript" language="javascript">
								setTimeout("document.getElementById('txt_rfc').value = '<?php echo $rfcProveedor; ?>'",500);
							</script><?php							
						}
						
						$conn = conecta("bd_compras");
						$result=mysql_query("SELECT DISTINCT rfc,razon_social FROM proveedores ORDER BY razon_social");?>
						<select name="cmb_proveedor" size="1" onchange="document.getElementById('txt_rfc').value = this.value;" class="combo_box" tabindex="11">
							<option value="">Proveedor</option><?php 
								while ($row=mysql_fetch_array($result)){
									if ($row['razon_social'] == $cmb_proveedor){
										echo "<option value='$row[rfc]' selected='selected'>$row[razon_social]</option>";
									}
									else{
										echo "<option value='$row[rfc]'>$row[razon_social]</option>";
									}
								} 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
						</select>
					</td>
				</tr>				
				<tr>
					<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
				<tr>
					<td colspan="4"><div align="center">
						<input type="hidden" name="hdn_ivaIncluido" id="hdn_ivaIncluido" value="<?php echo $iva_inc;?>" />
						<input type="hidden" id="txt_idPedido" name="txt_idPedido" value="<?php echo $id_pedido; ?>" />
						<input type="hidden" id="btn_modificar" name="btn_modificar"/>
						<input name="btn_modificarPedido" type="submit" class="botones" value="Registrar" title="Registrar" onmouseover="window.status='';return true;" 
						tabindex="12"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Borrar el Formulario" tabindex="13" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" id="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar" 
						onclick="document.getElementById('frm_modificarPedido').action='frm_consultadePedido.php';submit();" tabindex="14" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
					</td>
			   	</tr>
			</table>
		</form>
		</fieldset>
	
		<div id="editar-iva">
			<input type="image" src="../../images/editar.png" width="30" height="25" border="0" onclick="actualizarIVA('txt_subtotal','txt_iva','txt_total');"
			title="Modificar la Tasa de IVA" />	
		</div><?php
	}//Cierre if(!isset($_POST["btn_registrarDetalle"]))
	else{
		modificarPedido();
		?>
		<div class="titulo_etiqueta" id="procesando">
    		<div align="center">
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
	      	</div>
		</div><?php 
	}?>       
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
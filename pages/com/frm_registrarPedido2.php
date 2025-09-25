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
		include ("op_registrarPedido.php");	
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
		#editar-iva {position:absolute;left:600px;top:249px;width:35px;height:30px;z-index:12;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Registrar Pedido </div><?php 
	
	
	if(!isset($_POST["btn_registrarDetalle"])){
		
		//Si esta definido el boton 'btn_continuar' el pedido es Generado a partir de una Requisición
		if (isset($_POST["btn_continuar"])){
			$requisicion = $_POST["hdn_requisicion"];
			$subtotal = $_POST["txt_subtotal"];
			$id_pedido = obtenerIdPedido();
			$base = $_POST["hdn_depto"];
			if(strlen($subtotal)>6)
				$subtotal=str_replace(",","",$subtotal);
		}
		else{//Obtener los datos del Pedido, cuando sus partidas son registradas manualmente
			$id_pedido = $_POST["txt_pedido"];
			$requisicion = $_SESSION['aux_req'];
			$subtotal = obtenerSubtotal();
			$base = "";
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
		<legend class="titulo_etiqueta">Registrar Pedido</legend>
		<form onSubmit="return valFormRegistrarPedido(this);" name="frm_registrarPedido2" method="post" action=""><?php
		
		if (isset($_POST["btn_continuar"]))
			//Colocar las cantidades de cada partida y el equipo asociado cuando el pedido se genera a partir de una requisición
			obtenerPrecioUnitReq();?>
			
			<input type="hidden" name="hdn_base" value="<?php echo $base;?>"/>
			<input type="hidden" name="hdn_descto" value="<?php echo $_POST["txt_descto"]?>"/>
			<table cellpadding="5" cellspacing="5">
				<tr>
					<td><div align="right">Clave</div></td>
					<td>
						<input name="txt_noPedido" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly"
						onkeypress="return permite(event,'num_car', 3);" 
						value="<?php echo $id_pedido;?>"/>
				  	</td>
					<td width="120" align="center"><div align="right">Subtotal </div></td>
					<td>$
						<input name="txt_subtotal" id="txt_subtotal" type="text" class="caja_de_texto" readonly="readonly"
						onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" 
						value="<?php echo number_format($subtotal,2,".",",");?>"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">Requisici&oacute;n</div></td>
					<td>
						<input name="txt_noReq" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly" 
						onkeypress="return permite(event,'num_car', 3);" 
						value="<?php echo $requisicion;?>"/>
					</td>
					<td><div align="right">IVA </div></td>
					<td>$
						<input name="txt_iva" id="txt_iva" type="text" class="caja_de_texto" readonly="readonly" onkeypress="return permite(event,'num',2);" 
						size="15" maxlength="20" 
						value="<?php echo number_format($iva,2,".",",");?>"/>
						<input type="text" name="txt_lblIVA" id="txt_lblIVA" class="caja_de_num" onclick="alert ('IVA calculado en base al '+this.value);" 
						value="<?php echo $_SESSION['porcentajeIVA'];?>%" size="4" maxlength="10" readonly="true" />
					</td>
				</tr>
				<tr>
					<td><div align="right">Fecha</div></td>
					<td><input name="txt_fecha" type="text" readonly="readonly" class="caja_de_texto" value="<?php echo verFecha(4);?>" size="18" maxlength="25"/></td>
					<td><div align="right">Total </div></td>
					<td>$
						<input name="txt_total" id="txt_total" type="text" class="caja_de_texto" onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" 
						readonly="readonly" value="<?php echo number_format($total,2,".",",");?>"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">*Plazo Entrega </div></td>
					<td>
						<input name="txt_plazo" id="txt_plazo" type="text" class="caja_de_texto" size="4" maxlength="10" onkeypress="return permite(event,'num', 3);" tabindex="1" />
						<select name="cmb_plazo" class="combo_box" tabindex="2" >
							<option>Seleccionar</option>
						  	<option value="HORAS">HORAS</option>
						  	<option value="DIAS">DIAS</option>
							<option value="SEMANAS">SEMANAS</option>
						  	<option value="MESES">MESES</option>
						</select>
					</td>
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
									echo "<option value='$row[departamento]-$row[administrador]' selected='selected'>$row[departamento] - $row[administrador]</option>";
								}
								else{
									echo "<option value='$row[departamento]-$row[administrador]'>$row[departamento] - $row[administrador]</option>";
								}
							} 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
						</select>
					</td>
				</tr>
				<tr>
					<td><div align="right">*Condiciones Entrega </div></td>
					<td>
						<textarea name="txa_condEnt" class="caja_de_texto" id="txa_condEnt" rows="2" cols="30" maxlength="60" 
						onkeypress="return permite(event,'num_car', 0);"
						onkeyup="return ismaxlength(this);" tabindex="4" ondblclick="this.value=''">En Almac&eacute;n CLF</textarea>
					</td>
					<td><div align="right">*Revis&oacute;</div></td>
					<td><?php
						$rfc=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento","ASEGURAMIENTO DE CALIDAD");
						//Obtener nombre Recursos Humanos
						$nombre=obtenerNombreEmpleado($rfc);?>
						
						<input name="txt_reviso" type="text" class="caja_de_texto" id="txt_reviso" size="40" maxlength="60" 
						onkeypress="return permite(event,'num_car', 2);" ondblclick="txt_reviso.value='';" value="<?php echo $nombre; ?>" tabindex="5"/>
					</td>
			  	</tr>
				<tr>
					<td><div align="right">*Condiciones Pago </div></td>
					<td>
						<select name="cmb_condPago" class="combo_box" tabindex="6" onchange="agregarDescripcion(this);" >
							<option value="">Seleccionar</option>							
						  	<option value="CREDITO">CREDITO</option>
						  	<option value="CONTADO">CONTADO</option>					
							<option value="NUEVA">Agregar Condici&oacute;n Pago</option>
						</select>
					</td>
					<td><div align="right">*Autoriz&oacute;</div></td><?php
						$rfc=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento","DIRECCION GENERAL");
						//Obtener nombre Recursos Humanos
						$nombre=obtenerNombreEmpleado($rfc);?>
					<td>
						<input name="txt_autorizo" type="text" class="caja_de_texto" size="40" maxlength="60" onkeypress="return permite(event,'num_car', 2);" 
						ondblclick="txt_autorizo.value='';" value="<?php echo $nombre; ?>" tabindex="7" />
					</td>
				</tr>
				<tr>
					<td><div align="right">*V&iacute;a del Pedido </div></td>
					<td>
						<select name="cmb_viaPed" class="combo_box" tabindex="8">
							<option>Seleccionar</option>
							<option value="ELECTRONICA">ELECTR&Oacute;NICA</option>
							<option value="TELEFONICA">TELEFONICA</option>
							<option value="PRESENCIAL">PRESENCIAL</option>
						</select>
					</td>
					<td><div align="right">Comentarios</div></td>
					<td>
						<textarea name="txa_comentarios" class="caja_de_texto" id="txa_comentarios" cols="30" rows="2" onkeypress="return permite(event,'num_car', 0);"
						onkeyup="return ismaxlength(this);" tabindex="9"></textarea>
					</td>
				</tr>
				<tr>
					<td><div align="right">RFC</div></td>
					<td>
						<input name="txt_rfc" id="txt_rfc" type="text" class="caja_de_texto" readonly="readonly" size="20" maxlength="20" 
						value="" title="Seleccionar un Proveedor Para Mostrar su RFC" />
					</td>
					<td align="right">Tipo Moneda</td>
					<td>
						<select name="cmb_tipoMoneda" id="cmb_tipoMoneda" class="combo_box" tabindex="10">
							<option value="">Tipo Moneda</option>
							<option value="PESOS">PESOS</option>
							<option value="DOLARES">DOLARES</option>
							<option value="EUROS">EUROS</option>
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
												
						<input name="btn_registrarDetalle" type="submit" class="botones" value="Registrar" title="Registrar" onmouseover="window.status='';return true;" 
						tabindex="12"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Borrar el Formulario" tabindex="13" />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if (!isset($_POST["btn_continuar"])){?>
							<input name="btn_cancelar" type="button" class="botones" id="boton-cancelar" value="Cancelar" title="Cancelar" 
							onclick="location.href='frm_detallesDelPedido.php'" tabindex="14" />
						<?php }
						else {?>
							<input name="btn_cancelar" type="button" class="botones" id="boton-cancelar" value="Cancelar" title="Cancelar" 
							onclick="location.href='menu_requisiciones.php'" tabindex="15" />
						<?php }?>
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
		registraPedido();?>
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
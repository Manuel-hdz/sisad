<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Archivo que muestra los detalles de la Requisicion para Generar el Pedido
		include ("op_generarPedido.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" language="javascript">
		function confirmarIva(){
			if(confirm("Los Precios Introducidos.\n¿Incluyen IVA?"))
				document.getElementById("hdn_iva").value="si";
		}
	</script>
		
    <style type="text/css">
		<!--
		#titulo-generar { position:absolute; left:30px; top:146px; width:187px; height:19px; z-index:11; }
		#tabla-material { position:absolute; left:30px; top:190px; width:910px;	height:410px; z-index:12; overflow:scroll;}
		#botones{position:absolute; left:30px; top:660px; width:910px; height:10px; z-index:13;}
		#tabla-registro { position:absolute; left:30px;	top:190px; width:800px; height:440px; z-index:14; }
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:15; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Generar Pedido</div>

	<?php //Si la variable de idReq esta definida en el arreglo $_GET, proceder a mostrar los datos de la BD.
	if(isset($_GET['idReq'])){
		if (isset($_SESSION["detallePedido"]))
			unset($_SESSION["detallePedido"]);
	?>
		
		<form name="frm_generarRequisicion" onsubmit="return valFormComplementoPedido(this);" method="post" action="frm_generarPedido.php" >
			<div id="tabla-material" class="borde_seccion2">
				<?php 
				mostrarRequisicion($_GET["idReq"]);
				?>
			</div>
			<div id="botones" align="center">
				<input type="hidden" name="hdn_idReq" value="<?php echo $_GET["idReq"];?>"/>
				<input type="hidden" name="hdn_iva" id="hdn_iva" value="no"/>
				<input type="submit" title="Complementar Datos" class="botones_largos" value="Complementar Pedido" name="sbt_complementar" onmouseover="window.status='';return true;" onclick="confirmarIva();"/>&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" title="Cancelar y volver a Seleccionar Otra Requisici&oacute;n" class="botones" value="Cancelar" name="btn_camcelar" onclick="location.href='frm_consultarRequisiciones.php?idReq=<?php echo $_GET["idReq"];?>';"/>
			</div>
		</form>
	<?php }
	if (isset($_POST["sbt_complementar"])){
		//Cantidad de materiales en la Requisicion
		$cant=$_POST["hdn_partidas"]-1;
		$band=0;
		$id_pedido=obtenerIdPedido();
		$detalles=array();
		do{
			$band++;
			//Obtener el nombre del material para agregarlo al arreglo
			$nombre = obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $_POST["hdn_id$band"]);
			//Obtener  la unidad de medida del material para agregarlo al arreglo
			$unidad = obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $_POST["hdn_id$band"]);
			//Obtener el valor del Importe
			$importe=str_replace(",","",$_POST["hdn_cantidad$band"])*str_replace(",","",$_POST["txt_precio$band"]);
			
			$detalles[] = array("partida"=>$band, "unidad"=>$unidad, "cantidad"=>$_POST["hdn_cantidad$band"],
								"descripcion"=>$nombre, "precio_unitario"=>$_POST["txt_precio$band"],"importe"=>$importe);
		}while($band<$cant);
		$_SESSION["detallePedido"]=$detalles;
		$requisicion=$_POST["hdn_idReq"];
		if ($_POST["hdn_iva"]=="si"){
			$total=$_POST["txt_subtotal"];
			$subtotal="0.00";
		}
		else{
			$subtotal=$_POST["txt_subtotal"];
			$total="0.00";
		}
		?>
			<fieldset class="borde_seccion" id="tabla-registro" name="tabla-registro">
				<legend class="titulo_etiqueta">Registrar Pedido</legend>
				<form onSubmit="return valFormRegistrarPedido(this);" name="frm_registrarPedido" method="post" action="">
					<table cellpadding="5" cellspacing="5">
						<tr>
							<td><div align="right">Clave</div></td>
							<td>
								<input name="txt_noPedido" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly"
								onkeypress="return permite(event,'num_car', 3);" 
								value="<?php echo $id_pedido;?>"/>
							</td>
							<td width="120" align="center"><div align="right">Subtotal</div></td>
						  <td>
						  		$<input name="txt_subtotal" id="txt_subtotal" type="text" class="caja_de_texto" readonly="readonly"
								 onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" 
								 value="<?php echo $subtotal;?>"/>
								IVA %<input type="text" name="txt_lblIVA" id="txt_lblIVA" class="caja_de_num" value="" size="4" maxlength="10" onchange="sumarIva(this.value,hdn_iva.value);" onkeypress="return permite(event,'num', 2);"/>
								<input type="hidden" name="hdn_iva" id="hdn_iva" value="<?php echo $_POST["hdn_iva"];?>"/>
							</td>
						</tr>
						<tr>
							<td><div align="right">Requisici&oacute;n</div></td>
							<td>
								<input name="txt_noReq" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly" 
								onkeypress="return permite(event,'num_car', 3);" 
								value="<?php echo $requisicion;?>"/></td>
							<td><div align="right">IVA</div></td>
							<td>$
								<input name="txt_iva" id="txt_iva" type="text" class="caja_de_texto" readonly="readonly" onkeypress="return permite(event,'num',2);" 
								size="15" maxlength="20" value="0.00" onclick="alert('IVA calculado en base al '+txt_lblIVA.value+'%');"/>
							</td>
						</tr>
						<tr>
							<td><div align="right">Fecha</div></td>
							<td><input name="txt_fecha" type="text" readonly="readonly" class="caja_de_texto" value="<?php echo verFecha(4);?>" size="18" maxlength="25"/></td>
							<td><div align="right">Total </div></td>
							<td>$
								<input name="txt_total" id="txt_total" type="text" class="caja_de_texto" onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" 
								readonly="readonly" value="<?php echo $total;?>"/></td>
						 </tr>
						 <tr>
							<td><div align="right">*Plazo Entrega </div></td>
							<td><input name="txt_plazo" type="text" class="caja_de_num" size="4" maxlength="10" onkeypress="return permite(event,'num', 3);" />
								<select name="cmb_plazo" class="combo_box" >
								  <option>Seleccionar</option>
								  <option value="HORAS">HORAS</option>
								  <option value="DIAS">DIAS</option>
								  <option value="MESES">MESES</option>
								</select>							</td>
							<td><div align="right">*Solicit&oacute;</div></td>
							<td>
								<?php 
								$rfc=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento","GERENCIA TECNICA");
								//Obtener nombre Recursos Humanos
								$nombre=obtenerNombreEmpleado($rfc);
							?>
								<input name="txt_solicito" readonly="readonly" type="text" class="caja_de_texto" size="40" maxlength="60" onkeypress="return permite(event,'num_car', 2);" value="<?php echo $nombre;?>"/>
							</td>
						</tr>
						<tr>
							<td valign="top"><div align="right">*Condiciones Entrega</div></td>
							 <td>
								<textarea name="txa_condEnt" class="caja_de_texto" id="txa_condEnt" rows="2" cols="30" maxlength="60" 
								onkeypress="return permite(event,'num_car', 0);"
								onkeyup="return ismaxlength(this);"></textarea>							 </td>
							<td><div align="right">*Revis&oacute;</div></td>
							<td>
								<?php
								$rfc=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento","ASEGURAMIENTO DE CALIDAD");
								//Obtener nombre Recursos Humanos
								$nombre=obtenerNombreEmpleado($rfc);
								?>
								<input name="txt_reviso" type="text" class="caja_de_texto" id="txt_reviso" size="40" maxlength="60" 
								onkeypress="return permite(event,'num_car', 2);" ondblclick="txt_reviso.value='';" value="<?php echo $nombre; ?>"/>
							</td>
						</tr>
						<tr>
							<td valign="top"><div align="right">*Condiciones Pago </div></td>
							<td>
								<select name="cmb_condPago" class="combo_box" tabindex="6" onchange="agregarDescripcion(this);" >
									<option value="">Seleccionar</option>							
									<option value="CREDITO">CREDITO</option>
									<option value="CONTADO">CONTADO</option>					
									<option value="NUEVA">Agregar Condici&oacute;n Pago</option>
								</select>
							</td>
							<td valign="top"><div align="right">*Autoriz&oacute;</div></td>
								<?php
								$rfc=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento","DIRECCION GENERAL");
								//Obtener nombre Recursos Humanos
								$nombre=obtenerNombreEmpleado($rfc);
								?>
							<td valign="top"><input name="txt_autorizo" type="text" class="caja_de_texto" size="40" maxlength="60" onkeypress="return permite(event,'num_car', 2);" ondblclick="txt_autorizo.value='';" value="<?php echo $nombre; ?>"/></td>
						</tr>
						<tr>
							 <td><div align="right">*V&iacute;a del Pedido </div></td>
							 <td>
								 <select name="cmb_viaPed" class="combo_box">
									<option>Seleccionar</option>
									<option value="ELECTRONICA">ELECTR&Oacute;NICA</option>
									<option value="TELEFONICA">TELEFONICA</option>
									<option value="PRESENCIAL">PRESENCIAL</option>
								</select>							</td>
							<td><div align="right">Comentarios</div></td>
							<td>
								<textarea name="txa_comentarios" class="caja_de_texto" id="txa_comentarios" cols="30" rows="2" onkeypress="return permite(event,'num_car', 0);"
								onkeyup="return ismaxlength(this);"></textarea>							</td>
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
								</select>
							</td>
						</tr>
						<tr>
							<td><div align="right">*Proveedor</div></td>
							<td colspan="3">
							<?php 
								$cmb_proveedor="";
								$conn = conecta("bd_compras");
								$result=mysql_query("SELECT DISTINCT rfc,razon_social FROM proveedores ORDER BY razon_social");?>
								<select name="cmb_proveedor" size="1" onchange="document.getElementById('txt_rfc').value = this.value;" class="combo_box">
									<option value="">Proveedor</option>
										<?php while ($row=mysql_fetch_array($result)){
											if ($row['rfc'] == $cmb_proveedor){
												echo "<option value='$row[rfc]' selected='selected'>$row[razon_social]</option>";
											}
											else{
												echo "<option value='$row[rfc]'>$row[razon_social]</option>";
											}
										} 
								//Cerrar la conexion con la BD		
								mysql_close($conn);
							?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
						</tr>
						<tr>
							<td colspan="4">
								<div align="center">
								<input name="sbt_registrar" type="submit" class="botones" value="Registrar" title="Registrar" onmouseover="window.status='';return true;"/>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Borrar el Formulario" />
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="btn_cancelar" type="button" class="botones" id="boton-cancelar" value="Cancelar" title="Cancelar" onclick="location.href='frm_generarPedido.php?idReq=<?php echo $_POST["hdn_idReq"];?>'"/>
								</div>
							</td>
					   </tr>
					</table>
				</form>
			</fieldset><?php
	}
	
	if (isset($_POST["sbt_registrar"])){
		registrarPedido();?>
		<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div><?php
	}?>
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
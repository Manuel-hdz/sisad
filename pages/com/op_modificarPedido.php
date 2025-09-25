<?php
	function obtenerPrecioUnitReq(){		
		//Variable de control de ciclos
		$ctrl = 1;
		//Variable con la cantidad de CheckBox's
		$tam=$_POST["cant_ckbs"];
		if(isset($_SESSION["detalle_pedido"]))
			unset($_SESSION["detalle_pedido"]);
		do{
			if(isset($_POST["ckb_pieza$ctrl"])){
				$cant_ped=$_POST["hdn_cantReq$ctrl"];
				$descripcion=$_POST["descMat$ctrl"];
				$precio=$_POST["txt_precio$ctrl"];
				$equipo=$_POST["cmb_equipos$ctrl"];
				$control_costos=$_POST["cmb_con_cos$ctrl"];
				$cuenta=$_POST["cmb_cuenta$ctrl"];
				$subcuenta=$_POST["cmb_subcuenta$ctrl"];
				$partida=$_POST["txt_partidaReq$ctrl"];
				echo "<input type='hidden' name='hdn_cantPedida$ctrl' id='hdn_cantPedida$ctrl' value='$cant_ped'/>";
				echo "<input type='hidden' name='descMat$ctrl' id='descMat$ctrl' value='$descripcion'/>";
				echo "<input type='hidden' name='hdn_cantidad$ctrl' id='hdn_cantidad$ctrl' value='$precio'/>";
				echo "<input type='hidden' name='hdn_equipo$ctrl' id='hdn_equipo$ctrl' value='$equipo'/>";
				echo "<input type='hidden' name='hdn_control$ctrl' id='hdn_control$ctrl' value='$control_costos'/>";
				echo "<input type='hidden' name='hdn_cuenta$ctrl' id='hdn_cuenta$ctrl' value='$cuenta'/>";
				echo "<input type='hidden' name='hdn_subcuenta$ctrl' id='hdn_subcuenta$ctrl' value='$subcuenta'/>";
				echo "<input type='hidden' name='hdn_partidaReq$ctrl' id='hdn_partidaReq$ctrl' value='$partida'/>";
				if(isset($_SESSION["detalle_pedido"])){
					$detallePedido[] = array("seleccionado"=>'1',"cant_ped"=>$cant_ped,"descripcion"=>$descripcion,"precio"=>$precio,"equipo"=>$equipo,"control_costos"=>$control_costos,"cuenta"=>$cuenta,"subcuenta"=>$subcuenta, "partida"=>$partida);
				} else {
					$detallePedido = array(array("seleccionado"=>'1',"cant_ped"=>$cant_ped,"descripcion"=>$descripcion,"precio"=>$precio,"equipo"=>$equipo,"control_costos"=>$control_costos,"cuenta"=>$cuenta,"subcuenta"=>$subcuenta, "partida"=>$partida));
				}
				//Guardar el arreglo en la SESSION
				$_SESSION['detalle_pedido'] = $detallePedido;
			} else {
				if(isset($_SESSION["detalle_pedido"])){
					$detallePedido[] = array("seleccionado"=>'0',"cant_ped"=>'',"descripcion"=>'',"precio"=>'',"equipo"=>'',"control_costos"=>'',"cuenta"=>'',"subcuenta"=>'', "partida"=>'');
				} else {
					$detallePedido = array(array("seleccionado"=>'0',"cant_ped"=>'',"descripcion"=>'',"precio"=>'',"equipo"=>'',"control_costos"=>'',"cuenta"=>'',"subcuenta"=>'', "partida"=>''));
				}
				//Guardar el arreglo en la SESSION
				$_SESSION['detalle_pedido'] = $detallePedido;
			}
			$ctrl++;
		}while($ctrl<=$tam);
	}
	function obtenerDatoPedido($pedido,$campo,$tabla,$busq){
		$valor = "";
		$conexion = conecta("bd_compras");
		$stm_sql = "SELECT $campo FROM $tabla WHERE $busq = '$pedido'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$valor = $datos[0];
			}
		}
		return $valor;
	}
	
	function modificarPedido(){
		$conn = conecta("bd_compras");
		$txt_noPedido = $_POST["txt_idPedido"];
		
		if (strlen($_POST["txt_subtotal"])>6) 
			$_POST["txt_subtotal"] = str_replace(",","",$_POST["txt_subtotal"]);
		if (strlen($_POST["txt_iva"])>6)
			$_POST["txt_iva"] = str_replace(",","",$_POST["txt_iva"]);
		if (strlen($_POST["txt_total"])>6)
			$_POST["txt_total"] = str_replace(",","",$_POST["txt_total"]);
		
		$descto=str_replace(",","",$_POST["hdn_descto"]);
		$txt_rfc = strtoupper($_POST["txt_rfc"]);
		$txa_condEnt = strtoupper($_POST["txa_condEnt"]);
		$cmb_condPago = strtoupper($_POST["cmb_condPago"]);
		$txt_plazo = strtoupper($_POST["txt_plazo"])." ".strtoupper($_POST["cmb_plazo"]);
		$cmb_solicito = $_POST["cmb_solicito"];
		$solicito = split("-",$cmb_solicito);
		$txt_reviso = strtoupper($_POST["txt_reviso"]);
		$txt_autorizo = strtoupper($_POST["txt_autorizo"]);
		$txa_comentarios = strtoupper($_POST["txa_comentarios"]);
		$cmb_plazo = $_POST["cmb_plazo"];
		$cmb_viaPed = $_POST["cmb_viaPed"];
		$cmb_moneda = $_POST["cmb_tipoMoneda"];
		
		$stm_sql = "UPDATE pedido SET 
						proveedores_rfc = '$txt_rfc',
						cond_entrega = '$txa_condEnt',
						cond_pago = '$cmb_condPago',
						plazo_entrega = '$txt_plazo',
						subtotal = $_POST[txt_subtotal],
						iva = $_POST[txt_iva],
						pctje_descto = $descto,
						total = $_POST[txt_total],
						tipo_moneda = '$cmb_moneda',
						solicitor = '$solicito[1]',
						revisor = '$txt_reviso',
						autorizador = '$txt_autorizo',
						comentarios = '$txa_comentarios',
						via_pedido = '$cmb_viaPed',
						depto_solicitor = '$solicito[0]',
						impreso = 0
					WHERE id_pedido = '$txt_noPedido'";
		
		$rs = mysql_query($stm_sql);
		
		if($rs){		
			registrarOperacion("bd_compras",$txt_noPedido,"ModificarPedido",$_SESSION['usr_reg']);
			modificarDetalleDelPedido($txt_noPedido,$_POST["txt_lblIVA"],$_POST["hdn_ivaIncluido"]);
		}
		else{			
			$error = mysql_error();			
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}		
	}
	
	function modificarDetalleDelPedido($pedido,$iva,$ivaIncluido){
		$cont = 0;
		foreach($_SESSION['detalle_pedido'] as $ind => $concepto){
			if($concepto["seleccionado"] == 1){
				$conn = conecta("bd_compras");
				$error = "";
				$band = 0;
				
				if($ivaIncluido=="NO"){
					if (strlen($concepto["precio"])>6)
						$concepto["precio"]=str_replace(",","",$concepto["precio"]);
				}
				else if($ivaIncluido=="SI"){
					$concepto["precio"]=str_replace(",","",$concepto["precio"]) / (1+(str_replace("%","",$iva)/100));
				}
				
				$importe = $concepto["precio"] * $concepto["cant_ped"];
				$desc = strtoupper($concepto["descripcion"]);
				if($concepto["control_costos"] == '')
					$concepto["control_costos"] = "N/A";
				if($concepto["cuenta"] == '')
					$concepto["cuenta"] = "N/A";
				if($concepto["subcuenta"] == '')
					$concepto["subcuenta"] = "N/A";
				
				$stm_sql = "UPDATE detalles_pedido SET 
								cantidad = $concepto[cant_ped],
								descripcion = '$desc',
								equipo = '$concepto[equipo]',
								precio_unitario = '$concepto[precio]',
								importe = '$importe',
								id_control_costos = '$concepto[control_costos]',
								id_cuentas = '$concepto[cuenta]',
								id_subcuentas = '$concepto[subcuenta]',
								cantidad_real = $concepto[cant_ped]
							WHERE pedido_id_pedido = '$pedido' AND partida = '$concepto[partida]'";
				//if($cont == 0)
					//echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>".$stm_sql;
				//else
					//echo "<br>".$stm_sql;
				$rs = mysql_query($stm_sql);
				if(!$rs)
					$band = 1;						
				if($band==1){
					break;
					$error = mysql_error();
				}
				mysql_close($conn);
			}
			$cont++;
		}
		
		if ($band==0){
			?>
			<script type='text/javascript' language='javascript'>
				var codAbrirPedido = "window.open('../../includes/generadorPDF/pedido2.php?id=<?php echo $pedido; ?>', '_blank', ";
				codAbrirPedido += "'top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')";				
				setTimeout(codAbrirPedido,2000);
			</script>
			<?php
			echo "<meta http-equiv='refresh' content='4;url=exito.php'>";
		} else {
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
	}
?>
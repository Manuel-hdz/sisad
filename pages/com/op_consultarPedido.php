<?php

	/**
	  * Nombre del Módulo: Compras
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 22/Diciembre/2010                                      			
	  * Descripción: Este archivo contiene funciones desplegar la informacion de los pedidos registrados
	  **/
	
	//Funcion para mostrar los convenios registrados de los proveedores
	//La variable $opc, indica que tipo de consulta se realizará
	//	1-> Todos los pedidos
	//	2-> Pedidos de algun departamento
	//	3-> Pedidos desde la pantalla de complementacion
	function mostrarPedidos($opc){
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		if ($opc==1){
			//Obtener la fecha limite de los pedidos y convertirla al formato leido por MySQL
			$fecha_ini = modFecha($_POST["txt_fechaPed3"],3);
			$fecha_fin = modFecha($_POST["txt_fechaPed4"],3);
			
			//Crear sentencia SQL
			$stm_sql="SELECT * FROM pedido WHERE fecha>='$fecha_ini' AND fecha<='$fecha_fin' ORDER BY fecha,depto_solicitor";
			
			//Colocar los datos necesarios para regresar a esta patalla cuando se regrese de la pantalla de consultar detalle del pedido?>
			<input type="hidden" name="hdn_btnSeleccionado" value="btn_pedido" />
			<input type="hidden" name="hdn_fechaPed3" value="<?php echo $_POST["txt_fechaPed3"]; ?>" />
			<input type="hidden" name="hdn_fechaPed4" value="<?php echo $_POST["txt_fechaPed4"]; ?>" /><?php
		}
		if ($opc==2){
			//Obtener la fecha inicio de los pedidos y convertirla al formato leido por MySQL
			$fechaIni=modFecha($_POST["txt_fechaPed1"],3);
			//Obtener la fecha Final de los pedidos y convertirla al formato leido por MySQL
			$fechaFin=modFecha($_POST["txt_fechaPed2"],3);
			//Obtener el departamento seleccionado
			$depto=$_POST["cmb_departamento"];
			//Crear sentencia SQL
			$stm_sql="SELECT * FROM pedido WHERE fecha>='$fechaIni' AND fecha<='$fechaFin' AND depto_solicitor='$depto' ORDER BY fecha,depto_solicitor";
			
			//Colocar los datos necesarios para regresar a esta patalla cuando se regrese de la pantalla de consultar detalle del pedido?>
			<input type="hidden" name="hdn_btnSeleccionado" value="btn_depto" />
			<input type="hidden" name="hdn_departamento" value="<?php echo $_POST["cmb_departamento"]; ?>" />
			<input type="hidden" name="hdn_fechaPed1" value="<?php echo $_POST["txt_fechaPed1"]; ?>" />
			<input type="hidden" name="hdn_fechaPed2" value="<?php echo $_POST["txt_fechaPed2"]; ?>" /><?php
		}
		if ($opc==3){
			//Obtener la fecha inicio de los pedidos y convertirla al formato leido por MySQL
			$fechaIni=modFecha($_POST["txt_fechaIni"],3);
			//Obtener la fecha Final de los pedidos y convertirla al formato leido por MySQL
			$fechaFin=modFecha($_POST["txt_fechaFin"],3);
			//Crear sentencia SQL
			$stm_sql="SELECT * FROM pedido WHERE estado='NO PAGADO' AND fecha>='$fechaIni' AND fecha<='$fechaFin' ORDER BY id_pedido";
		}
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			echo "<table cellpadding='5' width='1000' align='center' id='tabla-resultadosPedidos'> 
				<caption class='titulo_etiqueta'>PEDIDOS REGISTRADOS</caption></br>";
			echo "
					<thead>
					<tr>
						<th class='nombres_columnas' align='center'>SELECCIONAR</th>
						<th class='nombres_columnas' align='center'>N° PEDIDO</th>
						<th class='nombres_columnas' align='center'>PROVEEDOR</th>
						<th class='nombres_columnas' align='center'>REQUISICI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>CONDICIONES ENTREGA</th>
						<th class='nombres_columnas' align='center'>CONDICIONES PAGO</th>
						<th class='nombres_columnas' align='center'>PLAZO ENTREGA</th>
						<th class='nombres_columnas' align='center'>FECHA PEDIDO</th>
						<th class='nombres_columnas' align='center'>SUBTOTAL</th>
						<th class='nombres_columnas' align='center'>IVA</th>
						<th class='nombres_columnas' align='center'>TOTAL</th>
						<th class='nombres_columnas' align='center'>MONEDA</th>
						<th class='nombres_columnas' align='center'>SOLICIT&Oacute;</th>
						<th class='nombres_columnas' align='center'>REVIS&Oacute;</th>
						<th class='nombres_columnas' align='center'>AUTORIZ&Oacute;</th>
						<th class='nombres_columnas' align='center'>COMENTARIOS</th>
						<th class='nombres_columnas' align='center'>VIA</th>
						<th class='nombres_columnas' align='center'>FECHA DE ENTREGA</th>
						<th class='nombres_columnas' align='center'>HORA DE ENTREGA</th>
						<th class='nombres_columnas' align='center'>FECHA DE PAGO</th>
						<th class='nombres_columnas' align='center'>ESTADO</th>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";	
			do{
				$proveedor=obtenerDato("bd_compras","proveedores","razon_social","rfc",$datos["proveedores_rfc"]);		
				echo "	<tr>
						<td class='$nom_clase' align='center'>";
						?><input type="radio" name="rdb_idPedido" id="rdb_idPedido<?php echo $cont;?>" value="<?php echo $datos["id_pedido"]?>" onclick="sbt_detalle.disabled=false;"/>
						<?php 
						echo "</td>					
						<td class='$nom_clase' align='center'>$datos[id_pedido]</td>					
						<td class='$nom_clase' align='center'>$proveedor</td>
						<td class='$nom_clase' align='center'>$datos[requisiciones_id_requisicion]</td>
						<td class='$nom_clase' align='center'>$datos[cond_entrega]</td>
						<td class='$nom_clase' align='center'>$datos[cond_pago]</td>
						<td class='$nom_clase' align='center'>$datos[plazo_entrega]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos["fecha"],2)."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["subtotal"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["iva"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["total"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[tipo_moneda]</td>
						<td class='$nom_clase' align='center'>$datos[solicitor]</td>
						<td class='$nom_clase' align='center'>$datos[revisor]</td>
						<td class='$nom_clase' align='center'>$datos[autorizador]</td>
						<td class='$nom_clase' align='center'>$datos[comentarios]</td>
						<td class='$nom_clase' align='center'>$datos[via_pedido]</td>"; 
						
						if ($datos["fecha_entrega"]!="")
							echo"<td class='$nom_clase' align='center'>".modFecha($datos["fecha_entrega"],2)."</td>";
						else
							echo"<td class='$nom_clase' align='center'>".$datos["fecha_entrega"]."</td>";
						
						if ($datos["hora_entrega"]!="")
							echo"<td class='$nom_clase' align='center'>".modHora($datos["hora_entrega"])."</td>";
						else
							echo"<td class='$nom_clase' align='center'>".$datos["hora_entrega"]."</td>";
						
						if ($datos["fecha_pago"]!="")
							echo"<td class='$nom_clase' align='center'>".modFecha($datos["fecha_pago"],2)."</td>";
						else
							echo"<td class='$nom_clase' align='center'>".$datos["fecha_pago"]."</td>";
							
				echo "	<td class='$nom_clase' align='center'>$datos[estado]</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";
			if ($opc==3)
				return true;
		}
		else{
			if ($opc!=3)
				echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>NO SE HAN REGISTRADO PEDIDOS</p>";
			else{
				echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>TODOS LOS PEDIDOS ESTAN COMPLEMENTADOS</p>";
				return false;
			}
		}
		//Cerar conexion a BD
		mysql_close($conn);		
	}
	
	
	function mostrarDetallePedido(){
		$pedido=$_POST["rdb_idPedido"];
		$tipoMoneda=obtenerdato("bd_compras","pedido","tipo_moneda","id_pedido",$pedido);
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		$stm_sql="SELECT partida,unidad,cantidad,descripcion,equipo,precio_unitario,importe,cantidad_real FROM detalles_pedido WHERE pedido_id_pedido='$pedido' ORDER BY partida";
		//Variable que acumula el total del Precio Unitario
		$cu=0;
		//Variable que acumula el total de los Importes
		$ct=0;
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){	
			echo "<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>DETALLES DEL PEDIDO ".$pedido."</caption></br>";
			echo "<tr>
						<td class='nombres_columnas' align='center'>PARTIDA</td>
						<td class='nombres_columnas' align='center'>UNIDAD</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>EQUIPO</td>
						<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
						<td class='nombres_columnas' align='center'>IMPORTE</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				$equipo="N/D";
					if ($datos["equipo"]!="")
						$equipo=$datos["equipo"];									
				echo "	<tr>					
						<td class='$nom_clase' align='center'>$datos[partida]</td>					
						<td class='$nom_clase' align='center'>$datos[unidad]</td>
						<td class='$nom_clase' align='center'>$datos[cantidad_real]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>$equipo</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["precio_unitario"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["importe"],2,".",",")."</td>
						</tr>";				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
				$cu+=$datos["precio_unitario"];
				$ct+=$datos["importe"];
					
			}while($datos=mysql_fetch_array($rs));
			//Obtener el total del pedido registrado
			$stm_sql="SELECT subtotal,iva,total FROM pedido WHERE id_pedido= '$pedido'";
			//Ejecutar la consulta
			$rs = mysql_query($stm_sql);		            						
			$datos_pedido=mysql_fetch_array($rs);
			$porcentaje_iva = round( ((intval($datos_pedido['iva'])/intval($datos_pedido['subtotal'])) * 100),0);
			if ($tipoMoneda=="PESOS")
				$tipoMoneda="M.N.";
			else
				$tipoMoneda="USD";
			echo "</tr>
				<tr>
					<td colspan='5'>&nbsp;</td>
					<td align='right' class='nombres_filas'><strong>SUBTOTAL</strong></td>
					<td align='center' class='nombres_filas'>$".number_format($datos_pedido['subtotal'],2,".",",")."</td>
				</tr>
				<tr>
					<td colspan='5'>&nbsp;</td>
					<td align='right' class='nombres_filas'><strong>IVA $porcentaje_iva%</strong></td>
					<td align='center' class='nombres_filas'>$".number_format($datos_pedido['iva'],2,".",",")."</td>
													
				</tr>
				<tr>
					<td colspan='5'>&nbsp;</td>
					<td align='right' class='nombres_filas'><strong>TOTAL $tipoMoneda</strong></td>
					<td class='nombres_columnas' align='center'>$".number_format($datos_pedido['total'],2,".",",")."</td>
				</tr>
			</table>";
		}
		else{
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		//Cerar conexion a BD
		mysql_close($conn);
	}
	
	function cancelarPedido($idPedido,$idRequisicion){
		//Conexion a la BD de Compras
		$conn=conecta("bd_compras");
		//Sentencia para actualizar el estado del Pedido
		$sql_stm="UPDATE pedido SET estado='CANCELADO' WHERE id_pedido='$idPedido'";
		//Ejecutar Sentencia
		$rs=mysql_query($sql_stm);
		//Si la sentencia se ejecutó correctamente, cancelar la Requisicion
		if($rs){
			switch(substr($idRequisicion,0,3)){
				case "ALM":
					$base="bd_almacen";
				break;
				case "GER":
					$base="bd_gerencia";
				break;
				case "REC":
					$base="bd_recursos";
				break;
				case "PRO":
					$base="bd_produccion";
				break;
				case "ASE":
					$base="bd_aseguramiento";
				break;
				case "DES":
					$base="bd_desarrollo";
				break;
				case "MAN":
					$base="bd_mantenimiento";
				break;
				case "MAC":
					$base="bd_mantenimiento";
				break;
				case "MAM":
					$base="bd_mantenimiento";
				break;
				case "MAE":
					$base="bd_mantenimientoe";
				break;
				case "TOP":
					$base="bd_topografia";
				break;
				case "LAB":
					$base="bd_laboratorio";
				break;
				case "SEG":
					$base="bd_seguridad";
				break;
				case "PAI":
					$base="bd_paileria";
				break;
				case "USO":
					$base="bd_clinica";
				break;
				default:
					$base="";
				break;
			}
			//Verificar si base tomo valor
			if($base!=""){
				//Sentencia para verificar que la Requisicion no se encuentre dentro de mas Pedidos
				$sql_stm="SELECT partida_requisicion FROM detalles_pedido WHERE pedido_id_pedido='$idPedido' AND partida_requisicion != 0";
				//Ejecutar Sentencia
				$rs=mysql_query($sql_stm);
				//Cerar conexion a BD de Compras
				mysql_close($conn);
				//Conexion a la BD donde pertenece la Requisicion
				$conn=conecta($base);
				//Si no hay mas Pedidos asociados, actualizar el estado de la Requisicion, de lo contrario informarselo al usuario
				if($datos = mysql_fetch_array($rs)){
					do{
						//Sentencia para actualizar el estado del Pedido
						//$sql_stm="UPDATE requisiciones SET estado='CANCELADA' WHERE id_requisicion='$idRequisicion'";
						$sql_stm = "UPDATE detalle_requisicion SET mat_pedido = 1 
									WHERE requisiciones_id_requisicion='$idRequisicion' AND partida='$datos[partida_requisicion]'";
						//Ejecutar Sentencia
						$rs2=mysql_query($sql_stm);
					}while($datos = mysql_fetch_array($rs));
					if($rs2){
						$sql_stm = "UPDATE requisiciones SET estado = 'ENVIADA' 
									WHERE id_requisicion='$idRequisicion'";
						//Ejecutar Sentencia
						$rs=mysql_query($sql_stm);
					}
					//Cerar conexion a BD
					mysql_close($conn);
					//Guardar el Movimiento
					registrarOperacion("bd_compras",$idPedido,"CancelarPedido",$_SESSION['usr_reg']);
					if($rs){
						//Guardar el Movimiento
						registrarOperacion("bd_compras",$idRequisicion,"CancelarPedRequisicion",$_SESSION['usr_reg']);
						//Redireccionar a la pagina de Exito
						echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
					}
					else{
						$error = "Debe Actualizar la Requisición Manualmente";
						echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
					}
				}
				else{
					$error = "No se encontraron requisiciones relacionadas al pedido";
					echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				}
			}
			else{
				//Si no se encontro BD pasar directo a la pagina de Exito
				//Redireccionar a la pagina de Exito
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
		}else{
			//Cerar conexion a BD
			mysql_close($conn);
			//Guardar el Movimiento
			registrarOperacion("bd_compras",$idPedido,"CancelarPedido",$_SESSION['usr_reg']);
		?>
			<script language="javascript" type="text/javascript">
				setTimeout("alert('No se pudo Actualizar el Estado del Pedido <?php echo $idPedido?>')",1000);
			</script>
		<?php
		}
	}
	
	function modificarDetallePedido(){
		$pedido = $_POST["txt_idPedido"];
		$conn = conecta("bd_compras");
		$proveedor = "";
		$descuento = "";
		
		$stm_sql = "SELECT * 
					FROM pedido AS T1
					JOIN detalles_pedido AS T2 ON T1.id_pedido = T2.pedido_id_pedido
					JOIN proveedores AS T3 ON T3.rfc = T1.proveedores_rfc
					WHERE T1.id_pedido =  '$pedido'";
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			$subtotal=0;
			echo "
			<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>PEDIDO ".$pedido."</caption></br>";
			
			echo "
				<tr>
					<td class='nombres_columnas' align='center' colspan='2'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
					<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>EQUIPO</td>
					<td class='nombres_columnas' align='center'>CONTROL DE COSTOS</td>
					<td class='nombres_columnas' align='center'>CUENTAS</td>
					<td class='nombres_columnas' align='center'>SUBCUENTAS</td>
					<td class='nombres_columnas' align='center'>PRECIO&nbsp;UNITARIO</td>
					<td class='nombres_columnas' align='center'>IMPORTE</td>
				</tr>";
			$proveedor = $datos["razon_social"];
			$descuento = $datos["pctje_descto"];;
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase?>">
						<?php
						echo "$cont.-";
						?>
					</td>
					<td align="center" class="<?php echo $nom_clase?>">
						<input type="checkbox" name="ckb_pieza<?php echo $cont?>" id="ckb_pieza<?php echo $cont?>" onclick="elegirMaterialMod(<?php echo $cont?>);" tabindex="<?php echo $cont?>" />
					</td>
					<?php
					if(isset($_SESSION["detalle_pedido"])){
						if($_SESSION["detalle_pedido"][$cont-1]["seleccionado"]=='1'){
						?>
							<script>
								objeto_chk<?php echo $cont?> = document.getElementById("ckb_pieza<?php echo $cont?>");
								setTimeout("objeto_chk<?php echo $cont?>.click()",100);
							</script>
						<?php
						}
					}
					?>
					
					<td class="<?php echo $nom_clase; ?>" align='center'>
						<input type="text" name="hdn_cantReq<?php echo $cont; ?>" id="hdn_cantReq<?php echo $cont; ?>" autocomplete="off" 
						<?php 
						if(isset($_SESSION["detalle_pedido"])){
							if($_SESSION["detalle_pedido"][$cont-1]["cant_ped"]!=''){
								echo "value='".$_SESSION['detalle_pedido'][$cont-1]['cant_ped']."'";
							} else {
								echo "value='$datos[cantidad_real]'";
							}
						} else {
							echo "value='$datos[cantidad_real]'";
						}?> class="caja_de_num" readonly="true" onChange="operacionesPedido(<?php echo $cont; ?>,'uni');"/>
					</td>
					
					<td class="<?php echo $nom_clase; ?>" align='center'>
						<?php echo $datos['unidad']; ?>
					</td>
					
					<td class="<?php echo $nom_clase; ?>" align='center'>
							<input type="text" name="descMat<?php echo $cont; ?>" id="descMat<?php echo $cont; ?>" autocomplete="off" 
							<?php 
							if(isset($_SESSION["detalle_pedido"])){ 
								if($_SESSION["detalle_pedido"][$cont-1]["descripcion"]!=''){
									echo "value='".$_SESSION['detalle_pedido'][$cont-1]['descripcion']."'";
								} else {
									echo "value='$datos[descripcion]'";
								}
							} else {
								echo "value='$datos[descripcion]'";
							}?> class="caja_de_num" readonly="true" onChange="operacionesPedido(<?php echo $cont; ?>,'uni');"/>
					</td>
					
					<td class='<?php echo $nom_clase; ?>' align='center'>
						<?php
						$conn1 = conecta("bd_mantenimiento");
						$rs_equipos = mysql_query("SELECT DISTINCT id_equipo FROM equipos WHERE estado='ACTIVO' ORDER BY id_equipo");
						if($equipos=mysql_fetch_array($rs_equipos)){
							?>
							<select name="cmb_equipos<?php echo $cont;?>" id="cmb_equipos<?php echo $cont;?>" class="combo_box" 
							onchange="agregarNvoEquipo(this); cargarCuentas_Equipo(this.value,'cmb_con_cos<?php echo $cont;?>','cmb_cuenta<?php echo $cont;?>','cmb_subcuenta<?php echo $cont;?>','hdn_control<?php echo $cont;?>','hdn_cuentas<?php echo $cont;?>');" disabled="disabled">
								<option value="">Equipos</option><?php
								do{
									if(isset($_SESSION["detalle_pedido"])){
										if($_SESSION["detalle_pedido"][$cont-1]["equipo"]==$equipos['id_equipo']){
											echo "<option selected='selected' value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
										}
										else{
											if($datos["equipo"] == $equipos["id_equipo"])
												echo "<option selected='selected' value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
											else
												echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
										}
									} else{
										if($datos["equipo"] == $equipos["id_equipo"])
											echo "<option selected='selected' value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
										else
											echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
									}
								}while($equipos=mysql_fetch_array($rs_equipos));?>
								<option value="NUEVO">Equipo Nuevo</option>
								</select><?php
						}
						else
							 echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Equipos Registrados</label>";
						mysql_close($conn1);
						//Cerrar la conexion a la BD, esto parece que cierra ambas conexiones a las BD's
					?> 
					</td>
					<td>
						<?php 
						$conn_rec = conecta("bd_recursos");		
						$stm_sql_rec = "SELECT * FROM control_costos ORDER BY descripcion";
						$rs_rec = mysql_query($stm_sql_rec);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos_rec = mysql_fetch_array($rs_rec)){?>
							<select name="cmb_con_cos<?php echo $cont;?>" id="cmb_con_cos<?php echo $cont;?>" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta<?php echo $cont;?>')" disabled="disabled">
								<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
								echo "<option value=''>Control de Costos</option>";
								do{
									if(isset($_SESSION["detalle_pedido"])){
										if($_SESSION["detalle_pedido"][$cont-1]["control_costos"]==$datos_rec['id_control_costos']){
											echo "<option selected='selected' value='$datos_rec[id_control_costos]'>$datos_rec[descripcion]</option>";
										}
										else{
											if($datos["id_control_costos"] == $datos_rec["id_control_costos"])
												echo "<option selected='selected' value='$datos_rec[id_control_costos]'>$datos_rec[descripcion]</option>";
											else
												echo "<option value='$datos_rec[id_control_costos]'>$datos_rec[descripcion]</option>";
										}
									} else{
										if($datos["id_control_costos"] == $datos_rec["id_control_costos"])
											echo "<option selected='selected' value='$datos_rec[id_control_costos]'>$datos_rec[descripcion]</option>";
										else
											echo "<option value='$datos_rec[id_control_costos]'>$datos_rec[descripcion]</option>";
									}
								}while($datos_rec = mysql_fetch_array($rs_rec));?>
							</select>
						<?php
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn_rec);
						if(isset($_SESSION["detalle_pedido"])){
							if($_SESSION["detalle_pedido"][$cont-1]["control_costos"]!=''){
								$tiempo = $cont*100;
								?>
								<script>
									objeto_cc<?php echo $cont?> = document.getElementById("cmb_con_cos<?php echo $cont?>");
									setTimeout("objeto_cc<?php echo $cont?>.onchange()",<?php echo $tiempo; ?>);
								</script>
								<?php
							} else if($datos["id_control_costos"]!="N/A"){
								$tiempo = $cont*100;
								?>
								<script>
									objeto_cc<?php echo $cont?> = document.getElementById("cmb_con_cos<?php echo $cont?>");
									setTimeout("objeto_cc<?php echo $cont?>.onchange()",<?php echo $tiempo; ?>);
								</script>
								<?php
							}
						} else if($datos["id_control_costos"]!="N/A"){
							$tiempo = $cont*100;
							?>
							<script>
								objeto_cc<?php echo $cont?> = document.getElementById("cmb_con_cos<?php echo $cont?>");
								setTimeout("objeto_cc<?php echo $cont?>.onchange()",<?php echo $tiempo; ?>);
							</script>
							<?php
						}
						?>
						<?php 
						echo "<input type='hidden' name='hdn_control$cont' id='hdn_control$cont' value=''/>";
						echo "<input type='hidden' name='hdn_cuentas$cont' id='hdn_cuentas$cont' value=''/>";
						?>
					</td>
					<td>
						<span id="datosCuenta">
							<select name="cmb_cuenta<?php echo $cont;?>" id="cmb_cuenta<?php echo $cont;?>" class="combo_box" onchange="cargarSubCuentas(cmb_con_cos<?php echo $cont;?>.value,this.value,'cmb_subcuenta<?php echo $cont;?>')" disabled="disabled">
								<option value="">Cuentas</option>
							</select>
						</span>
					</td>
					<?php
					if(isset($_SESSION["detalle_pedido"])){
						if($_SESSION["detalle_pedido"][$cont-1]["cuenta"]!=''){
							$tiempo1 = 200*$cont;
							$tiempo2 = 500*$cont;
							?>
							<script>
								objeto_cuen<?php echo $cont; ?> = document.getElementById("cmb_cuenta<?php echo $cont?>");
								setTimeout("objeto_cuen<?php echo $cont; ?>.value='<?php echo $_SESSION['detalle_pedido'][$cont-1]['cuenta']; ?>'",<?php echo $tiempo1; ?>);
								setTimeout("objeto_cuen<?php echo $cont?>.onchange()",<?php echo $tiempo2; ?>);
							</script>
							<?php
						} else if($datos["id_cuentas"]!="N/A"){
							$tiempo1 = 200*$cont;
							$tiempo2 = 500*$cont;
							?>
							<script>
								objeto_cuen<?php echo $cont; ?> = document.getElementById("cmb_cuenta<?php echo $cont?>");
								setTimeout("objeto_cuen<?php echo $cont; ?>.value='<?php echo $datos['id_cuentas']; ?>'",<?php echo $tiempo1; ?>);
								setTimeout("objeto_cuen<?php echo $cont?>.onchange()",<?php echo $tiempo2; ?>);
							</script>
							<?php
						}
					} else if($datos["id_cuentas"]!="N/A"){
						$tiempo1 = 200*$cont;
						$tiempo2 = 500*$cont;
						?>
						<script>
							objeto_cuen<?php echo $cont; ?> = document.getElementById("cmb_cuenta<?php echo $cont?>");
							setTimeout("objeto_cuen<?php echo $cont; ?>.value='<?php echo $datos['id_cuentas']; ?>'",<?php echo $tiempo1; ?>);
							setTimeout("objeto_cuen<?php echo $cont?>.onchange()",<?php echo $tiempo2; ?>);
						</script>
						<?php
					}
					?>
					<td>
						<span id="datosSubCuenta">
							<select name="cmb_subcuenta<?php echo $cont;?>" id="cmb_subcuenta<?php echo $cont;?>" class="combo_box" disabled="disabled">
								<option value="">SubCuentas</option>
							</select>
						</span>
					</td>
					<?php
					if(isset($_SESSION["detalle_pedido"])){
						if($_SESSION["detalle_pedido"][$cont-1]["subcuenta"]!=''){
							$tiempo3 = 700*$cont;
							?>
							<script>
								objeto_sub<?php echo $cont; ?> = document.getElementById("cmb_subcuenta<?php echo $cont?>");
								setTimeout("objeto_sub<?php echo $cont; ?>.value='<?php echo $_SESSION['detalle_pedido'][$cont-1]['subcuenta']; ?>'",<?php echo $tiempo3; ?>);
							</script>
							<?php
						} else if($datos["id_subcuentas"]!="N/A"){
							$tiempo3 = 700*$cont;
							?>
							<script>
								objeto_sub<?php echo $cont; ?> = document.getElementById("cmb_subcuenta<?php echo $cont?>");
								setTimeout("objeto_sub<?php echo $cont; ?>.value='<?php echo $datos['id_subcuentas']; ?>'",<?php echo $tiempo3; ?>);
							</script>
							<?php
						}
					} else if($datos["id_subcuentas"]!="N/A"){
						$tiempo3 = 700*$cont;
						?>
						<script>
							objeto_sub<?php echo $cont; ?> = document.getElementById("cmb_subcuenta<?php echo $cont?>");
							setTimeout("objeto_sub<?php echo $cont; ?>.value='<?php echo $datos['id_subcuentas']; ?>'",<?php echo $tiempo3; ?>);
						</script>
						<?php
					}
					?>
					<td class="<?php echo $nom_clase; ?>" align='center'>
						$<input name="txt_precio<?php echo $cont;?>" type="text" id="txt_precio<?php echo $cont;?>" class="caja_de_num" size="10" 
						maxlength="10" onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'uni');" readonly="readonly" 
						<?php 
						if(isset($_SESSION["detalle_pedido"])){ 
							if($_SESSION["detalle_pedido"][$cont-1]["precio"]!=''){
								echo "value='".$_SESSION['detalle_pedido'][$cont-1]['precio']."'";
							} else {
								echo "value='".$datos['precio_unitario']."'";
							}
						} else {
							echo "value='".$datos['precio_unitario']."'";
						}?> />
					</td>
					<td class="<?php echo $nom_clase; ?>" align='center'>
						$<input name="txt_importe<?php echo $cont;?>" type="text" id="txt_importe<?php echo $cont;?>" class="caja_de_num" size="10" 
						maxlength="10" onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
					</td>
				</tr>
				<?php
				if(isset($_SESSION["detalle_pedido"])){
					if($_SESSION["detalle_pedido"][$cont-1]["precio"]!=''){
						?>
						<script>
							objeto_precio<?php echo $cont; ?> = document.getElementById("txt_precio<?php echo $cont?>");
							setTimeout("objeto_precio<?php echo $cont?>.onchange()",3000);
						</script>
						<?php
					} else {
						?>
						<script>
							objeto_precio<?php echo $cont; ?> = document.getElementById("txt_precio<?php echo $cont?>");
							setTimeout("objeto_precio<?php echo $cont?>.onchange()",3000);
						</script>
						<?php
					}
				} else {
					?>
					<script>
						objeto_precio<?php echo $cont; ?> = document.getElementById("txt_precio<?php echo $cont?>");
						setTimeout("objeto_precio<?php echo $cont?>.onchange()",3000);
					</script>
					<?php
				}
				echo "<input type='hidden' value='$datos[partida]' name='txt_partidaReq$cont' id='txt_partidaReq$cont'/>";
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos = mysql_fetch_array($rs));
			echo "
				<tr>
					<td colspan='10' align='right'><strong>SUBTOTAL</strong></td>";?>
					<td align='center'>
						<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo $cont-1;?>"/>
						$<input type='text' name='txt_subtotal' id='txt_subtotal' class='caja_de_num' size='10' 
            			onClick="formatCurrency(value.replace(/,/g,''),'txt_subtotal');" tabindex="<?php echo $cont;?>" 
						onBlur="formatCurrency(value.replace(/,/g,''),'txt_subtotal');" value="0.00"/>
					</td>
					<?php
					echo "<input type='hidden' name='hdn_pedido' id='hdn_pedido' value='$pedido'/>";
					echo "<input type='hidden' name='hdn_cantidad' id='hdn_cantidad' value='$cont'/>
				</tr>
				<tr>
					<td colspan='7' align='right'><strong>DESCUENTO</strong></td>";?>
					<td align='center'>
						<input type="text" name="txt_descto" id="txt_descto" class="caja_de_num" size="6" maxlength="6" value="<?php echo number_format($descuento,2,".",",");?>"
						onkeypress="return permite(event,'num_car', 0);" onchange="validarDescto(this);calcularDesctoSobrePedido(0);formatCurrency(value.replace(/,/g,''),'txt_descto');"/>%
					</td>
					<?php
					echo "
				</tr>
			</table>";
			?>
			
			<table cellpadding="5" width="850" align="center" class="tabla_frm">
				<tr>					
					<td align="right" valign="top" colspan="2">
						<strong>PROVEEDOR</strong>
					</td>
					<td colspan="4">
						<input type="text" name="txt_nomProveedor" id="txt_nomProveedor" onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');" 
						value="<?php echo $proveedor; ?>" size="50" maxlength="80" onkeypress="return permite(event,'num_car', 0);" tabindex="6" readonly="true" />
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</td>
				</tr>
			</table><?php
		}
		else{
			echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>NO SE HAN REGISTRADO PEDIDOS</p>";
		}
	}
	
	function busqEntradaPed($pedido){
		$existe = false;
		$con = conecta("bd_almacen");
		$stm_sql = "SELECT * 
					FROM  `entradas` 
					WHERE  `requisiciones_id_requisicion` LIKE  '$pedido'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$existe = true;
			}
		}
		return $existe;
	}
?>
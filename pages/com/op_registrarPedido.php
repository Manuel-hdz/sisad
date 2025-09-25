<?php
	/**
	  * Nombre del M�dulo: Compras                                              
	  * Nombre Programador: Nadia Madah� L�pez Hern�ndez                            
	  * Fecha: 19/Noviembre/2010                                      			
	  * Descripci�n: Este archivo contiene funciones para guardar en la BD la informaci�n acerca de el registro de pedidos.
	  **/


	//Si el boton registrar ha sido presionado, realizar la insercion de datos
	function registraPedido(){
		$txt_noPedido = obtenerIdPedido();
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");	

		//Obtener y convertir la fecha al formato aaaa-mm-dd
		$fecha = date("Y-m-d");
		
		//Retirar la Coma en el caso que la cantidad supere los 6 digitos
		if (strlen($_POST["txt_subtotal"])>6) 
			$_POST["txt_subtotal"] = str_replace(",","",$_POST["txt_subtotal"]);
		if (strlen($_POST["txt_iva"])>6)
			$_POST["txt_iva"] = str_replace(",","",$_POST["txt_iva"]);
		if (strlen($_POST["txt_total"])>6)
			$_POST["txt_total"] = str_replace(",","",$_POST["txt_total"]);

		//Obtener el porcentaje de descuento
		$descto=str_replace(",","",$_POST["hdn_descto"]);

		//Convertir a mayusculas
		//$txt_noPedido = strtoupper($_POST["txt_noPedido"]);
		$txt_rfc = strtoupper($_POST["txt_rfc"]);
		$txt_noReq = strtoupper($_POST["txt_noReq"]);
		$txa_condEnt = strtoupper($_POST["txa_condEnt"]);
		$cmb_condPago = strtoupper($_POST["cmb_condPago"]);
		$txt_plazo = strtoupper($_POST["txt_plazo"])." ".strtoupper($_POST["cmb_plazo"]);
		$cmb_solicito = $_POST["cmb_solicito"];
		//Extraer los errores en un arreglo usando como separador el +
		$solicito = split("-",$cmb_solicito);
		$txt_reviso = strtoupper($_POST["txt_reviso"]);
		$txt_autorizo = strtoupper($_POST["txt_autorizo"]);
		$txa_comentarios = strtoupper($_POST["txa_comentarios"]);
		$cmb_plazo = $_POST["cmb_plazo"];
		$cmb_viaPed = $_POST["cmb_viaPed"];
		$cmb_moneda = $_POST["cmb_tipoMoneda"];

		//Crear la sentencia para realizar el registro del Pedido en la BD de Compras en la tabla de Pedido
		$stm_sql = "INSERT INTO pedido(id_pedido,proveedores_rfc,requisiciones_id_requisicion,cond_entrega,cond_pago,plazo_entrega,fecha,subtotal,
					iva,pctje_descto,total,tipo_moneda,solicitor,revisor,autorizador,comentarios,via_pedido,estado,depto_solicitor,forma_pago,impreso)
					VALUES('$txt_noPedido','$txt_rfc','$txt_noReq','$txa_condEnt','$cmb_condPago','$txt_plazo','$fecha',$_POST[txt_subtotal],
					$_POST[txt_iva],$descto,$_POST[txt_total],'$cmb_moneda','$solicito[1]','$txt_reviso','$txt_autorizo','$txa_comentarios','$cmb_viaPed','NO PAGADO','$solicito[0]','',0)";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Confirmar que la inserci�n de datos fue realizada con exito.
		if($rs){		
			//Guardar la descripci�n de la operacion realizada
			registrarOperacion("bd_compras",$txt_noPedido,"RegistrarPedido",$_SESSION['usr_reg']);
			
			//Registrar las partidas del Pedido cuando fueron registradas manualmente y se encuentran almacenadas en la SESSION
			if(isset($_SESSION["detallespedido"]))
				registrarDetallesPedido($txt_noPedido,$_POST["txt_lblIVA"],$_POST["hdn_ivaIncluido"]);
			else//Registrar las partidas del Pedido, cuando �ste es generado a partir de una Requisici�n
				registrarDetallesPedido2($txt_noPedido,$_POST["hdn_base"],$_POST["txt_lblIVA"],$_POST["hdn_ivaIncluido"]);
		}
		else{			
			$error = mysql_error();			
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}		
		//La Conexion a la BD se cierra en la funcion registrarOperacion();	
	}//Cierre de la funci�n registraPedido()
	
	
	//Esta funcion agrega los detalles del Pedido a la BD mediante el arreglo de SESSION
	function registrarDetallesPedido($id_pedido,$iva,$ivaIncluido){
		//Registrar todos los materiales dados de alta en el arreglo $detallespedido
		foreach($_SESSION['detallespedido'] as $ind => $concepto){
			//Conectar a la BD que corresponde
			$conn = conecta("bd_compras");
		
			//Variable que permite abortar si se han generado errores
			$band = 0;
			
			if($ivaIncluido=="NO"){
				//Buscar "comas [,]" en valores numericos y removerlas
				if (strlen($concepto["precioU"])>6)
					$concepto["precioU"]=str_replace(",","",$concepto["precioU"]);
				if (strlen($concepto["importe"])>6)
					$concepto["importe"]=str_replace(",","",$concepto["importe"]);
			}
			else if($ivaIncluido=="SI"){
				//Importe de la venta por partida
				$concepto["importe"]=str_replace(",","",$concepto["importe"]) / (1+(str_replace("%","",$iva)/100));
				//Precio Unitario
				$concepto["precioU"]=$concepto["importe"]/$concepto["cantidad"];
			}
			//Crear la sentencia para realizar el registro de los datos del detalle de los Pedidos
			$stm_sql = "INSERT INTO detalles_pedido (pedido_id_pedido,partida,unidad,cantidad,descripcion,equipo,precio_unitario,importe)
						VALUES('$id_pedido','$concepto[partida]','$concepto[unidad]','$concepto[cantidad]','$concepto[descripcion]',
						'$concepto[equipo]','$concepto[precioU]','$concepto[importe]')";
			//Ejecutar la sentencia previamente creada para agregar cada concepto a la tabla de Detalles de Pedido
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;
			//Cerramos la conexion a la BD del departamento que tiene la requisicion
			mysql_close($conn);
			$req = $_POST["txt_noReq"];
			actualizarMatReq("bd_almacen",$req,$concepto["descripcion"]);
		}
		
		//Si band permanece en 0, todos los datos sem agregaron correctamente.
		if ($band==0){?>
			<script type='text/javascript' language='javascript'>
				//Crear el Codigo Javascript para abrir la ventana emergente con el PDF del Pedido
				var codAbrirPedido = "window.open('../../includes/generadorPDF/pedido2.php?id=<?php echo $id_pedido; ?>', '_blank', ";
				codAbrirPedido += "'top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')";				

				//Retrasar la apertura de la ventana 2 segundos
				setTimeout(codAbrirPedido,2000);
				setTimeout(codAbrirPedido,3000);
			</script><?php
			echo "<meta http-equiv='refresh' content='6;url=exito.php'>";
			//Reabrir la conexion a la bd que corresponde
			$conexion = conecta("bd_almacen");
			//Verificar si TODOS los materiales de la Requisicion, ya fueron pedidos, de ser asi, modificar el estado de la Requisicion
			$stm_sql = "SELECT mat_pedido FROM detalle_requisicion WHERE requisiciones_id_requisicion='$req' AND mat_pedido='1'";
			//Ejecutar la sentencia de verificacion de Materiales NO PEDIDOS
			$rs=mysql_query($stm_sql);
			//Si no regresa resultados, Todos los Materiales ya se pidieron
			if(mysql_num_rows($rs)==0){			
				//Actualizamos el estado de la Requisicion que se ha Pedido
				$stm_sql = "UPDATE requisiciones SET estado='PEDIDO' WHERE id_requisicion='$req'";
				//Ejecutar la sentencia de actualizacion de estados para la Requisicion
				$rs = mysql_query($stm_sql);
			}
			//Nos aseguramos de cerrar la conexion
			mysql_close($conexion);
		}
	}//Cierre de la funci�n registrarDetallesPedido($id_pedido,$iva,$ivaIncluido)
	
	
	//Esta funcion agrega las partidas del detalle del pedido, cuando este es generado a partir de una requisicion
	function registrarDetallesPedido2($id_pedido,$base,$iva,$ivaIncluido){
		//Conectar a la BD que corresponde
		$conn2 = conecta($base);
		//Variable que permite abortar si se han generado errores
		$band = 0;
		//Variable que controla la cantidad de Partidas del Pedido en la requisicion
		$partida = 1;
		//Variable que obtiene el numero de Requisicion
		$req = $_POST["txt_noReq"];	
		//Crear la sentencia para obtener los materiales incluidos en la Requisicion seleccionada
		$stm_sql = "SELECT cant_req, unidad_medida, descripcion FROM detalle_requisicion WHERE requisiciones_id_requisicion = '$req' AND mat_pedido='1'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Cerramos la conexion a la BD del departamento que tiene la requisicion
		mysql_close($conn2);				
		//Verificar si la consulta arrojo datos
		if($datos = mysql_fetch_array($rs)){
			//Variable para almacenar el precio unitario de las piezas en la Requisicion
			$precio = 0;
			//Variable para capturar el Equipo que hace aplicacion al Material Solicitado
			$equipo = "";
			//Variable para controlar los registros, ya que se usaron variable hidden con un numero para identificar a que Material se le asigna que precio
			//es necesario ejecutar la misma sentencia a fin de extraer los datos de la BD en el mismo orden
			$ctrl=1;
			do{
				//Verificar si esta definido hdn_cantidad en la posicion actual, de esta manera, se revisa que partida de la requisicion es la que se va a agregar al Pedido
				if(isset($_POST["hdn_cantidad$ctrl"])){
					//Abrimos la conexion a la BD de Compras, esta la abrimos para registrar el Detalle de Pedido de uno en vez, se cierra para poder cambiar el estado del Material en cada ciclo
					$conn = conecta("bd_compras");	
					$cant_ped=$_POST["hdn_cantPedida$ctrl"];
					$descripcion=strtoupper($_POST["descMat$ctrl"]);
					$unidad=strtoupper($_POST["txt_uniMat$ctrl"]);
					$equipo=$_POST["hdn_equipo$ctrl"];
					$precio=$_POST["hdn_cantidad$ctrl"];
					$partidaReq=$_POST["hdn_partidaReq$ctrl"];
					if($_POST["hdn_control$ctrl"] == "") $control="N/A"; else $control=$_POST["hdn_control$ctrl"];
					if($_POST["hdn_cuenta$ctrl"] == "") $cuenta="N/A"; else $cuenta=$_POST["hdn_cuenta$ctrl"];
					if($_POST["hdn_subcuenta$ctrl"] == "") $subcuenta="N/A"; else $subcuenta=$_POST["hdn_subcuenta$ctrl"];
					//Verificar si esta incluido o NO el IVA
					if($ivaIncluido=="NO"){
						//Si el precio es mas largo de 6 digitos, estan incluidas "comas [,] " es necesario removerlas para que no �provoquen problemas con las operaciones
						if (strlen($precio)>6)
							$precio = str_replace(",","",$precio);
						//El importe se calcula en base al precio unitario por la cantidad solicitada
						$importe = $precio * $cant_ped;
					}
					else if($ivaIncluido=="SI"){
						//Importe de la venta por partida
						$precio = (str_replace(",","",$precio)) / (1+(str_replace("%","",$iva)/100));
						//Precio Unitario
						$importe = $precio * $cant_ped;
					}
					//Crear la sentencia de insercion de datos al detalle de Pedido
					$stm_sql = "INSERT INTO detalles_pedido (pedido_id_pedido,partida,unidad,cantidad,descripcion,equipo,precio_unitario,importe,id_control_costos,id_cuentas,id_subcuentas,cantidad_real,partida_requisicion)
					VALUES('$id_pedido','$partida','$unidad','$cant_ped','$descripcion','$equipo','$precio','$importe','$control','$cuenta','$subcuenta','$cant_ped',$partidaReq)";
					//Se incrementa el valor de la partida en 1
					$partida++;
					//Ejecutar la sentencia para insertar los datos de detalle de Pedido
					$rs2 = mysql_query($stm_sql);
					//Verificar si los datos fueron insertados con exito
					if(!$rs2){
						$band = 1;
						break;//Romper el ciclo en el caso de que se marque un error
					}
					//Cerramos la conexion a la BD de Compras para registrar los Pedidos
					mysql_close($conn);
					//Actuaizar el Material en la Requisicion
					actualizarParReq($base,$req,$partidaReq);
				}
				$ctrl++;
			}while($datos=mysql_fetch_array($rs));
		}//Cierre if($datos = mysql_fetch_array($rs))
		//Si no hubo errores en la Insercion
		if ($band!=1){
			echo "<meta http-equiv='refresh' content='6;url=exito.php'>";?>
			<script type='text/javascript' language='javascript'>
				//Crear el Codigo Javascript para abrir la ventana emergente con el PDF del Pedido
				var codAbrirPedido = "window.open('../../includes/generadorPDF/pedido2.php?id=<?php echo $id_pedido; ?>', '_blank', ";
				codAbrirPedido += "'top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')";
				//Retrasar la apertura de la ventana 2 segundos
				setTimeout(codAbrirPedido,2000);
				setTimeout(codAbrirPedido,3000);
			</script><?php
			//Reabrir la conexion a la bd que corresponde
			$conexion = conecta($base);
			//Verificar si TODOS los materiales de la Requisicion, ya fueron pedidos, de ser asi, modificar el estado de la Requisicion
			$stm_sql = "SELECT mat_pedido FROM detalle_requisicion WHERE requisiciones_id_requisicion='$req' AND mat_pedido='1'";
			//Ejecutar la sentencia de verificacion de Materiales NO PEDIDOS
			$rs=mysql_query($stm_sql);
			//Si no regresa resultados, Todos los Materiales ya se pidieron
			if(mysql_num_rows($rs)==0){
				//Actualizamos el estado de la Requisicion que se ha Pedido
				$stm_sql = "UPDATE requisiciones SET estado='PEDIDO' WHERE id_requisicion='$req'";
				//Ejecutar la sentencia de actualizacion de estados para la Requisicion
				$rs = mysql_query($stm_sql);
			}
			//Nos aseguramos de cerrar la conexion
			mysql_close($conexion);
		}//Cierre if ($band!=1)
		else{
			$error = mysql_error();			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Cierre de la funcion function registrarDetallesPedido2($id_pedido,$base,$iva,$ivaIncluido)
	
	//Funcion que actualiza el estado de cada Material en la Requisicion
	function actualizarMatReq($base,$req,$material){
		//Abrir conexion
		$conn=conecta($base);
		//Sentencia que actualiza el estado de los Materiales en la Requisicion
		$sql_stm2="UPDATE detalle_requisicion SET mat_pedido='2' WHERE requisiciones_id_requisicion='$req' AND descripcion='$material'";
		//Ejecutar la sentencia de actualizacion de Material
		$rs2=mysql_query($sql_stm2);
		//Cerrar conexion
		mysql_close($conn);
	}
	
	function actualizarParReq($base,$req,$partida){
		//Abrir conexion
		$conn=conecta($base);
		//Sentencia que actualiza el estado de los Materiales en la Requisicion
		$sql_stm2="UPDATE detalle_requisicion SET mat_pedido='2',estado='2' WHERE requisiciones_id_requisicion='$req' AND partida='$partida'";
		//Ejecutar la sentencia de actualizacion de Material
		$rs2=mysql_query($sql_stm2);
		//Cerrar conexion
		mysql_close($conn);
	}
	
	//Esta funcion se llama al redireccionar desde un pedido
	function agregarDetallesPedido(){
		//Obtener el numero de la Requisicion
		$requisicion = $_POST["hdn_numero"];
		//Conectar a la BD que corresponde, este dato llega por un Hidden
		$conn = conecta($_POST["hdn_bd"]);
		//Crear sentencia SQL
		$stm_sql = "SELECT cant_req,unidad_medida,materiales_id_material,descripcion,aplicacion,partida,precio_unit,id_control_costos,id_cuentas,id_subcuentas,id_equipo FROM detalle_requisicion WHERE requisiciones_id_requisicion='$requisicion' AND mat_pedido='1'";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			?>
				<script type="text/javascript" language="javascript">
					
				</script>
			<?php
			//Variable que acumula el subtotal
			$subtotal=0;
			echo "<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>REQUISICI&Oacute;N ".$requisicion."</caption></br>";
			
			if($datos["id_control_costos"] != "N/A" && $datos["id_control_costos"] != ""){
				echo "<tr>
						<td class='nombres_columnas' align='center' colspan='2'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>EQUIPO</td>
						<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
						<td class='nombres_columnas' align='center'>IMPORTE</td>
					</tr>";
			} else {
				echo "<tr>
						<td class='nombres_columnas' align='center' colspan='2'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>EQUIPO</td>
						<td class='nombres_columnas' align='center'>CONTROL DE COSTOS</td>
						<td class='nombres_columnas' align='center'>CUENTAS</td>
						<td class='nombres_columnas' align='center'>SUBCUENTAS</td>
						<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
						<td class='nombres_columnas' align='center'>IMPORTE</td>
					</tr>";
			}
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				if ($datos["materiales_id_material"] == 'CDIESEL' || $datos["materiales_id_material"] == 'MAGNA' || $datos["materiales_id_material"] == '´PREMIUM') {
					?>
					<script>
						document.getElementById("hdn_editable").value="SI";
					</script>
					<?php
				}
				echo "
					<tr>";
						?>
						<td align="center" class="<?php echo $nom_clase?>">
						<?php echo "$cont.-";?>
						</td>
						<td align="center" class="<?php echo $nom_clase?>">
							<input type="checkbox" name="ckb_pieza<?php echo $cont?>" id="ckb_pieza<?php echo $cont?>" onclick="elegirMaterial(<?php echo $cont?>);" tabindex="<?php echo $cont?>" >
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
				echo "
						<td class='$nom_clase' align='center'>
					 ";
							?>
							<input type="text" name="hdn_cantReq<?php echo $cont; ?>" id="hdn_cantReq<?php echo $cont; ?>" autocomplete="off"
							<?php 
							if(isset($_SESSION["detalle_pedido"])){ 
								if($_SESSION["detalle_pedido"][$cont-1]["cant_ped"]!=''){
									echo "value='".$_SESSION['detalle_pedido'][$cont-1]['cant_ped']."'";
								} else {
									echo "value='$datos[cant_req]'";
								}
							} else {
								echo "value='$datos[cant_req]'";
							}?> class="caja_de_num" readonly="true" onChange="operacionesPedido(<?php echo $cont; ?>,'uni');" style="width: 60px;" />
							<?php
				echo "	</td>";
						?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="text" class="caja_de_num" readonly name="txt_uniMat<?php echo $cont; ?>" id="txt_uniMat<?php echo $cont; ?>" autocomplete="off" onChange="operacionesPedido(<?php echo $cont; ?>,'uni');" size="10" style="text-transform:uppercase" maxlength="7"
							<?php 
							if(isset($_SESSION["detalle_pedido"])){ 
								if($_SESSION["detalle_pedido"][$cont-1]["unidad"]!=''){
									echo "value='".$_SESSION['detalle_pedido'][$cont-1]['unidad']."'";
								} else {
									echo "value='$datos[unidad_medida]'";
								}
							} else {
								echo "value='$datos[unidad_medida]'";
							}?>
							>
						</td>
						<?php
				echo "	<!-- <td class='$nom_clase' align='center'>$datos[unidad_medida]</td> -->
						<td class='$nom_clase' align='center'>";
							?>
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
							<?php
							if ($datos["id_equipo"] != "N/A") {
								$aplicacion = $datos["id_equipo"];
							} else {
								if (substr($datos["aplicacion"], 0, 3) == "CAT") {
									$aplicacion = strtoupper(obtenerDatoTabla('categorias_mat','id_categoria',$datos["aplicacion"],"bd_almacen"));
								} else if ($datos["aplicacion"] != "N/A") {
									$aplicacion = $datos["aplicacion"];
								} else {
									$aplicacion = obtenerCentroCosto('control_costos','id_control_costos',$datos['id_control_costos']);
								}
							}
						
				echo "	</td>
						<td class='$nom_clase' align='center'>$aplicacion</td>
						<td class='$nom_clase' align='center'>";
						//Abrir conexion para traer los Equipos de Mtto
						$conn1 = conecta("bd_mantenimiento");//Conectarse con la BD de Mantenimiento
						//Ejecutar la Sentencia para Obtener los Equipos de la Base de Datos de Mantenimiento
						$rs_equipos = mysql_query("SELECT DISTINCT id_equipo FROM equipos WHERE estado='ACTIVO' ORDER BY id_equipo");
						if($equipos=mysql_fetch_array($rs_equipos)){?>
							<select name="cmb_equipos<?php echo $cont;?>" id="cmb_equipos<?php echo $cont;?>" class="combo_box" 
							onchange="agregarNvoEquipo(this); cargarCuentas_Equipo(this.value,'cmb_con_cos<?php echo $cont;?>','cmb_cuenta<?php echo $cont;?>','cmb_subcuenta<?php echo $cont;?>','hdn_control<?php echo $cont;?>','hdn_cuentas<?php echo $cont;?>');" disabled="disabled">
								<option value="">Equipos</option><?php
								//Colocar los Equipos encontradas 
								do{
									if(isset($_SESSION["detalle_pedido"])){
										if($_SESSION["detalle_pedido"][$cont-1]["equipo"]==$equipos['id_equipo']){
											echo "<option selected='selected' value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
										}
										else{
											echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
										}
									} else{
										if ($datos["id_equipo"] != "N/A") {
											if ($datos["id_equipo"] == $equipos["id_equipo"]) {
												echo "<option selected='selected' value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
											} else {
												echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
											}
										} else {
											if ($datos["aplicacion"] == $equipos["id_equipo"]) {
												echo "<option selected='selected' value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
											} else {
												echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
											}
										}
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
						<?php
						if($datos["id_control_costos"] != "N/A" && $datos["id_control_costos"] != ""){
							?>
							<input type="hidden" id="cmb_con_cos<?php echo $cont;?>" name="cmb_con_cos<?php echo $cont;?>" value="<?php echo $datos["id_control_costos"]; ?>" disabled="disabled"/>
							<?php
						} else {
						?>
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
													echo "<option value='$datos_rec[id_control_costos]'>$datos_rec[descripcion]</option>";
												}
											} else{
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
									}
								}
								echo "<input type='hidden' name='hdn_control$cont' id='hdn_control$cont' value=''/>";
								echo "<input type='hidden' name='hdn_cuentas$cont' id='hdn_cuentas$cont' value=''/>";
						?>
							</td>
						<?php
						}
						if($datos["id_cuentas"] != "N/A" && $datos["id_cuentas"] != ""){
							?>
							<input type="hidden" id="cmb_cuenta<?php echo $cont;?>" name="cmb_cuenta<?php echo $cont;?>" value="<?php echo $datos["id_cuentas"]; ?>" disabled="disabled"/>
							<?php
						} else {
						?>
							<td>
								<span id="datosCuenta">
									<select name="cmb_cuenta<?php echo $cont;?>" id="cmb_cuenta<?php echo $cont;?>" class="combo_box" onchange="cargarSubCuentas(cmb_con_cos<?php echo $cont;?>.value,this.value,'cmb_subcuenta<?php echo $cont;?>')" disabled="disabled">
										<option value="">Cuentas</option>
									</select>
								</span>
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
									}
								}
						?>
							</td>
						<?php
						}
						if($datos["id_control_costos"] != "N/A" && $datos["id_control_costos"] != ""){
							?>
							<input type="hidden" id="cmb_subcuenta<?php echo $cont;?>" name="cmb_subcuenta<?php echo $cont;?>" value="<?php echo $datos["id_subcuentas"]; ?>" disabled="disabled"/>
							<?php
						} else {
						?>
							<td>
								<span id="datosSubCuenta">
									<select name="cmb_subcuenta<?php echo $cont;?>" id="cmb_subcuenta<?php echo $cont;?>" class="combo_box" disabled="disabled">
										<option value="">SubCuentas</option>
									</select>
								</span>
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
									}
								}
						?>
							</td>
						<?php
						}
						?>
						<td class="<?php echo $nom_clase; ?>" align='center' width="100px">
							$<input name="txt_precio<?php echo $cont;?>" type="text" id="txt_precio<?php echo $cont;?>" class="caja_de_num" size="10" autocomplete="off"
							maxlength="10" onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'uni');" readonly="readonly" 
							<?php 
							if(isset($_SESSION["detalle_pedido"])){ 
								if($_SESSION["detalle_pedido"][$cont-1]["precio"]!=''){
									echo "value='".$_SESSION['detalle_pedido'][$cont-1]['precio']."'";
								}
							} else {
								echo "value='".$datos['precio_unit']."'";
							}?> />
						</td>
						<td class="<?php echo $nom_clase; ?>" align='center' width="100px">
							$<input name="txt_importe<?php echo $cont;?>" type="text" id="txt_importe<?php echo $cont;?>" class="caja_de_num" size="10" autocomplete="off"
							maxlength="10" onChange="formatCurrency(this.value.replace(/,/g,''),this.name);operacionesPedido(<?php echo $cont;?>,'imp');" readonly="readonly"/>
						</td>
					</tr>
					<?php
					//if(isset($_SESSION["detalle_pedido"])){
						//if($_SESSION["detalle_pedido"][$cont-1]["precio"]!=''){
						?>
							<script>
								objeto_precio<?php echo $cont; ?> = document.getElementById("txt_precio<?php echo $cont?>");
								setTimeout("objeto_precio<?php echo $cont?>.onchange()",3000);
							</script>
						<?php
						//}
					//}
					echo "<input type='hidden' value='$datos[partida]' name='txt_partidaReq$cont' id='txt_partidaReq$cont'/>";
				//Obtener datos a agregar al arreglo de Session
				$unidadM = $datos["unidad_medida"];
				$cantidad = $datos["cant_req"];
				$desc = $datos["descripcion"];
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "
				<tr>
					<td colspan='8' align='right'><strong>SUBTOTAL</strong></td>";?>
					<td align='center'>
						<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo $cont-1;?>"/>
						$<input type='text' name='txt_subtotal' id='txt_subtotal' class='caja_de_num' size='10' 
            			onClick="formatCurrency(value.replace(/,/g,''),'txt_subtotal');" tabindex="<?php echo $cont;?>" 
						onBlur="formatCurrency(value.replace(/,/g,''),'txt_subtotal');" value="0.00"/>
					</td>
					<?php
					echo "<input type='hidden' name='hdn_requisicion' id='hdn_requisicion' value='$requisicion'/>";
					echo "<input type='hidden' name='hdn_bd' id='hdn_bd' value='$_POST[hdn_bd]'/>";
					echo "<input type='hidden' name='hdn_cantidad' id='hdn_cantidad' value='$cont'/>";
					$depto=$_GET["depto"];
					echo "<input type='hidden' name='hdn_depto' id='hdn_depto' value='$depto'/>
				</tr>
				<tr>
					<td colspan='8' align='right'><strong>DESCUENTO</strong></td>";?>
					<td align='center'>
						<input type="text" name="txt_descto" id="txt_descto" class="caja_de_num" value="0.00" size="6" maxlength="6" 
						onkeypress="return permite(event,'num_car', 0);" onchange="validarDescto(this);calcularDesctoSobrePedido(0);formatCurrency(value.replace(/,/g,''),'txt_descto');"/>%
					</td>
					<?php
					echo "
				</tr>
			</table>";
			
			
			//Obtener las observaciones agregadas a la requisici�n de la cual ser� generado un pedido
			$comentarios = obtenerDato($_POST["hdn_bd"], "requisiciones", "observaciones", "id_requisicion", $requisicion)?>
			<table cellpadding="5" width="850" align="center" class="tabla_frm"><?php
				//Mostrar los comentarios agregados en la Requisici�n, cuando existan
				if($comentarios!=""){?>				
					<tr>
						<td colspan="6"><strong>COMENTARIOS: </strong><?php echo $comentarios; ?></td>
					</tr><?php
				}?>
				<tr>					
					<td align="right" valign="top" colspan="2">
						<strong>PROVEEDOR</strong>
					</td>
					<td colspan="4">
						<input type="text" name="txt_nomProveedor" id="txt_nomProveedor" onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');" 
						value="" size="50" maxlength="80" onkeypress="return permite(event,'num_car', 0);" tabindex="6"/>
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
		//Cerar conexion a BD
		//mysql_close($conn);		
	}
	
	
	//Esta funci�n obtiene el total de las partidas registras en el Arreglo de SESSION, cuando estas son registradas manualmente
	function obtenerSubtotal(){
		$subtotal=0;
		foreach ($_SESSION['detallespedido'] as $ind => $concepto){
			if (strlen($concepto["importe"])>6)
				$subtotal+=str_replace(",","",$concepto["importe"]);
			else
				$subtotal+=$concepto["importe"];
		}
		return $subtotal;
	}//Cierre de la funci�n obtenerSubtotal()
	
	
	
	function mostrarDetallesPedido($detallespedido){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Detalles registrados del Pedido: ".$_SESSION['detallespedido'][0]['id_pedido']."</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>PARTIDA</td>
        		<td class='nombres_columnas' align='center'>UNIDAD</td>
			    <td class='nombres_columnas' align='center'>CANTIDAD</td>
				<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
				<td class='nombres_columnas' align='center'>EQUIPOS</td>
				<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
				<td class='nombres_columnas' align='center'>IMPORTE</td>			
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($detallespedido as $ind => $detalle) {
			echo "<tr>";
			foreach ($detalle as $key => $value) {
				switch($key){
					case "partida":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "unidad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "cantidad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "descripcion":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "equipo":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "precioU":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "importe":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;									
				}				
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion mostrarRegistros($datosSalida)
	
	
	//Esta funcion guarda en variables HIDDEN los datos de las partidas(Costo Unitario y Equipo Asociado) de PEDIDO que fueron creadas a partir de los registros de una Requisici�n
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
				$unitMat=$_POST["txt_uniMat$ctrl"];
				$precio=$_POST["txt_precio$ctrl"];
				$equipo=$_POST["cmb_equipos$ctrl"];
				$control_costos=$_POST["cmb_con_cos$ctrl"];
				$cuenta=$_POST["cmb_cuenta$ctrl"];
				$subcuenta=$_POST["cmb_subcuenta$ctrl"];
				$partida=$_POST["txt_partidaReq$ctrl"];
				echo "<input type='hidden' name='hdn_cantPedida$ctrl' id='hdn_cantPedida$ctrl' value='$cant_ped'/>";
				echo "<input type='hidden' name='descMat$ctrl' id='descMat$ctrl' value='$descripcion'/>";
				echo "<input type='hidden' name='txt_uniMat$ctrl' id='txt_uniMat$ctrl' value='$unitMat'/>";
				echo "<input type='hidden' name='hdn_cantidad$ctrl' id='hdn_cantidad$ctrl' value='$precio'/>";
				echo "<input type='hidden' name='hdn_equipo$ctrl' id='hdn_equipo$ctrl' value='$equipo'/>";
				echo "<input type='hidden' name='hdn_control$ctrl' id='hdn_control$ctrl' value='$control_costos'/>";
				echo "<input type='hidden' name='hdn_cuenta$ctrl' id='hdn_cuenta$ctrl' value='$cuenta'/>";
				echo "<input type='hidden' name='hdn_subcuenta$ctrl' id='hdn_subcuenta$ctrl' value='$subcuenta'/>";
				echo "<input type='hidden' name='hdn_partidaReq$ctrl' id='hdn_partidaReq$ctrl' value='$partida'/>";
				if(isset($_SESSION["detalle_pedido"])){
					$detallePedido[] = array("seleccionado"=>'1',"cant_ped"=>$cant_ped,"unidad"=>$unitMat,"descripcion"=>$descripcion,"precio"=>$precio,"equipo"=>$equipo,"control_costos"=>$control_costos,"cuenta"=>$cuenta,"subcuenta"=>$subcuenta, "partida"=>$partida);
				} else {
					$detallePedido = array(array("seleccionado"=>'1',"cant_ped"=>$cant_ped,"unidad"=>$unitMat,"descripcion"=>$descripcion,"precio"=>$precio,"equipo"=>$equipo,"control_costos"=>$control_costos,"cuenta"=>$cuenta,"subcuenta"=>$subcuenta, "partida"=>$partida));
				}
				//Guardar el arreglo en la SESSION
				$_SESSION['detalle_pedido'] = $detallePedido;
			} else {
				if(isset($_SESSION["detalle_pedido"])){
					$detallePedido[] = array("seleccionado"=>'0',"cant_ped"=>'',"unidad"=>'',"descripcion"=>'',"precio"=>'',"equipo"=>'',"control_costos"=>'',"cuenta"=>'',"subcuenta"=>'', "partida"=>'');
				} else {
					$detallePedido = array(array("seleccionado"=>'0',"cant_ped"=>'',"unidad"=>'',"descripcion"=>'',"precio"=>'',"equipo"=>'',"control_costos"=>'',"cuenta"=>'',"subcuenta"=>'', "partida"=>''));
				}
				//Guardar el arreglo en la SESSION
				$_SESSION['detalle_pedido'] = $detallePedido;
			}
			$ctrl++;
		}while($ctrl<=$tam);
	}//Fin de la Funcion obtenerPrecioUnitReq()
	
	
	//Esta funci�n se encarga de generar el Id de la Requisicion de acurdo a los registros existentes en la BD
	function obtenerIdPedido(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		//Definir las tres letras en la Id del Pedido
		$id_cadena = "PED";
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el a�o actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el Pedido Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_pedido) AS cant FROM pedido WHERE id_pedido LIKE 'PED$mes$anio%'";
		
		$stm_sql = "SELECT MAX( CAST( SUBSTR( id_pedido, 8 ) AS UNSIGNED ) ) AS cant
					FROM pedido
					WHERE id_pedido LIKE  'PED$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras del Pedido Registrado en la BD y sumarle 1
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "00".$cant;
			if($cant>99 && $cant<1000)
				$id_cadena .= "0".$cant;
			if($cant>=1000)
				$id_cadena .= $cant;
		}
			
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdPedido()	
	
	
	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica
	function obtenerDato2($nom_tabla, $campo_bus, $param_bus, $dato_bus){
		//Conectarse con la BD de Compras
		$conn = conecta("bd_compras");
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE $param_bus='$dato_bus'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
	
	//Esta funcion permite el registro del complemento de Detalles de Pedido
	function complementarDetalles(){
		//Conectarse con la BD de Compras
		$conn = conecta("bd_compras");
		$fechaE="";
		if (isset($_POST["txt_fechaE"]))
			//Obtener la fecha en Formato YYYY-MM-DD
			$fechaE=modFecha($_POST["txt_fechaE"],3);
		$horaE="";
		if (isset($_POST["txt_horaE"])){
			//Manejamos la Hora en una cadena de tipo hh:mm AM/PM para que se convierta al formato soportado por MySQL
			$horaE=$_POST["txt_horaE"]." ".$_POST["cmb_hora"];
			//ESPACIO IMPORTANTE--------^
			//Modificar la hora a formato 24hrs
			$horaE=modHora24($horaE);
		}
		$fechaP="";
		//Si txt_fechaP es diferente de vacio hacer la operacion de conversion
		if ($_POST["txt_fechaP"]!="")
			//Obtener la fecha en Formato YYYY-MM-DD
			$fechaP=modFecha($_POST["txt_fechaP"],3);
		//Obtener el estado asignado al Pedido
		$estado=$_POST["cmb_estado"];
		//Obtener el ID del Pedido
		$pedido=$_POST["hdn_pedido"];
		//Obtener la Forma de Pago
		$formaPago=$_POST["cmb_formaPago"];
		
		if (isset($_POST["txt_fechaE"]))
			//Crear la sentencia que actualiza los datos del Pedido Seleccionado
			$stm_sql = "UPDATE pedido SET fecha_entrega='$fechaE',hora_entrega='$horaE',fecha_pago='$fechaP',estado='$estado',forma_pago='$formaPago' WHERE id_pedido='$pedido'";
		else
			//Crear la sentencia que actualiza los datos del Pedido Seleccionado
			$stm_sql = "UPDATE pedido SET fecha_entrega='$fechaE',estado='$estado',forma_pago='$formaPago' WHERE id_pedido='$pedido'";
		
		$rs = mysql_query($stm_sql);
		if ($rs){
			registrarOperacion("bd_compras",$pedido,"ComplementarPedido",$_SESSION['usr_reg']);
			echo"<meta http-equiv='refresh' content='0;url=exito.php'>";}
			
		else{
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		//Cerrar la conexion con la BD		
	//La Conexion a la BD se cierra en la funcion registrarOperacion("bd_compras",$txt_rfc,"AgregarCliente",$_SESSION['usr_reg']);	
	}
	
	
	//Esta funci�n verifica que no se duplique un registro en el arreglo que guarda los datos de Registrar Pedido
	function verRegDuplicado($arr,$campo_clave,$campo_ref){
		$tam = count($arr);		
		$datos = $arr[$tam-1];
		if($datos[$campo_clave]==$campo_ref)
			return true;
		else 
			return false;
	}//Cierre de la funci�n verRegDuplicado($arr,$campo_clave,$campo_ref)
	
	function obtenerCentroCosto($tabla,$busq,$valor){
		$dat = $valor; 
		$con = conecta("bd_recursos");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		mysql_close($con);
		return $dat;
	}
	
	function obtenerDatoTabla($tabla,$busq,$valor,$bd){
		$dat = $valor; 
		$con = conecta("$bd");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		//mysql_close($con);
		return $dat;
	}
?>
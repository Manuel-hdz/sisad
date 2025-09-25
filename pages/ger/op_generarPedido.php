<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 19/Julio/2011                                     			
	  * Descripción: Este archivo contiene funciones para Generar los Pedidos que competen a Gerencia Técnica 
	  **/
	
	//Funcion que muestra los datos de la Requisicion Recien Generada
	function mostrarRequisicion($idRequisicion){
		//Conexion a la BD para recuperar los Datos de la Requisicion
		$conec=conecta("bd_gerencia");
		//Si la base de datos no existe, redirecciona a la página de construccion
		if (!$conec){
			echo "<meta http-equiv='refresh' content='0;url=construccion.php'>";
		}
		else{
			//funcion Javascript para pasar el Foco al primer elemento
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("document.getElementById('txt_precio1').focus()",500);
			</script>
			<?php
			//Sentencia SQL con los datos de la requisicion a buscar
			$stm_sql="SELECT materiales_id_material, cant_req, unidad_medida, descripcion, aplicacion FROM detalle_requisicion WHERE requisiciones_id_requisicion='$idRequisicion'";
			$rs=mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				//Desplegar los resultados de la consulta en una tabla
				echo "				
					<table cellpadding='5' width='100%'>      			
					<tr>
					<td colspan='6' align='center' class='titulo_etiqueta'>Materiales de la Requisici&oacute;n $idRequisicion</td>
					</tr>
					<tr>
							<td class='nombres_columnas' align='center'>NO.</td>
							<td class='nombres_columnas' align='center'>CANTIDAD</td>
							<td class='nombres_columnas' align='center'>UNIDAD</td>
							<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
							<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
							<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
							<td class='nombres_columnas' align='center'>IMPORTE</td>
						</tr>";
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{	
						echo "	<tr>
								<td class='nombres_filas' align='center'><strong>$cont.-</strong></td>
								<td class='nombres_filas' align='center'>$datos[cant_req]</td>
								<input name='hdn_id$cont' type='hidden' value='$datos[materiales_id_material]'/>
								<input name='hdn_cantidad$cont' type='hidden' value='$datos[cant_req]'/>
								<td class='$nom_clase' align='center'>$datos[unidad_medida]</td>
								<td class='$nom_clase' align='center'>$datos[descripcion]</td>
								<td class='$nom_clase' align='center'>$datos[aplicacion]</td>";?>
								<td class='<?php echo $nom_clase?>' align='center'>
									<input name="txt_precio<?php echo $cont;?>" type="text" id="txt_precio<?php echo $cont;?>" 
	                       			class="caja_de_num" size="10" maxlength="10" 
									onChange="formatCurrency(value.replace(/,/g,''),'txt_precio<?php echo $cont;?>');formatCurrency(txt_precio<?php echo $cont;?>.value.replace(/,/g,'')*<?php echo $datos["cant_req"];?>,'txt_importe<?php echo $cont;?>');sumaImporte();" tabindex="<?php echo $cont?>"/>
								</td>
								<td class='<?php echo $nom_clase?>' align='center'>
									<input name="txt_importe" type="text" id="txt_importe<?php echo $cont;?>" 
    	                    		class="caja_de_num" size="10" readonly="true" maxlength="10" onBlur="sumaImporte();"/>
								</td>
							<?php echo "</tr>";
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
							
					}while($datos=mysql_fetch_array($rs));
					echo "<td colspan='6' align='right'><strong>SUBTOTAL</strong></td>";
					?><td align='center'><input type='text' name='txt_subtotal' id='txt_subtotal' class='caja_de_num' size='10' 
		            onClick="formatCurrency(value.replace(/,/g,''),'txt_subtotal');" onBlur="formatCurrency(value.replace(/,/g,''),'txt_subtotal');" readonly="readonly"/></td><?php
					echo "<input type='hidden' name='hdn_partidas' value='$cont'/>";
				echo "</table>";
			}
		}
	 }
	 
	 //Esta función se encarga de generar el Id del Pedido segun la Base de Datos de Comptas
	function obtenerIdPedido(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Definir las tres letras en la Id del Pedido
		$id_cadena = "PED";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el Pedido Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_pedido) AS cant FROM pedido WHERE id_pedido LIKE 'PED$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras del Pedido Registrado en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id_cadena;
	}//Fin de la Funcion obtenerIdPedido()	 
	
	//Funcion que registra un Pedido en la BD de Compras
	function registrarPedido(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");	

		//Obtener y convertir la fecha al formato aaaa-mm-dd
		$fecha = date("Y-m-d");
		
		if (strlen($_POST["txt_subtotal"])>6) 
			$_POST["txt_subtotal"] = str_replace(",","",$_POST["txt_subtotal"]);
		if (strlen($_POST["txt_iva"])>6)
			$_POST["txt_iva"] = str_replace(",","",$_POST["txt_iva"]);
		if (strlen($_POST["txt_total"])>6)
			$_POST["txt_total"] = str_replace(",","",$_POST["txt_total"]);

		//Convertir a mayusculas
		$txt_noPedido=strtoupper($_POST["txt_noPedido"]);
		$txt_rfc=strtoupper($_POST["txt_rfc"]);
		$txt_noReq=strtoupper($_POST["txt_noReq"]);
		$txa_condEnt=strtoupper($_POST["txa_condEnt"]);
		$txt_plazo=strtoupper($_POST["txt_plazo"]);
		$txt_solicito=strtoupper($_POST["txt_solicito"]);
		$txt_reviso=strtoupper($_POST["txt_reviso"]);
		$txt_autorizo=strtoupper($_POST["txt_autorizo"]);
		$txa_comentarios=strtoupper($_POST["txa_comentarios"]);
		$cmb_plazo=$_POST["cmb_plazo"];
		$txa_condPago=$_POST["cmb_condPago"];
		$cmb_viaPed=$_POST["cmb_viaPed"];
		$tipo_moneda=$_POST["cmb_tipoMoneda"];

		//Crear la sentencia para realizar el registro del Pedido en la BD de Compras en la tabla de Pedido
		$stm_sql = "INSERT INTO pedido(id_pedido,proveedores_rfc,requisiciones_id_requisicion,cond_entrega,cond_pago,plazo_entrega,fecha,subtotal,
					iva,total,tipo_moneda,solicitor,revisor,autorizador,comentarios,via_pedido,estado,depto_solicitor)
					VALUES('$txt_noPedido','$txt_rfc','$txt_noReq','$txa_condEnt','$txa_condPago','$txt_plazo $cmb_plazo','$fecha',$_POST[txt_subtotal],
					$_POST[txt_iva],$_POST[txt_total],'$tipo_moneda','$txt_solicito','$txt_reviso','$txt_autorizo','$txa_comentarios','$cmb_viaPed','NO PAGADO','GERENCIA TECNICA')";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
			//Confirmar que la inserción de datos fue realizada con exito.
		if($rs){
				//Funcion que registra los detalles del Pedido		
				registrarDetallesPedido($_POST["txt_noPedido"]);
				mysql_close($conn);
				//Funcion que actualiza el estado de la Requisicion
				actualizarRequisicion($txt_noReq);
				registrarOperacion("bd_gerencia",$txt_noPedido,"RegistrarPedido",$_SESSION['usr_reg']);											
		}
		else{			
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
			mysql_close($conn);
		}		
		//La Conexion a la BD se cierra en la funcion registraPedido();	
	}//Fin de la funcion registrarPedido()
	
	//Funcion que registra el detalle del Pedido en la BD de Compras
	function registrarDetallesPedido($id_pedido){
		//Variable que permite abortar si se han generado errores
		$band=0;
		//Registrar todos los materiales dados de alta en el arreglo $detallePedido
		foreach ($_SESSION['detallePedido'] as $ind => $concepto){

			//Buscar "comas [,]" en valores numericos y removerlas
			if ($concepto["precio_unitario"])
				$concepto["precio_unitario"]=str_replace(",","",$concepto["precio_unitario"]);
			if ($concepto["importe"])
				$concepto["importe"]=str_replace(",","",$concepto["importe"]);

			//Crear la sentencia para realizar el registro de los datos del detalle de los Pedidos
			$stm_sql = "INSERT INTO detalles_pedido (pedido_id_pedido,partida,unidad,cantidad,descripcion,precio_unitario,importe)
			VALUES('$id_pedido','$concepto[partida]','$concepto[unidad]','$concepto[cantidad]','$concepto[descripcion]','$concepto[precio_unitario]','$concepto[importe]')";

			//Ejecutar la sentencia previamente creada para agregar cada concepto a la tabla de Detalles de Pedido
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;					

		}
		//Si band permanece en 0, todos los datos sem agregaron correctamente.
		if ($band==0)
		{
		?>
			<script type='text/javascript' language='javascript'>
			setTimeout("window.open('../../includes/generadorPDF/pedido2.php?id=<?php echo $id_pedido; ?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);
			</script>
			<?php
			echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
		}
	}//Fin de la Funcion registrarDetallePedido()
	
	function actualizarRequisicion($requisicion){
		//Abrir la conexion con la BD de Gerencia Tecnica
		$conn=conecta("bd_gerencia");
		//Crear la sentencia para realizar el registro de los datos del detalle de los Pedidos
		$stm_sql = "UPDATE requisiciones SET estado='PEDIDO' WHERE id_requisicion='$requisicion'";
		//Ejecutar la sentencia previamente creada para agregar cada concepto a la tabla de Detalles de Pedido
		$rs = mysql_query($stm_sql);
		mysql_close($conn);
	}
?>
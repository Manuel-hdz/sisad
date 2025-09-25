<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 21/Diciembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para  modificar informacion relacionada con el formulario de Modificar Convenio en la BD
	  **/		 

	//Esta funcion se encarga de guardar los cambios realizados al Provedor seleccionado por el usuario
	function guardarCambios(){
		//Obtener el estado, fecha y nuevos comentarios asignados
		$estado=$_POST["cmb_estado"];
		$fecha_fin=modFecha($_POST["txt_fechaFin"],3);
		$id=$_POST["hdn_conv"];
		$comentarios=strtoupper($_POST["txa_comentarios"]);
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");	
		
		//Crear la sentencia para modificar los Convenios en la BD de compras en la tabla de Convenios
		$stm_sql = "UPDATE convenios SET fecha_fin='$fecha_fin',estado='$estado',comentarios='$comentarios' WHERE id_convenio='$id'";
				
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);									
										
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){	
			registrarOperacion("bd_compras",$id,"ActualizoConvenio",$_SESSION['usr_reg']);					
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";											
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error>";			
		}
	}
	
	function agregarTermino($convenio){
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Obtener el nombre del Proveedor con el que se tiene el convenio seleccionado
		$proveedor=obtenerDato("bd_compras","convenios","proveedores_rfc","id_convenio",$convenio);
		$proveedor=obtenerDato("bd_compras","proveedores","razon_social","rfc",$proveedor);
		//Definimos $num;
		$num=0;
		//Consulta para obtener el ultimo termino agregado
		$stm_sql="SELECT MAX(numero) AS num FROM detalles_convenio WHERE convenios_id_convenio='$convenio'";
		//Ejecutar la consulta
		$rs = mysql_query($stm_sql);
		if ($datos = mysql_fetch_array($rs))
			$num=$datos['num']+1;//Obtener el valor del siguiente término a Agregar
		?>
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="68"><div align="right">Proveedor</div></td>
			<td colspan="4"><input name="txt_nombre" id="txt_nombre" value="<?php echo $proveedor;?>" type="text" class="caja_de_texto" size="80" maxlength="80" readonly="readonly"/></td>
		</tr>
		<tr>
			<td><div align="right">Convenio</div></td>
			<td width="135"><input name="txt_convenio" id="txt_convenio" value="<?php echo $convenio;?>" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="true"/></td>
			<td width="110">&nbsp;</td>
			<td width="120"><div align="right">Material y/o Servicio</div></td>
			<td width="180" rowspan="2"><textarea name="txa_material" id="txa_material" cols="30" rows="5" class="caja_de_texto"></textarea></td>
		</tr>
		<tr>
			<td><div align="right">N&uacute;mero</div></td>
			<td><input name="txt_numero" id="txt_numero" value="<?php echo $num;?>" type="text" class="caja_de_texto" size="2" maxlength="2" readonly="true"/></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><div align="right">Unidad</div></td>
			<td><input name="txt_unidad" type="text" id="txt_unidad" class="caja_de_texto" size="6" maxlength="10" onkeypress="return permite(event,'num_car')"/> </td>
			<td>&nbsp;</td>
			<td><div align="right">Precio Unitario </div></td>
			<td>$<input name="txt_precio" type="text" id="txt_precio" class="caja_de_texto" size="10" maxlength="10" onchange="formatCurrency(value,'txt_precio');
                    formatCurrency(txt_precio.value.replace(/,/g,'')*txt_cantidad.value,'txt_importe');" onkeypress="return permite(event,'num', 2)"/></td>
		</tr>
		<tr>
			<td><div align="right">Cantidad</div></td>
			<td><input name="txt_cantidad" type="text" onkeypress="return permite(event,'num', 2)" id="txt_cantidad" class="caja_de_texto" size="10" 
				maxlength="20" onchange="formatCurrency(txt_precio.value.replace(/,/g,'')*txt_cantidad.value,'txt_importe');" /></td>
			<td>&nbsp;</td>
			<td><div align="right">Importe</div></td>
			<td>$<input name="txt_importe" type="text" class="caja_de_texto" id="txt_importe" onclick="formatCurrency(txt_precio.value.replace(/,/g,'')*txt_cantidad.value,'txt_importe');" readonly="true"/>
			</td>
		</tr>
		</table>
	<?php
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	function insertarTermino(){			
		//Obtener el id del Convenio del campo de Texto
		$convenio=$_POST["txt_convenio"];
					
		$numero=$_POST["txt_numero"];
		$unidad=strtoupper($_POST["txt_unidad"]);
		$cantidad=$_POST["txt_cantidad"];
		$matS=strtoupper($_POST["txa_material"]);
		$pu=$_POST["txt_precio"];
		$importe=$_POST["txt_importe"];
		
		//Si el tamaño es mayor a 6, indica que hay una coma, se debe quitar para poder agregar a la BD correctamente
		if (strlen($pu)>6)
			$pu=str_replace(",","",$pu);
		if (strlen($importe)>6)
			$importe=str_replace(",","",$importe);
		
		//Variable que comprueba que todo se realizo correctamente, si cambia a 1, todo es correcto
		$band=0;
		
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		$stm_sql="INSERT INTO detalles_convenio (convenios_id_convenio,numero,unidad,cantidad,material_servicio,precio_unitario,importe) VALUES 
		('$convenio','$numero','$unidad','$cantidad','$matS','$pu','$importe')";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			//Consulta para obtener el total del importe de reigstrado en la BD de Convenios
			$stm_sql = "SELECT SUM(importe) AS importe FROM detalles_convenio WHERE convenios_id_convenio='$convenio'";
			//Ejecutar la sentencia de obtener el importe
			$rs2=mysql_query($stm_sql);
			//Sentencia para contar el numero de alertas de Órdenes de Compra
			$num_terminos=mysql_num_rows($rs2);
			if ($rs2&&$num_terminos>0){
				//Pasar a una variable el resultado de la consulta
				$datos=mysql_fetch_array($rs2);
				$subtotal=$datos['importe'];//Obtener el valor del subtotal
				$iva=$subtotal*0.16;//Iva calculado en base al 16%
				$total=$subtotal+$iva;//Total en base a la suma del subtotal y del iva
				//Crear la sentencia SQL para actualizar el total del convenio
				$stm_sql = "UPDATE convenios SET subtotal='$subtotal',iva='$iva',total='$total' WHERE id_convenio='$convenio'";
				//Ejecutar la actualizacion del precio
				$rs3=mysql_query($stm_sql);
				if ($rs3){
					registrarOperacion("bd_compras",$convenio,"AgregarTerminoConvenio",$_SESSION['usr_reg']);
					return $band=1;
				}
				else{
					$error = mysql_error();
					echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error>";			
				}
			}
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error>";			
			}	
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error>";			
		}	
	}
	
	
	function mostrarTermino(){
		//Obtener el id del Convenio
		$convenio=$_POST["hdn_conv"];
		//Conectar a la BD de Compras
		$conn=conecta("bd_compras");
		//Crear sentencia SQL
		$stm_sql="SELECT numero, unidad, cantidad, material_servicio, precio_unitario, importe FROM detalles_convenio WHERE convenios_id_convenio='$convenio' ORDER BY numero";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		$subtotal=0;	            						
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='850' align='center'> 
				<caption class='titulo_etiqueta'>Detalles de Convenio ".$convenio."</caption></br>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>T&Eacute;RMINOS</td>
						<td class='nombres_columnas' align='center'>UNIDAD</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>MATERIAL/SERVICIO</td>
						<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
						<td class='nombres_columnas' align='center'>IMPORTE</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	
					<tr>
						<td class='$nom_clase' align='center'><input type='radio' name='rdb_termino' id='rdb_termino$cont' value='$datos[numero]'/></td>	
						<td class='$nom_clase' align='center'>$datos[numero]</td>					
						<td class='$nom_clase' align='center'>$datos[unidad]</td>
						<td class='$nom_clase' align='center'>$datos[cantidad]</td>
						<td class='$nom_clase' align='center'>$datos[material_servicio]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["precio_unitario"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["importe"],2,".",",")."</td>
					</tr>
					<input type='hidden' name='hdn_convenio' value='".$convenio."'/>";
				//Acumular el subtotal
				$subtotal+=	$datos["importe"];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs));
			//Obtener el total del convenio registrado
			$stm_sql="SELECT total FROM convenios WHERE id_convenio='$convenio'";
			//Ejecutar la consulta
			$rs = mysql_query($stm_sql);		            						
			$total=mysql_fetch_array($rs);
			echo "</tr>
				<tr>
					<td colspan='6' align='right'><strong>SUBTOTAL</strong></td>
					<td align='center'>$".number_format($subtotal,2,".",",")."</td>
				</tr>
				<tr>
					<td colspan='6' align='right'><strong>IVA</strong></td>
					<td align='center'>$".number_format($subtotal*0.16,2,".",",")."</td>
				</tr>
				<tr>
					<td colspan='6' align='right'><strong>TOTAL</strong></td>
					<td class='nombres_columnas' align='center'>$".number_format($total["total"],2,".",",")."</td>
				</tr>
			</table>";
		}
		else{
			echo "</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>EL PROVEEDOR <u>".$_POST["txt_nombre"]."</u> NO TIENE NINGÚN 
			CONVENIO REGISTRADO</p>";
		}
		//Cerar conexion a BD
		mysql_close($conn);
	}
	
	//Comprobamos que el GET venga con contenido, para poder redireccionar a la funcion con la operacion a realizarse, esta comparación NO forma parte de ninguna 
	//FUNCION
	if (isset($_GET["del"]))
		eliminarTermino();
	
	//Esta funcion es llamada cuando se envia el comando del en el GET, esto indica que se borrarán los detalles de convenio que se especifiquen
	function eliminarTermino(){
		include_once ("../../includes/conexion.inc");
		include_once ("../../includes/op_operacionesBD.php");
		//include("../../include/op_operacionesBD.php");
		$termino=$_POST["rdb_termino"];
		$id=$_POST["hdn_convenio"];
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");	
		
		//Crear la sentencia para modificar los Convenios en la BD de compras en la tabla de Convenios
		$stm_sql="DELETE FROM detalles_convenio WHERE numero='$termino'"; 
				
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);									
										
		//Confirmar que el borrado de datos fue realizada con exito.
		if($rs){				
			//Consulta para obtener el total del importe de reigstrado en la BD de Convenios
			$stm_sql = "SELECT SUM(importe) AS importe FROM detalles_convenio WHERE convenios_id_convenio='$id'";
			//Ejecutar la sentencia de obtener el importe
			$rs2=mysql_query($stm_sql);
			//Sentencia para contar el numero de alertas de Órdenes de Compra
			$num_terminos=mysql_num_rows($rs2);
			if ($rs2&&$num_terminos>0){
				//Pasar a una variable el resultado de la consulta
				$datos=mysql_fetch_array($rs2);
				$subtotal=$datos['importe'];//Obtener el valor del subtotal
				$iva=$subtotal*0.16;//Iva calculado en base al 16%
				$total=$subtotal+$iva;//Total en base a la suma del subtotal y del iva
				//Crear la sentencia SQL para actualizar el total del convenio
				$stm_sql = "UPDATE convenios SET subtotal='$subtotal',iva='$iva',total='$total' WHERE id_convenio='$id'";
				//Ejecutar la actualizacion del precio
				$rs3=mysql_query($stm_sql);
				if ($rs3){	
					session_start();
					registrarOperacion("bd_compras",$id,"EliminarTerminoConvenio",$_SESSION['usr_reg']);
					echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
				}
				else{
					$error = mysql_error();
					echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error>";			
				}
			}
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error>";			
			}	
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error>";			
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
?> 
<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Nadia Madahí López Hernández                            
	  * Fecha: 17/Diciembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada
	   con el formulario de Registrar Venta en la BD**/
	  
	  	  
	if(isset($_POST["sbt_registrarDet"])){

		include_once("../../includes/conexion.inc");
		include_once("../../includes/func_fechas.php");
		include_once("../../includes/op_operacionesBD.php");
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");	
		
		//Transformar las fechas del formato dd/mm/aaa al formato aaaa-mm-dd
		$fecha = date("Y-m-d");
		$txt_fechaVenta = modFecha($txt_fechaVenta,3);
		
		if (strlen($txt_subtotal)>6)
			$txt_subtotal=str_replace(",","",$txt_subtotal);
		if (strlen($txt_iva)>6)
			$txt_iva=str_replace(",","",$txt_iva);
		if (strlen($txt_total)>6)
			$txt_total=str_replace(",","",$txt_total);
			
			
		//Transformar la infomación de los campos a Mayusculas
		$txt_vendio = strtoupper($txt_vendio); 			
		$txt_autorizo = strtoupper($txt_autorizo);
		$txa_comentarios = strtoupper($txa_comentarios);
		//Detectar cuando esta disponbles los datos del Publico en General
		$txt_nomCliente = "";
		$txt_direccion = "";
		if(isset($_POST['txt_nomCliente'])){
			$txt_nomCliente = strtoupper($_POST['txt_nomCliente']);
			$txt_direccion = strtoupper($_POST['txt_direccion']);
		}
					
		//Crear la sentencia para realizar el registro de los nuevos datos en ventas
		$stm_sql = "INSERT INTO ventas (id_venta,clientes_rfc,nom_cliente,direccion,fecha,subtotal,iva,total,factura,vendio,medio_venta,autorizador,comentarios)
		VALUES('$txt_noVenta','$cmb_cliente','$txt_nomCliente','$txt_direccion','$txt_fechaVenta','$txt_subtotal','$txt_iva','$txt_total','$cmb_factura','$txt_vendio','$cmb_medioVenta','$txt_autorizo','$txa_comentarios')";					
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
										
		//Confirmar que la insercion de datos fue realizada con exito.		
		if($rs){
			session_start();			
			if (isset($_SESSION["detalleVenta"]))
				registrarDetallesVenta($txt_noVenta);			
			
			registrarOperacion("bd_compras", $txt_noVenta, "AgregarVenta", $_SESSION['usr_reg']);
			//Liberar los datos de Ventas que estan en la SESSION
			unset($_SESSION["detalleVenta"]);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{			
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		//Cerrar la conexion con la BD si esta definido el Arreglo de Session
		if (isset($_SESSION["detalleVenta"]))
			mysql_close($conn);
	}


	//Esta funcion agrega los detalles de la Venta a la BD mediante el arreglo de SESSION
	function registrarDetallesVenta($id_venta){
		//Variable que permite abortar si se han generado errores
		$band=0;
		
		//Registrar todos los materiales dados de alta en el arreglo $detallesventa
		foreach ($_SESSION['detalleVenta'] as $ind => $concepto){
			//Buscar "comas [,]" en valores numericos y removerlas
			if (strlen($concepto["precio"])>6)
				$concepto["precio"]=str_replace(",","",$concepto["precio"]);
			if (strlen($concepto["importe"])>6)
				$concepto["importe"]=str_replace(",","",$concepto["importe"]);
						
			//Crear la sentencia para realizar el registro de los datos del detalle de las Ventas
			$stm_sql = "INSERT INTO detalles_venta (ventas_id_venta,partida,unidad,cantidad,descripcion,precio_unitario,importe)
				VALUES('$id_venta','$concepto[partida]','$concepto[unidad]','$concepto[cantidad]','$concepto[descripcion]','$concepto[precio]','$concepto[importe]')";
			//Ejecutar la sentencia previamente creada para agregar cada concepto a la tabla de Detalles de la Venta
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;
		}
	}


	/*Esta funcion genera la Clave de la venta de acuerdo a los registros en la BD*/
	function obtenerIdVenta(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Definir las tres letras en la Id de la Venta
		$id_cadena = "VEN";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Venta Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_venta) AS cant FROM ventas WHERE id_venta LIKE 'VEN$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la Venta Registrado en la BD y sumarle 1
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
	}//Fin de la Funcion obtenerIdVenta()	
	
	
	/*Esta función muestra el Detalle de la Venta en curso, cuyos datos estan registrados en la SESSION Actual*/
	function mostrarDetallesVenta(){
		echo "<table cellpadding='5' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Detalles registrados de la Venta: ".$_SESSION['detalleVenta'][0]['clave_venta']."</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>PARTIDA</td>
        		<td class='nombres_columnas' align='center'>UNIDAD</td>
			    <td class='nombres_columnas' align='center'>CANTIDAD</td>
				<td class='nombres_columnas' align='center'>DESCRIPCION</td>
				<td class='nombres_columnas' align='center'>PRECIO UNITARIO</td>
				<td class='nombres_columnas' align='center'>IMPORTE</td>
      		</tr>";
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($_SESSION['detalleVenta'] as $ind => $detalle) {
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
					case "precio":
						echo "<td class='$nom_clase' align='center'>$ $value</td>";
					break;
					case "importe":
						echo "<td class='$nom_clase' align='center'>$ $value</td>";
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
	
	
	//Esta funcion se llama al redireccionar desde una Venta
	function agregarDetallesVenta(){
		$conn=conecta($_POST["hdn_bd"]);
		//Variable que acumula el subtotal
		$subtotal=0;
		echo "<table cellpadding='5' width='850' align='center'> 
			<caption class='titulo_etiqueta'></caption></br>";										
		$nom_clase= "renglon_gris";
		$cont=1;
		do{
			echo "	
				<tr>					
					<td class='$nom_clase' align='center'>$datos[cant_req]</td>					
					<td class='$nom_clase' align='center'>$datos[unidad_medida]</td>
					<td class='$nom_clase' align='center'>$datos[descripcion]</td>
					<td class='$nom_clase' align='center'>$datos[aplicacion]</td>
					<td class='$nom_clase' align='center'>";?>
						<input name="txt_precio<?php echo $cont;?>" type="text" id="txt_precio<?php echo $cont;?>" class="caja_de_texto" size="10"
                     	maxlength="10" onChange="formatCurrency(value.replace(/,/g,''),'txt_precio<?php echo $cont;?>');formatCurrency(txt_precio<?php 
					 	echo $cont;?>.value.replace(/,/g,'')*<?php echo $datos["cant_req"];?>,'txt_importe<?php echo $cont;?>');"/><?php 
					 	echo "	
					</td>
					<td class='$nom_clase' align='center'>";?>
						<input name="txt_importe" type="text" id="txt_importe<?php echo $cont;?>" class="caja_de_texto" size="10" readonly="true" 
                        maxlength="10" onBlur="suma();"/><?php echo "
					</td>
				</tr>";
			//Obtener datos a agregar al arreglo de Session
			$unidadM=$datos["unidad_medida"];
			$cantidad=$datos["cant_req"];
			$desc=$datos["descripcion"];
				
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}while($datos=mysql_fetch_array($rs));
		echo "<td colspan='5' align='right'><strong>SUBTOTAL</strong></td>";				
		?><td align='center'>
			<input type='text' name='txt_subtotal' id='txt_subtotal' class='caja_de_texto' size='10' onClick="formatCurrency(value.replace(/,/g,''),'txt_subtotal');" 
			onBlur="formatCurrency(value.replace(/,/g,''),'txt_subtotal');"/>
		</td><?php		
		echo "<input type='hidden' name='hdn_cantidad' id='hdn_cantidad' value='$cont'/>
		</table>";
		//Cerar conexion a BD
		mysql_close($conn);		
	}


	function obtenerSubtotal(){
		$subtotal=0;
		foreach ($_SESSION['detallesventa'] as $ind => $concepto){
			if (strlen($concepto["importe"])>6)
				$subtotal+=str_replace(",","",$concepto["importe"]);
			else
				$subtotal+=$concepto["importe"];
		}
		return $subtotal;
	}
?>

<?php
	/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 30/Octubre/2012
	  * Descripción: Este archivo contiene funciones para gestionar el registro de Pagos
	  **/
	
	//Funcion que calcula el ID del Pago
	function calcularIdPago(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_compras");
		//Sentencia SQl para extraer el id
		$sql="SELECT MAX(id_pago)+1 AS id FROM pagos";
		//Ejecutar la sentencia
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos["id"]==NULL)
				$id=1;
			else
				$id=$datos["id"];
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
		//Retornar el ID calculado
		return $id;
	}

	//Funcion que muestra el detalle del Pago segun el Arreglo de Session
	function mostrarDetallesPago($detallesPago){
		echo "<table cellpadding='4' width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Detalle del Pago a $_POST[txt_nombre] el Dia $_POST[txt_fecha]</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>PARTIDA</td>
				<td class='nombres_columnas' align='center'>FORMA DE PAGO</td>
				<td class='nombres_columnas' align='center'>IMPORTE</td>
        		<td class='nombres_columnas' align='center'>IVA</td>
			    <td class='nombres_columnas' align='center'>TOTAL</td>
				<td class='nombres_columnas' align='center'>NO. FACTURA</td>
				<td class='nombres_columnas' align='center'>ESTADO DE LA FACTURA</td>
				<td class='nombres_columnas' align='center'>RESPONSABLE</td>
				<td class='nombres_columnas' align='center'>PEDIDO</td>
				<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
				<td class='nombres_columnas' align='center'>CONCEPTO</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$total=0;
		foreach ($detallesPago as $ind => $detalle){
			echo "<tr>";
				echo "<td class='$nom_clase' align='center'>$detalle[partida]</td>";
				echo "<td class='$nom_clase' align='center'>$detalle[formaPago]</td>";
				echo "<td class='$nom_clase' align='center'>$".number_format($detalle["subtotal"],2,".",",")."</td>";
				echo "<td class='$nom_clase' align='center'>$".number_format($detalle["iva"],2,".",",")."</td>";
				echo "<td class='$nom_clase' align='center'>$".number_format($detalle["total"],2,".",",")."</td>";
				echo "<td class='$nom_clase' align='center'>$detalle[factura]</td>";
				echo "<td class='$nom_clase' align='center'>$detalle[statusFactura]</td>";
				echo "<td class='$nom_clase' align='center'>$detalle[responsable]</td>";
				echo "<td class='$nom_clase' align='center'>$detalle[pedido]</td>";
				echo "<td class='$nom_clase' align='center'>$detalle[aplicacion]</td>";
				echo "<td class='$nom_clase' align='center'>$detalle[concepto]</td>";
			echo "</tr>";
			$total+=$detalle["total"];
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		echo "<tr>";
			echo "<td colspan='3'>&nbsp;</td>";
			echo "<td class='nombres_columnas' align='center'>PAGO TOTAL</td>";
			echo "<td class='nombres_columnas' align='center'>$".number_format($total,2,".",",")."</td>";
		echo "</tr>";
		echo "</table>";
	}//Fin de la funcion mostrarRegistros($datosSalida)
	
	//Funcion que guarda el Pago en la BD
	function guardarPago(){
		//Obtener el ID del Pago
		$id=calcularIdPago();
		//Obtener los datos del POST generales para guardar la informacion
		$fecha=modFecha($_POST["txt_fecha"],3);
		$proveedor=$_POST["txt_nombre"];
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_compras");
		//Sentencia SQL para guardar el PAGO
		$sql="INSERT INTO pagos(id_pago,fecha,proveedor) VALUES ('$id','$fecha','$proveedor')";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		if($rs){
			$band=0;
			foreach($_SESSION["detallesPago"] as $ind => $detalle){
				//Sentencia SQL para guardar el PAGO
				$sql="INSERT INTO detalle_pago(pagos_id_pago,partida,forma_pago,subtotal,iva,total,factura,estado_factura,responsable,pedido,aplicacion,concepto) VALUES ('$id','$detalle[partida]','$detalle[formaPago]','$detalle[subtotal]','$detalle[iva]','$detalle[total]','$detalle[factura]','$detalle[statusFactura]','$detalle[responsable]','$detalle[pedido]','$detalle[aplicacion]','$detalle[concepto]')";
				//Ejecutar la sentencia SQL
				$rs=mysql_query($sql);
				if(!$rs){
					$band=1;
					break;
				}
			}
			//Borrar el arreglo de Sesion del detalle del Pago
			if(isset($_SESSION["detallesPago"]))
				unset($_SESSION["detallesPago"]);
			if($band==0){
				//Cerrar la conexion con la BD
				mysql_close($conn);
				//Guardar la descripción de la operacion realizada
				registrarOperacion("bd_compras",$id,"RegistrarPago",$_SESSION['usr_reg']);
				//Enviar a la pagina de Exito
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
			else{
				//Obtener el Error en una variable
				$error = mysql_error();			
				//Si hubo errores borrar los registros relacionados con el ID
				mysql_query("DELETE FROM detalle_pago WHERE pagos_id_pago='$id'");
				mysql_query("DELETE FROM pagos WHERE id_pago='$id'");
				//Redireccionar a la ventana de Error
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				//Cerrar la conexion con la BD
				mysql_close($conn);
			}
		}
		else{
			//Borrar el arreglo de Sesion del detalle del Pago
			if(isset($_SESSION["detallesPago"]))
				unset($_SESSION["detallesPago"]);
			//Obtener el Error en una variable
			$error = mysql_error();			
			//Redireccionar a la ventana de Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
	}
	
	//Funcion que muestra los Pagos guardados en la BD
	function mostrarPagos(){
		$mes=$_POST["cmb_mes"];
		$anio=$_POST["cmb_anio"];
		$ultimoDia=diasMes($mes,$anio);
		
		//Obtener el nombre del MEs
		switch($mes){
			case "01":	$nomMes="ENERO";		break;
			case "02":	$nomMes="FEBRERO";		break;
			case "03":	$nomMes="MARZO";		break;
			case "04":	$nomMes="ABRIL";		break;
			case "05":	$nomMes="MAYO";			break;
			case "06":	$nomMes="JUNIO";		break;
			case "07":	$nomMes="JULIO";		break;
			case "08":	$nomMes="AGOSTO";		break;
			case "09":	$nomMes="SEPTIEMBRE";	break;
			case "10":	$nomMes="OCTUBRE";		break;
			case "11":	$nomMes="NOVIEMBRE";	break;
			case "12":	$nomMes="DICIEMBRE";	break;
		}
		
		//Armar la Fecha, si el mes es diferente de vacio, es decir que haya sido seleccionado un dato
		if($mes!=""){
			$fechaIni=$anio."-".$mes."-01";
			$fechaFin=$anio."-".$mes."-".$ultimoDia;
			$msg="<p align='center' class='msje_correcto'>Pagos Registrados en $nomMes $anio </p>";
		}else{
			$fechaIni=$anio."-01-01";
			$fechaFin=$anio."-12-31";
			$msg="<p align='center' class='msje_correcto'>Pagos Registrados en $anio </p>";
		}
		
		if($_POST["cmb_tipo"]=="PROVEEDOR"){
				$proveedor=strtoupper($_POST["txt_nombre"]);
			}
			else if($_POST["cmb_tipo"]=="RESPONSABLE"){
				$proveedor=strtoupper($_POST["txt_nombre"]);
			}
			else if($_POST["cmb_tipo"]=="CANTIDAD"){
				$costo_menor=str_replace(",","",$_POST["txt_cantInf"]);
				$costo_mayor=str_replace(",","",$_POST["txt_cantSup"]);
			}
			else if($_POST["cmb_tipo"]=="BAJAS"){
				$proveedor=strtoupper($_POST["txt_nombre"]);
			}

		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_compras");
		
		//Sentencia SQL para obtener los identificadores de los Pagos segun el dato de los proveedores
		if($_POST["cmb_tipo"]=="")
			$sql="SELECT id_pago,fecha,proveedor FROM pagos WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' ORDER BY proveedor,id_pago";
			//$msg = "<p align='center' class='msje_correcto'>No Hay Pagos Registrados en $nomMes $anio $proveedor</p>";
		
		if($_POST["cmb_tipo"]=="PROVEEDOR")
			$sql="SELECT id_pago,fecha,proveedor FROM pagos WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' AND proveedor='$proveedor' ORDER BY id_pago";
		
		if($_POST["cmb_tipo"]=="RESPONSABLE")
			$sql = "SELECT  DISTINCT id_pago,fecha,responsable FROM detalle_pago JOIN pagos ON id_pago = pagos_id_pago WHERE fecha 
			BETWEEN '$fechaIni' AND '$fechaFin' AND responsable='$proveedor'  ORDER BY responsable,id_pago";
			
		if($_POST["cmb_tipo"]=="CANTIDAD")
			$sql = "SELECT  DISTINCT id_pago,fecha,responsable, total FROM detalle_pago JOIN pagos ON id_pago = pagos_id_pago WHERE fecha 
			BETWEEN '$fechaIni' AND '$fechaFin' AND total>='$costo_menor' AND  total<='$costo_mayor'  ORDER BY responsable,id_pago";
			
		if($_POST["cmb_tipo"]=="BAJAS")
			$sql = "SELECT  DISTINCT id_pago,fecha,proveedor FROM detalle_pago JOIN pagos ON id_pago = pagos_id_pago WHERE fecha 
			BETWEEN '$fechaIni' AND '$fechaFin' AND proveedor='$proveedor'  ORDER BY responsable,id_pago";
		
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			echo "<table width='100%'  id='tabla-rpt-pagos'>";
			echo "<caption><p class='msje_correcto'><strong>$msg</strong></p></caption>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				$sql="SELECT * FROM detalle_pago JOIN pagos ON id_pago = pagos_id_pago WHERE pagos_id_pago='$datos[id_pago]'  ORDER BY partida";
				$rsDetalle=mysql_query($sql);
				$total=0;
				echo "
					<tr>
						<td colspan='2' class='nombres_columnas' align='left'>FECHA DE PAGO:</td>
						<td colspan='10' class='$nom_clase' align='left'>".modFecha($datos["fecha"],1)."</td>
					</tr>";
					if($_POST["cmb_tipo"]=="PROVEEDOR"){
						echo 
						"<tr>
							<td colspan='2' class='nombres_columnas' align='left'>PROVEEDOR:</td>
							<td colspan='10' class='$nom_clase' align='left'>$datos[proveedor]</td>
						</tr>";
						}if($_POST["cmb_tipo"]=="RESPONSABLE"){
						echo "
							<tr>
								<td colspan='2' class='nombres_columnas' align='left'>RESPONSABLE:</td>
								<td colspan='10' class='$nom_clase' align='left'>$datos[responsable]</td>
							</tr>";
						}if($_POST["cmb_tipo"]=="CANTIDAD"){
						echo "
							<tr>
								<td colspan='2' class='nombres_columnas' align='left'>CANTIDAD:</td>
								<td colspan='10' class='$nom_clase' align='left'>$".number_format($datos['total'],2,".",",")."</td>
							</tr>";
						}if($_POST["cmb_tipo"]=="BAJAS"){
						echo "
							<tr>
								<td colspan='2' class='nombres_columnas' align='left'>BAJAS EMPLEADOS:</td>
								<td colspan='10' class='$nom_clase' align='left'>$datos[proveedor]</td>
							</tr>";
						}
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				if($detalle=mysql_fetch_array($rsDetalle)){
						echo "<tr>";
							echo "<td class='nombres_columnas' align='center'>PARTIDA</td>";
							echo "<td class='nombres_columnas' align='center'>FORMA DE PAGO</td>";
							echo "<td class='nombres_columnas' align='center'>SUBTOTAL</td>";
							echo "<td class='nombres_columnas' align='center'>IVA</td>";
							echo "<td class='nombres_columnas' align='center'>TOTAL</td>";
							echo "<td class='nombres_columnas' align='center'>RESPONSABLE</td>";
							if($_POST["cmb_tipo"]=="BAJAS"){
								echo "<td class='nombres_columnas' align='center'>EMPLEADO DE BAJA</td>";
							}else{
								echo "<td class='nombres_columnas' align='center'>PROVEEDOR</td>";
							}
							echo "<td class='nombres_columnas' align='center'>FACTURA</td>";
							echo "<td class='nombres_columnas' align='center'>ESTADO DE LA FACTURA</td>";
							echo "<td class='nombres_columnas' align='center'>PEDIDO</td>";
							echo "<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>";
							echo "<td class='nombres_columnas' align='center'>CONCEPTO</td>";
						echo "</tr>";
					do{
						echo "<tr>";
							echo "<td class='$nom_clase' align='center'>$detalle[partida]</td>";
							echo "<td class='$nom_clase' align='center'>$detalle[forma_pago]</td>";
							echo "<td class='$nom_clase' align='center'>$".number_format($detalle["subtotal"],2,".",",")."</td>";
							echo "<td class='$nom_clase' align='center'>$".number_format($detalle["iva"],2,".",",")."</td>";
							echo "<td class='$nom_clase' align='center'>$".number_format($detalle["total"],2,".",",")."</td>";
							echo "<td class='$nom_clase' align='center'>$detalle[responsable]</td>";
							echo "<td class='$nom_clase' align='center'>$detalle[proveedor]</td>";
							echo "<td class='$nom_clase' align='center'>$detalle[factura]</td>";
							echo "<td class='$nom_clase' align='center'>$detalle[estado_factura]</td>";?><?php 
							echo "<td class='$nom_clase' align='center'>";
							echo "<span onclick=\"consultarPedido('$detalle[pedido]');\" class=\"msje_correcto\" style=\"cursor:pointer\">".$detalle['pedido']."</span>";
							echo "</td>";?><?php				
							echo "<td class='$nom_clase' align='center'>$detalle[aplicacion]</td>";
							echo "<td class='$nom_clase' align='center'>$detalle[concepto]</td>";
						echo "</tr>";
						$total+=$detalle["total"];
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					}while($detalle=mysql_fetch_array($rsDetalle));
						echo "<tr>";
							echo "<td colspan='3'>&nbsp;</td>";
							echo "<td class='nombres_columnas' align='center'>PAGO TOTAL</td>";
							echo "<td class='nombres_columnas' align='center'>$".number_format($total,2,".",",")."</td>";
						echo "</tr>";
						echo "<tr><td colspan='10'>&nbsp;</td></tr>";
				}
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
		else{
			//if($proveedor!="")
			//7if($_POST["cmb_tipo"]!="")
			
				//$_POST["cmb_tipo"]="a $proveedor";*/
			//echo"</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>No Hay Pagos Registrados en $nomMes $anio $proveedor</p>";
			echo "<meta http-equiv='refresh' content='0;url=frm_consultarPagos.php?noResults'>";

		}
	}
	
?>
<?php
	/**
	  * Nombre del M�dulo: Compras
	  * Nombre Programador: Miguel Angel Garay Castro                           
	  * Fecha: 07/Diciembre/2010                                      			
	  * Descripci�n: Este archivo contiene funciones para manejar el registro de los movimientos en a Caja Chica
	  **/  	  	  	 
	
	//Esta funci�n se encarga de generar el Id de la Caja Chica de acuerdo a los registros existentes en la BD
	function obtenerIdCCH(){
		
		//Definir las dos letras en la Id de la Orden de Compra
		$id_cadena = "CCH";
	
		//Obtener el mes y el a�o y agregarlo a la clave de la Caja Chica
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);		
		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdCCH() de la Orden de  Compra	  
	
	
	//Esta funci�n registra los movimientos realizados en la Caja Chica del mes en curso
	function guardarMovimiento($id_cajaChica,$txt_NoMov,$hdn_fechaMov,$txt_factura,$txt_responsable,$txa_descripcion,$cant_entregada,$cmb_depto){
		//Conectarse a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Pasar a mayusculas los datos Alfanumericos
		$txt_factura = strtoupper($txt_factura); $txt_responsable = strtoupper($txt_responsable); $txa_descripcion = strtoupper($txa_descripcion); 
		$cmb_depto = strtoupper($cmb_depto);
		
		//Crear la sentencia para guardar los movimientos en la caja chica
		$sql_stm = "INSERT INTO detalle_caja_chica (caja_chica_id_caja_chica,movimiento,fecha,factura,responsable,descripcion,cant_entregada,departamento) 
					VALUES('$id_cajaChica',$txt_NoMov,'$hdn_fechaMov','$txt_factura','$txt_responsable','$txa_descripcion',$cant_entregada ,'$cmb_depto')";
		//Ejecutar la sentencia
		$rs = mysql_query($sql_stm);
			if($rs){
			//Crear la sentencia para actualizar el monto del presupuesto
			$sql_stm = "UPDATE caja_chica SET presupuesto=presupuesto-$cant_entregada WHERE id_caja_chica='$id_cajaChica'";
			//Ejecutar la sentencia
			$rs = mysql_query($sql_stm);
			if($rs){
			registrarOperacion("bd_compras",$id_cajaChica."-".$txt_NoMov,"GuardarMovCajaChica",$_SESSION['usr_reg']);								
				//Extraer el presupuesto actualizado despues de guardar el movimiento y el incremento
				$presupuesto = obtenerDato("bd_compras","caja_chica","presupuesto","id_caja_chica","$id_cajaChica");
				//Guardar datos en la SESSION		
				$_SESSION['datosCajaChica']['presupuesto'] = $presupuesto;
				$_SESSION['datosCajaChica']['noMovimiento'] += 1;
				
				return "�El Movimiento Fue Registrado con &Ecirc;xito!";
			}
			else{
				//Redireccionar a la p�gina de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}			
		}
		else{
			//Redireccionar a la p�gina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
		//Cierra conexcion registrarOperacion 
	}//Fin de la funcion guardarMovimiento($id_cajaChica,$txt_NoMov,$hdn_fechaMov,$txt_factura,$txt_responsable,$txa_descripcion,$txt_total)
	
	
	//Esta funci�n registra los movimientos realizados en la Caja Chica del mes en curso
	function actualizarMovimiento($id_cajaChica,$txt_NoMov,$txt_factura,$txa_descripcion,$txt_dif,$txt_totalGastos){
		//Conectarse a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Pasar a mayusculas los datos Alfanumericos
		$txt_factura = strtoupper($txt_factura);
		$txa_descripcion = strtoupper($txa_descripcion);
		
		//Crear la sentencia para guardar los movimientos en la caja chica
		$sql_stm = "UPDATE detalle_caja_chica SET factura='$txt_factura', descripcion='$txa_descripcion', diferencia=$txt_dif, total_gastos=$txt_totalGastos, estado=1 
		WHERE movimiento = $txt_NoMov AND caja_chica_id_caja_chica='$id_cajaChica'";
		//Ejecutar la sentencia
		$rs = mysql_query($sql_stm);
		if($rs){
			//Crear la sentencia para actualizar el monto del presupuesto
			$sql_stm = "UPDATE caja_chica SET presupuesto=presupuesto+$txt_dif WHERE id_caja_chica='$id_cajaChica'";
			//Ejecutar la sentencia
			$rs = mysql_query($sql_stm);
			if($rs){
				registrarOperacion("bd_compras",$id_cajaChica."-".$txt_NoMov,"ActualizarMovCajaChica",$_SESSION['usr_reg']);								
				//Extraer el presupuesto actualizado despues de guardar el movimiento y el incremento
				$presupuesto = obtenerDato("bd_compras","caja_chica","presupuesto","id_caja_chica","$id_cajaChica");
				//Guardar datos en la SESSION		
				$_SESSION['datosCajaChica']['presupuesto'] = $presupuesto;
				
				return "�La Actualizaci&oacute;n del Movimiento Fue Realizada con &Eacute;xito!";
			}
			else{
				//Redireccionar a la p�gina de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}			
		}
		else{
			//Redireccionar a la p�gina de error			
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
		//Cerrar conexion con la BD
		mysql_close($conn);
	}//Fin de la funcion actualizarMovimiento($id_cajaChica,$txt_NoMov,$hdn_fechaMov,$txt_factura,$txt_responsable,$txa_descripcion,$txt_total)
	
	
	//Esta funci�n registra los incrementos que se le hayan hecho a la Caja Chica durante el mes
	function guardarIncremento($id_cajaChica,$txt_inPresupuesto,$hdn_fechaMov){						
		//Conectarse a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Crear la sentencia para guardar los incrementos en la caja chica
		$sql_stm = "INSERT INTO incrementos (caja_chica_id_caja_chica,incremento,fecha) VALUES('$id_cajaChica',$txt_inPresupuesto,'$hdn_fechaMov')";
		//Ejecutar la sentencia
		$rs = mysql_query($sql_stm);
		if($rs){
			//Crear la sentencia para actualizar el monto del presupuesto
			$sql_stm = "UPDATE caja_chica SET presupuesto=presupuesto+$txt_inPresupuesto WHERE id_caja_chica='$id_cajaChica'";
			//Ejecutar la sentencia
			$rs = mysql_query($sql_stm);
			if($rs){
				registrarOperacion("bd_compras",$id_cajaChica,"IncrementoCajaChica",$_SESSION['usr_reg']);
				//Extraer el presupuesto actualizado despues de guardar el movimiento y el incremento
				$presupuesto = obtenerDato("bd_compras","caja_chica","presupuesto","id_caja_chica","$id_cajaChica");
				//Guardar datos en la SESSION
				$_SESSION['datosCajaChica']['presupuesto'] = $presupuesto;
				
				return "�El Incremento Fue Registrado con Exito!";
			}
			else{
				//Redireccionar a la p�gina de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
			}
		}
		else{
			//Redireccionar a la p�gina de error
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
		//Cerrar conexion con la BD
		//Cierra conexcion registrarOperacion 
	}//Fin de la funcion guardarIncremento($id_cajaChica,$txt_inPresupuesto,$hdn_fechaMov)
	
	//Esta funci�n muestra el detalle de los movientos realizados en la Caja Chica durante el mes, los cuales estan registrados en la BD de Compras
	function verDetalleCajaChica($clave_cajaChicaRegBD){
		//Conectar con la BD de compras
		$conn = conecta("bd_compras");
				
		//Crear la sentencia para obtener los datos de los movimientos
		$sql_stm = "SELECT * FROM detalle_caja_chica WHERE caja_chica_id_caja_chica='$clave_cajaChicaRegBD' ORDER BY movimiento";
		$msg = "Movimientos Registrados en la Caja Chica del Mes de ".obtenerMesActual();
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);	
										
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='900'>      			
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
  				</tr>
				<tr>
					<td class='nombres_columnas'>NO.</td>
        			<td class='nombres_columnas'>FECHA</td>
			    	<td class='nombres_columnas'>FACTURA</td>
        			<td class='nombres_columnas'>RESPONSABLE</td>
					<td class='nombres_columnas'>DESCRIPCION</td>
        			<td class='nombres_columnas'>CANT. ENTREGADA</td>
					<td class='nombres_columnas'>DIFERENCIA</td>
					<td class='nombres_columnas'>COSTO TOTAL</td>
					<td class='nombres_columnas'>DEPTO</td>
					<td class='nombres_columnas'>EDITAR</td>
      			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Variables para almacenar el valor de lo Facturado y lo no Facturado
			$facturado = 0;
			$noFacturado = 0;			
			do{	
				if($datos['estado']==0){//Mostrar los registros que pueden ser modificados		
					$nom_clase="";
					$fondo="style='background-color:#F7FF08'";
					echo "	
					<tr>
						<form onsubmit='return verContFormEditarMovimiento(this,$cont);' name='frm_editarMovimiento$cont' action='frm_cajaChica.php' method='post'>
							<input type='hidden' name='hdn_cont' id='hdn_cont' value='$cont' />
							<input type='hidden' name='hdn_cantEntregada$cont' id='hdn_cantEntregada$cont' value='$datos[cant_entregada]' />
							<input type='hidden' name='hdn_NoMov$cont' id='hdn_NoMov$cont' value='$datos[movimiento]' />
							
							<td class='nombres_filas'>$datos[movimiento]</td>
							<td class='$nom_clase' $fondo>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase' $fondo>";?>
								<input type="text" <?php echo "name='txt_factura$cont' id='txt_factura$cont'"; ?> size="12" maxlength="40" disabled="disabled" 
                                value="<?php echo $datos['factura']; ?>" onkeypress="return permite(event,'num_car',1);" /><?php
								echo "
							</td>
							<td class='$nom_clase' align='left' $fondo>$datos[responsable]</td>
							<td class='$nom_clase' align='left' $fondo>";?>
								<textarea <?php echo "name='txa_descripcion$cont' id='txa_descripcion$cont'"; ?> maxlength="60" 
                                onkeyup="return ismaxlength(this);" class="caja_de_texto" rows="2" cols="20" disabled="disabled" 
		                        onkeypress="return permite(event,'num_car', 0);" ><?php echo $datos['descripcion']; ?></textarea>							
							<?php
							echo "
							</td>														
							<td class='$nom_clase' $fondo>$ ".number_format($datos['cant_entregada'],2,".",",")."</td>
							<td class='$nom_clase' width='120' $fondo>$<input type='text' name='txt_dif$cont' id='txt_dif$cont' class='caja_de_texto' 
								readonly='true' disabled='disabled' size='10' maxlength='20' /></td>
							<td class='$nom_clase' width='120' $fondo>";?>$
                            	<input type="text" <?php echo "name='txt_totalGastos$cont' id='txt_totalGastos$cont'"; ?> class="caja_de_texto" 
                                disabled="disabled" size="10" maxlength="20" 
								onclick="borrarDato(this);" onblur="calcDiferencia(this,<?php echo $cont; ?>);" onchange="formatCurrency(value,'txt_totalGastos<?php 
								echo $cont; ?>');" onkeypress="return permite(event,'num',2);" />				
							<?php				
							echo "
							</td>					
							<td class='$nom_clase' align='left' $fondo>$datos[departamento]</td>
							<td class='$nom_clase' align='left' $fondo>";?>
								<input type="submit" name="sbt_opciones<?php echo $cont; ?>" id="sbt_opciones<?php echo $cont ?>" value="Editar" 
                                onmouseover="window.status='';return true" /><?php
							echo " </td>
						</form>
					</tr>";
				}
				else{//Mostrar todos los registros que han sido completados
					echo "
					<tr>
						<td class='nombres_filas'>$datos[movimiento]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
						<td class='$nom_clase'>$datos[factura]</td>
						<td class='$nom_clase' align='left'>$datos[responsable]</td>
						<td class='$nom_clase' align='left'>$datos[descripcion]</td>					
						<td class='$nom_clase'>$ ".number_format($datos['cant_entregada'],2,".",",")."</td>
						<td class='$nom_clase'>$ ".number_format($datos['diferencia'],2,".",",")."</td>
						<td class='$nom_clase'>$ ".number_format($datos['total_gastos'],2,".",",")."</td>					
						<td class='$nom_clase' align='left'>$datos[departamento]</td>											
						<td class='$nom_clase'>&nbsp;&nbsp;&nbsp;</td>
					</tr>";
				}
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				
				//Sumar la cantidad de los gastos que cuentan con factura y los que no
				if($datos['factura']=="" || $datos['factura']=="N/A" || $datos['factura']=="NA" || $datos['factura']=="N/D" || $datos['factura']=="ND"){
					if($datos['total_gastos']!="")
						$noFacturado += $datos['total_gastos'];				
					else
						$noFacturado += $datos['cant_entregada'];
				}
				else{
					$facturado += $datos['total_gastos'];														
				}				
				
			}while($datos=mysql_fetch_array($rs));
			//Desplegar el monto de lo facturado y lo no facturado
			echo "
				<tr>
					<td colspan='5' class='nombres_columnas'>Total Facturado: $ ".number_format($facturado,2,".",",")."</td>
					<td class='nombres_columnas'>&nbsp;</td>
					<td colspan='5' class='nombres_columnas'>Total No Facturado: $ ".number_format($noFacturado,2,".",",")."</td>
				</tr>
			";						
			echo "</table>";
			
			
			//Mostrar el detalle de los incrementos en el caso de que existan
			$rs = mysql_query("SELECT incremento,fecha FROM incrementos WHERE caja_chica_id_caja_chica='$clave_cajaChicaRegBD'");
			if($datos=mysql_fetch_array($rs)){
				echo "
					<br><br>
					<table cellpadding='5' width='40%'>      			
						<caption><strong>Incrementos de la Caja Chica del Mes ".obtenerMesActual()."</strong></caption>
						<tr>
							<td class='nombres_columnas'>No.</td>
							<td class='nombres_columnas'>INCREMENTO</td>
        					<td class='nombres_columnas'>FECHA</td>
						</tr>
				";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				do{
					echo "
						<tr>
							<td class='nombres_filas'>$cont</td>
							<td class='$nom_clase'>$ ".number_format($datos['incremento'],2,".",",")."</td>
        					<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
						</tr>
					";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				echo "</table>";
			}
		}
				
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Fin de la funcion verDetalleCajaChica()
	
	
	/*Comparamos que lo que vieneen $txt_NoMov
	*TRUE: Existen movimientos iguales y no puede guardar el siguiente registro
	*FALSE: No hay Datos iguales y puede guardar el siguiente registro
	*/
	function verificarRegCCH($txt_NoMov,$id_cajaChica){
		//Creamos la conexi�n con la base de datos
		$conn = conecta("bd_compras");
				
		//Crear la sentencia para obtener los datos de los movimientos
		$sql_stm = "SELECT movimiento FROM detalle_caja_chica WHERE movimiento=$txt_NoMov AND caja_chica_id_caja_chica='$id_cajaChica'";
				
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);	
									
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos = mysql_fetch_array($rs))
			return true;					
		else 
			return false;
		//Cerramos la conexi�n con la BD
		mysql_close();		
	}
?>
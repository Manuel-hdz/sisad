<?php
	/**
	  * Nombre del Módulo: Compras
	  * Nombre Programador: Maurilio Hernandez Correa
	  * Fecha: 21/Enero/2011                                      			
	  * Descripción: Este archivo contiene funciones para desplegar la informacion de la Caja Chica de Meses Anteriores al Actual
	  **/
	
	
	/*Esta funcion se encarga de cargar el combo con los años disponibles para consultar la Caja Chica*/
	function cargarComboAnios($valSelect){
		//Conectar con la bd_compras		
		$conn = conecta("bd_compras");
		//Ejecutar la sentencia para obtner las claves de la Caja Chica
		$result=mysql_query("SELECT id_caja_chica FROM caja_chica");
		if($datos=mysql_fetch_array($result)){
			//Este arreglo almacenara los años disponibles para la CajaChica
			$anios = array();
			do{
				//Se convierten los valores del arreglo a entero
				$digitos_anio = intval(substr($datos['id_caja_chica'],5,2));
				//Añadir el primer año encontrado al arreglo anios
				if(count($anios)==0)
					$anios[] = $digitos_anio;
				else{
					if(in_array($digitos_anio,$anios)=="")
						$anios[] = $digitos_anio;
				}														
			}while($datos=mysql_fetch_array($result));						
		}
		//Ordenar Arreglo proporcionado
		sort($anios);?>
	      <select name="cmb_anio" size="1" class="combo_box" onchange="javascript:document.frm_consultarCajaChica.submit();">
            <option value="">A&ntilde;o</option>
            <?php 							
			foreach($anios as $key => $value){				
				if($valSelect==$value)
					echo "<option value='$value' selected='selected'>20$value</option>";
				else
					echo "<option value='$value'>20$value</option>";
			}?>
          </select>
	      <?php		
				
		//Cerrar la conexion con la BD		
		mysql_close($conn); 
	}
	
	
	/*Esta funcion cargar los meses disponibles del año seleccionado para mostrar el Detalle de la Caja Chica*/
	function cargarComboMeses($anio){
		//Conectar con la bd_compras		
		$conn = conecta("bd_compras");
		//Ejecutar la sentencia para obtner las claves de la Caja Chica
		$result=mysql_query("SELECT id_caja_chica FROM caja_chica  WHERE id_caja_chica LIKE '%$anio'");
		if($datos=mysql_fetch_array($result)){
			?>
	      <select name="cmb_mes" size="1" class="combo_box">
            <option value="">Mes</option>
            <?php				
			do{
			echo"Mes";
				//Se convierten los valores del arreglo a entero
				$digitos_mes = substr($datos['id_caja_chica'],3,2);
				$nomMes = obtenerMesTextual($digitos_mes);
				echo "<option value='$datos[id_caja_chica]'>$nomMes</option>";																												
			}while($datos=mysql_fetch_array($result));	
			?>
          </select>
          <?php				
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	  }

	
	function mostrarCajaChica($id_cajaChica){
		//Conectar a la BD de Compras
		$conn = conecta("bd_compras");
		//Crear sentencia SQL
		$sql_stm = "SELECT * FROM detalle_caja_chica WHERE caja_chica_id_caja_chica='$id_cajaChica' ORDER BY movimiento";
		
			//Obtener los digitos del mes
			$digitos_mes = substr($id_cajaChica,3,2);	
			$nomMes = obtenerMesTextual($digitos_mes);
			//Se imprimen el mes
			$msg = "Movimientos Registrados en la Caja Chica del Mes de ".obtenerMesTextual($digitos_mes);
		
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
					<td class='nombres_columnas'>No.</td>
        			<td class='nombres_columnas'>FECHA</td>
			    	<td class='nombres_columnas'>FACTURA</td>
        			<td class='nombres_columnas'>RESPONSABLE</td>
					<td class='nombres_columnas'>DESCRIPCION</td>
        			<td class='nombres_columnas'>CANT. ENTREGADA</td>
					<td class='nombres_columnas'>DIFERENCIA</td>
					<td class='nombres_columnas'>COSTO TOTAL</td>
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
						<form onsubmit='return verContFormEditarMovimiento(this,$cont);' name='frm_editarMovimiento$cont' action='frm_consultarCajaChica.php' method='post'>
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
								readonly='true' disabled='disabled' size='15' maxlength='20' /></td>
							<td class='$nom_clase' width='120' $fondo>";?>$
                            	<input type="text" <?php echo "name='txt_totalGastos$cont' id='txt_totalGastos$cont'"; ?> class="caja_de_texto" 
                                disabled="disabled" size="12" maxlength="20" 
								onclick="borrarDato(this);" onblur="calcDiferencia(this,<?php echo $cont; ?>);" onchange="formatCurrency(value,'txt_totalGastos<?php 
								echo $cont; ?>');" onkeypress="return permite(event,'num',2);" />				
							<?php				
							echo "
							</td>					
							<td class='$nom_clase' $fondo>"; ?>
								<input type="submit" name="sbt_opciones<?php echo $cont; ?>" id="sbt_opciones<?php echo $cont ?>" value="Editar" 
                                onmouseover="window.status='';return true"/><?php
							echo " </td>
							<input type='hidden' name='sbt_consultar' id='sbt_consultar'/>
							<input type='hidden' name='cmb_mes' id='cmb_mes' value='$id_cajaChica'/>
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
			//Crear Consulta para mostrar el remanente
			$stm_sql = "SELECT * FROM (detalle_caja_chica JOIN caja_chica ON caja_chica_id_caja_chica=id_caja_chica) WHERE id_caja_chica='$id_cajaChica'";
			
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($stm_sql);									
			//Confirmar consulta realizada con Éxito
			if($datos=mysql_fetch_array($rs)){
				//Desplegar el monto de lo facturado y lo no facturado asi como el remanente=presupuesto
				echo "
					<tr>
						<td colspan='3' class='nombres_columnas'>Total Facturado: $ ".number_format($facturado,2,".",",")."</td>
						<td colspan='3'  class='nombres_columnas'>Remanente: $".number_format($datos['presupuesto'],2,".",",")."</td>
						<td colspan='3' class='nombres_columnas'>Total No Facturado: $ ".number_format($noFacturado,2,".",",")."</td>
					</tr>
				";						
			echo "</table>";}
		
							
			//Mostrar el detalle de los incrementos en el caso de que existan
			$rs = mysql_query("SELECT incremento,fecha FROM incrementos WHERE caja_chica_id_caja_chica='$id_cajaChica'");
			if($datos=mysql_fetch_array($rs)){
			//Obtener los digitos del mes
		      echo "
					<br><br>
					<table cellpadding='5' width='40%'> 
						<caption><strong>Incrementos de la Caja Chica del Mes ".obtenerMesTextual($digitos_mes)."</strong></caption>
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
			else{	
				//Obtener los digitos del mes
				$digitos_mes = substr($id_cajaChica,3,2);	
				$nomMes = obtenerMesTextual($digitos_mes);
				echo"</br></br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>No existen datos para la Caja Chica del Mes de $nomMes</p>";
			}
			?>
          </p>
         </div> 
		<div id="btns-regpdf" align="center">
			<table width="100%">
				<tr>	
					<td align="center">
                		<form action="frm_consultarCajaChica.php" method="post">
		                    <input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla de Consultar Caja Chica" 
        		            onmouseover=    	  "window.estatus='';return true" id="sbt_regresar"  />
                	 </form>				
             		</td>			
		 		</tr>
			</table>			
		</div>
<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Fin de la funcion verDetalleCajaChica()
	
	
	//Esta función regresa el mes Textual y recibe como parametro el numero del mes que queremos obtener
	function obtenerMesTextual($noMes){		
		$mes = "";
		//Identificar mes
		switch($noMes){
			case "01":		$mes="Enero";		break;
			case "02":		$mes="Febrero";		break;
			case "03":		$mes="Marzo";		break;			
			case "04":		$mes="Abril";		break;
			case "05":		$mes="Mayo";		break;
			case "06":		$mes="Junio";		break;
			case "07":		$mes="Julio";		break;
			case "08":		$mes="Agosto";		break;
			case "09":		$mes="Septiembre";	break;
			case "10":		$mes="Octubre";		break;
			case "11":		$mes="Noviembre"; 	break;
			case "12": 		$mes="Diciembre";	break;
		}
		return $mes;
	}

	//Esta función registra los movimientos realizados en la Caja Chica del mes en curso
	function actualizarMovimiento($id_cajaChica,$txt_NoMov,$txt_factura,$txa_descripcion,$txt_dif,$txt_totalGastos){
		//Conectarse a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Pasar a mayusculas los datos Alfanumericos
		$txt_factura = strtoupper($txt_factura);
		$txa_descripcion = strtoupper($txa_descripcion);
		
		//Crear la sentencia para guardar los movimientos en la caja chica
		$sql_stm = "UPDATE detalle_caja_chica SET factura='$txt_factura', descripcion='$txa_descripcion', diferencia=$txt_dif, total_gastos=$txt_totalGastos,  estado=1
		 WHERE movimiento = $txt_NoMov AND caja_chica_id_caja_chica='$id_cajaChica'";
		//Ejecutar la sentencia
		$rs = mysql_query($sql_stm);
		if($rs){
			$pptoActual = mysql_fetch_array(mysql_query("SELECT id_caja_chica FROM caja_chica ORDER BY SUBSTRING(id_caja_chica,-2) DESC,SUBSTRING(id_caja_chica,4,2) DESC"));
			//Crear la sentencia para actualizar el monto del presupuesto
			$sql_stm = "UPDATE caja_chica SET presupuesto=presupuesto+$txt_dif WHERE id_caja_chica='$pptoActual[0]'";
			if(isset($_SESSION['datosCajaChica']['presupuesto']))
				$_SESSION['datosCajaChica']['presupuesto']=$_SESSION['datosCajaChica']['presupuesto']+$txt_dif;
			//Ejecutar la sentencia
			$rs = mysql_query($sql_stm);
			if($rs){
				registrarOperacion("bd_compras",$id_cajaChica."-".$txt_NoMov,"ActualizarMovCajaChica",$_SESSION['usr_reg']);								
				//Extraer el presupuesto actualizado despues de guardar el movimiento y el incremento
				$presupuesto = obtenerDato("bd_compras","caja_chica","presupuesto","id_caja_chica","$id_cajaChica");
				return "¡La Actualizaci&oacute;n del Movimiento Fue Realizada con &Eacute;xito!";
			}
			else{
				//Redireccionar a la página de error
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}			
		}
		else{
			//Redireccionar a la página de error			
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		//Cerrar conexion con la BD
		mysql_close($conn);
	}//Fin de la funcion actualizarMovimiento($id_cajaChica,$txt_NoMov,$hdn_fechaMov,$txt_factura,$txt_responsable,$txa_descripcion,$txt_total)
?>
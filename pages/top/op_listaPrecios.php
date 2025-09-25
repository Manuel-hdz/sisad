<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Maurilio Hernández Correa                            
	  * Fecha: 06/Junio/2011                                     			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de lista de precios 
	  **/

	//si viene el el post el botón sbt_registrar, proceder a guardar 
	if(isset($_POST['sbt_registrar']))
		guardarListaPrecios();

	//Esta función se encarga de generar el Id de los precios de acurdo a los registros existentes en la BD
	function obtenerIdPrecio(){
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		
		//Definir las  letras en la Id del precio
		$id_cadena = "PTR";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener los precios del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de precios registrados
		$stm_sql = "SELECT COUNT(id_precios) AS cant FROM precios_traspaleo WHERE id_precios LIKE 'PTR$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
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
	}//Fin de la Funcion obtenerIdPrecio()			


	 //Funcion para guardar en la tabla precios traspaleo el id, tipo y descripción posteriormente se registra el detalle en detallePrecioTraspaleo()
	 function guardarListaPrecios(){
		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//guardar primero el detalle de la lista de precios, obteniendo la informacion de arreglos de session y post
		//Recoger los datos	 
		$id_precios=$_SESSION['preciosGral']['id_precios'];
		$tipoTraspaleo=$_SESSION['preciosGral']['tipoTraspaleo'];
		$descripcion=$_SESSION['preciosGral']['descripcion'];

		//Crear la Sentencias SQL para Alamcenar los datos de la lista de precios
		$stm_sql= "INSERT INTO precios_traspaleo (id_precios, tipo, descripcion) VALUES ('$id_precios','$tipoTraspaleo','$descripcion')";
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			detallePrecioTraspaleo();
			//Guardar la operacion realizada
			registrarOperacion("bd_topografia",$id_precios,"GenerarListaPrecios",$_SESSION['usr_reg']);
		}
		else{
			$error = mysql_error();
			//liberar los datos del arreglo de sesion
			unset ($_SESSION['preciosGral']);
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
	 }// Fin function precioTraspaleo()	

	//Funcion para guardar el detalle del precio traspaleo
	function detallePrecioTraspaleo(){ 				
		
		$id_precios=$_SESSION['preciosGral']['id_precios'];
		$cont=$_POST['hdn_contador'];
 		
		//Recorrer el arreglo que contiene el detalle
		 for($i=1; $i<=$cont; $i++){

			//Retirar la coma de cada uno de los elementos numéricos en caso que exista, y pasar a mayusculas para alamcenar el dato en la BD
			$dist_inicial = str_replace(",","",$_POST['txt_inicial'.$i]);						
			$dist_final = str_replace(",","",$_POST['txt_final'.$i]);	
			$unidad = strtoupper($_POST['txt_unidad'.$i]);
			$precioMN = str_replace(",","",$_POST['txt_precioMN'.$i]);	
			$precioUSD = str_replace(",","",$_POST['txt_precioUSD'.$i]);
			$color = $_POST['txt_color'.$i];

			//Crear la Sentencia SQL para Alamcenar el detalle del tipo de traspaleo 
			$stm_sql= "INSERT INTO lista_precios (precios_traspaleo_id_precios, distancia_inicio, distancia_fin, unidad, pu_mn, pu_usd, color)
			VALUES ('$id_precios', '$dist_inicial', '$dist_final', '$unidad', '$precioMN', '$precioUSD', '$color')";
			//Ejecutar la Sentencia 
			$rs=mysql_query($stm_sql);
			
			//Verificar Resultado
			if ($rs){
				$band=1;
			}
			else{
				$band=0;
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				//liberar los datos del arreglo de sesion
				unset ($_SESSION['preciosGral']);
			}
		}//fin del 	for($i=0; $i<$cont; $i++)
		if($band==1){
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			//liberar los datos del arreglo de sesion
			unset ($_SESSION['preciosGral']);
		}					
	}// Fin function detallePrecioTraspaleo()
	

	//***************************************************************************//	
	//***Funciones necesarias para el formulario frm_consultarListaPrecios.php***//
	//***************************************************************************//	

	//Funcion que se encarga de desplegar los traspaleos registrados en la bd
	function consultarListaPrecios(){
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
	
		//Crear sentencia SQL
		$sql_stm = "SELECT * FROM precios_traspaleo JOIN lista_precios ON id_precios = precios_traspaleo_id_precios WHERE id_precios='$_POST[cmb_tipoTraspaleo]'";	
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Lista de Precios del Traspaleo de <em><u>$_POST[cmb_tipoTraspaleo]</u></em> ";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;na Lista de Precios de <em><u>$_POST[cmb_tipoTraspaleo]</u></em>
					 </label>";										
	
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if( $datos=mysql_fetch_array($rs)){
		
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='2' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='20%'>TIPO TRASPALEO</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
				</tr>
				<tr>
					<td class='$nom_clase'>$datos[tipo]</td>
					<td class='$nom_clase'>$datos[descripcion]</td>
				</tr>
			</table>
			
			<table cellpadding='5' width='100%'>	
				<tr>
					<td class='nombres_columnas' align='center'>DISTANCIA INICIAL</td>
					<td class='nombres_columnas' align='center'>DISTANCIA FINAL</td>
					<td class='nombres_columnas' align='center'>UNIDAD</td>
					<td class='nombres_columnas' align='center'>PRECIO UNITARIO MN</td>
					<td class='nombres_columnas' align='center'>PRECIO UNITARIO USD</td>
					<td class='nombres_columnas' align='center'>COLOR</td>
				</tr>";
			do{				
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>

						<td class='$nom_clase'>$datos[distancia_inicio]</td>
						<td class='$nom_clase'>$datos[distancia_fin]</td>
						<td class='$nom_clase'>$datos[unidad]</td>
						<td class='$nom_clase'>$".number_format($datos['pu_mn'],2,".",".")."</td>
						<td class='$nom_clase'>$".number_format($datos['pu_usd'],2,".",".")."</td>";
				if($datos['color']!="FFFFFF")
					echo "<td bgcolor='#$datos[color]'>&nbsp;</td>";
				else
					echo "<td class='$nom_clase'>Sin Color</td>";
					
					
				echo"
					</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
		}
	}//Fin de la funcion consultarListaPrecios()	

	//***************************************************************************//	
	//***Funciones necesarias para el formulario frm_modificarListaPrecios.php***//
	//***************************************************************************//	

	//Funcion que se encarga de desplegar los traspaleos registrados en la bd para modificar los campos correspondientes
	function modificarListaPrecios(){
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
	
		//Crear sentencia SQL
		$sql_stm = "SELECT * FROM precios_traspaleo JOIN lista_precios ON id_precios = precios_traspaleo_id_precios WHERE id_precios='$_POST[cmb_tipoTraspaleo]'";	
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Lista de Precios del Traspaleo de <em><u>$_POST[cmb_tipoTraspaleo]</u></em> ";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;na Lista de Precios de <em><u>$_POST[cmb_tipoTraspaleo]</u></em> 			
					</label>";										
	
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if( $datos=mysql_fetch_array($rs)){
		
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='7' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='20%'>TIPO TRASPALEO</td>
					<td class='nombres_columnas' align='center' colspan='6'>DESCRIPCI&Oacute;N</td>
				</tr>
				<tr>
					<td class='$nom_clase'>$datos[tipo]</td>
					<td class='$nom_clase' colspan='6'>$datos[descripcion]</td>
				</tr>
			</table>
			
			<table cellpadding='5' width='100%'>	
				<tr>
					<td class='nombres_columnas' align='center'>MODIFICAR</td>
					<td class='nombres_columnas' align='center'>DISTANCIA INICIAL</td>
					<td class='nombres_columnas' align='center'>DISTANCIA FINAL</td>
					<td class='nombres_columnas' align='center'>UNIDAD</td>
					<td class='nombres_columnas' align='center'>PRECIO UNITARIO MN</td>
					<td class='nombres_columnas' align='center'>PRECIO UNITARIO USD</td>
					<td class='nombres_columnas' align='center'>COLOR</td>					
				</tr>";
			do{				
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td  class='$nom_clase' align='center'>"; ?>
							<input type="checkbox" name="ckb_precio<?php echo $cont;?>" id="ckb_precio<?php echo $cont;?>" 
							value="<?php echo $datos['distancia_inicio']?>" onClick="activarCampos(this, <?php echo $cont; ?>)"/><?php echo" 
						</td>	
						<td class='$nom_clase'>$datos[distancia_inicio]</td>
						<td class='$nom_clase'>$datos[distancia_fin]</td>
						<td class='$nom_clase'>$datos[unidad]</td>
						<td  class='$nom_clase' align='center'>"; ?>
							<input type="text" name="txt_precioMN<?php echo $cont;?>" id="txt_precioMN<?php echo $cont;?>" size="10" maxlength="20" disabled="disabled"
							onkeypress="return permite(event,'num', 2);" value="<?php echo $datos['pu_mn']?>" class="caja_de_texto"/><?php echo "
						</td>
						<td  class='$nom_clase' align='center'>"; ?>
							<input type="text" name="txt_precioUSD<?php echo $cont;?>" id="txt_precioUSD<?php echo $cont;?>" size="10" maxlength="20" 
                            disabled="disabled" onkeypress="return permite(event,'num', 2);" value="<?php echo $datos['pu_usd']?>" class="caja_de_texto"/><?php echo "
						</td>
						<td  class='$nom_clase' align='center'>"; ?>
							<input type="text" name="txt_color<?php echo $cont;?>" id="txt_color<?php echo $cont;?>" size="6" maxlength="6" disabled="disabled"
                            onkeypress="return permite(event,'num_car', 3);" value="<?php echo $datos['color']?>" class="color" title="Seleccionar Color" /><?php echo "
						</td>
					</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			
			//Fin de la tabla donde se muestran los resultados de la consulta
			$ckbs= $cont-1;
			echo "<tr>
						<td><input type='hidden' name='cant_ckbs' id='cant_ckbs' value='$ckbs'/></td>
					</tr>
				</table>";				
		}// fin  if($datos=mysql_fetch_array($rs))
		
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
		}
	}//Fin de la funcion modificarListaPrecios()	


	//Funcion que se encarga guardar los precios de la lista de precios seleccionados y modificados
	function modificarPrecios(){
		$id_distancia_inicio= array();
		$pu_mn=array();
		$pu_usd=array();
		$color=array();
		
		foreach($_POST as $clave=> $valor) {
			if(substr($clave, 0,10)=='ckb_precio'){
				$id_pu_mn[] = $valor;
			}
		 	if(substr($clave, 0,12)=='txt_precioMN'){
				$pu_mn[] = $valor;
			}
		 	if(substr($clave, 0,13)=='txt_precioUSD'){
				$pu_usd[] = $valor;
			}
			if(substr($clave, 0,9)=='txt_color'){
				$color[] = $valor;
			}
		 }
		
		
		$tam = count($id_pu_mn);
		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_topografia");
		$status = 0;
		
		for($i=0; $i<$tam; $i++){
			
			$sql_stm="UPDATE lista_precios SET pu_mn= $pu_mn[$i], pu_usd=$pu_usd[$i], color='$color[$i]' WHERE distancia_inicio='$id_pu_mn[$i]'
						AND precios_traspaleo_id_precios='$_POST[hdn_idTraspaleo]'";					
		
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($sql_stm);		
			
			//Verificar Resultado
			if(!$rs){
				$status = 1;
				break;
			}				
			
		 }// fin  for($i=0; $i<$tam; $i++)
		 
		//Verificar Resultado
		if($status==0){
			//Guardar el registro de movimientos
			registrarOperacion("bd_topografia",$_POST['hdn_idTraspaleo'],"ModificarListaPrecio",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}		 		 
		
	} // fin de la function modificarPrecios()
	
	
	//funcion que se se encarga de realizar el calculo de las distancias y los rangos
	function generarRangos(){
		//recuperar del posto los datos necesarios para realizar los calculos pertinentes
		$dist_inicial= floatval(str_replace(",","",$_POST['txt_distancia_inicio']));
		$dist_final= floatval(str_replace(",","",$_POST['txt_distancia_fin']));
		$dist_intervalo=floatval(str_replace(",","",$_POST['txt_distanciaIntervalo']));
		
		//comenzar a calcular la cantidad total de intervalos
		$cant_Intervalos= ($dist_final-$dist_inicial)/$dist_intervalo;
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		$cant_total = 0;
			
		echo "<table cellpadding='5' width='900'>";
		echo " 
			<tr>
				<td class='nombres_columnas' align='center'>TIPO TRASPALEO</td>
        		<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
      		</tr>";
		echo "<tr>
			<td class='$nom_clase' align='center'>". $_SESSION['preciosGral']['tipoTraspaleo']."</td>
			<td class='$nom_clase' align='center'>". $_SESSION['preciosGral']['descripcion']."</td>
		</tr>
		</table>
		<table cellpadding='5' width='900'> " ;		
			
		//Desplegar los resultados
		echo "							
		<table cellpadding='5' width='100%'>	
			<tr>
				<td class='nombres_columnas' align='center'>DISTANCIA INICIAL</td>
				<td class='nombres_columnas' align='center'>DISTANCIA FINAL</td>
				<td class='nombres_columnas' align='center'>UNIDAD</td>
				<td class='nombres_columnas' align='center'>PRECIO UNITARIO MN</td>
				<td class='nombres_columnas' align='center'>PRECIO UNITARIO USD</td>
				<td class='nombres_columnas' align='center'>COLOR</td>
			</tr>";
			
			//realizar los calculos para obtener la distancia inicial y final correspondientes para cada caso
			$inicial=$dist_inicial;
			$final=$dist_intervalo+$dist_inicial;		
			//Delimitar el Ultimo rango para que no exceda el limite superior del rango general de Distancias
			if($final>$dist_final){
				$final = $dist_final;
			}
			

			for ($ind=0; $ind<$cant_Intervalos; $ind++){	
			
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase' align='center'>";?> 
							<input type="text" name="txt_inicial<?php echo $cont;?>" id="txt_inicial<?php echo $cont;?>" size="7" readonly="readonly"
							value="<?php echo $inicial; ?>" class="caja_de_num"/><?php echo" 
						</td>
						<td class='$nom_clase' align='center'>";?> 
							<input type="text" name="txt_final<?php echo $cont;?>" id="txt_final<?php echo $cont;?>" size="7" readonly="readonly"
							value="<?php echo $final; ?>" class="caja_de_num"/><?php echo" 
						</td>
						<td  class='$nom_clase' align='center'>"; ?>
							<input type="text" name="txt_unidad<?php echo $cont;?>" id="txt_unidad<?php echo $cont;?>" size="10" maxlength="20"
							onkeypress="return permite(event,'num_car', 2);" value="" class="caja_de_texto"/><?php echo "
						</td>
						<td  class='$nom_clase' align='center'>"; ?>
							$<input type="text" name="txt_precioMN<?php echo $cont;?>" id="txt_precioMN<?php echo $cont;?>" size="10" maxlength="20"
							onkeypress="return permite(event,'num', 2);" value="" class="caja_de_num"
                            onchange="if(!validarEntero(this.value.replace(/,/g,''),'Precio MN')){ this.value = ''; }
                            else {formatCurrency(this.value,'txt_precioMN<?php echo $cont;?>');}"/><?php echo "
						</td>
						<td  class='$nom_clase' align='center'>"; ?>
							$<input type="text" name="txt_precioUSD<?php echo $cont;?>" id="txt_precioUSD<?php echo $cont;?>" size="10" maxlength="20"
							onkeypress="return permite(event,'num', 2);" value="" class="caja_de_num"
                            onchange="if(!validarEnteroConCero(this.value.replace(/,/g,''),'Precio USD')){ this.value = ''; }
                            else {formatCurrency(this.value,'txt_precioUSD<?php echo $cont;?>');}"/><?php echo "
						</td>
						<td  class='$nom_clase' align='center'>"; ?>
							<input type="text" name="txt_color<?php echo $cont;?>" id="txt_color<?php echo $cont;?>" size="6" maxlength="6"
							onkeypress="return permite(event,'num_car', 3);" value="FFFFFF" title="Seleccionar Color" class="color" /><?php echo "
						</td>												
					</tr>";

				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
						
				//reasignar el valor para el sig registro
				$inicial = $final + 1;
				$final = $final + $dist_intervalo;
				
				//Delimitar el Ultimo rango para que no exceda el limite superior del rango general de Distancias
				if($final>$dist_final){
					$final = $dist_final;
				}
				

			}//FIN for ($ind=0; $ind<$cant_Intervalos; $ind++)
			
			//Fin de la tabla donde se muestran los resultados
			echo "
				</table>";
				?><input type="hidden" name="hdn_contador" id="hdn_contador" value="<?php echo $cont-1; ?>"/><?php
	}//	fin de la function generarRangos()

?>
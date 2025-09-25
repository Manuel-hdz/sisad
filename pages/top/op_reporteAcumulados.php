<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 18/Junio/2011
	  * Descripción: Este archivo contiene funciones para crear el Reporte de Acumulado de Metros Cubicos movidos en las diferentes distancias de la Obras
	  *				 consideradas para Costos y para Amortizaciones
	**/


	function analizarDetalleTraspaleos(){
		//Conectarse a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//Obtener los datos de la Quincena del POST
		$noQuincena = $_POST['cmb_noQuincena']." ".$_POST['cmb_mes']." ".$_POST['cmb_anio'];
		
		
		/******************************************************************************************************/
		/***************************************OBRAS AMORTIZABLES*********************************************/
		/******************************************************************************************************/		
		//Obtener los Intervalos de las distancias para hecer el acumulado de Metros Cubicos Movidos
		$obrasAmortizables = obtenerIntervalos($noQuincena,"AMORTIZABLE");						
		
		//Hacer el Analisis de los datos de la Obras Amortizables
		$sql_stm = "SELECT id_obra,volumen,origen,destino,distancia,importe_total FROM (obras JOIN traspaleos ON id_obra=obras_id_obra) 
					JOIN detalle_traspaleos ON id_traspaleo=traspaleos_id_traspaleo 
					WHERE no_quincena='$noQuincena' AND categoria='AMORTIZABLE' ORDER BY id_obra";
		//Ejecutar la Consulta			
		$rs = mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			do{
				//Obtener cada una de las idObra, Distancias, Destinos e Importe Total de las Obras Incluidas en la Quincena Seleccionada
				$idObra = $datos['id_obra'];
				$distancia = $datos['distancia'];
				$destino = $datos['destino'];				
				$importeTotal = $datos['importe_total'];				
				
				//Si el Registro no tiene Costo, no considerar para el Reporte de Acumulados
				if($importeTotal>0){
					//Si la Distancia va de 0 a 50 se considera como VACIADERO
					if($distancia<50){
						$obrasAmortizables["VACIADERO"] += $datos['volumen'];	
						//Definir color para el Vaciadero
						if(!isset($obrasAmortizables["colorVaciadero"]))
							$obrasAmortizables["colorVaciadero"] = "00B050";
					}
					//Si en el Destino se Indica APLANILLE se considerar el registro dentro de dicha categoria para obtener los precios
					else if($destino=="APLANILLE"){
						$obrasAmortizables["APLANILLE"] += $datos['volumen'];					
						//Definir color para el Aplanille
						if(!isset($obrasAmortizables["colorAplanille"]))
							$obrasAmortizables["colorAplanille"] = "948B54";
					}				
					else{
						//Recorrer el Arreglo que contiene los Intervalos de las Obras de Amortizaciones
						foreach($obrasAmortizables as $ind => $rango){																								
							//Omitir los Indices de "VACIADERO" y "APLANILLE", ya que no no contienen Limite Inferior y Superior para ubicar la Distancia en turno
							if( !($ind==="VACIADERO" || $ind==="APLANILLE")){																																															
								$limInferior = floatval($rango['limInferior']);
								$limSuperior = floatval($rango['limSuperior']);
								
								
								//Obtener el Color del Rango sin Importar si se registrar volumen en el o no								
								if(!isset($obrasAmortizables[$ind]["color"]))
									$obrasAmortizables[$ind]["color"] = obtenerColorRango($limInferior,$limSuperior,$idObra);

																												
								//Ubicar la distancia en los Intervalos del Arreglo de los rangos para las Obras de Amortizaciones y Acumular el Volumen
								if($distancia>=$limInferior && $distancia<=$limSuperior){
									$obrasAmortizables[$ind]['volumen'] += $datos['volumen'];									
									//Romper el ciclo del Foreach cuando el Volumen de la Distancia ha sido Agregado
									break;
								}
							}						
						}//Cierre foreach($obrasAmortizables as $ind => $rango)
					}
				}//Cierre if($importeTotal>0)								
			}while($datos=mysql_fetch_array($rs));
		}
		
		
		
		
		/******************************************************************************************************/
		/***************************************OBRAS DE COSTOS************************************************/
		/******************************************************************************************************/
		//Obtener los Intervalos de las distancias para hecer el acumulado de Metros Cubicos Movidos
		$obrasCostos = obtenerIntervalos($noQuincena,"COSTOS");				
				
		//Hacer el Analisis de los datos de la Obras de Costos
		$sql_stm = "SELECT id_obra,volumen,origen,destino,distancia,importe_total FROM (obras JOIN traspaleos ON id_obra=obras_id_obra) 
					JOIN detalle_traspaleos ON id_traspaleo=traspaleos_id_traspaleo 
					WHERE no_quincena='$noQuincena' AND categoria='COSTOS' ORDER BY id_obra";
		//Ejecutar la Consulta			
		$rs = mysql_query($sql_stm);				
		
		if($datos=mysql_fetch_array($rs)){
			do{
				//Obtener cada una de las IdObra, Distancias y Destinos de las Obras Incluidas en la Quincena Seleccionada
				$idObra = $datos['id_obra'];
				$distancia = $datos['distancia'];
				$destino = $datos['destino'];
				$importeTotal = $datos['importe_total'];				
				
				//Si el Registro no tiene Costo, no considerar para el Reporte de Acumulados
				if($importeTotal>0){								
					//Si el Destino "VACIADERO" acumular el volumen en el indice correspondiente
					if($distancia<50){					
						$obrasCostos["VACIADERO"] += $datos['volumen'];				
						//Definir color para el Vaciadero
						if(!isset($obrasCostos["colorVaciadero"]))
							$obrasCostos["colorVaciadero"] = "00B050";
					}
					else if($destino=="APLANILLE"){
						$obrasCostos["APLANILLE"] += $datos['volumen'];					
						//Definir color para el Aplanille
						if(!isset($obrasCostos["colorAplanille"]))
							$obrasCostos["colorAplanille"] = "948B54";
					}				
					else{
						//Recorrer el Arreglo que contiene los Intervalos de las Obras de Costos
						foreach($obrasCostos as $ind => $rango){
							//Omitir los Indices de "VACIADERO" y "APLANILLE", ya que no no contienen Limite Inferior y Superior para ubicar la Distancia en turno
							if( !($ind==="VACIADERO" || $ind==="APLANILLE")){																																															
								$limInferior = floatval($rango['limInferior']);
								$limSuperior = floatval($rango['limSuperior']);												
								
								
								//Obtener el Color del Rango sin Importar si se registrar volumen en el o no								
								if(!isset($obrasCostos[$ind]["color"]))
									$obrasCostos[$ind]["color"] = obtenerColorRango($limInferior,$limSuperior,$idObra);
								
								
								//Ubicar la distancia en los Intervalos del Arreglo de los rangos para las Obras de Amortizaciones
								if($distancia>=$limInferior && $distancia<=$limSuperior){
									$obrasCostos[$ind]['volumen'] += $datos['volumen'];																	
									//Romper el ciclo del Foreach cuando el Volumen de la Distancia ha sido Agregado
									break;
								}
							}						
						}//Cierre foreach												
					}
				}//Cierre if($importeTotal>0)
			}while($datos=mysql_fetch_array($rs));		
		}		
		
		
		//Si el el campo de color no va asociado a ningun campo 
		
		
		//Mostrar los datos encontrados
		mostrarReporteAcumulado($obrasAmortizables,$obrasCostos,$noQuincena);
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion analizarDetalleTraspaleos()
	
	
	/*Esta funcion obtiene los Intervalos de las distancias para las Obras de Costos y Amortizaciones*/
	function obtenerIntervalos($noQuincena,$categoriaObras){
		//Obtener la Distancia Maxima de los registros para crear los Intervalos 
		$rs_maxima = mysql_query("SELECT MAX(distancia) AS maxima FROM (obras JOIN traspaleos ON id_obra=obras_id_obra) 
								JOIN detalle_traspaleos ON id_traspaleo=traspaleos_id_traspaleo 
								WHERE no_quincena='$noQuincena' AND categoria='$categoriaObras' ORDER BY id_obra");
		$datos_maxima = mysql_fetch_array($rs_maxima);
		$distancia_max = $datos_maxima['maxima'];
		
		//Redondear a Centenas la distancia maxima para obtener el Limite Superior del Ultimo Intervalo
		$limiteSuperior = round($distancia_max,-2);
		
		//Determinar si el Limite Superior es correcto de acuerdo al redondeo hecho por encima o debajo de '50'
		//Ej. 	Distancia Maxima 573.23	=>	Limite Superior 600	=> OK
		//		Distancia Maxima 525.23	=>	Limite Superior 500	=> Error
		$decenas = 0;
		if(!strpbrk($distancia_max,'.')){//Si NO hay punto en la distancia, obtener los dos ultimos digitos del numero
			$decenas = intval(substr($distancia_max,-2));
		}
		else{//Si hay un punto obtener los dos primeros digitos a la izquierda del punto	
			$numeros = split("[.]",$distancia_max);
			$decenas = intval(substr($numeros[0],-2));
		}
		
		
		//Si las decenas son menores a 50, aumentar en 100 el limite superior
		if($decenas>0 && $decenas<50)
			$limiteSuperior += 100;
								
		//Esta variable indicara si fueron registrados los rangos en el arreglo en turno
		$status = 0;
		
		//Crear los Intervalos del Arreglo Correspondiente
		$arrIntervalos = array();
		$limInferiorIncremental = 0;
		//Iterar mientras el limite inferior incremental no exceda el limite superior calculado
		while($limInferiorIncremental<$limiteSuperior){
			//Colocar el Primer intervalo
			if($limInferiorIncremental==0)
				$arrIntervalos[] = array("limInferior"=>$limInferiorIncremental,"limSuperior"=>$limInferiorIncremental+100,"volumen"=>0);			
			else
				$arrIntervalos[] = array("limInferior"=>$limInferiorIncremental+1,"limSuperior"=>$limInferiorIncremental+100,"volumen"=>0);
			
			//Incrementar el Limite Inferior para calcular el Sig. Rango
			$limInferiorIncremental += 100;
			
			//Si la variable ya cambio su valor dentro de este ciclo, ya no cambiarla en las Iteraciones restantes
			if($status==0)
				$status = 1;
		}
		
		
		//Si fueron agregados rangos al Arreglo, tambien agregar las opciones de Aplanille y Vaciadero junto con sus colores
		if($status==1){
			$arrIntervalos["VACIADERO"] = 0;			
			$arrIntervalos["APLANILLE"] = 0;			
			$arrIntervalos["colorAplanille"] = "948B54";
			$arrIntervalos["colorVaciadero"] = "00B050";
		}
			
		
		//Retornar el Arreglo creado con los Rangos
		return $arrIntervalos;
	}//Cierre de la funcion obtenerIntervalos($noQuincena,$mes,$anio,$categoriaObras)
	
	
	/*Obtener el Color correspondiente al Intervalo en la lista de precios Asociada a las Obras*/
	function obtenerColorRango($limInferior,$limSuperior,$idObra){
		//Crear la Sentencia para obtener el color correspondiente al rango de precios asociado a la Obra indicada
		$sql_stm = "SELECT color FROM (lista_precios JOIN precios_traspaleo ON precios_traspaleo_id_precios=id_precios) JOIN obras ON id_precios=obras.precios_traspaleo_id_precios
					WHERE id_obra = '$idObra' AND distancia_inicio=$limInferior AND distancia_fin=$limSuperior";
		//Ejecutar la Setencia
		$rs = mysql_query($sql_stm);
		
		//Extraer los datos y retornar el valor encontrado, de lo contrario regresar vacio
		if($datos=mysql_fetch_array($rs))
			return $datos['color'];		
		else
			return "";		
	}//Cierre de la funcion obtenerColorRango($limInferior,$limSuperior,$idObra)
	
	
	
	/*Esta funcion mustra los resultados encontrados*/
	function mostrarReporteAcumulado($obrasAmortizables,$obrasCostos,$noQuincena){?>
		<div id="tabla-amortizaciones"><?php		

		//Desplegar los datos de las Obras Amortizables
		if(count($obrasAmortizables)>0){
			//Colocar el Encabezado de la Tabla?>	        
			<table width="60%" cellpadding="5" class="tabla_frm">
				<tr><td colspan="3" class="titulo_etiqueta">AMORTIZABLE</td></tr>
				<tr>
					<td width="35%" class="nombres_columnas">Distancia</td>
					<td width="35%" class="nombres_columnas">M&sup3;</td>
					<td width="30%" class="nombres_columnas">Color</td>
				</tr><?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$total = 0;
			//Recorrer cada uno de los registros del Acumulado de Obras Amortizables junto con el Color
			foreach($obrasAmortizables as $ind => $rango){
				//Desplegar el Renglon que muestra el Acumulado en VACIADERO junto con el Color
				if($ind==="VACIADERO"){?>
					<tr>
						<td class="<?php echo $nom_clase; ?>">VACIADERO</td>
						<td class="<?php echo $nom_clase; ?>"><?php echo number_format($obrasAmortizables['VACIADERO'],2,".",","); ?></td>						
						<td bgcolor="#<?php echo $obrasAmortizables['colorVaciadero']; ?>">&nbsp;</td>
					</tr><?php				
					//Sumatoria de los volumenes
					$total += $obrasAmortizables['VACIADERO'];
				}
				//Desplegar el Renglon que muestra el Acumulado en APLANILLE junto con el Color
				else if($ind==="APLANILLE"){?>
					<tr>
						<td class="<?php echo $nom_clase; ?>">APLANILLE</td>
						<td class="<?php echo $nom_clase; ?>"><?php echo number_format($obrasAmortizables['APLANILLE'],2,".",","); ?></td>
						<td bgcolor="#<?php echo $obrasAmortizables['colorAplanille']; ?>">&nbsp;</td>
					</tr><?php				
					//Sumatoria de los volumenes
					$total += $obrasAmortizables['APLANILLE'];
				}
				//Desplegar los Renglones con los Diferentes rangos enocntrados en el Analisis
				else if( !($ind==="colorVaciadero" || $ind==="colorAplanille") ) {?>
					<tr>
						<td class="<?php echo $nom_clase; ?>"><?php echo $rango['limInferior']." - ".$rango['limSuperior']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo number_format($rango['volumen'],2,".",","); ?></td><?php
						//Si esta disponible el color evaluar si tiene uno asignado y mostrarlo
						if(isset($rango['color'])){
							if($rango['color']=="" || $rango['color']=="FFFFFF"){?>
								<td class="<?php echo $nom_clase; ?>">Sin Color</td><?php
							} else {?>
								<td bgcolor="#<?php echo $rango['color']; ?>">&nbsp;</td><?php
							}
						}else{//Si no esta disponible el color indicar que no hay color registrado?>
							<td class="<?php echo $nom_clase; ?>">Sin Color</td><?php
						}?>						
					</tr><?php				
					//Sumatoria de los volumenes
					$total += $rango['volumen'];
				}
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}//Cierre foreach($obrasAmortizables as $ind => $rango)
			
			
				//Desplegar el Total de Metros Cubicos Movidos en las Diferentes Distancias?>
				<tr>
					<td class="<?php echo $nom_clase; ?>"><strong>Total</strong></td>
					<td class="<?php echo $nom_clase; ?>" colspan="2" align="left"><strong><?php echo number_format($total,2,".",","); ?></strong></td>
				</tr><?php
				
				//Obtener el Costo Total de las Obras Amortizables
				$rs_costoTotal = mysql_query("SELECT SUM(importe_total) AS total FROM (obras JOIN traspaleos ON id_obra=obras_id_obra) 
											  JOIN detalle_traspaleos ON id_traspaleo=traspaleos_id_traspaleo 
											  WHERE no_quincena='$noQuincena' AND categoria='AMORTIZABLE'");
				$datos_costoTotal = mysql_fetch_array($rs_costoTotal);
				//Cambiar el Color del Renglon
				if($nom_clase=="renglon_blanco")
					$nom_clase = "renglon_gris";
				else
					$nom_clase = "renglon_blanco"?>
				<tr>
					<td class="<?php echo $nom_clase; ?>"><strong>Costo Total</strong></td>
					<td class="<?php echo $nom_clase; ?>" colspan="2" align="left"><strong><?php echo "$ ".number_format($datos_costoTotal['total'],2,".",","); ?></strong></td>
				</tr>																
			</table><?php
		}
		else{?>
			<label class="msje_correcto">No Hay Registros Para Mostrar en las Obras de Amortizaciones en la Quincena:<br><?php echo $noQuincena; ?></label><?php
		}?>		
		</div>
		
		
		
		
		
		<div id="tabla-costos"><?php
		//Desplegar los datos de las Obras de Costos		
		if(count($obrasCostos)>0){
			//Colocar el Encabezado de la Tabla?>						
			<table width="60%" cellpadding="5" class="tabla_frm">
				<tr><td colspan="3" class="titulo_etiqueta">COSTOS</td></tr>
				<tr>
					<td width="30%" class="nombres_columnas">Distancia</td>
					<td width="30%" class="nombres_columnas">M&sup3;</td>
					<td width="30%" class="nombres_columnas">Color</td>
				</tr><?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$total = 0;
			//Recorrer cada uno de los registros del Acumulado de Obras de Costos junto con el Color
			foreach($obrasCostos as $ind => $rango){
				//Desplegar el Renglon que muestra el Acumulado en VACIADERO junto con el Color
				if($ind==="VACIADERO"){?>
					<tr>
						<td class="<?php echo $nom_clase; ?>">VACIADERO</td>
						<td class="<?php echo $nom_clase; ?>"><?php echo number_format($obrasCostos['VACIADERO'],2,".",","); ?></td>
						<td bgcolor="#<?php echo $obrasCostos['colorVaciadero']; ?>">&nbsp;</td>
					</tr><?php				
					//Sumatoria de los volumenes
					$total += $obrasCostos['VACIADERO'];
				}
				//Desplegar el Renglon que muestra el Acumulado en APLANILLE junto con el Color
				else if($ind==="APLANILLE"){?>
					<tr>
						<td class="<?php echo $nom_clase; ?>">APLANILLE</td>
						<td class="<?php echo $nom_clase; ?>"><?php echo number_format($obrasCostos['APLANILLE'],2,".",","); ?></td>
						<td bgcolor="#<?php echo $obrasCostos['colorAplanille']; ?>">&nbsp;</td>
					</tr><?php				
					//Sumatoria de los volumenes
					$total += $obrasCostos['APLANILLE'];
				}
				//Desplegar los Renglones con los Diferentes rangos enocntrados en el Analisis
				else if( !($ind==="colorVaciadero" || $ind==="colorAplanille") ) {?>
					<tr>
						<td class="<?php echo $nom_clase; ?>"><?php echo $rango['limInferior']." - ".$rango['limSuperior']; ?></td>
						<td class="<?php echo $nom_clase; ?>"><?php echo number_format($rango['volumen'],2,".",","); ?></td><?php
						//Si esta disponible el color evaluar si tiene uno asignado y mostrarlo
						if(isset($rango['color'])){
							if($rango['color']=="" || $rango['color']=="FFFFFF"){?>
								<td class="<?php echo $nom_clase; ?>">Sin Color</td><?php
							} else {?>
								<td bgcolor="#<?php echo $rango['color']; ?>">&nbsp;</td><?php
							}
						}else{//Si no esta disponible el color indicar que no hay color registrado?>
							<td class="<?php echo $nom_clase; ?>">Sin Color</td><?php
						}?>
					</tr><?php	
					//Sumatoria de los volumenes
					$total += $rango['volumen'];
				}
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";			
			}//Cierre foreach($obrasCostos as $ind => $rango)
			
			
				//Desplegar el Total de Metros Cubicos Movidos en las Diferentes Distancias?>
				<tr>
					<td class="<?php echo $nom_clase; ?>"><strong>Total</strong></td>
					<td class="<?php echo $nom_clase; ?>" colspan="2" align="left"><strong><?php echo number_format($total,2,".",","); ?></strong></td>
				</tr><?php
				
				//Obtener el Costo Total de las Obras Amortizables
				$rs_costoTotal = mysql_query("SELECT SUM(importe_total) AS total FROM (obras JOIN traspaleos ON id_obra=obras_id_obra) 
											  JOIN detalle_traspaleos ON id_traspaleo=traspaleos_id_traspaleo 
											  WHERE no_quincena='$noQuincena' AND categoria='COSTOS'");
				$datos_costoTotal = mysql_fetch_array($rs_costoTotal);
				//Cambiar el Color del Renglon
				if($nom_clase=="renglon_blanco")
					$nom_clase = "renglon_gris";
				else
					$nom_clase = "renglon_blanco"?>
				<tr>
					<td class="<?php echo $nom_clase; ?>"><strong>Costo Total</strong></td>
					<td class="<?php echo $nom_clase; ?>" colspan="2" align="left"><strong><?php echo "$ ".number_format($datos_costoTotal['total'],2,".",","); ?></strong></td>
				</tr>
			</table><?php
		}
		else{?>
			<label class="msje_correcto">No Hay Registros Para Mostrar en las Obras de Costos en la Quincena:<br><?php echo $noQuincena; ?></label><?php
		}?>			
								
		</div>
		
		</div><?php //Este es el cierre del DIV "reporte-acumuldo" declarado en el Archivo op_reporteAcumulados.php?>
		
				
		<div id='btn-regresar' align="center">
			<table width="50%">
				<tr>
					<td align="right">
						<form name="frm_regresarConciliacion" action="frm_consultarConciliacion.php" method="post"> 
							<input type="hidden" name="cmb_noQuincena" value="<?php echo $_POST['cmb_noQuincena'];?>" />
							<input type="hidden" name="cmb_mes" value="<?php echo $_POST['cmb_mes'];?>" />
							<input type="hidden" name="cmb_anio" value="<?php echo $_POST['cmb_anio'];?>" />
							<input type="hidden" name="sbt_consultar" value="Consultar" />
							<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar al Reporte de la Conciliaci&oacute;n" onmouseover="window.status='';return true" />
						</form>
					</td>
					<td align="left">
						<form name="frm_exportarDatos" method="post" action="guardar_reporte.php"><?php
							//Subir los datos del Reporte a SESSION
							$_SESSION['reporteAcumulados'] = array("obrasAmortizables"=>$obrasAmortizables,"obrasCostos"=>$obrasCostos,"noQuincena"=>$noQuincena);?>
							<input type="hidden" name="hdn_origen" value="reporteAcumulados" />
							<input type="hidden" name="hdn_consulta" value="" />	
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar Datos" title="Exportar Reporte de Acumulados a Excel" onmouseover="window.status='';return true"/>
						</form>
					</td>					
			</table>
		</div><?php
	}//Cierre de la funcion mostrarReporteAcumulado($obrasCostos,$obrasAmortizables)

?>
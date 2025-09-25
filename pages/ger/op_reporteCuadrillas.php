<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 01/Agosto/2011
	  * Descripción: Este archivo contiene las funciones para crear el reporte mensual de rendimientos
	**/



	/*Esta funcion genera el reporte mensual y regresa el periodo para indicar que los datos mostrados pueden ser exportados*/
	function verReporteMensual($periodo){		
		//Conectarse a la BD de Gerencia Técnica
		$conn = conecta("bd_gerencia");
		//Obtener las fechas del periodo seleccionado registradas en el Presupuesto
		$datosPeriodo = mysql_fetch_array(mysql_query("SELECT DISTINCT fecha_inicio,fecha_fin FROM presupuesto WHERE periodo = '$periodo'"));		
		$fechaIni = $datosPeriodo['fecha_inicio'];
		$fechaFin = $datosPeriodo['fecha_fin'];						
		
		
		//Obtener el año de inicio y el año de fin de las fechas que componen el periodo
		$anioInicio = substr($fechaIni,0,4);
		$anioFin = substr($fechaFin,0,4);
		
		//Separar el valor del Periodo para obtener los meses, aqui se considera que los periodos son siempre de dos meses consecutivos
		$nomMesInicio = obtenerNombreCompletoMes(substr($periodo,5,3));
		$nomMesFin = obtenerNombreCompletoMes(substr($periodo,9,3));
		
		//Obtener los dias del mes de Inicio del periodo
		$diasMesInicio = diasMes(obtenerNumMes($nomMesInicio), $anioInicio);						
		
		//Obtener el ancho en dias de los meses que componen el periodo
		$anchoDiasInicio = $diasMesInicio - intval(substr($fechaIni,-2)) + 1;
		$anchoDiasFin = intval(substr($fechaFin,-2));
		$totalDias = $anchoDiasInicio + $anchoDiasFin;				
		
		//Obtener los datos de la Bitacora de Zarpeo, primero obtener las Ubicaciones, luego las cuadrillas y por ultimo el registro individual de cada trabajador
		$rs_ubicaciones = mysql_query("SELECT DISTINCT destino FROM bitacora_zarpeo WHERE fecha>='$fechaIni' AND fecha<='$fechaFin'");
		
		//Revisar si existen UBICACIONES para mostrar
		if($ubicaciones=mysql_fetch_array($rs_ubicaciones)){																												
					
			//Este arreglo obtendra el total por dia de todas la ubicaciones registradas
			$sumTotalPorDia = array();
			$ctrlInicializacion = 0;
			
			do{//UBICACIONES								
			
				//Obtener la Ubicacion Actual
				$ubicacion = $ubicaciones['destino'];
				
				
				//Obtener el volumen diario y total del periodo con el id de la ubicacion de la tabla de Presupuesto 
				$idUbicacion = obtenerDato("bd_gerencia", "catalogo_ubicaciones", "id_ubicacion", "ubicacion", $ubicacion);				
				$datosPeriodo = mysql_fetch_array(mysql_query("SELECT DISTINCT vol_ppto_dia,vol_ppto_mes FROM presupuesto WHERE periodo = '$periodo' AND catalogo_ubicaciones_id_ubicacion = $idUbicacion"));		
			
				//Obtener el Presupuesto Total y el Diario por Cada Ubicacion mostrada
				$presMensual = $datosPeriodo['vol_ppto_mes'];
				$presDiario = $datosPeriodo['vol_ppto_dia'];
				
			
				//Arreglos para almacenar los totales que se muestran al final de cada Ubicación que es Mostrada
				$sumPorDia = array();//Arreglo que contendra la suma de los lanzamientos hechos por día
				$prodRealPorDia = array();//Arreglo que contendra el volumen real de los lanzamientos por dia acumulados
				$prodPresPorDia = array();//Arreglo que contendra el volumen presupuestado de los lanzamientos por dia acumulados
				$difPorDia = array();//Arrelo que contendrá la diferencia respecto				
				
																																		
				/***********************DIBUJAR EL ENCABEZADO DE LA TABLA**********************/?>
				<table border="0" cellpadding="5" class="tabla_frm" width="250%">
				<caption align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bolder;">
					Reporte Mensual del Periodo <em><u><?php echo $periodo;?></u></em>
				</caption>
				<tr>
					<td rowspan="2" width="15%" class="nombres_columnas" align="center" valign="middle"><?php echo $ubicaciones['destino'];?></td>
					<td rowspan="2" class="nombres_columnas" align="center" valign="middle">PUESTO</td>
					<td rowspan="2" class="nombres_columnas" align="center" valign="middle">CUADRILLA</td>					
					<td colspan="<?php echo $anchoDiasInicio; ?>" class="nombres_columnas" align="center" valign="middle"><?php echo $nomMesInicio."&nbsp;".$anioInicio; ?></td>	
					<td colspan="<?php echo $anchoDiasFin; ?>" class="nombres_columnas" align="center" valign="middle"><?php echo $nomMesFin."&nbsp;".$anioFin; ?></td>	
					<td rowspan="2" class="nombres_columnas" align="center" valign="middle">M&sup3; TOTALES</td>
					<td rowspan="2" width="6%" class="nombres_columnas" align="center" valign="middle">CUADRILLA</td>
					<td rowspan="2" class="nombres_columnas" align="center" valign="middle">VOLUMEN PRESUPUESTO</td>
					<td rowspan="2" class="nombres_columnas" align="center" valign="middle">VOLUMEN REAL</td>
					<td rowspan="2" class="nombres_columnas" align="center" valign="middle">REND. %</td>
					<td rowspan="2" class="nombres_columnas" align="center" valign="middle">M&sup3; PROMEDIO</td>
					
				</tr>
				
				
				<tr><?php								
				/***********************COLOCAR LOS DIAS EN EL ENCABEZADO DE LA TABLA**********************/
				//Obtener el dia, mes y año de inicio como actuales
				$diaActual = intval(substr($fechaIni,-2));
				$mesActual = intval(substr($fechaIni,5,2));
				$anioActual = $anioInicio;
				
				//Ciclo para recorrer la totalidad de dias del periodo seleccionado												
				for($i=0;$i<$totalDias;$i++){
					//Si el dia es menor a 10 colocar un cero a la izquierda
					if($diaActual<10){?>
						<td class="nombres_columnas" align="center">0<?php echo $diaActual; ?></td><?php
					}else{?>			
						<td class="nombres_columnas" align="center"><?php echo $diaActual; ?></td><?php
					}
										
					//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para hacer la consulta en la BD
					$fechaActual = $anioActual;
					if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
					if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
															
					//Inicializar cada posición del arreglo que contandrá la suma por día de cada una de las ubicaciones
					$sumPorDia[$fechaActual] = 0; 
					
					//Inicializar el arreglo que contendra el total por dia, que incluye todas las ubicaciones
					if($ctrlInicializacion==0){
						$sumTotalPorDia[$fechaActual] = 0;
					}										
					
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes
					if($diaActual==$diasMesInicio){
						$diaActual = 0;
						$mesActual++;
						
						//Verificar el cambio de año
						if($mesActual==13){
							$mesActual = 1;
							$anioActual++;
						}
					}																										
					
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)?>
				</tr><?php				
				
				
				//Revisar si el arreglo de Suma Total Por Dia fue inicializado
				if(count($sumTotalPorDia)>0)
					$ctrlInicializacion = 1;
			
															
				/***************COLOCAR POR RENGLON EL DETALLE DE CADA UBICACIÓN Y CUADRILLA*****************/																											
				//Obtener las Cuadrillas por Ubicacion registradas en la Bitacora de Zarpeo y almacenarlas en un Arreglo
				$cuadrillas = obtenerCuadrillas($ubicacion,$fechaIni,$fechaFin);
																				
																																				
				//Calcular el Presupuesto por Cuadrilla
				$numCuadrillas = count($cuadrillas);
				$presPorCuadrilla = floatval($presMensual/$numCuadrillas);									
				
				//Variable para contar las cuadrillas y obtener el total del TURNO, el cual se compone de dos cuadrillass
				$contCuadrillas = 0;
				
				//Revisar si existen CUADRILLAS para mostrar
				if(count($cuadrillas)>0){
					//Desplegar la Información de cada cuadrilla
					foreach($cuadrillas as $ind => $datosCuadrilla){//CUADRILLAS
						
						//Obtener el ID de la Cuadrilla
						$idCuadrilla = $datosCuadrilla['idCuadrilla'];
						
						//Incrementar el contador de cuadrillas
						$contCuadrillas++;																																			
						
						//Manipular el color de los renglones de cada Empleado
						$nom_clase = "renglon_gris";
						//Esta variable indica el numero de trabajor que esta siendo desplegado
						$cont = 1;
						
						//Obtener la cantidad de trabajadores de la cuadrilla actual
						$numTrabajadores = count($datosCuadrilla['integrantes']);																	
							
						//Este ciclo servira para mostrar los datos de los Lanzamientos realizados tanto por el Lanzador, como por el Ayudante por Cada Cuadrilla encontrada
						for($j=0;$j<$numTrabajadores;$j++){
							//Obtener los datos del Trabajador que será mostrado, empezando por el Lanzador
							$nomTrabajador = "";
							$puesto = "";
							$totalSuplente = 0;
							
							//Primero colocar los datos del LANZADOR, el cual se encuentra en la posicion 1 del arreglo Integrantes
							if($j==0){
								$nomTrabajador = $datosCuadrilla['integrantes'][1]['empleado'];
								$puesto = $datosCuadrilla['integrantes'][1]['puesto'];
							}
							//En Segundo lugar colocar los datos del AYUDANTE, el cual se encuentra en la posicion 0 del arreglo Integrantes
							else if($j==1){
								$nomTrabajador = $datosCuadrilla['integrantes'][0]['empleado'];
								$puesto = $datosCuadrilla['integrantes'][0]['puesto'];
							}
							//Por ultimo obtener los datos del resto de los empleados, sin importar el orden
							else{
								$nomTrabajador = $datosCuadrilla['integrantes'][$j]['empleado'];
								$puesto = $datosCuadrilla['integrantes'][$j]['puesto'];
							}?>
							
							
							<tr>
								<td class="nombres_filas" align="left"><strong><?php echo $nomTrabajador;?></strong></td>
								<td class="nombres_filas" align="left"><strong><?php echo $puesto;?></strong></td><?php
								if($j==0){?>
									<td rowspan="<?php echo $numTrabajadores; ?>" class="nombres_filas" align="center"><strong><?php echo $idCuadrilla; ?></strong></td><?php																																	
								}
							
							//Obtener el dia, mes y año de inicio como actuales
							$diaActual = intval(substr($fechaIni,-2));
							$mesActual = intval(substr($fechaIni,5,2));
							$anioActual = $anioInicio;			
							
							//Esta variable nos ayudara a acumular la cantidad de transportes realizados por Operador (Op. Olla u Op. Tornado)
							$totalTransporte = 0;													
								
							//Ciclo para colocar el valor de cada dia, en el caso de que no haya registro en el dia, se dejara vacio
							for($i=0;$i<$totalDias;$i++){							
								//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para hacer la consulta en la BD
								$fechaActual = $anioActual;
								if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
								if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
									
								//Comprobar si la Fecha Actual es Domingo
								$colorFondo = "";
								if(obtenerNombreDia($fechaActual)=="Domingo")
									$colorFondo = "#FFFF00";																								
																
								
								/***   IDENTIFICAR A LA PERSONA QUE REALIZO EL LANZAMIENTO   ***/	
								//Ejecutar la Sentencia para obtener los datos del Lanzamiento en la Fecha, Ubicación y Cuadrilla indicada para identificar la persona que lo realizo
								$rs_lanzamiento = mysql_query("SELECT bitacora_id_bitacora, cantidad, realizado, aplicacion, comentarios FROM bitacora_zarpeo WHERE cuadrillas_id_cuadrillas = '$idCuadrilla' 
																AND destino = '$ubicacion' AND fecha = '$fechaActual'");																								
								
								//Verificar que haya un lanzamiento en la Fecha Indicada
								if($datosLanzamiento=mysql_fetch_array($rs_lanzamiento)){
																													
									//Guardar los datos del Lanzamiento en las sig. vaiables
									$nombre = $datosLanzamiento['realizado'];
									$idBitacora = $datosLanzamiento['bitacora_id_bitacora'];
									$cantidad = $datosLanzamiento['cantidad'];
									$comentarios = trim($datosLanzamiento['comentarios']);
									//Obtener el puesto de la persona que realizo el lanzamiento
									$datosPuesto = mysql_fetch_array(mysql_query("SELECT puesto FROM cuadrillas_zarpeo WHERE id_cuadrilla = '$idCuadrilla' AND nom_empleado = '$nombre' 
																					AND id_bitacora = $idBitacora"));
									$realizado = $datosPuesto['puesto'];																					
																																				
									//Colocar los datos de los Integrantes de cada cuadrilla
									if($puesto=="LANZADOR"){									
										//Revisar si el Lanzador realizo el lanzamiento en la cuadrilla
										if($realizado=="LANZADOR"){
											if($nomTrabajador==$nombre){
												?>
												<td align="center" title="<?php echo $datosLanzamiento["aplicacion"]?>" <?php
													if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique ?> 
														class="<?php echo $nom_clase; ?>"<?php 								
													} 
													else {//Colocar fondo amarillo cuando sea domingo?> 
														bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;" <?php 
													} ?> 
												> <?php 
													if(strcmp($comentarios,"") != 0){ ?> 
														<input name="btn_comentarios" type="button" value="<?php echo $cantidad; ?>" 
															title="Ver los comentarios de la bitacora" 
															onmouseover="window.status='';return true"
															onclick="window.open('verComentarioCuad.php?idCuadrilla=<?php echo $idCuadrilla; ?>&comentarios=<?php echo $comentarios; ?>&fecha=<?php echo modFecha($fechaActual,9); ?>', '_blank','top=100, left=100, width=400, height=200, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no')" />
													<?php } else {
													//Imprimir el volumen de la fecha actual para el empleado actual
													echo $cantidad; }?>
												</td><?php																				
													
												//Sumar los volumenes encontrados por dia, tanto para Lanzador como para el Ayudante para el total por Dia
												$sumPorDia[$fechaActual] += $cantidad;
												//Acumular el total por dia por todas la ubicaciones dentro del periodo selecionado
												$sumTotalPorDia[$fechaActual] += $cantidad;
											}
											else{
												?>																		
												<td align="center" <?php 
													if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique ?> 
														class="<?php echo $nom_clase; ?>"<?php 								
													} 
													else { //Colocar fondo amarillo cuando sea domingo?> 
														bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;" <?php 
													}?>
												><?php 
											}
										}//Cierre if($realizado=="LANZADOR")
										else if($realizado!="AYUDANTE"){
										//else if($realizado=="SUPLENTE"){?>
											<td align="center" bgcolor="#F4510B" style="font-size:12px;" title="Lanzamiento Realizado por <?php echo $nombre; ?> \n(<?php echo $datosLanzamiento["aplicacion"];?>)"><?php 
												if(strcmp($comentarios,"") != 0){ ?> 
														<input name="btn_comentarios" type="button" value="<?php echo $cantidad; ?>" 
															title="Ver los comentarios de la bitacora" 
															onmouseover="window.status='';return true"
															onclick="window.open('verComentarioCuad.php?idCuadrilla=<?php echo $idCuadrilla; ?>&comentarios=<?php echo $comentarios; ?>&fecha=<?php echo modFecha($fechaActual,9); ?>', '_blank','top=100, left=100, width=400, height=200, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no')" />
													<?php } else { echo $cantidad; }?>
											</td><?php
											
											//Sumar los volumenes encontrados por dia, tanto para Lanzador como para el Ayudante para el total por Dia
											$sumPorDia[$fechaActual] += $cantidad;
											//Acumular el total por dia por todas la ubicaciones dentro del periodo selecionado
											$sumTotalPorDia[$fechaActual] += $cantidad;
											//Obtener el total de los Lanzamientos hechos por los suplentes
											$totalSuplente += $cantidad;
										}//Cierre else if($realizado=="SUPLENTE")										
										else{//Cuando no haya Lanzamiento en el dia actual, colocar un espacio en Blanco?>										
											<td align="center" <?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php									
										}
										
									}//Cierre if($puesto=="LANZADOR")							
									else if($puesto=="AYUDANTE"){
										//Revisar si el Ayudante realizo el lanzamiento en la cuadrilla
										if($realizado=="AYUDANTE"){
											if($nomTrabajador==$nombre){
												?>																		
												<td align="center" title="<?php echo $datosLanzamiento["aplicacion"]?>" <?php 
													if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique ?> 
														class="<?php echo $nom_clase; ?>"<?php 								
													} 
													else {//Colocar fondo amarillo cuando sea domingo?> 
														bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;" <?php 
													} ?>
												><?php 
													//Imprimir el volumen de la fecha actual para el empleado actual
													echo $cantidad; ?>
												</td><?php																				
													
												//Sumar los volumenes encontrados por dia, tanto para Lanzador como para el Ayudante para el total por Dia
												$sumPorDia[$fechaActual] += $cantidad;
												//Acumular el total por dia por todas la ubicaciones dentro del periodo selecionado
												$sumTotalPorDia[$fechaActual] += $cantidad;
											}
											else{
												?>																		
												<td align="center" <?php 
													if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique ?> 
														class="<?php echo $nom_clase; ?>"<?php 								
													} 
													else {//Colocar fondo amarillo cuando sea domingo?> 
														bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;" <?php 
													}?>
												><?php 
											}
										}//Cierre if($lanzamiento['realizado']=="AYUDANTE")
										else{//Cuando no haya Lanzamiento en el dia actual, colocar un espacio en Blanco?>										
											<td align="center" <?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php
										}
									}//Cierre else if($puesto=="AYUDANTE")
									else{
										/*Colocar a los operadores de Olla y Tornado, el SUPLENTE se excluye desde que se cargan los Integrantes de la Cuadrilla																				
										 *Para los Operadores se va a mostrar el transporte realizado, los datos serán obtenidos de la Bitacora de Transporte*/
																														
										//Obtener el registro del Transporte del la Bitacora de Transporte																						
										$rs_acarreo = mysql_query("SELECT cantidad, comentarios, ver_comentario FROM bitacora_transporte WHERE nombre='$nomTrabajador' AND fecha='$fechaActual'");
										
										//Extraer la cantidad de transporte
										$cantAcarreo = "";
										if($datosAcarreo = mysql_fetch_array($rs_acarreo))
											$cantAcarreo = $datosAcarreo['cantidad'];?>
											
											
										<td align="center" <?php
											if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique ?> 
												class="<?php echo $nom_clase; ?>"<?php 								
											} 
											else {//Colocar fondo amarillo cuando sea domingo?> 
												bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;" <?php 
											}
											
											//Verificar si existe un comentario para ser mostrado 
											if($datosAcarreo['ver_comentario']==1){?>
												title="<?php echo $datosAcarreo['comentarios']; ?>"<?php
											}?>																						
										><?php 
											if($datosAcarreo['ver_comentario']==1){
												//Imprimir el volumen de la fecha actual para el empleado actual subrayado para indicar que tiene un comentario asociado
												echo "<u>".$cantAcarreo."</u>";
											}
											else {
												//Imprimir el volumen de la fecha actual para el empleado actual
												echo $cantAcarreo; 
											}?>
										</td><?php																				
											
										//Sumar los volumenes encontrados por dia, para obtener el total de Trasnporte del Operador
										$totalTransporte += $cantAcarreo;
									}
								}//Cierre if($datosLanzamiento=mysql_fetch_array($rs_lanzamiento))
								else{ //Si no hubo lanzamiento en el día, solo colocar una columna vacia ?>
									<td align="center" <?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php
								}																
																	
								//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes
								if($diaActual==$diasMesInicio){
									$diaActual = 0;
									$mesActual++;
									
									//Verificar el cambio de año
									if($mesActual==13){
										$mesActual = 1;
										$anioActual++;
									}
								}																										
								
								//Incrementar el dia
								$diaActual++;
							}//Cierre for($i=0;$i<$totalDias;$i++)
																																																							
								
							//Despues de colocar los datos del Lanzador, colocar las columnas del resumen de cada cuadrilla
							if($cont==1){
															
								//Ejecutar Sentencia para obtener los totales de cada cuadrilla
								$totales = mysql_fetch_array(mysql_query("SELECT COUNT(cantidad) AS cant_registros, SUM(cantidad) AS vol_total FROM bitacora_zarpeo 
								WHERE fecha>='$fechaIni' AND fecha<='$fechaFin' AND cuadrillas_id_cuadrillas = '$idCuadrilla'"));								
								
								//Variables para calcular el total de los integrantes de cada cuadrilla
								$sumTotal = $totales['vol_total'];
								$sumTotal_SS = $sumTotal;// - $totalSuplente;
								$cantRegistros = $totales['cant_registros'];
								
								//Calcular el Porcentaje de Rendimiento y los Metros Cubicos Promedio
								$rendimiento = floatval($sumTotal_SS/$presPorCuadrilla)*100;
								
								//Obtener el Promedio de Cubicos descartando los volumenes hechos los domingos
								$promedio = 0;								
								if($sumTotal>0)
									$promedio = floatval($sumTotal/$cantRegistros);
									
								//Colocar el resumen de la cuadrilla ocupando dos renglones (El del Lanzador y del Ayudante)?>									
								<td rowspan="2" align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($sumTotal_SS,2,".",","); ?></strong></td>
								<td rowspan="<?php echo $numTrabajadores; ?>" class="nombres_filas" align="center"><strong><?php echo $idCuadrilla; ?></strong></td>
								<td rowspan="2" align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($presPorCuadrilla,2,".",","); ?></strong></td>
								<td rowspan="2" align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($sumTotal_SS,2,".",","); ?></strong></td>									
								<td rowspan="2" align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($rendimiento,1,".",",")."%"; ?></strong></td>
								<td rowspan="2" align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($promedio,2,".",","); ?></strong></td><?php																																																	
																									
							}//Cierre if($cont==1)
							
							
							//Desplegar el Resumen del Acarreo cuando ya han sido mostrados los datos del Lanzador y de la Cuadrilla
							if($cont==3 || $cont==4){
								//Obtener el presupuesto de trasnporte por operador registrado en la cuadrilla
								$presEmpleado = $presPorCuadrilla/($numTrabajadores-2);
															
								//Colocar el resumen del Transporte ?>									
								<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($totalTransporte,2,".",","); ?></strong></td>
								<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($presEmpleado,2,".",","); ?></strong></td>
								<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>									
								<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
								<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td><?php	
							}
							
							//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";?>
							</tr><?php												
						}//Cierre for($j=0;$j<$numTrabajadores;$j++)
					}//Cierre foreach($cuadrillas as $ind => $idCuadrilla)
				}//Cierre if(count($cuadrillas)>0)
																																								
											
				/****************REALIZAR LOS CALCULOS PARA OBTENER LOS TOTALES Y EL AVANCE DIARIO**************/
				//Variables para realizar los calculos
				$cont = 1;
				$prodRealAnterior = 0;												
				//Obtener el volumen real acumulado, volumen presupuestado real y la diferencia para ir viendo el avance dia a dia
				foreach($sumPorDia as $fechaActual => $volumen){					
					//Comprobar si la Fecha Actual es Domingo
					$domingo = false;
					if(obtenerNombreDia($fechaActual)=="Domingo")
						$domingo = true;
					
					
					//Colocar los valores del Volumen Real, presupuestado y diferencia en el primer dia del periodo
					if($cont==1){
						//Guardar la Produccion del Día y el Prespuesto del Día con valores FLotantes y a partir de ello realizar los respaldos necesarios para los calculos de los siguientes días
						$prodRealPorDia[$fechaActual] = floatval($volumen);
						//Verificar que en caso de el dia en el que se inicia el presupuesto es  diferente de domingo
						if(!$domingo){
							$prodPresPorDia[$fechaActual] = floatval($presDiario);
						}
						else{//De los contrario que la fecha actual la coloque como 0
							$prodPresPorDia[$fechaActual] = 0;
						}
						//Obtener la diferencia del Día
						$difPorDia[$fechaActual] = $prodRealPorDia[$fechaActual] - $prodPresPorDia[$fechaActual];
												
						//Guardar la produccion real del dia como anterior
						$prodRealAnterior = $prodRealPorDia[$fechaActual];
						//Guardar el presupuesto del dia como anterior
						$presAnterior = $prodPresPorDia[$fechaActual];
					}
					else{//Acumular los datos para el resto de los dias del periodo
						
						//Acumular el volumen diario real produccido
						$prodRealPorDia[$fechaActual] = floatval($volumen + $prodRealAnterior);
						//Guardar la produccion real del dia como anterior
						$prodRealAnterior = $prodRealPorDia[$fechaActual];
						
						
						//Verificar si el dia es domingo y no acumular el volumen Presupuestado
						if($domingo){
							$prodPresPorDia[$fechaActual] = $presAnterior;
						}
						else{
							$prodPresPorDia[$fechaActual] = floatval($presDiario + $presAnterior);
							//Guardar el presupuesto del Dia como Presupuesto del Día Anterior
							$presAnterior = $prodPresPorDia[$fechaActual];
						}
						
												
						//Obtener la Diferencia del Dia del Presupuesto Real menos el Presupuestado
						$difPorDia[$fechaActual] = $prodRealPorDia[$fechaActual] - $prodPresPorDia[$fechaActual];
					}										
					
					//Contador para saber cuando se colocan los valores del Dia inicial
					$cont++;
				}//Cierre foreach($sumPorDia as $diaActual => $volumen){
				
				
				/*************   COLOCAR LOS DATOS DEL AVANCE REAL Y EL PRESUPUESTO EN LA SESSION PARA GENERAR LA GRAFICA CORRESPONDIENTE A CADA UBICACION   *****************/
				if(!isset($_SESSION['ubicacionesGrafica'])){
					//Declarar un arreglo dentro del indice ubicacionesGrafica
					$_SESSION['ubicacionesGrafica'] = array();
					
					//Agregar la primera ubicacion
					$_SESSION['ubicacionesGrafica'][$ubicacion] = array("avanceReal"=>$prodRealPorDia,"presupuesto"=>$prodPresPorDia,
					"msgGraficaRptCuadrillas"=>"PRODUCCION DEL MES DE ".$nomMesFin." DE ".$anioFin." EN ".$ubicacion);
					
					//Agregar el periodo seleccionado a la SESSION 
					$_SESSION['periodoSeleccionado'] = $periodo;										
				}
				else{
					//Agregar el resto de las ubicaciones
					$_SESSION['ubicacionesGrafica'][$ubicacion] = array("avanceReal"=>$prodRealPorDia,"presupuesto"=>$prodPresPorDia,
					"msgGraficaRptCuadrillas"=>"PRODUCCION DEL MES DE ".$nomMesFin." DE ".$anioFin." EN ".$ubicacion);
				}
				
				
								
				/****************MOSTRAR EL TOTAL POR DIA, EL VOLUMEN REAL PRODUCIDO ACUMULADO, EL VOLUMEN PRESUPUESTADO ACUMULADO Y LA DIFERENCIA POR DIA**************/
				//Colocar el Renglon de los Totales por Día
				//Variable para almacenar el Volumen Total Producido por Ubicacion
				$volTotalUbicacion = 0;
				//Variable para obtener el total del volumen sin considerar los domingos
				$volTotalSinDomingos = 0;
				$totalDiasSinDomingos = 0;?>
				<tr>
					<td>&nbsp;</td>	
					<td>&nbsp;</td>					
					<td class="nombres_filas"><strong>TOTAL DIA</strong></td><?php
					foreach($sumPorDia as $fechaActual => $totalDia){?>
						<td align="center" class="<?php echo $nom_clase; ?>" style="color:#FF0000;"><strong><?php echo $totalDia;?></strong></td><?php					
						$volTotalUbicacion += $totalDia;
						//Obtener los datos para calcular el promedio diario de lanzamientos
						if(obtenerNombreDia($fechaActual)!="Domingo"){						
							$volTotalSinDomingos += $totalDia;
							$totalDiasSinDomingos++;
						}
					}?>
					<td align="center" class="<?php echo $nom_clase; ?>" style="font-size:15px;"><strong><?php echo number_format($volTotalUbicacion,0,".",",");?></strong></td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
					<td align="center" class="<?php echo $nom_clase; ?>" style="font-size:15px;"><strong><?php echo number_format($presMensual ,0,".",",");?></strong></td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
				</tr><?php
				//Colocar el Renglon del Volumen Real Acumulado por Día?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>	
					<td class="nombres_filas"><strong>REAL</strong></td><?php
					foreach($prodRealPorDia as $fechaActual => $realAcumulado){?>
						<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo $realAcumulado;?></strong></td><?php					
					}?>
					
					
					<td>&nbsp;</td>
					<td colspan="2" align="right" class="<?php echo $nom_clase; ?>"><strong>M&sup3;/Día</strong></td>					
					<td align="center" class="<?php echo $nom_clase; ?>" style="background:#FFFF00; color:#FF0000;">
						<strong><?php echo number_format(($volTotalSinDomingos/$totalDiasSinDomingos),2,".",",");?></strong>
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>					
				</tr><?php
				//Colocar el Renglon del Volumen Presupuestado Acumulado por Día?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>	
					<td class="nombres_filas"><strong>PPTO.</strong></td><?php
					foreach($prodPresPorDia as $fechaActual => $pptoAcumulado){?>
						<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($pptoAcumulado,1,".","");?></strong></td><?php					
					}?>
					
					<td>&nbsp;</td>
					<td colspan="2" align="right" class="<?php echo $nom_clase; ?>"><strong>M&sup3; Presupuesto Diario</strong></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo $presDiario;?></strong></td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
				</tr><?php
				//Colocar el Renglon del Volumen Presupuestado Acumulado por Día?>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>	
					<td class="nombres_filas"><strong>DIF.</strong></td><?php
					foreach($difPorDia as $fechaActual => $diferencia){?>
						<td align="center" class="<?php echo $nom_clase; ?>" <?php if($diferencia<0){?> style="color:#FF0000;"<?php }?>>
							<strong><?php echo number_format($diferencia,1,".",",");?></strong>
						</td><?php					
					}?>
					
					
					<td>&nbsp;</td>
					<td colspan="2" align="right" class="<?php echo $nom_clase; ?>"><strong>M&sup3; Por Lanzadora</strong></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo $presDiario/$numCuadrillas; ?></strong></td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
					<td align="center" class="<?php echo $nom_clase; ?>">&nbsp;</td>
				</tr>
				</table><br /><br /><br /><?php																				
			}while($ubicaciones=mysql_fetch_array($rs_ubicaciones));						
			
			//Regresar el periodo para activar el boton de Exportar Datos
			return $sumTotalPorDia;
		}//Cierre if($ubicaciones=mysql_fetch_array($rs_ubicaciones))
		else{?>
			<label class="msje_correcto">No Hay Registros Disponibles en el Periodo <em><u><?php echo $periodo; ?></u></em></label><?php
			return "";	
		}
				
		//Cerrar la Conexion con la BD
		mysql_close($conn);
						
	}//Cierre de la funcion verReporteMensual()
	
	
	/*Esta funcion obtiene las cuadrillas registradas en la Btacora de Zarpeo por cada ubicación en el periodo seleccionado*/
	function obtenerCuadrillas($ubicacion,$fechaIni,$fechaFin){
		//Este arreglo contedra las cuadrillas encontradas
		$cuadrillas = array();
		//Ejecutar la Sentencia para obtener el id de las cuadrillas registradas en la tabla de Bitacora Zarpeo en la Ubicación y Periodo seleccionados
		$rs_idCuadrillas = mysql_query("SELECT DISTINCT cuadrillas_id_cuadrillas as idCuadrilla FROM bitacora_zarpeo
										WHERE destino = '$ubicacion' AND fecha>='$fechaIni' AND fecha<='$fechaFin' ORDER BY cuadrillas_id_cuadrillas");
		//Obtener los Integrantes de cada cuadrilla de la tabla de Cuadrillas Zarpeo
		if($idCuadrillas=mysql_fetch_array($rs_idCuadrillas)){
			do{							
				//Arreglo que contendra los datos de las cuadrillas
				$infoCuadrilla = array("idCuadrilla"=>$idCuadrillas['idCuadrilla'],"integrantes"=>array());
				//Ejecutar la Sentencia SQL para obtener los integrantes de la Cuadrilla en el Periodo y Ubicación seleccionados
				$rs_datosCuadrilla = mysql_query("SELECT DISTINCT nom_empleado, puesto FROM cuadrillas_zarpeo JOIN bitacora_zarpeo ON id_bitacora=bitacora_id_bitacora
												  WHERE id_cuadrilla = '$idCuadrillas[idCuadrilla]' AND destino = '$ubicacion' AND fecha>='$fechaIni' AND fecha<='$fechaFin' ORDER BY puesto");
				//Extraer cada uno de los integrantes de la cuadrilla
				if($datosCuadrilla=mysql_fetch_array($rs_datosCuadrilla)){
					//Guardar cada puesto y nombre del empleado en el indice de integrantes, que a su vez es otro arreglo.
					do{												
						//Agregar todos los puesto excluyendo el de SUPLENTE
						if($datosCuadrilla['puesto']!="SUPLENTE"){
							//echo $idCuadrillas['idCuadrilla']." ".$datosCuadrilla['puesto']." ".$datosCuadrilla['nom_empleado']."<br />";
							$infoCuadrilla['integrantes'][] = array("puesto"=>$datosCuadrilla['puesto'], "empleado"=>$datosCuadrilla['nom_empleado']);
						}
					}while($datosCuadrilla=mysql_fetch_array($rs_datosCuadrilla));					
				}				
				//Guardar los datos de cada Cuadrilla en el arreglo de cuadrillas
				$cuadrillas[] = $infoCuadrilla;
				//$cuadrillas[] = array("idCuadrilla"=>$datosCuadrilla['cuadrillas_id_cuadrillas'],"Lanzador"=>$datosCuadrilla['lanzador'],"Ayudante"=>$datosCuadrilla['ayudante']);
			}while($idCuadrillas=mysql_fetch_array($rs_idCuadrillas));
		}//Cierre if($idCuadrillas=mysql_fetch_array($rs_idCuadrillas))
		//Retornar el arreglo con las Cuadrillas encontradas
		return $cuadrillas;
	}//Cierre de la funcion obtenerCuadrillas($ubicacion,$fechaIni,$fechaFin)			
	
	
	/***   ESTA FUNCION NO ESTA SIENDO IMPLEMENTADA YA QUE EL TRANSPORTE FUE INTEGRADO EN EL REPORTE DE CUADRILLA ***/
	/*Esta Funcion muestra el registro de Transporte del Periodo Seleccionado*/
	function verReporteTransporte($periodo,$sumaPorDiaZarpeo){		
		//Conectarse a la BD de Gerencia Técnica
		$conn = conecta("bd_gerencia");
		
		//Obtener las fechas del periodo seleccionado
		$datosPeriodo = mysql_fetch_array(mysql_query("SELECT DISTINCT fecha_inicio,fecha_fin FROM presupuesto WHERE periodo = '$periodo'"));		
		$fechaIni = $datosPeriodo['fecha_inicio'];
		$fechaFin = $datosPeriodo['fecha_fin'];						
		
		
		//Obtener el año de inicio y el año de fin de las fechas que componen el periodo
		$anioInicio = substr($fechaIni,0,4);
		$anioFin = substr($fechaFin,0,4);
		
		//Seperar el valor del Periodo para obtener los meses, aqui se considera que los periodos son siempre de dos meses consecutivos
		$nomMesInicio = obtenerNombreCompletoMes(substr($periodo,5,3));
		$nomMesFin = obtenerNombreCompletoMes(substr($periodo,9,3));
		
		//Obtener los dias del mes de Inicio del periodo
		$diasMesInicio = diasMes(obtenerNumMes($nomMesInicio), $anioInicio);						
		
		//Obtener el ancho en dias de los meses que componen el periodo
		$anchoDiasInicio = $diasMesInicio - intval(substr($fechaIni,-2)) + 1;
		$anchoDiasFin = intval(substr($fechaFin,-2));
		$totalDias = $anchoDiasInicio + $anchoDiasFin;
		
		
		//Arreglo que contendra la suma de los lanzamientos hechos por día
		$sumPorDia = array();																																											
						
				
		//Obtener los nombres de los operadores de Olla registrados en la Bitacora de Transporte dentro del periodo seleccionado
		$rs_operadores = mysql_query("SELECT DISTINCT nombre FROM bitacora_transporte WHERE fecha>='$fechaIni' AND fecha<='$fechaFin'");
		//Verificar que existan datos para ser mostrados
		if($operadores=mysql_fetch_array($rs_operadores)){		
										
			
			/***********************DIBUJAR EL ENCABEZADO DE LA TABLA**********************/?>
			<table border="0" cellpadding="5" class="tabla_frm" width="200%" align="center">
				<caption align="center" style="font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #000000; font-weight: bolder;">
					Reporte de Transporte Mensual del Periodo <em><u><?php echo $periodo;?></u></em>
				</caption>
				<tr>
					<td>&nbsp;</td>				
					<td rowspan="2" width="15%" class="nombres_columnas">OPERADORES DE OLLA</td>			
					<td colspan="<?php echo $anchoDiasInicio; ?>" align="center" class="nombres_columnas"><?php echo $nomMesInicio."&nbsp;".$anioInicio; ?></td>	
					<td colspan="<?php echo $anchoDiasFin; ?>" align="center" class="nombres_columnas"><?php echo $nomMesFin."&nbsp;".$anioFin; ?></td>	
					<td rowspan="2" width="10%" align="center" class="nombres_columnas">M&sup3; TOTALES</td>
				</tr>
				<tr>
					<td>&nbsp;</td><?php
			
			//Obtener el dia, mes y año de inicio como actuales
			$diaActual = intval(substr($fechaIni,-2));
			$mesActual = intval(substr($fechaIni,5,2));
			$anioActual = $anioInicio;
			
			//Ciclo para colocar el No. de Día en el Encabezado de la Tabla
			for($i=0;$i<$totalDias;$i++){
				//Si el dia es menor a 10 colocar un cero a la izquierda
				if($diaActual<10){?>
					<td class="nombres_columnas" align="center">0<?php echo $diaActual; ?></td><?php
				}else{?>			
					<td class="nombres_columnas" align="center"><?php echo $diaActual; ?></td><?php
				}
				
				//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para hacer la consulta en la BD
				$fechaActual = $anioActual;
				if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
				if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
				
				//Inicializar cada posición del arreglo que contandrá la suma por día de todas las ubicaciones
				$sumPorDia[$fechaActual] = 0; 
				
				
				//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes
				if($diaActual==$diasMesInicio){
					$diaActual = 0;
					$mesActual++;
					
					//Verificar el cambio de año
					if($mesActual==13){
						$mesActual = 1;
						$anioActual++;
					}
				}																										
				
				//Incrementar el dia
				$diaActual++;									
			}//Cierre for($i=0;$i<$totalDias;$i++)?>
				</tr><?php


						
		
			/************MOSTRAR EL VOLUMEN DEL TRANSPORTE DIARIO POR OPERADOR***********/
			//Manipular el color de los renglones de cada Empleado
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				//Obtener el nombre de cada Operador de Olla
				$nomOperador = $operadores['nombre'];?>			
				<tr>
					<td>&nbsp;</td>
					<td class="nombres_filas" align="left"><strong><?php echo $nomOperador; ?></strong></td><?php				
				//Obtener el dia, mes y año de inicio como actuales
				$diaActual = intval(substr($fechaIni,-2));
				$mesActual = intval(substr($fechaIni,5,2));
				$anioActual = $anioInicio;															
				
				//Variable para obtener el total del Operador dentro del periodo seleccionado
				$totalOperador = 0;
				
				//Ciclo para colocar el valor de cada dia, en el caso de que no haya registro en el dia, se dejara vacio
				for($i=0;$i<$totalDias;$i++){							
					//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para hacer la consulta en la BD
					$fechaActual = $anioActual;
					if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
					if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
					
					//Comprobar si la Fecha Actual es Domingo
					$colorFondo = "";
					if(obtenerNombreDia($fechaActual)=="Domingo")
						$colorFondo = "#FFFF00";
											
					//Mostrar los Registros por cada OPerador de Olla dentro de las del Periodo Seleccionado
					$rs_volumen = mysql_query("SELECT cantidad FROM bitacora_transporte WHERE fecha = '$fechaActual' AND nombre = '$nomOperador'");
					
					//Extraer el volumen del día actua, si no existe regresa vacio
					$volumen = mysql_fetch_array($rs_volumen);
															
					//Si existe Volumen, imprimirlo
					if($volumen['cantidad']!="" && $volumen['cantidad']>0){?>
						<td align="center" <?php 
							if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique?> 
								class="<?php echo $nom_clase; ?>"<?php 								
							} 
							else {//Colocar fondo amarillo cuando sea domingo?> 
								bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000;" <?php 
							}?>
						><?php 
							//Imprimir el volumen de la fecha actual para el Operador actual
							echo $volumen['cantidad']; ?>
						</td><?php																				
						
						//Sumar los volumenes encontrados por dia
						$sumPorDia[$fechaActual] += $volumen['cantidad']; 										
						//Obtener la Suma Total por Operador
						$totalOperador += $volumen['cantidad'];						
					}
					//Si no hay volumen para mostrar dejar un espacio vacio
					else {?>						
						<td align="center" <?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php										
					}
										
					
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes
					if($diaActual==$diasMesInicio){
						$diaActual = 0;
						$mesActual++;
						
						//Verificar el cambio de año
						if($mesActual==13){
							$mesActual = 1;
							$anioActual++;
						}
					}																										
					
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)?>
					<td align="center" class="<?php echo $nom_clase; ?>" style="font-size:14px;"><strong><?php echo $totalOperador; ?></strong></td>
				</tr><?php				
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			
			}while($operadores=mysql_fetch_array($rs_operadores));	
			
					
			/****************MOSTRAR EL VOLUMEN TOTAL POR DIA**************/
			//Colocar el Renglon de los Totales por Día
			$volumenTotal = 0; ?>
				<tr>				
					<td>&nbsp;</td>
					<td align="center" class="nombres_filas"><strong>TOTAL DIA</strong></td><?php
					foreach($sumPorDia as $fechaActual => $totalDia){?>
						<td align="center" class="<?php echo $nom_clase; ?>" style="color:#FF0000;"><strong><?php echo $totalDia; ?></strong></td><?php					
						$volumenTotal += $totalDia;
					}?>
					<td align="center" class="<?php echo $nom_clase; ?>" style="font-size:14px;"><strong><?php echo number_format($volumenTotal,0,".",",");?></strong></td>
				</tr>
				<tr>				
					<td>&nbsp;</td>
					<td align="center" class="nombres_filas"><strong>PISOS</strong></td><?php
					//Obtener el Total de los Pisos, esta información dolo contempla los datos de la Primera Ubicación desplegada para realizar el Calculo
					$totalPisos = 0;
					foreach($sumPorDia as $fechaActual => $totalDiaTrasporte){
						//Obtener la Diferencia entre el Volumen Trasportado y el Volumen de Zarpeo
						$piso = $totalDiaTrasporte - $sumaPorDiaZarpeo[$fechaActual]; ?>
						<td align="center" style="color:#009900; font-size:12px;"><strong><?php echo $piso; ?></strong></td><?php												
						//Sumar el volumen destinado a Pisos para obtener el total
						$totalPisos += $piso;
					}?>
					<td align="center"style="color:#009900; font-size:14px;"><strong><?php echo number_format($totalPisos,0,".",",");?></strong></td>
				</tr>
			</table><?php	
			
			return $sumPorDia;		
		}//Cierre de if($operadores=mysql_fetch_array($rs_operadores))
		else{?>
			<label class="msje_correcto">No Hay Registros Disponibles en el Periodo <em><u><?php echo $periodo; ?></u></em> para Transporte</label><?php
			return "";	
		}
		
		//Cerrar la Conexion con la BD
		mysql_close($conn);				
	}//Cierre de la funcion verReporteTransporte()
	
	
		//Grafica que es incluida en el reporte de Agregados
	function dibujarGrafica($msg,$datosPreupuesto,$datosProduccion){	
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_plotline.php');

								
		//Obtener las fechas para ser colocados en la Grafica
		$fechas = array_keys($datosPreupuesto);	
		
		$dias = array();
		//Solo dejar los digitos del dia de cada fecha para ser colocados en la grafica
		foreach($fechas as $ind => $fecha)
			$dias[] = substr($fecha,-2);
		
		//Redeondear los valores del presupuesto y colocarlos en otro Arreglo
		$preupuesto = array();
		foreach($datosPreupuesto as $ind => $valor)
			$preupuesto[] = round($valor);
		
		//Redeondear los valores del presupuesto y colocarlos en otro Arreglo
		$produccion = array();
		foreach($datosProduccion as $ind => $valor)
			$produccion[] = round($valor);
		
			
		//Crear el Grafico, se deben hacer dos llamadas a los metodos Graph() y SetScale()
		$graph = new Graph(940,450);
		$graph->SetScale('textlin');
		$graph->title->Set($msg);
		//Colocar los Margenes del Grafico(Izq,Der,Arriba,Abajo)
		$graph->SetMargin(60,120,40,60);				
		//Colocar el Color del Margen
		$graph->SetMarginColor('white@0.5');
		
		//Colocar los Titulos a los Ejes
		$graph->yaxis->title->Set('METROS CUBICOS');//Eje Y
		$graph->yaxis->title->SetMargin(20);
		$graph->xaxis->title->Set('DIAS');//Eje X								
		
			
		//Crear la primera linea del Grafico con los Datos del Presupuesto
		$lineplot=new LinePlot($preupuesto);
		$lineplot->SetColor('red');
		$lineplot->SetLegend('Presupuesto');
		//Muestra y formatea los valores de los datos en la linea correspondiente
		$lineplot->mark->SetType(MARK_FILLEDCIRCLE);
		$lineplot->mark->SetFillColor("black");
		$lineplot->mark->SetWidth(2);
		$lineplot->value->Show();
		
		
		//Crear la segunda linea del Grafico con los Datos de la Produccion		
		$lineplot2=new LinePlot($produccion);
		$lineplot2->SetColor('blue');
		$lineplot2->SetLegend('Producción Real');	
		//Muestra los valores de los datos en la linea correspondiente
		//$lineplot2->value->Show();					
		
		//Agregar Nombres de los rotulos del eje X
		$graph->xaxis->SetTickLabels($dias);
		//Establecer el margen separación entre etiquetas del Eje X
		$graph->xaxis->SetTextLabelInterval(1);
		
		//Agregar las lineas de datos a la grafica
		$graph->Add($lineplot);
		$graph->Add($lineplot2);
		
		//Alinear los rotulos de la leyenda
		$graph->legend->SetPos(0.05,0.5,'right','center');
		
		//Crea un nombre oara la grafica que sera guardada en un archivo temporal
		$rnd=rand(0,1000);		
		$grafica= "tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Retornar el directorio y nombre de la grafica creada temporalmente para ser mostrada en una pagina HTML
		return $grafica;
					
	}//Cierre dibujarGrafica($msg,$datosPreupuesto,$datosProduccion)


?>
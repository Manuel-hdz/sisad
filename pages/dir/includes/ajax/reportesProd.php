<?php
	/**
	  * Nombre del Módulo: Direccion General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 12/Marzo/2012                                      			
	  * Descripción: Este archivo contiene la función que muestra registros previos y siguientes
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
			include("../../../../includes/func_fechas.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["rep"])){
		$tipoRep=$_GET["rep"];
		switch($tipoRep){
			case 1:
				$periodo=$_GET["periodo"];
				$mes=substr($periodo,5,3);
				$mes=obtenerNombreCompletoMes($mes);
				$anio=substr($periodo,0,4);
				$titulo="REPORTE DE AVANCE VS PRESUPUESTO DE $mes $anio";
				$reporte=verReporteMensual($periodo,$titulo);
				header("Content-type: text/xml");	
				if ($reporte!=""){
					$resultado=split("¬X¬",$reporte);
					$grafica=str_replace("../../tmp/","",$resultado[0]);
					$tabla=str_replace("<","¬",$resultado[1]);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<grafica>$grafica</grafica>
							<tabla>$tabla</tabla>
						</existe>");
				}
				else{
					//Crear XML de error
					echo utf8_encode("
					<existe>
						<valor>false</valor>
					</existe>");
				}
				break;
		}
	}	
	
	/*Esta funcion genera el reporte mensual y regresa el periodo para indicar que los datos mostrados pueden ser exportados*/
	function verReporteMensual($periodo,$titulo){
		//Conectarse a la Base de Datos de Produccion
		$conn = conecta("bd_produccion");
		//Verificar si viene el numero de empleados en el post para agregar la Seccion de Productividad al reporte que será exportado
		$noEmpleados = "";
		if(isset($_POST['hdn_numEmpleados']))
			$noEmpleados = $_POST['hdn_numEmpleados'];
		
		//Crear y ejecutar la Sentencia SQL para obtener las fechas del Periodo Seleccionado
		$fechas = mysql_fetch_array(mysql_query("SELECT fecha_inicio, fecha_fin FROM presupuesto WHERE periodo = '$periodo'"));
				
		//Obtener el año de inicio y el año de fin de las fechas que componen el periodo
		$anioInicio = substr($fechas['fecha_inicio'],0,4);
		$anioFin = substr($fechas['fecha_fin'],0,4);
		
		//Seperar el valor del Periodo para obtener los meses, aqui se considera que los periodos son siempre de dos meses consecutivos
		$nomMesInicio = obtenerNombreCompletoMes(substr($periodo,5,3));
		$nomMesFin = obtenerNombreCompletoMes(substr($periodo,9,3));
		
		//Obtener los dias del mes de Inicio del periodo
		$diasMesInicio = diasMes(obtenerNumMes($nomMesInicio), $anioInicio);
						
		
		//Obtener el ancho en dias de los meses que componen el periodo
		$anchoDiasInicio = $diasMesInicio - intval(substr($fechas['fecha_inicio'],-2)) + 1;
		$anchoDiasFin = intval(substr($fechas['fecha_fin'],-2));
		$totalDias = $anchoDiasInicio + $anchoDiasFin;
		
		//Arreglos para almacenar los totales
		$sumPorDia = array();//Arreglo que contendra la suma de todas las ubicaciones por día
		$prodRealPorDia = array();//Arreglo que contendra la produccion real por dia acumulada
		$prodPresPorDia = array();//Arreglo que contendra la produccion presupuestada por dia acumulada
																		
		//Crear la Sentencia para obtener las ubicaciones que tienen registros en el periodo seleccionado
		$sql_stm_ubicaciones = "SELECT DISTINCT id_destino, destino FROM catalogo_destino JOIN datos_bitacora ON id_destino=catalogo_destino_id_destino 
								WHERE bitacora_produccion_fecha>='$fechas[fecha_inicio]' && bitacora_produccion_fecha<='$fechas[fecha_fin]'";
		//Ejecutar la Senetencia para obtener las ubicaciones
		$rs_ubicaciones = mysql_query($sql_stm_ubicaciones);
		
		//Sibujar los registros de cada una de las ubicaciones encontradas, de a renglon por ubicación		
		if($ubicaciones=mysql_fetch_array($rs_ubicaciones)){			
							
			/***********************DIBUJAR EL ENCABEZADO DE LA TABLA**********************/
			$tabla="<table border='0' cellpadding='5'>
			<caption class='titulo_tabla'><strong>Reporte de Producción en el Periodo <em><u>$periodo</u></em></strong></caption>
			<tr>
				<td rowspan='2' class='nombres_columnas'>CONCEPTO</td>
				<td colspan='$anchoDiasInicio' class='nombres_columnas' align='center'>$nomMesInicio $anioInicio</td>	
				<td colspan='$anchoDiasFin' class='nombres_columnas' align='center'>$nomMesFin $anioFin</td>	
				<td rowspan='2' class='nombres_columnas' align='center'>TOTAL MES</td>
				<td rowspan='2' class='nombres_columnas' align='center'>PROMEDIO</td>
			</tr>
			<tr>";
			//Ciclo para Colocar los dias en el encabezado
			$diaActual = substr($fechas['fecha_inicio'],-2);
			for($i=0;$i<$totalDias;$i++){
				//Si el dia es menor a 10 colocar un cero a la izquierda
				if($diaActual<10){
					$tabla.="<td class='nombres_columnas' align='center'>0$diaActual</td>";
				}else{
					$tabla.="<td class='nombres_columnas' align='center'>$diaActual</td>";
				}
				//Inicializar cada posición del arreglo que contandrá la suma por día de todas las ubicaciones
				$sumPorDia[$diaActual] = 0; 	
				if($diaActual==$diasMesInicio)
					$diaActual = 0;
				//Incrementar el dia
				$diaActual++;
			}//Cierre for($i=0;$i<$totalDias;$i++)
			$tabla.="</tr>";
			/***************COLOCAR POR RENGLON EL DETALLE DE CADA UBICACIÓN*****************/
			//Manipular el color de los renglones de cada ubicación
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				$tabla.="<tr>";
					$tabla.="<td class=nombres_filas><strong>$ubicaciones[destino]</strong></td>";
					//Obtener el dia, mes y año de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					$mesActual = substr($fechas['fecha_inicio'],5,2);
					$anioActual = $anioInicio;
					//Variables para calcular el total y el promedio de cada Concepto
					$sumTotal = 0;
					$sumPromedio = 0;
					$contRegs = 0;					
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
						//Ejecutar Sentencia SQL para obtener el Volumen del Dia y la Ubicacion Actuales
						$rs_volumen = mysql_query("SELECT SUM(vol_producido) AS vol_producido FROM datos_bitacora 
													WHERE bitacora_produccion_fecha = '$fechaActual' AND catalogo_destino_id_destino = $ubicaciones[id_destino]");
						$volumen = mysql_fetch_array($rs_volumen);
						//Si existe Volumen, imprimirlo
						if($volumen['vol_producido']!=""){
							$tabla.="<td align='center' ";
								if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique
									$tabla.="class='$nom_clase'";
									$sumPromedio += $volumen['vol_producido'];//Obtener la suma total para obtener el Promedio descartando los Domingos
									$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
								} 
								else {//Colocar fondo amarillo cuando sea domingo
									$tabla.="bgcolor='#FFFF00' style='font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000;'";
								}
							$tabla.=">";
								//Imprimir el volumen de la fecha actual								
								$tabla.="$volumen[vol_producido]";
							$tabla.="</td>";
							//Obtener la suma total de la ubicacion que esta siendo impresa
							$sumTotal += $volumen['vol_producido'];
							//Sumar los volumenes encontrados por dia
							$sumPorDia[$diaActual] += $volumen['vol_producido']; 
						}
						else{//Si no existe volumen colocar la celda vacia
							$tabla.="<td align='center' ";
							if($colorFondo==""){
								$tabla.="class='$nom_clase'";
							}else{
								$tabla.="bgcolor='#FFFF00'";
							}
							$tabla.="></td>";
						}
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
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
					$tabla.="<td align='center' class='$nom_clase'><strong>".number_format($sumTotal,2,".",",")."</strong></td>
							<td align='center' class='$nom_clase'><strong>".number_format(floatval($sumPromedio/$contRegs),2,".",",")."</strong></td>
							</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($ubicaciones=mysql_fetch_array($rs_ubicaciones));
			/****************COLOCAR EL TOTAL DE CADA DIA DEL PERIODO*******************/
			$tabla.="<tr><td class='nombres_filas'><strong>TOTAL DÍA</strong></td>";
				//Obtener el dia, mes y año de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				$mesActual = substr($fechas['fecha_inicio'],5,2);
				$anioActual = $anioInicio;
				//Variables para calcular el total y el promedio de cada Concepto
				$sumTotal = 0;
				$sumPromedio = 0;
				$contRegs = 0;
				for($i=0;$i<$totalDias;$i++){
					//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para saber si es domingo o no
					$fechaActual = $anioActual;
					if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
					if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
					//Comprobar si la Fecha Actual es Domingo
					$colorFondo = "";
					if(obtenerNombreDia($fechaActual)=="Domingo")
						$colorFondo = "#FFFF00";
					//Colocar la suma del dia Actual en el caso que exista
					if($sumPorDia[$diaActual]!=0){
						$tabla.="<td align='center' style='font-weight:bold;color:#FF0000;'";
							if($colorFondo==""){
								$tabla.="class='$nom_clase'";
								$sumPromedio += $sumPorDia[$diaActual];//Obtener la suma total para obtener el Promedio descartando los Domingos
								$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
							} 
							else{
								$tabla.="bgcolor='#FFFF00' style='font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000;'";
							}
						$tabla.=">";
							$tabla.=round($sumPorDia[$diaActual],1);
						$tabla.="</td>";					
						//Obtener la suma total de la ubicacion que esta siendo impresa
						$sumTotal += $sumPorDia[$diaActual];
					}
					else{//Si no existe suma del dia colocar un espacio vacio
						$tabla.="<td align='center' ";
							if($colorFondo==""){
								$tabla.="class='$nom_clase'";
							}else{
								$tabla.="bgcolor='#FFFF00'";
							}
							$tabla.="></td>";
					}
															
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
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
				$tabla.="<td align='center' class='$nom_clase'><strong>".number_format($sumTotal,2,".",",")."</strong></td>
						<td align='center' class='$nom_clase'><strong>".number_format(floatval($sumPromedio/$contRegs),2,".",",")."</strong></td>
						</tr>";
			/****************COLOCAR LA PRODUCCION REAL DEL PERIODO POR DIA*******************/
			//Hacer cambio del color de renglon
			if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";
				$tabla.="<tr><td class='nombres_filas'><strong>REAL</strong></td>";
				//Obtener el dia y mes de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				$valDiaAnterior = 0;
				for($i=0;$i<$totalDias;$i++){
					if($i==0){
						$tabla.="<td align='center' class='$nom_clase'><strong>".round($sumPorDia[$diaActual],1)."</strong></td>";
						$valDiaAnterior = $sumPorDia[$diaActual];
						//Almacenar la Produccion Real Diaria Acumulada
						$prodRealPorDia[$diaActual] = $sumPorDia[$diaActual];
					}
					else{
						$tabla.="<td align='center' class='$nom_clase'><strong>".round($sumPorDia[$diaActual]+$valDiaAnterior,1)."</strong></td>";
						//Almacenar la Produccion Real Diaria Acumulada
						$prodRealPorDia[$diaActual] = $sumPorDia[$diaActual] + $valDiaAnterior;
						//La suma del dia actual se convierte en el valor del dia anterior
						$valDiaAnterior = $sumPorDia[$diaActual] + $valDiaAnterior;						
					}
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
					if($diaActual==$diasMesInicio)
						$diaActual = 0;
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)
				$tabla.="<td></td><td></td></tr>";
			/****************COLOCAR LA PRODUCCION PRESUPUESTADA DEL PERIODO*******************/
			//Hacer cambio del color de renglon			
			if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";
			//Obtener el Presupusto diario del periodo
			$presupuesto = obtenerDato("bd_produccion", "presupuesto", "vol_ppto_dia", "periodo", $periodo);
			$tabla.="<tr><td class='nombres_filas'><strong>PRESUPUESTO</strong></td>";
				//Obtener el dia, mes y año de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				$mesActual = substr($fechas['fecha_inicio'],5,2);
				$anioActual = $anioInicio;
				
				//Variable para acumular el presupuesto dia a dia
				$presAnterior = 0;
				for($i=0;$i<$totalDias;$i++){
					//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para saber si es domingo o no
					$fechaActual = $anioActual;
					if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
					if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
					
					//Comprobar si la Fecha Actual es Domingo
					$diaDomingo = false;
					if(obtenerNombreDia($fechaActual)=="Domingo")
						$diaDomingo = true;
				
					//Colocar el presupuesto en el dia inicial
					if($i==0){
						$tabla.="<td align='center' class='$nom_clase'><strong>".round($presupuesto,1)."</strong></td>";
						$presAnterior = $presupuesto;
						//Almacenar la produccion presupuestada por dia acumulada
						$prodPresPorDia[$diaActual] = $presupuesto;
					}
					else{
						//Verificar si la fecha actual es domingo y colocar directamente el presupuesto anterior
						if($diaDomingo){
							$tabla.="<td align='center' class='$nom_clase'><strong>".round($presAnterior,1)."</strong></td>";
							//Almacenar la produccion presupuestada por dia acumulada
							$prodPresPorDia[$diaActual] = $presAnterior;
						}
						else{
							$tabla.="<td align='center' class='$nom_clase'><strong>".round($presupuesto+$presAnterior,1)."</strong></td>";
							//Almacenar la produccion presupuestada por dia acumulada
							$prodPresPorDia[$diaActual] = $presupuesto + $presAnterior;
							//La suma del dia actual se convierte en el valor del dia anterior
							$presAnterior = $presupuesto + $presAnterior;
						}
					}
								
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
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
				$tabla.="<td></td><td></td></tr>";

			/****************COLOCAR LA DIFERENCIA ENTRE LA PRODUCCION REAL Y LA PRESUPUESTADA*******************/
			//Hacer cambio del color de renglon			
			if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";
				$tabla.="<tr><td class='nombres_filas'><strong>DIFERENCIA</strong></td>";
				//Obtener el dia y mes de inicio como actuales
				$diaActual = substr($fechas['fecha_inicio'],-2);
				for($i=0;$i<$totalDias;$i++){
					//Hacer la resta de la Produccion Real menos la Presupuestada, cuando se llega aqui existe un registro de producción real por cada registro de Produccion presupuestada
					$tabla.="<td align='center' class='$nom_clase'><strong>".round($prodRealPorDia[$diaActual]-$prodPresPorDia[$diaActual],1)."</strong></td>";
					//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
					if($diaActual==$diasMesInicio)
						$diaActual = 0;
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)
				$tabla.="<td></td><td></td></tr>";
			/****************COLOCAR LA PRODUCTIVIDAD DE CADA DIA*******************/			
			if($noEmpleados!=""){
				$tabla.="<tr>
					<td colspan='31' align='right'></td>
					<td colspan='2' align='center' class='nombres_columnas'>PRODUCTIVIDAD PROMEDIO</td>
				</tr>
				<tr>
					<td class='nombres_filas'><strong>PRODUCTIVIDAD</strong></td>";
					//Obtener la Suma Total de la Productividad para sacar el Promedio de la misma
					$totalProductividad = 0;
					$contDias = 0;
					foreach($sumPorDia as $ind => $totalDia){
						//Colocar la suma del dia Actual en el caso que exista
						if($totalDia!=0){
							$productividad = $totalDia/$noEmpleados;
							$tabla.="<td align='center' class='$nom_clase'>".number_format($productividad,2,".",".")."</td>";
							//Obtener la suma total de la ubicacion que esta siendo impresa
							$totalProductividad += $productividad;
							$contDias++;
						}
						else{//Si no existe suma del dia colocar un espacio vacio
							$tabla.="<td class='$nom_clase'></td>";
						}																				
					}//Cierre foreach($sumPorDia as $ind => $totalDia)
					$tabla="<td colspan='2' class='$nom_clase' align='center'><strong>".number_format($totalProductividad/$contDias,2,".",",")."</strong></td></tr>";
			}//Cierre if($noEmpleados!="")
			$tabla.="</table>";
			//mostrarTabla($tabla);
			$grafica=dibujarGrafica1($prodPresPorDia,$prodRealPorDia,$titulo);
			return $grafica."¬X¬".$tabla;
		}//Cierre if($ubicaciones=mysql_fetch_array($rs_ubicaciones))
	}//Cierre de la funcion verReporteMensual()
	
	//Grafica que es incluida en el reporte de Agregados
	function dibujarGrafica1($datosPreupuesto,$datosProduccion,$msg){	
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_plotline.php');
							
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
		$grafica= "../../tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Retornar el directorio y nombre de la grafica creada temporalmente para ser mostrada en una pagina HTML
		return $grafica;
					
	}//Cierre dibujarGrafica($msg,$datosPreupuesto,$datosProduccion)
?>
<?php
	/**
	  * Nombre del Módulo: Direccion General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 01/Marzo/2012                                      			
	  * Descripción: Este archivo contiene las operaciones de Reportes de Almacen
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
			include("../../../../includes/func_fechas.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["noRep"])){
		$tipoRep=$_GET["noRep"];
		switch($tipoRep){
			case 1:
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$titulo="REPORTE DE ENTRADAS AL ALMACÉN DEL $fechaI AL $fechaF";
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				$graficas=reporteEntradas($fechaI,$fechaF,$titulo);
				$grafica=split("../../tmp/",$graficas);
				header("Content-type: text/xml");	
				if ($graficas!=""){
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
					");
					$ctrl=0;
					do{
						if ($ctrl!=0)
							echo utf8_encode("<grafica$ctrl>$grafica[$ctrl]</grafica$ctrl>");
						$ctrl++;
					}while($ctrl<count($grafica));
					$ctrl--;
					echo ("<cant>$ctrl</cant>
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
			case 2:
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$titulo="REPORTE DE SALIDAS DEL ALMACÉN DEL $fechaI AL $fechaF";
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				$combo=$_GET["combo"];
				$graficas=reporteSalidas($fechaI,$fechaF,$titulo,$combo);
				$grafica=split("../../tmp/",$graficas);
				header("Content-type: text/xml");
				if ($graficas!=""){
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<fechaI>$fechaI</fechaI>
							<fechaF>$fechaF</fechaF>
							<combo>$combo</combo>");
					$ctrl=0;
					do{
						$pos=intval(substr($grafica[$ctrl],7,2));
						if ($ctrl!=0)
							echo utf8_encode("<grafica$pos>$grafica[$ctrl]</grafica$pos>");
						$ctrl++;
					}while($ctrl<count($grafica));
					$ctrl--;
					echo ("<cant>$ctrl</cant>
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
			case 3:
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$titulo="REPORTE DE ENTRADAS VS SALIDAS DEL ALMACÉN DEL $fechaI AL $fechaF";
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				$graficas=reporteEntradasSalidas($fechaI,$fechaF,$titulo);
				$grafica=split("../../tmp/",$graficas);
				header("Content-type: text/xml");
				if ($graficas!=""){
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
					");
					$ctrl=0;
					do{
						if ($ctrl!=0)
							echo utf8_encode("<grafica$ctrl>$grafica[$ctrl]</grafica$ctrl>");
						$ctrl++;
					}while($ctrl<count($grafica));
					$ctrl--;
					echo ("<cant>$ctrl</cant>
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
	function reporteEntradas($fechaI,$fechaF,$titulo){
		//Conectarse a la BD de Almacén
		$conn = conecta("bd_almacen");
		//Sentencia SQL
		$sql_stm="SELECT SUM(costo_total) AS costo,fecha_entrada FROM entradas WHERE fecha_entrada BETWEEN '$fechaI' AND '$fechaF' GROUP BY fecha_entrada ORDER BY fecha_entrada";
		//Ejecutar Sentencia SQL
		$rs=mysql_query($sql_stm);
		//Extraer los datos a otro arreglo que permita un mejor manejo de la informacion
		if($datos=mysql_fetch_array($rs)){
			$fechas=array();
			$costos=array();
			do{
				$fechas[]=modFecha($datos["fecha_entrada"],1);
				$costos[]=$datos["costo"];
			}while($datos=mysql_fetch_array($rs));
			//Obtener el Grafico
			$grafica=graficaEntradas($fechas,$costos,$titulo);
			//Retornar la Grafica obtenida
			return $grafica;
			//Cerrar la conexion
			mysql_close($conn);
		}
		else{
			//Cerrar la conexion
			mysql_close($conn);
			//Retornar vacio
			return "";
		}
	}//Cierre de la funcion reporteEntradas($fechaI,$fechaF,$titulo)
	
	//Grafica que es incluida en el reporte de Entradas al almacen
	function graficaEntradas($fechas,$costos,$titulo){	
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ("../../../../includes/graficas/jpgraph/jpgraph_scatter.php");		
		//Obtener la cantidad de Registros
		$cantRes=count($fechas);
		//Registros por Grafica
		$cantDatos=20;
		$graficas="";
		//Obtener la cantidad de graficas
		$ciclos=$cantRes/$cantDatos;
		//Redondear el valor de los ciclos
		$ciclos=intval($ciclos);
		//Obtener el residuo para saber si incrementar en 1 la cantidad de ciclos
		$residuo=$cantRes%$cantDatos;
		//Si residuo es mayor a 0, incrementar en un los ciclos
		if($residuo>0)
			$ciclos+=1;
		//Inicializar variable de control para la cantidad de ciclos
		$cont=0;
		//Contador por cada grafica a dibujar
		$contPorGrafica=0;
		do{
			//Declarar el arreglo de costos Entrada por cada grafica
			$costoPorGrafica=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$costoPorGrafica[]=$costos[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$fechas[$contPorGrafica];
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			/**********************/
			$datay1 = $costoPorGrafica;
			// Setup the graph
			$graph = new Graph(1000,800);
			$graph->SetMarginColor('white');
			$graph->SetScale("textlin");
			$graph->SetFrame(false);
			$graph->SetMargin(100,80,60,100);
			// Setup the tab
			$graph->tabtitle->Set($titulo);
			$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,13);
			$graph->tabtitle->SetColor('darkred','#E1E1FF');
			// Mostrar Cuadricula
			$graph->xgrid->Show();
			//Eje X
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->xaxis->SetLabelAngle(45);
			//Exe Y
			$graph->yaxis->title->Set('Costos');
			$graph->yaxis->title->SetColor('darkred');
			$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,10);
			$graph->yaxis->SetLabelFormat('$%.2f');
			$graph->yaxis->SetTitleMargin(80);
			$graph->yaxis->scale->SetGrace(10);
			//Pie de Tabla
			$graph->footer->center->Set('Fechas');
			$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->footer->center->SetColor('darkred');
			// Create the plot
			$p1 = new LinePlot($datay1);
			$p1->SetColor("navy@0.5");
			$p1->SetWeight(3);//<--------Dejarlo en 0 para quitarlo
			// Use an image of favourite car as marker
			$p1->mark->SetType(MARK_IMG_DIAMOND,'red',0.5);
			// Displayes value on top of marker image
			$p1->value->SetFormat('%d mil');
			$p1->value->Show();
			$p1->value->SetColor('darkred');
			$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
			// Increase the margin so that the value is printed avove tje
			// img marker
			$p1->value->SetMargin(14);
			 //Valores de cada punto mostrado
			$p1->value->SetMargin(20);
			$p1->value->Show();
			$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
			$p1->value->SetColor('navy');
			$p1->value->SetFormat('$%.2f');
			$p1->value->SetAngle(45);
			// Incent the X-scale so the first and last point doesn't
			// fall on the edges
			$p1->SetCenter();
			$graph->Add($p1);
			//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
			$rnd=rand(0,1000);
			$grafica= "../../tmp/grafica".$rnd.".png";
			//Recuperar el nombre de la grafica en una cadena
			$graficas.=$grafica;
			//Dibujar la grafica y guardarla en un archivo temporal	
			$graph->Stroke($grafica);
			/**********************/
			$cont++;
		}while($cont<$ciclos);
		return $graficas;
	}//Cierre graficaEntradas($fechas,$costos,$titulo)
	
	/*Esta funcion genera el reporte mensual y regresa el periodo para indicar que los datos mostrados pueden ser exportados*/
	function reporteSalidas($fechaI,$fechaF,$titulo,$combo){
		//Conectarse a la BD de Almacén
		$conn = conecta("bd_almacen");
		//Si no hay parametro de ordenacion, ordenar por fecha y agrupar por fecha los costos de salida
		if($combo=="NADA")
			$sql_stm="SELECT SUM(costo_total) AS costo,fecha_salida AS ejeX FROM salidas WHERE fecha_salida BETWEEN '$fechaI' AND '$fechaF' GROUP BY fecha_salida ORDER BY fecha_salida";
		else
			$sql_stm="SELECT SUM(costo_total) AS costo,$combo AS ejeX FROM salidas WHERE fecha_salida BETWEEN '$fechaI' AND '$fechaF' GROUP BY $combo ORDER BY $combo";
		//Ejecutar Sentencia SQL
		$rs=mysql_query($sql_stm);
		//Extraer los datos a otro arreglo que permita un mejor manejo de la informacion
		if($datos=mysql_fetch_array($rs)){
			$ejeX=array();
			$costos=array();
			do{
				if ($combo=="NADA")
					$ejeX[]=modFecha($datos["ejeX"],1);
				else
					$ejeX[]=$datos["ejeX"];
				$costos[]=$datos["costo"];
			}while($datos=mysql_fetch_array($rs));
			//titulo del Eje X
			switch($combo){
				case "solicitante":
					$tituloX="Persona Solicitante";
				break;
				case "destino":
					$tituloX="Destino";
				break;
				case "depto_solicitante":
					$tituloX="Departamento";
				break;
				case "turno":
					$tituloX="Turno";
				break;
				default:
					$tituloX="Fecha";
				break;
			}
			//Obtener el Grafico
			$grafica=graficaSalidas($ejeX,$costos,$titulo,$tituloX);
			//Retornar la Grafica obtenida
			return $grafica;
			//Cerrar la conexion
			mysql_close($conn);
		}
		else{
			//Cerrar la conexion
			mysql_close($conn);
			//Retornar vacio
			return "";
		}
	}//Cierre de la funcion reporteSalidas($fechaI,$fechaF,$titulo,$combo)
	
	//Grafica de las salidas del almacen
	function graficaSalidas($ejeX,$costos,$titulo,$tituloX){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ("../../../../includes/graficas/jpgraph/jpgraph_scatter.php");
 
 		$cantRes=count($ejeX);
		//Obtener el total de los costos del arreglo de correspondiente
		$total=array_sum($costos);
		$total=number_format($total,2,".",",");
		$cantDatos=20;
		$graficas="";
		//Se toma 30 como punto de partida considerando que solo se graficaran 30 datos por grafica
		if($cantRes>$cantDatos){
			//Obtener la cantidad de graficas
			$ciclos=$cantRes/$cantDatos;
			//Redondear el valor de los ciclos
			$ciclos=intval($ciclos);
			//Obtener el residuo para saber si incrementar en 1 la cantidad de ciclos
			$residuo=$cantRes%$cantDatos;
			//Si residuo es mayor a 0, incrementar en un los ciclos
			if($residuo>0)
				$ciclos+=1;
			//Inicializar variable de control para la cantidad de ciclos
			$cont=0;
			//Contador por cada grafica a dibujar
			$contPorGrafica=0;
			do{
				//Declarar el arreglo de costos por cada grafica
				$costoPorGrafica=array();
				//Declarar el arreglo de leyendas por cada grafica
				$leyendaPorGrafica=array();
				//Obtener los datos a graficar
				do{
					//Asignar a la posicion actual el valor de costos en la posicion que corresponde
					$costoPorGrafica[]=$costos[$contPorGrafica];
					//Asignar a la posicion actual la leyenda en la posicion que corresponde
					$leyendaPorGrafica[]=$ejeX[$contPorGrafica];
					//Incrementar la variable de control por cada grafica
					$contPorGrafica++;
				}while(count($costoPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
				/*********/
				$datay1 = $costoPorGrafica;
				// Setup the graph
				$graph = new Graph(1000,800);
				$graph->SetMarginColor('white');
				$graph->SetScale("textlin");
				$graph->SetFrame(false);
				$graph->SetMargin(100,80,60,200);
				// Setup the tab
				$graph->tabtitle->Set($titulo);
				$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,13);
				$graph->tabtitle->SetColor('darkred','#E1E1FF');
				// Enable X-grid as well
				$graph->xgrid->Show();
				$graph->yaxis->title->Set('Costos');
				$graph->yaxis->title->SetColor('darkred');
				$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,10);
				$graph->yaxis->SetLabelFormat('$%.2f');
				$graph->yaxis->SetTitleMargin(80);
				$graph->yaxis->scale->SetGrace(20);
				// Use months as X-labels
				$graph->xaxis->SetTickLabels($leyendaPorGrafica);
				$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
				$graph->xaxis->SetLabelAngle(45);
				$graph->footer->center->Set($tituloX);
				$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
				$graph->footer->center->SetColor('darkred');
				// Create the plot
				$p1 = new LinePlot($datay1);
				$p1->SetColor("navy@0.5");
				$p1->SetWeight(3);//<--------Dejarlo en 0 para quitarlo
				// Use an image of favourite car as marker
				$p1->mark->SetType(MARK_IMG_DIAMOND,'red',0.5);
				// Displayes value on top of marker image
				$p1->value->SetFormat('%d mil');
				$p1->value->Show();
				$p1->value->SetColor('darkred');
				$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
				// Increase the margin so that the value is printed avove tje
				// img marker
				$p1->value->SetMargin(14);
				 //Valores de cada punto mostrado
				$p1->value->SetMargin(20);
				$p1->value->Show();
				$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
				$p1->value->SetColor('navy');
				$p1->value->SetFormat('$%.2f');
				$p1->value->SetAngle(45);
				// Incent the X-scale so the first and last point doesn't
				// fall on the edges
				$p1->SetCenter();
				//Agregar la Leyenda
				$p1->SetLegend('COSTO TOTAL DE SALIDAS $'.$total);
				$graph->legend->Pos(0.8,0.0,'center','top');
				$graph->Add($p1);
				//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
				$rnd=rand(0,1000);
				//Contar cuantas imagenes son
				$cantGraficas="";
				if($cont<10)
					$cantGraficas="0".$cont;
				else
					$cantGraficas=$cont;
				$grafica="../../tmp/grafica$cantGraficas".$rnd.".png";
				$graficas.=$grafica;
				//Dibujar la grafica y guardarla en un archivo temporal	
				$graph->Stroke($grafica);
				/********/
				$cont++;
			}while($cont<$ciclos);
		}
		else{
			$datay1 = $costos;
			// Setup the graph
			$graph = new Graph(1000,800);
			$graph->SetMarginColor('white');
			$graph->SetScale("textlin");
			$graph->SetFrame(false);
			$graph->SetMargin(100,80,60,200);
			// Setup the tab
			$graph->tabtitle->Set($titulo);
			$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,13);
			$graph->tabtitle->SetColor('darkred','#E1E1FF');
			// Enable X-grid as well
			$graph->xgrid->Show();
			$graph->yaxis->title->Set('Costos');
			$graph->yaxis->title->SetColor('darkred');
			$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,10);
			$graph->yaxis->SetLabelFormat('$%.2f');
			$graph->yaxis->SetTitleMargin(80);
			$graph->yaxis->scale->SetGrace(20);
			// Use months as X-labels
			$graph->xaxis->SetTickLabels($ejeX);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->xaxis->SetLabelAngle(45);
			$graph->footer->center->Set($tituloX);
			$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->footer->center->SetColor('darkred');
			// Create the plot
			$p1 = new LinePlot($datay1);
			$p1->SetColor("navy@0.5");
			$p1->SetWeight(3);//<--------Dejarlo en 0 para quitarlo
			// Use an image of favourite car as marker
			$p1->mark->SetType(MARK_IMG_DIAMOND,'red',0.5);
			// Displayes value on top of marker image
			$p1->value->SetFormat('%d mil');
			$p1->value->Show();
			$p1->value->SetColor('darkred');
			$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
			// Increase the margin so that the value is printed avove tje
			// img marker
			$p1->value->SetMargin(14);
			 //Valores de cada punto mostrado
			$p1->value->SetMargin(20);
			$p1->value->Show();
			$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
			$p1->value->SetColor('navy');
			$p1->value->SetFormat('$%.2f');
			$p1->value->SetAngle(45);
			// Incent the X-scale so the first and last point doesn't
			// fall on the edges
			$p1->SetCenter();
			//Agregar la Leyenda
			$p1->SetLegend('COSTO TOTAL SALIDAS $'.$total);
			$graph->legend->Pos(0.8,0.0,'center','top');
			$graph->Add($p1);
			//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
			$rnd=rand(0,1000);
			$graficas= "../../tmp/grafica01".$rnd.".png";
			//Dibujar la grafica y guardarla en un archivo temporal	
			$graph->Stroke($graficas);
		}
		return $graficas;
	}//Fin de function graficaSalidas($ejeX,$costos,$titulo)
	
	/*Esta funcion genera el reporte mensual y regresa el periodo para indicar que los datos mostrados pueden ser exportados*/
	function reporteEntradasSalidas($fechaI,$fechaF,$titulo){
		//Obtener la cantidad de Dias entre las 2 Fechas
		$dias=restarFechas($fechaI,$fechaF)+1;
		$verificaMes=0;
		//Partir la Fecha de Inicio en secciones de dia, mes y año
		$diaI=substr($fechaI,-2);
		$mesI=substr($fechaI,5,2);
		$anioI=substr($fechaI,0,4);
		//Obtener la cantidad de Dias del primer Mes
		$cantDiasMesCurso=diasMes($mesI,$anioI);
		//Convertir en numero los dias,mes y año de la Fecha de Inicio
		$diasActual=0+$diaI;
		$mesActual=0+$mesI;
		$anioActual=0+$anioI;
		//Partir la Fecha de Fin en secciones de dia, mes y año
		$diaF=substr($fechaF,-2);
		$mesF=substr($fechaF,5,2);
		$anioF=substr($fechaF,0,4);
		//Convertir en numero los dias,mes y año de la Fecha de Inicio
		$diasTope=0+$diaF;
		$mesTope=0+$mesF;
		$anioTope=0+$anioF;
		//Obtener en el contador como primer valor
		$cont=$diasActual;
		//Arreglo con la cantidad de Dias por Mes
		$cantDias=array();
		//Arreglo con las Fechas
		$fechas=array();
		//Proceso cuando el año de tope e inicial son iguales
		if ($anioTope==$anioActual){
			//Proceso cuando el mes de Tope es mayor al Actual
			if ($mesTope>$mesActual){
				/***********************************/
				$cols=($cantDiasMesCurso-$diasActual)+1;
				$cantDias[]=$cols;
				$ctrlFechas=$diasActual;
				do{
					$mesConc="".$mesActual;
					$diaConc="".$cont;
					if ($mesActual<10)
						$mesConc="0".$mesActual;
					if ($cont<10)
						$diaConc="0".$cont;
					$fechas[]=$anioActual."-".$mesConc."-".$diaConc;
					$cont++;
				}while($cont<=$cantDiasMesCurso);
				/***********************************/
				if(($mesActual+1)<$mesTope){
					//Siguientes Meses hasta antes del Tope
					do{
						$mesActual=$mesActual+1;
						$cantDiasMesCurso=diasMes($mesActual,$anioActual);
						$cont=1;
						do{
							$mesConc="".$mesActual;
							$diaConc="".$cont;
							if ($mesActual<10)
								$mesConc="0".$mesActual;
							if ($cont<10)
								$diaConc="0".$cont;
							$fechas[]=$anioActual."-".$mesConc."-".$diaConc;
							$cont++;
						}while($cont<=$cantDiasMesCurso);
						/***********************************/
						$cols=$cantDiasMesCurso;
						$cantDias[]=$cols;
						/***********************************/
					}while(($mesActual+1)<$mesTope);
				}
				//Mes Tope
				$mesActual=$mesTope;
				$cont=1;
				do{
					$mesConc="".$mesActual;
					$diaConc="".$cont;
					if ($mesActual<10)
						$mesConc="0".$mesActual;
					if ($cont<10)
						$diaConc="0".$cont;
					$fechas[]=$anioActual."-".$mesConc."-".$diaConc;
					$cont++;
				}while($cont<=$diasTope);
				/***********************************/
				$cols=$diasTope;
				$cantDias[]=$cols;
				/***********************************/
			}
			//Procesos cuando el mes de Tope y de inicio son iguales
			else{
				if($mesTope==$mesActual){
					do{
						$mesConc="".$mesActual;
						$diaConc="".$cont;
						if ($mesActual<10)
							$mesConc="0".$mesActual;
						if ($cont<10)
							$diaConc="0".$cont;
						$fechas[]=$anioActual."-".$mesConc."-".$diaConc;
						$cont++;
					}while($cont<=$diaF);
				}
				/***********************************/
				$cols=($diaF-$diaI)+1;
				$cantDias[]=$cols;
				/***********************************/
			}
		}
		//Proceso cuando los años son diferentes
		else{	
			$ctrl=1;
			//Primer Mes
			do{
				$mesConc="".$mesActual;
				$diaConc="".$cont;
				if ($mesActual<10)
					$mesConc="0".$mesActual;
				if ($cont<10)
					$diaConc="0".$cont;
				$fechas[]=$anioActual."-".$mesConc."-".$diaConc;
				$cont++;
			}while($cont<=$cantDiasMesCurso);
			/***********************************/
			$cols=($cantDiasMesCurso-$diasActual)+1;
			$cantDias[]=$cols;
			/***********************************/
			$estado=0;
			//Meses Siguientes
			do{
				$mesActual++;
				if($mesActual>12){
					$mesActual=$mesActual-12;
					$anioActual++;
				}
				$cantDiasMesCurso=diasMes($mesActual,$anioActual);
				/***********************************/
				$cols=$cantDiasMesCurso;
				$cantDias[]=$cols;
				/***********************************/
				$cont=1;
				do{
					$mesConc="".$mesActual;
					$diaConc="".$cont;
					if ($mesActual<10)
						$mesConc="0".$mesActual;
					if ($cont<10)
						$diaConc="0".$cont;
					$fechas[]=$anioActual."-".$mesConc."-".$diaConc;
					$cont++;
				}while($cont<=$cantDiasMesCurso);
				if ($anioActual==$anioTope && $mesActual==($mesTope-1))
					$estado=1;
			}while($estado!=1);
			//Ultimo Mes
			$cont=1;
			do{
				$mesConc="".$mesActual;
				$diaConc="".$cont;
				if ($mesActual<10)
					$mesConc="0".$mesActual;
				if ($cont<10)
					$diaConc="0".$cont;
				$fechas[]=$anioActual."-".$mesConc."-".$diaConc;
				$cont++;
			}while($cont<=$diasTope);
			/***********************************/
			$cols=$diasTope;
			$cantDias[]=$cols;
			/***********************************/
		}
		//Array de costos de E/S
		$costosE=array();
		$costosS=array();
		//Conectarse a la BD de Almacén
		$conn = conecta("bd_almacen");
		//Contador para recorrer el arreglo de Fechas generado
		$cont=0;
		do{
			//Sentencia SQL de Entradas
			$sql_stmIn="SELECT SUM(costo_total) AS costoEntradas FROM entradas WHERE fecha_entrada='$fechas[$cont]'";
			//Ejecutar Sentencia SQL
			$datoE=mysql_fetch_array(mysql_query($sql_stmIn));
			if($datoE["costoEntradas"]!=NULL)
				$costosE[]=$datoE["costoEntradas"];
			else
				$costosE[]=0;
			//Sentencia SQL de Salidas
			$sql_stmOut="SELECT SUM(costo_total) AS costoSalidas FROM salidas WHERE fecha_salida='$fechas[$cont]'";
			//Ejecutar Sentencia SQL
			$datoS=mysql_fetch_array(mysql_query($sql_stmOut));
			//Recuperar el dato de la consulta y pasarlo al arreglo de costos
			if($datoS["costoSalidas"]!=NULL)
				$costosS[]=$datoS["costoSalidas"];
			else
				$costosS[]=0;
			$cont++;
		}while($cont<count($fechas));
		//Generar el Grafico correspondiente
		$graficos=graficoEvsS($costosE,$costosS,$fechas,$titulo);
		return $graficos;
	}//Cierre de la funcion reporteSalidas($fechaI,$fechaF,$titulo,$combo)
		
	/*Funcion que dibuja el grafico de Entradas vs Salidas*/
	function graficoEvsS($costosE,$costosS,$fechas,$titulo){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
	
		//Reorganizar el arreglo de Fechas para mostrar los valores de 
		//forma mas presentable
		$tamFechas=0;
		do{
			$fechas[$tamFechas]=modFecha($fechas[$tamFechas],1);
			$tamFechas++;
		}while($tamFechas<count($fechas));

		//Obtener el total de los costos de Entrada
		$totalE=array_sum($costosE);
		$totalE=number_format($totalE,2,".",",");
		//Obtener el total de los costos de Salida
		$totalS=array_sum($costosS);
		$totalS=number_format($totalS,2,".",",");
		//Contador para verificar la cantidad de graficas tontas
		$dumbGraf=0;
		//Obtener la cantidad de Registros
		$cantRes=count($fechas);
		//Registros por Grafica
		$cantDatos=20;
		$graficas="";
		
		//Obtener la cantidad de graficas
		$ciclos=$cantRes/$cantDatos;
		//Redondear el valor de los ciclos
		$ciclos=intval($ciclos);
		//Obtener el residuo para saber si incrementar en 1 la cantidad de ciclos
		$residuo=$cantRes%$cantDatos;
		//Si residuo es mayor a 0, incrementar en un los ciclos
		if($residuo>0)
			$ciclos+=1;
		//Inicializar variable de control para la cantidad de ciclos
		$cont=0;
		//Contador por cada grafica a dibujar
		$contPorGrafica=0;
		do{
			//Declarar el arreglo de costos Entrada por cada grafica
			$costoPorGraficaE=array();
			//Declarar el arreglo de costos Salida por cada grafica
			$costoPorGraficaS=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$costoPorGraficaE[]=$costosE[$contPorGrafica];
				//Asignar a la posicion actual el valor de costos de Salida
				$costoPorGraficaS[]=$costosS[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$fechas[$contPorGrafica];
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			//Obtener el total de los costos de Entrada
			$entradas=array_sum($costoPorGraficaE);
			$salidas=array_sum($costoPorGraficaS);
			if(($entradas+$salidas)!=0){
				$data1y=$costoPorGraficaE;
				$data2y=$costoPorGraficaS;
				// Create the graph. These two calls are always required
				$graph = new Graph(1000,800);    
				$graph->SetScale("textlin");
				$graph->SetShadow();
				$graph->img->SetMargin(100,80,60,100);
				// Create the bar plots
				$b1plot = new BarPlot($data1y);
				$b1plot->SetWidth(9);
				$b1plot->SetFillColor("orange");
				$b1plot->SetLegend('ENTRADAS: $'.$totalE);
				$b1plot->SetCenter();
				//Mostrar los valores de las Entradas
				$b1plot->value->Show();
				$b1plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
				$b1plot->value->SetColor('navy');
				$b1plot->value->SetFormat('$%.2f');
				$b1plot->value->SetAngle(90);
				$b2plot = new BarPlot($data2y);
				$b2plot->SetWidth(9);
				$b2plot->SetFillColor("blue");
				$b2plot->SetLegend('SALIDAS: $'.$totalS);
				$b2plot->SetCenter();
				//Mostrar los valores de las Salidas
				$b2plot->value->Show();
				$b2plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
				$b2plot->value->SetColor('navy');
				$b2plot->value->SetFormat('$%.2f');
				$b2plot->value->SetAngle(90);
				// Create the grouped bar plot
				$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
				// ...and add it to the graPH
				$graph->Add($gbplot);
				$graph->title->Set($titulo);
				// Eje X
				$graph->xgrid->Show();
				$graph->yaxis->title->Set('Costos');
				$graph->yaxis->title->SetColor('darkred');
				$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,10);
				$graph->yaxis->SetLabelFormat('$%.2f');
				$graph->yaxis->SetTitleMargin(80);
				$graph->yaxis->scale->SetGrace(20);
				// Eje Y
				$graph->xaxis->SetTickLabels($leyendaPorGrafica);
				$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
				$graph->xaxis->SetLabelAngle(45);
				$graph->footer->center->Set("Fechas");
				$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
				$graph->footer->center->SetColor('darkred');
				//Titulo de la grafica
				$graph->title->SetFont(FF_FONT1,FS_BOLD);
				//Crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
				$rnd=rand(0,1000);
				$grafica= "../../tmp/grafica".$rnd.".png";
				$graficas.=$grafica;
				//Dibujar la grafica y guardarla en un archivo temporal	
				$graph->Stroke($grafica);
			}
			$cont++;
		}while($cont<$ciclos);
		return $graficas;
	}//Cierre de la funcion function graficoEvsS($costosE,$cantE,$fechaE,$costosS,$cantS,$fechaS)
?>
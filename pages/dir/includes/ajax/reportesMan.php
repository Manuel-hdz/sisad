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
				//Recuperar los datos
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$orden=$_GET["orden"];
				$area=$_GET["area"];
				//Verificar el titulo que tendra la grafica
				if ($area=="CONCRETO")
					//Crear el titulo
					$titulo="REPORTE DE SERVICIOS PREVENTIVOS DEL $fechaI AL $fechaF \nEN MANTENIMIENTO SUPERFICIE";
				else
					//Crear el titulo
					$titulo="REPORTE DE SERVICIOS PREVENTIVOS DEL $fechaI AL $fechaF \nEN MANTENIMIENTO $area";
				//Modificar las Fechara en formato legible por MySQL
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Ejecutar la funcion que genera el grafico
				$graficas=reporteServiciosPreventivos($fechaI,$fechaF,$area,$orden,$titulo);
				header("Content-type: text/xml");
				if ($graficas!=""){
					$grafica=split("../../tmp/",$graficas);
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
				//Recuperar los datos
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$orden=$_GET["orden"];
				$area=$_GET["area"];
				//Verificar el titulo que tendra la grafica
				if ($area=="CONCRETO")
					//Crear el titulo
					$titulo="REPORTE DE SERVICIOS CORRECTIVOS DEL $fechaI AL $fechaF \nEN MANTENIMIENTO SUPERFICIE";
				else
					//Crear el titulo
					$titulo="REPORTE DE SERVICIOS CORRECTIVOS DEL $fechaI AL $fechaF \nEN MANTENIMIENTO $area";
				//Modificar las Fechara en formato legible por MySQL
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Ejecutar la funcion que genera el grafico
				$graficas=reporteServiciosCorrectivos($fechaI,$fechaF,$area,$orden,$titulo);
				header("Content-type: text/xml");
				if ($graficas!=""){
					$grafica=split("../../tmp/",$graficas);
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
			case 3:
				//Recuperar los datos
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$area=$_GET["area"];
				//Verificar el titulo que tendra la grafica
				if ($area=="CONCRETO")
					//Crear el titulo
					$titulo="REPORTE DE SERVICIOS PREVENTIVOS VS CORRECTIVOS DEL $fechaI AL $fechaF \nEN MANTENIMIENTO SUPERFICIE";
				else
					//Crear el titulo
					$titulo="REPORTE DE SERVICIOS PREVENTIVOS VS CORRECTIVOS DEL $fechaI AL $fechaF \nEN MANTENIMIENTO $area";
				//Modificar las Fechara en formato legible por MySQL
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Ejecutar la funcion que genera el grafico
				$grafica=reporteServiciosPvsC($fechaI,$fechaF,$area,$titulo);
				header("Content-type: text/xml");
				if ($grafica!=""){
					$grafica=str_replace("../../tmp/","",$grafica);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<grafica>$grafica</grafica>
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
	
	/*Funcion que genera el grafico correspondiente a los servicios de Mantenimiento Preventivo*/
	function reporteServiciosPreventivos($fechaI,$fechaF,$area,$orden,$titulo){
		//Conectarse a la BD
		$conn=conecta("bd_mantenimiento");
		//Sentencia SQL
		$sql_stm="SELECT SUM(costo_mtto) AS costoPrev,$orden AS ejeX FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' 
				AND tipo_mtto='PREVENTIVO' AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='$area') GROUP BY $orden";
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			$ejeX=array();
			$costos=array();
			do{
				if ($orden=="fecha_mtto")
					$ejeX[]=modFecha($datos["ejeX"],1);
				else
					$ejeX[]=$datos["ejeX"];
				$costos[]=$datos["costoPrev"];
			}while($datos=mysql_fetch_array($rs));
			//Obtener la leyenda para el Exe X
			if ($orden=="fecha_mtto")
				$etiquetaX="Fecha";
			else
				$etiquetaX="Equipo";

			$graficas=graficaServiciosPreventivos($ejeX,$costos,$titulo,$etiquetaX);
			mysql_close($conn);
			return $graficas;
		}
		else{
			mysql_close($conn);
			return "";
		}
	}//Fin de reporteServiciosPreventivos($fechaI,$fechaF,$area,$orden,$titulo)
	
	/*Funcion que dibuja la grafica de los servicios preventivos*/
	function graficaServiciosPreventivos($ejeX,$costos,$titulo,$etiquetaX){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ("../../../../includes/graficas/jpgraph/jpgraph_scatter.php");		
		//Obtener la cantidad de Registros
		$cantRes=count($ejeX);
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
				$leyendaPorGrafica[]=$ejeX[$contPorGrafica];
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
			$graph->footer->center->Set($etiquetaX);
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
	}//Fin de function graficaServiciosPreventivos($ejeX,$costos,$titulo,$etiquetaX)
	
	/*Funcion que genera el grafico correspondiente a los servicios de Mantenimiento Preventivo*/
	function reporteServiciosCorrectivos($fechaI,$fechaF,$area,$orden,$titulo){
		//Conectarse a la BD
		$conn=conecta("bd_mantenimiento");
		//Sentencia SQL
		$sql_stm="SELECT SUM(costo_mtto) AS costoCorr,$orden AS ejeX FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' 
				AND tipo_mtto='CORRECTIVO' AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='$area') GROUP BY $orden";
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			$ejeX=array();
			$costos=array();
			do{
				if ($orden=="fecha_mtto")
					$ejeX[]=modFecha($datos["ejeX"],1);
				else
					$ejeX[]=$datos["ejeX"];
				$costos[]=$datos["costoCorr"];
			}while($datos=mysql_fetch_array($rs));
			//Obtener la leyenda para el Exe X
			if ($orden=="fecha_mtto")
				$etiquetaX="Fecha";
			else
				$etiquetaX="Equipo";

			$graficas=graficaServiciosCorrectivos($ejeX,$costos,$titulo,$etiquetaX);
			mysql_close($conn);
			return $graficas;
		}
		else{
			mysql_close($conn);
			return "";
		}
	}//Fin de reporteServiciosCorrectivos($fechaI,$fechaF,$area,$orden,$titulo)
	
	/*Funcion que dibuja la grafica de los servicios correctivos*/
	function graficaServiciosCorrectivos($ejeX,$costos,$titulo,$etiquetaX){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ("../../../../includes/graficas/jpgraph/jpgraph_scatter.php");		
		//Obtener la cantidad de Registros
		$cantRes=count($ejeX);
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
				$leyendaPorGrafica[]=$ejeX[$contPorGrafica];
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
			$graph->footer->center->Set($etiquetaX);
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
	}//Fin de function graficaServiciosCorrectivos($ejeX,$costos,$titulo,$etiquetaX)
	
	//Reporte de Ventas realizadas
	function reporteServiciosPvsC($fechaI,$fechaF,$area,$titulo){
		//Conexion con la BD de Compras
		$conn=conecta("bd_mantenimiento");
		$sql_stm="SELECT SUM(costo_mtto) AS costo,COUNT(costo_mtto) AS cant,tipo_mtto FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' 
				AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='$area') GROUP BY tipo_mtto ORDER BY tipo_mtto DESC";
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			$costoP=0;
			$costoC=0;
			$cantP=0;
			$cantC=0;
			do{
				if($datos["tipo_mtto"]=="PREVENTIVO"){
					$costoP=$datos["costo"];
					$cantP=$datos["cant"];
					$ejeX[]="SERVICIOS PREVENTIVOS\n$".number_format($costoP,2,".",",");
				}
				else{
					if($cantP==0)
						$ejeX[]="SERVICIOS PREVENTIVOS\n$0.00";
					$costoC=$datos["costo"];
					$cantC=$datos["cant"];
					$ejeX[]="SERVICIOS CORRECTIVOS\n$".number_format($costoC,2,".",",");
				}
			}while($datos=mysql_fetch_array($rs));
			if($cantC==0)
				$ejeX[]="SERVICIOS CORRECTIVOS\n$0.00";
			//Total invertido de los Servicios de Mantenimiento
			$totalCosto=$costoP+$costoC;
			$ejeX[]="TOTAL DE SERVICIOS\n$".number_format($totalCosto,2,".",",");
			//Total de Servicios
			$totalCant=$cantP+$cantC;
			$grafica=graficaServiciosPvsC($ejeX,$cantP,$cantC,$totalCant,$titulo);
			mysql_close($conn);
			return $grafica;
		}
	}//function reporteVentas($fechaI,$fechaF,$orden,$titulo)
	
	function graficaServiciosPvsC($ejeX,$cantP,$cantC,$totalCant,$titulo){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
	 
		// We need some data
		$datay=array($cantP,$cantC,$totalCant);
		 
		// Setup the graph. 
		$graph = new Graph(900,600);    
		$graph->SetScale("textlin");
		$graph->img->SetMargin(80,80,50,60);
		 
		$graph->title->Set($titulo);
		$graph->title->SetColor('darkgreen');
		 
		// Setup font for axis
		$graph->yaxis->title->Set('CANTIDAD SERVICIOS');
		$graph->yaxis->title->SetColor('darkred');
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->yaxis->SetTitleMargin(40);
		$graph->yaxis->scale->SetGrace(30);
		
		$graph->xaxis->SetTickLabels($ejeX);
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,10);
		
		$graph->footer->center->Set("SERVICIOS MANTENIMIENTO");
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		
		// Create the bar pot
		$bplot = new BarPlot($datay);
		
		$bplot->SetWidth(0.6);
		$bplot->value->Show();
		$bplot->value->SetFormat('%.d Servicio(s)');
		$bplot->value->SetFont(FF_ARIAL,FS_BOLD,12);
		// Setup color for gradient fill style 
		$bplot->SetFillGradient("darkgreen","lightsteelblue",GRAD_VER);
		 
		// Set color for the frame of each bar
		$bplot->SetColor("darkgreen");
		$graph->Add($bplot);

		$rnd=rand(0,1000);
		$grafica= "../../tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}
?>
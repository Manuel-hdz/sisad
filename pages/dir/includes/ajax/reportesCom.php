<?php
	/**
	  * Nombre del Módulo: Direccion General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 07/Marzo/2012                                      			
	  * Descripción: Este archivo contiene la función que muestra los Reportes de Compras
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
				//Crear el titulo
				$titulo="REPORTE DE COMPRAS DEL $fechaI AL $fechaF";
				//Modificar las Fechara en formato legible por MySQL
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Ejecutar la funcion que genera el grafico
				$graficas=reporteCompras($fechaI,$fechaF,$orden,$titulo);
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
				//Crear el titulo
				$titulo="REPORTE DE VENTAS DEL $fechaI AL $fechaF";
				//Modificar las Fechara en formato legible por MySQL
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Ejecutar la funcion que genera el grafico
				$graficas=reporteVentas($fechaI,$fechaF,$orden,$titulo);
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
				//Crear el titulo
				$titulo="REPORTE DE COMPRAS VS VENTAS DEL $fechaI AL $fechaF";
				//Modificar las Fechara en formato legible por MySQL
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Ejecutar la funcion que genera el grafico
				$grafica=reporteComprasVentas($fechaI,$fechaF,$titulo);
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
			case 4:
				//Recuperar los datos
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$orden=$_GET["orden"];
				//Crear el titulo
				$titulo="REPORTE DE CAJA CHICA DEL $fechaI AL $fechaF";
				//Modificar las Fechara en formato legible por MySQL
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Ejecutar la funcion que genera el grafico
				$graficas=reporteCajaChica($fechaI,$fechaF,$orden,$titulo);
				header("Content-type: text/xml");
				if ($graficas!=""){
					$grafica=split("../../tmp/",$graficas);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<fechaI>$fechaI</fechaI>
							<fechaF>$fechaF</fechaF>
							<combo>$orden</combo>
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
	
	//Reporte de Compras
	function reporteCompras($fechaI,$fechaF,$orden,$titulo){
		//Conexion con la BD de Compras
		$conn=conecta("bd_compras");
		$ejeX=array();
		$peso=array();
		$dolar=array();
		//Cuando el patron de ordenamiento es Proveedor
		if($orden=="proveedores_rfc"){
			$sql_stm="SELECT SUM(total) AS total,tipo_moneda,razon_social AS ejeX FROM pedido JOIN proveedores ON proveedores_rfc=rfc 
					WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND pedido.estado!='CANCELADO' GROUP BY tipo_moneda,proveedores_rfc ORDER BY razon_social,tipo_moneda DESC";
			$rs=mysql_query($sql_stm);
			if($datos=mysql_fetch_array($rs)){
				$ejeX[]=$datos["ejeX"];
				do{
					$ejeX[]=$datos["ejeX"];
					if($datos["tipo_moneda"]=="PESOS")
						$peso[$datos["ejeX"]]=$datos["total"];
					else
						$dolar[$datos["ejeX"]]=$datos["total"];
				}while($datos=mysql_fetch_array($rs));
				$etiquetaX="Proveedor";
				//Quitar valores repetidos
				$ejeX=array_unique($ejeX);
				//Reindexar el arreglo
				$ejeX=array_values($ejeX);
				//Arreglos para Peso y Dolar necesarios para graficar, APLICAR ESTO PARA RFC_PROVEEDOR
				$arrPesos=array();
				$arrDolares=array();
				//Recorrer el arreglo del EjeX para identificar los valores que corresponden por PESO y DOLAR
				foreach($ejeX as $ind => $valor){
					$dineroEnPeso=0;
					$dineroEnDolar=0;
					foreach($peso as $clave => $value){
						if($valor==$clave){
							$dineroEnPeso+=$value;
						}
					}
					foreach($dolar as $clave => $value){
						if($valor==$clave){
							$dineroEnDolar+=$value;
						}
					}
					//Agregar al arreglo de Pesos el valor obtenido
					$arrPesos[]=$dineroEnPeso;
					//Agregar al arreglo de Dolares el valor obtenido
					$arrDolares[]=$dineroEnDolar;
				}
				$titulo.=" \nCOMPRAS POR ".strtoupper($etiquetaX);
				$graficas=graficaComprasXProveedor($ejeX,$arrPesos,$arrDolares,$titulo,$etiquetaX);
				mysql_close($conn);
				return $graficas;
			}
		}
		//Cuando el patron de ordenamiento es Tipo de Moneda
		if($orden=="tipo_moneda"){
			$sql_stm="SELECT SUM(total) AS total,COUNT(total) AS compras,tipo_moneda FROM pedido WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND estado!='CANCELADO' GROUP BY tipo_moneda ORDER BY tipo_moneda DESC";
			$rs=mysql_query($sql_stm);
			if($datos=mysql_fetch_array($rs)){
				$cantPesos=0;
				$cantDolares=0;
				do{
					$ejeX[]="$".number_format($datos["total"],2,".",",")." ".$datos["tipo_moneda"];
					if($datos["tipo_moneda"]=="PESOS"){
						$pesos=$datos["total"];
						$cantPesos=$datos["compras"];
					}
					else{
						$dolares=$datos["total"];
						$cantDolares=$datos["compras"];
					}
				}while($datos=mysql_fetch_array($rs));
				$grafica=graficaComprasXMoneda($ejeX,$cantPesos,$cantDolares,$titulo);
				mysql_close($conn);
				return $grafica;
			}
		}
		//Cuando el patron de ordenamiento es cualquier otro
		if($orden!="tipo_moneda" && $orden!="proveedores_rfc"){
			$sql_stm="SELECT SUM(total) AS total,tipo_moneda,$orden AS ejeX FROM pedido WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND estado!='CANCELADO' GROUP BY tipo_moneda,$orden ORDER BY $orden,tipo_moneda DESC";
			$rs=mysql_query($sql_stm);
			if($datos=mysql_fetch_array($rs)){
				$ejeX[]=$datos["ejeX"];
				do{
					$ejeX[]=$datos["ejeX"];
					if($datos["tipo_moneda"]=="PESOS")
						$peso[$datos["ejeX"]]=$datos["total"];
					else
						$dolar[$datos["ejeX"]]=$datos["total"];
				}while($datos=mysql_fetch_array($rs));
				if($orden=="fecha")
					$etiquetaX="Fecha";
				if($orden=="depto_solicitor")
					$etiquetaX="Departamento";
				if($orden=="via_pedido")
					$etiquetaX="Via Pedido";
				if($orden=="solicitor")
					$etiquetaX="Solicitante";
				//Quitar valores repetidos
				$ejeX=array_unique($ejeX);
				//Reindexar el arreglo
				$ejeX=array_values($ejeX);
				//Arreglos para Peso y Dolar necesarios para graficar, APLICAR ESTO PARA RFC_PROVEEDOR
				$arrPesos=array();
				$arrDolares=array();
				//Recorrer el arreglo del EjeX para identificar los valores que corresponden por PESO y DOLAR
				foreach($ejeX as $ind => $valor){
					$dineroEnPeso=0;
					$dineroEnDolar=0;
					foreach($peso as $clave => $value){
						if($valor==$clave){
							$dineroEnPeso+=$value;
						}
					}
					foreach($dolar as $clave => $value){
						if($valor==$clave){
							$dineroEnDolar+=$value;
						}
					}
					//Agregar al arreglo de Pesos el valor obtenido
					$arrPesos[]=$dineroEnPeso;
					//Agregar al arreglo de Dolares el valor obtenido
					$arrDolares[]=$dineroEnDolar;
				}
				$titulo.=" \nCOMPRAS POR ".strtoupper($etiquetaX);
				$graficas=graficaComprasXProveedor($ejeX,$arrPesos,$arrDolares,$titulo,$etiquetaX);
				mysql_close($conn);
				return $graficas;
			}
		}
	}//Fin de function reporteCompras($fechaI,$fechaF,$orden,$titulo)
	
	/*Grafica de Compras por Proveedor*/
	function graficaComprasXProveedor($ejeX,$peso,$dolar,$titulo,$etiquetaX){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
		//Obtener el total de los costos de Entrada
		$totalP=array_sum($peso);
		$totalP=number_format($totalP,2,".",",");
		//Obtener el total de los costos de Salida
		$totalD=array_sum($dolar);
		$totalD=number_format($totalD,2,".",",");

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
			//Declarar el arreglo de costos Peso por cada grafica
			$costoPorGraficaPeso=array();
			//Declarar el arreglo de costos Dolar por cada grafica
			$costoPorGraficaDolar=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener la longitud del ejeX mas grande
			$long=0;
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$costoPorGraficaPeso[]=$peso[$contPorGrafica];
				//Asignar a la posicion actual el valor de costos de Salida
				$costoPorGraficaDolar[]=$dolar[$contPorGrafica];
				if($etiquetaX=="Fecha")
					//Asignar a la posicion actual la leyenda en la posicion que corresponde
					$leyendaPorGrafica[]=modFecha($ejeX[$contPorGrafica],1);
				else
					//Asignar a la posicion actual la leyenda en la posicion que corresponde
					$leyendaPorGrafica[]=$ejeX[$contPorGrafica];
				//Reasignar la longitud del EjeX si la longitud actual es mayor a la anterior
				if ($long<strlen($ejeX[$contPorGrafica]))
					$long=strlen($ejeX[$contPorGrafica]);
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			$data1y=$costoPorGraficaPeso;
			$data2y=$costoPorGraficaDolar;
			// Create the graph. These two calls are always required
			$graph = new Graph(1000,800);    
			$graph->SetScale("textlin");
			$graph->SetShadow();
			if($long<=15)
				$graph->img->SetMargin(150,80,50,100);
			if($long>15 && $long<=30)
				$graph->img->SetMargin(150,80,50,200);
			if($long>30 && $long<60)
				$graph->img->SetMargin(250,80,50,350);
			if($long>60)
				$graph->img->SetMargin(350,80,50,450);
			// Create the bar plots
			$b1plot = new BarPlot($data1y);
			$b1plot->SetWidth(9);
			$b1plot->SetFillColor("darkgreen@0.2");
			$b1plot->SetLegend('$'.$totalP." PESOS ");
			$b1plot->SetCenter();
			//Mostrar los valores de las Entradas
			$b1plot->value->Show();
			$b1plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
			$b1plot->value->SetColor('navy');
			$b1plot->value->SetFormat('$%.2f');
			$b1plot->value->SetAngle(90);
			$b2plot = new BarPlot($data2y);
			$b2plot->SetWidth(9);
			$b2plot->SetFillColor("yellow@0.2");
			$b2plot->SetLegend('$'.$totalD." DÓLARES");
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
			$graph->yaxis->scale->SetGrace(30);
			// Eje Y
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->xaxis->SetLabelAngle(45);
			$graph->footer->center->Set($etiquetaX);
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
			/********/
			$cont++;
		}while($cont<$ciclos);
		return $graficas;
	}//Fin de function graficaComprasXProveedor($ejeX,$peso,$dolar,$titulo)
	
	/*Grafica de Compras por Tipo de Moneda*/
	function graficaComprasXMoneda($ejeX,$pesos,$dolares,$titulo){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
	 
		// We need some data
		$datay=array($pesos,$dolares);
		 
		// Setup the graph. 
		$graph = new Graph(600,400);    
		$graph->SetScale("textlin");
		$graph->img->SetMargin(80,80,50,50);
		 
		$graph->title->Set($titulo."\nCANTIDAD DE COMPRAS EN PESOS Y DÓLARES");
		$graph->title->SetColor('darkgreen');
		 
		// Setup font for axis
		$graph->yaxis->title->Set('Costos');
		$graph->yaxis->title->SetColor('darkred');
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->yaxis->SetTitleMargin(40);
		$graph->yaxis->scale->SetGrace(30);
		
		$graph->xaxis->SetTickLabels($ejeX);
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,10);
		
		$graph->footer->center->Set("Tipo de Moneda");
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		
		// Create the bar pot
		$bplot = new BarPlot($datay);
		
		$bplot->SetWidth(0.6);
		$bplot->value->Show();
		$bplot->value->SetFormat('%.d Compras');
		$bplot->value->SetFont(FF_ARIAL,FS_BOLD,12);
		// Setup color for gradient fill style 
		$bplot->SetFillGradient("darkgreen","lightsteelblue",GRAD_MIDVER);
		 
		// Set color for the frame of each bar
		$bplot->SetColor("darkgreen");
		$graph->Add($bplot);

		$rnd=rand(0,1000);
		$grafica= "../../tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}//Fin de function graficaComprasXMoneda($ejeX,$pesos,$dolares,$titulo)
	
	//Reporte de Ventas realizadas
	function reporteVentas($fechaI,$fechaF,$orden,$titulo){
		//Conexion con la BD de Compras
		$conn=conecta("bd_compras");
		//Control de la etiqueta de los valores del eje X
		$ejeX=array();
		//Control de ventas facturadas
		$facturaSI=array();
		//Control de ventas NO facturadas
		$facturaNO=array();
		//Cuando el patron de ordenamiento es Proveedor
		if($orden=="fecha"){
			$sql_stm="SELECT SUM(total) AS total,COUNT(total) AS ventas,factura FROM ventas WHERE fecha BETWEEN '$fechaI' AND '$fechaF' GROUP BY factura ORDER BY factura DESC";
			$rs=mysql_query($sql_stm);
			if($datos=mysql_fetch_array($rs)){
				$cantFact=0;
				$cantNoFact=0;
				$totalVenta=0;
				do{
					if($datos["factura"]=="SI"){
						$facturaSI=$datos["total"];
						$totalVenta+=$datos["total"];
						$cantFact=$datos["ventas"];
						$ejeX[]="$".number_format($datos["total"],2,".",",")."\nFACTURADOS";
					}
					else{
						$facturaNO=$datos["total"];
						$totalVenta+=$datos["total"];
						$cantNoFact=$datos["ventas"];
						$ejeX[]="$".number_format($datos["total"],2,".",",")."\nNO FACTURADOS";
					}
				}while($datos=mysql_fetch_array($rs));
				if($cantFact==0)
					$ejeX[]="$0.00\nFACTURADOS";
				if($cantNoFact==0)
					$ejeX[]="$0.00\n SIN FACTURAR";
				$ejeX[]="TOTAL DE VENTAS\n$".number_format($totalVenta,2,".",",");
				$totalVenta=$cantFact+$cantNoFact;
				$grafica=graficaComprasXFactura($ejeX,$cantFact,$cantNoFact,$totalVenta,$titulo);
				mysql_close($conn);
				return $grafica;
			}
		}
		
		//Cuando el patron de ordenamiento es Tipo de Moneda
		if($orden=="vendio" || $orden=="autorizador"){
			$sql_stm="SELECT SUM(total) AS total,$orden AS ejeX,factura FROM ventas WHERE fecha BETWEEN '2012-01-01' AND '2012-03-07' GROUP BY $orden,factura ORDER BY $orden,factura DESC";
			$rs=mysql_query($sql_stm);
			if($datos=mysql_fetch_array($rs)){
				$cantFact=0;
				$cantNoFact=0;
				do{
					$ejeX[]=$datos["ejeX"];
					if($datos["factura"]=="SI")
						$facturaSI[$datos["ejeX"]]=$datos["total"];
					else
						$facturaNO[$datos["ejeX"]]=$datos["total"];
				}while($datos=mysql_fetch_array($rs));
				if($orden=="vendio")
					$etiquetaX="Vendedor";
				if($orden=="autorizador")
					$etiquetaX="Autorizador";
				//Quitar valores repetidos
				$ejeX=array_unique($ejeX);
				//Reindexar el arreglo
				$ejeX=array_values($ejeX);
				//Arreglos para Peso y Dolar necesarios para graficar
				$arrFact=array();
				$arrNoFact=array();
				//Recorrer el arreglo del EjeX para identificar los valores que corresponden por FACTURA y SIN FACTURA
				foreach($ejeX as $ind => $valor){
					$dineroFacturado=0;
					$dineroNoFacturado=0;
					foreach($facturaSI as $clave => $value){
						if($valor==$clave){
							$dineroFacturado+=$value;
						}
					}
					foreach($facturaNO as $clave => $value){
						if($valor==$clave){
							$dineroNoFacturado+=$value;
						}
					}
					//Agregar al arreglo de Pesos el valor obtenido
					$arrFact[]=$dineroFacturado;
					//Agregar al arreglo de Dolares el valor obtenido
					$arrNoFact[]=$dineroNoFacturado;
				}
				$titulo.="\nVENTAS POR ".strtoupper($etiquetaX);
				$graficas=graficaComprasXPersona($ejeX,$arrFact,$arrNoFact,$titulo,$etiquetaX);
				mysql_close($conn);
				return $graficas;
			}
		}
		//Cuando el patron de ordenamiento es cualquier otro
		if($orden="cliente"){
			$sql_stm="SELECT SUM(total) AS total,razon_social AS ejeX,factura FROM ventas JOIN clientes ON rfc=clientes_rfc WHERE fecha BETWEEN '2012-01-01' AND '2012-03-07' 
						GROUP BY clientes_rfc,factura ORDER BY razon_social,factura DESC";
			$rs=mysql_query($sql_stm);
			if($datos=mysql_fetch_array($rs)){
				$cantFact=0;
				$cantNoFact=0;
				do{
					$ejeX[]=$datos["ejeX"];
					if($datos["factura"]=="SI")
						$facturaSI[$datos["ejeX"]]=$datos["total"];
					else
						$facturaNO[$datos["ejeX"]]=$datos["total"];
				}while($datos=mysql_fetch_array($rs));
				$etiquetaX="Cliente";
				//Quitar valores repetidos
				$ejeX=array_unique($ejeX);
				//Reindexar el arreglo
				$ejeX=array_values($ejeX);
				//Arreglos para Peso y Dolar necesarios para graficar
				$arrFact=array();
				$arrNoFact=array();
				//Recorrer el arreglo del EjeX para identificar los valores que corresponden por FACTURA y SIN FACTURA
				foreach($ejeX as $ind => $valor){
					$dineroFacturado=0;
					$dineroNoFacturado=0;
					foreach($facturaSI as $clave => $value){
						if($valor==$clave){
							$dineroFacturado+=$value;
						}
					}
					foreach($facturaNO as $clave => $value){
						if($valor==$clave){
							$dineroNoFacturado+=$value;
						}
					}
					//Agregar al arreglo de Pesos el valor obtenido
					$arrFact[]=$dineroFacturado;
					//Agregar al arreglo de Dolares el valor obtenido
					$arrNoFact[]=$dineroNoFacturado;
				}
				$titulo.="\nVENTAS POR ".strtoupper($etiquetaX);
				$graficas=graficaComprasXPersona($ejeX,$arrFact,$arrNoFact,$titulo,$etiquetaX);
				mysql_close($conn);
				return $graficas;
			}
		}
	}//function reporteVentas($fechaI,$fechaF,$orden,$titulo)
	
	/*Grafica de Compras por Tipo de Moneda*/
	function graficaComprasXFactura($ejeX,$facturaSi,$facturaNo,$totalVenta,$titulo){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
	 
		// We need some data
		$datay=array($facturaSi,$facturaNo,$totalVenta);
		 
		// Setup the graph. 
		$graph = new Graph(600,400);    
		$graph->SetScale("textlin");
		$graph->img->SetMargin(80,80,50,50);
		 
		$graph->title->Set($titulo);
		$graph->title->SetColor('darkgreen');
		 
		// Setup font for axis
		$graph->yaxis->title->Set('GANANCIA');
		$graph->yaxis->title->SetColor('darkred');
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->yaxis->SetTitleMargin(40);
		$graph->yaxis->scale->SetGrace(30);
		
		$graph->xaxis->SetTickLabels($ejeX);
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,10);
		
		$graph->footer->center->Set("VENTAS");
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		
		// Create the bar pot
		$bplot = new BarPlot($datay);
		
		$bplot->SetWidth(0.6);
		$bplot->value->Show();
		$bplot->value->SetFormat('%.d Ventas');
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
	}//Fin de function graficaComprasXMoneda($ejeX,$pesos,$dolares,$titulo)
	
	/*Grafica de Compras por Proveedor*/
	function graficaComprasXPersona($ejeX,$peso,$dolar,$titulo,$etiquetaX){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
		//Obtener el Total de la Venta
		$totalVenta=0;
		//Obtener el total de los costos de Entrada
		$totalP=array_sum($peso);
		//Incrementar el total facturado
		$totalVenta+=$totalP;
		$totalP=number_format($totalP,2,".",",");
		//Obtener el total de los costos de Salida
		$totalD=array_sum($dolar);
		//Incrementar el total Sin facturar
		$totalVenta+=$totalD;
		$totalD=number_format($totalD,2,".",",");
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
			//Declarar el arreglo de costos Peso por cada grafica
			$costoPorGraficaPeso=array();
			//Declarar el arreglo de costos Dolar por cada grafica
			$costoPorGraficaDolar=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener la longitud del ejeX mas grande
			$long=0;
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$costoPorGraficaPeso[]=$peso[$contPorGrafica];
				//Asignar a la posicion actual el valor de costos de Salida
				$costoPorGraficaDolar[]=$dolar[$contPorGrafica];
				if($etiquetaX=="Fecha")
					//Asignar a la posicion actual la leyenda en la posicion que corresponde
					$leyendaPorGrafica[]=modFecha($ejeX[$contPorGrafica],1);
				else
					//Asignar a la posicion actual la leyenda en la posicion que corresponde
					$leyendaPorGrafica[]=$ejeX[$contPorGrafica];
				//Reasignar la longitud del EjeX si la longitud actual es mayor a la anterior
				if ($long<strlen($ejeX[$contPorGrafica]))
					$long=strlen($ejeX[$contPorGrafica]);
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			$data1y=$costoPorGraficaPeso;
			$data2y=$costoPorGraficaDolar;
			// Create the graph. These two calls are always required
			$graph = new Graph(1000,800);    
			$graph->SetScale("textlin");
			$graph->SetShadow();
			if($long<=15)
				$graph->img->SetMargin(100,80,50,100);
			if($long>15 && $long<=30)
				$graph->img->SetMargin(150,80,50,200);
			if($long>30 && $long<60)
				$graph->img->SetMargin(250,80,50,350);
			if($long>60)
				$graph->img->SetMargin(350,80,50,450);
			// Create the bar plots
			$b1plot = new BarPlot($data1y);
			$b1plot->SetWidth(9);
			$b1plot->SetFillColor("darkgreen@0.2");
			$b1plot->SetLegend('$'.$totalP." FACTURADOS");
			$b1plot->SetCenter();
			//Mostrar los valores de las Entradas
			$b1plot->value->Show();
			$b1plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
			$b1plot->value->SetColor('navy');
			$b1plot->value->SetFormat('$%.2f');
			$b1plot->value->SetAngle(90);
			$b2plot = new BarPlot($data2y);
			$b2plot->SetWidth(9);
			$b2plot->SetFillColor("yellow@0.2");
			$b2plot->SetLegend('$'.$totalD." SIN FACTURAR");
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
			$graph->yaxis->scale->SetGrace(30);
			// Eje Y
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->xaxis->SetLabelAngle(45);
			$graph->footer->center->Set($etiquetaX);
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
			/********/
			$cont++;
		}while($cont<$ciclos);
		return $graficas;
	}//Fin de function graficaComprasXProveedor($ejeX,$peso,$dolar,$titulo)
	
	//Funcion de Reporte Grafico de Compras Vs Ventas
	function reporteComprasVentas($fechaI,$fechaF,$titulo){
		//Conectar a la BD de compras
		$conn=conecta("bd_compras");
		//Sentencia SQL para extraer el total y la cantidad de Ventas Realizadas
		$sql_stmVentas="SELECT SUM(total) AS total,COUNT(total) AS cantVentas FROM ventas WHERE fecha BETWEEN '$fechaI' AND '$fechaF'";
		//Ejecutar la sentencia de Ventas
		$rsVentas=mysql_query($sql_stmVentas);
		//Extraer los datos de la consulta
		$datosVentas=mysql_fetch_array($rsVentas);
		//Variables para manejo de informacion de las ventas
		$totalVentas=0;
		$cantVentas=0;
		//Variables para manejo de informacion de las compras en Pesos y Dolares
		$totalComprasPesos=0;
		$cantComprasPesos=0;
		$totalComprasDolares=0;
		$cantComprasDolares=0;
		//Pasar los resultados de la consulta a variables para su posterior manejo en la grafica, siempre y cuando existan resultados
		if($datosVentas["cantVentas"]>0){
			$totalVentas=$datosVentas["total"];
			$cantVentas=$datosVentas["cantVentas"];
		}
		//Sentencia SQL para extraer el total y la cantidad de Compras Realizadas por tipo de cambio
		$sql_stmCompras="SELECT SUM(total) AS total,COUNT(total) AS cantCompras,tipo_moneda FROM pedido WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND estado!='CANCELADO' GROUP BY tipo_moneda ORDER BY tipo_moneda DESC";
		//Ejecutar la sentencia de Compras
		$rsCompras=mysql_query($sql_stmCompras);
		//Verificar que la sentencia regrese resultados
		if($datosCompras=mysql_fetch_array($rsCompras)){
			//Recorrer los registros obtenidos de Compras
			do{
				if($datosCompras["tipo_moneda"]=="PESOS"){
					$totalComprasPesos=$datosCompras["total"];
					$cantComprasPesos=$datosCompras["cantCompras"];
				}
				else{
					$totalComprasDolares=$datosCompras["total"];
					$cantComprasDolares=$datosCompras["cantCompras"];
				}
			}while($datosCompras=mysql_fetch_array($rsCompras));
		}
		$grafica=graficaComprasVsVentas($totalVentas,$cantVentas,$totalComprasPesos,$cantComprasPesos,$totalComprasDolares,$cantComprasDolares,$titulo);
		return $grafica;
	}//Fin de function reporteComprasVentas($fechaI,$fechaF,$titulo)
	
	//Funcion que dibuja la grafica de compras contrea ventas
	function graficaComprasVsVentas($totalVentas,$cantVentas,$totalComprasPesos,$cantComprasPesos,$totalComprasDolares,$cantComprasDolares,$titulo){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
		$data1y=array($totalVentas);
		$data2y=array($totalComprasPesos);
		$data3y=array($totalComprasDolares);
		// Create the graph. These two calls are always required
		$graph = new Graph(800,600);  
		$graph->SetScale("textlin");
		//$graph->img->SetMargin(100,80,50,100);  
		$graph->Set90AndMargin(100,80,80,50);
		$graph->SetShadow();
		//Barra de Ventas
		$b1plot = new BarPlot($data1y);
		$b1plot->SetWidth(100);
		//$b1plot->SetFillColor("darkgreen@0.2");
		$b1plot->SetFillGradient("darkgreen","olivedrab1",GRAD_HOR);
		$b1plot->SetLegend($cantVentas." VENTAS EN PESOS");
		$b1plot->SetCenter();
		//Mostrar los valores de las Entradas
		$b1plot->value->Show();
		$b1plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$b1plot->value->SetColor('navy');
		$b1plot->value->SetFormat('$%.2f');
		//Barra de Compras en Pesos
		$b2plot = new BarPlot($data2y);
		$b2plot->SetWidth(100);
		//$b2plot->SetFillColor("red@0.2");
		$b2plot->SetFillGradient("darkred","darkred@0.5",GRAD_HOR);
		$b2plot->SetLegend($cantComprasPesos." COMPRAS EN PESOS");
		$b2plot->SetCenter();
		//Mostrar los valores de las Salidas
		$b2plot->value->Show();
		$b2plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$b2plot->value->SetColor('navy');
		$b2plot->value->SetFormat('$%.2f');
		//Barra de Compras en Dólares
		$b3plot = new BarPlot($data3y);
		$b3plot->SetWidth(100);
		$b3plot->SetFillGradient("navy","lightsteelblue",GRAD_HOR);
		$b3plot->SetLegend($cantComprasDolares." COMPRAS EN DÓLARES");
		$b3plot->SetCenter();
		//Mostrar los valores de las Salidas
		$b3plot->value->Show();
		$b3plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$b3plot->value->SetColor('navy');
		$b3plot->value->SetFormat('$%.2f');
		// Create the grouped bar plot
		$gbplot = new GroupBarPlot(array($b1plot,$b2plot,$b3plot));
		// ...and add it to the graPH
		$graph->Add($gbplot);
		$graph->title->Set($titulo);
		// Eje X
		$graph->xgrid->Show();
		//Quitar los valores del eje Y
		$graph->yaxis->Hide();
		$graph->yaxis->scale->SetGrace(20);
		// Eje Y
		$graph->xaxis->SetTickLabels(array("Ventas\n\n\n\n\n\n\n\n\nCompras en\nPesos\n\n\n\n\n\n\n\n\nCompras en\nDólares"));
		// Label align for X-axis
		$graph->xaxis->SetLabelAlign('right','center','right');
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
		//Titulo de la grafica
		$graph->title->SetFont(FF_FONT1,FS_BOLD);
		//Crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= "../../tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}
	
	//Reporte de Ventas realizadas
	function reporteCajaChica($fechaI,$fechaF,$orden,$titulo){
		//Conexion con la BD de Compras
		$conn=conecta("bd_compras");
		//Control de la etiqueta de los valores del eje X
		$ejeX=array();
		//Arreglo con la suma de las salidas de caja chica por el concepto de $orden
		$total=array();
		$sql_stm="SELECT $orden,SUM(total_gastos) AS total,COUNT(total_gastos) AS salidasCaja FROM detalle_caja_chica WHERE estado=1 AND fecha BETWEEN '$fechaI' AND '$fechaF' GROUP BY $orden ORDER BY $orden";
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			do{
				$total[]=$datos["total"];
				$salida="RETIRO";
				if($datos["salidasCaja"]>1)
					$salida.="S";
				if ($orden=="fecha")
					$ejeX[]=modFecha($datos["$orden"],1)."\n$datos[salidasCaja] $salida";
				else
					$ejeX[]=$datos["$orden"]."\n$datos[salidasCaja] $salida";
			}while($datos=mysql_fetch_array($rs));
			if ($orden=="fecha")
				$etiquetaX="Fecha";
			else
				$etiquetaX="Responsable";
			$grafica=graficaComprasCajaChica($ejeX,$total,$titulo,$etiquetaX);
			mysql_close($conn);
			return $grafica;
		}
	}//function reporteVentas($fechaI,$fechaF,$orden,$titulo)
	
	function graficaComprasCajaChica($ejeX,$total,$titulo,$etiquetaX){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
		//Obtener el Total de la Venta
		$totalCajaChica=0;
		//Obtener el total de los costos de Entrada
		$totalCajaChica=number_format(array_sum($total),2,".",",");
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
			//Declarar el arreglo de costos por cada grafica
			$costoPorGrafica=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener la longitud del ejeX mas grande
			$long=0;
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$costoPorGrafica[]=$total[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$ejeX[$contPorGrafica];
				//Reasignar la longitud del EjeX si la longitud actual es mayor a la anterior
				if ($long<strlen($ejeX[$contPorGrafica]))
					$long=strlen($ejeX[$contPorGrafica]);
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			$data1y=$costoPorGrafica;
			// Create the graph. These two calls are always required
			$graph = new Graph(800,600);    
			$graph->SetScale("textlin");
			if($long<=15)
				$graph->img->SetMargin(100,80,100,100);
			if($long>15 && $long<=30)
				$graph->img->SetMargin(150,80,100,200);
			if($long>30 && $long<60)
				$graph->img->SetMargin(250,80,100,350);
			if($long>60)
				$graph->img->SetMargin(350,80,100,450);
			// Create the bar plots
			$b1plot = new BarPlot($data1y);
			$b1plot->SetWidth(9);
			$b1plot->SetFillColor("red@0.2");
			$b1plot->SetLegend('TOTAL $'.$totalCajaChica);
			$b1plot->SetCenter();
			//Mostrar los valores de las Entradas
			$b1plot->value->Show();
			$b1plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
			$b1plot->value->SetColor('navy');
			$b1plot->value->SetFormat('$%.2f');
			$b1plot->value->SetAngle(90);
			// ...and add it to the graPH
			$graph->Add($b1plot);
			$graph->title->Set($titulo);
			// Eje X
			$graph->xgrid->Show();
			$graph->yaxis->title->Set('Costos');
			$graph->yaxis->title->SetColor('darkred');
			$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->yaxis->SetLabelFormat('$%.2f');
			$graph->yaxis->SetTitleMargin(80);
			$graph->yaxis->scale->SetGrace(30);
			// Eje Y
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->xaxis->SetLabelAngle(45);
			$graph->footer->center->Set($etiquetaX);
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
			/********/
			$cont++;
		}while($cont<$ciclos);
		return $graficas;
	}
?>
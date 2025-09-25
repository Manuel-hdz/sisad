<?php
	/**
	  * Nombre del Módulo: Direccion General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 07/Septiembre/2012                                      			
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
				$fechaI=modFecha($_GET["fechaI"],3);
				$fechaF=modFecha($_GET["fechaF"],3);
				$conn=conecta("bd_seguridad");
				$sql="SELECT id_recorrido FROM recorridos_seguridad WHERE fecha BETWEEN '$fechaI' AND '$fechaF' ORDER BY id_recorrido";
				$rs=mysql_query($sql);
				header("Content-type: text/xml");	
				if ($datos=mysql_fetch_array($rs)){
					$tam=mysql_num_rows($rs);
					echo utf8_encode("<existe>
										<valor>true</valor>
										<tam>$tam</tam>");
					$cont=1;
					do{
						$idRecorrido=$datos["id_recorrido"];
						//Crear XML de la clave Generada
						echo utf8_encode("<id$cont>$idRecorrido</id$cont>");
						$cont++;
					}while($datos=mysql_fetch_array($rs));
					echo utf8_encode("</existe>");
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
				$fechaI=modFecha($_GET["fechaI"],3);
				$fechaF=modFecha($_GET["fechaF"],3);
				$conn=conecta("bd_seguridad");
				$sql="SELECT id_acta_comision FROM acta_comision WHERE fecha_registro BETWEEN '$fechaI' AND '$fechaF' ORDER BY id_acta_comision";
				$rs=mysql_query($sql);
				header("Content-type: text/xml");	
				if ($datos=mysql_fetch_array($rs)){
					$tam=mysql_num_rows($rs);
					echo utf8_encode("<existe>
										<valor>true</valor>
										<tam>$tam</tam>");
					$cont=1;
					do{
						$id=$datos["id_acta_comision"];
						//Crear XML de la clave Generada
						echo utf8_encode("<id$cont>$id</id$cont>");
						$cont++;
					}while($datos=mysql_fetch_array($rs));
					echo utf8_encode("</existe>");
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
				//Crear el titulo
				$titulo="REPORTE DE ACCIDENTES E INCIDENTES DEL $fechaI AL $fechaF";
				//Modificar las Fechara en formato legible por MySQL
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Ejecutar la funcion que genera el grafico
				$grafica=reporteAccInc($fechaI,$fechaF,$titulo);
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
	
	function reporteAccInc($fechaI,$fechaF,$titulo){
		//Variables para manejo de informacion de los resultados
		$totalAccidentes=0;
		$totalIncidentes=0;
		//Conectar a la BD de Seguridad
		$conn=conecta("bd_seguridad");
		//Sentencia SQL para extraer el total de eventos por cada tipo de informe
		$sql_stm="SELECT COUNT(tipo_informe) AS total,tipo_informe FROM accidentes_incidentes WHERE fecha_accidente BETWEEN '$fechaI' AND '$fechaF' GROUP BY tipo_informe";
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		//Verificar que la sentencia regrese resultados
		if($datos=mysql_fetch_array($rs)){
			//Recorrer los registros obtenidos de Compras
			do{
				if($datos["tipo_informe"]=="ACCIDENTE")
					$totalAccidentes=$datos["total"];
				else
					$totalIncidentes=$datos["total"];
			}while($datos=mysql_fetch_array($rs));
		}
		$ejeX=array("$totalAccidentes ACCIDENTES","$totalIncidentes INCIDENTES");
		$datos=array($totalAccidentes,$totalIncidentes);
		//Obtener el Grafico
		$grafica=graficaAccInc($ejeX,$datos,$titulo);
		//Cerrar la conexion
		mysql_close($conn);
		//Retornar la Grafica obtenida
		return $grafica;
	}
	
	//Grafica que es incluida en el reporte Historico de Acciones
	function graficaAccInc($ejeX,$datos,$titulo){
		//Obtener el numero total de incidencias de Kardex
		$total=array_sum($datos);
		//Recorrer el arreglo para obtener los datos en forma de porcentaje
		foreach($datos as $ind => $value){
			if($datos[$ind]!=0)
				$datos[$ind]=round(($datos[$ind]*100)/$total,2);
		}
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ("../../../../includes/graficas/jpgraph/jpgraph_bar.php");
		// Create the graph. These two calls are always required
		$graph = new Graph(800,600);    
		$graph->SetScale("textlin");
		$graph->SetShadow();
		$graph->img->SetMargin(100,60,60,100);
		//Calcular el valor de Gracia
		if(max($datos)!=0){
			$resto=(100-max($datos));
			$grace=($resto*100)/max($datos);
		}
		else
			$grace=9000;
		$datay=$datos;
		$graph->yaxis->scale->SetGrace($grace);
		//Eje X
		$graph->xaxis->SetTickLabels($ejeX);
		$graph->xaxis->SetLabelAngle(20);
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL);
		//Titulo
		$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->title->Set($titulo);
		$graph->title->SetColor('darkred');
		// Crear las barras
		$bplot = new BarPlot($datay);
		$bplot->SetFillColor('darkgreen@0.4');
		$bplot->SetWidth(30);
		$bplot->SetShadow();
		$bplot->SetCenter();
		$bplot->value->Show();
		$bplot->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$bplot->value->SetColor('navy');
		$bplot->value->SetFormat('%.2f %%');
		// Eje Y
		$graph->xgrid->Show();
		$graph->yaxis->title->Set('Cantidad');
		$graph->yaxis->title->SetColor('darkred');
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->yaxis->SetLabelFormat('%.2f %%');
		$graph->yaxis->SetTitleMargin(60);
		//Pie de la grafica con el titulo
		$graph->footer->center->Set("Tipo de Informes");
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		// ...y agregarlo a la grafica
		$graph->Add($bplot);
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= "../../tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}//Cierre graficaHistorico($ejeX,$datos,$titulo)
?>
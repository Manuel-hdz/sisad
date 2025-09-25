<?php
	/**
	  * Nombre del Módulo: Direccion General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 23/Febrero/2012                                      			
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
				$periodo=$_GET["combo"];
				$ubicacion=$_GET["ubicacion"];
				$mes=substr($periodo,5,3);
				$mes=obtenerNombreCompletoMes($mes);
				$anio=substr($periodo,0,4);
				$nomUbicacion=obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$ubicacion);
				$titulo="REPORTE DE AVANCE VS PRESUPUESTO DE $mes $anio EN $nomUbicacion";
				$grafica=verReporteMensual($periodo,$titulo,$ubicacion);
				
				header("Content-type: text/xml");	
				if ($grafica!=""){
					$grafica=str_replace("../../tmp/","",$grafica);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<grafica>$grafica</grafica>
							<titulo>$titulo</titulo>
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
				$destino=$_GET["ubicacion"];
				$anio=$_GET["anio"];
				$titulo="REPORTE DE AVANCE DE $anio EN $destino";
				$resultado=obtenerTabla($destino,$anio,$titulo);
				$resultado=split("SeparadorDeImagenImpuesto",$resultado);
				$tabla=$resultado[0];
				$grafica=$resultado[1];
				
				header("Content-type: text/xml");	
				if ($tabla!=""){
					$tabla=str_replace("<","¬",$tabla);
					$grafica=str_replace("../../tmp/","",$grafica);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<tabla>$tabla</tabla>
							<grafica>$grafica</grafica>
							<titulo>$titulo</titulo>
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
				$anio=$_GET["anio"];
				$titulo="REPORTE ANUAL $anio";
				$graficas=mostrarReporteAnual($anio,$titulo);
				header("Content-type: text/xml");	
				if ($graficas!=""){
					$grafica=split("X",$graficas);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>");
					$ctrl=0;
					do{
						$imagen=str_replace("../../tmp/","",$grafica[$ctrl]);
						$pos=intval(substr($imagen,7,2));
						echo utf8_encode("<grafica$pos>$imagen</grafica$pos>");
						$ctrl++;
					}while($ctrl<count($grafica));
					echo ("</existe>");
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
				$periodo=$_GET["periodo"];
				$titulo="REPORTE MENSUAL DE $periodo";
				$resultado=mostrarReporteMensual($periodo,$titulo);
				header("Content-type: text/xml");	
				if ($resultado!=""){
					$seccion=split("¬X¬",$resultado);
					$grafica=$seccion[0];
					$tabla=$seccion[1];
					//Remplazar el tag de apertura "menor que" por un simbolo menos usado, en este caso "¬"
					$tabla=str_replace("<","¬",$tabla);
					$grafica=str_replace("../../tmp/","",$grafica);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<tabla>$tabla</tabla>
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
	
	/*Esta funcion genera el reporte mensual y regresa el periodo para indicar que los datos mostrados pueden ser exportados*/
	function verReporteMensual($periodo,$titulo,$ubicacion){
		//Obtener el Nombre de la Ubicacion
		$nomUbicacion=obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$ubicacion);
		//Conectarse a la BD de Gerencia Técnica
		$conn = conecta("bd_gerencia");
		$sql_stm="SELECT fecha_inicio,fecha_fin,vol_ppto_mes,vol_ppto_dia FROM presupuesto WHERE periodo = '$periodo' AND catalogo_ubicaciones_id_ubicacion='$ubicacion'";
		$rs=mysql_query($sql_stm);
		if($datosPeriodo=mysql_fetch_array($rs)){
			//Arreglos para almacenar los totales que se muestran al final de cada Ubicación que es Mostrada
			$sumPorDia = array();//Arreglo que contendra la suma de los lanzamientos hechos por día
			$prodRealPorDia = array();//Arreglo que contendra el volumen real de los lanzamientos por dia acumulados
			$prodPresPorDia = array();//Arreglo que contendra el volumen presupuestado de los lanzamientos por dia acumulados
			$difPorDia = array();//Arrelo que contendrá la diferencia respecto
			
			$fechaIni = $datosPeriodo['fecha_inicio'];
			$fechaFin = $datosPeriodo['fecha_fin'];	
			
			//Obtener el Presupuesto Total y el Diario por Cada Ubicacion mostrada
			$presMensual = $datosPeriodo['vol_ppto_mes'];
			$presDiario = $datosPeriodo['vol_ppto_dia'];					
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
			
			//Obtener el dia, mes y año de inicio como actuales
			$diaActual = intval(substr($fechaIni,-2));
			$mesActual = intval(substr($fechaIni,5,2));
			$anioActual = $anioInicio;
			
			$ctrlInicializacion = 0;
			//Ciclo para recorrer la totalidad de dias del periodo seleccionado												
			for($i=0;$i<$totalDias;$i++){
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
				//Ejecutar la Sentencia para obtener los datos del Lanzamiento en la Fecha, y Ubicación indicados
				$datosLanzamiento = mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS cantidad FROM bitacora_zarpeo WHERE destino = '$nomUbicacion' AND fecha = '$fechaActual'"));
				//Inicializar cantidad con valor de 0 por default
				$cantidad=0;
				//Si el valor de retorno es diferente de NULL, extraerlo
				if ($datosLanzamiento!=NULL)
					$cantidad = $datosLanzamiento['cantidad'];
				//Sumar los volumenes encontrados por dia, tanto para Lanzador como para el Ayudante para el total por Dia
				$sumPorDia[$fechaActual] += $cantidad;
				//Acumular el total por dia por todas la ubicaciones dentro del periodo selecionado
				$sumTotalPorDia[$fechaActual] += $cantidad;		
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
				//Incrementar el dia
				$diaActual++;
			}//Cierre for($i=0;$i<$totalDias;$i++)
			//Cerrar la Conexion con la BD
			mysql_close($conn);
			//Dibujar Grafica
			$grafica=dibujarGrafica1($prodPresPorDia,$prodRealPorDia,$titulo);
			return $grafica;
		}
		else
			return "";						
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
	
	function obtenerTabla($destino,$anio,$titulo){
		//Obtener el ID de la ubicacion
		$idUbicacion=obtenerDato("bd_gerencia","catalogo_ubicaciones","id_ubicacion","ubicacion",$destino);
		//Conectarse a la BD de Gerencia Técnica
		$conn = conecta("bd_gerencia");	
		//contador que nos permite controlar el ciclo de los meses
		$cont = 0;
		//arreglos en el cual se almacenaran los tatales de zarpeos como los de pisos
		$resZarpeo = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0);
		$resPisos = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0);
		$meses = array(0=>"ENERO",1=>"FEBRERO",2=>"MARZO",3=>"ABRIL",4=>"MAYO",5=>"JUNIO",6=>"JULIO",7=>"AGOSTO",8=>"SEPTIEMBRE",9=>"OCTUBRE",10=>"NOVIEMBRE",
		11=>"DICIEMBRE");
		$rsMeses=mysql_query("SELECT SUBSTRING(periodo,-3) AS mes,fecha_inicio,fecha_fin FROM presupuesto WHERE catalogo_ubicaciones_id_ubicacion='$idUbicacion' AND SUBSTRING(periodo,1,4)='$anio' ORDER BY fecha_inicio");
		if($mes=mysql_fetch_array($rsMeses)){
			do{
				//Obtener las fechas de Inicio y de Fin
				$fechaIni=$mes["fecha_inicio"];
				$fechaFin=$mes["fecha_fin"];
				//Obtener el nombre del MES a buscar en la bitacora
				$mesBitacora=$mes["mes"];
				//Obtener la posicion del arreglo de meses que corresponde al Mes encontrado en la consulta
				foreach($meses as $ind =>$value){
					if(substr($value,0,3)==$mesBitacora){
						//Crear la sentencia SQL para obtener el registro del mes correspondiente de zarpeos
						$sql_stmZarp = "SELECT sum(cantidad) AS zarpeoTotal FROM bitacora_zarpeo WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' AND destino='$destino' AND aplicacion='ZARPEO VIA HUMEDA'";
						//Crear y ejecutar la sentencia SQL para obtener el registro del mes correspondiente de pisos
						$sql_stmPisos = "SELECT sum(cantidad) AS volTotal FROM bitacora_transporte WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' AND destino = '$destino'";
						//Ejecutar las sentencia de zarpeos
						$rsZarpeo = mysql_query($sql_stmZarp);
						$rsPisos = mysql_query($sql_stmPisos);
						
						//Comprobar si existen datos de Zarpeo
						if($datosZarp=mysql_fetch_array($rsZarpeo)){										
							//verificar si $datos['aplicacion'] esta vacia asignale valor 0
							if ($datosZarp['zarpeoTotal']!=0)
								$resZarpeo[$ind] = $datosZarp['zarpeoTotal'];										
						}//FIN if($datos=mysql_fetch_array($rsZarp))
						
						//comprobar si existen datos de Pisos
						if($datosPisos = mysql_fetch_array($rsPisos)){											
							//verificar si $datos['aplicacion'] esta vacia asignale valor 0
							if ($datosPisos['volTotal']>$resZarpeo[$ind])
								$resPisos[$ind] = $datosPisos['volTotal'] - $resZarpeo[$ind];
						}//FIN if($cantTrasporte = mysql_fetch_array($rs_transporte))
					}
				}
			}while($mes=mysql_fetch_array($rsMeses));
		}
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg="Comparativo Mensual de Producción en <em><u>$destino</u></em> en el Año <em><u> $anio</em></u>";			
		
		//Desplegar los resultados de la consulta en una tabla
		$tabla="<table cellpadding='5' width='100%'><tr><td colspan='4' align='center' class='titulo_etiqueta' style='color:#FFF'>$msg</td></tr><tr><td class='nombres_columnas' align='center'>MES</td><td class='nombres_columnas' align='center'>ZARPEO</td><td class='nombres_columnas' align='center'>PISOS</td><td class='nombres_columnas' align='center'>TOTAL</td></tr>";

		$nom_clase = "renglon_gris";
		$cont = 1;
		//contador que nos permite controlar el ciclo de los meses
		$contMes = 0;
		//Variables que permitiran sumar el total de cada arreglo para poder obtener su promedio
		$sumaZarpeo=0;
		$sumaPisos=0;
		$totalMes=0; 
		do{	
			//Realizar la suma de zarpeo y de pisos en un mes
			$totalMes=($resZarpeo[$contMes]+$resPisos[$contMes]);
			//Mostrar todos los registros que han sido completados
			$tabla.="<tr><td class='nombres_filas' width='15%'>$meses[$contMes]</td><td class='$nom_clase' width='15%' align='right'>".number_format($resZarpeo[$contMes],2,".",",")."</td><td class='$nom_clase' width='15%' align='right'>".number_format($resPisos[$contMes],2,".",",")."</td><td class='$nom_clase' width='15%' align='right'>".number_format($totalMes,2,".",",")."</td></tr>";
			//Realizar las sumas de los valores que contiene cada arreglo 
			$sumaZarpeo= ($sumaZarpeo+$resZarpeo[$contMes]);
			$sumaPisos= ($sumaPisos+$resPisos[$contMes]);
				
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
				$contMes++;
		}while($contMes<12);
		
		//declarar en 0 el promedio de ambas sumas
		$promSumas=0;
		//Realizar el promedio de cada obra y el total
		$sumaZarpeo= $sumaZarpeo/12;
		$sumaPisos= $sumaPisos/12;
		$promSumas= ($sumaZarpeo+$sumaPisos);
		
		$tabla.="<tr><td class='$nom_clase' width='15%' align='right'>PROMEDIO</td><td class='$nom_clase' width='15%' align='right'>".number_format(($sumaZarpeo),2,".",",")."</td><td class='$nom_clase' width='15%' align='right'>".number_format(($sumaPisos),2,".",",")."</td><td class='$nom_clase' width='15%' align='right'>".number_format(($promSumas),2,".",",")."</td></tr></table>";
		
		//Llamar la funcion que genera la gráfica    
		$grafica=dibujarGrafica2($resZarpeo,$resPisos,$sumaZarpeo,$sumaPisos,$destino,$anio,$titulo);
		return $tabla."SeparadorDeImagenImpuesto".$grafica;
	}
	
	function obtenerMes($cont){
		if($cont<=9)
			return '0'.$cont;
		if($cont>=10)
			return $cont;
	}
	
	//Funcion que dibuja la gráfica 
	function dibujarGrafica2($zarpeo,$pisos,$promZarp,$promPiso,$destino,$anio,$titulo){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
		
		//Coleccion de datos a graficar en cada barra
		//parte superior de la barra
		$datZarpeo=$zarpeo;
		$datZarpeo[]=$promZarp;
		//parte inferior de la barra
		$datPisos=$pisos;
		$datPisos[]=$promPiso;
		//arreglo contenedor de los meses del años
		$arrMeses=array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE","PROMEDIO");
		
		// Tamaño para el área de la gráfica ancho,alto
		$graph = new Graph(900,500);
		//Angulo inclinacion de las barras en la gráfica
		$graph->SetAngle(0);
		$graph->SetScale("textlin");
		$graph->img->SetMargin(15,60,100,100);
		
		//Color del fondo del grafico
		$graph->SetMarginColor('#EAEAEA');
		
		// Titulo del grafico
		$graph->title->Set($titulo);
		$graph->title->SetFont(FF_FONT2,FS_BOLD);
		
		// Ubicaciones,tipo de letra, del pie del grafico
		//tipo de letra de la etiqueta
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
		//Angulo de inclinación de las etiquetas
		$graph->xaxis->title->SetAngle(90);
		//Separación y/o margenes de las etiquetas 
		$graph->xaxis->SetTitleMargin(10);
		$graph->xaxis->SetLabelMargin(45);
		//Alineación de las etiquetas
		$graph->xaxis->SetLabelAlign('center','center');
		//Colocar el arreglo que contiene los meses, para cada barra
		$graph->xaxis->SetTickLabels($arrMeses);
		$graph->xaxis->SetLabelAngle(50);
		
		// Arrange the labels
		$graph->yaxis->SetLabelSide(SIDE_RIGHT);
		$graph->yaxis->SetLabelAlign('center','top');
		
		// Create the bar plots with image maps
		$barraZarpeo = new BarPlot($datZarpeo);
		//Color de relleno para la barra en la parte inferior correspondiente a zarpeo de color azul
		$barraZarpeo->SetFillColor("#0000CC");
		
		$barraPisos = new BarPlot($datPisos);
		//Color de relleno para la barra en la parte superior correspondiente a pisos de color rojo
		$barraPisos->SetFillColor("#FF0000");
		
		// Colocar en cada barra el acumulado tanto de zarpeo como de pisos
		$abplot = new AccBarPlot(array($barraZarpeo,$barraPisos));
		
		// We want to display the value of each bar at the top
		$abplot->value->Show();
		$abplot->value->SetFont(FF_FONT1,FS_NORMAL);
		$abplot->value->SetAlign('center','center');
		$abplot->value->SetColor("black","darkred");
		
		//Colocar las etiquetas para la iconografia colores... azul=> Zarpeo, rojo=>pisos
		$barraZarpeo->SetLegend("Zarpeo"); //Etiqueta para la iconografía, tomara el color por default que le corresponde
		$barraPisos->SetLegend("Pisos"); //Etiqueta para la iconografía, tomara el color por default que le corresponde
		
		//Agregar los datos a la gráfica
		$graph->Add($abplot);
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= "../../tmp/grafica".$rnd.".png";
		
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Devolver el nombre de la grafica para poder identificarla y colocarla en el reporte que se exporta y/o en el div donde se muestra
		return $grafica;
	}//FIN function generarGrafica()
	
	/******************/
	//funcion que se encarga de mostrar el reporte generado anualmente
	function mostrarReporteAnual($anio,$titulo){
		//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");		
		//contador que nos permite controlar el ciclo de los meses
		$cont = 0;
		//Variables que nos permiten sumar el total mensual y el total de los totales
		$totalMensual=0;
		$totalTotales=0;
		//arreglo que contiene el nombre de los meses del año
		$meses = array(0=>"ENERO",1=>"FEBRERO",2=>"MARZO",3=>"ABRIL",4=>"MAYO",5=>"JUNIO",6=>"JULIO",7=>"AGOSTO",8=>"SEPTIEMBRE",9=>"OCTUBRE",10=>"NOVIEMBRE",
		11=>"DICIEMBRE");
		//ayudara a obtener el total por concepto y posteriromente el promendio
		$sumaConceptos = array();
		//Este arreglo contendra el valor de cada concepto en cada mes del año seleccionado
		$conceptos = array(); 
		//Declarar el arreglo para almacenar los destinos
		$destinos = array();
		//Ejecutar la sentencia que permite obtener los destinos registrados en la Bitacora de Zarpeo
		$rsDestino = mysql_query("SELECT DISTINCT destino FROM bitacora_zarpeo");
		//Guardar las UBicaciones encontradas en el arreglo de destinos
		while($datos=mysql_fetch_array($rsDestino))
			$destinos[] = $datos['destino'];
		//Desplegar los resultados de la consulta en una tabla
		$tabla="<table cellpadding='5' width='130%'><tr><td colspan='10' align='center' class='titulo_etiqueta'>$titulo</td></tr><tr><td rowspan='2' class='nombres_columnas' align='center'>MES</td>";
		foreach($destinos as $ind => $destino){
			$sumaConceptos[$destino] = array("ZARPEO"=>0,"PISOS"=>0);
			//Inicializar cada concepto como un arreglo para almacenar los valores de cada uno de los meses
			$conceptos[$destino." ZARPEO"] = array();
			$conceptos[$destino." PISOS"] = array();			
			$tabla.="<td colspan='2' class='nombres_columnas' align='center'>$destino</td>";
		}
		//Agregar un indice para acumular los Colados y otro para el Zarpeo Via Seca
		$sumaConceptos['COLADOS'] = array("BOMBEO"=>0,"TD"=>0);
		$sumaConceptos['VIASECA'] = 0;
		//Inicializar cada concepto como un arreglo para almacenar los valores de cada uno de los meses
		$conceptos["COLADOS BOMBEO"] = array();
		$conceptos["COLADOS TD"] = array();
		$conceptos["VIASECA"] = array();
		$tabla.="<td colspan='2' class='nombres_columnas' align='center'>COLADOS</td><td rowspan='2' class='nombres_columnas' align='center'>VIA SECA</td><td rowspan='2' class='nombres_columnas' align='center'>TOTAL</td></tr>";
		foreach($destinos as $ind => $destino)
			$tabla.="<td class='nombres_columnas' align='center'>ZARPEO</td><td class='nombres_columnas' align='center'>PISOS</td>";
		
		$tabla.="<td class='nombres_columnas' align='center'>BOMBEO</td><td class='nombres_columnas' align='center'>TIRO DIRECTO</td></tr>";
		
		//Este ciclo ayudara a obtener los datos por cada mes
		$nom_clase = "renglon_gris";
		$contRenglon= 1;
		
		$contMes = 0;
		do{
			//Variable para acumular el total de los conceptos encotrados por cada mes
			$totalMes = 0;	
			//Conectar a la BD de gerencia
			$conn = conecta("bd_gerencia");											
			$tabla.="<tr><td class='nombres_filas' width='15%'>$meses[$contMes]</td>";
			//Este ciclo nos ayuda a obtener los conceptos de cada ubicacion encontrada
			foreach($destinos as $ind => $destino){
				//Obtener el numero del mes en dos digitos
				$mes = obtenerMes($contMes+1);
				//Variables de Zarpeo, Transporte y Pisos
				$cantZarpeoTotal = 0;
				$cantTrasporte = 0;
				$pisos = 0;
				//Extraer el Id del Destino
				$idUbicacion=mysql_fetch_array(mysql_query("SELECT id_ubicacion FROM catalogo_ubicaciones WHERE ubicacion='$destino'"));
				//Extraer los meses dados de alta en los periodos
				$mesActual=substr($meses[$contMes],0,3);
				$rsMeses=mysql_query("SELECT fecha_inicio,fecha_fin FROM presupuesto WHERE catalogo_ubicaciones_id_ubicacion='$idUbicacion[0]' AND SUBSTRING(periodo,-3)='$mesActual' AND SUBSTRING(periodo,1,4)='$anio'");
				if($datosMeses=mysql_fetch_array($rsMeses)){
					$fechaIni=$datosMeses["fecha_inicio"];
					$fechaFin=$datosMeses["fecha_fin"];
					//Crear la sentencia SQL para obtener el registro del mes correspondiente de zarpeos
					$datosZarpeoTotal = mysql_fetch_array(mysql_query("SELECT sum(cantidad) AS volTotal FROM bitacora_zarpeo WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' 
					AND destino='$destino'"));
					if($datosZarpeoTotal['volTotal']!="")
						$cantZarpeoTotal = $datosZarpeoTotal['volTotal'];
					//Crear la sentencia SQL para obtener el registro del mes correspondiente de pisos
					$datosTrasporte = mysql_fetch_array(mysql_query("SELECT sum(cantidad) AS volTotal FROM bitacora_transporte WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' 
					AND destino = '$destino'"));
					if($datosTrasporte['volTotal']!="")
						$cantTrasporte = $datosTrasporte['volTotal'];	
					//Obtener la Diferencia entre el volumen de Zarpeo y el volumen transportado, la diferencia se cosidera como pisos
					//La cantidad de zarpeo no puede ser negativa; la siguiente comparación verificara resultado positivo para obtener los pisos
					if($cantTrasporte>$cantZarpeoTotal)
						$pisos = $cantTrasporte - $cantZarpeoTotal;				
				}
				else{
					$fechaIni="";
					$fechaFin="";
				}
				$tabla.="					
					<td class='$nom_clase' width='15%' align='right'>".number_format($cantZarpeoTotal,2,".",",")."</td>
					<td class='$nom_clase' width='15%' align='right'>".number_format($pisos,2,".",",")."</td>";	
				//Acumular el Zarpeo Via Humeda y los Pisos por cada Ubicación en cada Mes registrado para sacar el Promedio
				$sumaConceptos[$destino]['ZARPEO'] += $cantZarpeoTotal;
				$sumaConceptos[$destino]['PISOS'] += $pisos;
				//Acumular el total de cada concepto por ubicacion para obtener el total del MES
				$totalMes += ($cantZarpeoTotal + $pisos);
				$totalTotales	+= ($cantZarpeoTotal + $pisos);
				//Guardar los datos necesario para la Grafica por cada Ubicacion encontrada por Mes
				$conceptos[$destino." ZARPEO"][] = $cantZarpeoTotal;
				$conceptos[$destino." PISOS"][] = $pisos;
			}//Fin de foreach($destinos as $ind => $destino)
			//************************
			//****** Para obtener los colados (estos provienen de la base de datos de producción en la tabla de detalle_colados)
			//************************
			//Cerrar el ultimo enlace de Conexion a la BD
			mysql_close($conn);
			//Reconectar a la BD de Produccion
			$conn = conecta("bd_produccion");
			//Obtener el numero del mes en dos digitos
			$mes = obtenerMes($contMes+1);
			//Crear la sentencia SQL para obtener el registro del mes correspondiente de bombeo
			$cantBombeo = 0;
			//Obtener las fechas de los presupuestos de Produccion
			$rsMesesProd=mysql_query("SELECT fecha_inicio,fecha_fin FROM presupuesto WHERE SUBSTRING(periodo,-3)='$mesActual' AND SUBSTRING(periodo,1,4)='$anio'");
			if($datosProd=mysql_fetch_array($rsMesesProd)){
				$fechaIniProd=$datosProd["fecha_inicio"];
				$fechaFinProd=$datosProd["fecha_fin"];
			}
			else{
				$fechaIniProd="";
				$fechaFinProd="";
			}
			if($fechaIniProd!="" && $fechaFinProd!=""){
				//Crear la sentencia SQL para obtener el registro del mes correspondiente de BOMBEO
				$datosBombeo = mysql_fetch_array(mysql_query("SELECT sum(volumen) AS volTotal FROM detalle_colados WHERE bitacora_produccion_fecha BETWEEN '$fechaIniProd' AND '$fechaFinProd' 
				AND tipo_colado='BOMBEO'"));
				if($datosBombeo['volTotal']!="")
					$cantBombeo = $datosBombeo['volTotal'];
									
				//Crear la sentencia SQL para obtener el registro del mes correspondiente de TIRO DIRECTO
				$cantTiroD = 0;
				$datosTiroD = mysql_fetch_array(mysql_query("SELECT sum(volumen) AS volTotal FROM detalle_colados WHERE bitacora_produccion_fecha BETWEEN '$fechaIniProd' AND '$fechaFinProd' 
				AND tipo_colado='TIRO DIRECTO'"));
				if($datosTiroD['volTotal']!="")
					$cantTiroD = $datosTiroD['volTotal'];
			}
			else{
				$cantBombeo = 0;
				$cantTiroD = 0;
			}
			$tabla.="<td class='$nom_clase' align='right'>".number_format($cantBombeo,2,".",",")."</td><td class='$nom_clase' align='right'>".number_format($cantTiroD,2,".",",")."</td>";
			//Acumular los Colados de cada mes para obtener el promedio
			$sumaConceptos['COLADOS']['BOMBEO'] += $cantBombeo;
			$sumaConceptos['COLADOS']['TD'] += $cantTiroD;
			//Acumular el total de los colados para obtener el total del mes
			 $totalMes += ($cantBombeo + $cantTiroD);
			//Total de todo el año
			$totalTotales += ($cantBombeo + $cantTiroD);
			//Guardar los datos necesario para la Grafica con los datos de Colados
			$conceptos["COLADOS BOMBEO"][] = $cantBombeo;
			$conceptos["COLADOS TD"][] = $cantTiroD;
			//************************
			//****** Para obtener la via seca (estos provienen de la base de datos de gerencia en la tabla de bitacora_zarpeo)
			//************************
			//Cerrar el ultimo enlace de Conexion a la BD
			mysql_close($conn);
			//Reconectar a la BD de Gerencia
			$conn = conecta("bd_gerencia");
			//Obtener el numero del mes en dos digitos
			$mes = obtenerMes($contMes+1);
			$cantViaSeca = 0;
			$datosViaSeca=mysql_fetch_array(mysql_query("SELECT sum(cantidad) AS volTotal FROM bitacora_zarpeo WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin' 
			AND aplicacion='ZARPEO VIA SECA'"));
			if($datosViaSeca['volTotal']!="")
				$cantViaSeca = $datosViaSeca['volTotal'];
			$tabla.="<td class='$nom_clase' align='right'>".number_format($cantViaSeca,2,".",",")."</td>";
			//Realizar la suma de cada mes para obtener el total del Zarpeo Via Seca
			$cantViaSeca= $sumaConceptos['VIASECA'] + $cantViaSeca;
			//Acumular el total del Zarpeo de la Via Seca para obtener el total del mes
			$totalMes += $cantViaSeca;	
			$totalTotales	+=  $cantViaSeca;
			//Guardar los datos necesario para la Grafica con los datos de los Volumenes de Via Seca
			$conceptos["VIASECA"][] = $cantViaSeca;
			$tabla.="<td class='$nom_clase' align='right'>".number_format($totalMes,2,".",",")."</td></tr>";
			//Determinar el color del siguiente renglon a dibujar
			$contMes++;
			$contRenglon++;
			if($contRenglon%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";			
			//Cerrar el ultimo enlace de Conexion a la BD
			mysql_close($conn);
		}while($contMes<12);					
		//Colocar el Promedio por mes de cada concepto
		$tabla.="<tr><td class='nombres_filas' width='15%' align='right'>PROMEDIO</td>";
		//Colocar el promedio por cada Concepto de Cada Ubicacion Encontrada
		foreach($destinos as $ind => $destino){
			//Obtener promedio de Zarpeo y de Pisos
			$promZarpeo = $sumaConceptos[$destino]['ZARPEO']/12;
			$promPisos = $sumaConceptos[$destino]['PISOS']/12;
			$tabla.="<td class='$nom_clase' width='15%' align='right'>".number_format($promZarpeo,2,".",",")."</td><td class='$nom_clase' width='15%' align='right'>".number_format($promPisos,2,".",",")."</td>";
		}
		//Colocar el Promedio de los Colados
		$promBombeo = $sumaConceptos["COLADOS"]['BOMBEO']/12;
		$promTD = $sumaConceptos["COLADOS"]['TD']/12;
		$tabla.="<td class='$nom_clase' width='15%' align='right'>".number_format($promBombeo,2,".",",")."</td><td class='$nom_clase' width='15%' align='right'>".number_format($promTD,2,".",",")."</td>";
		//Colocar el promedio del Zarpeo de Via Seca
		$promViaSeca = $sumaConceptos["VIASECA"]/12;
		$tabla.="<td class='$nom_clase' width='15%' align='right'>".number_format($promViaSeca,2,".",",")."</td><td class='$nom_clase' width='15%' align='right'>".number_format($totalTotales,2,".",",")."</td></tr></table>";		
		//Llamar la funcion que genera la gráfica    
		$grafica=dibujarGrafica3($anio,$conceptos,$titulo);
		return $grafica;
	}//FIN  function mostrarReporteAnual()

	//Funcion que se encarga de mostrar la gráfica generada
	function dibujarGrafica3($anio,$conceptos,$titulo){	
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_bar.php');
		//arreglo contenedor de los meses del años
		$arrMeses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
		$ctrl=0;
		//arreglco con los posibles colores para cada una de las barras a graficar
		$colores = array("blue@0.4","darkred@0.4","green@0.4","purple@0.4","lightblue@0.4","orange@0.4","brown@0.4","darkgreen@0.4","black@0.4","red@0.4",
		"yellow@0.4","gray@0.4","pink@0.4");
		$graficas="";
		do{
			$volumen=0;
			// Create the basic graph
			$graph = new Graph(800,600,'auto');
			$graph->SetScale("textlin");
			// Margenes del gráfico LRTB
			$graph->img->SetMargin(80,80,60,100);
			$etiquetas=array();
			$valor=array();
			foreach($conceptos as $ind => $concepto){
				$etiquetas[]=$ind;
				$volumen=$conceptos[$ind][$ctrl];
				$valor[]=$volumen;
			}
			// Ubicaciones,tipo de letra, del pie del grafico
			//tipo de letra de la etiqueta
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			//Angulo de inclinación de las etiquetas
			$graph->xaxis->title->SetAngle(90);
			//Separación y/o margenes de las etiquetas 
			$graph->xaxis->SetTitleMargin(10);
			$graph->xaxis->SetLabelMargin(10);
			//Alineación de las etiquetas
			$graph->xaxis->SetLabelAlign('center','center');
			//Colocar el arreglo que contiene los meses, para cada barra
			$graph->xaxis->SetTickLabels($etiquetas);
			$graph->xaxis->SetLabelAngle(45);
			$reg[] = new BarPlot($valor);
			$reg[$ctrl]->SetAlign("center");
			$reg[$ctrl]->value->Show();
			$reg[$ctrl]->value->SetFont(FF_ARIAL,FS_BOLD,7);
			// Setup the colors with 40% transparency (alpha channel)
			$reg[$ctrl]->SetFillColor($colores[$ctrl]);
			// Titulo del grafico
			$graph->title->Set($titulo." MES ".$arrMeses[$ctrl]);
			$graph->title->SetFont(FF_FONT2,FS_BOLD);
			//Agregar los datos a la gráfica
			$graph->Add($reg[$ctrl]);
			//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
			$rnd=rand(0,1000);
			$mes="";
			if($ctrl<10)
				$mes="0".$ctrl;
			else
				$mes=$ctrl;
			$grafica= "../../tmp/grafica".$mes.$rnd.".png";
			//Dibujar la grafica y guardarla en un archivo temporal	
			$graph->Stroke($grafica);
			$ctrl++;
			$graficas.=$grafica."X";
		}while($ctrl<=11);
		return $graficas;
	}
	
	function mostrarReporteMensual($periodo,$titulo){
		//Conectar a la BD de recursos para extraer el total de empleados del area de concreto
		$conn = conecta("bd_recursos");
		$cantEmp=mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM empleados WHERE area='CONCRETO'"));
		mysql_close($conn);
		if($cantEmp[0]!=NULL)
			$numEmp=$cantEmp[0];
		else
			$numEmp=0;
		$tabla="";
		$grafica="";
		//Obtenemos la fecha inicial y final de la BD para tomarla como parametro al realizar las operaciones para realizar los calculos
		$fechaIniMesActual=obtenerDato("bd_gerencia", "presupuesto", "fecha_inicio", "periodo", $periodo);
		$fechaFinMesActual=obtenerDato("bd_gerencia", "presupuesto", "fecha_fin", "periodo", $periodo);
		$anio=substr($periodo,0,4);
		$mesIniPer=substr($periodo,5,3);
		$mesFinPer=substr($periodo,-3);
		if($mesFinPer=="ENE")
			$anio-=1;	
		//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");
		//Ejecutar la sentencia que extrae las fechas de inicio y fin del periodo anterior al actual
		$rsPeriodo=mysql_query("SELECT SUBSTRING(periodo,6,3) AS mes,fecha_inicio,fecha_fin,periodo FROM presupuesto WHERE periodo LIKE '$anio%$mesIniPer' ORDER BY fecha_inicio");
		//Extraer los Datos del periodo anterior cuando este existe
		if($datosPeriodo=mysql_fetch_array($rsPeriodo)){
			$fechaIniMesAnterior=$datosPeriodo["fecha_inicio"];
			$fechaFinMesAnterior=$datosPeriodo["fecha_fin"];
			$mesAnterior=$datosPeriodo["mes"];
			$periodo2=$datosPeriodo["periodo"];
		}
		//Si el periodo no existe, obtener el mes que le corresponde y las Fechas quedan como cadenas vacias
		else{
			$fechaIniMesAnterior="";
			$fechaFinMesAnterior="";
			$secCombo=split("-",$periodo);
			$mesAnterior=obtenerNombreCompletoMes($secCombo[1]);
			$mesAnterior=substr(obtenerMesAnterior($mesAnterior),0,3);
			$periodo2=$anio."-".$mesAnterior."-".$mesIniPer;
		}
		//Crear el periodo 3 y obtener las fechas de la BD, si no existe el periodo, no se toman los registros asociados a el
		//Ejecutar la sentencia que extrae las fechas de inicio y fin del periodo anterior de inicio
		$rsPeriodo2=mysql_query("SELECT SUBSTRING(periodo,6,3) AS mes,fecha_inicio,fecha_fin,periodo FROM presupuesto WHERE periodo LIKE '$anio%$mesAnterior' ORDER BY fecha_inicio");
		//Extraer los Datos del periodo anterior cuando este existe
		if($datosPeriodo2=mysql_fetch_array($rsPeriodo2)){
			$fechaIniMesAnterior3=$datosPeriodo2["fecha_inicio"];
			$fechaFinMesAnterior3=$datosPeriodo2["fecha_fin"];
			$mesAnterior3=$datosPeriodo2["mes"];
			$periodo3=$datosPeriodo2["periodo"];
		}
		//Si el periodo no existe, obtener el mes que le corresponde y las Fechas quedan como cadenas vacias
		else{
			$fechaIniMesAnterior3="";
			$fechaFinMesAnterior3="";
			//Obtener el nombre completo del Mes
			$mesAnterior3=obtenerNombreCompletoMes($mesAnterior);
			//Obtener el Mes Anterior
			$mesAnterior3=substr(obtenerMesAnterior($mesAnterior3),0,3);
			//Obtener el Periodo
			$periodo3=$anio."-".$mesAnterior3."-".$mesAnterior;
		}
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg_titulo= "Comparativo Mensual en el Periodo <em><u>$periodo</u></em>";
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontró Ningún Registro en el Presupuesto del Periodo <u><em>$periodo</u></em></label>";
		//Extraer los conceptos de la bitacora que se tengan registrados para cualquiera de los 3 periodos a buscar
		$rsConceptos=mysql_query("SELECT DISTINCT aplicacion FROM bitacora_zarpeo WHERE ((fecha BETWEEN '$fechaIniMesAnterior3' AND '$fechaFinMesAnterior3') OR (fecha BETWEEN '$fechaIniMesAnterior' AND '$fechaFinMesAnterior') OR (fecha BETWEEN '$fechaIniMesActual' AND '$fechaFinMesActual')) ORDER BY fecha");		
		//Verificar la consulta y extraer los datos
		if($conceptos=mysql_fetch_array($rsConceptos)){
			//Desplegar los encabezados de la Tabla
			$tabla="				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='6' align='center' class='titulo_etiqueta' style='color:#FFF'>$msg_titulo</td>
				</tr>
				<tr>
					<td rowspan='2' class='nombres_columnas' align='center'>CONCEPTO</td>
					<td rowspan='2' class='nombres_columnas' align='center'>UNIDAD</td>
					<td colspan='3' class='nombres_columnas' align='center'>MES</td>
					<td rowspan='2' class='nombres_columnas' align='center'>PROMEDIO</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>$mesAnterior</td>
					<td class='nombres_columnas' align='center'>$mesIniPer</td>
					<td class='nombres_columnas' align='center'>$mesFinPer</td>
				</tr>";
			$flag=1;
			//Variables que acumulan la Produccion por Mes NO APLICA PARA INSTALACION DE MALLAS
			$totalMes3=0;//Mes Actual
			$totalMes2=0;//Mes Anterior
			$totalMes1=0;//Mes Primero(2 Meses Anterior al Actual)
			$totalPromedio=0;
			//Nombre de la Clase
			$nom_clase = "renglon_gris";
			//contador
			$cont = 1;
			do{
				//Produccion Mes Actual
				$prodMes3=0;
				//Produccion Mes Anterior
				$prodMes2=0;
				//Produccion del Primer Mes (calculo hecho en base al mes actual y los 2 anteriores)
				$prodMes1=0;
				//Extraer la cantidad de produccion realizada en el concepto indicado en el Primer Mes
				$produccion=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM bitacora_zarpeo WHERE fecha BETWEEN '$fechaIniMesAnterior3' AND '$fechaFinMesAnterior3' AND aplicacion='$conceptos[aplicacion]'"));
				if($produccion["total"]!=NULL)
					$prodMes1=$produccion["total"];
				//Extraer la cantidad de produccion realizada en el concepto indicado en el Segundo Mes
				$produccion=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM bitacora_zarpeo WHERE fecha BETWEEN '$fechaIniMesAnterior' AND '$fechaFinMesAnterior' AND aplicacion='$conceptos[aplicacion]'"));
				if($produccion["total"]!=NULL)
					$prodMes2=$produccion["total"];
				//Extraer la cantidad de produccion realizada en el concepto indicado en el Tercer Mes
				$produccion=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM bitacora_zarpeo WHERE fecha BETWEEN '$fechaIniMesActual' AND '$fechaFinMesActual' AND aplicacion='$conceptos[aplicacion]'"));
				if($produccion["total"]!=NULL)
					$prodMes3=$produccion["total"];
				
				if($conceptos["aplicacion"]=="INSTALACION MALLA")
					$unidadMedida="M²";
				else{
					$unidadMedida="M³";
					$totalMes3+=$prodMes3;
					$totalMes2+=$prodMes2;
					$totalMes1+=$prodMes1;
					$totalPromedio+=(($prodMes1+$prodMes2+$prodMes3)/3);
				}
				$tabla.="<tr>";
					$tabla.="<td class='nombres_columnas' align='center'>$conceptos[aplicacion]</td>";
					$tabla.="<td class='$nom_clase' align='center'>$unidadMedida</td>";
					$tabla.="<td class='$nom_clase' align='center'>".number_format($prodMes1,2,".",",")."</td>";
					$tabla.="<td class='$nom_clase' align='center'>".number_format($prodMes2,2,".",",")."</td>";
					$tabla.="<td class='$nom_clase' align='center'>".number_format($prodMes3,2,".",",")."</td>";
					$tabla.="<td class='$nom_clase' align='center'>".number_format((($prodMes1+$prodMes2+$prodMes3)/3),2,".",",")."</td>";
				$tabla.="</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";	
			}while($conceptos=mysql_fetch_array($rsConceptos));
			$tabla.="<tr>";
				$tabla.="<td class='nombres_columnas' align='center'>Total Mes</td>";
				$tabla.="<td class='nombres_filas' align='center'>M³</td>";
				$tabla.="<td class='nombres_filas' align='center'>".number_format($totalMes1,2,".",",")."</td>";
				$tabla.="<td class='nombres_filas' align='center'>".number_format($totalMes2,2,".",",")."</td>";
				$tabla.="<td class='nombres_filas' align='center'>".number_format($totalMes3,2,".",",")."</td>";
				$tabla.="<td class='nombres_filas' align='center'>".number_format($totalPromedio,2,".",",")."</td>";				
			$tabla.="</tr>";
			//Calcular y Mostrar la Productividad
			$prod1=$totalMes1/$numEmp/26;
			$prod2=$totalMes2/$numEmp/26;
			$prod3=$totalMes3/$numEmp/26;
			$prodPromedio=$totalPromedio/$numEmp/26;
			$tabla.="
				<tr><td colspan='6'></td></tr>
				<tr>
					<td class='nombres_columnas' align='center'>PRODUCTIVIDAD</td>
					<td class='nombres_columnas' align='center'>M³/PERSONA/DÍA</td>
					<td class='nombres_columnas' align='center'>".number_format($prod1,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>".number_format($prod2,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>".number_format($prod3,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>".number_format($prodPromedio,2,".",",")."</td>
				</tr>";
			$tabla.="</table>";
			$grafica=dibujarGrafica4($mesAnterior,$mesIniPer,$mesFinPer,$totalMes1,$totalMes2,$totalMes3,$msg_titulo);
		}		
		return $grafica."¬X¬".$tabla;
	}
	
	function dibujarGrafica4($mes1,$mes2,$mes3,$totalMesAnt,$totalMes1,$totalMes2,$titulo){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ("../../../../includes/graficas/jpgraph/jpgraph_scatter.php");
 
		$datay1 = array($totalMesAnt,$totalMes1,$totalMes2);
		$titulo=str_replace("<em>","",$titulo);
		$titulo=str_replace("<u>","",$titulo);
		$titulo=str_replace("</em>","",$titulo);
		$titulo=str_replace("</u>","",$titulo);
		
		// Setup the graph
		$graph = new Graph(800,500);
		$graph->SetMarginColor('white');
		$graph->SetScale("textlin");
		$graph->SetFrame(false);
		$graph->SetMargin(100,80,60,100);
		 
		// Setup the tab
		$graph->tabtitle->Set($titulo);
		$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,13);
		$graph->tabtitle->SetColor('darkred','#E1E1FF');
		 
		// Enable X-grid as well
		$graph->xgrid->Show();
		
		$graph->yaxis->title->Set('Producción');
		$graph->yaxis->title->SetColor('darkred');
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,10);
		$graph->yaxis->SetLabelFormat('%.2f M³');
		$graph->yaxis->SetTitleMargin(80);
		$graph->yaxis->scale->SetGrace(30);

		// Use months as X-labels
		$graph->xaxis->SetTickLabels(array($mes1,$mes2,$mes3));
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);
		 

		$graph->footer->center->Set('Mes');
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		 
		// Create the plot
		$p1 = new LinePlot($datay1);
		$p1->SetColor("navy@0.5");
		$p1->SetWeight(3);//<--------Dejarlo en 0 para quitarlo
		// Use an image of favourite car as marker
		$p1->mark->SetType(MARK_IMG_DIAMOND,'red',0.5);
		 
		// Displayes value on top of marker image
		$p1->value->SetFormat('%.2f M³');
		 //Valores de cada punto mostrado
		$p1->value->SetMargin(20);
		$p1->value->Show();
		$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$p1->value->SetColor('navy');
		$p1->value->SetAngle(45);
		 
		// Incent the X-scale so the first and last point doesn't
		// fall on the edges
		$p1->SetCenter();
		$graph->Add($p1);
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= "../../tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}
?>
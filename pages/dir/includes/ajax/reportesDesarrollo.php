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
				$periodo=$_GET["periodo"];
				$idCliente=$_GET["cliente"];
				$nomCliente=obtenerDato("bd_desarrollo","catalogo_clientes","nom_cliente","id_cliente",$cliente);
				$mes=substr($periodo,5,3);
				$mes=obtenerNombreCompletoMes($mes);
				$anio=substr($periodo,0,4);
				$titulo="REPORTE DE AVANCE VS PRESUPUESTO DE $mes $anio EN $nomCliente";
				$grafica=verReporteMensual($periodo,$idCliente,$titulo);
				header("Content-type: text/xml");	
				if ($grafica!=""){
					$grafica=str_replace("../../tmp","",$grafica);
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
	
	/*Funcion que se encarga de recopilar los datos el dibujado del Grafico*/
	function verReporteMensual($periodo,$cliente,$titulo){
		$mes=substr($periodo,5,3);
		$mes=obtenerNombreCompletoMes($mes);
		$anio=substr($periodo,0,4);
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		$sql_stm="SELECT fecha_inicio,fecha_fin,mts_mes,mts_mes_dia FROM presupuesto WHERE periodo = '$periodo' AND catalogo_clientes_id_cliente='$cliente'";
		$rs=mysql_query($sql_stm);
		if($datosPeriodo=mysql_fetch_array($rs)){
			//Arreglos para almacenar los totales que se muestran al final de cada Ubicación que es Mostrada
			$sumPorDia = array();//Arreglo que contendra la suma de los lanzamientos hechos por día
			$prodRealPorDia = array();//Arreglo que contendra el volumen real de los lanzamientos por dia acumulados
			$prodPresPorDia = array();//Arreglo que contendra el volumen presupuestado de los lanzamientos por dia acumulados
			$difPorDia = array();//Arrelo que contendrá la diferencia respecto
			
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
				$datosLanzamiento = mysql_fetch_array(mysql_query("SELECT SUM(avance) AS cantidad FROM bitacora_avance WHERE catalogo_ubicaciones_id_ubicacion =ANY(SELECT id_ubicacion FROM catalogo_ubicaciones WHERE catalogo_clientes_id_cliente='$cliente') AND fecha_registro = '$fechaActual'"));
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
//					//Comprobar si la Fecha Actual es Domingo
//					$domingo = false;
//					if(obtenerNombreDia($fechaActual)=="Domingo")
//						$domingo = true;
					//Colocar los valores del Volumen Real, presupuestado y diferencia en el primer dia del periodo
					if($cont==1){
						//Guardar la Produccion del Día y el Prespuesto del Día con valores FLotantes y a partir de ello realizar los respaldos necesarios para los calculos de los siguientes días
						$prodRealPorDia[$fechaActual] = floatval($volumen);
						$prodPresPorDia[$fechaActual] = floatval($datosPeriodo["mts_mes_dia"]);
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
//						if($domingo){
//							$prodPresPorDia[$fechaActual] = $presAnterior;
//						}
//						else{
							$prodPresPorDia[$fechaActual] = floatval($datosPeriodo["mts_mes_dia"] + $presAnterior);
							//Guardar el presupuesto del Dia como Presupuesto del Día Anterior
							$presAnterior = $prodPresPorDia[$fechaActual];
//						}
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
	}

	
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
		$graph->yaxis->title->Set('METROS LINEALES');//Eje Y
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
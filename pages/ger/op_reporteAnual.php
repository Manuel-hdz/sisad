<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica                                               
	  * Nombre Programador: Maurilio Hernández Correa                     
	  * Fecha: 22/08/2011                                      			
	  * Descripción: Este archivo contiene funciones para generar el reporte anual y la generación de la gráfica
	  * 			 frm_reporteAnual.php, frm_reporteAnualGrafica.php 	
	  **/	 		  	  	
	 
	 //**********************************************************************************
 	 //*********** FUNCIONES NECESARIAS PARA EL ARCHIVO frm_reporteAnual.php ************
 	 //**********************************************************************************

	 //funcion que carga los años disponibles en la tabla de bitacora_zarpeo en la bd de gerencia
	function cargarAniosDisponibles(){
		//conectar a la base de datos de gerencia
		$conn = conecta('bd_gerencia');
		$rs = mysql_query("SELECT DISTINCT SUBSTR(periodo,1,4) AS anio FROM presupuesto");
		$anios = array();
		while($datos=mysql_fetch_array($rs))
			$anios[] =  $datos['anio'];
		//cerrar conexion
		mysql_close($conn);	
		//llamar la funcion que permite cargar los años disponibles de producción
		cargarAniosDisponibles2($anios);
	} //Fin function cargarAniosDisponibles()	


	 //funcion que carga los años disponibles en la tabla de bitacora_zarpeo en la bd de produccion
	function cargarAniosDisponibles2($anios){
		//conectar a la base de datos de produccion
		$conn = conecta('bd_produccion');
		$rs = mysql_query("SELECT DISTINCT SUBSTR(periodo,1,4) AS anio FROM presupuesto");
		while($datos=mysql_fetch_array($rs))
			$anios[] =  $datos['anio'];
		//cerrar conexion
		mysql_close($conn);
		//Eliminar los años repetidos en el arreglo
		$anioUnico = array_unique($anios);
		//Ordenar el arreglo
		sort($anioUnico);
		?>
		
		<select name="cmb_anios" id="cmb_anios" class="combo_box">  
            <option value="">A&ntilde;o</option><?php
            foreach($anioUnico as $ind => $anio){?>
                <option value="<?php echo $anio;?>"><?php echo $anio;?></option><?php
            }?>
		</select><?php
	} //Fin function cargarAniosDisponibles2()	


	//funcion que se encarga de mostrar el reporte generado anualmente
	function mostrarReporteAnual($anio){
		if(isset($_SESSION['conceptos']))
			unset($_SESSION['conceptos']);
			
		if(isset($_SESSION['sumaConceptos']))
			unset($_SESSION['sumaConceptos']);
		//Arreglo que permitira llevar los valores  al frm_reporteAnual para de ahi mandarlos por el boton exportar a excel a guardar_reporte	
		$arreglo_Inf = array();
	
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
		$rsDestino = mysql_query("SELECT DISTINCT destino FROM bitacora_zarpeo JOIN catalogo_ubicaciones ON ubicacion=destino");
		
		//Guardar las UBicaciones encontradas en el arreglo de destinos
		while($datos=mysql_fetch_array($rsDestino))
			$destinos[] = $datos['destino'];
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Comparativo Anual de Producci&oacute;n en el A&ntilde;o <em><u> $anio</em></u>";			
		
		//Desplegar los resultados de la consulta en una tabla
		echo "				
		<table cellpadding='5' width='130%'>				
			<tr>
				<td colspan='10' align='center' class='titulo_etiqueta'>$msg</td>
			</tr>
			<tr>
				<td rowspan='2' class='nombres_columnas' align='center'>MES</td>";
		foreach($destinos as $ind => $destino){
			$sumaConceptos[$destino] = array("ZARPEO"=>0,"PISOS"=>0);
			//Inicializar cada concepto como un arreglo para almacenar los valores de cada uno de los meses
			$conceptos[$destino." ZARPEO"] = array();
			$conceptos[$destino." PISOS"] = array();			
			echo "<td colspan='2' class='nombres_columnas' align='center'>$destino</td>";
		}

		//Agregar un indice para acumular los Colados y otro para el Zarpeo Via Seca
		$sumaConceptos['COLADOS'] = array("BOMBEO"=>0,"TD"=>0);
		$sumaConceptos['VIASECA'] = 0;
		
		//Inicializar cada concepto como un arreglo para almacenar los valores de cada uno de los meses
		$conceptos["COLADOS BOMBEO"] = array();
		$conceptos["COLADOS TD"] = array();
		$conceptos["VIASECA"] = array();
			
		echo "	<td colspan='2' class='nombres_columnas' align='center'>COLADOS</td>
				<td rowspan='2' class='nombres_columnas' align='center'>VIA SECA</td>
				<td rowspan='2' class='nombres_columnas' align='center'>TOTAL</td>
			</tr>";
			
		foreach($destinos as $ind => $destino){
			echo"<td class='nombres_columnas' align='center'>ZARPEO</td>
				<td class='nombres_columnas' align='center'>PISOS</td>";
		}
		
		echo "	<td class='nombres_columnas' align='center'>BOMBEO</td>
				<td class='nombres_columnas' align='center'>TIRO DIRECTO</td>
			</tr>";
		
		//Este ciclo ayudara a obtener los datos por cada mes
		$nom_clase = "renglon_gris";
		$contRenglon= 1;
		
		$contMes = 0;
		do{	
			//Variable para acumular el total de los conceptos encotrados por cada mes
			$totalMes = 0;	
			//Conectar a la BD de gerencia
			$conn = conecta("bd_gerencia");

			echo "
			<tr>
				<td class='nombres_filas' width='15%'>$meses[$contMes]</td>";
			
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
				echo "					
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
			echo "	
				<td class='$nom_clase' align='right'>".number_format($cantBombeo,2,".",",")."</td>
				<td class='$nom_clase' align='right'>".number_format($cantTiroD,2,".",",")."</td>";
			
			//Acumular los Colados de cada mes para obtener el promedio
			$sumaConceptos['COLADOS']['BOMBEO'] += $cantBombeo;
			$sumaConceptos['COLADOS']['TD'] += $cantTiroD;
			//Acumular el total de los colados para obtener el total del mes
			 $totalMes += ($cantBombeo + $cantTiroD);
			//Total de todo el año
			$totalTotales	+= ($cantBombeo + $cantTiroD);
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
			
			echo "
					<td class='$nom_clase' align='right'>".number_format($cantViaSeca,2,".",",")."</td>";
			
			//Realizar la suma de cada mes para obtener el total del Zarpeo Via Seca
			$cantViaSeca= $sumaConceptos['VIASECA'] + $cantViaSeca;
			//Acumular el total del Zarpeo de la Via Seca para obtener el total del mes
			$totalMes += $cantViaSeca;	
			$totalTotales	+=  $cantViaSeca;
			//Guardar los datos necesario para la Grafica con los datos de los Volumenes de Via Seca
			$conceptos["VIASECA"][] = $cantViaSeca;
			
			echo "
					<td class='$nom_clase' align='right'>".number_format($totalMes,2,".",",")."</td>
				</tr>";
			
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
		echo "<tr>
				<td class='nombres_filas' width='15%' align='right'>PROMEDIO</td>";
		//Colocar el promedio por cada Concepto de Cada Ubicacion Encontrada
		foreach($destinos as $ind => $destino){
			//Obtener promedio de Zarpeo y de Pisos
			$promZarpeo = $sumaConceptos[$destino]['ZARPEO']/12;
			$promPisos = $sumaConceptos[$destino]['PISOS']/12;
			
			echo " 
				<td class='$nom_clase' width='15%' align='right'>".number_format($promZarpeo,2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format($promPisos,2,".",",")."</td>";
		}
		
		//Colocar el Promedio de los Colados
		$promBombeo = $sumaConceptos["COLADOS"]['BOMBEO']/12;
		$promTD = $sumaConceptos["COLADOS"]['TD']/12;
		echo " 
				<td class='$nom_clase' width='15%' align='right'>".number_format($promBombeo,2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format($promTD,2,".",",")."</td>";
				
		//Colocar el promedio del Zarpeo de Via Seca
		$promViaSeca = $sumaConceptos["VIASECA"]/12;
		echo " 
				<td class='$nom_clase' width='15%' align='right'>".number_format($promViaSeca,2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format($totalTotales,2,".",",")."</td>
			</tr>
		</table>";		
		
		//Subir a la SESSION los datos para crear la Grafica				
		$_SESSION['conceptos'] = $conceptos;
		$_SESSION['sumaConceptos'] = $sumaConceptos;

		//Llamar la funcion que genera la gráfica    
		$grafica=mostrarGrafica($anio);
		//Asignar los valores al arreglo ya que contendra 3 posiciones.  0=>mensaje  1=>cmb_anios   2=>nombGrafica
 		$arreglo_Inf[] = $msg;
		$arreglo_Inf[] = $anio;
		$arreglo_Inf[] = $grafica;
		
		return $arreglo_Inf;
	}//FIN  function mostrarReporteAnual()
	
	//funcion para obtener los meses en 2 digitos
	function obtenerMes($cont){
		if($cont<=9)
			return '0'.$cont;
		if($cont>=10)
			return $cont;
	} 

	 //**********************************************************************************
 	 //******* FUNCIONES NECESARIAS PARA EL ARCHIVO frm_reporteAnualGrafica.php *********
 	 //**********************************************************************************
	 
	//Funcion que se encarga de mostrar la gráfica generada
	function mostrarGrafica($anio){	
		if(isset($_SESSION['conceptos']) && isset($_SESSION['sumaConceptos'])){
			//arreglo contenedor de los meses del años
			$arrMeses = array("ENERO","FEBRERO","MARZO","ABRIL","MAYO","JUNIO","JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");						
			require_once ('../../includes/graficas/jpgraph/jpgraph.php');
			require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
			// Create the basic graph
			$graph = new Graph(1450,890,'auto');	
			$graph->SetScale("textlin");
			// Margenes del gráfico LRTB
			$graph->img->SetMargin(80,200,60,60);
			// Posicion de la caja de iconografia
			$graph->legend->Pos(0.00,0.07);
			// Color de la caja de iconografia 
			$graph->legend->SetShadow('darkgray@0.5');
			$graph->legend->SetFillColor('lightblue@0.3');
			//Colocar el arreglo que contiene los meses, para cada conjuto de barras
			$graph->xaxis->SetTickLabels($arrMeses);
			//Darle una inclinación a las etiquetas
			$graph->xaxis->SetLabelAngle(0);				
			//tipo de letra de la etiqueta
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,4);
			//Separación y/o margenes de las etiquetas 
			$graph->xaxis->SetLabelMargin(20);
			//Alineación de las etiquetas
			$graph->xaxis->SetLabelAlign('center','center');
			// Set axis titles and fonts
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,9);
			$graph->xaxis->SetColor('black');
			$graph->yaxis->SetFont(FF_VERDANA,FS_NORMAL,9);
			$graph->yaxis->SetColor('black');
			//$graph->ygrid->Show(false);
			$graph->ygrid->SetColor('pink@0.5');
			// Setup graph title
			$graph->title->Set("Reporte Anual ".$anio);
			// Some extra margin (from the top)
			$graph->title->SetMargin(3);
			$graph->title->SetFont(FF_ARIAL,FS_NORMAL,12);
			//Arreglo que contendra las barras a dibujar
			$barras = array();
			//arreglco con los posibles colores para cada una de las barras a graficar
			$colores = array("blue@0.4","darkred@0.4","green@0.4","purple@0.4","lightblue@0.4","orange@0.4","brown@0.4","darkgreen@0.4","black@0.4","red@0.4",
			"yellow@0.4","gray@0.4","pink@0.4");
			$cont = 0;
			foreach($_SESSION['conceptos'] as $ind => $concepto){
				// Create the three var series we will combine	
				$barras[] = new BarPlot($concepto);
				// Setup the colors with 40% transparency (alpha channel)
				$barras[$cont]->SetFillColor($colores[$cont]);
				// Setup legends
				$barras[$cont]->SetLegend($ind);
				// Setup each bar with a shadow of 50% transparency				
				$barras[$cont]->SetShadow("black@0.4");	
				$cont++;
			}
			$gbarplot = new GroupBarPlot($barras);
			$gbarplot->SetWidth(0.6);															
			//Agregar los datos a la gráfica
			$graph->Add($gbarplot);
			//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
			$rnd=rand(0,1000);
			$grafica= 'tmp/grafAnual'.$rnd.'.png';
			//Dibujar la grafica y guardarla en un archivo temporal	
			$graph->Stroke($grafica);
			//Devolver el nombre de la grafica para poder identificarla y colocarla en el reporte que se exporta y/o en el div donde se muestra
			return $grafica;
		}//FIN if(isset ($_SESSION['conceptos']) && isset ($_SESSION['sumaConceptos']))		
	}
	 
	//Esta Función elimina los archivos temporales de la carpeta tmp ubicada en gerencia técnica ('ger/tmp/'), se manda llamar desde el archivo de salir.php	
	function borrarTemporales(){
		//Borrar los ficheros temporales
		$h=opendir('ger/tmp/');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				@unlink("ger/tmp/".$file);
			}
		}
		closedir($h);
	}

?>
<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 30/Julio/2011
	  * Descripción: Este archivo contiene funciones relacionada con el formulario de reporte comparativo mina
	**/
	
	function cargarAniosDisponibles(){
		//conectar a la base de datos de gerencia
		$conn = conecta('bd_gerencia');
		$rs = mysql_query("SELECT DISTINCT SUBSTR(periodo,1,4) AS anio FROM presupuesto");
		$anios = array();
		while($datos=mysql_fetch_array($rs))
			$anios[] =  $datos['anio'];
		//cerrar conexion
		mysql_close($conn);	
		?>
		<select name="cmb_anios" id="cmb_anios" class="combo_box">  
            <option value="">A&ntilde;o</option><?php
            foreach($anios as $ind => $anio){?>
                <option value="<?php echo $anio;?>"><?php echo $anio;?></option><?php
            }?>
		</select><?php
	} //Fin function cargarAniosDisponibles()	
	
	//Funcion que se encarga de desplegar el reporte
	function mostrarReporte(){
		//Arreglo que permitira llevar el valor del cmb_ubicacion y de cmb_anios al frm_reporteComparativoMina para de ahi mandarlos por el boton exportar a excel
		// a guardar_reporte	
		$arreglo_Inf = array();

		//Obtener los datos del post
		$destino = $_POST['cmb_ubicacion'];
		$anio = $_POST['cmb_anios'];
		//Obtener el ID de la ubicacion
		$idUbicacion=obtenerDato("bd_gerencia","catalogo_ubicaciones","id_ubicacion","ubicacion",$destino);
		//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");
		//contador que nos permite controlar el ciclo de los meses
		$cont = 0;
		//arreglos en el cual se almacenaran los tatales de zarpeos como los de pisos
		
		$resZarpeo = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0);
		$resPisos = array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0,10=>0,11=>0);
		$meses = array(0=>"ENERO",1=>"FEBRERO",2=>"MARZO",3=>"ABRIL",4=>"MAYO",5=>"JUNIO",6=>"JULIO",7=>"AGOSTO",8=>"SEPTIEMBRE",9=>"OCTUBRE",10=>"NOVIEMBRE",11=>"DICIEMBRE");
		
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
		$msg= "Comparativo Mensual de Producci&oacute;n en <em><u>$destino</u></em> en el A&ntilde;o <em><u> $anio</em></u>";			
		
		//Desplegar los resultados de la consulta en una tabla
		echo "				
		<table cellpadding='5' width='100%'>				
			<tr>
				<td colspan='4' align='center' class='titulo_etiqueta'>$msg</td>
			</tr>
			<tr>
				<td class='nombres_columnas' align='center'>MES</td>
				<td class='nombres_columnas' align='center'>ZARPEO</td>
				<td class='nombres_columnas' align='center'>PISOS</td>
				<td class='nombres_columnas' align='center'>TOTAL</td>
			</tr>";

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
			echo "
				<tr>
					<td class='nombres_filas' width='15%'>$meses[$contMes]</td>
					<td class='$nom_clase' width='15%' align='right'>".number_format($resZarpeo[$contMes],2,".",",")."</td>
					<td class='$nom_clase' width='15%' align='right'>".number_format($resPisos[$contMes],2,".",",")."</td>
					<td class='$nom_clase' width='15%' align='right'>".number_format($totalMes,2,".",",")."</td>
				</tr>";
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
		
		echo  " 
			<tr>
				<td class='$nom_clase' width='15%' align='right'>PROMEDIO</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format(($sumaZarpeo),2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format(($sumaPisos),2,".",",")."</td>
				<td class='$nom_clase' width='15%' align='right'>".number_format(($promSumas),2,".",",")."</td>
			</tr>
		</table>";
		
		//Llamar la funcion que genera la gráfica    
		$grafica=generarGrafica($resZarpeo,$resPisos,$sumaZarpeo,$sumaPisos,$destino,$anio);
		
		//Asignar los valores al arreglo ya que contendra 4 posiciones.  0=>cmb_ubicacion,  1=>cmb_anios,  2=>msje de la grafica, 3=>Nombre de la gráfica>
		$arreglo_Inf[] = $destino;
		$arreglo_Inf[] = $anio;
		$arreglo_Inf[] = $msg;
		$arreglo_Inf[] = $grafica;
				
		return $arreglo_Inf;
	}//FIN  function mostrarReporte()
	
	function obtenerMes($cont){
		if($cont<=9)
			return '0'.$cont;
		if($cont>=10)
			return $cont;
	} 
	
	//Funcion que dibuja la gráfica 
	function generarGrafica($zarpeo,$pisos,$promZarp,$promPiso,$destino,$anio){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		
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
		$graph->title->Set('Comparativo Mensual de Producción en '.$destino.' en el Año '.$anio);
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
		$grafica= 'tmp/grafica'.$rnd.'.png';
		
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Devolver el nombre de la grafica para poder identificarla y colocarla en el reporte que se exporta y/o en el div donde se muestra
		return $grafica;
	}//FIN function generarGrafica()
	
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
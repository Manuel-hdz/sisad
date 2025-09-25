<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 19/Octubre/2012
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Consumo de Llantas 
	  **/

	/*Funcion que recopila los datos para dibujar la grafica*/
	function reporteLlantas(){
		//Crear las Fechas
		$fechaI=$_POST["cmb_anios"]."-".$_POST["cmb_meses"]."-01";
		//Obtener el ultimo dia del mes Seleccionado
		$diaFinal=diasMes($_POST["cmb_meses"],$_POST["cmb_anios"]);
		$fechaF=$_POST["cmb_anios"]."-".$_POST["cmb_meses"]."-".$diaFinal;
		//Obtener el nombre del Mes
		$mes=nombreMes($_POST["cmb_meses"]);
		//Extraer y verificar el Equipo, si tiene valor diferente de vacio, mostrar todo el Reporte
		$equipo=$_POST["cmb_equipo"];
		//Conectarse a la BD
		$conn=conecta("bd_mantenimiento");
		//Variable que contedra la grafica
		$grafica="";
		//Verificar el departamento donde el usuario esta logueado
		$area="CONCRETO";
		
		if($equipo==""){
			//Ensamblar el titulo de la grafica
			$titulo="Reporte de Consumo de Llantas de $mes $_POST[cmb_anios] \nÁrea: '$area'";
			//Sentencia SQL
			$sql_stm="SELECT SUM(costo) AS total,equipo FROM detalle_bitacora_llantas JOIN bitacora_llantas ON id_bitacora=bitacora_llantas_id_bitacora WHERE tipo_mov='S' GROUP BY equipo";
		}
		else{
			//Ensamblar el titulo de la grafica
			$titulo="Reporte de Consumo de Llantas del Equipo: $equipo en $mes $_POST[cmb_anios] \nÁrea: '$area'";
			//Sentencia SQL
			$sql_stm="SELECT SUM(costo) AS total,equipo FROM detalle_bitacora_llantas JOIN bitacora_llantas ON id_bitacora=bitacora_llantas_id_bitacora WHERE equipo='$equipo' AND tipo_mov='S'";
		}
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			$costoLlantas=array();
			$equipos=array();
			$cantRes=mysql_num_rows($rs);
			do{
				//Recuperar los servicios para calcular el porcentaje posteriormente
				$costoLlantas[]=$datos["total"];
				//Recuperar las etiquetas
				$equipos[]=$datos["equipo"];
			}while($datos=mysql_fetch_array($rs));
			//Dibujar la grafica
			$grafica=graficaLlantas($cantRes,$equipos,$titulo,$costoLlantas);
			mysql_close($conn);
		}
		else{
			mysql_close($conn);
			?>
			<script type="text/javascript" language="javascript">
				location.href='frm_reporteLlantas.php?noResults';
			</script>
			<?php
		}
	}//Fin function reporteAceites()
	
	//Funcion que muestra el Grafico de consumos de aceite
	function graficaLlantas($cantRes,$equipos,$titulo,$costoLlantas){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		//Registros por Grafica
		$cantDatos=10;
		//Obtener el total gastado en llantas
		$totalGastado="$".number_format(array_sum($costoLlantas),2,".",",");
		//Obtener la cantidad de graficas
		$ciclos=$cantRes/$cantDatos;
		//Redondear el valor de los ciclos
		$ciclos=intval($ciclos);
		//Obtener el residuo para saber si incrementar en 1 la cantidad de ciclos
		$residuo=$cantRes%$cantDatos;
		//Si residuo es mayor a 0, incrementar en uno los ciclos
		if($residuo>0)
			$ciclos+=1;
		//Inicializar variable de control para la cantidad de ciclos
		$cont=0;
		//Contador por cada grafica a dibujar
		$contPorGrafica=0;
		do{
			//Declarar el arreglo de costos Entrada por cada grafica
			$equiposPorGrafica=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$equiposPorGrafica[]=$costoLlantas[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$equipos[$contPorGrafica];
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			/**********************/
			$datay = $equiposPorGrafica;
			// Create the graph and setup the basic parameters
			$graph = new Graph(945,430,'auto');
			$graph->img->SetMargin(80,30,60,125);
			$graph->SetScale('textint');
			$graph->SetFrame(false);
			$graph->yaxis->SetLabelFormatCallback('formatoNumeros'); 
			// Setup X-axis labels
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetLabelAngle(45);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			// Setup graph title ands fonts
			$graph->title->Set($titulo);
			// Crear y agregar un Texto
			$txt=new Text("TOTAL GASTADO\n      $totalGastado");
			$txt->SetPos(620,30);
			$txt->SetColor('black');
			$txt->SetFont(FF_FONT2,FS_BOLD);
			$txt->SetBox('lightsteelblue','navy','gray@0.5');
			$graph->AddText($txt);
			//Pie de Tabla
			$graph->footer->center->Set('Aceite');
			$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->footer->center->SetColor('darkred');
			// Create a bar pot
			$bplot = new BarPlot($datay);
			$bplot->SetFillGradient("lightsteelblue","darkgreen",GRAD_VER);
			$bplot->SetWidth(0.5);
			//Obtener el Valor Minimo a Graficar
			$valorMinimo=min($equiposPorGrafica);
			$valorGrace=10;
			//Setup the values that are displayed on top of each bar
			$bplot->value->Show();
			$bplot->SetValuePos('center');
			//$bplot->value->SetFormat('%.2f%');
			$bplot->value->SetFormatCallback('formatoNumeros');
			// Must use TTF fonts if we want text at an arbitrary angle
			$bplot->value->SetFont(FF_ARIAL,FS_BOLD,12);
			$bplot->value->SetAngle(45);
			// Black color for positive values and darkred for negative values
			$bplot->value->SetColor('black','darkred');
			$graph->Add($bplot);
			//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
			$rnd=rand(0,1000);
			$grafica= "tmp/grafica".$rnd.".png";
			//Dibujar la grafica y guardarla en un archivo temporal	
			$graph->Stroke($grafica);
			/**********************/
			$cont++;
			//Agregar la primer grafica al DIV principal
			if($cont==1){
				?>
				<div align='center' id='tabla-graficas' class='borde_seccion2' width='100%' >
				<a href='<?php echo $grafica;?>' rel='lightbox[repMensual]' title='Gr&aacute;fica del Reporte de Gasto en Llantas'>
					<img width="100%" height="100%" border="0" src="<?php echo $grafica;?>" title="Gr&aacute;fica del Reporte de Gasto en Llantas"/>
				</a>
				</div>
				<?php
			}
			//Agregar las siguientes graficas al DIV secundario
			else{
				?>	
					<div id="imagenes" style="visibility:hidden;width:1px;height:1px;overflow:hidden">
					<a href='<?php echo $grafica;?>' rel='lightbox[repMensual]' title='Gr&aacute;fica del Reporte de Gasto en Llantas'>
						<img width="2%" height="2%" border="0" src="<?php echo $grafica;?>" title="Gr&aacute;fica del Reporte de Gasto en Llantas"/>
					</a>
					</div>
				<?php
			}
		}while($cont<$ciclos);
	}//Cierre graficaAceites($cantServicios,$equipos,$titulo,$costoLlantas)
	
	//Funcion que pone los valores en formato de Pesos con la "," incluida
	function formatoNumeros($aVal) {
		return '$'.number_format($aVal,2,".",",");
	}
	
	function nombreMes($mes){
		switch($mes){
			case "01":
				$mes="ENERO";
			break;
			case "02":
				$mes="FEBRERO";
			break;
			case "03":
				$mes="MARZO";
			break;
			case "04":
				$mes="ABRIL";
			break;
			case "05":
				$mes="MAYO";
			break;
			case "06":
				$mes="JUNIO";
			break;
			case "07":
				$mes="JULIO";
			break;
			case "08":
				$mes="AGOSTO";
			break;
			case "09":
				$mes="SEPTIEMBRE";
			break;
			case "10":
				$mes="OCTUBRE";
			break;
			case "11":
				$mes="NOVIEMBRE";
			break;
			case "12":
				$mes="DICIEMBRE";
			break;
		}
		return $mes;
	}
?>
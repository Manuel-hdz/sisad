<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 20/Abril/2012
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Disponibilidad de Equipo 
	  **/

	/*Funcion que recopila los datos para dibujar la grafica*/
	function reporteDisponibilidad(){
		//Verificar el departamento donde el usuario esta logueado
		if($_SESSION["depto"]=="MttoConcreto")
			$area="CONCRETO";
		else
			$area="MINA";
		//Crear las Fechas
		$fechaI=$_POST["cmb_anios"]."-".$_POST["cmb_meses"]."-01";
		//Obtener el ultimo dia del mes Seleccionado
		$diaFinal=diasMes($_POST["cmb_meses"],$_POST["cmb_anios"]);
		$fechaF=$_POST["cmb_anios"]."-".$_POST["cmb_meses"]."-".$diaFinal;
		//Extraer y verificar el Equipo, si tiene valor diferente de vacio, verificar la Familia
		$equipo=$_POST["cmb_equipo"];
		//Extraer y verificar la Familia, si tiene valor diferente de vacio, Mostrar el Reporte de Disponibilidad de forma Mensual
		$familia=$_POST["cmb_familia"];
		//Obtener el nombre del Mes
		$mes=nombreMes($_POST["cmb_meses"]);
		//Variable para obtener el Tipo de Reporte
		$tipoRep=0;
		//Verificar si equipo es diferente de vacio
		if($equipo!=""){
			//Ensamblar el titulo de la grafica
			$titulo="Reporte de Disponibilidad del Equipo: $equipo en $mes $_POST[cmb_anios] \nÁrea: '$area'";
			//Obtener el total de Horas Efectivas segun lo registrado en los Horometros y Odometros
			$hrsServicio=calcularHorasServicio($fechaI,$fechaF,$familia,$equipo,1);
			//Sentencia SQL
			echo $sql_stm="SELECT equipos_id_equipo,tiempo_total FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' AND equipos_id_equipo='$equipo'";
			//Variable para obtener el Tipo de Reporte
			$tipoRep=1;
		}
		elseif($familia!=""){
			//Ensamblar el titulo de la grafica
			$titulo="Reporte de Disponibilidad de los Equipos: $familia en $mes $_POST[cmb_anios] \nÁrea: '$area'";
			//Obtener el total de Horas Efectivas segun lo registrado en los Horometros y Odometros
			$hrsServicio=calcularHorasServicio($fechaI,$fechaF,$familia,"",2);
			//Sentencia SQL
			$sql_stm="SELECT equipos_id_equipo,tiempo_total FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' 
			AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='CONCRETO' AND familia='$familia' AND metrica='HOROMETRO' AND equipos_id_equipo!='') ORDER BY equipos_id_equipo";
			//Variable para obtener el Tipo de Reporte
			$tipoRep=2;
		}
		else{
			//Ensamblar el titulo de la grafica
			$titulo="Reporte de Disponibilidad de $mes $_POST[cmb_anios] \nÁrea: '$area'";
			//Obtener el total de Horas Efectivas segun lo registrado en los Horometros y Odometros
			$hrsServicio=calcularHorasServicio($fechaI,$fechaF,"","",3);
			//Sentencia SQL
			$sql_stm="SELECT equipos_id_equipo,tiempo_total FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' 
			AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='CONCRETO' AND metrica='HOROMETRO' AND equipos_id_equipo!='') ORDER BY equipos_id_equipo";
			//Variable para obtener el Tipo de Reporte
			$tipoRep=3;
		}
		//Verificar si hay Equipos en el arreglo $hrsServicio, de lo contrario, no hay registros de horometros_odometros
		if(count($hrsServicio)>0){
			//Conectarse a la BD
			$conn=conecta("bd_mantenimiento");
			//Ejecutar la sentencia
			$rs=mysql_query($sql_stm);
			//Variable para acumular las horas invertidas en Mantenimiento por Equipo
			$tiempoMtto=0;
			//Extraer los datos de la consulta
			if($datos=mysql_fetch_array($rs)){
				//Arreglo que almacenara los Equipos
				$equiposServicio=array();
				//Arreglo que almacenara el numero de Horas de Servicio en caso que se encuentren resultados
				$hrsMtto=array();
				//Variable para guardar la cantidad de Horas
				$hrs=0;
				//Variable para guardar la cantidad de Minutos
				$min=0;
				//Asignar a la variable Equipo, el primero encontrado
				$equipo=$datos["equipos_id_equipo"];
				do{
					if($equipo!=$datos["equipos_id_equipo"]){
						//Variable bandera para identificar si un Equipo con Mtto aparece en los Equipos Trabajando
						$flag=0;
						//Variable para guardar las Horas que ha Trabajado el Equipo
						$hrsTrabajando=0;
						//Variable con la disponibilidad de cada Equipo
						$disponibilidad=0;
						//Recorrer el Arreglo de Horas de Servicio en su posicion de Equipos
						foreach($hrsServicio["equipos"] as $ind => $value){
							//Si el Equipo es igual, quiere decir que al equipo se le hizo un mantenimiento
							if($value==$equipo){
								//Obtener las Horas Trabajas por el Equipo
								$hrsTrabajando=$hrsServicio["horas"][$ind];
								//Calcular la disponibilidad
								$disponibilidad=round((100-(($tiempoMtto*100)/$hrsTrabajando)),2);
								//Quitar del arreglo de Horas de Servicio el Equipo
								unset($hrsServicio["equipos"][$ind]);
								//Quitar del arreglo de Horas de Servicio las Horas
								unset($hrsServicio["horas"][$ind]);
								//Si se encontro el Equipo, activar una bandera para poder agregarlo al arreglo de Datos
								$flag=1;
								//Romper el ciclo, no tiene caso seguir buscando
								break;
							}
						}
						//Si la variable se activo, el Equipo recibiendo Mtto tiene registro de Horo_Odo
						if($flag==1){
							//Recuperar el ID por cada Equipo
							$equiposMtto[]=$equipo;
							//Recuperar el valor de las Horas
							$hrsMtto[]=$tiempoMtto;
							//Recuperar el % de Disponibilidad
							$dispEquipo[]=$disponibilidad;
						}
						//Asignar a la variable Equipo el siguiente Equipo del cual se van a obtener las horas
						$equipo=$datos["equipos_id_equipo"];
						//Variable para acumular las horas invertidas en Mantenimiento por Equipo
						$tiempoMtto=0;
					}
					//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
					$hora=split(":",$datos["tiempo_total"]);
					//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
					$hrs=intval($hora[0]);
					//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
					$min=intval($hora[1]);
					//Obtener el Tiempo Total en cantidad
					$tiempoMtto+=round(($hrs+($min/60)),2);
				}while($datos=mysql_fetch_array($rs));
				//Proceso para el ultimo Equipo
				//Recorrer el Arreglo de Horas de Servicio en su posicion de Equipos
				foreach($hrsServicio["equipos"] as $ind => $value){
					//Si el Equipo es igual, quiere decir que al equipo se le hizo un mantenimiento
					if($value==$equipo){
						//Obtener las Horas Trabajas por el Equipo
						$hrsTrabajando=$hrsServicio["horas"][$ind];
						//Calcular la disponibilidad
						$disponibilidad=round((100-(($tiempoMtto*100)/$hrsTrabajando)),2);
						//Quitar del arreglo de Horas de Servicio el Equipo
						unset($hrsServicio["equipos"][$ind]);
						//Quitar del arreglo de Horas de Servicio las Horas
						unset($hrsServicio["horas"][$ind]);
						//Si se encontro el Equipo, activar una bandera para poder agregarlo al arreglo de Datos
						$flag=1;
						//Romper el ciclo, no tiene caso seguir buscando
						break;
					}
				}
				//Si la variable se activo, el Equipo recibiendo Mtto tiene registro de Horo_Odo
				if($flag==1){
					//Ultimo Registro
					//Recuperar el ID por cada Equipo
					$equiposMtto[]=$equipo;
					//Recuperar el valor de las Horas
					$hrsMtto[]=$tiempoMtto;
					//Recuperar el % de Disponibilidad
					$dispEquipo[]=$disponibilidad;
				}
			}
			else{
				$equiposMtto=array();
				foreach($hrsServicio["equipos"] as $ind => $value){
					//Recuperar el ID por cada Equipo
					$equiposMtto[]=$value;
					//Recuperar el valor de las Horas
					$hrsMtto[]=0;
					//Recuperar el % de Disponibilidad
					$dispEquipo[]=100;
				}
			}
			/*ESTAS LINEAS PERMITEN AGREGAR LOS DEMAS EQUIPOS QUE NO SE CARGARON AL REGISTRO PARA GRAFICAR, SI SE DESEAN AGREGAR DECOMENTAR LAS LINEAS
			/*EQUIPOSEXTRA-INI
			//Si el Tipo de Reporte es diferente del de Equipo, agregar los demas Equipos al Arreglo a Graficar
			if($tipoRep!=1){
				//Agregar al Arreglo EquiposMtto los Equipos faltantes de Mtto, estos equipos se agregan con 100% de Disponibilidad al no tener registro de Horometro/Odometro
				if($tipoRep==2){
					//Sentencia SQL para extraer los demas Equipos segun el Area y la Familia
					$sql_stm="SELECT id_equipo FROM equipos WHERE area='CONCRETO' AND familia='$familia' AND disponibilidad='ACTIVO' AND metrica='HOROMETRO'";
				}
				elseif($tipoRep==3){
					//Sentencia SQL para extraer los demas Equipos segun el Area solamente
					$sql_stm="SELECT id_equipo FROM equipos WHERE area='CONCRETO' AND disponibilidad='ACTIVO' AND metrica='HOROMETRO'";
				}
				//Ejecutar la sentencia
				$rs=mysql_query($sql_stm);
				//Si se retornan Equipos, compararlos contra los agregados, si son diferentes, agregarlos al arreglo
				if($datos2=mysql_fetch_array($rs)){
					do{
						//Variable para identificar si los equipos ya estan en el arreglo de datos a graficar
						$flag=0;
						//Recorrer el arreglo de equipos que se van a graficar
						foreach($equiposMtto as $ind => $value){
							//Si es el mismo equipo, activar la bandera y romper el ciclo
							if($value==$datos2["id_equipo"]){
								$flag=1;
								break;
							}
						}
						//Si la bandera no se activo, agregar el Equipo al Arreglo
						if($flag==0){
							//Recuperar el ID por cada Equipo
							$equiposMtto[]=$datos2["id_equipo"];
							//Recuperar el valor de las Horas
							$hrsMtto[]=0;
							//Recuperar el % de Disponibilidad
							$dispEquipo[]=100;
						}
					}while($datos2=mysql_fetch_array($rs));
				}
			}
			//Cerrar la conexion con la BD
			mysql_close($conn);
			/*EQUIPOSEXTRA-FIN
			*/
			//Crear Tabla de Resultados
			$tabla=tablaDisponibilidad($equiposMtto,$dispEquipo,$hrsMtto,$fechaI,$fechaF,$titulo);
			?>
			<div align='center' id='tabla-datos' class='borde_seccion2' width='100%' align="center" style="visibility:hidden">
				<img src='images/grafica.png' title='Ver Gr&aacute;fica' style='cursor:pointer' onclick="alternarVista(2);"/>
				<?php echo $tabla;?>
			</div>
			<?php
			//Dibujar la grafica
			$grafica=graficaDisponibilidad($dispEquipo,$equiposMtto,$titulo);
			//Regresar valor de Verdadero
			return true;
		}
		else{
			echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>";
			echo "<p align='center' class='msje_correcto'>No existen Registros de Horómetro para Ningún Equipo, es Imposible Determinar la Disponibilidad</p>";
			?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('No existen Registros de Horómetro para Ningún Equipo, es Imposible Determinar la Disponibilidad');location.href='frm_reporteDisponibilidad.php'",1000);
				</script>
			<?php
			//Regresar valor de Falso
			return false;
		}
	}//Fin function reporteDisponibilidad()
	
	//Grafica que es incluida en el reporte de Disponibilidad
	function graficaDisponibilidad($dispEquipo,$equiposMtto,$titulo){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');	
		//Obtener la cantidad de Registros
		$cantRes=count($dispEquipo);
		//Registros por Grafica
		$cantDatos=10;
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
			//Declarar el arreglo de Disponibilidad por cada grafica
			$dispPorGrafica=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$dispPorGrafica[]=$dispEquipo[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$equiposMtto[$contPorGrafica];
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			/**********************/
			$datay = $dispPorGrafica;
			// Create the graph and setup the basic parameters
			$graph = new Graph(945,430,'auto');
			$graph->img->SetMargin(40,30,60,125);
			$graph->SetScale('textint');
			$graph->SetFrame(false);
			$graph->yaxis->SetLabelFormat('%.d%%');
			// Setup X-axis labels
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetLabelAngle(45);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			// Setup graph title ands fonts
			$graph->tabtitle->Set($titulo);
			//Pie de Tabla
			$graph->footer->center->Set('Equipos');
			$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->footer->center->SetColor('darkred');
			// Create a bar pot
			$bplot = new BarPlot($datay);
			$bplot->SetFillGradient("lightsteelblue","darkgreen",GRAD_VER);
			$bplot->SetWidth(0.5);
			//Obtener el Valor Minimo a Graficar
			$valorMinimo=min($dispPorGrafica);
			
			$graph->xscale->SetAutoMin(0);
			
			//Si el valor minimo es Mayor a 10, realizar la rutina que obtiene el porcentaje a ampliar la grafica
			if ($valorMinimo>10){
				//Obtener el Valor Maximo a graficar
				$valorMaximo=max($dispPorGrafica);
				//Restar a 100 el valor Máximo
				$valorGrace=100-$valorMaximo;
				//Obtener el valor de "Gracia" para ajustarlo ->"Gracia" se refiere al porcentaje dejado entre el valor maximo a graficar y el Alto de la Grafica
				$valorGrace=($valorGrace*100)/$valorMaximo;
				//Asignar el porcentaje de Gracia en valor Entero
				$graph->yaxis->scale->SetGrace(intval($valorGrace));
			}
			else{
				//Esta propiedad, da como maximo 100 en el Eje Y, se usa para datos menores a 10 solamente, ya que de lo contrario no genera el punto de partida como 0
				$bplot->SetYBase(100);
			}
			//Setup the values that are displayed on top of each bar
			$bplot->value->Show();
			$bplot->value->SetFormat('%.2f%%');
			//Posicionar el valor de la Barra en el Punto Maximo de la misma
			$bplot->SetValuePos('max');
			// Must use TTF fonts if we want text at an arbitrary angle
			$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
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
				<div align='center' id='tabla-graficas' class='borde_seccion2' width='100%' align="center">
				<img src='images/tabla.png' id="img-grafica" title='Ver Detalle a Modo Tabla' style='cursor:pointer' onclick="alternarVista(1);"/>
				<a href='<?php echo $grafica;?>' rel='lightbox[repDisponibilidad]' title='Gr&aacute;fica del Reporte de Disponibilidad'>
					<img id="grafica1" width='100%' height='93%' border='0' src='<?php echo $grafica;?>' title='Gr&aacute;fica del Reporte de Disponibilidad'/>
				</a>
				</div>
				<?php
			}
			//Agregar las siguientes graficas al DIV secundario
			else{
				?>	
					<div id="imagenes" style="visibility:hidden;width:1px;height:1px;overflow:hidden">
					<a href='<?php echo $grafica;?>' rel='lightbox[repDisponibilidad]' title='Gr&aacute;fica del Reporte de Disponibilidad'>
						<img width="2%" height="2%" border="0" src="<?php echo $grafica;?>" title="Gr&aacute;fica del Reporte de Disponibilidad"/>
					</a>
					</div>
				<?php
			}
		}while($cont<$ciclos);
	}//Cierre graficaEntradas($fechas,$costos,$titulo)
	
	/*Funcion que obtiene el nombre del Mes segun el Numero*/
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
	}//Fin de function nombreMes($mes)
	
	/*Funcion que calcula las Horas de Servicio segun los parametros especificados*/
	function calcularHorasServicio($fechaI,$fechaF,$familia,$equipo,$opc){
		//Conecta a la BD de Mantenimiento
		$conn=conecta("bd_mantenimiento");
		//Arreglo que contendra los resultados tanto de Hrs de Servicio como de los Equipos
		$resConsulta=array();
		//Arreglo que almacenara el numero de Horas de Servicio en caso que se encuentren resultados
		$hrs_servicio=array();
		//Arreglo que almacenara los Equipos
		$equipos=array();
		//Verificar la opcion que viene seleccionada
		switch($opc){
			case 1://Horas Servicio por Equipo
				//Sentencia SQL
				$sql_stm="SELECT SUM(hrs_efectivas) AS hrs_servicio FROM horometro_odometro WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND equipos_id_equipo='$equipo'";
				//Ejecutar la sentencia SQL
				$rs=mysql_query($sql_stm);
				//Extraer los datos de la consulta
				if($datos=mysql_fetch_array($rs)){
					//Si las Horas de Servicio son diferente de NULL, extraer los datos
					if($datos["hrs_servicio"]!=NULL){
						//Extraer los datos Obtenidos
						do{
							$equipos[]=$equipo;
							$hrs_servicio[]=$datos["hrs_servicio"];
						}while($datos=mysql_fetch_array($rs));
						$resConsulta=array("equipos"=>$equipos,"horas"=>$hrs_servicio);
					}
				}
			break;
			case 2://Horas Servicio por Familia
				//Sentencia SQL
				$sql_stm="SELECT equipos_id_equipo,SUM(hrs_efectivas) AS hrs_servicio FROM horometro_odometro WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND 
				equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='CONCRETO' AND familia='$familia' AND metrica='HOROMETRO' AND equipos_id_equipo!='') GROUP BY equipos_id_equipo";
				//Ejecutar la sentencia SQL
				$rs=mysql_query($sql_stm);
				//Extraer los datos de la consulta
				if($datos=mysql_fetch_array($rs)){
					//Extraer los datos Obtenidos
					do{
						$equipos[]=$datos["equipos_id_equipo"];
						$hrs_servicio[]=$datos["hrs_servicio"];
					}while($datos=mysql_fetch_array($rs));
					$resConsulta=array("equipos"=>$equipos,"horas"=>$hrs_servicio);
				}
			break;
			case 3://Horas Servicio por Fecha
				//Sentencia SQL
				$sql_stm="SELECT equipos_id_equipo,SUM(hrs_efectivas) AS hrs_servicio FROM horometro_odometro WHERE fecha BETWEEN '$fechaI' AND '$fechaF' 
						AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='CONCRETO' AND metrica='HOROMETRO' AND equipos_id_equipo!='') GROUP BY equipos_id_equipo";
				//Ejecutar la sentencia SQL
				$rs=mysql_query($sql_stm);
				//Extraer los datos de la consulta
				if($datos=mysql_fetch_array($rs)){
					//Extraer los datos Obtenidos
					do{
						$equipos[]=$datos["equipos_id_equipo"];
						$hrs_servicio[]=$datos["hrs_servicio"];
					}while($datos=mysql_fetch_array($rs));
					$resConsulta=array("equipos"=>$equipos,"horas"=>$hrs_servicio);
				}
			break;
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
		//Retornar el valor que haya tomado hrs_servicio
		return $resConsulta;
	}//Fin de calcularHorasServicio($fechaI,$fechaF,$area,$equipo,$opc)
	
	function tablaDisponibilidad($equipos,$disponibilidad,$hrsMtto,$fechaI,$fechaF,$titulo){
		$tabla="<table cellpadding='5' id='tabla-resultados' width='70%'><caption class='titulo_etiqueta'>$titulo</caption><tr><td class='nombres_columnas'>EQUIPO</td><td class='nombres_columnas'>HORAS TRABAJADAS</td><td class='nombres_columnas'>HORAS MANTENIMIENTO</td><td class='nombres_columnas'>DISPONIBILIDAD</td></tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		$costo_total =0;	
		foreach($equipos as $ind=>$value){
			//Recuperar las Horas de Servicio
			$hrsServicio=calcularHorasServicio($fechaI,$fechaF,"",$value,1);
			if(count($hrsServicio)==0)
				$horas=0;
			else{
				$horas=$hrsServicio["horas"][0];
			}
			if($disponibilidad[$ind]<=0)
				$etiqueta="<label class='msje_incorrecto'>";
			if($disponibilidad[$ind]>0 && $disponibilidad[$ind]<85)
				$etiqueta="<label>";
			if($disponibilidad[$ind]>=85)
				$etiqueta="<label class='msje_correcto'>";
			$tabla.="<tr><td class='$nom_clase'>$value</td><td class='$nom_clase'>".number_format($horas,2,".",",")." HRS</td><td class='$nom_clase'>".number_format($hrsMtto[$ind],2,".",",")." HRS</td><td class='$nom_clase'>$etiqueta$disponibilidad[$ind]%</label></td></tr>";	
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
		}
		$tabla.="</table>";
		return $tabla;
	}
	
	//Funcion que muestra los Equipos con la posibilidad de seleccionarlos para mostrarlos en los Reportes
	function mostrarEquiposMttoC(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Creamos la sentencia SQL para mostrar los Equipos
		$stm_sql="SELECT id_equipo,nom_equipo,familia,asignado,proveedor FROM equipos WHERE area='CONCRETO' AND estado='ACTIVO' ORDER BY familia,id_equipo";
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar los resultados de la consulta
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Seleccionar los Equipos a Mostrar en el Reporte</caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center' colspan='2'>CLAVE</td>
						<td class='nombres_columnas' align='center' rowspan='2'>NOMBRE EQUIPO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>FAMILIA</td>
						<td class='nombres_columnas' align='center' rowspan='2'>PROVEEDOR</td>
						<td class='nombres_columnas' align='center' rowspan='2'>EQUIPO ASIGNADO A</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='left' colspan='2'>
							<input type='checkbox' name='ckbTodo' id='ckbTodo' onclick='checarTodos(this);'/>Seleccionar Todos
						</td>
					</tr>
					";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>";
				?>
					<td class="nombres_filas" align="center">
						<input type="checkbox" name="ckb_equipo<?php echo $cont;?>" id="ckb_equipo<?php echo $cont;?>" value="<?php echo $datos['id_equipo']; ?>" onclick="desSeleccionar(this)"/>
					</td>
				<?php
				echo "	<td class='nombres_filas' align='center'>$datos[id_equipo]</td>					
						<td class='$nom_clase' align='left'>$datos[nom_equipo]</td>
						<td class='$nom_clase' align='left'>$datos[familia]</td>
						<td class='$nom_clase' align='left'>$datos[proveedor]</td>
						<td class='$nom_clase' align='left'>$datos[asignado]</td>
						";
				echo "	</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "<input type='hidden' name='hdn_cantEquipos' id='hdn_cantEquipos' value='$cont'/>";
			echo "</table>";
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}
	
	//Funcion para Mostrar la Disponibilidad de un Equipo por Fecha y Turno
	function reporteDisponibilidadFecha(){
		//Abrir la conexion con la BD de Mantenimiento
		$conn=conecta("bd_mantenimiento");
		//Obtener las Fechas en formato MySQL
		$fechaI=modFecha($_POST["hdn_fechaI"],3);
		$fechaF=modFecha($_POST["hdn_fechaF"],3);
		//Obtener los dias de Diferencia
		$diasDiff=restarFechas($fechaI,$fechaF);
		//Obtener la cantidad de checkbox escritos
		$cantidad=$_POST["hdn_cantEquipos"];
		//Contador para recorrer el arreglo POST
		$cont=1;
		//Recorrer el arreglo POST para verificar con que equipos realizar la rutina
		do{
			//Verificar que Equipo esta definido
			if(isset($_POST["ckb_equipo$cont"])){
				//Obtener el ID del Equipo
				$equipo=$_POST["ckb_equipo$cont"];
				//Sentencia SQL para verificar si tiene registro en la bitacora de Mantenimiento
				$sql_stm="SELECT id_bitacora,fecha_mtto,turno,horometro,odometro,tiempo_total,comentarios FROM bitacora_mtto 
						WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' AND equipos_id_equipo='$equipo' ORDER BY fecha_mtto,turno";
				//Ejecutar la sentencia SQL
				$rs=mysql_query($sql_stm);
				//Verificar el resultado de la ejecucion de sentencia
				if($datos=mysql_fetch_array($rs)){
					echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
					echo "<caption class='titulo_etiqueta'>Disponibilidad del Equipo <em><u>$equipo</u></em></caption>";
					echo "	<tr>
								<td class='nombres_columnas' align='center'>FECHA</td>
								<td class='nombres_columnas' align='center'>TURNO</td>
								<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
								<td class='nombres_columnas' align='center'>TIEMPO MANTENIMIENTO</td>
								<td class='nombres_columnas' align='center'>% DISPONIBILIDAD</td>
								<td class='nombres_columnas' align='center'>DETALLES BIT&Aacute;CORA</td>
							</tr>";
					//Variable para controlar la cantidad de Dias
					$dias=0;
					//Ciclo para extraer los datos consultados
					do{
						//Sumar a a fecha los dias por cada ciclo
						$fecha=sumarDiasFecha($fechaI,$dias);
						//Contador para control de Turnos
						$contador=1;
						//Nombre de clase
						$nom_clase = "renglon_gris";		
						//Ciclo para controlar los turnos
						do{
							//Verificar y asignar el nombre del Turno
							switch($contador){
								case 1:
									$turno="TURNO DE PRIMERA";
								break;
								case 2:
									$turno="TURNO DE SEGUNDA";
								break;
								case 3:
									$turno="TURNO DE TERCERA";
								break;
							}
							//Si es el primer Turno, los renglones,columnas se deben mostrar diferente
							if($contador==1){
								//Variable con el titulo a asignar en caso que no se pueda calcular la disponibilidad
								$titulo="";
								//Variable para activar o desactivar el boton de consulta de la Bitacora de Mantenimiento
								$ctrl_btn="";
								//Si el turno es igual al de primera y la fecha de Mantenimiento tambien, calcular la disponibilidad
								if($datos["turno"]==$turno && $datos["fecha_mtto"]=="$fecha"){
									//Extraer los comentarios
									$comentarios=$datos["comentarios"];
									//Extraer el tiempo total del mantenimiento
									$tiempoTotal=$datos["tiempo_total"];
									//Calcular la disponibilidad del Equipo en la fecha y turno actuales
									$disponibilidad=calcularDisponibilidadFecha($fecha,$tiempoTotal,$equipo,$turno);
									/************Convertir el Tiempo Total a numero fraccionario*******************/
									//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
									$hora=split(":",$tiempoTotal);
									//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
									$hrs=intval($hora[0]);
									//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
									$min=intval($hora[1]);
									//Obtener el Tiempo Total en cantidad
									$tiempoTotal=round(($hrs+($min/60)),2);
									/************Fin Convertir el Tiempo Total a numero fraccionario****************/
									//Si la disponibilidad es de 0, quiere decir que no hay registro de metrica en esa Fecha y Turno
									if($disponibilidad==0){
										$titulo=" title='No se Puede Calcular la Disponibilidad en el $turno, ya que no hay Registro de Hor&oacute;metro/Od&oacute;metro del Equipo $equipo para ese Turno'";
										$disp="<label class='msje_incorrecto'>$disponibilidad%</label>";
									}
									//Si la disponibilidad es mayor a 0, indicarlo sin estilo especifico
									else
										$disp="<label>$disponibilidad%</label>";
								}
								//Si no es el turno ni la fecha actual, ingresar los datos de las variables directamente
								else{
									$comentarios="NO HAY REGISTROS DE SERVICIOS DE MANTENIMIENTO";
									$tiempoTotal="0.00";
									$disp="<label class='msje_correcto'>100%</label>";
									//Se deshabilita el boton puesto que no hay actividades de Mantenimiento que revisar
									$ctrl_btn=" disabled='disabled'";
								}
								//Dibujar el renglon para el turno de primera con los resultados obtenidos
								echo "	<tr>
											<td class='$nom_clase' align='center' rowspan='3'>".modFecha($fecha,1)."</td>
											<td class='$nom_clase' align='center'>$turno</td>
											<td class='$nom_clase' align='center'>$comentarios</td>
											<td class='$nom_clase' align='center'>$tiempoTotal HRS</td>
											<td class='$nom_clase' align='center'$titulo>$disp</td>";
											?>
											<td class="<?php echo $nom_clase?>" align="center">
												<input type="button" name="btn_verActividades<?php echo $cont.$contador?>" id="btn_verActividades<?php echo $equipo.$fecha.$contador?>" 
												class="botones" value="Ver Detalle" onMouseOver="window.estatus='';return true;" title="Ver Detalle del Equipo <?php echo $equipo;?>" 
												onClick="javascript:window.open('verDetalleBitacora.php?id_bitacora=<?php echo $datos['id_bitacora'];?>&btn=btn_verActividades<?php echo $equipo.$fecha.$contador?>',
												'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled='true'"
												<?php echo $ctrl_btn;?>/>
											</td>
											<?php
								echo "	</tr>";
								//Verificar que el boton deshabilitar este vacio, de ser asi encontro resultados
								if($ctrl_btn=="")
									//Si encontro resultados, pasar al siguiente Registro
									$datos=mysql_fetch_array($rs);
							}
							//Verificar que el turno sea el segundo o tercer mediante el contador
							if($contador>1){
								//Variable con el titulo a asignar en caso que no se pueda calcular la disponibilidad
								$titulo="";
								//Variable para activar o desactivar el boton de consulta de la Bitacora de Mantenimiento
								$ctrl_btn="";
								//Si el turno es igual al de primera y la fecha de Mantenimiento tambien, calcular la disponibilidad
								if($datos["turno"]==$turno && $datos["fecha_mtto"]=="$fecha"){
									//Extraer los comentarios
									$comentarios=$datos["comentarios"];
									//Extraer el tiempo total del mantenimiento
									$tiempoTotal=$datos["tiempo_total"];
									//Calcular la disponibilidad del Equipo en la fecha y turno actuales
									$disponibilidad=calcularDisponibilidadFecha($fecha,$tiempoTotal,$equipo,$turno);
									/************Convertir el Tiempo Total a numero fraccionario*******************/
									//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
									$hora=split(":",$tiempoTotal);
									//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
									$hrs=intval($hora[0]);
									//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
									$min=intval($hora[1]);
									//Obtener el Tiempo Total en cantidad
									$tiempoTotal=round(($hrs+($min/60)),2);
									/************Fin Convertir el Tiempo Total a numero fraccionario****************/
									//Si la disponibilidad es de 0, quiere decir que no hay registro de metrica en esa Fecha y Turno
									if($disponibilidad==0){
										$titulo=" title='No se Puede Calcular la Disponibilidad en el $turno, ya que no hay Registro de Hor&oacute;metro/Od&oacute;metro del Equipo $equipo para ese Turno'";
										$disp="<label class='msje_incorrecto'>$disponibilidad%</label>";
									}
									//Si la disponibilidad es mayor a 0, indicarlo sin estilo especifico
									else
										$disp="<label>$disponibilidad%</label>";
								}
								//Si no es el turno ni la fecha actual, ingresar los datos de las variables directamente
								else{
									$comentarios="NO HAY REGISTROS DE SERVICIOS DE MANTENIMIENTO";
									$tiempoTotal="0.00";
									$disp="<label class='msje_correcto'>100%</label>";
									//Se deshabilita el boton puesto que no hay actividades de Mantenimiento que revisar
									$ctrl_btn=" disabled='disabled'";
								}
								//Dibujar el renglon para los turnos de segunda o tercera segun corresponda con los resultados obtenidos
								echo "	<tr>
											<td class='$nom_clase' align='center'>$turno</td>
											<td class='$nom_clase' align='center'>$comentarios</td>
											<td class='$nom_clase' align='center'>$tiempoTotal HRS</td>
											<td class='$nom_clase' align='center'$titulo>$disp</td>";
											?>
											<td class="<?php echo $nom_clase?>" align="center">
												<input type="button" name="btn_verActividades<?php echo $equipo.$fecha.$contador?>" id="btn_verActividades<?php echo $equipo.$fecha.$contador?>" 
												class="botones" value="Ver Detalle" onMouseOver="window.estatus='';return true;" title="Ver Detalle del Equipo <?php echo $equipo;?>" 
												onClick="javascript:window.open('verDetalleBitacora.php?id_bitacora=<?php echo $datos['id_bitacora'];?>&btn=btn_verActividades<?php echo $equipo.$fecha.$contador?>',
												'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled='true'"
												<?php echo $ctrl_btn;?>/>
											</td>
											<?php
								echo "	</tr>";
								//Verificar que el boton deshabilitar este vacio, de ser asi encontro resultados
								if($ctrl_btn=="")
									//Si encontro resultados, pasar al siguiente Registro
									$datos=mysql_fetch_array($rs);
							}
							//Incrementar el contador para el control de turnos
							$contador++;
							//En baseo al contador, indicar que clase debe tener el renglon
							if($contador%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						}while($contador<=3);//Ciclo por Turnos
						//Incrementar el contador para pasar al siguiente dia
						$dias++;
					}while($dias<=$diasDiff);//Ciclo por dias entre las fechas
					echo "</table><br>";//Cerrar la tabla y dar un "enter"
				}
				//En caso de No Encontrar resultados para el Equipo seleccionado, se pasa la Disponibilidad al 100%
				else{
					echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
					echo "<caption class='titulo_etiqueta'>Disponibilidad del Equipo <em><u>$equipo</u></em></caption>";
					echo "	<tr>
								<td class='nombres_columnas' align='center'>FECHA</td>
								<td class='nombres_columnas' align='center'>TURNO</td>
								<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
								<td class='nombres_columnas' align='center'>TIEMPO MANTENIMIENTO</td>
								<td class='nombres_columnas' align='center'>% DISPONIBILIDAD</td>
								<td class='nombres_columnas' align='center'>DETALLES BIT&Aacute;CORA</td>
							</tr>";
					//Variable para controlar la cantidad de Dias
					$dias=0;
					//Ciclo para controlar los dias entre las Fechas
					do{
						//Sumar los dias a la fecha de Inicio
						$fecha=sumarDiasFecha($fechaI,$dias);
						//Contador para el manejo de Turnos
						$contador=1;
						//Clase inicial para el renglon
						$nom_clase = "renglon_gris";
						//Ciclo para controlar los Turnos
						do{
							//Verificar el Turno actual
							switch($contador){
								case 1:
									$turno="TURNO DE PRIMERA";
								break;
								case 2:
									$turno="TURNO DE SEGUNDA";
								break;
								case 3:
									$turno="TURNO DE TERCERA";
								break;
							}
							//Ciclo de dibujo para el renglon del turno de primera
							if($contador==1){
								echo "	<tr>
											<td class='$nom_clase' align='center' rowspan='3'>".modFecha($fecha,1)."</td>
											<td class='$nom_clase' align='center'>$turno</td>
											<td class='$nom_clase' align='center'>NO HAY REGISTROS DE SERVICIOS DE MANTENIMIENTO</td>
											<td class='$nom_clase' align='center'>0 HRS</td>
											<td class='$nom_clase' align='center'><label class='msje_correcto'>100%</label></td>";
											?>
											<td class="<?php echo $nom_clase?>" align="center">
												<input type="button" name="btn_verActividades" id="btn_verActividades" class="botones" value="Ver Detalle" onMouseOver="window.estatus='';return true;" 
												title="Ver Detalle del Equipo <?php echo $equipo;?>" disabled="disabled"/>
											</td>
											<?php
								echo "	</tr>";
							}
							//Ciclo de dibujo para los renglones del turno de segunda y tercera
							if($contador>1){
								echo "	<tr>
											<td class='$nom_clase' align='center'>$turno</td>
											<td class='$nom_clase' align='center'>NO HAY REGISTROS DE SERVICIOS DE MANTENIMIENTO</td>
											<td class='$nom_clase' align='center'>0 HRS</td>
											<td class='$nom_clase' align='center'><label class='msje_correcto'>100%</label></td>";
											?>
											<td class="<?php echo $nom_clase?>" align="center">
												<input type="button" name="btn_verActividades" id="btn_verActividades" class="botones" value="Ver Detalle" onMouseOver="window.estatus='';return true;" 
												title="Ver Detalle del Equipo <?php echo $equipo;?>" disabled="disabled"/>
											</td>
											<?php
								echo "	</tr>";
							}
							//Incrementar el contador de Turnos
							$contador++;
							//Verificar que clase le toca al Renglon
							if($contador%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						}while($contador<=3);//Ciclo para turnos
						$dias++;//Incrementar los dias
					}while($dias<=$diasDiff);//Ciclo para fechas
					echo "</table><br>";//Cerrar la tabla y dar un "enter"
				}//Cierre del ELSE donde no hubo datos segun la consulta
			}//Cierre del if(isset($_POST["ckb_equipo$cont"]))
			$cont++;//Incrementar el contador para el manejo de Equipos en el POST
		}while($cont<$cantidad);//Ciclo de Equipos en el POST
		//Cerrar la conexion con la BD de Mantenimiento
		mysql_close($conn);
	}//Cierre de reporteDisponibilidadFecha()
	
	//Funcion para calcular la Disponibilidad de un Equipo pasando como parametros
	function calcularDisponibilidadFecha($fecha,$tiempoTotal,$equipo,$turno){
		//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
		$hora=split(":",$tiempoTotal);
		//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
		$hrs=intval($hora[0]);
		//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
		$min=intval($hora[1]);
		//Obtener el Tiempo Total en cantidad
		$tiempoMtto=round(($hrs+($min/60)),2);
		//Sentencia SQL
		$sql_stm="SELECT SUM(hrs_efectivas) AS hrs_servicio FROM horometro_odometro WHERE fecha='$fecha' AND equipos_id_equipo='$equipo' AND turno='$turno'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql_stm);
		//Extraer los datos de la consulta
		if($datos=mysql_fetch_array($rs)){
			//Si las Horas de Servicio son diferente de NULL, extraer los datos
			if($datos["hrs_servicio"]!=NULL){
				//Extraer los datos Obtenidos
				do{
					$hrs_servicio=$datos["hrs_servicio"];
				}while($datos=mysql_fetch_array($rs));
			}
			else
				$hrs_servicio=0;
		}
		//Calcular la disponibilidad siempre y cuando se pueda realizar, de lo contrario regresar 0,
		if($hrs_servicio!=0)
			$disponibilidad=round((100-(($tiempoMtto*100)/$hrs_servicio)),2);
		else
			$disponibilidad=$hrs_servicio;
		//Regresar el valor de la Disponibilidad
		return $disponibilidad;
	}
?>
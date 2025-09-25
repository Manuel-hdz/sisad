<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 26/Marzo/2012
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Sistemas en Cantidad 
	  **/

	/*Funcion que recopila los datos para dibujar la grafica*/
	function reporteSistemas(){
		//Variable que contedra la grafica
		$grafica="";
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
		//Extraer y verificar el Equipo, si tiene valor diferente de vacio, mostrar todo el Reporte
		$equipo=$_POST["cmb_equipo"];
		//Obtener el nombre del Mes
		$mes=nombreMes($_POST["cmb_meses"]);
		//Conectarse a la BD
		$conn=conecta("bd_mantenimiento");
		if($equipo==""){
			//Ensamblar el titulo de la grafica
			$titulo="Reporte Estadístico de $mes $_POST[cmb_anios] \nÁrea: '$area'";
			//Sentencia SQL
			$sql_stm="SELECT sistema,COUNT(sistema) AS cantServicios FROM actividades_correctivas WHERE 
					bitacora_mtto_id_bitacora=ANY(SELECT id_bitacora FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' AND 
					equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='$area')) GROUP BY sistema ORDER BY cantServicios DESC,sistema";
			//Sentencia SQL para servicios Externos
			$sql_stmExt="SELECT COUNT(sistema) AS cantServiciosExt FROM actividades_realizadas WHERE 
					orden_servicios_externos_id_orden=ANY(SELECT id_orden FROM orden_servicios_externos WHERE fecha_entrega BETWEEN '$fechaI' AND '$fechaF' AND 
					id_orden LIKE 'SEC%')";
			//Ejecutar y extraer los resultados de la consulta para Servicios Externos
			$cantExt=mysql_fetch_array(mysql_query($sql_stmExt));
		}
		else{
			//Ensamblar el titulo de la grafica
			$titulo="Reporte Estadístico del Equipo: $equipo en $mes $_POST[cmb_anios] \nÁrea: '$area'";
			//Sentencia SQL
			$sql_stm="SELECT sistema,COUNT(sistema) AS cantServicios FROM actividades_correctivas WHERE 
					bitacora_mtto_id_bitacora=ANY(SELECT id_bitacora FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' AND 
					equipos_id_equipo='$equipo') GROUP BY sistema ORDER BY cantServicios DESC,sistema";
			//Sentencia SQL para servicios Externos
			$sql_stmExt="SELECT COUNT(sistema) AS cantServiciosExt FROM actividades_realizadas WHERE 
					orden_servicios_externos_id_orden=ANY(SELECT id_orden FROM orden_servicios_externos WHERE fecha_entrega BETWEEN '$fechaI' AND '$fechaF' AND 
					id_orden LIKE 'SEC%') AND equipo='$equipo'";
			//Ejecutar y extraer los resultados de la consulta para Servicios Externos
			$cantExt=mysql_fetch_array(mysql_query($sql_stmExt));
		}
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			$cantServicios=array();
			$servicios=array();
			$totalServicios=0;
			do{
				//Acumular los servicios para obtener la cantidad de ellos
				$totalServicios+=$datos["cantServicios"];
				//Recuperar los servicios para calcular el porcentaje posteriormente
				$cantServicios[]=$datos["cantServicios"];
				//Recuperar las etiquetas
				$servicios[]=$datos["sistema"]."\n".$datos["cantServicios"]." SERVICIO(S)";
			}while($datos=mysql_fetch_array($rs));
			/*******INICIO - SERVICIOS EXTERNOS*************/
			//Verificar si se Encontraron Servicios Externos
			if($cantExt[0]!=0){
				//Agregar al total de Servicios los servicios Externos
				$totalServicios+=$cantExt[0];
				//Ingresar la cantidad de Servicios Externos en el Arreglo
				$cantServicios[]=$cantExt[0];
				//Recuperar las etiquetas
				$servicios[]="EXTERNOS"."\n".$cantExt[0]." SERVICIOS";
			}
			/*******FIN - SERVICIOS EXTERNOS*************/
			//Recorrer el arreglo de servicios para calcular su valor porcentual
			$cont=0;
			do{
				//Reasignar a la posicion actual el valor porcentual calculado
				$cantServicios[$cont]=($cantServicios[$cont]*100)/$totalServicios;
				$cont++;
			}while($cont<(count($cantServicios)));
			//Dibujar la grafica
			$grafica=graficaSistemas($cantServicios,$servicios,$titulo);
			mysql_close($conn);
		}
		else
			mysql_close($conn);
		
		return $grafica;
	}//Fin function reporteSistemas()
	
	//Grafica que es incluida en el reporte de Entradas al almacen
	function graficaSistemas($cantServicios,$servicios,$titulo){	
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');	
		//Obtener la cantidad de Registros
		$cantRes=count($cantServicios);
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
			//Declarar el arreglo de costos Entrada por cada grafica
			$serviciosPorGrafica=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$serviciosPorGrafica[]=$cantServicios[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$servicios[$contPorGrafica];
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			/**********************/
			$datay = $serviciosPorGrafica;
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
			$graph->footer->center->Set('Sistemas');
			$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->footer->center->SetColor('darkred');
			// Create a bar pot
			$bplot = new BarPlot($datay);
			$bplot->SetFillGradient("lightsteelblue","darkgreen",GRAD_VER);
			$bplot->SetWidth(0.5);
			//Obtener el Valor Minimo a Graficar
			$valorMinimo=min($serviciosPorGrafica);
			//Si el valor minimo es Mayor a 10, realizar la rutina que obtiene el porcentaje a ampliar la grafica
			if ($valorMinimo>10){
				//Obtener el Valor Maximo a graficar
				$valorMaximo=max($serviciosPorGrafica);
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
			// Must use TTF fonts if we want text at an arbitrary angle
			$bplot->value->SetFont(FF_ARIAL,FS_BOLD);
			$bplot->value->SetAngle(45);
			//$bplot->value->SetFormatCallback('separator1000');
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
				<a href='<?php echo $grafica;?>' rel='lightbox[repMensual]' title='Gr&aacute;fica del Reporte Estad&iacute;stico de Fallas'>
					<img width="100%" height="100%" border="0" src="<?php echo $grafica;?>" title="Gr&aacute;fica del Reporte Estad&iacute;stico de Fallas"/>
				</a>
				</div>
				<?php
			}
			//Agregar las siguientes graficas al DIV secundario
			else{
				?>	
					<div id="imagenes" style="visibility:hidden;width:1px;height:1px;overflow:hidden">
					<a href='<?php echo $grafica;?>' rel='lightbox[repMensual]' title='Gr&aacute;fica del Reporte Estad&iacute;stico de Fallas'>
						<img width="2%" height="2%" border="0" src="<?php echo $grafica;?>" title="Gr&aacute;fica del Reporte Estad&iacute;stico de Fallas"/>
					</a>
					</div>
				<?php
			}
		}while($cont<$ciclos);
	}//Cierre graficaEntradas($fechas,$costos,$titulo)
	
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
	
	//Funcion que muestra el Reporte Estadistico de fallas con opcion a exportarse por familia y por Equipo
	function reporteFallas(){
		/***********************************************
		//Abrir la conexion con la BD
		$conn=conecta("bd_mantenimiento");
		//Obtener las fechas de inicio y de fin
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Obtener la Familia
		$familia=$_POST["cmb_familia"];
		//Obtener el Equipo, Este puede ser vacio
		$equipo=$_POST["cmb_equipo2"];
		//Especificar el Area de Concreto
		$area="CONCRETO";
		if($equipo==""){
			//Ensamblar el titulo
			$msg="Reporte Estadístico de Fallas del $_POST[txt_fechaIni] al $_POST[txt_fechaFin] seg&uacute;n la Familia: '$familia'";
			$sql_stm="SELECT equipos_id_equipo,sistema,COUNT(sistema) AS cantServicios FROM actividades_correctivas JOIN bitacora_mtto 
					ON id_bitacora=bitacora_mtto_id_bitacora WHERE bitacora_mtto_id_bitacora=ANY(SELECT id_bitacora FROM bitacora_mtto 
					WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='$area' AND familia='$familia')) 
					GROUP BY equipos_id_equipo,sistema ORDER BY equipos_id_equipo,sistema,cantServicios DESC";
		}
		else{
			//Ensamblar el titulo
			$msg="Reporte Estadístico de Fallas del $_POST[txt_fechaIni] al $_POST[txt_fechaFin] seg&uacute;n el Equipo: '$equipo'";
			$sql_stm="SELECT sistema,COUNT(sistema) AS cantServicios FROM actividades_correctivas WHERE 
					bitacora_mtto_id_bitacora=ANY(SELECT id_bitacora FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' AND 
					equipos_id_equipo='$equipo') GROUP BY sistema ORDER BY cantServicios DESC,sistema";
		}
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>FAMILIA</td>
					<td class='nombres_columnas' align='center'>EQUIPO</td>
					<td class='nombres_columnas' align='center'>TIPO SERVICIO</td>
					<td class='nombres_columnas' align='center'>CANTIDAD DE SERVICIOS</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				if(isset($datos["equipos_id_equipo"]) && $equipo!=$datos["equipos_id_equipo"] && $cont>1){
					//Sentencia SQL para servicios Externos
					echo $sql_stmExt="SELECT COUNT(sistema) AS cantServiciosExt FROM actividades_realizadas WHERE 
							orden_servicios_externos_id_orden=ANY(SELECT id_orden FROM orden_servicios_externos WHERE fecha_entrega BETWEEN '$fechaI' AND '$fechaF' AND 
							id_orden LIKE 'SEC%' AND equipo='$equipo')";
					//Ejecutar y extraer los resultados de la consulta para Servicios Externos
					$cantExt=mysql_fetch_array(mysql_query($sql_stmExt));
					echo "
					<tr>
						<td class='$nom_clase'>$familia</td>
						<td class='$nom_clase'>$equipo</td>
						<td class='$nom_clase'>EXTERNO</td>
						<td class='$nom_clase'>$cantExt[0]</td>
					</tr>";
				}
				if(isset($datos["equipos_id_equipo"]))
					$equipo=$datos["equipos_id_equipo"];
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase'>$familia</td>
						<td class='$nom_clase'>$equipo</td>
						<td class='$nom_clase'>$datos[sistema]</td>
						<td class='$nom_clase'>$datos[cantServicios]</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			//Sentencia SQL para servicios Externos del ultimo Equipo encontrado
			$sql_stmExt="SELECT COUNT(sistema) AS cantServiciosExt FROM actividades_realizadas WHERE 
					orden_servicios_externos_id_orden=ANY(SELECT id_orden FROM orden_servicios_externos WHERE fecha_entrega BETWEEN '$fechaI' AND '$fechaF' AND 
					id_orden LIKE 'SEC%' AND equipo='$equipo')";
			//Ejecutar y extraer los resultados de la consulta para Servicios Externos
			$cantExt=mysql_fetch_array(mysql_query($sql_stmExt));
			echo "
				<tr>
					<td class='$nom_clase'>$familia</td>
					<td class='$nom_clase'>$equipo</td>
					<td class='$nom_clase'>EXTERNO</td>
					<td class='$nom_clase'>$cantExt[0]</td>
				</tr>";
			echo "</table>";
		}
		//Cerrar la conexion con la BD de Mtto
		mysql_close($conn);
	}//Fin de function reporteFallas()
	***********************************************/
		//Abrir la conexion con la BD
		$conn=conecta("bd_mantenimiento");
		//Obtener las fechas de inicio y de fin
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Obtener la Familia
		$familia=$_POST["cmb_familia"];
		//Obtener el Equipo, Este puede ser vacio
		$equipo=$_POST["cmb_equipo2"];
		//Especificar el Area de Concreto
		$area="CONCRETO";
		//Arreglo de Equipos
		$equipos=array();
		//Verificar si viene definido o no un Equipo
		if($equipo==""){
			//Ensamblar el titulo
			$msg="Reporte Estadístico de Servicios del $_POST[txt_fechaIni] al $_POST[txt_fechaFin] seg&uacute;n la Familia: '$familia'";
			//Extraer todos los equipos de concreto de dicha familia
			$sql_stm="SELECT id_equipo FROM equipos WHERE area='CONCRETO' AND familia='$familia' AND estado='ACTIVO'";
			//Ejecutar la sentencia
			$rs=mysql_query($sql_stm);
			if($datos=mysql_fetch_array($rs)){
				do{
					//Guardar los equipos en el arreglo de Equipos
					$equipos[]=$datos["id_equipo"];
				}while($datos=mysql_fetch_array($rs));
			}
		}
		else{
			//Ensamblar el titulo
			$msg="Reporte Estadístico de Servicios del $_POST[txt_fechaIni] al $_POST[txt_fechaFin] seg&uacute;n el Equipo: '$equipo'";
			//Guardar el Equipo en el arreglo
			$equipos[]=$equipo;
		}
		//Desplegar los encabezados de la consulta en una tabla
		echo "
			<table cellpadding='5' width='100%' id='tabla-rpt-fallas'>
			<tr>
				<td class='titulo_etiqueta' align='center' colspan='12'>$msg</td>
			</tr>
			<tr>
				<td align='center' colspan='7'></td>
			</tr>
			<tr>
				<td width='1%'>&nbsp;</td>
				<td width='6%' class='nombres_columnas' align='center'>EQUIPO</td>
				<td width='8%' class='nombres_columnas' align='center'>TIPO SERVICIO</td>
				<td width='8%' class='nombres_columnas' align='center'>FECHA</td>
				<td width='7%' class='nombres_columnas' align='center'>SISTEMA</td>
				<td width='8%' class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
				<td width='69%' class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
				<td width='69%' class='nombres_columnas' align='center'>HOR&Oacute;METRO</td>
				<td width='69%' class='nombres_columnas' align='center'>OD&Oacute;METRO</td>
				<td width='69%' class='nombres_columnas' align='center'>TIEMPO</td>
				<td width='69%' class='nombres_columnas' align='center'>MEC&Aacute;NICO(S)</td>
				<td width='1%'>&nbsp;</td>
			</tr>";
		//Declarar la clase
		$nom_clase = "renglon_gris";
		//Contador para  el control de la clase
		$cont = 1;
		//Variable para contar los servicios internos y externos por equipo
		$cantServ=0;
		//Recorrer el arreglo de Equipos para extraer los Servicios a los Sistemas y los servicios Externos por cada Equipo
		foreach($equipos as $ind=>$equipo){
			//Servicios internos
			$sql_stm = "SELECT sistema, aplicacion, descripcion, horometro, odometro, tiempo_total, fecha_mtto, GROUP_CONCAT( nom_mecanico SEPARATOR  ', ') AS mecanicos
						FROM actividades_correctivas
						JOIN bitacora_mtto ON actividades_correctivas.bitacora_mtto_id_bitacora = id_bitacora
						JOIN mecanicos
						USING ( bitacora_mtto_id_bitacora ) 
						WHERE bitacora_mtto_id_bitacora = ANY(
							SELECT id_bitacora
							FROM bitacora_mtto
							WHERE fecha_mtto
							BETWEEN  '$fechaI'
							AND  '$fechaF'
							AND equipos_id_equipo =  '$equipo'
						)
						GROUP BY sistema, aplicacion, descripcion, bitacora_mtto_id_bitacora
						ORDER BY sistema,aplicacion,descripcion";
			$rs=mysql_query($sql_stm);
			//Acumular la cantidad de servicios
			$cantServ+=mysql_num_rows($rs);
			if($servInt=mysql_fetch_array($rs)){
				do{
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>
							<td>&nbsp;</td>
							<td class='$nom_clase'>$equipo</td>
							<td class='$nom_clase'>INTERNO</td>
							<td class='$nom_clase'>".modFecha($servInt['fecha_mtto'],1)."</td>
							<td class='$nom_clase'>$servInt[sistema]</td>
							<td class='$nom_clase'>$servInt[aplicacion]</td>
							<td class='$nom_clase'>$servInt[descripcion]</td>
							<td class='$nom_clase' align='center'>$servInt[horometro]&nbsp;hrs.</td>
							<td class='$nom_clase' align='center'>$servInt[odometro]&nbsp;kms.</td>
							<td class='$nom_clase'>$servInt[tiempo_total]&nbsp;hrs.</td>
							<td class='$nom_clase'>$servInt[mecanicos]</td>
							<td>&nbsp;</td>
						</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($servInt=mysql_fetch_array($rs));
			}
			//Servicios Externos
			$sql_stmExt =  "SELECT orden_servicios_externos_id_orden, sistema, aplicacion, descripcion, fecha_creacion
							FROM actividades_realizadas
							JOIN orden_servicios_externos ON orden_servicios_externos_id_orden = id_orden
							WHERE orden_servicios_externos_id_orden = ANY(
								SELECT id_orden
								FROM orden_servicios_externos
								WHERE fecha_entrega
								BETWEEN  '$fechaI'
								AND  '$fechaF'
								AND id_orden LIKE  'SEC%'
								AND equipo =  '$equipo'
							)
							ORDER BY sistema, aplicacion, descripcion";
			//Ejecutar y extraer los resultados de la consulta para Servicios Externos
			$rs=mysql_query($sql_stmExt);
			//Acumular la cantidad de servicios
			$cantServ+=mysql_num_rows($rs);
			if($servExt=mysql_fetch_array($rs)){
				do{
					echo "
						<tr>
							<td>&nbsp;</td>
							<td class='$nom_clase'>$equipo</td>
							<td class='$nom_clase'>EXTERNO</td>
							<td class='$nom_clase'>".modFecha($servExt['fecha_creacion'],1)."</td>
							<td class='$nom_clase'>$servExt[sistema]</td>
							<td class='$nom_clase'>$servExt[aplicacion]</td>
							<td class='$nom_clase'>$servExt[descripcion]</td>
							<td class='$nom_clase' align='center'>N/A</td>
							<td class='$nom_clase' align='center'>N/A</td>
							<td class='$nom_clase' align='center'>N/A</td>
							<td class='$nom_clase' align='center'>N/A</td>
							<td>&nbsp;</td>
						</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($servExt=mysql_fetch_array($rs));
			}
		}
		echo "</table>";
		//Cerrar la conexion con la BD de Mtto
		mysql_close($conn);
		if($cantServ==0){
			echo "<meta http-equiv='refresh' content='0;url=frm_reporteEstadistico.php?noResults'>";
		}
	}//Fin de function reporteFallas()
?>?>
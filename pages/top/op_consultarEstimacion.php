<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 25/Mayo/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de consultar estimación en la BD
	**/
	
	//Funcion que se encarga de desplegar las estimaciones en el rango de fechas
	function mostrarEstimaciones(){
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
		//Si viene sbt_consultarObra la buqueda de los traspaleos proviene de seleccionar una obra
		if(isset($_POST["sbt_consultarObra"])){ 
			//Crear sentencia SQL
			$sql_stm ="SELECT * ,estimaciones.fecha_registro AS fecha_estimacion 
						FROM estimaciones JOIN obras ON id_obra=obras_id_obra 
						JOIN subcategorias ON subcategorias.id=subcategorias_id
						WHERE tipo_obra='$_POST[cmb_obra]' AND nombre_obra='$_POST[cmb_nombreObra]'
						ORDER BY orden,tipo_obra";	
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Estimaciones de <em><u>  $_POST[cmb_obra]    </u></em> de la Obra <em><u>	$_POST[cmb_nombreObra]  </u></em>";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Estimaci&oacute;n de <em><u>  $_POST[cmb_obra]    
			</u></em> de la Obra <em><u>	$_POST[cmb_nombreObra]  </u></em>";	
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_tipoObra" value="<?php echo $_POST['cmb_obra'] ?>"/>
			<input type="hidden" name="hdn_nombreObra" value="<?php echo $_POST['cmb_nombreObra'] ?>"/>
			<input type="hidden" name="hdn_consultarObra" value="<?php echo $_POST['sbt_consultarObra'] ?>"/><?php
		}
		//Si viene sbt_consultarMes la buqueda de los traspaleos proviene de seleccionar un mes y año
		if(isset($_POST["sbt_consultarMes"])){ 
			//Crear sentencia SQL
			$sql_stm ="SELECT * ,estimaciones.fecha_registro AS fecha_estimacion 
						FROM estimaciones JOIN obras ON id_obra=obras_id_obra 
						JOIN subcategorias ON subcategorias.id=subcategorias_id
						WHERE no_quincena LIKE'% $_POST[cmb_mes] $_POST[cmb_anios]' 
						ORDER BY orden,tipo_obra";	
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Estimaciones del mes de <em><u>  $_POST[cmb_mes]    </u></em> del a&ntilde;o<em><u>	$_POST[cmb_anios]  </u></em>";
			$titulo= "Estimaciones de $_POST[cmb_mes] $_POST[cmb_anios]";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Estimaci&oacute;n del mes de <em><u>  $_POST[cmb_mes]    
			</u></em> del a&ntilde;o<em><u>	$_POST[cmb_anios]  </u></em>";
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_mes" value="<?php echo $_POST['cmb_mes'] ?>"/>
			<input type="hidden" name="hdn_anios" value="<?php echo $_POST['cmb_anios'] ?>"/>
			<input type="hidden" name="hdn_consultarMes" value="<?php echo $_POST['sbt_consultarMes'] ?>"/><?php
		}
		//Si viene sbt_consultarQuincena la buqueda de los traspaleos proviene de seleccionar una quincena de una obra específica
		if(isset($_POST["sbt_consultarQuincena"])){ 			
			//Crear sentencia SQL
			$sql_stm ="SELECT * ,estimaciones.fecha_registro AS fecha_estimacion 
						FROM estimaciones JOIN obras ON id_obra=obras_id_obra
						JOIN subcategorias ON subcategorias.id=subcategorias_id
						WHERE tipo_obra='$_POST[cmb_tipoObra]' AND id_obra='$_POST[cmb_nomObra]' AND no_quincena='$_POST[cmb_numQuincena]' 
						ORDER BY orden,tipo_obra";		
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Estimaciones de <em><u>  $_POST[cmb_tipoObra]    </u></em> de la Obra <em><u>	$_POST[cmb_nomObra]  </u></em> de la Quincena 
			<em><u>	$_POST[cmb_numQuincena]  </u></em>";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Estimacione de <em><u>  $_POST[cmb_tipoObra]    
			</u></em> de la Obra <em><u>	$_POST[cmb_nomObra]  </u></em> de la Quincena <em><u>	$_POST[cmb_numQuincena]  </u></em>";
			// hiddens que permiten que al regresar de ver el detalle nos muestre la consulta previamente realizada?>
			<input type="hidden" name="hdn_tipo_obra" value="<?php echo $_POST['cmb_tipoObra'] ?>"/>
			<input type="hidden" name="hdn_idObra" value="<?php echo $_POST['cmb_nomObra'] ?>"/>
			<input type="hidden" name="hdn_noQuincena" value="<?php echo $_POST['cmb_numQuincena'] ?>"/>
			<input type="hidden" name="hdn_consultarQuincena" value="<?php echo $_POST['sbt_consultarQuincena'] ?>"/><?php
		}
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='1580'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='21%'>CONCEPTO</td>
					<td class='nombres_columnas' align='center' width='7%'>SECCI&Oacute;N</td>
					<td class='nombres_columnas' align='center' width='7%'>UNIDAD</td>
					<td class='nombres_columnas' align='center' width='7%'>CANTIDAD</td>
					<td class='nombres_columnas' align='center' width='7%'>PRECIO/U MN</td>
					<td class='nombres_columnas' align='center' width='7%'>PRECIO/U USD</td>
					<td class='nombres_columnas' align='center' width='7%'>TASA CAMBIO</td>
					<td class='nombres_columnas' align='center' width='7%'>TOTAL MN</td>
					<td class='nombres_columnas' align='center' width='7%'>TOTAL USD</td>
					<td class='nombres_columnas' align='center' width='7%'>IMPORTE TOTAL</td>
					<td class='nombres_columnas' align='center' width='7%'>FECHA REGISTRO</td>
					<td class='nombres_columnas' align='center' width='8%'>NO QUINCENA</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Contadores que nos permiten sumar el total de cada columna	
			$totalMN=0;
			$totalUSD=0;
			$importe=0;
			//Esto se realiza para solo imprimir un solo encabezado del tipo de obra y enseguida del de todos registros
			$tipo_obra= $datos['tipo_obra'];
			//Declarar el Arreglo con los tipos de obra
			$tiposObra[]=$tipo_obra;
			//Variable que acumulara la cantidad por Obra
			$cantXObra=0;
			//Arreglo que contendra las cantidades por Obras
			$cantXTipoObra=array();
			echo "
				<tr>
					<td class='nombres_columnas'>$datos[tipo_obra]</td>
				</tr>";
			do{	
				// Mostrar los totales de cada columna para todos los registros excepto el último
				if($tipo_obra != $datos['tipo_obra']){
					echo"
						<tr>
							<td class='$nom_clase' colspan='6' align='right'></td>
							<td class='nombres_columnas' align='right'>TOTALES</td>
							<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
							<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
							<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
						</tr>";
					$tipo_obra = $datos['tipo_obra'];
					//Agregar el nuevo Tipo de Obra al Arreglo con los tipos de Obras
					$tiposObra[]=$tipo_obra;
					echo "
						<tr>
							<td class='nombres_columnas'>$datos[tipo_obra]</td>
						</tr>";
					//Reiniciar los contadores para empezar la suma con el siguiente tipo de obra	
					$totalMN=0;
					$totalUSD=0;
					$importe=0;
					//Agregar la cantidad por Obra al arreglo correspondiente
					$cantXTipoObra[]=$cantXObra;
					//Resetear la variable de la suma por Obra
					$cantXObra=0;
				}	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>	
						<td class='$nom_clase' align='left'>$datos[nombre_obra]</td>
						<td class='$nom_clase'>$datos[seccion]</td>
						<td class='$nom_clase'>$datos[unidad]</td>					
						<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['pumn_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['puusd_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['t_cambio'],4,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['total_mn'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['total_usd'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['importe'],2,".",",")."</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_estimacion'],1)."</td>
						<td class='$nom_clase'>$datos[no_quincena]</td>
					</tr>";
					//Realizar la suma por cada registro de los totales
					$totalMN += $datos['total_mn'];
					$totalUSD += $datos['total_usd'];
					$importe += $datos['importe'];
					
					//Incrementar en cantXObra la cantidad
					$cantXObra+=$datos['cantidad'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			// Mostrar los totales de cada columna para el último registro
			echo"
				<tr>
					<td class='$nom_clase' colspan='6' align='right'></td>
					<td class='nombres_columnas' align='right'>TOTALES</td>
					<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
				</tr>";
			echo "</table>";
			//Agregar la ultima cantidad por Obra al arreglo correspondiente
			$cantXTipoObra[]=$cantXObra;
			if(isset($_POST["sbt_consultarMes"])){
				//Recorrer el arreglo para verificar si se toman en cuenta las anclas
				foreach($tiposObra as $ind => $value){
					// Verificar la existencia del patron ANCLA en el arreglo llenado con los datos
					// si se encuentra, quitar la posicion
					// La "i" después del delimitador de patrón indica una búsqueda
					// sin tener en cuenta mayúsculas/minúsculas
					if (preg_match("/ANCLA/i", $value)){
						unset($tiposObra[$ind]);
						unset($cantXTipoObra[$ind]);
					}
				}
				//Reacomodar los indices
				$cantXTipoObra=array_values($cantXTipoObra);
				$tiposObra=array_values($tiposObra);
				return $grafica=dibujarGrafica($cantXTipoObra,$tiposObra,$titulo);
			}
			return 1;
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	function dibujarGrafica($arrDatos,$ejeX,$titulo){
		//Obtener la suma del total en los arreglos de datos
		$total=array_sum($arrDatos);
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		// Create the graph. These two calls are always required
		$graph = new Graph(800,600);    
		$graph->SetScale("textlin");
		$graph->SetShadow();
		$graph->img->SetMargin(100,60,60,100);
		//Recorrer el arreglo para obtener los datos en forma de porcentaje
		foreach($arrDatos as $ind => $value){
			$arrDatos[$ind]=round(($arrDatos[$ind]*100)/$total,2);
		}
		//Calcular el valor de Gracia
		$resto=(100-max($arrDatos));
		$grace=($resto*100)/max($arrDatos);
		$datay=$arrDatos;
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
		$graph->footer->center->Set("Tipos de Obra");
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		// ...y agregarlo a la grafica
		$graph->Add($bplot);
		//Crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= "tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}
	
	function borrarHistorial(){
		 //Esta función elimina los graficos generados durante las consultas y se presione un boton de cancelar
		$h=opendir('tmp');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				unlink('tmp/'.$file);
			}
		}
		closedir($h);
	}
	
	function cargarAniosDisponible(){
		//conectar a bd_topografia
		$conn = conecta('bd_topografia');
		$rs_quincenas = mysql_query("SELECT DISTINCT no_quincena FROM estimaciones");
		$anios = array();
		while($datos_quincenas=mysql_fetch_array($rs_quincenas)){
			$quincena = $datos_quincenas['no_quincena'];
			$anios[] = substr($quincena, -4); 
		}
		$anioUnico = array_unique($anios);?>
		<select name="cmb_anios" id="cmb_anios" class="combo_box">  
            <option value="">Seleccione A&ntilde;o</option> <?php
            foreach($anioUnico as $ind => $anio){ ?>
                <option value="<?php echo $anio;?>"><?php echo $anio;?></option><?php
            }?>
		</select><?php
		//cerrar conexion
		mysql_close($conn);	
	} //Fin function cargarAniosDisponible()	
?>
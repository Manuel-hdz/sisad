<?php
	/**
	  * Nombre del Módulo: Dirección General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 01/Febrero/2012
	  * Descripción: Este archivo contiene funciones para mostrar los Avances en Desarrollo y Concreto
	**/
	//Ubicacion de las imagenes que estan contenidas en los encabezados
	define("HOST", $_SERVER['HTTP_HOST']);
	define("SISAD","Sisad-v1.0");
	
	function mostrarAvances(){
		/*********GERENCIA TECNICA*******/
		//Conectar a la BD de Gerencia Tecnica
		$conn=conecta("bd_gerencia");
		//Sentencia SQL
		$sql_ger="SELECT id_presupuesto,ubicacion,periodo,vol_ppto_mes FROM presupuesto JOIN 
		catalogo_ubicaciones ON catalogo_ubicaciones_id_ubicacion=id_ubicacion WHERE NOW() BETWEEN fecha_inicio AND fecha_fin ORDER BY vol_ppto_mes DESC";
		//Ejecutar sentencia SQL
		$rs=mysql_query($sql_ger);
		//Verificar si la consulta genero resultados
		if($datosConcreto=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "
				<div id='tabla-resultadosGT'>		
				<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'><span class='nombres_filas'>&nbsp;&nbsp;&nbsp;ZARPEO&nbsp;&nbsp;&nbsp;</span></caption>
					<br>
					<tr>
						<th class='nombres_columnas' align='center'>LUGAR</th>
        				<th class='nombres_columnas' align='center'>PERIODO</th>
				        <th class='nombres_columnas' align='center'>PRESUPUESTO</th>
        				<th class='nombres_columnas' align='center'>AVANCE</th>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			$avance=0;
			$ppto=0;
			do{
				//Obtener la suma de Metros en el ultimo periodo registrado
				$suma=mysql_fetch_array(mysql_query($sql_sum="SELECT SUM(cantidad) FROM bitacora_zarpeo JOIN bitacora ON bitacora_id_bitacora=id_bitacora WHERE periodo='$datosConcreto[periodo]' AND destino='$datosConcreto[ubicacion]'"));
				echo "<tr>
					<td class='nombres_filas' align='center'>$datosConcreto[ubicacion]</td>";
				?>
					<td class='<?php echo $nom_clase?>' align='center'><span id="<?php echo "periodo_$datosConcreto[id_presupuesto]_$cont"?>"><?php echo $datosConcreto["periodo"]?></span></td>
				<?php
				echo "
					<td class='$nom_clase' align='center'><span id='ppto_$datosConcreto[id_presupuesto]_$cont'>".number_format($datosConcreto["vol_ppto_mes"],2,".",",")."</span>M&sup3;</td>
					<td class='$nom_clase' align='center'><span id='avance_$datosConcreto[id_presupuesto]_$cont'>".number_format($suma[0],2,".",",")."</span>M&sup3;</td>
				</tr>";
				
				$avance+=$suma["0"];
				$ppto+=$datosConcreto["vol_ppto_mes"];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datosConcreto=mysql_fetch_array($rs));
			echo "<tr>
				<td>&nbsp;</td>
				<td align='right' class='nombres_columnas'>TOTAL</td>
				<td align='center' class='nombres_columnas'>".number_format($ppto,2,".",",")."</span>M&sup3;</td>
				<td align='center' class='nombres_columnas'>".number_format($avance,2,".",",")."</span>M&sup3;</td>
			</tr>";
			echo "</table>";
			
			$nombre=dibujarGrafico($ppto,$avance,"Zarpeo");
			$foto=str_replace("tmp/","",$nombre);
			?>
			<br/>
			<a href="<?php echo $nombre;?>" rel="lightbox[inicio]" title="Gr&aacute;fico del Avance en Concreto">
			<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/dir/<?php echo $nombre;?>" width="100%" height="200" align="absbottom" title="Ampliar"/>
			</a>
			<?php
			echo "</div>";
		}
		else{
			echo "
				<div id='tabla-resultadosGT'>
					<br><br><br><br><br><br><br><br><br><br>
					<p class='renglon_gris' align='center'><br><u><strong>No Hay Registros de Concreto</u></strong><br><br></p>
				</div>
			";
		}
		mysql_close($conn);
		/************************************/
		
		/*********DESARROLLO*******/
		//Conectar a la BD de Desarrollo
		$conn=conecta("bd_desarrollo");
		//Sentencia SQL
		$sql_des="SELECT id_presupuesto,id_cliente,nom_cliente,periodo,mts_mes,fecha_inicio,fecha_fin FROM presupuesto JOIN 
		catalogo_clientes ON catalogo_clientes_id_cliente=id_cliente WHERE NOW() BETWEEN fecha_inicio AND fecha_fin ORDER BY mts_mes DESC";
		//Ejecutar sentencia SQL
		$rs=mysql_query($sql_des);
		//Verificar si la consulta genero resultados
		if($datosDesarrollo=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "
				<div id='tabla-resultadosDes'>		
				<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'><span class='nombres_filas'>&nbsp;&nbsp;&nbsp;DESARROLLO&nbsp;&nbsp;&nbsp;</span></caption>
					<br>
					<tr>
						<th class='nombres_columnas' align='center'>LUGAR</th>
						<th class='nombres_columnas' align='center'>PERIODO</th>
				        <th class='nombres_columnas' align='center'>PRESUPUESTO</th>
        				<th class='nombres_columnas' align='center'>AVANCE</th>
      				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			$avance=0;
			$ppto=0;
			do{
				//Obtener la suma de Metros en el ultimo periodo registrado
				$suma=mysql_fetch_array(mysql_query($sql_sum="SELECT SUM(avance) FROM bitacora_avance WHERE catalogo_ubicaciones_id_ubicacion=ANY(SELECT id_ubicacion FROM catalogo_ubicaciones WHERE catalogo_clientes_id_cliente='$datosDesarrollo[id_cliente]') AND fecha_registro BETWEEN '$datosDesarrollo[fecha_inicio]' AND '$datosDesarrollo[fecha_fin]'"));
				echo "<tr>
					<td class='nombres_filas' align='center'>$datosDesarrollo[nom_cliente]</td>";
				?>
					<td class='<?php echo $nom_clase?>' align='center'><span id="<?php echo "periodo_$datosDesarrollo[id_presupuesto]_$cont"?>"><?php echo $datosDesarrollo["periodo"]?></span></td>
				<?php
				echo "
					<td class='$nom_clase' align='center'><span id='ppto_$datosDesarrollo[id_presupuesto]_$cont'>".number_format($datosDesarrollo["mts_mes"],2,".",",")."</span>M&sup2;</td>
					<td class='$nom_clase' align='center'><span id='avance_$datosDesarrollo[id_presupuesto]_$cont'>".number_format($suma[0],2,".",",")."</span>M&sup2;</td>
				</tr>";
				
				$avance+=$suma["0"];
				$ppto+=$datosDesarrollo["mts_mes"];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datosDesarrollo=mysql_fetch_array($rs));
			echo "<tr>
				<td>&nbsp;</td>
				<td align='right' class='nombres_columnas'>TOTAL</td>
				<td align='center' class='nombres_columnas'>".number_format($ppto,2,".",",")."</span>M&sup2;</td>
				<td align='center' class='nombres_columnas'>".number_format($avance,2,".",",")."</span>M&sup2;</td>
			</tr>";
			echo "</table>";
			
			$nombre=dibujarGrafico($ppto,$avance,"Desarrollo");
			$foto=str_replace("tmp/","",$nombre);
			?>
			<br/>
			<a href="<?php echo $nombre;?>" rel="lightbox[inicio]" title="Gr&aacute;fico del Avance en Desarrollo">
			<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/dir/<?php echo $nombre;?>" width="100%" height="200" align="absbottom" title="Ampliar"/>
			</a>
			<?php
			echo "</div>";
		}
		else{
			echo "
				<div id='tabla-resultadosDes'>
					<br><br><br><br><br><br><br><br><br><br>
					<p class='renglon_gris' align='center'><br><u><strong>No Hay Registros de Desarrollo</u></strong><br><br></p>
				</div>
			";
		}
		mysql_close($conn);
		/************************************/
	}
	
	function dibujarGrafico($ppto,$avance,$area){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		$pctje=round(($avance*100)/$ppto,2);
		//Arreglo de Datos a graficas
		$data1y=array($ppto);
		$data2y=array($avance);
		// Size of graph
		$width=600;
		$height=300;
		// Set the basic parameters of the graph
		$graph = new Graph($width,$height);
		$graph->SetScale('textlin');
		$top = 60;
		$bottom = 30;
		$left = 80;
		$right = 80;
		$graph->Set90AndMargin($left,$right,$top,$bottom);
		// Setup labels
		$lbl = array("Presupuesto\n\n\n\n\nAvance");
		$graph->xaxis->SetTickLabels($lbl);

		// Label align for X-axis
		$graph->xaxis->SetLabelAlign('right','center','right');
		// Label align for Y-axis
		$graph->yaxis->SetLabelAlign('center','bottom');
		// Titles
		$graph->title->Set('Avance en '.$area.' '.$pctje.'%');
		// Create a bar pot
		$b1plot = new BarPlot($data1y);
		$b1plot->SetFillGradient('olivedrab1','olivedrab4',GRAD_VERT);
		$b1plot->SetWidth(0.5);
		//Asignar el valor a cada dato
		$b1plot->value->Show();
		$b1plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$b1plot->value->SetAlign('left','center');
		$b1plot->value->SetColor('black','darkred');
		//Identificar la unidad de Medida
		if($area=="Zarpeo")
			$b1plot->value->SetFormat('%.2f M³');
		else
			$b1plot->value->SetFormat('%.2f M²');
		// Create a bar pot
		$b2plot = new BarPlot($data2y);
		if ($pctje<=30){
			$b2plot->SetFillGradient('lightsteelblue','red',GRAD_HOR);
			$b2plot->value->SetColor('darkred','olivedrab1');
		}
		if ($pctje>30 && $pctje<=60){
			$b2plot->SetFillGradient("olivedrab1","orange",GRAD_HOR);
			$b2plot->value->SetColor('darkorange','olivedrab1');
		}
		if ($pctje>60 && $pctje<100){
			$b2plot->SetFillGradient("olivedrab1","yellow",GRAD_HOR);
			$b2plot->value->SetColor('olivedrab4','olivedrab1');
		}
		if ($pctje==100){
			$b2plot->SetFillGradient("olivedrab1","olivedrab4",GRAD_HOR);
			$b2plot->value->SetColor('navy','olivedrab1');
		}
		if ($pctje>100){
			$b2plot->SetFillGradient("olivedrab1","olivedrab1",GRAD_VERT);
			$b2plot->value->SetColor('darkgreen','olivedrab1');
		}
		$b2plot->SetWidth(0.5);
		$b2plot->value->Show();
		$b2plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$b2plot->value->SetAlign('left','center');
		//Identificar la unidad de Medida
		if($area=="Zarpeo")
			$b2plot->value->SetFormat('%.2f M³');
		else
			$b2plot->value->SetFormat('%.2f M²');
		//Imagen de fondo segun el area
		if($area=="Zarpeo")
			$graph->SetBackgroundImage('images/alpha.png',BGIMG_FILLFRAME);
		else
			$graph->SetBackgroundImage('images/scoop.png',BGIMG_FILLFRAME);
		//Nivel de Transparencia
		$graph->SetBackgroundImageMix(30);

		
		// Create the grouped bar plot
		$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
		// ...and add it to the graPH
		$graph->Add($gbplot);
		
		$rnd=rand(0,1000);
		$grafica= 'tmp/grafica'.$rnd.'.png';
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}
	
	//Esta función elimina los archivos PDF que se hayan generado anteriormente
	function borrarGraficos(){
		//Borrar los ficheros temporales
		$t=time();
		$h=opendir('tmp');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				if (substr($file,0,5)!="alpha" && substr($file,0,5)!="scoop"){
					$tiempo=filemtime("tmp/".$file);
					$tiempo=$t-$tiempo;
					if($tiempo>50)
						@unlink("tmp/".$file);
				}
			}
		}
		closedir($h);
	}
?>
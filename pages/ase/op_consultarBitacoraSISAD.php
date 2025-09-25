<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 13/Diciembre/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de DesbloquearUsuarios del Sistema
	**/

	//Funcion que muestra los Usuarios Registrados
	function mostrarMovimientos($fechaI,$fechaF){
		//El valor de band es 0 por default, si no cambia, significa que la consulta no genero resultados
		$band=0;
		//Archivo de conexion
		include_once("../../includes/conexion.inc");
		//Archivo de fechas
		include_once("../../includes/func_fechas.php");
		//Modificamos las fechas para ponerlas en el formato necesario para la consulta
		$fechaI=modFecha($fechaI,3);
		$fechaF=modFecha($fechaF,3);
	
		/*************************************************************ALMACEN***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_almacen");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegAlmacen = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************COMPRAS***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_compras");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegCompras = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************GERENCIA TECNICA***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_gerencia");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegGerencia = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************RECURSOS HUMANOS***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_recursos");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegRecursos = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************PRODUCCIÓN***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_produccion");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegProdccion = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************ASEGURAMIENTO CALIDAD***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_aseguramiento");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegAseguramiento = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************DESARROLLO***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_desarrollo");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegDesarrollo = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************MANTENIMIENTO***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_mantenimiento");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegMantenimiento = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************TOPOGRAFIA***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_topografia");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegTopografia = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************LABORATORIO***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_laboratorio");
		//Crear la sentencia para mostrar los movimientos Realizados
		$noRegLaboratorio = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
		//Cerramos Conexion
		mysql_close($conn);
		/*************************************************************SEGURIDAD INDUSTRIAL***************************************************************/	
		//Realizamos la conexion 
		$conn = conecta("bd_seguridad");
		if (!$conn){
			$noRegSeguridad=0;
		}
		else{
			//Crear la sentencia para mostrar los movimientos Realizados
			$noRegSeguridad = mysql_num_rows(mysql_query("SELECT * FROM bitacora_movimientos WHERE fecha>='$fechaI' && fecha<='$fechaF' ORDER BY fecha,hora"));
			//Cerramos Conexion
			mysql_close($conn);
		}
		$noReg=array($noRegAlmacen,$noRegCompras,$noRegGerencia,$noRegRecursos,$noRegProdccion,$noRegAseguramiento,$noRegDesarrollo,$noRegMantenimiento,$noRegTopografia,$noRegLaboratorio,$noRegSeguridad);
		//Desplegar los resultados de la consulta en una tabla
		echo "<div class='borde_seccion' id='movimientos'>";
		echo "				
		<table cellpadding='5' width='100%'>      			
			<tr>
				<td colspan='18' align='center' class='titulo_etiqueta'>MOVIMIENTOS REALIZADOS DEL ".modFecha($fechaI,1)." AL ".modFecha($fechaF,1)."</td>
			</tr>
			<tr>
				<td class='nombres_columnas' align='center'>NO.</td>
				<td class='nombres_columnas' align='center'>DEPARTAMENTO.</td>
				<td class='nombres_columnas' align='center'>NO. MOVIMIENTOS</td>
			</tr>";
			$nom_clase = "renglon_gris";
		echo "<tr>
				<td class='nombres_filas' align='center'>1.-</td>
				<td class='$nom_clase' align='center'>ALMAC&Eacute;N</td>
				<td class='$nom_clase' align='center'>$noRegAlmacen</td>
			</tr>";
			$nom_clase = "renglon_blanco";
		echo "<tr>
				<td class='nombres_filas' align='center'>2.-</td>
				<td class='$nom_clase' align='center'>COMPRAS</td>
				<td class='$nom_clase' align='center'>$noRegCompras</td>
			</tr>";	
			$nom_clase = "renglon_gris";
		echo "<tr>
				<td class='nombres_filas' align='center'>3.-</td>
				<td class='$nom_clase' align='center'>GERENCIA T&Eacute;CNICA</td>
				<td class='$nom_clase' align='center'>$noRegGerencia</td>
			</tr>";	
			$nom_clase = "renglon_blanco";
		echo "<tr>
				<td class='nombres_filas' align='center'>4.-</td>
				<td class='$nom_clase' align='center'>RECURSOS HUMANOS</td>
				<td class='$nom_clase' align='center'>$noRegRecursos</td>
			</tr>";	
			$nom_clase = "renglon_gris";
		echo "<tr>
				<td class='nombres_filas' align='center'>5.-</td>
				<td class='$nom_clase' align='center'>PRODUCCI&Oacute;N</td>
				<td class='$nom_clase' align='center'>$noRegProdccion</td>
			</tr>";	
			$nom_clase = "renglon_blanco";
		echo "<tr>
				<td class='nombres_filas' align='center'>6.-</td>
				<td class='$nom_clase' align='center'>ASEGURAMIENTO CALIDAD</td>
				<td class='$nom_clase' align='center'>$noRegAseguramiento</td>
			</tr>";	
			$nom_clase = "renglon_gris";
		echo "<tr>
				<td class='nombres_filas' align='center'>7.-</td>
				<td class='$nom_clase' align='center'>DESARROLLO</td>
				<td class='$nom_clase' align='center'>$noRegDesarrollo</td>
			</tr>";	
			$nom_clase = "renglon_blanco";
		echo "<tr>
				<td class='nombres_filas' align='center'>8.-</td>
				<td class='$nom_clase' align='center'>MANTENIMIENTO</td>
				<td class='$nom_clase' align='center'>$noRegMantenimiento</td>
			</tr>";	
			$nom_clase = "renglon_gris";
		echo "<tr>
				<td class='nombres_filas' align='center'>9.-</td>
				<td class='$nom_clase' align='center'>TOPOGRAFIA</td>
				<td class='$nom_clase' align='center'>$noRegTopografia</td>
			</tr>";	
			$nom_clase = "renglon_blanco";
		echo "<tr>
				<td class='nombres_filas' align='center'>10.-</td>
				<td class='$nom_clase' align='center'>LABORATORIO</td>
				<td class='$nom_clase' align='center'>$noRegLaboratorio</td>
			</tr>";	
			$nom_clase = "renglon_gris";
		echo "<tr>
				<td class='nombres_filas' align='center'>11.-</td>
				<td class='$nom_clase' align='center'>SEGURIDAD INDUSTRIAL</td>
				<td class='$nom_clase' align='center'>$noRegSeguridad</td>
			</tr>";	
	echo "</table>";
	echo "</div>";
		$grafo=mostrarGrafica($noReg);?>
		<div class='borde_seccion'  title="Click Para Ampliar La Imagen" id='grafica'>
		<img src="<?php echo $grafo;?>" width="100%" height="100%" onclick="window.open('verGrafica.php?imagen=<?php echo $grafo;?>','_blank','top=50, left=50, width=740, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" align="absbottom" />
		</div><?php
	}
	
	function mostrarGrafica($noReg){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
		
		// Some "random" data
		$ydata  = $noReg;
		$ydata2 = array("ALMACEN","COMPRAS","G. TECNICA","R. HUMANOS","PRODUCCION","A. CALIDAD","DESARROLLO","MANTENIMIENTO","TOPOGRAFIA","LABORATORIO","S. INDUSTRIAL");
			
		// Create the graph. 
		$graph = new Graph(740,500);	
		$graph->SetScale("textlin");
		$graph->SetMarginColor('white');
		
		// Adjust the margin slightly so that we use the 
		// entire area (since we don't use a frame)
		$graph->yaxis->scale->SetGrace(10);
		
		
		// Box around plotarea
		$graph->SetBox(); 
		
		// No frame around the image
		$graph->SetFrame(false);
		
		// Setup the tab title
		$graph->tabtitle->Set('Movimientos SISAD');
		$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,10);
		$graph->img->SetMargin(40,25,30,80);
		
		// Setup the X and Y grid
		$graph->ygrid->SetFill(true,'#DDDDDD@0.5','#BBBBBB@0.5');
		$graph->ygrid->SetLineStyle('dashed');
		//Color de las lineas punteadas en el fondo de la grafica
		$graph->ygrid->SetColor('gray');
		$graph->xgrid->Show();
		$graph->xgrid->SetLineStyle('dashed');
		$graph->xgrid->SetColor('gray');
		
		// Setup month as labels on the X-axis
		$graph->xaxis->SetTickLabels($ydata2);
		$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);
		
		// Create a bar pot
		$bplot = new BarPlot($ydata);
		//Poner el valor en cada una de las columnas
		$bplot->value->Show();
		$bplot->SetWidth(0.6);
		$fcol='#000000';
		$tcol='#9BBB59';
		
		$bplot->SetFillGradient($fcol,$tcol,GRAD_LEFT_REFLECTION);
		
		// Set line weigth to 0 so that there are no border
		// around each bar
		$bplot->SetWeight(0);
		
		$graph->Add($bplot);
		
		// Create filled line plot
		$lplot = new LinePlot($ydata2);
		$lplot->SetFillColor('skyblue@0.5');
		$lplot->SetColor('navy@0.7');
		$lplot->SetBarCenter();
		
		$lplot->mark->SetType(MARK_SQUARE);
		$lplot->mark->SetColor('blue@0.5');
		$lplot->mark->SetFillColor('lightblue');
		$lplot->mark->SetSize(6);
		$lplot->value->Show();		
		$graph->Add($lplot);
	
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= 'tmp/grafica'.$rnd.'.png';
		
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Devolver el nombre de la grafica para poder identificarla y colocarla en el reporte que se exporta y/o en el div donde se muestra
		return $grafica;
	}
	
	//Funcion Borrar Temproales
	function borrarGraficoCalidad(){
		//Borrar los ficheros temporales
		$h=opendir('tmp/');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				@unlink("tmp/".$file);
			}
		}
		closedir($h);
	}
?>
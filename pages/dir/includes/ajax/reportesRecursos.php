<?php
	/**
	  * Nombre del MÛdulo: Direccion General
	  * Nombre Programador: Antonio de Jes˙s JimÈnez Cuevas
	  * Fecha: 24/Septiembre/2012
	  * DescripciÛn: Este archivo contiene las operaciones de Reportes de Recursos Humanos
	  **/
	 	
	 /**
      * Listado del contenido del programa
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
			include("../../../../includes/func_fechas.php");
	
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["noRep"])){
		$tipoRep=$_GET["noRep"];
		switch($tipoRep){
			case 1:
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$area=$_GET["area"];
				$titulo="REPORTE DE KARDEX DEL $fechaI AL $fechaF";
				if($area!="")
					$titulo.="\n¡REA: $area";
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				$grafica=reporteKardex($fechaI,$fechaF,$area,$titulo);
				$grafica=str_replace("../../tmp/","",$grafica);
				header("Content-type: text/xml");	
				if ($grafica!=""){
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<grafica>$grafica</grafica>
							</existe>
						");
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
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$area=$_GET["area"];
				$titulo="REPORTE DE ALTAS, BAJAS Y MODIFICACIONES DE PUESTOS DEL $fechaI AL $fechaF";
				if($area!="")
					$titulo.="\n¡REA: $area";
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				$grafica=reporteHistorial($fechaI,$fechaF,$area,$titulo);
				$grafica=str_replace("../../tmp/","",$grafica);
				header("Content-type: text/xml");	
				if ($grafica!=""){
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<grafica>$grafica</grafica>
							</existe>
						");
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
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$area=$_GET["area"];
				$titulo="REPORTE DE PERSONAL EN BOLSA DE TRABAJO DEL $fechaI AL $fechaF";
				if($area!="")
					$titulo.="\n¡REA: $area";
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Obtener la Tabla en una variable
				$tabla=reporteBolsaTrabajo($fechaI,$fechaF,$area,$titulo);
				header("Content-type: text/xml");	
				if ($tabla!=""){
					//Remplazar el tag de apertura "menor que" por un simbolo menos usado, en este caso "¨"
					$tabla=str_replace("<","¨",$tabla);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<tabla>$tabla</tabla>
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
			case 4:
				$fechaI=$_GET["fechaI"];
				$fechaF=$_GET["fechaF"];
				$titulo="REPORTE DE PR…STAMOS DEL $fechaI AL $fechaF";
				if($area!="")
					$titulo.="\n¡REA: $area";
				$fechaI=modFecha($fechaI,3);
				$fechaF=modFecha($fechaF,3);
				//Obtener la Grafica en una variable
				$resultados=reportePrestamos($fechaI,$fechaF,$area,$titulo);
				$partes=explode("¨",$resultados);
				$tabla=$partes[1];
				$grafica=$partes[0];
				header("Content-type: text/xml");	
				if ($tabla!=""){
					//Remplazar el tag de apertura "menor que" por un simbolo menos usado, en este caso "¨"
					$tabla=str_replace("<","¨",$tabla);
					//Obtener solo el nombre de la grafica
					$grafica=str_replace("../../tmp/","",$grafica);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
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
	
	/*Esta funcion genera el reporte de incidencias de Kardex*/
	function reporteKardex($fechaI,$fechaF,$area,$titulo){
		//Conectarse a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		if($area=="")
			//Sentencia SQL
			$sql_stm="SELECT estado,COUNT(estado) AS incidencia FROM checadas WHERE estado!='SALIDA' AND fecha_checada BETWEEN '$fechaI' AND '$fechaF' GROUP BY estado";
		else
			//Sentencia SQL
			$sql_stm="SELECT checadas.estado AS estado,COUNT(checadas.estado) AS incidencia FROM checadas JOIN empleados ON rfc_empleado=empleados_rfc_empleado WHERE checadas.estado!='SALIDA' AND area='$area' AND fecha_checada BETWEEN '$fechaI' AND '$fechaF' GROUP BY checadas.estado";
		//Ejecutar Sentencia SQL
		$rs=mysql_query($sql_stm);
		//Extraer los datos a otro arreglo que permita un mejor manejo de la informacion
		if($datos=mysql_fetch_array($rs)){
			$incidencias=array();
			$cantidad=array();
			do{
				switch($datos["estado"]){
					case "A":
						$inc="ASISTENCIA";
						break;
					case "F":
						$inc="FALTA";
						break;
					case "d":
						$inc="DESCANSO";
						break;
					case "V":
						$inc="VACACIONES";
						break;
					case "r":
						$inc="RETARDO";
						break;
					case "F/J":
						$inc="FALTA/JUSTIFICADA";
						break;
					case "P":
						$inc="PERMISO SIN GOCE SUELDO";
						break;
					case "P/G":
						$inc="PERMISO CON GOCE SUELDO";
						break;	
					case "E":
						$inc="INCAPACIDAD ENFERMEDAD GENERAL";
						break;
					case "RT":
						$inc="INCAPACIDAD ACCIDENTE TRABAJO";
						break;
					case "T":
						$inc="INCAPACIDAD TRAYECTO";
						break;
					case "D":
						$inc="SANCI”N DISCIPLINARIA";
						break;
					case "R":
						$inc="REGRESADO";
						break;
				}
				$incidencias[]=$inc;
				$cantidad[]=$datos["incidencia"];
			}while($datos=mysql_fetch_array($rs));
			//Obtener el Grafico
			$grafica=graficaKardex($incidencias,$cantidad,$titulo);
			//Cerrar la conexion
			mysql_close($conn);
			//Retornar la Grafica obtenida
			return $grafica;
		}
		else{
			//Cerrar la conexion
			mysql_close($conn);
			//Retornar vacio
			return "";
		}
	}//Cierre de la funcion reporteKardex($fechaI,$fechaF,$area,$titulo)
	
	//Grafica que es incluida en el reporte de Kardex
	function graficaKardex($incidencias,$cantidad,$titulo){
		//Obtener el numero total de incidencias de Kardex
		$total=array_sum($cantidad);
		//Recorrer el arreglo para obtener los datos en forma de porcentaje
		foreach($cantidad as $ind => $value){
			$cantidad[$ind]=round(($cantidad[$ind]*100)/$total,2);
		}
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ("../../../../includes/graficas/jpgraph/jpgraph_bar.php");
		// Create the graph. These two calls are always required
		$graph = new Graph(800,600);    
		$graph->SetScale("textlin");
		$graph->SetShadow();
		$graph->img->SetMargin(140,60,60,150);
		//Calcular el valor de Gracia
		$resto=(100-max($cantidad));
		$grace=($resto*100)/max($cantidad);
		$datay=$cantidad;
		$graph->yaxis->scale->SetGrace($grace);
		//Eje X
		$graph->xaxis->SetTickLabels($incidencias);
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
		$graph->footer->center->Set("Incidencias");
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		// ...y agregarlo a la grafica
		$graph->Add($bplot);
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= "../../tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}//Cierre graficaKardex($incidencias,$cantidad,$titulo)
	
	/*Esta funcion genera el reporte Historico de Movimientos*/
	function reporteHistorial($fechaI,$fechaF,$area,$titulo){
		//Conectarse a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		//Verificar el area
		if($area==""){
			//Sentencia SQL para Altas
			$altas=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM empleados WHERE fecha_ingreso BETWEEN '$fechaI' AND '$fechaF'"));
			//Sentencia SQL para Bajas
			$bajas=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM bajas_modificaciones WHERE fecha_baja BETWEEN '$fechaI' AND '$fechaF'"));
			//Sentencia SQL para Modificaciones
			$cambios=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM bajas_modificaciones WHERE fecha_mod_puesto BETWEEN '$fechaI' AND '$fechaF'"));
		}
		else{
			//Sentencia SQL para Altas
			$altas=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM empleados WHERE area='$area' AND fecha_ingreso BETWEEN '$fechaI' AND '$fechaF'"));
			//Sentencia SQL para Bajas
			$bajas=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM bajas_modificaciones WHERE area='$area' AND fecha_baja BETWEEN '$fechaI' AND '$fechaF'"));
			//Sentencia SQL para Modificaciones
			$cambios=mysql_fetch_array(mysql_query("SELECT COUNT(*) AS total FROM bajas_modificaciones WHERE area='$area' AND fecha_mod_puesto BETWEEN '$fechaI' AND '$fechaF'"));
		}
		$ejeX=array("$altas[total] ALTAS","$bajas[total] BAJAS","$cambios[total] CAMBIOS PUESTO");
		//Verificar que por lo menos hayan existido, 1 o mas Altas, Bajas o Cambios de Puesto
		if($altas["total"]>0 || $bajas["total"]>0 || $cambios["total"]>0){
			$datos=array($altas["total"],$bajas["total"],$cambios["total"]);
			//Obtener el Grafico
			$grafica=graficaHistorico($ejeX,$datos,$titulo);
			//Cerrar la conexion
			mysql_close($conn);
			//Retornar la Grafica obtenida
			return $grafica;
		}
	}//Cierre de la funcion reporteKardex($fechaI,$fechaF,$area,$titulo)
	
	//Grafica que es incluida en el reporte Historico de Acciones
	function graficaHistorico($ejeX,$datos,$titulo){
		//Obtener el numero total de incidencias de Kardex
		$total=array_sum($datos);
		//Recorrer el arreglo para obtener los datos en forma de porcentaje
		foreach($datos as $ind => $value){
			if($datos[$ind]!=0)
				$datos[$ind]=round(($datos[$ind]*100)/$total,2);
		}
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ("../../../../includes/graficas/jpgraph/jpgraph_bar.php");
		// Create the graph. These two calls are always required
		$graph = new Graph(800,600);    
		$graph->SetScale("textlin");
		$graph->SetShadow();
		$graph->img->SetMargin(100,60,60,100);
		//Calcular el valor de Gracia
		if(max($datos)!=0){
			$resto=(100-max($datos));
			$grace=($resto*100)/max($datos);
		}
		else
			$grace=9000;
		$datay=$datos;
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
		$graph->footer->center->Set("Movimientos");
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		// ...y agregarlo a la grafica
		$graph->Add($bplot);
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= "../../tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}//Cierre graficaHistorico($ejeX,$datos,$titulo)
	
	//Funcion que dibuja la Tabla y la regresa en una variable para mostrarla
	//segun los datos almacenados en la Bolsa de Trabajo
	function reporteBolsaTrabajo($fechaI,$fechaF,$area,$titulo){
		$tabla="";
		if($area=="")
			$sql="SELECT folio_aspirante,CONCAT(nombre,' ', ap_paterno,' ', ap_materno) AS nombre, estado_civil, telefono, edad, experiencia_laboral 
					FROM bolsa_trabajo WHERE fecha_solicitud>='$fechaI' AND fecha_solicitud<='$fechaF' ORDER BY fecha_solicitud";
		else
			$sql="SELECT DISTINCT folio_aspirante,CONCAT(nombre,' ', ap_paterno,' ', ap_materno) AS nombre, estado_civil, 	
				telefono, edad, experiencia_laboral FROM(bolsa_trabajo JOIN area_puesto ON folio_aspirante=bolsa_trabajo_folio_aspirante)
				WHERE fecha_solicitud>='$fechaI' AND fecha_solicitud<='$fechaF' AND area='$area' ORDER BY fecha_solicitud";
		//Conectarse a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			$tabla.="<table align='center'  class='tabla-frm' cellpadding='5'>
					<caption class='titulo_etiqueta' style='color:#FFF'>$titulo</caption>					
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>ESTADO CIVIL</td>
						<td align='center' class='nombres_columnas'>TEL…FONO</td>
						<td align='center' class='nombres_columnas'>EDAD</td>
						<td align='center' class='nombres_columnas'>EXPERIENCIA LABORAL</td>
						<td align='center' class='nombres_columnas'>PUESTOS</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				$tabla.="	
					<tr>
						<td align='center' class='$nom_clase'>$cont</td>		
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[estado_civil]</td>
						<td align='center' class='$nom_clase'>$datos[telefono]</td>
						<td align='center' class='$nom_clase'>$datos[edad]</td>
						<td align='center' class='$nom_clase'>$datos[experiencia_laboral]</td>";
					
					$rsPuestos=mysql_query("SELECT DISTINCT puesto FROM area_puesto WHERE bolsa_trabajo_folio_aspirante='$datos[folio_aspirante]'");
					$puesto="";
					if($puestos=mysql_fetch_array($rsPuestos)){
						$puesto=$puestos["puesto"];
						do{
							if($puesto!=$puestos["puesto"])
								$puesto.=", ".$puestos["puesto"];
						}while($puestos=mysql_fetch_array($rsPuestos));
					}
					
					$tabla.="<td align='center' class='$nom_clase'>$puesto</td>";
					
				$tabla.="</tr>";
										
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			mysql_close($conn);
		}
		else
			mysql_close($conn);
		return $tabla;
	}//function reporteBolsaTrabajo($fechaI,$fechaF,$area,$titulo)
	
	//Reporte de Prestamos Activos con el total a pagar aun
	function reportePrestamos($fechaI,$fechaF,$area,$titulo){
		$tabla="";
		$grafica="";
		//Sentencia para extraer los Prestamos que estan activos por Area o en General
		if($area=="")
			$sql="SELECT CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, id_deduccion, nom_deduccion, descripcion, total,
				autorizo, fecha_alta FROM deducciones JOIN empleados on (empleados_rfc_empleado=rfc_empleado) WHERE id_deduccion LIKE 'PRE%' AND deducciones.estado='ACTIVO' 
				AND fecha_alta BETWEEN '$fechaI' AND '$fechaF' ORDER BY nombre";
		else
			$sql="SELECT CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, id_deduccion, nom_deduccion, descripcion, total,
				autorizo, fecha_alta FROM deducciones JOIN empleados on (empleados_rfc_empleado=rfc_empleado) WHERE id_deduccion LIKE 'PRE%' AND deducciones.estado='ACTIVO' AND
				area='$area' AND fecha_alta BETWEEN '$fechaI' AND '$fechaF' ORDER BY nombre";
		//Conectarse a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		$rs=mysql_query($sql);
		$deudaTotal=0;
		$totalNoPagado=0;
		if($datos=mysql_fetch_array($rs)){
			$tabla.="<table align='center'  class='tabla-frm' cellpadding='5'>
					<caption class='titulo_etiqueta' style='color:#FFF'>$titulo</caption>					
					<tr>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>DEDUCCI”N</td>
						<td align='center' class='nombres_columnas'>DESCRIPCI”N</td>
						<td align='center' class='nombres_columnas'>AUTORIZADO POR:</td>
						<td align='center' class='nombres_columnas'>TOTAL PRESTADO</td>
						<td align='center' class='nombres_columnas'>POR PAGAR</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				//Acumular el total de la Deuda
				$deudaTotal+=$datos['total'];
				$tabla.="	
					<tr>		
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[nom_deduccion]</td>
						<td align='center' class='$nom_clase'>$datos[descripcion]</td>
						<td align='center' class='$nom_clase'>$datos[autorizo]</td>
						<td align='center' class='$nom_clase'>$".number_format($datos['total'],2,".",",")."</td>";
					$rsSaldo=mysql_query("SELECT MIN(saldo_final) AS resto FROM detalle_abonos WHERE deducciones_id_deduccion='$datos[id_deduccion]' AND fecha_abono=(SELECT MAX(fecha_abono) FROM detalle_abonos WHERE deducciones_id_deduccion='$datos[id_deduccion]')");
					$saldo="$datos[total]";
					if($resto=mysql_fetch_array($rsSaldo))
						$saldo=$resto["resto"];
					//Acumular el total de lo que NO se ha pagado
					$totalNoPagado+=$saldo;
					$saldo=number_format($saldo,2,".",",");
					$tabla.="<td align='center' class='$nom_clase'>$$saldo</td>";
					
				$tabla.="</tr>";
										
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
		if($tabla!="")
			//Crear la grafica de Deuda VS Pagado
			$grafica=graficaPrestamos($deudaTotal,$deudaTotal-$totalNoPagado,$titulo);
		return $grafica."¨".$tabla;
	}//Fin de function reportePrestamos($fechaI,$fechaF,$titulo)
	
	function graficaPrestamos($deudaTotal,$totalPagado,$titulo){
		require_once ('../../../../includes/graficas/jpgraph/jpgraph.php');
		require_once ("../../../../includes/graficas/jpgraph/jpgraph_bar.php");
		$pctje=round(($totalPagado*100)/$deudaTotal,2);
		//Arreglo de Datos a graficas
		$data1y=array($deudaTotal);
		$data2y=array($totalPagado);
		// Set the basic parameters of the graph
		//width,height
		$graph = new Graph(600,300);
		$graph->SetScale('textlin');
		//LRTB
		$graph->Set90AndMargin(80,80,60,30);
		// Setup labels
		$lbl = array("PRESTADO\n\n\n\n\nPAGADO");
		$graph->xaxis->SetTickLabels($lbl);
		// Label align for X-axis
		$graph->xaxis->SetLabelAlign('right','center','right');
		// Label align for Y-axis
		$graph->yaxis->SetLabelAlign('center','bottom');
		// Titles
		$graph->title->Set($titulo);
		// Create a bar pot
		$b1plot = new BarPlot($data1y);
		$b1plot->SetFillGradient('olivedrab1','olivedrab4',GRAD_VERT);
		$b1plot->SetWidth(0.5);
		//Asignar el valor a cada dato
		$b1plot->value->Show();
		$b1plot->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$b1plot->value->SetAlign('left','center');
		$b1plot->value->SetColor('black','darkred');
		$b1plot->value->SetFormatCallback('formatoNumeros');
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
		$b2plot->value->SetFormatCallback('formatoNumeros');

		// Create the grouped bar plot
		$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
		// ...and add it to the graPH
		$graph->Add($gbplot);
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= "../../tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}

	//Funcion que pone los valores en formato de Pesos con la "," incluida
	function formatoNumeros($aVal) {
		return '$'.number_format($aVal,2,".",",");
	}
?>
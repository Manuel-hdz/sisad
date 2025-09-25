<?php
	/**
	  * Nombre del Módulo: Unidadd e Salud Ocupacional
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:10/Agosto/2012
	  * Descripción: Este archivo contiene funciones para consultar la información relacionada con el formulario de donde se Generan los Planes de Contingencia
	**/


	//Funcion que muestra los registros en la bitácora de Radiografias
	function mostrarCensosConsultas($fechaIni,$fechaFin,$clasificacion,$tipo){
		//Convertimos las fechas en formato aaa-mm-dd ya que son como se guardan en la BD.
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		$titulo = "";
		//Volvemos a convertir las fechas definidas anteriormente para colocar el titulo dentro d ela tabla que mostrara los resultados 
		$fechaIni=modFecha($fechaI,1);
		$fechaFin=modFecha($fechaF,1);
		$conn=conecta("bd_clinica");
		
		//Si las opciones de consulta y tipo de ocnsulta no han sido seleccionadas significa que el usuario solo consulto de acuerdo a un rango de fechas
		if($clasificacion=="" && $tipo==""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas, catalogo_empresas_id_empresa, empleados_rfc_empleado, id_empleados_empresa, nom_empleado, area, puesto, 
			tipo_consulta, consulta, nom_familiar, parentesco, fecha, hora, lugar, pb_diagnostico, tratamiento, observaciones FROM bitacora_consultas
			 WHERE fecha BETWEEN '$fechaI' AND '$fechaF' ORDER BY id_bit_consultas";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Censos y Consultas Registradas del $fechaIni al $fechaFin";
		}
		else if($clasificacion!="" && $tipo!=""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas, catalogo_empresas_id_empresa, empleados_rfc_empleado, id_empleados_empresa, nom_empleado, area, puesto, 
			tipo_consulta, consulta, nom_familiar, parentesco, fecha, hora, lugar, pb_diagnostico, tratamiento, observaciones FROM bitacora_consultas 
			WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND consulta='$clasificacion' AND tipo_consulta='$tipo' ";
			 
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Censos y Consultas Registradas del $fechaIni al $fechaFin de las Consultas $clasificacion de Tipo $tipo";
		}
		else if($clasificacion!="" && $tipo==""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas, catalogo_empresas_id_empresa, empleados_rfc_empleado, id_empleados_empresa, nom_empleado, area, puesto, 
			tipo_consulta, consulta, nom_familiar, parentesco, fecha, hora, lugar, pb_diagnostico, tratamiento, observaciones FROM bitacora_consultas 
			WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND consulta='$clasificacion' ";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Censos y Consultas Registradas del $fechaIni al $fechaFin de las Consultas $clasificacion";
		}
		else if($clasificacion=="" && $tipo!=""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas, catalogo_empresas_id_empresa, empleados_rfc_empleado, id_empleados_empresa, nom_empleado, area, puesto, 
			tipo_consulta, consulta, nom_familiar, parentesco, fecha, hora, lugar, pb_diagnostico, tratamiento, observaciones FROM bitacora_consultas
			WHERE fecha BETWEEN '$fechaI' AND '$fechaF'  AND tipo_consulta='$tipo' ";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Censos y Consultas Registradas del $fechaIni al $fechaFin de las Consultas de Tipo $tipo";
		}
		//Ejecutar la sentencia SQL				
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosRepCC'>
				<caption class='titulo_etiqueta'>$titulo</caption>
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>CLAVE CONSULTA</th>
						<th class='nombres_columnas' align='center'>EMPRESA</th>
						<th class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</th>
        				<th class='nombres_columnas' align='center'>&Aacute;REA</th>
				        <th class='nombres_columnas' align='center'>PUESTO</th>
        				<th class='nombres_columnas' align='center'>TIPO CONSULTA</th>
						<th class='nombres_columnas' align='center'>CONSULTA</th>
						<th class='nombres_columnas' align='center'>FECHA</th>
        				<th class='nombres_columnas' align='center'>HORA</th>
        				<th class='nombres_columnas' align='center'>LUGAR</th>
        				<th colspan='2' class='nombres_columnas' align='center'>PB DIAGNOSTICO</th>
        				<th class='nombres_columnas' align='center'>TRATAMIENTO</th>					
        				<th class='nombres_columnas' align='center'>INFORME MEDICO</th>					
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{
				//Obtener el Id del Informe Medico
				$idInf=obtenerDato("bd_clinica","informe_medico", "id_informe", "bitacora_consultas_id_bit_consultas",$datos['id_bit_consultas']);
				if($datos["catalogo_empresas_id_empresa"]==0)
					$empresa="CLF";
				else
					$empresa=obtenerDato("bd_clinica","catalogo_empresas", "nom_empresa", "id_empresa", $datos['catalogo_empresas_id_empresa']);
				echo "	<tr>
							</td>
							<td class='$nom_clase' align='center'>$datos[id_bit_consultas]</td>
							<td class='$nom_clase' align='center'>$empresa</td>
							<td class='$nom_clase' align='center'>$datos[nom_empleado]</td>
							<td class='$nom_clase' align='center'>$datos[area]</td>
							<td class='$nom_clase' align='center'>$datos[puesto]</td>
							<td class='$nom_clase' align='center'>$datos[tipo_consulta]</td>
							<td class='$nom_clase' align='center'>$datos[consulta]</td>
							<td class='$nom_clase' align='center'>".modFecha($datos["fecha"],1)."</td>
							<td class='$nom_clase' align='center'>$datos[hora]</td>
							<td class='$nom_clase' align='center'>$datos[lugar]</td>
							<td colspan='2' class='$nom_clase' align='center'>$datos[pb_diagnostico]</td>
							<td class='$nom_clase' align='center'>$datos[observaciones]</td>";?>
			
				<?php if($idInf!=""){
						?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verInfMed<?php echo $cont?>" id="btn_verInfMed<?php echo $cont?>" class="botones" value="Informe M&eacute;dico" 
							onMouseOver="window.estatus='';return true" title="Ver Informe M&eacute;dico" 
							onClick="javascript:window.open('../../includes/generadorPDF/infMedico.php?id=<?php echo $idInf?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>							
						</td>						
					</tr>
				<?php
				}
				else{
				?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verInfMed<?php echo $cont?>" id="btn_verInfMed<?php echo $cont?>" class="botones" value="Informe M&eacute;dico" 
							title="El Trabajador no Tiene Informe M&eacute;dico" disabled="disabled"/>
						</td>						
					</tr>
				<?php
				}				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";					
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";
			$graficas=datosGrafica($fechaI,$fechaF,$titulo,$clasificacion,$tipo);
			return $graficas;
		}
		else{
			echo "<meta http-equiv='refresh' content='0;url=frm_reporteCensosConsultas.php?noResults'>";
		}
		mysql_close($conn);
	}
	
	function datosGrafica($fechaIni,$fechaFin,$titulo,$clasificacion,$tipo){
		$ejeX=array();
		$empresaAcc=array();
		$empresaGral=array();
		/****************************/
		if($clasificacion!="EXTERNA"){
			$sqlAcc="SELECT COUNT(tipo_consulta) FROM bitacora_consultas WHERE catalogo_empresas_id_empresa='0' 
					AND tipo_consulta='ACCIDENTE' AND fecha BETWEEN '$fechaIni' AND '$fechaFin'";
			if($tipo!="")
				$sqlAcc.=" AND tipo_consulta='$tipo'";
			//Extraer la cantidad de Consultas por Accidente de la empresa entre las fechas seleccionadas
			$accidente=mysql_fetch_array(mysql_query($sqlAcc));
			
			$sqlGral="SELECT COUNT(tipo_consulta) FROM bitacora_consultas WHERE catalogo_empresas_id_empresa='0' 
					AND tipo_consulta='GENERAL' AND fecha BETWEEN '$fechaIni' AND '$fechaFin'";
			if($tipo!="")
				$sqlGral.=" AND tipo_consulta='$tipo'";
			//Extraer la cantidad de Consultas Generales de la empresa entre las fechas seleccionadas
			$general=mysql_fetch_array(mysql_query($sqlGral));
			
			//Crear el arreglo para el Eje X
			$ejeX[]="CLF";
			//Crear los arreglos por empresa de los accidentes y consultas generales
			$empresaAcc[]=$accidente[0];
			$empresaGral[]=$general[0];
		}
		/****************************/
		//Sentencia para extrser la empresas en las fechas seleccionadas
		$sql="SELECT DISTINCT catalogo_empresas_id_empresa,nom_empresa FROM bitacora_consultas JOIN catalogo_empresas ON id_empresa=catalogo_empresas_id_empresa
				WHERE fecha BETWEEN '$fechaIni' AND '$fechaFin'";
		if($clasificacion!="")
			$sql.=" AND consulta='$clasificacion'";
		if($tipo!="")
			$sql.=" AND tipo_consulta='$tipo'";
		$sql.=" ORDER BY catalogo_empresas_id_empresa";
		//Ejecutar sentencia
		$rs=mysql_query($sql);
		//Verificar resultados
		if($datos=mysql_fetch_array($rs)){
			//Recorrer resultados
			do{
				$sqlAcc="SELECT COUNT(tipo_consulta) FROM bitacora_consultas WHERE catalogo_empresas_id_empresa='$datos[catalogo_empresas_id_empresa]' 
						AND tipo_consulta='ACCIDENTE' AND fecha BETWEEN '$fechaIni' AND '$fechaFin'";
				if($clasificacion!="")
					$sqlAcc.=" AND consulta='$clasificacion'";
				if($tipo!="")
					$sqlAcc.=" AND tipo_consulta='$tipo'";
				//Extraer la cantidad de Consultas por Accidente de la empresa entre las fechas seleccionadas
				$accidente=mysql_fetch_array(mysql_query($sqlAcc));
				$sqlGral="SELECT COUNT(tipo_consulta) FROM bitacora_consultas WHERE catalogo_empresas_id_empresa='$datos[catalogo_empresas_id_empresa]' 
						AND tipo_consulta='GENERAL' AND fecha BETWEEN '$fechaIni' AND '$fechaFin'";
				if($clasificacion!="")
					$sqlGral.=" AND consulta='$clasificacion'";
				if($tipo!="")
					$sqlGral.=" AND tipo_consulta='$tipo'";
				//Extraer la cantidad de Consultas Generales de la empresa entre las fechas seleccionadas
				$general=mysql_fetch_array(mysql_query($sqlGral));
				//Crear el arreglo para el Eje X
				$ejeX[]=$datos["nom_empresa"];
				//Crear los arreglos por empresa de los accidentes y consultas generales
				$empresaAcc[]=$accidente[0];
				$empresaGral[]=$general[0];
			}while($datos=mysql_fetch_array($rs));
			$graficas=dibujarGraficas($empresaAcc,$empresaGral,$ejeX,$titulo);
			return $graficas;
		}
		else{
			if(count($ejeX)>0){
				$graficas=dibujarGraficas($empresaAcc,$empresaGral,$ejeX,$titulo);
				return $graficas;
			}
		}
	}
	
	function dibujarGraficas($empresaAcc,$empresaGral,$ejeX,$titulo){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');

		//Contador para verificar la cantidad de graficas tontas
		$dumbGraf=0;
		//Obtener la cantidad de Registros
		$cantRes=count($ejeX);
		//Registros por Grafica
		$cantDatos=5;
		$graficas="";		
		//Obtener la cantidad de graficas
		$ciclos=$cantRes/$cantDatos;
		//Redondear el valor de los ciclos
		$ciclos=intval($ciclos);
		//Obtener el residuo para saber si incrementar en 1 la cantidad de ciclos
		$residuo=$cantRes%$cantDatos;
		//Si residuo es mayor a 0, incrementar en un los ciclos
		if($residuo>0)
			$ciclos+=1;
		//Inicializar variable de control para la cantidad de ciclos
		$cont=0;
		//Contador por cada grafica a dibujar
		$contPorGrafica=0;
		do{
			//Declarar el arreglo de costos Entrada por cada grafica
			$accidentes=array();
			//Declarar el arreglo de costos Salida por cada grafica
			$generales=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener los datos a graficar
			do{
				//Asignar a la posicion actual el valor de costos de Entrada
				$accidentes[]=$empresaAcc[$contPorGrafica];
				//Asignar a la posicion actual el valor de costos de Salida
				$generales[]=$empresaGral[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$ejeX[$contPorGrafica];
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			$data1y=$accidentes;
			$data2y=$generales;
			// Create the graph. These two calls are always required
			$graph = new Graph(800,600);    
			$graph->SetScale("textlin");
			$graph->SetShadow();
			$graph->img->SetMargin(100,80,60,100);
			// Create the bar plots
			$b1plot = new BarPlot($data1y);
			$b1plot->SetWidth(30);
			$b1plot->SetFillColor("orange");
			$b1plot->SetLegend('Consultas por Accidente');
			$b1plot->SetCenter();
			$b2plot = new BarPlot($data2y);
			$b2plot->SetWidth(30);
			$b2plot->SetFillColor("blue");
			$b2plot->SetLegend('Consultas Generales');
			$b2plot->SetCenter();
			// Create the grouped bar plot
			$gbplot = new GroupBarPlot(array($b1plot,$b2plot));
			// ...and add it to the graPH
			$graph->Add($gbplot);
			$graph->title->Set($titulo);
			// Eje X
			$graph->xgrid->Show();
			$graph->yaxis->title->Set('Cantidad');
			$graph->yaxis->title->SetColor('darkred');
			$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,10);
			//$graph->yaxis->SetLabelFormat('%.2f');
			$graph->yaxis->SetTitleMargin(80);
			$graph->yaxis->scale->SetGrace(20);
			// Eje Y
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			$graph->xaxis->SetLabelAngle(20);
			$graph->footer->center->Set("Empresas");
			$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->footer->center->SetColor('darkred');
			//Titulo de la grafica
			$graph->title->SetFont(FF_FONT1,FS_BOLD);
			//Crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
			$rnd=rand(0,1000);
			$grafica= "tmp/grafica".$rnd.".png";
			$graficas.=$grafica;
			//Dibujar la grafica y guardarla en un archivo temporal	
			$graph->Stroke($grafica);
			$cont++;
		}while($cont<$ciclos);
		return $graficas;
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
?>
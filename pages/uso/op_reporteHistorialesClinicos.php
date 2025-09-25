<?php
	/**
	  * Nombre del Módulo: Unidadd e Salud Ocupacional
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:15/Agosto/2012
	  * Descripción: Este archivo contiene funciones para consultar la información relacionada con el formulario de donde se Generan los Planes de Contingencia
	**/


	//Funcion que muestra los registros en la bitácora de Radiografias
	function mostrarHistorialesClinicos($fechaIni,$fechaFin,$clasificacion,$tipo){
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
			$sql_stm="SELECT DISTINCT 	`T1`.`id_historial` , `T1`.`puesto_realizar` , `T1`.`nom_empleado` , `T1`.`escolaridad` , 
										`T4`.`peso_kg` , `T4`.`talla_mts` ,  `T4`.`pres_arterial` , `T4`.`imc` ,
										`T2`.`lentes` ,  `T2`.`ojo_der_vision` , `T2`.`ojo_izq_vision` , `T2`.`membrana_der` , 
										`T2`.`membrana_izq` ,  `T2`.`porciento_hbc`, `T5`.`vdrl`, `T5`.`bh`, `T5`.`glicemia`,
										`T5`.`hiv`, `T5`.`tg`, `T5`.`colesterol`, `T5`.`tipo_sanguineo`,
										`T5`.`diag_laboratorio`, `T5`.`alcoholimetro`, `T5`.`espirometria`,
										`T5`.`rx_torax`, `T5`.`col_lumbrosaca`, `T5`.`diagnostico`
					  FROM  `historial_clinico` AS T1
					  JOIN  `aspectos_grales_1` AS T2 ON  `T1`.`id_historial` =  `T2`.`historial_clinico_id_historial` 
					  JOIN  `aspectos_grales_2` AS T3 ON  `T2`.`historial_clinico_id_historial` =  `T3`.`historial_clinico_id_historial` 
					  JOIN  `antecedentes_fam` AS T4 ON  `T3`.`historial_clinico_id_historial` =  `T4`.`historial_clinico_id_historial` 
					  JOIN  `laboratorio` AS T5 ON  `T4`.`historial_clinico_id_historial` =  `T5`.`historial_clinico_id_historial`
					  WHERE fecha_exp BETWEEN '$fechaI' AND '$fechaF' ORDER BY id_historial";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Historiales Clinicos Registrados del $fechaIni al $fechaFin";
		}
		else if($clasificacion!="" && $tipo!=""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT DISTINCT 	`T1`.`id_historial` , `T1`.`puesto_realizar` , `T1`.`nom_empleado` , `T1`.`escolaridad` , 
										`T4`.`peso_kg` , `T4`.`talla_mts` ,  `T4`.`pres_arterial` , `T4`.`imc` ,
										`T2`.`lentes` ,  `T2`.`ojo_der_vision` , `T2`.`ojo_izq_vision` , `T2`.`membrana_der` , 
										`T2`.`membrana_izq` ,  `T2`.`porciento_hbc`, `T5`.`vdrl`, `T5`.`bh`, `T5`.`glicemia`,
										`T5`.`hiv`, `T5`.`tg`, `T5`.`colesterol`, `T5`.`tipo_sanguineo`,
										`T5`.`diag_laboratorio`, `T5`.`alcoholimetro`, `T5`.`espirometria`,
										`T5`.`rx_torax`, `T5`.`col_lumbrosaca`, `T5`.`diagnostico`
					  FROM  `historial_clinico` AS T1
					  JOIN  `aspectos_grales_1` AS T2 ON  `T1`.`id_historial` =  `T2`.`historial_clinico_id_historial` 
					  JOIN  `aspectos_grales_2` AS T3 ON  `T2`.`historial_clinico_id_historial` =  `T3`.`historial_clinico_id_historial` 
					  JOIN  `antecedentes_fam` AS T4 ON  `T3`.`historial_clinico_id_historial` =  `T4`.`historial_clinico_id_historial` 
					  JOIN  `laboratorio` AS T5 ON  `T4`.`historial_clinico_id_historial` =  `T5`.`historial_clinico_id_historial`
					  WHERE `T1`.`fecha_exp` BETWEEN '$fechaI' AND '$fechaF' AND `T1`.`clasificacion_exa`='$clasificacion' AND `T1`.`tipo_clasificacion`='$tipo'";
			 
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Historiales Clinicos del $fechaIni al $fechaFin de Examenes $clasificacion de Tipo $tipo";
		}
		else if($clasificacion!="" && $tipo==""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT DISTINCT 	`T1`.`id_historial` , `T1`.`puesto_realizar` , `T1`.`nom_empleado` , `T1`.`escolaridad` , 
										`T4`.`peso_kg` , `T4`.`talla_mts` ,  `T4`.`pres_arterial` , `T4`.`imc` ,
										`T2`.`lentes` ,  `T2`.`ojo_der_vision` , `T2`.`ojo_izq_vision` , `T2`.`membrana_der` , 
										`T2`.`membrana_izq` ,  `T2`.`porciento_hbc`, `T5`.`vdrl`, `T5`.`bh`, `T5`.`glicemia`,
										`T5`.`hiv`, `T5`.`tg`, `T5`.`colesterol`, `T5`.`tipo_sanguineo`,
										`T5`.`diag_laboratorio`, `T5`.`alcoholimetro`, `T5`.`espirometria`,
										`T5`.`rx_torax`, `T5`.`col_lumbrosaca`, `T5`.`diagnostico`
					  FROM  `historial_clinico` AS T1
					  JOIN  `aspectos_grales_1` AS T2 ON  `T1`.`id_historial` =  `T2`.`historial_clinico_id_historial` 
					  JOIN  `aspectos_grales_2` AS T3 ON  `T2`.`historial_clinico_id_historial` =  `T3`.`historial_clinico_id_historial` 
					  JOIN  `antecedentes_fam` AS T4 ON  `T3`.`historial_clinico_id_historial` =  `T4`.`historial_clinico_id_historial` 
					  JOIN  `laboratorio` AS T5 ON  `T4`.`historial_clinico_id_historial` =  `T5`.`historial_clinico_id_historial`
			WHERE fecha_exp BETWEEN '$fechaI' AND '$fechaF' AND clasificacion_exa='$clasificacion' ";

			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Historiales Clinicos Registradas del $fechaIni al $fechaFin de Examenes $clasificacion";
		}
		else if($clasificacion=="" && $tipo!=""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT DISTINCT 	`T1`.`id_historial` , `T1`.`puesto_realizar` , `T1`.`nom_empleado` , `T1`.`escolaridad` , 
										`T4`.`peso_kg` , `T4`.`talla_mts` ,  `T4`.`pres_arterial` , `T4`.`imc` ,
										`T2`.`lentes` ,  `T2`.`ojo_der_vision` , `T2`.`ojo_izq_vision` , `T2`.`membrana_der` , 
										`T2`.`membrana_izq` ,  `T2`.`porciento_hbc`, `T5`.`vdrl`, `T5`.`bh`, `T5`.`glicemia`,
										`T5`.`hiv`, `T5`.`tg`, `T5`.`colesterol`, `T5`.`tipo_sanguineo`,
										`T5`.`diag_laboratorio`, `T5`.`alcoholimetro`, `T5`.`espirometria`,
										`T5`.`rx_torax`, `T5`.`col_lumbrosaca`, `T5`.`diagnostico`
					  FROM  `historial_clinico` AS T1
					  JOIN  `aspectos_grales_1` AS T2 ON  `T1`.`id_historial` =  `T2`.`historial_clinico_id_historial` 
					  JOIN  `aspectos_grales_2` AS T3 ON  `T2`.`historial_clinico_id_historial` =  `T3`.`historial_clinico_id_historial` 
					  JOIN  `antecedentes_fam` AS T4 ON  `T3`.`historial_clinico_id_historial` =  `T4`.`historial_clinico_id_historial` 
					  JOIN  `laboratorio` AS T5 ON  `T4`.`historial_clinico_id_historial` =  `T5`.`historial_clinico_id_historial`
			WHERE fecha_exp BETWEEN '$fechaI' AND '$fechaF'  AND tipo_clasificacion='$tipo' ";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Historiales Clinicos Registradas del $fechaIni al $fechaFin de los Examenes de Tipo $tipo";
		}
		//Ejecutar la sentencia SQL				
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosRepHC'>
				<caption class='titulo_etiqueta'>$titulo</caption>
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>CLAVE HISTORIAL</th>
						<th class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</th>
				        <th class='nombres_columnas' align='center'>PUESTO</th>
        				<th class='nombres_columnas' align='center'>ESCOLARIDAD</th>
						<th class='nombres_columnas' align='center'>PESO (KG)</th>
						<th class='nombres_columnas' align='center'>TALLA (MTS.)</th>
        				<th class='nombres_columnas' align='center'>PRESION ARTERIAL</th>
        				<th class='nombres_columnas' align='center'>IMC</th>
        				<th class='nombres_columnas' align='center'>LENTES</th>
        				<th class='nombres_columnas' align='center'>VISI&Oacute;N DER.</th>
						<th class='nombres_columnas' align='center'>VISI&Oacute;N IZQ.</th>
						<th class='nombres_columnas' align='center'>MEMBRANA DER.</th>
						<th class='nombres_columnas' align='center'>MEMBRANA IZQ.</th>
						<th class='nombres_columnas' align='center'>HBC %</th>
        				<th class='nombres_columnas' align='center'>VDRL</th>					
        				<th class='nombres_columnas' align='center'>B.H.</th>					
        				<th class='nombres_columnas' align='center'>GLICEMIA</th>					
        				<th class='nombres_columnas' align='center'>HIV</th>					
        				<th class='nombres_columnas' align='center'>TG</th>																							
        				<th class='nombres_columnas' align='center'>COLESTEROL</th>
						<th class='nombres_columnas' align='center'>TIPO SANGUINEO</th>
						<th class='nombres_columnas' align='center'>DIAG. LABORATORIO</th>
						<th class='nombres_columnas' align='center'>ALCOHOLIMETRO</th>
						<th class='nombres_columnas' align='center'>ESPIROMETRIA</th>
						<th class='nombres_columnas' align='center'>RX DE T&Oacute;RAX</th>
						<th class='nombres_columnas' align='center'>COL. LUMBOSACRA</th>
						<th class='nombres_columnas' align='center'>DIAGN&Oacute;STICOS</th>											
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{
				//Obtener el Id del Historial Medico
				$idHis=obtenerDato("bd_clinica","historial_clinico", "id_historial", "id_historial",$datos['id_historial']);
				//Obtener el nombre de la empresa de cauerdo al id del historial clinico
				$empresa=obtenerDato("bd_clinica","historial_clinico", "nom_empresa", "id_historial", $datos['id_historial']);
				echo "	<tr>
							</td>
							<td class='$nom_clase' align='center'>$datos[id_historial]</td>
							<td class='$nom_clase' align='center'>$datos[nom_empleado]</td>
							<td class='$nom_clase' align='center'>$datos[puesto_realizar]</td>
							<td class='$nom_clase' align='center'>$datos[escolaridad]</td>
							<td class='$nom_clase' align='center'>$datos[peso_kg]</td>
							<td class='$nom_clase' align='center'>$datos[talla_mts]</td>
							<td class='$nom_clase' align='center'>$datos[pres_arterial]</td>
							<td class='$nom_clase' align='center'>$datos[imc]</td>
							<td class='$nom_clase' align='center'>$datos[lentes]</td>
							<td class='$nom_clase' align='center'>$datos[ojo_der_vision]</td>
							<td class='$nom_clase' align='center'>$datos[ojo_izq_vision]</td>
							<td class='$nom_clase' align='center'>$datos[membrana_der]</td>
							<td class='$nom_clase' align='center'>$datos[membrana_izq]</td>
							<td class='$nom_clase' align='center'>$datos[porciento_hbc]</td>
							<td class='$nom_clase' align='center'>$datos[vdrl]</td>
							<td class='$nom_clase' align='center'>$datos[bh]</td>
							<td class='$nom_clase' align='center'>$datos[glicemia]</td>
							<td class='$nom_clase' align='center'>$datos[hiv]</td>
							<td class='$nom_clase' align='center'>$datos[tg]</td>
							<td class='$nom_clase' align='center'>$datos[colesterol]</td>
							<td class='$nom_clase' align='center'>$datos[tipo_sanguineo]</td>
							<td class='$nom_clase' align='center'>$datos[diag_laboratorio]</td>
							<td class='$nom_clase' align='center'>$datos[alcoholimetro]</td>
							<td class='$nom_clase' align='center'>$datos[espirometria]</td>
							<td class='$nom_clase' align='center'>$datos[rx_torax]</td>
							<td class='$nom_clase' align='center'>$datos[col_lumbrosaca]</td>
							<td class='$nom_clase' align='center'>$datos[diagnostico]</td>";?>
				<?php /*if($idHis!=""){
						?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verExaMed<?php echo $cont?>" id="btn_verExaMed<?php echo $cont?>" class="botones" value="Historial Clinico" 
							onMouseOver="window.estatus='';return true" title="Ver Historial M&eacute;dico" 
							onClick="javascript:window.open('../../includes/generadorPDF/historialClinico.php?id=<?php echo $idHis?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>							
						</td>						
					</tr>
				<?php
				}
				else{
				?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verExaMed<?php echo $cont?>" id="btn_verExaMed<?php echo $cont?>" class="botones" value="Historial Clinico" 
							title="El Trabajador no Tiene un Historial Clinico Asociado" disabled="disabled"/>
						</td>						
					</tr>
				<?php
				}	*/			
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
			echo "<meta http-equiv='refresh' content='0;url=frm_reporteHistorialesClinicos.php?noResults'>";
		}
		mysql_close($conn);
	}
	
	function datosGrafica($fechaIni,$fechaFin,$titulo,$clasificacion,$tipo){
		//Para cuando el examen es para trabajadores internos
		if($tipo=="INTERNO"){
			//Sentencia para extraer los historiales medicos de CLF
			$sql="SELECT COUNT(*) AS cant,nom_empresa,razon_social FROM historial_clinico WHERE tipo_clasificacion='INTERNO' AND fecha_exp BETWEEN '$fechaIni' AND '$fechaFin'";
			if($clasificacion!="")
				$sql.=" AND tipo_clasificacion='$tipo'";
		}
		//Para cuando el examen es para trabajadores de otras empresas
		if($tipo=="EXTERNO"){
			//Sentencia para extraer los historiales medicos de CLF
			$sql="SELECT COUNT(*) AS cant,nom_empresa,razon_social FROM historial_clinico WHERE tipo_clasificacion='EXTERNO' AND fecha_exp BETWEEN '$fechaIni' AND '$fechaFin'";
			if($clasificacion!="")
				$sql.=" AND tipo_clasificacion='$tipo'";
			$sql.=" GROUP BY nom_empresa ORDER BY nom_empresa";
		}
		//Para cuando son para ambos, internos y externos
		if($tipo==""){
			//Sentencia para extraer los historiales medicos de CLF
			$sql="SELECT COUNT(*) AS cant,nom_empresa,razon_social FROM historial_clinico WHERE fecha_exp BETWEEN '$fechaIni' AND '$fechaFin'";
			if($clasificacion!="")
				$sql.=" AND tipo_clasificacion='$tipo'";
			$sql.=" GROUP BY nom_empresa ORDER BY tipo_clasificacion DESC";
		}
		//Ejecutar sentencia
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			//Arreglos con las empresas y la cantidad de Exámenes por cada una de ellas
			$ejeX=array();
			$cantHistoriales=array();
			$color=array();
			do{
				$ejeX[]=$datos["nom_empresa"];
				$cantHistoriales[]=$datos["cant"];
				if($datos["nom_empresa"]=="CLF")
					$color[]="darkgreen";
				else{
					$infoColor=mysql_fetch_array(mysql_query("SELECT color FROM catalogo_empresas WHERE razon_social='$datos[razon_social]'"));
					$color[]=$infoColor["color"];
				}
			}while($datos=mysql_fetch_array($rs));
			$grafica=dibujarGrafica($cantHistoriales,$ejeX,$color,$titulo);
			return $grafica;
		}
	}
	
	function dibujarGrafica($cantHistoriales,$ejeX,$color,$titulo){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		// Create the graph. These two calls are always required
		$graph = new Graph(800,600);    
		$graph->SetScale("textlin");
		$graph->SetShadow();
		$graph->img->SetMargin(100,60,60,60);
		$cont=0;
		do{
			$datay=$cantHistoriales[$cont];
			// Crear las barras
			$bplot = new BarPlot($datay);
			$bplot->SetWidth(30);
			$bplot->SetFillColor($color[$cont]);
			$bplot->SetLegend($ejeX[$cont]);
			$bplot->SetCenter();
			
			$bplot->value->Show();
			$bplot->value->SetFont(FF_ARIAL,FS_BOLD,10);
			$bplot->value->SetColor('navy');
			$barras[]=$bplot;
			$cont++;
		}while($cont<count($cantHistoriales));
		// Crear el Grupo de Barras
		$gbplot = new GroupBarPlot($barras);
		// ...y agregarlo a la grafica
		$graph->Add($gbplot);
		$graph->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->title->Set($titulo);
		$graph->title->SetColor('darkred');
		// Eje X
		$graph->xgrid->Show();
		$graph->yaxis->title->Set('Cantidad');
		$graph->yaxis->title->SetColor('darkred');
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->yaxis->SetTitleMargin(60);
		$graph->yaxis->scale->SetGrace(10);
		// Eje Y con espacio Vacio para no mostrar nada
		$graph->xaxis->SetTickLabels(" ");
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,12);
		//Pie de la grafica con el titulo
		$graph->footer->center->Set("Exámenes Clínicos");
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		//Titulo de la grafica
		$graph->title->SetFont(FF_FONT1,FS_BOLD);
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
?>
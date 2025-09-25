<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 13/Marzo/2012
	  * Descripción: Este archivo permite consultar y generar la información relacionada con el acta de incidentes accidentes
	  **/
	  
	  //Funcion que permite mostrar las Actas regisatradas  	
	function mostrarInformeAreas($area){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Variable que permite conocer si hubo resultados de la consulta; si esta variable permanece en 0; quiere decir que no se encontraron resultados
		$band =0;
		
		//Modificamos las fechas para poder realizar la consulta en la base de datos
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		
		//Creamos la sentencia SQL correspondiente a las fechas
		$stm_sql = "SELECT * FROM accidentes_incidentes WHERE area = '$area' AND fecha_accidente>='$fechaIni'  AND fecha_accidente<='$fechaFin'  ORDER BY area";
		
		//Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
		$titulo = "Informes de Accidentes e Incidentes Registrados  Para el &Aacute;rea $area de $_POST[txt_fechaIni] a $_POST[txt_fechaFin]";
		
		//Titulo en caso de no haberse encontrado registros
		$noTitulo = "<label class='msje_correcto'>No existen Informes de Accidentes e Incidentes Registrados  Para el &Aacute;rea $area de $_POST[txt_fechaIni] a $_POST[txt_fechaFin]</label>";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			$band = 1;
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosConsulta'> 
				<thead>";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>NO. ACCIDENTE</th>
						<th class='nombres_columnas' align='center'>CLAVE INFORME</th>
						<th class='nombres_columnas' align='center'>EMPLEADO</th>
						<th class='nombres_columnas' align='center'>&Aacute;REA DE TRABAJO</th>
						<th class='nombres_columnas' align='center'>PUESTO</th>
						<th class='nombres_columnas' align='center'>TURNO</th>
						<th class='nombres_columnas' align='center'>TIPO DE INFORME</th>
						<td class='nombres_columnas' align='center'>LUGAR ACCIDENTE</td>
						<td class='nombres_columnas' align='center'>FECHA ACCIDENTE</td>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";	
			do{			
				//Obtenemos el nombre del Empleado
				$nombreEmpleado = obtenerNombreEmpleado($datos['empleados_rfc_empleado']);
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					 <td class='$nom_clase'>$cont</td>
					 <td class='$nom_clase'>$datos[id_informe]</td>
					 <td class='$nom_clase'>$nombreEmpleado</td>
					 <td class='$nom_clase'>$datos[area]</td>
					 <td class='$nom_clase'>$datos[puesto]</td>
					 <td class='$nom_clase'>$datos[turno]</td>
					 <td class='$nom_clase'>$datos[tipo_informe]</td>
					 <td class='$nom_clase'>$datos[area_acci]</td>
					 <td class='$nom_clase'>".modFecha($datos['fecha_accidente'],1)."</td>";?>
			<?php 
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";
			//Funcion que permite  mostrar la grafica
			mostrarRegArea($fechaIni, $fechaFin, $stm_sql, $titulo, $area);
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $noTitulo;?>
			</div>
			<div id="btn-regresar" align="center">
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la P&aacute;gina Anterior" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_reporteIncidentesAccidentes.php'" />
          </div><?php 
			return 0;
		}?>							
		<?php
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion

	 
	//Funcion que realiza el proceso de generaciond ela grafica  	
	function mostrarRegArea($fechaIni, $fechaFin, $stm_sqlPrinc, $msg, $area){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Variable que nos permtie conocer si la consulta arrojo resultados; si permanece en 0 quiere decir que no hubo ningun resultado
		$band =0;
		
		//Creamos la sentencia SQL correspondiente a las fechas
		$stm_sql = "SELECT * FROM accidentes_incidentes WHERE fecha_accidente>='$fechaIni'  AND fecha_accidente<='$fechaFin' ORDER BY area";
					
		//Variable para almacenar el area actual
		$areaAct = "";
			
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			//Cambiamos el valor de la variable bandera a 1
			$band = 1;
			//Iniciamos el contador
			$cont = 1;
			do{
				//Comprobamos que el contador se encuentre en uno de ser asi es el primer registro; por ello se permite la entrada al ciclo
				//O comprobamso que el area sea diferente a la almacenada y de ser asi permitimos la entrada			
				if($cont==1||$datos['area']!=$areaAct){
					//Guardamos el valor del area actual
					$areaAct = $datos['area'];
					
					//Realizar la conexion a la BD 
					$conn = conecta("bd_seguridad");
					//Consulta para conocer el numero de accidentes e incidentes por un area especifica
					$stm_deptoInc = "SELECT COUNT(*) AS cant FROM accidentes_incidentes WHERE area='$datos[area]' AND tipo_informe='INCIDENTE'";
					//Ejecutamos la consulta rpeviamente creada
					$rsDptoInc = mysql_query($stm_deptoInc);
					//Guardamos el resultado en un arreglo de datos
					$datosDptoInc = mysql_fetch_array($rsDptoInc);
					//Guardamos el valor de la cantidad
					$cantInc = $datosDptoInc['cant'];
					//Guardamos el valor en el arreglo anteriormente creado
					$dptosInc[] = $cantInc;
					
					//Guardamos el departamento Actual
					$departamentos [] = $areaAct;
					
					//Consulta para conocer el numero de accidentes e incidentes por un area especifica
					$stm_deptoAcc = "SELECT COUNT(*) AS cant FROM accidentes_incidentes WHERE area='$datos[area]' AND tipo_informe='ACCIDENTE'";
					//Ejecutamos la consulta rpeviamente creada
					$rsDptoAcc = mysql_query($stm_deptoAcc);
					//Guardamos el resultado en un arreglo de datos
					$datosDptoAcc = mysql_fetch_array($rsDptoAcc);
					//Guardamos el valor de la cantidad
					$cantAcc = $datosDptoAcc['cant'];					
					//Guardamos el valor en el arreglo anteriormente creado
					$dptosAcc[] = $cantAcc;
				}
				//Incrementamos el contador
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			//Obtenemos la grafica para mostrar
			$grafo=graficaAccIncAreas($dptosAcc,$dptosInc, $departamentos);?>
			</div>
			<div id="btn-regresar" align="center">
				<?php if($band!=0){?>
	        	<input name="btn_verGrafica" type="button" id="btn_verGrafica" class="botones" value="Ver Gr&aacute;fica" 
				title="Ver Gr&aacute;fica de Incides/Accidentes" 
				onMouseOver="window.status='';return true" onclick="graficaAccInc('<?php echo $grafo;?>');" />	
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="sbt_exportar" type="submit" id="sbt_exportar" class="botones_largos" value="Exportar a Excel" 
				title="Exportar a Excel" onMouseOver="window.status='';return true"/>						
				<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="<?php echo $stm_sqlPrinc;?>"/>
				<input type="hidden" name="hdn_tipoReporte" id="hdn_tipoReporte" value="reporteIncidentesAccidentes"/>
				<input type="hidden" name="hdn_nomReporte" id="hdn_nomReporte" value="reporteIncidentesAccidentesArea_<?php echo $area;?>"/>
				<input type="hidden" name="hdn_msg" id="hdn_msg" value="<?php echo $msg;?>"/>
				<?php }?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la P&aacute;gina Anterior" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_reporteIncidentesAccidentes.php'" />
          </div><?php
			return 1;
		}
		else{
			return 0;
		}?>							
		<?php
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion

	
	
	//Grafica de Accidentes Incidentes por AREA
	function graficaAccIncAreas($dptosAcc,$dptosInc, $departamentos){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
		
		$datay1=$dptosAcc;
		$datay2=$dptosInc;

		// Crear la grafica
		$graph = new Graph(740,500);
		$graph->SetScale('textlin');
		$graph->SetMarginColor('white');
		$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,10);
		$graph->img->SetMargin(30,25,30,100);
		
		// Escribir los valores para el eje de las x
		$graph->xaxis->SetTickLabels($departamentos);
		$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);

		// Setup title
		$graph->title->Set('Gráfica de Accidentes Vs Incidentes por Área');

		// Create la primer barra
		$bplot = new BarPlot($datay1);
		$bplot->SetFillGradient('AntiqueWhite2','AntiqueWhite4:0.8',GRAD_VERT);
		$bplot->SetColor('darkred');
		$bplot->SetWeight(0);
		$bplot->SetLegend("ACCIDENTE");
		

		// Crar la segunda barra
		$bplot2 = new BarPlot($datay2);
		$bplot2->SetFillGradient('olivedrab1','olivedrab4',GRAD_VERT);
		$bplot2->SetColor('darkgreen');
		$bplot2->SetWeight(0);
		$bplot2->SetLegend("INCIDENTE");

		// And join them in an accumulated bar
		$accbplot = new AccBarPlot(array($bplot,$bplot2));
		$accbplot->SetColor('darkgray');
		$accbplot->SetWeight(1);
		$graph->Add($accbplot);
	
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= 'tmp/grafica'.$rnd.'.png';
		
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Devolver el nombre de la grafica para poder identificarla y colocarla en el reporte que se exporta y/o en el div donde se muestra
		return $grafica;
	}//Fin de function 
	
	
	/******************************************************TURNO*******************************************************************************************/

	 //Funcion que permite mostra los informes registrados por turno  	
	function mostrarInformeTurno($turno){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Variable que permite conocer si hubo resultados en la consulta; si permanece en cero quiere decir que no hubo tal elemento
		$band =0;
		
		//Modificamos las fechas para poder realizar la consulta en la base de datos
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		
		//Creamos la sentencia SQL correspondiente a las fechas
		$stm_sql = "SELECT * FROM accidentes_incidentes WHERE turno = '$turno' AND fecha_accidente>='$fechaIni'  AND fecha_accidente<='$fechaFin' ORDER BY turno ASC";
		
		//Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
		$titulo = "Informes de Accidentes e Incidentes Registrados  Para el Turno $turno de $_POST[txt_fechaIni] a $_POST[txt_fechaFin]";
		
		//Titulo en caso de no encontrarse resultados
		$notitulo = "<label class='msje_correcto'>No existen Informes de Accidentes e Incidentes Registrados Para el Turno $turno de  $_POST[txt_fechaIni] a $_POST[txt_fechaFin]</label>";
			
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			$band = 1;
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosConsulta'> 
				<thead>";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>NO. ACCIDENTE</th>
						<th class='nombres_columnas' align='center'>CLAVE INFORME</th>
						<th class='nombres_columnas' align='center'>EMPLEADO</th>
						<th class='nombres_columnas' align='center'>&Aacute;REA DE TRABAJO</th>
						<th class='nombres_columnas' align='center'>PUESTO</th>
						<th class='nombres_columnas' align='center'>TURNO</th>
						<th class='nombres_columnas' align='center'>TIPO DE INFORME</th>
						<td class='nombres_columnas' align='center'>LUGAR ACCIDENTE</td>
						<td class='nombres_columnas' align='center'>FECHA ACCIDENTE</td>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";	
			do{			
				//Obtenemos el nombre del Empleado
				$nombreEmpleado = obtenerNombreEmpleado($datos['empleados_rfc_empleado']);
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					 <td class='$nom_clase'>$cont</td>
					 <td class='$nom_clase'>$datos[id_informe]</td>
					 <td class='$nom_clase'>$nombreEmpleado</td>
					 <td class='$nom_clase'>$datos[area]</td>
					 <td class='$nom_clase'>$datos[puesto]</td>
					 <td class='$nom_clase'>$datos[turno]</td>
					 <td class='$nom_clase'>$datos[tipo_informe]</td>
					 <td class='$nom_clase'>$datos[area_acci]</td>
					 <td class='$nom_clase'>".modFecha($datos['fecha_accidente'],1)."</td>";?>
			<?php 
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";
			//Funcion que nos permite mostrar la grafica correspondiente
			mostrarRegTurno($fechaIni, $fechaFin,  $stm_sql, $titulo, $turno);
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $noTitulo;?>
			</div>
			<div id="btn-regresar" align="center">
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la P&aacute;gina Anterior" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_reporteIncidentesAccidentes.php'" />
          </div><?php 
			return 0;
		}?>							
		<?php
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion

	 
	//Funcion que permite mostrar los informes registrados con su respectiva area  	
	function mostrarRegTurno($fechaIni, $fechaFin,  $stm_sqlPrinc, $msg, $turno){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Variable que nos permite conocer si hubo resultados; si no existieron tales elementos la variable permanecera en 0
		$band =0;
		
		//Variable para almacenar el turno actual
		$turno = "";
		//Creamos la sentencia SQL correspondiente a las fechas
		$stm_sql = "SELECT * FROM accidentes_incidentes WHERE fecha_accidente>='$fechaIni'  AND fecha_accidente<='$fechaFin' ORDER BY turno ASC";
			
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			//Cambiamos el valor de la bandera
			$band = 1;
			//Iniciamos el contador
			$cont = 1;
			do{			
				//Si el contador se encuentra en uno; entrara por primeta vez; de lo contrario verificamos que el area sea diferente para almacenar el valor en el
				// arreglo 
				if($cont==1||$datos['turno']!=$turnoAct){
					//Guardamos el valor del area actual
					$turnoAct = $datos['turno'];
					
					//Realizar la conexion a la BD 
					$conn = conecta("bd_seguridad");
					//Consulta para conocer el numero de accidentes e incidentes por un turno especifico
					$stm_deptoInc = "SELECT COUNT(*) AS cant FROM accidentes_incidentes WHERE turno='$datos[turno]' AND tipo_informe='INCIDENTE'";
					//Ejecutamos la consulta rpeviamente creada
					$rsDptoInc = mysql_query($stm_deptoInc);
					//Guardamos el resultado en un arreglo de datos
					$datosDptoInc = mysql_fetch_array($rsDptoInc);
					//Guardamos el valor de la cantidad
					$cantInc = $datosDptoInc['cant'];
					//Guardamos el valor en el arreglo anteriormente creado
					$turnoInc[] = $cantInc;
					
					//Guardamos el turno Actual
					$turnos [] = $turnoAct;
					
					//Consulta para conocer el numero de accidentes e incidentes por un turno especifico
					$stm_deptoAcc = "SELECT COUNT(*) AS cant FROM accidentes_incidentes WHERE turno='$datos[turno]' AND tipo_informe='ACCIDENTE'";
					//Ejecutamos la consulta rpeviamente creada
					$rsDptoAcc = mysql_query($stm_deptoAcc);
					//Guardamos el resultado en un arreglo de datos
					$datosDptoAcc = mysql_fetch_array($rsDptoAcc);
					//Guardamos el valor de la cantidad
					$cantAcc = $datosDptoAcc['cant'];					
					//Guardamos el valor en el arreglo anteriormente creado
					$turnoAcc[] = $cantAcc;
				}
				//Incrementamos el contador
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			//Obtenemos la grafica mostrada	
			$grafo=graficaAccIncTurnos($turnoAcc,$turnoInc, $turnos);?>
			</div>
			<div id="btn-regresar" align="center">
				<?php if($band!=0){?>
	        	<input name="btn_verGrafica" type="button" id="btn_verGrafica" class="botones" value="Ver Gr&aacute;fica" 
				title="Ver Gr&aacute;fica de Incides/Accidentes" 
				onMouseOver="window.status='';return true" onclick="graficaAccInc('<?php echo $grafo;?>');" />	
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="sbt_exportar" type="submit" id="sbt_exportar" class="botones_largos" value="Exportar a Excel" 
				title="Exportar a Excel" onMouseOver="window.status='';return true"/>						
				<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="<?php echo $stm_sqlPrinc;?>"/>
				<input type="hidden" name="hdn_tipoReporte" id="hdn_tipoReporte" value="reporteIncidentesAccidentes"/>
				<input type="hidden" name="hdn_nomReporte" id="hdn_nomReporte" value="reporteIncidentesAccidentesTurno_<?php echo $turno;?>"/>
				<input type="hidden" name="hdn_msg" id="hdn_msg" value="<?php echo $msg;?>"/>			
				<?php }?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la P&aacute;gina Anterior" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_reporteIncidentesAccidentes.php'" />
          </div><?php
			return 1;
		}
		else{
			return 0;
		}?>							
		<?php
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion

	
	
	//Funcion que nos permite mostrar la grafica por turno
	function graficaAccIncTurnos($turnoAcc,$turnoInc, $turnos){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
		
		$datay1=$turnoAcc;
		$datay2=$turnoInc;

		// Crear la grafica
		$graph = new Graph(740,500);
		$graph->SetScale('textlin');
		$graph->SetMarginColor('white');
		$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,10);
		$graph->img->SetMargin(30,25,30,100);
		
		// Escribir los valores para el eje de las x
		$graph->xaxis->SetTickLabels($turnos);
		$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);

		// Setup title
		$graph->title->Set('Gráfica de Accidentes Vs Incidentes Por Turno');

		// Create la primer barra
		$bplot = new BarPlot($datay1);
		$bplot->SetFillGradient('AntiqueWhite2','AntiqueWhite4:0.8',GRAD_VERT);
		$bplot->SetColor('darkred');
		$bplot->SetWeight(0);
		$bplot->SetLegend("ACCIDENTE");
		

		// Crar la segunda barra
		$bplot2 = new BarPlot($datay2);
		$bplot2->SetFillGradient('olivedrab1','olivedrab4',GRAD_VERT);
		$bplot2->SetColor('darkgreen');
		$bplot2->SetWeight(0);
		$bplot2->SetLegend("INCIDENTE");

		// And join them in an accumulated bar
		$accbplot = new AccBarPlot(array($bplot,$bplot2));
		$accbplot->SetColor('darkgray');
		$accbplot->SetWeight(1);
		$graph->Add($accbplot);
	
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= 'tmp/grafica'.$rnd.'.png';
		
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Devolver el nombre de la grafica para poder identificarla y colocarla en el reporte que se exporta y/o en el div donde se muestra
		return $grafica;
	}//Fin de function 
	
	
	
	//Funcion Borrar Temproales
	function borrarGraficoSeguridad(){
		//Borrar los ficheros temporales
		$h=opendir('tmp/');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				@unlink("tmp/".$file);
			}
		}
		closedir($h);
	}
	
	
	/******************************************************TIPO INFORME*******************************************************************************************/
	
	 //Funcion que permite mostrar los incidentes registrados por tipo de informe  	
	function mostrarInformeTipo($tipo){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Variable que nos permtie conocer si hubo resultados; de no existir dichos elementos la variable permanecera en cero (0)
		$band =0;
		
		//Modificamos las fechas para poder realizar la consulta en la base de datos
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		
		//Creamos la sentencia SQL correspondiente a las fechas
		$stm_sql = "SELECT * FROM accidentes_incidentes WHERE tipo_informe = '$tipo' AND fecha_accidente>='$fechaIni'  AND fecha_accidente<='$fechaFin'  
			ORDER BY area";
			
		//Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
		$titulo = "Informes de Accidentes e Incidentes Registrados  Para el Tipo de Informe  $tipo de $_POST[txt_fechaIni] a $_POST[txt_fechaFin]";
		
		//Titulo para mostrar en caso de no haberse encontrado resultados
		$noTitulo = "<label class='msje_correcto'>No existen Informes de Accidentes e Incidentes Registrados del Tipo de Informe $tipo de $_POST[txt_fechaIni] a $_POST[txt_fechaFin]</label>";
			
		//Variable para conocer el area actual
		$areaAct = "";
			
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			$band = 1;
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosConsulta'> 
				<thead>";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>NO. ACCIDENTE</th>
						<th class='nombres_columnas' align='center'>CLAVE INFORME</th>
						<th class='nombres_columnas' align='center'>EMPLEADO</th>
						<th class='nombres_columnas' align='center'>&Aacute;REA DE TRABAJO</th>
						<th class='nombres_columnas' align='center'>PUESTO</th>
						<th class='nombres_columnas' align='center'>TURNO</th>
						<th class='nombres_columnas' align='center'>TIPO DE INFORME</th>
						<td class='nombres_columnas' align='center'>LUGAR ACCIDENTE</td>
						<td class='nombres_columnas' align='center'>FECHA ACCIDENTE</td>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";	
			do{			
				//Obtenemos el nombre del Empleado
				$nombreEmpleado = obtenerNombreEmpleado($datos['empleados_rfc_empleado']);
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					 <td class='$nom_clase'>$cont</td>
					 <td class='$nom_clase'>$datos[id_informe]</td>
					 <td class='$nom_clase'>$nombreEmpleado</td>
					 <td class='$nom_clase'>$datos[area]</td>
					 <td class='$nom_clase'>$datos[puesto]</td>
					 <td class='$nom_clase'>$datos[turno]</td>
					 <td class='$nom_clase'>$datos[tipo_informe]</td>
					 <td class='$nom_clase'>$datos[area_acci]</td>
					 <td class='$nom_clase'>".modFecha($datos['fecha_accidente'],1)."</td>";?>
			<?php 
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";
			//Llamada a la Funcion para mostrar la grafica
			mostrarRegTipo($fechaIni, $fechaFin, $stm_sql, $titulo, $tipo);
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $noTitulo;?>
			</div>
			<div id="btn-regresar" align="center">
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la P&aacute;gina Anterior" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_reporteIncidentesAccidentes.php'" />
          </div><?php 
			return 0;
		}?>							
		<?php
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion

	 
	//Funcion que permite crear la grafica con los parametros enviados 	
	function mostrarRegTipo($fechaIni, $fechaFin, $stm_sqlPrinc, $msg, $tipo){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Consulta para conocer el numero de accidentes e incidentes por un tipo de informe especifico
		$stm_deptoInc = "SELECT COUNT(*) AS cant FROM accidentes_incidentes WHERE tipo_informe='INCIDENTE' AND fecha_accidente>='$fechaIni'  
			AND fecha_accidente<='$fechaFin'";
		//Ejecutamos la consulta rpeviamente creada
		$rsDptoInc = mysql_query($stm_deptoInc);
		//Guardamos el resultado en un arreglo de datos
		$datosDptoInc = mysql_fetch_array($rsDptoInc);
		//Guardamos el valor de la cantidad
		$cantInc = $datosDptoInc['cant'];
		//Guardamos el valor en el arreglo anteriormente creado
		$tipoInc[] = $cantInc;
		
		//Guardamos el tipo de informe Actual
		$tipos [] = "INCIDENTE                                           ACCIDENTE";
		
		//Consulta para conocer el numero de accidentes e incidentes por un area especifica
		$stm_deptoAcc = "SELECT COUNT(*) AS cant FROM accidentes_incidentes WHERE  tipo_informe='ACCIDENTE' AND fecha_accidente>='$fechaIni'  
			AND fecha_accidente<='$fechaFin'";
		//Ejecutamos la consulta rpeviamente creada
		$rsDptoAcc = mysql_query($stm_deptoAcc);
		//Guardamos el resultado en un arreglo de datos
		$datosDptoAcc = mysql_fetch_array($rsDptoAcc);
		//Guardamos el valor de la cantidad
		$cantAcc = $datosDptoAcc['cant'];					
		//Guardamos el valor en el arreglo anteriormente creado
		$tipoAcc[] = $cantAcc;
			//Obtenemos la grafica mostrada	
			$grafo=graficaAccIncTipo($tipoAcc,$tipoInc, $tipos);?>
			</div>
			<div id="btn-regresar" align="center">
	        	<input name="btn_verGrafica" type="button" id="btn_verGrafica" class="botones" value="Ver Gr&aacute;fica" 
				title="Ver Gr&aacute;fica de Incides/Accidentes" 
				onMouseOver="window.status='';return true" onclick="graficaAccInc('<?php echo $grafo;?>');" />	
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			
				<input name="sbt_exportar" type="submit" id="sbt_exportar" class="botones_largos" value="Exportar a Excel" 
				title="Exportar a Excel" onMouseOver="window.status='';return true"/>						
				<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="<?php echo $stm_sqlPrinc;?>"/>
				<input type="hidden" name="hdn_tipoReporte" id="hdn_tipoReporte" value="reporteIncidentesAccidentes"/>
				<input type="hidden" name="hdn_nomReporte" id="hdn_nomReporte" value="reporteIncidentesAccidentesTipo_<?php echo $tipo;?>"/>
				<input type="hidden" name="hdn_msg" id="hdn_msg" value="<?php echo $msg;?>"/>	
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la P&aacute;gina Anterior" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_reporteIncidentesAccidentes.php'" />
          </div><?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion

	
	//Grafica de accidentes e incidentes por Tipo de informe
	function graficaAccIncTipo($tipoAcc,$tipoInc, $tipos){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
		
		$datay1=$tipoInc;
		$datay2=$tipoAcc;

		// Crear la grafica
		$graph = new Graph(740,500);
		$graph->SetScale('textlin');
		$graph->SetMarginColor('white');
		$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,10);
		$graph->img->SetMargin(70,35,40,50);
		$graph->yaxis->title->Set("NO. ACCIDENTE/INCIDENTE");

		
		// Escribir los valores para el eje de las x
		$graph->xaxis->SetTickLabels($tipos);
		$graph->xaxis->SetFont(FF_ARIAL,FS_NORMAL,8);
		//$graph->xaxis->SetLabelAngle(45);

		// Setup title
		$graph->title->Set('Gráfica de Accidentes Vs Incidentes Por Tipo de Informe');

		// Create la primer barra
		$bplot = new BarPlot($datay1);
		$bplot->SetFillGradient('AntiqueWhite2','AntiqueWhite4:0.8',GRAD_VERT);
		$bplot->SetColor('darkgray');
		$bplot->SetWeight(0);
		$bplot->value->Show();
		$bplot->SetLegend("INCIDENTE");
		

		// Crar la segunda barra
		$bplot2 = new BarPlot($datay2);
		$bplot2->SetFillGradient('olivedrab1','olivedrab4',GRAD_VERT);
		$bplot2->SetColor('darkgreen');
		$bplot2->SetWeight(0);
		$bplot2->value->Show();
		$bplot2->SetLegend("ACCIDENTE");

		$gbplot = new GroupBarPlot(array($bplot,$bplot2));
		// ...and add it to the graPH
		$graph->Add($gbplot);
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= 'tmp/grafica'.$rnd.'.png';
		
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Devolver el nombre de la grafica para poder identificarla y colocarla en el reporte que se exporta y/o en el div donde se muestra
		return $grafica;
	}//Fin de function 
	
	
?>
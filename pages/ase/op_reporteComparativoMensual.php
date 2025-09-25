<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Maurilio Hernández Correa & Daisy Adriana Martínez Fernández
	  * Fecha: 25/Julio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Reporte comparativo mensual
	**/
	
	 //Funcion para mostrar la informacion del presupuesto seleccionado mediante los combo box de ubicación y periodo
	function mostrarPresupuestos(){
		//Conectar a la BD de producción
		$conn = conecta("bd_gerencia");
		$periodo=$_POST['cmb_periodo'];
		$ubicacion=$_POST['cmb_ubicacion'];
		
		//Obtener el nombre de la ubicacion para colocarlo en el titulo de la tabla
		$Nomubicacion= obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$ubicacion);
		
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM presupuesto WHERE periodo= '$periodo' AND catalogo_ubicaciones_id_ubicacion='$ubicacion'";	
				
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Comparativo Mensual de <em><u>  $periodo </u></em> de Ubicaci&oacute;n <em><u>  $Nomubicacion </u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Presupuesto Registrado del periodo de <em><u>  $periodo    
		 </u></em> de Ubicaci&oacute;n  <em><u>  $Nomubicacion </u></em>";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='120%'>				
				<tr>
					<td colspan='8' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>PERIODO</td>
					<td class='nombres_columnas' align='center'>FECHA INICIO</td>
					<td class='nombres_columnas' align='center'>FECHA FIN</td>
					<td class='nombres_columnas' align='center'>VOLUMEN PRESUPUESTADO MENSUAL</td>
					<td class='nombres_columnas' align='center'>VOLUMEN PRESUPUESTADO DIARIO</td>
					<td class='nombres_columnas' align='center'>D&Iacute;AS LABORABLES</td>
					<td class='nombres_columnas' align='center'>DOMINGOS</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>";?>
						<td class="nombres_filas" align="center">
						<input type="checkbox" name="ckb_idPresupuesto" id="ckb_idPresupuesto" value="<?php $datos['periodo'];?>"
						onClick="complementarReporteComparativoAseguramiento();" /><?php
				 echo " </td>
						<td class='$nom_clase' align='center'>$datos[periodo]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_inicio'],1)."</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_fin'],1)."</td>
						<td class='$nom_clase' align='center'>".number_format($datos['vol_ppto_mes'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>".number_format($datos['vol_ppto_dia'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>$datos[dias_habiles] D&iacute;as</td>
						<td class='$nom_clase' align='center'>$datos[dias_inhabiles] Domingos</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>
			<input type='hidden' name='cmb_periodo' value='$_POST[cmb_periodo]'/>";
			//Conectar a la BD de producción
			$conn = conecta("bd_recursos");
			$empleados=mysql_num_rows(mysql_query("SELECT rfc_empleado FROM empleados WHERE area='CONCRETO'"));
			echo "<input type='hidden' id='hdn_employ' name='hdn_employ' value='$empleados'/>";
			echo "<input type='hidden' id='hdn_ubicacion' name='hdn_ubicacion' value='$ubicacion'/>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	
	 //Funcion para mostrar el comparativo en el periodo seleccionado
	function mostrarComparativo(){
		//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");

		//Obtenemos la fecha inicial y final de la BD para tomarla como parametro al realizar las operaciones para realizar los calculos
		$fechaIniBD=obtenerDato("bd_gerencia", "presupuesto", "fecha_inicio", "periodo", $_POST['cmb_periodo']);
		$fechaFinBD=obtenerDato("bd_gerencia", "presupuesto", "fecha_fin", "periodo", $_POST['cmb_periodo']);
		
		
		//Obtener el nombre de la ubicacion para colocarlo en el titulo de la tabla
		$Nomubicacion= obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$_POST['hdn_ubicacion']);
		
		//Seccionamos la fecha de inicio
		$seccFechaIniBD=split("-",$fechaIniBD);
		$mesIni=$seccFechaIniBD[1];
		$anioIni=$seccFechaIniBD[0];
		$diasMesIni=diasMes($mesIni,$anioIni);
	
		//Seccionaos la fecha de Fin para obtener los conceptos de manera separada y asi pdoer procesarlos
		$seccFechaFinBD=split("-",$fechaFinBD);
		$mesFin=$seccFechaFinBD[1];
		$anioFin=$seccFechaFinBD[0];
		$diaFin=$seccFechaFinBD[2];
		$diasMesFin=diasMes($mesIni,$anioIni);
	
		//Seccionamos el combo que contiene el periodo	
		$secCombo=split("-",$_POST["cmb_periodo"]);
		$mes1=obtenerNombreCompletoMes($secCombo[1]);
		$mes2=obtenerNombreCompletoMes($secCombo[2]);
		$mes3=obtenerMesAnterior($mes1);
		$anio1=$secCombo[0];
		$anio2=$secCombo[0];
		$anio3=$secCombo[0];
		
		//Comprobamos que meses estan seleccionados para poner el valor
		if($mes2=="ENERO" && $mes1=="DICIEMBRE" && $mes3=="NOVIEMBRE"){		
		 	$anio2=$anio1-1;
			$anio3=$anio1;
			$anio1=$anio1-1;
		}
		if($mes3=="DICIEMBRE" && $mes2=="FEBRERO" && $mes1=="ENERO"){		
		 	$anio2=$anio1-1;
			$anio3=$anio1;
			$anio1=$anio1;
		}
		
		//Obtenemos el numero de mes del mes anterior para asi poder obtener el numero de dias y complementar la consulta
		$obtenerNumMesAnterior=obtenerNumMes($mes3);
		$diasMesAnterior=diasMes($obtenerNumMesAnterior,$anio2);
		
		//Variable que permite controlar si hubo resultados; si los hiubo se mostrara el boton exportar a excel
		$flag=0;
				
		//Crear sentencia principal que nos permitira mostrar el concepto y la unidad
		$sql_stm ="SELECT DISTINCT concepto FROM bitacora WHERE periodo='$_POST[cmb_periodo]'";
		
		//Crear sentencia para verificar que existan los datos
		$stm_comprobar="SELECT * FROM bitacora_zarpeo WHERE fecha>='$fechaIniBD' AND fecha<='$fechaFinBD'";
				
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg_titulo= "Comparativo Mensual <em><u>$Nomubicacion</em></u> en el Periodo <em><u>$_POST[cmb_periodo]</u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Presupuesto Registrado del periodo<u><em> $_POST[cmb_periodo]</u></em></label>";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		
		//Ejecutar la sentencia previamente creada
		$rsComp = mysql_query($stm_comprobar);
		
		if($info=mysql_fetch_array($rsComp)){									
		
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos=mysql_fetch_array($rs)){
				//Cambiamos la variable flag a 1; la consulta arrojo resultados
				$flag=1;
				//Desplegar los resultados de la consulta en una tabla
				echo "				
				<table cellpadding='5' width='100%'>				
					<tr>
						<td colspan='7' align='center' class='titulo_etiqueta'>$msg_titulo</td>
					</tr>
					<tr>
						<td rowspan='2' class='nombres_columnas' align='center'>CONCEPTO</td>
						<td colspan='3' class='nombres_columnas' align='center'>MES</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>$mes3 $anio2</td>
						<td class='nombres_columnas' align='center'>$mes1 $anio1</td>
						<td class='nombres_columnas' align='center'>$mes2 $anio3</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				//Variables que nos permite acyumular el total del mes Anterior	EJ... Diciembre, enero, febrero... Diciembre seria el mes anterior
				$totalMesAnt="";
				//Variable que almacena el primer mes; considerando el ejemplo anterior el primer mes seria enero
				$totalMes1="";
				//Almacena el segundo mes tomando de referencia el ejemplo antes mencionado
				$totalMes2="";
				//Permite acumular el total de Dias en el mes anterior
				$totalDiasGralAnt="";
				//Permite guardar el total de dias del mes 1
				$totalDiasGralMes1="";
				//Permite almacenar el totl de dias del mes2
				$totalDiasGralMes2="";
				
				do{
					
					//Creamos la consulta para el primer mes que es dibujado en la tabla
					$stm_sqlMesAnterior="SELECT SUM(cantidad) as cantidad FROM bitacora_zarpeo JOIN bitacora ON 
												bitacora_id_bitacora=id_bitacora WHERE 	concepto = '$datos[concepto]' AND fecha >= '$anio2-$obtenerNumMesAnterior-01' 
										AND fecha<='$anio2-$obtenerNumMesAnterior-$diasMesAnterior'";
					//Ejecutamos la sentencia		
					$rsMesMesAnt = mysql_query($stm_sqlMesAnterior);									
					//Creamnos el arreglo que guardara los valores arrojados por la consulta
					$cantMesAnt=mysql_fetch_array($rsMesMesAnt);
					//Si la cantidad es igual a null la igualamos a cero para que no afecte en las operaciones y permita realizar las mismas
					if($cantMesAnt['cantidad']==NULL){
						$cantMesAnt['cantidad']=0;
					}
					//Acunulamos el total del Mes ant
					$totalMesAnt=$totalMesAnt+$cantMesAnt['cantidad'];
					//Acumulamos Elt otal de dias
					$totalDiasGralAnt=restarFechas($anio2."-".$obtenerNumMesAnterior."-". 01,$anio2."-".$obtenerNumMesAnterior."-".$diasMesAnterior);
					
					//Creamos la consulta para el segundo mes que es dibujado en la BD
					$stm_sqlMes1="SELECT SUM(cantidad) as cantidad FROM bitacora_zarpeo JOIN bitacora ON bitacora_id_bitacora=id_bitacora WHERE 
								concepto = '$datos[concepto]' AND fecha >= '$fechaIniBD' AND fecha<='$anio1-$mesIni-$diasMesIni'";
					//Ejecutamos la sentenca		
					$rsMes1 = mysql_query($stm_sqlMes1);									
					//Creamos el arreglo que guardar los valores arrojados por la consulta
					$cantMes1=mysql_fetch_array($rsMes1);
					//Si la cantidad es igual a null la igualamos a cero para que no afecte en las operaciones y permita realizar las mismas
					if($cantMes1['cantidad']==NULL){
						$cantMes1['cantidad']=0;
					}
					//Acumulamos el total del mes Uno
					$totalMes1=$totalMes1+$cantMes1['cantidad'];
					//Acumulamos el total de dias
					$totalDiasGralMes1=restarFechas($fechaIniBD,$anio1."-".$mesIni."-".$diasMesIni)+1;
					
					//Creamos la consulta para el tercer mes que es dibujado en a tabla
					$stm_sqlMes2="SELECT SUM(cantidad) as cantidad, COUNT(bitacora_id_bitacora) as totalDias  FROM bitacora_zarpeo JOIN bitacora ON 
								bitacora_id_bitacora=id_bitacora WHERE concepto = '$datos[concepto]' AND fecha >= '$anio3-$mesFin-01' AND fecha<='$anio3-$mesFin-$diaFin'";
					//Ejecutamos la sentenca				
					$rsMes2 = mysql_query($stm_sqlMes2);									
					$cantMes2=mysql_fetch_array($rsMes2);
					//Si la cantidad es igual a null la igualamos a cero para que no afecte en las operaciones y permita realizar las mismas
					if($cantMes2['cantidad']==NULL){
						$cantMes2['cantidad']=0;
					}
					//Acumulamos el total del mes 2
					$totalMes2=$totalMes2+$cantMes2['cantidad'];
					//Acumulamos el total de dias del mes 2
					$totalDiasGralMes2=restarFechas($anio3."-".$mesFin."-". 01,$anio3."-".$mesFin."-".$diaFin)+1;
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>
							<td class='nombres_columnas' align='center'>$datos[concepto]</td>
							<td class='$nom_clase' align='center'>".number_format($cantMesAnt['cantidad'],2,".",",")."</td>
							<td class='$nom_clase' align='center'>".number_format($cantMes1['cantidad'],2,".",",")."</td>
							<td class='$nom_clase' align='center'>".number_format($cantMes2['cantidad'],2,".",",")."</td>
						</tr>";
						
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";				
				}while($datos=mysql_fetch_array($rs));
				//Fin de la tabla donde se muestran los resultados de la consulta
				echo "
				<tr>
					<td colspan='1' class='nombres_columnas' align='center'>TOTAL MES</td>
					<td class='$nom_clase' align='center'>".number_format($totalMesAnt,2,".",",")."</td>
					<td class='$nom_clase' align='center'>".number_format($totalMes1,2,".",",")."</td>
					<td class='$nom_clase' align='center'>".number_format($totalMes2,2,".",",")."</td>
				</tr>";
				//Comprobamos que los valores necesarios para realizar la operacion no sean iguales a cero y asi evitar errores al mostrar los resultados
				if($totalMesAnt==0||$_POST["hdn_empleados"]==0||$totalDiasGralAnt==0)
					$productividadMesAnt=0;
				else
					$productividadMesAnt=$totalMesAnt/($_POST["hdn_empleados"]/$totalDiasGralAnt);
				//Comprobamos que los valores necesarios para realizar la operacion no sean iguales a cero y asi evitar errores al mostrar los resultados
				if($totalMes1==0||$_POST["hdn_empleados"]==0||$totalDiasGralMes1==0)
					$productividadMes1=0;
				else{
					$productividadMes1=$totalMes1/($_POST["hdn_empleados"]/$totalDiasGralMes1);
				}
				//Comprobamos que los valores necesarios para realizar la operacion no sean iguales a cero y asi evitar errores al mostrar los resultados
				if($totalMes2==0||$_POST["hdn_empleados"]==0||$totalDiasGralMes2==0)
					$productividadMes2=0;
				else{
					$productividadMes2=$totalMes2/($_POST["hdn_empleados"]/$totalDiasGralMes2);
				}
				echo"<tr>
						<td colspan='1' class='nombres_columnas' align='center'>PRODUCTIVIDAD ((m&sup3;/m&sup2;)/PERSONA/D&Iacute;A)</td>
						<td class='nombres_columnas' align='center'>".number_format($productividadMesAnt,2,".",",")."</td>
						<td class='nombres_columnas' align='center'>".number_format($productividadMes1,2,".",",")."</td>
						<td class='nombres_columnas' align='center'>".number_format($productividadMes2,2,".",",")."</td>
					</tr>
				</table>
				<input type='hidden' name='cmb_periodo' value='$_POST[cmb_periodo]'/>";
				//Crear las graficas
				$grafica=dibujarGrafica($mes3,$mes1,$mes2,$productividadMesAnt,$productividadMes1,$productividadMes2,$msg_titulo);
			}// fin  if($datos=mysql_fetch_array($rs))
		}
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;
			$flag=0;			
		}?>
		</div>
		<div id="btns-regpdf" align="center" >
		<table width="90%" cellpadding="12">
			<tr>
				<td align="center">		  
						<form action="guardar_reporte.php" method="post">
					<?php 
					//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel
					if($flag==1){
					?>
							<input name="hdn_consulta" type="hidden" value="<?php echo $sql_stm; ?>" />
							<input name="hdn_nomReporte" type="hidden" 
							value="Reporte_Comparativo_<?php echo $_POST["cmb_periodo"];?>" />
							<input type="hidden"  name="hdn_consultaMesAnt" value="<?php echo $stm_sqlMesAnterior;?>"/>
							<input type="hidden"  name="hdn_consultaMes1" value="<?php echo $stm_sqlMes1;?>"/>
							<input type="hidden"  name="hdn_consultaMes2" value="<?php echo $stm_sqlMes2;?>"/>
							<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
							<input name="hdn_origen" type="hidden" value="reporteComparativoMensual" />
							
							<input type="button" name="btn_verGrafico" id="btn_verGrafico" class="botones" value="Ver Gr&aacute;fico" title="Ver Gr&aacute;fica del Reporte" 
							onclick="window.open('verGrafica.php?imagen=<?php echo $grafica;?>','_blank','top=50, left=50, width=800, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
							title="Exportar a Excel los Datos de la Consulta Realizada" 
							onMouseOver="window.estatus='';return true"  />
							<input type="hidden"  name="hdn_fechaIniBD" value="<?php echo $fechaIniBD;?>"/>
							<input type="hidden"  name="hdn_fechaFinBD" value="<?php echo $fechaFinBD;?>"/>
							<input type="hidden"  name="hdn_combo" value="<?php echo $_POST["cmb_periodo"];?>"/>
							<input type="hidden"  name="hdn_empleados" value="<?php echo $_POST["hdn_empleados"];?>"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
					<?php }?>
							<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a Seleccionar Nuevos Parametros" 
							onMouseOver="window.estatus='';return true" 
							onclick="location.href='frm_reporteComparativoMensual.php'" />	
						</form>			  
				</td>
			</tr>
		</table>			
		</div><?php
	}

	function dibujarGrafica($mes1,$mes2,$mes3,$totalMesAnt,$totalMes1,$totalMes2,$titulo){
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ("../../includes/graficas/jpgraph/jpgraph_scatter.php");
 
		$datay1 = array($totalMesAnt,$totalMes1,$totalMes2);
		$titulo=str_replace("<em>","",$titulo);
		$titulo=str_replace("<u>","",$titulo);
		$titulo=str_replace("</em>","",$titulo);
		$titulo=str_replace("</u>","",$titulo);
		
		// Setup the graph
		$graph = new Graph(800,500);
		$graph->SetMarginColor('white');
		$graph->SetScale("textlin");
		$graph->SetFrame(false);
		$graph->SetMargin(100,80,60,100);
		 
		// Setup the tab
		$graph->tabtitle->Set($titulo);
		$graph->tabtitle->SetFont(FF_ARIAL,FS_BOLD,13);
		$graph->tabtitle->SetColor('darkred','#E1E1FF');
		 
		// Enable X-grid as well
		$graph->xgrid->Show();
		
		$graph->yaxis->title->Set('Producción');
		$graph->yaxis->title->SetColor('darkred');
		$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,10);
		$graph->yaxis->SetLabelFormat('%.2f M³');
		$graph->yaxis->SetTitleMargin(80);
		$graph->yaxis->scale->SetGrace(30);

		// Use months as X-labels
		$graph->xaxis->SetTickLabels(array($mes3,$mes1,$mes2));
		$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
		$graph->xaxis->SetLabelAngle(45);
		 

		$graph->footer->center->Set('Mes');
		$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
		$graph->footer->center->SetColor('darkred');
		 
		// Create the plot
		$p1 = new LinePlot($datay1);
		$p1->SetColor("navy@0.5");
		$p1->SetWeight(3);//<--------Dejarlo en 0 para quitarlo
		// Use an image of favourite car as marker
		$p1->mark->SetType(MARK_IMG_DIAMOND,'red',0.5);
		 
		// Displayes value on top of marker image
		$p1->value->SetFormat('%.2f M³');
		 //Valores de cada punto mostrado
		$p1->value->SetMargin(20);
		$p1->value->Show();
		$p1->value->SetFont(FF_ARIAL,FS_BOLD,10);
		$p1->value->SetColor('navy');
		$p1->value->SetAngle(45);
		 
		// Incent the X-scale so the first and last point doesn't
		// fall on the edges
		$p1->SetCenter();
		$graph->Add($p1);
		//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
		$rnd=rand(0,1000);
		$grafica= "tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}

?>
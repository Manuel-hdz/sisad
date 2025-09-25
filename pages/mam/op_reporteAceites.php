<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 03/Agosto/2012
	  * Descripción: Este archivo contiene funciones para Realizar consultas Externas
	**/
	
	//Funcion que muestra el consumo de Aceites en Mantenimiento dada una fecha
	function reporteAceites(){
		//Arcivos que se incluyen para obtener informacion de la bitácora
		include_once ("../../includes/conexion.inc");
		include_once ("../../includes/op_operacionesBD.php");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Realizar la conexion con la BD
		$conn = conecta("bd_mantenimiento");
		
		//Tomamos los datos que vienen del post y las modificamos para la consulta
		$fecha=modFecha($_POST["txt_fecha"],3);
		
		//Revisar si se han agregado fotos a la Base de Datos
		$stm_sql="SELECT nom_aceite,fecha,SUM(bitacora_aceite_mina.cantidad) AS aceiteConsumido,turno,supervisor_mtto FROM bitacora_aceite_mina 
					JOIN catalogo_aceites_mina ON catalogo_aceites_id_aceite=id_aceite WHERE fecha='$fecha' AND tipo_mov='S' 
					GROUP BY nom_aceite,turno,supervisor_mtto ORDER BY fecha,nom_aceite,turno,supervisor_mtto";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se hayan encontrado resultados
		if ($datos=mysql_fetch_array($rs)){
			echo "
				<table cellpadding='5' width='100%' align='center' id='tablaResultados'> 
				<caption class='titulo_etiqueta'>Registro de Consumo de Aceites del <em><u>".modFecha($fecha,1)."</em></u></caption></br>";
			echo "
				<tr>
					<td class='nombres_columnas' align='center'>NOMBRE ACEITE</td>
					<td class='nombres_columnas' align='center'>TURNO</td>
					<td class='nombres_columnas' align='center'>SUPERVISOR DE MANTENIMIENTO EN TURNO</td>
					<td class='nombres_columnas' align='center'>CONSUMO DE ACEITE</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "<tr>";
				echo "	
						<td class='$nom_clase' align='center'>$datos[nom_aceite]</td>
						<td class='$nom_clase' align='center'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[supervisor_mtto]</td>
						<td class='$nom_clase' align='center'>".number_format($datos["aceiteConsumido"],2,".",",")." LTS</td>
					";
				echo "</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";			
			}while($datos=mysql_fetch_array($rs));
			//Obtener el Total por cada Aceite
			$stm_sql="SELECT nom_aceite,SUM(bitacora_aceite_mina.cantidad) AS aceiteConsumido FROM bitacora_aceite_mina JOIN catalogo_aceites_mina 
						ON catalogo_aceites_id_aceite=id_aceite WHERE fecha='$fecha' AND tipo_mov='S' GROUP BY nom_aceite ORDER BY nom_aceite";
			//Ejecutar sentencia SQL
			$rs2=mysql_query($stm_sql);
			//Extraer los datos
			$datos2=mysql_fetch_array($rs2);
			do{
				echo "
				<tr>
					<td align='right' colspan='3'><strong>CONSUMO DE $datos2[nom_aceite]</strong></td>
					<td align='center' class='nombres_columnas'>".number_format($datos2["aceiteConsumido"],2,".",",")." LTS</td>
				</tr>";
			}while($datos2=mysql_fetch_array($rs2));
			echo "</table>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
			$grafica=dibujarGraficaAceites($fecha);
			return $grafica;
		}
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
			?>
				<script language="javascript" type="text/javascript">
					location.href='frm_reporteAceites.php?noResults=<?php echo modFecha($fecha,1);?>';
				</script>
			<?php
		}
	}//Fin de la funcion reporteAceites
	
	//Funcion que dibuja la grafica del consumo de Aceistes por Turno y Aceite
	function dibujarGraficaAceites($fecha){
		$conn=conecta("bd_mantenimiento");
		//Obtener el Total por cada Aceite
		$stm_sql="SELECT DISTINCT catalogo_aceites_id_aceite,nom_aceite FROM bitacora_aceite_mina JOIN catalogo_aceites_mina ON catalogo_aceites_id_aceite=id_aceite WHERE fecha='$fecha' AND tipo_mov='S' ORDER BY nom_aceite";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Extraer los datos
		$datos=mysql_fetch_array($rs);
		//Declarar los arreglos para guardar los aceites y sus datos
		$nomAceites=array();
		$aceitePrimera=array();
		$aceiteSegunda=array();
		$aceiteTercera=array();
		do{
			//Obtener el nombre de los Aceites
			$nomAceites[]=$datos["nom_aceite"];

			//Obtener el consumo de Aceite en el Turno de primera
			$aceite1=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS consumo FROM bitacora_aceite_mina WHERE fecha='$fecha' AND turno='PRIMERA' AND catalogo_aceites_id_aceite='$datos[catalogo_aceites_id_aceite]'"));
			if($aceite1["consumo"]==NULL)
				$aceitePrimera[]=0;
			else
				$aceitePrimera[]=$aceite1["consumo"];
			//Obtener el consumo de Aceite en el Turno de segunda
			$aceite2=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS consumo FROM bitacora_aceite_mina WHERE fecha='$fecha' AND turno='SEGUNDA' AND catalogo_aceites_id_aceite='$datos[catalogo_aceites_id_aceite]'"));
			if($aceite2["consumo"]==NULL)
				$aceiteSegunda[]=0;
			else
				$aceiteSegunda[]=$aceite2["consumo"];
			//Obtener el consumo de Aceite en el Turno de tercera
			$aceite3=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS consumo FROM bitacora_aceite_mina WHERE fecha='$fecha' AND turno='TERCERA' AND catalogo_aceites_id_aceite='$datos[catalogo_aceites_id_aceite]'"));
			if($aceite3["consumo"]==NULL)
				$aceiteTercera[]=0;
			else
				$aceiteTercera[]=$aceite3["consumo"];
		}while($datos=mysql_fetch_array($rs));
		//Cerrar la conexion con la BD
		mysql_close($conn);
		//Incluir las funciones para cibujar las graficas
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		//Declarar la variable para regresar el nombre de la primer grafica
		$grafica1="";
		//Obtener la cantidad de Registros
		$cantRes=count($nomAceites);
		//Registros por Grafica
		$cantDatos=3;
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
			//Declarar el arreglo de cada Aceite por cada grafica
			$turnoP=array();
			$turnoS=array();
			$turnoT=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener los datos a graficar
			do{
				//Obtener el consumo para Aceite por turno
				$turnoP[]=$aceitePrimera[$contPorGrafica];
				$turnoS[]=$aceiteSegunda[$contPorGrafica];
				$turnoT[]=$aceiteTercera[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$nomAceites[$contPorGrafica];
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			$datay1 = $turnoP;
			$datay2 = $turnoS;
			$datay3 = $turnoT;
			// Create the graph and setup the basic parameters
			$graph = new Graph(945,430,'auto');
			$graph->img->SetMargin(80,30,60,125);
			$graph->SetScale('textint');
			$graph->SetFrame(false);
			$graph->yaxis->SetLabelFormat('%.2f');
			// Setup X-axis labels
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetLabelAngle(20);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			// Setup graph title ands fonts
			$graph->title->Set("Consumo de Aceite por Turno el ".modFecha($fecha,1));
			$graph->yaxis->scale->SetGrace(20);
			$graph->yaxis->SetTitleMargin(60);
			$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->yaxis->title->SetColor('darkred');
			$graph->yaxis->title->Set('Litros');
			//Pie de Tabla
			$graph->footer->center->Set('Aceite');
			$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->footer->center->SetColor('darkred');
			//
			$bplot1 = new BarPlot($datay1);
			$bplot2 = new BarPlot($datay2);
			$bplot3 = new BarPlot($datay3);
			$bplot1->SetFillColor("orange");
			$bplot2->SetFillColor("blue");
			$bplot3->SetFillColor("darkgreen");
			// Black color for positive values and darkred for negative values
			$gbarplot = new GroupBarPlot(array($bplot1,$bplot2,$bplot3));
			$gbarplot->SetWidth(0.6);
			$bplot1->value->Show();
			$bplot1->value->SetFormat('%.2f');
			$bplot2->value->Show();
			$bplot2->value->SetFormat('%.2f');
			$bplot3->value->Show();
			$bplot3->value->SetFormat('%.2f');
			$bplot1->SetLegend("PRIMERA");
			$bplot2->SetLegend("SEGUNDA");
			$bplot3->SetLegend("TERCERA");
			$graph->Add($gbarplot);
			//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
			$rnd=rand(0,1000);
			$grafica= "tmp/grafica".$rnd.".png";
			//Dibujar la grafica y guardarla en un archivo temporal	
			$graph->Stroke($grafica);
			$cont++;
			//Agregar la primer grafica al DIV principal
			if($cont==1)
				$grafica1=$grafica;
			//Agregar las siguientes graficas al DIV secundario
			else
				$grafica1.="¬".$grafica;
		}while($cont<$ciclos);
		return $grafica1;
	}
	
	//Funcion que muestra los Equipos con la posibilidad de seleccionarlos para mostrarlos en los Reportes
	function mostrarEquiposMttoM(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Creamos la sentencia SQL para mostrar los Equipos
		$stm_sql="SELECT id_equipo,nom_equipo,familia,asignado,proveedor FROM equipos WHERE area='MINA' AND estado='ACTIVO' ORDER BY familia,id_equipo";
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
?>
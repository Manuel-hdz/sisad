<?php
	/**
	  * Nombre del Módulo: Mantenimiento mina
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 05/Octubre/2012
	  * Descripción: Este archivo contiene funciones para Crear y mostrar los servicios Programados de Mtto Mina
	**/
	
	//Funcion que dibuja la tabla por dia, equipo y mes
	function dibujarTablaMes(){
		//Abrir la conexion a la BD de recursos
		$conn=conecta("bd_mantenimiento");
		//Recoger los datos de Fechas de los combos
		$anio=$_POST["cmb_Anio"];
		$mes=$_POST["cmb_Mes"];
		//Recoger el nombre de la familia
		$familia=$_POST["cmb_familia"];
		//Sentencia SQL para extraer los Equipos
		$sql="SELECT DISTINCT id_equipo FROM equipos WHERE familia='$familia' AND estado='ACTIVO'";
		//Obtener los colores y los ciclos mediante sentencia
		$sqlColores="SELECT DISTINCT ciclo_servicio,color FROM gama WHERE familia_aplicacion='$familia' ORDER BY ciclo_servicio";
		$rsColor=mysql_query($sqlColores);
		echo "<table><tr>";
		if($datosColor=mysql_fetch_array($rsColor)){
			do{
				echo "<td>$datosColor[ciclo_servicio]</td><td bgcolor='#$datosColor[color]'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			}while($datosColor=mysql_fetch_array($rsColor));
		}
		echo "<td colspan='2' align='right'>Ejecutado</td><td bgcolor='#608F00'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr></table>";
		//Obtener el numero de Mes para extraer los dias que tiene en el año seleccionado
		$numMes=obtenerNumMes($mes);
		//Obtener la cantidad de Dias del Mes
		$cantDias=diasMes($numMes, $anio);
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar si la consulta regreso equipos
		if($datos=mysql_fetch_array($rs)){
			//Comenzar a dibujar la Tabla
			echo "<br><table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>PROGRAMA DE MANTENIMIENTO DE $mes $anio</caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center' rowspan='2' colspan='2'>EQUIPOS</td>
						<td class='nombres_columnas' align='center' colspan='$cantDias'>$mes</td>
					</tr>";
			//Variable que en su primer uso permite dibujar los numeros del 1 al dia final como parte de las columnas
			$cont=1;
			echo "<tr>";
			do{
				if($cont<10)
					echo "<td class='nombres_columnas' align='center'>0$cont</td>";
				else
					echo "<td class='nombres_columnas' align='center'>$cont</td>";
				$cont++;
			}while($cont<=$cantDias);
			echo "</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>";
				echo "<td class='nombres_filas' align='center' rowspan='2'>$datos[id_equipo]</td>";
				echo "<td class='nombres_filas' align='center'>PROG.</td>";
				//Contador para dibujar cada columna depues del nombre de Equipo
				$numCol=1;
				do{
					if($numCol<10)
						$dia="0$numCol";
					else
						$dia="$numCol";
					$numMes=obtenerNumMes($mes);
					$fecha=$anio."-".$numMes."-".$dia;
					$propiedades=obtenerPropiedades($fecha,$datos["id_equipo"],"fecha_prog");
					//Si propiedades no regresa resultados, dibujar la celda solo con la clase correspondiente
					if($propiedades=="")
						echo "<td class='$nom_clase' align='center'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					//Si existen propiedades, mostrar la celda con el color correspondiente
					else
						echo "<td align='center'$propiedades>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					$numCol++;
				}while($numCol<=$cantDias);
				echo "</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				
				echo "<tr>";
				echo "	<td class='nombres_filas' align='center'>REAL</td>";
				//Contador para dibujar cada columna depues del nombre de Equipo
				$numCol=1;
				do{
					if($numCol<10)
						$dia="0$numCol";
					else
						$dia="$numCol";
					$numMes=obtenerNumMes($mes);
					$fecha=$anio."-".$numMes."-".$dia;
					$propiedades=obtenerPropiedades($fecha,$datos["id_equipo"],"fecha_mtto");
					//Si propiedades no regresa resultados, dibujar la celda solo con la clase correspondiente
					if($propiedades=="")
						echo "<td class='$nom_clase' align='center'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					//Si existen propiedades, mostrar la celda con el color correspondiente
					else
						echo "<td align='center'$propiedades</td>";
					$numCol++;
				}while($numCol<=$cantDias);
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}
	}//Fin de kardexIndividual	
	
	function obtenerPropiedades($fecha,$idEquipo,$tipoFecha){
		//Sentencia SQL
		$sql="SELECT color,ciclo_servicio FROM gama 
			WHERE id_gama=ANY(SELECT gama_id_gama FROM actividades_ot 
			WHERE orden_trabajo_id_orden_trabajo=ANY(SELECT id_orden_trabajo FROM orden_trabajo JOIN bitacora_mtto ON id_orden_trabajo=orden_trabajo_id_orden_trabajo 
			WHERE $tipoFecha='$fecha' AND equipos_id_equipo='$idEquipo'))";
		//Ejecutar sentencia SQL
		$rs=mysql_query($sql);
		//Verificar los resultados
		if($datos=mysql_fetch_array($rs)){
			$ciclo=$datos["ciclo_servicio"];
			if($tipoFecha=="fecha_prog"){
				//Recuperar los datos extraidos
				$color=$datos["color"];
				$fecha=modFecha($fecha,1);
				//Concatenar las propiedades
				$propiedades=" style='cursor:pointer;background-color:#$color' title='Servicio Programado de $ciclo HRS para el Equipo $idEquipo el $fecha'";
			}
			else{
				//Recuperar los datos extraidos
				$color="608F00";
				//Concatenar las propiedades
				$propiedades=" style='cursor:pointer;color:#FFF;;background-color:#$color' title='Servicio de $ciclo HRS Ejecutado para el Equipo $idEquipo'>$ciclo";
			}
			return $propiedades;
		}
		else
			return "";
	}
?>
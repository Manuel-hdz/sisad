<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:23/Agosto/2012
	  * Descripción: Este archivo contiene funciones para consultar la información relacionada con las actividades que se registran dentro del modulo de la clinica
	**/

	//Funcion para consultar la informacion del la clinica
	function mostrarActividadesSemanales(){
		//Conectar a la BD de Clinica
		$conn = conecta("bd_clinica");
		
		//Se recuperan los datos que se encuentran en el POST
		
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		
		//variables para manipular la consulta que será ejecutada y la que será regresada en caso de que haya datos
		$sql_stm = "";
		$consulta = "";
			
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Actividades Realizadas Semanalmente dentro de la Clinica del <em><u> ".$_POST['txt_fechaIni']."</u></em> al <em><u> ".$_POST['txt_fechaFin']." </u></em>";
		
			//Desplegar los resultados de la consulta en una tabla
			echo "<table cellpadding='5' width='100%' id='tabla-rpt-act'>
							
				<tr>
					<td colspan='8' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td colspan='8' class='nombres_columnas' align='center'>REPORTE SEMANAL ACTIVIDADES</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>ACTIVIDAD</td>";
				$fechaActual = 	$fechaIni;
				do{	
					if(obtenerNombreDia($fechaActual)!="Domingo"){
						echo "<td class='nombres_columnas' align='center'>".obtenerNombreDia($fechaActual)."<br>".modFecha($fechaActual,1)."</td>";																	
						$fechas[]=$fechaActual;				
					}
					$fechaActual = sumarDiasFecha($fechaActual,1);

				}while($fechaActual!=$fechaFin);
				if(obtenerNombreDia($fechaActual)!="Domingo"){
					echo "<td class='nombres_columnas' align='center'>".obtenerNombreDia($fechaActual)."<br>".modFecha($fechaActual,1)."</td>";																	
					$fechas[]=$fechaActual;	
				}
				echo "</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				$cantDias = restarFechas($fechaFin, $fechaIni);	
				$ctrl = 0;
				do{
					echo "<tr>";
					switch($cont){		
						case 1:
							echo"<td class='nombres_columnas' align='center'>CONSULTAS INTERNAS</td>";
						break;
						case 2:
							echo"<td class='nombres_columnas' align='center'>CONSULTAS EXTERNAS</td>";
						break;
						case 3:
							echo"<td class='nombres_columnas' align='center'>EX&Aacute;MEN PERIODICOS</td>";
						break;
						case 4:
							echo"<td class='nombres_columnas' align='center'>EX&Aacute;MEN INGRESOS</td>";
						break;
						case 5:
							echo"<td class='nombres_columnas' align='center'>EX&Aacute;MEN EMPRESAS EXTERNAS</td>";
						break;												
					}
					foreach($fechas as $ind => $value){	
						switch($cont){
							case 1:
								$sql_stm = "SELECT COUNT(id_bit_consultas) AS cant, fecha FROM bitacora_consultas WHERE consulta = 'INTERNA' AND fecha ='$value'";
							break;
							case 2:
								$sql_stm = "SELECT COUNT(id_bit_consultas) AS cant, fecha FROM bitacora_consultas WHERE consulta = 'EXTERNA' AND fecha ='$value'";														
							break;
							case 3:
								$sql_stm = "SELECT COUNT(clasificacion_exa) AS cant FROM historial_clinico WHERE clasificacion_exa = 'PERIODICO' AND fecha_exp = '$value'";
							break;
							case 4:
								$sql_stm = "SELECT COUNT(clasificacion_exa) AS cant FROM historial_clinico WHERE clasificacion_exa = 'INGRESO' AND fecha_exp = '$value'";
							break;
							case 5:
								$sql_stm = "SELECT COUNT(clasificacion_exa) AS cant FROM historial_clinico WHERE clasificacion_exa = 'EMPRESA EXTERNA' AND fecha_exp = '$value'";
							break;												
						}
						$datos = mysql_fetch_array(mysql_query($sql_stm));
						$cantidad = $datos["cant"];	
						echo "<td class='$nom_clase' align='center'>$cantidad</td>";
					}
					echo "</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";	
					$ctrl++;
				 }while($ctrl<5);
						
				echo "</table>";
				return $consulta;
		}//Cierre consultarPlanesContingenciaEjecutados
?>	
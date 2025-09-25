<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 19/Julio/2012
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Disponibilidad Mecanica, Fisica y Utilizacion de Equipos
	  **/

	/*Funcion que recopila los datos para dibujar la grafica*/
	function reporteEstatus(){
		//Verificar el departamento donde el usuario esta logueado
		if($_SESSION["depto"]=="MttoConcreto")
			$area="CONCRETO";
		else
			$area="MINA";
		//Obtener las Fechas
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Extraer y verificar el Equipo, si tiene valor diferente de vacio, verificar la Familia
		$equipo=$_POST["cmb_equipo"];
		//Obtener el Turno
		$turno=$_POST["cmb_turno"];
		//Preparacion de la sentencia SQL
		$sql_stm="SELECT equipos_id_equipo,fecha,turno,disponibilidad,observaciones FROM estatus WHERE fecha BETWEEN '$fechaI' AND '$fechaF'";
		if($turno!=""){
			$sql_stm.=" AND turno='$turno'";
			$hrsProgramadas=8;
		}
		if($equipo!="")
			$sql_stm.=" AND equipos_id_equipo='$equipo'";
		else
			$sql_stm.=" AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='$area')";
		//Concatenar la parte final de la sentencia
		$sql_stm.=" ORDER BY fecha,equipos_id_equipo,turno";
		//Conectar a la BD de Mtto
		$conn=conecta("bd_mantenimiento");
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		//Extraer los datos de la consulta
		if($datos=mysql_fetch_array($rs)){
			echo "<br>";
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Registrar Status de los Equipos<br>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>ID EQUIPO</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
						<td class='nombres_columnas' align='center'>STATUS</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>";
				echo "
						<td class='nombres_filas' align='center'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='center'>$datos[equipos_id_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[disponibilidad]</td>
						<td class='$nom_clase' align='center'>$datos[observaciones]</td>
						</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		}
		else{
			echo "<script type='text/javascript' language='javascript'>location.href='frm_reporteStatus.php?noResults'</script>";
		}
	}
?>
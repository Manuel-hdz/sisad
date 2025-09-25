<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 15/Agosto/2012
	  * Descripción: Este archivo contiene funciones para mostrar los registros de Equipo Pesado
	**/
	
	//Funcion que se encarga de desplegar los traspaleos según el criterio de busqueda utilizado
	function mostrarRegistros(){
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM bitacora_eq_pesado JOIN equipo_pesado ON id_registro=equipo_pesado_id_registro WHERE fam_equipo='$_POST[cmb_tipoObraEqP]' AND id_registro='$_POST[cmb_nomObraEq]'";
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro con Equipo Pesado de <em><u>$_POST[cmb_tipoObraEqP]</u></em> en la Obra Seleccionada";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>Registros de <em><u>$_POST[cmb_tipoObraEqP]</u></em> de la Obra <em><u>$datos[concepto]</u></em></td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>TIPO EQUIPO</td>
					<td class='nombres_columnas' align='center'>NOMBRE OBRA EQUIPO</td>
					<td class='nombres_columnas' align='center'>QUINCENA</td>
					<td class='nombres_columnas' align='center'>EQUIPOS/CANTIDAD</td>
					<td class='nombres_columnas' align='center'>CANTIDAD TOTAL<br />(HRS)</td>
					<td class='nombres_columnas' align='center'>TASA CAMBIO</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				$rs2=mysql_query("SELECT id_equipo,cantidad FROM detalle_eq_pesado WHERE bitacora_eq_pesado_idbitacora='$datos[idbitacora]'");
				if($equipos=mysql_fetch_array($rs2)){
					$suma=0;
					$listaEquipos="";
					do{
						$listaEquipos.=$equipos["id_equipo"].": ".$equipos["cantidad"]." <br>";
						$suma+=$equipos["cantidad"];
					}while($equipos=mysql_fetch_array($rs2));
					$listaEquipos=substr($listaEquipos,0,(strlen($listaEquipos)-5));
				}
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase' align='center'>$datos[fam_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[concepto]</td>
						<td class='$nom_clase' align='center'>$datos[no_quincena]</td>
						<td class='$nom_clase' align='left'>$listaEquipos</td>
						<td class='$nom_clase' align='center'>$suma</td>
						<td class='$nom_clase' align='center'>$".$datos["t_cambio"]."</td>
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo "<br><br><br><br><br><br><br><br><br><br><p align='center'>".$msg_error."</p>";
			return 0;		
		}
	}
?>
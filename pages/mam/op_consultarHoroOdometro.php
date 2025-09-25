<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 29/Abril/2011                                      			
	  * Descripción: Este archivo contiene funciones para consultar la metrica relacionada con el formulario de consultar horoodometro de la bd
	  **/		 
	  
	//Esta funcion se encarga de mostrar el registro del horometro u odometro segun el equipo seleccionado
	function mostrarMetrica(){	
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Recuperar el valor de los combos seleccionados
		$area= $_POST['cmb_area'];
		$familia= $_POST['cmb_familia'];		
		$equipo= $_POST['cmb_claveEquipo'];
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$f1 = modFecha($_POST['txt_fechaIni'],3);
		$f2 = modFecha($_POST['txt_fechaFin'],3);

		
		//Crear sentencia SQL
		"SELECT metrica FROM equipos  WHERE id_equipo = '$equipo'";
		
		//Crear sentencia SQL
		$sql_stm="SELECT equipos_id_equipo, fecha, reg_inicial, reg_final, hrs_efectivas, turno, observaciones, km_servicio, metrica, 
			nom_equipo FROM horometro_odometro JOIN equipos ON equipos_id_equipo=id_equipo WHERE equipos_id_equipo = '$equipo' AND fecha>='$f1' AND fecha<='$f2'
			ORDER BY fecha";
			
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Registro del Equipo <em><u> $equipo </u></em> En el Rango de Fechas del: <em><u> $_POST[txt_fechaIni] </u></em> al <em><u> $_POST[txt_fechaFin]
		</u></em>" ;
			
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>El Equipo <em><u>$equipo</u></em> En el Rango de Fechas del: <em><u> $_POST[txt_fechaIni]
		</u></em> al <em><u> $_POST[txt_fechaFin] </u></em> No Tiene Registros" ;										

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			if(($datos['metrica']=='HOROMETRO')){
				//Desplegar los resultados de la consulta en una tabla
				echo "				
				<table cellpadding='5' width='100%'>				
					<tr>
						<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>ID EQUIPO</td>
						<td class='nombres_columnas' align='center'>NOMBRE DEL EQUIPO</td>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
						<td class='nombres_columnas' align='center'>HOR&Oacute;METRO INICIAL</td>
						<td class='nombres_columnas' align='center'>HOR&Oacute;METRO FINAL</td>
						<td class='nombres_columnas' align='center'>HORAS SERVICIO</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>
							<td class='$nom_clase'>$datos[equipos_id_equipo]</td>
							<td class='$nom_clase'>$datos[nom_equipo]</td>
							<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase'>$datos[reg_inicial] ".'HRS'."</td>
							<td class='$nom_clase'>$datos[reg_final] ".'HRS'."</td>   
							<td class='$nom_clase'>$datos[hrs_efectivas] ".'HRS'."</td>
							<td class='$nom_clase'>$datos[turno]</td>
							<td class='$nom_clase'>$datos[observaciones]</td>
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
			}//Fin 	if(($datos['metrica']==HOROMETRO))
			else{// Mostrar la tabla correspondiente a los ODOMETROS
			//Desplegar los resultados de la consulta en una tabla
				echo "				
				<table cellpadding='5' width='100%'>				
					<tr>
						<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>ID EQUIPO</td>
						<td class='nombres_columnas' align='center'>NOMBRE DEL EQUIPO</td>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
						<td class='nombres_columnas' align='center'>OD&Oacute;METRO INICIAL</td>
						<td class='nombres_columnas' align='center'>OD&Oacute;METRO FINAL</td>
						<td class='nombres_columnas' align='center'>OD&Oacute;METRO EFECTIVO</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>
							<td class='$nom_clase'>$datos[equipos_id_equipo]</td>
							<td class='$nom_clase'>$datos[nom_equipo]</td>
							<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase'>$datos[reg_inicial] ".'KMS'."</td>
							<td class='$nom_clase'>$datos[reg_final] ".'KMS'."</td>   
							<td class='$nom_clase'>$datos[km_servicio] ".'KMS'."</td>
							<td class='$nom_clase'>$datos[turno]</td>
							<td class='$nom_clase'>$datos[observaciones]</td>
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
			}
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
		}
	
	}//Fin de la funcion mostrarMetrica
?> 
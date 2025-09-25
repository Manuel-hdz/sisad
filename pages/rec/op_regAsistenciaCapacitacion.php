<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 06/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario  frm_regAsistenciaCapacitacion.php
	  **/
	
	//Funcion que se encarga de desplegar las capacitaciones en el rango de fechas
	function mostrarCapacitaciones(){

		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
	
		if(isset($_POST["sbt_consultar"])){ 
		
			//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
			$f1 = modFecha($_POST['txt_fechaIni'],3);
			$f2 = modFecha($_POST['txt_fechaFin'],3);
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM capacitaciones	WHERE fecha_inicio>='$f1' AND fecha_inicio<='$f2' ORDER BY id_capacitacion";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Capacitaciones en el Periodo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>	$_POST[txt_fechaFin]</u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Capacitaci&oacute;n en las Fechas del <em><u>$_POST[txt_fechaIni]
			</u></em> al <em><u>$_POST[txt_fechaFin]</u></em></label>";										
		}
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>REG. ASISTENCIAS</td>
					<td class='nombres_columnas' align='center'>ID CAPACITACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>NOMBRE CAPACITACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>DURACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>INSTRUCTOR</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb_rfc' value= $datos[id_capacitacion] />
						</td>
						<td class='$nom_clase'>$datos[id_capacitacion]</td>
						<td class='$nom_clase'>$datos[nom_capacitacion]</td>
						<td class='$nom_clase'>$datos[hrs_capacitacion] HORAS</td>
						<td class='$nom_clase'>$datos[instructor]</td>
						<td class='$nom_clase'>$datos[descripcion]</td>
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
			echo $msg_error;	
			return 0;
		}
	}
?>
<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:12/Marzo/2012
	  * Descripción: Este archivo contiene funciones para consultar la información relacionada con el formulario de donde se Generan los Planes de Contingencia
	**/

	//Funcion para consultar la informacion del Permiso
	function consultarPlanesContingenciaEjecutados(){
		//Conectar a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Se recuperan los datos que se encuentran en el POST
		
		$fechaIni = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		
		//variables para manipular la consulta que será ejecutada y la que será regresada en caso de que haya datos
		$sql_stm = "";
		$consulta = "";

			//Crear sentencia SQL
			$sql_stm = "SELECT id_plan, responsable, area, lugar, nom_simulacro, tipo_simulacro, tiempo_total, fecha_reg, fecha_programada, fecha_realizado, 
				comentarios, observaciones
				FROM planes_contingencia JOIN tiempos_simulacro ON id_plan = planes_contingencia_id_plan
				WHERE estado = 'SI' AND fecha_reg BETWEEN '$fechaIni' AND '$fechaFin'";	
								
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Planes de Contingencia Programados y Realizados del  <em><u> ".$_POST['txt_fechaIni']."</u></em> al <em><u>   ".$_POST['txt_fechaFin']." </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Plan de Contingencia Registrado Como Realizado del
			<em><u>  ".$_POST['txt_fechaIni']." </u></em> al <em><u>  ".$_POST['txt_fechaFin']." </u></em> ";
			
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($sql_stm);									
	
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos=mysql_fetch_array($rs)){
			
			//Guardar la consulta cuando la que fue ejecutada regreso datos para mostrar
			$consulta = $sql_stm;
		
				//Desplegar los resultados de la consulta en una tabla
				echo "<table cellpadding='5' width='100%'>				
					<tr>
						<td colspan='12' align='center' class='titulo_etiqueta'>$msg</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>CLAVE PLAN</td>
						<td class='nombres_columnas' align='center'>RESPONSABLE SIMULACRO</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
						<td class='nombres_columnas' align='center'>LUGAR</td>
						<td class='nombres_columnas' align='center'>NOMBRE SIMULACRO</td>
						<td class='nombres_columnas' align='center'>TIPO SIMULACRO</td>
						<td class='nombres_columnas' align='center'>TIEMPO TOTAL</td>						
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
						<td class='nombres_columnas' align='center'>FECHA PROGRAMADA</td>
						<td class='nombres_columnas' align='center'>FECHA EJECUCI&Oacute;N DEL PLAN</td>												
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;	
				do{	
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>
							<td class='$nom_clase' align='center'>$datos[id_plan]</td>
							<td class='$nom_clase' align='center'>$datos[responsable]</td>
							<td class='$nom_clase' align='center'>$datos[area]</td>
							<td class='$nom_clase' align='center'>$datos[lugar]</td>
							<td class='$nom_clase' align='center'>$datos[nom_simulacro]</td>
							<td class='$nom_clase' align='center'>$datos[tipo_simulacro]</td>
							<td class='$nom_clase' align='center'>$datos[tiempo_total]</td>												
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha_reg'],1)."</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha_programada'],1)."</td>
							<td class='$nom_clase' align='center'>".modFecha($datos['fecha_realizado'],1)."</td>
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
				return $consulta;
			}// fin  if($datos=mysql_fetch_array($rs))
			else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
				echo $msg_error;					
					return $consulta;
			}
		}//Cierre consultarPlanesContingenciaEjecutados


?>	
<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 20/Julio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de consultar mantenimientos
	**/
	
	 //Funcion para mostrar la informacion del presupuesto
	function mostrarReporte(){
	
		//Conectar a la BD de mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$f1 = modFecha($_POST['txt_fechaIni'],3);
		$f2 = modFecha($_POST['txt_fechaFin'],3);
		
		//Recuperar del post el valor del combo
		$equipo=$_POST['cmb_equipo'];
		
		//Crear sentencia SQL
		$sql_stm ="SELECT equipos_id_equipo,fecha_mtto,tipo_mtto,costo_mtto,disponibilidad FROM bitacora_mtto JOIN equipos ON id_equipo=equipos_id_equipo 
		WHERE fecha_mtto>='$f1' AND fecha_mtto<='$f2' AND equipos_id_equipo='$equipo';";	
				
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Servicios Realizados del <em><u>  '$_POST[txt_fechaIni]' </u></em> al <em><u> '$_POST[txt_fechaFin]' </u></em>
		al Equipo<em><u>  '$_POST[cmb_equipo]' </u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Servicio Realizado del <em><u>  '$_POST[txt_fechaIni]' </u></em> 
		al <em><u> '$_POST[txt_fechaFin]' </u></em> del Equipo<em><u>  '$_POST[cmb_equipo]' </u></em>";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='5' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='30%'>CLAVE EQUIPO</td>
					<td class='nombres_columnas' align='center' width='30%'>DISPONIBILIDAD</td>
					<td class='nombres_columnas' align='center' width='25%'>FECHA</td>
					<td class='nombres_columnas' align='center' width='25%'>MANTENIMIENTO</td>
					<td class='nombres_columnas' align='center' width='20%'>COSTO</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			//Contador que nos permite sumar el total de la coumna	
			$total=0;

			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase' align='center'>$datos[equipos_id_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[disponibilidad]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_mtto'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[tipo_mtto]</td>
						<td class='$nom_clase' align='right'>$".number_format($datos['costo_mtto'],2,".",",")."</td>
					</tr>";

					//Realizar la suma por cada registro del total
					$total += $datos['costo_mtto'];
					
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
					<td class='$nom_clase' colspan='3'></td>
					<td class='nombres_columnas' align='right'>TOTAL</td>
					<td class='nombres_columnas' align='right'>$".number_format($total,2,".",",")."</td>
				</tr>	
			</table>";
			return 1;
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
?>
<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 12/Abril/2011
	  * Descripción: Este archivo permite mostrar los empleados; asi como el material que fue asignado a los mismos
	**/

	//Esta función se encarga de mostrar los empleados para verificar materiales de seguridad prestados al empleado 
	function mostrarEmpleados(){
		//Importamos el archivo que permite la conexión con la base de datos
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de recursos humanos
		$conn = conecta("bd_recursos");
		//Creamos la consulta SQL
		$stm_sql ="SELECT rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre,area,puesto FROM empleados 
				  WHERE CONCAT(nombre,' ', ape_pat,' ', ape_mat)='$_POST[txt_nombre]'";
		//Ejecutamos la consulta SQL
		$rs = mysql_query($stm_sql);
		//Si la consulta trajo datos creamos la tabla para mostrar los mismos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5'>
				<caption class='titulo_etiqueta'>EMPLEADO: <u><em>$_POST[txt_nombre]</em></u></caption>								    			
				<tr>
					<td width='70' class='nombres_columnas'>RFC</td>
					<td width='70' class='nombres_columnas'>NOMBRE</td>
					<td width='70' class='nombres_columnas'>&Aacute;rea</td>
					<td width='100' class='nombres_columnas'>PUESTO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>	
						<td align='center' class='$nom_clase'>$datos[rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[area]</td>
						<td align='center'class='$nom_clase'>$datos[puesto]</td>
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
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'> No Existen Empleados con el nombre <em><u>$_POST[txt_nombre]</u></em></label>";
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	//Esta función se encarga de mostrar los empleados para verificar materiales de seguridad prestados al empleado 
	function mostrarMateriales(){
		//Incluimos el archivo para realizar la conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de recursos
		$conn = conecta("bd_recursos");
		//Creamos la sentencia SQL
		$stm_sql ="SELECT 
						bd_almacen.devoluciones_es.materiales_id_material,
						bd_almacen.materiales.nom_material, 
						bd_almacen.devoluciones_es.estado, 
						bd_almacen.devoluciones_es.observaciones 
			FROM (bd_recursos.empleados JOIN bd_almacen.devoluciones_es ON rfc_empleado=empleados_rfc_empleado JOIN bd_almacen.materiales ON materiales_id_material=id_material)
			WHERE CONCAT(nombre,' ', ape_pat,' ', ape_mat)='$_POST[txt_nombre]'";
		//Ejecutamos la cosnulta SQL
		$rs = mysql_query($stm_sql);
		echo mysql_error();
		//Si la consulta trajo datos creamos la tabla para mostrar los mismos
		if($datos=mysql_fetch_array($rs)){			
			echo "								
			<table cellpadding='5' class='tala_frm' width='100%' cellspacing='5'>
				<caption class='titulo_etiqueta'>MATERIALES DE SEGURIDAD DEL EMPLEADO</caption>					
				<tr>
					<td  class='nombres_columnas'>NO</td>
					<td class='nombres_columnas'>CLAVE MATERIAL</td>
					<td class='nombres_columnas'>NOMBRE EQUIPO SEGURIDAD</td>
					<td class='nombres_columnas'>ESTADO</td>
					<td class='nombres_columnas'>OBSERVACIONES</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>	
						<td align='center' class='$nom_clase'>$cont</td>
						<td align='center' class='$nom_clase'>$datos[materiales_id_material]</td>
						<td align='center' class='$nom_clase'>$datos[nom_material]</td>
						<td align='center' class='$nom_clase'>$datos[estado]</td>
						<td align='center' class='$nom_clase'>$datos[observaciones]</td>
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
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'> No Existen Materiales Asociados con el nombre <em><u>$_POST[txt_nombre]</u></em></label>";
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
?>
<?php
	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 20/Abril/2011
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de un empleado que haya sido dado de baja con anterioridad
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: pages\rec\includes\validarCveEmpleado.php                                   
      **/	
	  	
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		
		//Obtener la clave Nueva de la URL
		$cveNueva = $_GET["cveNueva"];
		//Obtener la clave Original de la URL
		$cveOriginal = $_GET["cveOriginal"];
		//Obtener el nombre del Boton
		$boton= $_GET["boton"];
		
		//Crear la Sentencia SQL para verificar que el Empleado no tenga otro Prestamo asignado o si lo tiene, que dicho prestamo ya haya sido liquidado
		$sql_stm = "SELECT rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE id_empleados_empresa = '$cveNueva' AND id_empleados_empresa != '$cveOriginal'";
		
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Si se obtiene un registro significa que el Empleado ya tiene un Prestamo Asignado
		if($datos=mysql_fetch_array($rs)){			
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<boton>$boton</boton>
					<rfcEmpleado>$datos[rfc_empleado]</rfcEmpleado>
					<nombre>$datos[nombre]</nombre>
				</existe>");																
		}
		else{//No hay registros para el empleado seleccionado
			echo utf8_encode("
				<no-existe>
					<valor>false</valor>
					<boton>$boton</boton>
				</no-existe>");
				
			}
		//Cerrar la conexion a la BD
		mysql_close($conn);
?>

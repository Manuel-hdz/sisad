<?php
	/**
	  * Nombre del Módulo: Comaro                                               
	  * Nombre Programador: Armando Ayala Alvarado
	  * Fecha: 02/Septiembre/2015
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: ../comaro/includes/ajax/cargarInfoEmpleado.php
      **/	

	//Codigo para obtener los datos de un Empleado Especifico mediante codigo de Barras
	if(isset($_GET['numEmp'])){	
		//Recuperar los datos a buscar de la URL
		$numEmp = $_GET["numEmp"];	
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombreEmpleado FROM empleados WHERE id_empleados_empresa='$numEmp'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<empleado>$datos[nombreEmpleado]</empleado>
				</existe>
			");
		}//Cierre if($datos=mysql_fetch_array($rs))
		else
			echo "<valor>false</valor>";
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['familia']))

?>
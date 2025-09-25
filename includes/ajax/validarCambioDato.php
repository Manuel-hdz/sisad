<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 15/Marzo/2011                                 			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los dato especifico en la BD indicada
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Manipulacion de formatos de fechas*/
			include("../conexion.inc");			
			include("../func_fechas.php");			
			
	/**   Código en: \includes\ajax\obtenerDatoBD.php
      **/
	
	//Obtener un datos de la Base de Datos y tabla indicadas mediante un campo de referencia
	if(isset($_GET['valorNuevo'])){		 
		//Recuperar los datos a buscar de la URL
		$valorNuevo = $_GET["valorNuevo"];
		$valorOriginal = $_GET["valorOriginal"];	
		$tabla = $_GET["tabla"];
		$campoRef = $_GET["campoRef"];
		$base = $_GET["base"];
		
		//Conectarse a la BD
		$conn = conecta("$base");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT $campoRef FROM $tabla WHERE $campoRef = '$valorNuevo' AND $campoRef != '$valorOriginal'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo ("
					<existe>
						<valor>true</valor>
						<dato>$datos[$campoRef]</dato>
					</existe>");	
		}
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
?>

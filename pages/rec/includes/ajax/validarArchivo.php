<?php
	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 15/Junio/2011
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de un empleado que haya sido dado de baja con anterioridad
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
	/**   Código en: pages\alm\includes\validarDatoBD.php                                   
      **/	
	  	
	//Obtener el RFC del Empleado y verificar su antiguedad y la posible existencia de un prestamo para el Empleado Seleccionado		
	if(isset($_GET["fecha"])){//Validar una clave en la BD
		//Recuperar los datos a buscar de la URL
		$fecha = $_GET["fecha"];
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		
		//Crear la Sentencia SQL para verificar si hay entrada Registrada
		$sql_stm = "SELECT fecha_insercion FROM nomina_bancaria WHERE fecha_insercion='$fecha'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);

		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo "<valor>true</valor>";
		}
		else{
			echo "<valor>false</valor>";
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre else if(isset($_GET["datoBusq"]))
?>

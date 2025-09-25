<?php
	/**
	  * Nombre del Módulo: Panel de Control                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 16/Agosto/2011
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de un empleado que haya sido dado de baja con anterioridad
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
	/**   Código en: pages\alm\includes\validarPassword.php                                   
      **/	
	  	
	//Obtener el RFC del Empleado y verificar su antiguedad y la posible existencia de un prestamo para el Empleado Seleccionado		
	if(isset($_GET["pass"])){//Validar una clave en la BD
		//Recuperar los datos a buscar de la URL
		$pass = $_GET["pass"];
		//Conectarse a la BD
		$conn = conecta("bd_usuarios");
		
		//Crear la Sentencia SQL para verificar si hay entrada Registrada
		$sql_stm = "SELECT AES_DECRYPT(clave,128) AS clave FROM usuarios WHERE usuario='CPanel'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Recuperar la clave de la BD
		$datos=mysql_fetch_array($rs);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		//Comparar los resultados obtenidos 
		if($pass==$datos["clave"]){
			echo "<valor>true</valor>";
		}
		else{
			echo "<valor>false</valor>";
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre else if(isset($_GET["datoBusq"]))
?>

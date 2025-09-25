<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 16/Julio/2012
	  * Descripción: Este archivo se encarga para cargar el Tiempo a la Sesion de las Actividades de Mtto
	  **/	 		
	
	/**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: ../man/includes/ajax/cargarComboEquipoMtto.php
      **/
	
	if(isset($_GET['tiempo'])){
		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		//Obtener los datos de la URL
		$tiempo = $_GET['tiempo'];
		$ind = $_GET['ind'];	
		$tiempo.=":00";
		echo $sql="UPDATE gama_actividades SET tiempo_aprox='$tiempo' WHERE actividades_id_actividad='$ind'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql);
//		if(!$rs)
//			echo mysql_error();
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
?>
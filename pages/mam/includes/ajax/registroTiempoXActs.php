<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 16/Julio/2012
	  * Descripción: Este archivo se encarga para cargar el Tiempo a la Sesion de las Actividades de Mtto
	  **/	 		
	
	if(isset($_GET['tiempo'])){
		//Obtener los datos de la URL
		$tiempo = $_GET['tiempo'];
		$ind = $_GET['ind'];	
		//Si el indice es mayor a 0, calcular los negativos de cada cual
		if($ind>0)
			$ind = $ind*-1;
		//En el caso del 0, dejar -0 como cadena
		else
			$ind = "-0";
		session_start();
		//Si el tiempo es 00:00, remover el tiempo asignado de la sesion
		if($tiempo!="00:00"){
			$_SESSION["sistemasGamaNueva"][$_SESSION['sistemaEditar']][$_SESSION['appEditar']][$ind]=$tiempo;
		}
		else{
			if(isset($_SESSION["sistemasGamaNueva"][$_SESSION['sistemaEditar']][$_SESSION['appEditar']][$ind]))
				unset($_SESSION["sistemasGamaNueva"][$_SESSION['sistemaEditar']][$_SESSION['appEditar']][$ind]);
		}
	}
?>
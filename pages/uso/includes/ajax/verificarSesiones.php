<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional
	  * Nombre Programador: Nadi Madahí López Hernández
	  * Fecha: 19/Julio/2012
		  * Descripción: Este archivo contiene la funcion que permite verificar si la sesion dentro de la ventana emergente historial de trabajo fueron registrados
	  **/
	  if($_GET['alerta']){
		  //Definir el tipo de contenido que tendra el archivo creado
		  header("Content-type: text/xml");
		  session_start();
		  
			if(isset($_SESSION['historialTrabajo']))){
				//if(isset($_SESSION['historialTrabajo'])&&isset($_SESSION['asistentes'])&&isset($_SESSION['recorridos'])&&isset($_SESSION['visitas'])){
				//Crear XML de la clave Generada
				echo utf8_encode("
					<existe>
						<valor>true</valor>
					</existe>");
			}
			else{
				echo utf8_encode("
					<existe>
						<valor>false</valor>
					</existe>");
			}			
		}
?>
<?php
	/**
	  * Nombre del M�dulo: Seguridad Industrial
	  * Nombre Programador: Daisy Adriana Mart�nez Fern�ndez
	  * Fecha: 28/Enero/2012
		  * Descripci�n: Este archivo contiene la funcion que permite generar el id de las bitacoras dependiendo deel tipo de reisiduo seleccionado
	  **/
	  if($_GET['alerta']){
		  //Definir el tipo de contenido que tendra el archivo creado
		  header("Content-type: text/xml");
		  session_start();
		  
			if(isset($_SESSION['agenda'])&&isset($_SESSION['asistentes'])&&isset($_SESSION['recorridos'])&&isset($_SESSION['visitas'])){
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
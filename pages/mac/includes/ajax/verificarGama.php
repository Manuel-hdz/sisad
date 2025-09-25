<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 03/marzo/2011                                      			
	  * Descripción: Este archivo se encarga de verficiar que los datos de la SESSION relacionados con la Gama recien creada esten completos
	  **/	 		
	
	
	//Obtener los datos de la URL
	$nomArreglo = $_GET['nomArr'];
	//Verificar los datos contenidos en la SESSION para la Gama Nueva Actual
	$mensajes = array();//Este arreglo almacenará las inconsistencias encontradas
	session_start();
	$cantSistemas = count($_SESSION["$nomArreglo"]);
	//Recorrer los Sistemas registrados en la Nueva Gama
	foreach($_SESSION["$nomArreglo"] as $ind => $sistema){
		if(count($sistema)!=0){
			//Recorrer las aplicaciones de cada Sistema para verificar que no esten vacias
			foreach($sistema as $clave => $aplicacion){
				if(count($aplicacion)==0)
					$mensajes[] = "La Aplicación $clave del Sistema $ind esta vacía";
			}//foreach Aplicaciones
		}
		else
			$mensajes[] = "El Sistema $ind esta vacío";		
	}//foreach Sistemas
	
		
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");	 
	//Comparar los resultados obtenidos 
	if(count($mensajes)==0){
		echo "<valor>true</valor>";
	}
	else{
		echo ("
			<existe>
				<valor>false</valor>
				<cantMensajes>".count($mensajes)."</cantMensajes>			
		");
		foreach($mensajes as $key => $value)
			echo utf8_encode("<msg$key>$value</msg$key>");
		echo "</existe>";
	}
?>
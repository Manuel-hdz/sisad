<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 23/Noviembre/2010                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda del dato indicado para saber si ya esta registrado o no
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: pages\alm\includes\validarEstado.php                                   
      **/	
		 
	//Recuperar los datos a buscar de la URL
	$datoBusq = $_GET["datoBusq"];
	$BD = $_GET["BD"];	
	
	//Conectarse a la BD
	$conn = conecta("$BD");
	//Crear la Sentencia SQL
	$sql_stm = "SELECT id_requisicion, estado, area_solicitante FROM requisiciones WHERE id_requisicion='$datoBusq'";
	//Ejecutar la Sentencia previamente creada
	$rs = mysql_query($sql_stm);
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");	 
	//Comparar los resultados obtenidos 
	if($datos=mysql_fetch_array($rs)){
		//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
		echo utf8_encode("
			<existe>
				<valor>true</valor>
				<clave>$datos[id_requisicion]</clave>
				<estado>$datos[estado]</estado>
				<depart>$datos[area_solicitante]</depart>
			</existe>");
	}
	else{
		echo "<valor>false</valor>";
	}
	//Cerrar la conexion a la BD
	mysql_close($conn);
?>

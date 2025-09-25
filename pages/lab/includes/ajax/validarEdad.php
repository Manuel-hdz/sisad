<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 18/Julio/2011                                      			
	  * Descripción: Este archivo se encarga de consultar la BD para monitorear la Edad
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");	
			include("../../../../includes/op_operacionesBD.php");	
	/**   Código en: pages\alm\includes\buscarMaterial.php                                   
      **/	
		 
	//Recuperar los datos a buscar de la URL
	$edad = $_GET["edad"];
	$clave= $_GET["clave"];

	//Clave Prueba
	$idPrueba = obtenerDato("bd_laboratorio", "prueba_calidad", "id_prueba_calidad", "muestras_id_muestra", $clave);

	//Conectarse a la BD
	$conn = conecta("bd_laboratorio");
	//Crear la Sentencia SQL
	$sql_stm = "SELECT edad FROM detalle_prueba_calidad WHERE edad = '$edad' AND prueba_calidad_id_prueba_calidad = '$idPrueba'";
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
				<clave>$clave</clave>
				<edad>$datos[edad]</edad>
			</existe>");
	}
	else{
		echo "<valor>false</valor>";
	}
	//Cerrar la conexion a la BD
	mysql_close($conn);
?>

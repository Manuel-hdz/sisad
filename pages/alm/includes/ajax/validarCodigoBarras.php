<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 30/Mayo/2012
	  * Descripción: Este archivo se encarga de consultar la BD para verificar los Códigos de Barras
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
	  
	//Conectarse a la BD
	$conn = conecta("bd_almacen");
	
	if(isset($_GET["codigoBarras"])){
		//Recuperar el codigo de Barras
		$codBar=$_GET["codigoBarras"];
		//Sustituir los tags por el apostrofe
		$codBar=str_replace("<>","'",$codBar);
		//Crear la Sentencia SQL
		$sql_stm = "SELECT id_material,nom_material FROM materiales WHERE codigo_barras=\"$codBar\"";
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
					<clave>$datos[id_material]</clave>
					<nombre>$datos[nom_material]</nombre>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}
	//Cerrar la conexion a la BD
	mysql_close($conn);
?>

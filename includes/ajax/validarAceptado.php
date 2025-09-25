<?php
	/**
	  * Nombre del Módulo: Chat                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 01/Octubre/2012
	  * Descripción: Este archivo se encarga de monitorear los cambios en el archivo de Chat
	  **/
	  
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../conexion.inc");			
	/**   Código en: pages\alm\includes\validarDatoBD.php                                   
      **/	
		 
	//Recuperar los datos a buscar de la URL
	$usr = $_GET["usr"];
	//Conectarse a la BD
	$conn = conecta("bd_usuarios");
	if(isset($_GET["consulta"])){
		//Crear la Sentencia SQL
		$sql_stm = "SELECT estado FROM chat WHERE usuarios_usuario='$usr'";
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
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}
	elseif(isset($_GET["guarda"])){
		//Crear la Sentencia SQL
		$sql_stm = "INSERT INTO chat (usuarios_usuario,estado) VALUES('$usr','1')";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($rs){		
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}
	//Cerrar la conexion a la BD
	mysql_close($conn);
?>

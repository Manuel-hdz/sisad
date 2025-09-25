<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 02/Diciembre/2011                                      			
	  * Descripción: Este archivo contiene la función que carga verifica los catalogos en registrar lista maestra de documentos
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["manual"])){
		//Conectarse a la BD
		$conn = conecta("bd_aseguramiento");
		//Recoger los datos
		$manual = $_GET["manual"];
		$clausula = $_GET["clausula"];
		//Sentencia SQL
		$sql_stm="SELECT * FROM catalogo_clausulas WHERE id_clausula='$clausula' AND manual_calidad_id_manual='$manual'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Obtener los datos para manejarlos
		if($datos=mysql_fetch_array($rs)){
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<clave>$datos[id_clausula]</clave>
					<nombre>$datos[titulo_clausula]</nombre>
				</existe>");
		}else{
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
?>

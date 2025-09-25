<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 03/Agosto/2012
	  * Descripción: Este archivo contiene la función que carga el Catálogo de Aceites en Mtto
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["idAceite"])){
		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		//Recoger los datos
		$idAceite = $_GET["idAceite"];
		//Sentencia SQL
		$sql_stm="SELECT cantidad FROM catalogo_aceites_mina WHERE id_aceite='$idAceite'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		//Obtener los datos para manejarlos
		if($datos=mysql_fetch_array($rs)){
			$cantidad=number_format($datos["cantidad"],2,".",",");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<cantidad>$cantidad</cantidad>
				</existe>");
		}else{
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
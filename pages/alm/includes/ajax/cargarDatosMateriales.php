<?php
	/**
	  * Nombre del Módulo: Almacen
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 31/Marzo/2012
	  * Descripción: Este archivo contiene la función que carga el Catálogo de Salarios en la Sección Sueldo de Desarrollo
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["idMaterial"])){
		//Conectarse a la BD
		$conn = conecta("bd_almacen");
		//Recoger los datos
		$idMaterial = $_GET["idMaterial"];
		//Sentencia SQL
		$sql_stm="SELECT existencia,unidad_despacho,costo_unidad FROM materiales JOIN unidad_medida ON id_material=materiales_id_material WHERE id_material='$idMaterial'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Obtener los datos para manejarlos
		if($datos=mysql_fetch_array($rs)){
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");
			//Modificar el formato del numero a formato de presentacion de numero
			$costo_u=number_format($datos["costo_unidad"],2,".",",");
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<idMaterial>$idMaterial</idMaterial>
					<existencia>$datos[existencia]</existencia>
					<u_medida>$datos[unidad_despacho]</u_medida>
					<costo_u>$costo_u</costo_u>
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

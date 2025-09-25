<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 20/Octubre/2011                                      			
	  * Descripción: Este archivo contiene la función que carga el Catálogo de Salarios en la Sección Sueldo de Desarrollo
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["puesto"])){
		//Conectarse a la BD
		$conn = conecta("bd_desarrollo");
		//Recoger los datos
		$puesto = $_GET["puesto"];
		$area = $_GET["area"];
		//Sentencia SQL
		$sql_stm="SELECT sueldo_base,pctje_inc_act,pctje_inc_mts FROM catalogo_salarios WHERE puesto='$puesto' AND area='$area'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Obtener los datos para manejarlos
		if($datos=mysql_fetch_array($rs)){
			$sueldo=number_format($datos["sueldo_base"],2,".",",");
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<sueldo>$sueldo</sueldo>
					<incAct>$datos[pctje_inc_act]</incAct>
					<incMts>$datos[pctje_inc_mts]</incMts>
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

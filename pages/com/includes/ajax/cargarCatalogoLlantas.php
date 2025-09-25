<?php
	/**
	  * Nombre del M�dulo: Mantenimiento
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 18/Octubre/2012
	  * Descripci�n: Este archivo contiene la funci�n que carga el Cat�logo de Llantas en Mtto
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["idLlanta"])){
		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		//Recoger los datos
		$idLlanta = $_GET["idLlanta"];
		//Sentencia SQL
		$sql_stm="SELECT * FROM llantas WHERE id_llanta='$idLlanta'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		//Obtener los datos para manejarlos
		if($datos=mysql_fetch_array($rs)){
			$nueva=number_format($datos["nueva"],2,".",",");
			$reuso=number_format($datos["reuso"],2,".",",");
			$deshecho=number_format($datos["deshecho"],2,".",",");
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<marca>$datos[marca]</marca>
					<familia>$datos[familia]</familia>
					<medida>$datos[medida]</medida>
					<rin>$datos[medida_rin]</rin>
					<nueva>$nueva</nueva>
					<reuso>$reuso</reuso>
					<deshecho>$deshecho</deshecho>
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
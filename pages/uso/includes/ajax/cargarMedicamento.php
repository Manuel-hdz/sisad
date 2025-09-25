<?php
	/**
	  * Nombre del M�dulo: Mantenimiento
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 09/Junio/2012
	  * Descripci�n: Este archivo contiene la funci�n que carga el Cat�logo de Aceites en Mtto
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
	
	if(isset($_GET["tipoCons"])){
		//Conectarse a la BD
		$conn = conecta("bd_clinica");
		//Recoger los datos
		$idMed = $_GET["idMed"];
		//Sentencia SQL
		$sql_stm="SELECT codigo_med,tipo_presentacion,cant_presentacion,unidad_despacho,unidad_medida,existencia_actual FROM catalogo_medicamento WHERE id_med='$idMed'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		//Obtener los datos para manejarlos
		if($datos=mysql_fetch_array($rs)){
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<codigo>$datos[codigo_med]</codigo>
					<tipoPres>$datos[tipo_presentacion]</tipoPres>
					<cantPres>$datos[cant_presentacion]</cantPres>
					<uDespacho>$datos[unidad_despacho]</uDespacho>
					<uMedida>$datos[unidad_medida]</uMedida>
					<cantidad>$datos[existencia_actual]</cantidad>
					
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
	else{
		//Recuperar los datos a buscar de la URL
		if (isset($_GET["idMed"])){
			//Conectarse a la BD
			$conn = conecta("bd_clinica");
			//Recoger los datos
			$idMed = $_GET["idMed"];
			//Sentencia SQL
			$sql_stm="SELECT existencia_actual FROM catalogo_medicamento WHERE id_med='$idMed'";
			//Ejecutar la Sentencia previamente creada
			$rs = mysql_query($sql_stm);
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");
			//Obtener los datos para manejarlos
			if($datos=mysql_fetch_array($rs)){
				//Crear XML de la clave Generada
				echo utf8_encode("
					<existe>
						<valor>true</valor>
						<cantidad>$datos[existencia_actual]</cantidad>
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
	}
?>
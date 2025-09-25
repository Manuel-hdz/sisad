<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 13/Marzo/2012
		  * Descripción: Este archivo contiene la funcion que permite obtener el numero de accidente/incidente registrado en el año
	  **/
	  
	  /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			//Incluimos archivo para modificar fechas
			include("../../../../includes/func_fechas.php");
	/**    
      **/	
	//Conectarse a la BD
	$conn = conecta("bd_seguridad");
	
	//Comprobamos que exista el combo en el GET
	if($_GET['combo']){
		
		//Variable que nos permitira almacenar el tipo de informe que viene definido en el GET
		$combo =$_GET['combo'];
		
		//Fecha que nos permite obtener los accidentes o incidentes del año
		$fecha=date("Y-m-d");
		
		//Creamos la sentencia SQL
		$stm_sql="SELECT COUNT(id_informe)+1 AS cant FROM accidentes_incidentes WHERE tipo_informe = '$combo' AND fecha_accidente<='$fecha'";
		
		//Ejecutamos la consulta
		$rs = mysql_query($stm_sql);
		
		//Guardamos el resultado de la consulta en un arreglo de datos
		if($datos=mysql_fetch_array($rs)){
		
		
			//Guardamos el valor buscado
			$noAccInc =$datos["cant"];
			
		
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<cant>$noAccInc</cant>
					<tipo>$combo</tipo>
				</existe>");
			//Cerrar la conexion a la BD
			mysql_close($conn);
		}
		else{
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");
		}
	}
?>
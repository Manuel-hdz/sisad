<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 28/Enero/2012
		  * Descripción: Este archivo contiene la funcion que permite generar el id de las bitacoras dependiendo deel tipo de reisiduo seleccionado
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
	/**    
      **/	
	
	//Conectarse a la BD
	$conn = conecta("bd_seguridad");
		 
	//Recuperar los datos a buscar de la URL
	if(isset($_GET["combo"])){
		$tipoResiduo = $_GET["combo"];
		if($tipoResiduo=="ACEITE"){	
			//Definir las tres letras la clave de la Bitacora
			$id_cadena = "BTA";
			//Obtener el mes y el año
			$fecha = date("m-Y");
			$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
			//Obtener el mes actual y el año actual 
			$mes = substr($fecha,0,2);
			$anio = substr($fecha,5,2);
			
			//Crear la sentencia para obtener la Clave reciente acorde a la fecha
			$stm_sql = "SELECT MAX(id_bitacora_residuos) AS cant FROM bitacora_residuos WHERE id_bitacora_residuos LIKE 'BTA$mes$anio%'";
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
				$cant = substr($datos['cant'],-3)+1;
				if($cant>0 && $cant<10)
					$id_cadena .= "00".$cant;
				if($cant>9 && $cant<100)
					$id_cadena .= "0".$cant;
				if($cant>=100)
					$id_cadena .= $cant;
	
			}
		}
		if($tipoResiduo=="SOLIDOS"){	
			//Definir las tres letras la clave de la Bitacora
			$id_cadena = "BTS";
			//Obtener el mes y el año
			$fecha = date("m-Y");
			$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
			//Obtener el mes actual y el año actual 
			$mes = substr($fecha,0,2);
			$anio = substr($fecha,5,2);
			
			//Crear la sentencia para obtener la Clave reciente acorde a la fecha
			$stm_sql = "SELECT MAX(id_bitacora_residuos) AS cant FROM bitacora_residuos WHERE id_bitacora_residuos LIKE 'BTS$mes$anio%'";
			$rs = mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
				$cant = substr($datos['cant'],-3)+1;
				if($cant>0 && $cant<10)
					$id_cadena .= "00".$cant;
				if($cant>9 && $cant<100)
					$id_cadena .= "0".$cant;
				if($cant>=100)
					$id_cadena .= $cant;
			}
		}
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Crear XML de la clave Generada
		echo utf8_encode("
			<existe>
				<valor>true</valor>
				<clave>$id_cadena</clave>
				<residuo>$tipoResiduo</residuo>
			</existe>");
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
?>
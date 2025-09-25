<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 25/Febrero/2012                                      			
	  * Descripción: Este archivo contiene la funcion que valida que el presupuesto no este incluido en otro 
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de MAipulacion de formatos de fechas*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/func_fechas.php");			
	/**    
      **/	
		 
	//Recuperar los datos a buscar de la URL
	$idPA = $_GET["idPa"];
	
	//Conectarse a la BD
	$conn = conecta("bd_aseguramiento");
	

	//Crear la Sentencia SQL para verificar si existen registros 
	$sql_stm = "SELECT * FROM  detalle_referencias WHERE plan_acciones_id_plan_acciones='$idPA'";
	
	//Ejecutar la Sentencia previamente creada
	$rs = mysql_query($sql_stm);
	
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");
	//Verificar si existen registros previos
	if($datos=mysql_fetch_array($rs)){
		if($datos['justificacion']!=NULL&&$datos['accion_planeada']!=NULL&&$datos['fecha_planeada']!=NULL&&$datos['fecha_real_terminacion']!=NULL&&
		$datos['validacion_ase']!=""){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial			
			echo utf8_encode("
				<existe>
					<valor>true</valor>
				</existe>");
		}
		else{
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");	
		}
	}
		
	else{
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");	
		}		
	//Cerrar la conexion a la BD
	mysql_close($conn);
	
	
?>
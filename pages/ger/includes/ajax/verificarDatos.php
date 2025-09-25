<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas, Maurilio Hernández Correa
	  * Fecha: 14/Julio/2011                                      			
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
	$nomEmpleado = $_GET["nombre"];
	$fechaRegistro = modFecha($_GET["fecha"],3);
	
	//Conectarse a la BD
	$conn = conecta("bd_gerencia");
	

	//Crear la Sentencia SQL para verificar si existen registros previos del trabajador seleccionado
	$sql_stm = "SELECT nombre,fecha FROM bitacora_transporte WHERE nombre='$nomEmpleado' AND fecha='$fechaRegistro'";
	
	//Ejecutar la Sentencia previamente creada
	$rs = mysql_query($sql_stm);
	
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");
	//Verificar si existen registros previos
	if($datos=mysql_fetch_array($rs)){
		//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial			
		echo utf8_encode("
			<existe>
				<valor>true</valor>
				<registros>".mysql_num_rows($rs)."</registros>
				<nombre>$nomEmpleado</nombre>
				<fecha>".modFecha($fechaRegistro,1)."</fecha>
			</existe>");
	}
	else{
		//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial			
		echo utf8_encode("
			<existe>
				<valor>false</valor>
				<registros>0</registros>
			</existe>");	
	}
				
	//Cerrar la conexion a la BD
	mysql_close($conn);
	
	
?>
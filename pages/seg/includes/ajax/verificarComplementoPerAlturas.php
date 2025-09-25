<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial
	  * Nombre Programador: Daisy Adriana Martinez Fernandez - Nadia Madahí López Hernandez
	  * Fecha: 28/Febrero/2012                                      			
	  * Descripción: Este archivo contiene la funcion que valida que no se pueda continuar con el registro de la informacion del permiso de alturas si antes no se han 
	  					seleccionado ó complementado con las condiciones de seguridad correspondientes.
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
	$idPer = $_GET["idPer"];
	
	//Conectarse a la BD
	$conn = conecta("bd_seguridad");

	//Crear la Sentencia SQL para verificar si existen registros 
	$sql_stm = "SELECT * FROM  revision_cs WHERE permisos_trabajos_id_permiso_trab='$idPer'";
	
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
			</existe>");
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
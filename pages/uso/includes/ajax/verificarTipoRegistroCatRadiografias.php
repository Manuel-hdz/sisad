<?php
	/**
		* Nombre del Módulo: Unidad de Salud Ocupacional
		* Nombre Programador: Nadia Madahí López Hernández
		* Fecha:03/Julio/2012
		* Descripción: Este archivo contiene la funcion que permite Registrar ó Modificar la informacion de las Radiografiasdentro de la tabla catalogo_radiografias 
			en  la BD de la CLINICA
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
	$conn = conecta("bd_clinica");
	
	//Comprobamos que exista la clave en el GET
	if($_GET['clave']){
		
		//Variable que nos permitira almacenar el tipo de Registro de la Radiografias que viene definido en el GET
		$clave =$_GET['clave'];
				
		//Creamos la sentencia SQL
		$stm_sql="SELECT * FROM catalogo_radiografias WHERE id_proyeccion = '$clave'";
		
		//Ejecutamos la consulta
		$rs = mysql_query($stm_sql);
		
		//Guardamos el resultado de la consulta en un arreglo de datos
		if($datos=mysql_fetch_array($rs)){
			//Guardamos el valor buscado	
			$claveProy = $datos['id_proyeccion'];
			$nomProy = $datos['nom_proyeccion'];
			$com =$datos['comentarios'];

			if($datos['comentarios']!="")
				$com = $datos['comentarios'];
			else
				$com = "¬ND";			

			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<claveProy>$claveProy</claveProy>
					<nomProy>$nomProy</nomProy>
					<com>$com</com>	
					
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
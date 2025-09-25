<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 10/Febrero/2012                                      			
	  * Descripción: Este archivo se encarga de consultar la BD para monitorear la Edad
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");	
	/**   Código en: pages\lab\includes\clavesConcreto.php
      **/	
		 
	//Verificar que el Codigo de CONCRETO venga el la URL
	if(isset($_GET['codigo'])){
		//Recuperar los datos a buscar de la URL
		$codigo = $_GET["codigo"];
		
		//Conectarse a la BD
		$conn = conecta("bd_laboratorio");
		//Crear la Sentencia SQL para obtener la cantidad de muestras asociadas al Código de CONCRETO proporcionado
		$sql_stm = "SELECT num_muestra FROM muestras WHERE codigo_localizacion = '$codigo' ORDER BY num_muestra DESC";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<noMuestra>$datos[num_muestra]</noMuestra>
					<codigo>$codigo</codigo>
				</existe>");
		}
		else{
			echo utf8_encode("
				<existe>
					<valor>false</valor>
					<codigo>$codigo</codigo>
				</existe>");
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['codigo']))
	else if(isset($_GET['clave'])){
		//Recuperar los datos a buscar de la URL
		$clave = $_GET["clave"];
		
		//Conectarse a la BD
		$conn = conecta("bd_laboratorio");
		//Crear la Sentencia SQL para obtener la cantidad de muestras asociadas al Código de CONCRETO proporcionado
		$sql_stm = "SELECT id_muestra, num_muestra, codigo_localizacion FROM muestras WHERE id_muestra = '$clave'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>					
					<noMuestra>$datos[num_muestra]</noMuestra>
					<lugar>$datos[codigo_localizacion]</lugar>
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
	}
	
	
?>
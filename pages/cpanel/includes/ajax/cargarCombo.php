<?php
	/**
	  * Nombre del Módulo: Panel de Control                                               
	  * Nombre Programador: Antonio de Jesus Jimenez Cuevas
	  * Fecha: 16/Agosto/2011                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: \includes\ajax\cargarCombo.php                                   
      **/	
	if (isset($_GET["datoBusq"])){
		//Recuperar los datos a buscar de la URL
		$datoBusq = $_GET["datoBusq"];
		//Conectarse a la BD
		$conn = conecta("bd_usuarios");
		//Crear la Sentencia SQL en base al valor del Departamento
		if ($datoBusq!="Mantenimiento")
			$sql_stm = "SELECT DISTINCT usuario FROM usuarios WHERE depto='$datoBusq'";
		else
			$sql_stm = "SELECT DISTINCT usuario FROM usuarios WHERE depto='MttoConcreto' OR depto='MttoMina'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		$cont = 1;
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo "<existe><valor>true</valor><tam>$tam</tam>";
			do{
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("<dato$cont>$datos[usuario]</dato$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//FIN del else if(isset($_GET['datoBusq'))
?>

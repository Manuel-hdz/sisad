<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 18/Enero/2011                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda del dato indicado para obtener sus datos
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: pages\alm\includes\buscarMaterial.php                                   
      **/	
	
	if(isset($_GET["claveMaterial"])){
	//Recuperar los datos a buscar de la URL
	$claveMaterial = $_GET["claveMaterial"];
	
	
	//Conectarse a la BD
	$conn = conecta("bd_almacen");
	//Crear la Sentencia SQL
	$sql_stm = "SELECT id_material,nom_material,existencia,unidad_medida,costo_unidad,linea_articulo FROM materiales JOIN unidad_medida ON id_material=materiales_id_material 
				WHERE id_material='$claveMaterial'";
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
				<clave>$datos[id_material]</clave>
				<nombre>$datos[nom_material]</nombre>
				<existencia>$datos[existencia]</existencia>
				<unidad>$datos[unidad_medida]</unidad>
				<costo>$datos[costo_unidad]</costo>
				<categoria>$datos[linea_articulo]</categoria>
			</existe>");
	}
	else{
		echo "<valor>false</valor>";
	}
	//Cerrar la conexion a la BD
	mysql_close($conn);
	}
	
	$conn2 = conecta("bd_mantenimiento");
	
	if(isset($_GET["equipo"])){
		//Recuperar el codigo de Barras
		$equipo=$_GET["equipo"];
		//Sustituir los tags por el apostrofe
		$equipo=str_replace("<>","'",$equipo);
		//Crear la Sentencia SQL
		$sql_stm = "SELECT `id_equipo` 
					FROM  `equipos` 
					WHERE `id_equipo`=\"$equipo\"";
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
					<equipo>$datos[id_equipo]</equipo>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}
	//Cerrar la conexion a la BD
	mysql_close($conn2);
	
	$conn2 = conecta("bd_mantenimiento");
	
	if(isset($_GET["valeGas"])){
		//Recuperar el codigo de Barras
		$valeGas=$_GET["valeGas"];
		//Sustituir los tags por el apostrofe
		$valeGas=str_replace("<>","'",$valeGas);
		//Crear la Sentencia SQL
		$sql_stm = "SELECT `no_vale` 
					FROM  `bitacora_gasolina` 
					WHERE `no_vale`=\"$valeGas\"";
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
					<valeGas>$datos[no_vale]</valeGas>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
	}
	//Cerrar la conexion a la BD
	mysql_close($conn2);
?>

<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica      
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 09/Agosto/2011                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");	
			include("../../../../includes/op_operacionesBD.php");		
	/**   Código en: \includes\ajax\cargarCombo.php                                   
      **/	
	  
	//Funciones para ordenar un combo mediante un campo
	if(isset($_GET['destino'])){
			
		//Recuperar los datos a buscar de la URL
		$idDestino = $_GET["destino"];		

		//Obtener a partir del id del destino proporcionado para poder realiza la consulta
		$destino= obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$idDestino);
		
		//Conectarse a la BD
		$conn = conecta("bd_gerencia");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT periodo FROM bitacora JOIN bitacora_zarpeo ON id_bitacora=bitacora_id_bitacora
					WHERE destino='$destino' ORDER BY fecha";
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
				echo utf8_encode("<dato$cont>$datos[periodo]</dato$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}

?>

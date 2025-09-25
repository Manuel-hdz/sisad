<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 24/Julio/2012
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
	if(isset($_GET['periodo'])){	
		//Recuperar los datos a buscar de la URL
		$periodo = $_GET["periodo"];
		//Conectarse a la BD
		$conn = conecta("bd_desarrollo");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT catalogo_clientes_id_cliente AS id_cliente FROM presupuesto WHERE periodo='$periodo' ORDER BY id_cliente";
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
					$cliente=obtenerDato("bd_desarrollo","catalogo_clientes","nom_cliente","id_cliente",$datos["id_cliente"]);
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("
					<id$cont>$datos[id_cliente]</id$cont>
					<nombre$cont>$cliente</nombre$cont>
				");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//FIN del else if(isset($_GET['nomCampoOrd'))
?>

<?php
	/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Armando Ayala Alvarado
	  * Fecha: 24/Enero/2015
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: ../man/includes/ajax/cargarComboPersonalRH.php
      **/	
	  
	
	//Codigo para obtener los productos de una requisicion específica
	if(isset($_GET['reqC'])){
		//Recuperar los datos a buscar de la URL
		$reqC = $_GET["reqC"];
		//En caso de no seleccionar ninguna requisicion guarda que no aplica para su uso posterior
		if($reqC == "No aplica" ){
			session_start();
			$_SESSION["aplica"] = "No aplica";
		}
		//Conectarse a la BD
		$conn = conecta("bd_almacen");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT descripcion FROM detalle_requisicion WHERE requisiciones_id_requisicion='$reqC' AND mat_pedido=1 ORDER BY descripcion";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		$cont = 1;
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo "<existe>
					<valor>true</valor>
					<tam>$tam</tam>";
			do{
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("<descripcion$cont>$datos[descripcion]</descripcion$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['reqC']))
	
	//Codigo para obtener los productos de una requisicion específica
	if(isset($_GET['descC'])){
		//Recuperar los datos a buscar de la URL
		$descC = $_GET["descC"];	
		//Conectarse a la BD
		$conn = conecta("bd_almacen");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT cant_req, unidad_medida FROM detalle_requisicion WHERE descripcion='$descC' AND requisiciones_id_requisicion='$requiC' ORDER BY descripcion";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo "<existe>
					<valor>true</valor>
					<tam>$tam</tam>";
			do{
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("<cant>$datos[cant_req]</cant>");
				echo utf8_encode("<unidad>$datos[unidad_medida]</unidad>");
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['descC']))
?>
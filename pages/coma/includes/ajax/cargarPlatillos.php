<?php
	/**
	  * Nombre del Módulo: Comaro                                              
	  * Nombre Programador: Armando Ayala Alvarado
	  * Fecha: 27/Enero/2015
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: ../man/includes/ajax/cargarComboPersonalRH.php
      **/	
	 
	
	//Codigo para obtener las cuentas de un control de costos especifico
	if(isset($_GET['turnoC'])){
		//Recuperar los datos a buscar de la URL
		$turnoC = $_GET["turnoC"];
		//Conectarse a la BD
		$conn = conecta("bd_comaro");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT T1.id_menu, T2.descripcion
					FROM platillos_dia AS T1
					JOIN menu AS T2
					USING ( id_menu ) 
					WHERE turno =  '$turnoC'
					AND fecha =  '".date('Y-m-d')."'
					ORDER BY T2.descripcion";
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
				//Prevenir errores en el archivo XML generado, cuando el texto contenga
				//acentos o algun caracter especial
				echo utf8_encode("<id$cont>$datos[id_menu]</id$cont>");
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
	}//Cierre if(isset($_GET['controlC']))
?>
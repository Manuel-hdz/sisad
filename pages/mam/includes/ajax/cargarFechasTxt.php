<?php
	/**
	  * Nombre del Módulo: Compras                                              
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
	 
	
	
	if(isset($_GET['nominaC'])){
		include_once("../../../../includes/func_fechas.php");
		//Recuperar los datos a buscar de la URL
		$nominaC = $_GET["nominaC"];
		//Conectarse a la BD
		$conn = conecta("$_GET[bd]");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT id_nomina, fecha_inicio, fecha_fin, area FROM nominas
					WHERE id_nomina = '$nominaC'";
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
				echo utf8_encode("<id$cont>$datos[id_nomina]</id$cont>");
				echo utf8_encode("<fechaI$cont>".modFecha($datos["fecha_inicio"],1)."</fechaI$cont>");
				echo utf8_encode("<fechaF$cont>".modFecha($datos["fecha_fin"],1)."</fechaF$cont>");
				echo utf8_encode("<area$cont>$datos[area]</area$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['nominaC']))
	
	if(isset($_GET['area'])){
		include_once("../../../../includes/func_fechas.php");
		//Recuperar los datos a buscar de la URL
		$area = $_GET["area"];
		$fecha_ini = $_GET["fecha_ini"];
		$fecha_fin = $_GET["fecha_fin"];
		
		$fecha_ini = modFecha($fecha_ini,3);
		$fecha_fin = modFecha($fecha_fin,3);
		//Conectarse a la BD
		$conn = conecta("$_GET[bd]");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT * 
					FROM nominas
					WHERE fecha_registro
					BETWEEN  '$fecha_ini'
					AND  '$fecha_fin'
					AND area =  '$area'
					AND finalizada = '$_GET[stat]'";
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
				echo utf8_encode("<id$cont>$datos[id_nomina]</id$cont>");
				echo utf8_encode("<descripcion$cont>$datos[id_nomina]......$datos[fecha_registro]</descripcion$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['area']))
?>
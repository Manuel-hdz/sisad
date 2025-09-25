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
	if(isset($_GET['cuentaC'])){
		//Recuperar los datos a buscar de la URL
		$control2C = $_GET["control2C"];
		$cuentaC = $_GET["cuentaC"];	
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT `subcuentas`.`id_subcuentas`, `subcuentas`.`descripcion`
					FROM control_costos 
					JOIN rel_costos_cuentas_subcuentas USING(`id_control_costos`)
					JOIN cuentas USING(`id_cuentas`)
					JOIN subcuentas USING(`id_subcuentas`)
					WHERE (`control_costos`.`id_control_costos` = '$control2C') AND  (`cuentas`.`id_cuentas` = '$cuentaC')
					ORDER BY `subcuentas`.`descripcion` ASC";
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
				echo utf8_encode("<id$cont>$datos[id_subcuentas]</id$cont>");
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
	}//Cierre if(isset($_GET['cuentaC']))
	
	//Codigo para obtener los productos de una requisicion específica
	if(isset($_GET['controlC'])){
		//Recuperar los datos a buscar de la URL
		$controlC = $_GET["controlC"];
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT `cuentas`.`id_cuentas`, `cuentas`.`descripcion`
					FROM control_costos 
					JOIN rel_costos_cuentas_subcuentas USING(`id_control_costos`)
					JOIN cuentas USING(`id_cuentas`)
					WHERE (`control_costos`.`id_control_costos` = '$controlC')
					ORDER BY `cuentas`.`id_cuentas`";
					
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
				echo utf8_encode("<id$cont>$datos[id_cuentas]</id$cont>");
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
	
	//Codigo para obtener los productos de una requisicion específica
	if(isset($_GET['equipo'])){
		//Recuperar los datos a buscar de la URL
		$equipo = $_GET["equipo"];
		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT id_equipo, id_control_costos, id_cuentas
					FROM equipos
					WHERE id_equipo =  '$equipo'";
					
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
				echo utf8_encode("<control$cont>$datos[id_control_costos]</control$cont>");
				echo utf8_encode("<cuenta$cont>$datos[id_cuentas]</cuenta$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['equipo']))
?>
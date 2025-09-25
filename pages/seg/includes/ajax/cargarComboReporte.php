<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 14/Marzo/2012                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios realizar la busqueda segun el elemento seleccionado
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			//Incluimos archivo para modificar fechas
			include("../../../../includes/func_fechas.php");			
	/**   Código en: includes\ajax\cargarCombo.php                                   
      **/	
	  
	
	//Funciones para ordenar un combo mediante un campo
	if(isset($_GET['combo'])){	
		//REcuperamos la informacion contenida en el GET
		$combo = $_GET['combo'];
		$fechaIni = modFecha($_GET['fechaIni'],3);
		$fechaFin = modFecha($_GET['fechaFin'],3);
		//Declaramos variables para el proceso de llenado del combo
		$bd = "";
		if($combo=="AREA")
			$bd = "bd_recursos";
		if($combo=="TURNO"||$combo=="TIPO")
			$bd = "bd_seguridad";
		
		//Conectarse a la BD
		$conn = conecta("$bd");
		
		if($combo=="AREA"){
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT area FROM empleados ORDER BY area";
		}
		if($combo=="TURNO"){
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT turno FROM accidentes_incidentes WHERE fecha_accidente>='$fechaIni' AND fecha_accidente<='$fechaFin'ORDER BY turno";
		}
		if($combo=="TIPO"){
			//Crear la Sentencia SQL
			$sql_stm = "SELECT DISTINCT tipo_informe FROM accidentes_incidentes WHERE fecha_accidente>='$fechaIni' AND fecha_accidente<='$fechaFin' 
				ORDER BY tipo_informe";
		}
		
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		$tam = mysql_num_rows($rs);
		$cont = 1;
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("<existe><valor>true</valor><tam>$tam</tam>");
			do{
				if($combo=="AREA"){
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("<dato$cont>".$datos['area']."</dato$cont>");
				}
				if($combo=="TURNO"){
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("<dato$cont>".$datos['turno']."</dato$cont>");
				}
				if($combo=="TIPO"){
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("<dato$cont>".$datos['tipo_informe']."</dato$cont>");
				}
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		mysql_close($conn);
	}
?>

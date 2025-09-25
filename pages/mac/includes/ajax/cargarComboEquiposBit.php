<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 17/Abril/2012
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
	if(isset($_GET['anio']) && isset($_GET["mes"])){	
		//Recuperar los datos a buscar de la URL
		$anio = $_GET["anio"];
		$mes = $_GET["mes"];
		//Ensamblar la Fecha
		$fecha=$anio."-".$mes;
		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT(equipos_id_equipo) AS equipo,RTRIM(familia) AS familia FROM bitacora_mtto JOIN equipos ON equipos_id_equipo=id_equipo 
					WHERE SUBSTRING(fecha_mtto,1,7)='$fecha' AND area='CONCRETO' ORDER BY familia,equipo";
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
				echo utf8_encode("
					<familia$cont>$datos[familia]</familia$cont>
					<nombre$cont>$datos[equipo]</nombre$cont>
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
	
	//Funciones para ordenar un combo mediante un campo sobre la Bitacora de Aceite
	if(isset($_GET['anioBitAceite']) && isset($_GET["mes"])){	
		//Recuperar los datos a buscar de la URL
		$anio = $_GET["anioBitAceite"];
		$mes = $_GET["mes"];
		//Ensamblar la Fecha
		$fecha=$anio."-".$mes;
		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT(equipos_id_equipo) AS equipo,RTRIM(familia) AS familia FROM bitacora_aceite JOIN equipos ON equipos_id_equipo=id_equipo 
					WHERE SUBSTRING(fecha,1,7)='$fecha' AND area='CONCRETO' ORDER BY familia,equipo";
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
				echo utf8_encode("
					<familia$cont>$datos[familia]</familia$cont>
					<nombre$cont>$datos[equipo]</nombre$cont>
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

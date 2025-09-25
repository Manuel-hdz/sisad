<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 27/Agosto/2012
	  * Descripción: Este archivo se encarga de consultar los datos de la tabla de subtipos
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			
	/**   Código en: \includes\ajax\obtenerPrecioTraspaleo.php
      **/
	
	//Verificar este definido en el Get el ID de subtipo
	if(isset($_GET["idSubtipo"])){
		//Conectarse a la BD
		$conn = conecta("bd_topografia");
		//Recuperar los datos a buscar de la URL
		$idSubtipo = $_GET["idSubtipo"];
		//Crear la Sentencia SQL
		$sql_stm = "SELECT pu_umn,pu_usd,seccion,area FROM subcategorias WHERE id='$idSubtipo'";	
	
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			$seccion=$datos["seccion"];
			if($seccion=="")
				$seccion="¬EMPTY";
			echo utf8_encode("
				<existe>
					<valor>true</valor>																		
					<pumn>".number_format($datos['pu_umn'],2,".",",")."</pumn>
					<puusd>".number_format($datos['pu_usd'],2,".",",")."</puusd>
					<seccion>$seccion</seccion>
					<area>$datos[area]</area>
				</existe>");
		}
		else{	
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	if(isset($_GET["Num"]) && $_GET["Num"]==2){
		//Recuperar los datos a buscar de la URL
		$idObra = $_GET["idObra"];
		$tipoObra=$_GET["tipoObra"];
		//Comparar con obras de Anclas
		$pos1 = stripos($idObra, "ANCLA");
		$pos2 = stripos($tipoObra, "ANCLA");
		//Comparar con obras de Desbordes
		$pos3 = stripos($idObra, "DESBORDE");
		$pos4 = stripos($tipoObra, "DESBORDE");
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($pos1 !== false || $pos2 !== false){
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<clasificacion>ANCLA</clasificacion>
					<id>$idObra</id>
					<tipo>$tipoObra</tipo>
				</existe>");
		}
		elseif($pos3 !== false || $pos4 !== false){
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<clasificacion>DESBORDE</clasificacion>
					<id>$idObra</id>
					<tipo>$tipoObra</tipo>
				</existe>");
		}
		else{	
			echo "<valor>false</valor>";
		}
	}
	
?>
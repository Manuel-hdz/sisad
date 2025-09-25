<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 04/Junio/2011                                 			
	  * Descripción: Este archivo se encarga de consultar los precios unitarios del traspaleo de acuerdo a la Distancia y la Tabla de Precios Asociada
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Manipulacion de formatos de fechas*/
			include("../../../../includes/conexion.inc");
			
	/**   Código en: \includes\ajax\obtenerPrecioTraspaleo.php
      **/
	
		
	//Recuperar los datos a buscar de la URL
	$distancia = $_GET["distancia"];
	$idObra = $_GET["idObra"];
	
	//Conectarse a la BD
	$conn = conecta("bd_topografia");
	
	
	//Si la varible "destino=='APLANILLE'" esta definida en el GET, entonces tomar los precios de la tabla de Aplanille
	if(isset($_GET["destino"]) && $_GET["destino"]=="APLANILLE"){
		//Crear la Sentencia SQL
		$sql_stm = "SELECT pu_mn, pu_usd FROM precios_traspaleo JOIN lista_precios ON id_precios=precios_traspaleo_id_precios 
					WHERE tipo='APLANILLE' AND '$distancia'>=distancia_inicio AND '$distancia'<=distancia_fin";
	}
	else{//Si no tomar los precios de la lista asociada a la Obra
		//Si la obra NO esta registrada, proceder a tomar los precios de la Lista seleccionada en la Lista desplegable
		if($idObra=="OBRA_NR"){
			$lista = $_GET["lista"];
			//Crear la Sentencia SQL
			$sql_stm = "SELECT pu_mn, pu_usd FROM precios_traspaleo JOIN lista_precios ON id_precios=precios_traspaleo_id_precios 
						WHERE tipo = '$lista' AND '$distancia'>=distancia_inicio AND '$distancia'<=distancia_fin";
		}
		else{//Buscar los precios en la lista asociada a la Obra seleccionda
			//Crear la Sentencia SQL
			$sql_stm = "SELECT pu_mn, pu_usd FROM (obras JOIN precios_traspaleo ON obras.precios_traspaleo_id_precios=id_precios) 
						JOIN lista_precios ON id_precios=lista_precios.precios_traspaleo_id_precios 
						WHERE id_obra = '$idObra' AND '$distancia'>=distancia_inicio AND '$distancia'<=distancia_fin";
		}
	}
	
	
	//Ejecutar la Sentencia previamente creada
	$rs = mysql_query($sql_stm);
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");	 
	//Comparar los resultados obtenidos 
	if($datos=mysql_fetch_array($rs)){
		echo utf8_encode("
			<existe>
				<valor>true</valor>																		
				<pumn>".$datos['pu_mn']."</pumn>
				<puusd>".$datos['pu_usd']."</puusd>				
			</existe>");	
	}
	else{	
		echo utf8_encode("
			<existe>
				<valor>false</valor>
				<distancia>".$distancia."</distancia>
			</existe>");
	}
	//Cerrar la conexion a la BD
	mysql_close($conn);
	
	
?>
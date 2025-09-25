<?php
	/**
	  * Nombre del Módulo: Produccion                                               
	  * Nombre Programador:                             
	  * Fecha: 30/Diciembre/2011                                 			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los dato especifico en la BD indicada de acuerdo a la selecicon del destino y el presupuesot correspondiente
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Manipulacion de formatos de fechas*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/func_fechas.php");			
			
	/**   Código en: \includes\ajax\obtenerDatoPresupuesto.php
      **/
	
	
	//Obtener los datos del Presupuesto correspondiente a la fecha y destino seleccionado
	if(isset($_GET['destino'])){
		//Recuperar los datos a buscar de la URL
		$nomDestino = $_GET["destino"];				
		$fechaPpto = modFecha($_GET["fecha"],3);			

		//Conectarse a la BD
		$conn = conecta("bd_produccion");
		
		//Crear la Sentencia SQL
		$sql_stm = "SELECT vol_ppto_dia FROM presupuesto WHERE  catalogo_destino_id_destino = $nomDestino AND '$fechaPpto' BETWEEN fecha_inicio AND fecha_fin";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){						
			echo utf8_encode("
			<existe>
				<valor>true</valor>																		
				<pptoDestino>$datos[vol_ppto_dia]</pptoDestino>
			</existe>");							
		}
		else
			echo "<valor>false</valor>";
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
	
	}//Cierre if(isset($_GET['destino']))
	
	
?>
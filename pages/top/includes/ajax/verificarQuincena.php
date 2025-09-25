<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 23/Junio/2011                                      			
	  * Descripción: Este Archivo contiene las funciones para Verificar si la Obra Seleccionada tienen un registro previo en la Quincena Seleccionada
	  **/	 		
	
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: \includes\ajax\verificarQuincena.php
      **/
	
	//Obtener los datos de la URL
	$idObra = $_GET['idObra'];
	$noQuincena = $_GET['noQuincena'];
	$tipoObra = $_GET['tipoObra'];
	
	//Conectarse con la Base de Datos de Topografia
	$conn = conecta("bd_topografia");	
	
	
	//Hacer la Consulta en Base al tipo de Registro que se va a realizar
	if($tipoObra=="TRASPALEO"){			
		//Crea la Sentencia SQL para verificar si la Obra seleccionada tienen un registro en TRASPALEOS
		$sql_stm = "SELECT id_traspaleo FROM traspaleos WHERE obras_id_obra = '$idObra' AND no_quincena = '$noQuincena'";
	}
	else if($tipoObra=="ESTIMACION"){
		//Crea la Sentencia SQL para verificar si la Obra seleccionada tienen un registro en ESTIMACIONES
		$sql_stm = "SELECT id_estimacion FROM estimaciones WHERE obras_id_obra = '$idObra' AND no_quincena = '$noQuincena';";
	}
	
	//Ejecutar la Sentencia
	$rs = mysql_query($sql_stm);
	
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");
	//Comparar los resultados obtenidos 	
	if($datos=mysql_fetch_array($rs)){					 		
		echo "
			<existe>
				<valor>true</valor>
			</existe>
		";				
	}
	else{
		echo "
			<existe>
				<valor>false</valor>
			</existe>
		";
	}
	
	//Cerrar la Conexion con la BD
	mysql_close($conn);
?>
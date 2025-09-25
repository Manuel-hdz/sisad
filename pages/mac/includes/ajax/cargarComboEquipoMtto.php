<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 17/Febrero/2011                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: ../man/includes/ajax/cargarComboEquipoMtto.php
      **/	
	  
	
	//Codigo para obtener los Equipos activos de una Familia de una de las Áreas (Mina o Concreto)
	if(isset($_GET['familia'])){	
		//Recuperar los datos a buscar de la URL
		$familia = $_GET["familia"];		
		$area = $_GET["area"];	


		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		//Crear la Sentencia SQL
		if($area == "TODO"){
			$sql_stm = "SELECT id_equipo, nom_equipo FROM equipos WHERE familia = '$familia' AND estado = 'ACTIVO' ORDER BY id_equipo";
		} else {
			$sql_stm = "SELECT id_equipo, nom_equipo FROM equipos WHERE familia = '$familia' AND area = '$area' AND estado = 'ACTIVO' ORDER BY id_equipo";
		}
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
				echo utf8_encode("<idEquipo$cont>$datos[id_equipo]</idEquipo$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['familia']))
	
	if(isset($_GET['opcion'])){	
		//Recuperar los datos a buscar de la URL
		$area = $_GET["area"];

		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		//Crear la Sentencia SQL
		if($area == "TODO"){
			$sql_stm = "SELECT DISTINCT familia FROM equipos WHERE estado = 'ACTIVO' AND familia != '' ORDER BY familia";
		} else {
			$sql_stm = "SELECT DISTINCT familia FROM equipos WHERE area = '$area' AND estado = 'ACTIVO' ORDER BY familia";
		}
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
				echo utf8_encode("<familia$cont>$datos[familia]</familia$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['opcion']))

?>
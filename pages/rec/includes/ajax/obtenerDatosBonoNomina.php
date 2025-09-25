<?php
	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 21/Mayo/2011
	  * Descripción: Este archivo se encarga de consultar la BD los datos del Bono inidicado en la URL de l pagina
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes:
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/*		2. Operaciones sobre Fechas*/
			include("../../../../includes/func_fechas.php");
	/**   Código en: pages\alm\includes\obtenerDatosBono.php
      **/	
	  	
	//Verificar si viene el ID del Bono en la URL para obtener los datos de dicho Bono
	if(isset($_GET["idBono"])){
	
		//Recuperar los datos a buscar de la URL
		$idBono = $_GET["idBono"];
		
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		
		//Crear la Sentencia SQL para verificar si hay entrada Registrada
		$sql_stm = "SELECT * FROM bonos WHERE id = $idBono";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);

		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<descripcion>$datos[descripcion]</descripcion>
					<cantidad>$datos[cantidad]</cantidad>
					<autorizo>$datos[autorizo]</autorizo>
					<fecha>".modFecha($datos['fecha_bono'],1)."</fecha>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
		
	}//Cierre if(isset($_GET["idBono"]))
	
	//Obtener las areas registradas en un año y mes dentro del registro de nominas
	else if(!isset($_GET["area"]) && isset($_GET["anio"]) && isset($_GET["mes"])){
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		
		//Recuperar los datos a buscar de la URL
		$anio = $_GET["anio"];
		$mes = $_GET["mes"];	
		
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT area FROM nomina_interna WHERE anio = $anio AND mes = '$mes' ORDER BY area";
		
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		//Obtener la cantidad de registros
		$tam = mysql_num_rows($rs);		
		$cont = 1;
		
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo "<existe><valor>true</valor><tam>$tam</tam>";
			do{
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("<dato$cont>$datos[area]</dato$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
		
	}//Cierre else if(isset($_GET["anio"]) && isset($_GET["mes"]))
	
	
	//Obtener las Nominas registradas en un año, mes y área determinados	
	else if(isset($_GET["area"])){
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		
		//Recuperar los datos a buscar de la URL
		$area = $_GET["area"];
		$anio = $_GET["anio"];
		$mes = $_GET["mes"];	
		
		//Crear la Sentencia SQL
		$sql_stm = "SELECT * FROM nomina_interna WHERE anio = $anio AND mes = '$mes' AND area = '$area' ORDER BY fecha_inicio ASC";
		
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		//Obtener la cantidad de registros
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
					<idNomina$cont>$datos[id_nomina]</idNomina$cont>
					<periodo$cont>$datos[periodo] del ".modFecha($datos['fecha_inicio'],1)." al ".modFecha($datos['fecha_fin'],1)."</periodo$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	
	
?>

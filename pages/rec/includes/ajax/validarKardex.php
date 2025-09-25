<?php
	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 15/Junio/2011
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de un empleado que haya sido dado de baja con anterioridad
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/*		2. Operaciones sobre Fechas*/
			include("../../../../includes/func_fechas.php");
	/**   Código en: pages\alm\includes\validarDatoBD.php                                   
      **/	
	  	
	//Obtener el RFC del Empleado y verificar su antiguedad y la posible existencia de un prestamo para el Empleado Seleccionado		
	if(isset($_GET["datoBusq"]) && isset($_GET["fechaE"])){//Validar una clave en la BD
		//Recuperar los datos a buscar de la URL
		$datoBusq = $_GET["datoBusq"];
		$fechaE = modFecha($_GET["fechaE"],3);
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		
		//Crear la Sentencia SQL para verificar si hay entrada Registrada
		$sql_stm = "SELECT empleados_rfc_empleado AS rfcExistente FROM kardex WHERE fecha_entrada='$fechaE' AND empleados_rfc_empleado='$datoBusq'";
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
					<rfc>$datoBusq</rfc>
					<num>$num</num>
				</existe>");
		}
		else{
			echo "
				<no-existe>
					<valor>false</valor>
					<num>$num</num>
				</no-existe>
			";
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre else if(isset($_GET["datoBusq"]))
?>

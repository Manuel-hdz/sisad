<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 09/Marzo/2012
		  * Descripción: Este archivo contiene la funcion que permite generar el id de las bitacoras dependiendo deel tipo de reisiduo seleccionado
	  **/
	  
	  /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			//Incluimos archivo para modificar fechas
			include("../../../../includes/func_fechas.php");
	/**    
      **/	
	//Conectarse a la BD
	$conn = conecta("bd_recursos");
	
	//Comprobamos que exista el nombre en el GET
	if($_GET['nombre']){
		
		//Variable que nos permitira almacenar el nombre que viene definido en el GET
		$nombre =$_GET['nombre'];
		
		//Creamos la sentencia SQL
		$stm_sql="SELECT fecha_ingreso, id_empleados_empresa FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$nombre'";
		
		//Ejecutamos la consulta
		$rs = mysql_query($stm_sql);
		
		//Guardamos el resultado de la consulta en un arreglo de datos
		if($datos=mysql_fetch_array($rs)){
		
		
			//Guardamos el valor buscado
			$fechaIngreso =round((restarFechas($datos["fecha_ingreso"],date("Y-m-d"))/365),2);
			$idEmp = $datos['id_empleados_empresa'];
			
		
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<antiguedad>$fechaIngreso</antiguedad>
					<noEmp>$idEmp</noEmp>
				</existe>");
			//Cerrar la conexion a la BD
			mysql_close($conn);
		}
		else{
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");
		}
	}
?>
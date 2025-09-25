<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 04/Abril/2012
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: ../man/includes/ajax/cargarComboPersonalRH.php
      **/	
	  
	
	//Codigo para obtener los empleados de un área específica
	if(isset($_GET['area'])){	
		//Recuperar los datos a buscar de la URL
		$area = $_GET["area"];	
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombreEmpleado FROM empleados WHERE area='$area' AND estado_actual='ALTA' ORDER BY nombreEmpleado";
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
				echo utf8_encode("<empleado$cont>$datos[nombreEmpleado]</empleado$cont>");
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

	//Codigo para obtener los datos de un Empleado Especifico mediante codigo de Barras
	/*if(isset($_GET['numEmp'])){	
		//Recuperar los datos a buscar de la URL
		$numEmp = $_GET["numEmp"];	
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombreEmpleado,area FROM empleados WHERE id_empleados_empresa='$numEmp'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<empleado>$datos[nombreEmpleado]</empleado>
					<area>$datos[area]</area>
				</existe>
			");
		}//Cierre if($datos=mysql_fetch_array($rs))
		else
			echo "<valor>false</valor>";
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['familia']))*/
	
	//Codigo para obtener los datos de un Empleado Especifico mediante codigo de Barras
	if(isset($_GET['numEmp'])){	
		//Recuperar los datos a buscar de la URL
		$numEmp = $_GET["numEmp"];	
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombreEmpleado,area,id_control_costos,id_cuentas FROM empleados WHERE id_empleados_empresa='$numEmp'  AND estado_actual='ALTA'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<empleado>$datos[nombreEmpleado]</empleado>
					<area>$datos[area]</area>
			");
			if($datos[id_control_costos] != ""){
				echo utf8_encode("<costos>$datos[id_control_costos]</costos>
					<cuentas>$datos[id_cuentas]</cuentas>
				</existe>");
			}else{
				echo utf8_encode("<costos>'sin_dato'</costos>
					<cuentas>'sin_dato'</cuentas>
				</existe>");
			}
		}//Cierre if($datos=mysql_fetch_array($rs))
		else
			echo "<valor>false</valor>";
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['familia']))
?>
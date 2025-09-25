<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 15/Marzo/2011                                 			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los dato especifico en la BD indicada
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Manipulacion de formatos de fechas*/
			include("../conexion.inc");			
			include("../func_fechas.php");			
			
	/**   Código en: \includes\ajax\obtenerDatoBD.php
      **/
	
	
	//Obtener los datos de la Nomina Interna para cada trabajador a partir de su RFC y las fechas de registro
	if(isset($_GET['opcRealizar']) && $_GET['opcRealizar']=="obtenerNominaInterna"){
		//Recuperar los datos a buscar de la URL
		$rfcBuscar = $_GET["rfcBuscar"];				
		$fechaIni = modFecha($_GET["fechaIni"],3);
		$fechaFin = modFecha($_GET["fechaFin"],3); 
		
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT * FROM nomina_interna WHERE empleados_rfc_empleado = '$rfcBuscar' AND fecha_nomina_inicio = '$fechaIni' AND fecha_nomina_fin = '$fechaFin'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("
				<existe>
					<valor>true</valor>																		
					<rfc>$rfcBuscar</rfc>
					<fechaIni>".modFecha($datos['fecha_nomina_inicio'],1)."</fechaIni>
					<fechafin>".modFecha($datos['fecha_nomina_fin'],1)."</fechafin>										
					<diaFestivo>".number_format($datos['dia_festivo'],2,".",",")."</diaFestivo>
					<diasTrabajados>$datos[dias_trabajados]</diasTrabajados>
					<sueldoDiario>".number_format($datos['sueldo_diario'],2,".",",")."</sueldoDiario>
					<sueldoSemana>".number_format($datos['sueldo_semana'],2,".",",")."</sueldoSemana>
					<tiempoExtra>".number_format($datos['tiempo_extra'],2,".",",")."</tiempoExtra>
					<domingo>".number_format($datos['domingo'],2,".",",")."</domingo>
					<total>".number_format($datos['total'],2,".",",")."</total>
					<asistencia>$datos[dias_trabajados]</asistencia>
				</existe>");	
		}
		else{
		//Creamos la consulta para obtener las asistencias de los empleados
		$stm_sql2="SELECT count(estado) AS asistencia FROM kardex WHERE estado='A' AND empleados_rfc_empleado = '$rfcBuscar' AND fecha_entrada>='$fechaIni'
					AND fecha_salida<='$fechaFin'";
		//Ejecutamos la consulta
		$rs2=mysql_query($stm_sql2);
		//Guardamos ene l arreglo los datos
		$asistencia=mysql_fetch_array($rs2);
			echo utf8_encode("
				<existe>
					<valor>false</valor>
					<rfcEmpleado>$rfcBuscar</rfcEmpleado>
					<asistencia>$asistencia[asistencia]</asistencia>
				</existe>");
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	//Obtener la clave que sera asigana al Empleado de acuerdo al Area en la que será registrado
	else if(isset($_GET['opcRealizar']) && $_GET['opcRealizar']=="obtenerClaveArea"){
		//Recuperar los datos a buscar de la URL
		$nomArea = $_GET["nomArea"];				
		
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT MAX(id_empleados_area) AS id_area FROM empleados WHERE area = '$nomArea'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			if($datos['id_area']!=""){
				$claveArea=$datos["id_area"]+1;
				echo utf8_encode("
					<existe>
						<valor>true</valor>																		
						<claveArea>$claveArea</claveArea>
					</existe>");	
			}
			else
				echo "<valor>false</valor>";
		}
		else
			echo "<valor>false</valor>";
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	//Obtener el RFC de un Empleado en Recursos Humanos a partir de su nombre completo
	else if(isset($_GET['opcRealizar']) && $_GET['opcRealizar']=="obtenerRFCEmpleado"){
		//Recuperar los datos a buscar de la URL
		$nomEmpleado = $_GET["nomEmpleado"];				
		
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm= "SELECT rfc_empleado FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$nomEmpleado'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("
				<existe>
					<valor>true</valor>																		
					<RFCEmpleado>$datos[rfc_empleado]</RFCEmpleado>
				</existe>");	
		}
		else
			echo "<valor>false</valor>";
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	//Obtener el ID de un Empleado en Recursos Humanos a partir de su nombre completo
	else if(isset($_GET['opcRealizar']) && $_GET['opcRealizar']=="obtenerIDEmpleado"){
		//Recuperar los datos a buscar de la URL
		$nomEmpleado = $_GET["nomEmpleado"];				
		
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL
		$sql_stm= "SELECT id_empleados_empresa FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$nomEmpleado'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("
				<existe>
					<valor>true</valor>																		
					<IDEmpleado>$datos[id_empleados_empresa]</IDEmpleado>
				</existe>");	
		}
		else
			echo "<valor>false</valor>";
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	//Obtener el Nombre Completo de Un registro en la Base de Datos y Tabla indicadas
	else if(isset($_GET['opcRealizar']) && $_GET['opcRealizar']=="obtenerNombreCompleto"){
		//Recuperar los datos a buscar de la URL
		$idRegistro = $_GET["idRegistro"];
		$columaRef = $_GET["columaRef"];
		$campNombre = $_GET["campNombre"];
		$campApePat = $_GET["campApePat"];
		$campApeMat = $_GET["campApeMat"];
		$nomBD = $_GET["nomBD"];
		$nomTabla = $_GET["nomTabla"];
						
		
		//Conectarse a la BD
		$conn = conecta("$nomBD");
		//Crear la Sentencia SQL para obtener el nombre completo
		$sql_stm = "SELECT CONCAT($campNombre,' ',$campApePat,' ',$campApeMat) AS nombre FROM $nomTabla WHERE $columaRef = '$idRegistro'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("
				<existe>
					<valor>true</valor>																		
					<nombreCompleto>$datos[nombre]</nombreCompleto>
				</existe>");	
		}
		else
			echo "<valor>false</valor>";
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	//Obtener el Saldo Actual de una Deducción para registrar un Abono
	else if(isset($_GET['opcRealizar']) && $_GET['opcRealizar']=="obtenerSaldoActual"){
		//Recuperar los datos a buscar de la URL
		$idDeduccion = $_GET["idDeduccion"];						
		
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Crear la Sentencia SQL para obtener el nombre completo
		$sql_stm = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre, saldo_final, deducciones.estado
					FROM (detalle_abonos JOIN deducciones ON deducciones_id_deduccion=id_deduccion) JOIN empleados ON empleados_rfc_empleado=rfc_empleado  
					WHERE deducciones_id_deduccion = '$idDeduccion' 
					AND fecha_abono = (SELECT MAX(fecha_abono) FROM detalle_abonos WHERE deducciones_id_deduccion = '$idDeduccion') ORDER BY saldo_final ASC";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("
				<existe>
					<valor>true</valor>																		
					<saldoFinal>$datos[saldo_final]</saldoFinal>
					<nombreEmpleado>$datos[nombre]</nombreEmpleado>
					<estado>$datos[estado]</estado>
				</existe>");	
		}
		else
			echo "<valor>false</valor>";
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	//Obtener el Saldo Actual de una Deducción para registrar un Abono
	else if(isset($_GET['opcRealizar']) && $_GET['opcRealizar']=="obtenerMetricaEquipo"){
		//Recuperar los datos a buscar de la URL
		$claveEquipo = $_GET["claveEquipo"];						
		
		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		//Crear la Sentencia SQL para obtener el nombre completo
		$sql_stm = "SELECT reg_final, metrica FROM horometro_odometro  JOIN equipos ON equipos_id_equipo=id_equipo  WHERE equipos_id_equipo = '$claveEquipo' AND fecha = (SELECT MAX(fecha) FROM horometro_odometro WHERE equipos_id_equipo = '$claveEquipo')";
		//Ejecutar la Sentencia previamente creada		
		$rs = mysql_query($sql_stm);
		
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo utf8_encode("
				<existe>
					<valor>true</valor>																		
					<cantMetrica>$datos[reg_final]</cantMetrica>
					<metrica>$datos[metrica]</metrica>
				</existe>");	
		}
		else {
			include_once ("../op_operacionesBD.php");
			$metrica=obtenerDato('bd_mantenimiento','equipos','metrica','id_equipo', $claveEquipo);
			echo utf8_encode("
				<existe>
					<valor>false</valor>
					<metrica>$metrica</metrica>
				</existe>");	

		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	//Obtener un datos de la Base de Datos y tabla indicadas mediante un campo de referencia
	else if(isset($_GET['BD'])){		 
		//Recuperar los datos a buscar de la URL
		$datoBusq = $_GET["datoBusq"];
		$BD = $_GET["BD"];	
		$tabla = $_GET["tabla"];
		$campoBusq = $_GET["campoBusq"];
		$campoRef = $_GET["campoRef"];
		
		
		//Conectarse a la BD
		$conn = conecta("$BD");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT DISTINCT $campoBusq FROM $tabla WHERE $campoRef = '$datoBusq'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo ("
					<existe>
						<valor>true</valor>
						<dato>$datos[$campoBusq]</dato>
					</existe>");	
		}
		else{
			echo "<valor>false</valor>";
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
?>

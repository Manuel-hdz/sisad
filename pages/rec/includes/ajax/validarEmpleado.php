<?php
	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 20/Abril/2011
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
	if(isset($_GET["opcRealizar"]) && $_GET["opcRealizar"]=="validarEstadoEmpleado"){
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		
		//Obtener el nombre del Empleado de la URL
		$nombreEmp = $_GET["nomEmpleado"];
		$rfcEmpleado = "";
		
		//Crear la Sentencia SQL para verificar que el Empleado no tenga un pretamo vigente
		$sql_stm = "SELECT id_deduccion, nom_deduccion, rfc_empleado FROM empleados JOIN deducciones ON rfc_empleado=empleados_rfc_empleado 
					WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat) = '$nombreEmp' AND deducciones.estado!='TERMINADO' AND id_deduccion LIKE 'PRE%'";
					
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Si se obtiene un registro significa que el Empleado tiene un Prestamo Vigente
		if($datos=mysql_fetch_array($rs)){
			//PRESTAMO VIGENTE
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<condicion>prestamoExistente</condicion>
					<rfcEmpleado>$datos[rfc_empleado]</rfcEmpleado>
					<nombre>$nombreEmp</nombre>
					<idDeduccion>$datos[id_deduccion]</idDeduccion>
					<nomDeduccion>$datos[nom_deduccion]</nomDeduccion>
				</existe>");
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			//Ejecutar la Sentencia SQL para verificar la Antiguedad del Empleado
			$rs_datosEmp = mysql_query("SELECT rfc_empleado, fecha_ingreso FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat) = '$nombreEmp'");
			//Verificar que el empleado indicado se encuentre registrado en el catalogo de Recursos Humanos
			if($datosEmp=mysql_fetch_array($rs_datosEmp)){				
				//Guardar el RFC del Empleado que esta siendo evaluado para consultas posteriores
				$rfcEmpleado = $datosEmp['rfc_empleado'];
			
				//Calcular la Antiguedad del Empleado, primero hay que separar las Fechas
				$arrFechaIni = split("-",$datosEmp['fecha_ingreso']);//Formato aaaa-mm-dd 
				$arrFechaFin = split("/",date("m/d/Y"));//Formato mm/dd/aaaa
				//Obtener la Diferencia en Dias, parametros(int $mes, int $dia, int $año)
				$diferencia = gregoriantojd($arrFechaFin[0],$arrFechaFin[1],$arrFechaFin[2]) - gregoriantojd($arrFechaIni[1],$arrFechaIni[2],$arrFechaIni[0]);
				//Dart formato numerico a la diferencia obtenidad, la cual esta dada en meses
				$meses = number_format(($diferencia/30),1,".",",");
				
				//Si la antiguedad del Trabajador es igual o menor a 90 días, no puede ser candidato a recibir un Prestamo
				if($diferencia<=90){
					//ANTIGUEDAD MENOR A 3 MESES
					echo utf8_encode("
						<existe>
							<valor>true</valor>																					
							<condicion>antiguedadRequerida</condicion>
							<rfcEmpleado>$datosEmp[rfc_empleado]</rfcEmpleado>
							<nombre>$nombreEmp</nombre>														
							<antiguedad>$meses</antiguedad>
						</existe>");
				}//Cierre if($diferencia<=90)
				else if($diferencia>90){//El Empleado es candidato a recibir un Prestamo
					
					
					//Verificar si el ultimo prestamo del empleado tiene mas de 6 meses de antiguedad de haber sido liquidado
					$sql_stm_prest = "SELECT MAX(fecha_abono) AS fecha_liquidacion FROM detalle_abonos WHERE deducciones_id_deduccion = ANY 
					(SELECT id_deduccion FROM deducciones WHERE empleados_rfc_empleado = '$rfcEmpleado' AND estado = 'TERMINADO' AND id_deduccion LIKE 'PRE%' 
					ORDER BY fecha_alta DESC)";
					//Ejecutar sentencia SQL
					$rs_prest = mysql_query($sql_stm_prest);
					
					//Verificar si el empleado tiene prestamos liquidados registrados
					if($datosPres=mysql_fetch_array($rs_prest)){
					
						//Verificar que la fecha obtenida sea diferente de NULL
						$diferenciaDias = "No Hay Prestamos Previos";
						if($datosPres['fecha_liquidacion']!=NULL){
							//Obtener la cantidad de dias que han transcurridad de la fecha de liquidación del prestamo						
							$rsDif = mysql_query("SELECT DATEDIFF(NOW(),'$datosPres[fecha_liquidacion]') AS diferencia");
							$datosDif = mysql_fetch_array($rsDif);
							$diferenciaDias = $datosDif['diferencia'];
						}
						
						//Si no hay prestamos previos registrados, conceder el prestamo
						if($diferenciaDias=="No Hay Prestamos Previos"){
							//PRESTAMO OTORGADO
							echo utf8_encode("
							<existe>
								<valor>true</valor>
								<condicion>candidatoPrestamo</condicion>
								<rfcEmpleado>$rfcEmpleado</rfcEmpleado>
								<nombre>$nombreEmp</nombre>
								<antiguedad>$meses</antiguedad>
							</existe>");
						}//Cierre if($diferenciaDias=="No Hay Prestamos Previos")
						
						//Sí la diferencia entre la fecha de liquidación del ultimo prestamo y la fecha actual es menor a 180 (6 meses) negar el prestamo
						else if($diferenciaDias<=180){
							//PRESTAMO NO OTORGADO POR NO HABER PASADO MAS DE 6 MESES DESDE LA LIQUIDACION DEL ULTIMO PRESTAMO OTORGADO
							echo utf8_encode("
							<existe>
								<valor>true</valor>
								<condicion>SeisMesesNoCumplidos</condicion>
								<rfcEmpleado>$rfcEmpleado</rfcEmpleado>
								<nombre>$nombreEmp</nombre>
								<fechaLiqUltPrestamo>".modFecha($datosPres['fecha_liquidacion'],1)."</fechaLiqUltPrestamo>
								<antiguedad>$meses</antiguedad>
							</existe>");
						}//Cierre else if($diferenciaDias<=180)
						
						//Sí la diferencia entre la fecha de liquidación del ultimo prestamo y la fecha actual es mayor a 180 (6 meses) conceder el prestamo
						else if($diferenciaDias>180){
							//PRESTAMO OTORGADO
							echo utf8_encode("
							<existe>
								<valor>true</valor>							
								<condicion>candidatoPrestamo</condicion>
								<rfcEmpleado>$datosEmp[rfc_empleado]</rfcEmpleado>
								<nombre>$nombreEmp</nombre>
								<antiguedad>$meses</antiguedad>
							</existe>");
						}//Cierre else if($diferenciaDias>180)
						
					}//Cierre if($datosPres=mysql_fetch_array($rs_prest))

				}//Cierre else if($diferencia>90)
				
			}//Cierre if($datosEmp=mysql_fetch_array($rs))
			else//No hay registros para el empleado seleccionado
				echo "<valor>false</valor>";				
		}//Cierre ELSE
		
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET["opcRealizar"]) && $_GET["opcRealizar"]=="validarEstadoEmpleado")
	
	
	else if(isset($_GET["datoBusq"])){//Validar una clave en la BD
		//Recuperar los datos a buscar de la URL
		$datoBusq = $_GET["datoBusq"];
		include_once("../../../../includes/op_operacionesBD.php");
		//Verificar si existe en la tabla de empleados
		$duplicado=obtenerDato("bd_recursos", "empleados", "rfc_empleado", "rfc_empleado", $datoBusq);
		//Verificar si existe en la tabla de Bajas
		$bajas=obtenerDato("bd_recursos", "bajas_modificaciones", "empleados_rfc_empleado", "empleados_rfc_empleado", $datoBusq);
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
				
		//Crear la Sentencia SQL
		$sql_stm = "SELECT empleados_rfc_empleado,nombre,ape_pat,ape_mat,MAX(fecha_baja) AS fecha_baja,observaciones FROM bajas_modificaciones 
					WHERE empleados_rfc_empleado='$datoBusq'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		if($duplicado=="" && $bajas!=""){
			//Comparar los resultados obtenidos
			if($datos=mysql_fetch_array($rs)){
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("
					<existe>
						<valor>true</valor>
						<clave>$datos[empleados_rfc_empleado]</clave>
						<nombre>$datos[nombre]</nombre>
						<apePat>$datos[ape_pat]</apePat>
						<apeMat>$datos[ape_mat]</apeMat>
						<baja>".modFecha($datos["fecha_baja"],2)."</baja>
						<observaciones>$datos[observaciones]</observaciones>
					</existe>");
			}//Cierre if($datos=mysql_fetch_array($rs))
			else{
				echo "<valor>false</valor>";
			}
		}//Cierre if($duplicado=="" && $bajas!="")
		else
			echo "<valor>false</valor>";
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
		
	}//Cierre else if(isset($_GET["datoBusq"]))
		
?>
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
	/*		3. Operaciones sobre la BD*/
			include("../../../../includes/op_operacionesBD.php");
	/**   Código en: pages\alm\includes\validarDatoBD.php                                   
      **/	
	  	
	//Verificar si esta definido el RFC		
	if(isset($_GET["rfc"])){//Validar una clave en la BD
		//Recuperar los datos del GET
		$rfc=$_GET["rfc"];
		$inc=$_GET["inc"];
		$com=$_GET["com"];
		$fechaI=$_GET["fechaI"];
		$fechaF=$_GET["fechaF"];
		//Obtener el numero de Empleado
		$numEmp=obtenerDato("bd_recursos","empleados","id_empleados_empresa","rfc_empleado",$rfc);
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Obtener la cantidad de Dias entre las 2 Fechas
		$dias=restarFechas($fechaI,$fechaF)+1;
		//Contador para recorrer los dias de las fechas
		$cont=0;
		//Obtener el Turno
		$turno=obtenerDato("bd_recursos","roles","turnos_id_turno","empleados_rfc_empleado",$rfc);
		//Obtener las horas de Entrada y Salida del Turno
		$horaE=obtenerDato("bd_recursos","turnos","hora_entrada","id_turno",$turno);
		$horaS=obtenerDato("bd_recursos","turnos","hora_salida","id_turno",$turno);
		//Si la incidencia se paga, obtener el turno y las horas de entrada y salida
		/* A=>Asistencia, V=>Vacaciones, r=>Retardo, F/j=>Falta justificada, P/G=>Permiso con Goce */
		if($inc=="A" || $inc=="V" || $inc=="r" || $inc=="F/j" || $inc=="P/G"){
			if($horaE==""){
				//Obtener el numero de horas por Jornada Laboral
				$jornada=obtenerDato("bd_recursos","empleados","jornada","rfc_empleado",$rfc);
				$horaE="06:00:00";
				$horaS=(6+$jornada).":00:00";
			}
		}
		//Si la incidencia NO se paga, asignar a las horas de Entrada y Salida la misma hora
		else{
			if($horaE==""){
				//Obtener el numero de horas por Jornada Laboral
				$jornada=obtenerDato("bd_recursos","empleados","jornada","rfc_empleado",$rfc);
				$horaE="06:00:00";
				$horaS="06:00:00";
			}
			else
				$horaS=$horaE;
		}
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
		echo utf8_encode("<existe>
							<valor>true</valor>
		");
		//Hacer recorrido de fechas
		do{
			//Obtener la fecha de verificacion
			$fechaActual=sumarDiasFecha($fechaI,$cont);
			//Sentencia para actualizar el registro de incidencia
			$sql_stm = "UPDATE checadas SET estado='$inc',comentario='$com' WHERE fecha_checada='$fechaActual' AND estado!='SALIDA' AND empleados_rfc_empleado='$rfc'";
			//Ejecutar la sentencia
			$rs=mysql_query($sql_stm);
			//Verificar si el registro fue modificado, si no es asi, el registro NO existe... Agregarlo
			if(mysql_affected_rows()==0){
				//Sentencia de Entrada
				$sql_stm = "INSERT INTO checadas (empleados_rfc_empleado,empleados_id_empleados_empresa,fecha_checada,hora_checada,estado,comentario) VALUES ('$rfc','$numEmp','$fechaActual','$horaE','$inc','$com')";
				$rs=mysql_query($sql_stm);
				if($rs){
					$sql_stm = "INSERT INTO checadas (empleados_rfc_empleado,empleados_id_empleados_empresa,fecha_checada,hora_checada,estado,comentario) VALUES ('$rfc','$numEmp','$fechaActual','$horaS','SALIDA','$com')";
					$rs=mysql_query($sql_stm);
					if($rs)
						echo utf8_encode("<F$fechaActual>AGREGADO</F$fechaActual>");
				}
			}else
				echo utf8_encode("<F$fechaActual>MODIFICADO</F$fechaActual>");
			$cont++;
		}while($cont<$dias);
		//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
		echo utf8_encode("</existe>");
		//Cerrar la conexion a la BD
		mysql_close($conn);
		//Abrir la Sesion para registrar la Operacion
		session_start();
		//Registrar la Operacion en la Bitácora de Movimientos
		registrarOperacion("bd_recursos","$rfc","RegIncidenciaKardex $inc",$_SESSION['usr_reg']);
	}//Cierre else if(isset($_GET["datoBusq"]))
?>

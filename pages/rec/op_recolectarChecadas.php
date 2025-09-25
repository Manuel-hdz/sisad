<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas y Miguel Angel Garay Castro
	  * Fecha: 01/Febrero/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con los formularios de AgregarEmpleado en la BD o no? Oo
	**/


	//Función para extraer las Checadas de los empleados de la Base de Datos de Access y cargarla en los arreglos (IBIX)
	function cargarChecadasIbix(){										
		
		//Datos para realizar la Conexión a la BD Access de IBIX
		$user = "Administrador";//Usuario
		$password = "libre";//Password		
				
		//Ubicacion (Directorio en el Servidor) de la Base de Datos de Access
		$mdbFilename = realpath("handpunch\IBIXConnect.mdb");
		//Conexion a la Base de Datos de Access
		$db_connstr = "Driver={Microsoft Access Driver (*.mdb)}; Dbq=$mdbFilename";
		$connAccess = odbc_connect($db_connstr, $user, $password);
				
		//Preparar la sentencia Access para Extraer la información de todas las checadas de los trbajadores
		$stm_acc = "SELECT Trabajador,Checada FROM tblChecada ORDER BY Trabajador,Checada;";
		//Ejecutar la sentencia
		$rs = odbc_exec($connAccess,$stm_acc);
				
		//Verificar si hay datos para Procesar
		if($datos=odbc_fetch_array($rs)){
		
			//Definir el nombre y directorio del Archivo de errores de inserción de datos
			$nom_archivo = "documentos/errChecadas.txt";		
			//Verificar si el archivo existe para borrarlo y asi solo almacenar los errores generados en la última Recolección de Checadas
			if(file_exists($nom_archivo))
				unlink($nom_archivo);
						
			/*Abre un archivo o URL, el parámetro 'a' indica apertura para sólo escritura; coloca el puntero al archivo al final del archivo. 
			  Si el archivo no existe se intenta crear.*/
			$fp = fopen($nom_archivo, 'a');						
						
			
			//Crea la conexion con al Base de Datos de Recurso Humanos con MySQL
			$connMySQL = conecta("bd_recursos");
			
			//Recorrrer cada uno de los Registro de la Base de Datos de Access (IBIX)
			do{
				//Recuperar la Hora y Fecha de la Checada que esta siendo procesada
				$fechaChecada = substr($datos["Checada"],0,10);//Fecha Extraida en formato aaaa-mm-dd
				$horaChecada = substr($datos["Checada"],-8);//Hora Extraida en formato de 24 horas (hh:mm:ss)
				//Obtener el No. de Identificación en la Empresa de cada empleado
				$idEmpleadoEmpresa = $datos["Trabajador"];
												
				//Obtener el estado(E => Entrada, S=> Salida ó D=>Descartada) del registro de checada actual en base a los registro existentes en la BD de Recursos Humanos
				$estado = determinarEstadoChecada($idEmpleadoEmpresa,$fechaChecada,$horaChecada);
								
								
				//Si el estado obtenido es E => Entrada ó S=> Salida, proceder a registrar la checada actual en la BD de Recursos Humanos
				if($estado=="E" || $estado=="S"){
					registrarChecada($idEmpleadoEmpresa,$fechaChecada,$horaChecada,$estado,$fp);
				}
				else{
					//Si el estado obtenido es D => Descartada, el registro actual de checada no es registrado en la BD de Recursos Humanos
				}								

			}while($datos=odbc_fetch_array($rs));			
			
			//Cerrar conexion con la BD de MySQL
			mysql_close($connMySQL);
			
			//Cierra un puntero a un archivo abierto
			fclose($fp);
															
			//Cerrar la conexion con la BD
			odbc_close($connAccess);		
			
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_recursos","CHECADAS","RecoleccionDatos",$_SESSION['usr_reg']);
			
			//Redireccionar a la pagina de Error con el Error obtenido
			echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
			
			//Después de procesar todas las Checadas, procedemos a Borrar los datos de la BD de Access (IBIX) ?>
			<script type="text/javascript" language="javascript">
				limpiarIbix();
			</script><?php
						
		}//Cierre if($datos=odbc_fetch_array($rs))
		else{
			//Obtener el Error generado en el Driver ODBC
			$error =  odbc_errormsg();
			//Cerrar la conexion con la BD
			odbc_close($connAccess);
			
			//Si la descripción del error viene vacia, siginifica que no hay datos para procesar
			if($error=="")
				$error = "NO Hay Registros de Checadas Para Importar"; 
			
			//Redireccionar a la pagina de Error con el Error obtenido
			echo "<meta http-equiv='refresh' content='2;url=error.php?err=$error'>";			
			
		}
		
	}//Fin de function cargarChecadasIbix()


	/* Función que verifica la última checada del Trabajador registrada en la BD de Recursos Humanos de MySQL para determinar si el siguiente registro 
	 * será E => Entrada, S=> Salida ó D=>Descartada, las checadas descartadas es porque ya se encuentran registradas en la BD de Recursos Humanos
	 */
	function determinarEstadoChecada($idEmpleadoEmpresa,$fechaChecada,$horaChecada){
	
		//Variable que almacenará el estado de la Checada que esta siendo procesada
		$estado = "";
		
		//Crear sentencia SQL para verificar si la checada que esta siendo procesada no se encuentre ya registradas
		$rs_checadaEnProceso = mysql_query("SELECT fecha_checada, hora_checada, estado FROM checadas WHERE empleados_id_empleados_empresa='$idEmpleadoEmpresa' 
											AND fecha_checada = '$fechaChecada' AND hora_checada = '$horaChecada'");
											
		//Verificar si la checada que esta siendo procesada no se encuentre ya registradas
		if($checadaEnProceso=mysql_fetch_array($rs_checadaEnProceso)){
			//Si la checada ya esta registra en la BD de Recursos Humanos, la descartamos para no repetir registros
			$estado = "D";//<-----Asignar el Estado D => Descartada
		}		
		else{//Si la checada no esta registrada, procedemos a evaluarla							
		
			//Obtener el estado del último registro de checada del Trabajador
			$sql_checada = "SELECT fecha_checada, hora_checada, estado FROM checadas WHERE empleados_id_empleados_empresa = '$idEmpleadoEmpresa' 
							AND fecha_checada = (SELECT MAX(fecha_checada) FROM checadas WHERE empleados_id_empleados_empresa = '$idEmpleadoEmpresa')
							AND hora_checada = (SELECT MAX(hora_checada) FROM checadas WHERE empleados_id_empleados_empresa = '$idEmpleadoEmpresa' 
							AND fecha_checada = (SELECT MAX(fecha_checada) FROM checadas WHERE empleados_id_empleados_empresa = '$idEmpleadoEmpresa'))";
			
			
			$rs_checada = mysql_query($sql_checada);
			
			//Si la sentencia ejecutada regresa resultados, verificar el estado de la ultima checada existente en la BD de Recursos Humanos
			if($checadaReg=mysql_fetch_array($rs_checada)){
						
				//Si la ultima incidencia es SALIDA, el sistema contempla que la siguiente es una ENTRADA
				if($checadaReg["estado"]=="SALIDA")
					$estado = "E";//<-----Asignar el Estado E => Entrada
				else
					$estado = "S";//<-----Asignar el Estado S => Salida													
								
			}//Cierre if($checadaReg=mysql_fetch_array($rs_checada))					
			else{
				//Si la sentencia ejecutada NO regresa resultados, significa que no hay registro, por lo tanto la checada actual se considera como E => Entrada
				$estado = "E";//<-----Asignar el Estado E => Entrada
			}
			
		}//Cierre ELSE del if($checadaEnProceso=mysql_fetch_array($rs_checadaEnProceso))						
		
						
		//Retornar el estado obtenido
		return $estado;
		
	}//Fin de function determinarEstadoChecada($idEmpleadoEmpresa,$fechaChecada,$horaChecada)
	
	
	/*Función que asigna una incidencia de acuerdo a la hora en la que se realizo la checada y guarda el registro en la BD de Recursos Humanos*/
	function registrarChecada($idEmpleadoEmpresa,$fechaChecada,$horaChecada,$estado,$fp){
		
		//Obtener el RFC del Empleado
		$datosRFC = mysql_fetch_array(mysql_query("SELECT rfc_empleado FROM empleados WHERE id_empleados_empresa = $idEmpleadoEmpresa"));
		$rfc = $datosRFC['rfc_empleado'];
		
		
		//Obtener del Rol del Trabajador
		$rs_rolEmpleado = mysql_query("SELECT hora_entrada, hora_salida FROM roles JOIN turnos ON turnos_id_turno=id_turno WHERE empleados_rfc_empleado = '$rfc'");
		//Si hay un rol asignao al empleado, obtener la hora de entrada y la hora de salida
		if($rolEmpleado=mysql_fetch_array($rs_rolEmpleado)){
			
			//Obtener la Hora de Entrada segun el Rol asignado
			$horaE = $rolEmpleado['hora_entrada'];
			//Obtener la Hora de Salida segun el Rol asignado
			$horaS = $rolEmpleado['hora_salida'];
			
		}
		else{
			//En caso que el Trabajador no tenga ROL asignado, se le asigna como Hora de Entrada la Hora de la Checada, de forma que siempre sera incidencia (A) = Asistencia			
			$horaE = $horaChecada;
		}
		
						
		//Si el estado es igual a (E), es una entrada, verificar la incidencia que le corresponde
		if($estado=="E"){
			
			//Variable que almacenara la incidencia correspondiente
			$inc = "";
			
			//Obtener la diferencia de la hora checada contra la de Entrada segun el ROL
			$entradaReal = mysql_fetch_array(mysql_query("SELECT TIMEDIFF('$horaChecada','$horaE')"));
			
			//Si el primer digito es '-' indica que es una checada antes de la Hora de Entrada
			if (substr($entradaReal[0],0,1)=="-"){
				//Entro antes de la Hora, toca (A) de Asistencia
				$inc = "A";
			}
			//Si no es negativo, indica que es igual o mayor, en dicho caso verificar que tan mayor es el dato
			else{
				//Extraer la seccion de horas de la diferencia entre Checada y Hora de Entrada segun el Rol
				$hra = substr($entradaReal[0],0,2);
				//Extraer la seccion de mintos de la diferencia entre Checada y Hora de Entrada segun el Rol
				$min = substr($entradaReal[0],3,2);
				//Extraer la seccion de segundos de la diferencia entre Checada y Hora de Entrada segun el Rol
				$seg = substr($entradaReal[0],-2);
				
				//Si hra es igual a 0, la checada es dentro de la misma hora
				if($hra==0){
					if ($min<=10)//Si la diferencia de minutos es de 0 a 10, ingresar (A) de Asistencia
						$inc = "A";
					if ($min>10)//Si la diferencia de minutos es de 10 a una hora, toca (r) de Retraso
						$inc = "r";
				}
				else if($hra>0){//Si hra es mayor a 0, la checada es horas despues de la hora que corresponde
					//Entró HORAS después, toca (F) de FALTA
					$inc = "F";
				}
				
			}//Fin del else
			
			
			//Sentencia SQL para ingresar una Entrada con el incidente que corresponda
			$stm_sql = "INSERT INTO checadas (empleados_rfc_empleado,empleados_id_empleados_empresa,fecha_checada,hora_checada,estado) 
						VALUES ('$rfc','$idEmpleadoEmpresa','$fechaChecada','$horaChecada','$inc')";
			
		}//Fin de if($estado=="E")
		else{			
		
			//NOTA: Antes de registrar la salida, obtener la hora de entrada y calcular las horas trabajadas para ser almacenas en la Base de Datos			
			//Sentencia SQL para Obtener el registro de entrada en la fecha de registro de la Salida
			
			
			//Sentencia SQL para ingresar una SALIDA
			$stm_sql = "INSERT INTO checadas (empleados_rfc_empleado,empleados_id_empleados_empresa,fecha_checada,hora_checada,estado)
						VALUES ('$rfc','$idEmpleadoEmpresa','$fechaChecada','$horaChecada','SALIDA')";
		}				
		
		
		//Ejecutar la Sentecia creada con la entrada o salida en las checadas de cada trabajador
		$rs = mysql_query($stm_sql);//Ejecutar la sentencia SQL que se haya generado
		
		//En caso de haberlo, guardar el Error en un archivo de Errores de Kardex
		if(!$rs){
			
			//Agregar a la descripcion del error los datos del empleado colocando un 'enter' directo entre los datos del Empleado y la Descripción del Error
			$error = $idEmpleadoEmpresa.": ".$fechaChecada." ".$horaChecada."
".mysql_error();

			//Escritura de un archivo en modo binario seguro, $fp=>Puntero de Apertura al Archivo, $error => Datos que serán escritos en el archivo
			fwrite($fp, $error);
			
		}//Cierre if(!$rs)
				
	}//Fin de function verificarIncidencia($sql_stm,$horaE,$horaS)			


?>
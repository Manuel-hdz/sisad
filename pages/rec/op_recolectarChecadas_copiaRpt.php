<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 01/Febrero/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con los formularios de AgregarEmpleado en la BD o no? Oo
	**/


	//Función para extraer las Checadas de los empleados de la Base de Datos de Access y cargarla en los arreglos (IBIX)
	function cargarChecadasIbix(){
		//Datos para realizar la Conexión a la BD Access de IBIX
		$user = "Administrador";//Usuario		
		$password = "libre";//Password
		
		//Ubicacion de la Base de Datos
		$mdbFilename = realpath("handpunch\IBIXConnect.mdb");
		//Conexion a la Base de Datos
		$db_connstr = "Driver={Microsoft Access Driver (*.mdb)}; Dbq=$mdbFilename";
		$conn = odbc_connect($db_connstr, $user, $password);

		//Preparar la sentencia Access para Ingresar al Trabajador
		$stm_acc = "SELECT Trabajador,Checada FROM tblChecada ORDER BY Trabajador,Checada;";
		//Ejecutar la sentencia
		$rs = odbc_exec($conn,$stm_acc);
		if($datos=odbc_fetch_array($rs)){			
			//Arreglos para almacenar los datos extraidos de los empleados
			$fechas = array();
			$horas = array();
			$empleados = array();			
			do{
				//Recuperar la Hora y Fecha de la Checada
				$checada = $datos["Checada"];
				//Extraer la Fecha
				$fecha = substr($checada,0,10);
				//Extraer la Hora
				$hora = substr($checada,-8);
				
				//Arreglos de Fechas, Horas y Empleados
				$fechas[] = $fecha;
				$horas[] = $hora;
				$empleados[] = $datos["Trabajador"];

			}while($datos=odbc_fetch_array($rs));
			
			//Cerrar la conexion con la BD
			odbc_close($conn);
						
			//Validar y copiar los registro de Access a MySQl
			verificarInformacion($empleados,$fechas,$horas);
		}//Cierre if($datos=odbc_fetch_array($rs))
		else{
			//Obtener el Error generado en el Driver ODBC
			$error = odbc_error();
			//Cerrar la conexion con la BD
			odbc_close($conn);
			//Redireccionar a la pagina de Error con el Error obtenido
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}//Fin de function cargarChecadasIbix()


	//Funcion que verifica la informacion recopilada
	function verificarInformacion($empleados,$fechas,$horas){
		//Arreglo de Sentencia SQL a ejecutar
		$sql_stm = array();
		//Contador de los ciclos, para accesar a los indices de cada registro hecho en los arreglos $empleados, $fechas y $horas
		$cont = 0;
		do{
			
			//Variable que indica que se va a guardar el Registro siempre y cuando su valor sea 1
			$guardar = 0;
						
			//Si es el primer registro, verificar la hora y fecha con la Base de Datos
			if ($cont==0){//REGISTRO INICIAL
				//Variable que segun el valor que tome, indicara si se agrega o no el dato al arreglo de Sentencias
				$agregar = verificarDatosBD($empleados[$cont],$fechas[$cont]);
				//Si el valor de agregar es verdadero, verificar el tipo de checada que corresponde
				if($agregar){
					//Variable que segun el valor que tome, indicara la incidencia que corresponde al trabajador (E=>Entrada y S=>Salida)
					$checada = verificarChecada($empleados[$cont]);
					//Agregar al arreglo de sentencias el registro del ID del Trabajador -> la Hora de Checada -> la Fecha de Checada -> Incidencia del Trabajador
					$sql_stm[] = $empleados[$cont]."->".$horas[$cont]."->".$fechas[$cont]."->".$checada;
					//Indicar que el registro se va a guardar, cambiando el valor de guardar de 0 a 1
					$guardar = 1;
				}//Fin de if ($agregar){
			}//Fin de //Si es el primer registro, verificar la hora y fecha con la Base de Datos
			
			
			else{//Siguientes Registros
				
				
				//Verificar si la Fecha Actual es igual a la anterior
				if ($fechas[$cont]==$fechas[$cont-1]){//MISMAS FECHA
				
					
					//Verificar si el Empleado Actual es igual al Anterior
					if ($empleados[$cont]==$empleados[$cont-1]){//MISMA FECHA Y MISMO TRABAJADOR
						//Variable para controlar el numero de registros por trabajador
						$band = 0;
						//Verificar que el empleado solo aparezca una vez mas, en caso contrario, no Agregarlo
						//Considerando que los 2 primeros registros al dia, son los que se agregan al Registro
						foreach($sql_stm AS $ind => $value){
							//Si el empleado ya existe en el arreglo, incrementar la variable de Control
							if(substr($value,0,4)==$empleados[$cont])
								$band++;//<------Variable de Control
							//Si el empleado aparece mas de 2 veces, romper el ciclo para no agregar
							//un tercer registro que se vaya a guardar
							if ($band>1)
								break;
						}//Fin del Foreach
						//Si despues del proceso, band vale 2 o menos, proceder a guardar el registro
						if ($band<=1){
							//Variable que segun el valor que tome, indicara si se agrega o no el dato al arreglo de Sentencias
							$agregar = verificarDatosBD($empleados[$cont],$fechas[$cont]);
							//Si el valor de agregar es verdadero, verificar el tipo de checada que corresponde
							if ($agregar){
								//Variable que segun el valor que tome, indicara la incidencia que corresponde al trabajador
								$checada = verificarChecada($empleados[$cont]);
								//Agregar al arreglo de sentencias el registro del ID del Trabajador -> la Hora de Checada -> la Fecha de Checada -> Incidencia del Trabajador
								$sql_stm[] = $empleados[$cont]."->".$horas[$cont]."->".$fechas[$cont]."->$checada";
								//Indicar que el registro se va a guardar, cambiando el valor de guardar de 0 a 1
								$guardar = 1;
							}//Fin de if ($agregar){
						}//Fin de if ($band<=1){
					}//Fin de if ($empleados[$cont]==$empleados[$cont-1]){
										
					
					//Si el Empleado no es igual al anterior, verificar si se agregará o no					
					else{//MISMA FECHA y DIFERENTE TRABAJADOR
						//Variable que segun el valor que tome, indicara si se agrega o no el dato al arreglo de Sentencias
						$agregar = verificarDatosBD($empleados[$cont],$fechas[$cont]);
						//Si el valor de agregar es verdadero, verificar el tipo de checada que corresponde
						if ($agregar){
							//Variable que segun el valor que tome, indicara la incidencia que corresponde al trabajador
							$checada = verificarChecada($empleados[$cont]);
							//Agregar al arreglo de sentencias el registro del ID del Trabajador -> la Hora de Checada -> la Fecha de Checada -> Incidencia del Trabajador
							$sql_stm[] = $empleados[$cont]."->".$horas[$cont]."->".$fechas[$cont]."->$checada";
							//Indicar que el registro se va a guardar, cambiando el valor de guardar de 0 a 1
							$guardar = 1;
						}//Fin de if ($agregar){
					}//Fin del else{
				}//Fin del if ($fechas[$cont]==$fechas[$cont-1]){
				
								
				//Si la Fecha no es igual a la anterior, verificar si se agregará o no
				else{//DIFERENTE FECHA
					//Variable que segun el valor que tome, indicara si se agrega o no el dato al arreglo de Sentencias
					$agregar = verificarDatosBD($empleados[$cont],$fechas[$cont]);
					//Si el valor de agregar es verdadero, verificar el tipo de checada que corresponde
					if ($agregar){
						//Variable que segun el valor que tome, indicara la incidencia que corresponde al trabajador
						$checada = verificarChecada($empleados[$cont]);
						//Agregar al arreglo de sentencias el registro del ID del Trabajador -> la Hora de Checada -> la Fecha de Checada -> Incidencia del Trabajador
						$sql_stm[] = $empleados[$cont]."->".$horas[$cont]."->".$fechas[$cont]."->$checada";
						//Indicar que el registro se va a guardar, cambiando el valor de guardar de 0 a 1
						$guardar = 1;
					}//Fin de if ($agregar){
				}//Fin del else{
			}//Fin del else
			
			
			
			//Verificar si se va a guardar el Registro, siempre y cuando "guardar" valga 1
			if ($guardar==1){
				//Obtener el tamaño del arreglo de sentencias
				$tam = count($sql_stm);
				//Acceder a la ultima posicion del Arreglo
				$tam = $tam - 1;
				//Ejecutar la funcion de verificar incidencia pasandole el ultimo registro agregado las horas de Entrada y salida quitarlas
				verificarIncidencia($sql_stm[$tam]);
			}//Cierre if ($guardar==1)
			
			
			//Incrementar el contador
			$cont++;
			
		}while($cont<count($empleados));//Ciclo mientras el contador sea menor al numero de registros
		
		
		//Redireccionar a la pagina de Error con el Error obtenido
		echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
		
		
	}//Fin de verificarInformacion($empleados,$fechas,horas)


	/*Funcion que verifica la ultima checada del Trabajador, registrada en la Base de Datos para determinar si el registro será una Entrada o una Salida*/
	function verificarChecada($empleado){
		//Conectar a la BD
		$conn=conecta("bd_recursos");
		//Obtener el ultimo Estado del Trabajador
		$checadaReg=mysql_fetch_array(mysql_query("SELECT estado FROM checadas WHERE empleados_id_empleados_empresa='$empleado' AND 
		hora_checada=(SELECT MAX(hora_checada)FROM checadas WHERE empleados_id_empleados_empresa='$empleado' AND 
		fecha_checada=(SELECT MAX(fecha_checada)FROM checadas WHERE empleados_id_empleados_empresa='$empleado'))"));
		//Verificar que se tenga un registro previo de Estado
		if ($checadaReg["estado"]!=NULL){
			//Si la ultima incidencia es SALIDA, el sistema contempla que la siguiente es una ENTRADA
			if ($checadaReg["estado"]=="SALIDA")
				$estado="E";//<-----Asignar el Estado E
			else
				$estado="S";//<-----Asignar el Estado S
		}
		//Si no se tiene un registro previo, el estado es una Entrada
		else
			$estado="E";
		//Cerrar la conexion con la BD
		mysql_close($conn);
		//Retornar el estado obtenido
		return $estado;
	}//Fin de function verificarChecada($empleado)
	
	
	/*Funcion que asigna una incidencia de acuerdo a la hora en la que se realizo la checada y guarda en la base de datos*/
	function verificarIncidencia($sql_stm){
		//Variable para guardar los errores generados en la insercion de datos
		$error = "";
		//Convertir a un arreglo dividido por '->' el registro enviado para guardar
		//donde las posiciones son:
		//0->Numero del Trabajador
		//1->Hora checada
		//2->Fecha checada
		//3->Estado => (E) ó (S)
		
		//Separar los datos recibidos
		$sql = split("->",$sql_stm);
		
		//Obtener el RFC del Empleado
		$rfc = obtenerDato("bd_recursos","empleados","rfc_empleado","id_empleados_empresa",$sql[0]);
		
		//Obtener del Rol del Trabajador
		$rol = obtenerDato("bd_recursos","roles","turnos_id_turno","empleados_rfc_empleado",$rfc);		
		if ($rol==""){
			$msg = "El Trabajador $sql[0] No Tiene Turno Asignado";
			//En caso que el Trabajador no tenga ROL asignado, se le asigna como Hora de Entrada la Hora de la Checada, de forma que siempre sera incidencia (A) = Asistencia
			$horaE = $sql[1];
		}
		else{
			//Obtener la Hora de Entrada segun el Rol asignado
			$horaE = obtenerDato("bd_recursos","turnos","hora_entrada","id_turno",$rol);
			//Obtener la Hora de Salida segun el Rol asignado
			$horaS = obtenerDato("bd_recursos","turnos","hora_salida","id_turno",$rol);
		}
		
		//Abrir la conexion con la BD
		$conn = conecta("bd_recursos");
		//Si el estado es igual a (E), es una entrada, verificar la incidencia que le corresponde
		if($sql[3]=="E"){
			//Obtener la diferencia de la hora checada contra la de Entrada segun el ROL
			$entradaReal = mysql_fetch_array(mysql_query("SELECT TIMEDIFF('$sql[1]','$horaE')"));
			
			//Si el primer digito es '-' indica que es una checada antes de la Hora de Entrada
			if (substr($entradaReal[0],0,1)=="-")
				//Entro antes de la Hora, toca (A) de Asistencia
				$inc = "A";
			//Si no es negativo, indica que es igual o mayor, en dicho caso verificar que tan mayor es el dato
			else{
				//Extraer la seccion de horas de la diferencia entre Checada y Hora de Entrada segun el Rol
				$hra = substr($entradaReal[0],0,2);
				//Extraer la seccion de mintos de la diferencia entre Checada y Hora de Entrada segun el Rol
				$min = substr($entradaReal[0],3,2);
				//Extraer la seccion de segundos de la diferencia entre Checada y Hora de Entrada segun el Rol
				$seg = substr($entradaReal[0],-2);
				
				//Si hra es igual a 0, la checada es dentro de la misma hora
				if ($hra==0){
					if ($min<=10)//Si la diferencia de minutos es de 0 a 10, ingresar (A) de Asistencia
						$inc = "A";
					if ($min>10)//Si la diferencia de minutos es de 10 a una hora, toca (r) de Retraso
						$inc="r";
				}
				
				//Si hra es mayor a 0, la checada es horas despues de la hora que corresponde
				if ($hra>0){
					//Entro HORAS despues, toca (F) de FALTA
					$inc="F";
				}
				
			}//Fin del else
			
			
			//Sentencia SQL para ingresar una Entrada con el incidente que corresponda
			$stm_sql = "INSERT INTO checadas VALUES ('$rfc','$sql[0]','$sql[2]','$sql[1]','$inc')";
			
		}//Fin de if($sql[3]=="E"){
		else{
			//Sentencia SQL para ingresar una SALIDA
			$stm_sql = "INSERT INTO checadas VALUES ('$rfc','$sql[0]','$sql[2]','$sql[1]','SALIDA')";
		}				
		
		//Ejecutar la Sentecia creada con la entrada o salida en las checadas de cada trabajador
		$rs=mysql_query($stm_sql);//Ejecutar la sentencia SQL que se haya generado
		
		//En caso de haberlo, guardar el Error en un archivo de Errores de Kardex
		if (!$rs){
			$error = mysql_error();
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
		
	}//Fin de function verificarIncidencia($sql_stm,$horaE,$horaS)
	
	
	//Funcion que verifica cuantos checadas tiene registradas el Empleado en un Fecha Dada, para solo permitir agregar dos por dia
	function verificarDatosBD($empleado,$fecha){
		//Conectar a la BD
		$conn = conecta("bd_recursos");
		//Sentencia SQL para verificar cuantos registros tiene el Trabajador en la Fecha actual
		$sql_stm = "SELECT estado FROM checadas WHERE empleados_id_empleados_empresa='$empleado' AND fecha_checada='$fecha'";
		//Ejecutar la sentencia SQL
		$rs = mysql_query($sql_stm);
		//Obtener el numero de registros en una variable
		$checadas = mysql_num_rows($rs);
		//Si ya hay 2 checadas del Trabajador en el dia, retornar FALSO para NO guardar otro Registro
		if($checadas==2)
			return false;
		else//Si el valor de checadas es diferente de 2, retornar VERDADER, de forma que se pueda Agregar otro Registro, teniendo en cuenta, solo 0 o 1, como resultado de $checadas
			return true;
	}//Fin de function verificarDatosBD($empleado,$fecha)


?>
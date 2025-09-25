<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 29/Septiembre/2010                                      			
	  * Descripción: Este sección se encarga de realizar la autenficación del    
	  * usuario que desea ingresar al Sistema, verificando el nombre de usuario
	  * y contraseña proporcionados por el usuario contra los registrados en la Base de Datos
	  **/

	/**
      * Listado del contenido del programa                                            
      *   Includes: modulo de conexion con la base de datos*/
			include("../includes/conexion.inc");
	/**   Código en: pages\autentificar.php                                   
      **/
	//Iniciar Session para poder crear la variable que almacena los intentos de entrada
	session_start();
	//Se comprueba que este definida la variable intento y que ademas sea mayor a 5,
	//siendo 3, indica que los datos de usuario se han escrito mal en 5 ocasiones
	if (isset($_SESSION["intento"]) && $_SESSION["intento"]>3){
		//Obtener la hora de bloqueo del servidor
		$hora_blo=date("Y-m-d H:i:s");
		//Obtener la hora de desbloqueo agregando a la anterior 15 minutos (En este caso solo 1 para prueba)
		$hora_des=date("Y-m-d H:i:s",strtotime("+15 minutes"));
		//Se conecta a la BD de usuarios
		$dbd = conecta("bd_usuarios");
		//Obtenemos la Direccion IP del equipo 
		$ip=$_SERVER['REMOTE_ADDR'];
		//Se crea la sentencia SQL
		$sql_stm="INSERT INTO bloqueados (direccion_ip,hora_bloqueo,hora_desbloqueo) VALUES('$ip','$hora_blo','$hora_des')";
		//Ejecutar la sentencia creada
		$rs=mysql_query($sql_stm);
		if (!isset($_GET["dg"]))
			//Direccionamos a la paina de Login mandando el parametro HIT que indica, 3 intentos de inicio de sesion
			header("Location: login.php?usr_sts=hit");
		else
			//Direccionamos a la paina de Login mandando el parametro HIT que indica, 3 intentos de inicio de sesion
			header("Location: loginGerencia.php?usr_sts=hit");
		//Cerramos la conexion recien abierta a la BD
		mysql_close($dbd);
		//Quitamos de la sesion las variables de usuario y de intentos
		unset ($_SESSION["intento"]);
	}
	else{
		//Obtenemos la Direccion IP del equipo 
		$ip=$_SERVER['REMOTE_ADDR'];
		//Hacer conexion con la Base de Datos 'bd_usuarios'
		$dbd = conecta("bd_usuarios");
		//Sentencia SQL para verificar que no este bloqueada la IP
		$sql_stm_ip = "SELECT direccion_ip FROM bloqueados WHERE direccion_ip='$ip'";
		//Ejecutar la sentencia SQL
		$rs_ip=mysql_query($sql_stm_ip);
		if (mysql_num_rows($rs_ip)==0){
			//Sentencia SQL para buscar un usuario con los datos proporcionados en 'login.php' y desencriptar la clave que tiene una encriptacion de 128 bits
			$sql_stm = "SELECT AES_DECRYPT(clave,128) AS clave,usuario,tipo_usuario,depto FROM usuarios WHERE usuario='$txt_usuario' AND activo='SI'"; 
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			$rs = mysql_query($sql_stm);
			//Cargar los datos de usuario en el arreglo $campos
			if($campos = mysql_fetch_array($rs)){		
				//Comprobar que el nombre de usuario sea válido
				if (mysql_num_rows($rs)!=0 && $campos['usuario']==$txt_usuario){			
					if($campos['clave']==$txt_clave){		      							
						//Comprobar que no haya otra SESSION iniciada en el mismo equipo
						if(session_is_registered('usr_reg')){
							//Comprobar el parametro dg en el GET con valor de y, de esta forma, nos damos cuenta de si es un intento desde Gerencia u otro Equipo
							if (!isset($_GET["dg"]))
								//Si existe otra SESSION del Sistema iniciada redireccionar a la pagina de login
								header("Location: login.php?usr_sts=ssinc");
							else
								//Si existe otra SESSION del Sistema iniciada redireccionar a la pagina de login
								header("Location: loginGerencia.php?usr_sts=ssinc");
						}
						else{								
							//Definir en la sesion la variable usr_reg y guardar el nombre del usuario registrado
							session_register('usr_reg'); 
							$usr_reg = $campos['usuario'];
							//Definir en la sesion lo variable tipo_usr y guardar tipo de usuario
							session_register('tipo_usr'); 
							$tipo_usr = $campos['tipo_usuario'];
							//Definir en la sesion la variable depto y guardar el depto del usuario
							session_register('depto'); 
							$depto = $campos['depto'];
							//Definir la fecha y hora de inicio de sesión en formato aaaa-mm-dd hh:mm:ss 
							$_SESSION['ultimoAcceso'] = date("Y-n-j H:i:s");
							switch($campos['tipo_usuario']){
								case "administrador":
									switch($campos['depto']){
										case "Almacen":
											header ("Location: alm/inicio_almacen.php");
										break;
										case "Compras":
											header ("Location: com/inicio_compras.php");
										break;
										case "MttoConcreto":
											header ("Location: mac/inicio_mantenimiento.php");
										break;
										case "MttoMina":
											header ("Location: mam/inicio_mantenimiento.php");
										break;
										case "RecursosHumanos":
											header ("Location: rec/inicio_recursos.php");
										break;
										case "Topografia":
											header ("Location: top/inicio_topografia.php");
										break;
										case "Laboratorio":
											header ("Location: lab/inicio_laboratorio.php");
										break;
										case "Produccion":
											header ("Location: pro/inicio_produccion.php");
										break;
										case "GerenciaTecnica":
											header ("Location: ger/inicio_gerencia.php");
										break;
										case "Desarrollo":
											header ("Location: des/inicio_desarrollo.php");
										break;
										case "Calidad":
											header ("Location: ase/inicio_aseguramiento.php");
										break;
										case "Seguridad":
											header ("Location: seg/inicio_seguridad.php");
										break;	
										case "SeguridadAmbiental":
											header ("Location: seg/inicio_seguridad.php");
										break;
										case "MttoElectrico":
											header ("Location: mae/inicio_mantenimientoE.php");
										break;
										case "Clinica":
											header ("Location: uso/inicio_clinica.php");
										break;
										case "Llantas":
											header ("Location: llan/inicio_llantas.php");
										break;
										case "Comaro":
											header ("Location: coma/inicio_comaro.php");
										break;
										case "Sistemas":
											header ("Location: sis/inicio_sistemas.php");
										break;
										case "SupervisionDes":
											header ("Location: sup_des/inicio_supdes.php");
										break;
									}    					 									
								break;
								case "auxiliar":
									switch($campos['depto']){
										case "Almacen":
											header ("Location: alm/inicio_almacen.php");
										break;
										case "Compras":
											header ("Location: com/inicio_compras.php");
										break;
										case "Mantenimiento"://Si se desea que un Usuario vea toda la Info de Mtto. colocar el la Tabla de Usuarios el Depto de "Mantenimiento"
											header ("Location: man/inicio_mantenimiento.php");
										break;
										case "Topografia":
											header ("Location: top/inicio_topografia.php");
										break;
										case "Laboratorio":
											header ("Location: lab/inicio_laboratorio.php");
										break;
										case "Produccion":
											header ("Location: pro/inicio_produccion.php");
										break;
										case "GerenciaTecnica":
											header ("Location: ger/inicio_gerencia.php");
										break;
										case "MttoConcreto"://Si el Auxiliar es para concreto, solo colocar el Depto de "MttoConcreto" en la Tabla de Usuarios de la BD
											header ("Location: mac/inicio_mantenimiento.php");										
										break;
										case "MttoMina"://Si el Auxiliar es para concreto, solo colocar el Depto de "MttoMina" en la Tabla de Usuarios de la BD
											header ("Location: mam/inicio_mantenimiento.php");
										break;
										case "RecursosHumanos":
											header ("Location: rec/inicio_recursos.php");
										break;
										case "Desarrollo":
											header ("Location: des/inicio_desarrollo.php");
										break;
										case "Calidad":
											header ("Location: ase/inicio_aseguramiento.php");
										break;
										case "Seguridad":
											header ("Location: seg/inicio_seguridad.php");
										break;
										case "MttoElectrico":
											header ("Location: mae/inicio_mantenimientoE.php");
										break;
										case "Clinica":
											header ("Location: uso/inicio_clinica.php");
										break;
										case "Comaro":
											header ("Location: coma/inicio_comaro.php");
										break;
										case "Sistemas":
											header ("Location: sis/inicio_sistemas.php");
										break;
										case "SupervisionDes":
											header ("Location: sup_des/inicio_supdes.php");
										break;
									}										
								break;
								case "Panel":
									header ("Location: cpanel/cpanel.php");
								break;
								case "Gerencia":
									header ("Location: dir/inicio_direccion.php");
								break;
								case "externo":
									header ("Location: pai/inicio_paileria.php");
								break;
							}								
						}//Cierre Else comprobacion de la SESSION iniciada				 
					}
					else{
						//Comprobar el parametro dg en el GET con valor de y, de esta forma, nos damos cuenta de si es un intento desde Gerencia u otro Equipo
							if (!isset($_GET["dg"]))
								//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
								header("Location: login.php?usr_sts=err");
							else
								//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
								header("Location: loginGerencia.php?usr_sts=err");
						//Incrementar en uno por cada inicio fallido
						if(isset($_SESSION["intento"]))
							$_SESSION["intento"] += 1;//Solo se incrementa en los intentos fallidos
						else
							$_SESSION["intento"] = 1;//Solo se incrementa en los intentos fallidos
					}
				}
				else{
					//Comprobar el parametro dg en el GET con valor de y, de esta forma, nos damos cuenta de si es un intento desde Gerencia u otro Equipo
					if (!isset($_GET["dg"]))
						//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
						header("Location: login.php?usr_sts=err");
					else
						//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
						header("Location: loginGerencia.php?usr_sts=err");
					//Incrementar en uno por cada inicio fallido
					if(isset($_SESSION["intento"]))
						$_SESSION["intento"] += 1;//Solo se incrementa en los intentos fallidos
					else
						$_SESSION["intento"] = 1;//Solo se incrementa en los intentos fallidos
				} 
			}
			else{
				//Incrementar en uno por cada inicio fallido
				if(isset($_SESSION["intento"]))
					$_SESSION["intento"] += 1;//Solo se incrementa en los intentos fallidos
				else
					$_SESSION["intento"] = 1;//Solo se incrementa en los intentos fallidos
				//Comprobar el parametro dg en el GET con valor de y, de esta forma, nos damos cuenta de si es un intento desde Gerencia u otro Equipo
				if (!isset($_GET["dg"]))
					//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
					header("Location: login.php?usr_sts=err");
				else
					//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
					header("Location: loginGerencia.php?usr_sts=err");
			}
			//Cerrar la Conexion con la Base de Datos 'bd_usuarios'
			mysql_close($dbd);
		}//ELSE Agregado en caso de estar bloqueado
		else{
			//Crear sentencia para obtener la hora de desbloqueo de la IP
			$stm_sql="SELECT hora_desbloqueo FROM bloqueados WHERE direccion_ip='$ip'";
			//Ejecutar la sentencia
			$rs=mysql_query($stm_sql);
			//Guardar el arreglo de datos que genera la consulta
			$campo_horaDes = mysql_fetch_array($rs);
			//Comprobar Si la hora actual es mayor o igual a la hora de desbloqueo
			if (date("Y-m-d H:i:s")>=$campo_horaDes['hora_desbloqueo']){
				//Borrar el bloqueo de la BD
				$stm_sql="DELETE FROM bloqueados WHERE direccion_ip='$ip'";
				//Ejecutar la sentencia de desbloqueo
				$rs=mysql_query($stm_sql);
				//Redireccionar a la pagina de Login
				header("Location: login.php?usr_sts");
				/*****************ESTE BLOQUE ES UNA TOTAL COPIA DE LA SECCION DE VALIDACION PARA REPERMITIR EL ACCESO AL USUARIO, DADO EL TIEMPO LÍMITE***************/
				//Sentencia SQL para buscar un usuario con los datos proporcionados en 'login.php'
			$sql_stm = "SELECT * FROM usuarios WHERE usuario='$txt_usuario'"; 
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			$rs = mysql_query($sql_stm);
			//Cargar los datos de usuario en el arreglo $campos
			if($campos = mysql_fetch_array($rs)){		
				//Comprobar que el nombre de usuario sea válido
				if (mysql_num_rows($rs)!=0 && $campos['usuario']==$txt_usuario){			
					//Comprobar la contraseña del usuario
					if($campos['clave']==$txt_clave){		      							
						//Comprobar que no haya otra SESSION iniciada en el mismo equipo
						if(session_is_registered('usr_reg')){
							//Comprobar el parametro dg en el GET con valor de y, de esta forma, nos damos cuenta de si es un intento desde Gerencia u otro Equipo
							if (!isset($_GET["dg"]))
								//Si existe otra SESSION del Sistema iniciada redireccionar a la pagina de login
								header("Location: login.php?usr_sts=ssinc");
							else
								//Si existe otra SESSION del Sistema iniciada redireccionar a la pagina de login
								header("Location: loginGerencia.php?usr_sts=ssinc");
						}
						else{								
							//Definir en la sesion la variable usr_reg y guardar el nombre del usuario registrado
							session_register('usr_reg'); 
							$usr_reg = $campos['usuario'];
							//Definir en la sesion la variable tipo_usr y guardar tipo de usuario
							session_register('tipo_usr'); 
							$tipo_usr = $campos['tipo_usuario'];
							//Definir en la sesion la variable depto y guardar el depto del usuario
							session_register('depto'); 
							$depto = $campos['depto'];
							
							//Definir la fecha y hora de inicio de sesión en formato aaaa-mm-dd hh:mm:ss 
							$_SESSION['ultimoAcceso'] = date("Y-n-j H:i:s");
							switch($campos['tipo_usuario']){
								case "administrador":
									switch($campos['depto']){
										case "Almacen":
											header ("Location: alm/inicio_almacen.php");
										break;
										case "Compras":
											header ("Location: com/inicio_compras.php");
										break;
										case "MttoConcreto":
											header ("Location: mac/inicio_mantenimiento.php");
										break;
										case "MttoMina":
											header ("Location: mam/inicio_mantenimiento.php");
										break;
										case "RecursosHumanos":
											header ("Location: rec/inicio_recursos.php");
										break;
										case "Topografia":
											header ("Location: top/inicio_topografia.php");
										break;
										case "Laboratorio":
											header ("Location: lab/inicio_laboratorio.php");
										break;
										case "Produccion":
											header ("Location: pro/inicio_produccion.php");
										break;
										case "GerenciaTecnica":
											header ("Location: ger/inicio_gerencia.php");
										break;
										case "Desarrollo":
											header ("Location: des/inicio_desarrollo.php");
										break;
										case "Seguridad":
											header ("Location: seg/inicio_seguridad.php");
										break;
										case "SeguridadAmbiental":
											header ("Location: seg/inicio_seguridad.php");
										break;
										case "MttoElectrico":
											header ("Location: mae/inicio_mantenimientoE.php");
										break;
										case "Clinica":
											header ("Location: uso/inicio_clinica.php");
										break;
										case "Llantas":
											header ("Location: llan/inicio_llantas.php");
										break;
										case "SupervisionDes":
											header ("Location: sup_des/inicio_supdes.php");
										break;
									}    					 									
								break;
								case "auxiliar":
									switch($campos['depto']){
										case "Almacen":
											header ("Location: alm/inicio_almacen.php");
										break;
										case "Compras":
											header ("Location: com/inicio_compras.php");
										break;
										case "Mantenimiento"://Si se desea que un Usuario vea toda la Info de Mtto. colocar el la Tabla de Usuarios el Depto de "Mantenimiento"
											header ("Location: man/inicio_mantenimiento.php");
										break;
										case "Topografia":
											header ("Location: top/inicio_topografia.php");
										break;
										case "Laboratorio":
											header ("Location: lab/inicio_laboratorio.php");
										break;
										case "Produccion":
											header ("Location: pro/inicio_produccion.php");
										break;
										case "GerenciaTecnica":
											header ("Location: ger/inicio_gerencia.php");
										break;
										case "MttoConcreto"://Si el Auxiliar es para concreto, solo colocar el Depto de "MttoConcreto" en la Tabla de Usuarios de la BD
											header ("Location: mac/inicio_mantenimiento.php");										
										break;
										case "MttoMina"://Si el Auxiliar es para concreto, solo colocar el Depto de "MttoMina" en la Tabla de Usuarios de la BD
											header ("Location: mam/inicio_mantenimiento.php");
										break;
										case "RecursosHumanos":
											header ("Location: rec/inicio_recursos.php");
										break;
										case "Desarrollo":
											header ("Location: des/inicio_desarrollo.php");
										break;
										case "Seguridad":
											header ("Location: seg/inicio_seguridad.php");
										break;
										case "MttoElectrico":
											header ("Location: mae/inicio_mantenimientoE.php");
										break;
										case "Clinica":
											header ("Location: uso/inicio_clinica.php");
										break;
										case "SupervisionDes":
											header ("Location: sup_des/inicio_supdes.php");
										break;
									}										
								break;
								case "Panel":
									header ("Location: cpanel/cpanel.php");
								break;
								case "Gerencia":
									header ("Location: dir/inicio_direccion.php");
								break;
								case "externo":
									header ("Location: pai/inicio_paileria.php");
								break;
							}								
						}//Cierre Else comprobacion de la SESSION iniciada				 
					}
					else{
						if (!isset($_GET["dg"]))
							//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
							header("Location: login.php?usr_sts=err");
						else
							//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
							header("Location: loginGerencia.php?usr_sts=err");
						//Incrementar en uno por cada inicio fallido
						if(isset($_SESSION["intento"]))
							$_SESSION["intento"] += 1;//Solo se incrementa en los intentos fallidos
						else
							$_SESSION["intento"] = 1;//Solo se incrementa en los intentos fallidos
					}	
				}
				else{
					if (!isset($_GET["dg"]))
						//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
						header("Location: login.php?usr_sts=err");
					else
						//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
						header("Location: loginGerencia.php?usr_sts=err");
					//Incrementar en uno por cada inicio fallido
					if(isset($_SESSION["intento"]))
						$_SESSION["intento"] += 1;//Solo se incrementa en los intentos fallidos
					else
						$_SESSION["intento"] = 1;//Solo se incrementa en los intentos fallidos
				} 
			}
			else{
				//Incrementar en uno por cada inicio fallido
				if(isset($_SESSION["intento"]))
					$_SESSION["intento"] += 1;//Solo se incrementa en los intentos fallidos
				else
					$_SESSION["intento"] = 1;//Solo se incrementa en los intentos fallidos
				if (!isset($_GET["dg"]))
					//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
					header("Location: login.php?usr_sts=err");
				else
					//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
					header("Location: loginGerencia.php?usr_sts=err");
			}
			//Cerrar la Conexion con la Base de Datos 'bd_usuarios'
			mysql_close($dbd);
				///////////////////////////////////////////////FIN DEL BLOQUE COPIA///////////////////////
			}
			else{
				if (!isset($_GET["dg"]))
					//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
					header("Location: login.php?usr_sts=hit");
				else
					//Si la clave no coincide con la proporcionada por el usuario se redirecciona a la pagina de 'login.php'
					header("Location: loginGerencia.php?usr_sts=hit");
			}
		}
	}
?>
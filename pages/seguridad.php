<?php
	/**
	  * Nombre del M�dulo: Almac�n                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 29/Septiembre/2010                                      			
	  * Descripci�n: Este secci�n se encarga de verioficar que el usuario este autentificado antes de ingresar a cualquier pagina del sistema,    
	  * tambien revisa que el tiempo que lleva la sesion sin utilizarse no exceda del establecido, de lo contrario cerrar� la sesion actual y
	  * reenviar� a la pagina de login para que el usuario vuelva a autentificarse.
	  **/
	
	//Iniciar la sesi�n
	session_start();
	/*
	ESTAS LINEAS QUEDAN COMENTADAS PARA QUITAR EL SISTEMA DE SEGURIDAD EN BASE A PETICIONES AL SERVIDOR,
	EL SISTEMA DE SEGURIDAD QUEDA SOLAMENTE EN BASE A JAVASCRIPT EN EL ARCHIVO funcionesJS.js
	NO BORRAR PARA PREVER SOLUCIONES FUTURAS. GRACIAS ^^
	//Verificar que el usuario haya iniciado sesion
	if(session_is_registered('usr_reg')){
		//Calcular el tiempo transcurrido 
		$fechaGuardada = $_SESSION['ultimoAcceso']; 
		$ahora = date("Y-n-j H:i:s"); 
		$tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 

		//Comparamos el tiempo transcurrido en segundos
		if($tiempo_transcurrido >= 3600) { 			
			include("man/op_agregarEquipo.php");
			//Esta comprobacion es para verificar si se agregaron documentos a los Equipos de Mantenimiento y por alguna razon no se termino el proceso de Agregado, 
			//entonces eliminamos los que se hayan cargado para evitar inconsitencias de informacion
			if (isset($_SESSION["docTemporal"]))
				borrarArchivosExtremo();						
			
			//Si pasaron 60 minutos o m�s destruir la sesi�n 
			session_destroy(); 
			//Enviar al usuario a la pag. de autenticaci�n, tme = tiempo exedido
			header("Location: ../login.php?usr_sts=tme"); 			
		}
		else{
			//Actualizar la fecha de la sesi�n  
			$_SESSION["ultimoAcceso"] = $ahora; 
		}
	}
	else{*/
	if(!session_is_registered('usr_reg'))
		//Si no esta registrado, envio a la p�gina de autentificacion, unr = Usuario No Registrado
		header("Location: ../login.php?usr_sts=unr");
//	}

	//Esta funcion contiene funciones para verificar los permisos de los usuarios que se encuentren registrados en la BD.
	function verificarPermiso($usr_reg,$pagina){
		//Dividir la pagina en un arreglo dividio por las diagonales
		$paginaArr=split("/",$pagina);
		//Obtener el tama�o del arreglo
		$tam=count($paginaArr);
		//Obtener el nombre de la pagina haciendo referencia a su ultima posicion
		$pagina=$paginaArr[$tam-1];
		//Conectar a la BD de Usuarios
		$conn = conectar("bd_usuarios");
		
		//Crear la sentencia para comparar si el usuario autentificado se encuentra dentro del registro de la BD
		$stm_sql = "SELECT estatus FROM permisos WHERE usuarios_usuario='$usr_reg' AND seccion='$pagina'";				
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql,$conn);					
		//Verificar los resultados obtenidos
		if($datos=mysql_fetch_array($rs)){
			if($datos['estatus']==1)
				return true;
			else
				return false;
		}		
		//Cerrar la conexion con la BD		
		mysql_close($conn);				
	}
	
	//Esta funcion se encarga de verificar los Permisos de Usuario segun el Departamento recibido
	//para poder direccionar a un usuario al inicio o al cierre de sesion segun corresponda
	function verificarUsuarioPermisos($depto){
		//Conectar ala BD de usuarios
		$conn=conectar("bd_usuarios");
		//Sentencia SQL para extraer a los usuarios del departamento seleccionado
		$sql_stm="SELECT usuario FROM usuarios WHERE depto='$depto'";
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			$usuarios=array();
			do{
				$usuarios[]=$datos["usuario"];
			}while($datos=mysql_fetch_array($rs));
			mysql_close($conn);
			return $usuarios;
		}
	}//Fin de function verificarUsuarioPermisos($depto)
	
	//Esta funcion se encarga de Conectar a la Base de datos especificada en el parametro
	function conectar($bd){
		$dbd=mysql_connect("localhost:3308", "admin_sisad_clf", "SistemasCLF.2024");
		if (!$dbd){
			die ("<h3>*** ERROR al conectar... :(");
		}

		if (!mysql_select_db($bd, $dbd)){
			die("<h3>ERROR: ".mysql_error()."</h3>");
		}			
		return $dbd;	
	}
?>
	<!--<head>
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="sample.css" />
		<script type="text/javascript" src="alm/includes/popup-window.js"></script>
	</head>
	<body>
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popup', 'popup_drag', 'popup_exit', 'screen-center', 0, 0);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<!--<div class="sample_popup" id="popup" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag">
				<img class="menu_form_exit" id="popup_exit" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
				AVISO DE SISTEMAS
			</div>
	
			<div class="menu_form_body_red">
				<table>
					<tr>
						<td align="center">
							<font size="5px">Favor de realizar sus pendientes, a las 8:30 am se apagara el SISAD.</font>
						</td>						
					</tr>
					<tr>
						<td align="center">
							<font size="5px">El lapso de tiempo sera de 20 min aproximadamente.</font>
						</td>						
					</tr>
				</table>
			</div>
		</div>
	</body>-->
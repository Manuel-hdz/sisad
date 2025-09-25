<?php
	/**
	  * Nombre del M�dulo: Seguridad en Panel de Control                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 07/Septiembre/2011
	  * Descripci�n: Este secci�n se encarga de verificar que el usuario este autentificado antes de ingresar a cualquier pagina del Panel de Control,
	  * tambien revisa que el tiempo que lleva la sesion sin utilizarse no exceda del establecido, de lo contrario cerrar� la sesion actual y
	  * reenviar� a la pagina de login para que el usuario vuelva a autentificarse.
	  **/
	
	//Iniciar la sesi�n
	session_start();

	//Verificar que el usuario haya iniciado sesion
	if(session_is_registered('usr_reg')){
		//Calcular el tiempo transcurrido 
		$fechaGuardada = $_SESSION['ultimoAcceso']; 
		$ahora = date("Y-n-j H:i:s"); 
		$tiempo_transcurrido = (strtotime($ahora)-strtotime($fechaGuardada)); 

		//Comparamos el tiempo transcurrido en segundos
		if($tiempo_transcurrido >= 3600) {
			//Si pasaron 60 minutos o m�s destruir la sesi�n 
			session_destroy(); 
			//Enviar al usuario a la pag. de autenticaci�n, tme = tiempo exedido
			?>
			<form name="frm_dumb" action="../login.php?usr_sts=tme" target="_parent" method="post">
				<input type="hidden" value="hdn_dumb"/>
			</form>
			<script type="text/javascript" language="javascript">
				document.frm_dumb.submit();
			</script>
			<?php
		}
		else{
			//Actualizar la fecha de la sesi�n  
			$_SESSION["ultimoAcceso"] = $ahora; 
		}
	}
	else{
		//Si no esta registrado, envio a la p�gina de autentificacion, unr = Usuario No Registrado
		//header("Location: ../login.php?usr_sts=unr");
		?>
		<form name="frm_dumb" action="../login.php?usr_sts=tme" target="_parent" method="post">
			<input type="hidden" value="hdn_dumb"/>
		</form>
		<script type="text/javascript" language="javascript">
			document.frm_dumb.submit();
		</script>
		<?php
	}

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
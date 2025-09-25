<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 01/Marzo/2012
	  * Descripción: Este archivo contiene las funciones para realizar la Gestion de Obras de Desarrollo
	  **/ 
	  
	  
	//Genera la Id de la Obra que va a ser registrada
	function obtenerIdObra(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");								
		
		//Crear la sentencia para obtener el numero Obras registtradas
		$stm_sql = "SELECT MAX(id_ubicacion) AS clave FROM catalogo_ubicaciones";
		//Ejecutar Sentencia
		$rs = mysql_query($stm_sql);								
		//Evaluar Resultados y Generar Id a partir de ellos
		$idObra = 0;
		if($datos=mysql_fetch_array($rs)){
			$idObra = intval($datos['clave']) + 1;
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);		
		
		return $idObra;
	}//Fin de la Funcion obtenerIdBitAvance()
	
	
	
	//Esta funcion guardará los datos de la bitácora de rezagado
	function guardarObra(){
		
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST
		$idObra = $_POST['txt_idObra'];
		$cliente = $_POST['cmb_idCliente'];				
		$area = $_POST['cmb_area'];
		$bloque = $_POST['cmb_bloque'];		
		$obra = strtoupper($_POST['txt_nomObra']);

		
		//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
		$sql_stm = "INSERT INTO catalogo_ubicaciones(id_ubicacion,catalogo_clientes_id_cliente,area,bloque,obra) 
					VALUES($idObra,$cliente,'$area','$bloque','$obra')";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		if($rs){			
			//Iniciar la SESSION para tener acceso a los datos registrados del usuario
			session_start();
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idObra","RegistrarObra",$_SESSION['usr_reg']);
			
			//Mostrar al usuario el mensaje de operacion realizada con éxito en el DIV definido en la pagina verRegistrarObra.php
			echo "<img src='../../images/ok.png' width='376' height='369' />";
			
			//Ocultar El DIV que muestra las Imagenes de Éxito o Error?>
			<script type="text/javascript" language="javascript">
				setTimeout("document.getElementById('resultado-opr').style.visibility='hidden';",3000);
			</script><?php
		}
		else{
			$error = mysql_error();
			echo "
				<img src='../../images/error.png' width='376' height='369' />
				<br>
				<span class='msje_correcto'>***ERROR: No Pudo Registrar La Informaci&oacute;n en la Base de Datos => $error</span>";
				
			//Ocultar El DIV que muestra las Imagenes de Éxito o Error?>
			<script type="text/javascript" language="javascript">
				setTimeout("document.getElementById('resultado-opr').style.visibility='hidden';",3000);
			</script><?php
			
			//Cerrar la conexicion con la BD
			mysql_close();
		}												
	}//Cierre de la funcion guardarBitAvance()
	
	
	
	//Esta funcion guardará los cambios en los datos de la bitácora de rezagado
	function modificarObra(){
		
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST
		$idObra = $_POST['txt_idObra'];
		$cliente = $_POST['cmb_idCliente'];				
		$area = $_POST['cmb_area'];
		$bloque = $_POST['cmb_bloque'];		
		$obra = strtoupper($_POST['txt_nomObra']);

		
		//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
		$sql_stm = "UPDATE catalogo_ubicaciones SET catalogo_clientes_id_cliente=$cliente, area='$area', bloque='$bloque', obra='$obra'
					WHERE id_ubicacion = $idObra";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);
		
		if($rs){			
			//Iniciar la SESSION para tener acceso a los datos registrados del usuario
			session_start();
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idObra","ModificarObra",$_SESSION['usr_reg']);
			
			//Mostrar al usuario el mensaje de operacion realizada con éxito en el DIV definido en la pagina verRegistrarObra.php
			echo "<img src='../../images/ok.png' width='376' height='369' />";
			
			//Cerrar la ventana tras 3 segundos ?>
			<script type="text/javascript" language="javascript">
				setTimeout("window.close();",3000);
			</script><?php
		}
		else{
			$error = mysql_error();
			echo "
				<img src='../../images/error.png' width='376' height='369' />
				<br>
				<span class='msje_correcto'>***ERROR: No Pudo Registrar La Informaci&oacute;n en la Base de Datos => $error</span>";
			//Cerrar la conexicion con la BD
			mysql_close();
			
			//Cerrar la ventana tras 3 segundos ?>
			<script type="text/javascript" language="javascript">
				setTimeout("window.close();",3000);
			</script><?php
		}												
	}//Cierre de la funcion modificarObra()

?>
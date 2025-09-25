<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 21/Mayo/2012
	  * Descripción: Este archivo contiene funciones para guardar, modificar y eliminar bonos
	**/
	

	//Funcion para guardar los bonos
	function guardarBono(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_recursos");
		
		//Recuperar Datos del POST
		$nomBono = $_POST['hdn_nomBonoNvo'];
		$descripcion = strtoupper($_POST['txa_descripcion']);
		//Retirar la coma de la cantidad en caso que exista, para alamcenar el dato en la BD
		$cantidad = str_replace(",","",$_POST['txt_cantidadBono']);					
		$fecha = modfecha($_POST['txt_fecha'],3);
		$autorizo = strtoupper($_POST['txt_autorizo']);
			
		//Crear la Sentencia SQL para Alamcenar los bonos agregados 
		$stm_sql = "INSERT INTO bonos(nom_bono, descripcion, cantidad, autorizo, fecha_bono) VALUES('$nomBono', '$descripcion', $cantidad, '$autorizo', '$fecha')";
		
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		
		//Verificar Resultado
		if($rs){
			registrarOperacion("bd_recursos",$nomBono,"AgregarBono",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";			
		}
		else{				
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";						
			//Cerrar la Conexion con la BD
			mysql_close($conn);
		}			
					
	}//Cierre de la funcion guardarBonos()
	
	
	//Funcion que se encarga guardar los bonos seleccionados y modificados
	function modificarBono(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_recursos");
		
		//Recuperar Datos del POST
		$idBono = $_POST['cmb_bono'];
		$nomBono = $_POST['hdn_nomBonoNvo'];
		$descripcion = strtoupper($_POST['txa_descripcion']);
		//Retirar la coma de la cantidad en caso que exista, para alamcenar el dato en la BD
		$cantidad = str_replace(",","",$_POST['txt_cantidadBono']);					
		$fecha = modfecha($_POST['txt_fecha'],3);
		$autorizo = strtoupper($_POST['txt_autorizo']);
			
		//Crear la Sentencia SQL para Alamcenar los bonos agregados 
		$stm_sql = "UPDATE bonos SET nom_bono='$nomBono', descripcion='$descripcion', cantidad = $cantidad, autorizo='$autorizo', fecha_bono = '$fecha'	WHERE id = $idBono";
		
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		
		//Verificar Resultado
		if($rs){
			registrarOperacion("bd_recursos",$nomBono,"ModificarBono",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{				
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";						
			//Cerrar la Conexion con la BD
			mysql_close($conn);
		}										
		 
	}//Cierre de la función modificarBonos()
	
	
	
	//Funcion que se encarga de eliminar el bono seleccionado del empleado
	function eliminarBonoSeleccionado(){
		
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		
		//Recuperar Datos del POST
		$idBono = $_POST['cmb_bono'];
		$nomBono = $_POST['hdn_nomBonoNvo'];
		
		//Creamos la sentencia SQL para borrar el bono seleccionado
		$stm_sql = "DELETE FROM bonos WHERE id = $idBono";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);									
									
		//Verificar si la sentencia ejecutada se genero con exito
		if ($rs){
			//Guardar el registro de movimientos
			registrarOperacion("bd_recursos",$nomBono,"EliminarBono",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			
			//Cerrar la conexion con la BD de Recursos Humanos
			mysql_close($conn);
		}
	}//Cierre de la función eliminarBonoSeleccionado()
	
?>
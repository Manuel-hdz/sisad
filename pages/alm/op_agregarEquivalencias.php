<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 30/Septiembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de AgregarMaterial en la BD
	  **/
	 
	 
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaiones generales en la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");	
	/**   Código en: pages\alm\op_agregarMaterial.php                                   
      **/
		
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");	
		
		//Convertir los caracteres de los campos de texto en Mayúsculas
		$txt_claveEquiv = strtoupper($txt_claveEquiv); $txt_nombre = strtoupper($txt_nombre); $cmb_proveedor=strtoupper($cmb_proveedor);		
						
		//Crear la sentencia para realizar el registro del nuevo material en la BD 
		$stm_sql = "INSERT INTO equivalencias(materiales_id_material,clave_equivalente,nombre,proveedor)
		VALUES('$cmb_material','$txt_claveEquiv','$txt_nombre','$cmb_proveedor')";					
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
										
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			//Iniciar la SESSION
			session_start();
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_almacen",$txt_claveEquiv,"AgregarEquivalencia",$_SESSION['usr_reg']);
			header("Location: exito.php");								
		}
		else{			
			$error = mysql_error();			
			header("Location: error.php?err=$error");
		}
		
		//Cerrar la conexion con la BD		
		//La conexion a la BD se cierra en la funcion registrarOperacion("bd_almacen",$txt_claveEquiv,"equivagregar",$_SESSION['usr_reg']);

?>
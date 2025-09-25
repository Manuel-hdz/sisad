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
	/**   Código en: pages\alm\includes\validarDatoBD.php                                   
      **/	
	  	
	//Usuario
	$user="Administrador";
	//Password
	$password="libre";
	//Ubicacion de la Base de Datos
	$mdbFilename=realpath("..\..\handpunch\IBIXConnect.mdb");
	//Conexion a la Base de Datos
	$db_connstr="Driver={Microsoft Access Driver (*.mdb)}; Dbq=$mdbFilename"; 
	$conn=odbc_connect($db_connstr, $user, $password);
	//Preparar la sentencia Access para Ingresar al Trabajador
	$stm_acc="DELETE FROM tblChecada;";
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");
	//Ejecutar la sentencia
	$rs=odbc_exec($conn,$stm_acc);
	if($datos=odbc_fetch_array($rs))
		echo "<existe>true</existe>";
	else
		echo "<existe>false</existe>";
	//Cerrar la conexion con la BD
	odbc_close($conn);
?>

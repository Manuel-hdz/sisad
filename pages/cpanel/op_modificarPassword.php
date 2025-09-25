<?php
	/**
	  * Nombre del Módulo: Panel de Control
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 16/Agosto/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de BorrarUsuarios del Sistema
	**/
	function modificarPassword($pass){
		//Incluir el archivo de conexion
		include_once("../../includes/conexion.inc");
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_usuarios");
		//Crear la sentencia para mostrar el catalogo de Materiales
		$stm_sql = "UPDATE usuarios SET clave=AES_ENCRYPT('$pass',128) WHERE usuario='CPanel'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		if ($rs)
			return 1;
		else
			return mysql_error();
	}

?>
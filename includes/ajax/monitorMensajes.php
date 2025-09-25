<?php
	/**
	  * Nombre del Módulo: Chat                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 01/Octubre/2012
	  * Descripción: Este archivo se encarga de monitorear los cambios en el archivo de Chat
	  **/
	  
	include("../func_fechas.php");
	//Ruta al archivo de LOG
	$nombre_archivo = '../../includes/chat/log.html';
	//Iniciar la Session
	session_start();
	//Verificar que el archivo exita
	if (file_exists($nombre_archivo)) {
		ini_set("date.timezone","America/Mexico_City");
		//Obtener la hora de la ultima actualizacion del fichero en formato hora legible 12:00:00 AM
		$horaUltimaAct=date ("g:i:s A", filemtime($nombre_archivo));
		//Si el usuario tiene en la sesion registrada la ultima hora de los mensajes enviados, continuar
		if(isset($_SESSION["ultimoMsjeChat"])){
			//Si la hora de la ultima modificacion es igual a la registrada en sesion, no actualizar el estado del "icono"
			if($horaUltimaAct==$_SESSION["ultimoMsjeChat"])
				$band="false";
			//Si la hora es diferente, otro usuario actualizo el fichero, mostrar la alerta
			else
				$band="true";
		}
		else
			$band="false";
	}
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");
	//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
	echo utf8_encode("<valor>$band</valor>");
?>

<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial
	  * Nombre Programador: Daisy Adriana Martínez Fernández	
	  * Fecha: 17/Febrero/2012
	  * Descripción: Este archivo contiene funciones para Ver los documentos registrados
	  **/ 
	//Verificamos si viene el id del documento que  en el get; de ser asi llamar la funcion mostrarDescarga()
	if(isset($_GET['id'])){
		mostrarDescarga();
	}
				
	//Función que permite descargar el archivo seleccionado
	function mostrarDescarga(){	
		//Incluimos arrchivo de conexion
		include ("../../includes/conexion.inc");
		//Incluimos el archivo para modificar las fechas para la consulta
		include ("../../includes/func_fechas.php");
		
		//Recuperar el ID del empleado
		$id=basename($_GET["ruta"]);
		//Recuperamos los datos del GET y los modificamos para ser utilizados
		$nomArchivo=$_GET["nomArchivo"];
	
		$path="../seg/documentos/".$id."/".$nomArchivo;
		$type="";
		//Comprobamos si el nombre del archivo es un nombre normal por Ej. ejemplo.txt
		if (is_file($path)){
			//Obtenemos el tamaño del archivo con la funcion filezise
			$size = filesize($path);
			//Comprobamos sie existe el archivo o fichero con function_exists
			if(function_exists($path)){
				//Obtenemos la informacion del ficheron con fifo_open
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				//Obtenemos el tipo de archivo
				$type = finfo_file($finfo, $path);
				//Cerramos la funcion
				finfo_close($info);  
			}
			//Si el tipo de archivo aparece como vacio forzamos la descarga
			if($type == ''){
				$type = "application/force-download";
			}
			//Situamos los Headers
			header("Content-Type: $type");
			header("Content-Disposition: attachment; filename=\"$nomArchivo\"");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: " . $size);
			//Descargamos el archivo con los parametros 0777 
			readfile($path,0777);
	}
	//De lo contrario enviamos mensaje al usuario idicando que no existe el archivo deseado
	else{
		//Titulo de la etiqueta?>
		<p align='center' class='titulo_etiqueta'><b>Imagen <br /><?php echo $nomArchivo;?></b></p><?php 
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		echo "<p align='center' class='titulo_etiqueta'><b>No hay registro del Documento <br /></p>";?>
		<br /><br /><?php 
	}
}
?>

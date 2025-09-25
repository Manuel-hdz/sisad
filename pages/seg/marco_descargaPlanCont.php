<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial
	  * Nombre Programador: Daisy Adriana Martínez Fernández- Nadia Madahí López Hdz	
	  * Fecha: 28/Abril/2012
	  * Descripción: Este archivo contiene func-Niones para Ver los documentos registrados o vinculados al plan de contingencia
	  **/ 
	//Verificamos si viene el id del documento que  en el get; de ser asi llamar la funcion mostrarDescarga()
	if(isset($_GET['id_documento'])){
		mostrarDescarga();
	}
				
	//Función que permite descargar el archivo seleccionado
	function mostrarDescarga(){	
		//Incluimos arrchivo de conexion
		include ("../../includes/conexion.inc");
		$conn = conecta("bd_seguridad");
		
		//Recuperar el ID del docuemnto
		$id_documento=$_GET["id_documento"];

		$sql_stm = "SELECT * FROM repositorio_documentos WHERE id_documento = '$id_documento'";
		
		$rs = mysql_query($sql_stm);
		
		$datos = mysql_fetch_array($rs);
		
		//Recuperamos los datos del GET y los modificamos para ser utilizados
		$nomArchivo=$datos["nom_archivo"];
		$ruta=$datos["ruta"];
		$nombre=$datos["nom_archivo"];		
		$tipo=$datos["tipo_archivo"];
		$path=$ruta."/".$nomArchivo;
		$path = realpath($path);
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
		<p align='center' class='titulo_etiqueta'><b>Documento <br /><?php echo $id_documento." ".$nombre;?></b></p><?php 
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		echo "<p align='center' class='titulo_etiqueta'><b>No hay registro del Documento <br /></p>";?>
		<br /><br /><?php 
	}
}
?>

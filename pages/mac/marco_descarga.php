<?php

//Si estan definidos los parametros necesarios en el GET, ejecutar la funcion descargaArchivo
if (isset($_GET["archivo"]) && isset($_GET["nom"]))
	descargaArchivo();
	
//Funcion que lee el archivo almacenado en el Servidor y forza su descarga
function descargaArchivo(){
	// Recuperar la ruta del Archivo
	$file=$_GET["archivo"];
	//Obtener el nombre del Archivo
	$nom_file=$_GET["nom"];

	if(!$file){
		// Si el archivo no existe, mostrar el error
		die('Archivo No Encontrado');
	}
	else{
		// Establecer Cabeceras
		header("Content-Disposition: attachment; filename=$nom_file");
		//Comprobamos si tipo viene definido en el GET, si esta definido, el archivo es un PDF y se usa esta cabecera, de lo contrario empleamos la otra
		if (isset($_GET["tipo"])){
			header("Cache-Control: public");
			header("Content-Type: application/zip");
			header("Content-Transfer-Encoding: binary");
			header("Content-Description: File Transfer");
		}
		else{
			header("Content-Type: application/octet-stream");
			header("Content-Length ".filesize($file));
		}
		// Leer el Archivo para poder escribirlo y Descargarlo
		readfile($file);
	}
}
?>
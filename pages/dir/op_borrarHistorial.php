<?php
	/**
	  * Nombre del Módulo: Dirección General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 29/02/2012
	  * Descripción: Este archivo contiene la funcion para borrar las graficas generadas
	**/
	
	//Esta función elimina los graficos generados durante las consultas
	function borrarHistorial(){
		$h=opendir('dir/tmp');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				if (substr($file,0,5)!="alpha" && substr($file,0,5)!="scoop")
						@unlink("dir/tmp/".$file);
			}
		}
		closedir($h);
	}
?>
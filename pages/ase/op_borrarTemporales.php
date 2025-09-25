<?php
//Funcion Borrar Temporales
	function borrarGraficosCalidad(){
		//Borrar los ficheros temporales
		$h=opendir('tmp/');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				@unlink("tmp/".$file);
			}
		}
		closedir($h);
	}
?>
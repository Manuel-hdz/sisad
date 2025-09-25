<?php
	/**
	  * Nombre del M�dulo: Direcci�n General
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 29/02/2012
	  * Descripci�n: Este archivo contiene la funcion para borrar las graficas generadas
	**/
	
	//Esta funci�n elimina los graficos generados durante las consultas
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
<?php
	/**
	  * Nombre del M�dulo: Mantenimiento
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 26/Marzo/2012                                      			
	  * Descripci�n: Este archivo elimina los archivos temporales generados
	  **/
	 	
	 //Esta funci�n elimina los graficos generados durante las consultas y se presione un boton de cancelar
	$h=opendir('../../tmp');
	while ($file=readdir($h)){
		if (substr($file,-4)=='.png'){
			unlink('../../tmp/'.$file);
		}
	}
	closedir($h);
?>
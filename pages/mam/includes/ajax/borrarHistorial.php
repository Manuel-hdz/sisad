<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 26/Marzo/2012                                      			
	  * Descripción: Este archivo elimina los archivos temporales generados
	  **/
	 	
	 //Esta función elimina los graficos generados durante las consultas y se presione un boton de cancelar
	$h=opendir('../../tmp');
	while ($file=readdir($h)){
		if (substr($file,-4)=='.png'){
			unlink('../../tmp/'.$file);
		}
	}
	closedir($h);
?>
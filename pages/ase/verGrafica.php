<?php

	/**
	  * Nombre del M�dulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Mart�nez Fern�ndez
	  * Fecha: 13/Diciembre/2011
	  * Descripci�n: Archivo que permite ver la Grafica ampliada
	  **/  
	  //Titulo de la ventana emergente
	  echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>";
	  //Archivos que permtien desabilitar teclas especificas, as� como desabilitar el clic derecho?>
	  <script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script> 
	  
	  <div class='borde_seccion' title="Click Para Cerrar La Imagen" id='grafica' onclick="window.close();">
	  	<img src="<?php echo $imagen;?>" width="100%" height="100%" align="absbottom"/>
	  </div><?php 
?>
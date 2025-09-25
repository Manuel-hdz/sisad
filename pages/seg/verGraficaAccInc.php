	<?php //Archivo que permite desabilitar teclas como regresar f(1,2,3,4)?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
	<?php //Archivo que permite desabilitar el clic derecho?>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
<?php

	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 13/Diciembre/2011
	  * Descripción: Archivo que permite ver la Grafica ampliada
	  **/  
	  //Titulo de la ventana emergente
	  echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	  
	  $imagen=$_GET['imagen'];?>
	  <div class='borde_seccion' title="Click Para Cerrar La Imagen" id='grafica' onclick="window.close();">
	  	<img src="<?php echo $imagen;?>" width="100%" height="100%" align="absbottom"/>
	  </div><?php 
?>
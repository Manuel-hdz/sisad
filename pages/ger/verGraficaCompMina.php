<script type="text/javascript" src="../../includes/disableKeys.js"></script>
<script type="text/javascript" language="javascript">
	<!--
	//Funcion para desabilitar el clic derecho en la ventana pop-up
	function click() {
		if (event.button==2) {
			alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
		}
	}
	document.onmousedown=click;						
	//-->
</script>
<?php

if(isset($_GET['nombre'])){
	$nombre=$_GET['nombre'];
	mostrarGrafica($nombre);
}	
	
function mostrarGrafica($nombre){	
	//Imprimir la Imagen?>
	<img src="<?php echo $nombre; ?>" width="100%" height="100%" border="0" title="Clik para Cerrar esta Ventana" onclick="window.close();"/><?php
}?>
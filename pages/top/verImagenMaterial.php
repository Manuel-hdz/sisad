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

	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 01/Marzo/2011
	  * Descripción: Este archivo contiene funciones para Ver la Imágen relacionada con el Material de Almacen
	  **/ 

if(isset($_GET['id_material']))
	mostrarImagen($id_material);
function mostrarImagen($id_material){	
	//Incluir el Archivo de Conexion para realizar el enlace con la BD
	include ("../../includes/conexion.inc");
	//Conectarse a la BD de Almacén
	$conn = conecta("bd_almacen");
	
	//Crear la Sentencia SQL para obtener la Imagen
	$sql_stm = "SELECT fotografia, mime FROM materiales WHERE id_material = '$id_material'";
	//Ejecutar la Sentencia
	$rs = mysql_query($sql_stm);
	//Extraer los datos del ResultSets
	$datos = mysql_fetch_array($rs);
	//Obtener la Imagen
	$imagen = $datos['fotografia'];
	//Obtener el MIME Type para indicar el contenido de la ventana
	$mime = $datos['mime'];
	//Indicar el tipo de imagen que será mostrarda en la ventana emergente
	header("Content-Type: $mime");
	//Imprimir la Imagen
	echo $imagen;

	//Cerrar conexion con la BD
	mysql_close($conn);
}
?>

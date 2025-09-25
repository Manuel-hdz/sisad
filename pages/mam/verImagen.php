<?php //Archivos que permtien desabilitar teclas especificas, así como desabilitar el clic derecho?>
<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
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
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 01/Marzo/2011
	  * Descripción: Este archivo contiene funciones para Ver la Imágen relacionada con los Equipos de Mtto
	  **/ 

if(isset($_GET['id_equipo']))
	mostrarImagen();

function mostrarImagen(){	
	//Incluir el Archivo de Conexion para realizar el enlace con la BD
	include ("../../includes/conexion.inc");
	//Conectarse a la BD de Mantenimiento
	$conn = conecta("bd_mantenimiento");
	
	$id_equipo=$_GET["id_equipo"];

	//Recuper la foto almacenada en la tabla
	$sql = "SELECT fotografia, mime FROM equipos WHERE id_equipo = '$id_equipo'";
	//Ejecutar la Sentencia
	$rs = mysql_query($sql);
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

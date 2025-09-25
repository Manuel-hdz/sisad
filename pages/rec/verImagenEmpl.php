<?php

if(isset($_GET['id_empleado']))
	mostrarImagen();
	

function mostrarImagen(){	
	//Incluir el Archivo de Conexion para realizar el enlace con la BD
	include ("../../includes/conexion.inc");
	//Conectarse a la BD de Recursos Humanos
	$conn = conecta("bd_recursos");
	//Recuperamos el ID que viene definido en el get para realizar la consulta
	$id_empleado=$_GET["id_empleado"];

	//Recuperar la foto almacenada en la tabla
	$sql = "SELECT fotografia, mime FROM empleados WHERE id_empleados_empresa = '$id_empleado'";

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

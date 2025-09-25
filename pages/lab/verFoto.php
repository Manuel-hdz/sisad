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
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 08/Julio/2011
	  * Descripción: Este archivo contiene funciones para Ver la Imágen relacionada con el Material de Almacen
	  **/ 

if(isset($_GET['idServicio']))
	mostrarImagen($idServicio);

	
function mostrarImagen($idServicio){	
	//Incluir el Archivo de Conexion para realizar el enlace con la BD
	include ("../../includes/conexion.inc");
	//Conectarse a la BD de Almacén
	$conn = conecta("bd_laboratorio");
	
	$foto = $_GET['foto'];
	
	
	$sql_stm = "";
	if($foto=="antes"){		
		//Crear la Sentencia SQL para obtener la Imagen
		$sql_stm = "SELECT foto_antes,mime_antes FROM memoria_fotografica_mtto WHERE cronograma_servicios_id_servicio = '$idServicio'";
	}
	else{
		//Crear la Sentencia SQL para obtener la Imagen
		$sql_stm = "SELECT foto_despues,mime_despues FROM memoria_fotografica_mtto WHERE cronograma_servicios_id_servicio = '$idServicio'";
	}		
	
	//Ejecutar la Sentencia
	$rs = mysql_query($sql_stm);
	//Extraer los datos del ResultSets
	$datos = mysql_fetch_array($rs);
	//Obtener la Imagen
	$imagen = $datos[0];
	//Obtener el MIME Type para indicar el contenido de la ventana
	$mime = $datos[1];
	//Indicar el tipo de imagen que será mostrarda en la ventana emergente
	header("Content-Type: $mime");
	//Imprimir la Imagen
	echo $imagen;

	//Cerrar conexion con la BD
	mysql_close($conn);
}//mostrarImagen($id_material)


?>
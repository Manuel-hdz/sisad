<?php

	/**
	  * Nombre del M�dulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Mart�nez Fern�ndez
	  * Fecha: 21/Noviembre/2011
	  * Descripci�n: Archivo que permite cargar las fotos al Servidor, validando que solo sea un registro
	  **/  
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_consultarRecSeg.php");
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionAseguramiento.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Iniciamos la sesi�n para las operaciones necesarias en la pagina
	session_start();?>
	<?php //Archivos que permtien desabilitar teclas especificas, as� como desabilitar el clic derecho?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �CONCRETO LANZADO DE FRESNILLO MARCA');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
	<style type="text/css">
		<!--
		#titulo-agregar-archivo { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
		#tabla-agregarArchivos{position:absolute;left:30px;top:30px;width:265px;height:170px;z-index:13;}
		.Estilo1 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
		}
		-->
    </style>
<?php
	if(isset($_GET['idRegistro'])){
		$id = $_GET['idRegistro'];
		mostrarFotosRS($id);
	}
	
?>
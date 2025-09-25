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
	  * Nombre del Módulo: Seguridad Industrial                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 19/Diciembre/2012
	  * Descripción: Archivo que permite cargar las fotos al Servidor, validando que solo sea un registro
	  **/  
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_consultarRecSeg.php");
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionAseguramiento.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Iniciamos la sesión para las operaciones necesarias en la pagina
	session_start();?>
	<style type="text/css">
		<!--
		#titulo-agregar-documentos { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
		#tabla-agregarDepartamentos{position:absolute;left:30px;top:30px;width:265px;height:170px;z-index:13;}
		.Estilo1 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
		}
		-->
    </style>
		<?php 
			if(isset($_GET['idRegistro'])){
				$id = $_GET['idRegistro'];
				mostrarAnomalias($id);
			}
		?>
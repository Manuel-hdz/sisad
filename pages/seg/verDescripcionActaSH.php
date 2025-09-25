<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 10/Febrero/2012
	  * Descripción: Archivo que permite mostrar al usuario la descripcion de un acta de seguridad e higiene
	  **/  
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_registrarRecordatorio.php");
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionAseguramiento.js'></script>";
	//Importamos archivo para realizar la conexion con la BD
	include_once("../../includes/conexion.inc");
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Iniciamos la sesión para las operaciones necesarias en la pagina
	session_start();?>
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
	<style type="text/css">
		<!--
		#tabla-consultarActas{position:absolute;left:30px;top:30px;width:265px;height:170px;z-index:13;}
		.Estilo1 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
		}
		-->
    </style>
	<?php if(isset($_GET['id_registro'])){?>
			<div align="center" id="consultarActas">
			<?php //Realizar la conexion a la BD 
			$conn = conecta("bd_seguridad");
			//Obtenemos el id almacenado en el GET
			$id = $_GET['id_registro'];
			//Cremos la consulta
			$stm_sql ="SELECT descripcion_acta	FROM acta_comision WHERE id_acta_comision='$id'";
						
			//Ejecutamos la sentencia SQL
			$rs = mysql_query($stm_sql);
			//Si la consulta trajo datos creamos la tabla para mostrarlos
			if($datos = mysql_fetch_array($rs)){?>		
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>DESCRIPCI&Oacute;N REGISTRADA PARA LA ACTA <?php echo $_GET['id_registro'];?></caption>
					<tr>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					</tr>
					<tr>
				 	 	<td class='renglon_blanco'><?php echo $datos['descripcion_acta'];?></td>
					</tr>
				</table><?php 
			}
			//Cerrar la conexion con la BD
			mysql_close($conn);?>
			</div>
			<?php
		}
		
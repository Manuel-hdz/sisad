<?php

	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 21/Noviembre/2011
	  * Descripción: Archivo que permite cargar las fotos al Servidor, validando que solo sea un registro
	  **/  
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Archivo de validacion
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_consultarRecordatorioExterno.php");
	echo "<script type='text/javascript' src='../../includes/validacionAseguramiento.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Iniciamos la sesión para las operaciones necesarias en la pagina
	session_start();?>
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
		<form  onsubmit="return valArchivos(this);" name="frm_verArchivo" id="frm_verArchivo" method="post" action="verArchivos.php">
			<?php 
				//Verificamos el GET ; con el que haremos diferencia sera con el get de los recordatorios de seguridad ya que en el enviamos la variable seg
				if(isset($_GET['seg'])){
					mostrarArchivos($_GET['id_alerta'], "bd_seguridad","seg");
				}else{
					mostrarArchivos($_GET['id_alerta'], "bd_aseguramiento", "ase");
				}
			?>
			<table width="100%" border="0" cellpadding="5" cellspacing="5">
				<tr align="center">
					<td colspan="4">
						<input name="btn_cerrar" type="button" class="botones"  value="Cerrar" title="Cerrar El Formulario" onclick="window.close();"/>
					</td>
				</tr>
			</table>
		</form>
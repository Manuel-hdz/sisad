<?php

	/**
	  * Nombre del Módulo: Producción                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 14/Julio/2011
	  * Descripción: Este archivo contiene funciones para registrar un comentario a las Requisiciones de Producción
	  **/
	   
	//Incluimos arrchivo de conexion
	include ("../../includes/conexion.inc");
	//Incluimos el archivo para modificar las fechas para la consulta
	include ("../../includes/func_fechas.php");
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>";
	//Hoja de estilo para la ventana emergente
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
	//Funcion para validar que el maximo tamaño no sea excedido
	echo "<script type='text/javascript' src='../../includes/maxLength.js'></script>";
	//Archivo de validacion para indicar pruebas seleccionadas
	echo "<script type='text/javascript' src='../../includes/validacionLaboratorio.js'></script>";?>
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
	
	<style type="text/css">
	input[readonly="readonly"]{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		background-color:#808080;
		color:#FFFFFF;
	}
	</style>
			
		<p>
		<form name="frm_agregarComentario" method="post" action="verComentarioReq.php">
			<table cellpadding='5' width='100%' align='center' class="tabla_frm"> 
			<caption class='titulo_etiqueta'>Comentarios de la Cuadrilla <?php echo $_GET["idCuadrilla"];?></caption>
			<?php if(isset($_GET["fecha"])) { ?>
			<caption class='titulo_etiqueta'><?php echo $_GET["fecha"];?></caption>
			<?php } ?>
			<tr>
				<td align="center" >
					<textarea name="txa_comentario" maxlength="300" onkeyup="return ismaxlength(this)" cols="50" rows="6" class="caja_de_texto" id="txa_comentario" 
					readonly="readonly"><?php echo $_GET["comentarios"];?></textarea>
				</td>	
			</tr>
			<tr>
				<td colspan="2" align="center">
				<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cancela el Agregado de Comentario y Cierra la Operaci&oacute;n" 
				onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
				</td>
			</tr>
			</table>
		</form>
		</p>
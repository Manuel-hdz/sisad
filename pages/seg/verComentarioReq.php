<?php

	/**
	  * Nombre del Módulo: Seguridad                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 20/Enero/2012
	  * Descripción: Este archivo contiene funciones para registrar un comentario a las Requisiciones de Seguridad
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
	input[readonly="readonly"]{
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		background-color:#808080;
		color:#FFFFFF;
	}
	</style><?php	

 
	//Verificamos si viene la edad en el get; de ser asi llamar la funcion seleccionarPruebas()
	if(isset($_GET['id_requisicion']) || isset($_POST["sbt_borrar"])){
		session_start();
		if (isset($_POST["sbt_borrar"]))
			unset($_SESSION['comentario']);
		ingresarComentario();
	}
	
	//Función que permite ingresar los comentarios
	function ingresarComentario(){
		if (isset($_POST["sbt_borrar"]))
			//Recuperar el ID de Requisicion
			$idReq=$_POST["hdn_id"];
		else
			//Recuperar el ID de Requisicion
			$idReq=$_GET["id_requisicion"];
		$comentario="";
		if (isset($_SESSION["comentario"]))
			$comentario=$_SESSION["comentario"];?>
			
		<p>
		<form name="frm_agregarComentario" method="post" action="verComentarioReq.php">
			<table cellpadding='5' width='100%' align='center' class="tabla_frm"> 
			<caption class='titulo_etiqueta'>Ingrese Comentario a la Requisici&oacute;n <?php echo $idReq;?></caption>
			<tr>
				<td valign="top"><div align="right">Comentario</div></td>
				<td>
					<textarea name="txa_comentario" maxlength="250" onkeyup="return ismaxlength(this)" cols="40" rows="5" class="caja_de_texto" id="txa_comentario" 
					onkeypress="return permite(event,'num_car',0);"><?php echo $comentario;?></textarea>	
				</td>	
			</tr>
			<tr>
				<td colspan="2" align="center">
				<input type="hidden" name="hdn_id" value="<?php echo $idReq;?>"/>
				<input type="submit" name="sbt_guardar" value="Guardar" class="botones" title="Guardar el Comentario" onMouseOver="window.estatus='';return true"/>&nbsp;&nbsp;&nbsp;
				<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cancela el Agregado de Comentario y Cierra la Operaci&oacute;n" 
				onMouseOver="window.estatus='';return true"  onclick="window.close();"/>&nbsp;&nbsp;&nbsp;
				<input type="submit" name="sbt_borrar" value="Borrar" class="botones" title="Borra el Comentario Cargado" onMouseOver="window.estatus='';return true"/>
				</td>
			</tr>
			</table>
		</form>
		</p><?php
	}//fin de la funcion seleccionarPruebas
	
	//Si esta definido el boton de Asignar, pasar las claves a la Session
	if(isset($_POST["sbt_guardar"])){
		session_start();
		$_SESSION['comentario'] = strtoupper($_POST["txa_comentario"]);?>
		<script type="text/javascript" language="javascript">
			window.close();
		</script><?php
	}
	
	
?>
<?php
	include ("../../includes/conexion.inc");
	include ("../../includes/func_fechas.php");
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>";
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
	echo "<script type='text/javascript' src='../../includes/maxLength.js'></script>";
	echo "<script type='text/javascript' src='../../includes/validacionComaro.js'></script>";?>
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
	</style><?php	
	
	session_start();
	if (isset($_POST["sbt_borrar"]))
		unset($_SESSION['comentario']);
	
	ingresarComentario();
	
	function ingresarComentario(){
		$comentario="";
		if(isset($_SESSION["comentario"]))
			$comentario=$_SESSION["comentario"];?>
			
		<p>
		<form name="frm_agregarComentario" method="post" action="verComentarioReq.php">
			<table cellpadding='5' width='100%' align='center' class="tabla_frm"> 
			<caption class='titulo_etiqueta'>Ingrese Comentario a la Requisici&oacute;n</caption>
			<tr>
				<td valign="top"><div align="right">Comentario</div></td>
				<td>
					<textarea name="txa_comentario" maxlength="250" cols="40" rows="10" class="caja_de_texto" id="txa_comentario" 
					onkeypress="return permite(event,'num_car',0);" style="resize:none;"><?php echo $comentario;?></textarea>	
				</td>	
			</tr>
			<tr>
				<td colspan="2" align="center">
				<input type="submit" name="sbt_guardar" value="Guardar" class="botones" title="Guardar el Comentario" onMouseOver="window.estatus='';return true"/>&nbsp;&nbsp;&nbsp;
				<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cancela el Agregado de Comentario y Cierra la Operaci&oacute;n" 
				onMouseOver="window.estatus='';return true"  onclick="window.close();"/>&nbsp;&nbsp;&nbsp;
				<input type="submit" name="sbt_borrar" value="Borrar" class="botones" title="Borra el Comentario Cargado" onMouseOver="window.estatus='';return true"/>
				</td>
			</tr>
			</table>
		</form>
		</p>
		<?php
	}
	
	if(isset($_POST["sbt_guardar"])){
		session_start();
		$_SESSION['comentario'] = strtoupper($_POST["txa_comentario"]);?>
		<script type="text/javascript" language="javascript">
			window.close();
		</script><?php
	}
	
	
?>
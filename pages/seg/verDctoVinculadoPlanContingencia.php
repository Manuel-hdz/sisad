<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 25/Abril/2012
	  * Descripción: Archivo que permite cargar el archivo que se vinculara al plan de contingencia
	  **/  
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Archivo de validacion
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_planesContingencia.php");
	echo "<script type='text/javascript' src='../../includes/validacionSeguridad.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";?>
	
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
	<?php
		//Verificamos si esta definido el boton sbt_finalizarCargaDcto
		if(isset($_POST["sbt_finalizarCargaDcto"])){
			$contad = 1;
			//Recorremos el foreach  para almacenar el valor contenido en el Post en una variable y enviarla a una caja de texto
			foreach($_POST as $key => $valor){
				if($contad==1){
					$archivos = $valor;
				}
				$contad++;
			}?>
				<script type="text/javascript" language="javascript">
					window.opener.document.getElementById("txt_archivos").value="<?php echo $archivos;?>"; 				
					window.opener.focus();
					window.close();
				</script>
			<?php	
			}?>
		
		<form  onsubmit="return valArchivosPlanContingencia(this);" name="frm_verArchivoPC" id="frm_verArchivoPC" method="post" action="">
			<?php $band = mostrarArchivos();?>
			<table width="100%" border="0" cellpadding="5" cellspacing="5">
				<tr align="center">
					<td colspan="4">
					<?php if($band==1){?>
						<input name="sbt_finalizarCargaDcto" id="sbt_finalizarCargaDcto" type="submit" class="botones" value="Finalizar" 
						title="Vuelve al Registro de los Planes de Contingencia"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar las Opciones"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_cerrar" type="button" class="botones"  value="Cerrar" title="Cerrar El Formulario" onclick="window.close();"/>
					<?php }
					else{
						echo "<p class='msje_correcto'>No existen Archivos Registrados</p>";
					}?>
					</td>
				</tr>
			</table>
		</form>
	

<?php

	/**
	  * Nombre del Módulo: LAboratorio                                                
	  * Nombre Programador: Daisy Ariana Martínez Fernández
	  * Fecha: 09/Agosto/2011
	  * Descripción: Archivo que permite cargar las fotos al Servidor, validando que solo sea un registro
	  **/  
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_generarRequisicion.php");
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionLaboratorio.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	//Iniciamos la sesión para las operaciones necesarias en la pagina
	session_start();?>
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
		<!--
		#titulo-agregar-documentos { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
		#tabla-agregarFotos { position:absolute; left:24px; top:40px; width:550px; height:138px; z-index:12; padding:15px; padding-top:0px;}
		#tabla-fotos{position:absolute;left:30px;top:440px;width:746px;height:170px;z-index:13;overflow:scroll;}
		.Estilo1 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
		}
		-->
    </style>
    <fieldset class="borde_seccion" id="tabla-agregarFotos" name="tabla-agregarEquipoFotos">
	<legend class="titulo_etiqueta">Registrar Fotograf&iacute;a Requisici&oacute;n <?php echo $_SESSION['id_requisicion']; ?></legend>	
	<?php 
		//Verificamos si viene el GET; si es asi guardamos los valores en las variables para realizar su posterior uso. 
		if(isset($_GET)){ 
			$nombre=$_GET["nombreMat"]; 
		}
	?>
	<br>
	<form name="frm_agregarFoto" method="post" action="verRegistrarFotoRequisicion.php?nombreMat=<?php echo $nombre;?>"
	onsubmit="return valFormFoto(this);" enctype="multipart/form-data">
		<table width="519" border="0" cellpadding="5" cellspacing="5">
			<tr>
	    	    <td valign="top"><div align="right"><span class="Estilo1">Cargar Archivo</span></div></td>
			    <td valign="top">
					<input type="file" name="file_documento" id="file_documento" size="36" value="" />
					<input type="hidden" name="hdn_docValido" id="hdn_docValido" value="si" />				
				</td>
			</tr>
    		<tr align="center">
				<td colspan="4">
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="hidden" name="hdn_nombre" value="<?php echo $nombre; ?>"/>
					<input name="sbt_cargar" type="submit" id="sbt_cargar" class="botones" value="Cargar" 
					title="Finalizar el guardado del Registro Fotogr&aacute;fico"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" onclick="window.close();" 
					title="Vuelve al Registro de la Requiisici&oacute;n"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los campos"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;				
				</td>
   			</tr>
		</table>
	</form>
	</fieldset>
	<?php
		//Verificar que el combo estatus esta definido
		if (isset($_POST["sbt_cargar"])){
			//Variable que almacena el nombre del Archivo Fisico en caso que este haya sido cargado
			$archivo="";
			//Variable que indica si el archivo fue subido con éxito
			$resSubirArch="";
						
			//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado
			if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
				$nombreArchaivoOriginal=$_FILES['file_documento']['name'];
				$seccRuta=explode(".",$nombreArchaivoOriginal);
				$nombre=$nombre.".".$seccRuta[1];
				$archivo= $nombre;
				$resSubirArch = subirFotos($_SESSION['id_requisicion'], $_POST["hdn_nombre"]);
			}
			
			
			//Si ya esta definido el arreglo $fotos, entonces agregar el siguiente registro a el
			if(isset($_SESSION['fotosReq'])){			
				
				if($resSubirArch){
					//Guardar los datos en el arreglo
					$fotosReq[] = array("archivo"=>$archivo,"clave"=>$_SESSION['id_requisicion']);
				}
			}
			//Si no esta definido el arreglo $fotos definirlo y agregar el primer registro
			else{			
				//Guardar los datos en el arreglo
				$fotosReq = array(array("archivo"=>$archivo,"clave"=>$_SESSION['id_requisicion']));
				$_SESSION['fotosReq'] = $fotosReq;	
			}	
		}
				
	?>

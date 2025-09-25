<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las operaciones para Agregar Documentacion y Mostrarla en el formulario
		include ("op_agregarEquipo.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>

    <style type="text/css">
		<!--
		#titulo-agregar-documentos { position:absolute; left:30px; top:146px; width:200px; height:20px; z-index:11;}
		#tabla-agregarEquipoDocumentos { position:absolute; left:30px; top:190px; width:746px; height:275px; z-index:12; padding:15px; padding-top:0px;}
		#tabla-documentos{position:absolute;left:30px;top:490px;width:746px;height:170px;z-index:13;overflow:scroll;}
		-->
    </style>
</head>
<body>
	<?php 
	//Por razones de seguridad comprobamos que este definida la variable txt_clave en el POST para luego asignarla a una variable que facilite su manejo
	//Comprobamos que en el GET no se encuentre id, de ser asi, se presiono el boton Finalizar, y esto provocará que los datos agregados al arreglo de Sesion se agreguen
	//a la tabla que le corresponde
	if (isset($_POST["txt_clave"])&&!isset($_GET["id"])){ 
		$clave=$_POST["txt_clave"];
	?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    	<div class="titulo_barra" id="titulo-agregar-documentos">Registrar Documentos </div>
	
		<fieldset class="borde_seccion" id="tabla-agregarEquipoDocumentos" name="tabla-agregarEquipoDocumentos">
			<legend class="titulo_etiqueta">Registrar Documentos Equipo <?php echo $clave; ?></legend>	
			<br>
			<?php 
			//Verificar de donde se llego a esta pagina para asignar el respectivo Boton de Cancelar, en el caso que se declare la variable mod, se llego aqui desde la pantalla de Modificar Documentos
			if (!isset($_GET["mod"])){?>
				<form name="frm_equipoAgregado" method="post" action="frm_agregarDocumentacionEquipo.php" onsubmit="return valFormAgregarDocumento(this);" enctype="multipart/form-data">
			<?php }
			else {?>
				<form name="frm_equipoAgregado" method="post" action="frm_agregarDocumentacionEquipo.php?mod=si" onsubmit="return valFormAgregarDocumento(this);" enctype="multipart/form-data">
			<?php }?>
			<table width="763" border="0" cellpadding="5" cellspacing="5">
			<tr>
				<td width="76" align="right">Equipo</td>
    	    	<td width="167"><input type="text" id="txt_clave" name="txt_clave" value="<?php echo $clave;?>" readonly="readonly"/></td>
	    	    <td width="111">&nbsp;</td>
			    <td width="364">&nbsp;</td>
			</tr>
			<tr>
				<td align="right" valign="top">Documento</td>
        		<td>
                	<textarea name="txa_documento" id="txa_documento" cols="30" rows="3" maxlength="120" onkeypress="return permite(event,'num_car',0);" 
                    onkeyup="return ismaxlength(this)" class="caja_de_texto"></textarea>
				</td>
	    	    <td>&nbsp;</td>
			    <td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right">Estatus</td>
        		<td>
                	<select name="cmb_estatus" id="cmb_estatus" class="combo_box">
						<option selected="selected" value="NO ENTREGADO">NO ENTREGADO</option>
						<option value="ENTREGADO">ENTREGADO</option>
					</select>
				</td>
   	    	    <td>&nbsp;</td>
			    <td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right" valign="top">Ubicacion</td>
        		<td>
					<textarea name="txa_ubicacion" id="txa_ubicacion" cols="30" rows="3" maxlength="120" onkeypress="return permite(event,'num_car',0);" 
                    onkeyup="return ismaxlength(this);" class="caja_de_texto"></textarea>
				</td>
	    	    <td valign="top"><div align="right">Cargar Archivo</div></td>
			    <td valign="top">
					<input type="file" name="file_documento" id="file_documento" size="36" value="" onchange="validarDocumento(this);"/>
					<input type="hidden" name="hdn_docValido" id="hdn_docValido" value="si" />
				</td>
			</tr>
    		<tr align="center">
			<?php 
			//Verificamos que este definido en el POST el valor txt_nombre para mostrar este boton
			if (isset($_POST["txt_nombre"]))
				//Si viene definido, inicializamos var en 0, si vale 0, muestra el botón de Finalizar deshabilitado
				$var=0;
			else
				//Si var vale 1, el boton de finalizar se activa para poder emplearse
				$var=1;
			?>
        		<td colspan="4">
					<input name="sbt_registrar" type="submit" class="botones" value="Registrar" onmouseover="window.status='';return true;" 
                    title="Registrar los datos del Documento Actual"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if ($var==0){?>
						<input name="btn_finalizar" type="button" class="botones" value="Finalizar" disabled="disabled" 
						title="&iexcl;Opci&oacute;n No Disponible Hasta Agregar un Documento!"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php }else {?>
                        <input name="btn_finalizar" type="button" class="botones" value="Finalizar"
                        onclick="location.href='frm_agregarDocumentacionEquipo.php?id=<?php echo $clave;?>';" 
                        title="Terminar de guardar los documentos"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php }?>
                    <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los campos"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
					//Verificar de donde se llego a esta pagina para asignar el respectivo Boton de Cancelar
					if (!isset($_GET["mod"])){?>
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" onclick="confirmarSalida('frm_equipoAgregado.php?id_eq=<?php echo $clave;?>&cancelar');" 
    	                title="Guarda el Equipo y Vuelve al Men&uacute; de Equipos"/>
					<?php }
					else{?>
						<?php //Pasamos en la ruta del boton cancelar, el atributo GET para que muestre la pagina de donde se procedio, 
						//ademas de cancelar, que indica que se ha cancelado y se deben eliminar ciertos documentos del Servidor ?>
	                    <input name="btn_cancelar" type="button" class="botones"  value="Cancelar" onclick="confirmarSalida('frm_modificarEquipoDoc.php?id_equipo=<?php echo $clave;?>&cancelar')" 
    	                title="Cancela la Operaci&oacute;n y Regresa a Elegir Operaci&oacute;n sobre los Documentos"/>
					<?php }?>
				</td>
   			</tr>
			</table>
			</form>
		</fieldset>
	<?php
		//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
		if (isset($_POST["txt_clave"])&&isset($_POST["txa_documento"])&&isset($_POST["txa_ubicacion"])){
			//Variable que almacena el nombre del Archivo Fisico en caso que este haya sido cargado
			$archivo="";
			//Verificar si esta definido el Arreglo de Archivos y tiene documento agregado
			if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
				$archivo=$_FILES["file_documento"]["name"];
			}
			//Si ya esta definido el arreglo $documentos, entonces agregar el siguiente registro a el
			if(isset($_SESSION['documentos'])){			
				//Guardar los datos en el arreglo
				$documentos[] = array("nombre"=>strtoupper($txa_documento), "estatus"=>strtoupper($cmb_estatus), "ubicacion"=>strtoupper($txa_ubicacion), "archivo"=>$archivo);
			}
			//Si no esta definido el arreglo $datosEntrada definirlo y agregar el primer registro
			else{			
				//Guardar los datos en el arreglo
				$documentos = array(array("nombre"=>strtoupper($txa_documento), "estatus"=>strtoupper($cmb_estatus), "ubicacion"=>strtoupper($txa_ubicacion), "archivo"=>$archivo));
				$_SESSION['documentos'] = $documentos;	
			}	
		}
		
		//Verificar que este definido el Arreglo de Documentos, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["documentos"])){
			echo "<div id='tabla-documentos' class='borde_seccion2'>";
			mostrarDocumentosReg($documentos);
			echo "</div>";
		}
		
		//Verificar que en el arreglo FILES este declarado algun documento
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			//Si hay un documento que Cargar, los agregamos al momento llamando la funcion que permite esta accion
			subirArchivos($clave);
			//Si ya esta definido el Arreglo Documento Temporal, entonces agregamos el nombre del nuevo documento a el
			if (isset($_SESSION["docTemporal"])){
				//Guardamos el nombre del nuevo documento en el Arreglo de Session, esto nos permitira eliminarlo de ser presionado el boton cancelar, ademas de preservar el documento a lo largo del proceso
				$docTemporal[]=array("nom_archivo"=>$_FILES["file_documento"]["name"],"carpeta"=>$clave);
			}
			else{
				//Si no esta definido el arreglo $docTemporal, lo definimos y agregamos a el, el primer registro de archivos
				$docTemporal=array(array("nom_archivo"=>$_FILES["file_documento"]["name"],"carpeta"=>$clave));
				$_SESSION["docTemporal"]=$docTemporal;
			}
		}
		
		
	//Else que comprueba la llegada a esta pagina
	}else{
		//Si no esta definido el GET, se llego a esta pantalla de otra manera, en dado caso cerrar la sesion
		if (!isset($_GET["id"])){
			echo "<meta http-equiv='refresh' content='0;url=../salir.php'>";
		}
		else{
			//Mandar llamar a la funcion que registra los documentos en la tabla correspondiente, con los parametros de Clave de Equipo y el Arreglo de Documentos->documentos
			registrarDocumentosEquipo($_GET["id"],$documentos);
		}
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
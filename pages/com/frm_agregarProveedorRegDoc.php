<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo se encarga de agregar la documentacion del proveedor seleccionado
		include ("op_agregarProveedor.php");
		//Este archivo se usa para eliminar la documentacion del proveedor seleccionado
		include ("op_modificarProveedor.php");
	
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>

    <style type="text/css">
	<!--
		#agregar-prov {position:absolute;left:30px;top:146px;width:329px;height:19px;z-index:10;}
		#tabla-registro {position:absolute;left:30px;top:190px;width:600px;height:270px;z-index:11;}
		#tabla-documentos{position:absolute;left:30px;top:490px;width:600px;height:170px;z-index:12;overflow:scroll;}
		#eliminar-documentos{position:absolute;left:30px;top:190px;width:900px;height:270px;z-index:11;overflow:scroll;}
		#botones{position:absolute; left:30px; top:550px; width:900px; height:21px; z-index:13;}
		-->
   	 </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="agregar-prov">Agregar Proveedor/Registrar Documentaci&oacute;n</div>
	<?php if ($_GET["btn"]=="agregar"){?>
	<fieldset class="borde_seccion" id="tabla-registro" name="tabla-registro">
		<legend class="titulo_etiqueta">Registrar la Documentaci&oacute;n del Proveedor</legend>
		<br>
  		<form name="tabla-regDoc" onsubmit="return valTablaRegDoc(this)" method="post" action="">
		<table width="580" border="0" cellpadding="5" cellspacing="5">
			<tr>
			  <td width="126" align="right">Proveedor</td>
    	    	<td width="419">
				<?php if(isset($_POST["hdn_nombre"])){?>
						<input type="text" name="txt_nombre" class="caja_de_texto" size="60" readonly="true" value="<?php echo $_POST["hdn_nombre"];?>"/>
						<input type="hidden" id="hdn_nombre" name="hdn_nombre" value="<?php echo $_POST["hdn_nombre"];?>"/>
						<input type="hidden" id="hdn_rfc" name="hdn_rfc" value="<?php echo $_POST["hdn_rfc"];?>"/>
				<?php }
				else{?>
						<input type="text" name="txt_nombre" class="caja_de_texto" size="60" readonly="true" value="<?php echo $_POST["txt_razonSoc"];?>"/>
						<input type="hidden" id="hdn_nombre" name="hdn_nombre" value="<?php echo $_POST["txt_razonSoc"];?>"/>
						<input type="hidden" id="hdn_rfc" name="hdn_rfc" value="<?php echo $_POST["txt_rfc"];?>"/>
				<?php }?>
				</td>
	    	</tr>
			<tr>
				<td align="right" valign="top">Documento</td>
        		<td>
                	<textarea name="txa_documento" id="txa_documento" cols="30" rows="3" maxlength="120" onkeypress="return permite(event,'num_car',0);" 
                    onkeyup="return ismaxlength(this)" class="caja_de_texto"></textarea>
				</td>
	    	</tr>
			<tr>
				<td align="right">Estatus</td>
        		<td>
                	<select name="cmb_estatus" id="cmb_estatus" class="combo_box">
					<option selected="selected" value="NO ENTREGADO">NO ENTREGADO</option>
					<option value="ENTREGADO">ENTREGADO</option>
					</select>
                </td>
   	    	</tr>
			<tr>
				<td align="right">Ubicacion</td>
        		<td>
					<textarea name="txa_ubicacion" id="txa_ubicacion" cols="30" rows="3" maxlength="120" onkeypress="return permite(event,'num_car',0);" 
                    onkeyup="return ismaxlength(this)" class="caja_de_texto"></textarea>
				</td>
	    	</tr>

    		<tr align="center">
			<?php if (isset($_POST["txa_documento"]))
					$var=0;
				else
					$var=1;?>
        		<td colspan="2">
                    <input name="sbt_registrar" type="submit" class="botones" value="Registrar" onmouseover="window.status='';return true;" 
                    title="Registrar los datos del Documento Actual"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if ($var!=0){?>
                        <input name="btn_finalizar" type="button" class="botones" value="Finalizar" disabled='disabled' 
                        title="Terminar de guardar los documentos"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php }else {?>
                        <input name="btn_finalizar" type="button" class="botones" value="Finalizar" <?php echo "$var";?> 
                        onclick="location.href='frm_agregarProveedorRegDoc.php?btn=Finalizar&id=<?php echo $_POST["hdn_rfc"];?>';" 
                        title="Terminar de guardar los documentos"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php }?>
                    <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar los campos"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input name="btn_cancelar" type="button" class="botones"  value="Cancelar" onclick="location.href='menu_proveedores.php'" 
                    title="Guardar al Proveedor y Volver al Men&uacute; de Proveedores"/></td>
   			</tr>
		</table>
		</form>
	</fieldset>
		<?php
		//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
		if (isset($_POST["txa_documento"])&&isset($_POST["hdn_nombre"])&&isset($_POST["txa_documento"])){
			//Si ya esta definido el arreglo $documentos, entonces agregar el siguiente registro a el
			if(isset($_SESSION['documentos'])){			
				//Guardar los datos en el arreglo
				$documentos[] = array("nombre"=>strtoupper($txa_documento), "estatus"=>strtoupper($cmb_estatus), "ubicacion"=>strtoupper($txa_ubicacion));
			}
			//Si no esta definido el arreglo $datosEntrada definirlo y agregar el primer registro
			else{			
				//Guardar los datos en el arreglo
				$documentos = array(array("nombre"=>strtoupper($txa_documento), "estatus"=>strtoupper($cmb_estatus), "ubicacion"=>strtoupper($txa_ubicacion)));
				$_SESSION['documentos'] = $documentos;	
			}	
		}
		if (isset($_SESSION["documentos"])){
			echo "<div id='tabla-documentos' class='borde_seccion2'>";
			mostrarDocumentosReg($documentos);
			echo "</div>";
		}
	}
	else{
		//Instrucciones BOTON ELIMINAR
		if ($_GET["btn"]=="eliminar"){
			if (isset($_POST["rdb_documentos"]))
				eliminarDocumento();
			echo "<form method='post' name='frm_documentos' action='inicio_compras.php'>";
			echo "<div id='eliminar-documentos' align='center'>";
			$ctrl=seleccionarDocumentos();
			echo "</div>";
			?>
			<div id='botones' name='botones'>
                <table class='tabla_frm' border='0' align="center">
                    <tr>
                        <td>
                            <input type="hidden" value="<?php echo $_POST["hdn_nombre"]?>" name="hdn_nombre"/>
                            <input type="hidden" value="<?php echo $_POST["hdn_rfc"]?>" name="hdn_rfc"/>
                            <input name='btn_eliminar' id='btn_eliminar' type='button' value='Eliminar' class='botones' <?php echo $ctrl;?> 
                            title='Eliminar documento del expediente del proveedor' onmouseover="window.status='';return true" 
                            onclick="document.frm_documentos.action='frm_agregarProveedorRegDoc.php?btn=eliminar';document.frm_documentos.submit();"/>		
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input name='btn_cancelar' id="btn_cancelar" type='button' value='Cancelar' class='botones' title='Regresar a la página de Inicio de Compras'
                            onclick="location.href='frm_modificarProveedor.php';$_POST=0;"/>
                        </td>
                    </tr>
                </table>
			</div>
			<?php
			echo "</form>";
		}
		if ($_GET["btn"]=="Finalizar"&&isset($_SESSION["documentos"])){
			$rfc=$_GET["id"];
			agregarDocumentos($rfc,$documentos);
		}
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>

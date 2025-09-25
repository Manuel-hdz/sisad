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
		//archivo que ejecuta la inserción de datos del proveedor
		include ("op_agregarProveedor.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>	
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
    <style type="text/css">
	<!--
		#titulo-proveedor {	position:absolute;	left:30px; top:146px; width:170px; height:22px;	z-index:11; }
		#tabla-agregar { position:absolute;	left:30px; top:190px; width:679px;	height:490px; z-index:12; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-proveedor">Agregar Proveedor</div>
	
	<?php if (!isset($_GET["btn"])){?>
	<fieldset class="borde_seccion" id="tabla-agregar" name="tabla-agregar">
	<legend class="titulo_etiqueta">Agregar Proveedores</legend>
	<br>
	<form onSubmit="return verContFormAgregarProveedor(this);" name="frm_agregarProveedor" method="post" action="frm_agregarProveedor.php?btn=registrar">
	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
   	  	  <td width="120"><div align="right">*RFC</div></td>
          	<td>
				<input name="txt_rfc" type="text" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car',3);"
				onblur="verificarDatoBD(this,'bd_compras','proveedores','rfc','razon_social');" /><span id="error" class="msj_error">RFC Duplicado</span>
			</td>
          	<td><div align="right">*Tel&eacute;fono</div></td>
          	<td>
            	<input name="txt_tel" type="text" class="caja_de_texto" id="txt_telefono" size="15" maxlength="20" onkeypress="return permite(event,'num',6);"
            	onblur="validarTelefono(this);" /></td>
        </tr>
        <tr>
          	<td><div align="right">*Raz&oacute;n Social </div></td>
          	<td><input name="txt_razonSoc" type="text" class="caja_de_texto" onkeypress="return permite(event,'num_car',4);" size="40" maxlength="80"/></td>
          	<td><div align="right">Tel&eacute;fono 2 </div></td>
          	<td>
            	<input name="txt_tel2" type="text" class="caja_de_texto" id="txt_tel2" onkeypress="return permite(event,'num',6);" size="15" maxlength="20" 
                onblur="validarTelefono(this);" /></td>
       	</tr>
        <tr>
        	<td><div align="right">*Calle</div></td>
          	<td><input name="txt_calle" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car',0);"/></td>
          	<td><div align="right">Fax</div></td>
          	<td>
            	<input name="txt_fax" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num',6);"
                onblur="validarTelefono(this);" /></td>
        </tr>
        <tr>
        	<td><div align="right">*N&uacute;mero Ext.</div></td>
			<td align="left">	
				<input name="txt_numExt" type="text" class="caja_de_texto" size="5" maxlength="10" onkeypress="return permite(event,'num_car',1);" />
				&nbsp;Int.&nbsp;<input name="txt_numInt" type="text" class="caja_de_texto" size="5" maxlength="10" onkeypress="return permite(event,'num_car',1);"/>			
			</td>
          	<td><div align="right">*Relevancia </div></td>
          	<td>
				<select name="cmb_relevancia" class="combo_box">
					<option value="NO CRITICO">NO CRITICO</option>
			  		<option value="CRITICO">CRITICO</option>
           		</select>		
            </td>
        </tr>
        <tr>
        	<td><div align="right">*Colonia </div></td>
        	<td>
            	<input name="txt_col" type="text" class="caja_de_texto" id="txt_colonia" size="30" maxlength="60" onkeypress="return permite(event,'num_car',0);" />
            </td>
          	<td><div align="right">Correo</div></td>
          	<td>
            	<input name="txt_correo" type="text" class="caja_de_texto" size="30" maxlength="40" onblur="validarCorreo(this);"
                onkeypress="return permite(event,'num_car',0);" />
            </td>
        </tr>
        <tr>
        	<td><div align="right">*C&oacute;digo Postal </div></td>
          	<td><input name="txt_cp" type="text" class="caja_de_texto" size="5" maxlength="5" onkeypress="return permite(event,'num',3);" /></td>
          	<td><div align="right">Correo 2 </div></td>
          	<td>
            	<input name="txt_correo2" type="text" class="caja_de_texto" size="30" maxlength="40" onblur="validarCorreo(this);" 
            	onkeypress="return permite(event,'num_car',0);" />
            </td>
        </tr>
        <tr>
        	<td><div align="right">*Ciudad</div></td>
          	<td><input name="txt_ciudad" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car',2);"/></td>
          	<td><div align="right">*Contacto</div></td>
          	<td><input name="txt_contacto" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car',2);" /></td>
        </tr>
        <tr>
        	<td><div align="right">*Estado</div></td>
          	<td><input name="txt_estado" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car',2);"/></td>
          	<td>&nbsp;</td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td><div align="right">*Material y/o Servicio</div></td>
          	<td>
            	<textarea name="txa_matServ" id="txa_matServ" cols="30" rows="3" maxlength="120" onkeypress="return permite(event,'num_car',4);" 
                onkeyup="return ismaxlength(this)" class="caja_de_texto"></textarea>
            </td>
          	<td><div align="right">Observaciones </div></td>
          	<td>
            	<textarea name="txa_observaciones" id="txa_observaciones" cols="30" rows="3" maxlength="120" onkeypress="return permite(event,'num_car',0);"
                onkeyup="return ismaxlength(this)" class="caja_de_texto"></textarea>
            </td>
        </tr>
		<tr><td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
        <tr>
        	<td colspan="5">		  	
       	      	<div align="center">
                    <input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
					<input type="hidden" name="hdn_validaBoton" id="hdn_validaBoton" value="si" />
                    <input name="sbt_registrarDoc" type="button" class="botones_largos"  value="Registrar Documentaci&oacute;n" 
                    onclick="document.frm_agregarProveedor.action='frm_agregarProveedorRegDoc.php?btn=agregar';document.frm_agregarProveedor.submit();" 
                    title="¡Opción No Disponible hasta Agregar al Proveedor!" disabled="true"/>
					&nbsp;
       	        	<input name="btn_Agregar" type="submit" class="botones"  value="Agregar" title="Agregar los Datos del Proveedor"
                    onMouseOver="window.status='';return true" />
       	        	&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar el Formulario" /> 
       	        	&nbsp;
					<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Proveedores"
                    onclick="location.href='menu_proveedores.php'" />
              	</div>			
			</td>
		</tr>
    </table>
  	</form>
	</fieldset>
	<?php }else
	{
		$msg=agregarProveedor();
		?>
		<fieldset class="borde_seccion" id="tabla-agregar" name="tabla-agregar">
	<legend class="titulo_etiqueta">Agregar Proveedores</legend>
	<br>
	<form name="frm_agregarProveedor" method="post" action="frm_agregarProveedorRegDoc.php?btn=agregar">
	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
   	  	  <td width="120"><div align="right">RFC</div></td>
          	<td>
				<input name="txt_rfc" type="text" class="caja_de_texto" size="15" maxlength="13" readonly="true" value="<?php echo($_POST["txt_rfc"]);?>"/>
			</td>
          	<td><div align="right">Tel&eacute;fono</div></td>
          	<td>
            	<input name="txt_tel" type="text" class="caja_de_texto" id="txt_telefono" size="15" maxlength="20" readonly="true" 
                value="<?php echo($_POST["txt_tel"]);?>" />
            </td>
        </tr>
        <tr>
          	<td><div align="right">Raz&oacute;n Social </div></td>
          	<td>
            	<input name="txt_razonSoc" type="text" class="caja_de_texto" size="40" maxlength="80" readonly="true"
            	 value="<?php echo($_POST["txt_razonSoc"]);?>"/>
            </td>
          	<td><div align="right">Tel&eacute;fono 2 </div></td>
          	<td>	
            	<input name="txt_tel2" type="text" class="caja_de_texto" id="txt_tel2" size="15" maxlength="20" readonly="true"
                value="<?php echo($_POST["txt_tel2"]);?>" /></td>
       	</tr>
        <tr>
        	<td><div align="right">Calle</div></td>
          	<td><input name="txt_calle" type="text" class="caja_de_texto" size="30" maxlength="40" readonly="true" value="<?php echo($_POST["txt_calle"]);?>"/></td>
          	<td><div align="right">Fax</div></td>
          	<td><input name="txt_fax" type="text" class="caja_de_texto" size="15" maxlength="20" readonly="true" value="<?php echo($_POST["txt_fax"]);?>" /></td>
        </tr>
        <tr>
        	<td><div align="right">N&uacute;mero Ext.</div></td>
			<td align="left">	
				<input name="txt_numExt" type="text" class="caja_de_texto" size="5" maxlength="10" readonly="true" value="<?php echo($_POST["txt_numExt"]);?>"/>
				&nbsp;Int.&nbsp;<input name="txt_numInt" type="text" class="caja_de_texto" size="5" maxlength="10" readonly="true"
                value="<?php echo($_POST["txt_numInt"]);?>"/>			
			</td>
          	<td><div align="right">Relevancia </div></td>
          	<td>
            	<input name="cmb_relevancia" type="text" class="caja_de_texto" size="30" maxlength="40" readonly="true" 
                value="<?php echo($_POST["cmb_relevancia"]);?>"/>
            </td>
        </tr>
        <tr>
        	<td><div align="right">Colonia </div></td>
        	<td>
            	<input name="txt_col" type="text" class="caja_de_texto" id="txt_colonia" size="30" maxlength="60" readonly="true"
                value="<?php echo($_POST["txt_col"]);?>"/>
          </td>
          	<td><div align="right">Correo</div></td>
          	<td><input name="txt_correo" type="text" class="caja_de_texto" size="30" maxlength="40" readonly="true" value="<?php echo($_POST["txt_correo"]);?>"/></td>
        </tr>
        <tr>
        	<td><div align="right">C&oacute;digo Postal </div></td>
          	<td><input name="txt_cp" type="text" class="caja_de_texto" size="5" maxlength="5" readonly="true" value="<?php echo($_POST["txt_cp"]);?>"/></td>
          	<td><div align="right">Correo 2 </div></td>
          	<td><input name="txt_correo2" type="text" class="caja_de_texto" size="30" maxlength="40" readonly="true" value="<?php echo($_POST["txt_correo2"]);?>"/></td>
        </tr>
        <tr>
        	<td><div align="right">Ciudad</div></td>
          	<td><input name="txt_ciudad" type="text" class="caja_de_texto" size="30" maxlength="40" readonly="true" value="<?php echo($_POST["txt_ciudad"]);?>"/></td>
          	<td><div align="right">Contacto</div></td>
          	<td>
            	<input name="txt_contacto" type="text" class="caja_de_texto" size="30" maxlength="40" readonly="true" value="<?php echo($_POST["txt_contacto"]);?>"/>
            </td>
        </tr>
        <tr>
        	<td><div align="right">Estado</div></td>
          	<td><input name="txt_estado" type="text" class="caja_de_texto" size="30" maxlength="40" readonly="true" value="<?php echo($_POST["txt_estado"]);?>"/></td>
          	<td>&nbsp;</td>
          	<td>&nbsp;</td>
        </tr>
        <tr>
        	<td><div align="right">Material y/o Servicio</div></td>
          	<td>
            	<textarea name="txa_matServ" id="txa_matServ" cols="30" rows="3" maxlength="120" class="caja_de_texto" 
                readonly="true"><?php echo($_POST["txa_matServ"]);?></textarea>
            </td>
          	<td><div align="right">Observaciones </div></td>
          	<td>
            	<textarea name="txa_observaciones" id="txa_observaciones" cols="30" rows="3" maxlength="120" class="caja_de_texto" 
                readonly="true"><?php echo($_POST["txa_observaciones"]);?></textarea>
            </td>
        </tr>
		<tr><td colspan="4" align="center"><?php echo "<label class='msje_correcto'>".$msg."</label>"; ?></td></tr>
        <tr>
        	<td colspan="5">		  	
       	      	<div align="center">
       	        <input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
				<input name="sbt_registrarDoc" type="submit" class="botones_largos"  value="Registrar Documentaci&oacute;n"
                title="Registrar la Documentación del Proveedor" onmouseover="window.status='';return true;"/>
				&nbsp;
       	        <input name="btn_finalizar" type="button" class="botones" value="Finalizar" title="Terminar de Registrar El Proveedor"
                onMouseOver="window.status='';return true" onclick="location.href='exito.php'"/>
       	        &nbsp;
				<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="¡Opción No Disponible!" disabled="true"/> 
              	</div>			
			</td>
		</tr>
    </table>
  	</form>
</fieldset>
		<?php
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		//Este archivo contiene las operaciones para mostrar el detalle del proveedor
		include ("op_modificarCliente.php");
	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>	
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute; left:30px; top:146px;	width:132px; height:20px; z-index:11; }
		#tabla-modificarC {position:absolute; left:30px; top:190px; width:890px; height:505px; z-index:12; }
		#msg-resultado {position:absolute; left:30px; top:190px; width:940px; height:122px; z-index:13; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Cliente </div>
	
	<?php
	if(!isset($_POST['txt_rfc'])){	 	 
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");	 	
		
		//Obtener los datos del proveedor seleccionado por el usuario		
		$stm_sql = "SELECT * FROM clientes WHERE razon_social='$txt_nombre'";					
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);	
							
		//Confirmar que la operacion fue realizada con exito y desplegar el formulario pre-llenado al usuario
		if($datos=mysql_fetch_array($rs)){
			$_SESSION['rfc'] = $datos['rfc'];?>				
			<fieldset class="borde_seccion" id="tabla-modificarC" name="form-datos-salida">
			<legend class="titulo_etiqueta"> Modificar  Cliente</legend>
			<br>
			<form onSubmit="return verContFormModificarCliente(this);" name="frm_modificarCliente" method="post" action="frm_modificarCliente2.php"  >
    		<table height="336" width="891" cellpadding="5" cellspacing="5" class="tabla_frm">
	    		<tr>
   	  	  			<td width="145"><div align="right">&iquest;No Facturable? <input type="checkbox" name="ckb_factura" id="ckb_factura" onclick="validarFacturable(this);" value="SI" title="Seleccionar para Indicar el Cliente No Es Facturable"/>*RFC</div></td>
       	  			<td width="250">
		  				<input name="txt_rfc" id="txt_rfc" type="text" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car', 3);"
                        value="<?php echo $datos["rfc"]; ?>" />
					</td>
       	  			<td width="110"><div align="right">Id Fiscal (Folio) </div></td>
       	  			<td width="200"><input type="text" name="txt_idFiscal" id="txt_idFiscal" size="10" maxlength="8" onkeypress="return permite(event,'num_car', 3);" 
                    	class="caja_de_texto" value="<?php echo $datos["id_fiscal"]; ?>"/>
                    </td>
				</tr>
   	    		<tr>
       	   			<td><div align="right">*Raz&oacute;n Social </div></td>
		           	<td>
                    	<input name="txt_razon" id="txt_razon" type="text" class="caja_de_texto" size="50" maxlength="80" onkeypress="return permite(event,'num_car',4);"
                    	value="<?php echo $datos["razon_social"]; ?>"/>
                    </td>
		           	<td><div align="right">C&oacute;digo Postal </div></td>
        		   	<td>
                    	<input type="text" name="txt_cp" id="txt_cp" size="5" maxlength="5" onkeypress="return permite(event,'num',3);" class="caja_de_texto"
                    	value="<?php echo $datos["cp"]; ?>"/>
                    </td>
   	    		</tr>
        		<tr>
           			<td><div align="right">Calle</div></td>
           			<td>
                    	<input name="txt_calle" id="txt_calle" type="text" class="caja_de_texto" size="30" maxlength="40" onkeypress="return permite(event,'num_car', 0);"
						value="<?php echo $datos["calle"]; ?>"/>
                    </td>
           			<td><div align="right">Estado</div></td>
           			<td>
                    	<input name="txt_estado" id="txt_estado" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" 
                        value="<?php echo $datos["estado"]; ?>"/>
                     </td>
       			</tr>
        		<tr>
           			<td><div align="right">N&uacute;mero Externo </div></td>
       	  			<td>
                    	<input name="txt_numeroExt" id="txt_numeroExt" type="text" class="caja_de_texto" size="6" maxlength="10" 
                        onkeypress="return permite(event,'num_car',1);" value="<?php echo $datos["numero_ext"]; ?>"/> 
           				N&uacute;mero Int.  
       	      			<input name="txt_numeroInt" id="txt_numeroInt" type="text" class="caja_de_texto" size="6" maxlength="10" 
                        onkeypress="return 	permite(event,'num_car',1);" value="<?php echo $datos["numero_int"]; ?>"/>
                    </td>
           			<td><div align="right">Tel&eacute;fono</div></td>
          			<td>
                    	<input name="txt_telefono" id="txt_telefono" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num', 3);"
                        onblur="validarTelefono(this);" value="<?php echo $datos["telefono"]; ?>"/></td>
       			</tr>
        		<tr>
           			<td><div align="right">Colonia </div></td>
           			<td>
                    	<input name="txt_colonia" id="txt_colonia" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',0);" 
                        value="<?php echo $datos["colonia"]; ?>"/>
                    </td>
		           	<td><div align="right">Tel&eacute;fono 2 </div></td>
        		   	<td>
                    	<input name="txt_telefono2" id="txt_telefono2" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num', 3);"
                        onblur="validarTelefono(this);" value="<?php echo $datos["telefono2"]; ?>"/>
                    </td>
        		</tr>
        		<tr>
           			<td><div align="right">Ciudad</div></td>
		           	<td>
                    	<input name="txt_ciudad" id="txt_ciudad" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);"
                        value="<?php echo $datos["ciudad"]; ?>"/>
                    </td>
           			<td><div align="right">Fax</div></td>
           			<td>
                    	<input name="txt_fax" id="txt_fax" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num', 3);" 
                        onblur="validarTelefono(this);" value="<?php echo $datos["fax"]; ?>"/>
                    </td>
       			 </tr>       	
				 <tr>
		  			<td><div align="right">Municipio</div></td>
		 			<td>
                    	<input name="txt_municipio" id="txt_municipio" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car', 3);" 
                        value="<?php echo $datos["municipio"]; ?>"/>
                    </td>
		  			<td><div align="right">Correo</div></td>
		  			<td>
                    	<input name="txt_correo" id="txt_correo" type="text" class="caja_de_texto" size="40" maxlength="40" onblur="validarCorreo(this);" 
                        value="<?php echo $datos["correo"]; ?>"/>
                    </td>
	 			 </tr>
				<tr>
		  			<td colspan="4"><strong>Modificar Datos del Contacto</u></strong></td>
				</tr>
				<tr>
					<td align="right">Apellido Paterno</td>
					<td>
                    	<input name="txt_apPat" id="txt_apPat" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car',3);" 
                    	value="<?php echo $datos["ap_contacto"]; ?>"/>
                    </td>
					<td align="right">Apellido Materno</td>
					<td>
                    	<input name="txt_apMat" id="txt_apMat" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car', 3);"
                        value="<?php echo $datos["am_contacto"]; ?>"/>
                    </td>
				</tr>
				<tr>
					<td align="right">Nombre(s)</td>
					<td>
                    	<input name="txt_nomContacto" id="txt_nomContacto" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car', 2);"
                    	value="<?php echo $datos["nom_contacto"]; ?>"/>
                    </td>
					<td align="right">CURP</td>
					<td>
                    	<input name="txt_curp" id="txt_curp" type="text" class="caja_de_texto" size="20" maxlength="18" onkeypress="return permite(event,'num_car', 3);" 
                        value="<?php echo $datos["curp_contacto"]; ?>"/>
                    </td>
				</tr>
				<tr>
					<td><div align="right">Referencia</div></td>
					<td>
                    	<textarea name="txa_referencia" id="txa_referencia" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30" 
                        onkeypress="return permite(event,'num_car', 0);" ><?php echo $datos["referencia"]; ?></textarea>
                     </td>
					<td><div align="right">Observaciones</div></td>
					<td>
                    	<textarea name="txa_observaciones" id="txa_observaciones" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
                        onkeypress="return permite(event,'num_car',0);" ><?php echo $datos["comentarios"]; ?></textarea></td>
				</tr>
				<tr>
		   			<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
		       	<tr>       		
        		   	<td colspan="4" align="center">
						<input name="btn_modificar" type="submit" class="botones" value="Modificar" title="Modificar la Informaci&oacute;n del Cliente" 
                        onmouseover="window.status='';return true;"/>
		           		&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer"  title="Restablecer Datos del Cliente Seleccionado" onclick="restablecerFormularioClientes();"/>
           				&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Cancelar Modificaci&oacute;n"
                        onclick="location.href='frm_modificarCliente.php'" />					</td>
		       	</tr>
		   	</table>
		   </form>
	</fieldset>	  
    <?php 
		}//Cierre de if($datos=mysql_fetch_array($rs))
		else{?>
			<div id="msg-resultado" align="center"> 
				<p class="msje_correcto">No se Encontr&oacute; Ning&uacute;n Cliente con el Nombre: <em><u><?php echo $txt_nombre; ?></u></em></p>
				<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar a la P&aacute;gina de Seleccionar Cliente para
                ser Modificar" onclick="location.href='frm_modificarCliente.php'"  />			
			</div>
		<?php }		
	}//Cierre de if(!isset($_SESSION['rfc']))
	else{		 				
		//Guardar los cambios realizados al material 			
		guardarCambios($txt_rfc,$txt_idFiscal,$txt_razon,$txt_calle,$txt_numeroExt,$txt_numeroInt,$txt_colonia,$txt_ciudad,$txt_municipio,$txt_estado,$txt_telefono,
		$txt_telefono2,$txt_fax,$txt_correo,$txa_observaciones,$txt_curp,$txt_nomContacto,$txt_apPat,$txt_apMat,$txa_referencia,$txt_cp);
	}?>	    
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
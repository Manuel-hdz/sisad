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
		//Manejo de la funciones para Registrar los datos de los Convenios en la BD 
		include("op_registrarConvenio.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
    <style type="text/css">
        <!--
		#titulo-barra { position:absolute; left:25px; top:146px; width:282px; height:21px; z-index:11; }
		#tabla { position:absolute; left:30px; top:190px; width:696px; height:265px; z-index:12;}
		#boton-cancelar { position:absolute; left:255px; top:393px; width:122px; height:37px;	z-index:13;	}
		#tabla-detallesconvenio{position:absolute;left:-5px;top:300px;width:696px;height:150px;z-index:12;overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<?php 
	if(!isset($_POST['txt_proveedor'])){
		if (isset($_POST["sbt_detConv"]))
			$txt_numero=0;
		//Verificar que en el POST este definido el boton de Guardar para guardar en el Arreglo de Session, por cuestiones de validacion, 
		//no puede ser ejecutado a menos que se haya completado cada campo
		if (isset($_POST["sbt_guardar"])){
			$txt_responsable=$hdn_resp;
			$txt_autoriza=$hdn_auto;
			$cmb_estado=$hdn_estado;
			$txa_comentarios=$hdn_coment;
			$txt_fechaInicio=$hdn_fechaI;
			$txt_fechaFin=$hdn_fechaF;
			$txt_fechaElaboracion=$hdn_fechaE;
			//Si ya esta definido el arreglo $detallesconvenio, entonces agregar el siguiente registro a el
			if(isset($_SESSION['detallesconvenio'])){	
				if(!verRegDuplicadoArr($_SESSION['detallesconvenio'],"numero",$txt_numero)){		
					//Guardar los datos en el arreglo
					$detallesconvenio[] = array("numero"=>$txt_numero, "mat_serv"=>strtoupper($txa_material), "cantidad"=>$txt_cantidad, 
					"unidad"=>strtoupper($txt_unidad), "precio"=>$txt_precio, "importe"=>$txt_importe);
				}
			}
			//Si no esta definido el arreglo $detallesconvenio definirlo y agregar el primer registro
			else{			
				//Guardar los datos en el arreglo
				$detallesconvenio = array(array("numero"=>$txt_numero, "mat_serv"=>strtoupper($txa_material), "cantidad"=>$txt_cantidad, 
				"unidad"=>strtoupper($txt_unidad), "precio"=>$txt_precio, "importe"=>$txt_importe));
				$_SESSION['detallesconvenio'] = $detallesconvenio;	
			}
			$txt_numero++;
		}//Cierre if (isset($_POST["sbt_guardar"]))?>
		
    <div id="titulo-barra"><span class="titulo_barra">Registrar Convenio/Detalles Convenio</span></div>
    <fieldset class="borde_seccion" id="tabla" name "tabla"> 
    <legend class="titulo_etiqueta">Registro de Convenio/Detalles Convenio</legend>
	<form name="frm_registrarConvenioDet" method="post" action="frm_registrarConvDetConv.php" onsubmit="return valFormDetalleConvenio(this);">
    <table  border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
        	<td width="68"><div align="right">Proveedor</div></td>
        	<td colspan="4">
            	<input name="txt_nombre" id="txt_nombre" value="<?php echo $txt_nombre ?>" type="text" class="caja_de_texto" size="80" 
				maxlength="80" readonly="true"/>
            </td>
       	</tr>
      	<tr>
        	<td><div align="right">Convenio</div></td>
        	<td width="135">
            	<input name="txt_convenio" id="txt_convenio" value="<?php echo $txt_convenio ?>" type="text" class="caja_de_texto" size="10" 
				maxlength="10" readonly="true"/>
            </td>
        	<td width="110">&nbsp;</td>
        	<td width="120"><div align="right">Material y/o Servicio</div></td>
        	<td width="180" rowspan="2"><textarea name="txa_material" onkeypress="return permite(event,'num_car', 0);"  id="txa_material" 
            	cols="30" rows="5" class="caja_de_texto"></textarea>
            </td>
   	  </tr>
      	<tr>
        	<td><div align="right">N&uacute;mero</div></td>
        	<td><input name="txt_numero" id="txt_numero" value="<?php if (isset($_POST["sbt_guardar"])) echo $txt_numero; else echo 1;?>" 
            type="text" class="caja_de_num" size="2" maxlength="2" readonly="true"/></td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
       	</tr>
      	<tr>
        	<td><div align="right">Unidad</div></td>
        	<td>
            	<input name="txt_unidad" type="text" id="txt_unidad" onkeypress="return permite(event,'num_car', 3);"  class="caja_de_texto" size="6" maxlength="10"/>
            </td>
        	<td>&nbsp;</td>
        	<td><div align="right">Precio Unitario </div></td>
        	<td>$
        	  <input name="txt_precio" type="text" onkeypress="return permite(event,'num', 2);"  id="txt_precio" class="caja_de_texto" size="15" maxlength="20" 
              onchange="formatCurrency(value.replace(/,/g,''),'txt_precio');formatCurrency(txt_precio.value.replace(/,/g,'')*txt_cantidad.value,'txt_importe');" />
           </td>
      	</tr>
      	<tr>
        	<td><div align="right">Cantidad</div></td>
        	<td>
            <input name="txt_cantidad" type="text" onkeypress="return permite(event,'num', 2);"  id="txt_cantidad" class="caja_de_texto" size="10" maxlength="20" 
            onchange="formatCurrency(txt_precio.value.replace(/,/g,'')*txt_cantidad.value,'txt_importe');" /></td>
        	<td>&nbsp;</td>
        	<td><div align="right">Importe</div></td>
        	<td>$
        	  <input name="txt_importe" type="text" class="caja_de_texto" id="txt_importe" size="15" maxlength="20" readonly="true"/></td>
      	</tr>
      	<tr>
        	<td>&nbsp;</td>
			<input type="hidden" name="hdn_resp" id="hdn_resp" value="<?php echo $txt_responsable ?>"/>
			<input type="hidden" name="hdn_auto" id="hdn_auto" value="<?php echo $txt_autoriza ?>"/>
			<input type="hidden" name="hdn_estado" id="hdn_estado" value="<?php echo $cmb_estado ?>"/>
			<input type="hidden" name="hdn_coment" id="hdn_coment" value="<?php echo $txa_comentarios ?>"/>
			<input type="hidden" name="hdn_fechaI" id="hdn_fechaI" value="<?php echo $txt_fechaInicio ?>"/>
			<input type="hidden" name="hdn_fechaF" id="hdn_fechaF" value="<?php echo $txt_fechaFin ?>"/>
			<input type="hidden" name="hdn_fechaE" id="hdn_fechaE" value="<?php echo $txt_fechaElaboracion ?>"/>
			
			<input type="hidden" name="hdn_boton" id="hdn_boton"/>
			
        	<td colspan="4">
            	<input name="sbt_guardar" id="sbt_guardar" type="submit" class="botones" value="Guardar" 
            	title="Guardar la Información de Detalles Convenio" onmouseover="window.status='';return true" onclick="hdn_boton.value='guardar';"/>
                &nbsp;&nbsp;&nbsp;&nbsp;
 	        	<input name="btn_cancelar" type="submit" value="Cancelar" class="botones" title="Regresar a la Página de Registrar Convenio" 
				onclick="document.frm_registrarConvenioDet.action='frm_registrarConvenio.php?cancela=si&btn=btn_cancelar';hdn_boton.value='cancelar';"
                onmouseover="window.status='';return true"/>&nbsp;&nbsp;&nbsp;&nbsp;
				<?php 
				if (isset($_SESSION["detallesconvenio"])){?>
					<input name="sbt_finalizar" type="submit" id="sbt_finalizar" class="botones" value="Finalizar" title="Registrar Detalles del Convenio"
                    onmouseover="window.status='';return true" 
					onclick="document.frm_registrarConvenioDet.action='frm_registrarConvenio.php?btn=sbt_finalizar';hdn_boton.value='finalizar';"/>
                    &nbsp;&nbsp;&nbsp;&nbsp;
				<?php }?>
			<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" 
			title="Limpiar los Campos que Contienen Información" onmouseover="window.status='';return true"/></td>
      	</tr>
	</table>
	
	<?php 
	if (isset($_SESSION["detallesconvenio"])){
		echo "<div id='tabla-detallesconvenio' class='borde_seccion2'>";
			mostrarConvenioDet($detallesconvenio);
		echo "</div>";
	}?>  	    
	</form>
	</fieldset>   
	
	<?php }//Cierre if(!isset($_POST['txt_proveedor']))?>
</body>	
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
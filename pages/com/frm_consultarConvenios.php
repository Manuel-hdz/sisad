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
		//Este archivo contiene las funciones para mostrar la informacion del proveedor que se esta consultando
		include ("op_consultarConvenio.php");
	
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:173px; height:25px; z-index:11; }
		#tabla-convenios {position:absolute;left:30px;top:190px;width:900px;height:140px;z-index:11;}
		#tabla-mostrarConvenioDetalle {position:absolute;left:30px;top:360px;width:900px;height:35%;z-index:11;overflow:scroll;}
		#botones { position:absolute; left:30px; top:660px; width:940px; height:25px; z-index:15;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Convenios</div>	
	<form action="frm_consultarConvenios.php" method="post" name="frm_verConvenios" onSubmit="return valFormconsultaConvenios(this);">
		<fieldset class="borde_seccion" id="tabla-convenios" name="tabla-registro">
		<legend class="titulo_etiqueta">Convenio <?php echo $_POST["cmb_convenios"]; ?></legend>
			<table class="tabla_frm" border="0" cellpadding="5" cellspacing="5">
				<tr>
					<td width="108" align="right">Proveedor</td>
					<td colspan="3">
                    	<input type="text" size="80" name="txt_razonSocial" id="txt_razonSocial" class="caja_de_texto" readonly="true" 
                        value="<?php echo obtenerDato("bd_compras","proveedores","razon_social", "rfc", $_POST["hdn_rfc"])?>" title="Campo Solo de Lectura"/></td>
					<td width="63" align="right">Convenios</td>
					<td width="99">
						<?php cargarComboEspecifico("cmb_convenios","id_convenio","convenios","bd_compras",$_POST["hdn_rfc"],
						"proveedores_rfc","Seleccionar...",$_POST["cmb_convenios"]);?></td>
					<td width="120">
                    	<input type="submit" class="botones" name="btn_Consultar" id="btn_Consultar" value="Consultar" 
                    	onMouseOver="window.status='';return true;" title="Consultar el Convenio Seleccionado"/>
                    </td>
					<input type="hidden" name="hdn_rfc" id="hdn_rfc" value="<?php echo $_POST["hdn_rfc"]?>"/>
					<input type="hidden" name="hdn_conv" id="hdn_conv" value="<?php echo $_POST["cmb_convenios"]?>"/>
				</tr>
				<tr>
					<td align="right">Fecha Elaboración</td>
					<td>
                    	<input name="txt_fechaElaboracion" type="text" class="caja_de_texto" id="txt_fechaElaboracion" title="Campo Solo de Lectura" 
                        value="<?php echo modFecha(obtenerDato("bd_compras","convenios","fecha_elaboracion", "id_convenio", $_POST["cmb_convenios"]),1)?>" size="10" maxlength="15" readonly="true"/>
                    </td>
			   		<td align="right">Estado</td>
			    	<td>
                    	<input type="text" name="txt_estado" id="txt_estado" class="caja_de_texto" readonly="true" 
                        value="<?php echo obtenerDato("bd_compras","convenios","estado", "id_convenio", $_POST["cmb_convenios"])?>" title="Campo Solo de Lectura"/>
                    </td>
					<td>Comentarios</td>
					<td colspan="2" rowspan="2" valign="top">
						<textarea name="txa_comentarios" onkeypress="return permite(event,'num_car', 0);" 
                             id="txa_comentarios" cols="30" rows="3" maxlength="120" class="caja_de_texto" readonly="readonly" title="Campo Solo de Lectura"><?php echo 
                             obtenerDato("bd_compras","convenios","comentarios","id_convenio",$_POST["cmb_convenios"])?></textarea>
					</td>
				</tr>
				<tr>
					<td align="right">Fecha Inicio</td>
			  		<td width="159">
                    	<input name="txt_fechaInicio" type="text" class="caja_de_texto" id="txt_fechaInicio" title="Campo Solo de Lectura" 
                        value="<?php echo modFecha(obtenerDato("bd_compras","convenios","fecha_inicio", "id_convenio", $_POST["cmb_convenios"]),1)?>" size="10" maxlength="15" readonly="true"/>
                    </td>
					<td width="96" align="right">Fecha Fin</td>
			 		<td width="142">
                    	<input name="txt_fechaFin" type="text" class="caja_de_texto" id="txt_fechaFin" title="Campo Solo de Lectura" 
                   		value="<?php echo modFecha(obtenerDato("bd_compras","convenios","fecha_fin", "id_convenio", $_POST["cmb_convenios"]),1)?>" size="10" maxlength="15" readonly="true"/></td>
					<td align="right">&nbsp;</td>
				</tr>
			</table>
		</fieldset>
	
	<div id="tabla-mostrarConvenioDetalle" class="borde_seccion2">
	<?php mostrarConvenioDetalle()?>
	</div>
	
	<div id="botones">
		<table width="100%" align="center">
			<tr>
				<td colspan="4" align="center">
                    <input type="button" class="botones_largos" name="btn_Modificar" id="btn_Modificar" value="Modificar T&eacute;rminos" title="Modificar Terminos del Convenio"
                    onclick="document.frm_verConvenios.action='frm_modificarProvTerminCon.php';document.frm_verConvenios.submit();"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="button" class="botones_largos" name="btn_Eliminar" id="btn_Eliminar" value="Eliminar Convenio" title="Eliminar Convenio Completo" 
                    onclick="document.frm_verConvenios.action='op_eliminarConvenio.php';document.frm_verConvenios.submit();"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="button" class="botones" name="btn_Cancelar" id="btn_Cancelar" value="Cancelar" onclick="location.href='frm_consultarProveedor.php'" 
                    title="Regresar a la pantalla de Consultar Proveedor"/>
				</td>
			</tr>
		</table>
	</div>
	</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
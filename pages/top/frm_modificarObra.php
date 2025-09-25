<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarObra.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" language="javascript">
		//Colocar el foco en el Combo de Categoria de Precios al cargar la pagina
		setTimeout("document.getElementById('cmb_idPrecios').focus();document.getElementById('cmb_idPrecios').tabIndex=1;",500);
	</script>

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-modificarObra {position:absolute;left:30px;top:190px;width:763px;height:383px;z-index:12;}
		#calendarioObra {position:absolute;left:694px;top:233px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>	
	
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Obra</div>
		
	<fieldset class="borde_seccion" id="tabla-modificarObra" name="tabla-modificarObra">
	<legend class="titulo_etiqueta">Modificar Informaci&oacute;n de la Obra</legend>	
	<br>
	<form onSubmit="return valFormModificarObra(this);" name="frm_modificarObra" method="post" action="frm_modificarObra.php">
	<table cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  <td width="124"><div align="right">Id Obra </div></td>
		  <td width="234"><input name="txt_idObra" id="txt_idObra" type="text" class="caja_de_texto" size="10" maxlength="10" value="<?php echo $txt_idObra;?>"  readonly="readonly"/></td>
		  <td width="144"><div align="right">Fecha Registro </div></td>
		  <td width="194"><input name="txt_fechaRegistro" id="txt_fechaRegistro" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly" value="<?php echo $txt_fechaRegistro; ?>"/></td>
		</tr>
		<tr>
			<td><div align="right">*Categor&iacute;a de Precios </div></td>
			<td width="234">
				<select name="cmb_idPrecios" id="cmb_idPrecios" class="combo_box">
					<option value="">Precios</option>
					<?php 
					$conn = conecta("bd_topografia");//Conectarse con la BD de Topografía
					//Ejecutar la Sentencia para Obtener los tipos de Obra registrados en la BD de Topografía
					$rs_precios = mysql_query("SELECT DISTINCT tipo,id_precios FROM precios_traspaleo ORDER BY tipo");
					if($precios=mysql_fetch_array($rs_precios)){
						//Colocar los lugares encontrados
						do{
							if($cmb_idPrecios==$precios["id_precios"])
								echo "<option value='$precios[id_precios]' selected='selected'>$precios[tipo]</option>";
							else
								echo "<option value='$precios[id_precios]'>$precios[tipo]</option>";
						}while($precios=mysql_fetch_array($rs_precios));
					}
					mysql_close($conn);
					$atrib="";
					if($cmb_idPrecios=="N/A")
						$atrib=" selected='selected'";
					?>
					<option value="N/A" title="Esta Opci&oacute;n Aplica para Obras de Desborde y Anclas"<?php echo $atrib;?>>NO APLICA</option>
				</select>
			</td>
		  	<td><div align="right">*Categor&iacute;a</div>
	  	  <td width="194"><select name="cmb_categoria" title="cmb_categoria" class="combo_box" tabindex="3">
              <option value="">Categor&iacute;a</option>
              <option value="COSTOS" <?php if($cmb_categoria=="COSTOS"){?> selected="selected" <?php } ?>>COSTOS</option>
              <option value="AMORTIZABLE" <?php if($cmb_categoria=="AMORTIZABLE"){?> selected="selected" <?php } ?>>AMORTIZABLE</option>
            </select></td>
		</tr>
		<tr>
			<td><div align="right">*Tipo de Obra</div></td>
			<td><select name="cmb_tipoObra" id="cmb_tipoObra" class="combo_box" onchange="agregarNvoTipoObra(this);" tabindex="5">
              <option value="">Tipo Obra</option>
			  <?php 
					$conn = conecta("bd_topografia");//Conectarse con la BD de Topograf&iacute;a
					//Ejecutar la Sentencia para Obtener los tipos de Obra registrados en la BD de Topograf&iacute;a
					$rs_tipos = mysql_query("SELECT DISTINCT tipo_obra FROM obras ORDER BY tipo_obra");
					if($tiposObra=mysql_fetch_array($rs_tipos)){
						//Colocar los lugares encontrados
						do{
							if($tiposObra['tipo_obra']==$_POST['cmb_tipoObra'])
								echo "<option value='$tiposObra[tipo_obra]' selected='selected'>$tiposObra[tipo_obra]</option>";							
							else
								echo "<option value='$tiposObra[tipo_obra]'>$tiposObra[tipo_obra]</option>";							
						}while($tiposObra=mysql_fetch_array($rs_tipos));
					}					
					mysql_close($conn);?>
              <option value="NUEVA">Agregar Nuevo</option>
            </select></td>
			<td><div align="right">*Subtipo</div></td>
			<td><select name="cmb_subtipo" id="cmb_subtipo" class="combo_box" tabindex="4">
              <option value="">Subtipo</option>
              <?php 
					$conn = conecta("bd_topografia");//Conectarse con la BD de Topograf&iacute;a
					//Ejecutar la Sentencia para Obtener los tipos de Obra registrados en la BD de Topograf&iacute;a
					$rs_subtipos = mysql_query("SELECT DISTINCT id,subcategoria FROM subcategorias WHERE id>0 ORDER BY orden");
					if($subtiposObra=mysql_fetch_array($rs_subtipos)){
						//Colocar los lugares encontrados
						do{
							if($txt_subCategoria==$subtiposObra["id"])
								echo "<option value='$subtiposObra[id]' selected='selected'>$subtiposObra[subcategoria]</option>";
							else
								echo "<option value='$subtiposObra[id]'>$subtiposObra[subcategoria]</option>";
						}while($subtiposObra=mysql_fetch_array($rs_subtipos));
					}					
					mysql_close($conn);?>
            </select></td>
		</tr>
		<tr>
			<td><div align="right">*Precio Unitario M.N. Estimaci&oacute;n</div></td>
		  <td>$
          <input name="txt_precioEstimacionMN" id="txt_precioEstimacionMN" type="text" class="caja_de_texto" onkeypress="return permite(event,'num',2);" 
				onchange="formatCurrency(value,'txt_precioEstimacionMN')" value="<?php echo number_format($txt_precioEstimacionMN,2,".",","); ?>" tabindex="4" /></td>
			<td><div align="right">*Precio Unitario USD Estimaci&oacute;n</div></td>
			<td>$
		    	<input name="txt_precioEstimacionUSD" id="txt_precioEstimacionUSD" type="text" class="caja_de_texto" onkeypress="return permite(event,'num',2);" 
				onchange="formatCurrency(value,'txt_precioEstimacionUSD')" value="<?php echo number_format($txt_precioEstimacionUSD,2,".",","); ?>" tabindex="6" />			</td>
		</tr>
		<tr>
			<td valign="top"><div align="right">Secci&oacute;n</div></td>
			<td>
				<?php if($txt_seccion=="N/A") $atrib=" readonly='readonly'"; else $atrib="";?>
				<input name="txt_seccion" id="txt_seccion" type="text" class="caja_de_texto" size="10" maxlength="10" value="<?php echo $txt_seccion ?>" 
            	onchange="calcularArea();" onkeypress="return permite(event,'num',6);" tabindex="9"<?php echo $atrib;?>/>
			</td>
			<td><div align="right">&Aacute;rea </div></td>
			<td><input name="txt_area" id="txt_area" type="text" class="caja_de_texto" value="<?php echo $txt_area; ?>" readonly="readonly" tabindex="8" /></td>
		</tr>
		<tr>
		  <td><div align="right">*Unidad</div></td>
		  <td><input name="txt_unidad" id="txt_unidad" type="text" class="caja_de_texto" onkeypress="return permite(event,'num_car',2);" value="<?php echo $txt_unidad; ?>" tabindex="2" /></td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
	  </tr>
		<tr>
			<td><div align="right">*Nombre de la Obra</div></td>
		  <td colspan="3"><input name="txt_nombreObra" id="txt_nombreObra" type="text" class="caja_de_texto" size="40" maxlength="40" onkeypress="return permite(event,'num_car',0);" 
				value="<?php echo $cmb_nomObra ?>" onchange="verificarDatoBD(this,'bd_topografia','obras','nombre_obra','id_obra');" tabindex="7" />
                <span id="error" class="msj_error">Nombre Ya Registrado</span>			</td>
		</tr>
		<tr>
			<td colspan="4"><div align="left"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></div></td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
				<input name="sbt_modificarObra" type="submit" class="botones" id="sbt_modificarObra"  value="Modificar" 
                title="Modificar la Información de la Obra Seleccionada" 
				onmouseover="window.status='';return true" tabindex="10" />
				&nbsp;&nbsp;&nbsp;
				<input name="rst_restablecer" type="reset" class="botones" id="rst_restablecer" value="Restablecer" title="Restablecer los datos de la Obra Seleccionada" tabindex="11" 
				onclick="error.style.visibility='hidden';" />
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Obra " 
				onmouseover="window.status='';return true" onclick="confirmarSalida('frm_modificarNombreObra.php');" tabindex="12" />			</td>
		</tr>
	</table>
	</form>
</fieldset>		
	
	<?php //Calendario  para la fecha de Registro de la Obra ?>
	<div id="calendarioObra">
		<input type="image" name="txt_fechaRegistro" id="txt_fechaRegistro" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_modificarObra.txt_fechaRegistro,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
		title="Seleccionar la Fecha de Registro de la Obra"/> 
</div>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
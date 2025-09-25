<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_agregarDocumento.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionAseguramiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarDocumento {position:absolute;left:30px;top:190px;width:950px;height:309px;z-index:12;}
		#calendario{position:absolute;left:550px;top:235px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Agregar Documentos </div>
	<fieldset class="borde_seccion" id="tabla-agregarDocumento" name="tabla-agregarDocumento">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Documento </legend>	
	<br>
	
	<form onsubmit="return valFormRegDocumentos(this);" name="frm_agregarDocumento" method="post" action="frm_agregarDocumento.php"   enctype="multipart/form-data">
	  <table width="953" height="271"  cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
          <td>*Id  Documento </td>
          <td width="224">
		  	<input name="txt_idDocumento" id="txt_idDocumento" type="text" class="caja_de_texto" size="15" maxlength="15" value="" 
			onchange="validarCaracteres(this);" onblur="return verificarDatoBD(this,'bd_aseguramiento','repositorio_documentos','id_documento','nombre');" onkeypress="return permite(event,'num_car', 1);"/>
            <span id='error' class="msj_error">Clave Duplicada</span>
		</td>
          <td width="38"><div align="right">Fecha</div></td>
          <td width="77"><input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
          <td width="163"><div align="right">*Nombre </div></td>
          <td width="252"><input name="txt_nomDoc" id="txt_nomDoc" type="text" class="caja_de_texto" size="40" maxlength="80" 
				onkeypress="return permite(event,'num_car',6);"/>
          </td>
        </tr>
        <tr>
          <td width="102"><div align="right">Norma</div></td>
          <td colspan="3"><?php  
			$cmb_norma="";
			$conn = conecta("bd_aseguramiento");
			$result=mysql_query("SELECT DISTINCT norma FROM catalogo_norma ORDER BY norma");
			if($norma=mysql_fetch_array($result)){?>
              <select name="cmb_norma" id="cmb_norma" size="1" class="combo_box"  onchange="desactivarCombo();">
                <option value="">Norma</option>
                <?php 
				  do{
						if ($norma['norma'] == $cmb_norma){
							echo "<option value='$norma[norma]' selected='selected'>$norma[norma]</option>";
						}
						else{
							echo "<option value='$norma[norma]'>$norma[norma]</option>";
						}
					}while($norma=mysql_fetch_array($result)); 
				//Cerrar la conexion con la BD		
				mysql_close($conn);?>
              </select>
              <?php }
				else{
					echo "<label class='msje_correcto'> No hay Normas Registradas</label>
					<input type='hidden' name='cmb_norma' id='cmb_norma'/>";?>
              <input type="hidden" name="hdn_normaDefinida" id="hdn_normaDefinida" value=""/>
              <?php }?>
          </td>
          <td><div align="right">
              <input type="checkbox" name="ckb_norma" id="ckb_norma" 
				onclick="agregarNuevaNorma(this, 'txt_norma', 'cmb_norma'); " 
				title="Seleccione para Escribir el Nombre de una Norma que no Exista" />
            Agregar Norma </div></td>
          <td><input name="txt_norma" id="txt_norma" type="text" class="caja_de_texto" size="40" readonly="readonly"/></td>
        </tr>
        <tr>
          <td width="102"><div align="right">Clasificaci&oacute;n</div></td>
          <td colspan="3"><?php  
				$cmb_clasificacion="";
				$conn = conecta("bd_aseguramiento");
				$result=mysql_query("SELECT DISTINCT clasificacion FROM catalogo_clasificacion ORDER BY clasificacion");
				if($clasificacion=mysql_fetch_array($result)){?>
              <select name="cmb_clasificacion" id="cmb_clasificacion" size="1" class="combo_box" onchange="desactivarCombo();">
                <option value="">Clasificaci&oacute;n</option>
                <?php 
					  do{
							if ($clasificacion['clasificacion'] == $cmb_clasificacion){
								echo "<option value='$clasificacion[clasificacion]' selected='selected'>$clasificacion[clasificacion]</option>";
							}
							else{
								echo "<option value='$clasificacion[clasificacion]'>$clasificacion[clasificacion]</option>";
							}
						}while($clasificacion=mysql_fetch_array($result)); 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
              </select>
              <?php }
				else{
					echo "<label class='msje_correcto'> No hay Clasificaciones Registradas</label>
					<input type='hidden' name='cmb_clasificacion' id='cmb_clasificacion'/>";
				 ?>
              <input type="hidden" name="hdn_clafisificacionDefinida" id="hdn_clafisificacionDefinida" value=""/>
              <?php }?>
          </td>
          <td><div align="right">
              <input type="checkbox" name="ckb_clasificacion" id="ckb_clasificacion" 
					onclick="agregarNuevaClasificacion(this, 'txt_clasificacion', 'cmb_clasificacion'); " 
					title="Seleccione el Nombre de una Clasificaci&oacute;n que no Exista" />
            Agregar Clasificación </div></td>
          <td><input name="txt_clasificacion" id="txt_clasificacion" type="text" class="caja_de_texto" size="40" readonly="readonly"/></td>
        </tr>
        <tr>
          <td><div align="right">*Documento</div></td>
          <td colspan="3"><input type="file" name="file_documento" id="file_documento" size="36" value=""/></td>
          <td><div align="right">Descripci&oacute;n</div></td>
          <td><textarea name="txa_descripcion" id="txa_descripcion" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea>
          </td>
        </tr>
        <tr>
          <td colspan="5"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>
        <tr>
        	<td colspan="6"><div align="center">
            	<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Documentos"
				onmouseover="window.status='';return true"/>
            	&nbsp;&nbsp;&nbsp;
            	<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
            	&nbsp;&nbsp;&nbsp;
            	<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Repositorio" 
				onmouseover="window.status='';return true"  onclick="confirmarSalida('menu_repositorio.php')" />
				<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/>
          </div></td>
        </tr>
      </table>
	</form>
</fieldset>
<div id="calendario">
<input name="calendario" type="image" id="calendario2" onclick="displayCalendar(document.frm_agregarDocumento.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" /></div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
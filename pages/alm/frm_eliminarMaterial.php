<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){			
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Este archivo maneja las opciones para buscar material y despues eliminarlo del catalogo de Almacen
		include ("op_eliminarMaterial.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"  />
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js" ></script>
    <style type="text/css">
		<!--
		#titulo-eliminar { position:absolute; left:30px; top:146px; width:164px; height:18px; z-index:11; }
		#tabla-eliminar { position:absolute; left:30px;	top:190px;	width:591px; height:200px; z-index:13; }
		#tabla-eliminarXClave { position:absolute; left:675px;	top:190px;	width:277px; height:200px; z-index:13; }
		#mostrar-resultado { position:absolute; left:30px; top:190px; width:940px; height:390px; z-index:12; overflow:scroll; }
		#buscar-eliminar { position:absolute; left:30px; top:440px;	width:920px; height:107px; z-index:14; }
		#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>	
	<div class="titulo_barra" id="titulo-eliminar">Eliminar Material </div><?php 
	
	//Si no estan definidas las variables $hdn_clave, $cmb_datoBuscar y $rdb_clave mostrar los formularios para seleccionar el material a eleminar y el formulario para buscar
	if(!isset($_POST['hdn_clave']) && !isset($_POST['cmb_datoBuscar']) && !isset($_POST['rdb_clave']) ){ ?>
		<fieldset class="borde_seccion" id="tabla-eliminar" name="tabla-eliminar">
		<legend class="titulo_etiqueta">Seleccionar Material a Eliminar</legend>
		<form name="frm_cargarInfoCombos" method="post" action="">
		<table width="401" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="70"><div align="right">Categor&iacute;a</div></td>
				<td width="241" colspan="2"><?php $aux=1;
					//Evitar que la variable $cmb_categoria marque un error por no estar definida			
					if(!isset($_POST['cmb_categoria'])) $cmb_categoria = "";
					$conn = conecta("bd_almacen");
					$rs = mysql_query("SELECT DISTINCT linea_articulo FROM materiales WHERE grupo!='PLANTA' ORDER BY linea_articulo");
					if($row=mysql_fetch_array($rs)){?>            
						<select name="cmb_categoria" id="cmb_categoria" size="1" onChange="javascript:document.frm_cargarInfoCombos.submit();" class="combo_box">
							<option value="">Categor&iacute;a</option><?php 
							do{
								if ($row['linea_articulo'] == $cmb_categoria){
									echo "<option value='$row[linea_articulo]' selected='selected'>$row[linea_articulo]</option>";
								}
								else{
									echo "<option value='$row[linea_articulo]'>$row[linea_articulo]</option>";
								}
							}while($row=mysql_fetch_array($rs));?>
						</select><?php
					}
					else {?>
						<label class="msje_correcto"><u><strong>NO</strong></u> Hay Categor&iacute;as Registradas</label>
					<?php $aux=0; } ?>
				</td>
			</tr>
			<tr>
				<td width="70"><div align="right">Material</div></td>
				<td width="241"><?php if($aux==1){?>
					<select name="cmb_material" size="1" onChange="javascript:document.frm_cargarInfoCombos.submit();" class="combo_box">
					<option value="" selected="selected">Material</option>
					<?php 
						//Evitar que la variable $cmb_categoria marque un error por no estar definida			
						if(!isset($_POST['cmb_material'])) $cmb_material = "";
						$result1 = mysql_query("SELECT id_material,nom_material FROM materiales WHERE linea_articulo='$cmb_categoria'");		
						$band = 0;
						while ($row1=mysql_fetch_array($result1)){
							if ($row1['id_material'] == $cmb_material){
								echo "<option value='$row1[id_material]' selected='selected' title='$row1[id_material]'>$row1[nom_material]</option>";							
								$band = 1;
							}else{
								echo "<option value='$row1[id_material]' title='$row1[id_material]'>$row1[nom_material]</option>";							
							}
						}
						}
						else{
							echo "<label class='msje_correcto'><u><strong>NO</strong></u> Hay Materiales Registrados</label>"; 
						}?>
					</select>
					<input type="hidden" name="band" value="<?php echo $band; ?>"  /><?php
					//Cerrar la conexion con la BD		
					mysql_close($conn); ?>
			  </td>
			</tr>
		</table>
		</form> 
	
	 
		<form onSubmit="return valFormEliminar(this);" name="frm_eliminar" method="post" action="frm_eliminarMaterial.php">  
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="70"><div align="right">Clave</div></td>
				<td width="125">
					<input name="txt_clave" type="text" class="caja_de_texto" size="10" maxlength="10" disabled="disabled" 
					value="<?php if(isset($_POST['cmb_material'])){ if($cmb_material!="" && $band==1){ echo $cmb_material; } else{ echo "";} } ?>" />
					<input type="hidden" name="hdn_clave" 
					value="<?php if(isset($_POST['cmb_material'])){ if($cmb_material!="" && $band==1){ echo $cmb_material; } else{ echo "";} } ?>" />				
				</td>
				<td width="130">
					<input name="sbt_eliminar" type="submit" class="botones" value="Eliminar" title="Solo el material con existencia 0 podr&aacute; ser Eliminado" 
					onMouseOver="window.status='';return true" />
				</td>
			</tr>
			<tr>
				<td><div align="right">Existencia</div></td>
				<td>
					<input name="txt_existencia" type="text" class="caja_de_num" size="10" maxlength="10" disabled="disabled"
					value="<?php if(isset($_POST['cmb_material'])){ if($cmb_material!="" && $band==1){ echo obtenerDato("bd_almacen","materiales", "existencia", "id_material", $cmb_material); } else{ echo "";} } ?>" />
					<input type="hidden" name="hdn_existencia" 
					value="<?php if(isset($_POST['cmb_material'])){ if($cmb_material!="" && $band==1){ echo obtenerDato("bd_almacen","materiales", "existencia", "id_material", $cmb_material); } else{ echo "";} } ?>" />		  
				</td>
				<td><input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Materiales" onClick="location.href='menu_material.php'" /></td>
			</tr>
		</table>      
		</form>		
		</fieldset>
	
		<fieldset class="borde_seccion" id="tabla-eliminarXClave" name="tabla-eliminarXClave">
		<legend class="titulo_etiqueta">Eliminar Material Por Clave </legend><?php
	
		//Si no se ha presionado el boton, no se muestra ningun mensaje
		if(!isset($_POST["sbt_enviarClave"]))
			$msj="";
			
		else{
			$msj="";
			$grupo=obtenerDato("bd_almacen","materiales", "grupo", "id_material", $txt_clave);
			//Obtenemos la existencia del material con la clave escrita
			$existencia=obtenerDato("bd_almacen","materiales", "existencia", "id_material", $txt_clave);
			//Obtenemos el nombre del material de la clave proporcionada
			$material=obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $txt_clave);
			//Convertimos la clave en mayuscula
			$txt_clave=strtoupper($txt_clave);
			//Si el grupo es igual a planta el material no puede ser eliminado
			if ($grupo=="PLANTA")
				$msj="Material Administrado por Gerencia T&eacute;cnica. No se Puede Eliminar";				
			//Si la existencia del material es igual a 0 y diferente de vacio, el material se puede eliminar
			if ($existencia==0&&$existencia!=""&&$msj=="")				
				eliminarMaterial($txt_clave);				
				
			//Si la existencia arroja vacio, el material no existe en la Base de Datos
			if ($existencia=="")
				$msj="No existe Material Registrado con la clave $txt_clave";
			//Si la existencia es mayor a 0, el material no puede ser eliminado
			if ($existencia>0)
				$msj="El Material $material de clave $txt_clave, tiene existencia de $existencia, NO se puede eliminar";
		}?>
					
			
		<form name="frm_eliminarXClave" onsubmit="return valFormEliminarXClave(this);" action="" method="post">
		<br />
		<table class="tabla_frm" width="100%">
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td colspan="2" align="center">
					Clave
					&nbsp;&nbsp;
					<input type="text" name="txt_clave" id="txt_clave" size="10" maxlength="10" 
					onblur="obtenerDatoBD(this.value,'bd_almacen','materiales','nom_material','id_material','hdn_nomMaterial')" />
					<input type="hidden" name="hdn_nomMaterial" id="hdn_nomMaterial" value="" />
				</td>
			</tr>
			<tr><td colspan="2"><span class="msje_correcto" id="mensaje"><?php echo $msj;?></span></td></tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="sbt_enviarClave" class="botones" value="Eliminar" title="Solo el material con existencia 0 podr&aacute; ser Eliminado"
					onmouseover="window.status=''; return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_limpiar" class="botones" value="Limpiar" title="Limpiar el Formulario" onclick="mensaje.style.visibility = 'hidden';"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<fieldset class="borde_seccion" id="buscar-eliminar" name="buscar-eliminar">
		<legend class="titulo_etiqueta">Buscar Material a Eliminar</legend>	
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="160"><div align="left">Buscar por </div></td>
				<td width="180"><div align="left">Dato a buscar </div></td>
				<td width="130">&nbsp;</td>
			</tr>
			<tr>
				<form name="frm_cargarInfo" method="post" action="">
				<td>
					<div align="left">
					<select name="cmb_param" size="1" onChange="javascript:document.frm_cargarInfo.submit();" class="combo_box">
						<option value="">Par&aacute;metro</option>															
						<option value="fecha_alta" <?php if(isset($_POST['cmb_param'])){ if($cmb_param=="fecha_alta"){ echo "selected='selected'"; } }?>>Fecha Alta</option>					
						<option value="grupo" <?php if(isset($_POST['cmb_param'])){ if($cmb_param=="grupo"){ echo "selected='selected'"; } }?>>Grupo</option>					
						<option value="proveedor" <?php if(isset($_POST['cmb_param'])){ if($cmb_param=="proveedor"){ echo "selected='selected'"; } }?>>Proveedor</option>					
						<option value="unidad_medida" <?php if(isset($_POST['cmb_param'])){ if($cmb_param=="unidad_medida"){ echo "selected='selected'"; } }?>>Unidad de Medida</option>
					</select>
					</div>
				</td>
				</form>
				<form onsubmit="return valFormBuscar(this);" name="frm_buscar" method="post" action="frm_eliminarMaterial.php">
				<td><?php $result="";?>
					<div align="left">
					<input type="hidden" name="hdn_param" value="<?php if(isset($_POST['cmb_param'])) echo $cmb_param; ?>"  /><?php 
						if(isset($_POST['cmb_param']) && $cmb_param!=""){ 
							$tabla = "materiales";
							if($cmb_param=="unidad_medida"){
								$tabla = "unidad_medida";
								$result=cargarCombo("cmb_datoBuscar","$cmb_param",$tabla,"bd_almacen","Opci&oacute;n",""); 						
							}
							else {
								$result=cargarComboExcluyente("cmb_datoBuscar","$cmb_param",$tabla,"bd_almacen","PLANTA","grupo","Opci&oacute;n","");
							}
							if($result==0){
								echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Materiales Para Borrar</label>";	
							}						
						}
						else
							echo "<div align='left'>Seleccionar un Par&aacute;metro</div>";?>
					</div>
				</td>
				<td><?php if($result==1){?>
					<div align="left">
					<input name="sbt_buscar" type="submit" class="botones" value="Buscar" title="Buscar Material" onMouseOver="window.status='';return true"/></div>
					<?php }?>
				</td>
				</form>
			</tr>
		</table>	
		</fieldset><?php 
		
	}//Cierre del if(!isset($_GET['hdn_clave']) && isset($_GET['txt_datoBuscar'])) 
	else{ 
		//Si esta deinida la variable $hdn_clave procedemos a elminar el material seleccionado
		if(isset($_POST['hdn_clave']))
			eliminarMaterial($hdn_clave);
		
		
		//Si esta definida la variable $cmb_datoBuscar procedemos a realizar la búsqueda de materiales
		if(isset($_POST['cmb_datoBuscar'])){
			?><div id="mostrar-resultado" align="center" class="borde_seccion2"><?php													
			buscarMaterial($hdn_param, $cmb_datoBuscar);						
		}


		//Si esta definida la variable $rdb_clave procedemos a elminar el material seleccionado desde la ventana de resultados
		if(isset($_POST['rdb_clave']))
			eliminarMaterial($rdb_clave);
	} ?>		
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
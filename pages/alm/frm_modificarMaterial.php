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
		//Este archivo contiene las operaciones para mostrar el detalle del material o categoria de materiales seleccionadas
		include ("op_modificarMaterial.php");

?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarCambioDato.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
	
    <style type="text/css">
		<!--		
		#tabla-modificar { position:absolute; left:30px; top:190px; width:599px; height:210px; z-index:13; }
		#tabla-modificarClave { position:absolute; left:680px; top:190px; width:278px; height:210px; z-index:13; }
		#titulo-modificar { position:absolute; left:30px; top:146px; width:164px; height:23px; z-index:11; }
		#form-modificar { position:absolute;left:30px;top:180px;width:940px;height:515px;z-index:12; overflow:auto; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Modificar Material </div>
	
<?php //Si las variables $cmb_material y $txt_clave no estan definidas mostrar los formularios para seleccionar el material a modificar
	if(!isset($_POST['cmb_material']) && !isset($_POST['txt_clave']) && !isset($_GET['cve'])){ ?>		
	
	<fieldset id="tabla-modificar" class="borde_seccion">	
	<legend class="titulo_etiqueta">Modificar Material por Art&iacute;culo</legend>
	<br>	
	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<form name="frm_cargarInfoCombos" method="post" action="">
		<tr>
	  	  	<td width="100"><div align="right">Categor&iacute;a</div></td>
			<td width="250"><?php
				//Evitar que la variable $cmb_categoria marque un error por no estar definida			
				if(!isset($_POST['cmb_categoria'])) $cmb_categoria = "";
				$conn = conecta("bd_almacen");
				$rs = mysql_query("SELECT DISTINCT linea_articulo FROM materiales WHERE grupo!='PLANTA' ORDER BY linea_articulo");
				if($row=mysql_fetch_array($rs)){?>            
                    <select name="cmb_categoria" size="1" onChange="javascript:document.frm_cargarInfoCombos.submit();" class="combo_box">
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
					<label class="msje_correcto"><u><strong>NO</strong></u> Hay Categor&iacute;as Para Modificar</label>
					<input type='hidden' name='cmb_categoria' id='cmb_categoria'/>
                <?php } ?>
                    
			</td>			
		</tr>
		</form>
		
		<form onSubmit="return valFormElegirParams(this);" name="frm_elegirParams" method="post" action="frm_modificarMaterial.php" >
		<tr>
			<td><div align="right">Material</div></td>
			<td><?php $cmb_material="";?>
			<select name="cmb_material" size="1" class="combo_box" id="cmb_material">
				<option value="" selected="selected">Material</option>
				<?php 
				$result1 = mysql_query("SELECT id_material,nom_material FROM materiales WHERE linea_articulo='$cmb_categoria' ORDER BY nom_material");		
				while ($row1=mysql_fetch_array($result1))
					echo "<option value='$row1[id_material]' title='$row1[id_material]'>$row1[nom_material]</option>";											
				?>
			</select>
			<?php		
			//Cerrar la conexion con la BD		
			mysql_close($conn); ?>			
			</td>
		</tr>
		<tr>			
			<td><input type="hidden" name="hdn_categoria" value="<?php if(isset($_POST['cmb_categoria'])) echo $cmb_categoria; ?>"  /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2">
		  		<div align="center">
		  	    	<input name="sbt_modificar" type="submit" class="botones" value="Modificar" title="Modificar Material Seleccionado" onMouseOver="window.status='';return true" />  	          	
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Materiales" onClick="location.href='menu_material.php'" />
				</div>
			</td>
		</tr>
		</form>	      
	</table>		
</fieldset>
	
	<fieldset id="tabla-modificarClave" class="borde_seccion">	
	<legend class="titulo_etiqueta">Modificar Material por Clave </legend>
	<?php
	//Si no se ha presionado el boton, no se muestra ningun mensaje
		if(!isset($_POST["sbt_enviarClave"]))
			$msj="";
		else{
			$grupo=obtenerDato("bd_almacen","materiales", "grupo", "id_material", $txt_claveMod);
			//Obtenemos la existencia del material con la clave escrita
			$existencia=obtenerDato("bd_almacen","materiales", "existencia", "id_material", $txt_claveMod);
			//Obtenemos el nombre del material de la clave proporcionada
			$material=obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $txt_claveMod);
			//Convertimos la clave en mayuscula
			$txt_claveMod=strtoupper($txt_claveMod);
			//Si el grupo es igual a planta el material no puede ser modificado
			if($grupo=="PLANTA")
				$msj="Material Administrado por Gerencia T&eacute;cnica. No se Puede Modificar";				
			//Si la existencia del material es igual a 0 y diferente de vacio, el material se puede modificar
			if ($existencia!="" && $grupo!="PLANTA"){
				echo "<meta http-equiv='refresh' content='0;url=frm_modificarMaterial.php?cve=$txt_claveMod'>";
				$msj="";
			}
			if($existencia==""||$material=="")
				$msj="No existe Material Registrado con la clave $txt_claveMod";
		}
	?>
	<form method="post" name="frm_modificarXClave" action="" onsubmit="return valFormModificarXClave(this);">
		<br />
		<table>
		<tr>
			<td width="72" align="right">Clave</td>
			<td width="289">
 				<input type="text" name="txt_claveMod" id="txt_claveMod" size="20" maxlength="20"/>
		  </td>
		</tr>
		<tr><td colspan="2"><span class="msje_correcto" id="mensaje"><?php echo $msj;?></span></td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="center">
 				<input type="submit" name="sbt_enviarClave" class="botones" value="Modificar" title="Modificar Material Seleccionado"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" name="btn_limpiar" class="botones" value="Limpiar" title="Limpiar el Formulario" onclick="mensaje.style.visibility = 'hidden';"/>
			</td>
		</tr>
	</table>
	</form>
</fieldset>		
	
	<?php 
	}//Cierre if hdn_material 	
	else{
		//Mostrar el formulario para que el material seleccionado pueda ser modificado
		if(isset($_POST['cmb_material'])){ 
			?><fieldset id="form-modificar" class="borde_seccion">
			<legend class="titulo_etiqueta">Modificar Material</legend><?php
				 modificarMaterial($cmb_material);	
			?></fieldset><?php 
		}//Cierre if(isset($_GET['hdn_material']))
		
		//Mostrar el formulario para que el material que corresponde a la clave seleccionada pueda ser modificado
		if(isset($_GET['cve'])){ 
			?><fieldset id="form-modificar" class="borde_seccion">
			<legend class="titulo_etiqueta">Modificar Material</legend><?php
				 modificarMaterial($_GET["cve"]);	
			?></fieldset><?php 
		}//Cierre if(isset($_GET['hdn_material']))
		
		//Guardar los cambios realizados al material
		if(isset($_POST['txt_clave'])){										
			guardarCambios();
		}
	}//Cierre del Else ?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		include ("op_consultarMaterial.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	
    <style type="text/css">
		<!--
		#consulta-articulo {position:absolute; left:20px; top:186px; width:606px; height:134px; z-index:13; }
		#titulo-consultar { position:absolute; left:30px; top:146px; width:155px; height:19px; z-index:11; }	
		#boton-cancelar { position:absolute; left:445px; top:662px; width:124px; height:37px; z-index:17; }
		#resultado-consulta { position:absolute; left:30px; top:190px; width:930px; height:420px; z-index:12; overflow:scroll; }
		#consulta-categoria { position:absolute; left:20px; top:344px; width:605px; height:115px; z-index:14; }
		#consulta-mixta { position:absolute; left:22px; top:483px; width:950px; height:150px; z-index:15; }
		#consulta-clave { position:absolute; left:675px; top:344px; width:297px; height:115px; z-index:18; }
		#ver-todo { position:absolute; left:675px; top:187px; width:298px; height:134px; z-index:16; }
		#btns-regpdf { position: absolute; left:30px; top:640px; width:900px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Consultar Material </div>
	
<?php //Si las variables $cmb_material, $cmb_lineaArticulo, $cmb_param2 y $hdn_verTodo no estan definidas mostrar los formularios 
	//para consultar los materiales por material, por categoria, por parametro o consultar todo el catalogo
	if( !isset($_POST['cmb_material']) && !isset($_POST['cmb_lineaArticulo']) && !isset($_POST['cmb_param2']) && !isset($_POST['hdn_verTodo']) && !isset($_POST['txt_clave'])){ ?>		
	
	
	<fieldset class="borde_seccion" id="consulta-articulo" name="consulta-articulo">
	<legend class="titulo_etiqueta">Consultar Material por Art&iacute;culo</legend>	
	<br>
	<table width="100%" border="0" align="left" cellpadding="5" class="tabla_frm">
	<form name="frm_cargarInfoCombos" method="post" action="">
		<tr>
			<td width="80"><div align="right">Categor&iacute;a</div></td>
			<td width="301"><?php $aux=1;
				//Evitar que la variable $cmb_categoria marque un error por no estar definida			
				if(!isset($_POST['cmb_categoria'])) $cmb_categoria = "";
				$conn = conecta("bd_almacen");
				$rs = mysql_query("SELECT DISTINCT linea_articulo FROM materiales ORDER BY linea_articulo");
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
	</form>
	<form onSubmit="return valFormConsultarMaterial(this);" name="frm_consultarMaterial" method="post" action="frm_consultarMaterial.php">
		<tr>
			<td><div align="right">Material</div></td>
			<td><?php 
				if($aux==1){?>
					<select name="cmb_material" size="1" class="combo_box">
					<option value="" selected="selected">Material</option>
					<?php 
					$result1 = mysql_query("SELECT id_material,nom_material FROM materiales WHERE linea_articulo='$cmb_categoria' ORDER BY nom_material");		
					while ($row1=mysql_fetch_array($result1))
						echo "<option value='$row1[id_material]' title='$row1[id_material]'>$row1[nom_material]</option>";											
				}
				else{
					echo "<label class='msje_correcto'><u><strong>NO</strong></u> Hay Materiales Registrados</label>"; 
				}?>
				</select><?php
				//Cerrar la conexion con la BD		
				mysql_close($conn); ?>			
            </td>
		</tr>		
		<tr>
			<td colspan="2"><div align="center">
			  <input type="hidden" name="hdn_categoria" value="<?php if(isset($_POST['cmb_categoria'])) echo $cmb_categoria; ?>"  />
			  <input name="submit" type="submit" class="botones" value="Consultar" onMouseOver="window.status='';return true" title="Consultar Material por Art&iacute;culo" />
			  </div></td>			
		</tr>
	</form>	     
	</table>			      		
</fieldset>
				
	
	
	<fieldset class="borde_seccion" id="consulta-categoria" name="consulta-categoria">		
	<legend class="titulo_etiqueta">Consultar Material por Categor&iacute;a</legend>	
	<br>	
	<table width="100%" border="0" align="center" class="tabla_frm">
	<form onSubmit="return valFormConsultarCategoria(this);" name="frm_consultarCategoria" method="post" action="frm_consultarMaterial.php">
		<tr>
			<td width="120"><div align="right">Categor&iacute;a</div></td>
			<td width="220"><?php $lnArt= cargarCombo("cmb_lineaArticulo","linea_articulo","materiales","bd_almacen","Categor&iacute;a","");?></td>
		</tr>
		<tr>
		  	<td colspan="2"><?php if($lnArt==0){
					echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Categor&iacute;as para Consultar</label>";
				} ?> 
            </td>
	  	</tr>
		<tr>
	  		<td colspan="2"><?php if($lnArt==1){?>
				<div align="center"><input name="btn_consultar" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
                title="Consultar Material por Categor&iacute;a"/></div>
				<?php }?>
			</td>
		</tr>
	</form>
	</table>	  			
</fieldset>	
	
	<fieldset class="borde_seccion" id="consulta-clave" name="consulta-clave">		
	<legend class="titulo_etiqueta">Consultar Material por Clave</legend>	
	<br>	
	<table width="100%" border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
	<form onSubmit="return valFormConsultarClave(this);" name="frm_consultarClave" method="post" action="frm_consultarMaterial.php">
		<tr>
			<td width="120"><div align="right">Clave</div></td>
			<td width="220"><input type="text" name="txt_clave" id="txt_clave" size="10" maxlength="10" onkeypress="return permite(event,'num_car');" /></td>
		</tr>
		<tr>
	  		<td colspan="2">
				<div align="center"><input name="btn_consultar" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" title="Consultar Material por Clave"/></div>
			</td>
		</tr>
	</form>
	</table>	  			
</fieldset>	
	
	<fieldset class="borde_seccion" id="consulta-mixta" name="consulta-mixta">						
	<legend class="titulo_etiqueta">Consultar Material por Opci&oacute;n M&uacute;ltiple</legend>
	<br>
	<table width="100%" border="0" cellpadding="5" class="tabla_frm">
	<form name="frm_cargarInfo" method="post" action="">
		<tr>
  	  	  <td><div align="right">Buscar por</div></td>
			<td>			    					
				<select name="cmb_param" size="1" onChange="javascript:document.frm_cargarInfo.submit();" class="combo_box">
					<option value="">Par&aacute;metro</option>
					<option value="fecha_alta" <?php if(isset($_POST['cmb_param'])){ if($cmb_param=="fecha_alta"){ echo "selected='selected'"; } }?>>Fecha Alta</option>
					<option value="grupo" <?php if(isset($_POST['cmb_param'])){ if($cmb_param=="grupo"){ echo "selected='selected'"; } }?>>Grupo</option>
					<option value="proveedor" <?php if(isset($_POST['cmb_param'])){ if($cmb_param=="proveedor"){ echo "selected='selected'"; } }?>>Proveedor</option>					
					<option value="unidad_medida" <?php if(isset($_POST['cmb_param'])){ if($cmb_param=="unidad_medida"){ echo "selected='selected'"; } }?>>Unidad de Medida</option>																			
				</select>			  
		  </td>
		</tr>
	</form>
							
	<form onSubmit="return valFormConsultarMixta(this);" name="frm_consultarMixta" method="post" action="frm_consultarMaterial.php">
		<tr>
			<td><div align="right">Mostrar</div></td>
			<td>
				<input type="hidden" name="hdn_param" value="<?php if(isset($_POST['cmb_param'])) echo $cmb_param; ?>"  />
				<?php 
				if(isset($_POST['cmb_param']) && $cmb_param!=""){ 
					$tabla = "materiales";
					if($cmb_param=="unidad_medida")
						$tabla = "unidad_medida";
					$result=cargarCombo("cmb_param2","$cmb_param",$tabla,"bd_almacen","Opci&oacute;n","");
				}
				else
					echo "<div align='left'>Seleccionar un Par&aacute;metro</div>";
				?>				
			</td>
		</tr>
		<tr>
		  	<td colspan="2"><?php 
			$result=1;
				if(isset($_POST['cmb_param'])){ 
					if($result==1){ ?>
						<div align="center"><input name="btn_consultar2" type="submit" class="botones" value="Consultar" 
                        onMouseOver="window.status='';return true" title="Consultar Material por Opci&oacute;n M&uacute;ltiple" /></div><?php
                 	} 
					else{
                		echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Materiales Registrados para Consultarlos</label>";
					}
				}?> 
		  </td>
		</tr>
	</form>
	</table>										
</fieldset>
	
	
	
	<fieldset class="borde_seccion" id="ver-todo" name="ver-todo">
	<legend class="titulo_etiqueta">Ver Cat&aacute;logo de Almac&eacute;n</legend>
	<br><br><br>
	<form name="frm_verTodo" action="frm_consultarMaterial.php" method="post">
		<div align="center">
		  <input type="hidden" name="hdn_verTodo" value="todo" />
		  <input type="submit" name="btn_verTodo" value="Ver Cat&aacute;logo" class="botones" onMouseOver="window.status='';return true" 
          title="Ver todo el Cat&aacute;logo de Almac&eacute;n" />
      	</div>
	</form>
</fieldset>


	<div id="boton-cancelar">
        <form action="menu_material.php" method="post">
            <div align="center">
              <input name="sbt_cancelar" type="submit" value="Cancelar" class="botones" onMouseOver="window.status='';return true" 
              title="Regresar al Men&uacute; de Materiales" />
          </div>
        </form>
</div><?php
	
	}//Cierre if( !isset($_POST['cmb_material']) && !isset($_POST['cmb_lineaArticulo']) && !isset($_POST['cmb_param2']) && !isset($_POST['hdn_verTodo']))
	else{
		?><div id="resultado-consulta" class="borde_seccion2"><?php
		//Dibujar la Tabla de acuerdo al material seleccionado pasando como parametro el id del material seleccionado
		if(isset($_POST['cmb_material']))
			dibujarDetalle("id_material",$cmb_material);	
		//Dibujar la Tabla con el detalle de los artículos de la categoría seleccionada		
		if(isset($_POST['cmb_lineaArticulo']))
			dibujarDetalle("linea_articulo",$cmb_lineaArticulo);
		//Dibujar la Tabla con el detalle de los artículos del parametro(Proveedor, Fecha, Unidad de Medida, etc) seleccionado	
		if(isset($_POST['cmb_param2']))
			dibujarDetalle($hdn_param,$cmb_param2);		
		//Dibujar la Tabla con el detalle de los artículos registrados en el catalogo	
		if(isset($_POST['hdn_verTodo']))
			dibujarDetalle($hdn_verTodo,"");
		//Dibujar la Tabla con el detalle del articulo con la clave asignada
		if(isset($_POST['txt_clave']))
			dibujarDetalle("clave",$txt_clave);?>
		<?php  
	}//Cierre del else?>        
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
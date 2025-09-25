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
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
<script type="text/javascript" src="../../includes/validacionAlmacen.js"></script>

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:172px; height:23px; z-index:11; }
		#tabla { position:absolute;	left:30px; top:190px; width:651px; height:308px; z-index:12; }
		-->
    </style>
</head>
<body>
	
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Agregar Equivalencias</div>
    
    <fieldset id="tabla" class="borde_seccion">
	<legend class="titulo_etiqueta">Agregar Equivalencia de Material</legend>
  	<br>
	<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
	<form name="frm_cargarInfoCombos" method="post" action="frm_agregarEquivalencias.php">
      	<tr>
            <td width="110"><div align="right">*Categor&iacute;a</div></td>
            <td width="240"><?php 
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
					$aux=1;
				}
				else {?>
					<label class="msje_correcto"><u><strong>NO</strong></u> Hay Categor&iacute;as Registradas</label><?php                     
                	$aux=0;
				} ?>
			</td>
        	<td width="140">&nbsp;</td>
		</tr>
		</form>	  
		<form onSubmit="return verContFormEquivalencias(this);" name="frm_agregarEquivalencias" method="post"  action="op_agregarEquivalencias.php">
      	<tr>
            <td><div align="right">*Material</div></td>
            <td><?php 
				//Evitar que la variable $cmb_categoria marque un error por no estar definida			
				if(!isset($_POST['cmb_material'])) $cmb_material = "";
				
				$result1 = mysql_query("SELECT id_material,nom_material FROM materiales WHERE linea_articulo='$cmb_categoria' ORDER BY nom_material");						
				if($row1=mysql_fetch_array($result1)){ ?>
					<select name="cmb_material" id="cmb_material" size="1" class="combo_box">
                        <option value="">Material</option><?php					
                        do{
                            if ($row1['id_material'] == $cmb_material){
                                echo "<option value='$row1[id_material]' selected='selected' title='$row1[id_material]'>$row1[nom_material]</option>";							
                            }else{
                                echo "<option value='$row1[id_material]' title='$row1[id_material]'>$row1[nom_material]</option>";
                            }
                        }while($row1=mysql_fetch_array($result1));?>
                    </select>
                    <?php //Cerrar la conexion con la BD
				}
				if($aux==0){?> 
                	<label class="msje_correcto"><u><strong>NO</strong></u> Hay Materiales Registrados</label><?php
				}		
				mysql_close($conn); ?>	
           	</td>
        	<td>&nbsp;</td>
      	</tr>
	  		<td><input type="hidden" name="hdn_categoria" value="<?php if(isset($_POST['cmb_categoria'])) echo $cmb_categoria; ?>"  /></td>
      	<tr>
        	<td><div align="right">*Clave Equivalente </div></td>
        	<td>
            	<input name="txt_claveEquiv" type="text" class="caja_de_texto" id="txt_claveEquiv" size="10" maxlength="10" 
            	onkeypress="return permite(event,'num_car');" />
			</td>
        	<td>&nbsp;</td>
      	</tr>
      	<tr>
        	<td><div align="right">*Nombre</div></td>
            <td><input name="txt_nombre" type="text" class="caja_de_texto" id="txt_nombre" size="30" maxlength="40" onkeypress="return permite(event,'num_car');"/></td>
            <td>&nbsp;</td>
      	</tr>
      	<tr>
            <td><div align="right">*Proveedor</div></td>
            <td colspan="2"><?php 
				$result=cargarCombo("cmb_proveedor","razon_social","proveedores","bd_compras","Proveedor",""); 
				if($result==0){
					echo "<label class='msje_correcto'><u><strong>NO</strong></u> Hay Proveedores Registrados</label>";?>
					<input type="hidden" name="cmb_material" id="cmb_material"/><?php
				}?>		
        	</td>
		</tr>
      	<tr>
        	<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
      	</tr>
      	<tr>
        	<td colspan="3"><div align="center"><?php 
			if($result===1){ ?>
                <input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar" value="Agregar" title="Agregar Equivalencia de Material" 
                onmouseover="window.status='';return true" /><?php 
			}?>
			&nbsp;&nbsp;&nbsp;
          	<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar" value="Limpiar" title="Limpiar Formulario" 
            onmouseover="window.status='';return true" />
			&nbsp;&nbsp;&nbsp;
          	<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Equivalencias" 
            onclick="location.href='menu_equivalencias.php'" />
       	 	</div></td>
      	</tr>
  		</form>
  	</table>
	</fieldset>    
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
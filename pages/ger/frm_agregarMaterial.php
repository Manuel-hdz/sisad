<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"  />
	<script type="text/javascript" src="../../includes/validacionGerencia.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
	
    <style type="text/css">
		<!--
		#titulo-agregarMat { position:absolute; left:30px; top:146px; width:141px; height:19px; z-index:11; }
		#tabla-agregarMat {	position:absolute;left:30px;top:190px;width:940px;height:500px;z-index:12;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-agregarMat">Agregar Material</div><?php

	//Liberar los valores de la SESSION para Entrada de Material, en el caso que se haya dado click en el Menu Materiales y en la opcion material
	//materiales a los cuales se les va a dar entrada y tambien de la pagina donde se pide la información complementaria de la Entrada
	if( (isset($_GET['lmp']) && $_GET['lmp']=="si") || (isset($_POST['hdn_lmp']) && $_POST['hdn_lmp']=="si")){
		unset($_SESSION['datosEntrada']);		
		unset($_SESSION['cmb_prm2']);	
	}		
	//Cargar los datos de cada uno de los nuevos materiales para ser registrados en el catalogo de Almacen
	$claveNueva="";
	$nombre="";
	$unidad = "";
	$cantEntrada = "";
	$costoUnidad = "";
	$mensaje = "Agregar Material";
	if(isset($_SESSION['datosEntrada'])){
		$_SESSION['infoEntrada'] = $infoEntrada;


		//Obtener los datos del Nuevo Material que se esta registrando
		$num = $_SESSION['infoEntrada']['contador'];
		$material = $_SESSION['datosEntrada'][$num-1];
		if ($material['clave']!="N/A")
			$claveNueva = $material['clave'];
		$nombre = $material['nombre'];
		$unidad = $material['unidad'];
		$cantEntrada = $material['cantEntrada'];
		$costoUnidad = $material['costoUnidad'];		
		$mensaje = "Completar Datos del Nuevo Material (".$num." de ".$_SESSION['infoEntrada']['cantRegistros'].")";
		
		//Definir en la SESSION las variables que seran necesarias para verificar si el proceso entero de registro de nuevos materiales desde una 
		//Requsicion o una Orden de Compra se llevo a acabo con exito.
		if(!isset($_SESSION['procesoRegistroMat'])){
			$_SESSION['procesoRegistroMat'] = "NoTerminado";			
			$_SESSION['clavesRegistradasMat'] = array();
		}
	}?>

	<fieldset class="borde_seccion" id="tabla-agregarMat" name="tabla-agregarMat">
	<legend class="titulo_etiqueta"><?php echo $mensaje; ?></legend>
	<form onSubmit="return valFormAgregarMaterial(this);" name="frm_agregarMaterial" method="post" enctype="multipart/form-data" action="op_agregarMaterial.php">
	<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td><div align="right">*Clave</div></td>
			<td><input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="10" maxlength="10" 
				onkeypress="return permiteClavesMaterial(event,'num_car');" value="<?php echo $claveNueva;?>" 
				onblur="return verificarDatoBD(this,'bd_almacen','materiales','id_material','nom_material');" />
		  	<span id="error" class="msj_error">Clave Duplicada</span></td>
			<td><div align="right">*Nombre</div></td>
			<td><input name="txt_nombre" type="text" class="caja_de_texto" size="30" maxlength="60" onkeypress="return permite(event,'num_car');" value="<?php echo $nombre; ?>" /></td>
		</tr>
		<tr>
			<td><div align="right">*Relevancia</div></td>
			<td>
				<select name="cmb_relevancia" id="cmb_relevancia" class="combo_box" onchange="definirNivelesMovimiento(this);">
              		<option value="" selected="selected">Relevancia</option>
              		<option value="STOCK">STOCK</option>
              		<option value="CONSIGNACION">CONSIGNACION</option>
              		<option value="LENTO MOVIMIENTO">LENTO MOVIMIENTO</option>
            	</select>			</td>
			<td><div align="right">*Ubicaci&oacute;n</div></td>
			<td><input name="txt_ubicacion" type="text" class="caja_de_texto" size="30" maxlength="30" onkeypress="return permite(event,'num_car');" /></td>
		</tr>
		<tr>
			<td><div align="right">*Cantidad</div></td>
			<td><input name="txt_cantidad" id="txt_cantidad" type="text" class="caja_de_texto" size="15" maxlength="20" 
				onkeypress="return permite(event,'num',2);" value="<?php echo $cantEntrada; ?>" onchange="formatCurrency(value,'txt_cantidad');" /></td>
			<td><div align="right">Comentarios</div></td>			
			<td><textarea name="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30" 
				onkeypress="return permite(event,'num_car');" ></textarea>			</td>
		</tr>
		<tr>
			<td><div align="right">*Nivel M&iacute;nimo</div></td>
			<td><input name="txt_nivelMinimo" id="txt_nivelMinimo" type="text" class="caja_de_texto" size="15" maxlength="20" 
				onkeypress="return permite(event,'num',2);"/>			</td>
			<td><div align="right">*Factor de Conversi&oacute;n</div></td>
			<td><input name="txt_factor" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num',2);"/></td>
		</tr>
		<tr>
			<td><div align="right">* Nivel M&aacute;ximo </div></td>
			<td><input name="txt_nivelMaximo" id="txt_nivelMaximo" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'num',2);"/></td>
			<td><div align="right">*Unidad de Despacho</div></td>
			<td><input name="txt_unidadDespacho" type="text" class="caja_de_texto" size="15" maxlength="20" onkeypress="return permite(event,'car');"/></td>
		</tr>
		<tr>
		  <td><div align="right">*Punto Reorden </div></td>
			<td><input name="txt_puntoReorden" id="txt_puntoReorden" type="text" class="caja_de_texto" size="15" maxlength="20" 
				onkeypress="return permite(event,'num',2);"/>			</td>
			<td><div align="right">*Costo Unidad</div></td>
			<td>
				$
				<input name="txt_costoUnidad" type="text" class="caja_de_texto" id="txt_costoUnidad" value="<?php echo number_format($costoUnidad,2,".",","); ?>"
				onchange="formatCurrency(value,'txt_costoUnidad');" onkeypress="return permite(event,'num',2);" size="15" maxlength="20"/></td>
		</tr>
		<tr>
   		  	<td><div align="right">*L&iacute;nea del Art&iacute;culo</div></td>
			<td><?php
				//Evitar que la variable $cmb_lineaArticulo marque un error por no estar definida			
				if(!isset($_POST['cmb_lineaArticulo'])) $cmb_lineaArticulo = "";
				$conn = conecta("bd_almacen");
				$rs = mysql_query("SELECT DISTINCT linea_articulo FROM materiales WHERE grupo='PLANTA' ORDER BY linea_articulo");
				if($row=mysql_fetch_array($rs)){?>            
                    <select name="cmb_lineaArticulo" id="cmb_lineaArticulo" size="1"  class="combo_box">
                        <option value="">Categor&iacute;a</option><?php 
						do{
                            if ($row['linea_articulo'] == $cmb_lineaArticulo){
                                echo "<option value='$row[linea_articulo]' selected='selected'>$row[linea_articulo]</option>";
                            }
                            else{
                                echo "<option value='$row[linea_articulo]'>$row[linea_articulo]</option>";
                            }
                        }while($row=mysql_fetch_array($rs));?>
                    </select><?php
				}
				else {?>
					<label class="msje_correcto"><u><strong>NO</strong></u> Hay Categor&iacute;as Registradas, agregar una Nueva</label>
					<input type="hidden" name="cmb_lineaArticulo" id="cmb_lineaArticulo"/>
                <?php } ?>
			</td>			
			<td><div align="right"><input type="checkbox" name="ckb_lineaArticulo" id="ckb_lineaArticulo" onclick="obtenerLineaArticulo();" />Agregar Nueva Categor&iacute;a</div></td>
			<td><input type="text" name="txt_lineaArticulo" id="txt_lineaArticulo" size="20" maxlength="30" disabled="disabled" />
		    	<input type="hidden" name="hdn_lineaArticulo" id="hdn_lineaArticulo" size="20" maxlength="30" />			
			</td>
		</tr>
		<tr>
			<td><div align="right">*Unidad de Medida</div></td>
			<td><?php
				//Evitar que la variable $cmb_unidadMedida marque un error por no estar definida			
				if(!isset($_POST['cmb_unidadMedida'])) $cmb_unidadMedida = "";
				$conn = conecta("bd_almacen");
				$rs = mysql_query("SELECT DISTINCT unidad_medida FROM unidad_medida JOIN materiales ON materiales_id_material = id_material WHERE grupo='PLANTA' ORDER BY unidad_medida");
				if($row=mysql_fetch_array($rs)){?>            
                    <select name="cmb_unidadMedida" size="1" id="cmb_unidadMedida"  class="combo_box">
                        <option value="">Unidad Medida</option><?php 
						do{
                            if ($row['unidad_medida'] == $cmb_unidadMedida){
                                echo "<option value='$row[unidad_medida]' selected='selected'>$row[unidad_medida]</option>";
                            }
                            else{
                                echo "<option value='$row[unidad_medida]'>$row[unidad_medida]</option>";
                            }
                        }while($row=mysql_fetch_array($rs));?>
                    </select><?php
				}
				else {?>
					<label class="msje_correcto"><u><strong>NO</strong></u> Hay Unidad de Medida Registradas, agregar una Nueva</label>
					<input type="hidden" name="cmb_unidadMedida" id="cmb_unidadMedida" />
                <?php } ?>
			</td>		
			<td><div align="right"><input type="checkbox" name="ckb_unidadMedida" id="ckb_unidadMedida" onclick="obtenerUnidadMedia();" />Agregar Nueva Unidad</div></td>
			<td><input type="text" name="txt_unidadMedida" id="txt_unidadMedida" size="20" maxlength="20" disabled="disabled" />
		    	<input type="hidden" name="hdn_unidadMedida" id="hdn_unidadMedida" size="15" maxlength="20" />
				<?php		
				//Cerrar la conexion con la BD		
				mysql_close($conn); ?>		
			</td>
		</tr>
		<tr>
		  	<td><div align="right">Grupo</div></td>
			<td><input type="text" name="txt_grupo" id="txt_grupo" size="20" maxlength="20" readonly="readonly" value="PLANTA" style=" background-color:#666666; color:#FFFFFF"  />
		    	<input type="hidden" name="hdn_grupo" id="hdn_grupo" size="15" maxlength="20" />			</td>
			<td><div align="right">Fecha de Alta</div></td>
			<td><input name="fecha" type="text" disabled="disabled" class="caja_de_texto" value="<?php echo verFecha(4);?>" size="10" maxlength="10" />
		    <input name="hdn_fecha" type="hidden" id="hdn_fecha" value="<?php echo verFecha(3);?>"  /></td>
		</tr>		
		<tr>
			<td><div align="right">Fotograf&iacute;a</div></td>
			<td><input type="file" id="foto" name="foto" class="caja_de_texto" size="20" title="Buscar Imagen" onchange="validarImagen(this,'hdn_imgValida');" value=""
				onclick="alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenará en el Catálogo de Almacén');" />			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><div align="right">*Proveedor</div></td>
			<td colspan="3"><?php 
				$proveedor = cargarCombo("cmb_proveedor","razon_social","proveedores","bd_compras","Proveedor",""); 
				if($proveedor==0){ 
					echo "<label class='msje_correcto'>El Departamento de Compras <u><strong> NO</u></strong> Tiene Proveedores Registrados, por lo que 
					<u><strong> NO</u></strong> Puede Agregar Materiales. Cont&aacute;ctelo</label>";
					?><input type="hidden" name="cmb_proveedor" id="cmb_proveedor"/><?php
				}?>			
			</td>
	  	</tr>
	  	<tr>
			<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>		
		<tr>		  	
			<td colspan="4" align="center">
				<input type="hidden" name="hdn_matEspecial" id="hdn_matEspecial" value="no" />				
				<input type="hidden" name="hdn_tamImg" id="hdn_tamImg" value="" />
				<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
				<input type="hidden" name="hdn_imgValida" id="hdn_imgValida" value="si" />
				<?php if($proveedor==1){ ?>
					<input name="sbt_agregar" type="submit" value="Agregar" class="botones" title="Agregar Material" onMouseOver="window.status='';return true" />
				<?php } ?>
				&nbsp;&nbsp;		   
	         	<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" onClick="hablitarElementos();" />
				&nbsp;&nbsp;
		    	<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Materiales" 
				onClick="location.href='menu_materiales.php'" />			</td>
	  	</tr>	
	</table>
	</form>
</fieldset>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
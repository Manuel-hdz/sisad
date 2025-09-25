<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Almac�n
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
	<script type="text/javascript" src="../../includes/validacionAlmacen.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="includes/ajax/validarCodigoBarras.js"></script>

    <style type="text/css">
		<!--
		#titulo-agregarMat { position:absolute; left:30px; top:146px; width:141px; height:19px; z-index:11; }
		#tabla-agregarMat {	position:absolute;left:30px;top:180px;width:940px;height:515px;z-index:12; overflow:auto;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-agregarMat">Agregar Material</div><?php

	//Liberar los valores de la SESSION para Entrada de Material, en el caso que se haya dado click en el Menu Materiales y en la opcion material
	//materiales a los cuales se les va a dar entrada y tambien de la pagina donde se pide la informaci�n complementaria de la Entrada
	if( (isset($_GET['lmp']) && $_GET['lmp']=="si") || (isset($_POST['hdn_lmp']) && $_POST['hdn_lmp']=="si")){
		unset($_SESSION['datosEntrada']);		
		unset($_SESSION['id_entrada']);
		unset($_SESSION['origen']);
		unset($_SESSION['no_origen']);
		unset($_SESSION['cmb_prm2']);	
	}

	//Cargar los datos de cada uno de los nuevos materiales para ser registrados en el catalogo de Almac�n
	$claveNueva="";
	$nombre="";
	$cmb_cat="";
	$unidad = "";
	$cantEntrada = "";
	$costoUnidad = "";
	$moneda = "";
	$mensaje = "Agregar Material";
	$nuevoMat="Add";

	
	//Se llego aqui desde una probable Requisicion con Materiales No Registrados
	if(isset($_SESSION["datosEntradaNR"])){
		//Este arreglo contendra datos realcionados con la entrada del nuevo material
		if(!isset($_SESSION['infoEntrada'])){
			$infoEntrada = array("cantRegistros"=>count($_SESSION['datosEntradaNR']),"contador"=>1);
			$_SESSION['infoEntrada'] = $infoEntrada;
		}	

		//Obtener los datos del Nuevo Material que se esta registrando
		$num = $_SESSION['infoEntrada']['contador'];
		$material = $_SESSION['datosEntradaNR'][$num-1];
		if ($material['clave']!="N/A")
			$claveNueva = $material['clave'];
		$nombre = $material['nombre'];
		$cmb_cat = $material['cmb_cat'];
		$unidad = $material['unidad'];
		$cantEntrada = $material['cantEntrada'];
		$costoUnidad = $material['costoUnidad'];
		$moneda = $material['tipoMoneda'];
		$nuevoMat="Upd";		
		$mensaje = "Completar Datos del Nuevo Material (".$num." de ".$_SESSION['infoEntrada']['cantRegistros'].")";
		
		//Definir en la SESSION las variables que seran necesarias para verificar si el proceso entero de registro de nuevos materiales desde una 
		//Requsicion o una Orden de Compra se llevo a acabo con exito.
		if(!isset($_SESSION['procesoRegistroMat'])){
			$_SESSION['procesoRegistroMat'] = "NoTerminado";			
			$_SESSION['clavesRegistradasMat'] = array();
		}
	}
	//Se lleg� aqui con Materiales 
	if(isset($_SESSION['datosEntrada']) && !isset($_GET["ped"]) && !isset($_SESSION["datosEntradaNR"])){
		//Este arreglo contendra datos realcionados con la entrada del nuevo material
		if(!isset($_SESSION['infoEntrada'])){
			$infoEntrada = array("cantRegistros"=>count($_SESSION['datosEntrada']),"contador"=>1);
			$_SESSION['infoEntrada'] = $infoEntrada;
		}	

		//Obtener los datos del Nuevo Material que se esta registrando
		$num = $_SESSION['infoEntrada']['contador'];
		$material = $_SESSION['datosEntrada'][$num-1];
		if ($material['clave']!="N/A")
			$claveNueva = $material['clave'];
		$nombre = $material['nombre'];
		//$categoria_material = $material['categoria_material'];
		$unidad = $material['unidad'];
		$cantEntrada = $material['cantEntrada'];
		$costoUnidad = $material['costoUnidad'];
		$nuevoMat="Upd";		
		$mensaje = "Completar Datos del Nuevo Material (".$num." de ".$_SESSION['infoEntrada']['cantRegistros'].")";
		
		//Definir en la SESSION las variables que seran necesarias para verificar si el proceso entero de registro de nuevos materiales desde una 
		//Requsicion o una Orden de Compra se llevo a acabo con exito.
		if(!isset($_SESSION['procesoRegistroMat'])){
			$_SESSION['procesoRegistroMat'] = "NoTerminado";			
			$_SESSION['clavesRegistradasMat'] = array();
		}
	}
	
	//Verificar si se llego aqui desde un Pedido
	if(isset($_SESSION['datosEntrada']) && isset($_GET["ped"])){
		$materiales=array();
		$pos=array();
		$cant=count($_SESSION["datosEntrada"]);
		$cont=0;
		do{
			if ($_SESSION["datosEntrada"][$cont]["clave"]==""){
				$materiales[]=$_SESSION["datosEntrada"][$cont];
				$pos[]=$cont;
			}
			$cont++;
		}while($cont<$cant);
		$num = 0;
		//$num = count($materiales);
		$nombre = $materiales[$num]['nombre'];
		$cmb_cat = $materiales[$num]['cmb_cat'];
		$unidad = $materiales[$num]['unidad'];
		$cantEntrada = $materiales[$num]['cantEntrada'];
		$costoUnidad = $materiales[$num]['costoUnidad'];
		$moneda = $materiales[$num]['tipoMoneda'];
		
		if(!isset($_SESSION['procesoRegistroMat'])){
			$_SESSION['procesoRegistroMat'] = "NoTerminado";			
			$_SESSION['clavesRegistradasMat'] = array();
		}
		
		if(!isset($_SESSION['infoEntrada'])){
			$infoEntrada = array("cantRegistros"=>count($materiales),"contador"=>1);
			$_SESSION['infoEntrada'] = $infoEntrada;
		}
		$nuevoMat="Upd";
		$mensaje = "Completar Datos del Nuevo Material (".$_SESSION['infoEntrada']['contador']." de ".$_SESSION['infoEntrada']['cantRegistros'].")";
	}
	?>

	<fieldset class="borde_seccion" id="tabla-agregarMat" name="tabla-agregarMat">
		<legend class="titulo_etiqueta"><?php echo $mensaje; ?></legend>
		<form onSubmit="return valFormAgregarMateriales(this);" name="frm_agregarMaterial" method="post" enctype="multipart/form-data" action="op_agregarMaterial.php<?php if (isset($_GET["ped"])) echo "?ped&pos=$pos[0]"; ?>">
			<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td>
					<div align="right">*Clave</div>
				</td>
				<td>
					<input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="10" maxlength="20" 
					onkeypress="return permiteClavesMaterial(event,'num_car');" value="<?php echo $claveNueva;?>" autocomplete="off"
					onblur="return verificarDatoBD(this,'bd_almacen','materiales','id_material','nom_material');" required="required"/>
					<span id="error" class="msj_error">Clave Duplicada</span>
				</td>
				<td>
					<div align="right">*C&oacute;digo de Barras </div>
				</td>
				<td>
					<input name="txt_codigoBarras" id="txt_codigoBarras" type="text" class="caja_de_texto" size="20" maxlength="20" 
					value="" onchange="return verificarCodigoBarras(this)" required="required" autocomplete="off"/>
					<span id="errorCB" class="msje_incorrecto" style="visibility:hidden">C&oacute;digo Duplicado</span>
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">*Nombre</div>
				</td>
				<td>
					<input name="txt_nombre" type="text" class="caja_de_texto" size="30" maxlength="60" autocomplete="off"
					onkeypress="return permite(event,'num_car');" value="<?php echo $nombre; ?>" required="required"/>
				</td>
				<td>
					<div align="right">*Relevancia</div>
				</td>
				<td>
					<!--
					<select name="cmb_relevancia" id="cmb_relevancia" class="combo_box" onchange="definirNivelesMovimiento(this);" required="required">
					-->
					<select name="cmb_relevancia" id="cmb_relevancia" class="combo_box" required="required">
						<option value="" selected="selected">Relevancia</option>
						<option value="STOCK">STOCK</option>
						<option value="CONSIGNACION">CONSIGNACION</option>
						<option value="LENTO MOVIMIENTO">LENTO MOVIMIENTO</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">*Cantidad</div>
				</td>
				<td>
					<input name="txt_cantidad" id="txt_cantidad" type="text" class="caja_de_texto" size="15" maxlength="20" <?php if(isset($_GET["ped"])) {echo "readonly='readonly'";} ?>
					onkeypress="return permite(event,'num');" value="<?php echo $cantEntrada; ?>" autocomplete="off" required="required"/>
				</td>
				<td>
					<div align="right">Comentarios</div>
				</td>
				<td>
					<textarea name="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30" 
					onkeypress="return permite(event,'num_car');" style="resize: none;" ></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">*Nivel M&iacute;nimo</div>
				</td>
				<td>
					<input name="txt_nivelMinimo" id="txt_nivelMinimo" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" autocomplete="off" required="required"/>
				</td>
				<td>
					<div align="right">*Factor de Conversi&oacute;n</div>
				</td>
				<td>
					<input name="txt_factor" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" autocomplete="off" required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">*Nivel M&aacute;ximo </div>
				</td>
				<td>
					<input name="txt_nivelMaximo" id="txt_nivelMaximo" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" autocomplete="off" required="required"/>
				</td>
				<td>
					<div align="right">*Unidad de Despacho</div>
				</td>
				<td>
					<input name="txt_unidadDespacho" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'car');" autocomplete="off" required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">*Punto Reorden </div>
				</td>
				<td>
					<input name="txt_puntoReorden" id="txt_puntoReorden" type="text" class="caja_de_texto" size="15" maxlength="20" 
					onkeypress="return permite(event,'num');" autocomplete="off" required="required"/>
				</td>
				<td>
					<div align="right">*Costo Unidad</div>
				</td>
				<td>
					$<input name="txt_costoUnidad" type="text" class="caja_de_texto" id="txt_costoUnidad" value="<?php echo number_format($costoUnidad,2,".",","); ?>"
					onchange="formatCurrency(value,'txt_costoUnidad');" onkeypress="return permite(event,'num');" size="15" maxlength="20" autocomplete="off" required="required"/>
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">*Estado </div>
				</td>
				<td>
					<select name="cmb_estado" id="cmb_estado" class="combo_box" required="required">
						<option value="" selected="selected">Estatus</option>
						<option value="NUEVO">NUEVO</option>
						<option value="REACONDICIONADO">REACONDICIONADO</option>
						<option value="USADO">USADO</option>
					</select>
				</td>
				<td>
					<div align="right">*Moneda</div>
				</td>
				
				<td>
					<select name="txt_moneda" id="txt_moneda" size="1" class="combo_box" required="required">
						<option value="">Moneda</option>
						<option <?php if($moneda == "PESOS") echo "selected=selected" ?> value="PESOS">PESOS</option>
						<option <?php if($moneda == "DOLARES") echo "selected=selected" ?> value="DOLARES">DOLARES</option>
						<option <?php if($moneda == "EUROS") echo "selected=selected" ?> value="EUROS">EUROS</option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td>
					<div align="right">*L&iacute;nea del Art&iacute;culo</div>
				</td>
				<td>
					<?php 
					$conn = conecta("bd_almacen");
					$stm_sql = "SELECT DISTINCT linea_articulo FROM materiales WHERE grupo!='PLANTA' ORDER BY linea_articulo";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){
						?>
						<select name="cmb_lineaArticulo" id="cmb_lineaArticulo" class="combo_box" required="required">
							<option value=''>Familia</option>
							<?php
							do{
								echo "<option value='$datos[linea_articulo]'>$datos[linea_articulo]</option>";
							}while($datos = mysql_fetch_array($rs));
							?>
						</select>
						<?php
					} else { 
						echo "<label class='msje_correcto'>Es Necesario Agregar Nueva L&iacute;nea</label>";?>
						<input type="hidden" name="cmb_lineaArticulo" id="cmb_lineaArticulo"/><?php	
					}
					?>
				</td>
				<td>
					<div align="right">
						<input type="checkbox" name="ckb_lineaArticulo" id="ckb_lineaArticulo" onclick="obtenerLineaArticulo();" />Agregar Nueva L&iacute;nea
					</div>
				</td>
				<td>
					<input type="text" name="txt_lineaArticulo" id="txt_lineaArticulo" size="20" maxlength="30" disabled="disabled" />
					<input type="hidden" name="hdn_lineaArticulo" id="hdn_lineaArticulo" size="20" maxlength="30" />
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">*Unidad de Medida</div>
				</td>
				<td>
					<?php 
					$conn = conecta("bd_almacen");
					$stm_sql = "SELECT DISTINCT unidad_medida FROM unidad_medida ORDER BY unidad_medida";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){
						?>
						<select name="cmb_unidadMedida" id="cmb_unidadMedida" class="combo_box" required="required">
							<option value=''>Unidad</option>
							<?php
							do{
								if($datos["unidad_medida"]==$unidad)
									echo "<option value='$datos[unidad_medida]' selected='selected'>$datos[unidad_medida]</option>";
								else
									echo "<option value='$datos[unidad_medida]'>$datos[unidad_medida]</option>";
							}while($datos = mysql_fetch_array($rs));
							?>
						</select>
						<?php
					} else { 
						echo "<label class='msje_correcto'>Es Necesario Agregar Nueva Unidad</label>";?>
						<input type="hidden" name="cmb_unidadMedida" id="cmb_unidadMedida"/><?php	
					}
					?>
				</td>
				<td>
					<div align="right"><input type="checkbox" name="ckb_unidadMedida" id="ckb_unidadMedida" onclick="obtenerUnidadMedia();" />Agregar Nueva Unidad</div>
				</td>
				<td>
					<input type="text" name="txt_unidadMedida" id="txt_unidadMedida" size="20" maxlength="20" disabled="disabled" />
					<input type="hidden" name="hdn_unidadMedida" id="hdn_unidadMedida" size="15" maxlength="20" />
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">*Grupo</div>
				</td>
				<td>
					<?php 
					if(!isset($_POST['cmb_grupo'])) $cmb_grupo = "";
					$conn = conecta("bd_almacen");
					$stm_grupos =  "SELECT T1.id_grupo, T1.grupo, T3.descripcion
									FROM grupos_mat AS T1
									JOIN rel_grupos_cuentas AS T2
									USING ( id_grupo ) 
									JOIN bd_recursos.cuentas AS T3
									USING ( id_cuentas ) 
									WHERE T1.habilitado =  'SI'
									ORDER BY T3.descripcion, T1.grupo";
					$rs_grupos = mysql_query($stm_grupos);
					if ($dato_grupos = mysql_fetch_array($rs_grupos)) {
						$nom_grupo = "";
						?>
						<select name="cmb_grupo" id="cmb_grupo" class="combo_box" required="required">
							<option value="">Selecciona un grupo</option>
							<?php 
							do {
								if ($dato_grupos["descripcion"] != $nom_grupo) {
									echo "
									<option value='' disabled='disabled'>_________________________________</option>
									<option value='' disabled='disabled'>$dato_grupos[descripcion]</option>
									<option value='' disabled='disabled'>_________________________________</option>
									";
								}
								if ($dato_grupos["id_grupo"] == $cmb_grupo) {
									echo "
									<option value='$dato_grupos[id_grupo]' selected='selected'>$dato_grupos[grupo]</option>
									";
								} else{
									echo "
									<option value='$dato_grupos[id_grupo]'>$dato_grupos[grupo]</option>
									";
								}
								$nom_grupo = $dato_grupos["descripcion"];
							} while ($dato_grupos = mysql_fetch_array($rs_grupos));
							?>
						</select>
						<?php
					}
					?>
				</td>
				<!--<td><?php
					//Evitar que la variable $cmb_categoria marque un error por no estar definida			
					/*if(!isset($_POST['cmb_grupo'])) $cmb_grupo = "";
					$conn = conecta("bd_almacen");
					$rs = mysql_query("SELECT DISTINCT grupo FROM materiales WHERE grupo!='PLANTA' AND grupo!='' ORDER BY grupo");
					if($row=mysql_fetch_array($rs)){?>            
						<select name="cmb_grupo"  id="cmb_grupo"size="1" class="combo_box" required="required">
							<option value="">Grupo</option><?php 
							do{
								if ($row['grupo'] == $cmb_grupo){
									echo "<option value='$row[grupo]' selected='selected'>$row[grupo]</option>";
								}
								else{
									echo "<option value='$row[grupo]'>$row[grupo]</option>";
								}
							}while($row=mysql_fetch_array($rs));?>
						</select><?php
					}
					else {?>
						<label class="msje_correcto"><u><strong>NO</strong></u> Hay Categor&iacute;as Para Modificar</label>
						<input type='hidden' name='cmb_grupo' id='cmb_grupo'/>
						<?php 
					} */?>
				</td>
				<td>
					<div align="right"><input type="checkbox" name="ckb_grupo" id="ckb_grupo" onclick="obtenerGrupo();" />Agregar Nuevo Grupo</div>
				</td>
				<td>
					<input type="text" name="txt_grupo" id="txt_grupo" size="20" maxlength="20" disabled="disabled" />
					<input type="hidden" name="hdn_grupo" id="hdn_grupo" size="15" maxlength="20" />
				</td>
				-->
			</tr>
			<tr>
				<td>
					<div align="right">Fotograf&iacute;a</div>
				</td>
				<td>
					<input type="file" id="foto" name="foto" class="caja_de_texto" size="20" title="Buscar Imagen" onchange="validarImagen(this,'hdn_imgValida');" value=""
					onclick="alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenar� en el Cat�logo de Almac�n');" />
				</td>
				<td>
					<div align="right">*Ubicaci&oacute;n</div>
				</td>
				<td>
					<input name="txt_ubicacion" type="text" class="caja_de_texto" size="30" maxlength="30" onkeypress="return permite(event,'num_car');" required="required"/>
					<input name="hdn_fecha" type="hidden" id="hdn_fecha" value="<?php echo verFecha(3);?>"/>
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">*Proveedor</div>
				</td>
				<td colspan="3">
					<?php 
					$proveedor = 0;
					$conn = conecta("bd_compras");
					$stm_sql = "SELECT DISTINCT razon_social FROM proveedores ORDER BY razon_social";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){
						?>
						<select name="cmb_proveedor" id="cmb_proveedor" class="combo_box" required="required">
							<option value=''>Proveedor</option>
							<?php
							do{
								echo "<option value='$datos[razon_social]'>$datos[razon_social]</option>";
							}while($datos = mysql_fetch_array($rs));
							?>
						</select>
						<?php
						$proveedor = 1;
					} else {
						echo "<label class='msje_correcto'>El Departamento de Compras <u><strong> NO</u></strong> Tiene Proveedores Registrados, por lo que 
						<u><strong> NO</u></strong> Puede Agregar Materiales. Cont&aacute;ctelo</label>";
						?><input type="hidden" name="cmb_proveedor" id="cmb_proveedor"/><?php
					}?>
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">Aplicaciones</div>
				</td>
				<td>
					<textarea name="txa_aplicaciones" maxlength="100" cols="70" rows="2" class="caja_de_texto" id="txa_aplicaciones" style="resize:none;"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="4">
					<strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong>
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="hidden" name="hdn_codeValido" id="hdn_codeValido" value="0"/>
					<input type="hidden" name="hdn_matEspecial" id="hdn_matEspecial" value="no" />
					<input type="hidden" name="hdn_tamImg" id="hdn_tamImg" value="" />
					<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
					<input type="hidden" name="hdn_imgValida" id="hdn_imgValida" value="si" />
					<input type="hidden" name="hdn_continuar" id="hdn_continuar" value="" />
					<input type="hidden" name="hdn_nuevoAdd" id="hdn_nuevoAdd" value="<?php echo $nuevoMat;?>" />
					<?php 
					if(isset($_SESSION['datosEntrada'])){ ?>
						<input name="sbt_siguiente" type="submit" value="Siguiente" class="botones" title="Agregar Siguiente Material" 
						onMouseOver="window.status='';return true" />
					<?php 
					}
					if($proveedor==1){ ?>
						<input name="sbt_agregar" type="submit" value="Agregar" class="botones" title="Agregar Material" onMouseOver="window.status='';return true" />
						<?php 
					} ?>
					&nbsp;&nbsp;		   
					<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" onClick="hablitarElementos();errorCB.style.visibility='hidden'" />
					&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Materiales" 
					onClick="location.href='menu_material.php'" />
				</td>
			</tr>	
			</table>
		</form>
	</fieldset>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
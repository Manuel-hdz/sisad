<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
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
	
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_proveedores.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<!-- se anexa este archivo para obtener las funciones necesarias para el control de costos -->
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-agregarEquipo { position:absolute; left:30px; top:190px; width:908px; height:494px; z-index:12; padding:15px; padding-top:0px;}
		#calendario {position:absolute;left:730px;top:233px;width:30px;height:26px;z-index:13;}
		#res-spider1 { position:absolute; left:575px; top:270px; width:10px; height:10px; z-index:13; }
		#res-spider2 { position:absolute; left:575px; top:318px; width:10px; height:10px; z-index:13; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Equipo</div>
	
	<?php
	/***********************************************DECIDIR SI LOS DATOS QUE PROVIENEN DEL FORMULARIO DEL EQUIPO AGREGADO****************************/
	//Verificamos que datos vienen checados para verificar si se mostrara o no
	if(isset($_POST['ckb_fechaFabricacionEquipo']))
	    $txt_fechaFabricacionEquipo = $_POST['txt_fechaFabricacionEquipo'];
	else
		$txt_fechaFabricacionEquipo = date("d/m/Y");
	if(isset($_POST['ckb_placa']))
	    $txt_placa = $_POST['txt_placa'];
	else
		$txt_placa = "";
	if(isset($_POST['ckb_nombre']))
	    $txt_nombre = $_POST['txt_nombre'];
	else
		$txt_nombre = "";
	if(isset($_POST['ckb_tenencia']))
	    $txt_tenencia = $_POST['txt_tenencia'];
	else
		$txt_tenencia = "";
	if(isset($_POST['ckb_marcaModelo']))
	    $txt_marcaModelo = $_POST['txt_marcaModelo'];
	else
		$txt_marcaModelo = "";
	if(isset($_POST['ckb_modelo']))
	    $txt_modelo = $_POST['txt_modelo'];
	else
		$txt_modelo = "";
	if(isset($_POST['ckb_tarjetaCirculacion']))
	    $txt_tarjetaCirculacion = $_POST['txt_tarjetaCirculacion'];
	else
		$txt_tarjetaCirculacion = "";
	if(isset($_POST['ckb_serie']))
	    $txt_serie = $_POST['txt_serie'];
	else
		$txt_serie = "";
	if(isset($_POST['ckb_poliza']))
	    $txt_poliza = $_POST['txt_poliza'];
	else
		$txt_poliza = "";
	if(isset($_POST['ckb_serieOlla']))
	    $txt_serieOlla = $_POST['txt_serieOlla'];
	else
		$txt_serieOlla = "";
	if(isset($_POST['ckb_asignado']))
	    $txt_asignado = $_POST['txt_asignado'];
	else
		$txt_asignado = "";
	if(isset($_POST['ckb_motor']))
	    $txt_motor = $_POST['txt_motor'];
	else
		$txt_motor = "";
	if(isset($_POST['ckb_proveedor']))
	    $txt_proveedor = $_POST['txt_proveedor'];
	else
		$txt_proveedor = "";
	if(isset($_POST['ckb_area']))
	    $txt_area = $_POST['txt_area'];
	else
		$txt_area = "";
	if(isset($_POST['ckb_descripcion']))
	    $txa_descripcion = $_POST['txa_descripcion'];
	else
		$txa_descripcion = "";
	if(isset($_POST['ckb_metrica']))
	    $txt_metrica = $_POST['txt_metrica'];
	else
		$txt_metrica = "";

	/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
		$atributo = "";
		$area = "";
		$estado = 1;//El estado 1 Indica que el usuario con la SESSION abierta es AuxMtto
		if($_SESSION['depto']=="MttoConcreto"){
			$area = "CONCRETO";
			$atributo = "disabled='disabled'";
			$estado = 0;
		}
		else if($_SESSION['depto']=="MttoMina"){
			$area = "MINA";
			$atributo = "disabled='disabled'";
			$estado = 0;
		}
		
		if($estado==0){ ?>		
			<script type="text/javascript" language="javascript">
				setTimeout("cargarCombo('<?php echo $area;?>','bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','<?php echo $txt_area;?>')",500);
			</script>
		<?php } ?>
	
	<fieldset class="borde_seccion" id="tabla-agregarEquipo" name="tabla-agregarEquipo" style="height:570px;">
	<legend class="titulo_etiqueta">Agregar Equipo</legend>	
	<br>
	<form onSubmit="return valFormAgregarEquipo(this);" name="frm_agregarEquipo" method="post" action="op_agregarEquipo.php" enctype="multipart/form-data">
    <table width="923" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
	<tr>
		<td width="137"><div align="right">*Clave del Equipo </div></td>
		<td width="298">
			<input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car', 3);" 
			onblur="return verificarDatoBD(this,'bd_mantenimiento','equipos','id_equipo','nom_equipo');" />
			<span id="error" class="msj_error">Clave Duplicada</span>
		</td>
		<td width="168"><div align="right">Fecha de Fabricaci&oacute;n del Equipo </div></td>
		<td width="253"><input type="text" name="txt_fechaFabricacionEquipo" id="txt_fechaFabricacionEquipo" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo $txt_fechaFabricacionEquipo;?>"/></td>
	</tr>
	<tr>
		<td><div align="right">Fecha</div></td>
		<td><input type="text" name="txt_fecha" id="txt_fecha" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo date("d/m/Y"); ?>"/></td>
		<td><div align="right">No. Placas </div></td>
		<td>
			<input type="text" name="txt_placa" id="txt_placa" size="10" maxlength="10" onkeypress="return permite(event,'num_car',1);" class="caja_de_texto"
			value="<?php echo $txt_placa;?>"/></td>
	</tr>
	<tr>
		<td><div align="right">*Nombre del Equipo </div></td>
		<td>
			<input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" size="50" maxlength="60" onkeypress="return permite(event,'num_car',3);"
			value="<?php echo $txt_nombre;?>"/>
		</td>
		<td><div align="right">Tenencia</div></td>
		<td>
			<input name="txt_tenencia" id="txt_tenencia" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',3);"
			value="<?php echo $txt_tenencia;?>"/></td>
	</tr>
	<tr>
		<td><div align="right">*Marca/Modelo </div></td>
		<td>
			<input name="txt_marcaModelo" type="text" class="caja_de_texto" id="txt_marcaModelo" size="20" maxlength="60" 
          	onkeypress="return permite(event,'num_car', 1);" value="<?php echo $txt_marcaModelo;?>"/> 
			*Modelo  
			<input name="txt_modelo" type="text" class="caja_de_texto" id="txt_modelo" size="15" maxlength="30"
			onkeypress="return permite(event,'num_car',1);" value="<?php echo $txt_modelo;?>"/>
		</td>
		<td><div align="right">No. Tarjeta Circulaci&oacute;n </div></td>
		<td>
			<input name="txt_tarjetaCirculacion" id="txt_tarjetaCirculacion" type="text" class="caja_de_texto" size="20" maxlength="20" 
			onkeypress="return permite(event,'num_car',3);" value="<?php echo $txt_tarjetaCirculacion;?>"/></td>
	</tr>
	<tr>
		<td><div align="right">*No. de Serie </div></td>
		<td>
			<input name="txt_serie" id="txt_serie" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',1);"
			value="<?php echo $txt_serie;?>"/></td>
		<td><div align="right">*No. P&oacute;liza  </div></td>
		<td>
			<input name="txt_poliza" id="txt_poliza" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car', 3);"
			value="<?php echo $txt_poliza;?>"/></td>
	</tr>
	<tr>
		<td><div align="right">No. de Serie Equipo Adicional</div></td>
		<td>
			<input name="txt_serieOlla" id="txt_serieOlla" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',3);"
			value="<?php echo $txt_serieOlla;?>"/>
		</td>
		<td><div align="right">*Asignado a </div></td>
		<td>
			<input name="txt_asignado" type="text" class="caja_de_texto" id="txt_asignado" tabindex="1" onkeypress="return permite(event,'car',0);" 
			onkeyup="lookup(this,'1');" value="" size="40" maxlength="75" />
			<div id="res-spider1">
				<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
					<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
					<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
				</div>
			</div>
		</td>
	</tr>       	
	<tr>
		<td><div align="right">*Tipo de Motor </div></td>
		<td>
			<input name="txt_motor" id="txt_motor" type="text" class="caja_de_texto" size="15" maxlength="15" onkeypress="return permite(event,'num_car',3);"
			value="<?php echo $txt_motor;?>"/>
		</td>
		<td><div align="right">*Proveedor</div></td>
		<td>
			<input name="txt_proveedor" id="txt_proveedor" type="text" class="caja_de_texto" size="40" maxlength="80" onkeypress="return permite(event,'num_car',1);"
			onkeyup="lookupProv(this,'2');" value="<?php echo $txt_proveedor;?>"/>
			<div id="res-spider2">
				<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
					<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
					<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
				</div>
			</div>
			</td>
	</tr>	
	<tr>
		<td><div align="right">*&Aacute;rea</div></td>
		<td>
			<?php if($estado==1) {?>
				<select name="cmb_area" class="combo_box" onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','<?php echo $txt_area;?>');">
					<option value="">&Aacute;rea</option>						
					<option value="CONCRETO">CONCRETO</option>
					<option value="MINA">MINA</option>
				</select>
			<?php } else { ?>
				<select name="cmb_area" class="combo_box" onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','<?php echo $txt_area;?>');" <?php echo $atributo; ?>>
					<option value="">&Aacute;rea</option>						
					<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
					<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
				</select>		
				<input type="hidden" name="cmb_area" value="<?php echo $area; ?>" />
			<?php } ?>	
		</td>
		<td><div align="right">Fotograf&iacute;a</div></td>
		<td>
			<input type="file" id="foto" name="foto" class="caja_de_texto" size="20" title="Buscar Imagen" value=""
			onclick="alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenará en el Catálogo de Equipos');" onchange="return validarImagen(this,'hdn_foto');" />
			<input type="hidden" id="hdn_foto" name="hdn_foto" value=""/>
		</td>
	</tr>
	<tr>
		<td><div align="right">*Familia</div></td>
		<td>
			<select name="cmb_familia" id="cmb_familia">
				<option value="">Familia</option>
			</select>
		</td>
		<td><div align="right"><input type="checkbox" name="ckb_nuevaFamilia" id="ckb_nuevaFamilia" onclick="agregarNuevaFamilia();" title="Seleccione para escribir el nombre de una Familia que no exista"/>Agregar Nueva Familia</div></td>
		<td><input name="txt_nuevaFamilia" id="txt_nuevaFamilia" type="text" class="caja_de_texto" readonly="readonly" size="30" maxlength="30" onkeypress="return permite(event,'num_car', 3);" /></td>
	</tr>
	<tr>
		<td><div align="right">*Control de Costos</div></td>
		<td>
			<?php 
			$conn = conecta("bd_recursos");		
			$stm_sql = "SELECT * FROM control_costos WHERE habilitado = 'SI' ORDER BY descripcion";
			$rs = mysql_query($stm_sql);
			//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
			if($datos = mysql_fetch_array($rs)){?>
				<select name="cmb_con_cos" id="cmb_con_cos" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta')">
				<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
				echo "<option value=''>Control de Costos</option>";
				do{
					if ($datos['id_control_costos'] == $cmb_con_cos){
						echo "<option value='$datos[id_control_costos]' selected='selected'>$datos[descripcion]</option>";
					}else{
						echo "<option value='$datos[id_control_costos]'>$datos[descripcion]</option>";
					}
				}while($datos = mysql_fetch_array($rs));
				echo "<script type='text/javascript'>
						cargarCuentas(cmb_con_cos.value,'cmb_cuenta');
					  </script>";
				?>
				<script type="text/javascript">
					setTimeout("document.getElementById('cmb_cuenta').value='<?php echo $cmb_cuenta ?>'",500);
				</script>
				</select>
				<?php
			}
			else{
				echo "<label class='msje_correcto'> No actualmente control de costos</label>
					<input type='hidden' name='cmb_area' id='cmb_area'/>";
			}
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			?>
		</td>
		<td width="15%"><div align="right">*Cuenta</div></td>
    	    <td width="40%">
				<span id="datosCuenta">
					<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box">
						<option value="">Cuentas</option>
					</select>
				</span>
			</td>
	</tr>
	<tr>
		<td><div align="right">Descripci&oacute;n</div></td>
		<td>
			<textarea name="txa_descripcion" id="txa_observaciones" maxlength="160" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
            onkeypress="return permite(event,'num_car', 0);" ><?php echo $txa_descripcion;?></textarea></td>
		<td><div align="right">*Hor&oacute;metro/Od&oacute;metro</div></td>
		<td>
			<select name="cmb_metrica" id="cmb_metrica">
				<option <?php if($txt_metrica==""){?> selected="selected"<?php }?> value="">M&eacute;trica</option>
				<option value="HOROMETRO" <?php if($txt_metrica=="HOROMETRO"){?> selected="selected"<?php }?>>HOR&Oacute;METRO</option>
				<option value="ODOMETRO" <?php if($txt_metrica=="ODOMETRO"){?> selected="selected"<?php }?>>OD&Oacute;METRO</option>
			</select>
		</td>
	</tr>
	<tr>
	   <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	</tr>
	<tr>
		<td colspan="4">
			<div align="center">       	    	
			<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
			<input name="btn_agregar" type="submit" class="botones"  value="Agregar" title="Agregar los Datos del Equipo" 
            onMouseOver="window.status='';return true" />
			&nbsp;&nbsp;&nbsp;
			<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true" onclick="cmb_familia.disabled=false;"/> 
			&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Equipos" 
			onMouseOver="window.status='';return true" onclick="location.href='menu_equipos.php'" />
			</div>
		</td>
	</tr>
	</table>
	</form>
	</fieldset>

	<div id="calendario">
		<input type="image" name="fecha_Equipo" id="fecha_Equipo" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_agregarEquipo.txt_fechaFabricacionEquipo,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Fabricaci&oacute;n de Equipo"/> 
	</div>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
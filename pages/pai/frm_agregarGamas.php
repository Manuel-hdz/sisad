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
		//Este archivo contiene las funciones para agregar los datos de una Gama a la Bd de Mantenimiento
		include ("op_agregarGamas.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionPaileria.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>	
	<script type="text/javascript" src="../../includes/jsColor/jscolor.js" ></script>
    <style type="text/css">
		<!--
		#titulo-agregarGama { position:absolute; left:30px; top:146px; width:145px; height:21px; z-index:11; }
		#form-agregarGama { position:absolute; left:30px; top:190px; width:813px; height:280px; z-index:12; }		
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg-gomar.png" width="999" height="30" /></div>	
    <div id="titulo-agregarGama" class="titulo_barra">Agregar Gama</div><?php 
	
	//Si no esta deinida la variable $sbt_complementarGama en el arreglo POST, mostrar el formulario para agregar los datos de la Gama
	if(!isset($_POST['sbt_complementarGama']) && !isset($_GET['btn_guardarGama'])){ 
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
		else if($_SESSION['depto']=="Paileria"){
			$area = "GOMAR";
			$atributo = "disabled='disabled'";
			$estado = 0;
		}
		
		if($estado==0){ ?>		
			<script type="text/javascript" language="javascript">
				//cargarCombo('<?php echo $area;?>','bd_mantenimiento','equipos','familia','area','cmb_familiaGama','Familia','');
			</script>
		<?php } ?>
		
		<fieldset class="borde_seccion" id="form-agregarGama" name="form-agregarGama">
		<legend class="titulo_etiqueta">Agregar Gama</legend>
		<br />
		<form onsubmit="return valFormAgregarGama(this);" name="frm_agregarGama" method="post" action="frm_agregarGamas.php">	
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td width="17%"><div align="right">*Clave de la Gama </div></td>
				<td width="33%">
					<input type="text" name="txt_claveGama" class="caja_de_texto" size="12" maxlength="10" onkeypress="return permite(event,'num_car',1);" 
					onblur="return verificarDatoBD(this,'bd_paileria','gama','id_gama','nom_gama');" />
					<span id="error" class="msj_error">Clave Gama Duplicada</span>			  </td>
			  <td width="20%" rowspan="2"><div align="right">*Descripci&oacute;n</div></td>
				<td width="30%" rowspan="2">
					<textarea name="txa_descripcionGama" id="txa_descripcionGama" cols="30" rows="3" maxlength="120" class="caja_de_texto" 
					onkeypress="return permite(event,'num_car',0);" onkeyup="return ismaxlength(this)" ></textarea>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre de la Gama </div></td>
				<td><input type="text" name="txt_nombreGama" class="caja_de_texto" size="25" maxlength="40" onkeypress="return permite(event,'num_car',0);" /></td>
				
			</tr>
			<tr>
				<td><div align="right">*Area</div></td>
				<td>
					<?php if($estado==1) {?>
						<select name="cmb_areaGama" class="combo_box" onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familiaGama','Familia','');">
							<option value="">&Aacute;rea</option>						
							<option value="CONCRETO">CONCRETO</option>
							<option value="MINA">MINA</option>
							<option value="GOMAR">GOMAR</option>
						</select>
					<?php } else { ?>
						<select name="cmb_areaGama" class="combo_box" onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familiaGama','Familia','');" <?php echo $atributo; ?>>
							<option value="">&Aacute;rea</option>						
							<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
							<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
							<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'"; ?>>GOMAR</option>
						</select>		
						<input type="hidden" name="cmb_areaGama" value="<?php echo $area; ?>" />
					<?php } ?>	
				</td>
				<td width="20%"><div align="right">*Ciclo de Servicio</div></td>
				<td>
					<input name="txt_cicloServ" type="text" class="caja_de_texto" id="txt_cicloServ" onchange="colocarMensaje(this); formatCurrency(this.value,'txt_cicloServ');" 
					onkeypress="return permite(event,'num',2);" size="10" maxlength="15" />
					<input name="txt_msjMetrica" type="text" class="caja_de_texto" id="txt_msjMetrica" size="10" maxlength="15" readonly="readonly" />
				</td>
			</tr>
			<tr>
				<td><div align="right">*Familia</div></td>
				<td>
					<?php	
							$conn = conecta("bd_mantenimiento");
							$sql_stm = "SELECT DISTINCT familia FROM equipos ORDER BY familia";
							$rs = mysql_query($sql_stm);
					?>
					<select name="cmb_familiaGama" class="combo_box" id="cmb_familiaGama" 
					onchange="obtenerDatoBD(this.value,'bd_mantenimiento','equipos','metrica','familia','txt_tipoMetrica');">
						<option value="">Familia</option>
						<?php
							if($datos=mysql_fetch_array($rs)){
								do{
									echo "<option value='$datos[familia]'>$datos[familia]</option>";
								}while($datos=mysql_fetch_array($rs));
							}
							mysql_close($conn);
						?>
				  	</select>
				</td>
				<td width="20%"><div align="right">Tipo de M&eacute;trica</div></td>
				<td><input type="text" name="txt_tipoMetrica" id="txt_tipoMetrica" class="caja_de_texto" readonly="readonly" /></td>
			</tr>
			<tr>
				<td><div align="right">*Color</div></td>
				<td colspan="3">
					<input type="text" name="txt_color" id="txt_color" size="6" maxlength="6"
					onkeypress="return permite(event,'num_car', 3);" value="FFFFFF" class="color" title="Seleccionar Color"/>
				</td>
			</tr>
			<tr>
				<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
					
					<input type="submit" name="sbt_complementarGama" class="botones_largos" value="Complementar Gama" 
					title="Complementar la Informaci&oacute;n de la Gama" onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" class="botones" value="Limpiar" title="Limpiar Formulario"  />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar al Men&uacute; de Gamas" onclick="location.href='menu_gamas.php'"  />
				</td>
			</tr>		
		</table>
		</form>	
</fieldset><?php
	}//Cierre if(!isset($_POST['txt_claveGama']))
	else{		
		//Guardar los datos de la Gama Nueva
		if(isset($_GET['btn_guardarGama']) && $_GET['btn_guardarGama']=="GuardarGama"){
			guardarDatosGama();
		}		
		else{
			//Convertir en Mayusculas los datos de la Gama
			$id_gama = strtoupper($_POST['txt_claveGama']); $nom_gama = strtoupper($_POST['txt_nombreGama']);
			$descripcion = strtoupper($_POST['txa_descripcionGama']);
			//Guardar los datos de la Gama en la SESSION
			$_SESSION['datosGamaNueva'] = array("idGama"=>$id_gama,"nomGama"=>$nom_gama,"descripcion"=>$descripcion,"areaAplicacion"=>$_POST['cmb_areaGama'],
												"familiaAplicacion"=>$_POST['cmb_familiaGama'],"cicloServicio"=>str_replace(",","",$_POST['txt_cicloServ']),"color"=>$_POST["txt_color"]);
			
			//Redireccionar a la Pagina donde serán agreados los Sistemas a la Gama
			echo "<meta http-equiv='refresh' content='0;url=frm_agregarGamasSistema.php'>";		
		}
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		//Este archivo contiene las funciones para guardar los datos de una Gama Modificada en la Bd de Mantenimiento
		include ("op_modificarGamas.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionPaileria.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/jsColor/jscolor.js" ></script>
	
    <style type="text/css">
		<!--
		#titulo-modificarGama { position:absolute; left:30px; top:146px; width:145px; height:21px; z-index:11; }
		#form-seleccionarGama { position:absolute; left:30px; top:190px; width:599px; height:190px; z-index:12; }		
		#from-modificarGama { position:absolute; left:30px; top:190px; width:813px; height:280px; z-index:13; }
		-->
    </style>
</head>
<body>		

	<div id="barra"><img src="../../images/title-bar-bg-gomar.png" width="999" height="30" /></div>	
    <div id="titulo-modificarGama" class="titulo_barra">Modificar Gama</div><?php 
	
	//Guardar los datos de la Gama Nueva
	if(isset($_GET['btn_guardarGama']) && $_GET['btn_guardarGama']=="GuardarGama"){
		guardarDatosGama();
	}		
	else{		
		//Si no esta definida la variable $sbt_consultarGama en el arreglo POST, mostrar el formulario para consultar los datos de la Gama
		if(!isset($_POST['sbt_modificarGama'])){ 		
			/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
			$atributo = "";
			$area = "";
			$estado = 1;//El estado 1 indica que el departamento registrados en la SESSION tiene acceso a la información de MINA y CONCRETO
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
			
			//Cargar las Familias segun el usuario registrado en la SESSION del área de MttoMina o MttoConcreto
			if($estado==0){ ?>
				<script type="text/javascript" language="javascript">
					setTimeout("cargarCombo('<?php echo $area;?>','bd_paileria','gama','familia_aplicacion','area_aplicacion','cmb_familiaGama','Familia','')",500);
				</script><?php 
			} ?>
			
			
			<fieldset class="borde_seccion" id="form-seleccionarGama" name="form-seleccionarGama">
			<legend class="titulo_etiqueta">Seleccionar  Gama a Modificar </legend>
			<br />
			<form onsubmit="return valFormSeleccionarGama(this);" name="frm_seleccionarGama" method="post" action="frm_modificarGamas.php">	
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="10%"><div align="right">Area</div></td>
					<td width="30%"><?php 
						if($estado==1){ ?>
							<select name="cmb_areaGama" class="combo_box" 
							onchange="cargarCombo(this.value,'bd_mantenimiento','gama','familia_aplicacion','area_aplicacion','cmb_familiaGama','Familia','');">
								<option value="">&Aacute;rea</option>							
								<option value="CONCRETO">CONCRETO</option>
								<option value="MINA">MINA</option>
								<option value="GOMAR">GOMAR</option>
							</select><?php 
						} else { ?>
							<select name="cmb_areaGama" class="combo_box" 
							onchange="cargarCombo(this.value,'bd_mantenimiento','gama','familia_aplicacion','area_aplicacion','cmb_familiaGama','Familia','');" 
							<?php echo $atributo;?>>
								<option value="">&Aacute;rea</option>						
								<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
								<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
								<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'"; ?>>GOMAR</option>
							</select>		
							<input type="hidden" name="cmb_areaGama" value="<?php echo $area; ?>" /><?php 
						} ?>
					</td>
					<td width="20%"><div align="right">Clave de la Gama </div></td>
					<td width="30%">
						<select name="cmb_claveGama" class="combo_box" id="cmb_claveGama" >
							<option value="">Clave</option>
						</select>				
					</td>
				</tr>
				<tr>
					<td><div align="right">Familia </div></td>
					<td>
						<select name="cmb_familiaGama" class="combo_box" id="cmb_familiaGama" 
						onchange="
				cargarComboEspecifico(this.value,'bd_paileria','gama','id_gama','familia_aplicacion','area_aplicacion','<?php echo $area; ?>','cmb_claveGama','Clave','');
						">
							<option value="">Familia</option>
						</select>				
					</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>				
				</tr>			
				<tr>
					<td colspan="4" align="center">										
					  	<input name="sbt_modificarGama" type="submit" class="botones" id="sbt_modificarGama" title="Modificar Gama Seleccionada" 
					  	onmouseover="window.status='';return true" value="Modificar Gama" />
						&nbsp;&nbsp;&nbsp;
						<input type="button" name="btn_regresar2" class="botones" value="Regresar" title="Regresar al Men&uacute; de Gamas" 
						onclick="location.href='menu_gamas.php'" />
					</td>
				</tr>		
			</table>
			</form>	
			</fieldset><?php
		}//Cierre if(!isset($_POST['sbt_modificarGama']))
		else if(isset($_POST['sbt_modificarGama']) && !isset($_POST['sbt_modificarDetalleGama'])){//Extraer y Mostrar los datos de la Gama para que el usuario pueda modificarlos		
			//Conectarse con la Base de Datos
			$conn = conecta("bd_paileria");
			//Extraer los datos
			$datos_gama = mysql_fetch_array(mysql_query("SELECT * FROM gama WHERE id_gama = '$_POST[cmb_claveGama]'"));
			//Obtener los datos del Arreglo
			$id_gama = $datos_gama['id_gama'];
			$nom_gama = $datos_gama['nom_gama'];
			$descripcion = $datos_gama['descripcion'];
			$area = $datos_gama['area_aplicacion'];
			$familia = $datos_gama['familia_aplicacion'];
			$ciclo = $datos_gama['ciclo_servicio'];
			$color = $datos_gama['color'];
			
			//Determinar si es horometro u odometro el tipo de metrica
			$metrica = obtenerDato("bd_mantenimiento", "equipos", "metrica", "familia", $familia);
			$mensaje = "";
			if($metrica=="ODOMETRO") $mensaje = "Kil&oacute;metros";
			else if($metrica=="HOROMETRO") $mensaje = "Horas";
												
			//Cerrar la Conexion con la BD
			mysql_close($conn);
			
			/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
			$atributo = "";
			$estado = 1;//El estado 1 Indica que el usuario con la SESSION abierta es AuxMtto
			if($_SESSION['depto']=="MttoConcreto"){				
				$atributo = "disabled='disabled'";
				$estado = 0;
			}
			else if($_SESSION['depto']=="MttoMina"){
				$atributo = "disabled='disabled'";
				$estado = 0;
			}
			else if($_SESSION['depto']=="Paileria"){
				$atributo = "disabled='disabled'";
				$estado = 0;
			}?>
			
			<script type="text/javascript" language="javascript">
				//cargarCombo('<?php echo $area;?>','bd_mantenimiento','equipos','familia','area','cmb_familiaGama','Familia','<?php echo $familia;?>');
			</script>		
			<fieldset class="borde_seccion" id="from-modificarGama" name="from-modificarGama">
			<legend class="titulo_etiqueta">Modifcar Datos Gama <em><u><?php echo $id_gama; ?></u></em></legend>
			<br />
			
			<form onsubmit="return valFormModificarGama(this);" name="frm_modificarGama" method="post" action="frm_modificarGamas.php">	
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="20%"><div align="right">*Clave de la Gama</div></td>
					<td width="35%">
						<input type="text" name="txt_claveGama" class="caja_de_texto" size="12" maxlength="10" onkeypress="return permite(event,'num_car',1);" 
						value="<?php echo $id_gama ?>" />
						<input type="hidden" name="hdn_claveGama" value="<?php echo $id_gama ?>" />
					</td>
					<td width="15%" rowspan="2"><div align="right">*Descripci&oacute;n</div></td>
					<td width="30%" rowspan="2">
						<textarea name="txa_descripcionGama" id="txa_descripcionGama" cols="30" rows="3" maxlength="120" class="caja_de_texto" 
						onkeypress="return permite(event,'num_car',0);" onkeyup="return ismaxlength(this)" ><?php echo $descripcion; ?></textarea>				
					</td>
				</tr>
				<tr>
					<td><div align="right">*Nombre de la Gama </div></td>
					<td>
						<input type="text" name="txt_nombreGama" class="caja_de_texto" size="25" maxlength="40" onkeypress="return permite(event,'num_car',0);" 
						value="<?php echo $nom_gama; ?>" />				
					</td>				
				</tr>
				<tr>
					<td><div align="right">*Area</div></td>
					<td><?php 
						if($estado==1){ ?>
							<select name="cmb_areaGama" class="combo_box" 
							onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familiaGama','Familia','');">
								<option value="">&Aacute;rea</option>
								<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
								<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
								<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'"; ?>>GOMAR</option>
							</select><?php 
						} else { ?>
							<select name="cmb_areaGama" class="combo_box" 
							onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familiaGama','Familia','');" <?php echo $atributo; ?>>
								<option value="">&Aacute;rea</option>						
								<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
								<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
								<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'"; ?>>GOMAR</option>
							</select>		
							<input type="hidden" name="cmb_areaGama" value="<?php echo $area; ?>" /><?php 
						} ?>
					</td>
					<td><div align="right">*Ciclo Servicio</div></td>
					<td>
						<input name="txt_cicloServ" type="text" class="caja_de_texto" id="txt_cicloServ" 
						onchange="colocarMensaje(this); formatCurrency(this.value,'txt_cicloServ')" 
						onkeypress="return permite(event,'num',2);" size="10" maxlength="15" value="<?php echo number_format($ciclo,2,".",","); ?>" />
						<input name="txt_msjMetrica" type="text" class="caja_de_texto" id="txt_msjMetrica" size="10" maxlength="15" readonly="readonly" 
						value="<?php echo $mensaje; ?>" />
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
									if($datos["familia"] == $familia)
										echo "<option value='$datos[familia]' selected='selected'>$datos[familia]</option>";
									else
										echo "<option value='$datos[familia]'>$datos[familia]</option>";
								}while($datos=mysql_fetch_array($rs));
							}
							mysql_close($conn);
							?>
						</select>				
					</td>
					<td><div align="right">Tipo de M&eacute;trica</div></td>
					<td><input type="text" name="txt_tipoMetrica" id="txt_tipoMetrica" class="caja_de_texto" readonly="readonly" value="<?php echo $metrica; ?>" /></td>
				</tr>
				<tr>
					<td><div align="right">*Color</div></td>
					<td colspan="3">
						<input type="text" name="txt_color" id="txt_color" size="6" maxlength="6"
						onkeypress="return permite(event,'num_car', 3);" value="<?php echo $color?>" class="color" title="Seleccionar Color"/>
					</td>
				</tr>
				<tr>
					<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
				<tr>
					<td colspan="4" align="center">					
						<input type="hidden" name="sbt_modificarGama" value="" />					
						<input type="submit" name="sbt_modificarDetalleGama" class="botones_largos" value="Modificar Detalle Gama" 
						title="Modificar la Informaci&oacute;n de la Gama" onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<!-- <input type="reset" name="rst_restablecer" class="botones" value="Restablecer" title="Restablecer Datos del Formulario" 
						onclick="cargarCombo('<?php echo $area;?>','bd_mantenimiento','gama','familia_aplicacion','area_aplicacion','cmb_familiaGama','Familia','<?php echo $familia;?>');" /> -->
						<input type="reset" name="rst_restablecer" class="botones" value="Restablecer" title="Restablecer Datos del Formulario" />
						&nbsp;&nbsp;&nbsp;
						<input type="button" name="btn_cancelar" class="botones" value="Cancelar" title="Cancelar la Modificaci&oacute;n de la Gama" 
						onclick="confirmarSalida('menu_gamas.php');"  />				
					</td>
				</tr>		
			</table>
			</form>	
			</fieldset><?php		
		}//Cierre else if(isset($_POST['sbt_modificarGama']))
		else if(isset($_POST['sbt_modificarDetalleGama'])){				
			//Convertir en Mayusculas los datos de la Gama
			$id_gama = strtoupper($_POST['txt_claveGama']); $nom_gama = strtoupper($_POST['txt_nombreGama']);
			$descripcion = strtoupper($_POST['txa_descripcionGama']);
			//Guardar los datos de la Gama en la SESSION
			$_SESSION['datosGamaModificada'] = array("idGama"=>$id_gama,"nomGama"=>$nom_gama,"descripcion"=>$descripcion,"areaAplicacion"=>$_POST['cmb_areaGama'],
													 "familiaAplicacion"=>$_POST['cmb_familiaGama'],"cicloServicio"=>str_replace(",","",$_POST['txt_cicloServ']),
													 "idGamaAnt"=>$_POST['hdn_claveGama'],"color"=>$_POST["txt_color"]);
			
			//Redireccionar a la Pagina donde serán modificados los Sistemas de la Gama
			echo "<meta http-equiv='refresh' content='0;url=frm_modificarGamasSistema.php'>";					
			
		}//Cierre else if(isset($_POST['sbt_modificarDetalleGama']))		
	}//Cierre Else del if(isset($_GET['btn_guardarGama']) && $_GET['btn_guardarGama']=="GuardarGama")?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
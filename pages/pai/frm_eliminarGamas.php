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
		include ("op_eliminarGamas.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionPaileria.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
    <style type="text/css">
		<!--
		#titulo-eliminarGama { position:absolute; left:30px; top:146px; width:145px; height:21px; z-index:11; }
		#form-eliminarGama { position:absolute; left:30px; top:190px; width:667px; height:226px; z-index:12; }		
		-->
    </style>
</head><?php

	//Obtener el Area seleccionada cuando la pagina se recarga
	$area = "";
	if(isset($_POST['cmb_areaGama']) && $_POST['cmb_areaGama']!="")
		$area = $_POST['cmb_areaGama'];
	
	//Obtener la Familia seleccionada cuando la pagina se recarga
	$familia = "";
	if(isset($_POST['cmb_familiaGama']) && $_POST['cmb_familiaGama']!="")
		$familia = $_POST['cmb_familiaGama'];
		
	//Obtener la Clave de la Gama seleccionada cuando la pagina se recarga
	$claveGama = "";
	if(isset($_POST['cmb_claveGama']) && $_POST['cmb_claveGama']!="")
		$claveGama = $_POST['cmb_claveGama'];?>
		
		
<body>
	<script type="text/javascript" language="javascript" >
		window.onload = function(){<?php 
			if($area!=""){//Cargar el Combo de familias segun el area seleccionada ?>
				cargarCombo("<?php echo $area; ?>","bd_paileria","gama","familia_aplicacion","area_aplicacion","cmb_familiaGama","Familia","<?php echo $familia; ?>");<?php 
			}
			if($familia!=""){//Cargar el combo de Claves de las Gamas segun la Familia y Area seleccionadas ?>
				setTimeout("cargarComboEspecifico('<?php echo $familia; ?>','bd_paileria','gama','id_gama','familia_aplicacion','area_aplicacion','<?php echo $area; ?>','cmb_claveGama','Clave','<?php echo $claveGama; ?>');",500);<?php 
			}?>
		}
	</script>

	<div id="barra"><img src="../../images/title-bar-bg-gomar.png" width="999" height="30" /></div>	
    <div id="titulo-eliminarGama" class="titulo_barra">Eliminar Gama</div><?php 
	
	//Si no esta definida la variable $sbt_complementarGama en el arreglo POST, mostrar el formulario para agregar los datos de la Gama
	if(!isset($_POST['sbt_borrarGama'])){ 
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
			</script>
		<?php } ?>
		
		
		<fieldset class="borde_seccion" id="form-eliminarGama" name="form-eliminarGama">
		<legend class="titulo_etiqueta">Seleccionar la Gama a Eliminar</legend>
		<br />
		<form onsubmit="return valFormEliminarGama(this);" name="frm_eliminarGama" method="post" action="frm_eliminarGamas.php">	
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td width="10%"><div align="right">Area</div></td>
				<td width="30%">
					<?php if($estado==1){ ?>
						<select name="cmb_areaGama" class="combo_box" 
						onchange="cargarCombo(this.value,'bd_mantenimiento','gama','familia_aplicacion','area_aplicacion','cmb_familiaGama','Familia','');">
							<option value="">&Aacute;rea</option>						
							<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'";?>>CONCRETO</option>
							<option value="MINA" <?php if($area=="MINA") echo "selected='selected'";?>>MINA</option>
							<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'";?>>GOMAR</option>
						</select>
					<?php } else { ?>
						<select name="cmb_areaGama" class="combo_box" 
						onchange="cargarCombo(this.value,'bd_mantenimiento','gama','familia_aplicacion','area_aplicacion','cmb_familiaGama','Familia','');" 
						<?php echo $atributo; ?>>
							<option value="">&Aacute;rea</option>						
							<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
							<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
							<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'"; ?>>GOMAR</option>
						</select>		
						<input type="hidden" name="cmb_areaGama" value="<?php echo $area; ?>" />
					<?php } ?>
				</td>
				<td width="20%"><div align="right">Clave de la Gama </div></td>
				<td width="30%">
					<select name="cmb_claveGama" class="combo_box" id="cmb_claveGama" onchange="javascript:document.frm_eliminarGama.submit();">
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
				<td rowspan="2"><div align="right">Descripci&oacute;n</div></td>
				<td rowspan="2">					
					<?php 
					$descripcion = "";
					if(isset($_POST['cmb_claveGama']) && $_POST['cmb_claveGama']!="")
						$descripcion = obtenerDato("bd_paileria", "gama", "descripcion", "id_gama", $cmb_claveGama); ?>
					<textarea name="txa_descripcionGama" id="txa_descripcionGama" cols="40" rows="4" class="caja_de_texto" readonly="readonly"><?php echo $descripcion; ?></textarea>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>				
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" align="center">										
					<input type="submit" name="sbt_borrarGama" class="botones" value="Eliminar Gama" title="Eliminar Gama Seleccionada" onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar al Men&uacute; de Gamas" onclick="location.href='menu_gamas.php'"  />				
				</td>
			</tr>		
		</table>
		</form>	
	</fieldset><?php
	}//Cierre if(!isset($_POST['sbt_borrarGama']))
	else{
		//Eliminar la Gama Seleccionada		
		borrarGama();
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
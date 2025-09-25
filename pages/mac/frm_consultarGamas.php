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
		include ("op_consultarGamas.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
    <style type="text/css">
		<!--
		#titulo-consultarGama { position:absolute; left:30px; top:146px; width:145px; height:21px; z-index:11; }
		#form-consultarGama { position:absolute; left:30px; top:190px; width:667px; height:226px; z-index:12; }		
		#ver-detalleGama { position:absolute; left:32px; top:190px; width:940px; height:418px; z-index:13; overflow:scroll; }
		#capa-botones { position:absolute; left:30px; top:670px; width:980px; height:50px; z-index:14; }
		-->
    </style>
</head><?php

	//Obtener el Area
	$area = "";
	if(isset($_POST['cmb_areaGama']) && $_POST['cmb_areaGama']!="")
		$area = $_POST['cmb_areaGama'];
	
	//Obtener la Familia
	$familia = "";
	if(isset($_POST['cmb_familiaGama']) && $_POST['cmb_familiaGama']!="")
		$familia = $_POST['cmb_familiaGama'];
		
	//Obtener la Clave de la Gama
	$claveGama = "";
	if(isset($_POST['cmb_claveGama']) && $_POST['cmb_claveGama']!="")
		$claveGama = $_POST['cmb_claveGama'];?>
<body>			

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>	
    <div id="titulo-consultarGama" class="titulo_barra">Consultar Gama</div><?php 
	
	//Si no esta definida la variable $sbt_consultarGama en el arreglo POST, mostrar el formulario para consultar los datos de la Gama
	if(!isset($_POST['sbt_consultarGama']) && !isset($_POST['sbt_gamasRelacionadas'])){ 
		/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
		$atributo = "";
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
		
		
		//Cargar las Familias segun el usuario registrado en la SESSION del área de MttoMina o MttoConcreto
		if($estado==0){ ?>		
			<script type="text/javascript" language="javascript">
				setTimeout("cargarCombo('<?php echo $area;?>','bd_mantenimiento','gama','familia_aplicacion','area_aplicacion','cmb_familiaGama','Familia','<?php echo $familia; ?>')",500);
			</script><?php 
		}//Cargar el combo de Familias según el área seleccionada cuando la pagina se recarga.
		else if($area!=""){?>
			<script type="text/javascript" language="javascript">
				setTimeout("cargarCombo('<?php echo $area;?>','bd_mantenimiento','gama','familia_aplicacion','area_aplicacion','cmb_familiaGama','Familia','<?php echo $familia; ?>')",500);
			</script><?php 
		}?>
		
				
		<script type="text/javascript" language="javascript" >
		window.onload = function(){ 
			<?php //Cargar el Combo de claves de las Gamas según la Familia y Área seleccionadas cuando la página se recarga
			if($familia!=""){?>
				setTimeout("cargarComboEspecifico('<?php echo $familia; ?>','bd_mantenimiento','gama','id_gama','familia_aplicacion','area_aplicacion','<?php echo $area; ?>','cmb_claveGama','Clave','<?php echo $claveGama; ?>');",500);
			<?php } ?>
		}
		</script>
		
		
		<fieldset class="borde_seccion" id="form-consultarGama" name="form-consultarGama">
		<legend class="titulo_etiqueta">Consultar Gama por Familia de Equipos</legend>
		<br />
		<form onsubmit="return valFormConsultarGama(this);" name="frm_consultarGama" method="post" action="frm_consultarGamas.php">	
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
						</select>
					<?php } else { ?>
						<select name="cmb_areaGama" class="combo_box" 
						onchange="cargarCombo(this.value,'bd_mantenimiento','gama','familia_aplicacion','area_aplicacion','cmb_familiaGama','Familia','');" <?php echo $atributo;?>>
							<option value="">&Aacute;rea</option>						
							<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
							<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
						</select>		
						<input type="hidden" name="cmb_areaGama" value="<?php echo $area; ?>" />
					<?php } ?>
				</td>
				<td width="20%"><div align="right">Clave de la Gama </div></td>
				<td width="30%">
					<select name="cmb_claveGama" class="combo_box" id="cmb_claveGama" onchange="javascript:document.frm_consultarGama.submit();">
                  		<option value="">Clave</option>
                	</select>
				</td>
			</tr>
			<tr>
				<td><div align="right">Familia </div></td>
				<td>
					<select name="cmb_familiaGama" class="combo_box" id="cmb_familiaGama" 
					onchange="
					cargarComboEspecifico(this.value,'bd_mantenimiento','gama','id_gama','familia_aplicacion','area_aplicacion','<?php echo $area; ?>','cmb_claveGama','Clave','');
					">
                  		<option value="">Familia</option>
                	</select>
				</td>
				<td rowspan="2"><div align="right">Descripci&oacute;n</div></td>
				<td rowspan="2"><?php 
					$descripcion = "";
					if(isset($_POST['cmb_claveGama']) && $_POST['cmb_claveGama']!="")
						$descripcion = obtenerDato("bd_mantenimiento", "gama", "descripcion", "id_gama", $cmb_claveGama); ?>
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
					<input type="submit" name="sbt_consultarGama" class="botones" value="Consultar Gama" title="Consultar Gama Seleccionada" onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar al Men&uacute; de Gamas" onclick="location.href='menu_gamas.php'"  />				
				</td>
			</tr>		
		</table>
		</form>	
		</fieldset><?php
	}//Cierre if(!isset($_POST['sbt_consultarGama']) && !isset($_POST['sbt_gamasRelacionadas']))
	else{		
		
		//Mostrar el Detalle de la Gama seleccionada
		if(isset($_POST['sbt_consultarGama'])){?>
			<form name="frm_detalleGamas" method="post" action="frm_consultarGamas.php"> 
				<div id="ver-detalleGama" class="borde_seccion2" align="center"><?php 
					//Consultar la Gama Seleccionada		
					mostrarGamaSeleccionada();
				?></div>
				<div id="capa-botones" align="center">
					<input type="button" name="btn_regresar" value="Regresar" title="Regresar a la P&aacute;gina de Consulta de Gamas" class="botones" 
					onclick="location.href='frm_consultarGamas.php'" />
					&nbsp;&nbsp;
					<input type="submit" name="sbt_gamasRelacionadas" value="Gamas Relacionadas" title="Ver Gamas Relacionadas" class="botones_largos" onmouseover="window.status='';return true"/>
				</div>
			</form><?php 			
		}
		
		if(isset($_POST['sbt_gamasRelacionadas'])){?>
			<form name="frm_gamasRelacionadas" method="post" action="frm_consultarGamas.php"> 
				<div id="ver-detalleGama" class="borde_seccion2" align="center"><?php 
					//Consultar la Gama Seleccionada		
					mostrarGamaRelacionadas($_POST['hdn_area'],$_POST['hdn_familia']);
				?></div>
				<div id="capa-botones" align="center">
					<input type="hidden" name="sbt_consultarGama" value="" />
					<input type="button" name="btn_regresar" value="Regresar" title="Regresar a la P&aacute;gina de Consulta de Gamas" class="botones" onclick="location.href='frm_consultarGamas.php'" />
			  </div>
			</form><?php 			
		}
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
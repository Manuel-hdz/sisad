<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php

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
		//Archivo con la operacion de Consultar Equipo, se incluye para eliminarlo posteriormente
		include ("op_consultarEquipo.php");	
		//Archivo con la Operacionn de Dar de baja el Equipo
		include ("op_eliminarEquipo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquipoMtto.js"></script>	
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-eliminar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-eliminarEquipo { position:absolute; left:30px; top:190px; width:370px; height:200px; z-index:12; padding:15px; padding-top:0px;}
		#tabla-eliminarEquipoClave {position:absolute; left:460px; top:190px; width:370px; height:200px; z-index:13; padding:15px; padding-top:0px;}
		#resultados {position:absolute; left:30px; top:420px; width:800px; height:200px; z-index:14; padding:15px; padding-top:0px; overflow:scroll;}
		#botones{position:absolute;left:30px;top:650px;width:800px;height:37px;z-index:15;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-eliminar">Eliminar Equipo</div><?php 
	
	
	//Comprobamos si el boton de eliminar ya ha sido enviado en el POST, si es asi, eliminar el Equipo seleccionado
	if(isset($_POST["sbt_eliminar"])){
		bajaEquipo($_POST["hdn_clave"]);
	}
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
	
	if($estado==0){ ?>		
		<script type="text/javascript" language="javascript">
			setTimeout("cargarCombo('<?php echo $area;?>','bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','')",500);
		</script><?php 
	} ?>
	
	
	<fieldset class="borde_seccion" id="tabla-eliminarEquipo" name="tabla-eliminarEquipo">
	<legend class="titulo_etiqueta">Eliminar Equipo</legend>	
	<br>
	<form name="frm_elegirEquipo" action="frm_eliminarEquipo.php" method="post" onsubmit="return valFormEliminaEquipo(this);">
	<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td width="36%"><div align="right">&Aacute;rea</div></td>
			<td width="64%">
				<?php if($estado==1) {?>
					<select name="cmb_area" id="cmb_area" class="combo_box" 
					onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');">
						<option value="">&Aacute;rea</option>						
						<option value="CONCRETO">CONCRETO</option>
						<option value="MINA">MINA</option>
					</select>
				<?php } else { ?>
					<select name="cmb_area" id="cmb_area" class="combo_box" 
					onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');" 
					<?php echo $atributo; ?>>
						<option value="">&Aacute;rea</option>						
						<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
						<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
					</select>		
					<input type="hidden" name="cmb_area" value="<?php echo $area; ?>" />
				<?php } ?>	
			</td>
		</tr>
		<tr>
			<td><div align="right">Familia</div></td>
			<td>
				<select name="cmb_familia" id="cmb_familia" onchange="cargarEquiposFamilia(this.value,'<?php echo $area; ?>','cmb_claveEquipo','Clave','');">
					<option value="">Familia</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><div align="right">Clave del Equipo</div></td>
			<td>
				<select name="cmb_claveEquipo" id="cmb_claveEquipo">
					<option value="">Clave</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><br/>
				<input type="submit" name="sbt_buscar" title="Buscar el Equipo a Eliminar" value="Buscar" class="botones" onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_cancelar" title="Regresar al Men&uacute; de Equipos" onclick="location.href='menu_equipos.php';" 
				value="Cancelar" class="botones" onmouseover="window.status='';return true;"/>
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
	<fieldset class="borde_seccion" id="tabla-eliminarEquipoClave" name="tabla-eliminarEquipoClave">
	<legend class="titulo_etiqueta">Eliminar Equipo por Clave</legend>
	<br>
	<form name="frm_elegirEquipoClave" action="frm_eliminarEquipo.php" method="post" onsubmit="return valFormEliminaEquipoXClave(this);">
	<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
	<tr>
		<td>Clave del Equipo</td>
		<td>
			<input type="text" name="txt_clave" id="txt_clave" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car', 3);"/>
			<?php if ($estado==0){?>
				<input type="hidden" name="hdn_area" value="<?php echo $area; ?>" />
			<?php }?>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="submit" name="sbt_buscarXClave" title="Buscar el Equipo a Eliminar con la Clave Escrita" value="Buscar" class="botones" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" name="btn_cancelar" title="Regresar al Men&uacute; de Equipos" onclick="location.href='menu_equipos.php';" value="Cancelar" class="botones" onmouseover="window.status='';return true;"/>
		</td>
	</tr>
	</table>
	</form>
	</fieldset>
	
	<?php 
	if (isset($_POST["sbt_buscar"]) || isset($_POST["sbt_buscarXClave"])) {?>
		<form name="frm_eliminarEquipo" method="post" action="frm_eliminarEquipo.php">
		<div id="resultados" class="borde_seccion2">
		<?php
			//Inicializamos res
			$res=0;
			//Recuperar la clave del Equipo
			if (isset($_POST["sbt_buscar"]))
				$clave=$_POST["cmb_claveEquipo"];
			else
				$clave=$_POST["txt_clave"];
			$clave=strtoupper($clave);
			//Mandamos llamar la funcion de verEquipo con la clave del Equipo
			//Si res es igual a 1, mostrar el boton de Eliminar, de lo contrario no.
			$res=verEquipo($clave);
			//Cierre del DIV de resultados
			echo "</div>";
			if ($res==1 || $res==2){?>
				<div id="botones" align="center"/>
				<input type="hidden" name="hdn_clave" id="hdn_clave" value="<?php echo $clave;?>"/>
				<?php 
				if ($res==1){?>
					<input type="submit" name="sbt_eliminar" title="Eliminar el Equipo" value="Eliminar" onmouseover="window.status='';return true;" class="botones"/>
				<?php 
				}
				else {?>
					<input type="submit" name="sbt_eliminar" title="El Equipo ya ha Sido Dado de Baja" value="Eliminar" disabled="disabled" onmouseover="window.status='';return true;" class="botones"/>
				<?php } ?>
				</div>
			<?php } ?>
		</form>
	<?php } ?>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
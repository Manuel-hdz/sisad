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
		//Archivo con la operacion de Consultar Equipo
		include ("op_consultarEquipo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-consultar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-consultarEquipo { position:absolute; left:30px; top:190px; width:370px; height:125px; z-index:12; padding:15px; padding-top:0px;}
		#tabla-consultarEquipoClave {position:absolute; left:530px; top:190px; width:370px; height:125px; z-index:13; padding:15px; padding-top:0px;}
		#tabla-consultarEquipoFamilia{position:absolute; left:30px; top:400px; width:370px; height:125px; z-index:14; padding:15px; padding-top:0px;}
		#tabla-consultarEquipoTodo{position:absolute; left:530px; top:400px; width:370px; height:125px; z-index:15; padding:15px; padding-top:0px;}
		#botones{position:absolute;left:30px;top:600px;width:900px;height:37px;z-index:16;}
		#resultados{position:absolute; left:30px; top:190px; width:950px; height:440px; z-index:17; padding:15px; padding-top:0px; overflow:scroll;}
		#botones2{position:absolute;left:30px;top:666px;width:999px;height:37px;z-index:18;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Consultar Equipo</div>
	
	<?php
	//Si no esta definido ningun boton, entonces no se ha solicitado ninguna busqueda, mostrar las opciones de consulta
	if (!isset($_POST["sbt_buscarXArea"]) && !isset($_POST["sbt_buscarXClave"]) && !isset($_POST["sbt_buscarXFamilia"]) && !isset($_POST["sbt_buscarTodo"])){
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
			</script>
		<?php }
		//Consultar equipo por AREA ?>
		<fieldset class="borde_seccion" id="tabla-consultarEquipo" name="tabla-consultarEquipo">
			<legend class="titulo_etiqueta">Consultar Equipos por &Aacute;rea</legend>	
			<br>
			<form name="frm_consultarEquipoArea" action="frm_consultarEquipo.php" method="post" onsubmit="return valFormConsultaEquipoXArea(this);">
			<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<td width="36%"><div align="right">&Aacute;rea</div></td>
				<td width="64%">
					<?php if($estado==1) {?>
					<select name="cmb_area" class="combo_box">
						<option value="">&Aacute;rea</option>						
						<option value="CONCRETO">CONCRETO</option>
						<option value="MINA">MINA</option>
					</select>
					<?php } else { ?>
					<select name="cmb_area" class="combo_box" <?php echo $atributo; ?>>
						<option value="">&Aacute;rea</option>						
						<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
						<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
					</select>		
					<input type="hidden" name="cmb_area" value="<?php echo $area; ?>" />
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><br/>
					<input type="submit" name="sbt_buscarXArea" title="Consultar Equipo Mediante el &Aacute;rea" value="Buscar" class="botones" 
					onmouseover="window.status='';return true;"/>
				</td>
			</tr>
			</table>
			</form>
		</fieldset>
	
		<?php //Consultar Equipo por Clave ?>
		<fieldset class="borde_seccion" id="tabla-consultarEquipoClave" name="tabla-consultarEquipoClave">
			<legend class="titulo_etiqueta">Consultar Equipos por Clave</legend>
			<br>
			<form name="frm_consultarEquipoClave" action="frm_consultarEquipo.php" method="post" onsubmit="return valFormConsultaEquipoXClave(this);">
				<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
				<tr>
					<td>Clave del Equipo</td>
					<td>
						<input type="text" name="txt_clave" id="txt_clave" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car', 3);"/>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" name="sbt_buscarXClave" title="Consultar Equipo Mediante la Clave" value="Buscar" class="botones" 
						onmouseover="window.status='';return true;"/><?php 
						if ($estado==0){?>
							<input type="hidden" name="hdn_area" value="<?php echo $area; ?>" /><?php 
						}?>
					</td>
				</tr>
				</table>
			</form>
		</fieldset>

		<?php //Consultar Equipo por Familia ?>
		<fieldset class="borde_seccion" id="tabla-consultarEquipoFamilia" name="tabla-consultarEquipoFamilia">
			<legend class="titulo_etiqueta">Consultar Equipo por Familia</legend>
			<br>
			<form name="frm_consultarEquipoFamilia" action="frm_consultarEquipo.php" method="post" onsubmit="return valFormConsultaEquipoXFamilia(this);">
			<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<td width="37%">Familias de Equipos</td>
				<td width="63%">
					<?php $result = cargarComboEspecifico("cmb_familia","familia","equipos","bd_mantenimiento",$area,"area","Familia","");
					if($result==0){ ?>
						<select name="cmb_familia" id="cmb_familia" class="combo_box">
							<option value="">Familia</option>
						</select>
					<?php }?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="sbt_buscarXFamilia" title="Consultar Equipo Mediante la Familia" value="Buscar" class="botones" 
					onmouseover="window.status='';return true;"/><?php 
					if ($estado==0){?>
						<input type="hidden" name="hdn_area" value="<?php echo $area; ?>" /><?php 
					}?>
				</td>
			</tr>
			</table>
			</form>
		</fieldset>

		<?php //Boton de Cancelar?>
		<div id="botones" align="center">
			<input type="button" name="btn_cancelar" title="Regresar al Men&uacute; de Equipos" onclick="location.href='menu_equipos.php';" 
			value="Cancelar" class="botones" onmouseover="window.status='';return true;"/>
		</div><?php 
	}
	else{
		//Variable que indica mediante que patron se hara la consulta de Equipo
		$patron=0;
		if (isset($_POST["sbt_buscarXArea"]))
			$patron=1;
		if (isset($_POST["sbt_buscarXClave"]))
			$patron=2;
		if (isset($_POST["sbt_buscarXFamilia"]))
			$patron=3;
		echo "<div id='resultados' class='borde_seccion2'>";
			$res=mostrarEquipos($patron);
		echo "</div>";?>
		
		<div id="botones2" align="center">
			<?php
			if($res!="0"){
			?>
			<input type="button" name="btn_exportar" title="Exportar a Excel el Listado de los Equipos" id="btn_exportar" 
			onclick="location.href='guardar_reporte.php?tipoRep=RepEquipos&patron=<?php echo $patron?>&valPatron=<?php echo $res?>';"
			value="Exportar a Excel" class="botones"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php
			}
			?>
			<input type="button" name="btn_regresar" title="Regresar a Consultar Equipos" onclick="location.href='frm_consultarEquipo.php';" 
			value="Regresar" class="botones" onmouseover="window.status='';return true;"/>
		</div><?php
	}?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
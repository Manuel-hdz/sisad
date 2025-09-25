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
		//Archivo con la Operacionn de Modificar el Equipo
		include ("op_modificarEquipo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquipoMtto.js"></script>
	<!-- se anexa este archivo para obtener las funciones necesarias para el control de costos -->
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<?php //Elementos a mostrarse en el archivo de Operaciones?>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_proveedores.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>

    <style type="text/css">
		<!--
		#titulo-modificar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11;}
		#tabla-modificarEquipo { position:absolute; left:30px; top:190px; width:370px; height:200px; z-index:12; padding:15px; padding-top:0px;}
		#tabla-modificarEquipoClave {position:absolute; left:460px; top:190px; width:370px; height:200px; z-index:13; padding:15px; padding-top:0px;}
		#botones{position:absolute;left:30px;top:650px;width:800px;height:37px;z-index:14;}
		<?php //estilo de los elementos a mostrarse en el archivo de op_modificarEquipo.php?>
		#tabla-modificarEquipoDatos { position:absolute; left:30px; top:190px; width:908px; height:494px; z-index:15; padding:15px; padding-top:0px;}
		#calendario {position:absolute;left:730px;top:238px;width:30px;height:26px;z-index:16;}
		#res-spider1 { position:absolute; left:575px; top:290px; width:10px; height:10px; z-index:13; }
		#res-spider2 { position:absolute; left:575px; top:335px; width:10px; height:10px; z-index:13; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Equipo</div><?php
	
	
	//Verificar que se haya presionado el boton de Modificar, este boton aparece en el formulario op_modificarEquipo.php
	if (isset($_POST["btn_modificar"]))
		guardarCambios();
	if (isset($_POST["sbt_modificarDocs"])){
		$clave=$_POST["txt_clave"];
		echo "<meta http-equiv='refresh' content='0;url=frm_modificarEquipoDocs.php?clave=$clave'>";
	}
	//Verificar que no se haya presionado ningun boton para mostrar el formulario
	if (!isset($_POST["sbt_buscar"]) && !isset($_POST["sbt_buscarXClave"]) && !isset($_GET["clave"])){
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
		}
		
		//Modificar Equipo por Combo con Claves ?>
		<fieldset class="borde_seccion" id="tabla-modificarEquipo">
		<legend class="titulo_etiqueta">Seleccionar Equipo</legend>	
		<br>
		<form name="frm_seleccionarEquipo" action="frm_modificarEquipo.php" method="post" onsubmit="return valFormModificarEquipo(this);" >
		<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td width="36%"><div align="right">&Aacute;rea</div></td>
			<td width="64%">
				<?php if($estado==1) {?>
				<select name="cmb_area" class="combo_box" onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');">
					<option value="">&Aacute;rea</option>						
					<option value="CONCRETO">CONCRETO</option>
					<option value="MINA">MINA</option>
				</select>
				<?php } else { ?>
				<select name="cmb_area" class="combo_box" onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');" 
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
				<input type="submit" name="sbt_buscar" title="Buscar el Equipo a Modificar" value="Buscar" class="botones" onmouseover="window.status='';return true;" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_cancelar" title="Regresar al Men&uacute; de Equipos" onclick="location.href='menu_equipos.php';" 
				value="Cancelar" class="botones" onmouseover="window.status='';return true;"/>
			</td>
		</tr>
		</table>
		</form>
		</fieldset>
	
		<?php //Modificar Equipo por Clave Escrita?>
		<fieldset class="borde_seccion" id="tabla-modificarEquipoClave">
		<legend class="titulo_etiqueta">Seleccionar Equipo por Clave</legend>
		<br>
		<form name="frm_seleccionarEquipoClave" action="frm_modificarEquipo.php" method="post" onsubmit="return valFormModificarEquipoXClave(this);">
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
				<input type="submit" name="sbt_buscarXClave" title="Buscar el Equipo a Modificar con la Clave Escrita" value="Buscar" class="botones" 
				onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_cancelar" title="Regresar al Men&uacute; de Equipos" onclick="location.href='menu_equipos.php';" 
				value="Cancelar" class="botones" onmouseover="window.status='';return true;"/>
			</td>
		</tr>
		</table>
		</form>
		</fieldset><?php 
	}
	else{
		//Inicializamos la variable que capturara la clave del Equipo a mostrar
		$clave="";
		//Si se presiono el boton sbt_buscar, obtener el valor de la clave del combo
		if (isset($_POST["sbt_buscar"]))
			$clave=$_POST["cmb_claveEquipo"];
		//Si se presiono el boton sbt_buscar, obtener el valor de la clave del combo
		if (isset($_POST["sbt_buscarXClave"]))
			$clave=$_POST["txt_clave"];
		//Si se detecta el valor de la clave en el GET, entonces se llego aqui por el boton de cancelar desde la pagina de Modificar Documentos de Equipo
		if (isset($_GET["clave"]))
			$clave=$_GET["clave"];
		//Variable que indicara si se encontro o no el equipo
		$bandera=0;
		//Imprimimos el layer de contenido con codigo PHP, ya que no presenta ningun problema
		echo "
				<fieldset class='borde_seccion' id='tabla-modificarEquipoDatos' style='height:590px;'>
				<legend class='titulo_etiqueta'>Modificar Equipo</legend>	
				<br>";
					$bandera=mostrarEquipo($clave);
		echo "	</fieldset>";
		//Si el valor de bandera es igual a 1, se encontraron resultados, en caso que sea asi, mostramos el calendario que servirá para edición de Fechas
		//Si el valor de Bandera es diferente de 1, entonces no se mostrará el calendario
		if ($bandera==1){
		?>
			<div id="calendario">
				<input type="image" name="fecha_Equipo" id="fecha_Equipo" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_modificarEquipo.txt_fechaFabricacionEquipo,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Fabricaci&oacute;n de Equipo"/> 
			</div>
		<?php
		}
	}
	?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
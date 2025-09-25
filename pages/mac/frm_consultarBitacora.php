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
		include ("op_consultarBitacora.php");
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar{position:absolute;left:30px;top:146px;width:170px;height:20px;z-index:11;}
		#tabla-escogerBitacora{position:absolute;left:30px;top:190px;width:300px;height:110px;z-index:12;padding:15px;padding-top:0px;}
		#tabla-bitacoraEquipo{position:absolute; left:400px; top:193px; width:430px; height:201px; z-index:14;}
		#tabla-escogerOT{position:absolute;left:30px;top:190px;width:498px;height:149px;z-index:12;padding:15px;padding-top:0px;}
		#calendar-uno { position:absolute; left:612px; top:236px; width:30px; height:26px; z-index:16; }
		#calendar-dos { position:absolute; left:803px; top:236px; width:30px; height:26px; z-index:17; }
		#boton{ position: absolute; left:319px; top:670px; width:400px; height:40px; z-index:23; }	
		#resultados { position:absolute; left:30px; top:190px; width:940px; height:430px; z-index:22; overflow: scroll }	
		-->
    </style>
</head>
<body>

<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-registrar">Consultar Bit&aacute;cora</div>
<?php

	if(!isset($_POST["sbt_generar"])){
		//Si viene definido el arreglo datosConsBitacora =>Correctiva lo damos de baja de la sesión
		if(isset($_SESSION["datosConsBitacoraCorr"]))
			unset($_SESSION["datosConsBitacoraCorr"]);
		//Si viene definido el arreglo datosConsBitacora =>Preventivo lo damos de baja de la sesión
		if(isset($_SESSION["datosConsBitacora"]))
			unset($_SESSION["datosConsBitacora"]);
			
		//Verificamos el tipo de mantenimiento a seleccionar
		if(isset($_GET["cmb_tipoMtto"])){
			if($_GET["cmb_tipoMtto"]=="preventivo"){
				echo "<meta http-equiv='refresh' content='0;url=frm_consultarBitacoras.php'";
			}
			if($_GET["cmb_tipoMtto"]=="correctivo"){
				echo "<meta http-equiv='refresh' content='0;url=frm_consultarBitacoraCorr.php'";		
			}
		}?>
		
			
		<fieldset class="borde_seccion" id="tabla-escogerBitacora" name="tabla-escogerBitacora">
		<legend class="titulo_etiqueta">Escoger Tipo de Mantenimiento</legend>	
		<br>
		<form name="frm_tipoMtto" >
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Mantenimiento</div></td>
				<td align="left">
					<select name="cmb_tipoMtto" id="cmb_tipoMtto" onChange="javascript:document.frm_tipoMtto.submit();" >
						<option selected="selected" value="">Tipo de Matenimiento</option>
						<option value="preventivo">PREVENTIVO</option>
						<option value="correctivo">CORRECTIVO</option>
					</select>	
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
					title="Regresar al Men&uacute; Bit&aacute;cora"
					onclick="location.href='menu_bitacora.php'" onmouseover="window.status='';return true"/>									
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-bitacoraEquipo" name="tabla-bitacoraEquipo">
		<legend class="titulo_etiqueta">Consulta por Equipo</legend><br />
			<form onsubmit="return valFormCostosEquipo(this);" name="frm_consultarBitacoraEquipo" action="frm_consultarBitacora.php" method="post" >
			<table width="404" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td>Fecha Inicio</td>
					<td width="96"><input name="txt_fechaIni" id="txt_fechaIni2" type="text" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10"
						maxlength="15" readonly="true" width="90" /></td>
					<td width="65">Fecha Fin </td>
					<td width="104">
						<input name="txt_fechaFin" id="txt_fechaFin2" type="text" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" 
						readonly="true" width="90" />					</td>
				</tr>
				<td width="74"><div align="right">Familia </div></td>
				<td colspan="3"><?php 
					//Conectarse a la BD de Mantenimiento
					$conn = conecta("bd_mantenimiento");
					//Obtener los Sistemas Registrados en la BD		
					$rs_familia = mysql_query("SELECT DISTINCT familia FROM equipos WHERE area='CONCRETO' AND estado='ACTIVO' ORDER BY familia");
					if($familias=mysql_fetch_array($rs_familia)){?>
						<select name="cmb_familia" id="cmb_familia" class="combo_box"
						onchange="cargarComboEspecifico(this.value,'bd_mantenimiento','equipos','id_equipo','familia','area','CONCRETO','cmb_equipo','Equipo','');">
							<option value="">Familia</option><?php
							do{
								echo "<option value='$familias[familia]'>$familias[familia]</option>";
							}while($familias=mysql_fetch_array($rs_familia));
						?></select><?php
					}						
					else{
						echo "<label class='msje_correcto'> No hay Familias Registradas, Agregue una nueva</label>
						<input type='hidden' name='cmb_familia' id='cmb_familia'/>";
					}
					//Cerrar la conexion con la BD
					mysql_close($conn);?>
					</td>
			</tr>
			<tr>
				<td><div align="right">Equipo</div></td>
				<td colspan="3">
					<select name="cmb_equipo" class="combo_box" id="cmb_equipo">
						<option value="">Equipo</option>
					</select>				</td>
			<tr>
				<td colspan="4" align="center">
					<input name="sbt_generar" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true"
					title="Consultar Bit&aacute;cora Equipo"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
				</td>
			</tr>
			</table>
			</form>
		</fieldset>
		
		<div id="calendar-uno">
			<input name="finRptEquipo" id="iniRptArea" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_consultarBitacoraEquipo.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />
		</div>
		<div id="calendar-dos">
			<input name="finRptEquipo" id="finRptArea" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_consultarBitacoraEquipo.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />	
		</div>
	<?php }
	else{
	?>
		<div id="resultados" class="borde_seccion2">
			<?php mostrarBitacoraEquipo();?>
		</div>
		<div id="boton" align="center">
			<input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
			title="Regresar a Seleccionar Otros Criterios para Consultar la Bit&aacute;cora" onclick="location.href='frm_consultarBitacora.php'"/>
		</div>
	<?php
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
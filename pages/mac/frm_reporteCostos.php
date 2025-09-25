<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">


<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento Concreto
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para Generar el Reporte de Mnatenimientos Correctivos de Acuerdo a los Parametros Seleccionados
		include ("op_reporteCostos.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:264px; height:24px; z-index:11; }
		#rpt-familia { position:absolute; left:41px; top:193px; width:430px; height:199px; z-index:13; }
		#rpt-equipo { position:absolute; left:529px; top:193px; width:430px; height:201px; z-index:14; }
		#calendar-uno { position:absolute; left:742px; top:236px; width:30px; height:26px; z-index:16; }
		#calendar-dos { position:absolute; left:934px; top:236px; width:30px; height:26px; z-index:17; }
		#calendar-tres { position:absolute; left:250px; top:237px; width:30px; height:26px; z-index:18; }
		#calendar-cuatro { position:absolute; left:251px; top:275px; width:30px; height:26px; z-index:19; }
		#reporte { position:absolute; left:30px; top:190px; width:921px; height:430px; z-index:22; overflow: scroll }
		#btns-rpt { position: absolute; left:319px; top:670px; width:400px; height:40px; z-index:23; }								  
		#boton-cancelar { position:absolute; left:459px; top:454px; width:119px; height:34px; z-index:23; }							  
		-->
    </style>	
	
</head>
<body>
<?php /*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
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
	}?>
	
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-barra">Reporte  Costos del Mantenimiento</div>
<?php 	if(!isset($_POST['sbt_generar'])){?>
		<fieldset class="borde_seccion" id="rpt-familia" name="rpt-familia">	
		<legend class="titulo_etiqueta">Reporte por Familia</legend>
		<br />
		<form onsubmit="return valFormCostosFamilia(this);" name="frm_rptFamilia" action="frm_reporteCostos.php" method="post" >
		<input type="hidden" name="hdn_area" id="hdn_area" value="<?php echo $area; ?>" />
		<table height="169"  width="100%" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr><td><div align="right">Fecha Inicio</div></td>
				<td width="314">
					<input name="txt_fechaIni" id="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15"
					readonly=true width="90" />				
				</td>
			</tr>
			<tr>
				<td><div align="right">Fecha Fin </div></td>
				<td><input name="txt_fechaFin" id="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
			</tr>
			<tr>
				<td width="81"><div align="right">Familia </div></td>
				<td valign="top">
					<?php 
						if($estado==1){//El estado 1 indica que el departamento registrados en la SESSION tiene acceso a la información de MINA y CONCRETO
							$valida=cargarCombo("cmb_familia","familia","equipos","bd_mantenimiento","Familia",""); 
							if($valida==0)
								echo "<label class='msje_correcto'> No hay Familias Registradas, Agregue una Nueva</label>
									  <input type='hidden' name='cmb_familia' id='cmb_familia'/>";
						}
						else{
							//Obtener los Sistemas Registrados en la BD
							$conn = conecta("bd_mantenimiento");
							$rs_familia = mysql_query("SELECT DISTINCT familia FROM equipos WHERE area = '$area' ORDER BY familia");
							if($familias=mysql_fetch_array($rs_familia)){?>
								<select name="cmb_familia" id="cmb_familia" class="combo_box">
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
							mysql_close($conn);		
						}
						?>				
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_generar" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
					title="Generar Reporte por Familia"/> 
					&nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />				
				</td>
			</tr>
		  </table>
		</form>
		</fieldset>
		
		<fieldset class="borde_seccion" id="rpt-equipo" name="rpt-equipo">	
		<legend class="titulo_etiqueta">Reporte por Equipo</legend><br />
		<form onsubmit="return valFormCostosEquipo(this);" name="frm_rptEquipo" action="frm_reporteCostos.php" method="post" >
		<table width="404" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td>Fecha Ini </td>
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
				if($estado==1){//El estado 1 indica que el departamento registrados en la SESSION tiene acceso a la información de MINA y CONCRETO
					//Obtener los Sistemas Registrados en la BD		
					$rs_familia = mysql_query("SELECT DISTINCT familia FROM equipos ORDER BY familia");
					if($familias=mysql_fetch_array($rs_familia)){?>
						<select name="cmb_familia" id="cmb_familia" class="combo_box"
						onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','id_equipo','familia','cmb_equipo','Equipo','');">
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
				}
				else{//Solo mostrar las familias correspondientes al usuario registrado						
					$rs_familia = mysql_query("SELECT DISTINCT familia FROM equipos WHERE area = '$area' ORDER BY familia");
					if($familias=mysql_fetch_array($rs_familia)){?>
						<select name="cmb_familia" id="cmb_familia" class="combo_box"
						onchange="cargarComboEspecifico(this.value,'bd_mantenimiento','equipos','id_equipo','familia','area','<?php echo $area;?>','cmb_equipo','Equipo','');">
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
				}
				//Cerrar la conexion con la BD
				mysql_close($conn);?>				</td>
		</tr>
		<tr>
			<td><div align="right">Equipo</div></td>
			<td colspan="3">
				<select name="cmb_equipo" class="combo_box" id="cmb_equipo">
					<option value="">Equipo</option>
				</select>				</td>
		<tr>
			<td colspan="4" align="center">
				<input name="sbt_generar" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
				title="Generar Reporte por Equipo"/>
				&nbsp;&nbsp;&nbsp;
				<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />				</td>
		</tr>
		</table>
		</form>
	</fieldset>
	
			<div id="boton-cancelar">
				<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Men&uacute; de Reportes"
				onclick="location.href='menu_reportes.php'" />
</div>	
			<div id="calendar-uno">
				<input name="finRptEquipo" id="iniRptArea" type="image" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_rptEquipo.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
				width="25" height="25" border="0" align="absbottom" />
	</div>
			<div id="calendar-dos">
				<input name="finRptEquipo" id="finRptArea" type="image" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_rptEquipo.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
				width="25" height="25" border="0" align="absbottom" />	
	</div>
			<div id="calendar-tres">
				<input name="iniRptFamilia" id="iniRptFamilia" type="image" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_rptFamilia.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
				width="25" height="25" border="0" align="absbottom" />
	</div>
			<div id="calendar-cuatro">
				<input name="finRptFamilia" id="finRptFamilia" type="image" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_rptFamilia.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
				width="25" height="25" border="0" align="absbottom" />	
	</div>
<?php }
	else{ 
		generarReporte();
	 }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
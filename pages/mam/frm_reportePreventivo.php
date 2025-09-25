<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">


<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para Generar el Reporte de Mnatenimientos preventivos de Acuerdo a los Parametros Seleccionados
		include ("op_reportePreventivo.php");
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
		#rpt-area { position:absolute; left:30px; top:190px; width:430px; height:210px; z-index:12; }
		#rpt-familia { position:absolute; left:33px; top:437px; width:430px; height:199px; z-index:13; }
		#rpt-turno { position:absolute; left:529px; top:190px; width:394px; height:206px; z-index:14; }
		#rpt-costo { position:absolute; left:532px; top:437px; width:394px; height:200px; z-index:15; }
		#calendar-uno { position:absolute; left:343px; top:267px; width:30px; height:26px; z-index:16; }
		#calendar-dos { position:absolute; left:343px; top:304px; width:30px; height:26px; z-index:17; }
		#calendar-tres { position:absolute; left:241px; top:514px; width:30px; height:26px; z-index:18; }
		#calendar-cuatro { position:absolute; left:241px; top:553px; width:30px; height:26px; z-index:19; }
		#calendar-cinco { position:absolute; left:800px; top:266px; width:30px; height:26px; z-index:20; }
		#calendar-seis { position:absolute; left:800px; top:302px; width:30px; height:26px; z-index:21; }
		#reporte { position:absolute; left:30px; top:190px; width:921px; height:430px; z-index:22; overflow: scroll }
		#btns-rpt { position: absolute; left:319px; top:670px; width:400px; height:40px; z-index:23; }								  
		#boton-cancelar { position:absolute; left:457px; top:674px; width:119px; height:34px; z-index:23; }							  
		-->
    </style>	
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Mantenimientos Preventivos </div>
		
    <?php
	//Verificar si estan definidos los datos del reporte de compras en la SESSION, de estarlo procedemos a pasarlos al arreglo POST
	if(isset($_POST['hdn_tipoRpt'])){
		switch($_POST['hdn_tipoRpt']){
			case 1:
				$_POST['cmb_area'] = $_SESSION['datosRptPreventivos']['cmb_area'];
				$_POST['txt_fechaIni'] = $_SESSION['datosRptPreventivos']['txt_fechaIni'];
				$_POST['txt_fechaFin'] = $_SESSION['datosRptPreventivos']['txt_fechaFin'];
				unset($_SESSION['datosRptPreventivos']);//Quitar los datos de la SESSION
			break;
			case 2:
				$_POST['cmb_familia'] = $_SESSION['datosRptPreventivos']['cmb_familia'];
				$_POST['hdn_area'] = $_SESSION['datosRptPreventivos']['hdn_area'];
				$_POST['txt_fechaIni'] = $_SESSION['datosRptPreventivos']['txt_fechaIni'];
				$_POST['txt_fechaFin'] = $_SESSION['datosRptPreventivos']['txt_fechaFin'];
				unset($_SESSION['datosRptPreventivos']);//Quitar los datos de la SESSION
			break;
			case 3:
				$_POST['cmb_familia'] = $_SESSION['datosRptPreventivos']['cmb_familia'];
				$_POST['cmb_equipo'] = $_SESSION['datosRptPreventivos']['cmb_equipo'];				
				unset($_SESSION['datosRptPreventivos']);//Quitar los datos de la SESSION
			break;
			case 4:
				$_POST['txt_nivelSup'] = $_SESSION['datosRptPreventivos']['txt_nivelSup'];
				$_POST['txt_nivelInf'] = $_SESSION['datosRptPreventivos']['txt_nivelInf'];
				unset($_SESSION['datosRptPreventivos']);//Quitar los datos de la SESSION
			break;
		}
	}
	//El valor de 0 indica que ningun reporte fue desplegado, 1 significa que uno de los cuatro reportes fue desplegado
	$band = 0;
				
	
	//Quitar los datos de la grafica de la SESSION, antes de entrar a generar el nuevo reporte, en el caso de que exista uno previo
	unset($_SESSION['datosGrapPreventivos']);
		
	if(isset($_POST['cmb_area']) && isset($_POST['txt_fechaIni']) && isset($_POST['txt_fechaFin'])){
		$band = 1;		
		generarReporte(1);		
	}	
	if(isset($_POST['cmb_familia']) && isset($_POST['txt_fechaIni']) && isset($_POST['txt_fechaFin'])){
		$band = 1;		
		generarReporte(2);		
	}	
	if(isset($_POST['cmb_familia']) && isset($_POST['cmb_equipo'])){
		$band = 1;		
		generarReporte(3);
	}
	if(isset($_POST['txt_nivelSup'])){
		$band = 1;		
		generarReporte(4);		
	}
	
	if(isset($_POST['verDetalle'])){					
		$band = 1;		
		//Obtener el valor de la clave 
		$clave = "";
		$tam = count($_POST);
		$cont = 1;
		foreach($_POST as $nombre_campo => $valor){								
			if($cont==$tam)
				$clave = $valor;				
			$cont++;
		}
		//Mostrar el detalle  Seleccionado
		mostrarDetalleRP($clave,$no_reporte);						
	}	
	
	
	if($band==0){ 
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
		}?>
		
		
		<fieldset class="borde_seccion" id="rpt-area" name="rpt-area">	
		<legend class="titulo_etiqueta">Reporte por Área</legend>
		<br />
		<form onsubmit="return verFormReportesPreventivos(this,1);" name="frm_rptArea" action="frm_reportePreventivo.php" method="post" >
		<table width="286" border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  	<td><div align="right">&Aacute;rea </div></td>
				<td colspan="2">
					<?php if($estado==1){ //El estado 1 indica que el departamento registrados en la SESSION tiene acceso a la información de MINA y CONCRETO ?>
						<select name="cmb_area" class="combo_box" id="cmb_area">
							<option value="">&Aacute;rea</option>
							<option value="CONCRETO">CONCRETO</option>
							<option value="MINA">MINA</option>
						</select>
					<?php } else {//Mostrar las opciones de acuerdo al AdminMtto que este registrado ?>
						<select name="cmb_area" id="cmb_area" class="combo_box" <?php echo $atributo; ?>>
							<option value="">&Aacute;rea</option>						
							<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
							<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
						</select>		
						<input type="hidden" name="cmb_area" value="<?php echo $area; ?>" />					
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td><div align="right">Fecha Inicio</div></td>
				<td>
					<input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15"
					readonly=true width="90" />
				</td>
			</tr>
			<tr>
				<td><div align="right">Fecha Fin </div></td>
				<td><input name="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_generar" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
					title="Generar Reporte por Área"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
	
		<div id="calendar-uno">
	  		<input name="iniRptArea" id="iniRptArea" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_rptArea.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />
		</div>
		<div id="calendar-dos">
	  		<input name="finRptArea" id="finRptArea" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_rptArea.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />	
		</div>
	
		<fieldset class="borde_seccion" id="rpt-familia" name="rpt-familia">	
		<legend class="titulo_etiqueta">Reporte por Familia</legend>
		<br />
		<form onsubmit="return verFormReportesPreventivos(this,2);" name="frm_rptFamilia" action="frm_reportePreventivo.php" method="post" >
		<input type="hidden" name="hdn_area" id="hdn_area" value="<?php echo $area; ?>" />
		<table height="169"  width="100%" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">
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
				<td><div align="right">Fecha Inicio</div></td>
				<td width="314">
					<input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15"
					readonly=true width="90" />				</td>
			</tr>
			<tr>
				<td><div align="right">Fecha Fin </div></td>
				<td><input name="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_generar" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
					title="Generar Reporte por Familia"/> 
					&nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />				</td>
			</tr>
		</table>
		</form>
		</fieldset>
	
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
	
		<fieldset class="borde_seccion" id="rpt-turno" name="rpt-turno">	
		<legend class="titulo_etiqueta">Reporte por Equipo</legend><br />
		<form onsubmit="return verFormReportesPreventivos(this,3);" name="frm_rptEquipo" action="frm_reportePreventivo.php" method="post" >
		<table width="292" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Familia </div></td>
				<td><?php 
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
						mysql_close($conn);
					?>
				</td>
			</tr>
			<tr>
				<td><div align="right">Equipo</div></td>
				<td>
					<select name="cmb_equipo" class="combo_box" id="cmb_equipo">
						<option value="">Equipo</option>
				  	</select> 
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_generar" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
					title="Generar Reporte por Turno"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
	
		<fieldset class="borde_seccion" id="rpt-costo" name="rpt-costo">	
		<legend class="titulo_etiqueta">Reporte por Costo</legend>
		<br />
		<form onsubmit="return verFormReportesPreventivos(this,4);" name="frm_rptCosto" action="frm_reportePreventivo.php" method="post" >
		<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="140"><div align="right">Cantidad Nivel Inferior</div></td>
				<td width="160">$<?php 
					if($estado==1){//El estado 1 indica que el departamento registrados en la SESSION tiene acceso a la información de MINA y CONCRETO
						$valida=cargarCombo("txt_nivelInf","costo_mtto","bitacora_mtto","bd_mantenimiento","Costo Mtto",""); 
						if($valida==0)
							echo "<label class='msje_correcto'> No hay Costos Registrados</label>
							<input type='hidden' name='txt_nivelInf' id='txt_nivelInf'/>
							<input type='hidden' name='txt_nivelSup' id='txt_nivelSup'/>";
					}
					else{
						//Obtener los Sistemas Registrados en la BD
						$conn = conecta("bd_mantenimiento");
						$rs_costos = mysql_query("SELECT DISTINCT costo_mtto FROM bitacora_mtto JOIN equipos ON equipos_id_equipo=id_equipo 
													WHERE area = '$area' AND tipo_mtto = 'PREVENTIVO' AND fecha_mtto!='0000-00-00' ORDER BY costo_mtto");
						if($costos=mysql_fetch_array($rs_costos)){?>
							<select name="txt_nivelInf" id="txt_nivelInf" class="combo_box">
								<option value="">Costo Mtto</option><?php
							do{
								echo "<option value='$costos[costo_mtto]'>$costos[costo_mtto]</option>";
							}while($costos=mysql_fetch_array($rs_costos));
							?></select><?php
						}					
						else{
							echo "<label class='msje_correcto'> No hay Costos Registrados</label>
							<input type='hidden' name='txt_nivelInf' id='txt_nivelInf'/>";
						}
						//Cerrar la conexion con la BD
						mysql_close($conn);
					}?>										
			  	</td>
			</tr>
			<tr>
				<td><div align="right">Cantidad Nivel Superior</div></td>
				<td>$<?php 
					if($estado==1){
						$validar=cargarCombo("txt_nivelSup","costo_mtto","bitacora_mtto","bd_mantenimiento","Costo Mtto",""); 
						if($valida==0)
							echo "<label class='msje_correcto'> No hay Costos Registrados</label>";
					}
					else{
						//Obtener los Sistemas Registrados en la BD
						$conn = conecta("bd_mantenimiento");
						$rs_costos = mysql_query("SELECT DISTINCT costo_mtto FROM bitacora_mtto JOIN equipos ON equipos_id_equipo=id_equipo 
												WHERE area = '$area' AND tipo_mtto = 'PREVENTIVO' AND fecha_mtto!='0000-00-00' ORDER BY costo_mtto");
						if($costos=mysql_fetch_array($rs_costos)){?>
							<select name="txt_nivelSup" id="txt_nivelSup" class="combo_box">
								<option value="">Costo Mtto</option><?php
							do{
								echo "<option value='$costos[costo_mtto]'>$costos[costo_mtto]</option>";
							}while($costos=mysql_fetch_array($rs_costos));
							?></select><?php
						}
						else{
							echo "<label class='msje_correcto'> No hay Costos Registrados</label>
							<input type='hidden' name='txt_nivelInf' id='txt_nivelInf'/>
							<input type='hidden' name='txt_nivelSup' id='txt_nivelSup'/>";
						}
						//Cerrar la conexion con la BD
						mysql_close($conn);
					}?>					
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_generar" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
					title="Generar Reporte por Costos"/>
					&nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
				</td>
			</tr>
		</table>
		</form>	
		</fieldset>
	
		<div id="boton-cancelar">
			<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Reportes"
			onclick="location.href='menu_reportes.php'" />
		</div><?php 
	}//Cierre if($band==0) ?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
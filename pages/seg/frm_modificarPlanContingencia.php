<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarPlanContingencia.php");
		
		
		//Verificamos que existan las sesiones de ser asi darlas de baja
		if(isset($_SESSION['datosPlanContingencia'])){
			unset($_SESSION['datosPlanContingencia']);
		
		if(isset($_SESSION['datosGralPlan']))
			unset($_SESSION['datosGralPlan']);
		}
		
		?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:238px;height:20px;z-index:11;}
		#tabla-modificarPlanes{position:absolute;left:30px;top:190px;width:455px;height:187px;z-index:12;}
		#calendario-Ini {position:absolute;left:375px;top:215px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:372px;top:253px;width:30px;height:26px;z-index:14;}
		#tabla-resultados {position:absolute;left:23px;top:193px;width:944px;height:407px;z-index:14;overflow:scroll;}
		#botones-TablaRes {position:absolute;left:55px;top:654px;width:926px;height:44px;z-index:16;}
		#tabla-modificarIdPlan{position:absolute;left:554px;top:190px;width:377px;height:188px;z-index:12;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Modificar Planes Contingencia </div><?php 
	if(isset($_POST['sbt_consultarPlan']) || isset($_POST['sbt_consultarIdPlan'])){?>
		<form  name="frm_resultadosPlan" id="frm_resultadosPlan" method="post" action="frm_modificarPlanContingencia2.php" >
		<div id="tabla-resultados" class="borde_seccion2"><?php 
			//Colocamos una variable bandera para verificar si existen resultados o no y así manejar los botones de Exportar y Regresar
			$sql_stm = consultarPlanContingencia();?>
		</div>
		</form><?php 
	}
	else{?>	 	 
		<fieldset class="borde_seccion" id="tabla-modificarPlanes" name="tabla-modificarPlanes">
		<legend class="titulo_etiqueta">Consultar Permisos por Fechas de Registro</legend>
			<form onsubmit="return valFormModPlaFechas(this)"  name="frm_consultarPlanesFecha" id="frm_consultarPlanesFecha" 
				method="post" action="frm_modificarPlanContingencia.php">
				<table width="454" height="127"  cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td><div align="right">*Fecha Registro </div></td>
						<td width="176"><input name="txt_fechaReg" type="text" id="txt_fechaReg" size="10" maxlength="15"
							 value="<?php echo date("d/m/Y");?>" readonly="readonly" class="caja_de_texto"/></td>
					</tr>
					<tr>
						<td><div align="right">*Fecha Programada </div></td>
						<td><input name="txt_fechaProg" type="text" id="txt_fechaProg" size="10" maxlength="15" value="<?php echo date("d/m/Y", strtotime("+30 day"));?>" 
							readonly="readonly" class="caja_de_texto"/></td>
					</tr>
					<tr>
						<td height="45" colspan="9"><div align="center">
							<input name="sbt_consultarPlan" type="submit" class="botones" id="sbt_consultarPlan" 
								value="Consultar" title="Planes de Contingencia por Fechas"
							onmouseover="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones"  value="Regresar" title="Regresa al Menu de Plan de Contingencia" 
								onmouseover="window.status='';return true" onclick="location.href = 'menu_planContingencia.php'"/>
						</div></td>
					</tr>
				</table>
			</form>
		</fieldset>


		<fieldset class="borde_seccion" id="tabla-modificarIdPlan" name="tabla-modificarIdPlan">
		<legend class="titulo_etiqueta">Consultar Permisos por Fechas de Registro</legend>
			<form  onSubmit="return valFormConsultarIdPlan(this);" name="frm_consultarIdPlan" id="frm_consultarIdPlan" method="post" action="frm_modificarPlanContingencia.php">
				<table width="368" height="127"  cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td width="159"><div align="right">Clave Plan</div></td>
						<td width="172"><?php
							$idPlanCont=cargarCombo("cmb_idPlan","id_plan","planes_contingencia","bd_seguridad","CLAVE PLAN","");
						
							if($idPlanCont==0){
								echo "<label class='msje_correcto'><u><strong> NO</u></strong> Existen Planes de Contingencia Registrados</label>
								<input type='hidden' name='cmb_idPlan' id='cmb_idPlan'/>";
							}?></td>
					</tr>
					<tr>
						<td height="45" colspan="9"><div align="center">
							<input name="sbt_consultarIdPlan" type="submit" class="botones" id="sbt_consultarIdPlan" value="Consultar" 
								title="Consultar Planes de Contingencia por Clave"
							onmouseover="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones"  value="Regresar" title="Regresa al Menu de Plan de Contingencia" 
								onmouseover="window.status='';return true" onclick="location.href = 'menu_planContingencia.php'">
						</div></td>
					</tr>
				</table>
			</form>
		</fieldset>
		<div id="calendario-Ini">
			<input type="image" name="txt_fechaReg" id="txt_fechaReg" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_consultarPlanesFecha.txt_fechaReg,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Inicio"/> 
		</div>	
		<div id="calendario-Fin">
			<input type="image" name="txt_fechaProg" id="txt_fechaProg" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_consultarPlanesFecha.txt_fechaProg,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Programaci&oacute;n"/> 
		</div><?php 
	} // Llave de Cierre 	 <?php if(!isset($_POST['sbt_consultarPlan']) || !isset($_POST['sbt_consultarIdPlan'])){
	
	
	if(isset($_POST['sbt_consultarPlan']) || isset($_POST['sbt_consultarIdPlan'])){?>
		<div align="center" id="botones-TablaRes" >
			<form name="frm_exportarDatos" action="guardar_reportePermisos.php" method="post"><?php 
				if($sql_stm!=""){ //Si dentro de la BD existen datos que se muestre el boton de exportar, de lo contrario que no se muestre?>
					<input name="sbt_exportarRes" type="submit" class="botones_largos" id="sbt_exportarRes"  value="Exportar Excel" 
					title="Exportar a Formato Excel los Resultados del Plan de Contingencia" onmouseover="window.status='';return true" 
					onclick="hdn_btnCambiar.value='sbt_exportarRes';cambiarSubmitPlan();" />
					<input type="hidden" name="hdn_btnCambiar" id="hdn_btnCambiar" value="radio"/>
					<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="<?php echo $sql_stm; ?>"/>
					<input type="hidden" name="hdn_nomReporte" value="Reporte de Permisos" />
					<input type="hidden" name="hdn_origen" value="reportePlanContingencia" /><?php 
					if(isset($_POST['sbt_consultarPlan'])){?>
						<input type="hidden" name="hdn_msg" id="hdn_msg" value="PLANES DE CONTINGENCIA PROGRAMADOS DEL <em> 
						<?php echo $_POST['txt_fechaReg']?> </em> AL <em> <?php echo $_POST['txt_fechaProg'];?>"/><?php 
					}else if(isset($_POST['sbt_consultarIdPlan'])){?>
						<input type="hidden" name="hdn_msg" id="hdn_msg" value="PLAN DE CONTINGENCIA PROGRAMADO CON CLAVE <em> 
						<?php echo $idPlanCont = $_POST['cmb_idPlan'] ?> </em> "/><?php
					}
				} ?>	
				&nbsp;&nbsp;
				<input name="btn_regresar" type="button" class="botones" value="Regresar" 
				title="Regresar a Consultar otro Plan de Contingencia" onmouseover="window.status='';return true" onclick="location.href='frm_modificarPlanContingencia.php';"  />
			</form> 
</div><?php 
	} // Llave de Cierre de la condición if(isset($_POST["sbt_consultar"])){?>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
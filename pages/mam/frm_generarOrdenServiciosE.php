<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml"><?php


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
		include ("op_gestionarServiciosExternos.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js"></script>
   	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/datosProveedor.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_proveedores_otse.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script><?php
	
	if(!isset($_POST['sbt_generarOrden'])){?>
		<script type="text/javascript" language="javascript">
			//Colocar el foco en el combo de calsificación del trabajo con medio segundo de retraso
			setTimeout("document.frm_generarOTSE.cmb_clasificacion.focus(); document.frm_generarOTSE.cmb_proveedor.tabIndex = 4;",500);
			//Agregar la Opción de Proveedor no Registrado al Combo de Proveedores
			setTimeout("agregarNvaOpcion(document.frm_generarOTSE.cmb_proveedor);",500);
		</script><?php
	}//Cierre if(!isset($_POST['sbt_generarOrden']))?>
	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#titulo-paginaGenerar {position:absolute;left:30px;top:146px;width:538px;height:20px;z-index:11;}
		#tabla-generarOrdenTrabajo {position:absolute;left:30px;top:190px;width:908px;height:430px;z-index:12;}
		#procesando {position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:14;}
		#calendario-fechaSolicitud {position:absolute; left:325px; top:298px; width:30px; height:26px; z-index:13;}
		#calendario-fechaRecepcion {position:absolute; left:763px; top:298px; width:30px; height:26px; z-index:14;}
		#res-spider2 { position:absolute; width:10px; height:10px; z-index:16; }
		-->
    </style>
</head>
<body>


	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-paginaGenerar">Generar Orden de Trabajo para Servicios Externos</div><?php		
	
		
	//Guardar los datos de la Orden de Trabajo para servicios Externos cuando el Usuario le de Click al boton de Generar (sbt_generarOrden)
	if(isset($_POST['sbt_generarOrden'])){
		//Llamar a la función que guarda los datos en la BD
		generarOrdenTrabajoServicioExterno();?>
		
		<div class="titulo_etiqueta" id="procesando">
            <div align="center">
                <p><img src="../../images/loading.gif" width="70" height="70"  /></p>
                <p>Procesando...</p>
            </div>
        </div><?php		
	}//Cierre if(isset($_POST['sbt_generarOrden']))
	else if(!isset($_POST['sbt_generarOrden'])){
		
		$idOrdenTrabajo = ""; $fechaRegistro = date("d/m/Y"); $area = ""; $clasificacion = ""; $fechaSolicitud = date("d/m/Y"); $fechaRecepcion = date("d/m/Y");
		$rfcProveedor = ""; $proveedor = ""; $direccion = ""; $repProveedor = ""; $solicito = ""; $autorizo = "";
		
		
		//Recuperar el area de acuerdo a los datos cargados en la SESSION
		$deptoActual = "";
		if($_SESSION['depto']=="MttoMina"){
			$area = "MINA";
			$deptoActual = "MANTENIMIENTO MINA";
		}
		else if($_SESSION['depto']=="MttoConcreto"){
			$area = "CONCRETO";
			$deptoActual = "MANTENIMIENTO SUPERFICIE";
		}
		
			
		//Verificar si esta definido en la SESSION el arreglo 'ordenServicioExterno' con los datos de la Orden para mostrarlos en los campos
		if(isset($_SESSION['ordenServicioExterno'])){
			$idOrdenTrabajo = $_SESSION['ordenServicioExterno']['idOrdenTrabajo'];
			$fechaRegistro = $_SESSION['ordenServicioExterno']['fechaRegistro'];
			$area = $_SESSION['ordenServicioExterno']['area'];
			$clasificacion = $_SESSION['ordenServicioExterno']['clasificacion'];
			$fechaSolicitud = $_SESSION['ordenServicioExterno']['fechaSolicitud'];
			$fechaRecepcion = $_SESSION['ordenServicioExterno']['fechaRecepcion'];
			$rfcProveedor = $_SESSION['ordenServicioExterno']['rfcProveedor'];
			$proveedor = $_SESSION['ordenServicioExterno']['proveedor'];
			$direccion = $_SESSION['ordenServicioExterno']['direccion'];
			$repProveedor = $_SESSION['ordenServicioExterno']['repProveedor'];
			$solicito = $_SESSION['ordenServicioExterno']['solicito'];
			$autorizo = $_SESSION['ordenServicioExterno']['autorizo'];
		}
		else{
			//Crear el ID de la Orden de Trabajo de acuerdo al Area cuando se entra por primera vez a esta pagina
			$idOrdenTrabajo = obtenerIdOrdenTrabajoSE($area);
		}
		
	
	
		//Si se cancela el registro de Actividades o Materiales, retirar de la SESSION la información guardada respectivamente
		if(isset($_GET["cancelar"])){
			//Liberamos de la sesion el arreglo actividadesRealizar cuando el usuario de click en el boton cancelar de la pagina de registrar actividades a realizar
			if($_GET["cancelar"]=="actividades")
				unset($_SESSION["actividadesRealizar"]);
				
			//Liberamos de la sesion el arreglo mecanicos cuando el usuario de click en el boton cancelar de la pagina de registrar mecanicos
			if($_GET["cancelar"]=="materiales")
				unset($_SESSION["materialesUtilizar"]);
		}?>						
		
						 
		<fieldset class="borde_seccion" id="tabla-generarOrdenTrabajo" name="tabla-generarOrdenTrabajo">
		<legend class="titulo_etiqueta">Ingresar datos de la Orden de Trabajo</legend>	
		<br>
		<?php //La propiedad 'action' del formulario, se define en los botones de Registrar Actividades y Materiales ?>
		<form name="frm_generarOTSE" method="post" action="">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td>
					<div align="right">ID Orden de Trabajo</div>
				</td>
				<td>				
					<input type="text" name="txt_ordenTrabajo" id="txt_ordenTrabajo" class="caja_de_texto" readonly="readonly" size="15" value="<?php echo $idOrdenTrabajo; ?>" />
				</td>
				<td>
					<div align="right">Fecha</div>
				</td>
				<td>
					<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" readonly="readonly" size="10" value="<?php echo $fechaRegistro; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">
					<div align="right">&Aacute;rea</div>
				</td>
				<td>
					<input type="text" name="txt_area" id="txt_area" class="caja_de_texto" readonly="readonly" size="10" value="<?php echo $area; ?>" />
				</td>            
				<td>
					<div align="right">*Clasificaci&oacute;n</div>
				</td>
				<td>								
					<select name="cmb_clasificacion" id="cmb_clasificacion" class="combo_box" tabindex="1" required="required">
						<option value="">Clasificaci&oacute;n</option>						
						<option <?php if($clasificacion=="FABRICACIÓN"){ echo "selected='selected'"; }?> value="FABRICACIÓN">FABRICACIÓN</option>
						<option <?php if($clasificacion=="REPARACIÓN"){ echo "selected='selected'"; }?>value="REPARACIÓN">REPARACIÓN</option>
						<option <?php if($clasificacion=="RECONSTRUCCIÓN"){ echo "selected='selected'"; }?>value="RECONSTRUCCIÓN">RECONSTRUCCIÓN</option>
						<option <?php if($clasificacion=="GARANTÍA"){ echo "selected='selected'"; }?>value="GARANTÍA">GARANTÍA</option>
						<option <?php if($clasificacion=="SERVICIOS"){ echo "selected='selected'"; }?>value="SERVICIOS">SERVICIOS</option>
					</select>		  	
				</td>			
			</tr>
			<tr>
				<td align="right">
					<div align="right">*Fecha Solicitud</div>
				</td>
				<td>								
					<input type="text" name="txt_fechaSolicitud" id="txt_fechaSolicitud" class="caja_de_texto" readonly="readonly" size="10" 
					value="<?php echo $fechaSolicitud; ?>" />
				</td>
				<td>
					<div align="right">*Fecha Recepci&oacute;n</div>
				</td>
				<td>
					<input type="text" name="txt_fechaRecepcion" id="txt_fechaRecepcion" class="caja_de_texto" readonly="readonly" size="10" 
					value="<?php echo $fechaRecepcion; ?>" />
				</td>			
			</tr>
			<tr>
				<td>
					<div align="right">Proveedor</div>
				</td>
				<td colspan="3">
					<input name="txt_proveedor" id="txt_proveedor" type="text" class="caja_de_texto" size="91" maxlength="80" onkeyup="lookupProv(this,'2');" value="<?php echo $proveedor ?>"/>
					<div id="res-spider2">
						<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
						</div>
					</div>
					<input type="hidden" name="hdn_rfc" id="hdn_rfc" value="<?php echo $rfcProveedor ?>"/>
				</td>			
			</tr>
			<tr>
				<td>
					<div align="right">Direcci&oacute;n </div>
				</td>
				<td colspan="3">
					<input type="text" name="txt_direccion" id="txt_direccion" class="caja_de_texto" size="91" value="<?php echo $direccion; ?>" readonly="readonly" required="required"/>
				</td>						
			</tr>
			<tr>
				<td><div align="right">Representante Proveedor</div></td>
				<td colspan="3">
					<input type="text" name="txt_repProveedor" id="txt_repProveedor" class="caja_de_texto" onkeypress="return permite(event,'num_car',1);" size="40" maxlength="60"
					value="<?php echo $repProveedor; ?>" tabindex="7"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Solicit&oacute;</div></td>
				<td colspan="3">
					<input type="text" name="txt_solicito" id="txt_solicito" class="caja_de_texto" onkeypress="return permite(event,'num_car',1);" size="40" maxlength="60"
					value="<?php echo $solicito; ?>" tabindex="9" required="required"/>
				</td>
			</tr>		
			<tr>
				<td><div align="right">*Autoriz&oacute;</div></td>
				<td colspan="3">
					<input type="text" name="txt_autorizo" id="txt_autorizo" class="caja_de_texto" onkeypress="return permite(event,'num_car',1);" size="40" maxlength="60"
					value="<?php echo $autorizo; ?>" tabindex="10" required="required"/>
				</td>
			</tr>
			<tr>
				<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td colspan="4" align="center"><?php
					//Esta variable ayudará a notificar al usuario cuando los materiales no hayan sido registrados?>
					<input type="hidden" name="hdn_materialesAgregados" id="hdn_materialesAgregados" 
					value="<?php if(isset($_SESSION['materialesUtilizar'])){ echo "si"; } else { echo "no"; } ?>" />
					<input type="hidden" name="hdn_botonSelect" id="hdn_botonSelect" value="" /><?php 
					
					
					//Solo se verifica el arreglo de Actividades a Realizar, ya que los materiales pueden o no ir
					if(isset($_SESSION['actividadesRealizar'])){?>
						<input type="submit" name="sbt_generarOrden" class="botones" value="Generar Orden" title="Generar Orden de Trabajo para Servicio Externo"
						onmouseover="window.status='';return true" tabindex="15"
						onClick="hdn_botonSelect.value='generarOrden'; document.frm_generarOTSE.action='frm_generarOrdenServiciosE.php'" /><?php 
					}?>
					
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_regActividades" id="sbt_regActividades" class="botones_largos" value="Registrar Actividades"
					title="Registrar Actividades a Realizar" onmouseover="window.status='';return true" tabindex="11" 
					onClick="hdn_botonSelect.value='actividades'; document.frm_generarOTSE.action='frm_regActividadesRealizar.php'" />
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_regMateriales" id="sbt_regMateriales" class="botones_largos" value="Registrar Materiales"
					title="Registrar Materiales a Utilizar" onmouseover="window.status='';return true" tabindex="12"
					onClick="hdn_botonSelect.value='materiales'; document.frm_generarOTSE.action='frm_regMaterialesUtilizar.php'" />
					&nbsp;&nbsp;&nbsp;					 				
					<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" tabindex="13" />
					&nbsp;&nbsp;&nbsp;					
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar Orden de Trabajo " 
					onmouseover="window.status='';return true" onclick="confirmarSalida('frm_gestionarServiciosExternos.php');" tabindex="14" />
				</td>
			</tr>
		</table>
		</form>
		</fieldset>  
		
		<div id="calendario-fechaSolicitud">
			<input type="image" name="img_calendario_fechaSolicitud" id="img_calendario_fechaSolicitud" src="../../images/calendar.png" 
			align="absbottom" width="25" height="25" border="0" onclick="displayCalendar (document.frm_generarOTSE.txt_fechaSolicitud,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" tabindex="2" />
		</div>
		
		<div id="calendario-fechaRecepcion">
			<input type="image" name="img_calendario_fechaRecepcion" id="img_calendario_fechaRecepcion" src="../../images/calendar.png" 
			align="absbottom" width="25" height="25" border="0" onclick="displayCalendar (document.frm_generarOTSE.txt_fechaRecepcion,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" tabindex="3" />
		</div><?php
	}//CIerre else if(!isset($_POST['sbt_generarOrden']))?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
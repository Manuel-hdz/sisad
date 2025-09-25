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
		include ("op_gestionarServiciosExternos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>	
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
   	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
  	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#titulo-consultarOrdenTrabajo {position:absolute;left:30px;top:146px;width:381px;height:20px;z-index:11;}
		#tabla-consultarOrdenTrabajo {position:absolute;left:30px;top:190px;width:758px;height:150px;z-index:12;padding:15px;padding-top:0px;}
		#calendarioInicio {position:absolute;left:255px;top:315px;width:30px;height:26px;z-index:13;}
		#calendarioFin {position:absolute;left:480px;top:315px;width:30px;height:26px;z-index:14;}
		#consultar-ordenTrabajo {position:absolute;left:30px;top:370px;width:940px;height:250px;z-index:15;overflow:scroll;}
		#btns-regexp {position:absolute;left:30px;top:670px;width:940px;height:40px;z-index:16;}
		#detalleOT {position:absolute; left:30px; top:190px; width:940px; height:432px; z-index:18; overflow: scroll }
		#btn-detalleOT {position:absolute;left:30px;top:670px;width:940px;height:40px;z-index:17;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultarOrdenTrabajo">Consultar Orden de Trabajo para Servicios Externos</div><?php
	
	
	//Mostrar los datos de acuerdo a los parámetros de consulta seleccionados cuando se le de clic al boton 'sbt_consultar'
	if(isset($_POST['sbt_consultar'])){
		//Cada vez que se hace una consulta, guardar los datos de la consulta en la SESSION
		$_SESSION['datosConsultaOTSE'] = array("area"=>$_POST['txt_area'],"familia"=>$_POST['cmb_familia'],"equipo"=>$_POST['cmb_equipo'],"proveedor"=>$_POST['cmb_nomProveedor'],
												"fechaIni"=>$_POST['txt_fechaInicio'],"fechaFin"=>$_POST['txt_fechaFin']);
	
		//Desplegar la orden de trabajo seleccionada?>
		<div id="consultar-ordenTrabajo" class="borde_seccion2" align="center"><?php 
			mostrarOrdenesServiciosExternos();?>
		</div>
        
		
		<div id="btns-regexp" align="center">
			<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Consultar Orden de Trabajo"
			onmouseover="window.status='';return true" onclick="location.href='frm_consultarOrdenServiciosE.php'" />                       
		</div><?php		
	}//Cierre if(isset($_POST['sbt_consultar']))
	
	
	
	//Si esta definido el CheckBox, procedemos a mostrar el detalle de la Orden Seleccionada		
	if(isset($_POST['ckb_detalleOTSE'])){?>
		<div id="detalleOT" class="borde_seccion2" align="center"><?php
			//Mostrar el detalle del registros seleccionado
			mostrarDetalleOTSE();?>
		</div>
		
		<div id="btn-detalleOT">
			<table align="center">
				<tr>
					<td>
					<form name="frm_regresqar" action="frm_consultarOrdenServiciosE.php" method="post">
						<input name="sbt_consultar" type="hidden" value="" />
						<input type="hidden" name="txt_area" value="<?php echo $_SESSION['datosConsultaOTSE']['area']; ?>" />
						<input type="hidden" name="cmb_familia" value="<?php echo $_SESSION['datosConsultaOTSE']['familia']; ?>" />
						<input type="hidden" name="cmb_equipo" value="<?php echo $_SESSION['datosConsultaOTSE']['equipo']; ?>" />
						<input type="hidden" name="cmb_nomProveedor" value="<?php echo $_SESSION['datosConsultaOTSE']['proveedor']; ?>" />
						<input type="hidden" name="txt_fechaInicio" value="<?php echo $_SESSION['datosConsultaOTSE']['fechaIni']; ?>" />
						<input type="hidden" name="txt_fechaFin" value="<?php echo $_SESSION['datosConsultaOTSE']['fechaFin']; ?>" />
						
						<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Consulta de las Ordenes de Trabajo" 
						onmouseover="window.estatus='';return true" id="sbt_regresar" />
					</form>
					</td>
					<td><?php
						//Armar el código Javascript para abrir el archivo PDF
						$codigoPopUp = "window.open('../../includes/generadorPDF/ordenServicioExterno.php?";
						$codigoPopUp .= "id_orden=".$_SESSION['datosConsultaOTSE']['idOrden']."&nom_depto=".$_SESSION['datosConsultaOTSE']['area'];
						$codigoPopUp .= "&fecha_reg=".$_SESSION['datosConsultaOTSE']['fechaCreacion']."', ";
						$codigoPopUp .= "'_blank', 'top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, ";
						$codigoPopUp .= "toolbar=no, location=no, directories=no');"; ?>
						
						<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" title="Ver Archivo PDF de la Requisición Seleccionada" 
						onmouseover="window.status='';return true" onclick="<?php echo $codigoPopUp; ?>" />
					</td>
				</tr>
			</table>
		</div><?php		
	}
	else{
		
		/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
		$area = "";
		if($_SESSION['depto']=="MttoConcreto"){
			$area = "CONCRETO";
			$atributo = "disabled='disabled'";
		}
		else if($_SESSION['depto']=="MttoMina"){
			$area = "MINA";
			$atributo = "disabled='disabled'";
		}?>		
		
				
		<script type="text/javascript" language="javascript">
			cargarCombo('<?php echo $area;?>','bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');
		</script>
		
		
		<fieldset class="borde_seccion" id="tabla-consultarOrdenTrabajo" name="tabla-consultarOrdenTrabajo">
		<legend class="titulo_etiqueta">Consultar la Ordenes de Trabajo</legend>	
		<br>
		<form onsubmit="return valFormConsultarOTSE(this);" name="frm_consultarOTSE" method="post" action="frm_consultarOrdenServiciosE.php">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="15%" align="right">&Aacute;rea</td>
				<td width="15%">
					<input type="text" name="txt_area" id="txt_area" class="caja_de_texto" readonly="readonly" size="15" value="<?php echo $area; ?>" />					
				</td>
				<td width="15%" align="right">Familia</td>
				<td width="15%">
					<select name="cmb_familia" id="cmb_familia" 
					onchange="cargarComboConId(this.value,'bd_mantenimiento','equipos','id_equipo','id_equipo','familia','cmb_equipo','Clave Equipo','');" >
						<option value="">Familia</option>
					</select>
				</td>
				<td width="15%" align="right">Equipo</td>
				<td width="15%">
					<select name="cmb_equipo" id="cmb_equipo" class="combo_box">
						<option value="">Clave Equipo</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="6" align="center">Proveedor&nbsp;&nbsp;&nbsp;<?php				
				//Cargar el combo con los proveedores registardos en las OTSE de cada area
				$conn = conecta("bd_mantenimiento");
				$rs = mysql_query("SELECT DISTINCT nom_proveedor FROM orden_servicios_externos WHERE depto = '".$_SESSION['depto']."'");
				if($datos=mysql_fetch_array($rs)){?>
					<select name="cmb_nomProveedor" class="combo_box">
						<option value="">Proveedor</option><?php
						do{?>
							<option value="<?php echo $datos['nom_proveedor']; ?>"><?php echo $datos['nom_proveedor']; ?></option><?php
						}while($datos=mysql_fetch_array($rs));?>
					</select><?php
				}
				else{ 
					echo "<label class='msje_correcto'>No Hay Proveedores Registrados para su Consulta</label>";
					?><input type="hidden" name="cmb_nomProveedor" id="cmb_nomProveedor"/><?php
				}
				//Cerrar conexion
				mysql_close($conn);?>           
				</td>
			</tr>
		</table>
		
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="15%" align="right">Fecha Inicio</td>
				<td width="15%">
					<input type="text" name="txt_fechaInicio" id="txt_fechaInicio" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" 
					value="<?php echo date("d/m/Y", strtotime("-7 day")); ?>" />
				</td>
				<td width="15%" align="right">Fecha Fin</td>
				<td width="15%">
					<input type="text" name="txt_fechaFin" id="txt_fechaFin" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" 
					value="<?php echo date("d/m/Y"); ?>" />
				</td>
				<td width="40%" colspan="2">
					<input type="submit" name="sbt_consultar" value="Consultar" class="botones" title="Consultar Orden de Trabajo" onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Orden de Trabajo" 
					onmouseover="window.status='';return true" onclick="location.href='frm_gestionarServiciosExternos.php'" />
				</td>
			</tr>			
		</table>
		</form>
		</fieldset>
		
		
		<div id="calendarioInicio">
			<input type="image" name="img_fechaInicio" id="img_fechaInicio" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_consultarOTSE.txt_fechaInicio,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Inicio"/> 
		</div>
		
		<div id="calendarioFin">
			<input type="image" name="cmb_fechaFin" id="cmb_fechaFin" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_consultarOTSE.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Fin"/> 
		</div><?php 
	}//Cierre else if(isset($_POST['verDetalle']))?>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
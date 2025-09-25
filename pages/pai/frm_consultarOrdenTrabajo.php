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
		include ("op_consultarOrdenTrabajo.php");	

?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionPaileria.js" ></script>
   	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
  	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#titulo-consultarOrdenTrabajo {position:absolute;left:30px;top:146px;width:260px;height:20px;z-index:11;}
		#tabla-consultarOrdenTrabajo {position:absolute;left:30px;top:190px;width:616px;height:176px;z-index:12;padding:15px;padding-top:0px;}
		#calendarioInicio {position:absolute;left:315px;top:284px;width:30px;height:26px;z-index:13;}
		#calendarioFin {position:absolute;left:537px;top:284px;width:30px;height:26px;z-index:14;}
		#consultar-ordenTrabajo {position:absolute;left:33px;top:187px;width:940px;height:430px;z-index:15;overflow:scroll;}
		#btns-regexp {position:absolute;left:30px;top:666px;width:988px;height:57px;z-index:16;}
		#detalleOT { position:absolute; left:30px; top:190px; width:940px; height:300px; z-index:21; overflow: scroll }
		#btn-detalleOT {position:absolute;left:30px;top:548px;width:988px;height:57px;z-index:6;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-gomar.png" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultarOrdenTrabajo">Consultar Orden de Trabajo</div>
	<?php 	
	
	if(isset($_POST['sbt_consultar'])){
		//Guardar los datos en la SESSION 	
		$_SESSION['consultaOT'] = array ("servicio"=> $_POST['cmb_servicio'], "area"=>$_POST['cmb_area'], "familia"=>$_POST['cmb_familia'],
		 "fecha_ini"=> $_POST['txt_fechaInicio'], "fecha_fin"=>$_POST['txt_fechaFin']); 
	
		//Desplegar la orden de trabajo seleccionada
		?><div id="consultar-ordenTrabajo" class="borde_seccion2" align="center"><?php 
			$datosExportar = mostrarOrdenTrabajo($_POST['cmb_familia']);
		?></div>
        <div id="btns-regexp" align="center">
			<form action="guardar_reporte.php" method="post">
				<?php if (count($datosExportar) > 0){ ?>
			
				<input name="hdn_consulta" type="hidden" value="<?php echo $datosExportar[0];  ?>" />
				<input name="hdn_nomConsulta" type="hidden" value="Consulta Orden de Trabajo " />                  		
				<input name="hdn_origen" type="hidden" value="consultarOrdenTrabajo" />
				<input name="hdn_msg" type="hidden" value="<?php echo $datosExportar[1]; ?>" />
				<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" title="Exportar a Excel los Datos de la Consulta Realizada"
				onmouseover="window.estatus='';return true" 
				<?php if(count($datosExportar)==0){?> disabled="disabled" <?php }?>/>
				<?php }?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Consultar Orden de Trabajo" 
			onmouseover="window.status='';return true" onclick="location.href='frm_consultarOrdenTrabajo.php'" /> 
			</form>
		</div>
	<?php		
	}//Cierre if(!isset)
	else{
		if(isset($_POST['verDetalle'])){ 
			//Mostrar el detalle  Seleccionado
			mostrarDetalleOT($ckb);		
		}
		else {
			/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
			$atributo = "";
			$area = "";
			$estado = 1;//El estado 1 Indica que el usuario con la SESSION abierta es AuxMtto
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
			else if($_SESSION['depto']=="Paileria"){
				$area = "GOMAR";
				$atributo = "disabled='disabled'";
				$estado = 0;
			}
			
			if($estado==0){ ?>		
				<script type="text/javascript" language="javascript">
					//cargarCombo('<?php echo $area;?>','bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');
					cargarCombo('<?php echo $area;?>','bd_paileria','gama','familia_aplicacion','area_aplicacion','cmb_familia','Familia','');
				</script>
			<?php }	?>
			
			<fieldset class="borde_seccion" id="tabla-consultarOrdenTrabajo" name="tabla-consultarOrdenTrabajo">
			<legend class="titulo_etiqueta">Consultar la &Oacute;rdenes de Trabajo</legend>	
			<br>
			<form onSubmit="return valFormConsultarOrdenTrabajo(this);" name="frm_consultarOrdenTrabajo" method="post" action="frm_consultarOrdenTrabajo.php">
			<table width="596"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<!-- <td width="67" height="40"><div align="right">Servicio</div></td>
					<td width="81"><?php
						if(!isset($_POST['cmb_servicio']))
						$cmb_servicio = "";	?>
						<select name="cmb_servicio" id="cmb_servicio" size="1"  class="combo_box">
						<option selected="selected" value="">Servicio</option>
						<option <?php if($cmb_servicio=='INTERNO') echo "selected='selected'"?> value="INTERNO">INTERNO</option>
						<option <?php if($cmb_servicio=='EXTERNO') echo "selected='selected'"?> value="EXTERNO">EXTERNO</option>
						</select>
					</td> -->
					<input type="hidden" id="cmb_servicio" name="cmb_servicio" value="INTERNO"/>
					<td width="66"><div align="right">&Aacute;rea</div></td>
					<td width="109">
						<?php if($estado==1){ ?>
							<select name="cmb_area" class="combo_box" 
							onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');">
								<option value="">&Aacute;rea</option>						
								<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'";?>>CONCRETO</option>
								<option value="MINA" <?php if($area=="MINA") echo "selected='selected'";?>>MINA</option>
								<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'";?>>GOMAR</option>
							</select>
						<?php } else { ?>
							<select name="cmb_area" class="combo_box" onchange="cargarCombo(this.value,'bd_mantenimiento','gama','familia_aplicacion','area_aplicacion','cmb_familia','Familia','');" <?php echo $atributo; ?>>
								<option value="">&Aacute;rea</option>						
								<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
								<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
								<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'";?>>GOMAR</option>
							</select>		
							<input type="hidden" name="cmb_area" value="<?php echo $area; ?>" />
						<?php } ?>				</td>
					<td width="89"><div align="right">Familia</div></td>
					<td width="87"><select name="cmb_familia" id="cmb_familia" >
						<option value="">Familia</option>
						</select>
					</td>
				</tr>
				<tr>
					<td align="right">&nbsp;</td>
					<td><div align="right">Fecha Inicio</div></td>
					<td><input type="text" name="txt_fechaInicio" id="txt_fechaInicio" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" 
						value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> />
					</td>
					<td><div align="right">Fecha Fin</div></td>
					<td><input type="text" name="txt_fechaFin" id="txt_fechaFin" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" 
						value=<?php echo date("d/m/Y"); ?> />
					</td>
					<td width="87">&nbsp;</td>
				</tr>
				<tr>
					<td height="63" colspan="6"><div align="center">
					&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_consultar" value="Consultar" class="botones" title="Consultar Orden de Trabajo"
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" 
					title="Regresar al Men&uacute; Orden de Trabajo " 
					onmouseover="window.status='';return true" onclick="location.href='menu_ordenTrabajo.php'" /></div> 
					</td>
				</tr>
			</table>
			</form>
</fieldset>
			<div id="calendarioInicio">
				<input type="image" name="txt_fechaInicio" id="txt_fechaInicio" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_consultarOrdenTrabajo.txt_fechaInicio,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha de Inicio"/> 
</div>
			<div id="calendarioFin">
				<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_consultarOrdenTrabajo.txt_fechaFin,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha de Fin"/> 
</div><?php 
		}    
	}?>              
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
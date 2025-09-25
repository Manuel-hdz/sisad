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
		//Archivo que incluye la operación de consultar Empleado
		include ("op_consultarOTSE.php");?>
		
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script  type="text/javascript" src="../../includes/validacionCompras.js"></script>	
	<script type="text/javascript" language="javascript">
		//Esta variable guardará la referencia de la ventana donde se registra el costo de las actividades
		var vtnRegCostoActividades = "";
	</script>	
    <style type="text/css">
		<!--				
		#titulo-consultarOTSE {position:absolute; left:30px; top:146px; width:419px; height:25px; z-index:11; }
		#form-consulta {position:absolute; left:30px; top:191px; width:538px; height:186px; z-index:12; }
		#calendar-uno {position:absolute; left:255px; top:270px; width:30px; height:26px; z-index:16; }
		#calendar-dos {position:absolute; left:485px; top:270px; width:30px; height:26px; z-index:17; }
		#tabla-ordenesConsultadas {position:absolute; left:30px; top:406px; width:940px; height:250px; z-index:18; overflow:scroll; }
		#btns-regpdf {position:absolute; left:450px; top:680px; z-index:12; }
		-->
    </style>
</head>
<body onfocus="if(vtnRegCostoActividades.closed){ document.frm_consultarOTSE.submit(); }">
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultarOTSE">Consultar Ordenes de Trabajo Para Servicios Externos </div><?php
	
	//Definir las variables para cargar los campos cuando se entre por primera vez a la pagina
	$area = ""; $estado = ""; $fechaIni = date("d/m/Y", strtotime("-7 day")); $fechaFin = date("d/m/Y"); $atrbCheckBox = "";
	
	//Cuando este definido el boton Consultar (sbt_consultar), mostrar la Ordenes de acuerdo a los parametros seleccionados por el Usuario
	if(isset($_POST['sbt_consultar']) || isset($_POST['cmb_area'])){
		//Recuperar datos del POST para cargar el formulario con las opciones seleccionadas
		$area = $_POST['cmb_area']; $estado = $_POST['cmb_estado']; $fechaIni = $_POST['txt_fechaIni']; $fechaFin = $_POST['txt_fechaFin']; 
		if(isset($_POST['ckb_incluirFechas']))
			$atrbCheckBox = "checked='checked'";?>			
		
		<?php
			//Desplegar las Ordenes de Trabajo para Servicios Externos
			mostrarOrdenesServiciosExternos();
	}?>
	
	
	<fieldset class="borde_seccion" id="form-consulta" name="form-consulta">
	<legend class="titulo_etiqueta">Ordenes de Trabajo para Servicios Externos</legend>	
	<br>
	<form onsubmit="return valFormConsultarOTSE(this);" name="frm_consultarOTSE" id="frm_consultarOTSE" method="post" action="frm_consultarOTSE.php">
		<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="25%" align="right">&Aacute;rea</td>
				<td width="25%">
					<select name="cmb_area" id="cmb_area" class="combo_box">
						<option value="">&Aacute;rea</option>
						<option <?php if($area=="MttoConcreto"){ echo "selected='selected'"; }?> value="MttoConcreto">CONCRETO</option>
						<option <?php if($area=="MttoMina"){ echo "selected='selected'"; }?> value="MttoMina">MINA</option>
					</select>
				</td>
				<td width="25%" align="right">Estado</td>
				<td width="24%">
					<select name="cmb_estado" id="cmb_estado">    	
						<option value="">Seleccionar</option>
						<option <?php if($estado=="SI"){ echo "selected='selected'"; }?> value="SI">COMPLEMENTADAS</option>
						<option <?php if($estado=="NO"){ echo "selected='selected'"; }?> value="NO">NO COMPLEMENTADAS</option>						
					</select>				  
				</td>
			</tr>
			<tr>
				<td align="right">Fecha Inicio</td>
				<td>
					<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" value="<?php echo $fechaIni;  ?>" 
					size="10" maxlength="15" />
				</td>
				<td align="right">Fecha Fin</td>
				<td>
					<input name="txt_fechaFin" id="txt_fechaFin"   readonly="readonly" value="<?php echo $fechaFin;  ?>" size="10" maxlength="15" width="90" />				  
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="checkbox" name="ckb_incluirFechas" id="ckb_incluirFechas" value="SI" <?php echo $atrbCheckBox; ?> />Incluir Fechas en la Consulta
				</td>
			</tr>
			<tr>
				<td align="center" colspan="4">
					<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar" onmouseover="window.status='';return true;" 
					title="Consultar Ordenes de Trabajo para Servicios Externos" />
					&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Inicio" 
					onMouseOver="window.status='';return true" onclick="location.href='inicio_compras.php'" />
				</td>
			</tr>
		</table>
	</form>		
	</fieldset>	
		
	<div id="calendar-uno">
		<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_consultarOTSE.txt_fechaIni,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		width="25" height="25" border="0" />
	</div>
	
	<div id="calendar-dos">
		<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
		onclick="displayCalendar(document.frm_consultarOTSE.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />
	</div>
				
			
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
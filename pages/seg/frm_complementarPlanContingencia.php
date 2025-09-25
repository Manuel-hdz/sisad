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
			#titulo-seleccionar {	position:absolute;	left:24px;	top:146px;	width:292px;	height:20px;	z-index:11;}
			#tabla-seleccionarRegistro {position:absolute;left:30px;top:190px;width:450px;height:149px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-complementarPlan {position:absolute;left:37px;top:187px;width:907px;height:392px;z-index:12;padding:15px;padding-top:0px;}
			#periodo1{position:absolute; left:324px; top:267px; width:30px; height:26px; z-index:18; }	
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8; overflow:scroll}
			#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
			#boton-exp {position:absolute;left:162px;top:541px;width:677px;height:19px;z-index:12;padding:15px;padding-top:0px;}
		-->
    </style>
</head>
<body>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Registrar Plan Contingencia Realizado</div>
<?php 

if(isset($_POST['sbt_compTiemposPlan'])){
	complementarPlanEjecutado();
	
}
	//Colocar y rescatar las condiciones dependiendo de donde venga la clave del Plan seelccionado para ser complementados
	//Cuando se provenga de la Pagina de Modificar Plan Contingencia, guardar el Id del Plan seleccionado en la SESSION
	if(isset($_GET['clavePlan'])){
		$clave = $_GET['clavePlan']; 
		$pagina = "confirmarSalida('menu_planContingencia.php')"; 
	}
	
	//Cuando se llegue a esta pagina desde una Alerta, guardar el Id del Plan seleccionado en la SESSION	
	if(isset($_GET['rdb_plan'])){
		$clave = $_GET['rdb_plan'];
		$pagina = "location.href='inicio_seguridad.php'";
	}
	
?>
<fieldset class="borde_seccion" id="tabla-complementarPlan" name="tabla-complementarPlan">
	<legend class="titulo_etiqueta">Complementar el Plan de Contingencia</legend>
	<form onsubmit="return valFormTiemposPlanEjecutado(this);" name="frm_regTiemposPlan" id="frm_regTiemposPlan" method="post" enctype="multipart/form-data">
	<br/>
	<table width="94%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="21%"><div align="right">Clave Contingencia</div></td>
			<td width="33%">
				<input name="txt_idPlan" type="text" id="txt_idPlan" size="10" maxlength="15" value="<?php echo $clave; ?>" 
				readonly="readonly"  class="caja_de_texto"/>
			</td>
			<td width="15%"><div align="right">*Evidencia 1</div></td>
			<td width="31%">
				<input name="txt_foto1" type="file" class="caja_de_texto" id="txt_foto1" title="Buscar Imagen" 
				onchange="return validarImagen(this,'hdn_imgValida_1');"
				onclick="alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenará');" value="" size="20" maxlength="40" />
				<input type="hidden" name="hdn_imgValida_1" id="hdn_imgValida_1" value="" />
			</td>
		</tr>
		<tr>
			<td><div align="right">*Fecha Ejecuci&oacute;n del Plan</div></td>
			<td>
				<input name="txt_fechaEjecucion" type="text" id="txt_fechaEjecucion" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
				readonly="readonly" class="caja_de_texto"/>
			</td>
			<td><div align="right">Evidencia 2</div></td>
			<td>
				<input name="txt_foto2" type="file" class="caja_de_texto" id="txt_foto2" title="Buscar Imagen" 
				onchange="return validarImagen(this,'hdn_imgValida_2');" value="" size="20" maxlength="40" />
				<input type="hidden" name="hdn_imgValida_2" id="hdn_imgValida_2" value="" />				
			</td>
		</tr>
		<tr>
			<td><div align="right">*Tiempo Total Simulacro</div></td>
			<td>
				<input name="txt_tiempoTotal" type="text" id="txt_tiempoTotal" size="5" maxlength="5" value="" onblur="validarCantHoras(this);" 
				class="caja_de_texto" onkeypress="return permite(event,'num',5);"/> &nbsp;Hr:mn
			</td>
			<td><div align="right">Evidencia 3</div></td>
			<td>
				<input name="txt_foto3" type="file" class="caja_de_texto" id="txt_foto3" title="Buscar Imagen" 
				onchange="return validarImagen(this,'hdn_imgValida_3');" value="" size="20" maxlength="40" />
				<input type="hidden" name="hdn_imgValida_3" id="hdn_imgValida_3" value="" />				
			</td>
		</tr>
		<tr>
			<td><div align="right">*Comentarios</div></td>
			<td>
				<textarea name="txa_comentarios" cols="40" rows="4" class="caja_de_texto" id="txa_comentarios" 
				onkeypress="return permite(event,'num_car',0);" ></textarea>
			</td>
			<td><div align="right">Evidencia 4</div></td>
			<td>
				<input name="txt_foto4" type="file" class="caja_de_texto" id="txt_foto4" title="Buscar Imagen"
				onchange="return validarImagen(this,'hdn_imgValida_4');" value="" size="20" maxlength="40" />
				<input type="hidden" name="hdn_imgValida_4" id="hdn_imgValida_4" value="" />					
			</td>				
		</tr>
		<tr>
			<td><div align="right">*Observaciones</div></td>
			<td>
				<textarea name="txa_observaciones" cols="50" rows="5" class="caja_de_texto" id="txa_observaciones" 
				onkeypress="return permite(event,'num_car',0);" ></textarea>
			</td>
			<td><div align="right">Evidencia 5</div></td>
			<td>
				<input name="txt_foto5" type="file" class="caja_de_texto" id="txt_foto5" title="Buscar Imagen"
				onchange="return validarImagen(this,'hdn_imgValida_5');" value="" size="20" maxlength="40" />
				<input type="hidden" name="hdn_imgValida_5" id="hdn_imgValida_5" value="" />
			</td>
		</tr>
		<tr> 
			<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  	</tr>	
		<tr align="center">
			<td colspan="4">
				<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />	
				<input name="sbt_compTiemposPlan" id="sbt_compTiemposPlan" type="submit" class="botones" value="Registrar" 
				title="Complementa los Tiempos del Plan de Contingencia Ejecutado"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
				<input name="btn_limpiar" id="btn_limpiar" type="reset" class="botones" value="Limpiar" 
				title="Limpiar Formulario" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
				title="Regresar a Seleccionar Otro Plan para su Complementación" 
				onmouseover="window.status='';return true" onclick="<?php echo $pagina;?>"/>					
			</td>
		</tr>
	 </table>
	</form>
</fieldset>
		
<div id="periodo1">
	<input type="image" name="txt_fechaEjecucion" id="txt_fechaEjecucion" src="../../images/calendar.png"
	onclick="displayCalendar(document.frm_regTiemposPlan.txt_fechaEjecucion,'dd/mm/yyyy',this)" 
	onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" 
	title="Seleccionar Fecha de Ejecucion del Plan de Contingencia"/> 
</div>


</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Manejo de la funciones para Registrar los datos de los equipos que se manejan en el Laboratorio
		include ("op_registrarMttoEquipo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-foto {position:absolute;left:30px;top:146px;	width:441px;height:20px;z-index:11;}
		#tabla-cargarFotoLab {position:absolute;left:30px;top:190px;width:917px;height:195px;z-index:14;}
		#detalle-registroFoto {position:absolute;left:56px;top:422px;width:835px;height:171px;z-index:17;overflow:scroll;}
		#btn-finalizar {position:absolute;left:32px;top:680px;width:987px;height:40px;z-index:9;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-foto">Memoria Fotográfica del Servicio de Mantenimiento</div><?php
	
	
	//Cuando se de clic en el boton de sbt_finalizarRegistroMtto que se guarden los registros seleccionados
	$aparicionForm = 0;
		
	//Verificamos que se haya pulsado el boton de finalizar para proceder a Extraer los datos de la BD y subirlos a la SESSION
	if(isset($_POST['sbt_finalizar'])){
		$_SESSION['datosEquiposLab'] = obtenerDatosEquipo();
	}
	else if(isset($_POST['sbt_finalizarRegistroMtto'])){
		guardarRegistroMtto();
		
		//Valida la  aparicion del formulario 
		$aparicionForm = 1;
	}

	if($aparicionForm==0){?>
		<fieldset class="borde_seccion" id="tabla-cargarFotoLab" name="tabla-cargarFotoLab">
		<legend class="titulo_etiqueta">Cargar Fotos del Servicio de Mantenimientos</legend>	
		<br>
		<form onSubmit="return valFormCargarFotoLaboratorio(this);" name="frm_cargarFotoLab" method="post" enctype="multipart/form-data" action="frm_cargarFotoEquipoLab.php">
		<table width="925" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  	<td><div align="right">N&deg; Interno</div></td>
				<td width="180">
					<input type="text" name="txt_numInterno" id="txt_numInterno" size="5" maxlength="4" readonly="readonly" 
					value="<?php 
						if(isset($_SESSION['datosEquiposLab'])){ 
							echo $_SESSION['datosEquiposLab']['no_interno'];
						}
						else{ 
							 echo $_SESSION['datosEquipoAlerta']['idEquipo'];
						}?>" />
				</td>
				<td><div align="right">Instrumento</div></td>
				<td>
					<input type="text" name="txt_instrumento" id="txt_instrumento" size="30" maxlength="30" readonly="readonly" 
					value="<?php 
						if(isset($_SESSION['datosEquiposLab'])){ 
							echo $_SESSION['datosEquiposLab']['nombre'];
						}
						else{ 
							 echo $_SESSION['datosEquipoAlerta']['nombre'];
						}?>" />
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Fotografia Antes del Servicio </div></td>
				<td><input type="file" name="txt_fotografiaAntes" id="txt_fotografiaAntes" onchange="validarImagen(this,'hdn_imgValida_antes');"/></td>
				<td><div align="right">*Fotografia Despu&eacute;s del Servicio </p></td>
				<td><input type="file" name="txt_fotografiaDespues" id="txt_fotografiaDespues" onchange="validarImagen(this,'hdn_imgValida_despues');" /></td>				
			</tr>  
			<tr>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
			</tr>   
			<tr>
				<td colspan="6">
					<div align="center">
						<input type="hidden" name="hdn_imgValida" id="hdn_imgValida_antes" value="si" />
						<input type="hidden" name="hdn_imgValida" id="hdn_imgValida_despues" value="si" />
						<input name="sbt_finalizarRegistroMtto" id="sbt_finalizarRegistroMtto" type="submit" class="botones_largos"
						value="Finalizar Registro" title="Terminar de Registrar Información de los Equipos de Laboratorio"
						onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
						onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Equipos de Laboratorio " 
						onmouseover="window.status='';return true" onclick="confirmarSalida('menu_equipoLaboratorio.php');" />
					</div>			
				</td>
			</tr>
		</table>
		</form>
		</fieldset><?php		
	}//Cierre de if($aparicionForm==0)?>
	
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
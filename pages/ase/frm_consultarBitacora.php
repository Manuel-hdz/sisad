<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento Calidad
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
    <script type="text/javascript" src=""></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-registrar {	position:absolute;	left:30px;	top:146px;	width:340px;	height:20px;	z-index:11;}
			#tabla-escogerBitacora {position:absolute;	left:30px;	top:190px;	width:498px;	height:149px;	z-index:12;	padding:15px;	padding-top:0px;}
			#tabla-escogerOT {position:absolute;	left:30px;	top:190px;	width:498px;	height:149px;	z-index:12;	padding:15px;	padding-top:0px;}
		-->
    </style>
</head>
<body>

<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-registrar">Consulta Mantenimiento - Bit&aacute;cora</div>
<?php

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
	<table width="493" height="108" cellpadding="5" cellspacing="5" class="tabla_frm">
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
				title="Regresar a Seleccionar Otra Consulta"
				onclick="location.href='frm_seleccionarConsultaMtto.php'" onmouseover="window.status='';return true"/>									
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>	    
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-gestionar {position:absolute;left:32px;top:147px;	width:210px;height:20px;z-index:11;}
		#seleccionar-opc {position:absolute;left:30px;top:190px;width:398px;height:149px;z-index:14;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-gestionar">Gestionar Muestras </div>
	<fieldset class="borde_seccion" id="seleccionar-opc" name="seleccionar-opc">
	<legend class="titulo_etiqueta">Seleccionar Operaci&oacute;n a Realizar</legend>	
	<br>
	<form onSubmit="return valFormSeleccionarOpc(this);" name="frm_seleccionarOpc" method="post" action="">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td align="right">Seleccionar</td>
				<td>
					<select name="cmb_opcion" class="combo_box">
						<option value="">Operaci&oacute;n</option>
						<option value="registrar">Registrar Muestra</option>
						<option value="editar">Editar Muestra</option>
					</select>				
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="sbt_continuar" value="Continuar" title="Ejecutar la Opci&oacute;n Seleccionada" class="botones" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" title="Regresar al Men&uacute; de Mezclas" class="botones" 
					onclick="location.href='menu_mezclas.php'" />			  
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
	   
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
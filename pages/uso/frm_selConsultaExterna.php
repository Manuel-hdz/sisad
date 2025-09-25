	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_consultasExternasRH.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-registrar {	position:absolute;	left:30px;	top:146px;	width:384px;	height:20px;	z-index:11;}
			#tabla-seleccionarRegistro {position:absolute;left:29px;top:192px;width:338px;height:149px;z-index:12;padding:15px;padding-top:0px;}
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8; overflow:scroll}
		-->
    </style>
</head>
<body>
<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-registrar">Realizar Consultas a Departamentos Externos</div>
	<fieldset class="borde_seccion" id="tabla-seleccionarRegistro" name="tabla-seleccionarRegistro">
		<legend class="titulo_etiqueta">Seleccionar el Tipo Consulta</legend>	
        <br>
		<form name="frm_selConsulta"  id="frm_selConsulta" method="get" >
			<table cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">Tipo Consulta</div></td>
                	<td>
						<select name="cmb_tipoConsulta" id="cmb_tipoConsulta" class="combo_box"  onchange="selConsulta();" >
							<option selected="selected" value="">CONSULTAS EXTERNAS</option>
							<option value="DATOS GENERALES EMPLEADO">DATOS GENERALES EMPLEADO</option>
							<option value="INCAPACIDADES EMPLEADO">INCAPACIDADES EMPLEADO</option>
						</select>		
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<div align="center">       	    	
							<input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
							title="Regresar al la P&aacute;gina Principal"
							onmouseover="window.status='';return true" onclick="location.href='inicio_clinica.php';"/>					
						</div>				
					</td>
				</tr>
			</table>
		</form>
</fieldset>

</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
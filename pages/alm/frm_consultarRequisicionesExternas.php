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
		//Archivo que hace la consulta de las requisiciones publicadas por cada departamento
		include_once ("op_consultarRequisicionesExternas.php");
	
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>

    <style type="text/css">
		<!--
		#titulo-requisicion {position:absolute;left:25px;top:146px;width:293px;height:22px;z-index:11;}
		#consulta-requisicion {position:absolute; left:30px; top:190px; width:397px; height:160px; z-index:13;}
		#tabla-requisiciones {position:absolute;left:30px;top:190px;width:900px;height:420px;z-index:12;overflow:scroll;}
		#botones{position:absolute;left:30px;top:670px;width:900px;height:37px;z-index:13;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-requisicion">Requisiciones Departamentales</div>
	
	<?php 
		//Mostrar el formulario con el combo de los departamentos para seleccionar las requisiciones
		if(!isset($_POST["sbt_continuar"])){?>
		<fieldset class="borde_seccion" id="consulta-requisicion" name="consulta-requisicion">
			<legend class="titulo_etiqueta">Seleccionar Departamento</legend>
			<br>
			<form onSubmit="return valFormConsultarRequisicionesExternas(this);" name="frm_consultarRequisicionesExternas" method="post" action="frm_consultarRequisicionesExternas.php">
			<table border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<tr>
					<td width="32%" align="right">Departamento</td>
					<td width="68%">
					<select name="cmb_departamento" id="cmb_departamento" class="combo_box">
						<option value="" selected="selected">Departamento</option>
						<option value="desarrollo">DESARROLLO</option>
						<option value="topografia">TOPOGRAF&Iacute;A</option>
						<option value="gerenciatecnica">GERENCIA TECNICA</option>
						<option value="laboratorio">LABORATORIO</option>
						<option value="produccion">PRODUCCI&Oacute;N</option>
						<option value="mantenimiento">MANTENIMIENTO</option>
						<option value="seguridadindustrial">SEGURIDAD INDUSTRIAL</option>
						<option value="recursoshumanos">RECURSOS HUMANOS</option>
						<option value="aseguramientodecalidad">ASEGURAMIENTO DE CALIDAD</option>
						<option value="paileria">PAILERIA</option>
					</select>
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td colspan="2" align="center">
					<input name="sbt_continuar" type="submit" class="botones" value="Continuar" onMouseOver="window.status='';return true" title="Consultar Requisiciones del Departamento Seleccionado" />&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" onMouseOver="window.status='';return true" title="Regresar a la P&aacute;gina de Inicio" onclick="location.href='inicio_almacen.php';"/>
					</td>
				</tr>
			</table>
			</form>
		</fieldset>
	<?php
	}
	//Mostrar la tabla con las requisiciones del departamento
	else{
		echo "<div id='tabla-requisiciones' class='borde_seccion2'>";
		mostrarRequisiciones($_POST["cmb_departamento"]);
		echo "</div>";
		?>
		<div id="botones" align="center">
		<input name="btn_regresar" type="button" class="botones" value="Regresar" onMouseOver="window.status='';return true" title="Regresar a Seleccionar otro Departamento" onclick="location.href='frm_consultarRequisicionesExternas.php';"/>
		</div>
		<?php
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include ("head_menu.php");
		include("op_consultarBitacoraSISAD.php");
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionCPanel.js"></script>

	<style type="text/css">
		<!--
		#titulo-consultar {position:absolute;left:23px;top:145px;width:436px;height:20px;z-index:11;}
		#tabla-seleccionarDatos {position:absolute;left:30px;top:190px;width:500px;height:115px;z-index:12;}
		#movimientos {position:absolute;left:30px;top:190px; width:400px; height:375px;z-index:13; overflow:scroll;}
		#grafica {position:absolute;left:480px;top:190px; width:500px; height:375px;z-index:13; overflow:scroll;}
		#boton {position:absolute;left:410px;top:600px; width:100px; height:50px;z-index:15;}
		#calendar-uno { position:absolute; left:243px; top:234px; width:30px; height:26px; z-index:14; }
		#calendar-dos { position:absolute; left:519px; top:233px; width:30px; height:26px; z-index:15; }
		-->
	</style>
	</head>
	<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30"/></div>
	<div class="titulo_barra" id="titulo-consultar">Consultar SISAD - Consultar Bit&aacute;cora Movimientos </div>
	<?php if(!isset($_POST['sbt_consultar'])){
	borrarGraficoCalidad();?>	
	<fieldset class="borde_seccion" id="tabla-seleccionarDatos" name="tabla-seleccionarDatos">
	<legend class="titulo_etiqueta">Seleccionar M&oacute;dulo a Consultar</legend>
	<br>
	<form name="frm_consultarBitacora" method="post" action="frm_consultarBitacoraSISAD.php">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="20%"><div align="right">Fecha Inicio</div></td>
			<td width="27%">
				<input name="txt_fechaIni" id="txt_fechaIni" type="text" readonly="readonly" class="caja_de_texto" 
				value="<?php echo date("d/m/Y",strtotime("-30 day"));?>" size="10" maxlength="10"/></td>
			<td width="29%"><div align="right">Fecha Fin</div></td>
			<td width="24%">
				<input name="txt_fechaFin" id="txt_fechaFin" type="text" readonly="readonly" class="caja_de_texto" value="<?php echo date("d/m/Y");?>" 
				size="10" maxlength="10"/>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<div align="center">
					<input name="sbt_consultar" type="submit" class="botones" value="Consultar" title="Consultar Acciones del Departamento Seleccionado"
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_borrar" class="botones" value="Limpiar" title="Reestablecer el Formulario"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar Seleccionar Otra Consulta" 
					onMouseOver="window.status='';return true" onclick="location.href='frm_seleccionarConsulta.php';" />
				</div>
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
	<div id="calendar-uno">
		<input type="image" name="iniRepFecha" id="iniRepFecha" src="../../images/calendar.png" title="Seleccione Fecha de Inicio"
		onclick="displayCalendar(document.frm_consultarBitacora.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
	</div>
	<div id="calendar-dos">
		<input type="image" name="finRepFecha" id="finRepFecha" src="../../images/calendar.png" title="Seleccione Fecha de Fin" 
		onclick="displayCalendar(document.frm_consultarBitacora.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" />
	</div><?php 
}
//Si esta definido el boton de consultar mostramos los movimientos
	if (isset($_POST["sbt_consultar"])){
	//Mostrar a los Usuarios Registrados
		mostrarMovimientos($_POST["txt_fechaIni"],$_POST["txt_fechaFin"]);?>
		<div id="boton">
		<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar Seleccionar Otra Consulta" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_consultarBitacoraSISAD.php';" />
		</div><?php 
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
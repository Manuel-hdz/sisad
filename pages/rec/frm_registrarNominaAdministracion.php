<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
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
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-consultar{position:absolute;left:30px;top:146px; width:256px;height:20px;z-index:11;}
			#tabla-consultarFechas{position:absolute;left:30px;top:190px;width:425px;height:145px;z-index:12;}
			#calendario-uno{position:absolute;left:258px;top:235px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:258px;top:271px;width:30px;height:26px;z-index:13;}
			#botones{position:absolute;left:30px;top:640px;width:945px;height:40px;z-index:15;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar" style="width:300px;">Registrar N&oacute;mina de Administraci&oacute;n</div>

	<?php 
	if (isset($_GET["borrar"]) && isset($_SESSION["bonoNomina"])){
		unset($_SESSION["bonoNomina"]);
	}
	?>
	<fieldset class="borde_seccion" id="tabla-consultarFechas" name="tabla-consultarFechas">
	<legend class="titulo_etiqueta">Seleccione Fechas de Trabajo</legend>	
	<br>
	<form name="frm_registrarNomina" method="post" action="frm_registrarNominaInternaAdministracion.php" onsubmit="return valGenerarNomina(this);">
		<table width="415"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="104"><div align="right">Fecha Inicio</div></td>
				<td width="276">
					<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" 
					value="<?php echo date("d/m/Y", strtotime("-7 day"));?>" readonly="readonly"/>
				</td>
			</tr>
				<td width="104"><div align="right">Fecha Fin</div></td>
				<td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
			</tr>
			<tr>
				<td colspan="4">
					<div align="center">
						<input name="sbt_consultar" type="submit" class="botones" id= "sbt_consultar" value="Continuar" title="Continuar a Registrar N&oacute;mina de Administraci&oacute;n"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;
						<input name="btn_reset" type="reset" class="botones" id="btn_reset" value="Restablecer" title="Restablecer el Formulario"/>
						&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Sueldos" 
						onmouseover="window.status='';return true" onclick="location.href='menu_registrar_nomina.php'" />
					</div>
				</td>
			</tr>
	  </table>
	</form>
	</fieldset>
	
	<div id="calendario-uno">
		<input name="calendario_uno" type="image" id="calendario_uno" onclick="displayCalendar (document.frm_registrarNomina.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
		title="Seleccione Fecha de Inicio" />
	</div>
	<div id="calendario-dos">
		<input name="calendario_dos" type="image" id="calendario_dos" onclick="displayCalendar (document.frm_registrarNomina.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
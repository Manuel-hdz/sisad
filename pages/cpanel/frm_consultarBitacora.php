<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Gerencia T�cnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include("op_consultarBitacora.php");
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
		#titulo-consultar {position:absolute;left:30px;top:35px;width:298px;height:20px;z-index:11;}
		#tabla-seleccionarDatos {position:absolute;left:30px;top:80px;width:500px;height:140px;z-index:12;}
		#movimientos {position:absolute;left:30px;top:250px; width:742px; height:375px;z-index:13; overflow:scroll;}
		#calendar-uno { position:absolute; left:245px; top:158px; width:30px; height:26px; z-index:14; }
		#calendar-dos { position:absolute; left:520px; top:158px; width:30px; height:26px; z-index:15; }
		-->
	</style>
	</head>
	<body>

	<div id="barraCP"><img src="../../images/title-bar-bg.gif" width="100%" height="30"/></div>
	<div class="titulo_barra" id="titulo-consultar">Consultar Bit&aacute;cora</div>
		
	<fieldset class="borde_seccion" id="tabla-seleccionarDatos" name="tabla-seleccionarDatos">
	<legend class="titulo_etiqueta">Seleccionar M&oacute;dulo a Consultar</legend>
	<br>
	<form name="frm_consultarBitacora" method="post" action="frm_consultarBitacora.php" onsubmit="return valFormConsultarBitacora(this);">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="20%"><div align="right">M&oacute;dulo</div></td>
			<td colspan="3">
				<select name="cmb_modulo" id="cmb_modulo" size="1" class="combo_box">
					<option value="" selected="selected">Departamento</option>
					<option value="Almacen">ALMACEN</option>
					<option value="Calidad">ASEGURAMIENTO CALIDAD</option>
					<option value="Compras">COMPRAS</option>
					<option value="Desarrollo">DESARROLLO</option>
					<option value="GerenciaTecnica">GERENCIA TECNICA</option>
					<option value="Laboratorio">LABORATORIO</option>
					<option value="Lampisteria">LAMPISTERIA</option>
					<option value="Mantenimiento">MANTENIMIENTO</option>
					<option value="MttoElectrico">MANTENIMIENTO ELECTRICO</option>
					<option value="Paileria">PAILERIA</option>
					<option value="Produccion">PRODUCCION</option>
					<option value="RecursosHumanos">RECURSOS HUMANOS</option>
					<option value="Seguridad">SEGURIDAD</option>
					<option value="Topografia">TOPOGRAFIA</option>
					<option value="Clinica">UNIDAD SALUD OCUPACIONAL</option>
					<option value="Comaro">COMARO</option>
					<option value="Sistemas">SISTEMAS</option>
					<option value="SupervisionDes">SUPERVISION DESARROLLO</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><div align="right">Fecha Inicio</div></td>
			<td width="27%"><input name="txt_fechaIni" id="txt_fechaIni" type="text" readonly="readonly" class="caja_de_texto" value="<?php echo date("d/m/Y",strtotime("-30 day"));?>" size="10" maxlength="10"/></td>
			<td width="29%"><div align="right">Fecha Fin</div></td>
			<td width="24%"><input name="txt_fechaFin" id="txt_fechaFin" type="text" readonly="readonly" class="caja_de_texto" value="<?php echo date("d/m/Y");?>" size="10" maxlength="10"/></td>
		</tr>
		<tr>
			<td colspan="4">
				<div align="center">
					<input name="sbt_consultar" type="submit" class="botones" value="Consultar" title="Consultar Acciones del Departamento Seleccionado"
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_borrar" class="botones" value="Limpiar" title="Reestablecer el Formulario"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Inicio" 
					onMouseOver="window.status='';return true" onclick="location.href='main.php';" />
				</div>
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
	<div id="calendar-uno">
		<input type="image" name="iniRepFecha" id="iniRepFecha" src="../../images/calendar.png" onclick="displayCalendar(document.frm_consultarBitacora.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
	</div>
	
	<div id="calendar-dos">
		<input type="image" name="finRepFecha" id="finRepFecha" src="../../images/calendar.png" onclick="displayCalendar(document.frm_consultarBitacora.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" />
	</div>
	
	<?php 
		//Si esta definido el boton de Guardar, almacenar la informacion
		//del nuevo Usuario en la BD
		if (isset($_POST["sbt_consultar"])){
		//Mostrar a los Usuarios Registrados
		echo "<div class='borde_seccion' id='movimientos'>";
			$res=mostrarMovimientos($_POST["cmb_modulo"],$_POST["txt_fechaIni"],$_POST["txt_fechaFin"]);
			//Si res es igual a 0, no se encontraron resultados
			if ($res==0){
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("mensaje()",500);
					function mensaje(){
						alert("No Hay Movimientos Registrados en las Fechas Seleccionadas");
					}
				</script>
				<?php
				echo "<br><br><br><br><br><br><br><br><br><br><p class='msje_correcto' align='center'>No Hay Movimientos Registrados en las Fechas Seleccionadas</p>";
			}
		echo "</div>";
		}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
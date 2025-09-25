<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	include ("op_consultarbitacora.php"); 
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
	<script type="text/javascript" src="../../includes/validacionComaro.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#consultar-bitacora {position:absolute;left:30px;top:190px;width:600px;height:190px;z-index:12;}
		#tabla-bitacora {position:absolute;left:30px;top:190px;width:920px;height:400px;z-index:12; overflow:scroll;}
		#botones-bitacora {position:absolute;left:30px;top:640px;width:920px;height:30px;z-index:12;}
		#calendario-Ini {position:absolute;left:260px;top:230px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:570px;top:230px;width:30px;height:26px;z-index:14;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg-comaro.png" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Consultar Bitacoras</div>
		
	<?php
	if(isset($_POST["sbt_consultar"])){
	?>
		<form name="frm_consultarBitacora" method="post" action="frm_consultarBitacora.php" onsubmit="">
			<div id="tabla-bitacora" class="borde_seccion2" align="center"> 
				<?php
				mostrarBitacoras();
				?>
			</div>
			<div id="botones-bitacora" align="center">
				<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver a Seleccionar los Parametros de Busqueda" onclick="location.href='frm_consultarBitacora.php'"/>
			</div>
		</form>
	<?php
	} else {
	?>
		<fieldset class="borde_seccion" id="consultar-bitacora" name="consultar-bitacora">
		<legend class="titulo_etiqueta">Seleccionar Parametros de Busqueda</legend>	
		<br>
		<form name="frm_consultarBitacora" method="post" action="frm_consultarBitacora.php" onsubmit="return validarFechas(txt_fechaIni.value,txt_fechaFin.value);">
			<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
				<td><div align="right">Fecha Inicio</div></td>
				<td>
					<input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y"); ?>" 
					readonly="readonly"/>
				</td>
				<td><div align="right">Fecha Fin</div></td>
				<td>
					<input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y"); ?>" 
					readonly="readonly"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">Estado</div></td>
				<td>
					<select name="cmb_estado" id="cmb_estado" class="combo_box">
						<option value="">Seleccionar Estado</option>
						<option value="A">Apartado</option>
						<option value="E">Entregado</option>
					</select>			
				</td>
				<td><div align="right">Pagado</div></td>
				<td>
					<select name="cmb_pag" id="cmb_pag" class="combo_box">
						<option value="">Seleccionar</option>
						<option value="NO">NO</option>
						<option value="SI">SI</option>
					</select>			
				</td>
			</tr>
			<tr>
				<td><div align="right">Turno</div></td>
				<td>
					<select name="cmb_turno" id="cmb_turno" class="combo_box">
						<option value="">Seleccionar Turno</option>
						<option value="PRIMERA">Turno de Primera</option>
						<option value="SEGUNDA">Turno de Segunda</option>
						<option value="TERCERA">Turno de Tercera</option>
					</select>			
				</td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input type="submit" class="botones" name="sbt_consultar" id="sbt_consultar" value="Consultar" title="Consultar Bitacoras Registrdas;" onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; de Bitacoras" onclick="location.href='menu_bitacoras.php'"/>
				</td>
			</tr>
			</table>
		</form>
		</fieldset>
		<div id="calendario-Ini">
			<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_consultarBitacora.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Inicio"/> 
		</div>
		
		<div id="calendario-Fin">
			<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_consultarBitacora.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Fin"/> 
		</div>
	<?php
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
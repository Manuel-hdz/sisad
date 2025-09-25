<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">


<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento Concreto
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para Generar el Reporte de Mnatenimientos Correctivos de Acuerdo a los Parametros Seleccionados
		include ("op_gestionEstatus.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js"></script>

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:264px; height:24px; z-index:11; }
		#bitacoraAceites { position:absolute; left:30px; top:190px; width:405px; height:175px; z-index:12; }
		#calendario { position:absolute; left:178px; top:210px; width:30px; height:26px; z-index:13; }
		#equipos { position:absolute; left:30px; top:190px; width:921px; height:450px; z-index:22; overflow: scroll; z-index:14;}
		#botones{position:absolute;left:30px;top:675px;width:950px;height:37px;z-index:15;}
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		-->
    </style>
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Bit&aacute;cora de Consumo de Aceite</div>

	<?php 
	if(!isset($_POST["sbt_guardar"])){
	?>
		<form onSubmit="return valFormRegEstatusEquipos(this);" name="frm_gestionEstatus" method="post" action="frm_registrarEstatus.php">
			<div id='equipos' class='borde_seccion' align='center'>
				<BR />
				<table width="100%">
					<tr>
						<td width="5%" align="right">Fecha</td>
						<td align="left"><input type="text" name="txt_fecha" id="txt_fecha" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo date("d/m/Y"); ?>"/></td>
					</tr>
				</table>
				<?php mostrarEquipos();?>
			</div>
				
			<div id="botones" align="center">
				<input type="submit" class="botones" value="Guardar" title="Guardar Registros de Status de los Equipos" onmouseover="window.status='';return true" name="sbt_guardar"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" class="botones" value="Limpiar" title="Limpiar los datos del Formulario" name="btn_reset" onclick="restablerFormularioStatus();"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" value="Cancelar" title="Cancelar y volver a Seleccionar otra Familia" onclick="location.href='menu_equipos.php'" name="btn_cancelar"/>
			</div>
		</form>
		
		<div id="calendario">
			<input name="fechaRegistro" id="fechaRegistro" type="image" src="../../images/calendar.png" title="Seleccionar la Fecha de Registro de Consumo de Aceite"
			onclick="displayCalendar(document.frm_gestionEstatus.txt_fecha,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />
		</div>
	<?php
	}
	else{
		guardarStatusEquipos();
		?>
		<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div>
		<?php
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
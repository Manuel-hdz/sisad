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
		//Este archivo permite realizar las consultas a los Reportes REA, además del detalle de los mismos
		include ("op_consultarREA.php");	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>	
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarComboREA.js"></script>
    <style type="text/css">
		<!--
		#form-datos-salida { position:absolute; left:30px; top:190px; width:440px; height:160px; z-index:12; }
		#titulo-consultarREA { position:absolute; left:25px; top:146px; width:236px; height:19px; z-index:14; }
		#tabla-REA { position:absolute; left:25px; top:190px; width:940px; height:400px; z-index:15; overflow:scroll}
		#botones{position:absolute;left:30px;top:650px;width:971px;height:37px;z-index:13;}
		#calendar-uno { position:absolute; left:268px; top:218px; width:30px; height:26px; z-index:13; }
		#calendar-dos { position:absolute; left:268px; top:255px; width:30px; height:26px; z-index:14; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultarREA">Consultar REA</div>

<?php 
	//Si la variables $txt_fecha no esta definida en el arreglo $_POST, entonces desplegar el formulario para solictar las fechas
	if(!isset($_POST['txt_fechaIni'])&&!isset($_POST["sbt_detalle"])&&!isset($_POST['ckb_id'])){?>	
		<fieldset id="form-datos-salida" class="borde_seccion">	
		<legend class="titulo_etiqueta">Seleccione los Criterios de B&uacute;squeda del Reporte REA</legend>
		<form name="frm_datosReporteREA" onsubmit="return valFormConsultarREA(this);" id="frm_datosReporteREA" action="frm_consultarReporteREA.php" method="post">
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">		
   			<tr>
    			<td width="30%"><div align="right">Fecha Inicio </div></td>
    		    <td>
					<input name="txt_fechaIni" type="text" class="caja_de_texto" id="txt_fechaIni" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> 
					size="10" maxlength="15" readonly=true width="90"></td>
			</tr>
            <tr>
				<td><div align="right">Fecha Fin </div></td>
				<td><input name="txt_fechaFin" type="text" class="caja_de_texto" id="txt_fechaFin" value=<?php echo date("d/m/Y"); ?> size="10" 
                    maxlength="15" readonly=true width="90" /></td>
            </tr>
			<tr>
				<td colspan="6" align="center">
					<input name="sbt_consultar" type="submit" class="botones" value="Ver Reporte" onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_limpiar" type="reset" class="botones" value="Restablecer"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Cancelar" onclick="location.href='inicio_compras.php'" 
					onmouseover="window.status='';return true;"/>				
				</td>
			</tr>
		</table>
		</form>   	
		</fieldset>
		
		<div id="calendar-dos">
			<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_datosReporteREA.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />
		</div>
		<div id="calendar-uno">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_datosReporteREA.txt_fechaIni,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" />
		</div>
		
		<?php
	}
	if(isset($_POST["txt_fechaIni"])&&!isset($_POST["sbt_detalle"])&&!isset($_POST['ckb_id'])){?>
		<form action="frm_consultarReporteREA.php" method="post" name="frm_mostrarDetalleREA">
			<div id="tabla-REA" align="center" class="borde_seccion2">
				<?php mostrarREA(); ?></div>
			<div id="botones" align="center">
				<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni'];?>"/> 
				<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin'];?>"/>
				<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar a la Selecci&oacute;n de Reportes REA"
                onclick="location.href='frm_consultarReporteREA.php'"/>
			</div>
		</form><?php 
	}
	if(isset($_POST['ckb_id'])){?>
		<form action="frm_consultarReporteREA.php" method="post" name="frm_mostrarDetalleREA">
			<div id="tabla-REA" align="center" class="borde_seccion2">
			<?php mostrarDetalleREA($_POST['ckb_id']); ?></div>
			<div id="botones" align="center">
				<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni'];?>"/> 
				<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin'];?>"/>
				<input name="sbt_regresar" type="submit" value="Regresar" class="botones" title="Regresar a la Selecci&oacute;n de Reportes REA" 
				onmouseover="window.status='';return true;"/>
		  	</div>			 
		</form>
<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
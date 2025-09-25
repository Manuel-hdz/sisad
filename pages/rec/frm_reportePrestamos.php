<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que incluye las operaciones para realizar el reporte de Prestamos
		include ("op_reportePrestamos.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consultar-empleado {position:absolute;left:30px; top:144px;	width:228px; height:25px; index:1; z-index: 12;}
			#tabla-consultar-empleados2 {position:absolute; left:30px; top:198px; width:454px; height:117px; }
			#tabla-empleados { position:absolute; left:30px; top:190px; width:945px; height:380px;  overflow:scroll; }
			#detallePrestamo {	position:absolute;	left:30px;	top:190px;	width:940px;	height:351px;	overflow: scroll;	z-index: 30;}
			#btns-regpdf { position: absolute; left:30px; top:630px; width:940px; height:35px;  }
			#btn-regresar { position: absolute; left:456px; top:601px; width:112px; height:35px;  }
			#calendar-tres { position:absolute;	left:251px;	top:240px;	width:30px;	height:26px;	z-index: 21;}
			#calendar-cuatro {	position:absolute;	left:461px;	top:241px;	width:30px;	height:26px;	z-index: 20;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-empleado">Reporte de Pr&eacute;stamos </div><?php 
	//Verificamos si viene definido en el post el boton consultar
	if(isset($_POST["sbt_consultarPrestamos"])||isset($_POST["hdn_fechaFin"])){
		//Si viene definido el boton; mostrar el reporte de Prestamos
		reportePrestamos();
	 }
	elseif(isset($_POST['verDetalle'])){?>
		<form name="frm_detallePrestamosEmpleados" method="post" action="frm_reportePrestamos.php">
			<div id="detallePrestamo" class='borde_seccion2' align="center">
			  <?php detallePrestamos($_POST['ckb_detallePrestamos']);?>
			</div>
		
			<div align="center" id="btn-regresar">
				<input type="submit" name="btn-regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Reporte Pr&eacute;stamos" 
				onMouseOver="window.estatus='';return true" />
		  </div>
		</form>
<?php } 
	else {?>
		<fieldset class="borde_seccion" id="tabla-consultar-empleados2">
		<legend class="titulo_etiqueta">Reporte Pr&eacute;stamos por Fecha </legend>	
			<br>
			<form  method="post" name="frm_reportePrestamos" id="frm_reportePrestamos"  onsubmit="return valFormRptPrestamos(this);" >
			<table width="98%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">Fecha Inicio</div></td>
					<td>
						<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>"
						size="10" width="90"/>
					</td>
					<td><div align="right">Fecha Fin </div></td>
					<td>	
						<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly"  value="<?php echo date("d/m/Y"); ?>" size="10" 
						width="90"/>
					</td>
			  	</tr>
				<tr>
					<td colspan="4" align="center">
						<input name="sbt_consultarPrestamos" type="submit" class="botones" id="sbt_consultarPrestamos" 
						value="Consultar" onmouseover="window.status='';return true;" title="Consultar Prestamos"/>
						&nbsp;&nbsp;			
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Reportes"  
						onmouseover="window.status='';return true" onclick="location.href='menu_reportes.php'" />
					</td>
				</tr>
			</table>
		</form>
		</fieldset>
		<div id="calendar-tres">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reportePrestamos.txt_fechaIni,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<div id="calendar-cuatro">
			<input name="fechaFin" type="image" id="fechaFin" onclick="displayCalendar(document.frm_reportePrestamos.txt_fechaFin,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />	
		</div>
	<?php } ?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
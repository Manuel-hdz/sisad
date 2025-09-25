<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	//if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
	//Enviar a la pagina de acceso negado
	//	echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	//}
	//else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_consultasExternas.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--	
			#titulo-consultar-empleado {position:absolute; left:30px; top:146px; width:388px; height:25px; z-index:11; }
			#tabla-consultar-empleados {position:absolute; left:30px; top:198px; width:436px; height:133px; z-index:14;}
			#tabla-empleados { position:absolute; left:30px; top:190px; width:945px; height:430px; z-index:21; overflow:scroll; }
			#botones{position:absolute;left:30px;top:670px;width:950px;height:37px;z-index:16;}
			#calendar-tres {position:absolute; left:235px; top:239px; width:30px; height:26px; z-index:18; }
			#calendar-cuatro {position:absolute; left:445px; top:240px; width:30px; height:26px; z-index:18; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-empleado">Recursos Humanos - Reporte de Asistencias</div><?php 

	//Verificamos si viene definido en el post el boton consultar
	if(isset($_POST["sbt_consultar"])){?>
		<div align="center" id="botones">
			<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Seleccionar otras Fechas" 
			onMouseOver="window.status='';return true" onclick="location.href='frm_consultarRH.php'" />
		</div>
		
		<div align='center' id='tabla-empleados' class='borde_seccion2' width='100%' ><?php
			//Si viene definido el boton; mostrar el reporte de Asistencias
			reporteAsistencias();?>					
		</div>
		<?php	
	 }
	 
	 else{ ?> 
		<fieldset class="borde_seccion" id="tabla-consultar-empleados">
		<legend class="titulo_etiqueta">Reporte de Asistencias por Fecha</legend>	
		<br>
		<form  method="post" name="frm_reporteFecha" id="frm_reporteFecha" onsubmit="return valFormRptAsistenciaFecha(this);" >
		<table width="444" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Fecha Inicio</div></td>
				<td width="95">
					<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text"
					value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10"  width="90"/>			     
				</td>
				<td width="85"><div align="right">Fecha Fin </div></td>
				<td width="122">
					<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly"
					value="<?php echo date("d/m/Y"); ?>" size="10" width="90"/>
				</td>
			</tr>
		</table>
		<div align="center">
			<p>
				<input name="sbt_consultar" type="submit"  class="botones" id="sbt_consultar" value="Consultar"
				onmouseover="window.status='';return true;" title="Generar Reporte Asistencia"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" name="btn_limpiar" class="botones" value="Restablecer" title="Restablece el Formulario"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Inicio" 
				onMouseOver="window.status='';return true" onclick="location.href='inicio_mantenimiento.php'" />
			</p>
		</div>
		</form>
		</fieldset>
		<div id="calendar-tres">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaIni,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" />
		</div>
		<div id="calendar-cuatro">
			<input name="fechaFin" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaFin,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" />
		</div><?php 
	}?>
</body><?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
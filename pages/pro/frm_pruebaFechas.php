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
		//Archivo que incluye las operaciones para realizar el reporte de Asistencia?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionProduccion.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consultar-empleado {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
			#tabla-consultar-empleados {position:absolute; left:30px; top:198px; width:436px; height:163px; z-index:14;}
			#tabla-consultar-empleados2 {position:absolute; left:527px; top:198px; width:436px; height:163px; z-index:14;}
			#tabla-empleados { position:absolute; left:30px; top:190px; width:945px; height:380px; z-index:21; overflow:scroll; }
			#tabla-datos { position:absolute; left:404px; top:-10px; width:157px; height:56px; z-index:21;}
			#btns-regpdf { position: absolute; left:30px; top:620px; width:945px; height:40px; z-index:23; }
			#calendar-uno {position:absolute; left:234px; top:240px; width:30px; height:26px; z-index:18; }
			#calendar-dos {position:absolute; left:450px; top:240px; width:30px; height:26px; z-index:19; }
			#calendar-tres {position:absolute; left:732px; top:239px; width:30px; height:26px; z-index:18; }
			#calendar-cuatro {position:absolute; left:941px; top:240px; width:30px; height:26px; z-index:18; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-empleado">Pruebas con Fechas </div>
			</form> 
			<fieldset class="borde_seccion" id="tabla-consultar-empleados">
			<legend class="titulo_etiqueta">Fechas</legend>	
			<br>
			<form  method="post" name="frm_Prueba" id="frm_Prueba" >
			<table width="444" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="63"><div align="right">Fecha Inicio</div></td>
				  	<td width="95">
						<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
						value="<?php echo date("d/m/Y", strtotime("-1 month")); ?>" size="10"  width="90"  onchange="validarFechas(); calcularDiasLaborales(); calcularDomingos();"/>					</td>
					<td width="85"><div align="right">Fecha Fin </div></td>
					<td width="122">
						<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
						value="<?php echo date("d/m/Y"); ?>" size="10" width="90" onchange="calcularDiasLaborales(); calcularDomingos();"/>					</td>
				</tr>
					<tr>	
					<td width="85"><div align="right">D&iacute;as Laborales </div></td>
						<td width="122">
						<input name="txt_diasLaborales" id="txt_diasLaborales" type="text"  readonly="readonly" 
						size="10" width="90" />					</td>
						<td width="85"><div align="right">Domingos</div></td>
						<td width="122">
						<input name="txt_domingos" id="txt_domingos" type="text"  readonly="readonly" 
						size="10" width="90" />					</td>
				</tr>
			</table>
			<div align="center">
				<p>&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='inicio_produccion.php'" />
			  </p>
			</div>
			</form>
			</fieldset>	
			<div id="calendar-dos">
				<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_Prueba.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
				width="25" height="25" border="0" align="absbottom" />
</div>
			<div id="calendar-uno">
				<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_Prueba.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
				width="25" height="25" border="0" />
			</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo para realizar las operaciones correspondientes
		include ("op_reporteMttoEquipoLab.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>

    <style type="text/css">
		<!--
			#titulo-consultar {position:absolute;left:30px;top:146px;	width:440px;height:20px;z-index:11;}
			#tabla-reporteEquipoFecha {position:absolute;left:517px;top:194px;width:425px;height:176px;z-index:12;}
			#tabla-reporteEquipoNombre{position:absolute;left:32px;top:194px;width:425px;height:177px;z-index:13;}
			#tabla-NombreEquipos { position:absolute; left:33px; top:410px; width:906px; height:170px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:18; }
			#calendar-uno {position:absolute; left:732px; top:237px; width:30px; height:26px; z-index:14; }
			#calendar-dos {position:absolute; left:934px; top:236px; width:30px; height:26px; z-index:15; }
			#calendar-tres {position:absolute; left:243px; top:237px; width:30px; height:26px; z-index:16; }
			#calendar-cuatro {position:absolute; left:448px; top:237px; width:30px; height:26px; z-index:17; }
			#detalleMttoEquipoLab {	position:absolute; left:29px; top:195px; width:940px; height:351px; overflow:scroll; z-index:19;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Reporte de Mantenimiento de Equipo de Laboratorio</div>
    <?php  
	
	
	if(!isset($_POST['verDetalle'])){?>
		<fieldset class="borde_seccion" id="tabla-reporteEquipoFecha">
		<legend class="titulo_etiqueta">Reporte de Mantenimiento por Fecha</legend>	
		<br>			
		<form onSubmit="return valFormReporteEquipoLabFecha(this);" name="frm_reporteEquipoLabFecha" method="post" action="frm_reporteMttoEquipoLab.php">
		<table width="100%" height="108"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Fecha Inicio</div></td>
				<td>
					<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10" />					
				</td>
				<td><div align="right">Fecha Fin </div></td>
				<td>
					<input name="txt_fechaFin" id="txt_fechaFin" type="text" readonly="readonly" value="<?php echo date("d/m/Y"); ?>" size="10" />			
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>								
			</tr>
			<tr>
				<td colspan="4">
					<div align="center"> 
						<input name="sbt_generarRepFecha" type="submit" class="botones_largos" id= "sbt_generarRepFecha" value="Generar Reporte" title="Consultar Agregados"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
						onmouseover="window.status='';return true" onclick="location.href='menu_reportes.php'" />
					</div>
			 	</td>
			</tr>
		</table>	
		</form>
		</fieldset>
		

		<fieldset class="borde_seccion" id="tabla-reporteEquipoNombre">
		<legend class="titulo_etiqueta">Reporte de Mantenimiento por Nombre de Equipo</legend>	
		<br>
		<form onSubmit="return valFormReporteEquipoLabNombre(this);" name="frm_reporteEquipoLabNombre" method="post" action="frm_reporteMttoEquipoLab.php">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
			  	<td width="26%"><div align="right">Fecha Inicio</div></td>
				<td width="25%">
			  		<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
					value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10" />
				</td>
			  <td width="23%"><div align="right">Fecha Fin </div></td>
				<td width="26%">
			  		<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
					value="<?php echo date("d/m/Y"); ?>" size="10" />			
				</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre del Equipo</div></td>
				<td colspan="3"><?php									
					//Obtener los nombres de los equipos del Laboratorio y colocar el mismo dato en la Propiedad "value" del tag <option>
					$resultado = cargarComboConId("cmb_nombreEquipoLab","nombre","nombre","equipo_lab","bd_Laboratorio","Equipo Laboratorio","","");					
					if($resultado==0){?>
						<label class='msje_correcto'>No hay Equipos de Laboratorio Registrados</label>
						<input type='hidden' name='cmb_nombreEquipoLab' id='cmb_nombreEquipoLab' value=""/><?php 
					}?>				
				</td>				
			</tr>
			<tr>
	  			<td colspan="4">
					<div align="center">
					<input name="sbt_generarRepNombre" type="submit" class="botones_largos" id="sbt_generarRepNombre"  value="Generar Reporte" 
					title="Generar Reporte de Laboratorio por Nombre del Equipo" onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onmouseover="window.status='';return true" onclick="location.href='menu_reportes.php'" />
					</div>
				</td>
			</tr>	
		</table>	  		
		</form>
		</fieldset>
		
		
		<div id="calendar-uno">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteEquipoLabFecha.txt_fechaIni,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" />
</div>
		<div id="calendar-dos">
			<input name="fechaFin" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteEquipoLabFecha.txt_fechaFin,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" />
</div>
    
		<div id="calendar-tres">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteEquipoLabNombre.txt_fechaIni,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" />
</div>


		<div id="calendar-cuatro">
			<input name="fechaFin" type="image" id="fechaFin" onclick="displayCalendar(document.frm_reporteEquipoLabNombre.txt_fechaFin,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" />
</div><?php 
		
		
		//Verificamos que el boton sbt_generarRepNombre sea presionado; hará que muestre los equipos que han tenido un servicio
		if(isset($_POST['sbt_generarRepNombre']) || isset($_POST['sbt_generarRepFecha'])){?>
			<div id="tabla-NombreEquipos" class="borde_seccion2" align="center"><?php
				//Si $band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de consultar
				consultarServicioMttoLab();?>
			</div><?php 
		}//Cierre if(isset($_POST['sbt_generarRepNombre']) || isset($_POST['sbt_generarRepFecha']))   
	}//Cierre if(!isset($_POST['verDetalle']))	
	else if(isset($_POST['verDetalle']) && $_POST['verDetalle']=="si"){?>		
		<div id="detalleMttoEquipoLab" class='borde_seccion2' align="center"><?php 
			detalleServicioMttoNomEquipo();?>
		</div><?php 
	} ?>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
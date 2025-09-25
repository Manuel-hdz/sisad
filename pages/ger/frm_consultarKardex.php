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
		//Archivo que incluye la opcion de mostrar a los trabajadores, con la opcion a poder guardarlos
		include ("op_consultarKardex.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:173px; height:25px; z-index:11; }
		#consultar-asistencias {position:absolute; left:30px; top:190px; width:620px; height:150px; z-index:15;}
		#consultar-asistencias2 {position:absolute; left:30px; top:420px; width:620px; height:105px; z-index:12;}
		#res-spider {position:absolute;z-index:15;}
		#res-spiderK {position:absolute;z-index:15;}
		#botones{position:absolute;left:30px;top:670px;width:950px;height:37px;z-index:16;}
		#tabla-titulo{position:absolute; left:30px; top:190px; width:950px; height:75px; z-index:17; padding:15px; padding-top:0px; z-index:14;}
		#tabla-resultados1{position:absolute; left:30px; top:190px; width:950px; height:450px; z-index:17; padding:15px; padding-top:0px; overflow:scroll;}
		#tabla-resultados2{position:absolute; left:30px; top:300px; width:950px; height:330px; z-index:17; padding:15px; padding-top:0px; overflow:scroll;}
		#calendar-ini {position:absolute; left:205px; top:233px; width:30px; height:26px; z-index:18; }
		#calendar-fin {position:absolute; left:453px; top:233px; width:30px; height:26px; z-index:19; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Consulta de Asistencias</div><?php	

	//Si no esta definido ningun boton, mostrar el formulario de Base
	if (!isset($_POST["sbt_consultarAsistencias"]) && !isset($_POST["sbt_consultarIndividual"])){?>
	<fieldset class="borde_seccion" id="consultar-asistencias">
		<legend class="titulo_etiqueta">Consultar por Empleado y Fecha</legend>	
		<br>		
		<form name="frm_consultarKardex1" method="post" action="frm_consultarKardex.php" onsubmit="">
			<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="86"><div align="right">Fecha Inicio</div></td>
					<td width="269" >
						<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
						value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10"  width="90"/>
					</td>
					<td width="86"><div align="right">Fecha Fin</div></td>
					<td>
						<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly"
						value="<?php echo date("d/m/Y"); ?>" size="10" width="90" />
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div align="right">Filtrar por Trabajador <input type="checkbox" name="ckb_filtroTrab" id="ckb_filtroTrab" onclick="verificarFiltroEmpleado(this,txt_nombreK);"/></div>
					</td>
					<td colspan="2">
						<input type="text" name="txt_nombreK" id="txt_nombreK" class="caja_de_texto" onkeyup="lookup(this,'empleados','2');" 
						value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" readonly="readonly"/>
						<div id="res-spiderK">
							<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
							</div>
						</div>
					</td>
					<input type="hidden" name="hdn_rfc" id="hdn_rfc"/>
				</tr>
				<tr>
					<td align="center" colspan="4">
						<input name="sbt_consultarAsistencias" type="submit" class="botones" id="sbt_consultarAsistencias" 
						title="Consultar Informaci&oacute;n"  onmouseover="window.status='';return true" value="Consultar" />&nbsp;&nbsp;&nbsp;
						<input type="reset" class="botones" value="Restablecer" title="Borra los criterios de b&uacute;squeda y reestablece el formulario" onclick="txt_nombreK.readOnly=true;"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Reportes"
						onclick="location.href='menu_reportes.php'" />
					</td>
                </tr>
            </table>    
		</form>    			 		
	</fieldset>
	
	<div id="calendar-ini">
				<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_consultarKardex1.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
				width="25" height="25" border="0" align="absbottom" />
	</div>
	<div id="calendar-fin">
		<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_consultarKardex1.txt_fechaFin,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		width="25" height="25" border="0" />
	</div>
	
	<!--<fieldset id="consultar-asistencias2" class="borde_seccion">
		<legend class="titulo_etiqueta">Consultar Asistencias Individual por Año</legend>	
		<br>
		<form name="frm_consultarKardex2" method="post" action="frm_consultarKardex.php" onsubmit="">
			<table align="center" border="0" width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">Ejercicio</div></td>
					<td>
						<select name="cmb_ejercicio" id="cmb_ejercicio">
							<option selected="selected" value="">Ejercicio</option>
							<option value="2012">2012</option>
							<option value="2013">2013</option>
							<option value="2014">2014</option>
							<option value="2015">2015</option>
							<option value="2016">2016</option>
						</select>
					</td>
					<td><div align="right">Trabajador</div></td>
					<td>
						<input type="text" name="txt_nombre" id="txt_nombre" class="caja_de_texto" onkeyup="lookup(this,'empleados','1');" 
						value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4" align="center">
						<input name="sbt_consultarIndividual" type="submit" class="botones" id="sbt_consultarIndividual" 
						title="Consultar Informaci&oacute;n del Empleado Seleccionado"  onmouseover="window.status='';return true" value="Consultar" />&nbsp;&nbsp;&nbsp;
						<input type="reset" class="botones" value="Restablecer" title="Borra los criterios de b&uacute;squeda y reestablece el formulario"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Kardex"
						onclick="location.href='menu_reportes.php'" />
					</td>
				</tr>
			</table>
   		</form>	   
	</fieldset>-->
	<?php
	}
	else{
		if (isset($_POST["sbt_consultarAsistencias"])){
			//Obtener el Criterio para mostrar el Reporte
			$criterio=$_POST["txt_nombreK"];
			echo "<div id='tabla-resultados1' class='borde_seccion2'>";
			echo "<form name='frm_botones' method='post' action='guardar_reporte.php'>";
			$dias=kardexAsistencias();
			echo "</div>";
			echo "<div id='botones' align='center'>";
			/*$deshabilitar=" disabled='disabled'";
			$titulo=" title='S&oacute;lo se Pueden Exportar a PDF Rangos de 7 o de 15 d&iacute;as'";
			if($dias==7 || $dias==15){
				$deshabilitar="";
				$titulo=" title='Exportar a PDF el Registro del Kardex'";
			}*/
			?>
				<input type="hidden" name="hdn_fechaI" id="hdn_fechaI" value="<?php echo $_POST["txt_fechaIni"]; ?>"/>
				<input type="hidden" name="hdn_fechaF" id="hdn_fechaF" value="<?php echo $_POST["txt_fechaFin"]; ?>"/>
				<input type="hidden" name="hdn_dias" id="hdn_dias" value="<?php echo $dias[0]; ?>"/>
				<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="<?php echo $dias[1]; ?>"/>
				<input type="hidden" name="hdn_tipoReporte" id="hdn_tipoReporte" value="reporteKardexAsistencia"/>
				<input name="sbt_exportarExcel" type="submit" value="Exportar a Excel" class="botones"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<!--<input name="sbt_exportar" type="button" value="Exportar a PDF" class="botones"
				onclick="window.open('../../includes/generadorPDF/kardexDetallado.php?fechaI=<?php echo $_POST["txt_fechaIni"]?>&fechaF=<?php echo $_POST["txt_fechaFin"]?>&tipo=<?php echo $tipo?>&criterio=<?php echo $criterio?>&dias=<?php echo $dias?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=no, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"<?php echo $titulo.$deshabilitar;?>/>
				&nbsp;&nbsp;&nbsp;&nbsp;-->
				<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar a Consultar con Otros Datos"
				onclick="location.href='frm_consultarKardex.php'" />
			<?php
			echo "</div>";
			echo "</form>";
		}
		/*if (isset($_POST["sbt_consultarIndividual"])){
			echo "<div id='tabla-titulo' class='borde_seccion' align='center'>";
			mostrarEncabezado();
			echo "</div>";
			echo "<div id='tabla-resultados2' class='borde_seccion2'>";
			kardexIndividual();
			echo "</div>";
			echo "<div id='botones' align='center'>";
			?>
				<input name="sbt_exportar" type="button" value="Exportar a PDF" class="botones" title="Exportar a PDF el Registro del Kardex"
				onclick="window.open('../../includes/generadorPDF/kardexIndividual.php?anio=<?php echo $_POST["cmb_ejercicio"];?>&nombre=<?php echo $_POST["txt_nombre"];?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=no, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar a Consultar con Otros Datos"
				onclick="location.href='frm_consultarKardex.php'" />
			<?php
			echo "</div>";
		}*/
	}
	?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
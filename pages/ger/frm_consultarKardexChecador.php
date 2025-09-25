<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">
	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que incluye las operaciones para realizar el reporte de Kardex
		include ("op_reporteKardex.php");?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
		<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js"></script>
		<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
		<script type="text/javascript" src="../../includes/maxLength.js"></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
		<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
		<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112"
			media="screen">
		</link>
		<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

		<style type="text/css">
			#titulo-consultar-empleado {
				position: absolute;
				left: 30px;
				top: 146px;
				width: 228px;
				height: 25px;
				z-index: 11;
			}

			#tabla-consultar-empleados {
				position: absolute;
				left: 30px;
				top: 198px;
				width: 436px;
				height: 163px;
				z-index: 14;
			}

			#tabla-empleados {
				position: absolute;
				left: 30px;
				top: 190px;
				width: 945px;
				height: 420px;
				z-index: 21;
				overflow: scroll;
			}

			#botones {
				position: absolute;
				left: 30px;
				top: 670px;
				width: 950px;
				height: 37px;
				z-index: 16;
			}

			#res-spiderK {
				position: absolute;
				z-index: 15;
			}

			#consultar-kardex {
				position: absolute;
				left: 30px;
				top: 190px;
				width: 620px;
				height: 190px;
				z-index: 15;
			}

			#calendar-ini {
				position: absolute;
				left: 495px;
				top: 228px;
				width: 30px;
				height: 26px;
				z-index: 18;
			}

			#calendar-fin {
				position: absolute;
				left: 495px;
				top: 263px;
				width: 30px;
				height: 26px;
				z-index: 19;
			}
		</style>
	</head>

	<body>
		<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-consultar-empleado">Reporte de Kardex Checador</div>
		<?php 
		//Verificamos si viene definido en el post el boton consultar
		if(isset($_POST["sbt_consultar"])){
			echo"<div align='center' id='tabla-empleados' class='borde_seccion2' width='100%' >";
				//Si viene definido el boton; mostrar el reporte de Kardex
				$consulta = reporteKardexChecador();
			echo "</div>";
			//Obtener el Criterio para mostrar el Reporte
			$criterio=$_POST["txt_nombreK"];
			$tipo="ind";
			if(isset($_POST["cmb_area"])){
				$criterio=$_POST["cmb_area"];
				$tipo="area";
			}
			?>
		<div id="botones" align="center">
			<!--<input name="sbt_exportarExcel" type="button" value="Exportar a Excel" class="botones" 
				onclick="location.href='guardar_reporte.php?kardeDetalleChecador=1&fechaI=<?php echo $_POST["txt_fechaIni"]?>&fechaF=<?php echo $_POST["txt_fechaFin"]?>&tipo=<?php echo $tipo?>&criterio=<?php echo $criterio?>'"/>
				&nbsp;&nbsp;&nbsp;&nbsp; -->
			<input name="sbt_verPDF" type="button" value="Ver PDF" class="botones"
				onclick="window.open('../../includes/generadorPDF/Checador_Detalle.php?fechaI=<?php echo $_POST['txt_fechaIni']?>&fechaF=<?php echo $_POST['txt_fechaFin']?>&txt_cuenta=<?php echo $_POST['txt_cuenta']?>&tipo=<?php echo $tipo?>&criterio=<?php echo $criterio?>','_blank',
				'top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_regresar" type="button" value="Regresar" class="botones"
				title="Regresar a Consultar con Otros Datos"
				onclick="location.href='frm_consultarKardexChecador.php'" />
		</div>
		<?php }
	  else{ ?>
		<fieldset class="borde_seccion" id="consultar-kardex">
			<legend class="titulo_etiqueta">Consultar por &Aacute;rea y Fecha</legend>
			<br>
			<form name="frm_consultarKardex1" method="post" action="frm_consultarKardexChecador.php"
				onsubmit="return valFormSeleccionarKardex1(this);">
				<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td width="70">
							<div align="right">&Aacute;rea</div>
							<input type="hidden" id="txt_cuenta" name="txt_cuenta" value="PRODUCCION">
						</td>
						<td width="130"><?php
								$conn = conecta("bd_recursos");
								$sql = "SELECT DISTINCT `id_control_costos` , T2.descripcion
										FROM `empleados` AS T1
										JOIN control_costos AS T2
										USING ( id_control_costos )
										WHERE T1.estado_actual LIKE 'ALTA'
										AND T2.descripcion LIKE 'ZARPEO%'
										ORDER BY T2.descripcion";
								$rs = mysql_query ($sql);
								//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
								if($datos = mysql_fetch_array($rs)){?>
							<select name="cmb_area" id="cmb_area" class="combo_box"><?php
									//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
									echo "<option value=''>Seleccionar</option>";
									do{
										echo "<option value='$datos[id_control_costos]'>$datos[descripcion]</option>";
									}while($datos = mysql_fetch_array($rs));?>
							</select><?php
								}
								else{
									echo "<label class='msje_correcto'> No hay &Aacute;reas Registradas</label>
									<input type='hidden' name='cmb_area' id='cmb_area'/>";
								}
								//Cerrar la conexion con la BD		
								mysql_close($conn);?>
						</td>
						<td width="86">
							<div align="right">Fecha Inicio</div>
						</td>
						<td width="269">
							<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text"
								value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10" width="90" />
						</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td width="86">
							<div align="right">Fecha Fin</div>
						</td>
						<td>
							<input name="txt_fechaFin" id="txt_fechaFin" type="text" readonly="readonly"
								value="<?php echo date("d/m/Y"); ?>" size="10" width="90" />
						</td>
					</tr>
					<!-- <tr>
						<td colspan="2">
							<div align="right">Filtrar por Trabajador <input type="checkbox" name="ckb_filtroTrab" id="ckb_filtroTrab" onclick="verificarFiltroEmpleado(this,txt_nombreK,cmb_area);"/></div>
						</td>
						<td colspan="2">
							<input type="text" name="txt_nombreK" id="txt_nombreK" class="caja_de_texto" onkeyup="lookupKardex(this,'empleados','2');" 
							value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" readonly="readonly"/>
							<div id="res-spiderK">
								<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
									<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
									<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
								</div>
							</div>
						</td>
					</tr> -->
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td align="center" colspan="4">
							<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar"
								title="Consultar Informaci&oacute;n" onmouseover="window.status='';return true"
								value="Consultar" />&nbsp;&nbsp;&nbsp;
							<input type="reset" class="botones" value="Restablecer"
								title="Borra los criterios de b&uacute;squeda y reestablece el formulario"
								onclick="txt_nombreK.readOnly=true;cmb_area.disabled=false" />
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" value="Regresar" class="botones"
								title="Regresar al Men&uacute; de Kardex" onclick="location.href='menu_reportes.php'" />
						</td>
					</tr>
				</table>
			</form>
		</fieldset>

		<div id="calendar-ini">
			<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_consultarKardex1.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
		</div>
		<div id="calendar-fin">
			<input name="fechaIni" type="image" id="fechaIni"
				onclick="displayCalendar(document.frm_consultarKardex1.txt_fechaFin,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25"
				height="25" border="0" />
		</div>
		<?php }?>
	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
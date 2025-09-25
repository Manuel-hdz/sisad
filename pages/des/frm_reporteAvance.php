<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a estos Reportes
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reportesDesarrollo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validaciondesarrollo.js" ></script>
	<script type="text/javascript" src="includes/ajax/borrarHistorial.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboUbicaciones.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-consultar{position:absolute;left:30px;top:146px; width:210px;height:20px;z-index:11;}
			#tabla-fechaReporte{position:absolute;left:30px;top:190px;width:436px;height:162px;z-index:12;}
			#calendario-uno{position:absolute;left:265px;top:235px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:265px;top:271px;width:30px;height:26px;z-index:13;}
			#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; padding:15px; padding-top:0px; overflow:scroll; z-index:15;}
			#tabla-resultadosGrafica{position:absolute; left:30px; top:190px; width:950px; height:450px; z-index:12; padding:15px; padding-top:0px; z-index:15;}
			#btns{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:16;}
			#form-selecPeriodo {position:absolute;left:540px;top:190px;width:356px;height:162px;z-index:14;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Reporte de Avance</div>
	<?php 
	//Si no se ha presionado Boton, mostrar el Formulario
	if (!isset($_POST["sbt_consultar"]) && !isset($_POST["sbt_graficar"])){
		?>
		<fieldset class="borde_seccion" id="tabla-fechaReporte" name="tabla-fechaReporte">
		<legend class="titulo_etiqueta">Reporte por Fechas</legend>	
		<br>
		<form name="frm_consultarReporte" method="post" action="frm_reporteAvance.php"  onsubmit="return valFormConsultarReporte(this);">
			<table width="444"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="104"><div align="right">Fecha Inicio</div></td>
					<td width="276">
						<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" 
						value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" readonly="readonly"/>
					</td>
				</tr>
					<td width="104"><div align="right">Fecha Fin </div></td>
					<td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
				</tr>
				<tr>
					<td colspan="4">
						<div align="center"> 
							<input name="sbt_consultar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Generar Reporte"
							onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="reset" name="btn_limpiar" class="botones" value="Restablecer" title="Restablece el Formulario"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otro Reporte" 
							onmouseover="window.status='';return true" onclick="location.href='menu_reportes.php'" />
						</div>
					</td>
				</tr>
		  </table>
		</form>
		</fieldset>
	   <div id="calendario-uno">
			<input name="calendario_uno" type="image" id="calendario_uno" onclick="displayCalendar (document.frm_consultarReporte.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
			title="Seleccione Fecha de Inicio" />
		</div>
		<div id="calendario-dos">
			<input name="calendario_dos" type="image" id="calendario_dos" onclick="displayCalendar (document.frm_consultarReporte.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
		</div>
		
		<script type="text/javascript" language="javascript">
			setTimeout("cargarUbicaciones(document.getElementById('cmb_periodo').value,'cmb_cliente')",500);
		</script>
		<fieldset class="borde_seccion" id="form-selecPeriodo" name="form-selecPeriodo">
		<legend class="titulo_etiqueta">Reporte por Per&iacute;odo y Cliente</legend>	
		<br>
		<form name="frm_reportePptoAvance" method="post" action="frm_reporteAvance.php" onsubmit="return valFormRptAvanceGrafico(this);">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="40%" align="right">Periodo</td>
				<td width="60%"><?php 
					$conn = conecta("bd_desarrollo");
					$stm_sql="SELECT DISTINCT (periodo),fecha_inicio,fecha_fin FROM presupuesto";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){
						$fecha=date("Y-m-d");
						?>
						<select name="cmb_periodo" id="cmb_periodo" class="combo_box" onchange="cargarUbicaciones(this.value,'cmb_cliente')">
						<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
						echo "<option value=''>Seleccionar</option>";
						do{
							$sel="";
							if ($fecha>=$datos["fecha_inicio"] && $fecha<=$datos["fecha_fin"])
								$sel=" selected='selected'";
							echo "<option value='$datos[periodo]'$sel>$datos[periodo]</option>";
						}while($datos = mysql_fetch_array($rs));?>
						</select><?php
						$res="si";
					}
					else{
						$res="no";
						echo "<label class='msje_correcto'> No hay Periodos Registrados</label>
							<input type='hidden' name='cmb_area' id='cmb_area'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);
					?>
				</td>
			</tr>
			<tr>
				<td align="right">Clientes</td>
				<td >
				<?php if ($res=="si") {?>
				<select name="cmb_cliente" id="cmb_cliente" class="combo_box">
					<option value="">Clientes</option>
				</select>
				<?php }
				else
					echo "<label class='msje_correcto'>NO HAY DATOS</label>";
				?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="hidden" name="hdn_combo" id="hdn_combo" value=""/>
					<input type="submit" name="sbt_graficar" id="sbt_graficar" value="Consultar" class="botones" onmouseover="window.status='';return true" title="Generar Gr&aacute;fico de Avance VS Presupuesto"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otro Reporte" 
					onmouseover="window.status='';return true" onclick="location.href='menu_reportes.php'" />
				</td>
			</tr>		
		</table>
		</form>
		</fieldset>
		
		<?php 
	}
	else{
		if(isset($_POST["sbt_consultar"])){
			?>
			<form name="frm_consultarResultados" method="post" action="guardar_reporte.php"><?php 
				echo"<div id='tabla-resultados' class='borde_seccion2' align='center'>";
					$res=mostrarAvance();
				echo "</div>";
				echo "<div id='btns' align='center'>";
				if ($res!="false"){
					$datos=split("<br>",$res);
					?>
						<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="<?php echo $datos[0];?>"/>
						<input type="hidden" name="hdn_consultaMP" id="hdn_consultaMP" value="<?php echo $datos[1];?>"/>
						<input type="hidden" name="hdn_msje" id="hdn_msje" value="<?php echo $datos[2];?>"/>
						<input type="hidden" name="hdn_tipoReporte" id="hdn_tipoReporte" value="reporteAvance"/>
						<input name="sbt_excel" type="submit" class="botones" id= "sbt_excel" value="Exportar a Excel" title="Exportar a Excel"
						onMouseOver="window.status='';return true"/>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php 
				}?>
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar otras Fechas" 
					onMouseOver="window.status='';return true" onclick="location.href='frm_reporteAvance.php'" />
				</div>
			</form>
			<?php 
		}
		if(isset($_POST["sbt_graficar"])){
			$grafica=generarGrafico();
			echo "<div id='tabla-resultadosGrafica' align='center'><img src='$grafica' width='100%' height='100%'></div>";
			echo "<div id='btns' align='center'>";
			?>
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar otros Datos" 
				onMouseOver="window.status='';return true" onclick="borrarHistorial();location.href='frm_reporteAvance.php'"/>
			<?php
			echo "</div>";
		}
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
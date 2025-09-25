<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para realizar los Reportes REA
		include ("op_reporteSalidas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("#tabla-resultados").dataTable({
			"sPaginationType": "scrolling"
		});
	});
	</script>

    <style type="text/css">
		<!--
		#titulo-salida { position:absolute; left:15px; top:146px; width:141px; height:19px; z-index:11; }
		#form-datos-salida {position:absolute;	left:30px; top:190px; width:400px; height:207px; z-index:13; }
		#form-datos-salida2 {position:absolute;	left:30px; top:190px; width:400px; height:300px; z-index:13; }
		#registro-material { position:absolute; left:586px; top:192px; width:545px; height:206px; z-index:14; }
		#titulo-reporteREA { position:absolute; left:30px; top:146px; width:236px; height:19px; z-index:11; }
		#tabla-reporteSalidas {	position:absolute;	left:30px; top:190px; width:940px; height:410px; z-index:12; overflow:scroll; }
		#calendario_repInicio { position:absolute; left:275px; top:233px; width:29px; height:24px; z-index:14; }
		#calendario_repCierre { position:absolute; left:275px; top:270px; width:30px; height:26px; z-index:15; }
		#calendario_repInicio2 { position:absolute; left:280px; top:233px; width:29px; height:24px; z-index:14; }
		#calendario_repCierre2 { position:absolute; left:280px; top:270px; width:30px; height:26px; z-index:15; }
		#botones{position:absolute;left:30px;top:650px;width:999px;height:37px;z-index:13;}
		#btns-regpdf { position: absolute; left:30px; top:650px; width:945px; height:40px; z-index:23; }
		-->
    </style>
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-reporteREA">Reporte de Salidas de Material </div><?php 
	
	
	//Si la variables $theDate y $detalles no esta definidas en el arreglo $_POST, entonces desplegar el formulario para solictar las fechas
	if(!isset($_POST['txt_fechaInicio']) && !isset($_POST['fecha_ini'])){?>	
		<!-- <fieldset id="form-datos-salida" class="borde_seccion">	
		<legend class="titulo_etiqueta">Reporte por Fechas y Salidas</legend>	
		<br>
		<form name="frm_datosReporteSalidas" action="frm_reporteSalidas.php" method="post" onsubmit="return valFormFechas(this);">
		<table border="0" align="center" cellpadding="5" width="100%" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="120"><div align="right">Fecha de Inicio</div></td>
				<td width="120">
					<input name="txt_fechaInicio" type="text" value=<?php echo date("d/m/Y",strtotime("-30 day")); ?> size="10" maxlength="15" readonly=true width="50">	  
				</td>
				<td width="120">&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">Fecha de Cierre</div></td>
				<td>
					<input name="txt_fechaCierre" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="50">
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">Ordenar Por:</div></td>
				<td>
					<select class="combo_box" name="cmb_orden" id="cmb_orden">
						<option value="" selected="selected">Orden</option>
						<option value="depto_solicitante">DEPARTAMENTO</option>
						<option value="solicitante">SOLICITANTE</option>
						<option value="turno">TURNO</option>
						<option value="destino">DESTINO</option>
					</select>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				  <div align="center">
					<input name="sbt_registrar" type="submit" class="botones" value="Ver Reporte" onMouseOver="window.status='';return true" 
					title="Ver Salidas Registradas de Material"  />
				  </div>
				</td>
				<td>			  
					<div align="center">
					<input name="rst_limpiar" type="reset" class="botones" value="Restablecer" onMouseOver="window.status='';return true" 
					title="Restablecer las Fechas Seleccionadas" />
					</div>
				</td>
				<td>
					<div align="center">
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Reportes" 
					onClick="location.href='menu_reportes.php'" />
					</div>
				</td>
			</tr>
		</table>    
		</form>    			 	
		</fieldset>
			
		<div id="calendario_repInicio">
			<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_datosReporteSalidas.txt_fechaInicio,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		
		<div id="calendario_repCierre">
			<input name="calendario_cieRep" type="image" id="calendario_cieRep" onclick="displayCalendar(document.frm_datosReporteSalidas.txt_fechaCierre,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div> -->
	
		<fieldset id="form-datos-salida2" class="borde_seccion">	
		<legend class="titulo_etiqueta">Reporte por Fechas</legend>	
		<br>
		<form name="frm_datosReporteSalidas2" action="frm_reporteSalidas.php" method="post" onsubmit="return valFormFechas(this);">
		<table border="0" align="center" cellpadding="5" width="100%" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="120"><div align="right">Fecha de Inicio</div></td>
				<td width="120">
					<input name="txt_fechaInicio" type="text" value=<?php echo date("d/m/Y",strtotime("-7 day")); ?> size="10" maxlength="15" readonly=true width="50">	  
				</td>
				<td width="120">&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">Fecha de Cierre</div></td>
				<td>
					<input name="txt_fechaCierre" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="50">
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">Ordenar Por:</div></td>
				<td>
					<select class="combo_box" name="cmb_orden" id="cmb_orden">
						<option value="" selected="selected">Orden</option>
						<option value="depto_solicitante">DEPARTAMENTO</option>
						<option value="solicitante">SOLICITANTE</option>
						<option value="turno">TURNO</option>
						<option value="destino">DESTINO</option>
					</select>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3"><div align="center">Filtrar Por</div></td>
			</tr>
			<tr>
				<td><div align="right">Centro de Costos:</div></td>
				<td>
					<?php
					$conn = conecta("bd_recursos");
					$rs = mysql_query("SELECT * FROM control_costos WHERE descripcion LIKE '%ZARPEO%' ORDER BY descripcion");
					if($rs){
						$row=mysql_fetch_array($rs)
					?>
						<select name="cmb_cc" id="cmb_cc" size="1" class="combo_box">
							<option value="">Centro de Costos</option><?php 
							do{
								echo "<option value='$row[id_control_costos]'>$row[descripcion]</option>";
							}while($row=mysql_fetch_array($rs));?>
						</select>
					<?php
					} else { 
					?>
					<label class="msje_correcto"><u><strong>NO</strong></u> Hay Centros de Costo Registrados</label>
					<?php
					}
					?>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">Cuentas:</div></td>
				<td>
					<?php
					$conn = conecta("bd_recursos");
					$rs = mysql_query( "SELECT DISTINCT T1. * 
										FROM cuentas AS T1
										JOIN rel_costos_cuentas_subcuentas AS T2
										USING ( id_cuentas ) 
										JOIN control_costos AS T3
										USING ( id_control_costos ) 
										WHERE T3.descripcion LIKE  '%ZARPEO%'
										ORDER BY T1.descripcion");
					if($rs){
						$row=mysql_fetch_array($rs)
					?>
						<select name="cmb_cuenta" id="cmb_cuenta" size="1" class="combo_box">
							<option value="">Cuentas</option><?php 
							do{
								echo "<option value='$row[id_cuentas]'>$row[descripcion]</option>";
							}while($row=mysql_fetch_array($rs));?>
						</select>
					<?php
					} else { 
					?>
					<label class="msje_correcto"><u><strong>NO</strong></u> Hay Cuentas Registrados</label>
					<?php
					}
					?>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">Subcuentas:</div></td>
				<td>
					<?php
					$conn = conecta("bd_recursos");
					$rs = mysql_query( "SELECT DISTINCT T1. * 
										FROM subcuentas AS T1
										JOIN rel_costos_cuentas_subcuentas AS T2
										USING ( id_subcuentas ) 
										JOIN control_costos AS T3
										USING ( id_control_costos ) 
										WHERE T3.descripcion LIKE  '%ZARPEO%'
										ORDER BY T1.descripcion");
					if($rs){
						$row=mysql_fetch_array($rs)
					?>
						<select name="cmb_subcuenta" id="cmb_subcuenta" size="1" class="combo_box">
							<option value="">Subcuentas</option><?php 
							do{
								echo "<option value='$row[id_subcuentas]'>$row[descripcion]</option>";
							}while($row=mysql_fetch_array($rs));?>
						</select>
					<?php
					} else { 
					?>
					<label class="msje_correcto"><u><strong>NO</strong></u> Hay Subcuentas Registrados</label>
					<?php
					}
					?>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>
				  <div align="center">
					<input name="sbt_verReporte" type="submit" class="botones" value="Ver Reporte" onMouseOver="window.status='';return true" 
					title="Ver Salidas Registradas de Material"  />
				  </div>
				</td>
				<td>			  
					<div align="center">
					<input name="rst_limpiar" type="reset" class="botones" value="Restablecer" onMouseOver="window.status='';return true" 
					title="Restablecer las Fechas Seleccionadas" />
					</div>
				</td>
				<td>
					<div align="center">
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Reportes" 
					onClick="location.href='menu_reportes.php'" />
					</div>
				</td>
			</tr>
		</table>    
		</form>    			 	
		</fieldset>
		
		<div id="calendario_repInicio2">
			<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_datosReporteSalidas2.txt_fechaInicio,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		
		<div id="calendario_repCierre2">
			<input name="calendario_cieRep" type="image" id="calendario_cieRep" onclick="displayCalendar(document.frm_datosReporteSalidas2.txt_fechaCierre,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
	
	<?php
	
	}//Cierre if(!isset($_POST['txt_fechaInicio']) && !isset($_POST['fecha_ini']))
	else{
		?><div id="tabla-reporteSalidas" align="center" class="borde_seccion2"><?php 
		//Reporte por Fecha y Salidas
		if (!isset($_POST["sbt_verReporte"])){
			//Mostrar la informacion general de las entradas registradas
			if(isset($_POST['txt_fechaInicio']))
				mostrarSalidas($txt_fechaInicio,$txt_fechaCierre,$cmb_orden);
			
			if(isset($_POST['fecha_ini']) && isset($_POST['fecha_end'])){			
				//Obtener el valor de la clave de la Entrada seleccionada
				$clave = "";
				$tam = count($_POST);
				$cont = 1;
				foreach($_POST as $nombre_campo => $valor){								
					if($cont==$tam)
						$clave = $valor;				
					$cont++;
				}
				mostrarDetalleSM($clave,$fecha_ini,$fecha_end,$ordenar_por);
			}
		}
		else{
			$res=mostrarSalidas2($txt_fechaInicio,$txt_fechaCierre,$cmb_orden);
			echo "</div>";
			?>
			<div id="btns-regpdf" align="center">
			<table width="50%" cellpadding="12">
				<tr><td colspan="2">
					<form method="post" action="guardar_reporte.php">
					<?php
						if ($res==1){
					?>
							<input type="hidden" name="hdn_fechaI" value="<?php echo $txt_fechaInicio;?>"/>
							<input type="hidden" name="hdn_fechaF" value="<?php echo $txt_fechaCierre;?>"/>
							<input type="hidden" name="hdn_orden" value="<?php echo $cmb_orden;?>"/>
							<input type="hidden" name="hdn_cc" value="<?php echo $cmb_cc;?>"/>
							<input type="hidden" name="hdn_cuenta" value="<?php echo $cmb_cuenta;?>"/>
							<input type="hidden" name="hdn_subcuenta" value="<?php echo $cmb_subcuenta;?>"/>
							<input type="hidden" name="hdn_consulta"/>
							<input type="hidden" name="hdn_tipoReporte" value="salidasDetalle"/>
							<input type="submit" value="Exportar a Excel" name="sbt_excel" id="sbt_excel" class="botones" title="Exportar el Reporte a Excel" onmouseover="window.status='';return true;"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
						}
					?>
					<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Seleccionar Otro Rango de Fechas" 
					onclick="location.href='frm_reporteSalidas.php'" />
					</form>
				</td></tr>
			</table>
			</div>
			<?php
		}
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
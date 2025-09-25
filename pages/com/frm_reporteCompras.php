<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para Generar el Reporte de Compras de Acuerdo a los Parametros Seleccionados
		include ("op_reporteCompras.php");
?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
		<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112"
			media="screen">
		</link>
		<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
		<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
		<script type="text/javascript" src="../../includes/validacionCompras.js"></script>
		<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
		<?php //Funciones para Paginacion ?>
		<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
		<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
		<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>

		<script type="text/javascript">
			$(document).ready(function () {
				$("#tabla-detallePedido").dataTable({
					"sPaginationType": "scrolling"
				});
			});
		</script>

		<style type="text/css">
			#rpt-proveedor {
				position: absolute;
				left: 30px;
				top: 190px;
				width: 430px;
				height: 250px;
				z-index: 12;
			}

			#titulo-reporte {
				position: absolute;
				left: 30px;
				top: 146px;
				width: 136px;
				height: 20px;
				z-index: 11;
			}

			#rpt-fecha {
				position: absolute;
				left: 530px;
				top: 190px;
				width: 430px;
				height: 125px;
				z-index: 13;
			}

			#rpt-costo {
				position: absolute;
				left: 30px;
				top: 480px;
				width: 430px;
				height: 170px;
				z-index: 14;
			}

			#rpt-depto {
				position: absolute;
				left: 530px;
				top: 345px;
				width: 430px;
				height: 135px;
				z-index: 15;
			}

			#rpt-equipo {
				position: absolute;
				left: 530px;
				top: 513px;
				width: 430px;
				height: 135px;
				z-index: 16;
			}

			#calendar-uno {
				position: absolute;
				left: 330px;
				top: 332px;
				width: 30px;
				height: 26px;
				z-index: 17;
			}

			#calendar-dos {
				position: absolute;
				left: 330px;
				top: 368px;
				width: 30px;
				height: 26px;
				z-index: 18;
			}

			#sugerencias {
				position: absolute;
				left: 80px;
				top: 290px;
				width: 388px;
				height: 40px;
				z-index: 19;
			}

			#calendar-tres {
				position: absolute;
				left: 850px;
				top: 217px;
				width: 30px;
				height: 26px;
				z-index: 20;
			}

			#calendar-cuatro {
				position: absolute;
				left: 850px;
				top: 255px;
				width: 30px;
				height: 26px;
				z-index: 21;
			}

			#reporte {
				position: absolute;
				left: 30px;
				top: 190px;
				width: 921px;
				height: 430px;
				z-index: 22;
				overflow: scroll;
			}

			#btn-cancelar {
				position: absolute;
				left: 450px;
				top: 680px;
				width: 97px;
				height: 37px;
				z-index: 23;
			}

			#btns-regpdf {
				position: absolute;
				left: 319px;
				top: 670px;
				width: 400px;
				height: 40px;
				z-index: 24;
			}
		</style>
		<?php //Estilo para la Tabla Paginada?>
		<style type="text/css" title="currentStyle">
			@import "../../includes/jquery/dataTable/css/tabla.css";
		</style>
	</head>

	<body>

		<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-reporte">Reporte Compras </div>

		<?php
	//Verificar si estan definidos los datos del reporte de compras en la SESSION, de estarlo procedemos a pasarlos al arreglo POST
	if(isset($_POST['hdn_tipoRpt'])){
		switch($_POST['hdn_tipoRpt']){
			case 1:
				$_POST['txt_razon'] = $_SESSION['datosRptCompras']['txt_razon'];
				$_POST['txt_fechaIni'] = $_SESSION['datosRptCompras']['txt_fechaIni'];
				$_POST['txt_fechaFin'] = $_SESSION['datosRptCompras']['txt_fechaFin'];
				unset($_SESSION['datosRptCompras']);//Quitar los datos de la SESSION
			break;
			case 2:
				$_POST['txt_nivelInf'] = $_SESSION['datosRptCompras']['txt_nivelInf'];
				$_POST['txt_nivelSup'] = $_SESSION['datosRptCompras']['txt_nivelSup'];
				unset($_SESSION['datosRptCompras']);//Quitar los datos de la SESSION
			break;
			case 3:
				$_POST['txt_fechaIni'] = $_SESSION['datosRptCompras']['txt_fechaIni'];
				$_POST['txt_fechaFin'] = $_SESSION['datosRptCompras']['txt_fechaFin'];
				unset($_SESSION['datosRptCompras']);//Quitar los datos de la SESSION
			break;
			case 4:
				$_POST['cmb_departamento'] = $_SESSION['datosRptCompras']['cmb_departamento'];
				unset($_SESSION['datosRptCompras']);//Quitar los datos de la SESSION
			break;
			case 5:
				$_POST['cmb_equipos'] = $_SESSION['datosRptCompras']['cmb_equipo'];
				unset($_SESSION['datosRptCompras']);//Quitar los datos de la SESSION
			break;
			case 6:
				$_POST['cmb_familia'] = $_SESSION['datosRptCompras']['cmb_familia'];
				$_POST['ckb_todo'] = $_SESSION['datosRptCompras']['ckb_todo'];
				unset($_SESSION['datosRptCompras']);//Quitar los datos de la SESSION
			break;
		}
	}
	
	$band = 0;
	//Mostrar el Detalle de un Pedido
	if(isset($_POST['verDetalle'])){					
		$band = 1;		
		//Obtener el valor de la clave de la Entrada seleccionada
		$clave = $_POST['RC'];
		//Mostrar el detalle del Pedido Seleccionado
		mostrarDetalleRC($clave,$no_reporte);
	}
	
	//Identificar que tipo de Reporte se va a elaborar	
	if(isset($_POST['txt_razon']) || isset($_POST['txt_fechaIni']) || isset($_POST['txt_nivelInf']) || isset($_POST['cmb_departamento']) || isset($_POST['cmb_familia']) || isset($_POST['cmb_equipos'])){	
		//Quitar los datos de la grafica de la SESSION, antes de entrar a generar el nuevo reporte, en el caso de que exista uno previo
		unset($_SESSION['datosGrapCompras']);
		
		if(isset($_POST['txt_razon']) && isset($_POST['txt_fechaIni']) && isset($_POST['txt_fechaFin'])){
			$band = 1;		
			generarReporte(1);		
		}
	
		if(isset($_POST['txt_nivelInf'])){
			$band = 1;
			generarReporte(2);		
		}
		
		if(!isset($_POST['txt_razon']) && isset($_POST['txt_fechaIni']) && isset($_POST['txt_fechaFin'])){
			$band = 1;		
			generarReporte(3);
		}
	
		if(isset($_POST['cmb_departamento'])){
			$band = 1;		
			generarReporte(4);
		}
		
		if(isset($_POST['cmb_equipos']) && !isset($_POST['ckb_todo'])){
			$band = 1;		
			generarReporte(5);
		}
		
		if(isset($_POST['cmb_familia']) && isset($_POST['ckb_todo'])){
			$band = 1;		
			generarReporte(6);
		}
	}			
	
	    
	if($band==0){ ?>
		<fieldset class="borde_seccion" id="rpt-proveedor" name="rpt-proveedor">
			<legend class="titulo_etiqueta">Reporte por Proveedor</legend>
			<br />
			<form onsubmit="return verFormReportesCompras(this,1);" name="frm_rptProveedor"
				action="frm_reporteCompras.php" method="post">
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" align="center">
					<tr>
						<td colspan="2" align="center">Nombre o Raz&oacute;n Social </td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input name="txt_razon" id="txt_razon" type="text" class="caja_de_texto" size="60"
								maxlength="80" onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');"
								onkeypress="return permite(event,'num_car',0);" value="" />
						</td>
					</tr>
					<tr>
						<td colspan="2" class="titulo_etiqueta">Selecciona la Fecha del Reporte</td>
					</tr>
					<tr>
						<td>
							<div align="right">Fecha Inicio</div>
						</td>
						<td><input name="txt_fechaIni" type="text" class="caja_de_texto"
								value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10" maxlength="15"
								readonly=true width="90" /></td>
					</tr>
					<tr>
						<td>
							<div align="right">Fecha Fin </div>
						</td>
						<td><input name="txt_fechaFin" type="text" class="caja_de_texto"
								value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" readonly=true
								width="90" /></td>
					</tr>
					<tr>
						<td align="center" colspan="2">
							<input name="sbt_generarRpt" type="submit" class="botones" value="Generar Reporte"
								onmouseover="window.status='';return true" title="Generar Reporte Por Proveedor" />
							&nbsp;&nbsp;&nbsp;
							<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar"
								title="Limpiar Formulario" />
						</td>
					</tr>
				</table>
			</form>
		</fieldset>


		<div id="sugerencias">
			<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
				<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
				<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
			</div>
		</div>

		<div id="calendar-uno">
			<input type="image" name="iniRepProv" id="iniRepProv" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_rptProveedor.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
		</div>

		<div id="calendar-dos">
			<input type="image" name="finRepProv" id="finRepProv" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_rptProveedor.txt_fechaFin,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
		</div>

		<fieldset id="rpt-fecha" class="borde_seccion">
			<legend class="titulo_etiqueta">Reporte por Fecha</legend>
			<form onsubmit="return verFormReportesCompras(this,2);" name="frm_rptFecha" action="frm_reporteCompras.php"
				method="post">
				<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td>
							<div align="right">Fecha Inicio</div>
						</td>
						<td>
							<input name="txt_fechaIni" type="text"
								value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" size="10" maxlength="15"
								readonly=true width="90" />
						</td>
					</tr>
					<tr>
						<td>
							<div align="right">Fecha Fin</div>
						</td>
						<td><input name="txt_fechaFin" type="text" value="<?php echo date("d/m/Y"); ?>" size="10"
								maxlength="15" readonly=true width="90" /></td>
					</tr>
					<tr>
						<td align="center" colspan="2"><input name="sbt_generarRpt" type="submit" class="botones"
								value="Generar Reporte" onmouseover="window.status='';return true"
								title="Generar Reporte Por Fecha" /></td>
					</tr>
				</table>
			</form>
		</fieldset>

		<div id="calendar-tres">
			<input type="image" name="iniRepFecha" id="iniRepFecha" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_rptFecha.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
		</div>

		<div id="calendar-cuatro">
			<input type="image" name="finRepFecha" id="finRepFecha" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_rptFecha.txt_fechaFin,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
		</div>

		<fieldset class="borde_seccion" id="rpt-costo" name="rpt-costo">
			<legend class="titulo_etiqueta">Reporte por Costo</legend>
			<br />
			<form onsubmit="return verFormReportesCompras(this,3);" name="frm_rptCosto" action="" method="post">
				<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td width="140">
							<div align="right">Cantidad Nivel Inferior</div>
						</td>
						<td width="160">$
							<input name="txt_nivelInf" id="txt_nivelInf" type="text" class="caja_de_texto"
								onchange="formatCurrency(value,'txt_nivelInf');"
								onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" />
						</td>
					</tr>
					<tr>
						<td>
							<div align="right">Cantidad Nivel Superior</div>
						</td>
						<td>$
							<input name="txt_nivelSup" id="txt_nivelSup" type="text" class="caja_de_texto"
								onchange="formatCurrency(value,'txt_nivelSup');"
								onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" />
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input name="sbt_generar2" type="submit" class="botones" value="Generar Reporte"
								onmouseover="window.status='';return true" title="Generar Reporte Por Costo" />
							&nbsp;&nbsp;&nbsp;
							<input name="rst_Limpiar2" type="reset" class="botones" value="Limpiar"
								title="Limpiar Formulario" />
						</td>
					</tr>
				</table>
			</form>
		</fieldset>

		<fieldset class="borde_seccion" id="rpt-depto" name="rpt-depto">
			<legend class="titulo_etiqueta">Reporte por Departamento</legend>
			<br />
			<form onsubmit="return verFormReportesCompras(this,4);" name="frm_rptDepto" action="frm_reporteCompras.php"
				method="post">
				<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td>
							<div align="right">Departamento</div>
						</td>
						<td>
							<?php
					$res=cargarCombo("cmb_departamento","departamento","organigrama","bd_recursos","Departamento","");
					if ($res==""){
						echo "No Hay Departamentos Registrados, Consulte a Recursos Humanos";
					}
				?>
						</td>
					</tr>
					<tr>
						<td align="center" colspan="2"><input name="sbt_generar" type="submit" class="botones"
								value="Generar Reporte" onmouseover="window.status='';return	true"
								title="Generar Reporte Por Departamento" /></td>
					</tr>
				</table>
			</form>
		</fieldset>

		<fieldset class="borde_seccion" id="rpt-equipo" name="rpt-equipo">
			<legend class="titulo_etiqueta">Reporte por Equipo</legend>
			<br />
			<form onsubmit="return verFormReportesCompras(this,5);" name="frm_rptDepto" action="frm_reporteCompras.php"
				method="post">
				<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td>
							<div align="right">Familia</div>
						</td>
						<td>
							<?php
				$res=cargarComboTotal("cmb_familia","familia","familia","equipos","bd_mantenimiento","Familia","","cargarCombo(this.value,'bd_mantenimiento','equipos','id_equipo','familia','cmb_equipos','Equipos','')","Familia","","");
				if ($res==""){
					echo "<label class='msje_correcto'>No Hay Equipos Registrados, Consulte a Mantenimiento</label>";
					echo "<input type='hidden' id='cmb_area' name='cmb_area'/>";
					echo "<input type='hidden' id='cmb_puestos' name='cmb_equipos'/>";
				}
				?>
						</td>
						<td>
							<div align="right">Equipo</div>
						</td>
						<td>
							<?php
			$etiq="";
			if ($res!="") {?>
							<select name="cmb_equipos" id="cmb_equipos" class="combo_box">
								<option value="">Equipos</option>
							</select>
							<input type="checkbox" name="ckb_todo" id="ckb_todo"
								title="Dar Click para ver todos los Equipos de la Familia Seleccionada" value="TODO"
								onclick="reporteEquipos(this);" />Todo
							<?php }
			else{
				echo "<label class='msje_correcto'>No Hay Equipos Registrados, Consulte a Mantenimiento</label>";
				$etiq="disabled='disabled'";
			}
			?>
						</td>
					</tr>
					<tr>

						<td align="center" colspan="4"><input name="sbt_generar" type="submit" class="botones"
								value="Generar Reporte" onmouseover="window.status='';return true"
								title="Generar Reporte Por Equipo" <?php echo $etiq;?> /></td>
					</tr>
				</table>
			</form>
		</fieldset>

		<div id="btn-cancelar">
			<input type="button" name="btn_cancelar" value="Cancelar" class="botones"
				title="Regresar al Men&uacute; de Reportes" onclick="location.href='menu_reportes.php'" />
		</div>
		<?php }//Cierre if($band==0) ?>
	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
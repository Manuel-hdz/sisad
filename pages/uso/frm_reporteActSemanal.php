<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reporteActSemanal.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:187px;height:20px;z-index:11;}
		#tabla-repSemanal{position:absolute;left:30px;top:190px;width:469px;height:187px;z-index:12;}
		#calendario-Ini {position:absolute;left:309px;top:219px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:307px;top:253px;width:30px;height:26px;z-index:14;}
		#tabla-resultados {position:absolute;left:33px;top:191px;width:896px;height:336px;z-index:14;overflow:scroll;}
		#botones{position:absolute;left:50px;top:575px;width:888px;height:37px;z-index:16;}
		-->
    </style>
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Actividades Semanales</div>
	<?php 
	
	
	if(isset($_POST['sbt_consultar'])){?>
		<form name="frm_resultadosRptSemanal" id="frm_resultadosRptSemanal" method="post" action="frm_reporteActSemanal.php" >
		<div id="tabla-resultados" class="borde_seccion2"><?php 
			//Se retorna el valor d ela consulta que se encuentra en el archivo op_reportePlanConitngencia
			$sql_stm = mostrarActividadesSemanales();?>
		</div>
		</form><?php 
	}
	else{?>	 	
		<fieldset class="borde_seccion" id="tabla-repSemanal" name="tabla-repSemanal">
			<legend class="titulo_etiqueta">Consultar Actividades Semanales del Departamento</legend>
			<form onsubmit="return valFormRptActividadesSemanales(this);" name="frm_reporteActSemanal" id="frm_reporteActSemanal" method="post"
			 action="frm_reporteActSemanal.php">
				<table width="429" height="127"  cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td><div align="right">*Fecha Inicio </div></td>
						<td width="219"><input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" value="<?php echo date("d/m/Y", strtotime("-7 day"));?>" 
						readonly="readonly" class="caja_de_texto"/></td>
					</tr>
					<tr>
						<td><div align="right">*Fecha Fin </div></td>
						<td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
						readonly="readonly" class="caja_de_texto"/></td>
					</tr>
					<tr>
						<td height="45" colspan="9"><div align="center">
						<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar" 
						title="Consultar Actividades Realizadas por el Departamento de la Clinica"
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones"  value="Regresar" title="Regresa al Menu de Reportes" 
						onclick="location.href='menu_reportes.php'" onmouseover="window.status='';return true"/>
						</div></td>
					</tr>
				</table>
			</form>
		</fieldset>

		<div id="calendario-Ini">
			<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_reporteActSemanal.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Inicio"/> 
		</div>
		
		<div id="calendario-Fin">
			<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_reporteActSemanal.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Fin"/> 
		</div><?php 
	}//Cierre if(!isset($_POST["sbt_consultar"]))	


	//Verificamos si viene definido en el post el boton consultar
	if(isset($_POST["sbt_consultar"])){
	?>
		<div align="center" id="botones">
			<form action="guardar_reporte.php" method="post" id="frm_exportarDiv">
				<?php if(isset($_POST["sbt_consultar"])){?>
				<input type="hidden" id="hdn_divExpRepActSemanales" name="hdn_divExpRepActSemanales" />
				<input type="button" id="btn_exportar" name="btn_exportar" class='botones' value="Exportar a Excel" title="Exportar a Excel"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<?php }?>
				<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Seleccionar otras Fechas" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_reporteActSemanal.php'"/>
			</form>
</div><?php 
	
			//Script que envia el contenido de un DIV a imprimir en Excel
			?>
			<script type="text/javascript" src="../../includes/jquery-1.5.1.js" ></script>
			<script language="javascript">
				$(document).ready(function() {
					$("#btn_exportar").click(function(event) {
						$("#hdn_divExpRepActSemanales").val( $("<div>").append( $("#tabla-rpt-act").eq(0).clone()).html());
					$("#frm_exportarDiv").submit();
					});
				});
			</script>
			<?php
			}

?></body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
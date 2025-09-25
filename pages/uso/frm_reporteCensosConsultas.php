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
		//Este archivo guarda la informacion del registro de Radiografias en la bitacora
		include ("op_reporteCensosConsultas.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
			$("#tabla-resultadosRepCC").dataTable({
				"sPaginationType": "scrolling"
			});
	});
	</script>
	
	<script type="text/javascript" language="javascript">
		function mostrarGrafica(grafica){
			window.open('verRepGrafico.php?grafica='+grafica, '_blank','top=100, left=100, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
		}
	</script>
	
    <style type="text/css">
		<!--
			#titulo-consultar{position:absolute;left:30px;top:146px; width:374px;height:20px;z-index:11;}
			#tabla-repCensos{position:absolute;left:30px;top:190px;width:300px;height:270px;z-index:12;}
			#calendario-uno{position:absolute;left:247px;top:233px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:247px;top:272px;width:30px;height:26px;z-index:13;}
			#tabla-consultarResultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:14; padding:15px; padding-top:0px; overflow:scroll;}
			#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:15;}
			#tabla-consulta{ position:absolute; left:30px; top:190px; width:900px;	height:360px; z-index:12; }
			#calendario { position:absolute; left:223px; top:280px; width:30px; height:26px; z-index:13;}
		-->
    </style>
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Reporte de Censos y Consultas Realizadas</div>
	
	<?php
	//Si en el GET esta la variable noResults, mostrar un msje al usuario de no datos
	if(isset($_GET["noResults"])){
		?>
		<script type="text/javascript" language="javascript">
			setTimeout("alert('No se Encontraron Resultados con los Parámetros Ingresados')",1000);
		</script>
		<?php
	}
	//Si no esta definido nada en el POST, mostrar los calendarios de seleccion
	if(!isset($_POST["sbt_consultar"])){
		borrarHistorial();
	?>
		<fieldset class="borde_seccion" id="tabla-repCensos" name="tabla-repCensos">
		<legend class="titulo_etiqueta">Censos y Consultas Realizadas</legend>	
		<br>
		<form onsubmit="return valFormRepCensosConsultas(this);" name="frm_detalleCensosConsultas" method="post" action="frm_reporteCensosConsultas.php">
			<table width="100%"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="90"><div align="right">Fecha Inicio</div></td>
					<td width="173">
						<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" 
						value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" readonly="readonly"/>
				  </td>
				</tr>
					<td width="90"><div align="right">Fecha Fin</div></td>
					<td>
						<input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/>
					</td>
				</tr>
				<tr><td colspan="2"><label class="titulo_etiqueta">Filtrar por Consultas</label></td></tr>
				</tr>
					<td width="90"><div align="right">Consulta</div></td>
					<td>
						<select name="cmb_clasificacion" class="combo_box" id="cmb_clasificacion">
							<option value="" selected="selected">Consulta</option>
							<option value="INTERNA">INTERNA</option>
							<option value="EXTERNA">EXTERNA</option>
						</select>
					</td>
				</tr>
				<tr><td colspan="2"><label class="titulo_etiqueta">Filtrar por Tipo de Consulta</label></td></tr>
				</tr>
					<td width="90"><div align="right">Tipo Consulta</div></td>
					<td>
						<select name="cmb_tipo" class="combo_box" id="cmb_tipo">
							<option value="" selected="selected">Tipo Consulta</option>
							<option value="GENERAL">GENERAL</option>
							<option value="ACCIDENTE">ACCIDENTE</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div align="center"> 
							<input name="sbt_consultar" type="submit" class="botones" id= "sbt_consultar" value="Consultar" 
							title="Generar Reporte de Censos y Consultas Registradas"
							onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Reportes" 
							onmouseover="window.status='';return true" onclick="location.href='menu_reportes.php'" />
						</div>
					</td>
				</tr>
		  </table>
		</form>
</fieldset>
		<div id="calendario-uno">
			<input name="calendario_uno" type="image" id="calendario_uno" onclick="displayCalendar (document.frm_detalleCensosConsultas.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
			title="Seleccione Fecha de Inicio" />
</div>
		<div id="calendario-dos">
			<input name="calendario_dos" type="image" id="calendario_dos" onclick="displayCalendar (document.frm_detalleCensosConsultas.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
</div>
	<?php }
	else{
		//Recolectar los filtros de las consultas
		if(isset($_POST["txt_fechaIni"])){
			$fechaI=$_POST["txt_fechaIni"];
			$fechaF=$_POST["txt_fechaFin"];
			$clasificacion=$_POST["cmb_clasificacion"];
			$tipo=$_POST["cmb_tipo"];
		}
		if(isset($_GET["fechaI"])){
			$fechaI=$_GET["fechaI"];
			$fechaF=$_GET["fechaF"];
			$clasificacion=$_GET["clasificacion"];
			$tipo=$_GET["tipo"];
		}
			echo "<form name='frm_selDetalleCensosConsultas' method='post' onsubmit='return valFormSelDetalleCensosConsultas(this);' action='frm_reporteCensosConsultas.php'>";
			echo "<div class='borde_seccion2' id='tabla-consultarResultados'><br>";
				$graficas=mostrarCensosConsultas($fechaI,$fechaF,$clasificacion,$tipo);
			echo "</div>";
			echo "<div id='botones' align='center'>";
			?>
				<input type="hidden" name="hdn_fechaI" value="<?php echo $fechaI?>"/>
				<input type="hidden" name="hdn_fechaF" value="<?php echo $fechaF?>"/>
				<input type="hidden" name="hdn_clasificacion" value="<?php echo $clasificacion?>"/>
				<input type="hidden" name="hdn_tipo" value="<?php echo $tipo?>"/>
				
				<input type="button" class="botones" value="Exportar a Excel" name="btn_exportar" id="btn_exportar" title="Exportar a Excel los Registros los Censos y Consultas" 
				onclick="location.href='guardar_reporte.php?fechaI=<?php echo $fechaI?>&fechaF=<?php echo $fechaF?>&clasificacion=<?php echo $clasificacion?>&tipo=<?php echo $tipo?>&tipoRep=RepCensosConsultas'"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" value="Ver Gr&aacute;fica" name="btn_grafica" id="btn_grafica" title="Ver Gr&aacute;fica del Reporte de Censos y Concultas" 
				onclick="mostrarGrafica('<?php echo $graficas?>');"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" value="Regresar" name="btn_regresar" id="btn_regresar" title="Regresar a seleccionar otras Fechas" 
				onclick="location.href='frm_reporteCensosConsultas.php'"/>
			<?php
			echo "</div>";
			echo "</form>";
		
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
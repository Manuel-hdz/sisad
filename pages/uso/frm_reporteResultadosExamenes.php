<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a estos Reportes
	/*if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{*/
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo guarda la informacion del registro de Radiografias en la bitacora
		include ("op_reporteResultadosExamenes.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
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
		
    <style type="text/css">
		<!--
			#titulo-consultar{position:absolute;left:30px;top:146px; width:347px;height:20px;z-index:11;}
			#tabla-repCensos{position:absolute;left:30px;top:190px;width:312px;height:154px;z-index:12;}
			#calendario-uno{position:absolute;left:256px;top:233px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:254px;top:272px;width:30px;height:26px;z-index:13;}
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
    <div class="titulo_barra" id="titulo-consultar">Reporte de Resultados de Ex&aacute;men Periodicos</div>
	
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
		
	//Si viene definido el boton sbt_modificar que mande llamra la funcion que guarda los resultados de examenes periodicos
	if(isset($_POST["sbt_modificar"])){
		regResultadosExaPeriodicos();
	}	
	?>
		<fieldset class="borde_seccion" id="tabla-repCensos" name="tabla-repCensos">
		<legend class="titulo_etiqueta">Resultados Ex&aacute;men Periodicos</legend>	
		<br>
		<form name="frm_resultadosExaPeriodicos" method="post" action="frm_reporteResultadosExamenes.php">
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
			<input name="calendario_uno" type="image" id="calendario_uno" onclick="displayCalendar (document.frm_resultadosExaPeriodicos.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
			title="Seleccione Fecha de Inicio" />
</div>
		<div id="calendario-dos">
			<input name="calendario_dos" type="image" id="calendario_dos" onclick="displayCalendar (document.frm_resultadosExaPeriodicos.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
</div>
	<?php }
	else{
		//Recolectar los filtros de las consultas
		if(isset($_POST["txt_fechaIni"])){
			$fechaI=$_POST["txt_fechaIni"];
			$fechaF=$_POST["txt_fechaFin"];			
		}
		if(isset($_GET["fechaI"])){
			$fechaI=$_GET["fechaI"];
			$fechaF=$_GET["fechaF"];
		}
			echo "<form name='frm_selResultadosExamen' method='post' onsubmit='return valFormSelResultadosExamen(this);' action='frm_reporteResultadosExamenes.php'>";
			echo "<div class='borde_seccion2' id='tabla-consultarResultados'><br>";
					mostrarResultadosExamenes($fechaI,$fechaF);
			echo "</div>";
			echo "<div id='botones' align='center'>";
			?>
				<input type="hidden" name="hdn_accion" id="hdn_accion" value="" />
				<input type="hidden" name="hdn_fechaI" value="<?php echo $fechaI?>"/>
				<input type="hidden" name="hdn_fechaF" value="<?php echo $fechaF?>"/>
				
				<input type="submit" class="botones" value="Modificar" name="sbt_modificar" id="sbt_modificar" title="Modificar el Resultado del Examen Periodico" onclick="" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" class="botones" value="Eliminar" name="sbt_eliminar" id="sbt_eliminar" title="Eliminar Resultados del Examen Periodico"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" value="Exportar a Excel" name="btn_exportar" id="btn_exportar" title="Exportar a Excel los Registros los Censos y Consultas" 
				onclick="location.href='guardar_reporte.php?fechaI=<?php echo $fechaI?>&fechaF=<?php echo $fechaF?>&tipoRep=RepResultadosExa'"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" value="Regresar" name="btn_regresar" id="btn_regresar" title="Regresar a seleccionar otras Fechas" 
				onclick="location.href='frm_reporteResultadosExamenes.php'"/>
			<?php
			echo "</div>";
			echo "</form>";	
	}
	?>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
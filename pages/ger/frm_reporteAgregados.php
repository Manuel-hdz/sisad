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
		include ("op_reporteAgregado.php");?>

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
			#titulo-consultar {position:absolute;left:30px;top:146px;	width:322px;height:20px;z-index:11;}
			#btns-regpdf { position: absolute; left:30px; top:630px; width:940px; height:35px;  }
			#tabla-reporte-agregado {position:absolute;left:30px;top:190px;width:425px;height:170px;z-index:12;}
			#tabla-reporte-fecha{position:absolute;left:507px;top:190px;width:425px;height:170px;z-index:12;}
			#tabla-Agregados { position:absolute; left:30px; top:380px; width:945px; height:170px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
			#calendar-tres {position:absolute; left:723px; top:234px; width:30px; height:26px; z-index:18; }
			#calendar-uno {position:absolute; left:239px; top:267px; width:30px; height:26px; z-index:18; }
			#calendar-cuatro {position:absolute; left:921px; top:232px; width:30px; height:26px; z-index:18; }
			#calendar-dos {position:absolute; left:426px; top:265px; width:30px; height:26px; z-index:18; }
			#detalleAgregados {	position:absolute;	left:31px;	top:190px;	width:940px;	height:351px;	overflow: scroll;	z-index: 30;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Generar Reporte de Pruebas a Agregados </div>
<?php if(!isset($_POST['verDetalle'])){?>
	<fieldset class="borde_seccion" id="tabla-reporte-agregado" name="tabla-reporte-agregado">
	<legend class="titulo_etiqueta">Buscar Agregado por Nombre </legend>	
	<br>
	<form name="frm_consultarAgregado" method="post" action="frm_reporteAgregados.php" onsubmit="return valFormRptAgregados(this);">
		<table width="415" height="108"  cellpadding="5" cellspacing="5" class="tabla_frm">
      		<tr>
				<td width="63"><div align="right">Agregado</div></td>
		  		<td colspan="4"><?php 
						$cmb_agregado="";
						$conn = conecta("bd_almacen");
						$result=mysql_query("SELECT DISTINCT nom_material FROM materiales WHERE linea_articulo='AGREGADO' ORDER BY nom_material");
						if($agregados=mysql_fetch_array($result)){?>
							<select name="cmb_agregado" id="cmb_agregado" size="1" class="combo_box">
								<option value="">Agregado</option><?php 
									do{
										if ($agregados['nom_material'] == $cmb_agregado){
											echo "<option value='$agregados[nom_material]' selected='selected'>$agregados[nom_material]</option>";
										}
										else{
											echo "<option value='$agregados[nom_material]'>$agregados[nom_material]</option>";
										}
									}while($agregados=mysql_fetch_array($result)); 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
							</select><?php
						 }
						else{
							echo "<label class='msje_correcto'> No hay Agregados Registrados</label>
							<input type='hidden' name='cmb_agregado' id='cmb_agregado'/>";
				  		}?>
			  </td>
			</tr>
			<tr>
				<td width="88"><div align="right">Fecha Inicio</div></td>
				<td width="92">
					<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
					value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10"  width="90"/>					</td>
				<td width="75"><div align="right">Fecha Fin </div></td>
				<td width="116">
					<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
					value="<?php echo date("d/m/Y"); ?>" size="10" width="90"/>					</td>
			</tr>
			<tr>
			<td colspan="4">
					<div align="center"> 
						<input name="sbt_consultar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Consultar Agregados"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otro Tipo de Reporte" 
						onmouseover="window.status='';return true" onclick="location.href='frm_selTipoConsultaLaboratorio.php'" />
					</div>
			  </td>
			</tr>
   	  </table>
	</form>
</fieldset>
	
<fieldset class="borde_seccion" id="tabla-reporte-fecha">
<legend class="titulo_etiqueta">Reporte Agregados por Fecha </legend>	
<br>
	<form  method="post" name="frm_reporteFecha" id="frm_reporteFecha" onsubmit="return valFormRptAgregadosFecha(this);">
		<table width="438" height="108"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="88"><div align="right">Fecha Inicio</div></td>
				<td width="92">
					<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
					value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10"  width="90"/>					</td>
				<td width="75"><div align="right">Fecha Fin </div></td>
				<td width="116">
					<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
					value="<?php echo date("d/m/Y"); ?>" size="10" width="90"/>					</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td colspan="4">
					<div align="center"> 
						<input name="sbt_consultar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Consultar Agregados"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otro Tipo de Reporte" 
						onmouseover="window.status='';return true" onclick="location.href='frm_selTipoConsultaLaboratorio.php'" />
					</div>
			  	</td>
		  </tr>
	  </table>
	</form>
	</fieldset>
	<div id="calendar-uno">
		<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_consultarAgregado.txt_fechaIni,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		width="25" height="25" border="0" />
</div>
	<div id="calendar-cuatro">
		<input name="fechaFin" type="image" id="fechaFin" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaFin,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		width="25" height="25" border="0" />
</div>
	<div id="calendar-dos">
		<input name="fechaFin" type="image" id="fechaIni" onclick="displayCalendar(document.frm_consultarAgregado.txt_fechaFin,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		width="25" height="25" border="0" />
</div>
<div id="calendar-tres">
		<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaIni,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		width="25" height="25" border="0" />
</div>
    <?php 
	//Verificamos que el boton consultar sea presionado; si es asi mostrar los Empleados
	if(isset($_POST["txt_fechaIni"])){
		echo"<div id='tabla-Agregados' class='borde_seccion2' align='center'>";
		//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de consultar
			mostrarAgregados();
			echo "</div>";	?>
			<div id="btns-regpdf" align="center">
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_selTipoConsultaLaboratorio.php?band=si'" />
			</div>
		<?php
 }
	}	
	elseif(isset($_POST['verDetalle'])){
	?>
		<form name="frm_detalleAgregado"  id="frm_detalleAgregado" method="post"  action="guardar_reporte.php" >
			<div id="detalleAgregados" class='borde_seccion2' align="center">
			  <?php detalleAgregado($_POST['ckb_detalleAgregado']);?>
			</div>
	</form>
<?php } 
if(!isset($_GET["band"])&&isset($_POST["sbt_consultar"]))
		borrarTemporales();?>
</body><?php
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
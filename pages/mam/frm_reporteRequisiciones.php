<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reporteRequisiciones.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<?php //Funciones de LightBox?>
	<link rel="stylesheet" href="../../includes/lightbox/css/lightbox.css" type="text/css" media="screen" />
	<script src="../../includes/lightbox/js/prototype.js" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/lightbox.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquipoMtto.js"></script>
	<script type="text/javascript" src="includes/ajax/borrarHistorial.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboMesesBit.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquiposBit.js"></script>
	
  	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	
	<?php //Funciones para Paginacion ?>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
			$("#tabla-detallePedido").dataTable({
				"sPaginationType": "scrolling"
			});
	});
	</script>
	
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>
    <style type="text/css">
		<!--	
			#titulo-reporte {position:absolute; left:30px; top:146px; width:388px; height:25px; z-index:11; }
			#tabla-consultar {position:absolute; left:30px; top:190px; width:300px; height:150px; z-index:14;}
			#calendarioInicio {position:absolute;left:275px;top:230px;width:30px;height:26px;z-index:15;}
			#calendarioFin {position:absolute;left:275px;top:265px;width:30px;height:26px;z-index:15;}
			#reporte { position:absolute; left:30px; top:190px; width:921px; height:430px; z-index:22; overflow:scroll; }
			#tabla-graficas { position:absolute; left:30px; top:190px; width:945px; height:430px; z-index:21;overflow:scroll;}
			#botones{position:absolute;left:30px;top:670px;width:950px;height:37px;z-index:16;}
			#btns-regpdf { position: absolute; left:319px; top:670px; width:400px; height:40px; z-index:24; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-reporte">Reporte de Requisiciones</div>
	<?php
	//Verificamos si viene definido en el post el boton consultar
	if(isset($_POST['verDetalle'])){
		$band = 1;		
		//Obtener el valor de la clave de la Entrada seleccionada
		$clave = $_POST['RE'];
		$bd = $_POST['select_bd'];
		$fecha_i = $_POST['fecha_inicial'];
		$fecha_f = $_POST['fecha_final'];
		//Mostrar el detalle del Pedido Seleccionado
		if($bd != "TODOS")
			mostrarDetalleReqArea($clave,$bd,$fecha_i,$fecha_f);
		else
			mostrarDetalleReq($clave,$bd,$fecha_i,$fecha_f);
	}
	else if(!isset($_POST["sbt_consultarConsumo"])){
		if(isset($_GET["noResults"])){
			echo "
				<script type='text/javascript' language='javascript'>
					setTimeout(\"alert('NO se Encontraron Resultados con los Criterios Seleccionados');\",500);
				</script>
			";
		}
		?>
		
		<fieldset class="borde_seccion" id="tabla-consultar">
		<legend class="titulo_etiqueta">Seleccionar filtros del reporte</legend>	
		<br>
		<form method="post" name="frm_reportePedidos" id="frm_reportePedidos" onsubmit="return valFormRepAceites(this);">
		<table width="108%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td>
					<div align="right">Fecha Inicial</div>
				</td>
				<td>
					<input name="txt_fecha_ini" id="txt_fecha_ini" type="text" value=<?php echo date("d/m/Y", strtotime("-1 month"));?> size="10" maxlength="15"
					readonly="true" width="90" />
				</td>
			</tr>
			<tr>
				<td>
					<div align="right">Fecha Final</div>
				</td>
				<td>
					<input name="txt_fecha_fin" id="txt_fecha_fin" type="text" value=<?php echo date("d/m/Y");?> size="10" maxlength="15"
					readonly="true" width="90" />
				</td>
			</tr>
			<input name="cmb_departamento" id="cmb_departamento" type="hidden" value="bd_mantenimiento"/>
			<!--
			<tr>
				<td><div align="right">Departamento: </div></td>
				<td>
					<select name="cmb_departamento" id="cmb_departamento" size="1" class="combo_box">
						<option value="TODOS">Departamento</option><?php 
						/*$conn = conecta("bd_compras");
						$rs = mysql_query('SHOW DATABASES;');
						while($db = mysql_fetch_row($rs)){
							if(substr( $db[0], 0, 3 ) == "bd_"){
								$departamento = strtoupper(substr( $db[0], 3));
								mysql_select_db("$db[0]");
								$rs_tablas = mysql_query('SHOW TABLES;');
								while($db_tablas = mysql_fetch_row($rs_tablas)){
									if($db_tablas[0] == "requisiciones"){
										echo "<option value='$db[0]'>$departamento</option>";
									}
								}
							}
						}
						mysql_close($conn);*/?>
					</select>
				</td>
			</tr>
			-->
			<tr>
				<td colspan="4" align="center">
					<input name="sbt_consultarConsumo" type="submit"  class="botones" id="sbt_consultarConsumo" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte de Consumo de Aceite" tabindex="4"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" tabindex="5"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<div id="calendarioInicio">
			<input type="image" name="img_fechaInicio" id="img_fechaInicio" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_reportePedidos.txt_fecha_ini,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Inicio"/> 
		</div>
		
		<div id="calendarioFin">
			<input type="image" name="cmb_fechaFin" id="cmb_fechaFin" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_reportePedidos.txt_fecha_fin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Fin"/> 
		</div><?
	<?php 
	}
	else{
		if(isset($_POST["sbt_consultarConsumo"])){}
			reporteRequisiciones();
	}?>
	
</body><?php } //Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
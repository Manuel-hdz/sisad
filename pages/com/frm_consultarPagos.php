<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo el detalle de la Caja Chica seleccionada
		include ("op_gestionarPagos.php");

	
?><head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<?php //Busqueda Sphider?>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<?php //Busqueda Sphider?>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personalBajas.js"></script>

	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
   
   	<style type="text/css">
		<!--
		#titulo-consultaPagos { position:absolute;	left:30px; top:146px; width:163px; height:17px;	z-index:11;}
		#tabla-consultaPagos {position:absolute;left:26px;top:193px;width:568px;height:213px;z-index:12;}
		#res-spider{position:absolute;z-index:13;}
		#resultados{position:absolute;left:30px;top:190px;width:940px;height:430px;z-index:14;overflow:scroll;}
		#botones{position: absolute; left:319px; top:670px; width:400px; height:40px; z-index:15; }
		
		#titulo-consultaPagosRes { position:absolute;	left:30px; top:146px; width:163px; height:17px;	z-index:20;}
		#tabla-consultaPagosRes {position:absolute;left:527px;top:191px;width:441px;height:149px;z-index:21;}
		#res-spider2{position:absolute; z-index:25;}

		
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultaPagos">Consultar Pagos</div>

<?php
	//Si en el GET esta la variable noResults, mostrar un msje al usuario de no datos
	if(isset($_GET["noResults"])){
		?>
		<script type="text/javascript" language="javascript">
			setTimeout("alert('No se Encontraron Resultados con los Parámetros Ingresados')",1000);
		</script>
		<?php
	}
	
	if(!isset($_POST["sbt_consultar"])){
	?>
		<fieldset class="borde_seccion" id="tabla-consultaPagos" name="tabla-consultaPagos">
		<legend class="titulo_etiqueta">Seleccionar A&ntilde;o/Mes </legend>
		<br />
		<form method="post" name="frm_seleccionarDatosPago" onsubmit="return valFormConsultarPago(this);">
		<table width="101%" height="170" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td align="right" width="9%">A&ntilde;o</td>
				<td width="12%">
					<select name="cmb_anio" id="cmb_anio" class="combo_box">
						<option value="" selected="selected">A&ntilde;o</option>
					<?php
					$conn=conecta("bd_compras");
					$rs=mysql_query("SELECT DISTINCT SUBSTRING(fecha,1,4) as anio FROM pagos");
					if($datos=mysql_fetch_array($rs)){
						do{
							?>
							<option value="<?php echo $datos["anio"]?>"><?php echo $datos["anio"]?></option>
							<?php
						}while($datos=mysql_fetch_array($rs));
					}
					?>
					</select>
				</td>
				<td align="right" width="7%">Mes</td>
				<td width="23%">
					<select name="cmb_mes" id="cmb_mes" class="combo_box">
						<option value="" selected="selected">Mes</option>
					<?php
					$conn=conecta("bd_compras");
					$rs=mysql_query("SELECT DISTINCT SUBSTRING(fecha,6,2) as mes FROM pagos");
					if($datos=mysql_fetch_array($rs)){
						do{
							switch($datos["mes"]){
								case "01":	$nomMes="ENERO";		break;
								case "02":	$nomMes="FEBRERO";		break;
								case "03":	$nomMes="MARZO";		break;
								case "04":	$nomMes="ABRIL";		break;
								case "05":	$nomMes="MAYO";			break;
								case "06":	$nomMes="JUNIO";		break;
								case "07":	$nomMes="JULIO";		break;
								case "08":	$nomMes="AGOSTO";		break;
								case "09":	$nomMes="SEPTIEMBRE";	break;
								case "10":	$nomMes="OCTUBRE";		break;
								case "11":	$nomMes="NOVIEMBRE";	break;
								case "12":	$nomMes="DICIEMBRE";	break;
							}
							?>
							<option value="<?php echo $datos["mes"]?>"><?php echo $nomMes?></option>
							<?php
						}while($datos=mysql_fetch_array($rs));
					}
					?>
					</select>
				</td>
				<td width="14%" align="right">Filtrar Por</td>
				<td width="35%">
					<select id="cmb_tipo" name="cmb_tipo" class="combo_box" onchange="filtroTipoPago(this.value);">
					<option value="">Seleccionar</option>
						<option value="PROVEEDOR">Proveedor</option>
						<option value="RESPONSABLE">Responsable</option>					
						<option value="CANTIDAD">Cantidad</option>					
						<option value="BAJAS">Bajas</option>					
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2"><div align="right"><span id="etiqueta">&nbsp;</span></div></td>
				<td colspan="5"><span id="componenteHTML">&nbsp;</span></td>
			</tr>
			<tr>
				<td colspan="2"><div align="right"><span id="etiquetaInf">&nbsp;</span></div></td>
				<td colspan="5"><span id="componenteHTMLInf">&nbsp;</span></td>
			</tr>
			<tr>
				<td colspan="2"><div align="right"><span id="etiquetaSup">&nbsp;</span></div></td>
				<td colspan="5"><span id="componenteHTMLSup">&nbsp;</span></td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input type="submit" name="sbt_consultar" id="sbt_consultar" value="Consultar" class="botones" 
					title="Consultar los Pagos Efectuados en las Fechas Seleccionadas" 
					onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" id="btn_regresar" title="Regresar al Men&uacute; Anterior" onclick="location.href='menu_egresos.php'" 
					value="Regresar" class="botones"/>
				</td>
			</tr>  	 		
		</table>
	   </form>
</fieldset>

	<?php
	}
	else{?>
		<div id="resultados" class="borde_seccion2" align="center">
		<?php
			mostrarPagos();
		?>
		</div>
		<div id="botones" align="center">
		<form action="guardar_reporte.php" method="post" id="frm_exportarDiv">
			<input type="button" name="btn_regresar" id="btn_regresar" title="Regresar al Men&uacute; Anterior" onclick="location.href='frm_consultarPagos.php'" 
			value="Regresar" class="botones"/>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="hidden" id="hdn_divExpRepPagos" name="hdn_divExpRepPagos" />
			<input type="button" name="btn_exportar" id="btn_exportar" title="Exportar Resultados a Formato Excel" 
			value="Exportar Datos" class="botones_largos"/>
		</form>
		</div><?php
		
		//Script que envia el contenido de un DIV a imprimir en Excel
			?>
			<script type="text/javascript" src="../../includes/jquery-1.5.1.js" ></script>
			<script language="javascript">
				$(document).ready(function() {
					$("#btn_exportar").click(function(event) {
						$("#hdn_divExpRepPagos").val( $("<div>").append( $("#tabla-rpt-pagos").eq(0).clone()).html());
					$("#frm_exportarDiv").submit();
					});
				});
			</script>
		
	<?php }?>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
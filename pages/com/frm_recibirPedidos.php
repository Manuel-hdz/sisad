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
		//La llamada a este archivo es para almacenar el pedido en la BD y obtener datos del detalle de Pedido
		include ("op_recibirPedidos.php");	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="includes/ajax/verificarPedido.js"></script>
	
    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:25px; top:146px; width:227px; height:20px; z-index:10; }
		#tabla-registro { position:absolute; left:150px; top:190px; width:300px; height:100px; z-index:11; }
		#tabla-registro-fechas { position:absolute; left:520px; top:190px; width:300px; height:130px; z-index:11; }
		#tabla-pedidos { position:absolute; left:10px; top:380px; width:970px; height:280px; z-index:11; overflow:auto; }
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		#editar-iva {position:absolute;left:600px;top:249px;width:35px;height:30px;z-index:12;}
		#calendar-uno { position:absolute; left:750px; top:215px; width:30px; height:26px; z-index:17; }
		#calendar-dos { position:absolute; left:750px; top:250px; width:30px; height:26px; z-index:18; }
		#botonExp { position:absolute; left:10px; top:680px; width:990px; height:30px; z-index:11;}
		#titulo_pedidos { position:absolute; left:10px; top:360px; width:990px; height:30px; z-index:11;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Recibir Pedido</div><?php 
	
	//Si esta definido el boton 'btn_continuar' el pedido es Generado a partir de una Requisición
	if (isset($_POST["sbt_guardar"])){
		?>
		<div id="titulo_pedidos" align="center">
			<?php 
			if(isset($_POST["txt_pedido"])){
				if(substr(strtoupper($_POST["txt_pedido"]),0,3) == "PED"){
					echo "<legend class='titulo_etiqueta'>CONSULTA DE PEDIDOS Y ORDENES DE TRABAJO - PEDIDO ".$_POST['txt_pedido']."</legend>";
				} else {
					echo "<legend class='titulo_etiqueta'>CONSULTA DE PEDIDOS Y ORDENES DE TRABAJO - OTSE ".$_POST['txt_pedido']."</legend>";
				}
			} else {
				echo "<legend class='titulo_etiqueta'>CONSULTA DE PEDIDOS Y ORDENES DE TRABAJO DEL ".$_POST["txt_fechaIni"]." AL ".$_POST["txt_fechaFin"]."</legend>";
			}
			?>
		</div>
		<div id="tabla-pedidos" class="borde_seccion">
			<?php
			mostrarPedidos();
			?>
		</div>
		<form name="exportarReporte" method="post" action="guardar_reporte.php">
			<div id="botonExp" align="center">
				<?php
				if(isset($_POST["txt_pedido"])){
				?>
					<input type="hidden" name="txt_pedido" id="txt_pedido" value="<?php echo $_POST["txt_pedido"]; ?>"/>
				<?php
				} else {
				?>
					<input type="hidden" name="txt_fechaIni" id="txt_fechaIni" value="<?php echo $_POST["txt_fechaIni"]; ?>"/>
					<input type="hidden" name="txt_fechaFin" id="txt_fechaFin" value="<?php echo $_POST["txt_fechaFin"]; ?>"/>
				<?php
				}
				?>
				<input name="hdn_consulta" type="hidden" value="" />
				<input name="hdn_nomReporte" type="hidden" value="Reporte_Pedidos_OTSE" />
				<input name="hdn_origen" type="hidden" value="reporte_PedOTSE" />
				<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
				title="Exportar a Excel los Datos de la Consulta Realizada" onMouseOver="window.estatus='';return true"  />
			</div>
		</form>
		<?php
	} else if (isset($_POST["sbt_guardarRecibido"])){
		recibirPedidos();
	}?>
	<fieldset class="borde_seccion" id="tabla-registro" name="tabla-registro">
	<legend class="titulo_etiqueta">Introducir los datos de recepci&oacute;n</legend>
	<form onSubmit="" name="frm_recibirPedidos" method="post" action="frm_recibirPedidos.php">
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
				<td align="right">*Pedido / OTSE</td>
				<!-- <td><input type="text" name="txt_pedido" id="txt_pedido" size="15" maxlength="10" required="required" onchange="verificarPedido(this.value);"/></td> -->
				<td><input type="text" name="txt_pedido" id="txt_pedido" size="15" maxlength="11" autocomplete="off" required="required"/></td>
			</tr>
			<!-- <tr>
				<td align="right">*Factura</td>
				<td><input type="text" name="txt_factura" id="txt_factura" size="15" maxlength="20" required="required"/></td>
			</tr> -->
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="sbt_guardar" value="Consultar" class="botones" onmouseover="window.status='';return true;" 
					title="Consultar Informaci&oacute;n"/>									
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" onclick="location.href='menu_pedidos.php'"
					title="Regresar al Men&uacute; de Pedidos"/>
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
	
	<fieldset class="borde_seccion" id="tabla-registro-fechas" name="tabla-registro-fechas">
	<legend class="titulo_etiqueta">Introducir los datos de recepci&oacute;n</legend>
	<form name="frm_recibirPedidosFecha" method="post" action="frm_recibirPedidos.php">
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
				<td><div align="right">Fecha Inicio</div></td>
                <td><input name="txt_fechaIni" type="text" class="caja_de_texto" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10" 
                maxlength="15" readonly=true width="90" /></td>
			</tr>
            <tr>
				<td><div align="right">Fecha Fin </div></td>
				<td><input name="txt_fechaFin" type="text" class="caja_de_texto" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" 
                readonly=true width="90" /></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="sbt_guardar" value="Consultar" class="botones" onmouseover="window.status='';return true;" 
					title="Consultar Informaci&oacute;n"/>									
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" onclick="location.href='menu_pedidos.php'"
					title="Regresar al Men&uacute; de Pedidos"/>
				</td>
			</tr>
		</table>
	</form>
	</fieldset>
	<script>document.getElementById('txt_pedido').focus();</script>
	
	<div id="calendar-uno">
		<input type="image" name="iniRepProv" id="iniRepProv" src="../../images/calendar.png" onclick="displayCalendar(document.frm_recibirPedidosFecha.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
	</div>
    
	<div id="calendar-dos">
		<input type="image" name="finRepProv" id="finRepProv" src="../../images/calendar.png" onclick="displayCalendar(document.frm_recibirPedidosFecha.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
	</div>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<?php
	//Manejo de fechas
	include("../../includes/func_fechas.php");
	//Modulo de conexion con la base de datos, el cual estara diponible para todas la paginas a trav�s de este archivo (head_menu.php) 
	include("../../includes/conexion.inc");	
	//Manejo de operaciones que consultan datos en la BD y los regresan en el elemento de formulario undicado en los parametros de las funciones
	include("../../includes/op_operacionesBD.php");
	//Muestra la alerta para la junta administrativa
	include("../../includes/alertas_Junta.php");

	include("op_inicioCompras.php");
	include_once("op_registrarJunta.php");
?>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<!-- JS de Bootstrap -->
	<script src="../../includes/b4/bootstrap/js/bootstrap.min.js"></script>
	<script src="../../includes/b4/popper.js/popper.min.js"></script>
	<script src="../../includes/b4/jquery/jquery.slim.min.js"></script>

	<!-- Importacion de FontAwesome -->
	<script src="../../includes/fontawesome/js/all.js"></script>
	<link rel="stylesheet" href="../../includes/fontawesome/css/all.css">

	<link rel="stylesheet" href="menu_files/style.css" type="text/css" />
	<script type="text/javascript" src="../../includes/botones.js"></script>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" src="../../includes/maxLength.js"></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js"></script>
	<script type="text/javascript" src="../../includes/funcionesJS.js"></script>
	<script type="text/javascript" src="../../includes/reloj.js"></script>

	<script type="text/javascript" src="../../includes/chat/abrirChat.js"></script>
	<script type="text/javascript" src="../../includes/ajax/monitorMensajes.js"></script>
	<script type="text/javascript" language="javascript">
		setInterval(msjesNuevos, 2500);
	</script>

	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button == 2) {
				alert('Contenido Protegido, �Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown = click;
		//
		-->
	</script>

	<style type="text/css">
		.titulo-pagina {
			font-family: MicrogrammaDMedExt;
			color: #33761B;
			font-size: 13px;
			font-weight: bold;
		}

		.usr-reg {
			color: #FFFFFF;
			font-weight: bold;
			font-family: MicrogrammaDMedExt;
			font-size: 12px;
		}

		#modulo {
			position: absolute;
			left: 6px;
			top: 56px;
			width: 169px;
			height: 17px;
			z-index: 8;
		}

		#titulo {
			position: absolute;
			left: 306px;
			top: 15px;
			width: 528px;
			height: 22px;
			z-index: 3;
		}

		#fecha {
			position: absolute;
			left: 380px;
			top: 59px;
			width: 430px;
			height: 18px;
			z-index: 9;
		}

		.fecha {
			font-family: MicrogrammaDMedExt;
			font-size: 12px;
			color: #FFFFFF;
			font-weight: bold;
		}

		#dock-new {
			position: absolute;
			width: 275px;
			height: 50px;
			z-index: 124;
			left: 0;
			top: 0;
		}

		#msje_aviso_img {
			position: absolute;
			left: 257px;
			top: 0px;
			z-index: 200;
			cursor: pointer;
			width: 36px;
			height: 27px;
			background-image: url(images/msje-aviso.png);
			background-repeat: no-repeat;
			padding-top: 3px;
		}

		#tc_monext {
			position: fixed;
			top: 118px;
			left: 20px;
			border-radius: 15px;
			background-color: green;
			color: white;
			padding-left: 10px;
			padding-right: 10px;
			font-size: 15px;
		}
	</style>
</head><?php


if($_SESSION['usr_reg']=="AdminCompras"){?>

<body onLoad="setInterval(muestraReloj, 1000);"><?php
}else if($_SESSION['usr_reg']=="AuxCompras"){?>

	<body onLoad="inicio(); setInterval(muestraReloj, 1000);" onkeypress="parar()" onclick="parar()"
		onmousemove="parar()"><?php
}


	//Este codigo despliega la cantidad de OTSE que existen sin complementar
	$cantOrdPend = obtenerNumOTSE();	
	if($cantOrdPend>0){?>
		<div id="msje_aviso_img" title="Hay <?php echo $cantOrdPend;?> &Oacute;rdenes de Trabajo Externas Pendiente(s)"
			align="center" onClick="document.frm_alertarOTSE.submit();">
			<form name="frm_alertarOTSE" action="frm_consultarOTSE.php">
				<label
					style="font-family:Arial, Helvetica, sans-serif; color:#FFFFFF; font-weight:bolder; font-size:14px;"><?php echo $cantOrdPend;?></label>
			</form>
		</div><?php
	}?>


		<div id="dock-new">
			<table width="50" border="0">
				<tr>
					<td width="50" height="50">
						<form action="inicio_compras.php">
							<input type="image" src="../../images/dock/home.png" name="icono1" id="icono1" width="50"
								height="50" border="0" title="Inicio Compras"
								onClick="MM_nbGroup('down','group2','icono1','',1)"
								onMouseOver="MM_nbGroup('over','icono1','../../images/dock/home-over.png','',1); window.status='';return true"
								onMouseOut="MM_nbGroup('out')" />
						</form>
					</td>
					<td width="50" height="50">
						<form action="frm_consultarEmpleados.php">
							<input type="image" src="../../images/dock/rh.png" name="icono2" id="icono2" width="50"
								height="50" border="0" title="Recursos Humanos"
								onClick="MM_nbGroup('down','group2','icono2','',1)"
								onMouseOver="MM_nbGroup('over','icono2','../../images/dock/rh-over.png','',1); window.status='';return true"
								onMouseOut="MM_nbGroup('out')" />
						</form>
					</td>
					<td width="50" height="50">
						<form action="frm_consultarReporteREA.php">
							<input type="image" src="../../images/dock/alm-rea.png" name="icono3" id="icono3" width="50"
								height="50" border="0" title="Reporte REA"
								onClick="MM_nbGroup('down','group2','icono3','',1)"
								onMouseOver="MM_nbGroup('over','icono3','../../images/dock/alm-rea-over.png','',1); window.status='';return true"
								onMouseOut="MM_nbGroup('out')" />
						</form>
					</td>
					<td width="50" height="50">
						<form action="frm_gestionarSolicitud.php">
							<input type="image" src="../../images/dock/cons-ext.png" name="icono5" id="icono5"
								width="50" height="50" border="0" title="Consultas Externas a la Cl&iacute;nica"
								onClick="MM_nbGroup('down','group2','icono5','',1)"
								onMouseOver="MM_nbGroup('over','icono5','../../images/dock/cons-ext-over.png','',1); window.status='';return true"
								onMouseOut="MM_nbGroup('out')" />
						</form>
					</td>
					<td width="50" height="50">
						<form action="frm_consultarOTSE.php">
							<input type="image" src="../../images/dock/man.png" name="icono4" id="icono4" width="50"
								height="50" border="0"
								title="Ordenes de Trabajo para Servicios Externos de Mantenimiento"
								onClick="MM_nbGroup('down','group2','icono4','',1)"
								onMouseOver="MM_nbGroup('over','icono4','../../images/dock/man-over.png','',1); window.status='';return true"
								onMouseOut="MM_nbGroup('out')" />
						</form>
					</td>
				</tr>
			</table>
		</div>

		<div id="fondo-title" style="position:absolute; left:0px; top:0px; width:656px; height:52px; z-index:1"><img
				src="../../images/dock/dock-bg2.gif" width="1035" height="50" /></div>
		<div id="logo" style="position:absolute; left:831px; top:-1px; width:154px; height:54px; z-index:4"><a
				href="http://www.concretolanzadodefresnillo.com" target="_blank"
				title="Ir a la P�gina Web de Concreto Lanzado de Fresnillo S.A. de C.V."><img
					src="../../images/logo.png" width="151" height="56" border="0" /></a>
		</div>
		<div id="log-off" style="position:absolute; left:992px; top:10px; width:33px; height:34px; z-index:5">
			<form action="../salir.php">
				<input type="image" src="../../images/close.png" width="31" height="31" border="0"
					title="Cerrar Sesi&oacute;n" onMouseOver="window.estatus='';return true" />
			</form>
		</div>


		<div id="menu" style="position:absolute; left:-15px; top:70px; width:1035px; height:85px; z-index:123;">
			<ul id="css3menu">
				<li class="topfirst"><a href="menu_proveedores.php" title="Proveedores"
						onMouseOver="window.estatus='';return true"><span>Proveedores</span></a>
					<ul>
						<li class="subfirst"><a href="frm_agregarProveedor.php" title="Agregar Proveedor" onMouseOver="window.estatus='';
                    return true">Agregar Proveedor</a></li>
						<li class="subfirst"><a href="frm_consultarProveedor.php" title="Consultar Proveedor"
								onMouseOver="window.estatus='';
                    return true">Consultar Proveedor</a></li>
						<li class="subfirst"><a href="frm_modificarProveedor.php" title="Modificar Proveedor"
								onMouseOver="window.estatus='';
                    return true">Modificar Proveedor</a></li>
						<li class="subfirst"><a href="frm_registrarConvenio.php"
								title="Registrar Convenio con un Proveedor" onMouseOver="window.estatus='';
                    return true">Registrar Convenio</a></li>
						<li class="subfirst"><a href="frm_evaluarProveedor.php" title="Evaluar Proveedor" onMouseOver="window.estatus='';
                    return true">Evaluar Proveedor</a></li>
					</ul>
				</li>
				<li class="topfirst"><a href="menu_clientes.php" title="Clientes"
						onMouseOver="window.estatus='';return true"><span>Clientes</span></a>
					<ul>
						<li class="subfirst"><a href="frm_agregarCliente.php" title="Agregar Cliente"
								onMouseOver="window.estatus='';return true">Agregar Cliente</a></li>
						<li class="subfirst"><a href="frm_consultarCliente.php" title="Consultar Cliente" onMouseOver="window.estatus='';
                    return true">Consultar Cliente</a></li>
						<li class="subfirst"><a href="frm_modificarCliente.php" title="Modificar Cliente" onMouseOver="window.estatus='';
                    return true">Modificar Cliente</a></li>
						<li class="subfirst"><a href="frm_exportarCSV.php"
								title="Exportar Archivo CSV (Facturaci&oacute;n Electr&oacute;nica)"
								onMouseOver="window.estatus='';return true">Exportar Archivo CSV</a></li>
					</ul>
				</li>
				<li class="topfirst"><a href="menu_requisiciones.php" title="Requisiciones"
						onMouseOver="window.estatus='';return true"><span>Requisiciones</span></a>
					<ul>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=almacen"
								title="Revisar Requisiciones Almac&eacute;n"
								onMouseOver="window.estatus='';return true">Almac&eacute;n</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=gerenciatecnica"
								title="Revisar Requisiciones Gerencia T&eacute;cnica"
								onMouseOver="window.estatus='';return true">Gerencia T&eacute;cnica</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=recursoshumanos"
								title="Revisar Requisiciones Recursos"
								onMouseOver="window.estatus='';return true">Recursos Humanos</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=produccion"
								title="Revisar Requisiciones Producci&oacute;n"
								onMouseOver="window.estatus='';return true">Producci&oacute;n</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=aseguramientodecalidad"
								title="Revisar Requisiciones Aseguramiento de Calidad"
								onMouseOver="window.estatus='';return true">Aseguramiento de Calidad</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=desarrollo"
								title="Revisar Requisiciones Desarrollo"
								onMouseOver="window.estatus='';return true">Desarrollo</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=mantenimiento"
								title="Revisar Requisiciones Mantenimiento"
								onMouseOver="window.estatus='';return true">Mantenimiento</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=topografia"
								title="Revisar Requisiciones Topograf&iacute;a"
								onMouseOver="window.estatus='';return true">Topograf&iacute;a</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=laboratorio"
								title="Revisar Requisiciones Laboratorio"
								onMouseOver="window.estatus='';return true">Laboratorio</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=seguridadindustrial"
								title="Revisar Requisiciones Seguridad Industrial"
								onMouseOver="window.estatus='';return true">Seguridad Industrial</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=paileria"
								title="Revisar Requisiciones Paileria"
								onMouseOver="window.estatus='';return true">Paileria</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=mttoElectrico"
								title="Revisar Requisiciones Mantenimiento El&eacute;ctrico"
								onMouseOver="window.estatus='';return true">Mantenimiento El&eacute;ctrico</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=clinica"
								title="Revisar Requisiciones Unidad de Salud Ocupacional"
								onMouseOver="window.estatus='';return true">Unidad de Salud Ocupacional</a></li>
						<li class="subfirst"><a href="frm_consultarRequisiciones.php?depto=mainmi"
								title="Revisar Requisiciones MAINMI"
								onMouseOver="window.estatus='';return true">MAINMI</a></li>
					</ul>
				</li>
				<li class="topfirst"><a href="menu_pedidos.php" title="Pedidos"
						onMouseOver="window.estatus='';return true"><span>Pedidos</span></a>
					<ul>
						<!--
					<li class="subfirst"><a href="frm_detallesDelPedido2.php" title="Registrar Pedido" onMouseOver="window.estatus='';
                    return true">Registrar Pedido</a></li>
					-->
						<li class="subfirst"><a href="frm_consultadePedido.php" title="Consultar Pedido" onMouseOver="window.estatus='';
                    return true">Consultar Pedido</a></li>
						<!--<li class="subfirst"><a href="" title="Modificar Pedido" onMouseOver="window.estatus='';
                    return true">Modificar Pedido</a></li>
					<li class="subfirst"><a href="frm_complementarPedido.php" title="Complementar Informaci&oacute;n de Pedido" onMouseOver="window.estatus='';
                    return true">Complementar Pedido</a></li>-->
						<li class="subfirst"><a href="frm_recibirPedidos.php" title="Recibir Pedidos" onMouseOver="window.estatus='';
                    return true">Recibir Pedidos</a></li>
					</ul>
				</li>
				<!--
			<li class="topfirst"><a href="menu_ventas.php" title="Ventas" onMouseOver="window.estatus='';return true"><span>Ventas</span></a>
				<ul>
					<li class="subfirst"><a href="frm_detallesVenta.php" title="Registrar Venta" onMouseOver="window.estatus='';return true">Registrar Venta</a></li>
				</ul>
			</li>
			-->
				<li class="topfirst"><a href="menu_gastos.php" title="Pagos"
						onMouseOver="window.estatus='';return true"><span>Pagos</span></a>
					<ul>
						<li class="subfirst"><a href="frm_registrarGastos.php" title="Registrar Pagos"
								onMouseOver="window.estatus='';return true">Registrar Pagos</a></li>
						<li class="subfirst"><a href="frm_consultarGastos.php" title="Consultar Pagos"
								onMouseOver="window.estatus='';return true">Consultar Pagos</a></li>
					</ul>
				</li>
				<li class="topfirst"><a href="menu_cajachica.php" title="Caja Chica"
						onMouseOver="window.estatus='';return true"><span>Caja Chica</span></a>
					<ul>
						<li class="subfirst"><a href="frm_cajaChica.php" title="Registrar Caja Chica"
								onMouseOver="window.estatus='';return true">Registrar</a></li>
						<li class="subfirst"><a href="frm_consultarCajaChica.php" title="Consultar Caja Chica"
								onMouseOver="window.estatus='';
                    return true">Consultar</a></li>
					</ul>
				</li>
				<!--
			<li class="topfirst"><a href="menu_egresos.php" title="Pagos" onMouseOver="window.estatus='';return true"><span>Pagos</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registrarPagos.php" title="Registrar Pagos" onMouseOver="window.estatus='';return true">Registrar Pago</a></li>
					<li class="subfirst"><a href="frm_consultarPagos.php" title="Consultar Pagos" onMouseOver="window.estatus='';return true">Consultar Pago</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_vales.php" title="Caja Chica" onMouseOver="window.estatus='';return true"><span>Vales</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registrarVale.php" title="Registrar Vales" onMouseOver="window.estatus='';return true">Registrar Vale</a></li>
					<li class="subfirst"><a href="frm_consultarVale.php" title="Consultar Vales" onMouseOver="window.estatus='';return true">Consultar Vale</a></li>
				</ul>
			</li>
			-->
				<li class="topfirst"><a href="menu_reportes.php" title="Reportes"
						onMouseOver="window.estatus='';return true"><span>Reportes</span></a>
					<ul>
						<li class="subfirst"><a href="frm_reporteCompras.php" title="Reporte Compras"
								onMouseOver="window.estatus='';return true">Reporte Compras</a></li>
						<!--
					<li class="subfirst"><a href="frm_reporteVentas.php" title="Reporte Ventas" onMouseOver="window.estatus='';return true">Reporte Ventas</a></li>
					<li class="subfirst"><a href="frm_reporteCompraVenta.php" title="Reporte Compras/Ventas" onMouseOver="window.estatus='';
                    return true">Reporte Compras/Ventas</a></li>
					-->
						<li class="subfirst"><a href="frm_reportePedidos.php" title="Reporte Pedidos"
								onMouseOver="window.estatus='';return true">Reporte Pedidos</a></li>
						<li class="subfirst"><a href="frm_reporteRequisiciones.php" title="Reporte Requisiciones"
								onMouseOver="window.estatus='';return true">Reporte Requisiciones</a></li>
						<li class="subfirst"><a href="frm_reporteOTSE.php" title="Reporte OTSE"
								onMouseOver="window.estatus='';return true">Reporte OTSE</a></li>
					</ul>
				</li>
				<li class="topfirst">
					<a href="menu_junta.php" title="Junta Administrativa"
						onmouseover="window.status=''; return true"><span>Junta
							Administrativa</span></a>
					<ul>
						<li class="subfirst">
							<a href="frm_registrarJunta.php" title="Junta Diaria"
								onmouseover="window.status=''; return true">Junta Diara</a>
						</li>
						<li class="subfirst">
							<a href="frm_consultarJunta.php" title="Consultar Actividades"
								onmouseover="window.status=''; return true">Consultar Actividades</a>
						</li>
					</ul>
				</li>
				<!--
			<li class="topfirst"><a href="menu_formatos.php" title="Formatos" onMouseOver="window.estatus='';return true"><span>Formatos</span></a>
				<ul>
					<li class="subfirst"><a href="frm_formatos.php?doc=pedido" title="Pedido" onMouseOver="window.estatus='';return true">Pedido</a></li>
					<li class="subfirst"><a href="frm_formatos.php?doc=cotizacion" title="Cotizaci&oacute;n" onMouseOver="window.estatus='';
                    return true">Cotizaci&oacute;n</a></li>
					<li class="subfirst"><a href="frm_formatos.php?doc=cheque" title="Cheque" onMouseOver="window.estatus='';return true">Cheque</a></li>
				</ul>
			</li>
			-->
			</ul>
		</div>

		<div id="barra-titulo-mod" style="position:absolute; left:0px; top:51px; width:1035px; height:30px; z-index:6; background-color: #666666; layer-background-color:
     #666666; border: 1px none #000000; visibility: inherit;">
			<img src="../../images/title-bar-bg.gif" width="1035" height="30" />
		</div>

		<div id="usuario" style="position:absolute; left:873px; top:54px; width:156px; height:17; z-index:7">
			<div align="right" class="usr-reg"><?php echo $_SESSION['usr_reg']; ?><img src="../../images/boss-icon.png"
					width="25" height="25" align="absmiddle" /></div>
		</div>

		<div id="chat" style="position:absolute; left:1000px; top:86px; width:29px; height:32px; z-index:8">
			<img src="../../images/msn.png" width="100%" height="100%" title="Escribir Mensaje" align="absmiddle"
				style="cursor:pointer;" onClick="abrirVentanaChat();" />
		</div>

		<div id="modulo" class="usr-reg">M&oacute;dulo de Compras</div>

		<div id="titulo"><span class="titulo-pagina">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y
				Operaci&oacute;n</span></div>
		<div id="fecha" class="fecha" align="right"><?php echo verFecha(1); ?>&nbsp;&nbsp;&nbsp;&nbsp;<label id="reloj"
				class="fecha"></label>
		</div>

		<?php
		if(verificarExiste()){
		?>
		<div id="tc_monext">
			DOLAR: $<?php echo obtenerTC('DOLAR');?>&nbsp;&nbsp;&nbsp;EURO: $<?php echo obtenerTC('EURO');?>
		</div>
		<?php
		}
		?>
	</body>

	<?php
	actividadesAlerta();
	mostrarAlertaJunta("COMPRAS");
	?>
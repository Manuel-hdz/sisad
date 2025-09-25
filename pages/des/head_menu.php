<?php
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	//Modulo de conexion con la base de datos, el cual estara diponible para todas la paginas a trav�s de este archivo (head_menu.php) 
	include("../../includes/conexion.inc");	
	//Manejo de operaciones que consultan datos en la BD y los regresan en el elemento de formulario undicado en los parametros de las funciones
	include("../../includes/op_operacionesBD.php");
	//variable que nos indica el modulo en el cual estamos posicionados; con el objetivo de cambiar al modulo produccion y poder regresar
	$modulo="Gerencia";?>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" href="menu_files/style.css" type="text/css" />

	<script type="text/javascript" src="../../includes/botones.js"></script>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" src="../../includes/maxLength.js"></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js"></script>
	<script type="text/javascript" src="../../includes/validacionProduccion.js"></script>
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
			width: 251px;
			height: 17px;
			z-index: 8;
		}

		#titulo {
			position: absolute;
			left: 288px;
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
	</style>
</head>

<body onLoad="inicio(); setInterval(muestraReloj, 1000);" onkeypress="parar()" onclick="parar()" onmousemove="parar()">
	<div id="dock-new">
		<table width="50" border="0">
			<tr>
				<td width="50" height="50">
					<form action="inicio_desarrollo.php">
						<input type="image" src="../../images/dock/home.png" name="icono1" id="icono1" width="50"
							height="50" border="0" title="Inicio Desarrollo"
							onClick="MM_nbGroup('down','group2','icono1','',1)"
							onMouseOver="MM_nbGroup('over','icono1','../../images/dock/home-over.png','',1); window.status='';return true"
							onMouseOut="MM_nbGroup('out')" />
					</form>
				</td>
				<td width="50" height="50">
					<form action="frm_consultarAlmacen.php">
						<input type="image" src="../../images/dock/alm.png" name="icono2" id="icono2" width="50"
							height="50" border="0" title="Consultar Materiales de Almac&eacute;n"
							onClick="MM_nbGroup('down','group2','icono2','',1)"
							onMouseOver="MM_nbGroup('over','icono2','../../images/dock/alm-over.png','',1); window.status='';return true"
							onMouseOut="MM_nbGroup('out')" />
					</form>
				</td>
				<td width="50" height="50">
					<form action="frm_consultarRH.php">
						<input type="image" src="../../images/dock/rh.png" name="icono6" id="icono6" width="50"
							height="50" border="0" title="Consultar Asistencias de Personal"
							onClick="MM_nbGroup('down','group2','icono6','',1)"
							onMouseOver="MM_nbGroup('over','icono6','../../images/dock/rh-over.png','',1); window.status='';return true"
							onMouseOut="MM_nbGroup('out')" />
					</form>
				</td>
				<td width="50" height="50">
					<form action="frm_consultarTopografia.php">
						<input type="image" src="../../images/dock/top.png" name="icono3" id="icono3" width="50"
							height="50" border="0" title="Consultar Planos de Topograf&iacute;a"
							onClick="MM_nbGroup('down','group2','icono3','',1)"
							onMouseOver="MM_nbGroup('over','icono3','../../images/dock/top-over.png','',1); window.status='';return true"
							onMouseOut="MM_nbGroup('out')" />
					</form>
				</td>
				<td width="50" height="50">
					<form action="frm_consultarMantenimiento.php">
						<input type="image" src="../../images/dock/man.png" name="icono4" id="icono4" width="50"
							height="50" border="0" title="Consultar Bit&aacute;cora de Aceites"
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
			title="Ir a la P�gina Web de Concreto Lanzado de Fresnillo S.A. de C.V."><img src="../../images/logo.png"
				width="151" height="56" border="0" /></a></div>
	<div id="log-off" style="position:absolute; left:992px; top:10px; width:33px; height:34px; z-index:5">
		<form action="../salir.php">
			<input type="image" src="../../images/close.png" width="31" height="31" border="0"
				title="Cerrar Sesi&oacute;n" onMouseOver="window.status='';return true" />
		</form>
	</div>


	<div id="menu" style="position:absolute; left:-15px; top:70px; width:1035px; height:85px; z-index:123;">
		<ul id="css3menu">
			<li class="topfirst"><a href="menu_bitacora.php" title="Bit&aacute;coras de Actividades"
					onMouseOver="window.status='';return true"><span>Bit&aacute;coras</span></a>
				<ul>
					<li class="subfirst"><a href="menu_bitAvance.php" onMouseOver="window.status='';return true"
							title="Avance en Obras"><span>Bit&aacute;cora Avance</span></a>
						<ul>
							<li class="subfirst"><a href="frm_regAvance.php" onMouseOver="window.status='';return true"
									title="Registrar Avance de Obras en la Bit&aacute;cora">
									Registrar Avance</a>
							</li>
							<li class="subfirst"><a href="frm_modAvance.php" onMouseOver="window.status='';return true"
									title="Modificar Registro de Avance de Obras de la Bit&aacute;cora">
									Modificar Avance</a>
							</li>
						</ul>
					</li>
					<li class="subfirst"><a href="menu_bitUtilitario.php" onMouseOver="window.status='';return true"
							title="Equipo Tipo Retroexcavadora y Bulldozer">
							<span>Bit&aacute;cora Equipo Utilitario</span></a>
						<ul>
							<li class="subfirst"><a href="frm_regBitUtilitario.php"
									onMouseOver="window.status='';return true"
									title="Registrar Movimiento con Retroexcavadora y/o Bulldozer en la Bit&aacute;cora">Registrar
									Movimiento</a></li>
							<li class="subfirst"><a href="frm_modBitUtilitario.php"
									onMouseOver="window.status='';return true"
									title="Modificar Registro de Movimiento con Retroexcavadora y/o Bulldozer de la Bit&aacute;cora">Modificar
									Transporte</a></li>
						</ul>
					</li>
					<li class="subfirst"><a href="frm_gestionarObras.php" onMouseOver="window.status='';return true"
							title="Gestionar Obras de Desarrollo">
							Gestionar Obras</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_presupuesto.php" title="Avance en Metros Presupuestados"
					onMouseOver="window.status='';return true"><span>Presupuesto</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registrarPresupuesto.php"
							onMouseOver="window.status='';return true" title="Registrar Presupuesto Planeado">Registrar
							Presupuesto</a></li>
					<li class="subfirst"><a href="frm_modificarPresupuesto.php"
							onMouseOver="window.status='';return true" title="Modificar Presupuesto Planeado">Modificar
							Presupuesto</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_servicios.php" title="Servicios Realizados a Minera Fresnillo"
					onMouseOver="window.status='';return true"><span>Servicios</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registrarServicios.php" onMouseOver="window.status='';return true"
							title="Registrar Servicios Realizados a Minera Fresnillo">Registrar Servicios</a></li>
					<li class="subfirst"><a href="frm_modificarServicios.php" onMouseOver="window.status='';return true"
							title="Modificar Servicios Realizados a Minera Fresnillo">Modificar Servicios</a></li>
				</ul>
			</li>
			<!--
				<li class="topfirst"><a href="menu_sueldos.php" title="Sueldos, Bonificaciones y N&oacute;mina"
					onMouseOver="window.status='';return true"><span>Sueldos</span></a>
				<ul>
					<li class="subfirst"><a href="frm_catSueldos.php" onMouseOver="window.status='';return true"
					title="Actualizar Cat&aacute;logo de Sueldos">Cat&aacute;logo Sueldos</a></li>
					
					<li class="subfirst"><a href="frm_catIncentivos.php" onMouseOver="window.status='';return true" 
					title="Actualizar Cat&aacute;logo de Incentivos">Incentivos</a></li>
					<li class="subfirst"><a href="frm_registrarNomina.php" onMouseOver="window.status='';return true"
							title="Registrar Pago de N&oacute;mina">N&oacute;mina</a></li>
				</ul>
				</li>
			-->
			<li class="topfirst"><a href="menu_requisiciones.php" title="Requisiciones de Desarrollo"
					onMouseOver="window.status='';return true"><span>Requisici&oacute;n</span></a>
				<ul>
					<!-- 
					<li class="subfirst"><a href="menu_requisiciones2.php" title="Autorizar una Requisici&oacute;n" onMouseOver="window.status='';return true"><span>Autorizar Requisici&oacute;n</span></a>
						<ul>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=almacen" title="Revisar Requisiciones Almacen" 
							onMouseOver="window.estatus='';return true">Almacen</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=gerenciatecnica" title="Revisar Requisiciones Gerencia T&eacute;cnica" 
							onMouseOver="window.estatus='';return true">Gerencia T&eacute;cnica</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=recursoshumanos" title="Revisar Requisiciones Recursos" 
							onMouseOver="window.estatus='';return true">Recursos Humanos</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=produccion" title="Revisar Requisiciones Producci&oacute;n" 
							onMouseOver="window.estatus='';return true">Producci&oacute;n</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=aseguramientodecalidad" title="Revisar Requisiciones Aseguramiento de Calidad" 
							onMouseOver="window.estatus='';return true">Aseguramiento de Calidad</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=desarrollo" title="Revisar Requisiciones Desarrollo" 
							onMouseOver="window.estatus='';return true">Desarrollo</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=mantenimiento" title="Revisar Requisiciones Mantenimiento" 
							onMouseOver="window.estatus='';return true">Mantenimiento</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=topografia" title="Revisar Requisiciones Topograf&iacute;a" 
							onMouseOver="window.estatus='';return true">Topograf&iacute;a</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=laboratorio" title="Revisar Requisiciones Laboratorio" 
							onMouseOver="window.estatus='';return true">Laboratorio</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=seguridadindustrial" title="Revisar Requisiciones Seguridad Industrial" 
							onMouseOver="window.estatus='';return true">Seguridad Industrial</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=paileria" title="Revisar Requisiciones Paileria" 
							onMouseOver="window.estatus='';return true">Paileria</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=mttoElectrico" title="Revisar Requisiciones Mantenimiento El&eacute;ctrico" 
							onMouseOver="window.estatus='';return true">Mantenimiento El&eacute;ctrico</a></li>
							<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=clinica" title="Revisar Requisiciones Unidad de Salud Ocupacional" 
							onMouseOver="window.estatus='';return true">Unidad de Salud Ocupacional</a></li>	
						</ul>
					</li>
					-->
					<li class="subfirst"><a href="frm_autorizarRequisicion.php"
							title="Autorizar una Requisici&oacute;n de Recursos Humanos"
							onMouseOver="window.status='';return true">
							Autorizar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_generarRequisicion.php"
							title="Generar una Requisici&oacute;n de Desarrollo"
							onMouseOver="window.status='';return true">Generar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_consultarRequisiciones.php"
							title="Consultar las Requisiciones de Desarrollo"
							onMouseOver="window.status='';return true">Consultar Requisiciones</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_reportes.php" title="Reportes"
					onMouseOver="window.status='';return true"><span>Reportes</span></a>
				<ul>
					<!-- <li class="subfirst"><a href="frm_reporteRezagado.php" title="Reporte de Rezagado con Scoop Tram"
							onMouseOver="window.status='';return true">Reporte Rezagado</a></li> -->
					<li class="subfirst"><a href="frm_reporteUtilitario.php" title="Reporte de Equipo Utilitario"
							onMouseOver="window.status='';return true">Reporte Equipo Utilitario</a></li>
					<!-- <li class="subfirst"><a href="frm_reporteBarrenacion.php"
							title="Reporte de Barrenaci&oacute;n con Jumbo y/o M&aacute;quina de Pierna"
							onMouseOver="window.status='';return true">Reporte Barrenaci&oacute;n</a></li>
					<li class="subfirst"><a href="frm_reporteVoladuras.php" title="Reporte de Voladuras"
							onMouseOver="window.status='';return true">Reporte Voladuras</a></li>
					<li class="subfirst"><a href="frm_reporteAvance.php" title="Reporte de Avance"
							onMouseOver="window.status='';return true">Reporte Avance</a></li> -->
					<li class="subfirst"><a href="frm_reporteServicios.php" title="Reporte de Servicios"
							onMouseOver="window.status='';return true">Reporte Servicios</a></li>
					<!-- <li class="subfirst"><a href="frm_reporteAyudante.php" title="Reporte de Ayudante General"
							onMouseOver="window.status='';return true">Reporte Ayudante General</a></li>
					<li class="subfirst"><a href="frm_reporteNomina.php" title="Reporte de N&oacute;mina"
							onMouseOver="window.status='';return true">Reporte N&oacute;mina</a></li> -->
					<li class="subfirst"><a href="frm_consultarKardexChecador.php" title="Reporte Checador"
							onMouseOver="window.status='';return true">Reporte Checador</a></li>
					<li class="subfirst"><a href="frm_reporteSalidas.php" title="Reporte Salidas Almacen"
							onMouseOver="window.status='';return true">Reporte Salidas Almacen</a></li>
					<li class="subfirst"><a href="frm_reporteRequisiciones.php" title="Reporte Requisiciones"
							onMouseOver="window.status='';return true">Reporte Requisiciones</a></li>
				</ul>
			</li>
			<!-- <li class="topfirst"><a href="menu_turnos.php"
					title="Administraci&oacute;n de Turnos y Rolamiento de Personal"
					onMouseOver="window.status='';return true"><span>Turnos</span></a>
				<ul>
					<li class="subfirst"><a href="frm_asignarRoles.php" title="Organizar Roles de Trabajo"
							onMouseOver="window.status='';return true">Roles de Trabajo</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="" title="PUEBLE"
					onMouseOver="window.status='';return true"><span>Pueble</span></a>
				<ul>
					<li class="subfirst"><a href="" title="Registrar Pueble"
							onMouseOver="window.status='';return true">Registrar Pueble</a></li>
					<li class="subfirst"><a href="" title="Consultar Pueble"
							onMouseOver="window.status='';return true">Consultar Pueble</a></li>
					<li class="subfirst"><a href="" title="Modificar Pueble"
							onMouseOver="window.status='';return true">Modificar Pueble</a></li>
				</ul>
			</li> -->
		</ul>
	</div>

	<div id="barra-titulo-mod"
		style="position:absolute; left:0px; top:51px; width:1035px; height:30px; z-index:6; background-color: #666666; layer-background-color: #666666; border: 1px none #000000; visibility: inherit;">
		<img src="../../images/title-bar-bg.gif" width="1035" height="30" />
	</div>
	<div id="usuario" style="position:absolute; left:840px; top:54px; width:189px; height:17; z-index:7">
		<div align="right" class="usr-reg"><?php echo $_SESSION['usr_reg']; ?><img src="../../images/boss-icon.png"
				width="25" height="25" align="absmiddle" /></div>
	</div>

	<div id="chat" style="position:absolute; left:1000px; top:86px; width:29px; height:32px; z-index:8">
		<img src="../../images/msn.png" width="100%" height="100%" title="Escribir Mensaje" align="absmiddle"
			style="cursor:pointer;" onClick="abrirVentanaChat();" />
	</div>

	<div id="modulo" class="usr-reg">M&oacute;dulo de Desarrollo</div>

	<div id="titulo"><span class="titulo-pagina">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y
			Operaci&oacute;n</span></div>
	<div id="fecha" class="fecha" align="right"><?php echo verFecha(1); ?>&nbsp;&nbsp;&nbsp;&nbsp;
		<label id="reloj" class="fecha"></label></div>
</body>
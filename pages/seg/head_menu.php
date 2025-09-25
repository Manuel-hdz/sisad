<?php
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	//Modulo de conexion con la base de datos, el cual estara diponible para todas la paginas a través de este archivo (head_menu.php) 
	include("../../includes/conexion.inc");	
	//Manejo de operaciones que consultan datos en la BD y los regresan en el elemento de formulario undicado en los parametros de las funciones
	include("../../includes/op_operacionesBD.php");
	//variable que nos indica el modulo en el cual estamos posicionados; con el objetivo de cambiar al modulo produccion y poder regresar
	$modulo="Seguridad";?>
<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" href="menu_files/style.css" type="text/css" />
	<script type="text/javascript" src="../../includes/botones.js"></script>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js" ></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/funcionesJS.js" ></script>
	<script type="text/javascript" src="../../includes/reloj.js" ></script>				
	
	<script type="text/javascript" src="../../includes/chat/abrirChat.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/monitorMensajes.js"></script>
	<script type="text/javascript" language="javascript">
		setInterval(msjesNuevos, 2500);
	</script>
	
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>

	<style type="text/css">
		<!--		
		.titulo-pagina {font-family: MicrogrammaDMedExt; color: #33761B; font-size: 13px; font-weight: bold; }
		.usr-reg {color: #FFFFFF; font-weight: bold; font-family: MicrogrammaDMedExt; font-size: 12px; }
		#modulo {position:absolute; left:6px; top:56px; width:307px; height:17px; z-index:8; }
		#titulo {position:absolute;left:243px;top:15px;width:528px;height:19px;z-index:3;}
		#fecha {position:absolute; left:380px; top:59px; width:430px; height:18px;z-index:9;}
		.fecha {font-family: MicrogrammaDMedExt; font-size: 12px;color:#FFFFFF;font-weight:bold;}		
		#dock-new { position:absolute; width:173px; height:50px; z-index:124; left: 0; top: 0; }
		-->
	</style>
</head>

<body onLoad="inicio(); setInterval(muestraReloj, 1000);" onkeypress="parar()" onclick="parar()" onmousemove="parar()">
	<div id="dock-new">
  	<table width="50" border="0">    			
		<tr>
      		<td width="50" height="50">						
				<form action="inicio_seguridad.php">						
					<input type="image" src="../../images/dock/home.png" name="icono1" id="icono1" width="50" height="50" border="0" title="Inicio Seguridad"					
					onClick="MM_nbGroup('down','group2','icono1','',1)" 
					onMouseOver="MM_nbGroup('over','icono1','../../images/dock/home-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')" />
				</form>
			</td>		
			<td>
			<form action="frm_seleccionarConsulta.php">						
					<input type="image" src="../../images/dock/cons-ext.png" name="icono2" id="icono2" width="50" height="50" border="0" title="Consultas Externas"					
					onClick="MM_nbGroup('down','group2','icono2','',2)" 
					onMouseOver="MM_nbGroup('over','icono2','../../images/dock/cons-ext-over.png','',2); window.status='';return true" onMouseOut="MM_nbGroup('out')" />
				</form>
			</td>							     
    	</tr>
  	</table>
</div>

	<div id="fondo-title" style="position:absolute; left:0px; top:0px; width:656px; height:52px; z-index:1">
		<img src="../../images/dock/dock-bg2.gif" width="1035" height="50"  />
	</div>
	<div id="logo" style="position:absolute; left:831px; top:-1px; width:154px; height:54px; z-index:4">
		<a href="http://www.concretolanzadodefresnillo.com" target="_blank" title="Ir a la Página Web de Concreto Lanzado de Fresnillo S.A. de C.V.">
		<img src="../../images/logo.png" width="151" height="56" border="0" /></a>
	</div>
	<div id="log-off" style="position:absolute; left:992px; top:10px; width:33px; height:34px; z-index:5">
		<form action="../salir.php">
			<input type="image" src="../../images/close.png" width="31" height="31" border="0" title="Cerrar Sesi&oacute;n"
			onMouseOver="window.status='';return true" />
		</form>
	</div>

	<div id="menu" style="position:absolute; left:-15px; top:70px; width:1035px; height:85px; z-index:123;">
		<ul id="css3menu"> 
			<li class="topfirst"><a href="menu_repositorio.php" onMouseOver="window.status='';return true"  title="Repositorio de Documentos">Repositorio</a>
				<ul>
					<li class="subfirst">
						<a href="frm_agregarDocumento.php" onMouseOver="window.status='';return true" title="Cargar Documentos al Repositorio">Cargar Documentos</a>
					</li>
					<li class="subfirst">
						<a href="frm_modificarDocumentos.php" onMouseOver="window.status='';return true" title="Modificar Documentos del Repositorio">Modificar Documentos</a>
					</li>
					
				</ul>
			</li>
				<li class="topfirst"><a href="menu_recordatorios.php" onMouseOver="window.status='';return true"  title="Recordatorios">Recordatorios</a>
				<ul>
					<li class="subfirst">
						<a href="frm_registrarRecordatorio.php" onMouseOver="window.status='';return true" 
						title="Registrar Recordatorio">Registrar Recordatorios</a>	
					</li>	
					<li class="subfirst">
						<a href="frm_modificarRecordatorio.php" onMouseOver="window.status='';return true" 
						title="Modificar Registro de Recordatorio">Modificar Recordatorio</a>	
					</li>					
				</ul>
			</li>
			<li class="topfirst"><a href="menu_bitacora.php" onMouseOver="window.status='';return true"  title="Bit&aacute;cora de Residuos">Bit&aacute;cora</a>
				<ul>
					<li class="subfirst">
						<a href="frm_registrarBitacora.php" onMouseOver="window.status='';return true" 
						title="Registrar Bitacora de Residuos">Registrar Bitacora de Residuos</a>	
					</li>	
					<li class="subfirst">
						<a href="frm_modificarBitacora.php" onMouseOver="window.status='';return true" 
						title="Modificar Bitacora de Residuos">Modificar Bitacora de Residuos</a>	
					</li>					
				</ul>
			</li>
			<li class="topfirst"><a href="menu_seguridad.php" onMouseOver="window.status='';return true"  title="Seguridad">Seguridad</a>
				<ul>
					<li class="subfirst">
						<a href="menu_actaSeguridadHigiene.php" onMouseOver="window.status='';return true" 
						title="Acta Comision Seguridad e Higiene"><span>Acta Comision Seguridad e Higiene</span></a>
						<ul>
							<li class="subfirst"><a href="frm_registrarActaSeguridadHigiene.php" onMouseOver="window.status='';return true" 
							title="Generar Acta Comision Seguridad e Higiene">Generar Acta de Comision</a></li>
							<li class="subfirst"><a href="frm_consultarActaSeguridadHigiene.php" onMouseOver="window.status='';return true"
							title="Consultar Acta de Comision Seguridad e Higiene">Consultar de Acta Comision</a>
							</li>
						</ul>
					</li>
					<li class="subfirst">
						<a href="menu_recorridosSeguridad.php" onMouseOver="window.status='';return true" 
						title="Recorridos de Diarios de Seguridad"><span>Recorridos Diarios de Seguridad</span></a>
						<ul>
							<li class="subfirst"><a href="frm_registrarRecSeg.php" onMouseOver="window.status='';return true" 
							title="Registrar Recorridos Diarios Seguridad">Registrar Recorridos Seguridad</a></li>
							<li class="subfirst"><a href="frm_modificarRecSeg.php" onMouseOver="window.status='';return true"
							title="Modificar Recorridos Diarios Seguridad">Modificar Recorridos Seguridad</a>
							</li>
						</ul>
					</li>	
					<li class="subfirst">
						<a href="menu_planContingencia.php" onMouseOver="window.status='';return true" 
						title="Planes de Contingencia"><span>Planes de Contingencia</span></a>
						<ul>
							<li class="subfirst"><a href="frm_planesContingencia.php" onMouseOver="window.status='';return true" 
							title="Registrar Plan de Contingencia">Registrar Plan Contingencia</a></li>
							<li class="subfirst"><a href="frm_modificarPlanContingencia.php" onMouseOver="window.status='';return true"
							title="Modificar Plan de Contingencia">Modificar Plan Contingencia</a>
						</ul>
					</li>	
					<li class="subfirst">
						<a href="menu_actaIncidentesAccidentes.php" onMouseOver="window.status='';return true"  
						title="Informe Incidentes/Accidentes"><span>Informe Incidentes/Accidentes</span></a>
						<ul>
							<li class="subfirst"><a href="frm_registrarActaIncidentesAccidentes.php" onMouseOver="window.status='';return true" 
							title="Generar Informe de Incidentes/Accidentes">Generar Informe Incidentes/Accidentes</a></li>
							<li class="subfirst"><a href="frm_consultarActaIncidentesAccidentes.php" onMouseOver="window.status='';return true"
							title="Consultar Informe Incidentes/Accidentes">Consultar Informe Incidentes/Accidentes</a>
							</li>
						</ul>
					</li>
					<li class="subfirst">
						<a href="frm_registrarTiempoVidaES.php" onMouseOver="window.status='';return true"  
						title="Información Tiempo Vida Util del Equipo Seguridad">Tiempo Vida Equipo Seguridad</a>
					</li>						
				</ul>
			</li>
			<li class="topfirst"><a href="menu_permisos.php" title="Generaci&oacute;n de Permisos Especiales" 
				onMouseOver="window.status='';return true"><span>Permisos</span></a>
				<ul>
					<li class="subfirst"><a href="frm_seleccionarPermiso.php" title="Generar Permisos Especiales" 
						onMouseOver="window.status='';return true">Generar Permisos</a>
					</li>
					<li class="subfirst">
						<a href="frm_consultarPermisos.php" title="Consultar Permisos Generados" 
						onMouseOver="window.status='';return true">Consultar Permisos</a>
					</li>
				</ul>
			</li>		
			<li class="topfirst"><a href="menu_requisiciones.php" title="Requisiciones de Seguridad Industrial" 
				onMouseOver="window.status='';return true"><span>Requisici&oacute;n</span></a>
				<ul>
					<li class="subfirst"><a href="frm_autorizarRequisicion.php" title="Autorizar una Requisici&oacute;n de Recursos Humanos" onMouseOver="window.status='';return true">
					Autorizar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_generarRequisicion.php" title="Generar una Requisici&oacute;n de Seguridad Industrial" 
						onMouseOver="window.status='';return true">Generar Requisici&oacute;n</a>
					</li>
					<li class="subfirst">
						<a href="frm_consultarRequisiciones.php" title="Consultar las Requisiciones de Seguridad Industrial" 
						onMouseOver="window.status='';return true">Consultar Requisiciones</a>
					</li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_reportes.php" title="Reportes" onMouseOver="window.status='';return true"><span>Reportes</span></a>
				<ul>
					<li class="subfirst">
						<a href="frm_reporteIncidentesAccidentes.php" title="Reporte Informe de Incidentes/Accidentes" 
						onMouseOver="window.status='';return true">Reporte Informe Incidentes/Accidentes</a>
					</li>					
					<li class="subfirst">
						<a href="frm_reportePlanContingencia.php" title="Reporte Plan de Contingencia" 
						onMouseOver="window.status='';return true">Reporte Plan de Contingencia</a>
					</li>
					<li class="subfirst"><a href="frm_reporteRequisiciones.php" title="Reporte Requisiciones" onMouseOver="window.status='';return true">Reporte Requisiciones</a></li>
				</ul>
			</li>
	  </ul>
	</div>	

	<div id="barra-titulo-mod" style="position:absolute; left:0px; top:51px; width:1035px; height:30px; z-index:6; background-color: #666666; layer-background-color: #666666; border: 1px none #000000; visibility: inherit;">
		<img src="../../images/title-bar-bg.gif" width="1035" height="30" />	
	</div>
	<div id="usuario" style="position:absolute; left:840px; top:54px; width:189px; height:17; z-index:7">  
  		<div align="right" class="usr-reg" onClick="cambiarUsuario('<?php echo $_SESSION['usr_reg'];?>','<?php echo $modulo?>');"><?php echo $_SESSION['usr_reg']; ?><img src="../../images/boss-icon.png" width="25" height="25" align="absmiddle" /></div>
	</div>

	<div id="modulo" class="usr-reg">M&oacute;dulo de Seguridad Industrial</div>
	
	<div id="chat" style="position:absolute; left:1000px; top:86px; width:29px; height:32px; z-index:8">  
  		<img src="../../images/msn.png" width="100%" height="100%" title="Escribir Mensaje" align="absmiddle" style="cursor:pointer;" onClick="abrirVentanaChat();"/>
	</div>

	<div id="titulo"><span class="titulo-pagina">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</span></div>
<div id="fecha" class="fecha" align="right"><?php echo verFecha(1); ?>&nbsp;&nbsp;&nbsp;&nbsp;
<label id="reloj" class="fecha"></label></div>
</body>
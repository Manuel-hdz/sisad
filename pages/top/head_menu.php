<?php
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	//Modulo de conexion con la base de datos, el cual estara diponible para todas la paginas a través de este archivo (head_menu.php) 
	include("../../includes/conexion.inc");	
	//Manejo de operaciones que consultan datos en la BD y los regresan en el elemento de formulario undicado en los parametros de las funciones
	include("../../includes/op_operacionesBD.php");
?>

<head>
	
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" href="menu_files/style.css" type="text/css" />
	<script type="text/javascript" src="../../includes/botones.js"></script>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js" ></script>
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
		.usr-reg { color: #FFFFFF; font-weight: bold; font-family: MicrogrammaDMedExt; font-size: 12px; }
		#modulo {position:absolute; left:6px; top:56px; width:200px; height:17px; z-index:8; }
		#titulo {position:absolute; left:282px; top:15px; width:528px; height:22px; z-index:3;}
		#fecha {position:absolute; left:380px; top:59px; width:430px; height:18px;z-index:9;}
		.fecha {font-family: MicrogrammaDMedExt; font-size: 12px;color:#FFFFFF;font-weight:bold;}		
		#dock-new { position:absolute; width:275px; height:50px; z-index:124; left: 0; top: 0; }
		-->
	</style>
</head>

<body onLoad="inicio(); setInterval(muestraReloj, 1000);" onkeypress="parar()" onclick="parar()" onmousemove="parar()">
	<div id="dock-new">
  	<table width="50" border="0">    			
		<tr>
      		<td width="50" height="50">						
				<form action="inicio_topografia.php">						
					<input type="image" src="../../images/dock/home.png" name="icono1" id="icono1" width="50" height="50" border="0" title="Inicio Topograf&iacute;a"					
					onClick="MM_nbGroup('down','group2','icono1','',1)" 
					onMouseOver="MM_nbGroup('over','icono1','../../images/dock/home-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')" />
				</form>
			</td>
			<td width="50" height="50">
				<form action="frm_consultarMateriales.php">	
					<input type="image" src="../../images/dock/alm.png" name="icono2" id="icono2" width="50" height="50" border="0" title="Consultar Stock Almac&eacute;n"
					onClick="MM_nbGroup('down','group2','icono2','',1)" 
					onMouseOver="MM_nbGroup('over','icono2','../../images/dock/alm-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')" />					
				</form>		
			</td>						     
    	</tr>
  	</table>
	</div>

	<div id="fondo-title" style="position:absolute; left:0px; top:0px; width:656px; height:52px; z-index:1"><img src="../../images/dock/dock-bg2.gif" width="1035" height="50"  /></div>
	<div id="logo" style="position:absolute; left:831px; top:-1px; width:154px; height:54px; z-index:4"><a href="http://www.concretolanzadodefresnillo.com" target="_blank" title="Ir a la Página Web de Concreto Lanzado de Fresnillo S.A. de C.V."><img src="../../images/logo.png" width="151" height="56" border="0" /></a></div>
	<div id="log-off" style="position:absolute; left:992px; top:10px; width:33px; height:34px; z-index:5">
		<form action="../salir.php">
			<input type="image" src="../../images/close.png" width="31" height="31" border="0" title="Cerrar Sesi&oacute;n" onMouseOver="window.status='';return true" />
		</form>
	</div>

	<div id="menu" style="position:absolute; left:-15px; top:70px; width:1035px; height:85px; z-index:123;"> 
		<ul id="css3menu"> 
			<li class="topfirst"><a href="menu_planos.php" onMouseOver="window.status='';return true"  title="Planos"><span>Planos</span></a>
				<ul>
					<li class="subfirst"><a href="frm_agregarPlanos.php" onMouseOver="window.status='';return true" title="Cargar Plano">Cargar Plano</a></li>
					<li class="subfirst"><a href="frm_eliminarPlanos.php" onMouseOver="window.status='';return true"  title="Eliminar Plano">Eliminar Plano</a></li>
					<li class="subfirst"><a href="frm_consultarPlanos.php" onMouseOver="window.status='';return true"  title="Consultar Plano">Consultar Plano</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_obras.php" onMouseOver="window.status='';return true"  title="Obras"><span>Obras</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registrarObra.php" onMouseOver="window.status='';return true" title="Registrar Obra">Registrar Obra</a></li>
					<li class="subfirst"><a href="frm_modificarNombreObra.php" onMouseOver="window.status='';return true"  title="Modificar Obras">Modificar Obras</a></li>	
					<li class="subfirst"><a href="frm_consultarObra.php" onMouseOver="window.status='';return true"  title="Consultar Obras">Consultar Obras</a></li>							
				</ul>
			</li>
			<li class="topfirst"><a href="menu_equipos.php" onMouseOver="window.status='';return true"  title="Equipos"><span>Obras con Equipo</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registrarEqPesado.php" onMouseOver="window.status='';return true" title="Registrar Obras con Equipo Pesado">Registrar Obras Equipo</a></li>
					<li class="subfirst"><a href="frm_modificarEqPesado.php" onMouseOver="window.status='';return true"  title="Modificar Obras con Equipo Pesado">Modificar Obras Equipo</a></li>	
					<li class="subfirst"><a href="frm_consultarEqPesado.php" onMouseOver="window.status='';return true"  title="Consultar Obras con Equipo Pesado">Consultar Obras Equipo</a></li>							
				</ul>
			</li>
			<li class="topfirst"><a href="menu_estimaciones.php" onMouseOver="window.status='';return true"  title="Estimaciones"><span>Estimaciones</span></a>
				<ul>
					<li class="subfirst"><a href="frm_generarEstimacion.php" onMouseOver="window.status='';return true" title="Generar Estimaci&oacute;n">Registrar Estimaci&oacute;n</a></li>
					<li class="subfirst"><a href="frm_eliminarEstimacion.php" onMouseOver="window.status='';return true"  title="Eliminar Estimaci&oacute;n">Eliminar Estimaci&oacute;n</a></li>
					<li class="subfirst"><a href="frm_modificarEstimacion.php" onMouseOver="window.status='';return true"  title="Modificar Estimaci&oacute;n">Modificar Estimaci&oacute;n</a></li>
					<li class="subfirst"><a href="frm_consultarEstimacion.php" onMouseOver="window.status='';return true"  title="Consultar Estimaci&oacute;n">Consultar Estimaci&oacute;n</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_traspaleo.php" onMouseOver="window.status='';return true"  title="Traspaleo"><span>Traspaleo</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registrarTraspaleo.php" onMouseOver="window.status='';return true" title="Generar Traspaleo">Registrar Traspaleo</a></li>
					<li class="subfirst"><a href="frm_eliminarTraspaleo.php" onMouseOver="window.status='';return true"  title="Eliminar Traspaleo">Eliminar Traspaleo</a></li>
					<li class="subfirst"><a href="frm_modificarTraspaleo.php" onMouseOver="window.status='';return true"  title="Modificar Traspaleo">Modificar Traspaleo</a></li>
					<li class="subfirst"><a href="frm_consultarTraspaleo.php" onMouseOver="window.status='';return true"  title="Consultar Traspaleo">Consultar Traspaleo</a></li>
					<li class="subfirst"><a href="frm_listaPrecios.php" onMouseOver="window.status='';return true"  title="Lista de Precios">Lista de Precios</a></li>                    
				</ul>
			</li>
			<li class="topfirst"><a href="menu_equipoPesado.php" onMouseOver="window.status='';return true"  title="Movimientos de Equipo Pesado"><span>Equipo Pesado</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registrarEquipoPesado.php" onMouseOver="window.status='';return true" title="Registrar Movimientos de Equipo Pesado">Registrar Equipo Pesado</a></li>
					<li class="subfirst"><a href="frm_eliminarEquipoPesado.php" onMouseOver="window.status='';return true"  title="Eliminar Movimientos de Equipo Pesado">Eliminar Equipo Pesado</a></li>
					<li class="subfirst"><a href="frm_modificarEquipoPesado.php" onMouseOver="window.status='';return true"  title="Modificar Movimientos de Equipo Pesado">Modificar Equipo Pesado</a></li>
					<li class="subfirst"><a href="frm_consultarEquipoPesado.php" onMouseOver="window.status='';return true"  title="Consultar Movimientos de Equipo Pesado">Consultar Equipo Pesado</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_conciliaciones.php" title="Conciliaci&oacute;n" onMouseOver="window.status='';return true"><span>Conciliaci&oacute;n</span></a>
				<ul>
					<li class="subfirst"><a href="frm_consultarConciliacion.php" title="Consultar Conciliaci&oacute;n" onMouseOver="window.status='';return true">Consultar Conciliaci&oacute;n</a></li>					
				</ul>
			</li>	
			<li class="topfirst"><a href="menu_requisiciones.php" title="Requisiciones de Topograf&iacute;a" onMouseOver="window.status='';return true"><span>Requisici&oacute;n</span></a>
				<ul>
					<li class="subfirst"><a href="frm_autorizarRequisicion.php" title="Autorizar una Requisici&oacute;n de Recursos Humanos" onMouseOver="window.status='';return true">
					Autorizar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_generarRequisicion.php" title="Generar una Requisici&oacute;n de Topograf&iacute;a" onMouseOver="window.status='';return true">Generar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_consultarRequisiciones.php" title="Consultar las Requisiciones de Topograf&iacute;a" onMouseOver="window.status='';return true">Consultar Requisiciones</a></li>
					<li class="subfirst"><a href="frm_reporteRequisiciones.php" title="Reporte Requisiciones" onMouseOver="window.status='';return true">Reporte Requisiciones</a></li>
				</ul>
			</li>			
		</ul>
	</div>	

	<div id="barra-titulo-mod" style="position:absolute; left:0px; top:51px; width:1035px; height:30px; z-index:6; background-color: #666666; layer-background-color: #666666; border: 1px none #000000; visibility: inherit;">
		<img src="../../images/title-bar-bg.gif" width="1035" height="30" />	
	</div>

	<div id="usuario" style="position:absolute; left:840px; top:54px; width:189px; height:17; z-index:7">  
  		<div align="right" class="usr-reg"><?php echo $_SESSION['usr_reg']; ?><img src="../../images/boss-icon.png" width="25" height="25" align="absmiddle" /></div>
	</div>

	<div id="modulo" class="usr-reg">M&oacute;dulo de Topograf&iacute;a</div>
	
	<div id="chat" style="position:absolute; left:1000px; top:86px; width:29px; height:32px; z-index:8">  
  		<img src="../../images/msn.png" width="100%" height="100%" title="Escribir Mensaje" align="absmiddle" style="cursor:pointer;" onClick="abrirVentanaChat();"/>
	</div>

	<div id="titulo"><span class="titulo-pagina">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</span></div>
	<div id="fecha" class="fecha" align="right"><?php echo verFecha(1); ?>&nbsp;&nbsp;&nbsp;&nbsp;<label id="reloj" class="fecha"></label></div>
</body>
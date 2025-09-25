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
	<?php //Funciones de LightBox?>
	<link rel="stylesheet" href="../../includes/lightbox/css/lightbox.css" type="text/css" media="screen" />
	<script src="../../includes/lightbox/js/prototype.js" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/lightbox.js" type="text/javascript"></script>
	
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
	
	<script type="text/javascript" src="includes/ajax/borrarHistorial.js"></script>	
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
		.titulo-pagina {font-family: MicrogrammaDMedExt; color: #CCCCCC; font-size: 13px; font-weight: bold; }
		.usr-reg { color: #FFFFFF; font-weight: bold; font-family: MicrogrammaDMedExt; font-size: 12px; }
		#modulo {position:absolute; left:6px; top:56px; width:255px; height:17px; z-index:8; }
		#titulo {position:absolute; left:282px; top:15px; width:549px; height:22px; z-index:3;}
		#fecha {position:absolute; left:380px; top:60px; width:430px; height:18px;z-index:9;}
		.fecha {font-family: MicrogrammaDMedExt; font-size: 12px; color: #000000;}		
		#dock-new { position:absolute; width:275px; height:50px; z-index:124; left: 0; top: 0; }
		.Estilo4 {font-size: 13px;font-family: MicrogrammaDMedExt;color: #FFFFFF;font-weight: bold;}
		-->
	</style>
</head>

<body onLoad="inicio(); setInterval(muestraReloj, 1000);" onkeypress="parar()" onclick="parar()" onmousemove="parar()">
	<div id="dock-new">
  	<table width="50" border="0">    			
		<tr>
      		<td width="50" height="50">						
				<form action="inicio_direccion.php">						
					<input type="image" src="../../images/dock/home.png" name="icono1" id="icono1" width="50" height="50" border="0" title="Inicio Direcci&oacute;n General"					
					onClick="MM_nbGroup('down','group2','icono1','',1)" 
					onMouseOver="MM_nbGroup('over','icono1','../../images/dock/home-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')" />
				</form>
			</td>
    	</tr>
  	</table>
	</div>
	<div id="fondo-title" style="position:absolute; left:0px; top:0px; width:656px; height:52px; z-index:1"><img src="../../images/dock/dock-bg3.gif" width="1035" height="50"  /></div>
	<div id="logo" style="position:absolute; left:831px; top:-1px; width:154px; height:54px; z-index:4"><a href="http://www.concretolanzadodefresnillo.com" target="_blank" title="Ir a la Página Web de Concreto Lanzado de Fresnillo S.A. de C.V."><img src="../../images/logo.png" width="151" height="56" border="0" /></a></div>
	<div id="log-off" style="position:absolute; left:992px; top:10px; width:33px; height:34px; z-index:5">
		<form action="../salirGerencia.php">
			<input type="image" src="../../images/close.png" width="31" height="31" border="0" title="Cerrar Sesi&oacute;n" onMouseOver="window.status='';return true" />
		</form>
	</div>


	<div id="menu" style="position:absolute; left:-15px; top:70px; width:1035px; height:85px; z-index:123;"> 
		<ul id="css3menu">
			<li class="topfirst"><a href="menu_financiero.php" onMouseOver="window.status='';return true" title="Control Financiero de Gerencia General"><span>Control Financiero</span></a>
				<ul>
					<li class="subfirst"><a href="frm_regMovFin.php?id_pto=1" onMouseOver="window.status='';return true" title="Control Financiero de Rentas">Finanzas Renta</a></li>
					<li class="subfirst"><a href="frm_regMovFin.php?id_pto=2" onMouseOver="window.status='';return true" title="Control Financiero de Ventas de Concreto">Finanzas Venta Concreto</a></li>
					<li class="subfirst"><a href="frm_regMovFin.php?id_pto=3" onMouseOver="window.status='';return true" title="Control Financiero de Cl&iacute;nica">Finanzas Cl&iacute;nica</a></li>
					<li class="subfirst"><a href="frm_regMovFin.php?id_pto=4" onMouseOver="window.status='';return true" title="Control Financiero del Rancho">Finanzas Rancho</a></li>
					<li class="subfirst"><a href="frm_regMovFin.php?id_pto=5" onMouseOver="window.status='';return true" title="Control Financiero de Concreto Lanzado de Fresnillo">Finanzas CLF</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_mina.php" onMouseOver="window.status='';return true" title="&Aacute;reas que Corresponden a Mina"><span>Secci&oacute;n Mina</span></a>
				<ul>
					<li class="subfirst"><a href="submenu_desarrollo.php" onMouseOver="window.status='';return true" title="Consultar Reportes del M&oacute;dulo de Desarrollo">Desarrollo</a></li>
					<li class="subfirst"><a href="submenu_topografia.php" onMouseOver="window.status='';return true" title="Consultar Reportes del M&oacute;dulo de Topograf&iacute;a">Topograf&iacute;a</a></li>
					<li class="subfirst"><a href="submenu_mtto.php?tipo=Mina" onMouseOver="window.status='';return true" title="Consultar Reportes del M&oacute;dulo de Mantenimiento secci&oacute;n Mina">Mantenimiento Mina</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_concreto.php" title="&Aacute;reas que Corresponden a Concreto" onMouseOver="window.status='';return true"><span>Secci&oacute;n Concreto</span></a>
				<ul>
					<li class="subfirst"><a href="submenu_concreto.php" onMouseOver="window.status='';return true"  title="Consultar Reportes del M&oacute;dulo de Gerencia T&eacute;cnica">Zarpeo</a></li>
					<li class="subfirst"><a href="submenu_produccion.php" onMouseOver="window.status='';return true" title="Consultar Reportes del M&oacute;dulo de Producci&oacute;n">Producci&oacute;n</a></li>
					<li class="subfirst"><a href="submenu_laboratorio.php" onMouseOver="window.status='';return true"  title="Consultar Reportes del M&oacute;dulo de Laboratorio">Laboratorio</a></li>
					<li class="subfirst"><a href="submenu_mtto.php?tipo=Concreto" onMouseOver="window.status='';return true"  title="Consultar Reportes del M&oacute;dulo de Mantenimiento secci&oacute;n Concreto/Superficie">Mantenimiento Concreto</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_administrativo.php" title="&Aacute;reas que Corresponden al Sector Administrativo" onMouseOver="window.status='';return true"><span>Secci&oacute;n Administrativa</span></a>
				<ul>
					<li class="subfirst"><a href="submenu_almacen.php" title="Consultar Reportes del M&oacute;dulo de Almac&eacute;n" onMouseOver="window.status='';return true">Almac&eacute;n</a></li>
					<li class="subfirst"><a href="submenu_compras.php" title="Consultar Reportes del M&oacute;dulo de Compras" onMouseOver="window.status='';return true">Compras</a></li>
					<li class="subfirst"><a href="submenu_recursos.php" title="Consultar Reportes del M&oacute;dulo de Recursos Humanos" onMouseOver="window.status='';return true">Recursos Humanos</a></li>
					<li class="subfirst"><a href="submenu_seguridad.php" title="Consultar Reportes del M&oacute;dulo de Seguridad" onMouseOver="window.status='';return true">Seguridad</a></li>
					<!--<li class="subfirst"><a href="#" title="Consultar Reportes del M&oacute;dulo de Calidad" onMouseOver="window.status='';return true">Calidad</a></li>-->
				</ul>
			</li>
			<li class="topfirst"><a href="menu_requisiciones2.php" title="Autorizar Requisiciones" onMouseOver="window.status='';return true"><span>Autorizar Requisici&oacute;n</span></a>
				<ul>
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
		</ul>
	</div>	

	<div id="barra-titulo-mod" style="position:absolute; left:0px; top:51px; width:1035px; height:30px; z-index:6; background-color: #666666; layer-background-color: #666666; border: 1px none #000000; visibility: inherit;">
		<img src="../../images/title-bar-bg-Gerencia.gif" width="1035" height="30" />	
	</div>

	<div id="usuario" style="position:absolute; left:840px; top:54px; width:189px; height:17; z-index:7">  
  		<div align="right" class="usr-reg"><?php echo $_SESSION['usr_reg']; ?><img src="../../images/boss-icon.png" width="25" height="25" align="absmiddle" /></div>
	</div>
	
	<div id="chat" style="position:absolute; left:1000px; top:86px; width:29px; height:32px; z-index:8">  
  		<img src="../../images/msn.png" width="100%" height="100%" align="absmiddle" style="cursor:pointer;" onClick="abrirVentanaChat()"/>
	</div>

	<div id="modulo" class="usr-reg">Reportes Gerenciales</div>

	<div class="Estilo4" id="titulo">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</div>
	<div id="fecha" class="fecha" align="right" style="color:#FFFFFF"><?php echo verFecha(1); ?>&nbsp;&nbsp;&nbsp;&nbsp;
<label id="reloj" class="fecha" style="color:#FFFFFF"></label></div>
</body>
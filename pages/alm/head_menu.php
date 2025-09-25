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
		#modulo {position:absolute; left:6px; top:56px; width:169px; height:17px; z-index:8; }
		#titulo {position:absolute; left:282px; top:15px; width:528px; height:22px; z-index:3;}
		#fecha {position:absolute; left:380px; top:59px; width:430px; height:18px;z-index:9;}
		.fecha {font-family: MicrogrammaDMedExt; font-size: 12px;color:#FFFFFF;font-weight:bold;}		
		#dock-new { position:absolute; width:275px; height:50px; z-index:124; left: 0; top: 0; }
		-->
	</style>
</head>

<body onLoad="inicio(); setInterval(muestraReloj, 1000);" onkeypress="parar()" onclick="parar()" onmousemove="parar()">
	<div id="dock-new">
  	<table width="218" border="0">
    	<tr>
      		<td width="50" height="50">						
				<form action="inicio_almacen.php">						
					<input type="image" src="../../images/dock/home.png" name="icono1" id="icono1" width="50" height="50" border="0" title="Inicio Almac&eacute;n" 
					onClick="MM_nbGroup('down','group2','icono1','',1)" 
					onMouseOver="MM_nbGroup('over','icono1','../../images/dock/home-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')"/>						
				</form>
			</td>
			<td width="50" height="50">
				<form action="frm_consultarProveedor.php">	
					<input type="image" src="../../images/dock/proveedores.png" name="icono2" id="icono2" width="50" height="50" border="0" title="Proveedores" 
					onClick="MM_nbGroup('down','group2','icono2','',1)"
					onMouseOver="MM_nbGroup('over','icono2','../../images/dock/proveedores-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')"/>					
				</form>		
			</td>
			<td width="50" height="50">
				<form action="frm_consultarAlertasRH.php">
					<input type="image" src="../../images/dock/rh.png" name="icono3" id="icono3" width="50" height="50" border="0" title="Recursos Humanos" 
					onClick="MM_nbGroup('down','group2','icono3','',1)"
					onMouseOver="MM_nbGroup('over','icono3','../../images/dock/rh-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')"/>						
				</form>		    	
			</td>
			<td width="50" height="50">
				<form action="frm_consultarRequisicionesExternas.php">
					<input type="image" src="../../images/dock/requisicion.png" name="icono4" id="icono4" width="50" height="50" border="0" title="Requisiciones de Otros Departamentos" 
					onClick="MM_nbGroup('down','group2','icono4','',1)"
					onMouseOver="MM_nbGroup('over','icono4','../../images/dock/requisicion-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')"/>						
				</form>		    	
		  </td>
    	</tr>
  	</table>
	</div>
	
	<div id="fondo-title" style="position:absolute; left:0px; top:0px; width:656px; height:52px; z-index:1">
		<img src="../../images/dock/dock-bg2.gif" width="1035" height="50"  />
	</div>
	<div id="logo" style="position:absolute; left:831px; top:-1px; width:154px; height:54px; z-index:4">
		<a href="http://www.concretolanzadodefresnillo.com" target="_blank" title="Ir a la Página Web de Concreto Lanzado de Fresnillo S.A. de C.V."><img src="../../images/logo.png" width="151" height="56" border="0" /></a>
	</div>
	<div id="log-off" style="position:absolute; left:992px; top:10px; width:33px; height:34px; z-index:5">	
		<form action="../salir.php">
			<input type="image" src="../../images/close.png" width="31" height="31" border="0" title="Cerrar Sesi&oacute;n" onMouseOver="window.estatus='';return true;"/>
		</form>	
	</div>
	<div id="menu" style="position:absolute; left:-15px; top:70px; width:1035px; height:85px; z-index:123;"> 
		<ul id="css3menu"> 
			<li class="topfirst"><a href="menu_material.php" onMouseOver="window.estatus='';return true"  title="Materiales"><span>Materiales</span></a>
				<ul>
					<li class="subfirst"><a href="frm_agregarMaterial.php?lmp=si" onMouseOver="window.estatus='';return true" title="Agregar Material">Agregar Material</a></li>
					<li class="subfirst"><a href="frm_eliminarMaterial.php" onMouseOver="window.estatus='';return true"  title="Eliminar Material">Eliminar Material</a></li>
					<li class="subfirst"><a href="frm_consultarMaterial.php" onMouseOver="window.estatus='';return true"  title="Consultar Material">Consultar Material</a></li>
					<li class="subfirst"><a href="frm_modificarMaterial.php" onMouseOver="window.estatus='';return true"  title="Modificar Material">Modificar Material</a></li>
					<li class="subfirst"><a href="menu_entrada_salida.php" onMouseOver="window.estatus='';return true"  title="Entradas/Salidas"><span>Entradas/Salidas</span></a>
						<ul>
							<li class="subfirst"><a href="frm_entradaMaterial.php?lmp=si" onMouseOver="window.estatus='';return true"  title="Entrada">Entrada</a></li>
							<li class="subfirst"><a href="frm_salidaMaterial.php" onMouseOver="window.estatus='';return true"  title="Salida">Salida</a></li>
							<!--
							<li class="subfirst"><a href="frm_salidaMaterialBC.php" onMouseOver="window.estatus='';return true"  title="Salida con C&oacute;digo de Barras">Salida con C&oacute;digo de Barras</a></li>
							-->
						</ul>
					</li>
					<li class="subfirst"><a href="menu_equivalencias.php" onMouseOver="window.estatus='';return true"  title="Equivalencia de Material"><span>Equivalencias</span></a>
						<ul>
							<li class="subfirst"><a href="frm_agregarEquivalencias.php" onMouseOver="window.estatus='';return true"  title="Agregar Equivalencia">Agregar</a></li>
							<li class="subfirst"><a href="frm_eliminarEquivalencias.php" onMouseOver="window.estatus='';return true"  title="Eliminar Equivalencia">Eliminar</a></li>
						</ul>
					</li>
					<li class="subfirst"><a href="frm_inventario.php" onMouseOver="window.estatus='';return true" title="Realizar el Inventario de Materiales">Inventario</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_requisiciones.php" onMouseOver="window.estatus='';return true"  title="Requisiciones"><span>Requisiciones Almacen</span></a>
				<ul>
					<!--
					<li class="subfirst"><a href="frm_autorizarRequisicion.php" title="Autorizar una Requisici&oacute;n de Recursos Humanos" onMouseOver="window.status='';return true">
					Autorizar Requisici&oacute;n</a></li>
					-->
					<li class="subfirst"><a href="frm_generarRequisicion.php" onMouseOver="window.estatus='';return true"  title="Generar Requisici&oacute;n">Generar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_consultarRequisiciones.php" onMouseOver="window.estatus='';return true"  title="Estado Requisiciones">Estado Requisiciones</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_ordenCompra.php" onMouseOver="window.estatus='';return true"  title="Orden de Compra"><span>Orden de Compra</span></a>
				<ul>
					<li class="subfirst"><a href="frm_generarOC.php" onMouseOver="window.estatus='';return true"  title="Generar Orden de Compra">Generar Orden de Compra</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_reportes.php" onMouseOver="window.estatus='';return true"  title="Reportes"><span>Reportes</span></a>
				<ul>
					<li class="subfirst"><a href="frm_reporteInventario.php" onMouseOver="window.estatus='';return true"  title="Reporte Inventario">Reporte Inventario</a></li>
					<li class="subfirst"><a href="frm_reporteREA.php" onMouseOver="window.estatus='';return true"  title="Reporte REA">Reporte REA</a></li>
					<li class="subfirst"><a href="frm_reporteSalidasAuditoria.php" onMouseOver="window.estatus='';return true"  title="Reporte Salidas Auditoria">Reporte Salidas Auditoria</a></li>
					<li class="subfirst"><a href="frm_reporteOrdenCompra.php" onMouseOver="window.estatus='';return true"  title="Reporte Orden de Compra">Reporte Orden de Compra</a></li>
					<li class="subfirst"><a href="frm_reporteSalidas.php" onMouseOver="window.estatus='';return true"  title="Reporte de Salidas del Almac&eacute;n">Reporte de Salidas</a></li>
					<li class="subfirst"><a href="frm_reporteRequisiciones.php" title="Reporte Requisiciones" onMouseOver="window.status='';return true">Reporte Requisiciones</a></li>
				</ul>
			</li>
			<!--
			<li class="topfirst"><a href="menu_requisiciones2.php" title="Requisiciones" onMouseOver="window.estatus='';return true"><span>Requisiciones</span></a>
				<ul>
					<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=sabinas" title="Revisar Requisiciones Sabinas" 
                    onMouseOver="window.estatus='';return true">Sabinas</a></li>
					<li class="subfirst"><a href="frm_consultarRequisiciones2.php?depto=almacen" title="Revisar Requisiciones Almac&eacute;n" 
                    onMouseOver="window.estatus='';return true">Almac&eacute;n</a></li>
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
		</ul>		
	</div>

	<div id="barra-titulo-mod" style="position:absolute; left:0px; top:51px; width:1035px; height:30px; z-index:6; background-color: #666666; layer-background-color: #666666; border: 1px none #000000; visibility: inherit;">
		<img src="../../images/title-bar-bg.gif" width="1035" height="30" />	
	</div>

	<div id="usuario" style="position:absolute; left:873px; top:54px; width:156px; height:17; z-index:7">  
  		<div align="right" class="usr-reg"><?php echo $_SESSION['usr_reg']; ?><img src="../../images/boss-icon.png" width="25" height="25" align="absmiddle"/></div>
	</div>
	
	<div id="chat" style="position:absolute; left:1000px; top:86px; width:29px; height:32px; z-index:8">  
  		<img src="../../images/msn.png" width="100%" height="100%" title="Escribir Mensaje" align="absmiddle" style="cursor:pointer;" onClick="abrirVentanaChat();"/>
	</div>
	
	<div id="modulo" class="usr-reg">M&oacute;dulo de Almac&eacute;n</div>

	<div id="titulo"><span class="titulo-pagina">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</span></div>
	<div id="fecha" class="fecha" align="right"><?php echo verFecha(1); ?>&nbsp;&nbsp;&nbsp;&nbsp;
<label id="reloj" class="fecha"></label></div>
</body>
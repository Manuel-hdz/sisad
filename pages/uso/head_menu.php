<?php
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	//Modulo de conexion con la base de datos, el cual estara diponible para todas la paginas a trav�s de este archivo (head_menu.php) 
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
				alert ('Contenido Protegido, �Concreto Lanzado de Fresnillo MARCA ');
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
		#titulo {position:absolute;left:288px;top:15px;width:528px;height:22px;z-index:3;}
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
				<form action="inicio_clinica.php">
					<input type="image" src="../../images/dock/home.png" name="icono1" id="icono1" width="50" height="50" border="0" title="Inicio Unidad de Salud Ocupacional"					
					onClick="MM_nbGroup('down','group2','icono1','',1)" 
					onMouseOver="MM_nbGroup('over','icono1','../../images/dock/home-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')" />
				</form>
			</td>
			<td>
			<form action="frm_selConsultaExterna.php">
					<input type="image" src="../../images/dock/rh.png" name="icono3" id="icono3" width="50" height="50" border="0" title="Recursos Humanos" 
					onClick="MM_nbGroup('down','group2','icono3','',1)"
					onMouseOver="MM_nbGroup('over','icono3','../../images/dock/rh-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')"/>						
				</form>		
			</td>		
    	</tr>
  	</table>
	</div>

	<div id="fondo-title" style="position:absolute; left:0px; top:0px; width:656px; height:52px; z-index:1"><img src="../../images/dock/dock-bg2.gif" width="1035" height="50"  /></div>
	<div id="logo" style="position:absolute; left:831px; top:-1px; width:154px; height:54px; z-index:4"><a href="http://www.clfmarca.com" target="_blank" title="Ir a la P�gina Web de Concreto Lanzado de Fresnillo MARCA "><img src="../../images/logo.png" width="151" height="56" border="0" /></a></div>
	<div id="log-off" style="position:absolute; left:992px; top:10px; width:33px; height:34px; z-index:5">
		<form action="../salir.php">
			<input type="image" src="../../images/close.png" width="31" height="31" border="0" title="Cerrar Sesi&oacute;n" onMouseOver="window.status='';return true" />
		</form>
	</div>


	<div id="menu" style="position:absolute; left:-15px; top:70px; width:1035px; height:85px; z-index:123;"> 
		<ul id="css3menu">	
			<li class="topfirst"><a href="menu_catalogos.php" onMouseOver="window.status='';return true"  title="Cat&aacute;logos">Cat&aacute;logo</a>
					<ul>
						<li class="subfirst">
							<a href="frm_gestionEmpExternas.php" onMouseOver="window.status='';return true" 
							title="Gestionar Cat&aacute;logo de Empresas Externas">Empresas Externas</a>
						</li>
						<li class="subfirst">
							<a href="frm_gestionExaMedicos.php" onMouseOver="window.status='';return true" 
							title="Gestionar Cat&aacute;logo de Ex&aacute;menes M&eacute;dicos">Ex&aacute;menes M&eacute;dicos</a>
						</li>	
						<li class="subfirst">
							<a href="frm_gestionCatRadiografias.php" onMouseOver="window.status='';return true" 
							title="Gestionar Cat&aacute;logo de Radiograf&iacute;as">Radiograf&iacute;as</a>
						</li>
					</ul>	
		  </li>
				<li class="topfirst"><a href="menu_bitacoras.php" onMouseOver="window.status='';return true"  title="Bit&aacute;coras">Bit&aacute;coras</a>
					<ul>
						<li class="subfirst">
							<a href="menu_bitacoraConsultasMed.php" onMouseOver="window.status='';return true" 
							title="Bit&aacute;cora de Consultas M&eacute;dicas"><span>Bit&aacute;cora Consultas M&eacute;dicas</span></a>
							<ul>
								<li class="subfirst"><a href="frm_regBitacoraConsultasMed.php" onMouseOver="window.status='';return true" 
								title="Registrar Bit&aacute;cora de Consultas M&eacute;dicas">Registrar Bit&aacute;cora de Consultas M&eacute;dicas</a></li>
								<li class="subfirst"><a href="frm_modBitacoraConsultasMed.php" onMouseOver="window.status='';return true"
								title="Modificar Bit&aacute;cora de Consultas M&eacute;dicas">Modificar Bit&aacute;cora de Consultas M&eacute;dicas</a></li>
							</ul>
						</li>
						<li class="subfirst">
							<a href="frm_regBitacoraMedicamentos.php" onMouseOver="window.status='';return true" 
							title="Gestionar Bit&aacute;cora de Medicamentos">Bit&aacute;cora  de Medicamentos</a>
						</li>	
						<li class="subfirst">
							<a href="menu_bitacoraRadiografias.php" onMouseOver="window.status='';return true" 
							title="Bit&aacute;cora de Radiograf&iacute;as"><span>Bit&aacute;cora de Radiograf&iacute;as</span></a>
							<ul>
								<li class="subfirst"><a href="frm_regBitacoraRadiografias.php" onMouseOver="window.status='';return true" 
								title="Registrar Bit&aacute;cora de Radiograf&iacute;as">Registrar Bit&aacute;cora de Radiograf&iacute;as</a></li>
								<li class="subfirst"><a href="frm_modBitacoraRadiografias.php" onMouseOver="window.status='';return true"
								title="Modificar Bit&aacute;cora de Radiograf&iacute;as">Modificar Bit&aacute;cora de Radiograf&iacute;as</a></li>
							</ul>
						</li>	
					</ul>
				</li>
			<li class="topfirst">
				<a href="menu_historialClinico.php" onMouseOver="window.status='';return true" 
				title="Historial Cl&iacute;nico"><span>Historial Cl&iacute;nico</span></a>
				<ul>
					<li class="subfirst"><a href="frm_generarHistorialClinico.php" onMouseOver="window.status='';return true" 
					title="Generar el Historial Cl&iacute;nico del Trabajador">Generar Historial Cl&iacute;nico</a></li>
					<li class="subfirst"><a href="frm_consultarHistorialClinico.php" onMouseOver="window.status='';return true"
					title="Consultar Historial Cl&iacute;nico del Trabajador">Consultar Historial Cl&iacute;nico</a></li>
					<li class="subfirst"><a href="frm_gestionarSolicitud.php" onMouseOver="window.status='';return true"
					title="Gestionar Solicitudes de Ex&aacute;menes M&eacute;dicos">Solicitud Ex&aacute;men M&eacute;dico</a></li>
				</ul>
			</li>						
			<li class="topfirst">
				<a href="menu_requisiciones.php" title="Requisiciones de la Unidad de Salud Ocupacional" onMouseOver="window.status='';return true">
				<span>Requisici&oacute;n</span></a>
				<ul>
					<li class="subfirst"><a href="frm_autorizarRequisicion.php" title="Autorizar una Requisici&oacute;n de Recursos Humanos" onMouseOver="window.status='';return true">
					Autorizar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_generarRequisicion.php" title="Generar una Requisici&oacute;n de la Unidad de Salud Ocupacional" 
					onMouseOver="window.status='';return true">Generar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_consultarRequisiciones.php" title="Consultar las Requisiciones de la Unidad de Salud Ocupacional" 
					onMouseOver="window.status='';return true">Consultar Requisiciones</a></li>
				</ul>
			</li>
			<li class="topfirst">
				<a href="menu_reportes.php" title="Reportes de la Unidad de Salud Ocupacional" onMouseOver="window.status='';return true">
				<span>Reportes</span></a>
				<ul>
					<li class="subfirst"><a href="frm_reporteHistorialesClinicos.php" title="Generar Reporte de Examenes M&eacute;dicos" 
					onMouseOver="window.status='';return true">Generar Reporte de Examenes M&eacute;dicos</a></li>
					<li class="subfirst"><a href="frm_reporteActSemanal.php" title="Generar Reporte Semanal de Actividades" 
					onMouseOver="window.status='';return true">Generar Reporte Semanal de Actividades</a></li>
					<li class="subfirst"><a href="frm_reporteCensosConsultas.php" title="Generar Reporte de Censos y Consultas" 
					onMouseOver="window.status='';return true">Generar Reporte de Censos y Consultas Realizadas</a></li>
					<li class="subfirst"><a href="frm_reporteResultadosExamenes.php" title="Generar Reporte de Reportes de Ex&aacute;menes Cl&iacute;nicos" 
					onMouseOver="window.status='';return true">Generar Reporte de Ex&aacute;menes Cl&iacute;nicos</a></li>
					<li class="subfirst"><a href="frm_reporteRequisiciones.php" title="Reporte Requisiciones" onMouseOver="window.status='';return true">Reporte Requisiciones</a></li>
				</ul>
			</li>
		</li>
	</ul>
		
</div>	

	<div id="barra-titulo-mod" style="position:absolute; left:0px; top:51px; width:1035px; height:30px; z-index:6; background-color: #666666; layer-background-color: #666666; border: 1px none #000000; visibility: inherit;">
		<img src="../../images/title-bar-bg.gif" width="1035" height="30" />	
	</div>
	<div id="usuario" style="position:absolute; left:840px; top:54px; width:189px; height:17; z-index:7">  
  		<div align="right" class="usr-reg"><?php echo $_SESSION['usr_reg']; ?><img src="../../images/boss-icon.png" width="25" height="25" align="absmiddle" /></div>
	</div>

	<div id="modulo" class="usr-reg">M&oacute;dulo de Unidad de Salud Ocupacional</div>
	
	<div id="chat" style="position:absolute; left:1000px; top:86px; width:29px; height:32px; z-index:8">  
  		<img src="../../images/msn.png" width="100%" height="100%" title="Escribir Mensaje" align="absmiddle" style="cursor:pointer;" onClick="abrirVentanaChat();"/>
	</div>

	<div id="titulo"><span class="titulo-pagina">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</span></div>
<div id="fecha" class="fecha" align="right"><?php echo verFecha(1); ?>&nbsp;&nbsp;&nbsp;&nbsp;
<label id="reloj" class="fecha"></label></div>
</body>
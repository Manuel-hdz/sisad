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
				alert ('Contenido Protegido, �CONCRETO LANZADO DE FRESNILLO MARCA');
			}
		}
		document.onmousedown=click;
		//-->
	</script>

	<style type="text/css">
		<!--		
		.titulo-pagina {font-family: MicrogrammaDMedExt; color: #33761B; font-size: 13px; font-weight: bold; }
		.usr-reg { color: #FFFFFF; font-weight: bold; font-family: MicrogrammaDMedExt; font-size: 12px; }
		#modulo {position:absolute; left:6px; top:56px; width:271px; height:17px; z-index:8; }
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
				<form action="inicio_recursos.php">						
					<input type="image" src="../../images/dock/home.png" name="icono1" id="icono1" width="50" height="50" border="0" title="Inicio Recursos Humanos"					
					onClick="MM_nbGroup('down','group2','icono1','',1)" 
					onMouseOver="MM_nbGroup('over','icono1','../../images/dock/home-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')" />
				</form>
			</td>
			<td width="50" height="50">
				<form action="frm_consultarEquipoSeguridadDeudo.php">	
					<input type="image" src="../../images/dock/alm.png" name="icono2" id="icono2" width="50" height="50" border="0" 
					title="Consultar Equipo Seguridad Almac&eacute;n"
					onClick="MM_nbGroup('down','group2','icono2','',1)" 
					onMouseOver="MM_nbGroup('over','icono2','../../images/dock/alm-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')" />					
				</form>		
			</td>
			<td width="50" height="50">
				<form action="frm_gestionarSolicitud.php">						
					<input type="image" src="../../images/dock/cons-ext.png" name="icono3" id="icono3" width="50" height="50" border="0" 
					title="Consultas Externas a la Cl&iacute;nica"	onClick="MM_nbGroup('down','group2','icono3','',1)" 
					onMouseOver="MM_nbGroup('over','icono3','../../images/dock/cons-ext-over.png','',1); window.status='';return true" onMouseOut="MM_nbGroup('out')" />
				</form>
			</td>	
    	</tr>
  	</table>
	</div>

	<div id="fondo-title" style="position:absolute; left:0px; top:0px; width:656px; height:52px; z-index:1">
		<img src="../../images/dock/dock-bg2.gif" width="1035" height="50"  />
	</div>
	<div id="logo" style="position:absolute; left:831px; top:-1px; width:154px; height:54px; z-index:4">
		<a href="http://www.concretolanzadodefresnillo.com" target="_blank" title="Ir a la P�gina Web dCONCRETO LANZADO DE FRESNILLO MARCAV.">
			<img src="../../images/logo.png" width="151" height="56" border="0" />
		</a>
	</div>
	<div id="log-off" style="position:absolute; left:992px; top:10px; width:33px; height:34px; z-index:5">
		<form action="../salir.php">
			<input type="image" src="../../images/close.png" width="31" height="31" border="0" title="Cerrar Sesi&oacute;n" onMouseOver="window.status='';return true" />
		</form>
	</div>


	<div id="menu" style="position:absolute; left:-15px; top:70px; width:1035px; height:85px; z-index:123;"> 
		<ul id="css3menu"> 
			<li class="topfirst"><a href="menu_empleados.php" onMouseOver="window.status='';return true"  title="Control de Empleados"><span>Empleados</span></a>
				<ul>
					<li class="subfirst"><a href="frm_agregarEmpleado.php" onMouseOver="window.status='';return true" title="Agregar Empleados a la N&oacute;mina">
					Alta Empleado(s)</a></li>
					<li class="subfirst"><a href="frm_eliminarEmpleado.php" onMouseOver="window.status='';return true"  title="Eliminar Empleados de la N&oacute;mina">
					Baja Empleado(s)</a></li>
					<li class="subfirst"><a href="frm_consultarEmpleado.php" onMouseOver="window.status='';return true"  title="Consultar Empleados">Consultar Empleado(s)</a></li>
					<li class="subfirst"><a href="frm_modificarEmpleado.php" onMouseOver="window.status='';return true"  title="Modificar Empleados">Modificar Empleado(s)</a></li>
					<li class="subfirst"><a href="frm_agregarEmpleadoBeneficiario.php" onMouseOver="window.status='';return true"  title="Registrar Beneficiarios">
					Beneficiarios</a></li>
					<li class="subfirst"><a href="frm_agregarEmpleadoBecarios.php" onMouseOver="window.status='';return true"  title="Registrar Becarios">Becas</a></li>
					<li class="subfirst"><a href="menu_kardex.php" onMouseOver="window.status='';return true"  title="Operaciones de Kardex"><span>Kardex</span></a>
						<ul>
							<li class="subfirst"><a href="frm_recolectarChecadas.php" onMouseOver="window.status='';return true"  title="Recolectar Checadas">
							Recolectar Checadas</a></li>
							<li class="subfirst"><a href="frm_consultarKardex.php" onMouseOver="window.status='';return true" 
							title="Generar Kardex con opci&oacute;n a Modificaci&oacute;n">Generar Kardex</a></li>
							<li class="subfirst"><a href="frm_consultarKardexChecador.php" onMouseOver="window.status='';return true" 
							title="Consultar Kardex Checador">Consultar Kardex Checador</a></li>
						</ul>
					</li>
				</ul>
			</li>
            
			<li class="topfirst"><a href="menu_nomina.php" title="Registros de N&oacute;mina" onMouseOver="window.status='';return true"><span>N&oacute;mina</span></a>
				<ul>
					<li class="subfirst"><a href="menu_nominaInterna.php" title="N&oacute;mina Interna Manejada por Encargados de cada Departamento" 
					onMouseOver="window.status='';return true"><span>N&oacute;mina Interna</span></a>
						<ul>
							<li class="subfirst"><a href="menu_registrar_nomina.php" title="Registrar N&oacute;mina" 
							onMouseOver="window.status='';return true"><span>Registrar N&oacute;mina</span></a>
								<ul>
									<li class="subfirst"><a href="frm_registrarNominaDesarrollo.php" title="N&oacute;mina Desarrollo" 
									onMouseOver="window.status='';return true">N&oacute;mina Desarrollo</a></li>
									<li class="subfirst"><a href="frm_registrarNominaZarpeo.php" title="N&oacute;mina Zarpeo" 
									onMouseOver="window.status='';return true">N&oacute;mina Zarpeo</a></li>
									<li class="subfirst"><a href="frm_registrarNominaMam.php" title="N&oacute;mina Mantenimiento Mina" 
									onMouseOver="window.status='';return true">N&oacute;mina Mantenimiento Mina</a></li>
									<li class="subfirst"><a href="frm_registrarNominaMac.php" title="N&oacute;mina Mantenimiento Superficie" 
									onMouseOver="window.status='';return true">N&oacute;mina Mantenimiento Superficie</a></li>
									<li class="subfirst"><a href="frm_registrarNominaAdministracion.php" title="N&oacute;mina Administraci&oacute;n" 
									onMouseOver="window.status='';return true">N&oacute;mina Administraci&oacute;n</a></li>
								</ul>
							</li>
							<li class="subfirst"><a href="menu_reporte_nominaInterna.php" title="Consultar N&oacute;mina" 
							onMouseOver="window.status='';return true"><span>Consultar N&oacute;mina</span></a>
								<ul>
									<li class="subfirst"><a href="frm_consultarNominaDesarrollo.php" title="N&oacute;mina Desarrollo" 
									onMouseOver="window.status='';return true">N&oacute;mina Desarrollo</a></li>
									<li class="subfirst"><a href="frm_consultarNominaZarpeo.php" title="N&oacute;mina Zarpeo" 
									onMouseOver="window.status='';return true">N&oacute;mina Zarpeo</a></li>
									<li class="subfirst"><a href="frm_consultarNominaMam.php" title="N&oacute;mina Mantenimiento Mina" 
									onMouseOver="window.status='';return true">N&oacute;mina Mantenimiento Mina</a></li>
									<li class="subfirst"><a href="frm_consultarNominaMac.php" title="N&oacute;mina Mantenimiento Superficie" 
									onMouseOver="window.status='';return true">N&oacute;mina Mantenimiento Superficie</a></li>
									<li class="subfirst"><a href="frm_consultarNominaAdministracion.php" title="N&oacute;mina Administraci&oacute;n" 
									onMouseOver="window.status='';return true">N&oacute;mina Administraci&oacute;n</a></li>
								</ul>
							</li>
						</ul>
                    </li>
					
					<li class="subfirst"><a href="menu_nominaBancaria.php" title="N&oacute;mina Bancaria Entregada por Contabilidad" onMouseOver="window.status='';return true">
					<span>N&oacute;mina Bancaria</span></a>
						<ul>
							<li class="subfirst"><a href="frm_importarCSV.php" title="Importar N&oacute;mina Bancaria" 
							onMouseOver="window.status='';return true">Importar N&oacute;mina</a></li>
							<li class="subfirst"><a href="frm_consultarNominaBancaria.php" title="Consultar N&oacute;mina Bancaria" 
							onMouseOver="window.status='';return true">Consultar N&oacute;mina</a></li>
						</ul>
                    </li>
                </ul>
			</li>
			
			<li class="topfirst"><a href="menu_bonos.php" title="Registros de Bonos de Productividad" onMouseOver="window.status='';return true"><span>Bonos de Productividad</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registroBonos.php" title="Registrar Bonos de Productividad Por Departamento" 
					onMouseOver="window.status='';return true">Registrar Bonos de Productividad</a>
					<li class="subfirst"><a href="frm_modificarBonoProd.php" title="Modificar Bonos de Productividad Por Departamento" 
					onMouseOver="window.status='';return true">Modificar Bonos de Productividad</a>
					<li class="subfirst"><a href="frm_consultarBonoProd.php" title="Consultar Bonos de Productividad Por Departamento" 
					onMouseOver="window.status='';return true">Consultar Bonos de Productividad</a>
				</ul>
			</li>
                    
			<li class="topfirst"><a href="menu_capacitaciones.php" title="Capacitaciones para Trabajadores" onMouseOver="window.status='';return true">
			<span>Capacitaciones</span></a>
				<ul>
					<li class="subfirst"><a href="frm_agregarCapacitacion.php" title="Agregar Capacitaci&oacute;n" onMouseOver="window.status='';return true">
					Agregar Capacitaci&oacute;n</a></li>
					<li class="subfirst"><a href="frm_eliminarCapacitacion.php" title="Eliminar Capacitaci&oacute;n" onMouseOver="window.status='';return true">
					Eliminar Capacitaci&oacute;n</a></li>
					<li class="subfirst"><a href="frm_consultarCapacitacion.php" title="Consultar Capacitaci&oacute;n" onMouseOver="window.status='';return true">
					Consultar Capacitaci&oacute;n</a></li>
					<li class="subfirst"><a href="frm_modificarCapacitacion.php" title="Modificar Capacitaci&oacute;n" onMouseOver="window.status='';return true">
					Modificar Capacitaci&oacute;n</a></li>
					<li class="subfirst"><a href="frm_regAsistenciaCapacitacion.php" title="Registrar Asistencias a Capacitaci&oacute;n" onMouseOver="window.status='';return true">
					Registrar Asistencias a Capacitaci&oacute;n</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_bolsaTrabajo.php" title="Bolsa de Trabajo" onMouseOver="window.status='';return true"><span>Bolsa de Trabajo</span></a>
				<ul>
					<li class="subfirst"><a href="frm_registrarAspirante.php" title="Registrar Aspirantes en la Bolsa de Trabajo" onMouseOver="window.status='';return true">
					Alta de Aspirantes</a></li>
					<li class="subfirst"><a href="frm_eliminarAspirante.php" title="Dar de Baja Aspirantes de la Bolsa de Trabajo" onMouseOver="window.status='';return true">
					Baja de Aspirantes</a></li>
					<li class="subfirst"><a href="frm_consultarAspirante.php" title="Consultar Aspirantes de la Bolsa de Trabajo" onMouseOver="window.status='';return true">
					Consultar Aspirantes</a></li>
					<li class="subfirst"><a href="frm_modificarAspirante.php" title="Modificar Informaci&oacute;n de los Aspirantes en Bolsa de Trabajo" 
					onMouseOver="window.status='';return true">Modificar Aspirantes</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_requisiciones.php" title="Requisiciones de Recursos Humanos" onMouseOver="window.status='';return true">
			<span>Requisici&oacute;n</span></a>
				<ul>
					<li class="subfirst"><a href="frm_autorizarRequisicion.php" title="Autorizar una Requisici&oacute;n de Recursos Humanos" onMouseOver="window.status='';return true">
					Autorizar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_generarRequisicion.php" title="Generar una Requisici&oacute;n de Recursos Humanos" onMouseOver="window.status='';return true">
					Generar Requisici&oacute;n</a></li>
					<li class="subfirst"><a href="frm_consultarRequisiciones.php" title="Consultar las Requisiciones de Recursos Humanos" 
					onMouseOver="window.status='';return true">Consultar Requisiciones</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_reportes.php" title="Reportes" onMouseOver="window.status='';return true"><span>Reportes</span></a>
				<ul>
					<li class="subfirst"><a href="frm_reporteAsistencia.php" title="Reporte de Asistencias" onMouseOver="window.status='';return true">Asistencias</a></li>
					<li class="subfirst"><a href="frm_reporteIncapacidades.php" title="Reporte de Incapacidades" onMouseOver="window.status='';return true">Incapacidades</a></li>
					<li class="subfirst"><a href="frm_reporteAusentismo.php" title="Reporte de Ausentismo" onMouseOver="window.status='';return true">Ausentismo</a></li>
					<li class="subfirst"><a href="frm_reporteReclutamiento.php" title="Reporte de Reclutamiento" onMouseOver="window.status='';return true">Reclutamiento</a></li>
					<li class="subfirst"><a href="frm_reporteKardex.php" title="Reporte de Kardex" onMouseOver="window.status='';return true">Kardex</a></li>
					<li class="subfirst"><a href="frm_reporteAltasBajas.php" title="Reporte de Altas y Bajas" onMouseOver="window.status='';return true">Altas y Bajas</a></li>
					<li class="subfirst"><a href="frm_reportePrestamos.php" title="Reporte de Pr&eacute;stamos" onMouseOver="window.status='';return true">Pr&eacute;stamos</a>
                    </li>
					<li class="subfirst"><a href="frm_reporteCapacitaciones.php" title="Reporte de Capacitaciones" onMouseOver="window.status='';return true">
					Capacitaciones</a></li>
					<!-- <li class="subfirst"><a href="frm_reporteNomina.php" title="Reporte de N&oacute;mina" onMouseOver="window.status='';return true">N&oacute;mina</a></li> -->
					<li class="subfirst"><a href="frm_reportePagoSeguroSocial.php" title="Reporte de Pago de Seguro Social" onMouseOver="window.status='';return true">
					Pago de Seguro Social</a></li>
					<li class="subfirst"><a href="frm_reporteHistorico.php" title="Reporte Hist&oacute;rico del Personal" onMouseOver="window.status='';return true">
					Historial del Personal</a></li>	
					<li class="subfirst"><a href="menu_reporte_nomina.php" onMouseOver="window.status='';return true"  
					title="Reportes de N&oacute;mina"><span>N&oacute;mina Externa</span></a>
						<ul>
							<li class="subfirst"><a href="frm_reporteNominaDesarrollo.php" onMouseOver="window.status='';return true"  
							title="Reporte N&oacute;mina">N&oacute;mina Desarrollo</a></li>							
							<li class="subfirst"><a href="frm_reporteNominaZarpeo.php" onMouseOver="window.status='';return true"  
							title="Reporte N&oacute;mina">N&oacute;mina Zarpeo</a></li>
							<li class="subfirst"><a href="frm_reporteNominaMam.php" onMouseOver="window.status='';return true"  
							title="Reporte N&oacute;mina">N&oacute;mina Mantenimiento Mina</a></li>
							<li class="subfirst"><a href="frm_reporteNominaMac.php" onMouseOver="window.status='';return true"  
							title="Reporte N&oacute;mina">N&oacute;mina Mantenimiento Superficie</a></li>
						</ul>
					</li>
					<li class="subfirst"><a href="frm_reporteRequisiciones.php" title="Reporte Requisiciones" onMouseOver="window.status='';return true">Reporte Requisiciones</a></li>
				</ul>
			</li>
			<li class="topfirst"><a href="menu_administrativo.php" title="Secci&oacute;n de Actividades Administrativas" onMouseOver="window.status='';return true">
			<span>Administrativo</span></a>
				<ul>
					<li class="subfirst"><a href="frm_modificarOrganigrama.php" title="Modificar Organigrama" onMouseOver="window.status='';return true">Organigrama</a></li>
					<li class="subfirst"><a href="frm_generarNombramiento.php" title="Generar Nombramientos" onMouseOver="window.status='';return true">Nombramientos</a></li>
					<li class="subfirst"><a href="frm_gestionarBonos.php" title="Gestionar Bonos" onMouseOver="window.status='';return true">
					Gesti&oacute;n Bonos</a></li>										
					<li class="subfirst"><a href="menu_deducciones.php" onMouseOver="window.status='';return true"  title="Registrar Deducciones"><span>
					Deducciones
					</span></a>
						<ul>
							<li class="subfirst"><a href="frm_agregarDeduccion.php" onMouseOver="window.status='';return true"  
							title="Registrar Deducciones">Agregar Deducci&oacute;n</a></li>
							<li class="subfirst"><a href="frm_eliminarDeduccion.php" onMouseOver="window.status='';return true"  
							title="Eliminar Deducciones">Eliminar Deducci&oacute;n</a></li>
							<li class="subfirst"><a href="frm_registrarAbonos.php?hdn_org=deducciones" onMouseOver="window.status='';return true"  
							title="Registrar Abonos">Registrar Abonos</a></li>
							<li class="subfirst"><a href="frm_consultarDeducciones.php" onMouseOver="window.status='';return true"  
							title="Consultar Deducciones">Consultar Deducci&oacute;n</a></li>
						</ul>
					</li>
					<li class="subfirst"><a href="menu_prestamos.php" onMouseOver="window.status='';return true"  
					title="Registrar Pr&eacute;stamos"><span>Pr&eacute;stamos</span></a>
						<ul>
							<li class="subfirst"><a href="frm_agregarPrestamo.php" onMouseOver="window.status='';return true"  
							title="Registrar Pr&eacute;stamos">Agregar Pr&eacute;stamos</a></li>							
							<li class="subfirst"><a href="frm_registrarAbonos.php?hdn_org=prestamos" onMouseOver="window.status='';return true"  
							title="Registrar Abonos">Registrar Abonos</a></li>
							<li class="subfirst"><a href="frm_consultarPrestamos.php" onMouseOver="window.status='';return true"  
							title="Consultar Pr&eacute;stamos">Consultar Pr&eacute;stamos</a></li>
						</ul>
					</li>					
				</ul>
			</li>
			<li class="topfirst"><a href="menu_turnos.php" title="Administraci&oacute;n de Turnos y Rolamiento de Personal" onMouseOver="window.status='';return true">
			<span>Turnos</span></a>
				<ul>
					<li class="subfirst"><a href="frm_catalogoTurnos.php" title="Modificar Cat&aacute;logo de Turnos" onMouseOver="window.status='';return true">
					Cat&aacute;logo de Turnos</a></li>
					<li class="subfirst"><a href="frm_asignarRoles.php" title="Organizar Roles de Trabajo" onMouseOver="window.status='';return true">Roles de Trabajo</a></li>
				</ul>
			</li>
	  </ul>
	</div>	

	<div id="barra-titulo-mod" style="position:absolute; left:0px; top:51px; width:1035px; height:30px; z-index:6; background-color:#666666; layer-background-color: #666666; border:1px none #000000; visibility: inherit;">
		<img src="../../images/title-bar-bg.gif" width="1035" height="30" />	
	</div>

	<div id="usuario" style="position:absolute; left:840px; top:54px; width:189px; height:17; z-index:7">  
  		<div align="right" class="usr-reg"><?php echo $_SESSION['usr_reg']; ?><img src="../../images/boss-icon.png" width="25" height="25" align="absmiddle" /></div>
	</div>

	<div id="modulo" class="usr-reg">M&oacute;dulo de Recursos Humanos</div>
	
	<div id="chat" style="position:absolute; left:1000px; top:86px; width:29px; height:32px; z-index:8">  
  		<img src="../../images/msn.png" width="100%" height="100%" title="Escribir Mensaje" align="absmiddle" style="cursor:pointer;" onClick="abrirVentanaChat();"/>
	</div>

	<div id="titulo">
		<span class="titulo-pagina">
			Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n
			<a href="guardar_reporte.php?exp_nom" style="text-decoration:none">.</a>
		</span>
	</div>
	
    <div id="fecha" class="fecha" align="right"><?php echo verFecha(1); ?>&nbsp;&nbsp;&nbsp;&nbsp;<label id="reloj" class="fecha"></label></div>
	
	
	<?php 
	/*
	Codigo para vista Programador, cuando se exporten los datos del Sisad al HP, decomentar este codigo para verificar que no existan errores en la exportacion
	if (file_exists("documentos/errores.txt")){
	?>
		<script type="text/javascript" language="javascript">
		setTimeout("msje()",1000);
		
		function msje(){
			if (confirm("Existen Trabajadores que No se Agregaron al HandPunch.\n�Revisar Archivo de Errores?"))
				window.open('documentos/errores.txt', '_blank','top=100, left=100, width=680, height=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
		}
		</script>
	<?php 
	}*/ ?>
	
</body>
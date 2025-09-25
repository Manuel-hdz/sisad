<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Incluir el archivo que maneja las alertas de los Equipos que estan proximos a recibir Mtto. Preventivo
		//include_once ("alertas.php");
		//Desplegar las alertas registradas en la BD
		//desplegarAlertas();
		
		//Liberar de la SESSION los arreglos utilizados en el Registro de la Orden de Trabajo
		unset ($_SESSION['totalVale']);
		unset ($_SESSION['vales']);
		unset ($_SESSION['datosValeMtto']);
		unset ($_SESSION['datosOT']);
		unset ($_SESSION['gamasOT']);
		
		//Liberar de la SESSION los arreglos utilizados en el Registro de la Orden de Trabajo para Servicios Externos
		unset($_SESSION['ordenServicioExterno']);
		unset($_SESSION['actividadesRealizar']);
		unset($_SESSION['materialesUtilizar']);
		
		//Liberar de la SESSION el arreglo utilizado en la Consulta de la Orden de Trabajo para Servicios Externos
		unset($_SESSION['datosConsultaOTSE']);?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />	

    <style type="text/css">
		<!--
		#parrilla-menu2 { position:absolute; left:100px; top:160px; width:675px; height:229px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu2">
	<table class="tabla_frm" width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
			<td width="25%" align="center">
	    		<div align="center">
					<form action="frm_generarOrdenTrabajo.php">
						<input type="image" src="images/add-ordentrabajo.png" width="115" height="210" border="0" title="Generar &Oacute;rden de Trabajo" 
						onmouseover="window.status='';return true"  />						
						<input type="image" src="../../images/btn-add.png"  name="btn1" id="bnt1" width="118" height="46" border="0" title="Generar &Oacute;rden de Trabajo"
						onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1); window.status='';return true"
						onmouseout="MM_nbGroup('out')" />						
					</form>										
    		  </div>
			</td>
      		<td width="25%" align="center">
	    		<div align="center">
					<form action="frm_consultarOrdenTrabajo.php">
						<input type="image" src="images/sea-ordentrabajo.png" width="115" height="210" border="0" title="Consultar &Oacute;rden de Trabajo" 
						onmouseover="window.status='';return true"  />
						<input type="image" src="../../images/btn-sea.png" name="btn2" id="bnt2" width="118" height="46" border="0" title="Consultar &Oacute;rden de Trabajo" 
						onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-sea-over.png','',1);window.status='';return true"
					 	onmouseout="MM_nbGroup('out')" />
					</form>	      
	 		  </div>
	  		</td>
			<td width="25%" align="center">
	    		<div align="center">
					<form action="frm_gestionarServiciosExternos.php">
						<input type="image" src="images/upd-ordentrabajoexterna.png" width="110" height="210" border="0" 
						title="Generar y Consultar Ordenes de Trabajo para Servicios Externos" onmouseover="window.status='';return true"  />
						<input type="image" src="../../images/btn-gestionar.png" name="btn3" id="bnt3" width="118" height="46" border="0" 
						title="Generar y Consultar Ordenes de Trabajo para Servicios Externos" onclick="MM_nbGroup('down','group1','btn3','',1)" 
						onmouseover="MM_nbGroup('over','btn3','../../images/btn-gestionar-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')" />
					</form>	      
	 		  </div>
	  		</td>
			<!-- <td width="25%" align="center">
				<form action="frm_gestionarProgMtto.php">
					<input type="image" src="images/ges-progmtto.png" width="115" height="210" border="0" title="Consultar Programa de Mantenimiento" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gestionar.png" name="btn4" id="btn4" width="118" height="46" border="0" title="Registrar Hor&oacute;metro/Odor&oacute;metro de Equipo"
					onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-gestionar-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td> -->
    	</tr>
	</table>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
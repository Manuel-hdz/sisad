<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento de Calidad
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
			//Damos de baja las sesiones que implican el registro de la acta de seguridad e higiene
		if(isset($_SESSION['agenda'])){
			unset($_SESSION['agenda']);
		}
		if(isset($_SESSION['accidentes'])){
			unset($_SESSION['accidentes']);
		}
		if(isset($_SESSION['recorridos'])){
			unset($_SESSION['recorridos']);
		}
		if(isset($_SESSION['visitas'])){
			unset($_SESSION['visitas']);
		}
		if(isset($_SESSION['asistentes'])){
			unset($_SESSION['asistentes']);
		}
		//Damos de baja las sesiones que implican el registro de la acta de incidentes accidentes
		if(isset($_SESSION['actaIncAcc'])){
			unset($_SESSION['actaIncAcc']);
		}
		if(isset($_SESSION['accionesPrevCorr'])){
			unset($_SESSION['accionesPrevCorr']);
		}
		//Quitar de la SESSION los arreglos utilizados en la seccion de Tiempo de Vida Util del Equipo de Seguridad
		if(isset($_SESSION['datosTiempoVidaES']))
			unset($_SESSION['datosTiempoVidaES']);
			
		//Quitar de la SESSION los arreglos utilizados en la seccion de Planes de Contingencia
		if(isset($_SESSION['datosPlanContingencia']))
		unset($_SESSION['datosPlanContingencia']);
		
		if(isset($_SESSION['datosPlanPrincipal']))
			unset($_SESSION['datosPlanPrincipal']);
	
		if(isset($_SESSION['datosGralPlan']))
		unset($_SESSION['datosGralPlan']);
		
		//Damos de baja las sesiones que implican el registro de la acta de incidentes accidentes
		if(isset($_SESSION['actaIncAcc'])){
			unset($_SESSION['actaIncAcc']);
		}
		if(isset($_SESSION['accionesPrevCorr'])){
			unset($_SESSION['accionesPrevCorr']);
		}

		//Archivo para desplegar las alertas internas del departamento
		include("alertas_recordatoriosSI.php");
		desplegarAlertasRecordatorioSI();
		//Archivo para desplegar las alertas internas del departamento
		include("alertas_recordatoriosExternosSI.php");
		//Funcion para desplegar las alertas externas=>Recordatorios de Seguridad
		desplegarAlertasRecordatorioExternoSI();
		//Archivo para desplegar las alertas de los Recorridos
		include("alertas_recorridos.php");
		//Funcion para desplegar las alertas de Recorridos de Seguridad
		desplegarAlertasRecorridos();
		//Archivo para desplegar las alertas de los Planes de Contingencia
		include("alertas_PlanContingencia.php");
		//Funcion para desplegar las alertas de Recorridos de Seguridad
		desplegarAlertasPlanContingencia();

		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:566px; height:478px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
	<table class="tabla_frm" border="0" width="100%" align="center" >
    	<tr>
      		<td width="30%" align="center">
				<form action="menu_actaSeguridadHigiene.php">
					<input type="image" src="images/add-acta-seg.png" width="140" height="200" border="0" title="Acta Comisi&oacute;n Seguridad e Higiene" 
					onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn1" id="bnt1" width="118" height="46" border="0" 
					title="Acta Comisi&oacute;n Seguridad e Higiene"
					onclick="MM_nbGroup('down','group1','btn1','',1)" 
					onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>			
				</td>	
			<td width="40%" align="center">
				<form action="menu_recorridosSeguridad.php">
				  <input name="image" type="image" title="Recorridos Seguridad" 
					onmouseover="window.status='';return true" src="images/add-recorrido-seg.png" width="170" height="220" border="0"/>
				  <input type="image" src="../../images/btn-add.png"  name="btn2" id="bnt2" width="118" height="46" border="0" 
					title="Recorridos Seguridad"
					onclick="MM_nbGroup('down','group1','btn2','',1)" 
					onmouseover="MM_nbGroup('over','btn2','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />
				</form>			
			</td>
			<td width="30%" align="center">
				<form action="menu_planContingencia.php">
					<input type="image" src="images/add-plan-cont.png" width="140" height="200" border="0" title="Planes de Contingencia" 
					onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn3" id="bnt3" width="118" height="46" border="0" 
					title="Planes de Contingencia"
					onclick="MM_nbGroup('down','group1','btn3','',1)" 
					onmouseover="MM_nbGroup('over','btn3','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>			</td>
		</tr>
    </table>
	<table class="tabla_frm" border="0" width="100%" align="center">
		<tr>
      		<td width="50%" align="center">
				<form action="menu_actaIncidentesAccidentes.php">
					<input type="image" src="images/add-acta-acin.png" width="170" height="200" border="0" title="Informe Incidentes/Accidentes" 
					onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn5" id="bnt5" width="118" height="46" border="0" 
					title="Informe Incidentes/Accidentes"
					onclick="MM_nbGroup('down','group1','btn5','',1)" 
					onmouseover="MM_nbGroup('over','btn5','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />
				</form>   	 
			</td>
			<td width="50%" align="center">
				<form action="frm_registrarTiempoVidaES.php">
					<input type="image" src="images/upd-vida-eqs.png" width="160" height="200" border="0" title="Equipo de Seguridad y Protección Personal" 
					onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn4" id="bnt4" width="118" height="46" border="0" 
					title="Equipo de Seguridad y Protección Personal"
					onclick="MM_nbGroup('down','group1','btn4','',1)" 
					onmouseover="MM_nbGroup('over','btn4','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>	    
			</td>
   		</tr>
    	
  	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
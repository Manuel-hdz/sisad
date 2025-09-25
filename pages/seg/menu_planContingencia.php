<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		
		//Quitar de las SESSION los arreglos utilizados en la Modificación del Plan de COntingencia
		if(isset($_SESSION['datosPlanContingencia']))
			unset($_SESSION['datosPlanContingencia']);
		
		if(isset($_SESSION['datosPlanPrincipal'])){
			unset($_SESSION['datosPlanPrincipal']);
		}
		
		if(isset($_SESSION['datosGralPlan']))
			unset($_SESSION['datosGralPlan']);
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
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:566px; height:400px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="545" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="255" align="center">
				<form action="frm_planesContingencia.php">
					<input type="image" src="images/add-plan-cont.png" width="140" height="200" border="0" title="Generar Plan de Contingencia" 
						onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn1" id="bnt1" width="118" height="46" border="0" title="Generar un Plan de Contingencia"
						onclick="MM_nbGroup('down','group1','btn1','',1)" 
						onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>	      </td>
				<td width="255" align="center">
					<form action="frm_modificarPlanContingencia.php">
						<input type="image" src="images/upd-plan-cont.png" width="140" height="200" border="0" title="Modificar o Consultar los Planes de Contingencia Generados" 
							onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-upd.png" name="btn2" id="bnt2" width="118" height="46" border="0" 
						title="Modificar o Consultar los Planes de Contingencia Generados" onclick="MM_nbGroup('down','group1','btn2','',1)"
						onmouseover="MM_nbGroup('over','btn2','../../images/btn-upd-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
    		 		</form>   		  
			</td>
   		</tr>
  	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
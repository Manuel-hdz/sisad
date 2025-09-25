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
		include_once ("alertas.php");
		//Desplegar las alertas registradas en la BD
		desplegarAlertas();
		//Liberamos la sesion utilizada en el reporte correctivo
		unset($_SESSION['datosRptCorrectivos']);?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:492px; height:229px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1" >
  	<table class="tabla_frm" width="800" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reportePreventivo.php">
					<input type="image" onclick="location.href='frm_reportePreventivo.php'" src="images/add-reportepreventivo.png" width="150" height="208" 
					border="0" title="Reporte de Mantenimientos Preventivos" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn1" id="bnt1" width="118" height="46" border="0" 
					title="Reporte de Mantenimientos Preventivos" onclick="MM_nbGroup('down','group1','btn1','',1)" 
					onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
	    		</div>
		  	</td>
			<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteCorrectivo.php">
					<input type="image" onclick="location.href='frm_reporteCorrectivo.php'" src="images/add-reportecorrectivo.png" width="150" height="208" 
					border="0" title="Reporte de Mantenimientos Correctivos" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn2" id="bnt2" width="118" height="46" border="0" 
					title="Reporte de Mantenimientos Correctivos"
					onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseout="MM_nbGroup('out')"
					onmouseover="MM_nbGroup('over','btn2','../../images/btn-gen-over.png','',1);window.estatus='';return true" />
				</form>
	    		</div>
  		  	</td>
			<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reportePreventivoCorrectivo.php">
					<input type="image" onclick="location.hfer='frm_reportePreventivoCorrectivo.php'" src="images/add-reporteprevcor.png" width="150" height="208"
					border="0" title="Reporte de Mantenimientos Preventivos Contra Mantenimientos Correctivos" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn3" id="bnt3" width="118" height="46" border="0" 
					title="Reporte de Mantenimientos Preventivos Contra Mantenimientos Correctivos"
					onclick="MM_nbGroup('down','group1','btn3','',1)"  onmouseout="MM_nbGroup('out')"
					onmouseover="MM_nbGroup('over','btn3','../../images/btn-gen-over.png','',1);window.estatus='';return true"/>
				</form>
	    		</div>
	  		</td>
			<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteOrdenTrabajo.php">
					<input type="image" onclick="location.href='frm_reporteOrdenTrabajo.php'" src="images/add-reporteordentrabajo.png" width="150" height="208" 
					border="0" title="Reporte de &Oacute;rdenes de Trabajo" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn4" id="bnt4" width="118" height="46" border="0" 
					title="Reporte de &Oacute;rdenes de Trabajo" onclick="MM_nbGroup('down','group1','btn4','',1)" 
					onmouseover="MM_nbGroup('over','btn4','../../images/btn-gen-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
	    		</div>
  		  </td>
		  <td align="center">
				<div align="center">
					<input type="image" onclick="location.href='frm_consultarKardexChecador.php'" src="images/add-rep-kardex.png" width="150" height="208" border="0" 
					title="Generar Reporte Kardex" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn10" id="btn10" width="118" height="46" border="0"
					onclick="MM_nbGroup('down','group1','btn10','',1); location.href='frm_consultarKardexChecador.php'" 
					onmouseover="MM_nbGroup('over','btn10','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
					title="Generar Reporte Kardex" />
				</div>
		  </td>
		</tr>
	</table>
	<table class="tabla_frm" width="800" border="0" align="center" cellpadding="5" cellspacing="5">
		<tr>
			<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteEstadistico.php">
					<input type="image" src="images/add-reporteestadistico.png" width="150" height="208" border="0" title="Reporte Estad&Iacute;stico de Fallas"
					onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn5" id="bnt5" width="118" height="46" border="0" 
					title="Reporte Estad&Iacute;stico de Fallas" onclick="MM_nbGroup('down','group1','btn5','',1)" 
					onmouseover="MM_nbGroup('over','btn5','../../images/btn-gen-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
	    		</div>
  		  </td>
			<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteDisponibilidad.php">
					<input type="image" src="images/add-reportedisponibilidad.png" width="150" height="208" border="0" 
					title="Reporte de Disponibilidad de Equipos" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn6" id="bnt6" width="118" height="46" border="0" 
					title="Reporte de Disponibilidad de Equipos" onclick="MM_nbGroup('down','group1','btn6','',1)" 
					onmouseover="MM_nbGroup('over','btn6','../../images/btn-gen-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
	    		</div>
  		  </td>
		  <td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteCostos.php">
					<input type="image" src="images/add-reportecostos.png" width="150" height="208" border="0" title="Reporte de Costos" 
					onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn7" id="bnt7" width="118" height="46" border="0" title="Reporte de Costos"
					onclick="MM_nbGroup('down','group1','btn7','',1)" onmouseout="MM_nbGroup('out')"
					onmouseover="MM_nbGroup('over','btn7','../../images/btn-gen-over.png','',1);window.estatus='';return true" />
				</form>
	    		</div>
  		  </td>
		  <td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteAceites.php">
					<input type="image" src="images/add-reporteaceite.png" width="150" height="208" border="0" title="Reporte de Entrada/Consumo de Aceite" 
					onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn8" id="bnt8" width="118" height="46" border="0" title="Reporte de Entrada/Consumo de Aceite"
					onclick="MM_nbGroup('down','group1','btn8','',1)" onmouseout="MM_nbGroup('out')"
					onmouseover="MM_nbGroup('over','btn8','../../images/btn-gen-over.png','',1);window.estatus='';return true" />
				</form>
	    		</div>
  		  </td>
		  <td align="center">
				<div align="center">
					<input type="image" onclick="location.href='frm_reporteNomina.php'" src="images/add-reporte-nomina.png" width="150" height="208" border="0" 
					title="Reporte de N&oacute;mina de Mantenimiento Superficie" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn7" id="bnt7" width="118" height="46" border="0"
					onclick="MM_nbGroup('down','group1','btn7','',1); location.href='frm_reporteNomina.php'" 
					onmouseover="MM_nbGroup('over','btn7','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
					title="Reporte de N&oacute;mina de Mantenimiento Superficie" />
				</div>
		  </td>
		  <td align="center">
			<form action="frm_reporteRequisiciones.php">
			 	<input type="image" src="images/add-requisicion.png" width="115" height="160" border="0" title="Generar Reporte Requisiciones" 
                onmouseover="window.status='';return true" /><br/>
				<input type="image" src="../../images/btn-gen.png" name="btn12" id="btn12" width="118" height="46" border="0" 
                title="Generar Reporte Requisiciones" 
				onclick="MM_nbGroup('down','group1','btn12','',1)" 
                onmouseover="MM_nbGroup('over','btn12','../../images/btn-gen-over.png','',1);window.status='';return true" 
                onmouseout="MM_nbGroup('out')"/>
			</form>	  		
		  </td>
		</tr>
  	</table>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
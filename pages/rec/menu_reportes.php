<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#parrilla-menu1 {	position:absolute;	left:79px;	top:160px;	width:820px;	height:524px;	z-index:1;}
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="50%" border="0" align="center" cellpadding="5" cellspacing="5">
<tr>
      		<td width="20%" align="center">
				<form action="frm_reporteAsistencia.php">
					<input type="image" src="images/add-rep-asistencia.png" width="125" height="180" border="0" title="Generar Reporte Asistencia" 
                	onmouseover="window.status='';return true"/>
					<input type="image" src="../../images/btn-rep-asistencias.png"  name="btn1" id="bnt1" width="118" height="46" border="0"
                    title="Generar Reporte Asistencia"
					onclick="MM_nbGroup('down','group1','btn1','',1)" 
                    onmouseover="MM_nbGroup('over','btn1','../../images/btn-rep-asistencias-over.png','',1); window.status='';return true" 
                    onmouseout="MM_nbGroup('out')" />	
				</form>			
			</td>
			<td width="20%" align="center">
				<form action="frm_reporteIncapacidades.php">
					<input type="image" src="images/add-rep-incapacidad.png" width="125" height="180" border="0" title="Generar Reporte Incapacidades" 
                	onmouseover="window.status='';return true" />
					<input type="image" src="../../images/btn-rep-incapacidades.png" name="btn2" id="bnt2" width="118" height="46" border="0" 
                    title="Generar Reporte Incapacidades" 
					onclick="MM_nbGroup('down','group1','btn2','',1)" 
                    onmouseover="MM_nbGroup('over','btn2','../../images/btn-rep-incapacidades-over.png','',1);window.status='';return true" 
                    onmouseout="MM_nbGroup('out')"/>
				</form>	  		
            </td>
            <td width="20%" align="center">
                <form action="frm_reporteAusentismo.php">
                    <input type="image" src="images/add-rep-ausentismo.png" width="125" height="180" border="0" title="Generar Reporte Ausentismo" 
                    onmouseover="window.status='';return true" />
                    <input type="image" src="../../images/btn-rep-ausentismo.png" name="btn3" id="bnt3" width="118" height="46" border="0"
                    title="Generar Reporte Ausentismo" 
                    onclick="MM_nbGroup('down','group1','btn3','',1)" 
                    onmouseover="MM_nbGroup('over','btn3','../../images/btn-rep-ausentismo-over.png','',1);window.status='';return true" 
                    onmouseout="MM_nbGroup('out')"/>
                </form>	  		
			</td>
            <td width="20%" align="center">
				<form action="frm_reporteReclutamiento.php">
			 		<input type="image" src="images/add-rep-reclutamiento.png" width="125" height="180" border="0" title="Generar Reporte Reclutamiento" 
                	onmouseover="window.status='';return true" />
					<input type="image" src="../../images/btn-rep-reclutamiento.png" name="btn4" id="bnt4" width="118" height="46" border="0" 
                    title="Generar Reporte Reclutamiento" 
					onclick="MM_nbGroup('down','group1','btn4','',1)" 
                    onmouseover="MM_nbGroup('over','btn4','../../images/btn-rep-reclutamiento-over.png','',1);window.status='';return true" 
                    onmouseout="MM_nbGroup('out')"/>
				</form>	  		
        	</td>
			  <td width="20%" align="center">
				<form action="frm_reportekardex.php">
			 		<input type="image" src="images/add-rep-kardex.png" width="125" height="180" border="0" title="Generar Reporte Kardex" 
                	onmouseover="window.status='';return true" />
					<input type="image" src="../../images/btn-rep-kardex.png" name="btn10" id="bnt10" width="118" height="46" border="0" 
                    title="Generar Reporte Kardex" 
					onclick="MM_nbGroup('down','group1','btn10','',1)" 
                    onmouseover="MM_nbGroup('over','btn10','../../images/btn-rep-kardex-over.png','',1);window.status='';return true" 
                    onmouseout="MM_nbGroup('out')"/>
				</form>	  		
        	</td>
			<td align="center">
				<form action="frm_reporteRequisiciones.php">
			 		<input type="image" src="images/add-requisicion.png" width="115" height="160" border="0" title="Generar Reporte Requisiciones" 
                	onmouseover="window.status='';return true" />
					<input type="image" src="../../images/btn-gen.png" name="btn11" id="btn11" width="118" height="46" border="0" 
                    title="Generar Reporte Requisiciones" 
					onclick="MM_nbGroup('down','group1','btn11','',1)" 
                    onmouseover="MM_nbGroup('over','btn11','../../images/btn-gen-over.png','',1);window.status='';return true" 
                    onmouseout="MM_nbGroup('out')"/>
				</form>	  		
			</td>
  	  </tr>
	</table>
	<br />
  	<table class="tabla_frm" width="50%" border="0" align="center" cellpadding="5" cellspacing="5">
<tr>
      		<td width="20%" align="center">
				<form action="frm_reporteAltasBajas.php">
		 		  <input type="image" src="images/add-rep-ab.png" width="125" height="180" border="0" title="Generar Reportes Altas y Bajas" 
                    	onmouseover="window.status='';return true" />
		 		  <input type="image" src="../../images/btn-rep-ab.png" name="btn5" id="btn5" width="118" height="46" border="0" 
                        title="Generar Reportes Altas y Bajas" 
						onclick="MM_nbGroup('down','group1','btn5','',1)" 
                        onmouseover="MM_nbGroup('over','btn5','../../images/btn-rep-ab-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>			
			</td>
	  <td width="20%" align="center">
				<form action="frm_reportePrestamos.php">
                  <input type="image"src="images/add-rep-prestamo.png" width="125" height="180" border="0" title="Generar Reporte Pr&eacute;stamos"
                        onmouseover="window.status='';return true" />
                        <input type="image" src="../../images/btn-rep-prestamos.png"  name="btn6" id="btn6" width="118" height="46" border="0" 
                        title="Generar Reporte Pr&eacute;stamos" 
						onclick="MM_nbGroup('down','group1','btn6','',1)"
                        onmouseover="MM_nbGroup('over','btn6','../../images/btn-rep-prestamos-over.png','',1);window.status='';return true" 
                        onmouseout="MM_nbGroup('out')"/> 
				</form>				
      </td>
            <td width="20%" align="center">
				<form action="frm_reporteCapacitaciones.php">
		    	  <input type="image"src="images/add-rep-capacitacion.png" width="125" height="180" border="0" title="Generar Reporte Capacitaciones" 
                    	onmouseover="window.status='';return true" />
				    	<input type="image" src="../../images/btn-rep-capacitaciones.png"  name="btn7" id="btn7" width="118" height="46" border="0" 
                        title="Generar Reporte Capacitaciones" 
						onclick="MM_nbGroup('down','group1','btn7','',1)"
                        onmouseover="MM_nbGroup('over','btn7','../../images/btn-rep-capacitaciones-over.png','',1);window.status='';return true" 
                        onmouseout="MM_nbGroup('out')"/>
				</form>				
            </td>
            <td width="20%" align="center">
				<form action="menu_reporte_nomina.php">
		    	  <input type="image"src="images/add-rep-nomina.png" width="125" height="180" border="0" title="Generar Reporte N&oacute;mina" 
                    	onmouseover="window.status='';return true" />
				    	<input type="image" src="../../images/btn-rep-nomina.png"  name="btn8" id="btn8" width="118" height="46" border="0" 
                        title="Generar Reporte N&oacute;mina" 
						onclick="MM_nbGroup('down','group1','btn8','',1)"
                        onmouseover="MM_nbGroup('over','btn8','../../images/btn-rep-nomina-over.png','',1);window.status='';return true" 
                        onmouseout="MM_nbGroup('out')"/>
				</form>				
            </td>
            <td width="20%" align="center">
				<form action="frm_reportePagoSeguroSocial.php">
		    	  <input type="image"src="images/add-rep-pagoss.png" width="125" height="180" border="0" title="Generar Reporte Seguro Social" 
                    	onmouseover="window.status='';return true" />
					    <input type="image" src="../../images/btn-rep-pagoss.png"  name="btn9" id="btn9" width="118" height="46" border="0" 
                        title="Generar Reporte Seguro Social" 
						onclick="MM_nbGroup('down','group1','btn9','',1)"
                        onmouseover="MM_nbGroup('over','btn9','../../images/btn-rep-pagoss-over.png','',1);window.status='';return true" 
                        onmouseout="MM_nbGroup('out')"/>
				</form>				
            </td>
			<td width="20%" align="center">
				<form action="frm_reporteHistorico.php">
		    	  <input type="image"src="images/add-rep-historial.png" width="125" height="180" border="0" title="Generar Reporte Historico" 
                    	onmouseover="window.status='';return true" />
					    <input type="image" src="../../images/btn-rep-historial.png"  name="btn11" id="btn11" width="118" height="46" border="0" 
                        title="Generar Reporte Historico del Personal" 
						onclick="MM_nbGroup('down','group1','btn11','',1)"
                        onmouseover="MM_nbGroup('over','btn11','../../images/btn-rep-historial-over.png','',1);window.status='';return true" 
                        onmouseout="MM_nbGroup('out')"/>
				</form>				
            </td> 
   	  </tr>
  	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
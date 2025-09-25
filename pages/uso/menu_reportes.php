<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		//echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:610px; height:229px;	z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="656" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteHistorialesClinicos.php">
					<input type="image" src="images/add-rep-examenMedico.png"  width="120" height="160" border="0" title="Generar Reporte de Ex&aacute;menes M&eacute;dicos" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn1" id="bnt1" width="118" height="46" border="0" title="Generar Reporte de Ex&aacute;menes M&eacute;dicos"
					onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
	    		</form>
    		  </div>
			</td>
      		<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteActSemanal.php">
					<input type="image" src="images/add-rep-semanalAct.png" width="130" height="160" border="0" title="Generar Reporte Semanal de Actividades" onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn2" id="bnt2" width="118" height="46" border="0" title="Gestionar Reporte Semanal de Actividades" 
					onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-gen-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
	    		</form>
	 		  </div>
	  		</td>
			<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteCensosConsultas.php">
					<input type="image" src="images/add-rep-censCons.png" width="130" height="160" border="0" title="Generar Reporte de Censos y Consultas Realizadas" onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn3" id="bnt3" width="118" height="46" border="0" title="Generar Reporte de Censos y Consultas Realizadas" 
					onclick="MM_nbGroup('down','group1','btn3','',1)" onmouseover="MM_nbGroup('over','btn3','../../images/btn-gen-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
	    		</form>
	 		  </div>
	  		</td>
			<td width="33%" align="center">
	    		<div align="center">
				<form action="frm_reporteResultadosExamenes.php">
					<input type="image" src="images/add-rep-resexamed.png" width="130" height="160" border="0" title="Generar Reporte de Resultados de Ex&aacute;menes Periodicos" onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn4" id="bnt4" width="118" height="46" border="0" title="Generar Reporte de Resultados de Ex&aacute;menes Periodicos" 
					onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-gen-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
	    		</form>
	 		  </div>
	  		</td>
			<td align="center">
				<form action="frm_reporteRequisiciones.php">
				<input type="image" src="images/add-requisicion.png" width="115" height="160" border="0" title="Generar Reporte Requisiciones" 
               	onmouseover="window.status='';return true" />
				<input type="image" src="../../images/btn-gen.png" name="btn5" id="btn5" width="118" height="46" border="0" 
				title="Generar Reporte Requisiciones" 
				onclick="MM_nbGroup('down','group1','btn5','',1)" 
				onmouseover="MM_nbGroup('over','btn5','../../images/btn-gen-over.png','',1);window.status='';return true" 
                onmouseout="MM_nbGroup('out')"/>
				</form>	  		
			</td>
    	</tr>
  	</table>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
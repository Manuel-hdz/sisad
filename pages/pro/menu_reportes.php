<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
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
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:405px; height:229px;	z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="388" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="50%" align="left">
	    		<div align="left">
				<form action="frm_generarReporte.php">
					<input type="image" src="images/add-rep-produccion.png"  width="120" height="160" border="0" title="Generar Reporte de Producci&oacute;n Diaria/Mensual" 
					onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn1" id="btn1" width="118" height="46" border="0" title="Generar Reporte de Producci&oacute;n Diaria/Mensual"
					onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')" />
	    		</form>
    		  	</div>
				<td align="center">
					<form action="frm_reporteRequisiciones.php">
			 		<input type="image" src="images/add-requisicion.png" width="115" height="160" border="0" title="Generar Reporte Requisiciones" 
                	onmouseover="window.status='';return true" />
					<input type="image" src="../../images/btn-gen.png" name="btn2" id="btn2" width="118" height="46" border="0" 
                    title="Generar Reporte Requisiciones" 
					onclick="MM_nbGroup('down','group1','btn2','',1)" 
                    onmouseover="MM_nbGroup('over','btn2','../../images/btn-gen-over.png','',1);window.status='';return true" 
                    onmouseout="MM_nbGroup('out')"/>
					</form>	  		
				</td>
			</td>
    	</tr>
  	</table>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
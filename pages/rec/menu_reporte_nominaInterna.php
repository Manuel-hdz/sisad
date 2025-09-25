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
				<form action="frm_consultarNominaDesarrollo.php">
					<input type="image"src="images/add-rep-nomina.png" width="125" height="180" border="0" title="Generar Reporte N&oacute;mina Desarrollo" 
						onmouseover="window.status='';return true" />
				    	<input type="image" src="../../images/btn-rep-nomina-desarrollo.png"  name="btn1" id="btn1" width="118" height="46" border="0" 
                        title="Generar Reporte N&oacute;mina Desarrollo" 
						onclick="MM_nbGroup('down','group1','btn1','',1)"
                        onmouseover="MM_nbGroup('over','btn1','../../images/btn-rep-nomina-desarrollo-over.png','',1);window.status='';return true" 
                        onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td width="20%" align="center">
				<form action="frm_consultarNominaZarpeo.php">
					<input type="image"src="images/add-rep-nomina.png" width="125" height="180" border="0" title="Generar Reporte N&oacute;mina Zarpeo" 
						onmouseover="window.status='';return true" />
				    	<input type="image" src="../../images/btn-rep-nomina-zarpeo.png"  name="btn2" id="btn2" width="118" height="46" border="0" 
                        title="Generar Reporte N&oacute;mina Zarpeo" 
						onclick="MM_nbGroup('down','group1','btn2','',1)"
                        onmouseover="MM_nbGroup('over','btn2','../../images/btn-rep-nomina-zarpeo-over.png','',1);window.status='';return true" 
                        onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
		</tr>
	</table>
	<br>
	<table class="tabla_frm" width="50%" border="0" align="center" cellpadding="5" cellspacing="5">
		<tr>
      		<td width="20%" align="center">
				<form action="frm_consultarNominaMam.php">
					<input type="image"src="images/add-rep-nomina.png" width="125" height="180" border="0" title="Generar Reporte N&oacute;mina Mantenimiento Mina" 
						onmouseover="window.status='';return true" />
				    	<input type="image" src="../../images/btn-rep-nomina-mam.png"  name="btn3" id="btn3" width="118" height="46" border="0" 
                        title="Generar Reporte N&oacute;mina Mantenimiento Mina" 
						onclick="MM_nbGroup('down','group1','btn3','',1)"
                        onmouseover="MM_nbGroup('over','btn3','../../images/btn-rep-nomina-mam-over.png','',1);window.status='';return true" 
                        onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td width="20%" align="center">
				<form action="frm_consultarNominaMac.php">
					<input type="image"src="images/add-rep-nomina.png" width="125" height="180" border="0" title="Generar Reporte N&oacute;mina Mantenimiento Superficie" 
						onmouseover="window.status='';return true" />
				    	<input type="image" src="../../images/btn-rep-nomina-mac.png"  name="btn4" id="btn4" width="118" height="46" border="0" 
                        title="Generar Reporte N&oacute;mina Mantenimiento Superficie" 
						onclick="MM_nbGroup('down','group1','btn4','',1)"
                        onmouseover="MM_nbGroup('over','btn4','../../images/btn-rep-nomina-mac-over.png','',1);window.status='';return true" 
                        onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td width="20%" align="center">
				<form action="frm_consultarNominaAdministracion.php">
					<input type="image"src="images/add-rep-nomina.png" width="125" height="180" border="0" title="Generar Reporte N&oacute;mina Administraci&oacute;n" 
						onmouseover="window.status='';return true" />
				    	<input type="image" src="../../images/btn-rep-nomina-administracion.png"  name="btn5" id="btn5" width="118" height="46" border="0" 
                        title="Generar Reporte N&oacute;mina Administraci&oacute;n" 
						onclick="MM_nbGroup('down','group1','btn5','',1)"
                        onmouseover="MM_nbGroup('over','btn5','../../images/btn-rep-nomina-administracion-over.png','',1);window.status='';return true" 
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
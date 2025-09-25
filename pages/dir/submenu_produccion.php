<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	//if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
	//	echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	//}
	//else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />

    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:70px; top:160px; width:936px; height:229px;	z-index:1; }
		#parrila-volver{ position:absolute; left:977px; top:630px; width:26px; height:29px;z-index:1; }
		-->
    </style>
</head>
<body onfocus="borrarHistorial()">
	<div id="parrilla-menu1">
	<table class="tabla_frm" width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="100%" align="center">
				<br><br><br><br><br>
	    		<div align="center">
				<form action="frm_repProdAvancePpto.php">
					<input type="image" src="images/rep-ger-1.png" name="icon1" id="icon1" width="200" height="200" border="0" title="Reporte Mensual de Avance Presupuestado contra Avance Real" 
					onclick="MM_nbGroup('down','group1','icon1','',1)" onmouseover="MM_nbGroup('over','icon1','images/rep-ger-1-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
	    		</form>
    		  </div>
			</td>
		</tr>
	</table>
	</div>
	
	<div align="center" id="parrila-volver">
	<form action="menu_concreto.php">
		<input type="image" src="images/back.png" name="back" id="back" width="50" height="50" border="0" title="Subir un Nivel" 
		onmouseover="window.status='';return true" onclick="borrarHistorial();"/>
	</form>
	</div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
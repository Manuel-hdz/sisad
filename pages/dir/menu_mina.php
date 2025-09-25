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
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:827px; height:229px;	z-index:1; }
		-->
    </style>
</head>
<body onfocus="borrarHistorial()">
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
	<br /><br /><br /><br /><br />
    	<tr>
      		<td width="33%" align="center">
	    		<div align="center">
				<form action="submenu_desarrollo.php">
					<input type="image" src="images/logo-dir-des.png" name="icon1" id="icon1" width="200" height="200" border="0" title="M&oacute;dulo de Desarrollo" 
					onclick="MM_nbGroup('down','group1','icon1','',1)" onmouseover="MM_nbGroup('over','icon1','images/dir-des-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
	    		</form>
    		  </div>
			</td>
      		<td width="33%" align="center">
	    		<div align="center">
				<form action="submenu_topografia.php">
					<input type="image" src="images/logo-dir-top.png" name="icon2" id="icon2" width="200" height="200" border="0" title="M&oacute;dulo de Topograf&iacute;a" 
					onclick="MM_nbGroup('down','group1','icon2','',1)" onmouseover="MM_nbGroup('over','icon2','images/dir-top-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
	    		</form>
	 		  </div>
	  		</td>
			<td width="33%" align="center">
	    		<div align="center">
				<form action="submenu_mtto.php?tipo=Mina" method="post">
					<input type="image" src="images/logo-dir-mtm.png" name="icon3" id="icon3" width="200" height="200" border="0" title="M&oacute;dulo de Mantenimiento Mina" 
					onclick="MM_nbGroup('down','group1','icon3','',1)" onmouseover="MM_nbGroup('over','icon3','images/dir-mtm-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
	    		</form>
	 		  </div>
	  		</td>
    	</tr>
  	</table>
</div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
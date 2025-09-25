<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
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
				<form action="frm_registrarNomina.php">
					<input type="image" src="images/add-registrarNomina.png" width="170" height="200" border="0" title="Generar N&oacute;mina de Pago con o sin Bonos" onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png" name="btn3" id="bnt3" width="118" height="46" border="0" title="Generar N&oacute;mina de Pago con o sin Bonos" 
					onclick="MM_nbGroup('down','group1','btn3','',1)" onmouseover="MM_nbGroup('over','btn3','../../images/btn-add-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
	    		</form>
	 		  </div>
	  		</td>
    	</tr>
  	</table>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		
		//Libera los datos guardados en la SESSION
		unset($_SESSION['detalleVenta']);
		unset($_SESSION['totalVenta']);
	
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
<!--
#parrilla-menu1 {position:absolute;left:101px;top:159px;width:240px;height:272px;z-index:1;}
-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  		<table border="0" align="center" cellpadding="5" cellspacing="5" width="100%">
    		<tr>
      			<td align="center" width="50%">
					<input type="image" onclick="location.href='frm_detallesVenta.php'" src="images/add-venta.png" width="147" height="179" border="0" 
                    	title="Registrar Venta" />
					<input type="image" src="../../images/btn-reg.png" name="btn1" id="bnt1" width="118" height="46" border="0" 
                   		onclick="MM_nbGroup('down','group1','btn1','',1); location.href='frm_detallesVenta.php'" 
						onmouseover="MM_nbGroup('over','btn1','../../images/btn-reg-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"
                        title="Registrar Venta" />
		 		 </td>
    		</tr>
  		</table>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
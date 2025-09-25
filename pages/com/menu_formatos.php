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
		//Incluir el archivo que maneja las alertas de las Requisiciones Enviadas
		include_once("alertas.php");
		//Desplegar las alertas registradas en la BD
		//desplegarAlertas();
		/***********************************ALMACEN**********************************************/
		//Incluir el archivo que maneja las alertas de las Requisiciones Enviadas
		include_once("alertasAlm.php");
		//Desplegar las alertas registradas en la BD
		desplegarAlertasAlmacen();
		/*********************************ALERTAS PRIORIDAD*******************************/
		include("alertasPrioridad.php");
		desplegarAlertasPrioridad();
		/*****************************************************************************/
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:570px; height:240px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
		<table border="0" align="center" cellpadding="5" cellspacing="5" width="100%">
 		   	<tr>
      			<td align="center" width="33%">
					<input type="image" onclick="location.href='frm_formatos.php?doc=pedido'" src="images/add-pedido-formato.png" width="102" height="158"
                     border="0" title="Descargar Formato de Pedido" /> 
					<input type="image" src="../../images/btn-gen.png" name="btn1" id="bnt1" width="118" height="46" border="0"
                     onclick="MM_nbGroup('down','group1','btn1','',1); location.href='frm_formatos.php?doc=pedido'" 
					onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" title="Descargar Formato de Pedido" />
                </td>
                <td align="center" width="33%">
					<input type="image" onclick="location.href='frm_formatos.php?doc=cotizacion'" src="images/add-cotizacion-formato.png" width="102" height="158"
                     border="0" title="Descargar Formato de Cotizaci&oacute;n" /> 
					<input type="image" src="../../images/btn-gen.png" name="btn2" id="bnt2" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn2','',1); location.href='frm_formatos.php?doc=cotizacion'" 
					onmouseover="MM_nbGroup('over','btn2','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Descargar Formato de Cotizaci&oacute;n" />
				</td>
	  			<td align="center" width="34%">
					<input type="image" onclick="location.href='frm_formatos.php?doc=cheque'" src="images/add-cheque-formato.png" width="102" height="158" border="0"
                     title="Descargar Formato de Cheque" /> 
					<input type="image" src="../../images/btn-gen.png" name="btn3" id="bnt3" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn3','',1); location.href='frm_formatos.php?doc=cheque'" 
					onmouseover="MM_nbGroup('over','btn3','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" title="Descargar Formato de Cheque" />
				</td>
			</tr>
		</table>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
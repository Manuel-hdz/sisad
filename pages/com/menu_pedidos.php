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
		//Quitar Arreglos de Session que correspondan a la pagina actual
		//Registro de detalles de Pedido
		if (isset($_SESSION["detallespedido"]))
			unset($_SESSION["detallespedido"]);
	
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
<!--
#parrilla-menu1 {position:absolute;left:101px;top:159px;width:806px;height:272px;z-index:1;}
-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
		<table border="0" align="center" cellpadding="5" cellspacing="5" width="100%">
    		<tr>
				<!--
				<td align="center">
					<input type="image" onclick="location.href='frm_detallesDelPedido2.php'" src="images/add-pedido.png" width="147" height="179" border="0" 
                    title="Registrar Pedido" />
                    <input type="image" src="../../images/btn-reg.png" name="btn1" id="btn1" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn1','',1); location.href='frm_detallesDelPedido.php'" 
					onmouseover="MM_nbGroup('over','btn1','../../images/btn-reg-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" 
                    title="Registrar Pedido" />
	  			</td>
				-->
				<td align="center">
					<input type="image" onclick="location.href='frm_consultadePedido.php'" src="images/sea-pedido.png" width="147" height="179" border="0"
                    title="Consultar Pedido" />
					<br>
					<input type="image" src="../../images/btn-sea.png" name="btn2" id="btn2" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn2','',1); location.href='frm_consultadePedido.php'" 
					onmouseover="MM_nbGroup('over','btn2','../../images/btn-sea-over.png','',1)" onmouseout="MM_nbGroup('out')" title="Consultar Pedido" />
	  			</td>
				<!--<td align="center">
					<input type="image" onclick="location.href=''" src="images/upd-pedido.png" width="147" height="179" border="0"
                    title="Modificar Pedido" />
					<input type="image" src="../../images/btn-upd.png" name="btn3" id="btn3" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn3','',1); location.href=''" 
					onmouseover="MM_nbGroup('over','btn3','../../images/btn-upd-over.png','',1)" onmouseout="MM_nbGroup('out')" title="Modificar Pedido" />
	  			</td>
				<td align="center">
					<input type="image" onclick="location.href='frm_complementarPedido.php'" src="images/upd-pedido.png" width="147" height="179" border="0"
                    title="Complementar Pedido" />
					<input type="image" src="../../images/btn-com.png" name="btn3" id="bnt3" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn3','',1); location.href='frm_complementarPedido.php'" 
					onmouseover="MM_nbGroup('over','btn3','../../images/btn-com-over.png','',1)" onmouseout="MM_nbGroup('out')" title="Complementar Pedido" />
	  			</td>-->
				<td align="center">
					<input type="image" onclick="location.href='frm_recibirPedidos.php'" src="images/seb-pedido.png" width="147" height="179" border="0"
                    title="Complementar Pedido" />
					<br>
					<input type="image" src="../../images/btn-reg.png" name="bnt4" id="bnt4" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','bnt4','',1); location.href='frm_recibirPedidos.php'" 
					onmouseover="MM_nbGroup('over','bnt4','../../images/btn-reg-over.png','',1)" onmouseout="MM_nbGroup('out')" title="Complementar Pedido" />
	  			</td>
    		</tr>
  		</table>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
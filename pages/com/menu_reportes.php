<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Compras
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
			#parrilla-menu1 {
				position: absolute;
				left: 100px;
				top: 160px;
				width: 540px;
				height: 270px;
				z-index: 1;
			}
		</style>
	</head>

	<body>
		<div id="parrilla-menu1">
			<table border="0" align="center" cellpadding="5" cellspacing="5" width="100%">
				<tr>
					<td align="center">
						<input type="image" onclick="location.href='frm_reporteCompras.php'"
							src="images/add-reportecompras.png" width="147" height="179" border="0"
							title="Generar Reporte de Compras" />
						<input type="image" src="../../images/btn-gen.png" name="btn1" id="btn1" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn1','',1); location.href='frm_reporteCompras.php'"
							onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Generar Reporte de Compras" />
					</td>
					<!--
				<td align="center">
	    			<input type="image" onclick="location.href='frm_reporteVentas.php'" src="images/add-reporteventas.png" width="147" height="179" border="0" 
                   	title="Generar Reporte de Ventas" />
					<input type="image" src="../../images/btn-gen.png" name="btn2" id="btn2" width="118" height="46" border="0"
                    onclick="MM_nbGroup('down','group1','btn2','',1); location.href='frm_reporteVentas.php'" 
				    onmouseover="MM_nbGroup('over','btn2','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Generar Reporte de Ventas" />
	  			</td>
      			<td align="center">
                    <input type="image" onclick="location.href='frm_reporteCompraVenta.php'" src="images/add-reportecompraventa.png" width="147" height="179" border="0"
                     title="Generar Reporte Comparativo de Compras y Ventas" />
                    <input type="image" src="../../images/btn-gen.png" name="btn3" id="btn3" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn3','',1); location.href='frm_reporteCompraVenta.php'" 
                    onmouseover="MM_nbGroup('over','btn3','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Generar Reporte Comparativo de Compras y Ventas" />
	  			</td>
				-->
					<td align="center">
						<input type="image" onclick="location.href='frm_reportePedidos.php'"
							src="images/add-pedido-formato.png" width="147" height="179" border="0"
							title="Generar Reporte de Pedidos" />
						<input type="image" src="../../images/btn-gen.png" name="btn4" id="btn4" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn4','',1); location.href='frm_reportePedidos.php'"
							onmouseover="MM_nbGroup('over','btn4','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Generar Reporte de Pedidos" />
					</td>
					<td align="center">
						<input type="image" onclick="location.href='frm_reporteRequisiciones.php'"
							src="images/add-reporterequisicion.png" width="147" height="179" border="0"
							title="Generar Reporte de Requisiciones" />
						<input type="image" src="../../images/btn-gen.png" name="btn5" id="btn5" width="118" height="46"
							border="0"
							onclick="MM_nbGroup('down','group1','btn5','',1); location.href='frm_reporteRequisiciones.php'"
							onmouseover="MM_nbGroup('over','btn5','../../images/btn-gen-over.png','',1)"
							onmouseout="MM_nbGroup('out')" title="Generar Reporte de Requisiciones" />
					</td>
				</tr>
			</table>
		</div>
	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
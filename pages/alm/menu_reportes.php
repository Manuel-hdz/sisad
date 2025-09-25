<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Incluir el archivo que maneja las alertas de los materiales que han rebasado el punto de reorden 
		include_once ("alertas.php");
		include_once ("alertasRH.php");
		include_once ("alertasKiosco.php");
		//Desplegar las alertas registradas en la BD
		desplegarAlertas();
		desplegarAlertasRH();
		desplegarAlertasKiosco();
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:492px; height:229px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1" >
  	<table width="600" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="300" align="center">
	    		<div align="center">
				<form action="frm_reporteInventario.php">
					<input type="image" src="images/add-reporteinventario.png" width="115" height="158" border="0" title="Reporte de Inventario" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-rep-inventario.png" name="btn1" id="bnt1" width="118" height="46" border="0" title="Reporte de Inventario"
					onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-rep-inventario-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
	    		</div>
			</td>
      		<td width="300" align="center">
	    		<div align="center">
				<form action="frm_reporteREA.php">
					<input type="image" src="images/add-reporterea.png" width="115" height="158" border="0" title="Reporte REA" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-rep-rea.png" name="btn2" id="bnt2" width="118" height="46" border="0" title="Reporte REA"
					onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-rep-rea-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
	    		</div>
	  		</td>
		</tr>
		<tr>
	  		<td width="300" align="center">
	    		<div align="center">
				<form action="frm_reporteOrdenCompra.php">
					<input type="image" src="images/add-reporteordendecompra.png" width="115" height="158" border="0" title="Reporte Orden de Compra" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-rep-oc.png" name="btn3" id="bnt3" width="118" height="46" border="0" title="Reporte Orden de Compra"
					onclick="MM_nbGroup('down','group1','btn3','',1)" onmouseover="MM_nbGroup('over','btn3','../../images/btn-rep-oc-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
	    		</div>
	  		</td>
			<td width="300" align="center">
	    		<div align="center">
				<form action="frm_reporteSalidas.php">
					<input type="image" src="images/add-reportesalidas.png" width="115" height="158" border="0" title="Reporte Orden de Compra" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-rep-salidas.png" name="btn4" id="bnt4" width="118" height="46" border="0" title="Reporte de Salidas de Material"
					onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-rep-salidas-over.png','',1);window.estatus='';return true" onmouseout="MM_nbGroup('out')"/>
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
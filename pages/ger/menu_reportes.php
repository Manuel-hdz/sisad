<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Téncica
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
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:540px; height:270px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  		<table border="0" align="center" cellpadding="5" cellspacing="5" width="100%">
    		<tr>
                <td align="center">
                    <input type="image" onclick="location.href='frm_reporteCuadrillas.php'" src="images/add-reporte-cuadrilla.png" width="115" height="160" border="0" 
                    title="Reporte de Producci&oacute;n por Cuadrillas" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn1" id="btn1" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn1','',1); location.href='frm_reporteCuadrillas.php'" 
					onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Reporte de Producci&oacute;n por Cuadrillas" />
		  		</td>
      			<td align="center">
	    			<input type="image" onclick="location.href='frm_reporteComparativoMina.php'" src="images/add-reporte-comp-mina.png" width="115" height="160" border="0" 
                   	title="Reporte Comparativo de Minas de Zarpeo y Pisos" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn2" id="btn2" width="118" height="46" border="0"
                    onclick="MM_nbGroup('down','group1','btn2','',1); location.href='frm_reporteComparativoMina.php'" 
				    onmouseover="MM_nbGroup('over','btn2','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Reporte Comparativo de Minas de Zarpeo y Pisos" />
	  			</td>
				<td align="center">
	    			<input type="image" onclick="location.href='frm_reporteNomina.php'" src="images/add-reporte-nomina.png" width="115" height="160" border="0" 
                   	title="Reporte de N&oacute;mina de Zarpeo" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn7" id="btn7" width="118" height="46" border="0"
                    onclick="MM_nbGroup('down','group1','btn7','',1); location.href='frm_reporteNomina.php'" 
				    onmouseover="MM_nbGroup('over','btn7','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Reporte de N&oacute;mina de Zarpeo" />
	  			</td>
				<td align="center">
					<form action="frm_reporteRequisiciones.php">
			 		<input type="image" src="images/add-requisicion.png" width="115" height="160" border="0" title="Generar Reporte Requisiciones" 
                	onmouseover="window.status='';return true" />
					<input type="image" src="../../images/btn-gen.png" name="btn6" id="btn6" width="118" height="46" border="0" 
                    title="Generar Reporte Requisiciones" 
					onclick="MM_nbGroup('down','group1','btn6','',1)" 
                    onmouseover="MM_nbGroup('over','btn6','../../images/btn-gen-over.png','',1);window.status='';return true" 
                    onmouseout="MM_nbGroup('out')"/>
					</form>	  		
				</td>
			</tr>
			<tr>
      			<td align="center">
                    <input type="image" onclick="location.href='frm_reporteAnual.php'" src="images/add-reporte-anual.png" width="115" height="160" border="0"
                     title="Reporte Comparativo Total de Minas, Colados y V&iacute;a Seca" /><br/>
                    <input type="image" src="../../images/btn-gen.png" name="btn3" id="btn3" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn3','',1); location.href='frm_reporteAnual.php'" 
                    onmouseover="MM_nbGroup('over','btn3','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Reporte Comparativo Total de Minas, Colados y V&iacute;a Seca" />
	  			</td>
				<td align="center">
                    <input type="image" onclick="location.href='frm_reporteComparativoMensual.php'" src="images/add-reporte-mensual.png" width="115" height="160" border="0"
                     title="Reporte Comparativo Mensual" /><br/>
                    <input type="image" src="../../images/btn-gen.png" name="btn4" id="btn4" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn4','',1); location.href='frm_reporteComparativoMensual.php'" 
                    onmouseover="MM_nbGroup('over','btn4','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Reporte Comparativo Mensual" />
	  			</td>
				<td align="center">
                    <input type="image" onclick="location.href='frm_consultarKardexChecador.php'" src="images/add-rep-kardex.png" width="115" height="160" border="0"
                     title="Generar Reporte Kardex" /><br/>
                    <input type="image" src="../../images/btn-gen.png" name="btn10" id="btn10" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn10','',1); location.href='frm_consultarKardexChecador.php'" 
                    onmouseover="MM_nbGroup('over','btn10','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Generar Reporte Kardex" />
	  			</td>
				<td align="center">
                    <input type="image" onclick="location.href='frm_reporteSalidas.php'" src="images/add-reportesalidas.png" width="115" height="160" border="0"
                     title="Reporte de Salidas Almacen" /><br/>
                    <input type="image" src="../../images/btn-gen.png" name="btn5" id="btn5" width="118" height="46" border="0" 
                    onclick="MM_nbGroup('down','group1','btn5','',1); location.href='frm_reporteSalidas.php'" 
                    onmouseover="MM_nbGroup('over','btn5','../../images/btn-gen-over.png','',1)" onmouseout="MM_nbGroup('out')" 
                    title="Reporte de Salidas Almacen" />
	  			</td>
    		</tr>
		</table>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
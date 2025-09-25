<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

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
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#parrilla-menu1 {position:absolute; left:100px; top:160px; width:806px; height:400px; z-index:1; }
		-->
    </style>
</head>
<body>
    <div id="parrilla-menu1">
        <table width="780" border="0" align="center" cellpadding="5" cellspacing="5">
            <tr>
                <td width="260" align="center">
                    <form action="frm_agregarCliente.php">
                        <input type="image" src="images/add-cliente.png" width="115" height="158" border="0" title="Agregar Cliente"
                         onmouseover="window.status='';return true"/><br/>
						<input type="image" src="../../images/btn-add.png" name="btn1" id="bnt1" width="118" height="46" border="0" title="Agregar Cliente"
						onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1);
                         window.status='';return true" onmouseout="MM_nbGroup('out')" />					
					</form>				</td>
				<td width="260" align="center">
					<form action="frm_consultarCliente.php">
						<input type="image" src="images/sea-cliente.png" width="115" height="158" border="0" title="Consultar Cliente" 
                        onmouseover="window.status='';return true"/><br/>
						<input type="image" src="../../images/btn-sea.png" name="btn2" id="bnt2" width="118" height="46" border="0" title="Consultar Cliente"
						onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-sea-over.png','',1);
                         window.status='';return true" onmouseout="MM_nbGroup('out')" />					
					</form>				</td>
			</tr>
			<tr>
				<td width="260" align="center">
					<form action="frm_modificarCliente.php">
						<input type="image" src="images/upd-cliente.png" width="115" height="158" border="0" title="Modificar Cliente" 
                        onmouseover="window.status='';return true"/><br/>
						<input type="image" src="../../images/btn-upd.png" name="btn3" id="bnt3" width="118" height="46" border="0" title="Modificar Cliente"
						onclick="MM_nbGroup('down','group1','btn3','',1)" onmouseover="MM_nbGroup('over','btn3','../../images/btn-upd-over.png','',1); 
                        window.status='';return true" onmouseout="MM_nbGroup('out')" />					
					</form>				</td>
				<td width="260" align="center">
					<form action="frm_exportarCSV.php" method="post">
						<input type="image" src="images/dwn-csvclientes.png" width="115" height="158" border="0" title="Exportar Archivo CSV de Clientes" 
                        onmouseover="window.status='';return true"/><br/>
						<input type="image" src="../../images/btn-dwn-csv.png" name="btn4" id="bnt4" width="118" height="46" border="0" 
                        title="Exportar Archivo CSV de Clientes"
						onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-dwn-csv-over.png','',1)
                        ; window.status='';return true" onmouseout="MM_nbGroup('out')" />					
					</form>				</td>
			</tr>
 	 </table>
    </div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		//Registro de documentos de proveedores
		unset($_SESSION["documentos"]);
		//Registro de detalles de convenios
		unset($_SESSION['detallesconvenio']);
	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:806px; height:400px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
        <table border="0" align="center" cellpadding="5" cellspacing="5">
            <tr>
                <td width="280" align="center">
                    <form action="frm_agregarProveedor.php">
                        <input type="image" src="images/add-proveedor.png" width="115" height="158" border="0" title="Agregar Proveedor"
                         onmouseover="window.status='';return true"/><br/>
						<input type="image" src="../../images/btn-add.png" name="btn1" id="bnt1" width="118" height="46" border="0" title="Agregar Proveedor"
                        onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1);
                         window.status='';return true" onmouseout="MM_nbGroup('out')"/>					
                    </form>
          	    </td>
          	    <td width="280" align="center">
					<form action="frm_consultarProveedor.php">
                        <input type="image" src="images/sea-proveedor.png" width="115" height="158" border="0" title="Consultar Proveedor"
                         onmouseover="window.status='';return true"/><br/>
                        <input type="image" src="../../images/btn-sea.png" name="btn2" id="bnt2" width="118" height="46" border="0" title="Consultar Proveedor"
                        onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-sea-over.png','',1);
                         window.status='';return true" onmouseout="MM_nbGroup('out')" />
					</form>
		 		</td>
    		</tr>		
		</table>
  	
        <table border="0" align="center" cellpadding="5" cellspacing="5">
            <tr>
                <td width="250" align="center">
                    <form action="frm_modificarProveedor.php">
                        <input type="image" src="images/upd-proveedor.png" width="115" height="158" border="0" title="Modificar Proveedor" 
                        onmouseover="window.status='';return true"/><br/>
                        <input type="image" src="../../images/btn-upd.png" name="btn3" id="btn3" width="118" height="46" border="0" title="Modificar Proveedor"
                        onclick="MM_nbGroup('down','group1','btn3','',1)" onmouseover="MM_nbGroup('over','btn3','../../images/btn-upd-over.png','',1);
                         window.status='';return true" onmouseout="MM_nbGroup('out')" />
                    </form>
                </td>
                <td width="250" align="center">
                    <form action="frm_registrarConvenio.php">
                        <input type="image" src="images/add-convenio.png" width="115" height="158" border="0" title="Registrar Convenio" 
                        onmouseover="window.status='';return true"/><br/>
                        <input type="image" src="../../images/btn-con.png" name="btn4" id="btn4" width="118" height="46" border="0" title="Registrar Convenio"				
                        onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-con-over.png','',1);
                         window.status='';return true" onmouseout="MM_nbGroup('out')" />
                    </form>
                </td>
                <td width="250" align="center">
                    <form action="frm_evaluarProveedor.php">
                        <input type="image" src="images/eva-proveedor.png" width="115" height="158" border="0" title="Evaluar Proveedor" 
                        onmouseover="window.status='';return true"/><br/>
                        <input type="image" src="../../images/btn-eva.png" name="btn5" id="btn5" width="118" height="46" border="0" title="Evaluar Proveedor"				
                        onclick="MM_nbGroup('down','group1','btn5','',1)" onmouseover="MM_nbGroup('over','btn5','../../images/btn-eva-over.png','',1); 
                        window.status='';return true" onmouseout="MM_nbGroup('out')" />
                    </form>				
                </td>
            </tr>
        </table>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
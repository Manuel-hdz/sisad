<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
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
		#parrilla-menu1 { position:absolute; left:79px; top:160px; width:871px; height:400px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="80%" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="50%" align="center">
				<form action="frm_modificarOrganigrama.php">
					<input type="image" src="images/upd-organigrama.png" width="130" height="200" border="0" title="Modificar Organigrama" 
                    onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-upd.png"  name="btn1" id="btn1" width="118" height="46" border="0" title="Modificar Organigrama"
					onclick="MM_nbGroup('down','group1','btn1','',1)" 
                    onmouseover="MM_nbGroup('over','btn1','../../images/btn-upd-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>
            </td>
      		<td width="50%" align="center">
				<form action="frm_generarNombramiento.php">
					<input type="image" src="images/add-nombramiento.png" width="130" height="200" border="0" title="Generar Nombramiento" 
                    onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen.png" name="btn2" id="btn2" width="118" height="46" border="0" 
                    title="Generar Nombramiento" onclick="MM_nbGroup('down','group1','btn2','',1)" 
                    onmouseover="MM_nbGroup('over','btn2','../../images/btn-gen-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
    		 	</form>
            </td>
    	</tr>
	</table>
	
	
  	<table class="tabla_frm" width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="33%" align="center">
				<form action="frm_gestionarBonos.php">
					<input type="image"src="images/upd-gestion-bonos.png" width="130" height="200" border="0" title="Registrar Bonos" 
                    onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-reg.png" name="btn3" id="btn3" width="118" height="46" border="0" title="Registrar Bonos" 
					onclick="MM_nbGroup('down','group1','btn3','',1)" 
                    onmouseover="MM_nbGroup('over','btn3','../../images/btn-reg-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>			</td>
			<td width="33%" align="center">
				<form action="menu_deducciones.php">
				  <input name="image" type="image" title="Registrar Deducciones a los Empleados"
                    onmouseover="window.status='';return true" src="images/deduccion.png" width="130" height="200" border="0" } />
				  <br/>
					<input type="image" src="../../images/btn-reg.png" name="btn4" id="btn4" width="118" height="46" border="0" title="Registrar Deducciones a los Empleados"
					onclick="MM_nbGroup('down','group1','btn4','',1)" 
                    onmouseover="MM_nbGroup('over','btn4','../../images/btn-reg-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>			</td>
			<td width="33%" align="center">
				<form action="menu_prestamos.php">
					<input type="image" src="images/prestamo.png" width="130" height="200" border="0" title="Registrar Prestamos a los Empleados" }
                    onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-reg.png" name="btn5" id="btn5" width="118" height="46" border="0" title="Registrar Prestamos a los Empleados"
					onclick="MM_nbGroup('down','group1','btn5','',1)" 
                    onmouseover="MM_nbGroup('over','btn5','../../images/btn-reg-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>			</td>			
    	</tr>
  	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
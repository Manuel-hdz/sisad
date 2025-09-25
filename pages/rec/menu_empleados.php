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
  	<table class="tabla_frm" width="765" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="255" align="center">
				<form action="frm_agregarEmpleado.php">
					<input type="image" src="images/add-empleado.png" width="130" height="200" border="0" title="Registrar Nuevo Empleado" onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn1" id="btn1" width="118" height="46" border="0" title="Registrar Nuevo Empleado"
					onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>
	      </td>
      		<td width="255" align="center">
				<form action="frm_eliminarEmpleado.php">
					<input type="image" src="images/del-empleado.png" width="130" height="200" border="0" title="Dar de Baja Empleado" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-del.png" name="btn2" id="btn2" width="118" height="46" border="0" title="Dar de Baja Empleado" 
					onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-del-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
    		 	</form>
   		  </td>
      		<td width="255" align="center">
				<form action="frm_consultarEmpleado.php">
					<input type="image" src="images/sea-empleado.png" width="130" height="200" border="0" title="Consultar Empleado" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-sea.png" name="btn3" id="btn3" width="118" height="46" border="0" title="Consultar Empleado" 
					onclick="MM_nbGroup('down','group1','btn3','',1)" onmouseover="MM_nbGroup('over','btn3','../../images/btn-sea-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
  		  </td>
    	</tr>
	</table>
  	<table class="tabla_frm" width="890" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="25%" align="center">
				<form action="frm_modificarEmpleado.php">
					<input type="image"src="images/upd-empleado.png" width="130" height="200" border="0" title="Modificar Empleado" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-upd.png"  name="btn4" id="btn4" width="118" height="46" border="0" title="Modificar Empleado" 
					onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-upd-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td width="25%" align="center">
				<form action="frm_agregarEmpleadoBeneficiario.php">
					<input type="image" src="images/add-beneficiario.png" width="130" height="200" border="0" title="Registrar Beneficiarios de Empleado" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-add-benef.png" name="btn5" id="btn5" width="118" height="46" border="0" title="Registrar Beneficiarios de Empleado"
					onclick="MM_nbGroup('down','group1','btn5','',1)" onmouseover="MM_nbGroup('over','btn5','../../images/btn-add-benef-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td width="25%" align="center">
				<form action="frm_agregarEmpleadoBecarios.php">
					<input type="image" src="images/add-beca.png" width="130" height="200" border="0" title="Registrar Becarios de Empleado" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-add-beca.png" name="btn6" id="btn6" width="118" height="46" border="0" title="Registrar Becarios de Empleado"
					onclick="MM_nbGroup('down','group1','btn6','',1)" onmouseover="MM_nbGroup('over','btn6','../../images/btn-add-beca-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td width="25%" align="center">
				<form action="menu_kardex.php">
					<input type="image" src="images/add-kardex.png" width="130" height="200" border="0" title="Kardex de Empleados" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-kardex.png" name="btn7" id="btn7" width="118" height="46" border="0" title="Kardex de Empleados"
					onclick="MM_nbGroup('down','group1','btn7','',1)" onmouseover="MM_nbGroup('over','btn7','../../images/btn-kardex-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
    	</tr>
  	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
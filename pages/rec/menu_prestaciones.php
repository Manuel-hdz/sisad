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
	<br />
  	<table class="tabla_frm" width="890" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td>
			</td>
			<td width="25%" align="center">
				<form action="menu_deducciones.php">
					<input type="image" src="images/deduccion.png" width="130" height="200" border="0" title="Registrar Deducciones" 
                    onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-reg.png" name="btn5" id="btn5" width="118" height="46" border="0" 
                    title="Registrar Deducciones" onclick="MM_nbGroup('down','group1','btn5','',1)" 
                    onmouseover="MM_nbGroup('over','btn5','../../images/btn-reg-over.png','',1);window.status='';return true" 
                    onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
      		<td width="10%">
			</td>
			<td width="25%" align="center">
				<form action="menu_prestamos.php">
					<input type="image" src="images/bono.png" width="130" height="200" border="0" title="Registrar Pr&eacute;stamos" 
                    onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-reg.png" name="btn6" id="btn6" width="118" height="46" border="0" 
                    title="Registrar Pr&eacute;stamos" onclick="MM_nbGroup('down','group1','btn6','',1)" 
                    onmouseover="MM_nbGroup('over','btn6','../../images/btn-reg-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td>
			</td>
    	</tr>
  	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
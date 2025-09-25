<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
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
		#parrilla-menu1 { position:absolute; left:79px; top:160px; width:871px; height:400px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="890" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="25%" align="center">
				<form action="frm_registrarEqPesado.php">
					<input type="image" src="images/add-registroEqP.png" width="170" height="200" border="0" title="Registrar Obras con Equipo Pesado" 
                    onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-gen.png"  name="btn1" id="btn1" width="118" height="46" border="0" title="Registrar Obras con Equipo Pesado"
					onclick="MM_nbGroup('down','group1','btn1','',1)" 
                    onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>
            </td>
      		<td width="25%" align="center">
				<form action="frm_modificarEqPesado.php">
					<input type="image"src="images/upd-registroEqP.png" width="170" height="200" border="0" title="Modificar Obras con Equipo Pesado" 
					onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-upd.png" name="btn5" id="btn5" width="118" height="46" border="0" title="Modificar Obras con Equipo Pesado"
					onclick="MM_nbGroup('down','group1','btn5','',1)" 
					onmouseover="MM_nbGroup('over','btn5','../../images/btn-upd-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>	
            </td>
			<td width="25%" align="center">
				<form action="frm_consultarEqPesado.php">
					<input type="image" src="images/sea-registroEqP.png" width="170" height="200" border="0" title="Consultar Obras con Equipo Pesado" 
					onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-sea.png"  name="btn4" id="btn4" width="118" height="46" border="0" title="Consultar Obras con Equipo Pesado" 
					onclick="MM_nbGroup('down','group1','btn4','',1)" 
					onmouseover="MM_nbGroup('over','btn4','../../images/btn-sea-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>			
			</td>
    	</tr>
	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
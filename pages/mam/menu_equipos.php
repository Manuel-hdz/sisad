<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Incluir el archivo que maneja las alertas de los Equipos que estan proximos a recibir Mtto. Preventivo
		include_once ("alertas.php");
		//Desplegar las alertas registradas en la BD
		desplegarAlertas();?>

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
  	<table class="tabla_frm" width="800" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="255" align="center">
				<form action="frm_agregarEquipo.php">
					<input type="image" src="images/add-equipo.png" width="200" height="158" border="0" title="Agregar Equipo" onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn1" id="bnt1" width="118" height="46" border="0" title="Agregar Equipo"
					onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>
	      </td>
      		<td width="255" align="center">
				<form action="frm_eliminarEquipo.php">
					<input type="image" src="images/del-equipo.png" width="200" height="158" border="0" title="Eliminar Equipo" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-del.png" name="btn2" id="bnt2" width="118" height="46" border="0" title="Eliminar Equipo" 
					onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-del-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
    		 	</form>
   		  </td>
      		<td width="255" align="center">
				<form action="frm_consultarEquipo.php">
					<input type="image" src="images/sea-equipo.png" width="200" height="158" border="0" title="Consultar Equipo" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-sea.png" name="btn3" id="btn3" width="118" height="46" border="0" title="Consultar Equipo" 
					onclick="MM_nbGroup('down','group1','btn3','',1)" onmouseover="MM_nbGroup('over','btn3','../../images/btn-sea-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
  		  </td>
    	</tr>
	</table>
  
  	<table class="tabla_frm" width="800" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="255" align="center">
				<form action="frm_modificarEquipo.php">
					<input type="image"src="images/upd-equipo.png" width="200" height="158" border="0" title="Modificar Equipo" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-upd.png"  name="btn4" id="btn4" width="118" height="46" border="0" title="Modificar Equipo" 
					onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-upd-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td width="255" align="center">
				<form action="frm_registrarEstatus.php">
					<input type="image"src="images/add-status.png" width="150" height="188" border="0" title="Registrar Status Equipos" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn6" id="btn6" width="118" height="46" border="0" title="Registrar Status Equipos" 
					onclick="MM_nbGroup('down','group1','btn6','',1)" onmouseover="MM_nbGroup('over','btn6','../../images/btn-add-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td width="255" align="center">
				<form action="menu_metricas.php">
					<input type="image" src="images/add-h-o-equipo.png" width="160" height="188" border="0" title="Registrar Hor&oacute;metro/Odor&oacute;metro de Equipo" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-add-oh.png" name="btn5" id="btn5" width="118" height="46" border="0" title="Registrar Hor&oacute;metro/Odor&oacute;metro de Equipo"
					onclick="MM_nbGroup('down','group1','btn5','',1)" onmouseover="MM_nbGroup('over','btn5','../../images/btn-add-oh-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
    	</tr>
  	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
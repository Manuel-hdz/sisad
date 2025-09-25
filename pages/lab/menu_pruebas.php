<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Incluimos archivo para generar alertas de las pruebas a las mezclas
		include_once ("alertas_pruebas.php");
		//Incluimos Archivo para generar las alertas de los equipos que estan proximos a recibir mantenimiento
		include_once ("alertas_mtto.php");
		//Desplegar las alertas registradas en la BD 
		//Función para Generar las Alertas de Pruebas a mezclas inlcuido en alertas_pruebas.php
		desplegarAlertas();
		//Función para generar las Alertas de Pruenas a Mezclas incluido en alertas_mtto.php
		desplegarAlertasMtto();
		if(isset($_SESSION['catNormas'])){
			unset($_SESSION['catNormas']);
		}?>

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
  	<table class="tabla_frm" width="631" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="255" align="center">
				<form action="frm_agregarPrueba.php">
					<input type="image" src="images/add-prueba.png" width="110" height="200" border="0" title="Agregar Pruebas" onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn1" id="bnt1" width="118" height="46" border="0" title="Agregar Pruebas"
					onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>
	      </td>
      		<td width="255" align="center">
				<form action="frm_eliminarPrueba.php">
					<input type="image" src="images/del-prueba.png" width="110" height="200" border="0" title="Eliminar Pruebas" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-del.png" name="btn2" id="bnt2" width="118" height="46" border="0" title="Eliminar Pruebas" 
					onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-del-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
    		 	</form>
   		  </td>
    	</tr>
	  </table>
		<br />
		<table class="tabla_frm" width="800" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td align="center">
				<form action="frm_modificarPrueba.php">
					<input type="image" src="images/upd-prueba.png" width="110" height="200" border="0" title="Modificar Pruebas" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-upd.png" name="btn3" id="btn3" width="118" height="46" border="0" title="Modificar Pruebas" 
					onclick="MM_nbGroup('down','group1','btn3','',1)" onmouseover="MM_nbGroup('over','btn3','../../images/btn-upd-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td align="center">
				<form action="frm_consultarPrueba.php">
					<input type="image" src="images/sea-prueba.png" width="110" height="200" border="0" title="Consultar Pruebas" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-sea.png" name="btn4" id="btn4" width="118" height="46" border="0" title="Consultar Pruebas"
					onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-sea-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td align="center">
				<form action="frm_opcionesCatalogoNormas.php">
					<input type="image" src="images/reg-normas.png" width="100" height="200" border="0" title="Gestionar Norma" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-add.png" name="btn5" id="btn5" width="118" height="46" border="0" title="Gestionar Norma"
					onclick="MM_nbGroup('down','group1','btn5','',1)" onmouseover="MM_nbGroup('over','btn5','../../images/btn-add-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
    	</tr>
  	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
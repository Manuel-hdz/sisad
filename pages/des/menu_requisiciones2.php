<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		
		//Liberar arreglos de session utilizados en las requisiciones
		if(isset($_SESSION['datosRequisicion']))
			unset($_SESSION['datosRequisicion']);
		if(isset($_SESSION['id_requisicion']))	
			unset($_SESSION['id_requisicion']);
		if(isset($_SESSION["activos"]))
			unset($_SESSION["activos"]);?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/botones.js"></script>

    <style type="text/css">
<!--
#parrilla-menu1 {position:absolute;left:101px;top:159px;width:706px;height:331px;z-index:1;}
-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
		<table border="0" align="center" cellpadding="5" cellspacing="5">
    		<tr>
     	 		<td align="center">	  			
					<input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=almacen'" src="../../images/logo-almacen.png" width="149" 
                    height="151" border="0" title="Revisar Requisiciones de Almac&eacute;n" />
		  		</td>
      			<td align="center">
					<input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=gerenciatecnica'" src="../../images/logo-gt.png" width="149" 
                    height="151" border="0" title="Revisar Requisiciones de Gerencia T&eacute;cnica" />
		  		</td>
				<td align="center">
					<input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=recursoshumanos'" src="../../images/logo-rh.png" width="149" 
                    height="151" border="0" title="Revisar Requisiciones de Recursos Humanos" />
                </td>
                <td align="center">
					<input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=produccion'" src="../../images/logo-produccion.png" width="149"
                     height="151" border="0" title="Revisar Requisiciones de Producci&oacute;n" />
                </td>
				<td align="center">
					<input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=aseguramientodecalidad'" src="../../images/logo-ac.png" width="149"
                     height="151" border="0" title="Revisar Requisiciones de Aseguramiento de Calidad" />
	  			</td>
    	  </tr>
    	  <tr>
      			<td align="center">
					<input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=desarrollo'" src="../../images/logo-desarrollo.png" width="149"
                    height="151" border="0" title="Revisar Requisiciones de Desarrollo" />
                </td>
                <td align="center">
					<input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=mantenimiento'" src="../../images/logo-mantenimiento.png" 
                    width="149" height="151" border="0" title="Revisar Requisiciones de Mantenimiento" />
                </td>
                <td align="center">
                    <input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=topografia'" src="../../images/logo-topografia.png" width="149"
                     height="151" border="0" title="Revisar Requisiciones de Topograf&iacute;a" />
                </td>
				<td align="center">
                    <input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=laboratorio'" src="../../images/logo-laboratorio.png" width="149"
                    height="151" border="0" title="Revisar Requisiciones de Laboratoria" />
                </td>
				<td align="center" colspan="2">
                    <input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=seguridadindustrial'" src="../../images/logo-si.png" width="149"
                     height="151" border="0" title="Revisar Requisiciones de Seguridad Industrial" />
                </td>
          </tr>
		  <tr>
		  		<td align="center">&nbsp;</td>
			  	<td align="center">
                    <input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=paileria'" src="../../images/logo-paileria.png" width="149"
                     height="151" border="0" title="Revisar Requisiciones de Paileria" />
                </td>
				<td align="center">
                    <input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=mttoElectrico'" src="../../images/logo-mantenimiento-electrico.png" width="149"
                     height="151" border="0" title="Revisar Requisiciones de Mantenimiento El&eacute;ctrico" />
                </td>
				<td align="center">
                    <input type="image" onclick="location.href='frm_consultarRequisiciones2.php?depto=clinica'" src="../../images/logo-clinica.png" width="149"
                     height="151" border="0" title="Revisar Requisiciones de la Unidad de Salud Ocupacional" />
                </td>
				<td align="center">&nbsp;</td>
          </tr>
       </table>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
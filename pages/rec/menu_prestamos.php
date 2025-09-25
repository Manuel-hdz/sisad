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
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:79px; top:160px; width:650px; height:400px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="50%" align="center">
				<form action="frm_agregarPrestamo.php">
					<input type="image" src="images/add-prestamo.png" width="130" height="200" border="0" title="Agregar Pr&eacute;stamo" 
                    onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn1" id="btn1" width="118" height="46" border="0" title="Agregar Pr&eacute;stamo"
					onclick="MM_nbGroup('down','group1','btn1','',1)" 
                    onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>            </td>
   		  <td width="50%" align="center"><form action="frm_registrarAbonos.php" method="get">
            <input type="hidden" name="hdn_org" id="hdn_org" value="prestamos" />
            <input name="image" type="image" title="Registrar Abonos" 
                 onmouseover="window.status='';return true"src="images/add-reg-abono.png" width="130" height="200" border="0" />
            <br/>
            <input type="image" src="../../images/btn-reg.png"  name="btn42" id="btn42" width="118" height="46" border="0" title="Registrar Abonos" 
                onclick="MM_nbGroup('down','group1','btn4','',1)" 
                onmouseover="MM_nbGroup('over','btn4','../../images/btn-reg-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
          </form></td>
    	</tr>
	</table>
	<br />
  	<table class="tabla_frm" width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>   		  
      		<td align="center">
				<form action="frm_consultarPrestamos.php">
			    <input type="image"src="images/sea-prestamo.png" width="130" height="200" border="0" title="Consultar Pr&eacute;stamos" 
                    onmouseover="window.status='';return true" />
		      	<br/>
                <input type="image" src="../../images/btn-sea.png"  name="btn4" id="btn4" width="118" height="46" border="0" title="Consultar Pr&eacute;stamos" 
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
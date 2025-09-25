<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Incluir el archivo que maneja las alertas de los materiales que han rebasado el punto de reorden 
		include_once ("alertas.php");
		include_once ("alertasRH.php");
		include_once ("alertasKiosco.php");
		//Desplegar las alertas registradas en la BD
		desplegarAlertas();
		desplegarAlertasRH();
		desplegarAlertasKiosco();
		
		//Vaciar la información almacenada en la SESSION cada vez que el usuario de cancelar en las diferentes paginas de la Seccion de Entrada/Salida
		//Manejo de la Entrada de material
		unset($_SESSION['datosEntrada']);
		unset($_SESSION['datosEntradaNR']);		
		unset($_SESSION['id_entrada']);
		unset($_SESSION['origen']);
		unset($_SESSION['no_origen']);
		unset($_SESSION['cmb_prm2']);	
		//Vaciar de la SESSION los nombre de los materiales obtenidos de un pedido
		unset($_SESSION['nomMaterialesPedido']);
		unset($_SESSION['bd']);	
				
		//Manejo de la Salidad de Material
		unset($_SESSION['datosSalida']);
		unset($_SESSION['id_salida']);
		//unset($_SESSION['id_equipo']);
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />	

    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:479px; height:229px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
	<table width="100%" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
			<td width="33%" align="center">
	    		<div align="center">
					<form action="frm_entradaMaterial.php">
						<input type="image" src="images/in-material.png" width="110" height="158" border="0" title="Entrada Material" onmouseover="window.status='';return true"  />						
						<input type="image" src="../../images/btn-i-material.png" name="btn1" id="bnt1" width="118" height="46" border="0" title="Entrada Material" 
						onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-i-material-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />						
					</form>										
    		  </div>
			</td>
      		<td width="33%" align="center">
	    		<div align="center">
					<form action="frm_salidaMaterial.php">
						<input type="image" src="images/out-material.png" width="110" height="158" border="0" title="Salida Material" onmouseover="window.status='';return true"  />
						<input type="image" src="../../images/btn-o-material.png" name="btn2" id="bnt2" width="118" height="46" border="0" title="Salida Material"  
						onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-o-material-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')"/>
					</form>	      
	 		  </div>
	  		</td>
			<!--
			<td width="33%" align="center">
	    		<div align="center">
					<form action="frm_salidaMaterialBC.php">
						<input type="image" src="images/out-material-bc.png" width="120" height="158" border="0" title="Salida de Material Usando C&oacute;digo de Barras" onmouseover="window.status='';return true"  />
						<input type="image" src="../../images/btn-o-material.png" name="btn4" id="bnt4" width="118" height="46" border="0" title="Salida de Material Usando C&oacute;digo de Barras"  
						onclick="MM_nbGroup('down','group1','btn4','',1)" onmouseover="MM_nbGroup('over','btn4','../../images/btn-o-material-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')"/>
					</form>	      
	 		  </div>
	  		</td>
	  		-->
    	</tr>
	</table>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
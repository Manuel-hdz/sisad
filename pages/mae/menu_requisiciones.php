<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Paileria
	//if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
	//	echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	//}
	//else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que tiene la funcion para borrar las imagenes en caso de haber cancelado
		include ("op_generarRequisicion.php");		
		//Si el usuario cancelo el registro de la requisición, verificar si existen fotografias cargadas para borrarlas
		if(isset($_GET['cancel'])){//verificar si se le dio click al boton de cancelar en el formulario de generar requisición
			if(isset($_SESSION["id_requisicion"])){//Verificar que el ID de la Requisición (Nombre de la carpeta) esta definido en la SESSION
				if(isset($_SESSION["fotosReq"]))//Verificar si hay fotos cargadas en el servidor
					borrarFotos($_SESSION["id_requisicion"]);		
			}
		}
		//Liberar arreglos de session utilizados en las requisiciones
		if(isset($_SESSION['datosRequisicion']))
			unset($_SESSION['datosRequisicion']);
		if(isset($_SESSION['comentario']))
			unset($_SESSION['comentario']);
		//Fotografias en las requisiciones
		if(isset($_SESSION["fotosReq"]))
			unset($_SESSION['fotosReq']);?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:405px; height:229px;	z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="388" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="50%" align="center">
	    		<div align="center">
				<form action="frm_generarRequisicion.php">
					<input type="image" src="images/add-requisicion.png"  width="100" height="158" border="0" title="Generar Requisici&oacute;n" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-gen-requisicion.png" name="btn1" id="bnt1" width="118" height="46" border="0" title="Generar Requisici&oacute;n"
					onclick="MM_nbGroup('down','group1','btn1','',1)" onmouseover="MM_nbGroup('over','btn1','../../images/btn-gen-requisicion-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
	    		</form>
    		  </div>
			</td>
      		<td width="50%" align="center">
	    		<div align="center">
				<form action="frm_consultarRequisiciones.php">
					<input type="image" src="images/sea-requisicion.png" width="100" height="158" border="0" title="Estado Requisiciones" onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-est-req.png" name="btn2" id="bnt2" width="118" height="46" border="0" title="Estado Requisiciones" 
					onclick="MM_nbGroup('down','group1','btn2','',1)" onmouseover="MM_nbGroup('over','btn2','../../images/btn-est-req-over.png','',1);window.status='';return true"  onmouseout="MM_nbGroup('out')"/>
	    		</form>
	 		  </div>
	  		</td>
    	</tr>
  	</table>
	</div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
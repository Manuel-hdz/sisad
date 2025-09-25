<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento de Calidad
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que tiene la funcion para mostrar las alertas
		include ("alertas_recordatorios.php");	
		//Funcion para desplegar las alertas internas=>Recordatorios
		desplegarAlertasRecordatorio();
		include("alertas_recordatoriosExternos.php");	
		//Funcion para desplegar las alertas Externas=>Recordatorios
		desplegarAlertasRecordatorioExterno();
		//Archivo que incluye las alertas de recordatorios de plan de acciones
		include("alertas_auditorias.php");	
		//Funcion para desplegar las alertas PlanAcciones
		desplegarAlertasPlanAcciones();
		//Archivo que incluye las alertas de los planes de acciones en relacion con la fecha planeada de la accion
		include("alertas_referencias.php");	
		//Funcion para desplegar las alertas de las referencias
		desplegarAlertasFechaReferencias();
		//Liberamos las sesiones de registrar Lista MAestra de Documentos
		if(isset($_SESSION['lista'])){
			unset($_SESSION['lista']);
		}
		if(isset($_SESSION['lista_maestra'])){
			unset($_SESSION['lista_maestra']);
		}
		if(isset($_SESSION['bandera'])){
			unset($_SESSION['bandera']);
		}?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:100px; top:160px; width:566px; height:400px; z-index:1; }
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="545" border="0" align="center" cellpadding="5" cellspacing="5">
    	<tr>
      		<td width="255" align="center">
				<form action="frm_agregarDocumento.php">
					<input type="image" src="images/add-documento.png" width="120" height="200" border="0" title="Agregar Documento al Repositorio" 
					onmouseover="window.status='';return true"/><br/>
					<input type="image" src="../../images/btn-add.png"  name="btn1" id="bnt1" width="118" height="46" border="0" 
					title="Agregar Documento al Repositorio"
					onclick="MM_nbGroup('down','group1','btn1','',1)" 
					onmouseover="MM_nbGroup('over','btn1','../../images/btn-add-over.png','',1); window.status='';return true" onmouseout="MM_nbGroup('out')" />	
				</form>	     
			</td>
      		<td width="255" align="center">
				<form action="frm_modificarDocumentos.php">
					<input type="image" src="images/upd-documento.png" width="120" height="200" border="0" title="Modificar Documento en Repositorio" 
					onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-upd.png" name="btn2" id="bnt2" width="118" height="46" border="0" 
					title="Modificar Documento en Repositorio" 
					onclick="MM_nbGroup('down','group1','btn2','',1)" 
					onmouseover="MM_nbGroup('over','btn2','../../images/btn-upd-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
    		 	</form>   		  
			</td>
   		</tr>
    	<tr>
      		<td align="center">
				<form action="menu_listaRegCal.php">
					<input type="image" src="images/add-listaMaestra.png" width="150" height="190" border="0" 
					title="Registrar Lista Maestra Registros de Calidad" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-reg.png" name="btn3" id="btn3" width="118" height="46" border="0" 
					title="Registrar Lista Maestra Registros de Calidad" 
					onclick="MM_nbGroup('down','group1','btn3','',1)" 
					onmouseover="MM_nbGroup('over','btn3','../../images/btn-reg-over.png','',1);window.status='';return true" 
					onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
			<td align="center">
				<form action="menu_listaDocumentos.php">
					<input type="image" src="images/add-listaMaestra.png" width="150" height="190" border="0"
					title="Registrar Lista Maestra Control de Documentos" onmouseover="window.status='';return true" /><br/>
					<input type="image" src="../../images/btn-reg.png" name="btn5" id="btn5" width="118" height="46" border="0" 
					title="Registrar Lista Maestra Control de Documentos"
					onclick="MM_nbGroup('down','group1','btn5','',1)" 
					onmouseover="MM_nbGroup('over','btn5','../../images/btn-reg-over.png','',1);window.status='';return true" onmouseout="MM_nbGroup('out')"/>
				</form>
			</td>
		</tr>
  	</table>
	<br />
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
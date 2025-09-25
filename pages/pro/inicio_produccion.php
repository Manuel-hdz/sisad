<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de  Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		/*********************************ALERTAS DE ASEGURAMIENTO CALIDAD****************************/
		//ncluimos Archivo que contiene las alertas de recordatorios de Aseguramiento de Calidad
		include("../ase/alertas_recordatoriosExternos.php");	
		//Funcion para desplegar las alertas internas=>Recordatorios
		desplegarAlertasRecordatorioExterno();
		//Incluimos archivo que contiene las alertas de los planes de Acciion publicados por Aseguramiento de Calidad
		include("../ase/alertas_auditorias.php");	
		//Funcion para desplegar las alertas
		desplegarAlertasPlanAcciones();
		
		/*********************************ALERTAS SEGURIDAD INDUSTRIAL****************************/
		//Incluimos archivo que contiene las alertas de las actividades publicadas por seguridad
		include("../seg/alertas_recordatoriosExternosSI.php");
		//Funcion para desplegar las alertas externas=>Recorridos  de Seguridad
		desplegarAlertasRecordatorioExternoSI();
		//Incluimos archivo que contiene las alertas de los Recorridos publicados por seguridad
		include("../seg/alertas_recorridos.php");
		//Funcion para desplegar las alertas externas=>Reecorridos de Seguridad
		desplegarAlertasRecorridos();
		
			
		/*********************************ALERTAS COMPRAS*******************************/
		//Archivo que porporciona la informacion para generar de los movimientos de compras
		include("alertasCompras.php");
		//Archivo para desplegar las alertas de los movimientos regoistrados en compras
		desplegarAlertasCompras();
		/*****************************************************************************/
		
		
		//Liberamos los datos de la SESSION utilizados en la Sección de Bitácora
		if(isset($_SESSION["produccion"]))
			unset($_SESSION["produccion"]);
		if(isset($_SESSION["seguridad"]))
			unset($_SESSION["seguridad"]);
		if(isset($_SESSION["menuProduccion"]))
			unset($_SESSION["menuProduccion"]);
		
		
		//Liberar los datos de la SESSION utilizados en la Sección de Requisiciones		
		if(isset($_GET['cancel'])){
			borrarFotos($_SESSION["id_requisicion"]);		
		}	
		if(isset($_SESSION['datosRequisicion']))
			unset($_SESSION['datosRequisicion']);
		if(isset($_SESSION['comentario']))	
			unset($_SESSION['comentario']);
		if(isset($_SESSION["fotosReq"]))
			unset($_SESSION['fotosReq']);			
		if(isset($_SESSION["fotosReq"]))
			unset($_SESSION['fotosReq']);?>
			
			
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:106px; top:130px; width:806px; height:517px; z-index:1; }
		-->
    </style>
</head>
<body>
    <div id="parrilla-menu1">
      <div align="center">
        <p><img src="../../images/logo-produccion.png" width="449" height="453" /><br/>
        <img src="../../images/bienvenido.png" width="449" height="54" /></p>
      </div>
    </div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado?>
</html>
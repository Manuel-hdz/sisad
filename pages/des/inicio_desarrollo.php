<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		/*********************************ALERTAS DE ASEGURAMIENTO CALIDAD****************************/
		include("../ase/alertas_recordatoriosExternos.php");			
		//Funcion para desplegar las alertas internas=>Recordatorios
		desplegarAlertasRecordatorioExterno();//Incluimos archivo que contiene las alertas de los planes de Acciion publicados por Aseguramiento de Calidad
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
		
		
		/*********************************ALERTAS CLINICA*******************************/
		include_once ("alertasClinica.php");
		//Desplegar las alertas registradas en la BD
		desplegarAlertasHC();
		/*****************************************************************************/
		
		
		/*********************************ALERTAS COMPRAS*******************************/
		//Archivo que porporciona la informacion para generar las alertas correspondientes a los historiales clinicos
		include("alertasCompras.php");
		//Archivo para desplegar las alertas de los Historiales Clinicos
		desplegarAlertasCompras();
		/*****************************************************************************/
		
		
		//Quitar de la SESSION los datos utilizados en el registro de la Bitacora de Avance			
		unset($_SESSION['bitsAgregadas']);
		//Quitar de la SESSION los Datos Implementados en la Actualizaci�n de la Bit�cora de Avance
		unset($_SESSION['bitsActualizadas']);
		unset($_SESSION['bitacoraAvance']);?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

		<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
		<style type="text/css">
			#parrilla-menu1 {
				position: absolute;
				left: 106px;
				top: 130px;
				width: 806px;
				height: 517px;
				z-index: 1;
			}
		</style>
	</head>

	<body>
		<div id="parrilla-menu1">
			<div align="center">
				<p><img src="../../images/logo-desarrollo.png" width="449" height="453" /><br />
					<img src="../../images/bienvenido.png" width="449" height="54" /></p>
			</div>
		</div>
	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
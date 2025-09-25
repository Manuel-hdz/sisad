<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
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
		desplegarAlertas();
		
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
		
		/*********************************ALERTAS CLINICA*******************************/
		include_once ("alertasClinica.php");
		//Desplegar las alertas registradas en la BD
		desplegarAlertasHC();
		/*****************************************************************************/
		
		/*********************************ALERTAS COMPRAS*******************************/
		//Archivo que porporciona la informacion para generar de los movimientos de compras
		include("alertasCompras.php");
		//Archivo para desplegar las alertas de los movimientos regoistrados en compras
		desplegarAlertasCompras();
		/*****************************************************************************/
		
		
		/****************GAMAS*************/
		//Liberar de la SESSION la información utilizada en la Manipulación de las GAMAS
		unset($_SESSION['datosGamaNueva']);
		unset($_SESSION['sistemasGamaNueva']);
		unset($_SESSION['sistemaEditar']);
		unset($_SESSION['appEditar']);
		
		unset($_SESSION['datosGamaModificada']);
		unset($_SESSION['sistemasGamaModificada']);
		
		
		/****************OT*************/
		//Liberar de la SESSION la información utilizada en la la Orden de Trabajo
		unset ($_SESSION['totalVale']);
		unset ($_SESSION['vales']);
		unset ($_SESSION['datosValeMtto']);
		unset ($_SESSION['datosOT']);
		unset ($_SESSION['gamasOT']);
		
		
		/************Orden de Trabajo para Servicios Externos*************/
		unset($_SESSION['ordenServicioExterno']);
		unset($_SESSION['actividadesRealizar']);
		unset($_SESSION['materialesUtilizar']);
		unset($_SESSION['datosConsultaOTSE']);

		
		/****************REQUISICIONES*******/
		//Liberar de la SESSION la información utilizada en la la Requisicion
		unset($_SESSION['datosRequisicion']);
		unset($_SESSION['id_requisicion']);
		//Fotografias
		if(isset($_SESSION["fotosReq"]))
			unset($_SESSION['fotosReq']);
		if(isset($_SESSION['comentario']))
			unset($_SESSION['comentario']);
			
		
		/*****************BITÁCORA***********/
		//Verificamos que el arreglo de actividades no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["actividades"])){
			unset($_SESSION["actividades"]);
		}
		//Verificamos que el arreglo de mecanicos no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["mecanicos"])){
			unset($_SESSION["mecanicos"]);
		}
		//Verificamos que el arreglo de materialesMtto no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["valesMtto"])){
			unset($_SESSION["valesMtto"]);
		}
		//Verificamos que el arreglo de regSinValeMtto no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION['regSinValeMtto'])){
			unset($_SESSION["regSinValeMtto"]);			
		}	
		//Verificamos que el arreglo de materialesMtto no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["materialesMtto"])){
			unset($_SESSION["materialesMtto"]);
		}
		//Verificamos que el arreglo de bitacoraPrev no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["bitacoraPrev"])){
			unset($_SESSION["bitacoraPrev"]);
		}
		//Verificamos que el arreglo de bitacoraCorr no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["bitacoraCorr"])){
			unset($_SESSION["bitacoraCorr"]);
		}
		//Verificamos que el arreglo de fotos no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["fotos"])){
			unset($_SESSION["fotos"]);
		}
		//Verificamos que el arreglo de fotos no este declarado, en caso de ser asi, vaciarlo
		if(isset($_SESSION["docTemporal"])){
			unset($_SESSION["docTemporal"]);
		}?>                

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
        <p><img src="../../images/logo-mantenimiento.png" width="449" height="453" /><br/>
        <img src="../../images/bienvenido.png" width="449" height="54" /></p>
      </div>
    </div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
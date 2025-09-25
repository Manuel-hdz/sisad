<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		/*********************************ALERTAS ASEGURAMIENTO DE LA CALIDAD*****************************************/
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
		
		//DAR DE BAJA SESSIONES UTILIZADAS//
		/*****************REQUISICIONES*******************/
		if(isset($_SESSION['datosRequisicion']))
			unset($_SESSION['datosRequisicion']);
		/*****************AUDITORIAS**********************/
		if(isset($_SESSION['referencias'])){
			unset($_SESSION['referencias']);
		}
		/*****************LISTAS MAESTRAS****************/
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
		#parrilla-menu1 { position:absolute; left:106px; top:130px; width:806px; height:517px; z-index:1; }
		-->
    </style>
</head>
<body>
    <div id="parrilla-menu1">
      <div align="center">
        <p><img src="../../images/logo-ac.png" width="449" height="453" /><br/>
        <img src="../../images/bienvenido.png" width="449" height="54" /></p>
      </div>
    </div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
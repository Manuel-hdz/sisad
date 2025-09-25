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
		include("../ase/alertas_recordatoriosExternos.php");	
		//Funcion para desplegar las alertas externas=>Recordatorios de Aseguramiento
		desplegarAlertasRecordatorioExterno();
		//Incluimos archivo que contiene las alertas de los planes de Acciion publicados por Aseguramiento de Calidad
		include("../ase/alertas_auditorias.php");	
		//Funcion para desplegar las alertas
		desplegarAlertasPlanAcciones();
		//Archivo para desplegar las alertas internas del departamento
		include("alertas_recordatoriosSI.php");
		desplegarAlertasRecordatorioSI();
		//Archivo para desplegar las alertas internas del departamento
		include("alertas_recordatoriosExternosSI.php");
		//Funcion para desplegar las alertas externas=>Recordatorios de Seguridad
		desplegarAlertasRecordatorioExternoSI();
		//Archivo para desplegar las alertas de los Recorridos
		include("alertas_recorridos.php");
		//Funcion para desplegar las alertas de Recorridos de Seguridad
		desplegarAlertasRecorridos();
		
		//Archivo para desplegar las alertas de los Planes de Contingencia
		include("alertas_PlanContingencia.php");
		//Funcion para desplegar las alertas de Recorridos de Seguridad
		desplegarAlertasPlanContingencia();
			
		/*********************************ALERTAS COMPRAS*******************************/
		//Archivo que porporciona la informacion para generar de los movimientos de compras
		include("alertasCompras.php");
		//Archivo para desplegar las alertas de los movimientos regoistrados en compras
		desplegarAlertasCompras();
		/*****************************************************************************/	
		
		//Quitar de las SESSION los arreglos utilizados en la Modificación del Plan de COntingencia
		if(isset($_SESSION['datosPlanContingencia']))
			unset($_SESSION['datosPlanContingencia']);
		if(isset($_SESSION['datosGralPlan']))
			unset($_SESSION['datosGralPlan']);
		if(isset($_SESSION['datosPlanPrincipal']))
			unset($_SESSION['datosPlanPrincipal']);
			
		//Quitar de la SESSION los arreglos utilizados en la seccion de Tiempo de Vida Util del Equipo de Seguridad
		if(isset($_SESSION['datosTiempoVidaES']))
			unset($_SESSION['datosTiempoVidaES']);
		
		//Damos de baja las sesiones que implican el registro de la acta de incidentes accidentes
		if(isset($_SESSION['actaIncAcc'])){
			unset($_SESSION['actaIncAcc']);
		}
		if(isset($_SESSION['accionesPrevCorr'])){
			unset($_SESSION['accionesPrevCorr']);
		}
		//Damos de baja las sesiones que implican el registro de la acta de seguridad e Higiene
		if(isset($_SESSION['accidentes'])){
			unset($_SESSION['accidentes']);
		}
		if(isset($_SESSION['visitas'])){
			unset($_SESSION['visitas']);
		}
		if(isset($_SESSION['asistentes'])){
			unset($_SESSION['asistentes']);
		}
		if(isset($_SESSION['agenda'])){
			unset($_SESSION['agenda']);
		}
		if(isset($_SESSION['recorridos'])){
			unset($_SESSION['recorridos']);
		}
		//Damos de baja las sesiones que implican el registro de los Recorridos de Seguridad
		if(isset($_SESSION['registroFotografico'])){
			unset($_SESSION['registroFotografico']);
		}
		if(isset($_SESSION['recorridosSeg'])){
			unset($_SESSION['recorridosSeg']);
		}
		if(isset($_SESSION['recorridosPrinc'])){
			unset($_SESSION['recorridosPrinc']);
		}
		if(isset($_SESSION['banderas'])){
			unset($_SESSION['banderas']);
		}
			?>
			
			
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
        <p><img src="../../images/logo-si.png" width="449" height="453" /><br/>
        <img src="../../images/bienvenido.png" width="449" height="54" /></p>
      </div>
    </div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
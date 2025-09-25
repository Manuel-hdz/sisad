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
		
		//Eliminar los materiales registrados, cuando el proceso de regitro no se haya finalizado correctamente
		if(isset($_SESSION['procesoRegistroMat']) && $_SESSION['procesoRegistroMat']=="NoTerminado"){ 			
			deshacerCambios($_SESSION['clavesRegistradasMat']);						
		}//Realizar esto antes de incluir el Archivo de Alertas, esto para poder eliminar los Materiales antes de que se les genere una alerta
		//Incluir el archivo que maneja las alertas de los materiales que han rebasado el punto de reorden 
		include_once ("alertas2.php");
		//Incluir el archivo que maneja las alertas de los materiales que han rebasado el punto de reorden 
		include_once ("alertas.php");
		/*********************************ALERTAS RECURSOS  HUMANOS*******************************/
		include_once ("alertasRH.php");
		include_once ("alertasKiosco.php");
		//Desplegar las alertas registradas en la BD
		desplegarAlertas();
		//Alertas de Sabinas
		//desplegarAlertas2();
		
		//desplegarAlertasRH();
		desplegarAlertasKiosco();
		/*********************************ALERTAS ASEGURAMIENTO CALIDAD***************************/
		//ncluimos Archivo que contiene las alertas de recordatorios de Aseguramiento de Calidad
		include("../ase/alertas_recordatoriosExternos.php");	
		//Funcion para desplegar las alertas internas=>Recordatorios de ASE
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
	
		//Vaciar la información almacenada en la SESSION cada vez que el usuario de cancelar en las diferentes paginas del Modulo de Almacen
		//Manejo de Agregar Material desde la Entrada de Almacen
		unset($_SESSION['infoEntrada']);
		
		//Manejo de la Entrada de material
		unset($_SESSION['datosEntrada']);		
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
		
		//Manejo de la generacion de la Requisicion
		unset($_SESSION['datosRequisicion']);
		unset($_SESSION['id_requisicion']);

		//Fotografias
		if(isset($_SESSION["fotosReq"]))
			unset($_SESSION['fotosReq']);
	
		//Manejo de la Generacion de Orden de Compra
		unset($_SESSION['datosOC']);
		unset($_SESSION['id_ordenOC']);
		
		//Eliminar los materiales registrados, cuando el proceso de regitro no se haya finalizado correctamente
		if(isset($_SESSION['procesoRegistroMat']) && $_SESSION['procesoRegistroMat']=="NoTerminado"){ 			
			deshacerCambios($_SESSION['clavesRegistradasMat']);						
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
    	<p><img src="../../images/logo-almacen.png" width="449" height="453" /><br/>
    	<img src="../../images/bienvenido.png" width="449" height="54" /></p>
  		</div>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
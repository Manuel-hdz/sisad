<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Incluimos archivo para generar alertas de las pruebas a las mezclas
		include_once ("alertas_pruebas.php");
		//Incluimos Archivo para generar las alertas de los equipos que estan proximos a recibir mantenimiento
		include_once ("alertas_mtto.php");
		//Desplegar las alertas registradas en la BD 
		//Función para Generar las Alertas de Pruebas a mezclas inlcuido en alertas_pruebas.php
		desplegarAlertas();
		//Función para generar las Alertas de Pruenas a Mezclas incluido en alertas_mtto.php
		desplegarAlertasMtto();
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
		
		/***********REQUISICIONES***********/
		//Fotografias
		if(isset($_SESSION["fotosReq"]))
			unset($_SESSION['fotosReq']);
		
		/************EQUIPOS*********/
		if(isset($_SESSION['datosMtto']))
			unset($_SESSION['datosMtto']);
		if(isset($_SESSION['datosEquiposLab']))
			unset($_SESSION['datosEquiposLab']);
		if(isset($_SESSION['datosRegistroMtto']))
			unset($_SESSION['datosRegistroMtto']);
		if(isset($_SESSION['datosEquipoAlerta']))
			unset($_SESSION['datosEquipoAlerta']);
			
		/***********ALERTAS PRUEBAS*****/
		if(isset($_SESSION['datosPruebaAlerta']))
			unset($_SESSION['datosPruebaAlerta']);
				
		/***********ALERTAS MTTO*****/
		if(isset($_SESSION['datosEquipoAlerta']))
			unset($_SESSION['datosEquipoAlerta']);
				
		/***********MEZCLAS*****/
		if(isset($_SESSION['mezclaGral']))
			unset($_SESSION['mezclaGral']);
		if(isset($_SESSION['materiales']))
			unset($_SESSION['materiales']);
		if(isset($_SESSION['datosMuestra']))
			unset($_SESSION['datosMuestra']);
		if(isset($_SESSION['pruebas']))
			unset($_SESSION['pruebas']);
		if(isset($_SESSION['resPruebas']))
			unset($_SESSION['resPruebas']);
		if(isset($_SESSION['fotosPruebas']))
			unset($_SESSION['fotosPruebas']);
		if(isset($_SESSION['infoAgregado']))
			unset($_SESSION['infoAgregado']);
		if(isset($_SESSION['nomAgregado']))
			unset($_SESSION['nomAgregado']);
		if(isset($_SESSION['idCatalogoPruebas']))
			unset($_SESSION['idCatalogoPruebas']);
		if(isset($_SESSION['idCarpeta']))
			unset($_SESSION['idCarpeta']);
		if(isset($_SESSION['idMezclaSel']))
			unset($_SESSION['idMezclaSel']);
		if(isset($_SESSION['pruebasEjecutadas']))
			unset($_SESSION['pruebasEjecutadas']);
		if(isset($_SESSION['memoriaFoto']))
			unset($_SESSION['memoriaFoto']);
		
		/***********CATALOGO NORMAS y RESULTADO DE LAS PRUEBAS A AGREGADOS*****/
		if(isset($_SESSION['catNormas']))
			unset($_SESSION['catNormas']);
		if(isset($_SESSION['nomAgregado']))
			unset($_SESSION['nomAgregado']);
		if(isset($_SESSION['infoAgregado']))
			unset($_SESSION['infoAgregado']);
		if(isset($_SESSION['pruebas']))
			unset($_SESSION['pruebas']);
		
		/***********RENDIMIENTO****************/
		if(isset($_SESSION['rendimiento'])) 
			unset($_SESSION['rendimiento']);
		if(isset($_SESSION['datosDisenio']))
			unset($_SESSION['datosDisenio']);
			
		/*****************ALERTAS COMPRAS********/
		//Archivo que porporciona la informacion para generar las alertas correspondientes a los historiales clinicos
		include("alertasCompras.php");
		//Archivo para desplegar las alertas de los Historiales Clinicos
		desplegarAlertasCompras();
			
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
        <p><img src="../../images/logo-laboratorio.png" width="449" height="453" /><br/>
        <img src="../../images/bienvenido.png" width="449" height="54" /></p>
      </div>
    </div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Incluir el archivo que maneja las alertas de las Requisiciones Enviadas
		include_once("alertas.php");
		//Desplegar las alertas registradas en la BD
		//desplegarAlertas();
		/***********************************ALMACEN**********************************************/
		//Incluir el archivo que maneja las alertas de las Requisiciones Enviadas
		include_once("alertasAlm.php");
		//Desplegar las alertas registradas en la BD
		desplegarAlertasAlmacen();
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
		//Verificar si esta definido el IVA en la SESSION Actual
		if(!isset($_SESSION['porcentajeIVA']))
			cargarIVA();
			
		/*********************************ALERTAS COMPRAS*******************************/
		//Archivo que porporciona la informacion para generar de los movimientos de compras
		include("alertasCompras.php");
		//Archivo para desplegar las alertas de los movimientos regoistrados en compras
		//desplegarAlertasCompras();
		/*****************************************************************************/
		
		/*********************************ALERTAS PRIORIDAD*******************************/
		include("alertasPrioridad.php");
		desplegarAlertasPrioridad();
		/*****************************************************************************/
				
		/************CAJA CHICA*********/
		//Liberar de la SESSION la variables utilizadas en la Caja Chica
		unset($_SESSION['datosCajaChica']);
		
		/*************PEDIDOS***********/
		//Registro de detalles de Pedido
		if (isset($_SESSION["detallespedido"]))
			unset($_SESSION["detallespedido"]);
			
		/**************VENTAS***********/
		//Libera los datos guardados en la SESSION
		unset($_SESSION['detalleVenta']);
		unset($_SESSION['totalVenta']);
		
		/***********PROVEEDORES*********/
		//Registro de documentos de proveedores
		unset($_SESSION["documentos"]);
		//Registro de detalles de convenios
		unset($_SESSION['detallesconvenio']);
?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

		<link rel="stylesheet" href="../../includes/b4/bootstrap/css/bootstrap.min.css">
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

			<?php
			if(isset($_POST['btn_guardartc'])){
				guardarTC();
			}

			if (!verificarExiste()){
			?>
			<div class="row" style="position: fixed;">
				<form action="" method="post">
					<div class="form-group form-row text-center">
						<div class="col-sm-8">
							<h5><span class="badge badge-success badge">TIPO DE CAMBIO DEL DIA</span></h5>
						</div>
					</div>
					<div class="form-group form-row">
						<label class="col-sm-1 col-form-label-sm" for="txt_tcdolar">Dolar</label>
						<div class="col-sm-3">
							<input class="form-control form-control-sm" type="number" name="txt_tcdolar"
								id="txt_tcdolar" step="0.0001" min="0" required>
						</div>
						<label class="col-sm-1 col-form-label-sm" for="txt_tceuro">Euro</label>
						<div class="col-sm-3">
							<input class="form-control form-control-sm" type="number" name="txt_tceuro" id="txt_tceuro"
								step="0.0001" min="0" required>
						</div>
					</div>
					<div class="form-group form-row text-center">
						<div class="col-sm-8">
							<button class="btn btn-secondary btn-sm" name="btn_guardartc"
								id="btn_guardartc">GUARDAR</button>
						</div>
					</div>
				</form>
			</div>
			<?php 
			}
			?>
			<div align="center">
				<p><img src="../../images/logo-compras.png" width="449" height="453" /><br />
					<img src="../../images/bienvenido.png" width="449" height="54" /></p>
			</div>
		</div>
	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");			
		//Manejo de la funciones para editar los registros del detalle de Requisiciones 
		include ("op_editarRegistros.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" /> 
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js" ></script>
    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:338px; height:21px; z-index:11; }
		#editar-registro { position:absolute; left:30px; top:190px; width:570px; height:380px; z-index:12; }
		-->
    </style>
</head>
<body><?php
		//Obtener el origen para ser mostrardo enla barra de titulo y en la etiqueta del Layer
		if(isset($_GET['origen']))
			$msg_origen = $_GET['origen'];
		else
			$msg_origen = $_POST['hdn_origen'];?>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>	
    <div id="titulo-barra" class="titulo_barra">Editar Registro <?php echo strtoupper($msg_origen); ?></div>
    <fieldset id="editar-registro" class="borde_seccion">
		<legend class="titulo_etiqueta">Editar Registro del Detalle de <?php echo strtoupper($msg_origen); ?></legend> 		
		<br /><?php
		//Mostrar el Formulario para editar el Registro dependiendo del origen de este
		if(isset($_GET['origen'])){
			switch($_GET['origen']){
				case "requisicion":
					editarRegistroRequisicion($_GET['pos']);
				break;
			}
		}
		
		//Guardar los cambios en el Registro dependiendo del origen de este
		if(isset($_POST['hdn_origen'])){
			switch($_POST['hdn_origen']){
				case "requisicion":
					guardarRegistroRequisicion();
				break;
			}
		}?>	
</fieldset>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
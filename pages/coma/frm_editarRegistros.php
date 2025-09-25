<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

<?php
	include ("../seguridad.php");
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	} else {
		include ("head_menu.php");			
		include ("op_editarRegistros.php");?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" /> 
			<script type="text/javascript" src="../../includes/validacionComaro.js" ></script>
			<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js" ></script>
			<style type="text/css">
				<!--
				#titulo-barra { position:absolute; left:30px; top:146px; width:338px; height:21px; z-index:11; }
				#editar-registro { position:absolute; left:30px; top:190px; width:570px; height:380px; z-index:12; }
				-->
			</style>
		</head>
		<body>
			<?php
			if(isset($_GET['origen']))
				$msg_origen = $_GET['origen'];
			else
				$msg_origen = $_POST['hdn_origen'];?>
			
			<div id="barra"><img src="../../images/title-bar-bg-comaro.png" width="999" height="30" /></div>	
			<div id="titulo-barra" class="titulo_barra">Editar Registro <?php echo strtoupper($msg_origen); ?></div>
			<fieldset id="editar-registro" class="borde_seccion">
				<legend class="titulo_etiqueta">Editar Registro del Detalle de <?php echo strtoupper($msg_origen); ?></legend> 		
				<br/>
				<?php
				if(isset($_GET['origen'])){
					switch($_GET['origen']){
						case "requisicion":
							editarRegistroRequisicion($_GET['pos']);
						break;
					}
				}
				if(isset($_POST['hdn_origen'])){
					switch($_POST['hdn_origen']){
						case "requisicion":
							guardarRegistroRequisicion();
						break;
					}
				}
				?>
			</fieldset>
		</body>
		<?php  
	}
	?>
</html>
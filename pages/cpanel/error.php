<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	
	<style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:30px; top:60px; width:90%; height:300; z-index:1; }
		-->
	</style>
</head>
<body>
	<div id="parrilla-menu1" align="center">
		<?php 
		if(isset($_GET['err'])) { 
			if($err=="AccesoNegado" && $_SESSION["usr_reg"]=="CPanel") {?>
				<meta http-equiv="refresh" content="7;url=main.php">
				<p>
					<img src="../../images/acceso-negado.png" width="265" height="264" />
					<br /><br />
					<?php echo "<label class='titulo_etiqueta'>Descripci&oacute;n: &iexcl;No tienes los permisos necesarios para ingresar a esta secci&oacute;n!</label>"; 
				?></p><?php
			}
		}?>
	</div>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
	include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	
	<style type="text/css">
		<!--
		#parrilla-menu1 { position:absolute; left:30px; top:160px; width:940px; height:400px; z-index:1; }
		-->
	</style>
</head>
<body>
	<div id="parrilla-menu1" align="center">
		<?php 
		if(isset($_GET['err'])) { 
			$usuarios=verificarUsuarioPermisos("GerenciaTecnica");
				$band=0;
				foreach($usuarios as $ind=> $valor){
					if($usr_reg==$valor){
						$band=1;
						break;
					}
				}
				//Comprarar con cada usuario que puede entrar al modulo, para verificar si se cierra la sesion o se redirecciona al Inicio						
				if($band==1){?>
 					<meta http-equiv="refresh" content="2;url=inicio_gerencia.php">
					<p>
						<img src="../../images/acceso-negado.png" width="265" height="264" />
						<br /><br />
	  					<?php echo "<label class='titulo_etiqueta'>Descripci&oacute;n: No tienes los permisos necesarios para ingresar a esta p&aacute;gina!</label>"; 
					?></p><?php
				}
				else{
              		 //Si es cualquier otro usuario cerrar la SESSION
					?><meta http-equiv="refresh" content="0;url=../salir.php"><?php		
				}
			}
			else{?>
            	<meta http-equiv="refresh" content="7;url=inicio_gerencia.php">
				<p>
					<img src="../../images/error.png" width="376" height="369" /><br />
					<br />
					<?php echo "<label class='titulo_etiqueta'>Descripci&oacute;n: $err</label>"; 
				?></p><?php
			}	
		 ?>
	</div>
</body>
</html>
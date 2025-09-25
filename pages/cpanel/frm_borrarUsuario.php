<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include("op_borrarUsuario.php");
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionCPanel.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<style type="text/css">
		<!--
		#titulo-borrar{position:absolute;left:30px;top:35px;width:298px;height:20px;z-index:11;}
		#tabla-borrarUsuario {position:absolute;left:30px;top:80px; width:90%; height:490px;z-index:12;overflow:scroll;}
		#botones {position:absolute;left:30px;top:620px; width:90%; height:20px;z-index:13;}
		-->
	</style>
	</head>
	<body>
	
	<?php
		if (isset($_POST["sbt_borrar"])){
			$res=borrarUsuario();
			if ($res==1){
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("mensaje()",500);
					function mensaje(){
						alert("Usuario Eliminado del Sistema");
					}
				</script>
				<?php
			}
			else{
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("mensaje()",500);
					function mensaje(){
						alert("Ocurrió el Siguiente Error: <?php echo $res;?>");
					}
				</script>
				<?php
			}
		}
	?>

	<div id="barraCP"><img src="../../images/title-bar-bg.gif" width="100%" height="30"/></div>
	<div class="titulo_barra" id="titulo-borrar">Borrar Usuarios</div>

	<form name="frm_borrarUsuario" method="post" action="frm_borrarUsuario.php" onsubmit="return valFormBorrarUsuarios(this);">
	<div class="borde_seccion2" id="tabla-borrarUsuario" name="tabla-borrarUsuario">
		<?php mostrarUsuarios();?>
	</div>
	
	<div id="botones" align="center">
		<input type="submit" name="sbt_borrar" id="sbt_borrar" title="Eliminar al Usuario Seleccionado" value="Eliminar" class="botones" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Inicio" 
		onMouseOver="window.status='';return true" onclick="location.href='main.php';" />
	</div>
	</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
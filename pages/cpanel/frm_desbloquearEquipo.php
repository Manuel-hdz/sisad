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
		include("op_desbloquearEquipo.php");
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionCPanel.js"></script>
	<script type="text/javascript" language="javascript">
		function desbloquear(imagen){
			var id=imagen.name;
			id=id.replace("img","",id);
			document.getElementById("hdnBorrar").value=id;
			document.frm_desbloquearEquipo.submit();
		}
	</script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<style type="text/css">
		<!--
		#titulo-desbloquear{position:absolute;left:30px;top:35px;width:298px;height:20px;z-index:11;}
		#tabla-desbloquearUsuario {position:absolute;left:30px;top:80px; width:90%; height:490px;z-index:12;overflow:scroll;}
		-->
	</style>
	</head>
	<body>
	
	<div id="barraCP"><img src="../../images/title-bar-bg.gif" width="100%" height="30"/></div>
	<div class="titulo_barra" id="titulo-desbloquear">Desbloquear Usuarios</div>

	<div id="datos" style="visibility:hidden"></div>

	<?php
		if (isset($_POST["hdnBorrar"])){
			$res=desbloquearIP();
			if ($res==1){
				?>
				<script type="text/javascript" language="javascript">
					setTimeout("mensaje()",500);
					function mensaje(){
						alert("Equipo Desbloqueado del Sistema");
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

	<form name="frm_desbloquearEquipo" method="post" action="frm_desbloquearEquipo.php">
	<input type="hidden" name="hdnBorrar" id="hdnBorrar" value=""/>
	<div class="borde_seccion2" id="tabla-desbloquearUsuario" name="tabla-desbloquearUsuario">
		<?php 
		$band=mostrarUsuarios();
		if ($band==0)
			echo "<br><br><br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'><img src='images/ok.png' width='100' height='130'/><br>¡Felicidades!<br>No Hay Ning&uacute;n Equipo Bloqueado</p>";
		?>
	</div>	
	</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
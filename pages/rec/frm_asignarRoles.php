<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para mostrar Datos y modificarlos
		include ("op_asignarRoles.php");
		?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:173px; height:25px; z-index:11; }
		#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
		#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; padding:15px; padding-top:0px; overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Consultar Empleados</div>
	
	<?php
	if (isset($_POST["sbt_guardar"]))
		asignarRol();
	?>
	
	<form name="frm_asignarRoles" method="post" action="frm_asignarRoles.php" onsubmit="return valFormAsignacionRoles(this);">
		<div id="tabla-resultados" class="borde_seccion2">
			<?php
			mostrarTrabajadores();
			?>
		</div>
		<div align="center" id="botones">
			<input type="submit" value="Guardar" title="Asignar al Empleado al Turno Seleccionado" class="botones" name="sbt_guardar" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" value="Cancelar" onclick="location.href='menu_turnos.php';" title="Cancelar el Proceso de Asignaci&oacute;n de Turnos y Volver al Men&uacute; de Turnos" class="botones" name="btn_regresar"/>
		</div>
	</form>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridadPanel.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Gerencia T�cnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		include("op_modificarPermisos.php");
		//Modulo de conexion con la base de datos
		include("../../includes/conexion.inc");	
		//Manejo de operaciones que consultan datos en la BD y los regresan en el elemento de formulario undicado en los parametros de las funciones
		include("../../includes/op_operacionesBD.php");
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionCPanel.js"></script>
	<?php /*cargarCombo que se usa para Panel de Control solamente*/?>
	<script type="text/javascript" src="includes/ajax/cargarCombo.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		<!--
		#titulo-permisos {position:absolute;left:30px;top:35px;width:298px;height:20px;z-index:11;}
		#tabla-registrarUsuario {position:absolute;left:30px;top:80px;width:400px;height:140px;z-index:12;}
		#tabla-resultados {position:absolute;left:30px;top:80px; width:90%; height:490px;z-index:13;overflow:scroll;}
		#botones {position:absolute;left:30px;top:620px; width:90%; height:20px;z-index:14;}
		-->
	</style>
	</head>
	<body>
	<div id="barraCP"><img src="../../images/title-bar-bg.gif" width="100%" height="30"/></div>
	<div class="titulo_barra" id="titulo-permisos">Agregar Permisos</div>
	<?php
	
	if (isset($_POST["hdn_seccion"])){
		$res=modificarPermiso($_POST["hdn_seccion"],$_POST["hdn_accion"],$_POST["cmb_modulo"],$_POST["cmb_usuario"]);
		if ($res==1){
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("mensaje()",500);
				function mensaje(){
					alert("Modificaci�n de Permiso Realizada");
				}
			</script>
			<?php
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
				setTimeout("mensaje()",500);
				function mensaje(){
					alert("Ocurri� el Siguiente Error: <?php echo $res;?>");
				}
			</script>
			<?php
		}
	}
	
	//Verificamos que el combo de los modulos se haya enviado mediante POST
	if (!isset($_POST["cmb_depto"])){
	?>
		<fieldset class="borde_seccion" id="tabla-registrarUsuario" name="tabla-registrarUsuario">
		<legend class="titulo_etiqueta">Seleccionar Usuario</legend>
		<br>
		<form name="frm_consultarPermisos" method="post" action="frm_registrarPermisos.php" onsubmit="return valFormConsultarPermisos(this);">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td width="35%"><div align="right">M&oacute;dulo</div></td>
				<td width="65%">
					<select name="cmb_depto" id="cmb_depto" size="1" class="combo_box" onchange="cargarCombo(this.value);">
					<option value="" selected="selected">Departamento</option>
					<option value="Almacen">ALMACEN</option>
					<option value="Calidad">ASEGURAMIENTO CALIDAD</option>
					<option value="Compras">COMPRAS</option>
					<option value="Desarrollo">DESARROLLO</option>
					<option value="GerenciaTecnica">GERENCIA TECNICA</option>
					<option value="Laboratorio">LABORATORIO</option>
					<option value="Lampisteria">LAMPISTERIA</option>
					<option value="MttoConcreto">MANTENIMIENTO CONCRETO</option>
					<option value="MttoMina">MANTENIMIENTO MINA</option>
					<option value="Paileria">PAILERIA</option>
					<option value="Produccion">PRODUCCION</option>
					<option value="RecursosHumanos">RECURSOS HUMANOS</option>
					<option value="SeguridadAmbiental">SEGURIDAD AMBIENTAL</option>
					<option value="Seguridad">SEGURIDAD INDUSTRIAL</option>
					<option value="Topografia">TOPOGRAFIA</option>
					<option value="Comaro">COMARO</option>
					<option value="Sistemas">SISTEMAS</option>
					<option value="SupervisionDes">SUPERVISION DESARROLLO</option>
				</select>
			  </td>
			</tr>
			<tr>
				<td><div align="right">Nombre de Usuario</div></td>
				<td>
					<select name="cmb_usuario" id="cmb_usuario">
					<option value="">Usuario</option>
				</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<div align="center">
						<input name="sbt_continuar" type="submit" class="botones" value="Continuar" title="Registrar Permisos del Usuario Seleccionado" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Inicio" 
						onMouseOver="window.status='';return true" onclick="location.href='main.php';" />
					</div>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
	<?php 
	}
	else{
		echo "<div id='tabla-resultados' align='center' class='borde_seccion2'>";
		echo "<form name='frm_modificarPermisos' method='post' action='frm_registrarPermisos.php'/>";
		echo "<input type='hidden' id='hdn_seccion' name='hdn_seccion' value=''/>";
		echo "<input type='hidden' id='hdn_accion' name='hdn_accion' value=''/>";
		mostrarArchivos($_POST["cmb_depto"],$_POST["cmb_usuario"]);
		echo "</div>";
		echo "</form>";
		?>
		<div id="botones" align="center">
			<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Seleccionar otro Usuario" onMouseOver="window.status='';return true" onclick="location.href='frm_registrarPermisos.php';" />
		</div>
		<?php
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
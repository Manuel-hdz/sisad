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
		include("head_menu.php");
		include("op_registrarCatalogoNormas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:230px; height:20px; z-index:11; }
		#tabla-mostrarNorma {position:absolute;left:30px;top:190px;width:753px;height:281px;z-index:12; overflow:scroll}
		#btns{position:absolute;left:30px;top:430px;width:753px;height:281px;z-index:13;}
		#tabla-agregarNorma {position:absolute;left:30px;top:190px;width:753px;height:281px;z-index:12;}
		#tabla-mostrarMateriales {position:absolute;left:30px;top:500px;width:753px;height:200px;z-index:13; overflow:scroll}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
   <?php
   	if(isset($_SESSION['catNormas'])){
		unset($_SESSION['catNormas']);
	} 
	//Verificar que no existan los botones para mostrar o no el formulario
   	 if(!isset($_POST['sbt_modificar']) && !isset($_POST['sbt_guardarModificacion'])&& !isset($_POST['sbt_eliminar'])){?> 
   		<div class="titulo_barra" id="titulo-agregar">Seleccionar Acci&oacute;n </div>
		<br>
		<form name="frm_opcionesCatalogo"  id="frm_opcionesCatalogo" method="post" onsubmit="return valFormOpcionesCatalogo(this);" 
		action="frm_opcionesCatalogoNormas.php">
		<div class="borde_seccion2" id="tabla-mostrarNorma" align="center">
			<?php $band=mostrarNormas();?>
		</div>
		<div id="btns">
		<table width="749" height="253" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td colspan="6">
					<div align="center"> 
						<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
						<input type="hidden" name="hdn_band" id="hdn_band" value="" />     	    	
						<input name="btn_agregar" type="button" class="botones"  value="Agregar" title="Agregar Registro" 
						onMouseOver="window.status='';return true" onclick="location.href='frm_registrarCatalogoNormas.php'" />
						&nbsp;&nbsp;&nbsp;
						<?php if($band!=0){?>
						<input name="sbt_eliminar" type="submit" class="botones"  value="Eliminar" title="Eliminar Registro" 
						onMouseOver="window.status='';return true"  onclick="hdn_botonSeleccionado.value='sbt_eliminar'" />
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_modificar" type="submit" class="botones"  value="Modificar" title="Modificar Registro" 
						onMouseOver="window.status='';return true"  onclick="hdn_botonSeleccionado.value='sbt_modificar'" />
						&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Pruebas" 
						onMouseOver="window.status='';return true" onclick="confirmarSalida('menu_pruebas.php')" />
					</div>			
				</td>
			</tr>
		</table>
		</form>
		</div><?php 
	}?>
</body><?php 
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
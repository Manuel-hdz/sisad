<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Archivo con la operacion de Consultar Equipo
		include ("op_consultarEquipo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-consultar {position:absolute;left:30px;top:146px;width:323px;height:20px;z-index:11;}
		#tabla-consultarDisp {position:absolute;left:30px;top:190px;width:328px;height:120px;z-index:12}
		#btns{position:absolute;width:533px;height:42px;z-index:15;left:40px;top:506px;}		
		-->
    </style>
</head>
<body><?php 
	if(isset($_POST['sbt_guardar']))	
		cambiarDisponibilidadEq();
			
	$id_equipo= "";
	$disponibilidad="";
	if(isset($_GET['id_equipo'])) 
		$id_equipo= $_GET['id_equipo'];
	if(isset($_GET['disponibilidad']))
		$disponibilidad= $_GET['disponibilidad'];
	
	 if(!isset($_POST['sbt_guardar'])){?>
			
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-consultar">Disponibilidad de Equipos</div>
			
		<fieldset class="borde_seccion" id="tabla-consultarDisp" name="tabla-consultarDisp">
		<legend class="titulo_etiqueta">Seleccionar Disponibilidad</legend>
		<legend class="titulo_etiqueta">Equipo <?php echo $id_equipo;?></legend>
		<br>
		<form onSubmit="return valFormCambiarDisponibilidad(this);" name="frm_consultarDisponibilidadEq" method="post" action="frm_consultarDisponibilidadEq.php">
		<table width="285" cellpadding="5" cellspacing="5" class="tabla_frm">	
			<tr>
				<td width="80">Disponibilidad</td>
				<td width="168">
					<select name="cmb_disponibilidad" class="combo_box">
						<option value="">Disponibilidad</option>
						<option value="ACTIVO" <?php if($disponibilidad=="ACTIVO") echo "selected='selected'"; ?>>ACTIVO</option>
						<option value="INACTIVO" <?php if($disponibilidad=="INACTIVO") echo "selected='selected'"; ?>>INACTIVO</option>
					</select>
				</td>
			</tr>
			<tr>	                
				<td colspan="2" align="center">			
					<input name="sbt_guardar" type="submit" class="botones" value="Guardar" title="Cambiar Disponibilidad" 
					onmouseover="window.status='';return true" />
					<input type="hidden" name="hdn_idEquipo" id="hdn_idEquipo" value="<?php echo $id_equipo;?>"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset><?php
	}// FIN if(!isset($_POST['sbt_guardar']))?>	
			
</body><?php 
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
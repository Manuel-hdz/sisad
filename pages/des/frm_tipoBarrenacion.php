<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
	?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
    <style type="text/css">
		<!--
		#titulo-regBarrenacion { position:absolute; left:30px; top:146px; width:350px; height:20px; z-index:11; }
		#seleccionar-barrenacion { position:absolute; left:34px; top:188px; width:417px; height:145px; z-index:12; }		
		-->
    </style>
</head>
<body>


	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>		
    <div id="titulo-regBarrenacion" class="titulo_barra">Registro de Barrenaci&oacute;n</div>
	
	
    <fieldset id="seleccionar-barrenacion" class="borde_seccion">
		<legend class="titulo_etiqueta">Seleccionar el Tipo de Equipo Usado para la Barrenaci&oacute;n</legend>
		<form onsubmit="return valFormSeleccionarEquipo(this);" name="frm_seleccionarEquipo" method="post">
		<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<td align="right">Tipo Equipo</td>
				<td>
					<select name="cmb_tipoEquipo" id="cmb_tipoEquipo" class="combo_box">
						<option value="">Equipo</option>
						<?php if($_SESSION['bitsAgregadas']['bitBarrenacion']==0){?>
							<option value="JUMBO">JUMBO</option>
						<?php } 
						if($_SESSION['bitsAgregadas']['bitBarrenacionMP']==0){ ?>
							<option value="MAQUINA DE PIERNA">MAQUINA DE PIERNA</option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<?php //Guardar el Id de la Bitacora de Avance y el Tipo de Bitacora para guardar las Fallas y Consumos que vayan a ser registrados ?>
					<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $_POST['hdn_idBitacora']; ?>" />
					<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="<?php echo $_POST['hdn_tipoBitacora']; ?>" />					
					
					<input type="submit" name="sbt_continuar" value="Continuar" class="botones" title="Continuar con el Registro de Barrenaci&oacute;n" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Registro de la Bit&aacute;cora de Avance" 
					onclick="location.href='frm_regAvance.php'" />
				</td>
			</tr>
		</table>
		</form>
	</fieldset>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
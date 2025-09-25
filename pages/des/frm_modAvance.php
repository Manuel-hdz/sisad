<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		
		include ("op_modAvance.php");?>


	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

		<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
		<script type="text/javascript" src="../../includes/validacionDesarrollo.js"></script>
		<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
		<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen" />

		<style type="text/css">
			#titulo-modificarBitacora {
				position: absolute;
				left: 30px;
				top: 146px;
				width: 350px;
				height: 20px;
				z-index: 11;
			}

			#consultarRegBitacora {
				position: absolute;
				left: 30px;
				top: 190px;
				width: 437px;
				height: 140px;
				z-index: 12;
			}

			#calendario-f1 {
				position: absolute;
				left: 246px;
				top: 232px;
				width: 30px;
				height: 27px;
				z-index: 13;
			}

			#calendario-f2 {
				position: absolute;
				left: 453px;
				top: 232px;
				width: 30px;
				height: 27px;
				z-index: 14;
			}

			#seleccionarRegBitacora {
				position: absolute;
				left: 30px;
				top: 370px;
				width: 940px;
				height: 250px;
				z-index: 15;
				overflow: scroll;
			}

			#div-botones {
				position: absolute;
				left: 30px;
				top: 670px;
				width: 940px;
				height: 40px;
				z-index: 16;
			}
		</style>
	</head>

	<body><?php

	//Quitar de la SESSION los datos de la Bitacora Seleccionada para su edici�n en el caso de que exista
	if(isset($_SESSION['bitacoraAvance']))
		unset($_SESSION['bitacoraAvance']);?>


		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div id="titulo-modificarBitacora" class="titulo_barra">Modificar Registro de la Bit&aacute;cora de Avance</div>


		<fieldset class="borde_seccion" id="consultarRegBitacora" name="consultarRegBitacora">
			<legend class="titulo_etiqueta">Seleccionar Registro para Complmentar/Modificar</legend>
			<br />
			<form onsubmit="return valFormSeleccionarRegBitAvance(this);" name="frm_seleccionarRegBitAvance" method="post"
				action="frm_modAvance.php">
				<table width="100%" cellpadding="5" cellspacing="5">
					<tr>
						<td align="right">Fecha Inicio</td>
						<td>
							<input type="text" name="txt_fechaIni" id="txt_fechaIni" class="caja_de_texto" readonly="readonly"
								size="10" value="<?php echo date("d/m/Y", strtotime("-7 day")); ?>" />
						</td>
						<td align="right">Fecha Fin</td>
						<td>
							<input type="text" name="txt_fechaFin" id="txt_fechaFin" class="caja_de_texto" readonly="readonly"
								size="10" value="<?php echo date("d/m/Y"); ?>" />
						</td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4" align="center">
							<input type="submit" name="sbt_consultar" class="botones" value="Consultar"
								title="Consultar Registro en la Bit&aacute;cora de Avance" onmouseover="window.status='';return true" />
							&nbsp;&nbsp;&nbsp;
							<input type="button" name="btn_regresar" class="botones" value="Regresar"
								title="Regresar al Men&uacute; de Bit&aacute;cora de Avance"
								onclick="location.href='menu_bitAvance.php'" />
						</td>
					</tr>
				</table>
			</form>
		</fieldset>
		<div id="calendario-f1">
			<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_seleccionarRegBitAvance.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom"
				title="Seleccionar Fecha de Inicio" />
		</div>
		<div id="calendario-f2">
			<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_seleccionarRegBitAvance.txt_fechaFin,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom"
				title="Seleccionar Fecha de Cierre" />
		</div><?php		
	
	
	
	//Si esta definido el boton de consultar, proceder a mostrar los registros encontrados en las fechas seleccionadas
	if(isset($_POST['sbt_consultar'])){?>
		<form onsubmit="return valFormSelecRegistroBitAvance(this);" name="frm_selecRegistroBitAvance" method="post"
			action="frm_modAvance2.php">
			<div id="seleccionarRegBitacora" class="borde_seccion2" align="center"><?php
				$res=verRegistrosBitAvance();?>
			</div>
			<?php if($res==1){?>
			<div id="div-botones" align="center">
				<input type="submit" name="sbt_selecRegistro" id="sbt_selecRegistro" class="botones" value="Seleccionar"
					title="Editar Registro Seleccionado" onmouseover="window.status='';return true" />
			</div>
			<?php }?>
		</form><?php
	}?>

	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
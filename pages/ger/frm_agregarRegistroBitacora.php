<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Tecnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Manejo de la funciones para Registrar los datos de de la Bitacora que se maneja en Gerencia Tecnica
		include ("op_agregarRegistroBitacora.php");?>
				
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
			<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
			<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
			<script type="text/javascript" src="includes/ajax/cargarComboPresupuesto.js"></script>
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			
			<style type="text/css">
				<!--
				#titulo-mostrarPeriodo {position:absolute; left:30px; top:146px; width:219px; height:20px; z-index:11; }
				#tabla-mostrarPeriodoBitacora { position:absolute; left:30px; top:190px; width:500px; height:150px; z-index:17; }
				-->
			</style>
		</head>
		<body>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-mostrarPeriodo">Agregar Registro a la Bitácora</div>
			<fieldset class="borde_seccion" id="tabla-mostrarPeriodoBitacora">
				<legend class="titulo_etiqueta">Ingresar la Información del Registro</legend>
				<br>
				<form onSubmit="return valFormAgregarRegistroBitacora(this);" name="frm_agregarRegistroBitacora" method="post" action="frm_agregarLanzamientoBitacora.php">
					<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
						<tr>
							<td>
								<div align="right">Ubicaci&oacute;n</div>
							</td>
							<td width="200">
								<select name="cmb_ubicacion" id="cmb_ubicacion" size="1" class="combo_box" required="required" 
								onchange="cargarPresupuesto(this,'cmb_periodo','Presupuesto'); document.getElementById('txt_concepto').value=this.options[this.selectedIndex].text;">
									<option value="">Ubicaci&oacute;n</option>
									<?php
									$cmb_ubicacion="";				
									$conn = conecta("bd_gerencia");
									$result = mysql_query ("SELECT T1.id_control_costos, T2.descripcion
															FROM  `presupuesto` AS T1
															JOIN bd_recursos.control_costos AS T2
															USING ( id_control_costos ) 
															WHERE T2.habilitado =  'SI'
															GROUP BY T2.descripcion
															ORDER BY T2.descripcion");				 
									while ($row=mysql_fetch_array($result)){
										echo "<option value='$row[id_control_costos]'>$row[descripcion]</option>";
									}
									mysql_close($conn);
									?>
								</select>
							</td>
							<td>
								<div align="right">
									<span id="etiquetaPeriodo">Periodo</span>
								</div>
							</td>
							<td width="147">
								<select name="cmb_periodo" id="cmb_periodo" class="combo_box" required="required">
									<option value="">Seleccione</option>
								</select>
							</td>
						</tr>
						<tr>
							<td>&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;</td>
							<td>&nbsp;&nbsp;</td>
						</tr>
						<input name="txt_concepto" id="txt_concepto" type="hidden" class="caja_de_texto" size="50" readonly="readOnly" />
						<tr>
							<td colspan="4">
								<div align="center">
									<input name="sbt_continuarRegistroBitacora" type="submit" class="botones"  value="Continuar" title="Continuar con el Registro de la Bitacora" 
									onMouseOver="window.status='';return true" />
									&nbsp;&nbsp;&nbsp;
									<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar" 
									onMouseOver="window.status='';return true" onclick="location.href='menu_bitacora.php';" />
								</div>
							</td>
						</tr>
					</table>
				</form>
			</fieldset>
		</body>	
	<?php  
	}
	?>
</html>
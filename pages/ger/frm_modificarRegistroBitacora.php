<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarRegistroBitacora.php");?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
			<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>  
			<script type="text/javascript" src="includes/ajax/cargarComboGT.js"></script>
			<script type="text/javascript" src="includes/ajax/cargarComboPresupuesto.js"></script>
			<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			
			<style type="text/css">
				<!--
				#titulo-modificar {position:absolute; left:30px; top:146px; width:219px; height:20px; z-index:11; }
				#tabla-modificarRegistro { position:absolute; left:30px; top:190px; width:940px; height:100px; z-index:17; }
				#mostrarBit {position:absolute;left:30px;top:320px;width:940px;height:340px;z-index:12;overflow:auto}
				#btnReg {position:absolute;left:30px;top:680px;width:940px;height:20px;z-index:12;}
				#calendario-Ini {position:absolute;left:743px;top:228px;width:30px;height:26px;z-index:17;}
				#calendario-Fin {position:absolute;left:945px;top:228px;width:30px;height:26px;z-index:17;}
				-->
			</style>
		</head>
		<body>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-modificar">Modificar Registro Bitácora</div>
			<fieldset class="borde_seccion" id="tabla-modificarRegistro">
				<legend class="titulo_etiqueta">Ingresar Parametros de Busqueda</legend>
				<br>
				<form onSubmit="" name="frm_modificarRegistroBitacora" method="post" action="">
					<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
						<tr>
							<td>
								<div align="right">Ubicaci&oacute;n</div>
							</td>
							<td width="200">
								<select name="cmb_ubicacion" id="cmb_ubicacion" size="1" class="combo_box" required="required" 
								onchange="cargarPresupuesto(this,'cmb_periodo','Presupuesto');document.getElementById('txt_cuadrilla').value=this.options[this.selectedIndex].text;">
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
								<input type="hidden" name="txt_cuadrilla" id="txt_cuadrilla" value=""/>
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
							<td>
								<div align="right">Fecha Inicial</div>
							</td>
							<td>
								<input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" 
								value="<?php echo date("d/m/Y"); ?>" readonly="readonly" onchange="comprobarFecha();"/>
							</td>
							<td>
								<div align="right">Fecha Final</div>
							</td>
							<td>
								<input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" 
								value="<?php echo date("d/m/Y", strtotime("+1 month")); ?>" readonly="readonly" onchange="comprobarFecha();"/>
							</td>
						</tr>
						<tr>
							<td colspan="8">
								<div align="center">
									<input name="sbt_continuarModificarBitacora" type="submit" class="botones"  value="Continuar" title="Continuar con la Modificacion de la Bitacora" 
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
			<div id="calendario-Ini">
				<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_modificarRegistroBitacora.txt_fechaIni,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha de Inicio"/> 
			</div>
			
			<div id="calendario-Fin">
				<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_modificarRegistroBitacora.txt_fechaFin,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha de Fin"/>
			</div>
			<?php
			if(isset($_POST["sbt_continuarModificarBitacora"])){
			?>
				<form name="frm_bitacoras" id="frm_bitacoras" method="post" action="frm_modificarLanzamientoBitacora.php">
					<fieldset class="borde_seccion" id="mostrarBit">
						<?php
						mostrarBitacoras();
						?>
					</fieldset>
					<div id="btnReg" align="center">
						<input name="sbt_continuarModificacion" type="submit" class="botones"  value="Continuar" 
						title="Continuar con la Modificacion de la Bitacora" onMouseOver="window.status='';return true" />
					</div>
				</form>
			<?php	
			}
			?>
		</body>
	<?php 
	}
	?>
</html>
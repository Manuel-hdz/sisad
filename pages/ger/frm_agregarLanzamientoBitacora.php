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
			
			<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
			<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
			<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
			<script type="text/javascript" src="includes/ajax/busq_spider_personal_bitacora.js"></script>
			<script type="text/javascript" src="includes/ajax/comprobarFechaPresupuesto.js"></script>
			<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
			<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>     
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			
			<style type="text/css">
				<!--
				#titulo-lanzamientosBitacora {position:absolute; left:30px; top:146px; width:284px; height:20px; z-index:11; }
				#informacionBitacora {position:absolute;left:58px;top:557px;width:897px;height:201px;z-index:2;}
				#tabla-mostrarLanzamientoBitacora { position:absolute; left:30px; top:188px; width:930px; height:130px; z-index:13;}
				#calendario_fechaRegistro {position:absolute;left:510px;top:226px;width:30px;height:27px;z-index:14;}
				#detalleRegBit { position:absolute; left:30px; top:345px; width:930px; height:315px; z-index:15; overflow:auto}
				#botones_pdf { position:absolute; left:30px; top:685px; width:950px; height:35px; z-index:15;}
				-->
			</style>
		</head>
		<body>
			<?php
			if(isset($_POST['sbt_continuarRegistroBitacora'])){
				$destino = $_POST['txt_concepto'];
				$periodo=$_POST['cmb_periodo'];
				$idUbicacion = $_POST['cmb_ubicacion'];
				$fechaD = obtenerDatosTabla("presupuesto","fecha_inicio","id_presupuesto",$periodo,"bd_gerencia");
				$fechaD = modFecha($fechaD,1);
				$infoBitacora= array ("destino"=>$destino, "periodo"=>$periodo, "idUbicacion"=>$idUbicacion, "fechaD"=>$fechaD);
				$_SESSION['infoBitacora'] = $infoBitacora;
			}
			
			if(isset($_POST["btn_registrar"])){
				guardarBitacora();
			}
			?>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-lanzamientosBitacora">Complementar el Registro a la Bitácora</div>
			<fieldset class="borde_seccion" id="tabla-mostrarLanzamientoBitacora">
				<legend class="titulo_etiqueta">Ingresar la Información del Registro de Lanzamiento</legend>
				<br>
				<form onSubmit="return valFormRegistroLanzamientoBitacora(this);" name="frm_agregarLanzamientoBitacora" method="post" action="frm_agregarLanzamientoBitacora.php">
					<table width="100%"  cellpadding="5" cellspacing="5" class="tabla_frm">
						<tr>
							<td width="15px" >
								<div align="right">*Destino </div>
							</td>
							<td width="30px" >
								<input type="text" name="txt_destino" id="txt_destino" class="caja_de_texto" value="<?php echo $_SESSION['infoBitacora']['destino']; ?>" 
								readonly="readonly" size="35" />
							</td>
							<td width="15px">
								<div align="right">*Fecha</div>
							</td>
							<td width="40px">
								<?php
								if(!isset($_POST["sbt_continuarRegLanzamientoBitacora"])){
								?>
									<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" value="<?php echo $_SESSION['infoBitacora']['fechaD']; ?>" size="10" readonly="readonly" 
									onchange="comprobarFecha(this.value,this,'<?php echo $_SESSION['infoBitacora']['periodo']; ?>','<?php echo $_SESSION['infoBitacora']['fechaD']; ?>')"/>
								<?php
								} else {
								?>
									<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" value="<?php echo $_POST["txt_fechaRegistro"]; ?>" size="10" readonly="readonly"/>
								<?php
								}
								?>
							</td>
							<td width="15px">
								<div align="right">*Cuadrilla</div>
							</td>
							<td>
								<?php
								if(!isset($_POST["sbt_continuarRegLanzamientoBitacora"])){
								?>
									<select name="cmb_cuadrillas" id="cmb_cuadrillas" class="combo_box" required="required"
									onchange="document.getElementById('txt_cuadrilla').value=this.options[this.selectedIndex].text;">
										<option value="">Cuadrillas</option>
										<?php
										$id_cuad = $_SESSION['infoBitacora']['idUbicacion'];
										$conn = conecta("bd_gerencia");
										$result = mysql_query ("SELECT T1.id_cuadrilla, T2.nombre_emp
																FROM cuadrillas AS T1
																JOIN integrantes_cuadrilla AS T2
																USING ( id_cuadrilla ) 
																WHERE id_control_costos LIKE  '$id_cuad'
																AND puesto LIKE  'LANZADOR'
																GROUP BY id_cuadrilla");				 
										while ($row=mysql_fetch_array($result)){
											echo "<option value='$row[id_cuadrilla]'>$row[id_cuadrilla] - $row[nombre_emp]</option>";
										}
										mysql_close($conn);
										?>
									</select>
									<input type="hidden" name="txt_cuadrilla" id="txt_cuadrilla" value=""/>
								<?php
								} else {
									echo "<input type='hidden' name='txt_idCuadrilla' id='txt_idCuadrilla' value='".$_POST['cmb_cuadrillas']."'/>";
									echo "<input type='text' name='txt_cuadrilla' id='txt_cuadrilla' size='55' value='".$_POST['txt_cuadrilla']."' readonly='readonly'/>";
								}
								?>
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<div align="center">
									<strong>* Datos marcados con asterisco son <u>obligatorios</u></strong>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<div align="center">
									<?php
									if(!isset($_POST["sbt_continuarRegLanzamientoBitacora"])){
									?>
										<input name="sbt_continuarRegLanzamientoBitacora" id="sbt_continuarRegLanzamientoBitacora" type="submit" class="botones"  
										value="Continuar" title="Continuar con el Registro del Lanzamiento en la Bitacora" onMouseOver="window.status='';return true" />
										&nbsp;&nbsp;&nbsp;
										<input id="btn_cancelar" name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Bitácora" 
										onMouseOver="window.status='';return true" onclick="location.href='frm_agregarRegistroBitacora.php';" />
									<?php
									} else {
									?>
										<input id="btn_cancelar" name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Bitácora" 
										onMouseOver="window.status='';return true" onclick="location.href='frm_agregarLanzamientoBitacora.php';" />
									<?php
									}
									?>
								</div>
							</td>
						</tr>
					</table>
				</form>
			</fieldset>
			
			<?php
			if(isset($_POST["sbt_continuarRegLanzamientoBitacora"])){
			?>
				<form name="frm_registrarBitacora" id="frm_registrarBitacora" method="post" action="frm_agregarLanzamientoBitacora.php" >
					<fieldset class="borde_seccion" id="detalleRegBit">
						<?php
						$num_reg = mostrarCuadrilla();
						?>
						<input type="hidden" name="num_registros" id="num_registros" value="<?php echo $num_reg; ?>"/>
						<input type="hidden" name="txt_idCuadrilla" id="txt_idCuadrilla" value="<?php echo $_POST['cmb_cuadrillas']; ?>"/>
						<input type="hidden" name="txt_fechaRegistro" id="txt_fechaRegistro" value="<?php echo $_POST["txt_fechaRegistro"]; ?>"/>
					</fieldset>
					<div id="botones_pdf" name="botones_pdf" align="center">
						<input id="btn_registrar" name="btn_registrar" type="submit" class="botones" value="Registrar" title="Registrar bitacora de Zarpeo" onMouseOver="window.status='';return true" />
					</div>
				</form>
			<?php
			}
			
			if(!isset($_POST["sbt_continuarRegLanzamientoBitacora"])){
			?>
				<div id="calendario_fechaRegistro">
					<input type="image" name="txt_fechaRegistro" id="txt_fechaRegistro" src="../../images/calendar.png"
					onclick="displayCalendar(document.frm_agregarLanzamientoBitacora.txt_fechaRegistro,'dd/mm/yyyy',this)" 
					onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
					title="Seleccionar Fecha de Registro"/>
				</div>
			<?php
			}
			?>
		</body>
	<?php 
	}
	?>
</html>
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
		include ("op_modificarBitacoras.php");?>
		
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			
			<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
			<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
			<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
			<script type="text/javascript" src="includes/ajax/busq_spider_personal_bitacora.js"></script>
			<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
			<script type="text/javascript" src="includes/ajax/comprobarFechaPresupuesto.js"></script>
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
			
			<style type="text/css">
				<!--
				#titulo-lanzamientosBitacora {position:absolute; left:30px; top:146px; width:284px; height:20px; z-index:11; }
				#informacionBitacora {position:absolute;left:58px;top:557px;width:897px;height:201px;z-index:2;}
				#tabla-mostrarLanzamientoBitacora { position:absolute; left:30px; top:188px; width:930px; height:60px; z-index:13;}
				#calendario_fechaRegistro {position:absolute;left:503px;top:212px;width:30px;height:27px;z-index:14;}
				#detalleRegBit { position:absolute; left:30px; top:275px; width:930px; height:380px; z-index:15; overflow:auto}
				#boton_modificar { position:absolute; left:350px; top:685px; width:150px; height:35px; z-index:15; border-style:none;}
				#boton_cancelar { position:absolute; left:500px; top:685px; width:150px; height:35px; z-index:15; border-style:none;}
				-->
			</style>
		</head>
		<body>
			<?php
			if(isset($_POST["btn_modificar"])){
				actualizarBitacora();
			}
			$id_bitacora = $_POST["rdb_idBitacora"];
			$conn = conecta("bd_gerencia");
			$stm_sql = "SELECT * 
						FROM bitacora AS T1
						JOIN detalle_bitacora AS T2
						USING ( id_bitacora ) 
						JOIN bd_recursos.control_costos AS T3
						USING ( id_control_costos ) 
						WHERE id_bitacora LIKE  '$id_bitacora'";
			$rs = mysql_query($stm_sql);
			$datos = mysql_fetch_array($rs);
			?>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-lanzamientosBitacora">Modificar datos de Bitácora</div>
			<form name="frm_modificarBitacora" id="frm_modificarBitacora" method="post" action="frm_modificarLanzamientoBitacora.php" >
				<fieldset class="borde_seccion" id="tabla-mostrarLanzamientoBitacora">
					<legend class="titulo_etiqueta">Modificar Información del Registro de Lanzamiento</legend>
					<table width="100%"  cellpadding="5" cellspacing="5" class="tabla_frm">
					<input type="hidden" name="rdb_idBitacora" id="rdb_idBitacora" value="<?php echo $id_bitacora; ?>"/>
						<tr>
							<td width="15px" >
								<div align="right">*Destino </div>
							</td>
							<td width="30px" >
								<input type="text" name="txt_destino" id="txt_destino" class="caja_de_texto" value="<?php echo $datos['descripcion']; ?>"
								readonly="readonly" size="35" />
							</td>
							<td width="15px">
								<div align="right">*Fecha</div>
							</td>
							<td width="40px">
								<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" value="<?php echo modFecha($datos['fecha'],1); ?>" size="10" readonly="readonly" 
								onchange="comprobarFecha(this.value,this,'<?php echo $datos['id_presupuesto']; ?>','<?php echo modFecha($datos['fecha'],1); ?>')"/>
							</td>
							<td width="15px">
								<div align="right">*Cuadrilla</div>
							</td>
							<td>
								<?php
								echo "<input type='hidden' name='txt_idCuadrilla' id='txt_idCuadrilla' value='".$datos['id_cuadrilla']."'/>";
								echo "<input type='text' name='txt_cuad' id='txt_cuad' size='55' value='".$datos['id_cuadrilla']." - ".$datos['nombre_emp']."' readonly='readonly'/>";
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
					</table>
				</fieldset>
				<fieldset class="borde_seccion" id="detalleRegBit">
					<?php
					$num_reg = mostrarCuadrilla($rs,$datos);
					?>
					<input type="hidden" name="num_registros" id="num_registros" value="<?php echo $num_reg; ?>"/>
				</fieldset>
				<div id="boton_modificar" name="boton_modificar" align="center">
					<input id="btn_modificar" name="btn_modificar" type="submit" class="botones" value="Modificar" title="Modificar bitacora de Zarpeo" onMouseOver="window.status='';return true" />
				</div>
			<!--
			</form>
			<form name="frm_regresarBitacora" id="frm_regresarBitacora" method="post" action="frm_modificarRegistroBitacora.php">
			-->
				<div id="boton_cancelar" name="boton_cancelar" align="center">
					<input id="btn_cancelar" name="btn_cancelar" type="submit" class="botones" value="Regresar" title="Regresar a consulta de bitacoras de Zarpeo" 
					onMouseOver="window.status='';return true" onclick="document.getElementById('frm_modificarBitacora').action='frm_modificarRegistroBitacora.php'"/>
					<input type='hidden' name='cmb_ubicacion' id='cmb_ubicacion' value='<?php echo $_POST['cmb_ubicacion']; ?>'/>
					<input type='hidden' name='txt_cuadrilla' id='txt_cuadrilla' value='<?php echo $_POST['txt_cuadrilla']; ?>'/>
					<input type='hidden' name='cmb_periodo' id='cmb_periodo' value='<?php echo $_POST['cmb_periodo']; ?>'/>
					<input type='hidden' name='txt_fechaIni' id='txt_fechaIni' value='<?php echo $_POST['txt_fechaIni']; ?>'/>
					<input type='hidden' name='txt_fechaFin' id='txt_fechaFin' value='<?php echo $_POST['txt_fechaFin']; ?>'/>
					<input type='hidden' name='sbt_continuarModificarBitacora' id='sbt_continuarModificarBitacora'/>
				</div>
			</form>
			<div id="calendario_fechaRegistro">
				<input type="image" name="txt_fechaRegistro" id="txt_fechaRegistro" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_modificarBitacora.txt_fechaRegistro,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha de Registro"/>
			</div>
		</body>
	<?php
		mysql_close($conn);
	}
	?>
</html>
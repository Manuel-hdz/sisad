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
		include ("op_agregarCuadrilla.php");?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
		<script type="text/javascript" src="includes/ajax/calcularID.js" ></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
		<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
		<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
		<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
		<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
		
		<style type="text/css">
			<!--
			#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
			#tabla-registrarPresupuesto {position:absolute;left:30px;top:190px;width:940px;height:170px;z-index:14;}
			#res-spider {position:absolute;z-index:15;}
			#tabla-personal{position:absolute;left:30px;top:390px;width:940px;height:290px;z-index:13;overflow:scroll;}
			-->
		</style>
	</head>
	<body>
		<?php	
		if (!isset($_POST["sbt_finalizar"])){
			
			if(isset($_POST['sbt_continuar'])){
				$id_cuadrilla = $_POST["txt_IDCuadrilla"];
				$comentarios = strtoupper($_POST["txa_comentarios"]);
				if (isset($_POST["cmb_ubicacion"])){
					$ubicacion = $_POST["cmb_ubicacion"];
				}
				else if (isset($_POST["cmb_nuevaUbicacion"])){
					$ubicacion = $_POST["cmb_nuevaUbicacion"];
				}
				if(isset($_POST['ckb_zarpeoViaSeca']))
					$via_seca = $_POST['ckb_zarpeoViaSeca'];
				else
					$via_seca = "";
				
				if(isset($_POST['ckb_zarpeoViaHumeda']))
					$via_humeda = $_POST['ckb_zarpeoViaHumeda'];
				else
					$via_humeda = "";
				
				$_SESSION["cuadrilla"] = array(
											"id_cuadrilla"=>$id_cuadrilla,
											"comentarios"=>$comentarios,
											"ubicacion"=>$ubicacion,
											"via_seca"=>$via_seca,
											"via_humeda"=>$via_humeda
										 );
			}
			
			if (isset($_POST["sbt_agregar"])){
				if(isset($_SESSION['personalCuadrilla'])){
					
					$ctrl = verificarPersona($hdn_rfc,$cmb_puesto);
					
					if ($ctrl == 0){
						$personalCuadrilla[] = array("rfc"=>strtoupper($hdn_rfc),"nombre"=>strtoupper($txt_nombre), "puesto"=>strtoupper($cmb_puesto));
					}
					else{
						if ($ctrl==1){
							?>
							<script type="text/javascript" language="javascript">
								setTimeout("mensaje();",1000);
								function mensaje(){
									alert("El Trabajador ya fue Agregado a la Cuadrilla Actual");
								}
							</script>
							<?php
						}
						if ($ctrl==2){
							?>
							<script type="text/javascript" language="javascript">
								setTimeout("mensaje();",1000);
								function mensaje(){
									alert("El Puesto ya fue Agregado a la Cuadrilla Actual");
								}
							</script>
							<?php
						}
						if ($ctrl!=1 && $ctrl!=2){
							?>
							<script type="text/javascript" language="javascript">
								setTimeout("mensaje();",1000);
								function mensaje(){
									alert("El Trabajador <?php echo $txt_nombre;?> Pertenece a la Cuadrilla <?php echo  $ctrl;?>");
								}
							</script>
							<?php
						}
					}
				}
				
				else{		
					$ctrl=verificarPersona($hdn_rfc,$cmb_puesto);
					
					if ($ctrl == ""){
						$personalCuadrilla = array(array("rfc"=>strtoupper($hdn_rfc),"nombre"=>strtoupper($txt_nombre), "puesto"=>strtoupper($cmb_puesto)));
						$_SESSION['personalCuadrilla'] = $personalCuadrilla;
					} else {
						?>
						<script type="text/javascript" language="javascript">
							setTimeout("mensaje();",1000);
							function mensaje(){
								alert("El Trabajador <?php echo  $txt_nombre;?> Pertenece a la Cuadrilla <?php echo  $ctrl;?>");
							}
						</script>
					<?php
					}
				}
			}
			
			if (isset($_POST["btn_eliminar"])){
				borrarPersonal($_POST["txt_rfc"]);
			}
			
			if (isset($_SESSION["personalCuadrilla"])){
				?>
				<div id='tabla-personal' class='borde_seccion'>
				<?php
					mostrarPersonalCuadrilla($_SESSION["personalCuadrilla"]);
				?>
				</div>
				<?php
			}
			
			$area = $_POST["txt_ubicacion"];
			?>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-registrar">Agregar Cuadrilla</div>
			<fieldset class="borde_seccion" id="tabla-registrarPresupuesto" name="tabla-registrarPresupuesto">
				<legend class="titulo_etiqueta">Ingresar el Personal que Integra la Cuadrilla</legend>	
				<br>
				<form name="frm_agregarCuadrilla" method="post" action="frm_agregarCuadrilla2.php" onsubmit="return valFormCuadrillas2(this)">
					<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
						<tr>
							<td width="128">
								<div align="right">ID Cuadrilla</div>
							</td>
							<td>
								<input name="txt_IDCuadrilla" id="txt_IDCuadrilla" type="text" class="caja_de_texto" size="15" value="<?php echo $_SESSION["cuadrilla"]["id_cuadrilla"];?>" readonly="readonly"/>
							</td>
							<td width="128">
								<div align="right">Ubicaci&oacute;n</div>
							</td>
							<td>
								<input name="txt_ubicacion" id="txt_ubicacion" type="text" class="caja_de_texto" size="30" value="<?php echo $area;?>" readonly="readonly"/>
							</td>
						</tr>
						<tr>
							<td>
								<div align="right">*Nombre</div>
							</td>
							<td>
								<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados','1','<?php echo $_SESSION["cuadrilla"]["ubicacion"]; ?>');" 
								value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" autocomplete="off"/>
								<div id="res-spider">
									<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
										<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
										<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
									</div>
								</div>
								<input type="hidden" name="hdn_rfc" id="hdn_rfc" value=""/>
							</td>
							<td>
								<div align="right">*Puesto</div>
							</td>
							<td>
								<select name="cmb_puesto" id="cmb_puesto" class="combo_box" onchange="agregarNvoPuesto(this);">
									<option selected="selected" value="">Puesto</option>
									<option value="LANZADOR">LANZADOR</option>
									<option value="AYUDANTE">AYUDANTE</option>
									<option value="OP. OLLA">OP. OLLA</option>
									<option value="OP. TORNADO">OP. TORNADO</option>
									<?php
									$conn = conecta("bd_gerencia");
									$rs = mysql_query( "SELECT DISTINCT  `puesto` 
														FROM  `integrantes_cuadrilla` 
														WHERE puesto !=  'LANZADOR'
														AND puesto !=  'AYUDANTE'
														AND puesto !=  'OP. OLLA'
														AND puesto !=  'OP. TORNADO'");
									if($rs){
										while($datos = mysql_fetch_array($rs)){
											echo "<option value='$datos[puesto]'>$datos[puesto]</option>";
										}
									}
									?>
									<option value="NUEVO">NUEVO PUESTO</option>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="5">
								<strong>* Datos marcados con asterisco son <u>obligatorios</u></strong>
							</td>
						</tr>
						<tr>
							<td colspan="6">
								<div align="center">
									<input type="hidden" name="hdn_validar" value="si"/>
									<?php
									if(isset($_SESSION["personalCuadrilla"]) && count($_SESSION["personalCuadrilla"])>=4){
									?>
										<input name="sbt_finalizar" type="submit" class="botones" value="Finalizar" title="Finalizar y Guardar la Cuadrilla" 
										onmouseover="window.status='';return true" onclick="hdn_validar.value='no';"/>
										&nbsp;&nbsp;&nbsp;
									<?php 
									}
									?>
									<input name="sbt_agregar" type="submit" class="botones" value="Guardar" title="Continuar a Guardar el Personal de la Cuadrilla" 
									onmouseover="window.status='';return true"/>
									&nbsp;&nbsp;&nbsp;
									<input type="reset" name="btn_borrar" class="botones" value="Limpiar" title="Reestablecer el Formulario"/>
									&nbsp;&nbsp;&nbsp;
									<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar y Regresar a Agregar Cuadrillas" 
									onMouseOver="window.status='';return true" onclick="location.href='frm_agregarCuadrilla.php?cancela';" />
								</div>
							</td>
						</tr>
					</table>
				</form>
			</fieldset>
			<?php
		}
		else{
			registrarCuadrilla();
		}
		?>
	</body>
	<?php 
	}
	?>
</html>
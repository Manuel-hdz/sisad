<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

<html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que hace la consulta de las requisiciones publicadas por cada departamento
		include_once ("op_cotizacionProveedor.php");?>


		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
			<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
			<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
			<script type="text/javascript" src="../../includes/maxLength.js" ></script>
			
			<script type="text/javascript" src="includes/ajax/busq_spider_material.js"></script>
			<script type="text/javascript" src="includes/ajax/busq_spider_proveedor.js"></script>
			<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	
			<style type="text/css">
				<!--
				#titulo-pagina {position:absolute;left:25px;top:146px;width:293px;height:22px;z-index:11;}
				#botones{position:absolute;left:350px;top:683px;width:150px;height:37px;z-index:15;}
				#botones2{position:absolute;left:500px;top:683px;width:150px;height:37px;z-index:13;}
				#datos-partida { position:absolute; left:25px; top:190px; width:920px; height:50px; z-index:12; }
				#datos-cotizacion { position:absolute; left:25px; top:265px; width:920px; height:90px; z-index:12; }
				#tabla-proveedores { position:absolute; left:25px; top:380px; width:920px; height:280px; z-index:1; }
				#res-spider {position:fixed;left:110px;z-index:10;}
				#res-spider2 {position:fixed;left:110px;z-index:10;}
				-->
			</style>
			
			<script>
				function validarAsignar(){
					document.getElementById('costo_mat').value=' ';
					document.getElementById('txt_moneda').value='PESOS';
					document.getElementById('txt_proveedor').value=' ';
				}
			</script>
		</head>
		<body>
			<?php
			if(isset($_POST["btn_agregar"]) && $_POST["btn_agregar"]){
				agregarCotizacion();
			}
			?>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-pagina">Cotizacion Proveedores</div>
			<form action="frm_consultarRequisiciones.php?depto=<?php echo $_POST['depto']; ?>&bus=<?php echo $_POST['bus']; ?>" method="post" name="frm_material" id="frm_material">
				<fieldset id="datos-partida" class="borde_seccion">
					<legend class="titulo_etiqueta">Datos Material</legend>
					<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
						<tr>
							<td align="right">Requisici&oacute;n</td>
							<td align="left">
								<input type="text" name="rdb_req" value="<?php echo $_POST["rdb_req"]; ?>" readonly="true" size="10" class="caja_de_texto"/>
							</td>
							<td align="right">Material Requisici&oacute;n</td>
							<td align="left">
								<?php
								$mat = consultarDatoMaterial($_POST["rdb_req"],$_GET["partida"],"descripcion");
								$clave = consultarDatoMaterial($_POST["rdb_req"],$_GET["partida"],"materiales_id_material");
								?>
								<input type="text" name="nombre_mat" id="nombre_mat" value="<?php echo $mat; ?>" readonly="true" size="70" class="caja_de_texto"/>
							</td>
						</tr>
					</table>
				</fieldset>
				<div id="botones" align="center">
					<input type='hidden' name='txt_fechaIni' value="<?php echo $_POST["txt_fechaIni"]; ?>"/>
					<input type='hidden' name='txt_fechaFin' value="<?php echo $_POST["txt_fechaFin"]; ?>"/>
					<input type="hidden" name="cmb_estadoBuscar" value="<?php echo $_POST["cmb_estadoBuscar"];?>"/>
					<input name="btn_revisar" id="btn_revisar" type="submit" value="Regresar" class="botones" title="Regresar a Detalles Requisicion" onMouseOver="window.status='';return true"/>
				</div>
			</form>
			<form action="frm_cotizacionProveedores.php?partida=<?php echo $_GET['partida']; ?>" method="post" name="frm_cotizacion" id="frm_cotizacion">
				<div id="botones2" align="center">
					<?php
					if(isset($_POST['txt_material'])){
					?>
						<input name="btn_asignar" id="btn_asignar" type="submit" value="Asignar Precio" class="botones" disabled=true
						title="Asigna el precio a la partida seleccionada" onclick="validarAsignar();"/>
					<?php
					}
					?>
				</div>
				<fieldset id="datos-cotizacion" class="borde_seccion">
					<legend class="titulo_etiqueta">Agregar Cotizaci&oacute;n</legend>
					<table width="100%" class="tabla_frm" cellpadding="5" cellspacing="5">
						
						<input type="hidden" name="nombre_mat" id="nombre_mat" value="<?php echo $mat; ?>" />
						<input type="hidden" name="partida" id="partida" value="<?php echo $_GET["partida"]; ?>"/>
						<input type='hidden' name='txt_fechaIni' value="<?php echo $_POST["txt_fechaIni"]; ?>"/>
						<input type='hidden' name='txt_fechaFin' value="<?php echo $_POST["txt_fechaFin"]; ?>"/>
						<input type='hidden' name='bus' value="<?php echo $_POST["bus"]; ?>"/>
						<input type='hidden' name='depto' value="<?php echo $_POST["depto"]; ?>"/>
						<input type="hidden" name="cmb_estadoBuscar" value="<?php echo $_POST["cmb_estadoBuscar"];?>"/>
						<input type="hidden" name="rdb_req" value="<?php echo $_POST["rdb_req"]; ?>"/>
						
						<tr>
							<td align="right">Material</td>
							<td align="left">
								<?php
								if( $clave != "N/A" || ( isset($_POST['txt_material']) && $_POST['txt_material'] != "" ) ){
									
									if(isset($_POST['txt_material'])){
										$clave = $_POST['txt_clave'];
										$mat = $_POST['txt_material'];
									}
								?>
									<input type="hidden" name="txt_clave" id="txt_clave" class="caja_de_texto" value="<?php echo $clave; ?>" readonly="true"/>
									<input type="text" name="txt_material" id="txt_material" class="caja_de_texto" size="70" onkeyup="lookup_material(this,'1');" 
									maxlength="60" autocomplete="off" required="required" value="<?php echo $mat; ?>" readonly="true"/>
								<?php
								} else {
								?>
									<input type="hidden" name="txt_clave" id="txt_clave" class="caja_de_texto" value="N/A"/>
									<input type="text" name="txt_material" id="txt_material" class="caja_de_texto" size="70" onkeyup="lookup_material(this,'1');" 
									maxlength="60" autocomplete="off" required="required" value="MATERIAL NUEVO"/>
								<?php
								}
								?>
								<div id="res-spider">
									<div align="left" class="suggestionsBox" id="suggestions1" style="display: none; width:380px;">
										<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
										<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
									</div>
								</div>
							</td>
							<td align="right">Costo Unitario</td>
							<td align="left">
								<input type="text" name="costo_mat" id="costo_mat" value="" size="12" class="caja_de_num" autocomplete="off" 
								required="required" onChange="formatCurrency(this.value.replace(/,/g,''),this.name);" maxlength=10/>
							</td>
							<td align="right">Moneda</td>
							<td align="left">
								<select name="txt_moneda" id="txt_moneda" required="required">
									<option value="">MONEDA</option>
									<option value="PESOS">PESOS</option>
									<option value="DOLARES">DOLARES</option>
									<option value="EUROS">EUROS</option>
								</select>
							</td>
						</tr>
						<tr>
							<td align="right">Proveedor</td>
							<td align="left">
								<input type="hidden" name="txt_rfc_prov" id="txt_rfc_prov" class="caja_de_texto" />
								<input type="text" name="txt_proveedor" id="txt_proveedor" class="caja_de_texto" size="70" onkeyup="lookup_proveedor(this,'2');" 
								value="" maxlength="60" autocomplete="off" required="required" />
								<div id="res-spider2">
									<div align="left" class="suggestionsBox" id="suggestions2" style="display: none; width:380px;">
										<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
										<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
									</div>
								</div>
							</td>
							<td colspan="4" align="center">
								<?php
								if( !isset($_POST['txt_material']) ){
								?>
									<input name="btn_seleccionar" id="btn_seleccionar" type="button" value="Seleccionar Material" class="botones" 
									title="Seleccionar Material" onclick="submit()" style="width:200px"/>
								<?php
								} else {
								?>
									<input name="btn_agregar" id="btn_agregar" type="submit" value="Agregar Cotizacion" class="botones" 
									title="Agregar Cotizacion" onMouseOver="window.status='';return true" style="width:200px"/>
								<?php
								}
								?>
							</td>
						</tr>
					</table>
				</fieldset>
				<?php
				if( isset($_POST['txt_material']) ){
				?>
					<fieldset id="tabla-proveedores" class="borde_seccion">
						<legend class="titulo_etiqueta">Proveedores</legend>
						<?php
							$hay_datos = mostrarProveedores();
							if($hay_datos){
								?>
								<script>
									document.getElementById("btn_asignar").disabled=false;
								</script>
								<?php
							}
						?>
					</fieldset>
				<?php
				}
				if(isset($_POST['btn_asignar'])){
					asignarPrecio();
				}
				?>
			</form>
		</body>
		<?php 
	}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
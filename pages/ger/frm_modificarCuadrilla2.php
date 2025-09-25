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
		include ("op_modificarCuadrilla.php");?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
			<script type="text/javascript" src="includes/ajax/calcularID.js" ></script>
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			
			<style type="text/css">
				<!--
				#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
				#tabla-modificarCuadrilla {position:absolute;left:30px;top:190px;width:742px;height:200px;z-index:12;}
				-->
			</style>
		</head>
		<body>
			<?php
			if(isset($_POST["sbt_modificarCuadrilla"])){
				modificarCuadrilla();
			}
			
			if(isset($_POST["rdb_idCuadrilla"]))
				$idCuadrilla=$_POST["rdb_idCuadrilla"];
			
			$id_cc = obtenerDato("bd_gerencia", "cuadrillas", "id_control_costos", "id_cuadrilla", $idCuadrilla);
			$ubicacion = obtenerDato("bd_recursos", "control_costos", "descripcion", "id_control_costos", $id_cc);
			$comentario = obtenerDato("bd_gerencia", "cuadrillas", "comentarios", "id_cuadrilla", $idCuadrilla);
			$aplicacion = obtenerDato("bd_gerencia", "cuadrillas", "aplicacion", "id_cuadrilla", $idCuadrilla);
			
			$partesApp = split(", ",$aplicacion);
			$viaSeca = 0; $viaHumeda = 0;
			foreach($partesApp as $ind => $valor){
				switch($valor){
					case "ZARPEO VIA SECA": $viaSeca = 1; break;
					case "ZARPEO VIA HUMEDA": $viaHumeda = 1; break;
				}
			}
			
			?>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-registrar">Modificar Cuadrilla</div>
			<fieldset class="borde_seccion" id="tabla-modificarCuadrilla" name="tabla-modificarCuadrilla">
				<legend class="titulo_etiqueta">Modifique la Informaci&oacute;n de la Cuadrilla</legend>
				<br>
				<form name="frm_modificarCuadrilla" method="post" action="frm_modificarCuadrilla2.php" onsubmit="return valFormCuadrilla(this)">
					<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
						<tr>
							<td width="128">
								<div align="right">ID Cuadrilla </div>
							</td>
							<td width="197">
								<input name="txt_IDCuadrilla" id="txt_IDCuadrilla" type="text" class="caja_de_texto" size="15" readonly="readonly" 
								value="<?php echo $idCuadrilla;?>"/>
							</td>
							<td width="145">
								<div align="right">Ubicaci&oacute;n</div>
							</td>
							<td width="204">
								<input type="text" name="txt_ubicacion" id="txt_ubicacion" class="caja_de_texto" readonly="readonly" value="<?php echo $ubicacion;?>"/>
							</td>
						</tr>
						<tr>
							<td width="128">
								<div align="right">Comentarios</div>
							</td>
							<td>
								<textarea name="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" cols="30" rows="2" class="caja_de_texto" 
								id="txa_comentarios" onkeypress="return permite(event,'num_car',0);"><?php echo $comentario;?></textarea>
							</td>
							<td align="right">
								*Aplicaci&oacute;n
							</td>
							<td>
								<input type="checkbox" name="ckb_zarpeoViaSeca" id="ckb_zarpeoViaSeca" 
								value="ZARPEO VIA SECA" <?php if($viaSeca==1) {?> checked="checked" <?php }?> />
								Zarpeo V&iacute;a Seca
								<br />
								<input type="checkbox" name="ckb_zarpeoViaHumeda" id="ckb_zarpeoViaHumeda" 
								value="ZARPEO VIA HUMEDA" <?php if($viaHumeda==1) {?> checked="checked" <?php }?> />
								Zarpeo V&iacute;a H&uacute;meda
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
									<?php 
									if(isset($_POST["sbt_consultarUbicacion"])){
										$buscar=$_POST["cmb_ubicacion"];
										echo "<input type='hidden' name='sbt_consultarUbicacion'/>";
										echo "<input type='hidden' name='cmb_ubicacion' value='$buscar'/>";
									}if(isset($_POST["sbt_consultarEmpl"])){
										$buscar=$_POST["hdn_rfc"];
										$buscar2=$_POST["hdn_cuad"];
										echo "<input type='hidden' name='sbt_consultarEmpl'/>";
										echo "<input type='hidden' name='hdn_rfc' value='$buscar'/>";
										echo "<input type='hidden' name='hdn_cuad' value='$buscar2'/>";
									}
									echo "<input type='hidden' name='rdb_idCuadrilla' value='$idCuadrilla'/>";
									?>
									<input name="btn_modificarPersonal" type="button" class="botones_largos" value="Modificar Personal" 
									title="Modificar el Personal de la Cuadrilla" onmouseover="window.status='';return true" 
									onclick="window.open('verPersonalCuadrilla.php?idCuadrilla=<?php echo $idCuadrilla; ?>', 
									'_blank','top=100, left=100, width=900, height=550, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no')" />
									&nbsp;&nbsp;&nbsp;
									<input name="sbt_modificarCuadrilla" type="submit" class="botones" value="Modificar" title="Modificar la Información de la Cuadrilla" 
									id="sbt_modificarCuadrilla" onmouseover="window.status='';return true"/>
									&nbsp;&nbsp;&nbsp;
									<input type="submit" name="sbt_regresar" id="sbt_regresar" value="Regresar" class="botones" 
									onmouseover="window.status='';return true;" title="Regresar a Seleccionar Otra Cuadrilla" 
									onclick="frm_modificarCuadrilla.onsubmit='';frm_modificarCuadrilla.action='frm_modificarCuadrilla.php'"/>
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
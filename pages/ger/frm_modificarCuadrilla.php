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
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
			<script type="text/javascript" src="includes/ajax/busq_spider_personal_cuadrilla.js"></script>
			
			<style type="text/css">
				<!--
				#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
				#tabla-consultarCuadrillaUbicacion {position:absolute;left:30px;top:190px;width:350px;height:110px;z-index:12;}
				#tabla-consultarCuadrillaEmpl {position:absolute;left:430px;top:190px;width:500px;height:110px;z-index:13;}
				#resultados {position:absolute;left:30px;top:190px;width:940px;height:440px;z-index:15; overflow:scroll; }
				#botones {position:absolute;left:30px;top:680px;width:900px;height:30px;z-index:16;}
				#res-spider {position:absolute;z-index:20;}
				-->
			</style>
		</head>
		<body>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-registrar">Modificar Cuadrilla</div>
			<?php
			if(!isset($_POST["sbt_consultarUbicacion"]) && !isset($_POST["sbt_consultarEmpl"])){
			?>
				<fieldset class="borde_seccion" id="tabla-consultarCuadrillaUbicacion" name="tabla-consultarCuadrillaArea">
					<legend class="titulo_etiqueta">Buscar Cuadrilla a Modificar por Ubicaci&oacute;n</legend>	
					<br>
					<form name="frm_consultarCuadrillaUbicacion" method="post" action="frm_modificarCuadrilla.php">
						<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
							<tr>
								<td>
									<div align="right">*Ubicaci&oacute;n</div>
								</td>
								<td>
									<select name="cmb_ubicacion" id="cmb_ubicacion" size="1" class="combo_box" required="required">
										<option value="">Ubicaci&oacute;n</option>
										<?php
										$cmb_ubicacion="";				
										$conn = conecta("bd_gerencia");
										$result = mysql_query ("SELECT T2 . * 
																FROM cuadrillas AS T1
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
							</tr>
							<tr>
								<td colspan="2" align="center">
									<input type="submit" name="sbt_consultarUbicacion" id="sbt_consultarUbicacion" value="Consultar" class="botones" 
									onmouseover="window.status='';return true;"/>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Cuadrillas" 
									onMouseOver="window.status='';return true" onclick="location.href='menu_cuadrillas.php';" />
								</td>
							</tr>
						</table>
					</form>
				</fieldset>
				
				<fieldset class="borde_seccion" id="tabla-consultarCuadrillaEmpl" name="tabla-consultarCuadrillaEmpl">
					<legend class="titulo_etiqueta">Buscar Cuadrilla a Modificar por Empleado</legend>	
					<br>
					<form name="frm_consultarCuadrillaEmpl" method="post" action="frm_modificarCuadrilla.php">
						<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
							<tr>
								<td>
									<div align="right">*Nombre</div>
								</td>
								<td>
									<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados','1');" 
									value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" autocomplete="off" required="required"/>
									<div id="res-spider">
										<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
											<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
											<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
										</div>
									</div>
									<input type="hidden" name="hdn_rfc" id="hdn_rfc" value=""/>
									<input type="hidden" name="hdn_cuad" id="hdn_cuad" value=""/>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center">
									<input type="submit" name="sbt_consultarEmpl" id="sbt_consultarEmpl" value="Consultar" class="botones" 
									onmouseover="window.status='';return true;"/>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Cuadrillas" 
									onMouseOver="window.status='';return true" onclick="location.href='menu_cuadrillas.php';" />
								</td>
							</tr>
						</table>
					</form>
				</fieldset>
			<?php
			} else {
			?>
				<form action="frm_modificarCuadrilla2.php" name="frm_seleccionarCuadrilla" method="post" onSubmit="return valFormSeleccionarCuadrillaMod(this);">
					<div id="resultados" class="borde_seccion">
						<?php
						if(isset($_POST["sbt_consultarUbicacion"])){
							$buscar=$_POST["cmb_ubicacion"];
							$res=mostrarCuadrillas(1,$buscar,"");
							echo "<input type='hidden' name='sbt_consultarUbicacion'/>";
							echo "<input type='hidden' name='cmb_ubicacion' value='$buscar'/>";
						}if(isset($_POST["sbt_consultarEmpl"])){
							$buscar=$_POST["hdn_rfc"];
							$buscar2=$_POST["hdn_cuad"];
							$res=mostrarCuadrillas(2,$buscar,$buscar2);
							echo "<input type='hidden' name='sbt_consultarEmpl'/>";
							echo "<input type='hidden' name='hdn_rfc' value='$buscar'/>";
							echo "<input type='hidden' name='hdn_cuad' value='$buscar2'/>";
						}
						?>
					</div>
					<div id="botones" align="center">
						<?php
						if ($res==1){
							?>
							<input type="hidden" name="hdn_accion" id="hdn_accion" value=""/>
							<!--
							<input type="submit" name="sbt_eliminar" id="sbt_eliminar" value="Eliminar" class="botones" onmouseover="window.status='';return true;" 
							onclick="hdn_accion.value='Eliminar';document.frm_seleccionarCuadrilla.action='frm_modificarCuadrilla.php';"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							-->
							<input type="submit" name="sbt_modificar" id="sbt_modificar" value="Modificar" class="botones" onmouseover="window.status='';return true;"
							onclick="hdn_accion.value='Modificar';"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<?php
						}
						?>
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Cuadrillas" 
						onMouseOver="window.status='';return true" onclick="location.href='frm_modificarCuadrilla.php';" />
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
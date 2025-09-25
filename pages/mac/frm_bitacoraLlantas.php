<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">


<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento Concreto
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para Generar el Reporte de Mnatenimientos Correctivos de Acuerdo a los Parametros Seleccionados
		include ("op_gestionLlantas.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarCatalogoLlantas.js" ></script>

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:264px; height:24px; z-index:11; }
		#bitacoraAceites { position:absolute; left:30px; top:190px; width:655px; height:880px; z-index:12; }
		#calendario { position:absolute; left:237px; top:232px; width:30px; height:26px; z-index:13; }
		#equipos { position:absolute; left:30px; top:190px; width:921px; height:450px; z-index:22; overflow: scroll; z-index:14;}
		#botones{position:absolute;left:30px;top:675px;width:950px;height:37px;z-index:15;}
		-->
    </style>	
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Bit&aacute;cora de Cambio de Llantas </div>
	
	<?php if(!isset($_POST["sbt_continuar"]) && !isset($_POST["sbt_guardar"])){?>
	
	<script type="text/javascript" language="javascript">
		setTimeout("document.getElementById('cmb_turno').focus();",500);
	</script>
	
	<fieldset class="borde_seccion" id="bitacoraAceites" name="bitacoraAceites">	
	<legend class="titulo_etiqueta">Registro de Uso de Llantas </legend>
	<br />
	<form name="frm_bitacoraLlantas" action="frm_bitacoraLlantas.php" method="post" onsubmit="return valFormBitacoraLlantas(this);">
		<table width="100%" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
				<td><div align="right">Fecha </div></td>
				<td width="115">
					<input name="txt_fecha" id="txt_fecha" type="text" value=<?php if(isset($_POST["txt_fecha"])) echo $_POST["txt_fecha"]; else echo date("d/m/Y");?> size="10" maxlength="15" readonly="true"/>
			  </td>
			  <td width="93">&nbsp;</td>
			  <td width="136"><div align="right">*Turno</div></td>
			  <td colspan="2"><select name="cmb_turno" id="cmb_turno" class="combo_box" title="Seleccionar el Turno del Registro">
                <option value="">Turno</option>
                <option value="PRIMERA" <?php if(isset($_POST["cmb_turno"])) if($_POST["cmb_turno"]=="PRIMERA") echo "selected='selected'"; ?>>PRIMERA</option>
                <option value="SEGUNDA" <?php if(isset($_POST["cmb_turno"])) if($_POST["cmb_turno"]=="SEGUNDA") echo "selected='selected'"; ?>>SEGUNDA</option>
                <option value="TERCERA" <?php if(isset($_POST["cmb_turno"])) if($_POST["cmb_turno"]=="TERCERA") echo "selected='selected'"; ?>>TERCERA</option>
              </select></td>
			</tr>
			<tr>
				<td><div align="right">*Equipo</div></td>
				<td>
					<select name="cmb_equipo" id="cmb_equipo" class="combo_box">
						<option value="">Equipo</option>
						<?php 
						$area="";
						//Obtener los Sistemas Registrados en la BD
						$conn = conecta("bd_mantenimiento");
						$rs_equipos = mysql_query("SELECT id_equipo FROM equipos WHERE area!='$area' AND estado='ACTIVO' ORDER BY familia,id_equipo");
						if($equipos=mysql_fetch_array($rs_equipos)){
							do{
								if(isset($_POST["cmb_equipo"])){
									if($_POST["cmb_equipo"]==$equipos["id_equipo"]){
										echo "<option value='$equipos[id_equipo]' selected='selected'>$equipos[id_equipo]</option>";
									}
									else
										echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
								}
								else
									echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
							}while($equipos=mysql_fetch_array($rs_equipos));
						}
						//Cerrar la conexion con la BD
						mysql_close($conn);		
						?>
					</select>
				</td>
				<td><div align="right" title="Od&oacute;metro">*Od&oacute;metro</div></td>
				<td>
					<input type="text" class="caja_de_num" id="txt_odometro" name="txt_odometro" size="10" maxlength="10" value="<?php if(isset($_POST["txt_odometro"])) echo $_POST["txt_odometro"]; else echo "0.00";?>" 
					onkeypress="return permite(event,'num_car',2);" onchange="formatCurrency(value,'txt_odometro');"/>
				</td>
				<td><div align="right" title="Hor&oacute;metro">*Hor&oacute;metro</div></td>
				<td>
					<input type="text" class="caja_de_num" id="txt_horometro" name="txt_horometro" size="10" maxlength="10" value="<?php if(isset($_POST["txt_horometro"])) echo $_POST["txt_horometro"]; else echo "0.00";?>" 
					onkeypress="return permite(event,'num_car',2);" onchange="formatCurrency(value,'txt_horometro');"/>
				</td>
		</tr>
		<tr>
			<td width="75"><div align="right">*Codigo Trabajador</div></td>
			<td>
				<input type="text" class="caja_de_num" id="txt_codigo" name="txt_codigo" size="10" maxlength="10" onchange="extraerInfoEmpleado(this.value);"
				value="<?php if(isset($_POST["txt_codigo"])) echo $_POST["txt_codigo"]; ?>" onkeypress="return permite(event,'num',2);"/>
			</td>
		    <td width="75"><div align="right">*Trabajador</div></td>
			<td colspan="4">
				<!--<select name="cmb_responsable" id="cmb_responsable" class="combo_box">
				  <option value="">Responsable</option>
				  <?php 
					//Obtener los Sistemas Registrados en la BD
					/*$conn = conecta("bd_recursos");
					$rs = mysql_query("SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombreEmpleado,puesto FROM empleados WHERE id_cuentas='CUEN002' ORDER BY nombreEmpleado");
					if($datos=mysql_fetch_array($rs)){
						do{
							echo "<option value='$datos[nombreEmpleado]' title='$datos[puesto]'>$datos[nombreEmpleado]</option>";
						}while($datos=mysql_fetch_array($rs));
					}
					//Cerrar la conexion con la BD
					mysql_close($conn);*/		
					?>
				</select>-->
				<input type="text" class="caja_de_texto" id="txt_empleado" name="txt_empleado" size="53" maxlength="53" value="<?php if(isset($_POST["txt_empleado"])) echo $_POST["txt_empleado"]; ?>" readonly="readonly"/>
			</td>
			<input type="hidden" id="hdn_rfc" name="hdn_rfc" value="<?php if(isset($_POST["hdn_rfc"])) echo $_POST["hdn_rfc"]; ?>"/>
		</tr>
		<tr><td colspan="10"><hr/></td></tr>
		<tr>
			<td colspan="10">
			<label class="titulo_etiqueta">Llantas Colocadas en el Equipo</label>
			</td>
		</tr>
		<!--<tr>
			<td rowspan="2"><div align="right">*Llanta</div></td>
			<td rowspan="2">
				<select name="cmb_llantaColocada" id="cmb_llantaColocada" class="combo_box" onchange="extraerCantidadLlantas(this.value);">
					<option value="" selected="selected">Llanta</option>
					<?php 
					/*$conn = conecta("bd_mantenimiento");		
					$stm_sql = "SELECT id_llanta,descripcion FROM llantas ORDER BY descripcion";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){
						$llantaExist=1;
						do{
							echo "<option value='$datos[id_llanta]'>$datos[descripcion]</option>";
						}while($datos = mysql_fetch_array($rs));
					}
					else
						$llantaExist=0;
					//Cerrar la conexion con la BD		
					mysql_close($conn);	*/
					?>
				</select>
			</td>
			<td><div align="right">*Nuevas</div></td>
			<td>
				<input type="text" class="caja_de_num" id="txt_nuevas" name="txt_nuevas" size="10" maxlength="10" value="0" onkeypress="return permite(event,'num',3);" 
				onchange="verificarExistSalida(this,hdn_cantNuevas.value,'Nuevas')"/>
				<input type="hidden" name="hdn_cantNuevas" id="hdn_cantNuevas" value=""/>
			</td>
		  <td width="91"><div align="right">*Reuso</div></td>
			<td width="50">
				<input type="text" class="caja_de_num" id="txt_reuso" name="txt_reuso" size="10" maxlength="10" value="0" onkeypress="return permite(event,'num',3);"
				onchange="verificarExistSalida(this,hdn_cantReuso.value,'de Reuso')" tabindex="8"/>
				<input type="hidden" name="hdn_cantReuso" id="hdn_cantReuso" value=""/>
		  </td>
		</tr>-->
		<?php
		for($i=1; $i<=10; $i++){
			if($i==1){
				echo "<tr>";
			}
		?>
			<td><div align="right">Llanta&nbsp;<?php echo $i; ?></div></td>
			<td>
				<input name="txt_llanta<?php echo $i; ?>" id="txt_llanta<?php echo $i; ?>" type="text" class="caja_de_texto" size="10" maxlength="10" style="text-transform:uppercase"
				value="<?php if(isset($_POST["txt_llanta".$i])) echo $_POST["txt_llanta".$i]; ?>" onchange="extraerInfoLlanta(this.value, this.id, 3)"/>
			</td>
		<?php
			if($i%5==0 && $i!=10){
				echo "</tr><tr>";
			}
		}
		?>
		</tr>
		<!--<tr>
			<td><div align="right">*Costo $</div></td>
			<td><input type="text" class="caja_de_num" id="txt_costoUniNvas" name="txt_costoUniNvas" size="10" maxlength="10" value="0.00" onkeypress="return permite(event,'num',2);" 
				onchange="formatCurrency(value,'txt_costoUniNvas');" tabindex="9"/>
			</td>
			<td><div align="right">*Costo $ </div></td>
			<td><input type="text" class="caja_de_num" id="txt_costoUniReuso" name="txt_costoUniReuso" size="10" maxlength="10" value="0.00" onkeypress="return permite(event,'num',2);"
				onchange="formatCurrency(value,'txt_costoUniReuso');" tabindex="10"/>
			</td>
		</tr>-->
		<tr><td colspan="10"><hr /></td></tr>
		<tr>
			<td colspan="10">
			<label class="titulo_etiqueta">Llantas Retiradas del Equipo</label>
			</td>
		</tr>
		<tr>
			<td><div align="right">Existente</div></td>
			<td>
				<input type="text" class="caja_de_num" id="txt_existente" name="txt_existente" size="10" maxlength="10" 
				value="<?php if(isset($_POST["txt_existente"])) echo $_POST["txt_existente"]; ?>" onchange="submit();" onkeypress="return permite(event,'num',2);"/>
			</td>
		
			<td><div align="right">Sin Codigo</div></td>
			<td>
				<input type="text" class="caja_de_num" id="txt_sinCodigo" name="txt_sinCodigo" size="10" maxlength="10" 
				value="<?php if(isset($_POST["txt_sinCodigo"])) echo $_POST["txt_sinCodigo"]; ?>" onchange="submit();" onkeypress="return permite(event,'num',2);"/>
			</td>
		
			<td><div align="right">Desechadas</div></td>
			<td>
				<input type="text" class="caja_de_num" id="txt_desechadas" name="txt_desechadas" size="10" maxlength="10" 
				value="<?php if(isset($_POST["txt_desechadas"])) echo $_POST["txt_desechadas"]; ?>" onchange="submit();" onkeypress="return permite(event,'num',2);"/>
			</td>
		</tr>
		<?php
		if(isset($_POST["txt_existente"]) && ($_POST["txt_existente"] > 0)){
		?>
			<tr><td colspan="10"><hr /></td></tr>
			<tr>
				<td colspan="10">
				<label class="titulo_etiqueta">Llantas Retiradas Existentes del Equipo</label>
				</td>
			</tr>
		<?php
			for($i=1; $i<=$_POST["txt_existente"]; $i++){
				if($i==1){
					echo "<tr>";
				}
		?>
				<td><div align="right">Llanta <?php echo $i; ?></div></td>
				<td>
					<input name="txt_existente<?php echo $i; ?>" id="txt_existente<?php echo $i; ?>" type="text" class="caja_de_texto" size="10" maxlength="10" style="text-transform:uppercase"
					value="<?php if(isset($_POST["txt_existente".$i])) echo $_POST["txt_existente".$i]; ?>" onchange="extraerInfoLlanta(this.value, this.id, 2)"/>
				</td>
		<?php
				if($i%5==0 && $i!=10){
					echo "</tr><tr>";
				}
			}
		}
		?>
		</tr>
		<?php
		if(isset($_POST["txt_sinCodigo"]) && ($_POST["txt_sinCodigo"] > 0)){
		?>
			<tr><td colspan="10"><hr /></td></tr>
			<tr>
				<td colspan="10">
				<label class="titulo_etiqueta">Llantas Retiradas Sin Codigo del Equipo</label>
				</td>
			</tr>
		<?php
			for($i=1; $i<=$_POST["txt_sinCodigo"]; $i++){
				if($i==1){
					echo "<tr>";
				}
		?>
				<td><div align="right">Llanta <?php echo $i; ?></div></td>
				<td>
					<input name="txt_sinCodigo<?php echo $i; ?>" id="txt_sinCodigo<?php echo $i; ?>" type="text" class="caja_de_texto" size="10" maxlength="10" style="text-transform:uppercase"
					value="<?php if(isset($_POST["txt_sinCodigo".$i])) echo $_POST["txt_sinCodigo".$i]; ?>" onchange="extraerInfoLlanta(this.value, this.id, 1)"/>
				</td>
				<td><div align="right">*Tipo Llanta</div></td>
				<td>
					<select name="cmb_tipo<?php echo $i; ?>" id="cmb_tipo<?php echo $i; ?>" class="combo_box">
						<option value="" selected="selected">Tipo</option>
						<?php 
						$conn = conecta("bd_mantenimiento");		
						$stm_sql = "SELECT id_marca,descripcion FROM llantas ORDER BY descripcion";
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){
							$llantaExist=1;
							do{
								echo "<option value='$datos[id_marca]'>$datos[descripcion]</option>";
							}while($datos = mysql_fetch_array($rs));
						}
						else
							$llantaExist=0;
						//Cerrar la conexion con la BD		
						mysql_close($conn);	
						?>
					</select>
				</td>
		<?php
				if($i%2==0 && $i!=10){
					echo "</tr><tr>";
				}
			}
		}
		?>
		</tr>
		<?php
		if(isset($_POST["txt_desechadas"]) && ($_POST["txt_desechadas"] > 0)){
		?>
			<tr><td colspan="10"><hr /></td></tr>
			<tr>
				<td colspan="10">
				<label class="titulo_etiqueta">Llantas Desechadas del Equipo</label>
				</td>
			</tr>
		<?php
			for($i=1; $i<=$_POST["txt_desechadas"]; $i++){
				if($i==1){
					echo "<tr>";
				}
		?>
				<td><div align="right">Llanta <?php echo $i; ?></div></td>
				<td>
					<input name="txt_desechadas<?php echo $i; ?>" id="txt_desechadas<?php echo $i; ?>" type="text" class="caja_de_texto" size="10" maxlength="10" style="text-transform:uppercase"
					value="<?php if(isset($_POST["txt_desechadas".$i])) echo $_POST["txt_desechadas".$i]; ?>" onchange="extraerInfoLlanta(this.value, this.id, 2)"/>
				</td>
		<?php
				if($i%5==0 && $i!=10){
					echo "</tr><tr>";
				}
			}
		}
		?>
		</tr>
		<!--<tr>
			<td><div align="right">*Llanta</div></td>
			<td>
				<select name="cmb_llantaRetirada" id="cmb_llantaRetirada" class="combo_box" tabindex="11">
					<option value="" selected="selected">Llanta</option>
					<?php 
					/*$conn = conecta("bd_mantenimiento");		
					$stm_sql = "SELECT id_llanta,descripcion FROM llantas ORDER BY descripcion";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){
						$llantaExist=1;
						do{
							echo "<option value='$datos[id_llanta]'>$datos[descripcion]</option>";
						}while($datos = mysql_fetch_array($rs));
					}
					else
						$llantaExist=0;
					//Cerrar la conexion con la BD		
					mysql_close($conn);	*/
					?>
				</select>
			</td>
			<td><div align="right">*Reutilizables</div></td>
			<td>
				<input type="text" class="caja_de_num" id="txt_reusables" name="txt_reusables" size="10" maxlength="10" value="0" onkeypress="return permite(event,'num',3);" tabindex="12"/>
			</td>
			<td><div align="right">*Deshechables</div></td>
			<td>
				<input type="text" class="caja_de_num" id="txt_deshechables" name="txt_deshechables" size="10" maxlength="10" value="0" onkeypress="return permite(event,'num',3);" tabindex="13"/>
			</td>
		</tr>-->
		<tr><td colspan="10"><hr /></td></tr>
		<tr>
			<td colspan="10" align="center">
				<input name="sbt_guardar" type="submit" class="botones" value="Guardar" onmouseover="window.status='';return true"
				title="Guardar Registro en la Bit&aacute;cora de Llantas" tabindex="14"/> 
				&nbsp;
				<input name="rst_Limpiar" type="button" class="botones" value="Limpiar" title="Limpiar Formulario" tabindex="14" onclick="location.href='frm_bitacoraLlantas.php'"/>
				&nbsp;
				<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar al Men&uacute; de Llantas" onclick="location.href='menu_llantas.php'" tabindex="15"/>
			</td>
		</tr>
		</table>
	</form>
</fieldset>
			
		<div id="calendario">
			<input name="fechaRegistro" id="fechaRegistro" type="image" src="../../images/calendar.png" title="Seleccionar la Fecha de Registro de Cambio de Llantas"
			onclick="displayCalendar(document.frm_bitacoraLlantas.txt_fecha,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom"/>
		</div>
		
	<?php
	}
	if(isset($_POST["sbt_guardar"])){
		guardarRegistroLlantas();
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
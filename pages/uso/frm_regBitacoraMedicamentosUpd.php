<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo USO
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo guarda la informacion del registro de Medicamentos en la bitacora
		include ("op_bitacoraMedicamentos.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarCambioDato.js"></script>
	
    <style type="text/css">
		<!--
		#titulo-generar{ position:absolute; left:30px; top:146px; width:285px; height:19px; z-index:11;}
		#tabla-medicamento{ position:absolute; left:30px; top:190px; width:900px;height:410px; z-index:12;}
		#calendario { position:absolute; left:838px; top:233px; width:30px; height:26px; z-index:13; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Datos del Medicamento</div>
	<?php
	if(isset($_POST["sbt_continuar"])){
		if($_POST["hdn_accion"]=="ADD")
			guardarInfoMedicamento();
		else
			actualizarInfoMedicamento();
	}
	
	$id_Med="";
	$clasificacion="";
	$descripcion="";
	$presentacion="";
	$nombre="";
	$cantMed="";
	$totalMed="";
	$unidad="";
	$tipoPres="";
	$unidadDesp="";
	$cantPresentacion="";
	$medExtra="";
	$accion="ADD";
	if(isset($_GET["idMed"])){
		$codMed=$_GET["idMed"];
		$accion=$codMed;
		$id_Med=obtenerDato("bd_clinica","catalogo_medicamento","codigo_med","id_med",$codMed);
		$clasificacion=obtenerDato("bd_clinica","catalogo_medicamento","clasificacion_med","id_med",$codMed);
		$descripcion=obtenerDato("bd_clinica","catalogo_medicamento","descripcion_med","id_med",$codMed);
		$presentacion=obtenerDato("bd_clinica","catalogo_medicamento","presentacion","id_med",$codMed);
		$nombre=obtenerDato("bd_clinica","catalogo_medicamento","nombre_med","id_med",$codMed);
		$unidad=obtenerDato("bd_clinica","catalogo_medicamento","unidad_medida","id_med",$codMed);
		$tipoPres=obtenerDato("bd_clinica","catalogo_medicamento","tipo_presentacion","id_med",$codMed);
		$unidadDesp=obtenerDato("bd_clinica","catalogo_medicamento","unidad_despacho","id_med",$codMed);
		$cantMed=obtenerDato("bd_clinica","catalogo_medicamento","existencia_actual","id_med",$codMed);
		$cantPresentacion=obtenerDato("bd_clinica","catalogo_medicamento","cant_presentacion","id_med",$codMed);
		if($cantPresentacion<=$cantMed){
			$totalMed=round($cantMed/$cantPresentacion,0);
			$medExtra=$cantMed%$cantPresentacion;
			if($medExtra!=0)
				$medExtra="<label class='msje_correcto'>Hay Un(a) $unidad Abierto(a) con $medExtra <br>$tipoPres(S)</label>";
			else
				$medExtra="";
		}
		else{
			if($cantMed>0)
				$totalMed=1;
			else
				$totalMed=0;
		}
	}
	?>

	<fieldset id="tabla-medicamento" class="borde_seccion">
	<legend class="titulo_etiqueta">Datos del Medicamento</legend>
	<br>	
	<form onsubmit="return valFormActualizarMedicamento(this);" name="frm_registrarBitacoraMedicamentos" method="post" action="frm_regBitacoraMedicamentosUpd.php" >
	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
		<tr>
		  <td width="149"><div align="right">*C&oacute;digo Medicamento</div></td>
			<td width="293" colspan="3">
				<input type="text" name="txt_codigo" id="txt_codigo" class="caja_de_num" value="<?php echo $id_Med;?>" onkeypress="return permite(event,'num',9);" size="5" 
				maxlength="5" onblur="validarCambioClave('bd_clinica','catalogo_medicamento','codigo_med',this,'<?php echo $id_Med;?>','sbt_continuar','frm_registrarBitacoraMedicamentos')"/>
		  </td>
		</tr>
		<tr>
		  <td width="149"><div align="right">*Clasificaci&oacute;n</div></td>
			<td width="293">
				<?php 
					$conn = conecta("bd_clinica");		
					$stm_sql = "SELECT DISTINCT clasificacion_med FROM catalogo_medicamento ORDER BY clasificacion_med";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_clasificacion" class="combo_box" id="cmb_clasificacion">
							<?php
							if($datos["clasificacion_med"]=="")
								echo "<option value='' selected='selected'>Clasificaci&oacute;n</option>";
							else
								echo "<option value=''>Clasificaci&oacute;n</option>";
							do{
								if($datos["clasificacion_med"]==$clasificacion)
									echo "<option value='$datos[clasificacion_med]' selected='selected'>$datos[clasificacion_med]</option>";
								else
									echo "<option value='$datos[clasificacion_med]'>$datos[clasificacion_med]</option>";
							}while($datos = mysql_fetch_array($rs));?>
						</select>
						<?php
					}
					else{
						echo "<label class='msje_correcto'>No hay Medicamento Registrado</label>
							<input type='hidden' name='cmb_clasificacion' id='cmb_clasificacion'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);	
				?>
		  </td>
			<td width="208"><div align="right">
  		  <input type="checkbox" name="ckb_nuevaClasificacion" id="ckb_nuevaClasificacion" onclick="agregarNuevoDato(cmb_clasificacion,this,txt_nuevaClasificacion)">*Agregar Nueva Clasificaci&oacute;n</div></td>
			<td width="185">
				<input type="text" name="txt_nuevaClasificacion" id="txt_nuevaClasificacion" readonly="readonly" maxlength="30" size="20"/>
		  </td>
		</tr>
		<tr>
			<td valign="top"><div align="right">*Descripci&oacute;n</div></td>
			<td><textarea name="txa_descripcion" id="txa_descripcion" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
				rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $descripcion;?></textarea></td>
			<td valign="top"><div align="right">*Presentaci&oacute;n</div></td>
			<td><textarea name="txa_presentacion" id="txa_presentacion" maxlength="100" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
				rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $presentacion;?></textarea></td>
		</tr>
		<tr>
			<td><div align="right">*Nombre Medicamento</div></td>
			<td><input type="text" name="txt_nomMed" id="txt_nomMed" class="caja_de_texto" value="<?php echo $nombre;?>" size="50" maxlength="75"/></td>
			<td><div align="right">*Cantidad por Presentaci&oacute;n</div></td>
			<td>
				<input type="text" name="txt_cantPres" id="txt_cantPres" class="caja_de_num" value="<?php echo $cantPresentacion;?>" size="10" maxlength="5" 
				onchange="obtenerTotalMedicamento(this.value,txt_cantMed.value);" onkeypress="return permite(event,'num',3);"/>
			</td>
		</tr>
	  </table>
		
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
		<tr>
			<td>*Cantidad Medicamento</td>
			<td>
				<input type="text" name="txt_cantMed" id="txt_cantMed" class="caja_de_num" value="<?php echo $totalMed?>" onkeypress="return permite(event,'num',3);" size="5" maxlength="5"
				onchange="obtenerTotalMedicamento(txt_cantPres.value,this.value);"/>
			</td>
			<td>*Unidad de Medida</td>
			<td>
				<?php
					$conn = conecta("bd_clinica");		
					$stm_sql = "SELECT DISTINCT unidad_medida FROM catalogo_medicamento ORDER BY unidad_medida";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_unidadMed" class="combo_box" id="cmb_unidadMed">
							<?php
							if($datos["unidad_medida"]=="")
								echo "<option value='' selected='selected'>Unidad de Medida</option>";
							else
								echo "<option value=''>Unidad de Medida</option>";
							do{
								if($datos["unidad_medida"]==$unidad)
									echo "<option value='$datos[unidad_medida]' selected='selected'>$datos[unidad_medida]</option>";
								else
									echo "<option value='$datos[unidad_medida]'>$datos[unidad_medida]</option>";
							}while($datos = mysql_fetch_array($rs));?>
						</select>
						<?php
					}
					else{
						echo "<label class='msje_correcto'>No hay Medicamento Registrado</label>
							<input type='hidden' name='cmb_unidadMed' id='cmb_unidadMed'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);	
				?>
			</td>
			<td><div align="right">
		  		<input type="checkbox" name="ckb_nuevaUnidadMedida" id="ckb_nuevaUnidadMedida" onclick="agregarNuevoDato(cmb_unidadMed,this,txt_nuevaUnidadMedida)">*Agregar Nueva Unidad Medida
			</div></td>
			<td><input type="text" name="txt_nuevaUnidadMedida" id="txt_nuevaUnidadMedida" readonly="readonly" maxlength="20" size="20"/></td>
		</tr>
		<tr>
			<td>*Total Medicamento</td>
			<td>
				<input type="text" name="txt_cantMedTotal" id="txt_cantMedTotal" class="caja_de_num" value="<?php echo $cantMed;?>" onkeypress="return permite(event,'num',3);" size="5" maxlength="5" readonly="readonly"/>
			</td>
			<td>*Tipo Presentaci&oacute;n</td>
			<td>
				<?php
					$conn = conecta("bd_clinica");		
					$stm_sql = "SELECT DISTINCT tipo_presentacion FROM catalogo_medicamento ORDER BY tipo_presentacion";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_tipoPres" class="combo_box" id="cmb_tipoPres">
							<?php
							if($datos["tipo_presentacion"]=="")
								echo "<option value='' selected='selected'>Tipo Presentaci&oacute;n</option>";
							else
								echo "<option value=''>Tipo Presentaci&oacute;n</option>";
							do{
								if($datos["tipo_presentacion"]==$tipoPres)
									echo "<option value='$datos[tipo_presentacion]' selected='selected'>$datos[tipo_presentacion]</option>";
								else
									echo "<option value='$datos[tipo_presentacion]'>$datos[tipo_presentacion]</option>";
							}while($datos = mysql_fetch_array($rs));?>
						</select>
						<?php
					}
					else{
						echo "<label class='msje_correcto'>No hay Medicamento Registrado</label>
							<input type='hidden' name='cmb_tipoPres' id='cmb_tipoPres'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);	
				?>
			</td>
			<td><div align="right">
		  		<input type="checkbox" name="ckb_nuevaPresentacion" id="ckb_nuevaPresentacion" onclick="agregarNuevoDato(cmb_tipoPres,this,txt_nuevaPresentacion)">*Agregar Nueva Presentaci&oacute;n
			</div></td>
			<td><input type="text" name="txt_nuevaPresentacion" id="txt_nuevaPresentacion" readonly="readonly" maxlength="20" size="20"/></td>
		</tr>
		<tr>
			<td colspan="2"><?php echo $medExtra;?></td>
			<td>*Unidad despacho</td>
			<td>
				<?php
					$conn = conecta("bd_clinica");		
					$stm_sql = "SELECT DISTINCT unidad_despacho FROM catalogo_medicamento ORDER BY unidad_despacho";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_unidadDesp" class="combo_box" id="cmb_unidadDesp">
							<?php
							if($datos["unidad_despacho"]=="")
								echo "<option value='' selected='selected'>Unidad Despacho</option>";
							else
								echo "<option value=''>Unidad Despacho</option>";
							do{
								if($datos["unidad_despacho"]==$unidadDesp)
									echo "<option value='$datos[unidad_despacho]' selected='selected'>$datos[unidad_despacho]</option>";
								else
									echo "<option value='$datos[unidad_despacho]'>$datos[unidad_despacho]</option>";
							}while($datos = mysql_fetch_array($rs));?>
						</select>
						<?php
					}
					else{
						echo "<label class='msje_correcto'>No hay Medicamento Registrado</label>
							<input type='hidden' name='cmb_unidadDesp' id='cmb_unidadDesp'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);	
				?>
			</td>
			<td><div align="right">
		  		<input type="checkbox" name="ckb_nuevaUnidadDesp" id="ckb_nuevaUnidadDesp" onclick="agregarNuevoDato(cmb_unidadDesp,this,txt_nuevaUnidadDesp)">
		  		*Agregar Nueva Unidad Despacho
			</div></td>
			<td><input type="text" name="txt_nuevaUnidadDesp" id="txt_nuevaUnidadDesp" readonly="readonly" maxlength="20" size="20"/></td>
		</tr>
		<tr>
			<td colspan="6" align="center">
				<input type="hidden" name="hdn_accion" id="hdn_accion" value="<?php echo $accion?>"/>
				<input type="submit" name="sbt_continuar" id="sbt_continuar" class="botones" title="Continuar a Registrar el Medicamento" value="Continuar" onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" title="Limpiar los Datos" value="Limpiar"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_cancelar" id="btn_cancelar" class="botones" title="Cancelar y Volver a la Bit&aacute;cora de Medicamentos" value="Regresar" 
				onclick="location.href='frm_regBitacoraMedicamentos.php'"/>						
			</td>
		</tr>
	</table>
	</form>
</fieldset>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
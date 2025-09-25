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
	<script type="text/javascript" src="includes/ajax/cargarMedicamento.js"></script>
	
    <style type="text/css">
		<!--
		#titulo-generar{ position:absolute; left:30px; top:146px; width:285px; height:19px; z-index:11;}
		#tabla-medicamento{ position:absolute; left:30px; top:190px; width:600px;height:180px; z-index:12;}
		#tabla-medActual{position:absolute;left:31px;top:399px;width:950px;height:283px;z-index:12; overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Actualizar Cat&aacute;logo de Medicamentos</div>

	<?php 
	if(isset($_POST["sbt_guardar"])){
		actualizarMedicamentos();
	}
	?>
		<fieldset id="tabla-medicamento" class="borde_seccion">
		<legend class="titulo_etiqueta">Seleccionar la Clasificación y el Medicamento</legend>
		<br>	
		<form onsubmit="return valFormIncMedicamento(this);" name="frm_incrementarMedicamento" method="post" action="frm_regBitacoraMedicamentos.php" >
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td width="88"><div align="right">Clasificaci&oacute;n</div></td>
				<td width="477" colspan="5">
					<?php 
					$conn = conecta("bd_clinica");		
					$stm_sql = "SELECT DISTINCT clasificacion_med FROM catalogo_medicamento ORDER BY clasificacion_med";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_clasificacion" class="combo_box" id="cmb_clasificacion" 
						onchange="cargarComboConId(this.value,'bd_clinica','catalogo_medicamento','nombre_med','id_med','clasificacion_med','cmb_medicamento','Medicamento','');txt_existencia.value='';txt_surtido.value='';txt_total.value='';">
							<option value="" selected="selected">Clasificaci&oacute;n</option>
							<?php
							do{
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
			  	&nbsp;&nbsp;<input type="checkbox" name="ckb_nuevoMedicamento" id="ckb_nuevoMedicamento" onclick="location.href='frm_regBitacoraMedicamentosUpd.php'">Agregar Nuevo Medicamento
			  </td>
			</tr>
			<tr>
			  <td width="88"><div align="right">Medicamento</div></td>
				<td width="477" colspan="5">
					<select name="cmb_medicamento" class="combo_box" id="cmb_medicamento" onchange="obtenerMedicamento(this.value);">
						<option value="" selected="selected">Medicamento</option>
					</select>
			  </td>
			</tr>
			<tr>
				<td><div align="right">Existencia</div></td>
				<td><input type="text" name="txt_existencia" id="txt_existencia" class="caja_de_num" value="" readonly="readonly" size="10" maxlength="10" title="Existencia Unitaria"/></td>
				<td><div align="right">Surtido</div></td>
				<td><input type="text" name="txt_surtido" id="txt_surtido" class="caja_de_num" value="" onkeypress="return permite(event,'num',3);" size="10" 
					maxlength="10" onblur="if(txt_existencia.value!='' && this.value!=''){txt_total.value=parseInt(txt_existencia.value)+parseInt(this.value)}"
					title="Ingresar Surtido Unitario"/></td>
				<td><div align="right">Existencia Nueva</div></td>
				<td><input type="text" name="txt_total" id="txt_total" class="caja_de_num" value="" readonly="readonly" size="10" maxlength="10"/></td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input type="submit" name="sbt_guardar" id="sbt_guardar" class="botones" title="Guardar la Nueva Existencia de Medicamento" value="Guardar" onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" title="Limpiar los Datos" value="Limpiar"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Volver al Men&uacute; de bit&aacute;coras" value="Regresar" onclick="location.href='menu_bitacoras.php'"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" class="botones" value="Exportar a Excel" name="btn_exportar" id="btn_exportar" title="Exportar a Excel los Registros del Cat&aacute;logo de Medicamentos" 
					onclick="location.href='guardar_reporte.php?tipoRep=RepCatMedicamento'"/>
				</td>
			</tr>
		</table>
		</form>
</fieldset>
		
		<div class="borde_seccion2" id="tabla-medActual">
			<?php
			mostrarMedicamentos();
			?>
		</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
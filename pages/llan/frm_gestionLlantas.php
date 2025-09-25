<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_gestionLlantas.php");
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarCatalogoLlantas.js" ></script>

    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#catalogo-llantas {position:absolute;left:30px;top:190px;width:920px;height:200px;z-index:12;}
		#tabla-llantas {position:absolute;left:30px;top:415px;width:920px;height:260px;z-index:12; overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Actualizaci&oacute;n del Stock de Llantas</div>
	
	<?php
		//Verificar si se debe guardar un registro
		if(isset($_POST["sbt_guardar"])){
			guardarActualizacionLlanta();
		}
	?>
			
	<fieldset class="borde_seccion" id="catalogo-llantas" name="catalogo-llantas">
    <legend class="titulo_etiqueta">Ingresar Datos de la Llanta</legend>	
    <br>
	<form name="frm_gestionLlantas" method="post" action="frm_gestionLlantas.php" onsubmit="return valFormGestionLlantas(this);">
		<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<tr>
		  <td width="9%"><div align="right">*ID Llanta</div></td>
			<td width="25%">
				<select name="cmb_llanta" id="cmb_llanta" class="combo_box" onchange="obtenerLlanta(this)" tabindex="1">
					<option value="" selected="selected">Llanta</option>
					<?php 
					$conn = conecta("bd_mantenimiento");		
					$stm_sql = "SELECT id_llanta FROM llantas ORDER BY id_llanta";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){
						$llantaExist=1;
						do{
							echo "<option value='$datos[id_llanta]'>$datos[id_llanta]</option>";
						}while($datos = mysql_fetch_array($rs));
					}
					else
						$llantaExist=0;
					//Cerrar la conexion con la BD		
					mysql_close($conn);	
					?>
					<option value="NUEVALLANTA">Agregar Llanta...</option>
				</select>		  </td>
		  <td width="11%"><div align="right">*Marca</div></td>
			<td width="22%">
				<select name="cmb_marca" id="cmb_marca" class="combo_box" onchange="agregarMarcaLlantas(this)" tabindex="2">
					<option value="">Marca</option>
					<?php 
					$conn = conecta("bd_mantenimiento");		
					$stm_sql = "SELECT DISTINCT marca FROM llantas ORDER BY marca";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){
						do{
							echo "<option value='$datos[marca]'>$datos[marca]</option>";
						}while($datos = mysql_fetch_array($rs));
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);	
					?>
					<option value="NUEVAMARCA">Agregar Marca...</option>
				</select>		  </td>
		    <td width="11%"><div align="right">*Ubicaci&oacute;n</div></td>
			<td width="22%">
				<select name="cmb_ubicacion" id="cmb_ubicacion" class="combo_box" tabindex="3">
					<option value="">Equipo</option>
					<option value="STOCK">STOCK LLANTAS</option>
					<?php 
					//Obtener los Sistemas Registrados en la BD
					$conn = conecta("bd_mantenimiento");
					$rs_equipos = mysql_query("SELECT id_equipo FROM equipos WHERE estado='ACTIVO' AND id_equipo!='STOCK' ORDER BY familia,id_equipo");
					if($equipos=mysql_fetch_array($rs_equipos)){
						do{
							echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
						}while($equipos=mysql_fetch_array($rs_equipos));
					}
					//Cerrar la conexion con la BD
					mysql_close($conn);		
					?>
			  </select>
			</td>
		</tr>
		<tr>
			<td><div align="right">Estado</div></td>
			<td>
		  		<select name="cmb_estado" id="cmb_estado" tabindex="4">
					<option value="NUEVA" selected="selected">NUEVA</option>
					<option value="REUSO">REUSO</option>
					<option value="DESHECHO">DESHECHO</option>
				</select>
			</td>
			<td><div align="right">Costo</div></td>
			<td>
				$<input type='text' name='txt_costo' id='txt_costo' class='caja_de_num' size='10' value="0.00" tabindex="5"
				onClick="formatCurrency(value.replace(/,/g,''),'txt_costo');"onBlur="formatCurrency(value.replace(/,/g,''),'txt_costo');"/>
			</td>
			<td><div align="right">Disponibilidad</div></td>
			<td>
				<select name="cmb_disponibilidad" id="cmb_disponibilidad" tabindex="6">
					<option value="DESMONTADA" selected="selected">DESMONTADA</option>
					<option value="MONTADA">MONTADA</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><div align="right">*Medida</div></td>
			<td><input name="txt_medida" id="txt_medida" type="text" class="caja_de_texto" size="10" maxlength="10" onkeypress="return permite(event,'num_car',4);" tabindex="7"/></td>
			<td><div align="right">*Medida Rin</div></td>
			<td><input name="txt_medidaRin" id="txt_medidaRin" type="text" class="caja_de_texto" size="10" maxlength="10" onkeypress="return permite(event,'num_car',4);" tabindex="8"/></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
		</tr>
		<tr>
			<td colspan="6" align="center">
				<input type="hidden" name="hdn_estado" id="hdn_estado" value="Agregar"/>
				<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Guardar" title="Guardar la Llanta Registrada" onmouseover="window.status='';return true;" tabindex="9"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario" onclick="cmb_llanta.focus();" tabindex="10"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; de Llantas" onclick="location.href='menu_llantas.php'" tabindex="11"/>
			</td>
		</tr>
		</table>
	</form>
</fieldset>

	<?php 
		//Verificar si existen aceites, de haberlo, mostrar el catálogo de los mismos
		if($llantaExist==1){?>
		<div id="tabla-llantas" class="borde_seccion2" align="center"> 
			<?php
			mostrarLlantas();
			?>
		</div>
	<?php }
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
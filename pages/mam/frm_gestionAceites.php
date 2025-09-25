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
		include ("op_gestionAceites.php");
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarCatalogoAceites.js" ></script>

    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#catalogo-aceites {position:absolute; left:30px; top:190px; width:690px; height:181px; z-index:12; }
		#tabla-aceites {position:absolute;left:30px;top:400px;width:690px;height:260px;z-index:12; overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Actualizaci&oacute;n del Cat&aacute;logo de Aceites</div>
	
	<?php
		//Verificar si se debe guardar un registro
		if(isset($_POST["sbt_guardar"])){
			guardarActualizacionAceite();
		}
	?>
			
	<fieldset class="borde_seccion" id="catalogo-aceites" name="catalogo-aceites">
    <legend class="titulo_etiqueta">Ingresar Sueldos por Puestos</legend>	
    <br>
	<form name="frm_gestionAceites" method="post" action="frm_gestionAceites.php" onsubmit="return valFormCatalogoAceites(this);">
		<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<tr>
		  <td width="15%"><div align="right">*Aceite</div></td>
			<td width="31%">
				<?php 
					$conn = conecta("bd_mantenimiento");		
					$stm_sql = "SELECT id_aceite,nom_aceite FROM catalogo_aceites_mina ORDER BY nom_aceite";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_aceite" id="cmb_aceite" class="combo_box" onchange="obtenerAceite(this)">
							<option value="" selected="selected">ACEITE</option>
						<?php
						do{
							echo "<option value='$datos[id_aceite]'>$datos[nom_aceite]</option>";
						}while($datos = mysql_fetch_array($rs));?>
						</select><?php
						$aceiteExist=1;
					}
					else{
						echo "<label class='msje_correcto'>No hay Aceite Registrado</label>
							<input type='hidden' name='cmb_aceite' id='cmb_aceite'/>";
						$aceiteExist=0;
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);	
				?>
			</td>
		  <td width="28%">
				<div align="right">
					<input type="checkbox" name="ckb_nuevoAceite" id="ckb_nuevoAceite" onclick="agregarNuevoAceite(this);" title="Seleccione para escribir el nombre de un Aceite que no exista"/>Agregar Nuevo Aceite
				</div>
		  </td>
		  <td width="26%"><input type="text" name="txt_nuevoAceite" id="txt_nuevoAceite" class="caja_de_texto" readonly="readonly" size="30" onchange="validarAceiteRepetido(this);"/></td>
		</tr>
		<tr>
			<td><div align="right">*Cantidad</div></td>
			<td>
				<input name="txt_cantidad" id="txt_cantidad" type="text" class="caja_de_num" size="10" maxlength="10" onkeypress="return permite(event,'num',2);"
				onchange="formatCurrency(value,'txt_cantidad');"/> LTS
			</td>
			<td><div align="right" id="etiquetaInc" style="visibility:hidden">*Incremento</div></td>
			<td>
				<span id="campoInc" style="visibility:hidden">
				<input name="txt_incremento" id="txt_incremento" type="text" class="caja_de_num" size="10" maxlength="10" onkeypress="return permite(event,'num',2);" 
				onchange="formatCurrency(value,'txt_incremento');"/> LTS
				</span>
			</td>
		</tr>
		<tr>
			<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<input type="hidden" name="hdn_estado" id="hdn_estado" value="Agregar"/>
				<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Guardar" title="Guardar el Aceite Registrado" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario" onclick="restablecerAceites();"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; de Aceites" onclick="location.href='menu_aceites.php'"/>
			</td>
		</tr>
		</table>
	</form>
	</fieldset>

	<?php 
		//Verificar si existen aceites, de haberlo, mostrar el catálogo de los mismos
		if($aceiteExist==1){?>
		<div id="tabla-aceites" class="borde_seccion2" align="center"> 
			<?php
			mostrarAceites();
			?>
		</div>
	<?php }
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
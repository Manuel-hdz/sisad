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
		#bitacoraAceites { position:absolute; left:30px; top:190px; width:655px; height:255px; z-index:12; }
		#calendario { position:absolute; left:235px; top:268px; width:30px; height:26px; z-index:13; }
		#equipos { position:absolute; left:30px; top:190px; width:921px; height:450px; z-index:22; overflow: scroll; z-index:14;}
		#botones{position:absolute;left:30px;top:675px;width:950px;height:37px;z-index:15;}
		-->
    </style>	
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Bit&aacute;cora de Llantas </div>
	
	<?php if(!isset($_POST["sbt_guardar"])){?>
	
	<script type="text/javascript" language="javascript">
		setTimeout("document.getElementById('cmb_llanta').focus();",500);
	</script>
	
	<fieldset class="borde_seccion" id="bitacoraAceites" name="bitacoraAceites">	
	<legend class="titulo_etiqueta">Registro de Uso de Llantas</legend>
	<br />
	<form name="frm_bitacoraLlantas" action="frm_bitacoraLlantas.php" method="post" onsubmit="return valFormBitacoraLlantas(this);">
		<table width="100%" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
				<td width="77"><div align="right">*No. Llanta</div></td>
				<td><select name="cmb_llanta" id="cmb_llanta" class="combo_box" tabindex="1">
                      <option value="" selected="selected">No. Llanta</option>
                      <?php 
					$conn = conecta("bd_mantenimiento");		
					$stm_sql = "SELECT id_llanta FROM llantas WHERE estado!='DESHECHO' ORDER BY id_llanta";
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
                    </select></td>
			  <td width="139"><div align="right">*Equipo</div></td>
			  <td width="146"><select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="4">
                  <option value="">Equipo</option>
                  <option value="STOCK">STOCK LLANTAS</option>
                  <?php 
						//Obtener los Sistemas Registrados en la BD
						$conn = conecta("bd_mantenimiento");
						$rs_equipos = mysql_query("SELECT id_equipo FROM equipos WHERE estado='ACTIVO' ORDER BY familia,id_equipo");
						if($equipos=mysql_fetch_array($rs_equipos)){
							do{
								echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
							}while($equipos=mysql_fetch_array($rs_equipos));
						}
						//Cerrar la conexion con la BD
						mysql_close($conn);		
						?>
                </select></td>
			</tr>
			<tr>
			  <td><div align="right">Fecha </div></td>
			  <td><input name="txt_fecha" id="txt_fecha" type="text" value="<?php echo date("d/m/Y");?>" size="10" maxlength="15" readonly="true"/></td>
			  <td><div align="right">*Turno</div></td>
			  <td><select name="cmb_turno" id="cmb_turno" class="combo_box" title="Seleccionar el Turno del Registro" tabindex="2">
                <option value="">Turno</option>
                <option value="PRIMERA">PRIMERA</option>
                <option value="SEGUNDA">SEGUNDA</option>
                <option value="TERCERA">TERCERA</option>
              </select></td>
		  </tr>
			<tr>
			  <td><div align="right">*Tipo Trabajo </div></td>
			  <td>
			  	<select name="cmb_tipoTrabajo" id="cmb_tipoTrabajo" class="combo_box" title="Seleccionar el Servicio Realizado a la Llanta" tabindex="5">
					<option value="">Tipo Trabajo</option>
					<option value="INSTALAR">INSTALAR</option>
					<option value="DESHECHAR">DESHECHAR</option>
					<option value="REPARAR">REPARAR</option>
				</select>
			  </td>
			  <td><div align="right">Costo</div></td>
			  <td>
			  	$<input type='text' name='txt_costo' id='txt_costo' class='caja_de_num' size='10' value="0.00" tabindex="6"
				onClick="formatCurrency(value.replace(/,/g,''),'txt_costo');"onBlur="formatCurrency(value.replace(/,/g,''),'txt_costo');"/>
			  </td>
		  </tr>
			<tr>
				<td valign="top"><div align="right">*Descripci&oacute;n</div></td>
				<td colspan="3">
					<textarea name="txa_descripcion" id="txa_descripcion" maxlength="160" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="4" cols="40"
            		onkeypress="return permite(event,'num_car', 0);" tabindex="7"></textarea>
			</td>
		  </tr>
		<tr>
			<td colspan="4" align="center">
				<input name="sbt_guardar" type="submit" class="botones" value="Guardar" onmouseover="window.status='';return true"
				title="Guardar Registro en la Bit&aacute;cora de Llantas" tabindex="8"/> 
				&nbsp;
				<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" tabindex="9" onclick="cmb_llanta.focus();"/>
				&nbsp;
				<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar al Men&uacute; de Llantas" onclick="location.href='menu_llantas.php'" tabindex="10"/>			</td>
		</tr>
		</table>
	</form>
	</fieldset>
			
	<div id="calendario">
		<input name="fechaRegistro" id="fechaRegistro" type="image" src="../../images/calendar.png" title="Seleccionar la Fecha de Registro de la Bit&aacute;cora de Llantas"
		onclick="displayCalendar(document.frm_bitacoraLlantas.txt_fecha,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" tabindex="3"/>
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
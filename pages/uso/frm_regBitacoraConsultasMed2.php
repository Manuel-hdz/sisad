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
		//Este archivo contiene las funciones sobre la bitacora de consultas
		include ("op_bitacoraConsultas.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<?php
	if(isset($_POST["cmb_clasificacion"]) && $_POST["cmb_clasificacion"]=="INTERNA" || isset($_SESSION["datosConsMedica"])){
	?>
		<script type="text/javascript" src="includes/ajax/busq_spider_personal_datos.js"></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<?php
	}
	?>

    <style type="text/css">
		<!--
		#titulo-generar{ position:absolute; left:30px; top:146px; width:285px; height:19px; z-index:11;}
		#tabla-consulta{ position:absolute; left:30px; top:190px; width:900px;	height:360px; z-index:12; }
		#calendario { position:absolute; left:223px; top:280px; width:30px; height:26px; z-index:13;}
	<?php
	if(isset($_POST["cmb_clasificacion"]) && $_POST["cmb_clasificacion"]=="INTERNA" || isset($_SESSION["datosConsMedica"])){
	?>
		#res-spider {position:absolute;z-index:15;}
	<?php
	}
	?>
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Registrar Bit&aacute;cora de Consultas M&eacute;dicas</div>

	<?php
	if(isset($_POST["cmb_clasificacion"]) || isset($_SESSION["datosConsMedica"]) && !isset($_POST["sbt_guardar"])){
		if(isset($_POST["cmb_clasificacion"])){
			$tipoCons=$_POST["cmb_tipo"];
			$mer=date("A");
			$consulta=$_POST["cmb_clasificacion"];
			$fecha=date("d/m/Y");
			$hora=date("h:i");
			$empleado="";
			$rfc="";
			$numEmp="";
			$lugar="UNIDAD DE SALUD OCUPACIONAL";
			$area="";
			$puesto="";
			$diag="";
			$tratamiento="";
			$obs="";
			$nomFamiliar="";
			$parentesco="";
		}
		if(isset($_SESSION["datosConsMedica"])){
			$tipoCons=$_SESSION["datosConsMedica"]["tipoConsulta"];
			if(substr($_SESSION["datosConsMedica"]["hora"],0,2)<13)
				$mer="AM";
			else
				$mer="PM";
			$consulta=$_SESSION["datosConsMedica"]["consulta"];
			$fecha=modFecha($_SESSION["datosConsMedica"]["fecha"],1);
			$hora=substr($_SESSION["datosConsMedica"]["hora"],0,5);
			$empleado=$_SESSION["datosConsMedica"]["nomEmpleado"];
			$rfc=$_SESSION["datosConsMedica"]["rfc"];
			$numEmp=$_SESSION["datosConsMedica"]["numEmp"];
			$lugar=$_SESSION["datosConsMedica"]["lugar"];
			$area=$_SESSION["datosConsMedica"]["area"];
			$puesto=$_SESSION["datosConsMedica"]["puesto"];
			$diag=$_SESSION["datosConsMedica"]["diagnostico"];
			$tratamiento=$_SESSION["datosConsMedica"]["tratamiento"];
			$obs=$_SESSION["datosConsMedica"]["observaciones"];
			$nomFamiliar=$_SESSION["datosConsMedica"]["nom_familiar"];
			$parentesco=$_SESSION["datosConsMedica"]["parentesco"];
		}
		if($consulta=="INTERNA"){
			?>
			<div id="calendario">
				<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_regBitConInterna.txt_fecha,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
			</div>
			
			<fieldset id="tabla-consulta" class="borde_seccion">
			<legend class="titulo_etiqueta">Bit&aacute;cora de Consultas M&eacute;dicas a Personal de Concreto Lanzado de Fresnillo</legend>
			<br>	
			<form name="frm_regBitConInterna" method="post" action="frm_regBitacoraConsultasMed2.php" onsubmit="return valFormConsMedInterna(this);">
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<tr>
				  <td width="92"><div align="right">Empresa</div></td>
					<td width="267">
						<input type="text" name="txt_consulta" id="txt_consulta" class="caja_de_texto" readonly="readonly" value="CONCRETO LANZADO DE FRESNILLO" size="40" />
				  </td>
				  <td width="92"><div align="right">Consulta</div></td>
					<td width="150">
						<input type="text" name="txt_consulta" id="txt_consulta" class="caja_de_texto" readonly="readonly" value="INTERNA" size="10" />
				  </td>
				  <td width="91"><div align="right">Tipo de Consulta</div></td>
					<td width="113">
						<input type="text" name="txt_tipoConsulta" id="txt_tipoConsulta" class="caja_de_texto" readonly="readonly" value="<?php echo $tipoCons;?>" size="10" />
				  </td>
				</tr>
				<tr>
					<td><div align="right">Fecha</div></td>
					<td>
						<input name="txt_fecha" type="text" id="txt_fecha" value="<?php echo $fecha;?>" size="10" maxlength="15" readonly="readonly" class="caja_de_texto"/>
					</td>
					<td><div align="right">*Hora</div></td>
					<td>
						<input type="text" class="caja_de_texto" name="txt_hora" id="txt_hora" size="5" onchange="formatHora(this,'cmb_hora');" maxlength="5"
						onkeypress="return permite(event,'num',0);" value="<?php echo $hora; ?>"/>&nbsp;
						<select name="cmb_hora" id="cmb_hora" class="combo_box">
							<option value="AM"<?php if($mer=="AM") echo " selected='selected'";?>>a.m.</option>
							<option value="PM"<?php if($mer=="PM") echo " selected='selected'";?>>p.m.</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><div align="right">Empleado</div></td>
					<td>
						<input type="text" name="txt_nombre" id="txt_nombre" class="caja_de_texto" onkeyup="lookup(this,'empleados','1');" value="<?php echo $empleado;?>" size="50" maxlength="75" onkeypress="return permite(event,'car',0);"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow"/>
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>
					</td>
					<td><div align="right">RFC</div></td>
					<td>
						<input type="text" name="txt_rfc" id="txt_rfc" class="caja_de_texto" readonly="readonly" value="<?php echo $rfc?>" size="15" maxlength="15"/>
					</td>
					<td><div align="right">Num. Empleado</div></td>
					<td>
						<input type="text" name="txt_noEmpleado" id="txt_noEmpleado" class="caja_de_num" readonly="readonly" value="<?php echo $numEmp?>" size="10" maxlength="20"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">
						<?php if($nomFamiliar==""){?>
							<input type="checkbox" name="ckb_familiar" id="ckb_familiar" onclick="activarFamiliarTrabajador(this,txt_nomFamiliar,txt_parentesco);"/>Familiar
						<?php }else{?>
							<input type="checkbox" name="ckb_familiar" id="ckb_familiar" onclick="activarFamiliarTrabajador(this,txt_nomFamiliar,txt_parentesco);" checked="checked"/>Familiar
						<?php }?>
					</div></td>
					<td><input type="text" name="txt_nomFamiliar" id="txt_nomFamiliar" class="caja_de_texto" value="<?php echo $nomFamiliar;?>" size="50" maxlength="75" 
						onkeypress="return permite(event,'car',0);" <?php if($nomFamiliar=="") echo "readonly='readonly'"?>/></td>
					<td><div align="right">Parentesco</div></td>
					<td><input type="text" name="txt_parentesco" id="txt_parentesco" class="caja_de_texto" value="<?php echo $parentesco;?>" size="30" maxlength="30" 
						onkeypress="return permite(event,'car',0);" <?php if($nomFamiliar=="") echo "readonly='readonly'"?>/></td>
				</tr>
				<tr>
					<td><div align="right">*Lugar</div></td>
					<td>
						<input type="text" name="txt_lugar" id="txt_lugar" class="caja_de_texto" value="<?php echo $lugar;?>" size="40" maxlength="60" onkeypress="return permite(event,'car',0);"/>
					</td>
					<td><div align="right">*&Aacute;rea</div></td>
					<td>
						<input type="text" name="txt_area" id="txt_area" class="caja_de_texto" value="<?php echo $area?>" size="20" maxlength="20" onkeypress="return permite(event,'car',0);" readonly="readonly"/>
					</td>
					<td><div align="right">*Puesto</div></td>
					<td>
						<input type="text" name="txt_puesto" id="txt_puesto" class="caja_de_texto" value="<?php echo $puesto?>" size="20" maxlength="30" onkeypress="return permite(event,'car',0);" readonly="readonly"/>
					</td>
				</tr>
			  </table>
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<tr>
					<td valign="top"><div align="right">*PB Diagn&oacute;stico</div></td>
					<td>
						<textarea name="txa_diagnostico" id="txa_diagnostico" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $diag?></textarea>
					</td>
					<td valign="top"><div align="right">*Tratamiento</div></td>
					<td>
						<textarea name="txa_tratamiento" id="txa_tratamiento" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $tratamiento?></textarea>
					</td>
					<td valign="top"><div align="right">Observaciones</div></td>
					<td>
						<textarea name="txa_observaciones" id="txa_observaciones" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $obs?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="6" align="center">
						<?php if(!isset($_SESSION["medicamento"])){?>
							<input type="hidden" name="hdn_medicamento" id="hdn_medicamento" value="0"/>
						<?php }else{?>
							<input type="hidden" name="hdn_medicamento" id="hdn_medicamento" value="1"/>
						<?php }?>
						<input type="button" name="btn_regMedicamento" id="btn_regMedicamento" class="botones_largos" title="Registrar Medicamentos Suministrados al Trabajador" value="Registrar Medicamentos"
						onclick="registrarMedicamento(this);" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if($tipoCons=="ACCIDENTE"){?>
							<input type="submit" name="sbt_guardar" id="sbt_guardar" class="botones" title="Continuar a Realizar el Informe Médico" value="Continuar" 
							onmouseover="window.status='';return true;"/>						
						<?php } else{?>
							<input type="submit" name="sbt_guardar" id="sbt_guardar" class="botones" title="Guardar el Registro en la Bit&aacute;cora de Consultas" value="Guardar" 
							onmouseover="window.status='';return true;"/>
						<?php }?>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" title="Limpiar el Formulario" value="Limpiar" onclick="restablecerFormConMedInterno(txt_nomFamiliar,txt_parentesco);"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Cancelar y Volver a Seleccionar Otros Criterios para la Consulta M&eacute;dica" value="Regresar" 
						onclick="location.href='frm_regBitacoraConsultasMed.php'"/>
					</td>
				</tr>
			</table>
			</form>
			</fieldset>
			<?php
		}
		if($consulta=="EXTERNA"){
			?>
			<div id="calendario">
				<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_regBitConExterna.txt_fecha,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
			</div>
			
			<fieldset id="tabla-consulta" class="borde_seccion">
			<legend class="titulo_etiqueta">Bit&aacute;cora de Consultas M&eacute;dicas a Empresas Externas</legend>
			<br>	
			<form name="frm_regBitConExterna" method="post" action="frm_regBitacoraConsultasMed2.php" onsubmit="return valFormConsMedExterna(this);">
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<tr>
				  <td width="92"><div align="right">Empresa</div></td>
					<td width="267">
						<?php 
						$result=cargarComboConId("cmb_empresa","nom_empresa","id_empresa","catalogo_empresas","bd_clinica","Seleccionar","","");
						if($result==0){
							echo "<label class='msje_correcto'>No hay Empresas Registradas</label>
							<input type='hidden' name='cmb_empresa' id='cmb_empresa'/>";
						}
						?>
				  </td>
				  <td width="92"><div align="right">Consulta</div></td>
					<td width="150">
						<input type="text" name="txt_consulta" id="txt_consulta" class="caja_de_texto" readonly="readonly" value="EXTERNA" size="10" />
				  </td>
				  <td width="91"><div align="right">Tipo de Consulta</div></td>
					<td width="113">
						<input type="text" name="txt_tipoConsulta" id="txt_tipoConsulta" class="caja_de_texto" readonly="readonly" value="<?php echo $tipoCons;?>" size="10" />
				  </td>
				</tr>
				<tr>
					<td><div align="right">Fecha</div></td>
					<td>
						<input name="txt_fecha" type="text" id="txt_fecha" value="<?php echo date("d/m/Y");?>" size="10" maxlength="15" readonly="readonly" class="caja_de_texto"/>
					</td>
					<td><div align="right">*Hora</div></td>
					<td>
						<input type="text" class="caja_de_texto" name="txt_hora" id="txt_hora" size="5" onchange="formatHora(this,'cmb_hora');" maxlength="5"
						onkeypress="return permite(event,'num',0);" value="<?php echo date("h:i"); ?>"/>&nbsp;
						<select name="cmb_hora" id="cmb_hora" class="combo_box">
							<option value="AM"<?php if($mer=="AM") echo " selected='selected'";?>>a.m.</option>
							<option value="PM"<?php if($mer=="PM") echo " selected='selected'";?>>p.m.</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><div align="right">Empleado</div></td>
					<td>
						<input type="text" name="txt_nombre" id="txt_nombre" class="caja_de_texto" value="" size="50" maxlength="75" onkeypress="return permite(event,'car',0);"/>
					</td>
					<td><div align="right">RFC</div></td>
					<td>
						<input type="text" name="txt_rfc" id="txt_rfc" class="caja_de_texto" value="" size="15" onkeypress="return permite(event,'num_car', 3);" maxlength="15"/>
					</td>
					<td><div align="right">Num. Empleado</div></td>
					<td>
						<input type="text" name="txt_noEmpleado" id="txt_noEmpleado" class="caja_de_num" value="" size="10" onkeypress="return permite(event,'num',3);" maxlength="10"/>
					</td>
				</tr>
				<tr>
					<td><div align="right"><input type="checkbox" name="ckb_familiar" id="ckb_familiar" onclick="activarFamiliarTrabajador(this,txt_nomFamiliar,txt_parentesco);"/>Familiar</div></td>
					<td><input type="text" name="txt_nomFamiliar" id="txt_nomFamiliar" class="caja_de_texto" readonly="readonly" value="" size="50" maxlength="75" onkeypress="return permite(event,'car',0);"/></td>
					<td><div align="right">Parentesco</div></td>
					<td><input type="text" name="txt_parentesco" id="txt_parentesco" class="caja_de_texto" readonly="readonly" value="" size="30" maxlength="30" onkeypress="return permite(event,'car',0);"/></td>
				</tr>
				<tr>
					<td><div align="right">*Lugar</div></td>
					<td>
						<input type="text" name="txt_lugar" id="txt_lugar" class="caja_de_texto" value="UNIDAD DE SALUD OCUPACIONAL" size="40" maxlength="60" onkeypress="return permite(event,'car',0);"/>
					</td>
					<td><div align="right">*&Aacute;rea</div></td>
					<td>
						<input type="text" name="txt_area" id="txt_area" class="caja_de_texto" value="" size="20" maxlength="20" onkeypress="return permite(event,'car',0);"/>
					</td>
					<td><div align="right">*Puesto</div></td>
					<td>
						<input type="text" name="txt_puesto" id="txt_puesto" class="caja_de_texto" value="" size="20" maxlength="30" onkeypress="return permite(event,'car',0);"/>
					</td>
				</tr>
			  </table>
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<tr>
					<td valign="top"><div align="right">*PB Diagn&oacute;stico</div></td>
					<td>
						<textarea name="txa_diagnostico" id="txa_diagnostico" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"></textarea>
					</td>
					<td valign="top"><div align="right">*Tratamiento</div></td>
					<td>
						<textarea name="txa_tratamiento" id="txa_tratamiento" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"></textarea>
					</td>
					<td valign="top"><div align="right">Observaciones</div></td>
					<td>
						<textarea name="txa_observaciones" id="txa_observaciones" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="6" align="center">
						<input type="hidden" name="hdn_medicamento" id="hdn_medicamento" value="0"/>
						<input type="button" name="btn_regMedicamento" id="btn_regMedicamento" class="botones_largos" title="Registrar Medicamentos Suministrados al Trabajador" value="Registrar Medicamentos"
						onclick="registrarMedicamento(this);" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" name="sbt_guardar" id="sbt_guardar" class="botones" title="Guardar el Registro en la Bit&aacute;cora de Consultas" value="Guardar" 
						onmouseover="window.status='';return true;"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" title="Limpiar el Formulario" value="Limpiar" onclick="restablecerFormConMedInterno(txt_nomFamiliar,txt_parentesco);"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Cancelar y Volver a Seleccionar Otros Criterios para la Consulta M&eacute;dica" value="Regresar" 
						onclick="location.href='frm_regBitacoraConsultasMed.php'"/>
					</td>
				</tr>
			</table>
			</form>
			</fieldset>
			<?php
		}
	}
	else{
		if(isset($_POST["sbt_guardar"])){
			if($_POST["txt_consulta"]=="INTERNA")
				guardaBitInterna();
			if($_POST["txt_consulta"]=="EXTERNA")
				guardaBitExterna();
		}
	}
	?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
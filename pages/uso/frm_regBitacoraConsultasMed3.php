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
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	
	<script type="text/javascript" language="javascript">
		//Funcion que activa la caja de Texto donde se guarda la especificacion de las ambulancias
		function activarAmbulancia(valor,caja){
			if(valor=="SI"){
				caja.value="";
				caja.readOnly=false;
			}
			else{
				caja.value="";
				caja.readOnly=true;
			}
		}
		
		//Funcion que valida el formulario de guardarRegistroBitacora en la 3er Pantalla
		function valFormRegConsMedInterna3(frm_regBitConInterna){
			var res=1;
			
			if(frm_regBitConInterna.txt_edad.value==""){
				alert("Ingresar la Edad del Trabajador");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txt_antigEmp.value==""){
				alert("Ingresar la Antigüedad del Trabajador en la Empresa");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txt_antigPuesto.value==""){
				alert("Ingresar la Antigüedad del Trabajador en el Puesto Desempeñado");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txt_area.value==""){
				alert("Ingresar el Área");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txt_lugar.value==""){
				alert("Ingresar el Lugar");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txa_mecanismo.value==""){
				alert("Ingresar el Mecanismo del Accidente");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txa_descripcion.value==""){
				alert("Ingresar la Descripción del Accidente");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txa_diagnostico.value==""){
				alert("Ingresar el Diagnóstico");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txa_tratamiento.value==""){
				alert("Ingresar el Tratamiento");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txt_supervisor.value==""){
				alert("Ingresar el Nombre del Supervisor");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txt_facilitador.value==""){
				alert("Ingresar el Nombre del Facilitador");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txt_avisado.value==""){
				alert("Ingresar el Nombre de la Persona a Quien se dió Aviso");
				res=0;
			}
			if(res==1 && (!frm_regBitConInterna.ckb_ninguna.checked && !frm_regBitConInterna.ckb_rina.checked && !frm_regBitConInterna.ckb_intox.checked && !frm_regBitConInterna.ckb_sim.checked && !frm_regBitConInterna.ckb_ener.checked && !frm_regBitConInterna.ckb_lesion.checked)){
				alert("Seleccionar por lo Menos una Condicion");
				res=0;
			}
			if(res==1 && (!frm_regBitConInterna.ckb_aux.checked && !frm_regBitConInterna.ckb_medico.checked && !frm_regBitConInterna.ckb_imss.checked)){
				alert("Seleccionar por lo Menos una Forma de Manejo");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.cmb_ambulancia.value==""){
				alert("Indicar en que fue el Traslado");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.cmb_ambulancia.value=="SI"){
				if(frm_regBitConInterna.txt_ambulancia.value==""){
					alert("Indicar como fue el Traslado");
					res=0;
				}
			}
			if(res==1 && frm_regBitConInterna.cmb_calificacion.value==""){
				alert("Seleccionar la Calificación del Accidente");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txt_dias.value==""){
				alert("Ingresar el Número de Días Perdidos");
				res=0;
			}
			if(res==1 && frm_regBitConInterna.txt_resposable.value==""){
				alert("Ingresar el Nombre de la Persona que Atendió al Paciente");
				res=0;
			}
			
			if(res==1)
				return true;
			else
				return false;
		}
	</script>
	
    <style type="text/css">
		<!--
		#titulo-generar{ position:absolute; left:30px; top:146px; width:285px; height:19px; z-index:11;}
		#tabla-consulta{ position:absolute; left:30px; top:190px; width:944px;	height:490px; z-index:12;}
		#calendarioRT { position:absolute; left:433px; top:310px; width:30px; height:26px; z-index:13;}
		#calendarioCons { position:absolute; left:770px; top:310px; width:30px; height:26px; z-index:13;}
		#res-spider {position:absolute;z-index:15;}
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Generar Informe M&eacute;dico del Trabajador </div>

	<?php
	if(isset($_POST["sbt_guardar"])){
		guardarRegInfMedico();
		?>
		<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div>
		<?php
	}
	if(isset($_SESSION["datosConsMedica"]) && !isset($_POST["sbt_guardar"])){
		//Obtener la Antigüedad en la empresa a partir de la fecha de Ingreso a la Fecha Actual
		$fechaIngreso=obtenerDato("bd_recursos","empleados","fecha_ingreso","rfc_empleado",$_SESSION["datosConsMedica"]["rfc"]);
		$antig=restarFechas(date("Y-m-d"),$fechaIngreso);
		$antig=round($antig/365,2);
		//Obtener el Meridiano para colocarlo en la seccion de Hora
		$mer=date("A");
		?>
		<div id="calendarioRT">
			<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_regBitConInterna.txt_fechaRT,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<div id="calendarioCons">
			<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_regBitConInterna.txt_fechaConsulta,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		
		<fieldset id="tabla-consulta" class="borde_seccion">
		<legend class="titulo_etiqueta">Generaci&oacute;n del Informe M&eacute;dico del Trabajador </legend>
		<br>	
		<form name="frm_regBitConInterna" method="post" action="frm_regBitacoraConsultasMed3.php" onsubmit="return valFormRegConsMedInterna3(this);">
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td width="81"><div align="right">Trabajador</div></td>
				<td width="200">
					<input type="text" name="txt_nombre" id="txt_nombre" class="caja_de_texto" readonly="readonly" value="<?php echo $_SESSION["datosConsMedica"]["nomEmpleado"]?>" size="40" />			  </td>
			  <td width="120"><div align="right">Num. Empleado</div></td>
			  <td width="100">
			  <input type="text" name="txt_noEmpleado" id="txt_noEmpleado" class="caja_de_num" readonly="readonly" value="<?php echo $_SESSION["datosConsMedica"]["numEmp"]?>" size="10" maxlength="20"/>			  </td>
			  <td width="186"><div align="right">*Edad</div></td>
				<td width="162">
			  	<input type="text" name="txt_edad" id="txt_edad" class="caja_de_num" value="" size="10" maxlength="2" onkeypress="return permite(event,'num',3);"/>
			  	A&ntilde;os
			  	</td>
			</tr>
			<tr>
			  <td width="81"><div align="right">Departamento</div></td>
				<td width="200">
					<input type="text" name="txt_depto" id="txt_depto" class="caja_de_texto" readonly="readonly" value="<?php echo $_SESSION["datosConsMedica"]["area"]?>" size="40" />			  </td>
			  <td width="120"><div align="right">Actividad</div></td>
				<td width="100">
			  <input type="text" name="txt_actividad" id="txt_actividad" class="caja_de_texto" readonly="readonly" value="<?php echo $_SESSION["datosConsMedica"]["puesto"]?>" size="20"/>			  </td>
			  <td width="186"><div align="right">Antig&uuml;edad Empresa</div></td>
				<td width="162">
				  <input type="text" name="txt_antigEmp" id="txt_antigEmp" class="caja_de_num" onkeypress="return permite(event,'num',2);"
				   value="<?php echo $antig?>" size="10" maxlength="10"/>
				  A&ntilde;os
			  	</td>
			</tr>
			<tr>
				<td width="81"><div align="right">Antig&uuml;edad Puesto</div></td>
				<td colspan="5">
					<input name="txt_antigPuesto" type="text" class="caja_de_num" id="txt_antigPuesto" onkeypress="return permite(event,'num',2);" value="" size="10" maxlength="10"/>
					A&ntilde;os
					&nbsp;
					Fecha R.T.
					<input name="txt_fechaRT" type="text" id="txt_fechaRT" value="<?php echo date("d/m/Y");?>" size="10" maxlength="15" readonly="readonly" class="caja_de_texto"/>&nbsp;&nbsp;&nbsp;&nbsp;
					Hora
					<input type="text" class="caja_de_texto" name="txt_horaRT" id="txt_horaRT" size="2" onchange="formatHora(this,'cmb_horaRT');" maxlength="5"
					onkeypress="return permite(event,'num',0);" value="<?php echo date("h:i"); ?>"/>
					<select name="cmb_horaRT" id="cmb_horaRT" class="combo_box">
						<option value="AM"<?php if($mer=="AM") echo " selected='selected'";?>>a.m.</option>
						<option value="PM"<?php if($mer=="PM") echo " selected='selected'";?>>p.m.</option>
					</select>
					Fecha Consulta
					&nbsp;
					<input name="txt_fechaConsulta" type="text" id="txt_fechaConsulta" value="<?php echo date("d/m/Y");?>" size="10" maxlength="15" readonly="readonly" class="caja_de_texto"/>&nbsp;&nbsp;&nbsp;&nbsp;
					Hora
					<input type="text" class="caja_de_texto" name="txt_horaConsulta" id="txt_horaConsulta" size="2" onchange="formatHora(this,'cmb_horaConsulta');" maxlength="5"
					onkeypress="return permite(event,'num',0);" value="<?php echo date("h:i"); ?>"/>
					<select name="cmb_horaConsulta" id="cmb_horaConsulta" class="combo_box">
						<option value="AM"<?php if($mer=="AM") echo " selected='selected'";?>>a.m.</option>
						<option value="PM"<?php if($mer=="PM") echo " selected='selected'";?>>p.m.</option>
			  </select>			  </td>
			</tr>
			<tr>
				<td><div align="right">*&Aacute;rea</div></td>
				<td><input type="text" name="txt_area" id="txt_area" class="caja_de_texto" value="CONCRETO LANZADO DE FRESNILLO" size="40" maxlength="40" onkeypress="return permite(event,'car',0);"/>				</td>
				<td><div align="right">*Lugar</div></td>
				<td colspan="3"><input type="text" name="txt_lugar" id="txt_lugar" class="caja_de_texto" value="" size="40" maxlength="60" onkeypress="return permite(event,'num_car',0);"/></td>
			</tr>
		  </table>
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td width="12%" valign="top"><div align="right">*Mecanismo Accidente </div></td>
				<td width="20%">
					<textarea name="txa_mecanismo" id="txa_mecanismo" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
					rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"></textarea>
			  </td>
				<td width="13%" valign="top"><div align="right">*Descripci&oacute;n Accidente </div></td>
				<td width="18%">
					<textarea name="txa_descripcion" id="txa_descripcion" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
					rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"></textarea>
			  </td>
			  <td width="14%" valign="top"><div align="right">*Diagn&oacute;stico</div></td>
				<td width="23%">
					<textarea name="txa_diagnostico" id="txa_diagnostico" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
					rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"></textarea>
			  </td>
			</tr>
			<tr>
				<td valign="top"><div align="right">*Tratamiento</div></td>
				<td>
					<textarea name="txa_tratamiento" id="txa_tratamiento" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
					rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"></textarea>
				</td>
				<td valign="top"><div align="right">Auxiliares Diagn&oacute;stico </div></td>
				<td>
					<textarea name="txa_auxDiagnostico" id="txa_auxDiagnostico" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
					rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"></textarea>
				</td>
				<td valign="top"><div align="right">Observaciones</div></td>
				<td valign="top">
				  <textarea name="txa_observaciones" id="txa_observaciones" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
					rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"></textarea></td>
			</tr>
			<tr>
				<td valign="top"><div align="right">*Facilitador</div></td>
				<td valign="top">
					<input type="text" name="txt_facilitador" id="txt_facilitador" class="caja_de_texto" onkeyup="lookup(this,'empleados','2');" value="" size="30" maxlength="75" onkeypress="return permite(event,'car',0);"/>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
						<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow"/>
						<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
						</div>
					</div>
				</td>
				<td valign="top"><div align="right">*Aviso A:</div></td>
				<td valign="top">
					<input type="text" name="txt_avisado" id="txt_avisado" class="caja_de_texto" onkeyup="lookup(this,'empleados','3');" value="" size="30" maxlength="75" onkeypress="return permite(event,'car',0);"/>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions3" style="display: none;">
						<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow"/>
						<div class="suggestionList" id="autoSuggestionsList3">&nbsp;</div>
						</div>
					</div>
				</td>
				<td><div align="right">*Supervisor</div>
				</td>
				<td><input type="text" name="txt_supervisor" id="txt_supervisor" class="caja_de_texto" onkeyup="lookup(this,'empleados','1');" value="" size="40" maxlength="75" onkeypress="return permite(event,'car',0);"/>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
						<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow"/>
						<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Otras Condiciones:</div></td>
				<td colspan="3">
					<input type="checkbox" name="ckb_ninguna" id="ckb_ninguna" value="1"/>Ninguna
					<input type="checkbox" name="ckb_rina" id="ckb_rina" value="1"/>Hubo Riña
					<input type="checkbox" name="ckb_intox" id="ckb_intox" value="1"/>Intoxicación Alcoholica
					<input type="checkbox" name="ckb_sim" id="ckb_sim" value="1"/>Existe Simulación
				</td>
				<td>
					<input type="checkbox" name="ckb_ener" id="ckb_ener" value="1"/>Intoxicación por Enervantes
				</td>
				<td>
					<input type="checkbox" name="ckb_lesion" id="ckb_lesion" value="1"/>Se Provoc&oacute; las Lesiones Intencionalmente
				</td>
			</tr>
			<tr>
				<td><div align="right">*Manejo</div></td>
				<td colspan="3">
					<input type="checkbox" name="ckb_aux" id="ckb_aux" value="1"/>Primeros Auxiliios
					<input type="checkbox" name="ckb_medico" id="ckb_medico" value="1"/>Manejo M&eacute;dico
					<input type="checkbox" name="ckb_imss" id="ckb_imss" value="1"/>Envio IMSS
				</td>
				<td><div align="right">*Ambulancia</div></td>
				<td>
					<select name="cmb_ambulancia" id="cmb_ambulancia" class="combo_box" onchange="activarAmbulancia(this.value,txt_ambulancia);">
						<option value="" selected="selected">Traslado</option>
						<option value="SI">SI</option>
						<option value="NO">NO</option>
					</select>
					<input type="text" name="txt_ambulancia" id="txt_ambulancia" class="caja_de_texto" readonly="readonly" onkeypress="return permite(event,'num_car',0);" maxlength="60"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Calificaci&oacute;n</div></td>
				<td>
					<select name="cmb_calificacion" id="cmb_calificacion" class="combo_box">
						<option value="" selected="selected">Calificaci&oacute;n</option>
						<option value="A">A</option>
						<option value="B">B</option>
						<option value="C">C</option>
						<option value="D">D</option>
						<option value="E">E</option>
						<option value="F">F</option>
					</select>
				</td>
				<td><div align="right">*N&deg; de D&iacute;as</div></td>
				<td>
					<input type="text" class="caja_de_num" name="txt_dias" id="txt_dias" onkeypress="return permite(event,'num',0);" size="5" maxlength="10"/>
				</td>
				<td><div align="right">*Atendido Por</div></td>
				<td>
					<input type="text" name="txt_resposable" id="txt_resposable" class="caja_de_texto" value="DR. MALCO OBED GARC&Iacute;A BORJ&Oacute;N" size="30" maxlength="40" onkeypress="return permite(event,'car',0);"/>
				</td>
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input type="submit" name="sbt_guardar" id="sbt_guardar" class="botones_largos" title="Guardar y Generar Informe M&eacute;dico" value="Generar Informe M&eacute;dico" 
					onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" title="Limpiar el Formulario" value="Limpiar"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Regresar a la Pantalla Anterior" value="Regresar" 
					onclick="location.href='frm_regBitacoraConsultasMed2.php'"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" class="botones" title="Cancelar y Elegir otros Datos" value="Cancelar" 
					onclick="location.href='frm_regBitacoraConsultasMed.php'"/>
				</td>
			</tr>
		</table>
		</form>
</fieldset>
		<?php
	}
	?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
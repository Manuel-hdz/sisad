<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion
html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_seleccionarPermiso.php");
		?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>
    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:489px;	height:20px;	z-index:11;}
			#tabla-permisoPeligroso {position:absolute;left:23px;top:177px;width:950px;height:439px;z-index:12;padding:15px;padding-top:0px;}
			#periodo1{position:absolute; left:581px; top:509px; width:30px; height:26px; z-index:18; }	
			#periodo2{position:absolute; left:891px; top:508px; width:30px; height:26px; z-index:18; }	
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Generar Permisos para Realizar Trabajos Peligrosos</div>
<?php
	//verificar que este definido el ID del tipo de reporte a mostrar
	if (isset($_GET["id_tipoPer"])){
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente, es decir las variables que vienen en la url
		$idPermiso = $_GET["id_tipoPer"];
	}?>
	<fieldset class="borde_seccion" id="tabla-permisoPeligroso" name="tabla-permisoPeligroso">
	<legend class="titulo_etiqueta">Ingresar los Datos del Permiso para Trabajos Peligroso</legend>
	<form onsubmit="return valFormPermisoTrabPeligroso(this);" name="frm_permisoTrabPeligroso" method="post"  id="frm_permisoTrabPeligroso" >
		<table width="100%"  cellpadding="4" cellspacing="4"  class="tabla_frm">
			<tr>
			  <td width="13%"><div align="right">Clave Permiso</div></td>
				<td width="22%">
					<input type="text" name="txt_idPermisoPel" id="txt_idPermisoPel" maxlength="10" size="10" class="caja_de_texto" 
					value="<?php echo obtenerIdPermisoPeligroso();?>" onkeypress="return permite(event,'num',1);" readonly="readonly"/>			 
				</td>
				<td width="12%"><div align="right">Tipo Permiso</div></td>
				<td width="22%">
					<input name="txt_tipoPermiso" type="text" class="caja_de_texto" id="txt_tipoPermiso" 
					onkeypress="return permite(event,'num',1);" value="<?php echo $idPermiso; ?>" size="25" readonly="readonly"/>			  
				</td>
				<td width="11%"><div align="right">* Solicitante</div></td>
				<td>
					<input type="text" name="txt_nomSolicitante" id="txt_nomSolicitante" maxlength="80" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" onkeyup="return ismaxlength(this)"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">* Supervisor</div></td>
				<td>
					<input type="text" name="txt_nomSupervisor" id="txt_nomSupervisor" maxlength="100" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>				
				</td>
				<td><div align="right">* Responsable</div></td>
				<td>
					<input type="text" name="txt_nomResponsable" id="txt_nomResponsable" maxlength="100" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" />
				</td>
				<td><div align="right">* Contratista</div></td>
				<td>
					<input type="text" name="txt_nomContratista" id="txt_nomContratista" maxlength="100" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',7);" />				
				</td>
			</tr>
			<tr>
				<td><div align="right">*Encargado Trabajo</div></td>
				<td>
					<input type="text" name="txt_encargadoTrab" id="txt_encargadoTrab" maxlength="60" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>				
				</td>
				<td><div align="right">*Operador</div></td>
				<td>
					<input type="text" name="txt_operador" id="txt_operador" maxlength="60" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>				
				</td>
				<td><div align="right">*Funcionario Reponsable</div></td>
				<td>
					<input type="text" name="txt_funResponsable" id="txt_funResponsable" maxlength="60" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>				
				</td>
			</tr>
			<tr>
				<td><div align="right">*Supervisor Obra </div></td>
				<td>
					<input type="text" name="txt_supervisorObra" id="txt_supervisorObra" maxlength="60" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>				
				</td>
				<td><div align="right">*Supervisor</div></td>
				<td>
					<input type="text" name="txt_supervisor" id="txt_supervisor" maxlength="60" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>				
				</td>
				<td><div align="right">*Aceptaci&oacute;n</div></td>
				<td>
					<input type="text" name="txt_aceptacion" id="txt_aceptacion" maxlength="60" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>				
				</td>
			</tr>
				<td><div align="right">*Descripci&oacute;n Trabajo</div></td>
				<td>
					<textarea name="txa_desTrabajo" cols="36" rows="5" class="caja_de_texto" id="txa_desTrabajo"  
					onkeypress="return permite(event,'num_car',0);"  maxlength="180" onkeyup="return ismaxlength(this)"></textarea>				
				</td>
				<td><div align="right">*Trabajo a Realizar</div></td>
				<td>
					<textarea name="txa_trabRealizar" cols="34" rows="3" class="caja_de_texto" id="txa_trabRealizar"  
					onkeypress="return permite(event,'num_car',0);"  maxlength="100" onkeyup="return ismaxlength(this)"></textarea>				
				</td>
				<td><div align="right">*Trabajo Especifico</div></td>
				<td>
					<textarea name="txa_trabEspecifico" cols="30" rows="4" class="caja_de_texto" id="txa_trabEspecifico"  
					onkeypress="return permite(event,'num_car',0);"  maxlength="120" onkeyup="return ismaxlength(this)"></textarea>				
				</td>
			</tr>
			<tr>
				<td><div align="right">*Tipo Trabajo</div></td>
				<td>
					<input type="radio"  name="rdb_tipoTrabajo" id="rdb_tipoTrabajo" value="PTC001" />							
					Trabajos Espacios Confinados
					<input type="radio" name="rdb_tipoTrabajo" id="rdb_tipoTrabajo" value="PTE002" />
					Trabajos Electricos</br>													
					<input type="radio" name="rdb_tipoTrabajo" id="rdb_tipoTrabajo" value="PTM003" />
					Maniobra  Industrial				
				</td>				
				<td><div align="right">*Hora Inicio</div><br />
				<div align="right">*Periodo Inicio</div></td><br />
				<td>
					<input name="txt_horaIni"  type="text" id="txt_horaIni" 
					onchange="formatHora(this,'cmb_meridiano1');" onkeypress="return permite(event,'num',5);" value="" size="5" maxlength="5" />
						<select name="cmb_meridiano1" id="cmb_meridiano1" class="combo_box" >
							<option value="AM">a.m.</option>
							<option value="PM">p.m.</option>
						</select>
							&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />
							<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text"  
							value="<?php echo date("d/m/Y")?>" size="10"  width="90" />				
				</td>
				<td><div align="right">*Hora Fin</div><br />
					<div align="right">*Periodo Fin</div></td><br />
				<td>
					<input name="txt_horaFin" id="txt_horaFin"  type="text" maxlength="5" value="" size="5" onchange="formatHora(this,'cmb_meridiano2');" 
					onkeypress="return permite(event,'num',5);"/>
						<select name="cmb_meridiano2" id="cmb_meridiano2" class="combo_box" >
							<option value="AM">a.m.</option>
							<option value="PM">p.m.</option>
						</select>
							&nbsp;&nbsp;&nbsp;&nbsp;<br/><br/>
							<input name="txt_fechaFin" id="txt_fechaFin" readonly="readonly" type="text" value="<?php echo date("d/m/Y"); ?>" size="10"  width="90"/>
				</td>
			</tr>
	  		<tr> 
				<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  		</tr>
	  		<tr>
				<td colspan="6"><div align="center">
					<input type="hidden" name="hdn_cmbTipo" id="hdn_cmbTipo"/>
					<input name="sbt_guardar" type="submit" class="botones" id="sbt_generar"  value="Guardar" title="Guardar Permiso de Trabajos Peligroso" 
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					title="Regresar para Generar otro Tipo de Permiso" onmouseover="window.status='';return true" onclick="confirmarSalida('frm_seleccionarPermiso.php')" />
				</div></td>
			</tr>
	  </table>
	</form>
</fieldset>	

<div id="periodo1">
	<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_permisoTrabPeligroso.txt_fechaIni,'dd/mm/yyyy',this)"
	onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"  title="Seleccionar Fecha"
	width="25" height="25" border="0" />
</div>

<div id="periodo2">
	<input name="fechaFin" type="image" id="fechaFin" onclick="displayCalendar(document.frm_permisoTrabPeligroso.txt_fechaFin,'dd/mm/yyyy',this)"
	onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"  title="Seleccionar Fecha"
	width="25" height="25" border="0" />
</div>
			
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
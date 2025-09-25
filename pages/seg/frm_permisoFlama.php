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
		include ("op_seleccionarPermiso.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-seleccionar {position:absolute;	left:30px;	top:146px;	width:432px;	height:20px;	z-index:11;}
			#tabla-seleccionarRegistro {position:absolute;left:30px;top:190px;width:450px;height:149px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-permisoIncendio {position:absolute;left:33px;top:188px;width:812px;height:401px;z-index:12;padding:15px;padding-top:0px;}
			#expiracionPermiso{position:absolute; left:694px; top:337px; width:31px; height:26px; z-index:18; }	
			#registroPermiso{position:absolute; left:693px; top:258px; width:31px; height:26px; z-index:18; }
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8; overflow:scroll}
		-->
    </style>
</head>
<body>
<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Generar Permisos para Realizar Trabajos con Flama Abierta</div>
<?php
	//verificar que este definido el ID del tipo de reporte a mostrar
	if (isset($_GET["id_tipoPer"])){
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente, es decir las variables que vienen en la url
		$idPermiso = $_GET["id_tipoPer"];
	}?>
	<fieldset class="borde_seccion" id="tabla-permisoIncendio" name="tabla-permisoIncendio">
	<legend class="titulo_etiqueta">Ingresar los Datos del Permiso para Trabajos con Flama Abierta</legend>	
	<br>
	<form name="frm_permisoTrabFlama" method="post"  id="frm_permisoTrabFlama" onsubmit="return valFormPermisoTrabIncendio(this);" enctype="multipart/form-data">
		<table width="96%" cellpadding="5" cellspacing="2" class="tabla_frm">
			<tr>
				<td width="18%"><div align="right">Clave Permiso</div></td>
				<td width="25%"><input type="text" name="txt_idPermisoFlama" id="txt_idPermisoFlama" maxlength="10" size="10" class="caja_de_texto" 
						value="<?php echo obtenerIdPermisoFlama();?>" readonly="readonly"/>
				</td>
				<td width="26%"><div align="right">Tipo Permiso</div></td>
				<td width="31%"><input name="txt_tipoPermiso" type="text" class="caja_de_texto" id="txt_tipoPermiso" 
						value="<?php echo $idPermiso; ?>" size="35" readonly="readonly"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Folio Permiso</div></td>
				<td><input type="text" name="txt_folioPermiso" id="txt_folioPermiso" maxlength="10" size="10" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num',1);"/>
				</td>
				<td><div align="right">*Fecha Registro</div></td>
				<td><input name="txt_fechaReg" id="txt_fechaReg" readonly="readonly" type="text" value="<?php echo date("d/m/Y"); ?>" size="10"  width="90"/></td>
			</tr>
			<tr>
				<td colspan="2"><div align="right">*Trabajo en Curso que Produce Calor, Realizado por Personal de la Empresa Externa &oacute; Contratista</div></td>
				<td colspan="2">
					<input type="text" name="txt_nomEmpContratista" id="txt_nomEmpContratista" maxlength="100" size="50" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>
				</td>				
			</tr>
			<tr>
				<td><div align="right">*Encargado Trabajo</div></td>
				<td>
					<input type="text" name="txt_encargadoTrab" id="txt_encargadoTrab" maxlength="70" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"/>
				</td>
				<td><div align="right">*Expiraci&oacute;n del Permiso</div></td>
				<td width="31%"><input name="txt_fechaExp" id="txt_fechaExp" readonly="readonly" type="text" value="<?php echo date("d/m/Y"); ?>" size="10"  width="90"/></td>
			</tr>			
			<tr>
				<td><div align="right">*Lugar Trabajo</div></td>
				<td>
					<input  name="txt_lugarTrabajo" class="caja_de_texto" id="txt_lugarTrabajo"  
					onkeypress="return permite(event,'num_car',1);" size="30" maxlength="70"/></td>
				<td><div align="right">*Hora Expiraci&oacute;n</div></td>
				<td>
					<input name="txt_horaIni"  type="text" id="txt_horaIni" onchange="formatHora(this,'cmb_meridiano1');" 
					onkeypress="return permite(event,'num',5);" value="<?php echo date("h:i"); ?>" size="5" maxlength="5"/>
					<select name="cmb_meridiano1" id="cmb_meridiano1" class="combo_box" >
                    <option value="am">A.M.</option>
                    <option value="pm">P.M.</option>
                  </select>
				</td>
			</tr>		
			<tr>
				<td><div align="right">*Trabajo Especifico </div></td>
				<td colspan="3">
					<textarea name="txa_trabEspecifico" cols="60" rows="2" class="caja_de_texto" id="txa_trabEspecifico" 
				 	onkeypress="return permite(event,'num_car',7);" maxlength="120" onkeyup="return ismaxlength(this)"></textarea>
				</td>
			</tr>		
			<tr>
				<td><div align="right">*Supervisor Obra Contratista</div></td>
				<td><input name="txt_supObra" type="text" id="txt_supObra"  size="30" maxlength="60"  onkeypress="return permite(event,'num_car',1);"/></td>
				<td><div align="right">*Funcionario Responsable</div></td>
				<td><input  name="txt_funResponsable" class="caja_de_texto" id="txa_funResponsable"  onkeypress="return permite(event,'num_car',1);" size="30" maxlength="60"/></td>
			</tr>
			<tr>
				<td><div align="right">*Evidencia 1</div></td>
				<td>
					<input name="txt_foto1" type="file" class="caja_de_texto" id="txt_foto1" title="Buscar Imagen" 
					onchange="return validarImagen(this,'hdn_imgValida_1');"
					onclick="alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenará');" value="" size="20" maxlength="40" />												
				</td>
				<td><div align="right">*Evidencia 2</div></td>
				<td><input name="txt_foto2" type="file" class="caja_de_texto" id="txt_foto2" title="Buscar Imagen"
					onchange="return validarImagen(this,'hdn_imgValida_2');" value="" size="20" maxlength="40" />				
				</td>
			</tr>
	  		<tr> 
				<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  		</tr>
	  		<tr>
				<td colspan="6"><div align="center">
					<input type="hidden" name="hdn_imgValida_1" id="hdn_imgValida_1" value="si" />
					<input type="hidden" name="hdn_imgValida_2" id="hdn_imgValida_2" value="si" />
					<input name="sbt_generar" type="submit" class="botones" id="sbt_generar"  value="Generar" title="Generar Permiso de Trabajos para Flama Abierta" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
					onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					title="Regresar para Generar otro Tipo de Permiso" onmouseover="window.status='';return true" onclick="confirmarSalida('frm_seleccionarPermiso.php')" />
				</div></td>
			</tr>
	  </table>
	</form>
</fieldset>	
		
<div id="registroPermiso">
	<input name="fechaReg" type="image" id="fechaReg" onclick="displayCalendar(document.frm_permisoTrabFlama.txt_fechaReg,'dd/mm/yyyy',this)"
	onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"  title="Seleccionar Fechas"
	width="25" height="25" border="0" />
</div>

<div id="expiracionPermiso">
	<input name="fechaExp" type="image" id="fechaExp" onclick="displayCalendar(document.frm_permisoTrabFlama.txt_fechaExp,'dd/mm/yyyy',this)"
	onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"  title="Seleccionar Fechas"
	width="25" height="25" border="0" />
</div>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
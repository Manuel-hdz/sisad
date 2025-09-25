<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion
html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de la Clinica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_gestionExaMedicos.php");
		?>
		
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="includes/ajax/verificarTipoRegistroExaMedico.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:313px;	height:20px;	z-index:11;}
			#tabla-exaMedico {position:absolute;left:24px;top:193px;width:786px;height:304px;z-index:12;padding:15px;padding-top:0px;}
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8;}
			#botones-TablaDatCat {position:absolute;left:892px;top:470px;width:204px;height:35px;z-index:12;padding:15px;padding-top:0px;}
		
		
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Registrar Catálogo de Exámenes Médicos</div>

	<fieldset class="borde_seccion" id="tabla-exaMedico" name="tabla-exaMedico">
	<legend class="titulo_etiqueta">Seleccionar o Ingresar los Datos del Examen Medico</legend>
	<form onsubmit="return valFormgestionExaMedicos(this);"  name="frm_gesExamenMedico" method="post"  id="frm_gesExamenMedico" >
		<table width="106%"  cellpadding="5" cellspacing="5"  class="tabla_frm">
			<tr>
			  <td width="19%"><div align="right">Nombre Examen</div></td>
				<td width="23%"><?php 
						$result=cargarComboConId("cmb_examen","nom_examen","id_examen","catalogo_examen","bd_clinica","Seleccionar","","verificarRegistroExamenMedico(this.value)");
							if($result==0){
								echo "<label class='msje_correcto'>No hay Examenes Registrados</label>
								<input type='hidden' name='cmb_examen' id='cmb_examen'/>";
							}	
						?></td>	
				<td colspan="2"><div align="center">
					<input type="checkbox" name="ckb_nuevoExamen" id="ckb_nuevoExamen" 
					onclick="agregarNuevoExaMedico(this);"/><strong><u>Registrar Examen Medico</u></strong></div>				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>	
				<td>&nbsp;</td>
				<td>&nbsp;</td>				
			</tr>
			<tr>
				<td colspan="2"><strong>Datos del Examen Medico</strong></td>
				<td><input type="hidden" name="hdn_claveExamen" id="hdn_claveExamen" value="" /></td>	
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre Examen</div></td>
				<td><input type="text" name="txt_nomExamen" id="txt_nomExamen" maxlength="60" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" readonly="readonly"/>				</td>
				<td><div align="right">*Costo Examen</div></td>
				<td>
					<input type="text" name="txt_costoExamen" id="txt_costoExamen" maxlength="10" size="5" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num',2);" readonly="readonly"  onchange="formatCurrency(this.value,'txt_costoExamen');" />				</td>
			</tr>
				<td width="19%"><div align="right">*Tipo Examen</div></td>
				<td>
					<input type="text" name="txt_tipoExamen" id="txt_tipoExamen" maxlength="30" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" onkeyup="return ismaxlength(this)" readonly="readonly" />				</td>
				<td><div align="right">Comentarios</div></td>
				<td>
					<textarea name="txa_comentarios" id="txa_comentarios" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
					rows="2" cols="40"	onkeypress="return permite(event,'num_car', 0);" readonly="readonly"></textarea>				</td>
			</tr>
	  		<tr> 
				<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  		</tr>
	  		<tr>
				<td colspan="6"><div align="center">
					<input type="hidden" name="cmb_examen" id="cmb_examen"/>
					<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar el Registro del Examen Medico" 
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
					onmouseover="window.status='';return true" onclick="restablecerExamenMedico(this.value)" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					title="Regresar para Seleccionar otra Opción" onmouseover="window.status='';return true" onclick="confirmarSalida('menu_catalogos.php')" />
					&nbsp;&nbsp;&nbsp;					
					<?php //Si no existen datos dentro de la BD, entonces que el boton de exportar a excel NO se muestre
					if($result==0){?>
						<input type="button" class="botones_largos" value="Exportar a Excel" name="btn_exportar" id="btn_exportar" 
						title="Exportar a Excel los Registros de los Exámenes Medicos"
						onclick="location.href='guardar_reporte.php?&tipoRep=catalogoExaMed'" disabled="disabled"/>
					<?php }else{ ?>
					<input type="button" class="botones_largos" value="Exportar a Excel" name="btn_exportar2" id="btn_exportar2" 
						title="Exportar a Excel los Registros de las  Exámenes Medicos"
						onclick="location.href='guardar_reporte.php?&tipoRep=catalogoExaMed'" />
					<?php } ?>
				</div></td>
			</tr>
	  </table>
	</form>
</fieldset>	

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
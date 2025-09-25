<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Manejo de la funciones para Registrar los datos del Aspirante en la BD 
		include ("op_modificarAspirante.php");?>
				
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute; left:30px; top:146px; width:247px; height:20px; z-index:11; }
		#tabla-buscarAspirante { position:absolute; left:30px; top:190px; width:906px; height:81px; z-index:13; padding:15px; padding-top:0px;}
		#tabla-modificarAspirante { position:absolute; left:30px; top:299px; width:908px; height:389px; z-index:12; padding:15px; padding-top:0px;}
		#calendario {position:absolute; left:800px; top:341px; width:30px; height:26px; z-index:13;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Modificar Aspirantes a Empleo</div>	
	
	<fieldset class="borde_seccion" id="tabla-buscarAspirante">
	<legend class="titulo_etiqueta">Buscar Aspirante por Nombre</legend>	
	<br>		
	<form name="frm_buscarAspirante" method="post" action="frm_modificarAspirante.php">
	<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr valign="top">			
	  	  	<td width="25%"><div align="right">Nombre del Aspirante</div></td>
			<td width="50%" align="left">
				<input type="text" name="txt_nombreAspirante" id="txt_nombreAspirante"
				 onkeyup="lookup(this,'bolsa_trabajo','','1'); 
				 	if(txt_nombreAspirante.value!='') sbt_consultar.style.visibility = 'visible'; 
						else sbt_consultar.style.visibility = 'hidden' "  
				value="" size="55" maxlength="80"/>
				
		  	  	<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
					<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
					<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
				</div>	
			</td>
			<td width="12%" align="center">
				<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" 
				title="Consultar Informaci&oacute;n del Aspirante Seleccionado"  onmouseover="window.status='';return true" value="Consultar" style="visibility:hidden" />	
			</td>
			<td width="12%" align="center">
				<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Bolsa de Trabajo"
				onclick="location.href='menu_bolsaTrabajo.php'" />			
			</td>
		</tr>
	</table>    
	</form>    			 		
	</fieldset>		
		
	<fieldset class="borde_seccion" id="tabla-modificarAspirante">
	<legend class="titulo_etiqueta">Modificar los Datos del Aspirante Seleccionado</legend>	
	<br>

	<form onSubmit="return valFormModificarAspirante(this);" name="frm_modificarAspirante" method="post" action="frm_modificarAspirante.php" >
	<table cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="138"><div align="right">*Folio Aspirante </div></td>
			<!--Dentro de este campo se manda llamar la función obtenerFolioAspirante  para que este campo en el formulario despliegue 
				el folio en orden consecutivo y de acuerdo al mes en el que se esta registrando el aspirante  -->
			<td width="237">
				<input name="txt_folioAspirante" id="txt_folioAspirante" type="text" class="caja_de_texto" size="10" maxlength="10" onkeypress="return permite(event,'num_car', 3);" 
				readonly="readonly" value="<?php echo $txt_folioAspirante; ?>" />			
			</td>
			<td width="258"><div align="right">Fecha de Solicitud </div></td>
			<td width="237">
				<input type="text" name="txt_fechaSolicitud" id="txt_fechaSolicitud" size="10" maxlength="10" class="caja_de_texto" 
				readonly="readonly" value="<?php echo $txt_fechaSolicitud; ?>"/>			
			</td>
		</tr>
		<tr>
			<td><div align="right">*Nombre</div></td>
			<td>
				<input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" 
				value="<?php echo $txt_nombre; ?>" />			
			</td>
			<td><div align="right">*Estado Civil </div></td>
			<td>
				<input name="txt_edoCivil" id="txt_edoCivil" type="text" class="caja_de_texto" size="15" maxlength="15" 
				onkeypress="return permite(event,'car',0);" value="<?php echo $txt_edoCivil; ?>" />		
			</td>
		</tr>
		<tr>
			<td><div align="right">*Apellido Paterno</div></td>
			<td>
				<input name="txt_apePat" id="txt_apePat" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" 
				value="<?php echo $txt_apePat; ?>" />			
			</td>
			<td><div align="right">*Lugar de Nacimiento </div></td>
			<td>
				<input type="text" name="txt_lugarNac" id="txt_lugarNac" size="20" maxlength="20" onkeypress="return permite(event,'num_car',1);" 
				class="caja_de_texto" value="<?php echo $txt_lugarNac; ?>" />			
			</td>
		</tr>
		<tr>
			<td><div align="right">*Apellido Materno </div></td>
			<td>
				<input name="txt_apeMat" id="txt_apeMat" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" 
				value="<?php echo $txt_apeMat; ?>" />			
			</td>
			<td><div align="right">*Nacionalidad</div></td>
			<td>
				<input name="txt_nacionalidad" id="txt_nacionalidad" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'car',0);" 
				value="<?php echo $txt_nacionalidad; ?>" />			
			</td>
		</tr>
		<tr>
			<td><div align="right">*CURP</div></td>
			<td>
				<input name="txt_curp" id="txt_curp" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',3);" 
				value="<?php echo $txt_curp; ?>" />			
			</td>
			<td><div align="right">Telefono </div></td>
			<td>
				<input name="txt_tel" id="txt_tel" type="text" class="caja_de_texto" size="10" maxlength="15" onkeypress="return permite(event,'num',3);" onblur="validarTelefono(this);" 
				value="<?php echo $txt_tel; ?>" />			
			</td>
		</tr>
		<tr>
			<td><div align="right">*Edad</div></td>
			<td>
				<input name="txt_edad" type="text" class="caja_de_texto" id="txt_edad" onkeypress="return permite(event,'num',3);" size="2" maxlength="2" value="<?php echo $txt_edad; ?>" />	
			</td>
			<td><div align="right">Telefono de Referencia </div></td>
			<td>
				<input name="txt_telRef" id="txt_telRef" type="text" class="caja_de_texto" size="10" maxlength="15" onkeypress="return permite(event,'num',3);"  onblur="validarTelefono(this);" 
				value="<?php echo $txt_telRef; ?>" />
			</td>	
		</tr>
		<tr>
			<td><div align="right">Experiencia Laboral </div></td>
			<td>
				<textarea name="txa_experiencia" id="txa_experiencia" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="3" cols="40"
				onkeypress="return permite(event,'num_car', 0);" ><?php echo $txa_experiencia; ?></textarea>			
			</td>
			<td><div align="right">Observaciones </div></td>
			<td>
				<textarea name="txa_observaciones" id="txa_observaciones" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="3" cols="40"
				onkeypress="return permite(event,'num_car', 0);" ><?php echo $txa_observaciones; ?></textarea>			
			</td>
		</tr>
		<tr>
		   <td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		   <td colspan="2" class="msje_correcto"><?php echo $msj_resultados;?></td>
		</tr>
		<tr>
			<td colspan="4">
				<div align="center"><?php 
					if($txt_folioAspirante!=""){ ?>
						<input name="sbt_modificarAspirante" type="submit" class="botones" id="sbt_modificarAspirante" title="Modificar los Datos Personales del Aspirante a Empleo" 
						onMouseOver="window.status='';return true"  value="Modificar"   />
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_modificarAreaPuesto" type="submit"  class="botones_largos" id="sbt_modificarAreaPuesto" title="Modificar el Área y Puesto recomendados" 
						onmouseover="window.status='';return true"  value="Modificar &Aacute;rea y Puesto" onclick="location.href='frm_modificarPuestoAspirante.php'" />
						&nbsp;&nbsp;&nbsp;
						<input name="rst_restablecer" type="reset" class="botones"  value="Restablecer" title="Restablece el Formulario" onMouseOver="window.status='';return true"/> 
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Bolsa de Trabajo" 
						onMouseOver="window.status='';return true" onclick="confirmarSalida('menu_bolsaTrabajo.php');" /><?php 	 
					} ?>
				</div>			
			</td>
		</tr>
	</table>
	</form>
</fieldset>

<div id="calendario">
	<input type="image" name="fechaSolicitud" id="fechaSolicitud" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_modificarAspirante.txt_fechaSolicitud,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" title="Seleccionar la fecha en la que se Registro el Aspirante para ser Modificada"/>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		include ("op_registrarAspirante.php");	
		/*Determinar que Teclas Especiales seran Permitidas segun el campo de texto que se este llenando
			0 = Campos mas generales como comentarios, observaciones y nombres		
			1 =  Campos que contengan claves que puedan contener guion medio, punto o diagonal
			2 = Para cajas de texto que contengan valores tipo moneda, solo acepta numeros y el punto
			3 = Campo RFC, numero telefónico, solo acepta numeros o letras o ambos, no permite ningun caracter especial
			4 = Campos que se utilizan para manejar la Busqueda Sphider, Razon Social del Cliente y del Proveedor y el campo de Material o Servicio del Proveedor
		*/
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute; left:30px; top:146px; width:247px; height:20px; z-index:11; }
		#tabla-registrarAspirante { position:absolute; left:30px; top:190px; width:908px; height:403px; z-index:12; padding:15px; padding-top:0px;}
		#calendario {position:absolute; left:799px; top:232px; width:30px; height:26px; z-index:13;}
		-->
    </style>
</head>
<body>	
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar Aspirantes a Empleo</div>
	
	<?php //Dejar de mostrar el Formulario cuando se pasa a registrar la Informacion complementaria del Aspirantre y cuando se da click al boton de Guardar  
		if($ctrl_vistaForm==0){?>
		<fieldset class="borde_seccion" id="tabla-registrarAspirante">
		<legend class="titulo_etiqueta">Registrar Aspirante</legend>	
		<br>
		<!--En  la propiedad action=""  de este formulario debera contener el nombre del formulario   -->
		<form onSubmit="return valFormRegistrarAspirante(this);" name="frm_registrarAspirante" method="post" action="frm_registrarAspirante.php" >
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="138"><div align="right">*Folio Aspirante </div></td>
				<!--Dentro de este campo se manda llamar la función obtenerFolioAspirante  para que este campo en el formulario despliegue 
					el folio en orden consecutivo y de acuerdo al mes en el que se esta registrando el aspirante  -->
				<td width="237">
					<input name="txt_folioAspirante" id="txt_folioAspirante" type="text" class="caja_de_texto" size="10" maxlength="10" onkeypress="return permite(event,'num_car', 3);" 
					readonly="readonly" value="<?php echo obtenerFolioAspirante();?>" />				</td>
				<td width="258"><div align="right">Fecha de Solicitud </div></td>
				<td width="237">
			  		<input type="text" name="txt_fechaSolicitud" id="txt_fechaSolicitud" size="10" maxlength="10" class="caja_de_texto" 
					readonly="readonly" value="<?php echo date("d/m/Y"); ?>"/>				</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre</div></td>
				<td>
					<input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" 
					value="<?php echo $txt_nombre; ?>" />				</td>
				<td><div align="right">*Estado Civil </div></td>
				<td>
					<input name="txt_edoCivil" id="txt_edoCivil" type="text" class="caja_de_texto" size="15" maxlength="15" 
					onkeypress="return permite(event,'car',0);" value="<?php echo $txt_edoCivil; ?>" />				</td>
			</tr>
			<tr>
				<td><div align="right">*Apellido Paterno</div></td>
				<td>
					<input name="txt_apePat" id="txt_apePat" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" 
					value="<?php echo $txt_apePat; ?>" />				</td>
				<td><div align="right">*Lugar de Nacimiento </div></td>
				<td>
					<input type="text" name="txt_lugarNac" id="txt_lugarNac" size="20" maxlength="20" onkeypress="return permite(event,'num_car',1);" 
					class="caja_de_texto" value="<?php echo $txt_lugarNac; ?>" />				</td>
			</tr>
			<tr>
				<td><div align="right">*Apellido Materno </div></td>
				<td>
					<input name="txt_apeMat" id="txt_apeMat" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" 
					value="<?php echo $txt_apeMat; ?>" />				</td>
				<td><div align="right">*Nacionalidad</div></td>
				<td>
					<input name="txt_nacionalidad" id="txt_nacionalidad" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'car',0);" 
					value="<?php echo $txt_nacionalidad; ?>" />				</td>
			</tr>
			<tr>
				<td><div align="right">*CURP</div></td>
				<td>
					<input name="txt_curp" id="txt_curp" type="text" class="caja_de_texto" size="18" maxlength="18" onkeypress="return permite(event,'num_car',3);" 
					value="<?php echo $txt_curp; ?>" />				</td>
				<td><div align="right">Telefono </div></td>
				<td>
					<input name="txt_tel" id="txt_tel" type="text" class="caja_de_texto" size="15" maxlength="15" onkeypress="return permite(event,'num',3);" onblur="validarTelefono(this);" 
					value="<?php echo $txt_tel; ?>" />				</td>
			</tr>
			<tr>
				<td><div align="right">*Edad</div></td>
				<td>
					<input name="txt_edad" type="text" class="caja_de_texto" id="txt_edad" onkeypress="return permite(event,'num',3);" size="2" maxlength="3" 
					value="<?php echo $txt_edad; ?>" />				</td>
				<td><div align="right">Telefono de Referencia </div></td>
				<td><input name="txt_telRef" id="txt_telRef" type="text" class="caja_de_texto" size="15" maxlength="15" onkeypress="return permite(event,'num',3);" 
					onblur="validarTelefono(this);" value="<?php echo $txt_telRef; ?>" /></td>	
			</tr>
			<tr>
				<td><div align="right">Experiencia Laboral </div></td>
				<td>
					<textarea name="txa_experiencia" id="txa_experiencia" onkeyup="return ismaxlength(this)" maxlength="3000" class="caja_de_texto" rows="3" cols="40"
					onkeypress="return permite(event,'num_car', 0);" ><?php echo $txa_experiencia; ?></textarea>				</td>
				<td><div align="right">Observaciones </div></td>
				<td>
					<textarea name="txa_observaciones" id="txa_observaciones" onkeyup="return ismaxlength(this)" maxlength="120" class="caja_de_texto" rows="3" cols="40"
					onkeypress="return permite(event,'num_car', 0);" ><?php echo $txa_observaciones; ?></textarea>				</td>
			</tr>
			<tr>
			   <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td colspan="4">
					<div align="center">
					<?php /*Verificar cuando el boton este declarado o definido*/?>
					<?php if(isset($_SESSION['datosContactoAspirante'])) { ?>
						<input name="sbt_guardarAspirante" type="submit" class="botones"  value="Guardar" title="Agregar los Datos Personales del Aspirante a Empleo" 
						onMouseOver="window.status='';return true" />

					<?php } else { ?>
						<?php // Si este boton se encuentra definido direccionara a la pantalla donde se registraran el Área y Puesto Recomendado ?>
						&nbsp;&nbsp;&nbsp;						
						<input name="sbt_registrarAreaPuesto" type="submit" class="botones_largos" id="sbt_puesto" title="Agregar los Puestos Recomendados para el Aspirante" 
						onMouseOver="window.status='';return true"  value="Registrar &Aacute;rea y Puesto" />
					<?php } ?>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/> 
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Bolsa de Trabajo" 
					onMouseOver="window.status='';return true" onclick="confirmarSalida('menu_bolsaTrabajo.php');" />
					</div>				
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
	
		<div id="calendario">
			<input type="image" name="fechaSolicitud" id="fechaSolicitud" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_registrarAspirante.txt_fechaSolicitud,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" title="Seleccionar la fecha en la que se Registro el Aspirante"/>
</div>
	<?php }//Cierre if($ctrl_vistaForm==0) ?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
			//Enviar a la pagina de acceso negado
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//ESter archivo se incluye porque es donde se guarda el registro del permiso generado, para posteriormente guardarlo y mostrarlo en formato excel
		include ("op_seleccionarPermiso.php");
		
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-generado { position:absolute; left:30px; top:146px; width:185px; height:20px; z-index:11; }
		#tabla-permisoGenerado { position:absolute; left:30px; top:190px; width:945px; height:408px; z-index:12; padding:15px; padding-top:0px;}
		#btn_per { position:absolute; left:7px; top:380px; width:945px; height:23px; z-index:12; padding:15px; padding-top:0px;}

    </style>
</head>
<body>
	<?php 
	//verificar que este definido el ID del tipo de reporte a mostrar
	if (isset($_GET["id_perAlt"])){
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente, es decir las variables que vienen en la url
		$idPerAltura = $_GET["id_perAlt"];
		//Se conecta a la Base de Seguridad para obtener los datosPermiso que se han agregado recientemente
		$conn=conecta("bd_seguridad");
		//Sentencia SQL para obtener los datosPermiso segun el ID del Permiso      AND id_permiso_secundario = '$tipoTrabajo'
		$stm_sql = "SELECT id_permiso_trab, tipo_permiso, lugar_trabajo, riesgos_trabajo, nom_solicitante, nom_supervisor, 
			nom_responsable, descripcion_trabajo, trabajo_realizar, fecha_ini FROM permisos_trabajos WHERE id_permiso_trab = '$idPerAltura'";
			
		$stm_sqlCS = "SELECT num_actividad, respuesta, actividad, nom_permiso FROM revision_cs  JOIN permisos_secundarios 
			ON permisos_secundarios_id_permiso_secundario = id_permiso_secundario WHERE permisos_trabajos_id_permiso_trab = '$idPerAltura'";
								
		//Ejecutar la sentencia previamente creada
		$rs=mysql_query($stm_sql);
		//Ejecutar la sentencia previamente creada
		$rs_cs=mysql_query($stm_sqlCS);
		
		//Pasamos el resultado de la consulta a un arreglo de datosPermiso
		$datosPA=mysql_fetch_array($rs);
		$datosPA2=mysql_fetch_array($rs_cs);?><?php 
		
		//Verificamos si viene definido en el post el boton guardar
		if(isset($_POST["sbt_continuar"])){
			//Si viene definido el boton; mostrar el Permiso Peligroso generaod
			reportePermisoPeligroso();					
		}?> 
		     
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-generado">Permiso Generado</div>

		<fieldset class="borde_seccion" id="tabla-permisoGenerado" name="tabla-permisoGenerado">
		<legend class="titulo_etiqueta">Informacion del Permiso de Alturas Generado</legend>
		<form name="frm_permisoTrabAlturas2" method="post"  id="frm_permisoTrabAlturas2" >
		<br />	
		<table width="100%" cellpadding="5" cellspacing="2" class="tabla_frm">
			<tr>
			  <td width="17%"><div align="right">Clave Permiso</div></td>
				<td width="10%"><input type="text" name="txt_idPermisoPel" id="txt_idPermisoPel" maxlength="10" size="10" class="caja_de_texto" 
						value="<?php echo $datosPA["id_permiso_trab"]; ?>" readonly="readonly"/>				</td>
				<td width="18%"><div align="right">Tipo Permiso</div></td>
				<td width="15%"><input name="txt_tipoPermiso" type="text" class="caja_de_texto" id="txt_tipoPermiso" 
					onkeypress="return permite(event,'num',1);" value="<?php echo $datosPA["tipo_permiso"]; ?>" size="25" readonly="readonly"/></td>
				<td width="12%"><div align="right">Fecha</div></td>
				<td width="28%"><input name="txt_fechaReg" id="txt_fechaReg" readonly="readonly" type="text" size="10"  width="90" 
					value="<?php echo date("d/m/Y"); ?>" /></td>
			</tr>
			<tr>
				<td><div align="right">* Nombre  Quien Realiza  Trabajo </div></td>
				<td colspan="2"><input type="text" name="txt_nomTrabajador" id="txt_nomTrabajador" maxlength="80" size="40" class="caja_de_texto" 
					value="<?php echo $datosPA["nom_solicitante"]; ?>" onkeypress="return permite(event,'num_car',0);" readonly="readonly"/></td>
				<td><div align="right">*Nombre Autoriza Trabajo</div></td>
				<td colspan="2"><input type="text" name="txt_nomAutoriza" id="txt_nomAutoriza" maxlength="100" size="40" class="caja_de_texto" 
					value="<?php echo $datosPA["nom_responsable"]; ?>" onkeypress="return permite(event,'num_car',0);" readonly="readonly" /></td>
			</tr>
			<tr>
				<td><div align="right">*Nombre L&iacute;der  &Aacute;rea Operativa </div></td>
				<td colspan="2"><input type="text" name="txt_liderOper" id="txt_liderOper" maxlength="100" size="40" class="caja_de_texto" 
					value="<?php echo $datosPA["nom_supervisor"]; ?>" onkeypress="return permite(event,'num_car',0);" readonly="readonly"/></td>
				<td><div align="right">*Trabajo a Realizar</div></td>
				<td colspan="2"><textarea name="txa_trabRealizar" cols="35" rows="3" class="caja_de_texto" id="txa_trabRealizar"  
					onkeypress="return permite(event,'num_car',0);" readonly="readonly"><?php echo $datosPA["trabajo_realizar"]; ?></textarea></td>
			</tr>
			<tr>
				<td><div align="right">*Lugar</div></td>
				<td colspan="2"><input type="text" name="txt_lugar" id="txt_lugar" maxlength="70" size="40" class="caja_de_texto" 
					value="<?php echo $datosPA["lugar_trabajo"]; ?>" onkeypress="return permite(event,'num_car',0);" readonly="readonly"/></td>
				<td><div align="right">*Descripci&oacute;n Trabajo:<br />
				    (Caida en altura, atrapado por y golpeado por:)</div></td>
				<td colspan="2"><textarea name="txa_desTrabajo" cols="45" rows="4" class="caja_de_texto" id="txa_desTrabajo"  
					onkeypress="return permite(event,'num_car',0);" readonly="readonly"><?php echo $datosPA["descripcion_trabajo"]; ?></textarea></td>
			</tr>
			<tr>
				<td colspan="6"><div align="center">
					<strong>*¿CU&Aacute;LES SON LOS RIESGOS QUE EL COLABORADOR VA ENCONTRAR EN EL DESARROLLO DE SU TRABAJO Y COMO EVITARLOS?</strong><br />
					Caída a Desnivel y Golpeado Por:</div></td>			
			</tr>
			<tr>
				<td align="center" colspan="6"><textarea  name="txa_riesgosTrab" cols="125" rows="3" class="caja_de_texto" id="txa_riesgosTrab"  
					onkeypress="return permite(event,'num_car',0);" readonly="readonly"><?php echo $datosPA["riesgos_trabajo"]; ?></textarea></td>
			</tr>
	  		<tr> 
				<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  		</tr>
	  </table>
	</form>  
<div align="center" id="btn_per" >
	<form action="guardar_reportePermisos.php" method="post">
		<input name="sbt_exportar" type="submit" class="botones_largos" id="sbt_exportar"  value="Exportar a Excel" title="Generar el Permiso en Formato Excel" 
			onmouseover="window.status='';return true" onclick="location.href='guardar_reportePermisos.php'" />				
		<input type="hidden" name="hdn_nomReporte" value="Reporte de Permisos" />
		<input type="hidden" name="hdn_origen" value="reportePermisoAlturas" />
		<input type="hidden" id="hdn_consulta" name="hdn_consulta" value="<?php echo $stm_sql; ?>" />	
		<input type="hidden" id="hdn_consulta2" name="hdn_consulta2" value="<?php echo $stm_sqlCS; ?>" />	
		  <input name="btn_regresar" type="button" class="botones" value="Regresar" 
			title="Regresar para Generar otro Tipo de Permiso" onmouseover="window.status='';return true" onclick="location.href='frm_seleccionarPermiso.php';"  />
  </form>
</div>
	
</fieldset>
<?php 
	//Cerramos la conexion con la Base de datosPermiso
	mysql_close($conn);
	}//Fin del IF que comprueba si en el GET viene definida la clave del Permiso Seleccionado
	else{
		//Si no esta definido el GET, se llego a esta pantalla de otra manera, en dado caso cerrar la sesion
		echo "<meta http-equiv='refresh' content='0;url=../salir.php'>";
	}?>
			
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
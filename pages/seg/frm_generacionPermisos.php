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
		#tabla-permisoGenerado { position:absolute; left:27px; top:176px; width:945px; height:448px; z-index:12; padding:15px; padding-top:0px;}
		
    </style>
</head>
<body>
	<?php 
	//verificar que este definido el ID del tipo de reporte a mostrar
	if (isset($_GET["id_perPel"])){
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente, es decir las variables que vienen en la url
		$idPermisoPel = $_GET["id_perPel"];
		//Se conecta a la Base de Seguridad para obtener los datosPermiso que se han agregado recientemente
		$conn=conecta("bd_seguridad");
		//Sentencia SQL para obtener los datosPermiso segun el ID del Permiso      AND id_permiso_secundario = '$tipoTrabajo'
		
		$stm_sql = "SELECT id_permiso_trab, permisos_secundarios_id_permiso_secundario, nom_permiso, nom_solicitante, id_permiso_secundario,
				nom_supervisor, nom_responsable, nom_contratista, descripcion_trabajo, trabajo_realizar, fecha_ini, fecha_fin, horario_ini, meridiano_ini,
				horario_fin, meridiano_fin, trabajo_especifico, firma_responsable, funcionario_res, supervisor, supervisor_obra, operador, aceptacion, 
				fecha_expiracion, hora_expiracion
				FROM permisos_trabajos JOIN permisos_secundarios ON permisos_secundarios_id_permiso_secundario = id_permiso_secundario 
				WHERE id_permiso_trab = '$idPermisoPel'";
								
		$stm_sql2 = "SELECT  pasos_permiso.permisos_secundarios_id_permiso_secundario, actividad
						FROM permisos_trabajos JOIN pasos_permiso 
						ON pasos_permiso.permisos_secundarios_id_permiso_secundario = permisos_trabajos.permisos_secundarios_id_permiso_secundario
						WHERE id_permiso_trab = '$idPermisoPel'";		

		//Ejecutar la sentencia previamente creada
		$rs=mysql_query($stm_sql);
		$rs2 = mysql_query($stm_sql2);
		
		//Pasamos el resultado de la consulta a un arreglo de datosPermiso
		$datosPermiso=mysql_fetch_array($rs);?><?php 
		$datosPermiso2 = mysql_fetch_array($rs2);
		
		//Verificamos si viene definido en el post el boton guardar
		if(isset($_POST["sbt_guardar"])){
			//Si viene definido el boton; mostrar el Permiso Peligroso generaod
			reportePermisoPeligroso();					
			}?>   
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-generado">Permiso Generado</div>

		<fieldset class="borde_seccion" id="tabla-permisoGenerado" name="tabla-permisoGenerado">
		<legend class="titulo_etiqueta">Informacion del Permiso Peligroso Generado</legend>
		<form name="frm_permisoTrabPeligroso" method="post"  id="frm_permisoTrabPeligroso" >
		<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
			<tr>
				<td width="12%"><div align="right">Clave Permiso</div></td>
				<td width="23%"><input type="text" name="txt_idPermisoPel" id="txt_idPermisoPel" maxlength="10" size="10" class="caja_de_texto" 
						value="<?php echo $datosPermiso["id_permiso_trab"]; ?>" readonly="readonly"/>				</td>
				<td width="11%"><div align="right">Tipo Permiso</div></td>
				<td width="24%"><input name="txt_tipoPermiso" type="text" class="caja_de_texto" id="txt_tipoPermiso" size="25" readonly="readonly" 
				 value="PERMISOS PELIGROSOS"  />				</td>
				<td width="10%"><div align="right">* Solicitante</div></td>
				<td width="20%"><input type="text" name="txt_nomSolicitante" id="txt_nomSolicitante" maxlength="80" size="30" class="caja_de_texto" 
						value="<?php echo $datosPermiso["nom_solicitante"]; ?>" readonly="readonly"/></td>
			</tr>
			<tr>
				<td><div align="right">* Supervisor</div></td>
				<td><input type="text" name="txt_nomSupervisor" id="txt_nomSupervisor" maxlength="100" size="30" class="caja_de_texto" 
					value="<?php echo $datosPermiso["nom_supervisor"]; ?>"  readonly="readonly"/></td>
				<td><div align="right">* Responsable</div></td>
				<td><input type="text" name="txt_nomResponsable" id="txt_nomResponsable" maxlength="100" size="30" class="caja_de_texto" 
					value="<?php echo $datosPermiso["nom_responsable"]; ?>" onkeypress="return permite(event,'num_car',0);" readonly="readonly" /></td>
				<td><div align="right">* Contratista</div></td>
				<td><input type="text" name="txt_nomContratista" id="txt_nomContratista" maxlength="100" size="30" class="caja_de_texto" 
					value="<?php echo $datosPermiso["nom_contratista"]; ?>"  readonly="readonly" /></td>
			</tr>
			<tr>
				<td><div align="right">*Encargado Trabajo</div></td>
				<td><input type="text" name="txt_encargadoTrab" id="txt_encargadoTrab" maxlength="60" size="30" class="caja_de_texto" 
					value="<?php echo $datosPermiso["nom_contratista"]; ?>" readonly="readonly"/></td>
				<td><div align="right">*Operador</div></td>
			  <td><input type="text" name="txt_operador" id="txt_operador" maxlength="60" size="30" class="caja_de_texto" 
					value="<?php echo $datosPermiso["operador"]; ?>" readonly="readonly"/></td>
				<td><div align="right">*Funcionario Reponsable</div></td>
				<td><input type="text" name="txt_funResponsable" id="txt_funResponsable" maxlength="60" size="30" class="caja_de_texto" 
							value="<?php echo $datosPermiso["funcionario_res"]; ?>" readonly="readonly"/></td>
			</tr>
			<tr>
				<td><div align="right">*Supervisor Obra </div></td>
				<td><input type="text" name="txt_supervisorObra" id="txt_supervisorObra" maxlength="60" size="30" class="caja_de_texto" 
					value="<?php echo $datosPermiso["supervisor"]; ?>" readonly="readonly"/></td>
				<td><div align="right">*Supervisor</div></td>
				<td><input type="text" name="txt_supervisor" id="txt_supervisor" maxlength="60" size="30" class="caja_de_texto" 
					value="<?php echo $datosPermiso["supervisor_obra"]; ?>"readonly="readonly"/></td>
				<td><div align="right">*Aceptaci&oacute;n</div></td>
				<td><input type="text" name="txt_aceptacion" id="txt_aceptacion" maxlength="60" size="30" class="caja_de_texto" 
					value="<?php echo $datosPermiso["aceptacion"]; ?>" readonly="readonly"/></td>
			</tr>
				<td><div align="right">*Descripci&oacute;n Trabajo</div></td>
				  <td><textarea name="txa_desTrabajo" cols="36" rows="5" class="caja_de_texto" id="txa_desTrabajo" readonly="readonly" ><?php echo $datosPermiso["descripcion_trabajo"]; ?></textarea></td>
				  <td><div align="right">*Trabajo a Realizar</div></td>
				<td><textarea name="txa_trabRealizar" cols="34" rows="3" class="caja_de_texto" id="txa_trabRealizar"  
					 readonly="readonly"><?php echo $datosPermiso["trabajo_realizar"]; ?></textarea></td>
				<td><div align="right">*Trabajo Especifico</div></td>
				<td><textarea name="txa_trabEspecifico" cols="30" rows="4" class="caja_de_texto" id="txa_trabEspecifico"  
					 readonly="readonly"><?php echo $datosPermiso["trabajo_especifico"]; ?></textarea></td>
			</tr>
			<tr>
				<td><div align="right">*Tipo Trabajo</div></td>
				<td><?php 
						switch($datosPermiso['id_permiso_secundario']){
							case 'PTC001':
								echo "<strong><u>TRABAJOS EN ESPACIOS CONFINADOS</u></strong>";
							break;
							case 'PTE002':
								echo "<strong><u>TRABAJOS EL&Eacute;TRICOS</u></strong>";
							break;
							case 'PTM003':
								echo "<strong><u>MANIOBRA INDUSTRIAL</u></strong>";
							break;
					}?></td>
				<td><div align="right">*Hora Inicio</div><br />
				<div align="right">*Periodo Inicio</div></td><br />
			  	<td><input name="txt_horaIni"  type="text" id="txt_horaIni"  value="<?php echo $datosPermiso["horario_ini"]; ?>" size="5" maxlength="5"  readonly="readonly" />
				  <input name="txt_meridiano1" id="txt_meridiano1"  type="text" size="5"  readonly="readonly"  value="<?php echo $datosPermiso["meridiano_ini"]; ?>" />
				&nbsp;&nbsp;&nbsp;&nbsp;<br /><br />
				<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" size="10"  width="90" 
				value="<?php echo modFecha($datosPermiso["fecha_ini"],1); ?>" />			  </td><br />
				<td><div align="right">*Hora Fin</div>
				<br />
				<div align="right">*Periodo Fin</div></td>
				<br />
				<td width="20%"><input name="txt_horaFin"  type="text" id="txt_horaFin" 
				value="<?php echo $datosPermiso["horario_fin"]; ?>"  size="5" maxlength="5"  readonly="readonly"/>
				<input name="txt_meridiano2" id="txt_meridiano2"  type="text" size="5"  readonly="readonly"  value="<?php echo $datosPermiso["meridiano_fin"]; ?>" />
				<br/>
				<br/>
				<input name="txt_fechaFin" id="txt_fechaFin" readonly="readonly" type="text"  size="10"  width="90" 
				value="<?php echo modFecha($datosPermiso["fecha_fin"],1); ?>" /></td>
			</tr>
	  		<tr> 
				<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  		</tr>
	  </table>
	</form>
		  
<div align="center" >
	<form action="guardar_reportePermisos.php" method="post">
		<input name="sbt_exportar" type="submit" class="botones_largos" id="sbt_exportar"  value="Exportar a Excel" title="Generar el Permiso en Formato Excel" 
			onmouseover="window.status='';return true" onclick="location.href='guardar_reportePermisos.php'" />				
		<input type="hidden" name="hdn_nomReporte" value="Reporte de Permisos" />
		<input type="hidden" name="hdn_origen" value="reportePermisoPeligroso" />
		<input type="hidden" id="hdn_consulta" name="hdn_consulta" value="<?php echo $stm_sql; ?>" />	
		<input type="hidden" id="hdn_consulta2" name="hdn_consulta2" value="<?php echo $stm_sql2; ?>"/> 					
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
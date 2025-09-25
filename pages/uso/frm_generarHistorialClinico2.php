<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_generarHistorialClinico.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>

	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:298px;height:20px;z-index:11;}
		#calendario{position:absolute;left:557px;top:239px;width:30px;height:26px;z-index:13;}		
		#calendario-nac{position:absolute;left:554px;top:342px;width:30px;height:26px;z-index:13;}		
		#tabla-clasificacion{ position:absolute; left:26px; top:190px; width:854px;	height:178px; z-index:12; }
		#res-spider {position:absolute;z-index:20;}
		#tabla-complementar-historial{ position:absolute; left:26px; top:190px; width:953px;	height:416px; z-index:13; }
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:25; }
		-->
    </style>
</head>
<body>
	<?php if(isset($_POST['sbt_guardar'])){?>
	<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div><?php 
		registrarHistorialClinico();
	}
	if(!isset($_POST['sbt_guardar'])){
		eliminarRegistroCancelado();
	}

	
	if(isset($_GET["rdb_examen"])){
		//Obtener el Id del Historial Clinico
		$claveEmpleado = $_GET["rdb_examen"];
		$nomEmpleado = obtenerDato("bd_clinica","historial_clinico","nom_empleado","id_empleados_empresa",$claveEmpleado);
		$idHistorial = obtenerIdHistorialClinico();
		
		//Se conecta a la Base de la clinica para obtener los datosPermiso que se han agregado recientemente
		$conn=conecta("bd_clinica");
		//Sentencia SQL para obtener los datos del historial segun la clave del mismo
		$stm_sql = "SELECT DISTINCT clasificacion_exa, tipo_clasificacion, puesto_realizar, num_afiliacion, nom_empleado, id_empleados_empresa, sexo, edad, 
		reside_en, originario_de, edo_civil, domicilio, telefono, fecha_nac, escolaridad, clave_escolaridad, nom_empresa, razon_social, nom_dr FROM historial_clinico 
		WHERE nom_empleado = '$nomEmpleado'";
		//Ejecutar la sentencia previamente creada
		$rs=mysql_query($stm_sql);
		
		//Pasamos el resultado de la consulta a un arreglo de datosHC
		$datosHC=mysql_fetch_array($rs);?>				
		
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-registrar">Generar Historial Clinico  </div>	
	
	<?php  if(!isset($_POST["sbt_continuar"]) && !isset($_POST["sbt_guardar"]) ){?>
	<fieldset class="borde_seccion" id="tabla-complementar-historial" name="tabla-complementar-historial">
	<legend class="titulo_etiqueta">Ingresar Informaci&oacute;n General del Historial Clinico </legend>	
	<form  onsubmit="return valFormRegHistorialClinico(this);"name="frm_historialClinico" id="frm_historialClinico" method="post" action="">
		<table width="101%" cellpadding="3" cellspacing="3" class="tabla_frm">
			<tr>
				<td width="9%"><div align="right">Historial</div></td>
				<td width="23%">
					<input type="text" name="txt_idHistorial" id="txt_idHistorial" value="<?php echo $idHistorial;?>" size="10" maxlength="10" 
					readonly="readonly" class="caja_de_texto"/>              
				</td>
				<td width="10%"><div align="right"> Examen</div></td>
				<td width="22%">
			  <input type="text" name="txt_clasExa" id="txt_clasExa" value="<?php echo $datosHC["clasificacion_exa"]; ?>" size="18" maxlength="15" readonly="readonly" 
			  	class="caja_de_texto"/>              	</td>
				<td width="10%"><div align="right">Tipo </div></td>
				<td width="26%">
					<input type="text" name="txt_tipoClas" id="txt_tipoClas" value="<?php echo $datosHC["tipo_clasificacion"];?>" size="10" maxlength="10" 
					readonly="readonly" class="caja_de_texto"/>              
			  </td>
			</tr>
			<tr>
				<td><div align="right">Afiliaci&oacute;n</div></td>
				<td>
					<input type="text" name="txt_numSS" id="txt_numSS" value="<?php echo $datosHC["num_afiliacion"]; ?>" size="15" maxlength="15"  class="caja_de_texto"/>              
				</td>
				<td><div align="right">Fecha</div></td>
				<td>
					<input name="txt_fechaReg" type="text" class="caja_de_texto" id="txt_fechaReg" value=<?php echo date("d/m/Y");?>
					size="10" maxlength="10"  readonly="readonly"/>
				</td>
				<td><div align="right">*Puesto</div></td>
				<td>
					<input name="txt_puesto" type="text" class="caja_de_texto" id="txt_puesto" onkeypress="return permite(event,'num_car',8);" 
					value="<?php echo $datosHC["puesto_realizar"];?>" size="25" maxlength="30"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre </div></td>
				<td>
					<input type="text" name="txt_nombre" id="txt_nombre" value="<?php echo $nomEmpleado;?>" size="40" maxlength="75" 
					onkeypress="return permite(event,'num_car',0);" class="caja_de_texto"/>
				</td>
				<td><div align="right">*Sexo</div></td>
				<td>
					<select name="cmb_sexo" class="combo_box" id="cmb_sexo" >
						<option value="" selected="selected">Sexo</option>
						<option value="F">F</option>
						<option value="M">M</option>
					</select>
				</td>
				<td><div align="right">*Edad</div></td>
				<td>
					<input name="txt_edad" type="text" class="caja_de_num" id="txt_edad" 
					onkeypress="return permite(event,'num',3);" value="<?php echo $datosHC["edad"]; ?>" size="2" maxlength="2" onchange="validarEdad(this);"/>
					N&deg; Empleado
					<input type="text" name="txt_numEmp" id="txt_numEmp" value="<?php echo $claveEmpleado;?>" size="5" maxlength="20" 
					onkeypress="return permite(event,'num',3);" class="caja_de_num"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Reside en:</div></td>
				<td>
					<input name="txt_reside" type="text" class="caja_de_texto" id="txt_reside" value="<?php echo $datosHC["reside_en"]; ?>" 
					size="40" maxlength="60" onkeypress="return permite(event,'num_car',0);"/>              	</td>
				<td><div align="right">*Originario</div></td>
				<td>
					<input type="text" name="txt_originario" id="txt_originario" value="<?php echo $datosHC["originario_de"]; ?>" 
					size="40" maxlength="75" onkeypress="return permite(event,'num_car',0);" 
					class="caja_de_texto"/>              
				</td>
				<td><div align="right">*Estado Civil</div></td>
				<td><?php 
						$edoCivil = obtenerDato("bd_clinica", "historial_clinico", "edo_civil", "id_empleados_empresa", $claveEmpleado);?> 
						<select name="cmb_edoCivil" class="combo_box" id="cmb_edoCivil">
							<option value=""<?php if($edoCivil=="")echo "selected='selected'"?>>Edo. civil</option>
							<option value="CASADO" <?php if($edoCivil=="CASADO")echo "selected='selected'"?>>CASADO</option>
							<option value="DIVORCIADO" <?php if($edoCivil=="DIVORCIADO")echo "selected='selected'"?>>DIVORCIADO</option>
							<option value="SOLTERO" <?php if($edoCivil=="SOLTERO")echo "selected='selected'"?>>SOLTERO</option>
							<option value="UNI&Oacute;N LIBRE" <?php if($edoCivil=="UNIÓN LIBRE")echo "selected='selected'"?>>UNI&Oacute;N LIBRE</option>												
							<option value="VIUDO" <?php if($edoCivil=="VIUDO")echo "selected='selected'"?>>VIUDO</option>							
						</select>       
				</td>
			</tr>
			<tr>
				<td><div align="right">*Domicilio</div></td>
				<td>
					<input name="txt_domicilio" type="text"class="caja_de_texto" id="txt_domicilio" onkeypress="return permite(event,'num_car',0);" 
					value="<?php echo $datosHC["domicilio"];?>" size="40" maxlength="120"/>              
				</td>
				<td><div align="right">Fecha Nacimiento </div></td>
				<td>
					<input name="txt_fechaNac" type="text" id="txt_fechaNac" value=<?php echo modFecha($datosHC["fecha_nac"],1);?> size="10"  readonly="readonly" 
					class="caja_de_texto"/> 
					<input type="hidden" name="hdn_fechaAct" id="hdn_fechaAct" value="<?php echo date("d/m/Y");?>"/>          
				</td>
				<td><div align="right">Telefono</div></td>
				<td>
					<input name="txt_tel" type="text" id="txt_tel" value="<?php echo $datosHC["telefono"];?>" size="10" onblur="validarTelefono(this);" 
					 class="caja_de_texto" onkeypress="return permite(event,'num',3);"/>              	
				</td>
			</tr>
			<tr>
				<td><div align="right">*Escolaridad</div></td>
				<td>
					<input type="text" name="txt_escolaridad" id="txt_escolaridad" value="<?php echo $datosHC["escolaridad"]; ?>" size="15" 
					maxlength="15" onkeypress="return permite(event,'num_car',0);" class="caja_de_texto" />
					*Clave
					<select name="cmb_claveEsc" class="combo_box" id="cmb_claveEsc">						
						<option value="" selected="selected">Clave</option>
						<option value="AN" title="ANALFABETA">AN</option>
						<option value="PT" title="PRIMARIA TERMINADA">PT</option>
						<option value="PTN" title="PRIMARIA NO TERMINADA">PTN</option>
						<option value="ST" title="SECUNDARIA TERMINADA">ST</option>
						<option value="STN" title="SECUNDARIA NO TERMINADA">STN</option>
						<option value="PPT" title="PREPARATORIO TERMINADA">PPT</option>
						<option value="PPNT" title="PREPARATORIA NO TERMINADA">PPNT</option>
						<option value="TCT" title="">TCT</option>
						<option value="TCNT" title="">TCNT</option>
						<option value="LT" title="LICENCIATURA TERMINADA">LT</option>
						<option value="LNT" title="LICENCIATURA NO TERMINADA">LNT</option>
						<option value="MT" title="MAESTRIA TERMINADA">MT</option>
						<option value="MNT" title="MAESTRIA NO TERMINADA">MNT</option>
						<option value="DOCT" title="DOCTORADO TERMINADO">DOCT</option>
						<option value="DOCNT" title="DOCTORADO NO TERMINADO">DOCNT</option>
					</select> 				             
				</td>
				<td><div align="right">*Empresa</div></td>
				<td>
					<input type="text" name="txt_empresa" id="txt_empresa" value="<?php echo $datosHC["nom_empresa"];?>" size="40" maxlength="80" 
					onkeypress="return permite(event,'num_car',4);" class="caja_de_texto"/>              
				</td>
				<td><div align="right">*Raz&oacute;n Social</div></td>
				<td>
					<input type="text" name="txt_razSocial" id="txt_razSocial" size="30" maxlength="80" value="<?php echo $datosHC["razon_social"];?>" 
					onkeypress="return permite(event,'num_car',4);" class="caja_de_texto"/>              
				</td>
			</tr>
			<tr>
				<td><div align="right">
					<input type="checkbox" name="ckb_historialFam" id="ckb_historialFam" value="ckb_historialFam" onclick="abrirHistorialFamiliar();" 
					title="Registrar el Historial Familiar del Trabajador" />
				</div></td>
				<td>Registrar Historial Familiar </td>
				<td><div align="right">
					<input type="checkbox" name="ckb_aspetosGrales1" id="ckb_aspetosGrales1" value="ckb_aspetosGrales1" onclick="abrirAspectosGrales1();" />
				</div></td>
				<td>Registrar Aspectos Generales I </td>
				<td><div align="right">
					<input type="checkbox" name="ckb_aspetosGrales2" id="ckb_aspetosGrales2" value="ckb_aspetosGrales2" onclick="abrirAspectosGrales2();" />
				</div></td>
				<td>Registrar Aspectos Generales II</td>
			</tr>
				<td><div align="right">
					<input type="checkbox" name="ckb_antPatologicos" id="ckb_antPatologicos" value="ckb_antPatologicos" onclick="abrirAntNoPatalogicos();" />
				</div></td>
				<td>Registrar Ant. NO Patalogicos</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><div align="right">
					<input type="checkbox" name="ckb_pruebasEsfuerzo" id="ckb_pruebasEsfuerzo" value="ckb_pruebasEsfuerzo" onclick="abrirPruebasEsfuerzo();" />
				</div></td>
				<td>Registrar Prueba de Esfuerzo</td>
			<tr>
				<td><div align="right">
					<input type="checkbox" name="ckb_hisTrabajo" id="ckb_hisTrabajo" value="ckb_hisTrabajo" onclick="abrirHistorialTrabajo();"/>
				</div></td>
				<td>Registrar Historial de Trabajo </td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><div align="right">
					<input type="checkbox" name="ckb_pruebasLab" id="ckb_pruebasLab" value="ckb_pruebasLab" onclick="abrirPruebasLaboratorio();" />
				</div></td>
				<td>Registrar Prueba de Laboratorio</td>
			</tr>
			<tr>
				<td><div align="right">*Responsable</div></td>
				<td>
					<input name="txt_resUSO" type="text"class="caja_de_texto" id="txt_resUSO" onkeypress="return permite(event,'num_car',0);" 
					value="DR. MALCO OBED GARC&Iacute;A BORJ&Oacute;N" size="40" maxlength="120"/>             
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><div align="right">*Fotograf&iacute;a</div></td>
				<td><?php 
				//Funcion necesaria para obtener si el trabajador cuanta con una foto registrada dentro de la BD, por medio de su numero de empleado
						$mime = obtenerDatoEmpleadoPorNombre("mime",$claveEmpleado);
						//Condicion para notificarle al usuario en caso de que exista o NO una foto cargada del trabajador al cual se le generara el historial clinico
							if($mime!=""){
								echo "<label class='msje_correcto'>Foto Cargada</label>";
							}
							else{
								echo "<label class='msje_correcto'>No Existe Foto Cargada</label>";
							}?> 
				</td>
			</tr>
			<tr>
				<td colspan="7"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td height="45" colspan="9"><div align="center">
							
				<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar" value="Guardar" title="Guardar y Generar el Historial Clinico" 
				onmouseover="window.status='';return true"/>

				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Historial Clinico" 
				onmouseover="window.status='';return true"  onclick="confirmarSalida('menu_historialClinico.php')" />
				</div></td>
			</tr>
	  </table>
	</form>
</fieldset>
		<div id="calendario">
			<input name="calendario" type="image" id="calendario5" onclick="displayCalendar(document.frm_historialClinico.txt_fechaReg,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>
</div>
		<div id="calendario-nac">
			<input name="calendario" type="image" id="calendario5" onclick="displayCalendar(document.frm_historialClinico.txt_fechaNac,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>
</div>					
	
	<?php }//Fin de <?php  if(!isset($_POST["sbt_continuar"]) && !isset($_POST["sbt_guardar"]) ){

	}//Fin del if(isset($_POST["sbt_continuar"])){	?>
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
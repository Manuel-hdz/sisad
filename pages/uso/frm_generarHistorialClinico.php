<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de la Unidad de Salud Ocupacional
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
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>

	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:298px;height:20px;z-index:11;}
		#calendario{position:absolute;left:546px;top:248px;width:30px;height:26px;z-index:13;}		
		#calendario-nac{position:absolute;left:550px;top:349px;width:30px;height:26px;z-index:13;}		
		#tabla-clasificacion{ position:absolute; left:26px; top:190px; width:854px;	height:178px; z-index:12; }
		#res-spider {position:absolute;z-index:20;}
		#tabla-complementar-historial{ position:absolute; left:26px; top:190px; width:953px;	height:416px; z-index:13; }
		#boton-RegExamen{position:absolute;left:167px;top:386px;width:20px;height:1px;z-index:12;}
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
?>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Generar Historial Clinico  </div>
				
<?php  if(!isset($_POST["sbt_continuar"]) && !isset($_POST["sbt_guardar"]) ){?>
<fieldset id="tabla-clasificacion" class="borde_seccion">
<legend class="titulo_etiqueta">Seleccionar el Tipo de Examen a Pr�cticar </legend>
<br>	
	<form onsubmit="return valFormSelTipoHisClinico(this);" name="frm_seleccionarHistorialClinico" method="post" action="frm_generarHistorialClinico.php" >
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td width="144"><div align="right">Clasificaci&oacute;n Examen</div></td>
				<td width="174">
					<select name="cmb_clasificacionExa" class="combo_box" id="cmb_clasificacionExa">
						<option value="" selected="selected">Clasificaci&oacute;n Examen</option>
						<option value="ESPECIAL">ESPECIAL</option>
						<option value="EMPRESA EXTERNA">EMPRESA EXTERNA</option>
						<option value="INGRESO">INGRESO</option>
						<option value="PERIODICO">PERIODICO</option>
						<option value="RETIRO">RETIRO</option>												
					</select>
			  </td>					
			  <td colspan="2"><div align="right"><span id="etiquetaEmp">&nbsp;</span></div></td>
				<td width="40"><span id="componenteHTMLEmp">&nbsp;</span></td>
			</tr>
			<tr>
			  <td width="144"><div align="right">Tipo Clasificaci&oacute;n</div></td>
				<td width="174">
					<select name="cmb_tipoClasificacion" class="combo_box" id="cmb_tipoClasificacion" 
					onchange="filtroClasificacionHistorialClinico(this.value); activarCajaHisClinico(this.value)">
						<option value="" selected="selected">Tipo Clasificaci&oacute;n</option>
						<option value="EXTERNO">EXTERNO</option>
						<option value="INTERNO">INTERNO</option>											
					</select>
			  </td>	
			  <td width="157"><div align="right"><span id="etiqueta">&nbsp;</span></div></td>
				<td width="40"><span id="componenteHTML">&nbsp;</span></td>
				<td width="259"><span id="componenteHTML2">&nbsp;</span></td>
			</tr>
			<tr>	
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><div align="right"><span id="etiqueta2">&nbsp;</span></div></td>
				<td><span id="componenteHTML3">&nbsp;</span></td>
				<td><span id="componenteHTML4">&nbsp;</span></td>			
			</tr>
			<tr>
				<td colspan="6" align="center">
					<input type="submit" name="sbt_continuar" id="sbt_continuar" class="botones" title="Continuar con la Generaci�n del Historial Clinico" 
						value="Continuar" onmouseover="window.status='';return true;"/>
					
					<input type="hidden" name="hdn_nomEmpresa" id="hdn_nomEmpresa" value="CLF"/>
					<input type="hidden" name="hdn_razSocial" id="hdn_razSocial" value="CONCRETO LANZADO DE FRESNILLO MARCA "/> 
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" title="Limpiar los Datos" value="Limpiar" 
						onclick="limpiarRegHistorialClinico();"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Volver al Men&uacute; del Historial Clinico" 
						value="Regresar" onclick="location.href='menu_historialClinico.php'"/>
				</td>
			</tr>
		</table>
	</form>
</fieldset>
	<?php  }
	
	if(isset($_POST["sbt_continuar"])){
		//Obtener el Id del Historial Clinico
		$idHistorial=obtenerIdHistorialClinico();
		$empleado ="";
		$numEmp ="";
		$nss = "";
		$puesto ="";
		$fechaReg = "";
		$sexo = "";
		$edad = "";
		$reside = "";
		$originario = "";
		$edoCivil = "";
		$dom = "";	
		$fechaNac = date ("d/m/Y");
		$tel = "";
		$escolaridad = "";
		$claveEsc = "";
		$idEmpresa = "";
		$nomEmpresa ="";
		$razSocial = "";

		$foto = "";
		$resUSO = "";
		
		//Variables que vienen en la pantalla de seleccion, se recuperan dentro de esta parte y se asignan a variables para el manejo posterior
		$tipoClas = strtoupper($_POST["cmb_tipoClasificacion"]);
		$clasExa = strtoupper($_POST["cmb_clasificacionExa"]);


		//Verificar el tipo de clasificacion
		if($_POST["cmb_tipoClasificacion"]!="EXTERNO"){

			/*Es ciclo controla que de acuerdo al nombre que venga en la caja de texto de acuerdo a la eleccion de los radios, se muestre en el siguiente formulario, 
			yaS sea directamente desde la busqueda sphider o ingresando el nombre directamente*/
			if($_POST["rdb_nomEmpleado"]=="BUSCAR"){
				$empleado=strtoupper($_POST["txt_nombre"]);
			}
			else if($_POST["rdb_nomEmpleado"]=="INGRESAR"){
				$empleado=strtoupper($_POST["txt_nombre2"]);
			}				
				$numEmp = obtenerDatoEmpleadoPorNombre("id_empleados_empresa",$empleado);
				//Funcion que permite obtener el puesto del trabajdor seleccionado desde la busqueda sphider
				$puesto = obtenerDatoEmpleadoPorNombre("puesto",$empleado);
				//Funcion que obtiene el domicilio del empleado seleccionado desde la busqueda sphider					
				$dom = obtenerDomicilioEmpleado($empleado);
					
				//Obtener el numero del seguo social, estado civil y el telefono mediante la funcion obtenerDato y de acuerdo al numero de empleado que tiene registrado
				$nss = obtenerDato("bd_recursos", "empleados", "no_ss", "id_empleados_empresa", $numEmp);
				$edoCivil = obtenerDato("bd_recursos", "empleados", "edo_civil", "id_empleados_empresa", $numEmp);				
				$tel = obtenerDato("bd_recursos", "empleados", "telefono", "id_empleados_empresa", $numEmp);				
				//$idDepto = obtenerDato("bd_usuarios", "usuarios", "no_depto", "depto", "no_depto");
				$foto = obtenerDato('bd_recursos', 'empleados', 'fotografia', 'id_empleados_empresa', $empleado);
				$mime = obtenerDato('bd_recursos', 'empleados', 'mime', 'id_empleados_empresa', $empleado);
				
				//Recuperamos el valor de las varibles que contienen a la empresa interna, como tal CLF
				$nomEmpresa = $_POST["hdn_nomEmpresa"];
				$razSocial = $_POST["hdn_razSocial"];
			
		}// Fin del if($_POST["cmb_tipoClasificacion"]!="EXTERNO"){
		else{
			$idEmpresa=$_POST["cmb_empresas"];
			$nomEmpresa = obtenerDato("bd_clinica", "catalogo_empresas", "nom_empresa", "id_empresa", $idEmpresa);
			$razSocial = obtenerDato("bd_clinica", "catalogo_empresas", "razon_social", "id_empresa", $idEmpresa);	?>
	<?php }//Fin del 	else{?>
		

	<fieldset class="borde_seccion" id="tabla-complementar-historial" name="tabla-complementar-historial">
	<legend class="titulo_etiqueta">Ingresar Informaci&oacute;n General del Historial Clinico </legend>	
	<form  onsubmit="return valFormRegHistorialClinico(this);"name="frm_historialClinico" id="frm_historialClinico" method="post" action="" >
		<table width="101%" cellpadding="3" cellspacing="3" class="tabla_frm">
			<tr>
				<td width="9%"><div align="right">Historial</div></td>
				<td width="23%">
					<input type="text" name="txt_idHistorial" id="txt_idHistorial" value="<?php echo $idHistorial;?>" size="10" maxlength="10" 
					readonly="readonly" class="caja_de_texto"/>              
				</td>
				<td width="10%"><div align="right"> Examen</div></td>
				<td width="22%">
			  <input type="text" name="txt_clasExa" id="txt_clasExa" value="<?php echo $clasExa;?>" size="18" maxlength="15" readonly="readonly" class="caja_de_texto"/>              	</td>
				<td width="10%"><div align="right">Tipo </div></td>
				<td width="26%">
					<input type="text" name="txt_tipoClas" id="txt_tipoClas" value="<?php echo $tipoClas;?>" size="10" maxlength="10" 
					readonly="readonly" class="caja_de_texto"/>              
			  </td>
			</tr>
			<tr>
				<td><div align="right">Afiliaci&oacute;n</div></td>
				<td>
					<input type="text" name="txt_numSS" id="txt_numSS" value="<?php echo $nss; ?>" size="15" maxlength="15"  class="caja_de_texto"/>              
				</td>
				<td><div align="right">Fecha</div></td>
				<td>
					<input name="txt_fechaReg" type="text" class="caja_de_texto" id="txt_fechaReg" value=<?php echo date("d/m/Y");?> 
					size="10" maxlength="10"  readonly="readonly"/>              
				</td>
				<td><div align="right">*Puesto</div></td>
				<td>
					<input name="txt_puesto" type="text" class="caja_de_texto" id="txt_puesto" onkeypress="return permite(event,'num_car',8);" 
					value="<?php echo $puesto;?>" size="25" maxlength="30"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre </div></td>
				<td>
					<input type="text" name="txt_nombre" id="txt_nombre" value="<?php echo $empleado;?>" size="40" maxlength="75" 
					onkeypress="return permite(event,'num_car',0);" class="caja_de_texto"/> 
					<input type="hidden" name="hdn_idEmpleadoExt" id="hdn_idEmpleadoExt" value="<?php echo $empleado; ?>"/>				
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
					<input name="txt_edad" type="text" class="caja_de_num" id="txt_edad" onkeypress="return permite(event,'num',3);" value="" size="2" maxlength="2"
					 onchange="validarEdad(this);" />
					N&deg; Empleado
					<input type="text" name="txt_numEmp" id="txt_numEmp" value="<?php echo $numEmp;?>" size="5" maxlength="20" 
					onkeypress="return permite(event,'num',3);" class="caja_de_num"/>              
				</td>
			</tr>
			<tr>
				<td><div align="right">*Reside en:</div></td>
				<td>
					<input name="txt_reside" type="text" class="caja_de_texto" id="txt_reside" value="" size="40" maxlength="60" onkeypress="return permite(event,'num_car',0);"/>              	</td>
				<td><div align="right">*Originario</div></td>
				<td>
					<input type="text" name="txt_originario" id="txt_originario" value="" size="40" maxlength="75" onkeypress="return permite(event,'num_car',0);" 
					class="caja_de_texto"/>              
				</td>
				<td><div align="right">*Estado Civil</div></td>
				<td><?php 
						//function que se encarga de colocar automaticamente el estado civil del trabajador de acuerdo al registro en la BD
						$result=cargarCombo("cmb_edoCivil","edo_civil","empleados","bd_recursos","Edo. Civil",$edoCivil); 
							if($result==0) {
								echo "<label class='msje_correcto'>No Datos Registrados</label>
								<input type='hidden' name='cmb_edoCivil' id='cmb_edoCivil'/>";
							}
						?>  
				</td>
			</tr>
			<tr>
				<td><div align="right">*Domicilio</div></td>
				<td>
					<input name="txt_domicilio" type="text"class="caja_de_texto" id="txt_domicilio" onkeypress="return permite(event,'num_car',0);" 
					value="<?php echo $dom; ?>" size="40" maxlength="120"/>              
				</td>
				<td><div align="right">Fecha Nacimiento </div></td>
				<td>
					<input name="txt_fechaNac" type="text" id="txt_fechaNac" value=<?php echo $fechaNac;?> size="10"  readonly="readonly" 
					class="caja_de_texto"/>  
					<input type="hidden" name="hdn_fechaAct" id="hdn_fechaAct" value="<?php echo date("d/m/Y");?>"/>          
				</td>
				<td><div align="right">Telefono</div></td>
				<td>
					<input name="txt_tel" type="text" id="txt_tel" value="<?php echo $tel; ?>" size="10" onblur="validarTelefono(this);" 
					 class="caja_de_texto" onkeypress="return permite(event,'num',3);"/>              	
				</td>
			</tr>
			<tr>
				<td><div align="right">*Escolaridad</div></td>
				<td>
					<input type="text" name="txt_escolaridad" id="txt_escolaridad" value="" size="15" maxlength="15" onkeypress="return permite(event,'num_car',0);"
					class="caja_de_texto"/>
					*Clave
					<select name="cmb_claveEsc" class="combo_box" id="cmb_claveEsc" >
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
					<input type="text" name="txt_empresa" id="txt_empresa" value="<?php echo $nomEmpresa;?>" size="40" maxlength="80" 
					onkeypress="return permite(event,'num_car',4);" class="caja_de_texto"/>              
				</td>
				<td><div align="right">*Raz&oacute;n Social</div></td>
				<td>
					<input type="text" name="txt_razSocial" id="txt_razSocial" size="30" maxlength="80" value="<?php echo $razSocial;?>" 
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
					<input type="checkbox" name="ckb_aspetosGrales1" id="ckb_aspetosGrales1" value="ckb_aspetosGrales1" onclick="abrirAspectosGrales1();" title="Registrar los Aspectos Generales I" />
				</div></td>
				<td>Registrar Aspectos Generales I </td>
				<td><div align="right">
					<input type="checkbox" name="ckb_aspetosGrales2" id="ckb_aspetosGrales2" value="ckb_aspetosGrales2" onclick="abrirAspectosGrales2();" title="Registrar los Aspectos Generales II" />
				</div></td>
				<td>Registrar Aspectos Generales II</td>
			</tr>
				<td><div align="right">
					<input type="checkbox" name="ckb_antPatologicos" id="ckb_antPatologicos" 
					value="ckb_antPatologicos" onclick="abrirAntNoPatalogicos();" title="Registrar los Antecedentes NO Pat&oacute;logicos" />
				</div></td>
				<td>Registrar Ant. NO Patalogicos</td>
				<td><input type="hidden" name="hdn_area_Empleado" id="hdn_area_Empleado" value="<?php //echo $area; ?>"/></td>
				<td><input type="hidden" name="idDepto" id="idDepto" value="<?php //echo $idDepto; ?>"/></td>
				<td><div align="right">
					<input type="checkbox" name="ckb_pruebasEsfuerzo" id="ckb_pruebasEsfuerzo" value="ckb_pruebasEsfuerzo" onclick="abrirPruebasEsfuerzo();" title="Registrar Resultados de las Pruebas de Esfuerzo" />
				</div></td>
				<td>Registrar Prueba de Esfuerzo</td>
			<tr>
				<td><div align="right">
					<input type="checkbox" name="ckb_hisTrabajo" id="ckb_hisTrabajo" value="ckb_hisTrabajo" onclick="abrirHistorialTrabajo();" title="Registrar el Historial de Trabajo"/>
				</div></td>
				<td>Registrar Historial de Trabajo </td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><div align="right">
					<input type="checkbox" name="ckb_pruebasLab" id="ckb_pruebasLab" value="ckb_pruebasLab" onclick="abrirPruebasLaboratorio();" title="Registrar Resultados de las Pruebas de Laboratorio" />
				</div></td>
				<td>Registrar Prueba de Laboratorio</td>
			</tr>
			<tr>
				<td><div align="right">*Responsable</div></td>
				<td>
					<!-- <input name="txt_resUSO" type="text"class="caja_de_texto" id="txt_resUSO" onkeypress="return permite(event,'num_car',0);" 
					value="DRA. DIANA ROCIO CHAIREZ TRETO" size="40" maxlength="120"/> -->
					<select name="txt_resUSO" class="combo_box" id="txt_resUSO" required="required">
						<option value="" selected="selected">Responsable</option>
						<option value="1,DR. DAVID ALBERTO CARLOS BARAJAS">DR. DAVID ALBERTO CARLOS BARAJAS</option>
						<!--<option value="2,DR. DAVID ALBERTO CARLOS BARAJAS">DR. DAVID ALBERTO CARLOS BARAJAS</option>-->
					</select>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td><div align="right">*Fotograf&iacute;a</div></td>
				<td><?php 
				//Funcion necesaria para obtener si el trabajador cuanta con una foto registrada dentro de la BD, por medio de su numero de empleado
						$mime = obtenerDatoEmpleadoPorNombre("mime",$empleado);
						//Condicion para notificarle al usuario en caso de que exista o NO una foto cargada del trabajador al cual se le generara el historial clinico
							if($mime!=""){
								echo "<label class='msje_correcto'>Foto Cargada</label>";
							}
							else{
								echo "<label class='msje_correcto'>No Existe Foto Cargada</label>";
							}?> 
				</td>  
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
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione la Fecha de Nacimiento" align="absbottom" width="25" height="25" 
			border="0"/>
</div>					
	
	<?php }//Fin del if(isset($_POST["sbt_continuar"])){	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
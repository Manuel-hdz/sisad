<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="includes/ajax/validarEmpleado.js"></script>
	<!-- se anexa este archivo para obtener las funciones necesarias para el control de costos -->
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-agregarEmpleado {position: absolute; left: 10px; top: 190px; width: 900px; height: 600px; z-index: 12;}
		#calendario {position: absolute; left: 540px; top: 438px; width: 30px; height: 26px; z-index: 13;}
		#calendario_nac {position: absolute; left: 862px; top: 483px; width: 30px; height: 26px; z-index: 13;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Empleado</div>
	
    <?php
	//Verificar si cancelar esta definido en el GET, de ser asi, se debe redireccionar a la pagina de inicio
	if (isset($_GET["cancelar"])){
		//Si esta denifido el arreglo datosPersonales, eliminarlo, ya que debe desaparecer al cancelar la operacion
		if (isset($_SESSION["datosPersonales"]))
			unset($_SESSION["datosPersonales"]);
		//Redireccionar a la pantalla de menu de empleados
		echo "<meta http-equiv='refresh' content='0;url=menu_empleados.php'>";
	}	
	//Definir el conjunto de variables que tomara el valor de vacio en caso de ser la primera vez
	//que se entre a este formulario
	$rfc="";
	$nombre="";
	$apPat="";
	$apMat="";
	$sangre="";
	$curp="";
	$calle="";
	$num_ext="";
	$num_int="";
	$codigoPost="";
	$fecha=date("d/m/Y");
	$col="";
	$estado="";
	$pais="";
	$nac="";
	$nss="";
	$edoCivil="";
	$obs="";
	$id_bolsa="N/A";
	$contactoAcc="";
	$telCasa="";
	$celular="";
	$mun_loc="";
	$telTrabajador="";
	$lugarNac="";
	//Datos Formato DC-4
	$discapacidad="";
	$hijosDepEco=0;
	//Datos Academicos
	$nivEstudios="";
	$titulo="";
	$carrera="";
	$tipoEscuela="";
	//Control de costos
	$cmb_con_cos="";
	$cmb_cuenta="";
	//Datos de alimentos
	$derechoAlimento="";
	
	//Verificar si esta definido en el GET la variable folioAspirante, esto significa que el Empleado ha sido contratado desde la Bolsa de Trabajo
	if (isset($_GET["folioAspirante"])){
		$nombre=obtenerDato("bd_recursos","bolsa_trabajo","nombre","folio_aspirante",$_GET["folioAspirante"]);
		$apPat=obtenerDato("bd_recursos","bolsa_trabajo","ap_paterno","folio_aspirante",$_GET["folioAspirante"]);
		$apMat=obtenerDato("bd_recursos","bolsa_trabajo","ap_materno","folio_aspirante",$_GET["folioAspirante"]);
		$curp=obtenerDato("bd_recursos","bolsa_trabajo","curp","folio_aspirante",$_GET["folioAspirante"]);
		$nac=obtenerDato("bd_recursos","bolsa_trabajo","nacionalidad","folio_aspirante",$_GET["folioAspirante"]);
		$obs=obtenerDato("bd_recursos","bolsa_trabajo","observaciones","folio_aspirante",$_GET["folioAspirante"]);
		$id_bolsa=$_GET["folioAspirante"];
	}
	
	//Verificar si esta definido el arreglo datosPersonales en la SESSION, de ser as�
	//Asignarles los datos de las variables de Session a las variables anteriormente
	//declaradas
	if (isset($_SESSION["datosPersonales"])){
		$rfc=$_SESSION["datosPersonales"]["rfc"];
		$nombre=$_SESSION["datosPersonales"]["nombre"];
		$apPat=$_SESSION["datosPersonales"]["apPat"];
		$apMat=$_SESSION["datosPersonales"]["apMat"];
		$sangre=$_SESSION["datosPersonales"]["sangre"];
		$curp=$_SESSION["datosPersonales"]["curp"];
		$calle=$_SESSION["datosPersonales"]["calle"];
		$num_ext=$_SESSION["datosPersonales"]["num_ext"];
		$num_int=$_SESSION["datosPersonales"]["num_int"];
		$codigoPost=$_SESSION["datosPersonales"]["cp"];
		$fecha=$_SESSION["datosPersonales"]["fecha"];
		$col=$_SESSION["datosPersonales"]["col"];
		$estado=$_SESSION["datosPersonales"]["estado"];
		$pais=$_SESSION["datosPersonales"]["pais"];
		$nac=$_SESSION["datosPersonales"]["nac"];
		$nss=$_SESSION["datosPersonales"]["nss"];
		$edoCivil=$_SESSION["datosPersonales"]["edoCivil"];
		$obs=$_SESSION["datosPersonales"]["obs"];
		$id_bolsa=$_SESSION["datosPersonales"]["id_bolsa"];
		$contactoAcc=$_SESSION["datosPersonales"]["contactoAcc"];
		$telCasa=$_SESSION["datosPersonales"]["telCasa"];
		$celular=$_SESSION["datosPersonales"]["celular"];
		$mun_loc=$_SESSION["datosPersonales"]["mun_loc"];
		$telTrabajador=$_SESSION["datosPersonales"]["telTrabajador"];
		$lugarNac=$_SESSION["datosPersonales"]["lugarNac"];
		//Datos Formato DC-4
		$discapacidad=$_SESSION["datosPersonales"]["discapacidad"];
		$hijosDepEco=$_SESSION["datosPersonales"]["hijosDepEco"];
		//Datos Academicos
		$nivEstudios=$_SESSION["datosPersonales"]["nivEstudios"];
		$titulo=$_SESSION["datosPersonales"]["titulo"];
		$carrera=$_SESSION["datosPersonales"]["carrera"];
		$tipoEscuela=$_SESSION["datosPersonales"]["tipoEscuela"];
		//Control de costos
		$cmb_con_cos=$_SESSION["datosPersonales"]["control_cos"];
		$cmb_cuenta=$_SESSION["datosPersonales"]["cuentas"];
		//Datos de alimentos
		$derechoAlimento=$_SESSION["datosPersonales"]["alimento"];
	}?>
    
	<fieldset class="borde_seccion" id="tabla-agregarEmpleado">
	<legend class="titulo_etiqueta">Agregar Empleado - Datos Personales (1/2) </legend>	
	<form onSubmit="return valFormAgregarEmpleado(this);" name="frm_agregarEmpleado1" method="post" action="frm_agregarEmpleado2.php">
    <table width="100%" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
        	<td width="119">
          		<div align="right">*RFC</div>
          	</td>
            <td width="161">
            	<input name="txt_rfc" id="txt_rfc" type="text" class="caja_de_texto" size="13" maxlength="13" autocomplete="off"
            	onkeypress="return permite(event,'num_car', 3);" 
            	onblur="return verificarDatoBD(this,'bd_recursos','empleados','rfc_empleado','nombre');" onchange="return validarEmpleado(this);"
            	value="<?php echo $rfc;?>" required="required"/>
				<span id="error" class="msj_error">RFC Duplicado</span>
			</td>
			<td width="143">
				<div align="right">*CURP</div>
			</td>
			<td width="151">
				<input name="txt_curp" id="txt_curp" type="text" class="caja_de_texto" size="20" maxlength="18" onkeypress="return permite(event,'num_car',3);" value="<?php echo $curp;?>" autocomplete="off" required="required"/>
            </td>
			<td width="95">
				<div align="right">*NSS  </div>
			</td>
			<td width="167">
				<input name="txt_nss" id="txt_nss" type="text" class="caja_de_texto" size="11" maxlength="11" onkeypress="return permite(event,'num',3);" value="<?php echo $nss;?>" autocomplete="off" required="required"/>
			</td>
        </tr>
        <tr>
        	<td>
        		<div align="right">*Nombre</div>
        	</td>
        	<td>
        		<input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" value="<?php echo $nombre;?>" autocomplete="off" required="required"/>
        	</td>
        	<td>
        		<div align="right">*Apellido Paterno </div>
        	</td>
        	<td>
        		<input name="txt_apePat" id="txt_apePat" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" value="<?php echo $apPat;?>" autocomplete="off" required="required"/>
			</td>
			<td width="95">
				<div align="right">*Apellido Materno </div>
			</td>
			<td width="167">
				<input name="txt_apeMat" id="txt_apeMat" type="text" class="caja_de_texto" size="25" maxlength="25" onkeypress="return permite(event,'car',0);" value="<?php echo $apMat;?>" autocomplete="off" required="required"/>
			</td>
        </tr>
        <tr>
        	<td>
        		<div align="right">*Calle</div>
        	</td>
        	<td>
        		<input name="txt_calle" id="txt_calle" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" value="<?php echo $calle;?>" autocomplete="off" required="required"/>
        	</td>
        	<td>
        		<div align="right">*N&uacute;m Ext.</div>
        	</td>
        	<td>
        		<input name="txt_numExt" id="txt_numExt" type="text" class="caja_de_texto" size="3" maxlength="5" onkeypress="return permite(event,'num',0);" value="<?php echo $num_ext;?>" onchange="if (this.value&lt;=0){ alert ('N&uacute;mero no V&aacute;lido');this.value='';}" autocomplete="off" required="required"/>
        		N&uacute;m Int.
        		<input name="txt_numInt" id="txt_numInt" type="text" class="caja_de_texto" size="3" maxlength="5" onkeypress="return permite(event,'num_car',3);" value="<?php echo $num_int;?>" onchange="if (this.value&lt;=0){ alert ('N&uacute;mero no V&aacute;lido');this.value='';}" autocomplete="off"/>
        	</td>
        	<td width="95">
        		<div align="right">*Colonia</div>
        	</td>
        	<td width="167">
        		<input type="text" name="txt_colonia" id="txt_colonia" size="20" maxlength="20" onkeypress="return permite(event,'num_car',1);" class="caja_de_texto" value="<?php echo $col;?>" autocomplete="off" required="required"/>
        	</td>
        </tr>
        <tr>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>
        		<div align="right">*C.P.</div>
        	</td>
        	<td>
        		<input name="txt_cp" id="txt_cp" type="text" class="caja_de_num" size="5" maxlength="5" onkeypress="return permite(event,'num',3)" value="<?php echo $codigoPost; ?>" autocomplete="off" required="required">
        	</td>
        </tr>
        <tr>
        	<td>
        		<div align="right">*Municipio/Localidad</div>
        	</td>
        	<td>
        		<input name="txt_munLoc" id="txt_munLoc" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" value="<?php echo $mun_loc;?>" autocomplete="off" required="required"/>
        	</td>
        	<td>
        		<div align="right">*Estado</div>
        	</td>
        	<td>
        		<input name="txt_estado" id="txt_estado" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'car',0);" value="<?php echo $estado;?>" autocomplete="off" required="required"/>
        	</td>
            <td>
            	<div align="right">*Pa&iacute;s</div>
            </td>
            <td>
            	<input name="txt_pais" id="txt_pais" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'car',0);" value="<?php echo $pais;?>" autocomplete="off" required="required"/>
            </td>
        </tr>
        <tr>
        	<td>
        		<div align="right">*Nacionalidad</div>
        	</td>
        	<td>
        		<input name="txt_nacionalidad" id="txt_nacionalidad" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'car',0);" value="<?php echo $nac;?>" autocomplete="off" required="required"/>
			</td>
			<td>
				<div align="right">Tel&eacute;fono</div>
			</td>
			<td>
				<input name="txt_telTrabajador" id="txt_telTrabajador" type="text" class="caja_de_texto" size="16" maxlength="15"  onblur="validarTelefono(this);" onkeypress="return permite(event,'num',3);" value="<?php echo $telTrabajador;?>" autocomplete="off"/>
			</td>
			<td>
				<div align="right">*Estado Civil</div>
			</td>
			<td>
				<select name="cmb_estado" id="cmb_estado" size="1" class="combo_box" required="required">
					<option <?php if($edoCivil=="") echo "selected='selected'"?> value="">Estado Civil</option>
					<option <?php if($edoCivil=="SOLTERO") echo "selected='selected'"?> value="SOLTERO">SOLTERO</option>
					<option <?php if($edoCivil=="UNI�N LIBRE") echo "selected='selected'"?> value="UNI&Oacute;N LIBRE">UNI&Oacute;N LIBRE</option>
					<option <?php if($edoCivil=="CASADO") echo "selected='selected'"?> value="CASADO">CASADO</option>
					<option <?php if($edoCivil=="DIVORCIADO") echo "selected='selected'"?> value="DIVORCIADO">DIVORCIADO</option>
					<option <?php if($edoCivil=="VIUDO") echo "selected='selected'"?> value="VIUDO">VIUDO</option>
				</select>
			</td>
        </tr>
        <tr>
        	<td>
        		<div align="right">*Tipo Sangre</div>
        	</td>
        	<td>
        		<input name="txt_sangre" id="txt_sangre" type="text" class="caja_de_texto" size="5" maxlength="5" onkeypress="return permite(event,'car',0);" value="<?php echo $sangre;?>" autocomplete="off" required="required"/>
        	</td>
        	<td>
        		<div align="right">*Fecha de Ingreso</div>
        	</td>
            <td>
            	<input type="text" name="txt_fechaIngreso" id="txt_fechaIngreso" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo $fecha; ?>"/>
            </td>
            <td>
            	<div align="right">Lugar Nacimiento </div>
            </td>
            <td>
            	<input name="txt_lugarNac" id="txt_lugarNac" type="text" class="caja_de_texto" size="20" maxlength="40" onkeypress="return permite(event,'num_car',3);" value="<?php echo $lugarNac;?>" autocomplete="off" required="required"/>
            </td>
        </tr>
        <!-- <tr>
        	<td></td><td></td>
        	<td></td><td></td>
        	<td>
        		<div align="right">Fecha de Nacimiento</div>
        	</td>
        	<td>
        		<input type="text" name="txt_fechaNacimiento" id="txt_fechaNacimiento" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo $fecha; ?>"/>
        	</td>
        </tr> -->
		<tr>
			<td>
				<div align="right">*Discapacidad</div>
			</td>
			<td>
				<select name="cmb_tipoDisc" id="cmb_tipoDisc" class="combo_box" required="required">
					<option <?php if($discapacidad=="") echo "selected='selected' ";?>value="">Discapacidad</option>
					<option <?php if($discapacidad=="N/A") echo "selected='selected' ";?>value="N/A">NINGUNA</option>
					<option <?php if($discapacidad=="MOTRIZ") echo "selected='selected' ";?>value="MOTRIZ">MOTRIZ</option>
					<option <?php if($discapacidad=="VISUAL") echo "selected='selected' ";?>value="VISUAL">VISUAL</option>
					<option <?php if($discapacidad=="MENTAL") echo "selected='selected' ";?>value="MENTAL">MENTAL</option>
					<option <?php if($discapacidad=="AUDITIVA") echo "selected='selected' ";?>value="AUDITIVA">AUDITIVA</option>
					<option <?php if($discapacidad=="DE LENGUAJE") echo "selected='selected' ";?>value="DE LENGUAJE">DE LENGUAJE</option>
				</select>
			</td>
			<td>
				<div align="right">Dep. Econ&oacute;micos </div>
			</td>
			<td>
				<input type="text" class="caja_de_num" name="txt_depEco" id="txt_depEco" size="2" maxlength="2" onkeypress="return permite(event,'num',3);" value="<?php echo $hijosDepEco;?>" autocomplete="off" required="required"/> Hijos
			</td>
			<td>
				<div align="right">Observaciones</div>
			</td>
			<td>
				<textarea name="txa_observaciones" id="txa_observaciones" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);" style="resize: none;"><?php echo $obs;?></textarea>
            </td>
		</tr>
		<tr>
			<td>
				<div align="right">*Nivel Estudios</div>
			</td>
			<td>
				<select name="cmb_nivEstudios" id="cmb_nivEstudios" class="combo_box" title="Nivel M&aacute;ximo de Estudios Terminados" required="required">
					<option <?php if($nivEstudios=="") echo "selected='selected' ";?>value="">Escolaridad</option>
					<option <?php if($nivEstudios=="1") echo "selected='selected' ";?>value="1">PRIMARIA</option>
					<option <?php if($nivEstudios=="2") echo "selected='selected' ";?>value="2">SECUNDARIA</option>
					<option <?php if($nivEstudios=="3") echo "selected='selected' ";?>value="3">BACHILLERATO</option>
					<option <?php if($nivEstudios=="4") echo "selected='selected' ";?>value="4">CARRERA T&Eacute;CNICA</option>
					<option <?php if($nivEstudios=="5") echo "selected='selected' ";?>value="5">LICENCIATURA</option>
					<option <?php if($nivEstudios=="6") echo "selected='selected' ";?>value="6">ESPECIALIDAD</option>
					<option <?php if($nivEstudios=="7") echo "selected='selected' ";?>value="7">MAESTR&Iacute;A</option>
					<option <?php if($nivEstudios=="8") echo "selected='selected' ";?>value="8">DOCTORADO</option>
				</select>
			</td>
			<td>
				<div align="right">*Doc. Obtenido</div>
			</td>
			<td>
				<select name="cmb_docObtenido" id="cmb_docObtenido" class="combo_box" title="Documento Probatorio Obtenido" required="required">
					<option <?php if($titulo=="") echo "selected='selected' ";?>value="">Documento</option>
					<option <?php if($titulo=="1") echo "selected='selected' ";?>value="1">T&Iacute;TULO</option>
					<option <?php if($titulo=="2") echo "selected='selected' ";?>value="2">CERTIFICADO</option>
					<option <?php if($titulo=="3") echo "selected='selected' ";?>value="3">DIPLOMA</option>
					<option <?php if($titulo=="4") echo "selected='selected' ";?>value="4">OTRO</option>
				</select>
			</td>
			<td>
				<div align="right">*Estudio/Carrera</div>
			</td>
			<td>
				<input type="text" name="txt_carrera" id="txt_carrera" class="caja_de_texto" maxlength="40" size="30" value="<?php echo $carrera;?>" autocomplete="off" required="required"/>
			</td>
		</tr>
		<tr>
			<td>
				<div align="right">*Instituci&oacute;n</div>
			</td>
			<td>
				<select name="cmb_institucion" id="cmb_institucion" class="combo_box" title="Tipo de Instituci&oacute;n Educativa" required="required">
					<option <?php if($tipoEscuela=="") echo "selected='selected' ";?>value="">Tipo</option>
					<option <?php if($tipoEscuela=="1") echo "selected='selected' ";?>value="1">P&Uacute;BLICA</option>
					<option <?php if($tipoEscuela=="2") echo "selected='selected' ";?>value="2">PRIVADA</option>
				</select>
			</td>
			<td>
				<div align="right">*Control de Costos</div>
			</td>
			<td>
				<?php 
					$conn = conecta("bd_recursos");		
					$stm_sql = "SELECT * FROM control_costos WHERE habilitado = 'SI' ORDER BY descripcion";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_con_cos" id="cmb_con_cos" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta')" required="required">
							<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value=''>Control de Costos</option>";
							do{
								if ($datos['id_control_costos'] == $cmb_con_cos){
									echo "<option value='$datos[id_control_costos]' selected='selected'>$datos[descripcion]</option>";
								}else{
									echo "<option value='$datos[id_control_costos]'>$datos[descripcion]</option>";
								}
							}while($datos = mysql_fetch_array($rs));
							echo "<script type='text/javascript'>
									cargarCuentas(cmb_con_cos.value,'cmb_cuenta');
								  </script>";
							?>
							<script type="text/javascript">
								setTimeout("document.getElementById('cmb_cuenta').value='<?php echo $cmb_cuenta ?>'",500);
							</script>
						</select>
					<?php
					}
					else{
						echo "<label class='msje_correcto'> No actualmente control de costos</label>
							<input type='hidden' name='cmb_area' id='cmb_area'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);
				?>
			</td>
			<td width="15%">
				<div align="right">*Cuenta</div>
			</td>
			<td width="40%">
				<span id="datosCuenta" required="required">
					<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box" required="required">
						<option value="">Cuentas</option>
					</select>
				</span>
			</td>
		</tr>
		<tr>
			<td colspan="6">
				<strong>Derecho de alimentos</strong>
			</td>
		</tr>
        <tr>
            <td>
            	<div align="right">Derecho </div>
            </td>
            <td>
            	<select name="cmb_alimento" id="cmb_alimento" class="combo_box" title="Nivel de Beneficio de alimentos" required="required">
					<option <?php if($derechoAlimento=="") echo "selected='selected' ";?>value="">% de Beneficio</option>
					<option <?php if($derechoAlimento=="1") echo "selected='selected' ";?>value="1">3 Comidas al 100%</option>
					<option <?php if($derechoAlimento=="2") echo "selected='selected' ";?>value="2">2 Comidas al 100%</option>
					<option <?php if($derechoAlimento=="3") echo "selected='selected' ";?>value="3">1 Comida al 100%</option>
					<option <?php if($derechoAlimento=="4") echo "selected='selected' ";?>value="4">3 Comidas al 50%</option>
					<option <?php if($derechoAlimento=="5") echo "selected='selected' ";?>value="5">0% Sin Descuento</option>
				</select>
            </td>
           
        </tr>
		<tr>
			<td colspan="6">
				<strong>Datos de Contacto en caso de Accidente</strong>
			</td>
		</tr>
        <tr>
            <td>
            	<div align="right">Nombre </div>
            </td>
            <td>
            	<input name="txt_contactoAcc" id="txt_contactoAcc" type="text" class="caja_de_texto" size="28" maxlength="60" onkeypress="return permite(event,'num_car',0);" value="<?php echo $contactoAcc;?>" autocomplete="off"/>
            </td>
            <td>
            	<div align="right">Tel Casa </div>
            </td>
            <td>
            	<input name="txt_telCasa" id="txt_telCasa" type="text" class="caja_de_texto" size="16" maxlength="15" onblur="validarTelefono(this);" onkeypress="return permite(event,'num',3);" value="<?php echo $telCasa;?>" autocomplete="off"/>
            </td>
            <td>
            	<div align="right">Celular</div>
            </td>
            <td>
            	<input name="txt_celular" id="txt_celular" type="text" class="caja_de_texto" size="16" maxlength="15"  onblur="validarTelefono(this);" onkeypress="return permite(event,'num',3);" value="<?php echo $celular;?>" autocomplete="off"/>
            </td>
        </tr>
        <tr>
        	<td colspan="4">
        		<strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong>
        	</td>
        </tr>
        <tr>
            <td colspan="6">
                <div align="center">       	    	
                    <input type="hidden" name="hdn_idBolsa" value="<?php echo $id_bolsa;?>"/>
                    <input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/>
                    <input name="sbt_continuar" id="sbt_continuar" type="submit" class="botones"  value="Continuar" title="Agregar los Datos Personales del Empleado" 
                    onMouseOver="window.status='';return true" />
                    &nbsp;&nbsp;&nbsp;<?php 
                    if (!isset($_SESSION["datosPersonales"])){?>
                    	<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true" 
                        onclick="error.style.visibility='hidden';sbt_continuar.disabled=false;sbt_continuar.title='Agregar los Datos Personales del Empleado';"/><?php  
                    }
                    else {?>
                    	<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Reestablecer Formulario" 
                        onMouseOver="window.status='';return true"
                        onclick="error.style.visibility='hidden';sbt_continuar.disabled=false;sbt_continuar.title='Agregar los Datos Personales del Empleado';"/><?php  
					}?>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Empleados" 
                    onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_agregarEmpleado.php?cancelar')" />
                </div>
			</td>
        </tr>
	</table>
	</form>
	</fieldset>

	<div id="calendario">
		<input type="image" name="fechaIngreso" id="fechaIngreso" src="../../images/calendar.png" onclick="displayCalendar(document.frm_agregarEmpleado1.txt_fechaIngreso,'dd/mm/yyyy',this)"         onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Ingreso de Empleado"/> 
	</div>

	<!-- <div id="calendario_nac">
		<input type="image" name="fechaNacimiento" id="fechaNacimiento" src="../../images/calendar.png" onclick="displayCalendar(document.frm_agregarEmpleado1.txt_fechaNacimiento,'dd/mm/yyyy',this)"         onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Nacimiento de Empleado"/> 
	</div> -->

</body><?php
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		include ("head_menu.php");
		include ("op_agregarEmpleado.php")?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="includes/ajax/validarCveEmpleado.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-agregarEmpleado { position:absolute; left:30px; top:190px; width:908px; height:350px; z-index:12; padding:15px; padding-top:0px;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Empleado</div><?php
	
	//Verificar si cancelar esta definido en el GET, de ser asi, se debe redireccionar a la pagina de inicio
	if (isset($_GET["cancelar"])){
		//Si esta denifido el arreglo datosPersonales, eliminarlo, ya que debe desaparecer al cancelar la operacion
		if (isset($_SESSION["datosPersonales"]))
			unset($_SESSION["datosPersonales"]);
		echo "<meta http-equiv='refresh' content='0;url=menu_empleados.php'>";
	}

	//Verificar si el boton sbt_continuar esta definido en el POST, de ser asi cargar los datos en la Sesion
	if (isset($_POST["sbt_continuar"])){
		$_SESSION["datosPersonales"]["rfc"]=$_POST["txt_rfc"];
		$_SESSION["datosPersonales"]["nombre"]=$_POST["txt_nombre"];
		$_SESSION["datosPersonales"]["apPat"]=$_POST["txt_apePat"];
		$_SESSION["datosPersonales"]["apMat"]=$_POST["txt_apeMat"];
		$_SESSION["datosPersonales"]["sangre"]=$_POST["txt_sangre"];
		$_SESSION["datosPersonales"]["curp"]=$_POST["txt_curp"];
		$_SESSION["datosPersonales"]["calle"]=$_POST["txt_calle"];
		$_SESSION["datosPersonales"]["num_ext"]=$_POST["txt_numExt"];
		$_SESSION["datosPersonales"]["num_int"]=$_POST["txt_numInt"];
		$_SESSION["datosPersonales"]["cp"]=$_POST["txt_cp"];
		$_SESSION["datosPersonales"]["fecha"]=$_POST["txt_fechaIngreso"];
		$_SESSION["datosPersonales"]["col"]=$_POST["txt_colonia"];
		$_SESSION["datosPersonales"]["estado"]=$_POST["txt_estado"];
		$_SESSION["datosPersonales"]["pais"]=$_POST["txt_pais"];
		$_SESSION["datosPersonales"]["nac"]=$_POST["txt_nacionalidad"];
		$_SESSION["datosPersonales"]["nss"]=$_POST["txt_nss"];
		$_SESSION["datosPersonales"]["edoCivil"]=$_POST["cmb_estado"];
		$_SESSION["datosPersonales"]["obs"]=$_POST["txa_observaciones"];
		$_SESSION["datosPersonales"]["id_bolsa"]=$_POST["hdn_idBolsa"];
		$_SESSION["datosPersonales"]["contactoAcc"]=$_POST["txt_contactoAcc"];
		$_SESSION["datosPersonales"]["telCasa"]=$_POST["txt_telCasa"];
		$_SESSION["datosPersonales"]["celular"]=$_POST["txt_celular"];
		$_SESSION["datosPersonales"]["mun_loc"]=$_POST["txt_munLoc"];
		$_SESSION["datosPersonales"]["telTrabajador"]=$_POST["txt_telTrabajador"];
		$_SESSION["datosPersonales"]["lugarNac"]=$_POST["txt_lugarNac"];
		//Datos Formato DC-4
		$_SESSION["datosPersonales"]["discapacidad"]=$_POST["cmb_tipoDisc"];
		$_SESSION["datosPersonales"]["hijosDepEco"]=$_POST["txt_depEco"];
		//Datos Academicos
		$_SESSION["datosPersonales"]["nivEstudios"]=$_POST["cmb_nivEstudios"]; 
		$_SESSION["datosPersonales"]["titulo"]=$_POST["cmb_docObtenido"];
		$_SESSION["datosPersonales"]["carrera"]=$_POST["txt_carrera"];
		$_SESSION["datosPersonales"]["tipoEscuela"]=$_POST["cmb_institucion"];
		//Control de costos
		$_SESSION["datosPersonales"]["control_cos"]=$_POST["cmb_con_cos"];
		$_SESSION["datosPersonales"]["cuentas"]=$_POST["cmb_cuenta"];
		//Datos de alimentos
		$_SESSION["datosPersonales"]["alimento"]=$_POST["cmb_alimento"];
		
		//Verificar si esta definido un Id de bolsa de Trabajo, esto por contratacion desde la pantalla correspondiente
		if ($_SESSION["datosPersonales"]["id_bolsa"]!="N/A"){
			//Ventana de solicitud de consulta de Datos?>
			<script type="text/javascript" language="javascript">
				setTimeout("if (confirm('�Consultar Datos de �rea y Puesto?')){window.open('verDatosBolsa.php?id_bolsa=<?php echo $_POST['hdn_idBolsa'];?>&consulta=areapuesto','_blank','top=50, left=50, width=500, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');};",1000);
			</script><?php
		}
	}
	
	//Abrir la conexion con la BD de Recursos
	$conn=conecta("bd_recursos");
	//Sentencia SQL para calcular el numero de clave Empresarial que se le asignar� al Trabajador
	$stm_sql_idEmpresa="SELECT MAX(id_empleados_empresa)+1 AS claveEmp FROM empleados";
	$rs_idEmpresa=mysql_query($stm_sql_idEmpresa);
	//Cerrar la conexion
	mysql_close($conn);
	$cveEmp=mysql_fetch_array($rs_idEmpresa);
	?>
	
	<fieldset class="borde_seccion" id="tabla-agregarEmpleado">
	<legend class="titulo_etiqueta">Agregar Empleado - Datos Laborales (2/2) </legend>	
	<br>
	<form onSubmit="return valFormAgregarEmpleado2(this);" name="frm_agregarEmpleado2" method="post" action="op_agregarEmpleado.php" enctype="multipart/form-data">
    <table width="923" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
	<tr>
		<td width="137"
			<div align="right">*Clave Empresarial</div>
		</td>
		<td width="298">
			<input name="txt_cveEmp" id="txt_cveEmp" type="text" class="caja_de_texto" size="15" maxlength="13" onkeypress="return permite(event,'num_car', 3);" 
			onblur="verificarCveEmp(this.value,'','sbt_agregarEmpleado');" value="<?php echo $cveEmp["claveEmp"];?>" required="required"/>
			<span id="error" class="msj_error">Clave Duplicada</span>
		</td>
		<?php 
			$cc = obtenerNombreCentroCostos($_SESSION["datosPersonales"]["control_cos"]);
			$clave_area = obtenerIdArea($cc,1);
			if($clave_area==null){
				$clave_area = obtenerIdArea($cc,2);
			}
			$id_depto=obtenerIdDepto($cc,1);
			if ($id_depto==null){
				$id_depto=obtenerIdDepto($cc,2);
			}
			if(isset($_POST["cmb_cuenta"])){
				$id_depto=obtenerIdDepto2($_POST["cmb_cuenta"],$clave_area);
			}
		?>
		<td width="168">
			<div align="right">*Clave &Aacute;rea</div
		</td>
		<td width="253">
			<input name="txt_cveArea" id="txt_cveArea" type="text" class="caja_de_texto" size="25" maxlength="25" 
			onkeypress="return permite(event,'num',0);" readonly="readonly" value="<?php echo $clave_area; ?>"/>
		</td>
	</tr>
	
	<tr>
		<td>
			<div align="right">Jornada</div>
		</td>
		<td>
			<input name="txt_jornada" id="txt_jornada" type="text" class="caja_de_num" size="5" maxlength="2" onkeypress="return permite(event,'num',3);" />&nbsp;Hrs.
		</td>
		<td>
			<div align="right">*N&uacute;mero de Cuenta</div>
		</td>
		<td>
			<input name="txt_numCta" id="txt_numCta" type="text" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',3);" required="required"/>
		</td>
	</tr>
	<!--<tr>
		<td><div align="right">*&Aacute;rea </div></td>
		<td><?php 
				//Definir la variable que contendra el nombre del combo
				$cmb_area="";
				//Conectar a la BD de recursos
				$conn = conecta("bd_recursos");
				$result=mysql_query("SELECT DISTINCT area,id_depto,id_empleados_area FROM empleados ORDER BY area");?>
				<select name="cmb_area" id="cmb_area" size="1" class="combo_box" onchange="ordDato(this.value,'txt_cveArea','hdn_claveDepto');">
					<option value="">&Aacute;rea</option>
						<?php while ($row=mysql_fetch_array($result)){
							if ($row['area'] == $cc){
								echo "<option value='$row[id_depto];$row[area]' selected='selected'>$row[area]</option>";
								$clave_area = $row['id_empleados_area'];
							}
							else{
								echo "<option value='$row[id_depto];$row[area]'>$row[area]</option>";
							}
						} 
				//Cerrar la conexion con la BD		
				mysql_close($conn);?>
				</select>
		</td>
		<td><div align="right">
		  <input type="checkbox" name="ckb_nuevaArea" id="ckb_nuevaArea" 
		  onclick="agregarNuevaArea(this,'ckb_nuevoPuesto','txt_nuevaArea','txt_nuevoPuesto','cmb_area','cmb_puesto'); if(ckb_nuevaArea.checked) txt_cveArea.value=1; else txt_cveArea.value = ''" 
		  title="Seleccione para escribir el nombre de un &Aacute;rea que no exista"/>
		  Agregar Nueva &Aacute;rea </div></td>
		<td><input name="txt_nuevaArea" id="txt_nuevaArea" type="text" class="caja_de_texto" readonly="readonly" size="30" maxlength="30"/></td>
	</tr> -->
	<tr>
		<td>
			<div align="right">*Puesto</div>
		</td>
		<td>
			<?php 
			$conn = conecta("bd_recursos");
			$puesto = "";
			$result=mysql_query("SELECT DISTINCT puesto FROM empleados WHERE id_depto = '$id_depto' AND id_empleados_area = '$clave_area' ORDER BY puesto");?>
			<select name="cmb_puesto" id="cmb_puesto" required="required">
				<option value="">Puesto</option>
				<?php while ($row=mysql_fetch_array($result)){
							if ($row['puesto'] == $puesto){
								echo "<option value='$row[puesto]' selected='selected'>$row[puesto]</option>";
							}
							else{
								echo "<option value='$row[puesto]'>$row[puesto]</option>";
							}
						} 
				//Cerrar la conexion con la BD		
				mysql_close($conn);?>
			</select>
		</td>
		<td>
			<div align="right">
				<input type="checkbox" name="ckb_nuevoPuesto" id="ckb_nuevoPuesto" onclick="agregarNuevoPuesto(this, 'ckb_nuevaArea', 'txt_nuevaArea', 'txt_nuevoPuesto', 'cmb_area', 'cmb_puesto');" title="Seleccione para escribir el nombre de un Puesto que no exista"/>
				Agregar Nuevo Puesto
			</div>
		</td>
	  	<td>
	  		<input name="txt_nuevoPuesto" id="txt_nuevoPuesto" type="text" class="caja_de_texto" readonly="readonly" size="30" maxlength="30"/>
	  	</td>
	</tr>
	<tr>
		<td><div align="right">Fotograf&iacute;a</div></td>
		<td><input type="file" id="foto" name="foto" class="caja_de_texto" size="20" title="Buscar Imagen" value=""
			onclick="alert('La Imagen no Debe Pesar mas de 10 Mb, de los Contrario no se Almacenar&aacute; en el Cat&aacute;logo de Empleados');" 
			onchange="return validarImagen(this,'hdn_foto');" />
			<input type="hidden" id="hdn_foto" name="hdn_foto" value=""/></td>
		<td><div align="right">Sueldo Diario </div></td>
		<td>$
		  <input type="text" name="txt_sueldo" id="txt_sueldo" class="caja_de_num" size="10" maxlength="10" onchange="formatCurrency(value,'txt_sueldo');" 
		  onkeypress="return permite(event,'num',2);"/>
		</td>
	</tr>
	<tr>
		<td><div align="right">Ocupaci&oacute;n Espec&iacute;fica</div></td>
		<td>
		  <input type="text" name="txt_ocEsp" id="txt_ocEsp" class="caja_de_texto" size="20" maxlength="20" onkeypress="return permite(event,'num_car',2);" 
		  title="Ocupaci&oacute;n Espec&iacute;fica, usada para el Formato de Capacitaciones DC-4"/>
		</td>
	</tr>
	<tr>
	   <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	</tr>
	<tr>
		<td colspan="4">
			<div align="center">
			<?php //Elemento HTML que permite verificar si la clave de Empresa es valida o no?>
			<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
			<?php //Elemento HTML que permite agregar un Nuevo Deprtamento?>
			<input type="hidden" name="hdn_claveDepto" id="hdn_claveDepto" value=""/>
			<input name="sbt_agregarEmpleado" id="sbt_agregarEmpleado" type="submit" class="botones"  value="Agregar" title="Guardar el Registro" 
            onMouseOver="window.status='';return true" />
			&nbsp;&nbsp;&nbsp;
			<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true" onclick="cmb_area.disabled=false;cmb_puesto.disabled=false;error.style.visibility='hidden';sbt_agregarEmpleado.disabled=false;error.style.visibility='hidden';sbt_agregarEmpleado.title='Guardar el Registro';"/> 
			&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Datos Personales" 
			onMouseOver="window.status='';return true" onclick="location.href='frm_agregarEmpleado.php?rfc=<?php if(isset($txt_rfc)) echo $txt_rfc; else echo "";?>'"/>
			&nbsp;&nbsp;&nbsp;
			<input name="btn_regresar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Empleados" 
			onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_agregarEmpleado2.php?cancelar=si')"/>
			<?php if (isset($_SESSION["datosPersonales"]["id_bolsa"]) && $_SESSION["datosPersonales"]["id_bolsa"]!="N/A"){?>
			&nbsp;&nbsp;&nbsp;
			<input name="btn_revisar" type="button" class="botones" value="&Aacute;reas/Puestos" title="Consultar &Aacute;reas y Puestos a los que Aspira el Interesado" 
			onMouseOver="window.status='';return true" onclick="javascript:window.open('verDatosBolsa.php?id_bolsa=<?php if (isset($_POST["hdn_idBolsa"])) echo $_POST['hdn_idBolsa']; else echo "";?>&consulta=areapuesto','_blank','top=50, left=50, width=500, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>
			<?php }?>
			</div>
		</td>
	</tr>
	</table>
	</form>
</fieldset>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
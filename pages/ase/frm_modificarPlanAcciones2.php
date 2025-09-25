<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarPlanAcciones.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionAseguramiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregar {position:absolute;left:30px;top:190px;width:950px;height:346px;z-index:12;}
		#calendario{position:absolute;left:799px;top:233px;width:30px;height:26px;z-index:13;}
		#res-spider {position:absolute;z-index:15;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<?php 
	//**********RECUPERACION DE LOS DATOS SEGUN EL REGISTRO**********************//
	if(!isset($_POST['sbt_guardar'])){
		
		//Variable en la cual se almacenara el valor del registro seleccionado para proceder a llenar las cajas de texto
		$radio=$_POST['rdb_id'];
		
		//Verificamos que existan registros de complemtento por parte de los departamentos
		$detalleRef1 = obtenerDato("bd_aseguramiento", "referencias", "id_referencia", "plan_acciones_id_plan_acciones", $radio);
		$detalleRef2 = obtenerDato("bd_aseguramiento", "detalle_referencias", "justificacion", "referencias_id_referencia", $detalleRef1);
		
		//Verificamos el resultado para mostrarlo en la caja de texto
		if($detalleRef2=="")
			$detalleRef2="NO";
		else
			$detalleRef2="SI";
		
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM plan_acciones WHERE id_plan_acciones = '$radio'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
			
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);
		$areaAuditada = $datos['area_auditada'];
		$fecha = modFecha($datos['fecha'],1);
		$creador = $datos['creador'];
		$aprobador = $datos['aprobador'];
		$verificador = $datos['verificador'];
		$noDoc = $datos['no_documento'];
		$noRev = $datos['revision'];
		$referencia = $datos['referencia'];
		$departamento = $_POST['hdn_depto'];
		
		//Consulta que permite seleccionar los departamentos para despues proceder a concatenarlos
		$stm_sqlDep="SELECT catalogo_departamentos_id_departamento FROM copias_entregadas WHERE plan_acciones_id_plan_acciones='$radio'";
				
		//Ejecutar la sentencia previamente creada
		$rsDep = mysql_query($stm_sqlDep);
			
		//Confirmar que la consulta de datos fue realizada con exito.
		$datosDep=mysql_fetch_array($rsDep);
		
		//Variable que permite controlar el agregado de archivos
		$contad = 1;
		do{	
			if($contad==1){
				$nomDepto = obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datosDep['catalogo_departamentos_id_departamento']);
				$deptos = strtoupper($nomDepto);
			}
			if($contad>1){
				$nomDepto = obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datosDep['catalogo_departamentos_id_departamento']);
				$deptos .= ",".strtoupper($nomDepto);
			}
			$contad++;
		}while($datosDep=mysql_fetch_array($rsDep));
		
		//ReConectar a la BD de Aseguramiento ya que la funcion de obtener dato anterior, cierra la conexcion
		$conn = conecta("bd_aseguramiento");
		//Consulta que permite seleccionar los departamentos para despues proceder a concatenarlos
		$stm_sqlPart="SELECT nombre FROM catalogo_participantes_auditoria WHERE plan_acciones_id_plan_acciones='$radio'";
				
		//Ejecutar la sentencia previamente creada
		$rsPart = mysql_query($stm_sqlPart);
			
		//Confirmar que la consulta de datos fue realizada con exito.
		$datosPart=mysql_fetch_array($rsPart);
		
		//Variable que permite controlar el agregado de archivos
		$contad = 1;
		do{	
			if($contad==1){
				$participantes = $datosPart['nombre']; 
			}
			if($contad>1){
				$participantes .= ",".$datosPart['nombre'];
			}
			$contad++;
		}while($datosPart=mysql_fetch_array($rsPart));
	}
	else{
		$areaAuditada = $_POST['cmb_depto'];
		$fecha = $_POST["txt_fecha"];
		$creador = $_POST['txt_creador'];
		$aprobador = $_POST['txt_aprobado'];
		$verificador = $_POST['txt_verificado'];
		$participantes = $_POST['txt_paticipantesAu'];
		$noDoc = $_POST['txt_NoDoc'];
		$noRev = $_POST['txt_rev'];
		$deptos = $_POST['txt_ubicacion'];
		$detalleRef = $_POST['txt_detallePA'];
		$detalleRef2 = $_POST['txt_detallePA'];
		$referencia = $_POST['txt_referencias'];
		$departamento = $_POST['hdn_depto'];
	}
	if(isset($_POST['sbt_guardar'])&&!isset($_SESSION['referencias'])){?>
			<script language="javascript" type="text/javascript">
				setTimeout("alert('No Se Puede Guardar El Registro; Es Necesario Registrar Referencias')",500);
			</script>
		<?php }
	//Verificamos si viene definido el boton; de ser asi almacenar la información
	if(isset($_POST["sbt_guardar"])&&isset($_SESSION['referencias'])){
		//Llamamos la funcion guardarRegistro
		guardarRegistro();
	}else{?>
		<div class="titulo_barra" id="titulo-registrar">Modificar Plan Acciones </div>
		<fieldset class="borde_seccion" id="tabla-agregar" name="tabla-agregar">
		<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Plan de Acciones </legend>	
		<br>
		<form onsubmit="return valFormPlanAcciones(this);"name="frm_agregarPA"  id="frm_agregarPA" method="post" action="frm_modificarPlanAcciones2.php">
			<table width="953" height="308"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">*Area Auditada</div></td>
					<td width="372"><?php  
						$cmb_depto=$areaAuditada;
						$conn = conecta("bd_usuarios");
						$result=mysql_query("SELECT DISTINCT UPPER(depto) AS depto FROM usuarios WHERE depto NOT LIKE 'Panel' AND depto NOT LIKE 'GerenciaGral' 
											ORDER BY depto");
						if($depto=mysql_fetch_array($result)){?>
						<select name="cmb_depto" id="cmb_depto" size="1" class="combo_box">
							<option <?php if($cmb_depto==$areaAuditada){echo "selected='selected'";}?>value="">Departamentos</option>
							<?php 
							do{
								if ($depto['depto'] == $cmb_depto){
									echo "<option value='$depto[depto]' selected='selected'>$depto[depto]</option>";
								}
								else{
									echo "<option value='$depto[depto]'>$depto[depto]</option>";
								}
							}while($depto=mysql_fetch_array($result)); 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
						</select>
					<?php }
						else{
							echo "<label class='msje_correcto'> No hay Departamentos Registrados</label>
							<input type='hidden' name='cmb_depto' id='cmb_depto'/>";?>
				  <?php }?>				</td>
				<td width="124"><div align="right">Fecha  </div></td>
				<td width="282"><input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
			</tr>
			<tr>
				<td width="108"><div align="right">*Creado Por</div></td>
				<td>
					<input type="text" name="txt_creador" id="txt_creador" onkeyup="lookup(this,'empleados','1');" 
					value="<?php  echo $creador;?>" size="60" maxlength="120" onkeypress="return permite(event,'car',0);" tabindex="1"/>				</td>
				<td><div align="right">*No. Documento</div></td>
			  <td>
			  	<input name="txt_NoDoc" id="txt_NoDoc" type="text" class="caja_de_texto" size="30" maxlength="30" onkeypress="return permite(event,'car',1);"
				 value="<?php  echo $noDoc;?>"/></td>
			</tr>
			<tr>
				<td width="108"><div align="right">*Aprobado Por </div></td>
				<td>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>		
					<input type="text" name="txt_aprobado" id="txt_aprobado" onkeyup="lookup(this,'empleados','1');" 
					value="<?php  echo $aprobador;?>" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" tabindex="1"/></td>
				<td><div align="right">*No. Revisi&oacute;n</div></td>
				<td>
					<input name="txt_rev" id="txt_rev" type="text" class="caja_de_texto" size="3" maxlength="3" onkeypress="return permite(event,'num',3);"
					 value="<?php  echo $noRev;?>"/></td>
			</tr>
			<tr>
				<td><div align="right">*Verificado Por</div></td>
				<td>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>	
					<input type="text" name="txt_verificado" id="txt_verificado" onkeyup="lookup(this,'empleados','1');" 
					value="<?php  echo $verificador;?>" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" tabindex="1"/>				</td>
				<td><div align="right">* Departamentos </div></td>
				<td><input name="txt_ubicacion" id="txt_ubicacion" type="text" class="caja_de_texto" size="40" readonly="readonly" 
					onclick="window.open('verDepartamentos.php','_blank','top=50, left=50, width=380, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar Departamentos"
					 value="<?php  echo $deptos;?>"/></td>
			</tr>
			<tr>
				<td><div align="right">*Participantes</div></td>
				<td>
					<input name="txt_paticipantesAu" id="txt_paticipantesAu" type="text" class="caja_de_texto" size="71" readonly="readonly" 
					onclick="window.open('verParticipantesAuditoria.php','_blank','top=50, left=50, width=380, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar Participantes Auditoria"
					value="<?php  echo $participantes;?>"/>				</td>
						<td><div align="right">*Referencia</div></td>
				        <td><input name="txt_referencias" id="txt_referencias" type="text" class="caja_de_texto" size="20" value="<?php  echo $referencia;?>"onkeypress="return permite(event,'num_car',1);"/></td>
			</tr>
			<tr>
				<td><div align="right">* Complemento </div></td>
			  <td><input name="txt_detallePA" id="txt_detallePA" type="text" class="caja_de_texto" size="40" readonly="readonly" 
					 value="<?php  echo $detalleRef2;?>"/></td>
			</tr>
			<tr>
				<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td height="45" colspan="4">
					<div align="center">
						<input type="hidden" name="rdb_id" id="rdb_id" value="<?php echo $_POST['rdb_id'];?>"/>
						<input type="hidden" name="hdn_depto" value="<?php echo $_POST['hdn_depto'];?>"/>
						<input type="hidden" name="hdn_bandera" id="hdn_bandera" value=""/>
						<input name="btn_referencias" type="button" class="botones_largos" value="Modificar Referencias" title="Modificar Referencias" 
						onclick="abrirModRef();"/>
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Modificaci&oacute;n Plan Acciones"
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Restablecer Formulario" onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" id="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar a Seleccionar Otro Departamento" 
						onmouseover="window.status='';return true"  onclick="confirmarSalida('frm_modificarPlanAcciones.php?depto=<?php echo $_POST['hdn_depto'];?>')" />
					</div>				</td>
			</tr>
		  </table>
		</form>
</fieldset>
		<div id="calendario">
			<input name="calendario" type="image" id="calendario2" onclick="displayCalendar (document.frm_agregarPA.txt_fecha,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />			
		</div><?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>

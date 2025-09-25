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
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-agregarEmpleado { position:absolute; left:30px; top:190px; width:908px; height:403px; z-index:12; padding:15px; padding-top:0px;}
		#calendario {position:absolute;left:790px;top:234px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Empleado</div>
	
    <?php
	//Elimina el Arreglo de Sesion de Beneficiarios en caso de estar definido
	if (isset($_SESSION["beneficiarios"]))
		unset($_SESSION["beneficiarios"]);
	//Elimina el Arreglo de Sesion de Becarios en caso de estar definido
	if (isset($_SESSION["becarios"]))
		unset($_SESSION["becarios"]);
	
	//Verificar si el RFC esta definido en el GET, de ser asi mostrar los datoa recien agregados
	if (isset($_GET["rfc"]))
		$rfc=$_GET["rfc"];
	$conn=conecta("bd_recursos");
	$rs=mysql_query("SELECT * FROM empleados WHERE rfc_empleado='$rfc'");
	$datos=mysql_fetch_array($rs);
	mysql_close($conn);?>
    
	<fieldset class="borde_seccion" id="tabla-agregarEmpleado">
	<legend class="titulo_etiqueta">Trabajador Registrado <?php echo $datos["nombre"]." ".$datos["ape_pat"]." ".$datos["ape_mat"];?></legend>	
	<br>
    <table width="923" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="137"><div align="right"><strong>RFC</strong></div></td>
			<td width="298"><?php echo $rfc;?></td>
			<td width="168"><div align="right"><strong>Fecha de Ingreso</strong></div></td>
			<td width="253"><?php echo modFecha($datos["fecha_ingreso"],2);?></td>
		</tr>
		<tr>
			<td><div align="right"><strong>Curp</strong></div></td>
			<td><?php echo $datos["curp"];?></td>
			<td><div align="right"><strong>&Aacute;rea </strong></div></td>
			<td><?php echo $datos["area"];?></td>
		</tr>
		<tr>
			<td><div align="right"><strong>ID Empresa </strong></div></td>
			<td><?php echo $datos["id_empleados_empresa"];?></td>
			<td><div align="right"><strong>Puesto</strong></div></td>
			<td><?php echo $datos["puesto"];?></td>
		</tr>
		<tr>
			<td><div align="right"><strong>ID &Aacute;rea </strong></div></td>
			<td><?php echo $datos["id_empleados_area"];?></td>
			<td><div align="right"><strong>No. Cuenta  </strong></div></td>
			<td><?php echo $datos["no_cta"];?></td>
		</tr>
		<tr>
			<td><div align="right"><strong>Nombre </strong></div></td>
			<td><?php echo $datos["nombre"]." ".$datos["ape_pat"]." ".$datos["ape_mat"];?></td>
			<td><div align="right"><strong>Jornada  </strong></div></td>
			<td><?php echo $datos["jornada"];?></td>
		</tr>
		<tr>
			<td><div align="right"><strong>Tipo Sangre </strong></div></td>
			<td><?php echo $datos["tipo_sangre"];?></td>
			<td><div align="right"><strong>Pais </strong></div></td>
			<td><?php echo $datos["pais"];?></td>
		</tr>       	
		<tr>
			<td><div align="right"><strong>No. Seguro Social </strong></div></td>
			<td><?php echo $datos["no_ss"];?></td>
			<td><div align="right"><strong>Estado Civil </strong></div></td>
			<td><?php echo $datos["edo_civil"];?></td>
		</tr>	
		<tr>
			<td valign="top"><div align="right"><strong>Direcci&oacute;n </strong></div></td>
			<td><?php echo $datos["calle"]." ".$datos["num_ext"]." ".$datos["num_int"]." ".$datos["colonia"]." ".$datos["localidad"]." ".$datos["estado"];?></td>
		    <td valign="top"><div align="right"><strong>Observaciones</strong></div></td>
		    <td><?php echo $datos["observaciones"];?></td>
		</tr>
		<tr>
			<td><div align="right"><strong>Tel&eacute;fono</strong></div></td>
			<td><?php echo $datos["telefono"];?></td>
		</tr>
		<tr>
			<td>
				<div align="right">
					<strong>SOLICITO: </strong>
				</div>
			</td>
			<td>
				<input type="text" id="txt_solicitante" name="txt_solicitante" class="caja_de_texto" size="80" />
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<div align="center">       	    	
				<input name="btn_beneficiarios" type="button" class="botones_largos"  value="Registrar Beneficiarios" title="Registrar los Beneficarios del Trabajador" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_agregarEmpleadoBeneficiario.php?rfc=<?php echo $rfc; ?>'"/>
				&nbsp;&nbsp;&nbsp;
				<input name="btn_becarios" type="button" class="botones_largos"  value="Registrar Becarios" title="Registrar los Becarios del Trabajador" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_agregarEmpleadoBecarios.php?rfc=<?php echo $rfc; ?>';"/>
				&nbsp;&nbsp;&nbsp;
				<input name="btn_registroPersonal" type="button" class="botones"  value="Ver PDF" title="Ver Registro de Personal" onMouseOver="window.status='';return true" 
				onclick="window.open('../../includes/generadorPDF/registro_personal.php?id_empl=<?php echo $datos["id_empleados_empresa"]; ?>&solicito='+txt_solicitante.value+'&usuario=<?php echo $usr_reg; ?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=no, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>
				&nbsp;&nbsp;&nbsp;
				<input name="btn_finalizar" type="button" class="botones"  value="Finalizar" title="Finalizar y Guardar" 
				onMouseOver="window.status='';return true" onclick="location.href='exito.php';"/>
				</div>			
			</td>
		</tr>
	</table>
	</form>
	</fieldset>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
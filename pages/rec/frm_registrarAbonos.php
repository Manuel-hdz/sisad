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
		include ("op_registrarAbonos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js"></script>	
    <script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/validarPagosPeriodo.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
		<!--
		#titulo-registrar{position:absolute;left:30px;top:146px;width:276px;height:20px;z-index:11;}
		#tabla-registrarAbonos{position:absolute;left:30px;top:190px;width:750px;height:250px;z-index:12;padding:15px;padding-top:0px;}
		#calendarioIni {position:absolute;left:755px;top:331px;width:30px;height:26px;z-index:13;}
		#abonosAgregados {position:absolute;left:30px;top:480px;width:940px;height:160px;z-index:12; overflow:scroll; }
		-->
    </style>
</head>
<body><?php 
	//Determinar el Origen de la Solicitud y Controlar el regreso con el Boton de Cancelar
	$destino = "";	$msg = ""; $param_consulta = ""; $orgMensaje = "";
	if(isset($_GET['hdn_org']) && $_GET['hdn_org']=="prestamos"){
		$destino = "menu_prestamos.php"; $msg = "Prestamos"; $param_consulta = "LIKE"; $orgMensaje = "El Prestamo";
	}
	else if($_GET['hdn_org']=="deducciones"){
		$destino = "menu_deducciones.php"; $msg = "Deducciones"; $param_consulta = "NOT LIKE"; $orgMensaje = "La Deducci&oacute;n";
	}
		
	
	//Si esta se ha presionado el boton finalizar proceder a guardar los datos almacenados en la sesion
	$mensaje = "";
	if(isset($_POST['sbt_guardar'])){
		$mensaje = guardarAbonos();
	}?>

	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Registrar Abonos de Empleados</div>
	
	<fieldset class="borde_seccion" id="tabla-registrarAbonos" name="tabla-registrarAbonos">
	<legend class="titulo_etiqueta">Registrar Abonos para <?php echo $msg; ?></legend>	
	<br>
	<form onSubmit="return valFormRegAbonos(this);" name="frm_registrarAbonos" method="post" action="">
	  <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
          <td width="25%"><div align="right">Empleado</div></td>
          <td width="30%"><?php					
				$conn = conecta('bd_recursos');//Conectarse a la BD de Recursos Humanos
				//Obtener los nombres 
				$rs_empleados = mysql_query("SELECT id_deduccion, empleados_rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM deducciones JOIN empleados ON
				empleados_rfc_empleado=rfc_empleado WHERE id_deduccion ".$param_consulta." 'PRE%' AND deducciones.estado = 'ACTIVO' ORDER BY nombre");
				if($datosEmpleados=mysql_fetch_array($rs_empleados)){?>
              <select name="cmb_idDeduccion" id="cmb_idDeduccion"class="combo_box" 
					onchange="obtenerDatosPrestamo(this.value);">
                <option value="">Seleccionar Empleado</option>
                <?php 
							do{                                
								echo "<option value='$datosEmpleados[id_deduccion]'>$datosEmpleados[nombre]</option>";                                
							}while($datosEmpleados=mysql_fetch_array($rs_empleados))?>
              </select>
            <?php
				}
				else {?>
              <label class="msje_correcto"><u><strong>NO</strong></u> Hay Empleados con Prestamo Registrado</label>
              <input type="hidden" name="cmb_idDeduccion" id="cmb_idDeduccion"/>
            <?php
				}?>
          </td>
          <td width="25%" align="right">Total a Pagar</td>
          <td width="20%">$
            <input name="txt_totalPagar" id="txt_totalPagar" type="text" class="caja_de_texto" size="10" readonly="readonly"/>
          </td>
        </tr>
        <tr>
          <td align="right">Fecha &Uacute;ltimo Abono</td>
          <td><input name="txt_fechaUltimoAbono" id="txt_fechaUltimoAbono" type="text" class="caja_de_texto" size="10" value="" readonly="readonly" /></td>
          <td align="right">Fecha Registro Prestamo </td>
          <td><input name="txt_fechaRegPrestamo" id="txt_fechaRegPrestamo" type="text" class="caja_de_texto" size="10" value="" readonly="readonly" /></td>
        </tr>
        <tr>
          <td align="right">Saldo Actual</td>
          <td>$
              <input type="text" name="txt_saldoActual" id="txt_saldoActual" class="caja_de_texto" size="15" readonly="readonly" /></td>
          <td align="right">*Abono</td>
          <td> $
              <input name="txt_abono" id="txt_abono" type="text" class="caja_de_texto" size="10" maxlength="10" 
				onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_abono'); restarAbono(this.value);" />
          </td>
        </tr>
        <tr>
          <td align="right">Nuevo Saldo</td>
          <td>$
              <input type="text" name="txt_nuevoSaldo" id="txt_nuevoSaldo" class="caja_de_texto" size="15" readonly="readonly" /></td>
          <td align="right">*Fecha Registro Abono </td>
          <td><input name="txt_fechaAbono" id="txt_fechaAbono" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y");?>" readonly="readonly" /></td>
        </tr>
        <tr>
          <td class="msje_incorrecto" colspan="4"></td>
        </tr>
        <tr>
          <td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
          <td colspan="2" align="center"><span class="msje_correcto" id="msjAgregarAbono"><?php echo $mensaje; ?></span></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><input name="sbt_guardar" id="sbt_guardar" type="submit" class="botones" value="Guardar" title="Guardar Abono" onmouseover="window.status='';return true" />
            &nbsp;&nbsp;&nbsp;
            <input name="rst_limpiar" id="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
            &nbsp;&nbsp;&nbsp;
            <input name="btn_cancelar" id="btn_cancelar" type="button" class="botones"  value="Cancelar" title="Cancelar y Regresar al Men&uacute; de <?php echo $msg; ?>" 
				onmouseover="window.status='';return true" onclick="confirmarSalida('<?php echo $destino; ?>');"/>
          </td>
        </tr>
      </table>
	</form>
	</fieldset>
	
	<div id="calendarioIni">
		<input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_registrarAbonos.txt_fechaAbono,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
		title="Seleccionar la Fecha del Abono"/> 
</div>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
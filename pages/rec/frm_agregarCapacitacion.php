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
		include ("op_agregarCapacitacion.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarCapacitacion {position:absolute;left:30px;top:190px;width:850px;height:470px;z-index:12;}
		#calendarioIni {position:absolute;left:290px;top:270px;width:30px;height:26px;z-index:13;}
		#calendarioFin {position:absolute;left:735px;top:270px;width:30px;height:26px;z-index:14;}
		-->
    </style>
</head>
<body><?php 

	//Obtener el id de la capacitacion según el registro correspondiente en la BD
	$txt_claveCapacitacion = obtenerIdCapacitacion();
	//Obtener la fecha del sistema para la fecha inicio y fecha fin
	$txt_fechaIni = date("d/m/Y");
	$txt_fechaFin = date("d/m/Y", strtotime("+5 day"));?>
    
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Capacitaci&oacute;nes</div>
    <fieldset class="borde_seccion" id="tabla-agregarCapacitacion" name="tabla-agregarCapacitacion">
    <legend class="titulo_etiqueta">Agregar Capacitaci&oacute;n </legend>	
    <br>
    <form onSubmit="return valFormAgregarCapacitacion(this);" name="frm_agregarCapacitacion" method="post" action="frm_agregarCapacitacion.php">
    <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
			<td width="154"><div align="right">Clave Capacitaci&oacute;n:</div></td>
            <td width="200">
				<input name="txt_claveCapacitacion" id="txt_claveCapacitacion" type="text" class="caja_de_texto" size="10" 
				value="<?php echo $txt_claveCapacitacion;?>" readonly="readonly" />
			</td>
			<td><div align="right">*Horas de Capacitaci&oacute;n:</div></td>
			<td>
				<input name="txt_hrsCapacitacion" id="txt_hrsCapacitacion" type="text" class="caja_de_texto" size="15" maxlength="10" 
                onkeypress="return permite(event,'num',2);" value=""/>
			</td>
			
        </tr>
        <tr>
            <td><div align="right">Fecha Inicio</div></td>
            <td>
				<input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaIni;?>" readonly="readonly"/>
            </td>
          <td width="226"><div align="right">Fecha de Fin:</div></td>
            <td width="203">
				<input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaFin;?>" readonly="readonly"/>
			</td>
        </tr>
		<tr>
			<td><div align="right">*Tema Capacitaci&oacute;n</div></td>
			<td colspan="3">
				<input type="text" name="txt_tema" id="txt_tema" class="caja_de_texto" size="60" maxlength="60" onkeypress="return permite(event,'num_car', 0);"/>
			</td>
		</tr>
		<tr>
			<td><div align="right">*Norma Capacitaci&oacute;n</div></td>
			<td>
				<input name="txt_normaCapacitacion" id="txt_normaCapacitacion" type="text" class="caja_de_texto" size="30" maxlength="30" 
				onkeypress="return permite(event,'num_car',0);" value=""/>
			</td>
			<td><div align="right">*Nombre Capacitaci&oacute;n</div></td>
			<td>
				<input name="txt_nomCapacitacion" id="txt_nomCapacitacion" type="text" class="caja_de_texto" size="40" maxlength="60" 
				onkeypress="return permite(event,'num_car',0);" value=""/>
			</td>
		</tr>
        <tr>
        <td><div align="right">*Modalidad Capacitaci&oacute;n:</div></td>
          <td><select name="cmb_modo" id="cmb_modo" class="combo_box">
            <option value="" selected="selected">Modalidad</option>
            <option value="1">PRESENCIAL</option>
            <option value="2">EN L&Iacute;NEA</option>
            <option value="3">MIXTA</option>
          </select></td>
            <td valign="top"><div align="right">*Descripci&oacute;n:</div></td>
            <td valign="top" ><textarea name="txa_descripcion" id="txa_descripcion"  maxlength="120" onkeyup="return ismaxlength(this)" 
                class="caja_de_texto" rows="2" cols="37" onkeypress="return permite(event,'num_car', 0);" ></textarea>
            </td>
        </tr>
		<tr>
			<td><div align="right">*Objetivo Capacitaci&oacute;n:</div></td>
			<td colspan="4">
				<select name="cmb_objetivo" id="cmb_objetivo" class="combo_box">
					<option value="" selected="selected">Objetivo</option>
					<option value="1">ACTUALIZAR Y PERFECCIONAR CONOCIMIENTOS Y HABILIDADES</option>
					<option value="2">PROPORCIONAR INFORMACI&Oacute;N DE NUEVAS TECNOLOG&Iacute;AS</option>
					<option value="3">PREPARAR PARA OCUPAR VACANTES O PUESTOS DE NUEVA CREACI&Oacute;N</option>
					<option value="4">PREVENIR RIESGOS DE TRABAJO</option>
					<option value="5">INCREMENTAR LA PRODUCTIVIDAD</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4"><strong>Datos del Instructor</strong></td>
		</tr>
		<tr>
            <td><div align="right">*Tipo Instructor:</div></td>
            <td>
				Interno<input type="radio" name="rdb_tipoIns" id="rdb_tipoIns" value="INTERNO" onclick="txt_numRegSTPS.value='';txt_numRegSTPS.readOnly=true;" checked="checked"/>
				Externo<input type="radio" name="rdb_tipoIns" id="rdb_tipoIns" value="EXTERNO" onclick="txt_numRegSTPS.readOnly=false;"/>
			</td>
		</tr>
        <tr>
            <td><div align="right">*Nombre Instructor:</div></td>
            <td><input name="txt_instructor" id="txt_instructor" type="text" class="caja_de_texto" size="40" maxlength="60" 
                onkeypress="return permite(event,'car',0);" value=""/>
            </td>
			<td><div align="right">**N&uacute;mero Registro Instructor Externo en STPS:</div></td>
            <td><input name="txt_numRegSTPS" id="txt_numRegSTPS" type="text" class="caja_de_texto" size="20" maxlength="20" 
                onkeypress="return permite(event,'num_car',0);" value="" readonly="readonly"/>
            </td>
		</tr>
        <tr>	   
        	<td colspan="4">
				<strong>
				* Los campos marcados con asterisco son <u>obligatorios</u>.<br>
				** Datos Obligatorios Dependiendo de lo Seleccionado.
				</strong>
			</td>
		</tr>            
        <tr>
            <td colspan="4"><div align="center">
                <input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar Capacitaci&oacute;n" 
                onmouseover="window.status='';return true"/>
                &nbsp;&nbsp;&nbsp;
                <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true" onclick="txt_numRegSTPS.readOnly=true;"/>
                &nbsp;&nbsp;&nbsp;
                <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar Capacitaci&oacute;n" 
                onmouseover="window.status='';return true" onclick="confirmarSalida('menu_capacitaciones.php');"/></div>
            </td>							               
        </tr>
    </table>
    </form>
    </fieldset>
    <div id="calendarioIni">
        <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_agregarCapacitacion.txt_fechaIni,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Inicio"/> 
    </div>
    <div id="calendarioFin">
        <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_agregarCapacitacion.txt_fechaFin,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Fin"/> 
    </div>	

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
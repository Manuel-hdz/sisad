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
		include ("op_consultarHistorialClinico.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:298px;height:20px;z-index:11;}
		#tabla-consultarExamenTipo {position:absolute;left:30px;top:190px;width:446px;height:194px;z-index:12;}
		#tabla-consultarExamenFecha {position:absolute;left:528px;top:189px;width:388px;height:193px;z-index:12;}
		#calendario{position:absolute;left:301px;top:214px;width:30px;height:26px;z-index:13;}
		#calendario2{position:absolute;left:300px;top:251px;width:30px;height:26px;z-index:14;}
		#calendario3{position:absolute;left:775px;top:227px;width:30px;height:26px;z-index:13;}
		#calendario4{position:absolute;left:774px;top:266px;width:30px;height:26px;z-index:14;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Consultar Historial Cl&iacute;nico </div>
	<fieldset class="borde_seccion" id="tabla-consultarExamenTipo" name="tabla-consultarExamenTipo">
    <legend class="titulo_etiqueta">Consultar Historial Cl&iacute;nico por Tipo </legend>
    <form  onsubmit="return valFormConExamenTipo(this);"name="frm_consultarExamenTipo" id="frm_consultarExamenTipo" method="post" action="frm_consultarHistorialClinico2.php">
      <table width="100%"  cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
          <td><div align="right">*Fecha Inicio </div></td>
          <td width="240"><input name="txt_fechaTipoIni" type="text" id="txt_fechaTipoIni" size="10" maxlength="15" value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" 
				readonly="readonly" class="caja_de_texto"/>
          </td>
        </tr>
        <tr>
          <td width="144"><div align="right">*Fecha Fin </div></td>
          <td><input name="txt_fechaTipoFin" type="text" id="txt_fechaTipoFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
				readonly="readonly" class="caja_de_texto"/>
          </td>
        </tr>
		<tr>
		  <td width="144"><div align="right">Tipo Clasificaci&oacute;n</div></td>
			<td width="240">
					<select name="cmb_tipoClasificacion" class="combo_box" id="cmb_tipoClasificacion" >
						<option value="" selected="selected">Tipo Clasificaci&oacute;n</option>
						<option value="EXTERNO">EXTERNO</option>
						<option value="INTERNO">INTERNO</option>											
					</select>
		  </td>	
		</tr>
        <tr>
          <td height="45" colspan="9"><div align="center">
              <input name="sbt_consultarTipo" type="submit" class="botones" id="sbt_consultarTipo" value="Consultar" title="Consultar Historial Clinico"
					onmouseover="window.status='';return true"/>
            &nbsp;&nbsp;&nbsp;
            <input name="btn_regresar" type="button" class="botones"  value="Regresar" title="Regresar al Men&uacute; de Historial Clinico" 
			onmouseover="window.status='';return true" 	onclick="location.href='menu_historialClinico.php'"/>
          </div></td>
        </tr>
      </table>
    </form>
</fieldset>
		<div id="calendario">
			<input name="calendario" type="image" id="calendario5" onclick="displayCalendar(document.frm_consultarExamenTipo.txt_fechaTipoIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>
</div>
		
		<div id="calendario2">
			<input name="calendario2" type="image" id="calendario22" onclick="displayCalendar(document.frm_consultarExamenTipo.txt_fechaTipoFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>
</div>
	<fieldset class="borde_seccion" id="tabla-consultarExamenFecha" name="tabla-consultarExamenFecha">
    <legend class="titulo_etiqueta">Consultar Historial Cl&iacute;nico por Fecha</legend>
    <form  onsubmit="return valFormConExamenFecha(this);"name="frm_consultarExamenFecha" id="frm_consultarExamenFecha" method="post" action="frm_consultarHistorialClinico2.php">
      <br />
      <table width="100%"  cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
          <td><div align="right">*Fecha Inicio </div></td>
          <td width="240"><input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" 
				readonly="readonly" class="caja_de_texto"/>
          </td>
        </tr>
        <tr>
          <td width="144"><div align="right">*Fecha Fin </div></td>
          <td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" 
				readonly="readonly" class="caja_de_texto"/>
          </td>
        </tr>
        <tr>
          <td width="93">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td height="45" colspan="9"><div align="center">
              <input name="sbt_consultarFechas" type="submit" class="botones" id="sbt_consultarFechas" value="Consultar" title="Consultar Historial Clinico por Fechas"
					onmouseover="window.status='';return true"/>
            &nbsp;&nbsp;&nbsp;
            <input name="btn_regresar" type="button" class="botones"  value="Regresar" title="Regresar al Men&uacute; del Historial Clinico" 
			onmouseover="window.status='';return true"	onclick="location.href='menu_historialClinico.php'"/>
          </div></td>
        </tr>
      </table>
    </form>
</fieldset>
	
		<div id="calendario3">
			<input name="calendario3" type="image" id="calendario6" onclick="displayCalendar(document.frm_consultarExamenFecha.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>
		</div>
		<div id="calendario4">
			<input name="calendario4" type="image" id="calendario7" onclick="displayCalendar(document.frm_consultarExamenFecha.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
			border="0"/>
		</div>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
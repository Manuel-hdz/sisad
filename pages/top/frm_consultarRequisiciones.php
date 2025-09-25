<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografia
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	 	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarRequisicion.js"></script>
	<script type="text/javascript" language="javascript">
		setTimeout("cargarRequisicion(6);",500);
	</script>
	
	
    <style type="text/css">
		<!--
		#consulta-requisicion {position:absolute; left:30px; top:190px; width:944px; height:127px; z-index:13; }
		#consulta-datosReq {position:absolute; left:30px; top:350px; width:944px; height:200px; z-index:13; overflow:scroll; }
		#titulo-consultar { position:absolute; left:30px; top:146px; width:248px; height:19px; z-index:11; }	
		#botones{ position:absolute; left:37px; top:594px; width:988px; height:37px; z-index:17; }
		#calendario_repInicio { position:absolute; left:275px; top:239px; width:30px; height:26px; z-index:14; }
		#calendario_repCierre { position:absolute; left:565px; top:238px; width:30px; height:26px; z-index:14; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Consultar Estado de Requisiciones</div>
			
	<fieldset class="borde_seccion" id="consulta-requisicion" name="consulta-requisicion">
    <legend class="titulo_etiqueta">Seleccionar Requisici&oacute;n</legend>	
    <br>
	<form name="frm_seleccionarFechas" method="post" action="">
    	<table width="931" height="92" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
    		<tr>
				<td width="118" height="34" align="right">Fecha de Inicio</td>
				<td width="163">
		  			<input name="txt_fechaIni" type="text" id="txt_fechaIni" value=<?php echo date("d/m/Y",strtotime("-7 day")); ?> size="10" maxlength="15" 
					readonly="readonly" width="50" onchange="cargarRequisicion(6);"/>
				</td>
            	<td width="97">Fecha de Cierre</td>
            	<td colspan="3">
					<input name="txt_fechaFin" id="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly="readonly" 
					width="50" 	onchange="cargarRequisicion(6);" />
				</td>
			</tr>
        	<tr>
            <td align="right">
				<input type="checkbox" name="ckb_parametros" id="ckb_parametros" value="checkbox" onclick="activarBusqReq(this);" />
				Buscar Por 
			</td>
            <td>
				<select name="cmb_buscarPor" id="cmb_buscarPor" class="combo_box" disabled="disabled"  onchange="cargarRequisicion(6);">
              		<option value="">Par&aacute;metro</option>
              		<option value="descripcion">DESCRIPCI&Oacute;N</option>
              		<option value="aplicacion">APLICACI&Oacute;N</option>
            	</select>
			</td>
            <td><div align="right">Filtro</div></td>
            <td width="239">
				<textarea  class="caja_de_texto" name="txa_notas" id="txa_notas" cols="40" rows="2"  maxlength="80"readonly="readonly"  
				onchange="cargarRequisicion(6);" ></textarea>
			</td>
            <td width="67">Requisici&oacute;n</td>
            <td width="152">
				<select name="cmb_estadoRequisicion" id="cmb_estadoRequisicion" class="combo_box" onchange="cargarTablaRequisicion(this.value,6);">
              		<option value="">Requisici&oacute;n</option>
            	</select>
			</td>	
	  </table>			
	</form>      		
	</fieldset>
	<div id="consulta-datosReq" class="borde_seccion2" style="visibility:hidden"></div>
	<div id="botones" align="center">
		<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Requisiciones" 
        onClick="location.href='menu_requisiciones.php'" />			
	</div>	
	<div id="calendario_repInicio">
		<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_seleccionarFechas.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>
	<div id="calendario_repCierre">
        <input name="calendario_cieRep" type="image" id="calendario_cieRep" onclick="displayCalendar(document.frm_seleccionarFechas.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
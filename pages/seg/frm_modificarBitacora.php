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
		//Manejo de la funciones para registrar los residuos peligrosos dentro de la bitacora en la BD de Seguridad
		include ("op_modificarBitacora.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/calcularID.js"></script>

    <style type="text/css">
		<!--
		#titulo-modBitacora { position:absolute; left:30px; top:146px; width:268px; height:19px; z-index:11; }
		#tabla-almacenamiento { position:absolute; left:13px; top:192px; width:463px;	height:147px; z-index:16; }
		#tabla-verDetalle { position:absolute; left:12px; top:372px; width:978px;	height:188px; z-index:16; overflow:scroll }
		#fechaIni { position:absolute; left:235px; top:254px; width:30px; height:26px; z-index:14; }
		#fechaFin { position:absolute; left:456px; top:255px; width:30px; height:26px; z-index:14; }
		#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
		#botonesBit {position:absolute;left:166px;top:613px;width:716px;height:37px;z-index:14;}

		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modBitacora">Modificar Bitacora de Residuos</div>
	<form  onsubmit="return valFormModBitacora(this);"name="frm_modRegistro" method="post" action="frm_modificarBitacora.php">
	<fieldset id="tabla-almacenamiento" class="borde_seccion">
	<legend class="titulo_etiqueta">Seleccionar Tipo de Registro a Modificar</legend>
	<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
  		  <td><div align="right">*Tipo Residuo</div></td>
		 	<td  colspan="3"><select name="cmb_residuo" id="cmb_residuo" class="combo_box">
              <option value="" selected="selected">Seleccionar</option>
              <option value="ACEITE">ACEITE</option>
              <option value="SOLIDOS">SOLIDOS</option>
            </select></td>
	 	</tr>
		<tr>
		  <td width="24%"><div align="right">*Fecha Inicio</div></td>
		  	<td width="22%">
		  		<input name="txt_fechaIni" id="txt_fechaIni" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y"); ?>" readonly="readonly"  type="text"/>		  </td>
			<td width="26%"><div align="right">*Fecha Fin</div></td>
			<td width="28%">  
				<div align="left">
		    	  <input name="txt_fechaFin" id="txt_fechaFin" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y",strtotime("+30 day")); ?>" 
					readonly="readonly" type="text"/>
		      </div>		  </td>
		</tr>
		<tr>
			<td colspan="4">
			  <div align="center">
					<input type="submit" name="sbt_consultar" value="Consultar" class="botones" title="Consultar Registros de la Bit&aacute;cora" 
					onMouseOver="window.estatus='';return true"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Bit&aacute;cora" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_bitacora.php'" />
				</div>			</td>
		</tr>
	</table>
	</fieldset>	
	</form>
	<div id="fechaIni">
        <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_modRegistro.txt_fechaIni,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Ingreso"/> 
</div>
<div id="fechaFin">
        <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_modRegistro.txt_fechaFin,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar la Fecha de Salida"/> 
</div>
<?php //Comprobamos si fue presionado el boton de consutlar
if(isset($_POST['sbt_consultar'])){?>
<form  name="frm_verDetalle" id="frm_verDetalle" method="post">
	<div id="tabla-verDetalle" class="borde_seccion2">
	<?php 
		$band=mostrarRegistros();
	?>
	</div>
	<?php if($band==1){?>
	<div align="center" id="botonesBit">
		<input type="hidden" name="hdn_btn" id="hdn_btn" value="radio"/>
    	<input name="sbt_exportar" type="submit" class="botones" id="sbt_exportar"  value="Exportar a Excel" 
		title="Exportar Registro en la Bitacora de Residuos Peligroso" onmouseover="window.status='';return true" onclick="hdn_btn.value='sbt_exportar';cambiarSubmit();"/>
  </div>			
  <?php }?>
</form>

<?php }?>
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
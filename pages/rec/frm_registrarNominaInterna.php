<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php

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
		//Este Archivo contine las funciones para realizar los calculos de la Nomina Interna.
		include ("op_registrarNominaInterna.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>			
    <style type="text/css">
		<!--				
		#titulo-registrar-nomina {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
		#consultar-empleado {position:absolute; left:30px; top:190px; width:550px; height:190px; z-index:12;}						
		#calendar-ini {position:absolute; left:265px; top:268px; width:30px; height:26px; z-index:13; }
		#calendar-fin {position:absolute; left:535px; top:268px; width:30px; height:26px; z-index:14; }
		#res-spider { position:absolute; left:130px; top:133px; width:376px; height:197px; z-index:15; }
		#tabla-empleados { position:absolute; left:30px; top:190px; width:940px; height:440px; z-index:16; overflow:scroll; }
		#btn-regresar { position:absolute; left:30px; top:680px; width:940px; height:40px; z-index:17; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar-nomina">Registrar N&oacute;mina Interna</div><?php
	
	
	if(!isset($_POST['sbt_continuar']) && !isset($_POST['sbt_guardarNomina'])){?>
		<fieldset class="borde_seccion" id="consultar-empleado" name="consultar-empleado">
		<legend class="titulo_etiqueta">Seleccionar Empleados</legend>	
		<br>		
		<form onSubmit="return valFormSeleccionarEmpleados(this);" name="frm_seleccionarEmpleados" method="post" action="frm_registrarNominaInterna.php">
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">&Aacute;rea</div></td>
					<td colspan="3"><?php
						$res = cargarComboConId("cmb_area","area","area","empleados","bd_recursos","&Aacute;rea","",
												"txt_nombre.value=''; lookup(txt_nombre,'empleados',cmb_area.value,'1');");
						if($res==0){?>
							<label class="msje_correcto">No hay &Aacute;reas Registradas</label>
							<input type="hidden" name="cmb_area" id="cmb_area" value="" /><?php
						}?>
					</td>
				</tr>
				<tr>
					<td width="25%"><div align="right">Fecha Inicio</div></td>
					<td width="25%">
						<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10" />
					</td>
					<td width="25%"><div align="right">Fecha Fin</div></td>
					<td width="25%">
						<input name="txt_fechaFin" id="txt_fechaFin" type="text" readonly="readonly" value="<?php echo date("d/m/Y"); ?>" size="10" />
						<input type="hidden" name="hdn_cantDias" id="hdn_cantDias" value="" />
					</td>
				</tr>
				<tr>
					<td><div align="right">Trabajador</div></td>
					<td colspan="3">
						<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados',cmb_area.value,'1');" 
						value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
					  </div>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="4">
						<input type="submit" name="sbt_continuar" id="sbt_continuar" value="Continuar" class="botones" 
						title="Mostrar Empleados de Acuerdo a los Par&aacute;metros Seleccionados" onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar al Men&uacute; de N&oacute;mina Interna"
						onclick="location.href='menu_nominaInterna.php'" />
					</td>
				</tr>
			</table>    
		</form>    			 		
		</fieldset>
		
		<div id="calendar-ini">
			<input name="fechaIni" id="fechaIni" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_seleccionarEmpleados.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
		</div>
		<div id="calendar-fin">
			<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_seleccionarEmpleados.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
		</div><?php	
			
	}//Cierre if(!isset($_POST['sbt_continuar']))
	else if(isset($_POST['sbt_continuar'])){?>
		<form name="frm_nominaInterna" method="post" action="frm_registrarNominaInterna.php">
			<div id="tabla-empleados" class="borde_seccion2"><?php
				mostrarEmpleados(); ?>
			</div>
			<div id="btn-regresar" align="center">
				<input type="hidden" name="hdn_area" id="hdn_area" value="<?php echo $_POST['cmb_area']; ?>" />
				<input type="hidden" name="hdn_fechaIni" id="hdn_fechaIni" value="<?php echo $_POST['txt_fechaIni']; ?>" />
				<input type="hidden" name="hdn_fechaFin" id="hdn_fechaFin" value="<?php echo $_POST['txt_fechaFin']; ?>" />
			
				<input type="submit" name="sbt_guardarNomina" id="sbt_guardarNomina" class="botones" title="Guardar Nomina" value="Guardar Nomina" 
				onmouseover="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Regresar a la P&aacute;gina de Consulta" value="Regresar" 
				onclick="location.href='frm_registrarNominaInterna.php'" />
			</div>
		</form><?php
	}//Cierre else if(isset($_POST['sbt_continuar']))
	else if(isset($_POST['sbt_guardarNomina'])){
		guardarNominaInterna();
	}?>
		 	 	         
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
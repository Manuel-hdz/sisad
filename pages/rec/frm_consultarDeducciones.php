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
		include ("op_consultarDeducciones.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
    <script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-consultar {position:absolute;left:30px;top:146px;width:313px;height:20px;z-index:11;}
		#tabla-consultarDeducciones { position:absolute; left:30px; top:190px; width:908px; height:403px; z-index:12;}
		#tabla-consultarDeduccionesFecha {position:absolute;left:30px;top:190px;width:369px;height:151px;z-index:12;}
		#calendario-Ini {position:absolute;left:294px;top:232px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:294px;top:270px;width:30px;height:26px;z-index:14;}
		#tabla-consultarDeduccionesTodo {position:absolute;left:461px;top:190px;width:326px;height:151px;z-index:14;}
		#tabla-consultarDeduccionesNombre {position:absolute;left:30px;top:390px;width:760px;height:159px;z-index:14;}
		#btnConsultar {position:absolute;left:3px;top:75px;width:331px;height:28px;z-index:14;}
		#res-spider{position:absolute; z-index:15;}
		#mostrarDeducciones {position:absolute;left:30px;top:193px;width:928px;height:419px;z-index:12;overflow:scroll}
		#btnRegresar {position:absolute;left:36px;top:666px;width:940px;height:27px;z-index:12;}
		-->
    </style>
</head>
<body><?php 

	//Obtener la fecha del sistema para la fecha inicio y fecha fin
	$txt_fechaIni = date("d/m/Y", strtotime("-30 day"));
	$txt_fechaFin = date("d/m/Y");?>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Consultar Deducciones de Empleados</div><?php
    
	//verificar si esta deleccionado el checkbox para poder mostrar el detalle
	if(isset($_POST['ckb_idDeduccion'])){?>
		<form name="frm_DetalleDeduccion" method="post" action="frm_consultarDeducciones.php">
			<div id='mostrarDeducciones' class='borde_seccion2' align="center"><?php 
				//Repostear la Informacion necesaria para mostrar la consulta previa cuando se le da click al boton regresar
				switch($_POST['hdn_tipo']){
					case "fechas":?>						
						<input type="hidden" name="sbt_consultarFecha" value="" />
						<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni']; ?>" />
						<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin']; ?>" /><?php
					break;
					case "todas":?>
						<input type="hidden" name="sbt_consultarTodo" value="" /><?php
					break;
					case "empleado":?>
						<input type="hidden" name="sbt_consultarNombre" value="" />
						<input type="hidden" name="cmb_area" value="<?php echo $_POST['cmb_area']; ?>" />
						<input type="hidden" name="txt_RFCEmpleado" value="<?php echo $_POST['txt_RFCEmpleado']; ?>" /><?php
					break;					
				}
				verDetalleDeduccion();?>
			</div>
			<div id='btnRegresar' align="center">
				<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar"/>
			</div>	

		</form><?php
	}
	else{
		if(isset($_POST["sbt_consultarFecha"])|| isset($_POST["sbt_consultarTodo"]) || isset($_POST["sbt_consultarNombre"])){?>
			<div id='mostrarDeducciones' class='borde_seccion2' align="center">
			<form name="frm_verDetalleDeduccion" method="post" action="frm_consultarDeducciones.php"><?php 
			//Repostear la Informacion necesaria para mostrar la consulta previa cuando se le da click al boton regresar
				mostrarDeducciones();?>		
			</form>
</div>
			<div id='btnRegresar' align="center">
			  <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='frm_consultarDeducciones.php';"
				title="Regresar a Consultar Deducciones"/>
</div><?php
		}
		else {
			 // fieldset para manipulacion de la consulta de deducciones buscadas por fecha?>	
			<fieldset class="borde_seccion" id="tabla-consultarDeduccionesFecha">
			<legend class="titulo_etiqueta">Consultar Deducciones a Empleados por Fecha</legend>	
			<br>
			<form onSubmit="return valFormconsultarDeduccionesFecha(this);" name="frm_consultarDeduccionesFecha" method="post" action="frm_consultarDeducciones.php">
			<table width="372" height="36" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<input type="hidden" name="hdn_tipo" value=""/>
					<td width="133"><div align="right">Fecha Inicio</div></td>
					<td width="202"><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaIni;?>" 
					readonly="readonly"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">Fecha Fin </div></td>
					<td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaFin;?>" 
					readonly="readonly"/></td>          
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input name="sbt_consultarFecha" id="sbt_consultarFecha" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
						title="Consultar Deducciones en las Fechas Seleccionadas" onclick="hdn_tipo.value='fechas'"/>
						&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_deducciones.php';"
						title="Regresar al men&uacute; de Deducciones"/>
					</td>
				</tr>
			</table>
			</form>   
			</fieldset>
			
			<?php //Calendarios para consultar capacitacion por fecha?>
			<div id="calendario-Ini">
				<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_consultarDeduccionesFecha.txt_fechaIni,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha de Inicio"/> 
			</div>
			
			<div id="calendario-Fin">
				<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_consultarDeduccionesFecha.txt_fechaFin,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha de Fin"/> 
			</div>
		
			<?php // fieldset para manipulacion de la consulta de todas las deducciones?>	
			<fieldset class="borde_seccion" id="tabla-consultarDeduccionesTodo" name="tabla-consultarDeduccionesTodo">
			<legend class="titulo_etiqueta">Consultar Todas las Deducciones</legend>	
			<br>
			<form  name="frm_consultarDeduccionesTodo" method="post" action="frm_consultarDeducciones.php">
				<div id="btnConsultar" align="center">
					<input type="hidden" name="hdn_tipo" value=""/>
					<input name="sbt_consultarTodo" type="submit" class="botones" id="sbt_consultarTodo"  value="Consultar" 
					title="Consultar Deducciones del Empleado Seleccionado"  onmouseover="window.status='';return true" onclick="hdn_tipo.value='todas'" />
					&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_deducciones.php';"
					title="Regresar al men&uacute; de Deducciones"/>
				</div>
			</form>
			</fieldset>
		
			<?php // fieldset para manipulacion de la consulta de todas las deducciones?>	
			<fieldset class="borde_seccion" id="tabla-consultarDeduccionesNombre" name="tabla-consultarDeduccionesNombre">
			<legend class="titulo_etiqueta">Consultar Deducciones por Empleado</legend>	
			<br>
			<form onSubmit="return valFormConsultarDeduccionesNom(this);" name="frm_consultarDeduccionesNom" method="post" action="frm_consultarDeducciones.php">
			<table width="760"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="152"><div align="right">&Aacute;rea</div></td>
					<td width="256"><?php $dat=cargarComboConId("cmb_area","area","area","empleados","bd_recursos","&Aacute;rea","",		
						"txt_nomEmpleado.value='';lookup(txt_nomEmpleado,'empleados',cmb_area.value,'1');");
						if($dat==0){
						echo "<label class='msje_correcto'><u><strong> NO</u></strong> hay &Aacute;reas Registradas</label>
						<input type='hidden' name='cmb_area' id='cmb_area'/>";
						}?>         
					</td>
				</tr>
				<tr>
					<td><div align="right">Nombre del Empleado</div></td>
					<td>
						<input type="text" name="txt_nomEmpleado" id="txt_nomEmpleado" onkeyup="obtenerNombreRFCEmpleado(this,'empleados',cmb_area.value,'1');" 
						value="" size="50" maxlength="70" onkeypress="return permite(event,'car',0);" class="caja_de_texto" 
						onblur="obtenerRFCEmpleado(this.value, 'txt_RFCEmpleado');"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							  <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>            
					</td>  
					<td width="144"><div align="right">RFC del Empleado</div></td>
					<td width="141">
						<input name="txt_RFCEmpleado" id="txt_RFCEmpleado" type="text" class="caja_de_texto" size="20" maxlength="60" 
						onkeypress="return permite(event,'num_car',3);" readonly="readonly"/>
					</td>
				</tr>
				<tr>
					<td colspan="4"><div align="center">
						<input type="hidden" name="hdn_tipo" value=""/>
						<input name="sbt_consultarNombre" type="submit" class="botones" id="sbt_consultarNombre"  value="Consultar" 
						title="Consultar Deducciones del Empleado Seleccionado" 
						onmouseover="window.status='';return true" onclick="hdn_tipo.value='empleado'"  />
						&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_deducciones.php';"
						title="Regresar al men&uacute; de Deducciones"/></div>
					</td>   	
				</tr>        
			</table>
			</form>
			</fieldset><?php
		}//Cierre del Else if(isset($_POST["sbt_consultarFecha"])|| isset($_POST["sbt_consultarTodo"]) || isset($_POST["sbt_consultarNombre"]))
	}//cierre del else donde se verifica si viene definido ckb_idDeduccion?>
</body>

<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
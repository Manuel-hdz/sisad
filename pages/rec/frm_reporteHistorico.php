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
		//Este archivo contiene las funciones para Generar el Reporte de Bajas_Modificaciones de Acuerdo a los Parametros Seleccionados
		include ("op_reporteHistorico.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
   
   	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
   
    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:293px; height:24px; z-index:11; }		
		#rpt-bajasEmpleado { position:absolute; left:42px; top:326px; width:865px; height:135px; z-index:13; }
		#reporte { position:absolute; left:39px; top:190px; width:921px; height:369px; z-index:21; overflow: scroll; }
		#btns-rpt { position:absolute; left:339px; top:638px; width:100px; height:13px; z-index:23; }
		#res-spider {position:absolute;z-index:15;}
		#tabla-consultarEmpleado { position:absolute; left:40px; top:188px; width:327px; height:99px; z-index:18; padding:15px; padding-top:0px;}
		-->
    </style>	
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Histórico del Personal Laboral </div>
			<?php 
		//Verificamos si viene definido en el post el boton consultarEmpleado
		if(isset($_POST["sbt_consultarEmpleado"]) || isset($_POST["sbt_consultarTodos"])){
				//Si viene definido el boton; mostrar el reporte Historico del Personal
				reporteHistorico();					
		  }  
	if(!isset($_POST["sbt_consultarEmpleado"]) && !isset($_POST["sbt_consultarTodos"])){ ?>
	<fieldset class="borde_seccion"  id="rpt-bajasEmpleado">
		<legend class="titulo_etiqueta">Consultar Historial por Nombre del Trabajador</legend>	
		<br>		
		<form onSubmit="return valFormHistoricoEmpleado(this,1);" name="frm_reporteHistoricoEmpleado" method="post" action="frm_reporteHistorico.php">
			<table width="848" height="102" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="180"><div align="right">*Nombre del Empleado</div></td>
					<td width="360">
						<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="obtenerNombreRFCEmpleado(this,'bajas_modificaciones','todo','1');
						if(txt_nombre.value!='') sbt_consultarEmpleado.style.visibility = 'visible'; 
						else sbt_consultarEmpleado.style.visibility = 'hidden' " 
						value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" onblur="obtenerRFCEmpleado(this.value, 'txt_RFCEmpleado');"/>
				  	  <div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							  <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
			  		  </div>					</td>  
					<td width="126"><div align="right">RFC del Empleado</div></td>
					<td width="115"><input name="txt_RFCEmpleado" id="txt_RFCEmpleado" type="text" class="caja_de_texto" size="20" readonly="readonly" /></td>
				</tr>
				<tr>
					<td align="center" colspan="4"><input name="sbt_consultarEmpleado" type="submit" class="botones" id="sbt_consultarEmpleado" 
						title="Consultar Registro Histórico del Empleado Seleccionado"  onmouseover="window.status='';return true" value="Consultar" style="visibility:hidden" />					  
							&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Reportes"
						onclick="location.href='menu_reportes.php'" />					  
				 			&nbsp;&nbsp;&nbsp;
				  <input name="rst_restablecer" type="reset" class="botones" id="rst_restablecer" title="Borra los criterios de b&uacute;squeda y reestablece el formulario" value="Reestablecer"/></td>
				</tr>        
			</table>
        </form>
</fieldset>



<fieldset id="tabla-consultarEmpleado" class="borde_seccion">
		<legend class="titulo_etiqueta">Consultar Histórico de los Empleados Registrados</legend>	
		<br>
		<form  name="frm_reporteHistoricoTodos" method="post" action="frm_reporteHistorico.php">
			<table align="center" border="0" width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td align="center"><input name="sbt_consultarTodos" type="submit" class="botones" id="sbt_consultarTodos" 
						title="Consultar el Histórico de los Empleados"  onmouseover="window.status='';return true" value="Consultar" />
					</td>
				</tr>
			</table>
		</form>	   
</fieldset>

<?php }  ?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>

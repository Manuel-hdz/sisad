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
		include ("op_consultarPrestamos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>	
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-consultar {position:absolute; left:30px; top:145px; width:210px; height:20px; z-index:11;}
		#tabla-consultarBonos {position:absolute; left:30px; top:190px; width:330px; height:160px; z-index:12; }
		#tabla-consultarBonosArea {position:absolute; left:440px; top:190px; width:330px; height:160px; z-index:13; }
		#tabla-consultarBonosEmpleado {position:absolute; left:30px; top:400px; width:740px; height:122px; z-index:14; }
		#calendario-Ini {position:absolute;left:240px;top:232px;width:30px;height:26px;z-index:15;}
		#calendario-Fin {position:absolute;left:240px;top:272px;width:30px;height:26px;z-index:16;}
		#res-spider{position:absolute; z-index:23;}
		#mostrarPrestamos {	position:absolute; left:30px; top:190px; width:930px; height:245px; z-index:17; overflow:scroll; }
		#btnRegresar {position:absolute; left:30px; top:490px; width:940px; height:27px; z-index:18; }
		-->
    </style>
</head>
<body>
	
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Consultar Prestamos</div><?php 
	
	
	
	//Esta variable control cuando se despliega el formulario para consultar Prestamo en base al despliegue del detalle de uno
	$ctrl_desplegar = 1;
	//Mostrar el Detalle del Prestamo Seleccionado
	if(isset($_POST["hdn_verDetalle"])){?>
		
			<div id="mostrarPrestamos" class="borde_seccion2" align="center"><?php				
				//Mostrar el detalle del prestamo
				$res = verDetallePrestamo(); ?>			
        	</div>
			
			<div id="btnRegresar" align="center">
				<table class="tabla_frm" width="100%" cellpadding="5" cellspacing="5">
					<tr>
						<td align="right">
							<form name="frm_regresarDelDetallePrestamo" method="post" action="frm_consultarPrestamos.php"><?php
								//Repostear la Informacion necesaria para mostrar la consulta previa cuando se le da click al boton regresar
								switch($_POST['hdn_tipoReporte']){
									case "fechas":?>						
										<input type="hidden" name="sbt_consultarFecha" value="" />
										<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni']; ?>" />
										<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin']; ?>" /><?php
									break;
									case "area":?>
										<input type="hidden" name="sbt_consultarArea" value="" />
										<input type="hidden" name="cmb_area" value="<?php echo $_POST['cmb_area']; ?>" /><?php
									break;
									case "empleado":?>
										<input type="hidden" name="sbt_consultarNombre" value="" />
										<input type="hidden" name="txt_RFCEmpleado" value="<?php echo $_POST['txt_RFCEmpleado']; ?>" /><?php
									break;					
								}?>				
								<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Consulta Realizada" />
							</form>
						</td>
						<td align="left"><?php
							if($res==1){?>
								<form name="frm_exportarAbonos" method="post" action="guardar_reporte.php">
									<input type="hidden" name="hdn_consulta" id="hdn_consulta" value="Esta variable no requiere valor, solo requiere estar presente en el POST" />
									<input type="hidden" name="hdn_idPrestamo" id="hdn_idPrestamo" value="<?php echo $_POST['ckb_idPrestamo'];?>" />
									<input type="hidden" name="hdn_origen" id="hdn_origen" value="reporteAbonos" />
									<input type="submit" name="sbt_exportarDatos" class="botones" value="Exportar" title="Exportar Abonos" />
								</form><?php
							}?>
						</td>
					</tr>
				</table>				
			</div><?php		
		$ctrl_desplegar = 0;
		
	}//Cierre if(isset($_POST["hdn_verDetalle"]))
	
	
	
	
	//Mostrar los prestamos de acuerdo a los parametros seleccioandos por el usuario
	if( (isset($_POST["sbt_consultarFecha"])|| isset($_POST["sbt_consultarArea"]) || isset($_POST["sbt_consultarNombre"]) ) && !isset($_POST["hdn_verDetalle"])){?>
        <div id="mostrarPrestamos" class="borde_seccion2" align="center">
			<form name="frm_verDetallePrestamo" method="post" action="frm_consultarPrestamos.php">
				<input type="hidden" name="hdn_verDetalle" id="hdn_verDetalle" value="si" /><?php
            	mostrarPrestamos(); ?>
			</form>
        </div>
		<div id='btnRegresar' align="center">
          <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='frm_consultarPrestamos.php';" title="Regresar a Consultar"/>
		</div><?php
	}
	
	
	
	
	//Desplegar el formulario donde se seleccionan los parametros para consultar los Prestamos
	else if($ctrl_desplegar==1){?>
	        
        <fieldset class="borde_seccion" id="tabla-consultarBonos" name="tabla-consultarPrestamos">
        <legend class="titulo_etiqueta">Consultar Prestamos a Empleados por Fechas</legend>	
        <br>
        <form onSubmit="return valFormConsultarPrestamoFecha(this);" name="frm_consultarPrestamoFecha" method="post" action="frm_consultarPrestamos.php">
        <table width="325" height="36" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td width="89"><div align="right">Fecha Inicio</div></td>
                <td width="246">
					<input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" maxlength="10" 
                	value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" readonly="readonly"/>
                </td>
            </tr>
            <tr>
                <td><div align="right">Fecha Fin </div></td>
                <td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" maxlength="10" 
                onkeypress="return permite(event,'car',0);" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>          
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <input name="sbt_consultarFecha" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
                    title="Consultar Prestamos por Fecha"/>
					&nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_prestamos.php';"
                    title="Regresar al men&uacute; de Prestamos"/>
                </td>
            </tr>
        </table>
        </form>
		</fieldset>
		
        <?php //Calendarios para consultar capacitacion por fecha?>
        <div id="calendario-Ini">
          	<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_consultarPrestamoFecha.txt_fechaIni,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Inicio"/> 
		</div>
        
        <div id="calendario-Fin">
        	<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_consultarPrestamoFecha.txt_fechaFin,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Fin"/> 
		</div>
        
        <?php //Fieldset para la consulta de bonos por nombre de empleado?>
        <fieldset class="borde_seccion" id="tabla-consultarBonosEmpleado" name="tabla-consultarPrestamosEmpleado">
        <legend class="titulo_etiqueta">Consultar Prestamos por Nombre del Empleado</legend>	
        <br>
        <form onSubmit="return valFormConsultarPrestamosEmpleado(this);" name="frm_consultarPrestamosEmpleado" method="post" action="frm_consultarPrestamos.php">
        <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td width="155"><div align="right">*Nombre del Empleado</div></td>
                <td width="240">
                    <input type="text" name="txt_nomEmpleado" id="txt_nomEmpleado" onkeyup="obtenerNombreRFCEmpleado(this,'empleados','todo','1');" 
                    value="" size="40" maxlength="70" onkeypress="return permite(event,'car',0);" />
                    <div id="res-spider">
                        <div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
                            <img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                            <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
                        </div>
                    </div>          
                </td>  
                <td width="128"><div align="right">RFC del Empleado</div></td>
                <td width="161"><input name="txt_RFCEmpleado" id="txt_RFCEmpleado" type="text" class="caja_de_texto" size="20" maxlength="60" readonly="readonly"/></td>
            </tr>            
            <tr>
                <td colspan="4"><div align="center">
                    <input name="sbt_consultarNombre" type="submit" class="botones" id="sbt_consultar"  value="Consultar" title="Consultar Prestamos del Empleado Seleccionado" 
                    onmouseover="window.status='';return true" />
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_prestamos.php';"
                    title="Regresar al men&uacute; de Prestamos"/></div>
                </td>   	
            </tr>        
        </table>
        </form>
		</fieldset>
    
        <?php //Fieldset para la consulta de bonos por nombre de empleado?>
        <fieldset class="borde_seccion" id="tabla-consultarBonosArea" name="tabla-consultarPrestamosArea">
        <legend class="titulo_etiqueta">Consultar Prestamos del Empleado por &Aacute;rea</legend>	
        <br>
        <form onSubmit="return valFormConsultarPrestamoArea(this);" name="frm_consultarPrestamoArea" method="post" action="frm_consultarPrestamos.php">
        <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td><div align="right">&Aacute;rea</div></td>
                <td><?php 
					$conn=conecta('bd_recursos');
                    $result=mysql_query("SELECT DISTINCT area FROM empleados ORDER BY area");
					if($row=mysql_fetch_array($result)){?>					
				  		<select name="cmb_area" id="cmb_area"class="combo_box">
							<option value="">Seleccionar &Aacute;rea</option><?php 
								do{								
									echo "<option value='$row[area]'>$row[area]</option>";								
								}while ($row=mysql_fetch_array($result));?>
						</select><?php
					}
					else { ?>
						<label class="msje_correcto"><u><strong>NO</strong></u> Hay Datos Registrados</label>
						<input type="hidden" name="cmb_area" id="cmb_area" value="" />
					<?php } ?>
				</td>
            </tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr>
                <td colspan="2" align="center">
					<input name="sbt_consultarArea" type="submit" class="botones" value="Consultar" 
                	onmouseover="window.status='';return true" title="Consultar Bonos del &Aacute;rea" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar2" type="button" class="botones" value="Regresar" onclick="location.href='menu_prestamos.php';"
                	title="Regresar al men&uacute; de Prestamos"/>
	  		</td>
            </tr>
        </table>
        </form>
	</fieldset><?php
	}//Cierre del Else if(isset($_POST["sbt_consultarFecha"])|| isset($_POST["sbt_consultarArea"]) || isset($_POST["sbt_consultarNombre"]))?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
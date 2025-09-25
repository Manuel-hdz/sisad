	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Este Archivo incluye las funciones para Mostrar el Concentrado de Traspaleos y Estimaciones de una Quincena Dada
		include ("op_consultarConciliacion.php");
		//Este Archivo contiene las funciones para crear el Reporte de Cubicos Acumulados por distancias recorridas
		include ("op_reporteAcumulados.php");?>
		

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
		<!--
		#titulo-consultar {position:absolute;left:30px;top:146px;width:210px;height:20px;z-index:11;}
		#tabla-consultarConciliacion {position:absolute;left:30px;top:190px;width:423px;height:151px;z-index:12;}
		#detalle_conciliacion {position:absolute;left:30px;top:190px;width:940px;height:435px;z-index:13; overflow:scroll;}
		#btn-regresar {position:absolute;left:30px;top:670px;width:940px;height:40px;z-index:14;}
		#reporte-acumuldo { position:absolute; left:30px; top:190px; width:940px; height:390px; z-index:15; overflow:scroll; }
		#tabla-amortizaciones { position:absolute; left:40px; top:40px; width:430px; height:390px; z-index:16;}
		#tabla-costos { position:absolute; left:510px; top:40px; width:430px; height:390px; z-index:17; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Consultar Conciliaciones</div><?php 
	
	//Obtener las quincenas diponibles en el siguiente formato Años Diponibles, Meses Diponibles y Quincenas Disponibles
	$quincenasDisponibles = obtenerQuincenas();
	
	
	//Mostrar el Formaulario para seleccionar la Quincena de al cual se quiere generar la Conciliacion
	if(!isset($_POST['sbt_consultar']) && !isset($_POST['sbt_verReporteAcumulado'])){?>
		<fieldset class="borde_seccion" id="tabla-consultarConciliacion" name="tabla-consultarConciliacion">
		<legend class="titulo_etiqueta">Seleccionar Quincena</legend>	
		<br>
		<form onSubmit="return valFormConsultarConciliacion(this);" name="frm_consultarConciliacion" method="post" action="frm_consultarConciliacion.php">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="30%"><div align="right">Quincena</div></td>
				<td width="70%"><?php
					if($quincenasDisponibles!=0){?>
						<select name="cmb_noQuincena" id="cmb_noQuincena" class="combo_box">
							<option value="">No. Quincena</option><?php
							foreach($quincenasDisponibles['numeros'] as $ind => $numQuincena){?>
								<option value="<?php echo $numQuincena; ?>"><?php echo $numQuincena; ?></option><?php
							}?>							
						</select>
						
						<select name="cmb_mes" id="cmb_mes" class="combo_box">
							<option value="">Mes</option><?php
							foreach($quincenasDisponibles['meses'] as $ind => $mes){?>
								<option value="<?php echo $mes; ?>"><?php echo $mes; ?></option><?php
							}?>							
						</select>
						
						<select name="cmb_anio" id="cmb_anio" class="combo_box">
							<option value="">A&ntilde;o</option><?php
							foreach($quincenasDisponibles['anios'] as $ind => $anio){?>
								<option value="<?php echo $anio; ?>"><?php echo $anio; ?></option><?php
							}?>							
						</select><?php
					}
					else {?>
						<label class="msje_correcto">No Hay Datos Registrados</label>
						<input type="hidden" name="cmb_noQuincena" id="cmb_noQuincena" value="" /><?php
					}?>
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>				
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_consultar" type="submit" class="botones" value="Consultar " onmouseover="window.status='';return true" 
					title="Consultar Conciliaciones"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_conciliaciones.php';"
					title="Regresar al men&uacute; de Conciliaciones"/>
				</td>
			</tr>
		</table>
		</form>   
		</fieldset><?php
	}//	if(!isset($_POST['sbt_consultar'])){



	//Si esta definido  sbt_consultar se muestran las conciliaciones 
	if(isset($_POST['sbt_consultar'])){
	
		//Liberar Datos de la SESSION del Reporte de Acumulados si Existen
		if(isset($_SESSION['reporteAcumulados']))
			unset($_SESSION['reporteAcumulados']);?>
			
				
		<div id='detalle_conciliacion' class='borde_seccion2' align="center"><?php
			$datosExportarEst = mostrarConciliacionEstim();
			$datosExportarTrasp = mostrarConciliacionTrasp();
			$datosExportarEquipo = mostrarConciliacionEquipo();
			?>
		</div>
		
		<div id='btn-regresar' align="center">
        <table width="50%" cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
                <td align="center" width='100%'>
                    <input name="btn_regresar2" type="button" class="botones" value="Regresar" onclick="location.href='frm_consultarConciliacion.php';"
                    title="Regresar a la Consulta de Conciliaciones"/>
				</td><?php									
				//Colocar el Boton para exportar la Conciliacion a Excel
				if (count($datosExportarEst) > 0 && count($datosExportarTrasp) > 0){ ?>                	
					<td align="center" width='100%'>
						<form action="guardar_reporte.php" method="post" onsubmit="return complementarConciliacion();">
							<input name="hdn_contratista" type="hidden" value="" id="hdn_contratista"/>
							<input name="hdn_jefeSeccion" type="hidden" value="" id="hdn_jefeSeccion"/>                         	
							<input name="hdn_revisor" type="hidden" value="" id="hdn_revisor"/>                             
							
							<input name="hdn_quincenaEst" type="hidden" value="<?php echo $datosExportarEst[0];  ?>" />
							<input name="hdn_noQuincena" type="hidden" value="<?php echo $datosExportarTrasp[0];  ?>" />
							<input name="hdn_datoEquipo" type="hidden" value="<?php if(isset($datosExportarEquipo[0])) echo $datosExportarEquipo[0];?>"/>
							<input type="hidden" name="hdn_consulta" value="" />
							<input name="hdn_origen" type="hidden" value="consultarConciliacion" />
							
							<input name="hdn_msg" type="hidden" value="<?php echo $datosExportarEst[1]; ?>" />
							<input name="hdn_msgTrasp" type="hidden" value="<?php echo $datosExportarTrasp[1]; ?>" />
							<input name="hdn_msgEquipo" type="hidden" value="<?php if(isset($datosExportarEquipo[1])) echo $datosExportarEquipo[1];?>"/>
							
							<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
							title="Exportar a Excel los Datos de la Consulta Realizada" onmouseover="window.estatus='';return true"/>
						</form>
					</td><?php 					
				}
				//Colocar el boton para ver los datos del reporte de Acumulados por Obras de Costos y Amortizaciones
				if (count($datosExportarTrasp) > 0){ ?>
					<td align="center" width="100%">
						<form name="frm_verReporteAcumulados" method="post" action="frm_consultarConciliacion.php">
							<input type="hidden" name="cmb_noQuincena" value="<?php echo $_POST['cmb_noQuincena'];?>" />
							<input type="hidden" name="cmb_mes" value="<?php echo $_POST['cmb_mes'];?>" />
							<input type="hidden" name="cmb_anio" value="<?php echo $_POST['cmb_anio'];?>" />
														
							<input type="submit" name="sbt_verReporteAcumulado" class="botones_largos" value="Reporte Acumulados" 
							title="Reporte de Acumulados por Obras de Costos y Amortizaciones" onmouseover="window.status='';return true" />
						</form>
					</td><?php 
				}?>								
            </tr>
        </table>
		</div><?php
	}
	
		
	//Si esta definido  sbt_verReporteAcumulado se muestra el Reporte de Metros Cubicos Acumulados por Distancias en las Obras de Costos y Amortizaciones
	if(isset($_POST['sbt_verReporteAcumulado'])){?>
		<div id="reporte-acumuldo" class="borde_seccion2" align="center">		
		<label class="titulo_etiqueta">Reporte de Acumulados de la Quincena <?php echo $_POST['cmb_noQuincena']." ".$_POST['cmb_mes']." ".$_POST['cmb_anio']; ?></label><?php
			analizarDetalleTraspaleos();
		//</div> El cierre de este DIV se encuentra en la Funcion "mostrarReporteAcumulado" en el archivo op_reporteAcumulados.php
	}?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include ("op_reporteProduccion.php");
		include ("op_borrarTemporales.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionProduccion.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-generar {position:absolute;left:30px;top:146px;	width:342px;height:20px;z-index:11;}
		#tabla-generarReporteMes {position:absolute;left:30px;top:190px;width:290px;height:120px;z-index:14;}
		#tabla-generarReporteFecha {position:absolute;left:420px;top:190px;width:283px;height:125px;z-index:14;}
		#calendario {position:absolute;left:625px;top:232px;width:30px;height:26px;z-index:13;}		
		#mostrarRepoFecha {position:absolute;left:30px;top:190px;width:900px;height:431px;z-index:14; overflow:scroll}
		#btnRegExpRepoFecha {position:absolute;left:46px;top:674px;width:900px;height:30px;z-index:14;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-generar">Producci&oacute;n - Consulta de Producci&oacute;n</div>
    <?php		
	
	//Si viene en el post sbt_continuarFecha desplegar la tabla de resultados mostrando el reporte por fecha
	if(isset($_POST['sbt_continuarFecha'])){?>
		<form name="frm_mostrarRepoFecha" method="post" action="guardar_reporte.php">
			<div id='mostrarRepoFecha' class='borde_seccion2'><?php
				//Mandar llamar cada una de las funciones que muestran las diferentes tablas
				$datosPro = mostrarRepoFechaProd();
				echo "<br><br>";
				$datosEq = mostrarRepoFechaEq();
				echo "<br><br>";
				$datosSeg = mostrarRepoFechaSeg();
				echo "<br><br>";
				$datosCol = mostrarRepoFechaCol();?>
            </div>
            <div id='btnRegExpRepoFecha' align="center"><?php
				//Con que cualquiera de los arreglos que regresan las funciones contenga datos mostrar el boton de exportar
            	if(count($datosPro) >0 || count($datosEq) >0  || count($datosSeg) >0  || count($datosCol) >0 ){
                    if (count($datosPro) >0){?>
                    	<input name="hdn_msgPro" type="hidden" value="<?php echo $datosPro[0];?>"/>
                        <input name="hdn_presupuestoDiario" type="hidden" value="<?php echo $datosPro[1];?>"/>
						<input name="hdn_volProDiario" type="hidden" value="<?php echo $datosPro[2];?>"/>
						<input name="hdn_presAcumulado" type="hidden" value="<?php echo $datosPro[3];?>"/>
						<input name="hdn_volumenRealTotal" type="hidden" value="<?php echo $datosPro[4];?>"/>
						<input name="hdn_observaciones" type="hidden" value="<?php echo $datosPro[5];?>"/><?php
					}
					if (count($datosEq) >0){?>
                        <input name="hdn_msgEq" type="hidden" value="<?php echo $datosEq[0];?>"/><?php
					}
					if (count($datosSeg) >0){?>
                        <input name="hdn_msgSeg" type="hidden" value="<?php echo $datosSeg[0];?>"/>
                        <input name="hdn_consultaSeg" type="hidden" value="<?php echo $datosSeg[1];?>"/>
                        <input name="hdn_numAcc" type="hidden" value="<?php echo $datosSeg[2];?>"/>
						<input name="hdn_numRegistros" type="hidden" value="<?php echo $datosSeg[3];?>"/><?php
					}
                    if (count($datosCol) >0){?>
						<input name="hdn_msgCol" type="hidden" value="<?php echo $datosCol[0];?>"/>
    	                <input name="hdn_consultaCol" type="hidden" value="<?php echo $datosCol[1];?>"/><?php
					}?>
					
					<input name="hdn_fecha" type="hidden" value="<?php echo modFecha($_POST['txt_fecha'],3);?>"/>
                    <input name="hdn_origen" type="hidden" value="ReporteFechas"/>
                    <input name="sbt_exportar" id="sbt_exportar" type="submit" class="botones" value="Exportar Datos" title="Exportar Datos de las Consultas"
                    onmouseover="window.estatus='';return true"/>
					&nbsp;&nbsp;&nbsp;<?php
				}?>
                <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Reportes" 
                onMouseOver="window.status='';return true" onclick="location.href='frm_reporteProduccion.php';" />
            </div>
		</form><?php
	}
	else if(isset($_POST['sbt_continuarMes'])){?>
		<form name="frm_mostrarRepoFecha" method="post" action="guardar_reporte.php">
			<div id="mostrarRepoFecha" class="borde_seccion2" align="center"><?php
				//Mostrar el reporte mensual
				$datosReporte = mostrarRepoMensual();
				$datosRep=explode("¬",$datosReporte);
				?>
            </div>
            <div id="btnRegExpRepoFecha" align="center"><?php
				//Con que cualquiera de los arreglos que regresan las funciones contenga datos mostrar el boton de exportar
            	if($datosReporte!=""){?>
                    <input name="hdn_origen" type="hidden" value="ReportePeriodo"/>
					<input name="hdn_periodo" type="hidden" value="<?php echo $datosRep[1]; ?>"/>
					<input type="button" name="btn_verGrafico" id="btn_verGrafico" class="botones" value="Ver Gr&aacute;fico" title="Ver Gr&aacute;fica del Reporte" 
					onclick="window.open('verGrafica.php?imagen=<?php echo $datosRep[0];?>','_blank','top=50, left=50, width=940, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>
					&nbsp;&nbsp;&nbsp;	
                    <input name="sbt_exportar" id="sbt_exportar" type="submit" class="botones" value="Exportar Datos" title="Exportar Datos de las Consultas"
                    onmouseover="window.estatus='';return true"/>
					&nbsp;&nbsp;&nbsp;<?php
				}?>
                <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Consultas" 
                onMouseOver="window.status='';return true" onclick="location.href='frm_reporteProduccion.php';" />
          </div>
		</form><?php		
	}	
	else if(!isset($_POST['sbt_continuarFecha']) && !isset($_POST['sbt_continuarMes'])){
		borrarGraficosCalidad();
		?>
		<fieldset class="borde_seccion" id="tabla-generarReporteMes" name="tabla-generarReporteMes">
        <legend class="titulo_etiqueta">Buscar Registro por Mes</legend>	
        <br>
        <form onSubmit="return valFormGenerarRepoMes(this);" name="frm_generarReporteMes" method="post" action="frm_reporteProduccion.php">
        <table cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
              	<td><div align="right">Periodo</div></td>
                <td><?php 
                    $res=cargarComboOrdenado('cmb_periodo','periodo','presupuesto','bd_produccion','Seleccione','','fecha_inicio');
                    if($res==0){?>
                        <label class="msje_correcto">No Hay Periodos Registrados</label>
                        <input type="hidden" name="cmb_periodo" value="" /><?php
                    }?>
              	</td>            
            </tr>
            <tr>
              	<td colspan="2">
                <div align="center">
                	<input name="sbt_continuarMes" type="submit" class="botones" id="sbt_continuarMes"  value="Continuar" title="Continuar"
                    onmouseover="window.status='';return true"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otra Consulta" 
                    onMouseOver="window.status='';return true" onclick="location.href='frm_seleccionarConsulta.php';" />
                </div>          
                </td>
            </tr>
        </table>
        </form>
        </fieldset>
        
        <fieldset class="borde_seccion" id="tabla-generarReporteFecha" name="tabla-generarReporteFecha">
        <legend class="titulo_etiqueta">Buscar Registro por Fecha</legend>	
        <br>
        <form name="frm_generarReporteFecha" method="post" action="frm_reporteProduccion.php">
        <table  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
                <td><div align="right">Fecha</div></td>
                <td><input name="txt_fecha" id="txt_fecha" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>		 			
			</tr> 
            <tr>
				<td colspan="2">
					<div align="center">
                    <input name="sbt_continuarFecha" type="submit" class="botones" id="sbt_continuarFecha"  value="Continuar" title="Continuar con Reporte" 
                    onmouseover="window.status='';return true"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otra Consulta" 
                    onMouseOver="window.status='';return true" onclick="location.href='frm_seleccionarConsulta.php';" />
                    </div>          
                </td>
            </tr>
        </table>
        </form>
        </fieldset>
        <div id="calendario">
            <input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_generarReporteFecha.txt_fecha,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha"/>
        </div><?php
	}//FIN else if(!isset($_POST['sbt_continuarFecha'])){?>        

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
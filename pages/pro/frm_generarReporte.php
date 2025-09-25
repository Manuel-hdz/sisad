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
		include ("op_generarReporte.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionProduccion.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<script type="text/javascript" language="javascript">
		function mostrarDetalles(msje){
			mensaje=msje.replace(/<br>/g,"\n");
			alert(mensaje);
		}
		
		function valFormGenerarRepoCte(frm_generarReporteCte){
			if(frm_generarReporteCte.cmb_cliente.value==""){
				alert("Seleccionar el Cliente");
				return false;
			}
			else
				return true;
		}
	</script>

    <style type="text/css">
		<!--
		#titulo-generar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-generarReporteMes {position:absolute;left:30px;top:190px;width:290px;height:120px;z-index:14;}
		#tabla-generarReporteFecha {position:absolute;left:420px;top:190px;width:283px;height:125px;z-index:14;}
		#tabla-generarReporteCliente {position:absolute;left:30px;top:370px;width:355px;height:210px;z-index:14;}
		#calendario {position:absolute;left:625px;top:232px;width:30px;height:26px;z-index:13;}		
		#mostrarRepoFecha {position:absolute;left:30px;top:190px;width:900px;height:431px;z-index:14; overflow:scroll}
		#btnRegExpRepoFecha {position:absolute;left:46px;top:674px;width:900px;height:30px;z-index:14;}
		
		#calendarioCte1 {position:absolute;left:228px;top:447px;width:30px;height:26px;z-index:13;}	
		#calendarioCte2 {position:absolute;left:228px;top:482px;width:30px;height:26px;z-index:13;}	
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-generar">Reporte de Producci&oacute;n</div><?php		
	
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
                onMouseOver="window.status='';return true" onclick="location.href='frm_generarReporte.php';" />
            </div>
		</form><?php
	}
	else if(isset($_POST['sbt_continuarMes'])){?>
		<form name="frm_mostrarRepoFecha" method="post" action="guardar_reporte.php">
			<div id="mostrarRepoFecha" class="borde_seccion2" align="center"><?php
				//Mostrar el reporte mensual
				$periodo = mostrarRepoMensual();?>
            </div>
            <div id="btnRegExpRepoFecha" align="center"><?php
				//Con que cualquiera de los arreglos que regresan las funciones contenga datos mostrar el boton de exportar
            	if($periodo!=""){?>					
                    <input name="hdn_origen" type="hidden" value="ReportePeriodo"/>
					<input name="hdn_periodo" type="hidden" value="<?php echo $periodo; ?>"/>
                    <input name="sbt_exportar" id="sbt_exportar" type="submit" class="botones" value="Exportar Datos" title="Exportar Datos de las Consultas"
                    onmouseover="window.estatus='';return true"/>
					&nbsp;&nbsp;&nbsp;<?php
				}?>
                <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Reportes" 
                onMouseOver="window.status='';return true" onclick="location.href='frm_generarReporte.php';" />
          </div>
		</form><?php		
	}
	else if(isset($_POST['sbt_continuarCliente'])){
		?>
			<div id="mostrarRepoFecha" class="borde_seccion2" align="center">
				<?php
				if($_POST["rdb_tipoReporte"]==1)
					$res = mostrarRepoCliente1();
				else
					$res = mostrarRepoCliente2();
				?>
            </div>
			<div id="btnRegExpRepoFecha" align="center"><?php
				//Con que cualquiera de los arreglos que regresan las funciones contenga datos mostrar el boton de exportar
				if($res!=""){
					$datos=explode("¬",$res);
					?>					
					<input name="btn_exportar" id="btn_exportar" type="button" class="botones" value="Exportar Datos" title="Exportar Datos del Reporte a Excel" 
					onclick="location.href='guardar_reporte.php?cte=<?php echo $datos[0]?>&f1=<?php echo $datos[1]?>&f2=<?php echo $datos[2]?>&forma=<?php echo $datos[3]?>&tipoRep=Cte'"/>
					&nbsp;&nbsp;&nbsp;<?php
				}?>
				<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla de Reportes" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_generarReporte.php';" />
			</div>
		<?php
	}
	else if(!isset($_POST['sbt_continuarFecha']) && !isset($_POST['sbt_continuarMes']) && !isset($_POST['sbt_continuarCliente'])){?>
		<fieldset class="borde_seccion" id="tabla-generarReporteMes" name="tabla-generarReporteMes">
        <legend class="titulo_etiqueta">Buscar Registro por Mes</legend>	
        <br>
        <form onSubmit="return valFormGenerarRepoMes(this);" name="frm_generarReporteMes" method="post" action="frm_generarReporte.php">
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
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute;  Reportes" 
                    onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php';" />
                </div>          
                </td>
            </tr>
        </table>
        </form>
        </fieldset>
        
        <fieldset class="borde_seccion" id="tabla-generarReporteFecha" name="tabla-generarReporteFecha">
        <legend class="titulo_etiqueta">Buscar Registro por Fecha</legend>	
        <br>
        <form name="frm_generarReporteFecha" method="post" action="frm_generarReporte.php">
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
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute;  Reportes" 
                    onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php';" />
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
        </div>
		
		<fieldset class="borde_seccion" id="tabla-generarReporteCliente" name="tabla-generarReporteCliente">
        <legend class="titulo_etiqueta">Buscar Registro por Cliente</legend>	
        <br>
        <form onSubmit="return valFormGenerarRepoCte(this);" name="frm_generarReporteCte" method="post" action="frm_generarReporte.php">
        <table cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
              	<td><div align="right">Cliente</div></td>
                <td><?php 
                    $res=cargarComboOrdenado('cmb_cliente','cliente','detalle_colados','bd_produccion','Seleccione','','cliente');
                    if($res==0){?>
                        <label class="msje_correcto">No Hay Clientes Registrados</label>
                        <input type="hidden" name="cmb_cliente" value="" /><?php
                    }?>
              	</td>
            </tr>
			<tr>
                <td><div align="right">Fecha Inicio</div></td>
                <td><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y", strtotime("-1 month")); ?>" readonly="readonly"/></td>		 			
			</tr>
			<tr>
                <td><div align="right">Fecha Fin</div></td>
                <td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>		 			
			</tr>
			<tr>
                <td><div align="right">Tipo Reporte</div></td>
                <td>
					<input type="radio" name="rdb_tipoReporte" id="rdb_tipoReporte" value="1" checked="checked"/>Representaci&oacute;n 1
					<input type="radio" name="rdb_tipoReporte" id="rdb_tipoReporte" value="2"/>Representaci&oacute;n 2
				</td>
			</tr>
            <tr>
              	<td colspan="2">
                <div align="center">
                	<input name="sbt_continuarCliente" type="submit" class="botones" id="sbt_continuarCliente" value="Continuar" title="Continuar"
                    onmouseover="window.status='';return true"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute;  Reportes" 
                    onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php';" />
                </div>          
                </td>
            </tr>
        </table>
        </form>
        </fieldset>
		
		<div id="calendarioCte1">
            <input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_generarReporteCte.txt_fechaIni,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Inicio"/>
        </div>
		
		<div id="calendarioCte2">
            <input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_generarReporteCte.txt_fechaFin,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Fin"/>
        </div>
		<?php
	}//FIN else if(!isset($_POST['sbt_continuarFecha'])){?>        

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
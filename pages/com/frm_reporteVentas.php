<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Este archivo contiene las funciones para Generar el Reporte de Ventas de Acuerdo a los Parametros Seleccionados
		include ("op_reporteVentas.php");	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:138px; height:24px; z-index:11; }
		#rpt-cliente { position:absolute; left:30px; top:190px; width:430px; height:283px; z-index:12; }
		#rpt-fecha { position:absolute; left:30px; top:512px; width:434px; height:117px; z-index:14; }
		#rpt-ganacia { position:absolute; left:529px; top:190px; width:394px; height:252px; z-index:15; }
		#rpt-factura { position:absolute; left:531px; top:485px; width:401px; height:143px; z-index:14; }
		#calendar-uno { position:absolute; left:327px; top:325px; width:30px; height:26px; z-index:16; }
		#calendar-dos { position:absolute; left:328px; top:364px; width:30px; height:26px; z-index:17; }
		#calendar-tres { position:absolute; left:261px; top:552px; width:30px; height:26px; z-index:18; }
		#calendar-cuatro { position:absolute; left:261px; top:592px; width:30px; height:26px; z-index:19; }
		#calendar-cinco { position:absolute; left:785px; top:559px; width:30px; height:26px; z-index:18; }
		#calendar-seis { position:absolute; left:786px; top:597px; width:30px; height:26px; z-index:19; }
		#sugerencias { position:absolute; left:100px; top:273px; width:332px; height:40px; z-index:20; }
		#reporte { position:absolute; left:30px; top:190px; width:921px; height:369px; z-index:21; overflow: scroll }
		#boton-cancelar { position:absolute; left:459px; top:680px; width:119px; height:34px; z-index:22; }
		#btns-regpdf { position: absolute; left:319px; top:670px; width:400px; height:40px; z-index:23; }								  
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Ventas </div>
	
<?php
	//Verificar si estan definidos los datos del reporte de compras en la SESSION, de estarlo procedemos a pasarlos al arreglo POST
	if(isset($_POST['hdn_tipoRpt'])){
		switch($_POST['hdn_tipoRpt']){
			case 1:
				$_POST['txt_cliente'] = $_SESSION['datosRptVentas']['txt_cliente'];
				$_POST['txt_fechaIni'] = $_SESSION['datosRptVentas']['txt_fechaIni'];
				$_POST['txt_fechaFin'] = $_SESSION['datosRptVentas']['txt_fechaFin'];
				unset($_SESSION['datosRptVentas']);//Quitar los datos de la SESSION
			break;
			case 2:
				$_POST['txt_nivelInf'] = $_SESSION['datosRptVentas']['txt_nivelInf'];
				$_POST['txt_nivelSup'] = $_SESSION['datosRptVentas']['txt_nivelSup'];
				unset($_SESSION['datosRptVentas']);//Quitar los datos de la SESSION
			break;
			case 3:
				$_POST['txt_fechaIni'] = $_SESSION['datosRptVentas']['txt_fechaIni'];
				$_POST['txt_fechaFin'] = $_SESSION['datosRptVentas']['txt_fechaFin'];
				unset($_SESSION['datosRptVentas']);//Quitar los datos de la SESSION
			break;
			case 4:
				$_POST['cmb_factura'] = $_SESSION['datosRptVentas']['cmb_factura'];
				$_POST['txt_fechaIni'] = $_SESSION['datosRptVentas']['txt_fechaIni'];
				$_POST['txt_fechaFin'] = $_SESSION['datosRptVentas']['txt_fechaFin'];
				unset($_SESSION['datosRptVentas']);//Quitar los datos de la SESSION
			break;
		}
	}
	
	$band = 0;
	//Mostrar el Detalle de un Pedido
	if(isset($_POST['verDetalle'])){					
		$band = 1;		
		//Obtener el valor de la clave de la Entrada seleccionada
		$clave = "";
		$tam = count($_POST);
		$cont = 1;
		foreach($_POST as $nombre_campo => $valor){								
			if($cont==$tam)
				$clave = $valor;				
			$cont++;
		}
		//Mostrar el detalle del Pedido Seleccionado
		mostrarDetalleRV($clave,$no_reporte);						
	}
		
	
	if(isset($_POST['txt_cliente']) || isset($_POST['txt_fechaIni']) || isset($_POST['txt_nivelInf']) || isset($_POST['cmb_factura'])){				
		//Quitar los datos de la grafica de la SESSION, antes de entrar a generar el nuevo reporte, en el caso de que exista uno previo
		unset($_SESSION['datosGrapVentas']);
		
		if(isset($_POST['txt_cliente']) && isset($_POST['txt_fechaIni']) && isset($_POST['txt_fechaFin'])){
			$band = 1;		
			generarReporte(1);		
		}
	
		if(isset($_POST['txt_nivelInf'])){
			$band = 1;
			generarReporte(2);		
		}		
		if(!isset($_POST['cmb_factura']) && !isset($_POST['txt_cliente']) && isset($_POST['txt_fechaIni']) && isset($_POST['txt_fechaFin'])){
			$band = 1;		
			generarReporte(3);
		}		
		if(isset($_POST['cmb_factura']) && isset($_POST['txt_fechaIni']) && isset($_POST['txt_fechaFin'])){
			$band = 1;		
			generarReporte(4);
		}
	}			
	
	    
	if($band==0){ ?>	
	<fieldset id="rpt-cliente" class="borde_seccion">
	<legend class="titulo_etiqueta">Reporte por Cliente</legend>
	<br />
	<form onsubmit="return verFormReportesVentas(this,1);" name="frm_rptClientes" method="post" action="frm_reporteVentas.php">
        <table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">    	
            <tr>
                <td align="center" colspan="3"><div align="center">Nombre o Raz&oacute;n Social </div></td>
            </tr>
            <tr>
                <td colspan="3" align="center"><input name="txt_cliente" id="txt_cliente" type="text" class="caja_de_texto" size="60" maxlength="80"
                     onkeyup="lookup(this,'bd_compras','clientes','razon_social','1');" 
                     onkeypress="return permite(event,'num_car', 0);" value="" /></td>
          </tr>
            <tr>
                <td colspan="3" class="titulo_etiqueta">Seleccionar la Fecha del Reporte </td>
            </tr>
            <tr>
                <td><div align="right">Fecha Inicio </div></td>
                <td><input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15"
                 readonly=true width="90"></td>        	
            </tr>
            <tr>
                <td><div align="right">Fecha Fin </div></td>
                <td><input name="txt_fechaFin"  type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90"></td>
            </tr>
            <tr>
          	 	<td colspan="2"><input type="checkbox" name="ckb_publicoGral" id="ckb_publicoGral" value="si" 
                	onclick="valPublico(this,'txt_cliente'); "/>  P&uacute;blico en General               
                </td>
            </tr>        
             <tr>
                <td colspan="2" align="center">
                    <input name="btn_generarReporte" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true" 
                    title="Generar Reporte Por Cliente" />
                    &nbsp;&nbsp;&nbsp;
                    <input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />                </td>
            </tr>
        </table>
    </form>
</fieldset>
	
<div id="calendar-uno">
  <input type="image" name="iniRepClientes" id="iniRepClientes" src="../../images/calendar.png"
         onclick="displayCalendar(document.frm_rptClientes.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
		width="25" height="25" border="0" align="absbottom" />
</div>
<div id="calendar-dos">
  <input type="image" name="finRepClientes" id="finRepClientes" src="../../images/calendar.png"
         onclick="displayCalendar(document.frm_rptClientes.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
		width="25" height="25" border="0" align="absbottom" />	
</div>
<div id="sugerencias">
		<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
			<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
			<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
  	  	</div>
	</div>


    <fieldset class="borde_seccion" id="rpt-fecha" name="rpt-fecha">
	<legend class="titulo_etiqueta">Reporte por Fecha</legend>
	<br />
	<form onsubmit="return verFormReportesVentas(this,2);" name="frm_rptFecha" action="frm_reporteVentas.php" method="post">
	  <table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td><div align="right">Fecha Inicio</div></td>
			<td width="140"><input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15"
             readonly=true width="90" /></td>
          	<td rowspan="2"><input name="btn_generarReporte3" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
             title="Generar Reporte Por Fecha" /></td>
        </tr>
        <tr>
          	<td><div align="right">Fecha Fin </div></td>
          	<td><input name="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
		</tr>
	</table>
	</form>
</fieldset>

<div id="calendar-tres">
		<input name="iniRptFecha" id="iniRptFecha" type="image" src="../../images/calendar.png" 
        onclick="displayCalendar(document.frm_rptFecha.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />
</div>
    <div id="calendar-cuatro">
		<input name="finRptFecha" id="finRptFecha" type="image" src="../../images/calendar.png" 
        onclick="displayCalendar(document.frm_rptFecha.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />	
</div>

	<fieldset class="borde_seccion" id="rpt-ganacia" name="rpt-ganacia">	
	<legend class="titulo_etiqueta">Reporte por Ganancia</legend>
	<br /><br /><br />
	<form onsubmit="return verFormReportesVentas(this,3);" name="frm_rptGanancia" action="frm_reporteVentas.php" method="post" >
	<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
       	  <td width="140"><div align="right">Cantidad Nivel Inferior</div>
       	  </td>
       	  <td width="160">
				$<input name="txt_nivelInf" id="txt_nivelInf" type="text" class="caja_de_texto" onchange="formatCurrency(value,'txt_nivelInf');" 
                onkeypress="return permite(event,'num', 2);" size="15" maxlength="20"  />
		  </td>
		</tr>
        <tr>
          	<td><div align="right">Cantidad Nivel Superior</div></td>
          	<td>
				$<input name="txt_nivelSup" id="txt_nivelSup" type="text" class="caja_de_texto" onchange="formatCurrency(value,'txt_nivelSup');"
                 onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" />
			</td>
        </tr>
<tr>
          	<td colspan="2" align="center">
		  		<input name="sbt_generar" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
                 title="Generar Reporte por Ganancia"/>
				&nbsp;&nbsp;&nbsp;
				<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
		  	</td>
        </tr>
    </table>
	</form>
	
</fieldset>

	<fieldset class="borde_seccion" id="rpt-factura" name="rpt-factura">
	<legend class="titulo_etiqueta">Reporte por Factura</legend>
	<br />
	<form onsubmit="return verFormReportesVentas(this,4);" name="frm_rptFactura" action="frm_reporteVentas.php" method="post">
    <table width="395" border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="113"><div align="right">Ventas Facturadas </div></td>
			<td colspan="2"><select name="cmb_factura" class="combo_box" id="cmb_factura">
              <option value="">Facturas de Venta</option>
              <option value="SI">SI</option>
              <option value="NO">NO</option>
            </select></td>
		</tr>
		<tr>
			<td><div align="right">Fecha Inicio</div></td>
		  <td width="112"><input name="txt_fechaIni" type="text" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10" maxlength="15"
             readonly=true width="90" /></td>
          	<td width="120" rowspan="2"><input name="btn_generarReporte" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true"
             title="Generar Reporte Por Factura" /></td>
        </tr>
        <tr>
          	<td><div align="right">Fecha Fin </div></td>
          	<td><input name="txt_fechaFin" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="90" /></td>          
		</tr>
	</table>
	</form>
</fieldset>

<div id="calendar-cinco">
  <input name="iniRptFactura" id="iniRptFactura" type="image" src="../../images/calendar.png" 
        onclick="displayCalendar(document.frm_rptFactura.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />
</div>
<div id="calendar-seis">
  <input name="finRptFactura" id="finRptFactura" type="image" src="../../images/calendar.png" 
        onclick="displayCalendar(document.frm_rptFactura.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		width="25" height="25" border="0" align="absbottom" />	
</div>

<div id="boton-cancelar">
		<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Reportes"
         onclick="location.href='menu_reportes.php'" />
</div><?php 
	}//Cierre if($band==0) ?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
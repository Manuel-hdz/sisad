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
		//Este archivo contiene las funciones para generar el Reporte Compra/Venta
		include ("op_reporteCompraVenta.php");
	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
	<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:143px; width:188px; height:21px; z-index:11; }
		#fechas { position:absolute; left:30px; top:180px; width:440px; height:160px; z-index:12; }
		#calendar-uno { position:absolute; left:285px; top:210px; width:30px; height:26px; z-index:13; }
		#calendar-dos { position:absolute; left:285px; top:245px; width:30px; height:26px; z-index:14; }
		#resultados { position:absolute; left:30px; top:180px; width:940px; height:270px; z-index:15; overflow:scroll; }		
		#btns-regpdf { position: absolute; left:30px; top:550px; width:940px; height:40px; z-index:17; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Compra/Venta </div><?php
	
	$band = 0;
	if(isset($_POST['txt_fechaIni'])){?>				
		<div id="resultados" align="center" class="borde_seccion2"><?php
			//Quitar los datos de la grafica de la SESSION, antes de entrar a generar el nuevo reporte, en el caso de que exista uno previo
			unset($_SESSION['datosGrafica']);
			$band = 1;		
			generarReporte();?>
		</div>
		<div id="btns-regpdf" align="center">
			<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Compra/Venta" 
			onclick="location.href='frm_reporteCompraVenta.php'" />							
			 &nbsp;&nbsp;&nbsp;
			 <input type="button" name="btn_verGrafica" class="botones" value="Ver Grafica" title="Ver Gr&aacute;fica Comparativa de Compra/Venta" 
			onClick="javascript:window.open('verGraficas.php?graph=CompraVenta',
			'_blank','top=0, left=0, width=1035, height=723, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" />									  						
		</div><?php		
	}		
		    
	if($band==0){ ?>
	<fieldset class="borde_seccion" id="fechas" name="fechas">
		<legend class="titulo_etiqueta">Reporte por Fecha</legend>
		<form onsubmit="return verContFormCompraVenta(this);" name="frm_rptCompraVenta" method="post" action="frm_reporteCompraVenta.php">
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">		
   			 	<tr>
    		    	<td width="30%"><div align="right">Fecha Inicio </div></td>
    		    	<td>
						<input name="txt_fechaIni" type="text" class="caja_de_texto" id="txt_fechaIni" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> 
                        size="10" maxlength="15" readonly=true width="90"></td>
                </tr>
                <tr>
                    <td><div align="right">Fecha Fin </div></td>
                    <td><input name="txt_fechaFin" type="text" class="caja_de_texto" id="txt_fechaFin" value=<?php echo date("d/m/Y"); ?> size="10" 
                    maxlength="15" readonly=true width="90" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input name="btn_generarReporte" type="submit" class="botones" value="Generar Reporte" onmouseover="window.status='';return true" 
                        title="Generar Reporte por Fecha" />
                        &nbsp;
                        <input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />		  
                        &nbsp;
                        <input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Reportes" 
                        onclick="location.href='menu_reportes.php'" />
                    </td>
                </tr>
            </table>
        </form>
    </fieldset>
	
    <div id="calendar-uno">
		<input name="iniRepFecha" id="iniRepFecha" type="image" src="../../images/calendar.png"
         onclick="displayCalendar(document.frm_rptCompraVenta.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
		width="25" height="25" border="0" align="absbottom" />
    </div>
	
	<div id="calendar-dos">
		<input name="finRepFecha" id="finRepFecha" type="image" src="../../images/calendar.png" onclick=
        "displayCalendar(document.frm_rptCompraVenta.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
		width="25" height="25" border="0" align="absbottom" />
	</div>		
	
<?php }//Cierre if($band==0) ?>	   	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
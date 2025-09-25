<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Este archivo contiene las funciones para mostrar las Ordenes de Compra registradas y el detalle de las mismas
		include ("op_reporteOrdenCompra.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>

    <style type="text/css">
		<!--
		#titulo-salida { position:absolute; left:15px; top:146px; width:141px; height:19px; z-index:11; }
		#form-datos-salida {	position:absolute; left:30px; top:190px; width:509px; height:237px;	z-index:13;	}
		#registro-material { position:absolute; left:586px; top:192px; width:545px; height:206px; z-index:14; }
		#titulo-reporteOC { position:absolute; left:30px; top:146px; width:236px; height:19px; z-index:11; }
		#tabla-ordenesOC { position:absolute; left:30px; top:192px;	width:940px; height:450px; z-index:12; overflow:scroll; }
		#calendarioOC_cierre { position:absolute; left:331px; top:268px; width:30px; height:25px; z-index:15; }
		#calendarioOC_inicio { position:absolute; left:331px; top:232px; width:29px; height:25px; z-index:14; }
		#btns-regpdf { position: absolute; left:30px; top:680px; width:945px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-reporteOC">Reporte de &Oacute;rden de Compra </div>

<?php //Si la variables $txt_fecha no esta definida en el arreglo $_POST, entonces desplegar el formulario para solictar las fechas
	if(!isset($_POST['txt_fechaInicio']) && !isset($_POST['fecha_ini'])){?>	
    
	<fieldset id="form-datos-salida" class="borde_seccion">
	<legend class="titulo_etiqueta">Seleccionar la Fecha de Inicio y Fin de las &Oacute;rdenes de Compra</legend>	
	<br>
	<form name="frm_datosReporteOrdenCompra" action="frm_reporteOrdenCompra.php" method="post" onsubmit="return valFormFechas(this);">
	<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  	<td width="120"><div align="right">Fecha de Inicio</div></td>
		  	<td width="120">
				<input name="txt_fechaInicio" type="text" value=<?php echo date("d/m/Y",strtotime("-30 day")); ?> size="10" maxlength="15" readonly=true width="50">
			</td>
		    <td width="120">&nbsp;</td>
		</tr>
		<tr>
			<td><div align="right">Fecha de Cierre</div></td>
			<td>
				<input name="txt_fechaCierre" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="50">
			</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
			<td>
			  <div align="center">
			    <input name="sbt_registrar" type="submit" class="botones" value="Ver Reporte" onMouseOver="window.status='';return true" title="Ver &Oacute;rdenes de Compra" />
	          </div></td>
			<td>
		      <div align="left">
		        <input name="btn_limpiar" type="reset" class="botones" value="Restablecer" onMouseOver="window.status='';return true" title="Restablecer las Fechas Seleccionadas" />
              </div></td>
		    <td><div align="center">
		      <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Reportes" onClick="location.href='menu_reportes.php'" />
		      </div></td>
		</tr>
	</table>    
	</form>    			 	
	</fieldset>
		
	<div id="calendarioOC_inicio">
		<input type="image" onclick="displayCalendar(document.frm_datosReporteOrdenCompra.txt_fechaInicio,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
</div>

	<div id="calendarioOC_cierre">
  		<input type="image" onclick="displayCalendar(document.frm_datosReporteOrdenCompra.txt_fechaCierre,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
</div>
	
	<?php 
	}
	else{
		?><div id="tabla-ordenesOC" align="center" class="borde_seccion2"><?php 
		//Mostrar la informacion general de las ordenes de compra registradas
		if(isset($_POST['txt_fechaInicio']))
			mostrarOrdenesCompra($txt_fechaInicio,$txt_fechaCierre); 
		
		if(isset($_POST['fecha_ini']) && isset($_POST['fecha_end'])){			
			//Obtener el valor de la clave de la Orden de Compra seleccionada
			$clave = "";
			$tam = count($_POST);
			$cont = 1;
			foreach($_POST as $nombre_campo => $valor){								
				if($cont==$tam)
					$clave = $valor;				
				$cont++;
			}			
			//Mostrar el detalle de la Orden de COmpra Seleccionada
			mostrarDetalleOC($clave,$fecha_ini,$fecha_end);
		}
	}?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
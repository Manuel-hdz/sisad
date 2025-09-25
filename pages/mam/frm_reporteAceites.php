<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reporteAceites.php");
	?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<?php //Funciones de LightBox?>
	<link rel="stylesheet" href="../../includes/lightbox/css/lightbox.css" type="text/css" media="screen" />
	<script src="../../includes/lightbox/js/prototype.js" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>
	<script src="../../includes/lightbox/js/lightbox.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="includes/ajax/borrarHistorial.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	
    <style type="text/css">
		<!--	
			#titulo-consultar-empleado {position:absolute; left:30px; top:146px; width:388px; height:25px; z-index:11; }
			#tabla-consultar-empleados {position:absolute; left:30px; top:190px; width:436px; height:144px; z-index:14;}
			#tabla-empleados { position:absolute; left:30px; top:190px; width:945px; height:430px; z-index:21; overflow:scroll; }
			#botones{position:absolute;left:30px;top:670px;width:950px;height:37px;z-index:16;}
			#calendar-tres {position:absolute; left:235px; top:233px; width:30px; height:26px; z-index:18;}
		-->
    </style>

</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-empleado">Reporte de Consumo de Aceites</div><?php 
	
	if(isset($_GET["noResults"])){
		echo "
		<script language='javascript' type='text/javascript'>
			setTimeout(\"alert('No se Encontraron Registros de Consumo de Aceites en la Fecha $_GET[noResults]');\",500);
		</script>
		";
	}

	//Verificamos si viene definido en el post el boton consultar
	if(isset($_POST["sbt_consultar"])){?>
		<div align='center' id='tabla-empleados' class='borde_seccion2' width='100%'>
			<?php
			//Mostrar reporte de Aceites Consumidos
			$graficas=reporteAceites();
			$grafica=split("¬",$graficas);
			?>
		</div>
		
		<div align="center" id="botones">
			<a href='<?php echo $grafica[0];?>' rel='lightbox[repMensual]' title='Gr&aacute;fica del Reporte de Consumo de Aceites'><input type="button" name="btn_grafico" class="botones" value="Ver Gr&aacute;fico" title="Gr&aacute;fica del Reporte de Consumo de Aceites"/></a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar otras Fechas" 
			onMouseOver="window.status='';return true" onclick="borrarHistorial();location.href='frm_reporteAceites.php'" />
		</div>
		
		<div id="imagenes" style="visibility:hidden;width:1px;height:1px;overflow:hidden">
			<?php
			if(count($grafica)>1){
				$cont=1;
				do{
					?>
					<a href='<?php echo $grafica[$cont];?>' rel='lightbox[repMensual]' title='Gr&aacute;fica del Reporte de Consumo de Aceites'>
						<img width="2%" height="2%" border="0" src="<?php echo $grafica[$cont];?>" title="Gr&aacute;fica del Reporte de Consumo de Aceites"/>
					</a>
					<?php
					$cont++;
				}while($cont<count($grafica));
			}
			?>
		</div>
		<?php	
	 }
	 //Formulario con los Equipos
	 if(isset($_POST["sbt_consultar2"])){
			echo "<form name='frm_seleccionarEquipoFiltro' method='POST' onsubmit='return valFormSelEquipoFiltro(this)';>";
			echo "<div id='tabla-empleados' class='borde_seccion2' align='center'>";
				mostrarEquiposMttoM();
			echo "</div>";
			?>
			<div align="center" id="botones">
				<input type="hidden" name="hdn_fechaI" id="hdn_fechaI" value="<?php echo $_POST["txt_fechaIni"]?>"/>
				<input type="hidden" name="hdn_fechaF" id="hdn_fechaF" value="<?php echo $_POST["txt_fechaFin"]?>"/>
				<input name="sbt_repDisp2" id="sbt_repDisp2" type="submit" class="botones" value="Generar Reporte" title="Generar Reporte de Disponibilidad por Fechas" onMouseOver="window.status='';return true"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Seleccionar otras Fechas" 
				onMouseOver="window.status='';return true" onclick="borrarHistorial();location.href='frm_reporteAceites.php'"/>
			</div>
			<?php
			echo "</form>";
		}//Fin de if(isset($_POST["sbt_consultar"]))
	 
	 if(!isset($_POST["sbt_consultar"]) && !isset($_POST["sbt_consultar2"])) { ?> 
	 	<script type="text/javascript" language="javascript">
			setTimeout("borrarHistorial()",1000);
		</script>
		<fieldset class="borde_seccion" id="tabla-consultar-empleados">
		<legend class="titulo_etiqueta">Reporte de Consumo de Aceite por Fecha</legend>	
		<br>
		<form method="post" name="frm_reporteFecha" id="frm_reporteFecha" action="frm_reporteAceites.php">
		<table width="444" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td width="77"><div align="right">Fecha</div></td>
				<td width="332">
					<input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text"
					value="<?php echo date("d/m/Y"); ?>" size="10"  width="90"/>			     
			  </td>
			</tr>
		</table>
		<div align="center">
			<p>
				<input name="sbt_consultar" type="submit"  class="botones" id="sbt_consultar" value="Consultar"
				onmouseover="window.status='';return true;" title="Generar Reporte de Consumo Aceites"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" name="btn_limpiar" class="botones" value="Restablecer" title="Restablece el Formulario"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Reportes" 
				onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" />
			</p>
		</div>
		</form>
		</fieldset>
		<div id="calendar-tres">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteFecha.txt_fecha,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" title="Seleccionar Fecha de Reporte"
			width="25" height="25" border="0" />
		</div>
	<?php 
	}?>
</body><?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
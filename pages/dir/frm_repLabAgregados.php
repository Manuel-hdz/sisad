<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	//if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
	//	echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	//}
	//else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />
	<script type="text/javascript" src="includes/ajax/reportesLaboratorio.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js" ></script>
	<script type="text/javascript" src="../../includes/validacionDireccion.js"></script>

<style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#tabla-reporte-agregado {position:absolute;left:30px;top:190px;width:425px;height:170px;z-index:12;}
		#resultado{position:absolute;left:30px;top:396px;width:931px; height:292px;;z-index:14;overflow:hidden;}
		#calendar-uno {position:absolute; left:239px; top:267px; width:30px; height:26px; z-index:13; }
		#calendar-dos {position:absolute; left:426px; top:265px; width:30px; height:26px; z-index:13; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30"/></div>
	<div class="titulo_barra" id="titulo-barra">Reporte de Agregados </div>
	
	<fieldset class="borde_seccion" id="tabla-reporte-agregado" name="tabla-reporte-agregado">
	<legend class="titulo_etiqueta" style="color:#FFF">Seleccionar Agregado </legend>	
	<br>
	<form name="frm_reporteAgregados">
		<table width="415" height="108"  cellpadding="5" cellspacing="5" class="tabla_frm">
      		<tr>
				<td width="63" style="color:#FFF"><div align="right">Agregado</div></td>
		  		<td colspan="4">
				<?php 
				$cmb_agregado="";
				$conn = conecta("bd_almacen");
				$result=mysql_query("SELECT id_material,nom_material FROM materiales WHERE linea_articulo='AGREGADO' ORDER BY nom_material");
				if($agregados=mysql_fetch_array($result)){?>
					<select name="cmb_agregado" id="cmb_agregado" size="1" class="combo_box">
						<option value="">Agregado</option><?php 
							do{
								if ($agregados['nom_material'] == $cmb_agregado){
									echo "<option value='$agregados[id_material]' selected='selected'>$agregados[nom_material]</option>";
								}
								else{
									echo "<option value='$agregados[id_material]'>$agregados[nom_material]</option>";
								}
							}while($agregados=mysql_fetch_array($result)); 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
					</select><?php
				 }
				else{
					echo "<label class='msje_correcto'> No hay Agregados Registrados</label>
					<input type='hidden' name='cmb_agregado' id='cmb_agregado'/>";
				}?>
				</td>
			</tr>
			<tr>
				<td width="88" style="color:#FFF"><div align="right">Fecha Inicio</div></td>
				<td width="92">
					<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
					value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10"  width="90"/>
				</td>
				<td width="75" style="color:#FFF"><div align="right">Fecha Fin </div></td>
				<td width="116">
					<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
					value="<?php echo date("d/m/Y"); ?>" size="10" width="90"/>					</td>
			</tr>
			<tr>
			<td colspan="4" align="center">
					<input name="btn_reporte" type="button" class="botones" value="Ver Agregados" onMouseOver="window.status='';return true" title="Ver Agregados" 
					onclick="mostrarReporteAgregados(3,cmb_agregado.value,txt_fechaIni.value,txt_fechaFin.value);"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Reportes de Laboratorio" 
					onClick="borrarHistorial();location.href='submenu_laboratorio.php'" />
			  </td>
			</tr>
   	  </table>
	</form>
	</fieldset>
	
	<div id="calendar-uno">
		<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteAgregados.txt_fechaIni,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		width="25" height="25" border="0" />
	</div>
	
	<div id="calendar-dos">
		<input name="fechaFin" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteAgregados.txt_fechaFin,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		width="25" height="25" border="0" />
	</div>

	<div id="resultado"></div>
	</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
    <link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />
	<script type="text/javascript" src="includes/ajax/reportesLaboratorio.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js" ></script>
	<script type="text/javascript" src="../../includes/validacionDireccion.js"></script>

<style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#tabla-generarReporte {position:absolute;left:30px;top:190px;width:510px;height:150px;z-index:14;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30"/></div>
	<div class="titulo_barra" id="titulo-barra">Reporte de Resistencias </div>
	
	<fieldset class="borde_seccion" id="tabla-generarReporte" name="tabla-generarReporte">
	<legend class="titulo_etiqueta" style="color:#FFFFFF">Seleccionar Muestra para Generar Reporte de Resistencias</legend>	
	<br>
	<form name="frm_reporteResistencias">
	<table width="568" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="96"><div align="right" style="color:#FFF">*Tipo Prueba</div></td>
			<td width="435"><?php 
				//Conectar a la BD de laboratorio
				$conn = conecta("bd_laboratorio");
				$sql_stm = "SELECT DISTINCT tipo_prueba FROM muestras"; 
				$rs = mysql_query($sql_stm);?>
				<select name="cmb_tipoPrueba" id="cmb_tipoPrueba" class="combo_box" 
					onchange="cargarCombo(this.value,'bd_laboratorio','muestras','id_muestra','tipo_prueba','cmb_idMuestra','ID Muestra','')">
					<option value="" selected="selected">Seleccione</option><?php
						if($datos=mysql_fetch_array($rs)){
							do{ 
								echo "<option value = '$datos[tipo_prueba]'>$datos[tipo_prueba]</option>";
							}while($datos=mysql_fetch_array($rs));
						}?>
				</select>         
			</td>
		</tr>
		<tr>         
			<td width="96" style="color:#FFF"><div align="right">*Clave</div></td>
			<td width="435">
				<select name="cmb_idMuestra" id="cmb_idMuestra" class="combo_box">
					<option value="">ID Muestra</option>
				</select>
			</td>			
		</tr>  
		<tr>
			<td colspan="2" align="center">
				<input name="btn_reporte" type="button" class="botones" value="Ver Reporte" onMouseOver="window.status='';return true" title="Ver Reporte de Resistencias" 
				onclick="mostrarReporteResistencias(2,cmb_idMuestra.value);"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Reportes de Laboratorio" 
				onClick="borrarHistorial();location.href='submenu_laboratorio.php'" />
			</td>
		</tr>
	</table>
	</form>
	</fieldset>

	</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
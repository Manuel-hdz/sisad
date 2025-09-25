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
	<script type="text/javascript" src="../../includes/validacionDireccion.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js" ></script>

	<style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#tabla-reporte-agregado {position:absolute;left:30px;top:190px;width:425px;height:170px;z-index:12;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30"/></div>
	<div class="titulo_barra" id="titulo-barra">Reporte de Mezclas</div>
	
	<fieldset class="borde_seccion" id="tabla-reporte-agregado" name="tabla-reporte-agregado">
	<legend class="titulo_etiqueta" style="color:#FFF">Seleccionar Mezcla</legend>	
	<br>
	<form name="frm_reporteRendimiento">
		<table width="415" height="108"  cellpadding="5" cellspacing="5" class="tabla_frm">
      		<tr>
			  <td width="101" style="color:#FFF"><div align="right">Clave de Mezcla</div></td>
		  		<td width="277" colspan="4">
				<?php 
				$cmb_mezclas="";
				$conn = conecta("bd_laboratorio");
				$result=mysql_query("SELECT mezclas_id_mezcla,localizacion FROM rendimiento ORDER BY localizacion");
				if($mezclas=mysql_fetch_array($result)){?>
					<select name="cmb_mezclas" id="cmb_mezclas" size="1" class="combo_box" 
					onchange="cargarCombo(this.value,'bd_laboratorio','rendimiento','id_registro_rendimiento','mezclas_id_mezcla','cmb_idMuestra','No. Muestra','')">
						<option value="">Mezcla</option><?php 
							do{
								echo "<option value='$mezclas[mezclas_id_mezcla]'>$mezclas[localizacion]</option>";
							}while($mezclas=mysql_fetch_array($result)); 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
					</select><?php
				 }
				 ?>
			  </td>
			</tr>
			<tr>         
			  <td width="101" style="color:#FFF"><div align="right">*No Muestra</div></td>
				<td width="277">
					<select name="cmb_idMuestra" id="cmb_idMuestra" class="combo_box">
						<option value="">No. Muestra</option>
					</select>
			  </td>			
			</tr>  
			<tr>
			<td colspan="4" align="center">
					<input name="btn_reporte" type="button" class="botones" value="Consultar" onMouseOver="window.status='';return true" title="Ver Reporte de Pruebas de Rendimiento a Mezclas" 
					onclick="mostrarReporteRendimiento(cmb_mezclas.value,cmb_idMuestra.value);"/>
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
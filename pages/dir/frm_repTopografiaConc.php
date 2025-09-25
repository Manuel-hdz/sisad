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
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
    <link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css"/>
	<script type="text/javascript" src="includes/ajax/reportesTop.js"></script>

    <style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#form-selecPeriodo {position:absolute;left:30px;top:190px;width:546px;height:87px;z-index:14;}
		#resultados{position:absolute;left:30px;top:310px;width:974px; height:368px;;z-index:14;overflow:hidden;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Conciliaci&oacute;n </div>
	
	<fieldset class="borde_seccion" id="form-selecPeriodo" name="form-selecPeriodo">
	<legend class="titulo_etiqueta" style="color:#FFFFFF">Seleccionar Quincena</legend>	
	<br>	
	<form name="frm_reporteConciliacion">
	<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="30%" style="color:#FFF"><div align="right">Quincena</div></td>
			<td width="70%">
				<?php
				$conn = conecta("bd_topografia");		
				$stm_sql = "SELECT DISTINCT no_quincena FROM estimaciones ORDER BY SUBSTRING(no_quincena,-4), fecha_registro";
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
				if($datos = mysql_fetch_array($rs)){?>
					<select name="cmb_noQuincena" id="cmb_noQuincena" class="combo_box">				
					<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
					echo "<option value=''>Quincena</option>";
					do{
						echo "<option value='$datos[no_quincena]'>$datos[no_quincena]</option>";
					}while($datos = mysql_fetch_array($rs));?>
					</select><?php
				}
				else{
					echo "<label class='msje_correcto'> No hay Registros</label>
						<input type='hidden' name='cmb_noQuincena' id='cmb_noQuincena' value=''/>";
				}
				//Cerrar la conexion con la BD		
				mysql_close($conn);?>
			</td>
			<td>
				<input name="btn_reporte" type="button" class="botones" value="Consultar" onMouseOver="window.status='';return true" title="Ver Reporte de Conciliaci&oacute;n" 
				onclick="mostrarReporteConc(2,cmb_noQuincena.value);"/>
			</td>
			<td>
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Reportes de Topograf&iacute;a" onClick="borrarHistorial();location.href='submenu_topografia.php'" />
			</td>
		</tr>
	</table>    
	</form>    			 	
</fieldset>
	
	<div id="resultados"></div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
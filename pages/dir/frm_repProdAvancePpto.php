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
	<script type="text/javascript" src="includes/ajax/reportesProd.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarComboUbicaciones.js"></script>
	<script type="text/javascript" src="../../includes/validacionDireccion.js"></script>
    <link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />

    <style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#form-selecPeriodo {position:absolute;left:30px;top:190px;width:356px;height:120px;z-index:14;}
		#resultados{position:absolute;left:440px;top:191px;width:568px; height:320px;;z-index:14;}
		#consultarDetalle{position:absolute; left:223px; top:461px; width:130px; height:110px; z-index:16;visibility:hidden;}
		#tabla{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; padding:15px; padding-top:0px; overflow:scroll;visibility:hidden;}
		#parrila-volver{ position:absolute; left:30px; top:640px; width:950px; height:29px;z-index:1;visibility:hidden;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Presupuesto VS Avance</div>
	
	<fieldset class="borde_seccion" id="form-selecPeriodo" name="form-selecPeriodo">
	<legend class="titulo_etiqueta" style="color:#FFFFFF">Seleccionar Periodo</legend>	
	<br>
	<form name="frm_reporteProduccion">	
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="40%" align="right" style="color:#FFFFFF">Periodo</td>
			<td width="60%"><?php 
				$conn = conecta("bd_produccion");		
				$stm_sql="SELECT DISTINCT (periodo),fecha_inicio,fecha_fin FROM presupuesto";
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
				if($datos = mysql_fetch_array($rs)){
					$fecha=date("Y-m-d");
					?>
					<select name="cmb_periodo" id="cmb_periodo" class="combo_box">
					<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
					echo "<option value=''>Seleccionar</option>";
					do{
						$sel="";
						if ($fecha>=$datos["fecha_inicio"] && $fecha<=$datos["fecha_fin"])
							$sel=" selected='selected'";
						echo "<option value='$datos[periodo]'$sel>$datos[periodo]</option>";
					}while($datos = mysql_fetch_array($rs));?>
					</select><?php
					$res="si";
				}
				else{
					$res="no";
					echo "<label class='msje_correcto'> No hay Periodos Registrados</label>
						<input type='hidden' name='cmb_area' id='cmb_area'/>";
				}
				//Cerrar la conexion con la BD		
				mysql_close($conn);
				?>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="hidden" name="hdn_combo" id="hdn_combo" value=""/>
				<input type="button" name="sbt_consultar" value="Consultar" class="botones" onmouseover="window.status='';return true" title="Consultar el Periodo Seleccionado" onclick="reporteProduccion(1,cmb_periodo.value);"/>
				&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_regresar" value="Regresar" class="botones" onclick="borrarHistorial();location.href='submenu_produccion.php'" title="Regresar al Men&uacute; de Reportes de Producci&oacute;n" />
			</td>
		</tr>		
	</table>
	</form>
</fieldset>

<div align="center" id="parrila-volver">
	<img src='images/back.png' width="50" height="50" title='Regresar a la Secci&oacute;n Anterior' border=0 onclick="ocultarDetalleAvanceProd();" style="cursor:pointer"/>
</div>

<div id="consultarDetalle">
	<img src='images/verDetalle.png' width='100%' height='100%' title='Consultar a Detalle la Consulta' border=0 onclick="mostrarDetalleAvanceProd();" style="cursor:pointer"/>
</div>
<div id="resultados" class="resultados"></div>
<div id="tabla" class="borde_seccion2"></div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
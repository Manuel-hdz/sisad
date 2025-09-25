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
	<script type="text/javascript" src="includes/ajax/reportesGT.js" ></script>
    <link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />

    <style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:30px;top:146px; width:313px;height:20px;z-index:11;}
		#form-selecPeriodo {position:absolute;left:30px;top:190px;width:356px;height:187px;z-index:14;}
		#resultadoTabla {position:absolute;left:30px;top:190px;width:333px;height:380px;z-index:14; overflow:scroll}
		#resultadoGrafico{position:absolute;left:420px;top:190px;width:590px;height:420px;z-index:15;}
		#parrila-volver{ position:absolute; left:977px; top:630px; width:26px; height:29px;z-index:1; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Reporte Comparativo por Mina</div>
	
	<fieldset class="borde_seccion" id="form-selecPeriodo" name="form-selecPeriodo">
	<legend class="titulo_etiqueta" style="color:#FFFFFF">Seleccionar Periodo</legend>	
	<br>	
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="40%" align="right" style="color:#FFFFFF">Ubicaci&oacute;n</td>
			<td width="60%"><?php 
				$res=cargarCombo('cmb_ubicacion','destino','bitacora_zarpeo','bd_gerencia','Ubicaci&oacute;n','');
				if($res==0){
					echo "<label class='msje_correcto'>No hay Ubicaciones Registradas</label>
					<input type='hidden' name='cmb_ubicacion' id='cmb_ubicacion' disabled='disabled'/>";
				}
				?>
			</td>
		</tr>
		<tr>
			<td align="right" style="color:#FFFFFF">A&ntilde;o</td>
			<td >
			<?php if ($res!=0) {
				//conectar a gerencia
				$conn = conecta('bd_gerencia');
				$rs = mysql_query("SELECT DISTINCT fecha FROM bitacora_zarpeo");
				$anios = array();
				while($datos=mysql_fetch_array($rs)){
					$fecha = $datos['fecha'];
					$anios[] = substr($fecha,0,4); 
				}
				$anioUnico = array_unique($anios);?>
				<select name="cmb_anios" id="cmb_anios" class="combo_box">  
					<option value="">A&ntilde;o</option><?php
					foreach($anioUnico as $ind => $anio){?>
						<option value="<?php echo $anio;?>"<?php if(date("Y")==$anio) echo "selected='selected'";?>><?php echo $anio;?></option><?php
					}?>
				</select><?php
				//cerrar conexion
				mysql_close($conn);
			}
			else
				echo "<label class='msje_correcto'>NO HAY DATOS</label>";
			?>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>	
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" name="sbt_consultar" value="Consultar" class="botones" onmouseover="window.status='';return true" title="Consultar el Periodo Seleccionado"
				onclick="mostrarReporteCompMina(2,cmb_ubicacion,cmb_anios);"/>
				&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_regresar" value="Regresar" class="botones" onclick="borrarHistorial();location.href='submenu_concreto.php'" title="Regresar al Men&uacute; de Reportes de Zarpeo" />
			</td>
		</tr>		
	</table>
</fieldset>
	<div id="resultadoTabla" style="visibility:hidden"></div>
	<div id="resultadoGrafico" style="visibility:hidden"></div>
	<div align="center" id="parrila-volver" style="visibility:hidden">
	<form action="frm_repGerenciaCompMina.php">
		<input type="image" src="images/back.png" name="back" id="back" width="50" height="50" border="0" title="Subir un Nivel" 
		onmouseover="window.status='';return true"/>
	</form>
	</div>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
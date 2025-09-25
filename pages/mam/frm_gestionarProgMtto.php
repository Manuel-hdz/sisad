<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que incluye la opcion de mostrar los Equipos por Mes y Año
		include ("op_gestionarProgMtto.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:243px; height:25px; z-index:11; }
		#consultar-equipos {position:absolute; left:30px; top:190px; width:620px; height:190px; z-index:15;}
		#botones{position:absolute;left:30px;top:670px;width:950px;height:37px;z-index:16;}
		#tabla-titulo{position:absolute; left:30px; top:190px; width:950px; height:75px; z-index:17; padding:15px; padding-top:0px; z-index:14;}
		#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:450px; z-index:17; padding:15px; padding-top:0px; overflow:scroll;}
		-->
    </style>
	
	<script type="text/javascript" src="../../includes/jquery-1.5.1.js" ></script>
	<script language="javascript">
		$(document).ready(function() {
			$("#btn_exportar").click(function(event) {
				$("#hdn_divRepProgMtto").val( $("<div>").append( $("#tabla-resultados").eq(0).clone()).html());
			$("#frm_exportarDiv").submit();
			});
		});
	</script>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Programa de Mantenimiento</div>
	<?php	

	//Si no esta definido ningun boton, mostrar el formulario de Base
	if (!isset($_POST["sbt_consultarProgramacion"])){
		//Verificar si se llego aqui desde una Alerta
		if(isset($_POST["hdn_orden"])){
			$idOrden=$_POST["hdn_orden"];
			$equipo=obtenerDato("bd_mantenimiento","bitacora_mtto","equipos_id_equipo","orden_trabajo_id_orden_trabajo",$idOrden);
			$familia=obtenerDato("bd_mantenimiento","equipos","familia","id_equipo",$equipo);
			$mes=substr(obtenerDato("bd_mantenimiento","orden_trabajo","fecha_prog","id_orden_trabajo",$idOrden),5,2);
			//Identificar mes de numero a Letra
			switch($mes){
				case "01":	$mes="ENERO";		break;
				case "02":	$mes="FEBRERO";		break;
				case "03":	$mes="MARZO";		break;
				case "04":	$mes="ABRIL";		break;
				case "05":	$mes="MAYO";		break;
				case "06":	$mes="JUNIO";		break;
				case "07":	$mes="JULIO";		break;
				case "08":	$mes="AGOSTO";		break;
				case "09":	$mes="SEPTIEMBRE";	break;
				case "10":	$mes="OCTUBRE";		break;
				case "11":	$mes="NOVIEMBRE";	break;
				case "12":	$mes="DICIEMBRE";	break;
			}
			$anio=substr(obtenerDato("bd_mantenimiento","orden_trabajo","fecha_prog","id_orden_trabajo",$idOrden),0,4);
		}
		else{
			$familia="";
			$anio=date("Y");
			$mes=strtoupper(obtenerMesActual());
		}
	?>
	<fieldset class="borde_seccion" id="consultar-equipos">
	<legend class="titulo_etiqueta">Consultar por &Aacute;rea y Fecha</legend>	
	<br>		
	<form name="frm_seleccionarDatos" method="post" action="frm_gestionarProgMtto.php" onsubmit="return valFormSelDatosProgMtto(this);">
		<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td width="125" ><div align="right">Familia</div></td>
				<td width="460"><?php
					$conn = conecta("bd_mantenimiento");		
					$stm_sql = "SELECT DISTINCT familia FROM equipos WHERE area='MINA' ORDER BY familia";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>			
						<select name="cmb_familia" id="cmb_familia" class="combo_box"><?php
						//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
						echo "<option value=''>Seleccionar</option>";
						do{
							if($familia==$datos["familia"])
								echo "<option value='$datos[familia]' selected='selected'>$datos[familia]</option>";
							else
								echo "<option value='$datos[familia]'>$datos[familia]</option>";
						}while($datos = mysql_fetch_array($rs));?>
						</select><?php
					}
					else{
						echo "<label class='msje_correcto'> No hay Familias Registradas</label>
						<input type='hidden' name='cmb_familia' id='cmb_familia'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>	
			  </td>
			</tr>
			<tr>
				<td><div align="right">Mes</div></td>
				<td>
					<select name="cmb_Mes" id="cmb_Mes" class="combo_box">
                        <option value="">Mes</option>
                        <option value="ENERO"<?php if($mes=="ENERO") echo " selected='selected'";?>>Enero</option>
                        <option value="FEBRERO"<?php if($mes=="FEBRERO") echo " selected='selected'";?>>Febrero</option>
                        <option value="MARZO"<?php if($mes=="MARZO") echo " selected='selected'";?>>Marzo</option>
                        <option value="ABRIL"<?php if($mes=="ABRIL") echo " selected='selected'";?>>Abril</option>
                        <option value="MAYO"<?php if($mes=="MAYO") echo " selected='selected'";?>>Mayo</option>
                        <option value="JUNIO"<?php if($mes=="JUNIO") echo " selected='selected'";?>>Junio</option>
                        <option value="JULIO"<?php if($mes=="JULIO") echo " selected='selected'";?>>Julio</option>
                        <option value="AGOSTO"<?php if($mes=="AGOSTO") echo " selected='selected'";?>>Agosto</option>
                        <option value="SEPTIEMBRE"<?php if($mes=="SEPTIEMBRE") echo " selected='selected'";?>>Septiembre</option>
                        <option value="OCTUBRE"<?php if($mes=="OCTUBRE") echo " selected='selected'";?>>Octubre</option>
                        <option value="NOVIEMBRE"<?php if($mes=="NOVIEMBRE") echo " selected='selected'";?>>Noviembre</option>
                        <option value="DICIEMBRE"<?php if($mes=="DICIEMBRE") echo " selected='selected'";?>>Diciembre</option>
                    </select>
				</td>
			</tr>
			<tr>
				<td><div align="right">A&ntilde;o</div></td>
				<td>
					<select name="cmb_Anio" id="cmb_Anio" class="combo_box">
                        <option value="">A&ntilde;o</option><?php
                        //Obtener el Año Actual
                        $anioInicio = intval(date("Y")) - 2;
                        for($i=0;$i<21;$i++){
							if($anioInicio==$anio)
								echo "<option value='$anioInicio' selected='selected'>$anioInicio</option>";
							else
	                            echo "<option value='$anioInicio'>$anioInicio</option>";
                            $anioInicio++;
                        }?>							
                    </select>
				</td>
			</tr>
			<tr>
				<td align="center" colspan="4">
					<input name="sbt_consultarProgramacion" type="submit" class="botones" id="sbt_consultarProgramacion" 
					title="Consultar Informaci&oacute;n"  onmouseover="window.status='';return true" value="Consultar" />&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" value="Restablecer" title="Borra los criterios de b&uacute;squeda y reestablece el formulario" onclick="txt_nombreK.readOnly=true;cmb_area.disabled=false"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" value="Regresar" class="botones" title="Regresar al Men&uacute; de Equipos"
					onclick="location.href='menu_ordenTrabajo.php'" />
				</td>
			</tr>
		</table>    
	</form>    			 		
	</fieldset>
	
	<?php
	}
	else{
		echo "<div id='tabla-resultados' align='center' class='borde_seccion2'>";
			dibujarTablaMes();
		echo "</div>";
		
		echo "<div id='botones' align='center'>";
		?>	
			<form action="guardar_reporte.php" method="post" id="frm_exportarDiv">
				<input type="hidden" id="hdn_divRepProgMtto" name="hdn_divRepProgMtto" />
				<input type="button" id="btn_exportar" name="btn_exportar" class='botones' value="Exportar a Excel" title="Exportar a Excel"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Consultar con Otros Datos" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_gestionarProgMtto.php'"/>
			</form>
		<?php
		echo "</div>";
	}
	?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
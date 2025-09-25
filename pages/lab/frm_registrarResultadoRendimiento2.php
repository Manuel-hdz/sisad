<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php 


	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarResultadoRendimiento.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/validarEdad.js"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;width:357px;height:20px;z-index:11;}
		#tabla-agregarPrueba {position:absolute;left:30px;top:191px;width:800px;height:330px;z-index:14;}
		#tabla-detalleRendimiento { position:absolute; left:30px; top:191px; width:900px; height:350px; z-index:14; }
		#calendario-FechaProg {position:absolute;left:622px;top:233px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body><?php

	//Verificar si fue presionado el boton de guardar; el cual permitira guardar la información general del Rendimiento de la Mezcla seleccionada
	if(isset($_POST["sbt_guardar"])){
		guardarRegRendimiento();
	}
	else{

		//Guardar el ID de la Mezcla seleccionada en la SESSION, para su uso posterior
		if(isset($_POST['sbt_continuar']))
			$_SESSION['idMezclaSel'] = $_POST['rdb_idMezcla'];
			
			
		if(!isset($_POST['sbt_continuarRegistro'])){
			//Obtener datos adicionales de la Mezcla
			$expediente = obtenerDato("bd_laboratorio","mezclas","expediente","id_mezcla",$_SESSION['idMezclaSel']);
			$equipo_mez = obtenerDato("bd_laboratorio","mezclas","equipo_mezclado","id_mezcla",$_SESSION['idMezclaSel']);?> 
			
			
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-agregar">Registrar Resultados de Rendimiento </div>
			<fieldset class="borde_seccion" id="tabla-agregarPrueba" name="tabla-agregarPrueba">
			<legend class="titulo_etiqueta">Ingrese Informaci&oacute;n de Rendimiento </legend>
			<br />
			<form onsubmit="return valFormRegistrarRendimiento(this);" method="post" name="frm_registrarResultadoRendimiento" action="frm_registrarResultadoRendimiento2.php">
			<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="15%"><div align="right">Id Mezcla</div></td>
					<td width="30%">
						<input type="text" name="txt_idMezcla" id="txt_idMezcla" value="<?php echo $_SESSION['idMezclaSel']; ?>" readonly="readonly" size="30" 
						class="caja_de_texto" />				
					</td>
					<td width="15%"><div align="right">Fecha</div></td>
					<td width="40%">
						<input type="text" name="txt_fecha" id="txt_fecha" value="<?php echo date("d/m/Y");?>" size="10" maxlength="10" readonly="readonly"
						class="caja_de_texto" />				
					</td>				
				</tr>
				<tr>
					<td><div align="right">Expediente</div></td>
					<td>
						<input type="text" name="txt_expediente" id="txt_expediente" value="<?php echo $expediente;?>" size="5" maxlength="5" readonly="readonly"
						class="caja_de_texto" />
					</td>
					<td><div align="right">Equipo Mezclado </div></td>
					<td>
						<input type="text" name="txt_equipo" id="txt_equipo" value="<?php echo $equipo_mez;?>" size="30" maxlength="30" readonly="readonly" 
						class="caja_de_texto" />
					</td>
				</tr>
				<tr>
				  <td><div align="right">*Localizaci&oacute;n</div></td>
					<td>
						<input type="text" name="txt_localizacion" id="txt_localizacion" value="" size="30" maxlength="50" onkeypress="return permite(event,'num_car',0);" 
						class="caja_de_texto" />
					</td>
					<td><div align="right">*No. Muestra</div></td>
					<td>
						<input type="text" name="txt_numMuestra" id="txt_numMuestra" value="" size="5" maxlength="2" onkeypress="return permite(event,'num',2);"
						class="caja_de_texto" />
						&nbsp;&nbsp;&nbsp;*Temperatura
						<input type="text" name="txt_temperatura" id="txt_temperatura" value="" size="5" maxlength="10" onkeypress="return permite(event,'num',2);"
						class="caja_de_texto" />
						&nbsp;&deg;C
					</td>              
				</tr>
				<tr>
					<td><div align="right">*Revenimiento</div></td>
					<td>
						<input type="text" name="txt_revenimiento" id="txt_revenimiento" value="" size="10" maxlength="10" onkeypress="return permite(event,'num',2);" 
						class="caja_de_texto" />&nbsp;cm
					</td>
					<td><div align="right">*Hora</div></td>
					<td><input type="text" name="txt_hora" id="txt_hora" value="" size="13" maxlength="10" onchange="validarHoras(this);" />&nbsp;Hrs:min</td>
				</tr>
				<tr>
					<td><div align="right">Observaciones</div></td>
					<td valign="top" rowspan="2">
						<textarea name="txa_observaciones" id="txa_observaciones" class="caja_de_texto" cols="40" rows="3" maxlength="120"
						onkeyup="return ismaxlength(this);"></textarea> 
					</td>
					<td><div align="right">Notas</div></td>
					<td>
						<textarea name="txa_notas" id="txa_notas" class="caja_de_texto" cols="40" rows="3" maxlength="160"  
						onkeyup="return ismaxlength(this);"></textarea>
					</td>				
				</tr>
				<tr>
					<td colspan="4"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
				<tr>
					<td colspan="4" align="center">
						<input name="sbt_continuarRegistro" type="submit" class="botones_largos" id="sbt_continuarRegistro"  value="Continuar Registro" 
						title="Continuar con Registro de Rendimiento" onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar" 
						onmouseover="window.status='';return true" onclick="confirmarSalida('menu_mezclas.php');"/>				
					</td>
				</tr>
			</table>
			</form>
			</fieldset>
			
			<div id="calendario-FechaProg">
				<input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_registrarResultadoRendimiento.txt_fecha,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha"/> 
			</div><?php 
			
		}//Cierre de if(!isset($_POST['sbt_continuarRegistro']))
		else if(isset($_POST['sbt_continuarRegistro'])){
		
			//Guardar en la SESSION los datos Generales del Rendimiento de la Mezcla seleccionda		
			$rendimiento = array("idMezcla"=>$_POST['txt_idMezcla'], "fecha"=>$_POST['txt_fecha'], "lugar"=>strtoupper($_POST['txt_localizacion']), 
								"numMuestra"=>$_POST['txt_numMuestra'],"revenimiento"=>$_POST['txt_revenimiento'], "temperatura"=>$_POST['txt_temperatura'], 
								"hora"=>$_POST['txt_hora'],"observaciones"=>strtoupper($_POST["txa_observaciones"]), "notas"=>strtoupper($_POST["txa_notas"]));
			$_SESSION["rendimiento"]=$rendimiento;?>
			
			
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-agregar">Registrar Resultados de Rendimiento </div>
			<script type="text/javascript" language="javascript">
				setTimeout("document.getElementById('txt_pvolBruto').focus();",500);
			</script>
			
			
			<fieldset class="borde_seccion" id="tabla-detalleRendimiento" name="tabla-detalleRendimiento">
			<legend class="titulo_etiqueta">Ingrese Detalle de Rendimiento Para la Mezcla <?php echo $_SESSION['idMezclaSel']; ?> </legend>
			<br />
			<form onsubmit="return valFormRegistrarRendimiento2(this);" name="frm_registrarResultadoRendimiento2" method="post" action="frm_registrarResultadoRendimiento2.php" >
				<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td colspan="8">
							<label class="titulo_etiqueta"><div align="center">P.Vol. (Kg/m&sup3;)</div></label>
						</td>
					</tr>
					<tr>
						<td width="14%"><div align="right">*Peso Bruto</div></td>
						<td width="12%" >
							<input type="text" name="txt_pvolBruto" id="txt_pvolBruto" value="" size="10" maxlength="10" class="caja_de_texto" 
							onchange="formatNumDecimalLab(this.value,'txt_pvolBruto'); calcularPesoUnitario();" onkeypress="return permite(event,'num',2);" tabindex="1"/>
						</td>             
						<td width="14%"><div align="right">*Peso Molde</div></td>
						<td width="12%" >
							<input type="text" name="txt_pvolMolde" id="txt_pvolMolde" value="" size="10" maxlength="10" class="caja_de_texto" 
							onchange="formatNumDecimalLab(this.value,'txt_pvolMolde'); calcularPesoUnitario();" onkeypress="return permite(event,'num',2);" tabindex="2" />
						</td> 
						<td width="12%"><div align="right">*Peso Unitario</div></td>
						<td width="12%" >
							<input type="text" name="txt_pvolUnitario" id="txt_pvolUnitario" value="" size="10" maxlength="10" class="caja_de_texto" readonly="readonly"/>
						</td>   
						<td width="12%"><div align="right">*Factor Recipiente</div></td>
						<td width="12%">
							<input type="text" name="txt_factorRec" id="txt_factorRec"size="10" maxlength="10"  value="141.3229" class="caja_de_texto"
							onchange="formatNumDecimalLab(this.value,'txt_factorRec'); calcularPesoUnitario();" onkeypress="return permite(event,'num',2);" tabindex="3"/>
						</td>  
					</tr> 
					<tr>
						<td colspan="4">
							<label class="titulo_etiqueta"><div align="center">Rendimiento (m&sup3;)</div></label>
						</td>
						<td colspan="4">
							<label class="titulo_etiqueta"><div align="center">Contenido de Aire (%)</div></label>
						</td>
					</tr>
					<tr>
						<td><div align="right">*Peso Volumen Te&oacute;rico</div></td>
						<td><?php 
							//Realizar la conexion a la BD 
							$conn = conecta("bd_laboratorio");
							$stm_sql2 = "SELECT SUM(cantidad) as total FROM materiales_de_mezclas WHERE mezclas_id_mezcla='$_SESSION[idMezclaSel]'";
							$rs2 = mysql_query($stm_sql2);
							$volTeoricoRend = "0.00";
							if($datos=mysql_fetch_array($rs2)){
								//Obtener la cantidad de decimales del numero renodeado de 5 digitos hacia abajo
								$cantDecimales = contarDecimales(round($datos['total'],5));
								$volTeoricoRend = number_format($datos['total'],$cantDecimales,".",",");
							}
							//Cerrar conexion con la BD
							mysql_close();?> 					
							<input type="text" name="txt_pvolTeoricoRend" id="txt_pvolTeoricoRend" readonly="readonly" size="10" 
							value="<?php echo $volTeoricoRend; ?>" class="caja_de_texto" />
						</td>             
						<td><div align="right">*Peso Volumen</div></td>
						<td>
							<input type="text" name="txt_volRend" id="txt_volRend" size="10" readonly="readonly" value="" class="caja_de_texto" />
						</td> 
						<td><div align="right">*Peso Volumen Te&oacute;rico </div></td>
						<td>
							<input type="text" name="txt_pvolTeoricoAire" id="txt_pvolTeoricoAire"  value="<?php echo  $volTeoricoRend; ?>" size="10" readonly="readonly"
							class="caja_de_texto" />
						</td>   
						<td><div align="right">*Peso Volumen</div></td>
						<td>
							<input type="text" name="txt_pvolAire" id="txt_pvolAire" value="" size="10" maxlength="10" readonly="readonly" class="caja_de_texto"/>
						</td>   
					</tr>
					<tr>
						<td colspan="4">
							<label class="titulo_etiqueta"><div align="center">Contenido Real del Cemento (Kg)</div></label>
						</td>
						<td colspan="4">
							<label class="titulo_etiqueta"><div align="center">Contenido Real del Aire (%)</div></label>
						</td>
					</tr>
					<tr>
						<td><div align="right">*Cb</div></td><?php 
							//Realizar la conexion a la BD 
							$conn = conecta("bd_laboratorio");
							$stm_sql = "SELECT cantidad FROM materiales_de_mezclas WHERE mezclas_id_mezcla='$_SESSION[idMezclaSel]' AND catalogo_materiales_id_material='MATGT005'";
							$rs = mysql_query($stm_sql);
							$CB="0.00";
							if($datos=mysql_fetch_array($rs)){
								//Obtener la cantidad de decimales del numero renodeado de 5 digitos hacia abajo
								$cantDecimales = contarDecimales(round($datos['cantidad'],5));
								$CB = number_format($datos['cantidad'], $cantDecimales,".",",");
							}
							//Cerrar la conexion con la BD						
							mysql_close();?> 
						<td>
							<input type="text" name="txt_cb" id="txt_cb" value="<?php echo $CB;?>" size="10" readonly="readonly" class="caja_de_texto" />
						</td>
						<td><div align="right">*R</div></td>
						<td>
							<input type="text" name="txt_r" id="txt_r" value="" size="10" onchange="formatCurrency(value,'txt_r')" readonly="readonly" class="caja_de_texto" />
						</td>
						<td><div align="right">*Contenido Real Aire</div></td>
						<td>
							<input type="text" name="txt_caireReal" id="txt_caireReal" value="" size="10" maxlength="10" onchange="formatNumDecimalLab(this.value,'txt_caireReal')" 
							onkeypress="return permite(event,'num',2);" class="caja_de_texto" tabindex="4" />
						</td>
					</tr>
					<tr>
						<td colspan="8"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
					</tr>
					<tr>
						<td colspan="8" align="center">
							<input type="hidden" name="hdn_pruebasCargadas" id="hdn_pruebasCargadas" value="no"/>
							<input type="hidden" name="hdn_disenioMod" id="hdn_disenioMod" value="no"/>	
							
							
							<input name="btn_modificarDisenio" id="btn_modificarDisenio" type="button" class="botones" value="Modificar Dise&ntilde;o" 
							title="Modificar Diseño de la Mezcla Seleccionada"	onclick="window.open('verModDisenio.php?idMezcla=<?php echo $_POST['txt_idMezcla']; ?>', 
							'_blank','top=100, left=100, width=800, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no')" />
							&nbsp;&nbsp;&nbsp;
							<input name="btn_verPruebasLab" id="btn_verPruebasLab" type="button" class="botones" value="Cargar Pruebas" title="Agregar Pruebas Aplicadas" 
							onmouseover="window.status='';return true" 
							onclick="window.open('verPruebasLab.php?accion=mostrarPruebas', 
							'_blank','top=100, left=100, width=800, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no')" />
							&nbsp;&nbsp;&nbsp;											
							<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Registro de Rendimiento" 
							onmouseover="window.status='';return true" tabindex="5" />
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
							onmouseover="window.status='';return true" tabindex="6" onclick="btn_verPruebasLab.disabled=false;" />
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar2" value="Cancelar" title="Cancelar y Regresar" tabindex="7"
							onmouseover="window.status='';return true" onclick="confirmarSalida('frm_registrarResultadoRendimiento2.php?cancelar');" />
						</td>
					</tr>
			  </table>
			</form>
			</fieldset><?php 
		}//Cierre else if(isset($_POST['sbt_continuarRegistro']))
		
	}//Cierre de else if(isset($_POST["sbt_guardar"]))?>
	
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
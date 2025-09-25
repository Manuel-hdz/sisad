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
	
		include("op_gestionarBitacoras.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link> 
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="includes/ajax/fallasConsumosTNT.js"></script>
	
	<script type="text/javascript" language="javascript">
		//Esta variable guardará la referencia de la página de Modificar Registro Fallas/Consumos para detectar cuando ésta sea crerrada.
		var vtnAbierta = "";
		//Al cargar la pagina colocar el foco la caja de texto donde ira el nombre del Perforista
		setTimeout("document.frm_modBarrMP.txt_perforista.focus();",500);
	</script>
    <style type="text/css">
		<!--
		#titulo-barrMP { position:absolute; left:30px; top:146px; width:454px; height:20px; z-index:11; }
		#form-registrarDatos { position:absolute; left:30px; top:190px; width:910px; height:400px; z-index:12; }		
		#res-spider1 { position:absolute; left:125px; top:50px; width:361px; height:183px; z-index:13; }
		#res-spider2 { position:absolute; left:125px; top:87px; width:361px; height:183px; z-index:14; }
		#calendario { position:absolute; left:824px; top:252px; width:30px; height:27px; z-index:15; }
		-->
    </style>
</head>
<body onfocus="verificarCierreVtn();">

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>	
	<div id="titulo-barrMP" class="titulo_barra">Modificar Registro de Barrenaci&oacute;n con M&aacute;quina de Pierna</div>
	<?php
	
	if(!isset($_POST['sbt_modificar'])){
		//Obtener el Id del registro de la Bitacora de Avance y el tipo de bitácora que vienen en la SESSION
		$idBitAvance = $_SESSION['bitacoraAvance']['idBitacora'];		
		$tipoBitacora = $_SESSION['bitacoraAvance']['tipoBitacora'];
		
		//Formular las sentencias para obtener los datos de las diferentes bitacoras
		/*BARRENACION_MP=> */ $sql_stm_barrMP = "SELECT * FROM barrenacion_maq_pierna WHERE bitacora_avance_id_bitacora = '$idBitAvance'";		
		/*EQUIPO		=> */ $sql_stm_equipo = "SELECT * FROM equipo WHERE bitacora_avance_id_bitacora = '$idBitAvance' AND area = 'MP'";
		/*PERSONAL		=> */ $sql_stm_personal = "SELECT * FROM personal WHERE bitacora_avance_id_bitacora = '$idBitAvance' AND area = 'MP'";
		
		//Hacer la Consulta para obtener los datos de la Bitacora 
		$conn = conecta("bd_desarrollo");
		
		
		//Ejecutar las Diferentes Sentencias SQL
		$rs_barrMP = mysql_query($sql_stm_barrMP);
		$rs_equipo = mysql_query($sql_stm_equipo);
		$rs_personal = mysql_query($sql_stm_personal);
		
		
		//Declarar las variables con valores por defecto, en el caso de que la consulta realizada no arroje ningún resultado
		$perforista = ""; $ayudante = ""; $turno = ""; $fechaReg = date("d/m/Y");				
		$idEquipo = "";	$horoIni = ""; $horoFin = ""; $totalHoras = "";				
		$barrDados = ""; $disparos = ""; $longitud = ""; $brocasNuevas = ""; $brocasAfiladas = ""; $barras6 = ""; $barras8 = ""; $anclas = "";
		$observaciones = "";
		
		
		//Extraer los datos de los ResultSet's		
		if($datosBarrMP=mysql_fetch_array($rs_barrMP)){
			//Obtener los datos del Equipo y de Personal de los ResultSet's correspodnientes
			$datosEquipo = mysql_fetch_array($rs_equipo);
		  	$datosPersonal = mysql_fetch_array($rs_personal);
						
			//Recuperar los datos del Personal
			$perforista = $datosPersonal['nombre'];//Recuperar el nombre del Operador, el operador siempre viene primero
			$datosPersonal = mysql_fetch_array($rs_personal);//Extraer el sig. registro donde viene el Ayudante
			$ayudante = $datosPersonal['nombre'];//Recuperar el nombre del Ayudante, el Ayudnate siempre viene segundo
			$turno = $datosBarrMP['turno']; 
			$fechaReg = modFecha($datosBarrMP['fecha'],1);
			
			//Recuperar los datos del Equipo
			$idEquipo = $datosEquipo['id_equipo'];
			$horoIni = $datosEquipo['horo_ini']; 
			$horoFin = $datosEquipo['horo_fin'];
			$totalHoras = $datosEquipo['horas_totales'];
			
			//Recuperar los datos de Barrenación
			$barrDados = $datosBarrMP['barrenos_dados'];
			$disparos = $datosBarrMP['barrenos_disparos'];
			$longitud = $datosBarrMP['barrenos_longitud'];
			$brocasNuevas = $datosBarrMP['broca_nva'];
			$brocasAfiladas = $datosBarrMP['broca_afil'];
			$barras6 = $datosBarrMP['barra_6'];
			$barras8 = $datosBarrMP['barra_8'];
			$anclas = $datosBarrMP['anclas'];
						
			$observaciones = $datosBarrMP['observaciones'];
		}//Cierre if($datosBarrMP=mysql_fetch_array($rs_barrMP))
		
		
		
		//Cerrar la conexión con la BD de Desarrollo
		mysql_close($conn);?>
		
		<fieldset class="borde_seccion" id="form-registrarDatos" name="form-registrarDatos">
		<legend class="titulo_etiqueta">Ingresar la informaci&oacute;n del Registro de Barrenaci&oacute;n con M&aacute;quina de Pierna</legend>
		<form onsubmit="return valFormModBarrMP(this);" name="frm_modBarrMP" method="post" action="frm_modBarrMP.php">
		<table cellpadding="5" cellspacing="5">
			<tr>
				<td align="right">*Perforista</td>
				<td colspan="3">
					<input name="txt_perforista" type="text" class="caja_de_texto" id="txt_perforista" tabindex="1" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookup(this,'empleados','1');" value="<?php echo $perforista; ?>" size="50" maxlength="80" />
					<div id="res-spider1">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
				  	</div>
					<?php //Esta variable 'hdn_rfc' guarda el RFC del empleado seleccionado en la Busqueda Sphider ?>
					<input type="hidden" name="hdn_rfc" id="hdn_rfc" value="" />				
				</td>			
	  			<td align="right" colspan="2">*Turno</td>
				<td colspan="2">
					<select name="cmb_turno" id="cmb_turno" class="combo_box" tabindex="2">
						<option value="">Turno</option>
						<option value="PRIMERA" <?php if($turno=="PRIMERA"){?> selected="selected" <?php }?>>PRIMERA</option>
						<option value="SEGUNDA" <?php if($turno=="SEGUNDA"){?> selected="selected" <?php }?>>SEGUNDA</option>
						<option value="TERCERA" <?php if($turno=="TERCERA"){?> selected="selected" <?php }?>>TERCERA</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">*Ayudante</td>
				<td colspan="3">
					<input name="txt_ayudante" type="text" class="caja_de_texto" id="txt_ayudante" tabindex="4" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookup(this,'empleados','2');" value="<?php echo $ayudante; ?>" size="50" maxlength="80" />
					<div id="res-spider2">
						<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
						</div>
				  </div>				
			  </td>			
				<td colspan="2" align="right">Fecha Registro</td>
				<td colspan="2">
					<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" readonly="readonly" size="10" 
					value="<?php echo $fechaReg; ?>" />
				</td>
	 	  	</tr>
			<tr>
				<td align="right">*Equipo</td>
				<td colspan="7"><?php								
					$conn = conecta("bd_mantenimiento");//Conectarse a la Base de Datos
					$result=mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE familia = 'PERFORADORA' AND disponibilidad = 'ACTIVO' ORDER BY id_equipo");
						
					if($registro=mysql_fetch_array($result)){?>				
						<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="5">
							<option value="">Equipo</option><?php															 
							do{
								if($idEquipo==$registro['id_equipo']){?>
									<option value="<?php echo $registro['id_equipo']; ?>" title="<?php echo $registro['id_equipo']; ?>" selected="selected">
										<?php echo $registro['id_equipo']; ?>
									</option><?php								
								} else {?>
									<option value="<?php echo $registro['id_equipo']; ?>" title="<?php echo $registro['id_equipo']; ?>">
										<?php echo $registro['id_equipo']; ?>
									</option><?php
								}
							}while($registro=mysql_fetch_array($result))?>
						</select><?php
					} else {?>
						<span class="msje_correcto">No Hay Equipos Registrados</span>
						<input type="hidden" name="cmb_equipo" id="cmb_equipo" value="" /><?php
					}
					mysql_close($conn);//Cerrar la conexion con la BD ?>		  	  	
				</td>
			</tr>
			<tr>
				<td align="right">*Hor&oacute;metro Inicial</td>            
				<td>
					<input type="text" name="txt_HIEquipo" id="txt_HIEquipo" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="6"
					onblur="calcularHorasTotales('txt_HIEquipo','txt_HFEquipo','txt_HTEquipo');" value="<?php echo number_format($horoIni,2,".",","); ?>" 
					onchange="formatCurrency(this.value,'txt_HIEquipo')" />
				</td>
				<td align="right">*Hor&oacute;metro Final</td>
				<td>
					<input type="text" name="txt_HFEquipo" id="txt_HFEquipo" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="7"
					onblur="calcularHorasTotales('txt_HIEquipo','txt_HFEquipo','txt_HTEquipo');" value="<?php echo number_format($horoFin,2,".",","); ?>"
					onchange="formatCurrency(this.value,'txt_HFEquipo')" />  		  		
				</td>
				<td align="right">Hrs. Totales</td>
				<td>
					<input type="text" name="txt_HTEquipo" id="txt_HTEquipo" class="caja_de_texto" size="9" readonly="readonly" 
					value="<?php echo number_format($totalHoras,2,".",","); ?>" />
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>				
			</tr>
			<tr>
				<td align="right">*Barrenos Dados </td>
				<td>
					<input type="text" name="txt_barrDados" id="txt_barrDados" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					tabindex="13" value="<?php echo $barrDados; ?>" />
				</td>
				<td align="right">*Disparos</td>
				<td>
					<input type="text" name="txt_disparos" id="txt_disparos" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					tabindex="14" value="<?php echo $disparos; ?>" />
				</td>
				<td align="right">*Longitud</td>
				<td>
					<input type="text" name="txt_longitud" id="txt_longitud" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					tabindex="15" value="<?php echo $longitud; ?>" />
				</td>
				<td align="right">*Brocas Nuevas</td>
				<td>
					<input type="text" name="txt_brocasNuevas" id="txt_brocasNuevas" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="16" value="<?php echo $brocasNuevas; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">*Brocas Afiladas</td>
				<td>
					<input type="text" name="txt_brocasAfiladas" id="txt_brocasAfiladas" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="17" value="<?php echo $brocasAfiladas; ?>" />
				</td>
				<td align="right">*Barras 6</td>
				<td>
					<input type="text" name="txt_barras6" id="txt_barras6" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					tabindex="18" value="<?php echo $barras6; ?>" />
				</td>
				<td align="right">*Barras 8</td>
				<td>
					<input type="text" name="txt_barras8" id="txt_barras8" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					tabindex="19" value="<?php echo $barras8; ?>" />
				</td>
				<td align="right">*Anclas</td>
				<td>
					<input type="text" name="txt_anclas" id="txt_anclas" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					tabindex="20" value="<?php echo $anclas; ?>" />
				</td>
			</tr>
			<tr>
				<td align="right">Observaciones</td>
				<td colspan="2">
					<textarea name="txa_observaciones" onkeyup="return ismaxlength(this)" maxlength="120" class="caja_de_texto" rows="3" cols="35" 
					onkeypress="return permite(event,'num_car',0);" tabindex="21" ><?php echo $observaciones; ?></textarea> 		  		
				</td>           
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
	  	  	</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td colspan="7"><strong>*Los  datos marcados con asterisco (*) son obligatorios.</strong></td>
			</tr>
			<tr>
				<td width="15%">&nbsp;</td>
				<td width="10%">&nbsp;</td>
				<td width="15%">&nbsp;</td>
				<td width="15%">&nbsp;</td>
				<td width="10%">&nbsp;</td>
				<td width="10%">&nbsp;</td>
				<td width="15%">&nbsp;</td>
				<td width="10%">&nbsp;</td>
			</tr>
			<tr>
				<td align="center" colspan="8"><?php 
					/*Estas variables ayudan a identificar cual de las Bitácoras (Avance y Retro-Bull) será registrada en las Bitacoras de Fallas y Consumos
					asi como el tipo de registro (Bitacora de Barrenación(Jumbo y MP), Voladura y Rezagado*/ ?>
					<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $idBitAvance; ?>" />
					<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="<?php echo $tipoBitacora; ?>" />
					<input type="hidden" name="hdn_tipoRegistro" id="hdn_tipoRegistro" value="BARRENACIONMP" />
					
					<?php //Esta variable ayudara a determinar el tipo de Falla (Operativa, Mecánica y Eléctrica) que sera registrada en la Bitacora de Fallas?>
					<input type="hidden" name="hdn_tipoEquipo" id="hdn_tipoEquipo" value="MP" />
					
					<?php //Esta variable indica si fueron modificados los cosumos del equipo, en el caso que no se notifica al usuario sobre este hecho?>
					<input type="hidden" name="hdn_regBitConsumos" id="hdn_regBitConsumos" value="no" />
					
					<?php //Esta variable indica si la Bitácora de Barrenación con Jumbo debe ser Actualizada(valor='si') o Registrada(valor='no') por primera vez ?>
					<input type="hidden" name="hdn_actualizarBitacora" id="hdn_actualizarBitacora" value="<?php echo $_POST['hdn_bitBarrMP']?>" />
					
					
					
					<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Datos en la Bit&aacute;cora" 
					onmouseover="window.status='';return true" tabindex="22" />					
					&nbsp;&nbsp;
					<input name="btn_regFallas" id="btn_regFallas" type="button" class="botones_largos" value="Modificar Fallas" 
					title="Modificar Fallas de los Equipos" onmouseover="window.status='';return true" onclick="abrirVentana('fallas','modificar');" tabindex="23" />
					&nbsp;&nbsp;
					<input name="btn_regConsumos" id="btn_regConsumos" type="button" class="botones_largos" value="Modificar Consumos" 
					title="Modificar Consumos Realizados" onmouseover="window.status='';return true" onclick="abrirVentana('consumos','modificar');" tabindex="24" />					
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Restablecer" class="botones" title="Restablecer los Campos del Formulario" tabindex="25" />
					&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Anterior" 
					onclick="confirmarRegreso('frm_modAvance2.php');" tabindex="26" />																			
				</td>         
			</tr>
		</table>	
		</form>
		</fieldset>
		
		<div id="calendario">
			<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modBarrMP.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Registro" tabindex="3" /> 
		</div><?php
	}//Cierre if(!isset($_POST['sbt_modificar'])) 
	else{
		//Actualizar los datos de la Bitácora en la Base de Datos
		modificarBitBarrenacionMP();
	}?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
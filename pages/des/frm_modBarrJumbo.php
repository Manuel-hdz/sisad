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
		//Esta variable guardará la referencia de la página de Modificar Registro Fallas/Explosivos para detectar cuando ésta sea crerrada.
		var vtnAbierta = "";
		//Al cargar la pagina colocar el foco la caja de texto donde ira el nombre de Jumbero
		setTimeout("document.frm_modBitBarrenacionJumbo.txt_jumbero.focus();",500);
	</script>
    <style type="text/css">
		<!--
		#titulo-barrJumbo { position:absolute; left:30px; top:146px; width:350px; height:20px; z-index:11; }
		#form-registrarDatos { position:absolute; left:30px; top:190px; width:940px; height:600px; z-index:12; }		
		#res-spider1 { position:absolute; left:100px; top:40px; width:1px; height:1px; z-index:13; }
		#res-spider2 { position:absolute; left:100px; top:70px; width:1px; height:1px; z-index:14; }
		#calendario { position:absolute; left:930px; top:212px; width:30px; height:27px; z-index:15; }
		-->
    </style>
</head>
<body onfocus="verificarCierreVtn();">

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>	
	<div id="titulo-barrJumbo" class="titulo_barra">Modificar Registro de Barrenaci&oacute;n con Jumbo</div><?php
	
	if(!isset($_POST['sbt_modificar'])){
		//Obtener el Id del registro de la Bitacora de Avance y el tipo de bitácora que vienen en la SESSION
		$idBitAvance = $_SESSION['bitacoraAvance']['idBitacora'];
		$tipoBitacora = $_SESSION['bitacoraAvance']['tipoBitacora'];
		
		//Formular las sentencias para obtener los datos de las diferentes bitacoras
		/*BARRENACION JUMBO	=> */ $sql_stm_barrenacion = "SELECT * FROM barrenacion_jumbo WHERE bitacora_avance_id_bitacora = '$idBitAvance'";
		/*BARRENOS	=> */ $sql_stm_barrenos = "SELECT * FROM barrenos WHERE bitacora_avance_id_bitacora = '$idBitAvance' AND area = 'JUMBO'";
		/*BRAZOS	=> */ $sql_stm_brazos = "SELECT * FROM registro_brazos WHERE bitacora_avance_id_bitacora = '$idBitAvance'";
		/*PERSONAL	=> */ $sql_stm_personal = "SELECT * FROM personal WHERE bitacora_avance_id_bitacora = '$idBitAvance' AND area = 'JUMBO' ORDER BY  `personal`.`puesto` DESC ";
		/*EQUIPO	=> */ $sql_stm_equipo = "SELECT * FROM equipo WHERE bitacora_avance_id_bitacora = '$idBitAvance' AND area = 'JUMBO'";
		
		
		//Hacer la Consulta para obtener los datos de la Bitacora 
		$conn = conecta("bd_desarrollo");
		
		//Ejecutar las Diferentes Sentencias SQL
		$rs_barrenacion = mysql_query($sql_stm_barrenacion);
		$rs_barrenos = mysql_query($sql_stm_barrenos);
		$rs_brazos = mysql_query($sql_stm_brazos);		
		$rs_personal = mysql_query($sql_stm_personal);
		$rs_equipo = mysql_query($sql_stm_equipo);
		
		
		//Declarar las variables con valores por defecto, en el caso de que la consulta realizada no arroje ningún resultado
		$jumbero = ""; $ayudante = ""; $turno = ""; $fechaReg = date("d/m/Y");				
		$idEquipo = "";	$horoIni = ""; $horoFin = ""; $totalHoras = "";
		$b1_HI = ""; $b1_HF = ""; $b1_HT = ""; $b2_HI = ""; $b2_HF = ""; $b2_HT = "";
		$barrDados = ""; $disparos = ""; $longitud = ""; $barrDesborde = ""; $barrEncapille = ""; $barrDespate = ""; $reanclaje = "";
		$coples = ""; $zancos = ""; $anclas = ""; $brocasNuevas = ""; $brocasAfiladas = ""; $observaciones = "";
				
		//Variables para activar o desactivar el CheckBox y las Cajas de Texto del HI y HF
		$atrbCheckBox = "";
		$atrbCajaTexto = "readonly='readonly'";
		
		//Extraer los datos de los ResultSet's		
		if($datosBarrJU=mysql_fetch_array($rs_barrenacion)){
			//Obtener los datos de los Barrenos, Brazos, Equipo y de Personal de los ResultSet's correspodnientes
			$datosBarrenos = mysql_fetch_array($rs_barrenos);
			$datosBrazos = mysql_fetch_array($rs_brazos);
			$datosPersonal = mysql_fetch_array($rs_personal);
			$datosEquipo = mysql_fetch_array($rs_equipo);
		  				
			
			//Recuperar los datos del Personal
			$jumbero = $datosPersonal['nombre'];//Recuperar el nombre del Operador, el operador siempre viene primero
			$datosPersonal = mysql_fetch_array($rs_personal);//Extraer el sig. registro donde viene el Ayudante
			$ayudante = $datosPersonal['nombre'];//Recuperar el nombre del Ayudante, el Ayudnate siempre viene segundo
			$turno = $datosBarrJU['turno']; 
			$fechaReg = modFecha($datosBarrJU['fecha'],1);
			
			$jumbero = obtenerPersonalBitacora($jumbero);
			$ayudante = obtenerPersonalBitacora($ayudante);
			
			//Recuperar Datos del Equipo
			$idEquipo = $datosEquipo['id_equipo'];
			$horoIni = $datosEquipo['horo_ini']; 
			$horoFin = $datosEquipo['horo_fin'];
			$totalHoras = $datosEquipo['horas_totales'];
			
			//Recuperar Datos de los Brazos
			$b1_HI = $datosBrazos['horo_ini'];//Recupera datos del Brazo 1, el Brazo 1 siempre viene primero
			$b1_HF = $datosBrazos['horo_fin'];
			$b1_HT = $datosBrazos['horas_totales'];
			//Verificar si hay registro del segundo brazo
			if($datosBrazos=mysql_fetch_array($rs_brazos)){
				$atrbCheckBox = "checked='checked'";
				$atrbCajaTexto = "";
				$b2_HI = $datosBrazos['horo_ini'];
				$b2_HF = $datosBrazos['horo_fin'];
				$b2_HT = $datosBrazos['horas_totales'];
			}			
			
			//Recuperar datos de los Barrenos
			$barrDesborde = $datosBarrenos['desborde'];
			$barrEncapille = $datosBarrenos['encapille'];
			$barrDespate = $datosBarrenos['despate'];
			
			//Recuperar datos de la Barrenación
			$barrDados = $datosBarrJU['barrenos_dados'];
			$disparos = $datosBarrJU['barrenos_disp'];
			$longitud = $datosBarrJU['barrenos_long'];			
			$reanclaje = $datosBarrJU['reanclaje'];
			$coples = $datosBarrJU['coples'];
			$zancos = $datosBarrJU['zancos'];
			$anclas = $datosBarrJU['anclas'];
			$brocasNuevas = $datosBarrJU['broca_nva'];
			$brocasAfiladas = $datosBarrJU['broca_afil'];
			
			$observaciones = $datosBarrJU['observaciones'];
		}//Cierre if($datosBarrJU=mysql_fetch_array($rs_barrenacion))
		
		
		
		//Cerrar la conexión con la BD de Desarrollo
		mysql_close($conn);?>
		
		
		<fieldset class="borde_seccion" id="form-registrarDatos" name="form-registrarDatos">
			<legend class="titulo_etiqueta">Ingresar la Informaci&oacute;n del Registro de Barrenaci&oacute;n con Jumbo</legend>
			<form onsubmit="return valFormModBitBarrenacionJumbo(this);" name="frm_modBitBarrenacionJumbo" method="post" action="frm_modBarrJumbo.php">
			<table width="100%" cellspacing="5">          
			  <tr>
				<td align="right">*Jumbero</td>
				<td colspan="3">
					<input name="txt_jumbero" type="text" class="caja_de_texto" id="txt_jumbero" tabindex="1" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookup(this,'empleados','1');" value="<?php echo $jumbero; ?>" size="50" maxlength="80" />
					<div id="res-spider1">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
			  	  	</div>		
					<?php //Esta variable 'hdn_rfc' guarda el RFC del empleado seleccionado en la Busqueda Sphider ?>
					<input type="hidden" name="hdn_rfc" id="hdn_rfc" value="" />	
				</td>
				<td align="right">*Turno</td>
				<td colspan="2">
					<select name="cmb_turno" id="cmb_turno" class="combo_box" tabindex="2">
						<option value="">Turno</option>
						<option value="PRIMERA" <?php if($turno=="PRIMERA"){?> selected="selected" <?php }?>>PRIMERA</option>
						<option value="SEGUNDA" <?php if($turno=="SEGUNDA"){?> selected="selected" <?php }?>>SEGUNDA</option>
						<option value="TERCERA" <?php if($turno=="TERCERA"){?> selected="selected" <?php }?>>TERCERA</option>
					</select>
				</td>
				<td align="right">Fecha Registro</td>
				<td>
					<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" readonly="readonly" size="10" value="<?php echo $fechaReg; ?>" />
				</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td align="right">*Ayudante</td>
				<td colspan="3">
					<input name="txt_ayudante" type="text" class="caja_de_texto" id="txt_ayudante" tabindex="4" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookup(this,'empleados','2');" value="<?php echo $ayudante; ?>" size="50" maxlength="80"/>
					<div id="res-spider2">
						<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
						</div>
				  </div>			
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td align="right">*Equipo</td>
				<td><?php								
					$conn = conecta("bd_mantenimiento");//Conectarse a la Base de Datos
					$result=mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE familia = 'JUMBOS' AND disponibilidad = 'ACTIVO' ORDER BY id_equipo");
						
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
				<td colspan="2" align="right">*Hor&oacute;metro Inicial</td>            
				<td>
					<input type="text" name="txt_HIEquipo" id="txt_HIEquipo" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="6"
					onblur="calcularHorasTotales('txt_HIEquipo','txt_HFEquipo','txt_HTEquipo');" value="<?php echo number_format($horoIni,2,".",","); ?>"
					onchange="formatCurrency(this.value,'txt_HIEquipo')" />
				</td>
				<td colspan="2" align="right">*Hor&oacute;metro Final</td>
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
			  </tr>
			  <tr>
				<td align="right">Brazo 1</td>
				<td>
					*HI<input type="text" name="txt_HIB1" id="txt_HIB1" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="8"
					onblur="calcularHorasTotales('txt_HIB1','txt_HFB1','txt_HTB1');" value="<?php echo number_format($b1_HI,2,".",","); ?>" 
					onchange="formatCurrency(this.value,'txt_HIB1')" />			
				</td>
				<td>
					*HF<input type="text" name="txt_HFB1" id="txt_HFB1" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" tabindex="9"
					onblur="calcularHorasTotales('txt_HIB1','txt_HFB1','txt_HTB1');" value="<?php echo number_format($b1_HF,2,".",","); ?>"
					onchange="formatCurrency(this.value,'txt_HFB1')" />			
				</td>
				<td>
					HT<input type="text" name="txt_HTB1" id="txt_HTB1" class="caja_de_texto" size="9" readonly="readonly" 
					value="<?php echo number_format($b1_HT,2,".",","); ?>" />
				</td>
				<td>&nbsp;</td>
				<td align="right">
					<input type="checkbox" name="ckb_brazo2" id="ckb_brazo2" value="activo" onclick="activarCampos(this);" tabindex="10" <?php echo $atrbCheckBox; ?> />Brazo 2
				</td>
				<td>
					**HI<input type="text" name="txt_HIB2" id="txt_HIB2" class="caja_de_texto" size="9" maxlength="15" 
					onblur="calcularHorasTotales('txt_HIB2','txt_HFB2','txt_HTB2');" onkeypress="return permite(event,'num',2);" <?php echo $atrbCajaTexto; ?>  
					value="<?php echo number_format($b2_HI,2,".",","); ?>" onchange="formatCurrency(this.value,'txt_HIB2')" />
				</td>
				<td>
					**HF<input type="text" name="txt_HFB2" id="txt_HFB2" class="caja_de_texto" size="9" maxlength="15" 
					onblur="calcularHorasTotales('txt_HIB2','txt_HFB2','txt_HTB2');" onkeypress="return permite(event,'num',2);" <?php echo $atrbCajaTexto; ?> 
					value="<?php echo number_format($b2_HF,2,".",","); ?>" onchange="formatCurrency(this.value,'txt_HFB2')" />			
				</td>
				<td>
					HT<input type="text" name="txt_HTB2" id="txt_HTB2" class="caja_de_texto" size="9" readonly="readonly" 
					value="<?php echo number_format($b2_HT,2,".",","); ?>" />
				</td>
				<td>&nbsp;</td>
			  </tr>
			  <?php
				$i = 0;
				$con = conecta("bd_desarrollo");
				$stm_sql = "SELECT * 
							FROM  `barrenacion_jumbo` 
							JOIN  `barrenos` 
							USING (  `bitacora_avance_id_bitacora` ) 
							WHERE  `bitacora_avance_id_bitacora` =  '$idBitAvance'";
				$rs = mysql_query($stm_sql);
				if($rs){
					while($datos = mysql_fetch_array($rs)){
			  ?>
			  <tr>
				<td colspan="8" align="left"><span class="titulo_etiqueta">Barrenacion <?php echo $i+1;?></span><input type="checkbox" title="Activelo para que el Registro de Voladura sea guardado" name="ckb_activarBarr<?php echo $i;?>" id="ckb_activarBarr<?php echo $i;?>"/></td>
			  </tr>
			  <tr>
				<td align="right">*Barrenos Dados </td>
				<td>
					<input type="text" name="txt_barrDados<?php echo $i;?>" id="txt_barrDados<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="13" value="<?php echo $datos["barrenos_dados"]; ?>"/>
				</td>
				<td align="right">*Barrenos Desborde </td>
				<td>
					<input type="text" name="txt_barrDesborde<?php echo $i;?>" id="txt_barrDesborde<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="16" value="<?php echo $datos["desborde"] / 2; ?>"/>
				</td>
				<td align="right">*Barrenos Encapille </td>
				<td>
					<input type="text" name="txt_barrEncapille<?php echo $i;?>" id="txt_barrEncapille<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="17" value="<?php echo $datos["encapille"] / 2; ?>"/>
				</td>
				<td align="right">*Barrenos Despate </td>
				<td>
					<input type="text" name="txt_barrDespate<?php echo $i;?>" id="txt_barrDespate<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="18" value="<?php echo $datos["despate"] / 2; ?>"/>
				</td>
				<!--<td align="right">*Disparos</td>
				<td>
					<input type="text" name="txt_disparos<?php echo $i;?>" id="txt_disparos<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="14" />
				</td>
				<td align="right">*Longitud</td>
				<td>
					<input type="text" name="txt_longitud<?php echo $i;?>" id="txt_longitud<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="15" />
				</td>-->
				<input type="hidden" name="txt_disparos<?php echo $i;?>" id="txt_disparos<?php echo $i;?>" value="<?php echo $datos["barrenos_disp"]; ?>"/>
				<input type="hidden" name="txt_longitud<?php echo $i;?>" id="txt_longitud<?php echo $i;?>" value="<?php echo $datos["barrenos_long"]; ?>"/>
				<input type="hidden" name="txt_coples<?php echo $i;?>" id="txt_coples<?php echo $i;?>" value="<?php echo $datos["coples"]; ?>"/>
				<input type="hidden" name="txt_zancos<?php echo $i;?>" id="txt_zancos<?php echo $i;?>" value="<?php echo $datos["zancos"]; ?>"/>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td align="right">*Reanclaje</td>
				<td>
					<input type="text" name="txt_reanclaje<?php echo $i;?>" id="txt_reanclaje<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="19" value="<?php echo $datos["reanclaje"]; ?>"/>
				</td>
				<td width="10%" align="right">*Anclas</td>
				<td width="10%">
					<input type="text" name="txt_anclas<?php echo $i;?>" id="txt_anclas<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="22" value="<?php echo $datos["anclas"]; ?>"/>
				</td>
				<td width="10%" align="right">*Escareado</td>
				<td width="10%">
					<input type="text" name="txt_escareado<?php echo $i;?>" id="txt_escareado<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="23" value="<?php echo $datos["escareado"]; ?>"/>
				</td>
				<td width="10%" align="right">*Topes Barrenados</td>
				<td width="10%">
					<input type="text" name="txt_topesBarrenados<?php echo $i;?>" id="txt_topesBarrenados<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="24" value="<?php echo $datos["topes_barrenados"]; ?>"/>
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<!--<td width="10%" align="right">*Coples</td>
				<td width="10%">
					<input type="text" name="txt_coples<?php echo $i;?>" id="txt_coples<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="20" />
				</td>
				<td width="10%" align="right">*Zancos</td>
				<td width="10%">
					<input type="text" name="txt_zancos<?php echo $i;?>" id="txt_zancos<?php echo $i;?>" class="caja_de_texto" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" tabindex="21" />
				</td>-->
				
			  </tr>
			  <tr>
				<td align="right">Observaciones</td>
				<td colspan="3">
					<textarea name="txa_observaciones<?php echo $i;?>" onkeyup="return ismaxlength(this)" maxlength="120" class="caja_de_texto" rows="3" cols="35" 
					onkeypress="return permite(event,'num_car',0);" tabindex="25" ><?php echo $datos["observaciones"]; ?></textarea>			
				</td>           
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td align="right">&nbsp;</td>
				<td colspan="9"><strong>*Los  datos marcados con asterisco (*) son obligatorios.</strong></td>
			  </tr>
			  <tr>
				<td align="right">&nbsp;</td>
				<td colspan="9"><strong>**Los datos marcados con doble asterisco (**) son obligatorios s&oacute;alo si el Jumbo tiene 2 Brazos</strong></td>
			  </tr>
			  <?php
						$i++;
					}
				}
			  ?>
			  <tr>
				<td align="right">&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td align="center" colspan="10"><?php 
					/*Estas variables ayudan a identificar cual de las Bitácoras (Avance y Retro-Bull) será registrada en las Bitacoras de Fallas y Consumos
					asi como el tipo de registro (Bitacora de Barrenación(Jumbo y MP), Voladura y Rezagado*/ ?>
					<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $idBitAvance; ?>" />
					<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="<?php echo $tipoBitacora; ?>" />
					<input type="hidden" name="hdn_tipoRegistro" id="hdn_tipoRegistro" value="BARRENACION" />
					
					<?php //Esta variable ayudara a determinar el tipo de Falla (Operativa, Mecánica y Eléctrica) que sera registrada en la Bitacora de Fallas?>
					<input type="hidden" name="hdn_tipoEquipo" id="hdn_tipoEquipo" value="JUMBO" />
					
					<?php //Esta variable indica si fueron modificados los cosumos del equipo, en el caso que no se notifica al usuario sobre este hecho?>
					<input type="hidden" name="hdn_regBitConsumos" id="hdn_regBitConsumos" value="no" />
					
					<?php //Esta variable indica si la Bitácora de Barrenación con Jumbo debe ser Actualizada(valor='si') o Registrada(valor='no') por primera vez ?>
					<input type="hidden" name="hdn_actualizarBitacora" id="hdn_actualizarBitacora" value="<?php echo $_POST['hdn_bitBarrJU']?>" />

					
					<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Datos en la Bit&aacute;cora" 
					onmouseover="window.status='';return true" tabindex="26" />					
					&nbsp;&nbsp;
					<input name="btn_regFallas" id="btn_regFallas" type="button" class="botones_largos" value="Modificar Fallas" 
					title="Modificar Fallas de los Equipos" onmouseover="window.status='';return true" onclick="abrirVentana('fallas','modificar');" tabindex="27" />					
					&nbsp;&nbsp;
					<input name="btn_regConsumos" id="btn_regConsumos" type="button" class="botones_largos" value="Modificar Consumos" 
					title="Modificar Consumos Realizados" onmouseover="window.status='';return true" onclick="abrirVentana('consumos','modificar');" tabindex="28" />					
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Restablecer" class="botones" title="Restablecer los Campos del Formulario" tabindex="29" />
					&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Anterior" 
					onclick="confirmarRegreso('frm_modAvance2.php');" tabindex="30" />
				</td>         
			  </tr>
			</table>	
			</form>
		</fieldset>
		
		<div id="calendario">
			<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modBitBarrenacionJumbo.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Registro" tabindex="3" /> 
		</div><?php
	}//Cierre if(!isset($_POST['sbt_modificar']))
	else{
		//Actualzar los datos de la Bitácora en la Base de Datos
		modificarBitBarrenacion();
	}?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
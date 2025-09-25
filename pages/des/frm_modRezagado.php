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
		include("head_menu.php");
		
		include("op_gestionarBitacoras.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link> 
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" language="javascript">
	
		var vtnAbierta = "";//Esta variable guardará la referencia de la página de Modificar Registro Fallas/Consumos para detectar cuando ésta sea crerrada.
	
		function iniciarCamposRezagado(){
			//Colocar el foco en el comboBox donde se selecciona al operador del equipo
			document.frm_modBitRezagado.cmb_operador.focus();
			
			//Colocar el orden de selección a los ComboBox que son cargados con la funcion PHP de cargarCombo
			document.frm_modBitRezagado.cmb_origenTepetate.tabIndex = "7";
			document.frm_modBitRezagado.cmb_destinoTepetate.tabIndex = "8";
			document.frm_modBitRezagado.cmb_origenMineral.tabIndex = "10";
			document.frm_modBitRezagado.cmb_destinoMineral.tabIndex = "11";
		
		}
		//Ejecutar la función JavaScript medio segundo despues de dibujar el formulario
		setTimeout("iniciarCamposRezagado();",500);
		
	</script>
    <style type="text/css">
		<!--
		#titulo-regRezagado {position:absolute; left:30px; top:146px; width:350px; height:20px; z-index:11; }		
		#tabla-registroRezagado {position:absolute; left:30px; top:190px; width:940px; height:460px; z-index:12; }
		#calendario { position:absolute; left:859px; top:217px; width:30px; height:27px; z-index:13; }
		#pregunta { position:absolute; left:30px; top:190px; width:940px; height:200px; z-index:14; }
		-->
    </style>
</head>
<body onfocus="verificarCierreVtn();"><?php

	if(!isset($_POST['sbt_modificar'])){
		//Obtener el Id del registro de la Bitacora de Avance y el tipo de bitácora que vienen en la SESSION
		$idBitAvance = $_SESSION['bitacoraAvance']['idBitacora'];		
		$tipoBitacora = $_SESSION['bitacoraAvance']['tipoBitacora'];		
		
		//Formular las sentencias para obtener los datos de las diferentes bitacoras
		/*REZAGADO	=> */ $sql_stm_rezagado = "SELECT * FROM rezagado WHERE bitacora_avance_id_bitacora = '$idBitAvance'";
		/*EQUIPO	=> */ $sql_stm_equipo = "SELECT * FROM equipo WHERE bitacora_avance_id_bitacora = '$idBitAvance' AND area = 'SCOOP'";
		/*PERSONAL	=> */ $sql_stm_personal = "SELECT * FROM personal WHERE bitacora_avance_id_bitacora = '$idBitAvance' AND area = 'SCOOP'";
		
				
		//Hacer la Consulta para obtener los datos de la Bitacora 
		$conn = conecta("bd_desarrollo");
		
		
		//Ejecutar las Diferentes Sentencias SQL
		$rs_rezagado = mysql_query($sql_stm_rezagado);
		$rs_equipo = mysql_query($sql_stm_equipo);
		$rs_personal = mysql_query($sql_stm_personal);
		
		
		//Declarar las variables con valores por defecto, en el caso de que la consulta realizada no arroje ningún resultado
		$nomOperador = ""; $turno = ""; $fecha = date("d/m/Y");				
		$idEquipo = "";	$horoIni = ""; $horoFin = ""; $totalHoras = "";				
		$orgTepetate = ""; $desTepetate = ""; $cuchTepetate = ""; $orgMineral = ""; $desMineral = ""; $cuchMineral = ""; $observaciones = "";				
				
		
		//Extraer los datos de los ResultSet's		
		if($datosRezagado=mysql_fetch_array($rs_rezagado)){
			//Obtener los datos del Equipo y de Personal de los ResultSet's correspodnientes
			$datosEquipo = mysql_fetch_array($rs_equipo);
		  	$datosPersonal = mysql_fetch_array($rs_personal);
						
			//Recuperar los datos del Personal
			$nomOperador = $datosPersonal['nombre']; 
			$turno = $datosRezagado['turno']; 
			$fecha = modFecha($datosRezagado['fecha'],1);
			
			//Recuperar los datos del Equipo
			$idEquipo = $datosEquipo['id_equipo'];
			$horoIni = $datosEquipo['horo_ini']; 
			$horoFin = $datosEquipo['horo_fin'];
			$totalHoras = $datosEquipo['horas_totales'];
			
			//Recuperar los datos de Tepetate
			$orgTepetate = $datosRezagado['tep_origen'];
			$desTepetate = $datosRezagado['tep_destino'];
			$cuchTepetate = $datosRezagado['tep_cuch'];
			
			//Recuperar los datos del Mineral
			$orgMineral = $datosRezagado['min_origen'];
			$desMineral = $datosRezagado['min_destino'];
			$cuchMineral = $datosRezagado['min_cuch'];			
						
			$observaciones = $datosRezagado['observaciones'];
		}//Cierre if($datosRezagado=mysql_fetch_array($rs_rezagado))		
		
		
		
		//Cerrar la conexión con la BD de Desarrollo
		mysql_close($conn); ?>
		
	
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>		   
		<div id="titulo-regRezagado" class="titulo_barra">Modificar Registro de la Bit&aacute;cora de Rezagado</div>
						
		<fieldset class="borde_seccion" id="tabla-registroRezagado" name="tabla-registroRezagado">
		<legend class="titulo_etiqueta">Modificar la informaci&oacute;n del Registro de Rezagado</legend>
		<form onsubmit="return valFormModBitRezagado(this)" name="frm_modBitRezagado" method="post" action="frm_modRezagado.php">
		<input type="hidden" name="hdn_orgTep" id="hdn_orgTep" value="<?php echo $orgTepetate?>"/>
		<input type="hidden" name="hdn_desTepetate" id="hdn_desTepetate" value="<?php echo $desTepetate?>"/>
		<input type="hidden" name="hdn_cuchTepetate" id="hdn_cuchTepetate" value="<?php echo $cuchTepetate?>"/>
		<input type="hidden" name="hdn_orgMineral" id="hdn_orgMineral" value="<?php echo $orgMineral?>"/>
		<input type="hidden" name="hdn_desMineral" id="hdn_desMineral" value="<?php echo $desMineral?>"/>
		<input type="hidden" name="hdn_cuchMineral" id="hdn_cuchMineral" value="<?php echo $cuchMineral?>"/>
		<table class="tabla_frm" width="100%" cellpadding="5" cellspacing="5">
			<tr>
				<td align="right">*Operador</td>
				<td colspan="2"><?php																	
					$conn = conecta("bd_recursos");//Conectarse a la Base de Datos
					$result=mysql_query("SELECT nombre,ape_pat,ape_mat,puesto,id_empleados_empresa FROM empleados WHERE puesto LIKE '%SCOOP%' AND area='DESARROLLO FRESNILLO' ORDER BY nombre");
					
					if($registro=mysql_fetch_array($result)){?>				
						<select name="cmb_operador" id="cmb_operador" class="combo_box" tabindex="1">
							<option value="">Operador</option><?php															 
							do{
								$nombre = $registro['nombre']." ".$registro['ape_pat']." ".$registro['ape_mat'];
								$id_personal = $registro['id_empleados_empresa'];
								$puesto = $registro['puesto'];
								if($nomOperador==$id_personal){?>
									<option value="<?php echo $id_personal;?>" title="<?php echo $puesto; ?>" selected="selected"><?php echo $nombre; ?></option><?php
								}
								else{?>
									<option value="<?php echo $id_personal;?>" title="<?php echo $puesto; ?>"><?php echo $nombre; ?></option><?php									
								}
							}while($registro=mysql_fetch_array($result))?>
						</select>
						<input type="hidden" name="hdn_puesto" id="hdn_puesto" value="OPERADOR"/><?php
					} else {?>
						<span class="msje_correcto">No Hay Operadores Registrados</span><?php
					}
					mysql_close($conn);//Cerrar la conexion con la BD ?>				
				</td>		   
				<td align="right">*Turno</td>
				<td>
					<select name="cmd_turno" id="cmd_turno" class="combo_box" tabindex="2">
						<option value="">Turno</option>
						<option value="PRIMERA" <?php if($turno=="PRIMERA"){?> selected="selected" <?php }?>>PRIMERA</option>
						<option value="SEGUNDA" <?php if($turno=="SEGUNDA"){?> selected="selected" <?php }?>>SEGUNDA</option>
						<option value="TERCERA" <?php if($turno=="TERCERA"){?> selected="selected" <?php }?>>TERCERA</option>
					</select>			
				</td>
				<td align="right">Fecha Registro</td>
				<td><input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" readonly="readonly" size="10" value="<?php echo $fecha; ?>" /></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8" align="left"><span class="titulo_etiqueta">Equipo</span></td>
			</tr>
			<tr>
				<td width="12%" align="right">*Equipo</td>
				<td width="14%"><?php								
					$conn = conecta("bd_mantenimiento");//Conectarse a la Base de Datos
					$result=mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE familia LIKE '%SCOOP%' AND disponibilidad = 'ACTIVO' ORDER BY id_equipo");
					
					if($registro=mysql_fetch_array($result)){?>				
						<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="4">
							<option value="">Equipo</option><?php															 
							do{
								if($idEquipo==$registro['id_equipo']){?>
									<option value="<?php echo $registro['id_equipo']; ?>" title="<?php echo $registro['id_equipo']; ?>" selected="selected"><?php 
										echo $registro['id_equipo']; ?>
									</option><?php
								}
								else{?>
									<option value="<?php echo $registro['id_equipo']; ?>" title="<?php echo $registro['id_equipo']; ?>">
										<?php echo $registro['id_equipo']; ?>
									</option><?php
								}
							}while($registro=mysql_fetch_array($result))?>
						</select><?php
					} else {?>
						<span class="msje_correcto">No Hay Equipos Registrados</span><?php
					}
					mysql_close($conn);//Cerrar la conexion con la BD ?>
				</td>
				<td width="13%" align="right">*Hor&oacute;metro Inicial</td>
				<td width="12%">
					<input type="text" name="txt_horoIni" id="txt_horoIni" class="caja_de_texto" size="9" maxlength="15" value="<?php echo number_format($horoIni,2,".",","); ?>"
					onblur="calcularHorasTotales('txt_horoIni','txt_horoFin','txt_horasTotales');" onkeypress="return permite(event,'num',2);" 
					onchange="formatCurrency(this.value,'txt_horoIni');" tabindex="5" />
				</td>
				<td width="13%">*Hor&oacute;metro Final</td>
				<td width="12%">
					<input type="text" name="txt_horoFin" id="txt_horoFin" class="caja_de_texto" size="9" maxlength="15" value="<?php echo number_format($horoFin,2,".",","); ?>"
					onblur="calcularHorasTotales('txt_horoIni','txt_horoFin','txt_horasTotales');" onkeypress="return permite(event,'num',2);" 
					onchange="formatCurrency(this.value,'txt_horoFin');" tabindex="6" />
				</td>
				<td width="12%" align="right">Total Horas </td>
				<td width="12%"><input type="text" name="txt_horasTotales" id="txt_horasTotales" class="caja_de_texto" size="9" readonly="readonly" 
				value="<?php echo number_format($totalHoras,2,".",","); ?>" /></td>
			</tr>
			<!--<tr>
				<td colspan="8" align="left"><span class="titulo_etiqueta">Tepetate</span><input type="checkbox" title="Activelo para que el Registro de Tepetate sea Guardado" name="ckb_activarTep" id="ckb_activarTep" onclick="mostrarObligatorio(this,'Tep');"<?php if($orgTepetate!="") echo " checked='checked'";?>/></td>
			</tr>
			<tr>
				<td align="right"><span id="tepC"<?php if($orgTepetate=="") echo "style='visibility:hidden'";?>>*</span>Cargados en</td>
				<td colspan="3"><?php	
					//Obtener las obras del Catálogo de Obras y el campo de Origen de Tepetate
					$obras = obtenerObrasRezagado("tep_origen");
					if(count($obras)>0){?>
						<select name="cmb_origenTepetate" id="cmb_origenTepetate" class="combo_box" 
						onchange="verificarObras(this,cmb_destinoTepetate,'Tepetate'); agregarNvaUbicacion(this);">
							<option value="">Origen</option><?php
							foreach($obras as $ind => $nomObra){
								if($nomObra==$orgTepetate)
									echo "<option value='$nomObra' selected='selected'>$nomObra</option>";
								else
									echo "<option value='$nomObra'>$nomObra</option>";
							}?>
							<option value="NUEVA">Nueva Ubicaci&oacute;n</option>
						</select><?php						
					}
					else{?>
						<span class="msje_correcto">No Hay Obras Registradas</span>
						<input type="hidden" name="cmb_origenTepetate" id="cmb_origenTepetate" value="" /><?php
					}?>	
				</td>
				<td align="right"><span id="tepV"<?php if($orgTepetate=="") echo "style='visibility:hidden'";?>>*</span>Vaciados en</td>
				<td colspan="3"><?php	
					//Obtener las obras del Catálogo de Obras y el campo de Destino de Tepetate
					$obras = obtenerObrasRezagado("tep_destino");
					if(count($obras)>0){?>
						<select name="cmb_destinoTepetate" id="cmb_destinoTepetate" class="combo_box" 
						onchange="verificarObras(cmb_origenTepetate,this,'Tepetate'); agregarNvaUbicacion(this);">
							<option value="">Origen</option><?php
							foreach($obras as $ind => $nomObra){
								if($nomObra==$desTepetate)
									echo "<option value='$nomObra' selected='selected'>$nomObra</option>";
								else
									echo "<option value='$nomObra'>$nomObra</option>";
							}?>
							<option value="NUEVA">Nueva Ubicaci&oacute;n</option>
						</select><?php						
					}
					else{?>
						<span class="msje_correcto">No Hay Obras Registradas</span>
						<input type="hidden" name="cmb_destinoTepetate" id="cmb_destinoTepetate" value="" /><?php
					}?>	
				</td>
			</tr>
			<tr>
				<td align="right"><span id="tepCu"<?php if($orgTepetate=="") echo "style='visibility:hidden'";?>>*</span>Cucharones</td>
				<td>
					<input name="txt_cucharonesTep" type="text" class="caja_de_texto" id="txt_cucharonesTep" tabindex="9" onkeypress="return permite(event,'num',2);"
					value="<?php echo $cuchTepetate; ?>" size="9" maxlength="15" />
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8" align="left"><span class="titulo_etiqueta">Mineral</span><input type="checkbox" title="Activelo para que el Registro de Mineral sea Guardado" name="ckb_activarMin" id="ckb_activarMin" onclick="mostrarObligatorio(this,'Min');"<?php if($orgMineral!="") echo " checked='checked'";?>/></td>
			</tr>
			<tr>
				<td align="right"><span id="minC"<?php if($orgMineral=="") echo "style='visibility:hidden'";?>>*</span>Cargados en</td>
				<td colspan="3"><?php	
					//Obtener las obras del Catálogo de Obras y el campo de Origen de Mineral
					$obras = obtenerObrasRezagado("min_origen");
					if(count($obras)>0){?>
						<select name="cmb_origenMineral" id="cmb_origenMineral" class="combo_box" 
						onchange="verificarObras(this,cmb_destinoMineral,'Mineral'); agregarNvaUbicacion(this);">
							<option value="">Origen</option><?php
							foreach($obras as $ind => $nomObra){
								if($nomObra==$orgMineral)
									echo "<option value='$nomObra' selected='selected'>$nomObra</option>";
								else
									echo "<option value='$nomObra'>$nomObra</option>";
							}?>
							<option value="NUEVA">Nueva Ubicaci&oacute;n</option>
						</select><?php						
					}
					else{?>
						<span class="msje_correcto">No Hay Obras Registradas</span>
						<input type="hidden" name="cmb_origenMineral" id="cmb_origenMineral" value="" /><?php
					}?>
				</td>	      
				<td align="right"><span id="minV"<?php if($orgMineral=="") echo "style='visibility:hidden'";?>>*</span>Vaciados en</td>
				<td colspan="3"><?php	
					//Obtener las obras del Catálogo de Obras y el campo de Destino de Mineral
					$obras = obtenerObrasRezagado("min_destino");
					if(count($obras)>0){?>
						<select name="cmb_destinoMineral" id="cmb_destinoMineral" class="combo_box" 
						onchange="verificarObras(cmb_origenMineral,this,'Mineral'); agregarNvaUbicacion(this);">
							<option value="">Origen</option><?php
							foreach($obras as $ind => $nomObra){
								if($nomObra==$desMineral)
									echo "<option value='$nomObra' selected='selected'>$nomObra</option>";
								else
									echo "<option value='$nomObra'>$nomObra</option>";
							}?>
							<option value="NUEVA">Nueva Ubicaci&oacute;n</option>
						</select><?php						
					}
					else{?>
						<span class="msje_correcto">No Hay Obras Registradas</span>
						<input type="hidden" name="cmb_destinoMineral" id="cmb_destinoMineral" value="" /><?php
					}?>	
				</td>
			</tr>
			<tr>
				<td align="right"><span id="minCu"<?php if($orgMineral=="") echo "style='visibility:hidden'";?>>*</span>Cucharones</td>
				<td>
					<input name="txt_cucharonesMin" type="text" class="caja_de_texto" id="txt_cucharonesMin" tabindex="12" onkeypress="return permite(event,'num',2);" 
					value="<?php echo $cuchMineral; ?>" size="9" maxlength="15" />
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="right">Observaicones</td>
				<td colspan="3">
					<textarea name="txa_observaciones" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="3" cols="30" 
					onkeypress="return permite(event,'num_car',0);" tabindex="13"><?php echo $observaciones; ?></textarea>
				</td>
				<td colspan="4" class="titulo_etiqueta">* Los datos marcados con asterisco (*) son obligatorios.</td>				
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>	      
				<td>&nbsp;</td>
			</tr> -->
			<?php
				$i = 0;
				$con = conecta("bd_desarrollo");
				$stm_sql = "SELECT * 
							FROM  `rezagado` 
							WHERE  `bitacora_avance_id_bitacora` LIKE  '$idBitAvance'";
				$rs = mysql_query($stm_sql);
				if($rs){
					while($datos = mysql_fetch_array($rs)){
			  ?>
			<tr>
				<td colspan="8" align="left"><span class="titulo_etiqueta">Rezagado <?php echo $i+1;?></span><input type="checkbox" title="Activelo para que el Registro de Mineral sea Guardado" name="ckb_activarMin<?php echo $i;?>" id="ckb_activarMin<?php echo $i;?>" onclick="mostrarObligatorio(this,'Min',<?php echo $i;?>);"/></td>
			</tr>
			<tr>
				<td align="right"><span id="minCu<?php echo $i?>" style="visibility:hidden">*</span>Cucharones</td>
				<td>
					<input name="txt_cucharonesMin<?php echo $i?>" type="text" class="caja_de_texto" id="txt_cucharonesMin<?php echo $i?>" tabindex="12" size="9" maxlength="15" 
					onkeypress="return permite(event,'num',2);" onchange="document.getElementById('ckb_activarTLimp'+<?php echo $i;?>).checked=false;" value="<?php echo $datos["cuch"]; ?>"/>
				</td>
				<td>
					<input type="checkbox" title="Activarlo para indicar que son cucharones de traspaleo" name="ckb_activarTrasp<?php echo $i;?>" id="ckb_activarTrasp<?php echo $i;?>"
					onchange="establecerTras_Limp(<?php echo $i;?>,this.id,0,'ckb_activarTLimp');" <?php if($datos["traspaleo"] == 1) echo "checked=checked"; ?>/>
					Traspaleo
				</td>
				<td>
					<input type="checkbox" title="Activarlo para indicar que el tope esta limpio" name="ckb_activarTLimp<?php echo $i;?>" id="ckb_activarTLimp<?php echo $i;?>"
					onchange="establecerTras_Limp(<?php echo $i;?>,this.id,1,'ckb_activarTrasp');" <?php if($datos["tope_limpio"] == 1) echo "checked=checked"; ?>/>
					T. Limpio
				</td>
			</tr>
			<tr>
				<td align="right">Observaciones</td>
				<td colspan="3">
					<textarea name="txa_observaciones<?php echo $i?>" onkeyup="return ismaxlength(this)" maxlength="120" class="caja_de_texto" rows="3" cols="30" 
					onkeypress="return permite(event,'num_car',0);" tabindex="13"><?php echo $datos["observaciones"]; ?></textarea>				
				</td>
				<td colspan="4" class="titulo_etiqueta">* Los datos marcados con asterisco (*) son obligatorios.</td>				
			</tr>
			<?php
						$i++;
					}
				}
			  ?>
			<tr>
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
				<td colspan="8" align="center"><?php
					/*Estas variables ayudan a identificar cual de las Bitácoras (Avance y Retro-Bull) será registrada en las Bitacoras de Fallas y Consumos
					asi como el tipo de registro (Bitacora de Barrenación(Jumbo y MP), Voladura y Rezagado*/ ?>
					<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $idBitAvance; ?>" />
					<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="<?php echo $tipoBitacora; ?>" />
					<input type="hidden" name="hdn_tipoRegistro" id="hdn_tipoRegistro" value="REZAGADO" />
					
					<?php //Esta variable ayudara a determinar el tipo de Falla (Operativa, Mecánica y Eléctrica) que sera registrada en la Bitacora de Fallas?>
					<input type="hidden" name="hdn_tipoEquipo" id="hdn_tipoEquipo" value="SCOOP" />
					
					<?php //Esta variable indica si fueron modificados los cosumos del equipo, en el caso que no se notifica al usuario sobre este hecho?>
					<input type="hidden" name="hdn_regBitConsumos" id="hdn_regBitConsumos" value="no" />	
					
					<?php //Esta variable indica si la Bitácora de Rezagado debe ser Actualizada(valor='si') o Registrada(valor='no') por primera vez ?>
					<input type="hidden" name="hdn_actualizarBitacora" id="hdn_actualizarBitacora" value="<?php echo $_POST['hdn_bitRezagado']?>" />
															
					
					<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Datos en la Bit&aacute;cora" 
					onmouseover="window.status='';return true" tabindex="14" />
					&nbsp;&nbsp;
					<input name="btn_regFallas" id="btn_regFallas" type="button" class="botones_largos" value="Modificar Fallas" 
					title="Modificar Fallas de los Equipos" onmouseover="window.status='';return true" onclick="abrirVentana('fallas','modificar');" tabindex="15" />
					&nbsp;&nbsp;
					<input name="btn_regConsumos" id="btn_regConsumos" type="button" class="botones_largos" value="Modificar Consumos" 
					title="Modificar Consumos Realizados" onmouseover="window.status='';return true" onclick="abrirVentana('consumos','modificar');" tabindex="16" />
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Restablecer" class="botones" title="Restablecer los Campos del Formulario" tabindex="17" />
					&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina Anterior" 
					onclick="confirmarRegreso('frm_modAvance2.php');" tabindex="18" />
				</td>	      
			</tr>
		</table>		
		</form>
		</fieldset>
		
		<div id="calendario">
			<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_regBitRezagado.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Registro" tabindex="3" /> 
		</div><?php
	} 
	else{
		//Guardar los cambios hechos al Registro de la Bitacora de Rezagado
		modificarBitRezagado();		
	}?>        
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
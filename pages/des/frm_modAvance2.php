<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include("head_menu.php");
		
		include("op_modAvance.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link> 
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="includes/ajax/fallasConsumosTNT.js" ></script>
	<script type="text/javascript" language="javascript">
		//Al Cargar la Pagina colocar el foco en el CoboBox que contiene las ubicaciones
		setTimeout("document.frm_modBitAvance.cmb_lugar.focus();",500);
	</script>
    <style type="text/css">
		<!--
		#titulo-regRezagado {position:absolute; left:30px; top:146px; width:350px; height:20px; z-index:11; }		
		#tabla-registroRezagado {position:absolute; left:30px; top:190px; width:940px; height:200px; z-index:12; }
		#pregunta { position:absolute; left:30px; top:190px; width:940px; height:200px; z-index:14; }
		#calendario { position:absolute; left:635px; top:215px; width:30px; height:27px; z-index:15; }
		-->
    </style>
</head>
<body><?php

	//Para mostrar el formulario de captura de datos, verificar que ninguno de los botones de esta ventana haya sido seleccionado
	if(!isset($_POST['sbt_actualizar'])){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		
		//Obtener el ID de la Bitácora de Avance que esta siendo Modificada
		$idBitAvance = "";
		//Verificar si los datos de la Bitácora de Avance estan guardados en la SESSION
		if(!isset($_SESSION['bitacoraAvance'])){
			//Obtener el Id de la Bitacora de Avance para guardarlo en la SESSION
			$idBitAvance = $_POST['rdb_idBitAvance'];
			
			//Subir a la SESSION el Id de la Bitacora de Avance seleccionado y el tipo de Bitacora 'bitAvance'
			$_SESSION['bitacoraAvance'] = array("idBitacora"=>$idBitAvance, "tipoBitacora"=>"bitAvance");
		}
		else {
			//Obtener el ID de la Bitácora de Avance de la SESSION cuando se llegue a esta página desde los formularios de modificación de las bitácoras de 
			//BarrJU, BarrMP, VOL y REZ
			$idBitAvance = $_SESSION['bitacoraAvance']['idBitacora'];
		}
		
		
		
		//Obtener los datos del registro seleccionado
		$datosBitAvance = mysql_fetch_array(mysql_query("SELECT * FROM bitacora_avance WHERE id_bitacora = '$idBitAvance'"));
	
		/* NOTA: Los siguientes arreglos tienen los mismos indices con el objetivo de manipular diferente información de las cuatro bitacoras que incluye la de Avance
		 * 1. $_SESSION['bitsActualizadas']
		 * 2. $msgsBitacoras => Manipular los mensajes de los Botones del Formulario
		 * 3. $varHiddenBitacoras => Manipular el valor de las variables Hidden que indicaran cual bitácora falta por ser registrada */
		
		
		//Definir en la SESSION el arreglo que indicará cuales bitácoras faltan por complementar y cuales ya han sido registradas en la BD
		if(!isset($_SESSION['bitsActualizadas'])){
			$_SESSION['bitsActualizadas'] = array("barrenacion_jumbo"=>0,"barrenacion_maq_pierna"=>0,"voladuras"=>0,"rezagado"=>0);
		}																
		
		
		//Guardar las variables hidden que indicarán cuales bitacoras serán registradas y cuales serán modificadas
		$varHiddenBitacoras = array();
		$varHiddenBitacoras['barrenacion_jumbo'] = "si";
		$varHiddenBitacoras['barrenacion_maq_pierna'] = "si";
		$varHiddenBitacoras['voladuras'] = "si";
		$varHiddenBitacoras['rezagado'] = "si";
		
		//De inicio se considera que todas las bitácoras han sido registradas
		$msgsBitacoras = array();
		$msgsBitacoras['barrenacion_jumbo'] = "Modificar Bit&aacute;cora de Barrenaci&oacute;n con Jumbo"; 
		$msgsBitacoras['barrenacion_maq_pierna'] = "Modificar Bit&aacute;cora de Barrenaci&oacute;n con Maquina de Pierna"; 
		$msgsBitacoras['voladuras'] = "Modificar Bit&aacute;cora de Voladura"; 
		$msgsBitacoras['rezagado'] = "Modificar Bit&aacute;cora de Rezagado";
		
		//Verificar cual de las bitacoras no ha sido registrada		
		$nomTablas = array("barrenacion_jumbo","barrenacion_maq_pierna","voladuras","rezagado");
		foreach($nomTablas as $ind => $bitActual){
			//Verificar si la bitacora actual tiene registro en la Base de Datos
			$estadoRegBitActual = verificarRegBitacora($datosBitAvance['id_bitacora'], $bitActual);
			if($estadoRegBitActual=="NO"){
				//Verificar si la bitacora no ha sido registrada en la SESSION
				if($_SESSION['bitsActualizadas'][$bitActual]==0){
					//Modificar el mensaje que aparecerá en el Boton de cada bitacora no registrada
					$msgsBitacoras[$bitActual] = str_replace("Modificar","Registrar",$msgsBitacoras[$bitActual]);
					//Modificar el valor del elemento Hidden que indicara cual de las bitácoras falta por registrar.
					$varHiddenBitacoras[$bitActual] = "no";
				}	
			}
		}//Cierre for($i=0;$i<4;$i++)?>
		
	
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>		   
		<div id="titulo-regRezagado" class="titulo_barra">Complementar Registro de la Bit&aacute;cora de Avance</div>
						
		<fieldset class="borde_seccion" id="tabla-registroRezagado" name="tabla-registroRezagado">
		<legend class="titulo_etiqueta">Ingresar la informaci&oacute;n del Registro de Avance</legend><?php 
		
		/* El valor del atributo action del formulario estara dado por la funcion direccionarPagina definica en la sección de Bitácora de Avance en el archivo
		 * validacionDesarrollo.js */ ?>
		 
		<form onsubmit="return valFormModBitAvance(this)" name="frm_modBitAvance" method="post" action="">
		<table class="tabla_frm" width="100%" cellpadding="5" cellspacing="5">			
			<tr>
				<td align="right">*Lugar</td>
				<td colspan="2"><?php	
					$resultado = cargarComboTotal("cmb_lugar","obra","id_ubicacion","catalogo_ubicaciones","bd_desarrollo","Origen",
												"$datosBitAvance[catalogo_ubicaciones_id_ubicacion]","","id_ubicacion","","");
					if($resultado==0){?>
						<span class="msje_correcto">No Hay Lugares Registradas</span>
						<input type="hidden" name="cmb_lugar" id="cmb_lugar" value="" /><?php
					}?>				
				</td>
				<td>
					*Fecha Registro&nbsp;					
				    <input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" readonly="readonly" size="10" 
					value="<?php echo modFecha($datosBitAvance['fecha_registro'],1); ?>" />
				</td>
				<td width="10%" align="right">*Avance</td>
				<td width="20%">
					<input type="text" name="txt_avance" id="txt_avance" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);"
					onchange="formatCurrency(this.value,'txt_avance');" value="<?php echo number_format($datosBitAvance['avance'],2,".",","); ?>" />&nbsp;ml				
				</td>
				<input type="hidden" name="txt_machote" id="txt_machote" value="<?php echo number_format($datosBitAvance['machote'],2,".",","); ?>" />
				<input type="hidden" name="txt_medida" id="txt_medida" value="<?php echo number_format($datosBitAvance['medida'],2,".",","); ?>" />
			</tr>
			<!--<tr>
				<td width="10%" align="right">*Machote</td>
				<td width="30%">
					<input type="text" name="txt_machote" id="txt_machote" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					onchange="formatCurrency(this.value,'txt_machote');" onblur="calcularAvance();" 
					value="<?php echo number_format($datosBitAvance['machote'],2,".",","); ?>" />
				</td>
				<td width="10%" align="right">*Medida</td>
				<td width="20%">
					<input type="text" name="txt_medida" id="txt_medida" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);" 
					onchange="formatCurrency(this.value,'txt_medida');" onblur="calcularAvance();" 
					value="<?php echo number_format($datosBitAvance['medida'],2,".",","); ?>" />				
				</td>
				<td width="10%" align="right">*Avance</td>
				<td width="20%">
					<input type="text" name="txt_avance" id="txt_avance" class="caja_de_texto" size="9" maxlength="15" onkeypress="return permite(event,'num',2);"
					onchange="formatCurrency(this.value,'txt_avance');" value="<?php echo number_format($datosBitAvance['avance'],2,".",","); ?>" />&nbsp;ml				
				</td>				
			</tr>	-->		
			<tr>
				<td align="right">Observaciones</td>
				<td colspan="5">
					<textarea name="txa_observaciones" id="txa_observaciones" onkeyup="return ismaxlength(this)" maxlength="120" class="caja_de_texto" rows="3" cols="45"
           	 		onkeypress="return permite(event,'num_car', 0);" ><?php echo $datosBitAvance['observaciones']; ?></textarea>				
				</td>			
			</tr>
			<tr><td colspan="6">&nbsp;</td></tr>
			<tr>
				<td colspan="6" align="center">		
					<?php //La variable 'hdn_btnClick' ayudara a realizar la validación correspondiente dependiendo del boton seleccionado ?>
					<input type="hidden" name="hdn_btnClick" id="hdn_btnClick" value="" />
					<?php //Estas variables indican cuales bitácoras serán registradas y cuales serán modificadas en los formularios de las Bitácoras de BarrJU, BarrMP, Vol y Rez?>
					<input type="hidden" name="hdn_bitBarrJU" id="hdn_bitBarrJU" value="<?php echo $varHiddenBitacoras['barrenacion_jumbo']; ?>" />
					<input type="hidden" name="hdn_bitBarrMP" id="hdn_bitBarrMP" value="<?php echo $varHiddenBitacoras['barrenacion_maq_pierna']; ?>" />
					<input type="hidden" name="hdn_bitVoladuras" id="hdn_bitVoladuras" value="<?php echo $varHiddenBitacoras['voladuras']; ?>" />
					<input type="hidden" name="hdn_bitRezagado" id="hdn_bitRezagado" value="<?php echo $varHiddenBitacoras['rezagado']; ?>" />
					
					
					<?php //Botones de manipulación de Datos ?>
					<input type="submit" name="sbt_actualizar" class="botones" value="Actualizar" title="Actualizar Registro en la Bit&aacute;cora de Avance" 
					onmouseover="window.status='';return true" onclick="hdn_btnClick.value='actualizar'; direccionarPagina(this.name);" />
					&nbsp;
					<input type="submit" name="sbt_modBarrJumbo" class="botones" value="Barrenaci&oacute;n JU" title="<?php echo $msgsBitacoras['barrenacion_jumbo']; ?>" 
					onmouseover="window.status='';return true" onclick="hdn_btnClick.value='barrenacion'; direccionarPagina(this.name);" />
					&nbsp;
					<input type="submit" name="sbt_modBarrMP" class="botones" value="Barrenaci&oacute;n MP" 
					title="<?php echo $msgsBitacoras['barrenacion_maq_pierna']; ?>"  onmouseover="window.status='';return true" 
					onclick="hdn_btnClick.value='barrenacion'; direccionarPagina(this.name);" />
					&nbsp;
					<input type="submit" name="sbt_modVoladura" class="botones" value="Voladura" title="<?php echo $msgsBitacoras['voladuras']; ?>" 
					onmouseover="window.status='';return true" onclick="hdn_btnClick.value='voladura'; direccionarPagina(this.name);" />
					&nbsp;
					<input type="submit" name="sbt_modRezagado" class="botones" value="Rezagado" title="<?php echo $msgsBitacoras['rezagado']; ?>" 
					onmouseover="window.status='';return true" onclick="hdn_btnClick.value='rezagado'; direccionarPagina(this.name);" />
					&nbsp;
					<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar a la Selecci&oacute;n de Registro de Bit&nbsp;cora de Avance" 
					onclick="confirmarRegreso('frm_modAvance.php');" />
				</td>
			</tr>			
		</table>		
		</form>
		</fieldset>
		
		
		<div id="calendario">
			<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modBitAvance.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Registro" tabindex="3" /> 
		</div><?php
	}//Cierre if(!isset($_POST['sbt_actualizar'])) 
	else{
		//Guardar el Registro en la Bitacora de Avance		
		actualizarBitAvance();		
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
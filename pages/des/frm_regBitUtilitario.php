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
		
		include ("op_gestionarBitUtilitario.php");
		?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link> 
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>	
	<script type="text/javascript" src="includes/ajax/fallasConsumosTNT.js"></script>
	<script type="text/javascript" language="javascript">
		
		//Esta variable guardará la referencia de la página de Modificar Registro Fallas/Consumos para detectar cuando ésta sea crerrada.
		var vtnAbierta = "";
		
		function iniciarCamposBitUtilitario(){
			//Colocar el foco en el comboBox donde se selecciona al operador del equipo
			document.frm_registroBitUtilitario.cmb_operador.focus();						
			
			//Colocar el orden de selección a los ComboBox que son cargados con la funcion PHP de cargarCombo
			document.frm_registroBitUtilitario.cmb_lugarAmacizado.tabIndex = "7";
			document.frm_registroBitUtilitario.cmb_limpiaAcequia.tabIndex = "8";
			document.frm_registroBitUtilitario.cmb_lugarBalastreo.tabIndex = "10";
		}
		//Ejecutar la función JavaScript medio segundo despues de dibujar el formulario
		setTimeout("iniciarCamposBitUtilitario();",500);
		
	</script>
	
    <style type="text/css">
		<!--
		#titulo-EqUtilitario { position:absolute; left:30px; top:146px; width:486px; height:20px; z-index:11; }
		#form-regBitUtilitario { position:absolute; left:30px; top:190px; width:940px; height:400px; z-index:12; }
		#calendario {position:absolute; left:607px; top:257px; width:30px; height:27px; z-index:13; }
		-->
    </style>
</head>
<body onfocus="verificarCierreVtn();">

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>			
    <div id="titulo-EqUtilitario" class="titulo_barra">Agregar Registro a la Bit&aacute;cora de Equipo Utilitario </div><?php

	//Para mostrar el formulario de captura de datos, verificar que ninguno de los botones de esta ventana haya sido seleccionado
	if(!isset($_POST['sbt_guardar'])){
	
		//Obtener el Id de la Bitacora de Retro-Bull para poder registrar la Bitacora de Fallas y los Consumos
		$idBitUtilitario = obtenerIdBitUtilitario();?>
							
    	<fieldset class="borde_seccion" id="form-regBitUtilitario" name="form-regBitUtilitario">
		<legend class="titulo_etiqueta">Ingresar la Informaci&oacute;n del Registro de Rezagado</legend>
		<br />
		<form onsubmit="return valFormRegistroBitUtilitario(this);" name="frm_registroBitUtilitario" method="post" action="frm_regBitUtilitario.php">
		<table width="100%" cellpadding="2" cellspacing="5">
			<tr>
              	<td align="right">*Operador</td>
              	<td colspan="5"><?php							
					$conn = conecta("bd_recursos");//Conectarse a la Base de Datos
					$result = mysql_query("SELECT nombre,ape_pat,ape_mat,puesto FROM empleados WHERE area = 'DESARROLLO' ORDER BY nombre");
					
					if($registro=mysql_fetch_array($result)){?>				
						<select name="cmb_operador" id="cmb_operador" class="combo_box" onchange="hdn_puesto.value=this.options[this.selectedIndex].title" tabindex="1">
							<option value="">Operador</option><?php															 
							do{
								$nombre = $registro['nombre']." ".$registro['ape_pat']." ".$registro['ape_mat'];
								$puesto = $registro['puesto'];?>
								<option value="<?php echo $nombre;?>" title="<?php echo $puesto; ?>"><?php echo $nombre; ?></option><?php
							}while($registro=mysql_fetch_array($result))?>
						</select>
						<input type="hidden" name="hdn_puesto" id="hdn_puesto" value="" /><?php
					} else {?>
			  			<span class="msje_correcto">No Hay Operadores Registrados</span><?php
					}
					mysql_close($conn);//Cerrar la conexion con la BD ?>				
				</td>
			</tr>
			<tr>
           	  	<td width="15%" align="right">*Turno</td>
           	  	<td width="20%">
                  	<select name="cmb_turno" class="combo_box" tabindex="1">
				  		<option value="">Turno</option>
                    	<option value="PRIMERA">PRIMERA</option>
                    	<option value="SEGUNDA">SEGUNDA</option>
                    	<option value="TERCERA">TERCERA</option>
   	  		  </select>			  </td>
              	<td width="15%" align="right">*Fecha Registro</td>
              	<td width="20%">
					<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" readonly="readonly" size="10" value="<?php echo date("d/m/Y"); ?>" />			  </td>
				<td width="15%">&nbsp;</td>
				<td width="15%">&nbsp;</td>
			</tr>
            <tr>
              	<td colspan="6" align="left" class="titulo_etiqueta">Equipo</td>              	
            </tr>
            <tr>
				<td align="right">*Equipo</td>
				<td colspan="5"><?php								
					$conn = conecta("bd_mantenimiento");//Conectarse a la Base de Datos
					$result = mysql_query("SELECT id_equipo, nom_equipo FROM equipos WHERE (familia = 'BULLDOZER' OR  familia = 'RETROS') AND disponibilidad = 'ACTIVO' 
					ORDER BY id_equipo");
					
					if($registro=mysql_fetch_array($result)){?>				
						<select name="cmb_equipo" id="cmb_equipo" class="combo_box" tabindex="4">
							<option value="">Equipo</option><?php															 
							do{?>
								<option value="<?php echo $registro['id_equipo']; ?>" title="<?php echo $registro['id_equipo']; ?>"><?php 
									echo $registro['id_equipo']; ?>
								</option><?php
							}while($registro=mysql_fetch_array($result))?>
						</select><?php
					} else {?>
						<span class="msje_correcto">No Hay Equipos Registrados</span><?php
					}
					mysql_close($conn);//Cerrar la conexion con la BD ?>				
				</td>
			</tr>
			<tr>
				<td align="right">*Hor&oacute;metro Inicial</td>
				<td>
		    		<input type="text" name="txt_horoIni" id="txt_horoIni" class="caja_de_texto" size="7" maxlength="15" onkeypress="return permite(event,'num',2);"
					onblur="calcularHorasTotales('txt_horoIni','txt_horoFin','txt_horasTotales');" tabindex="5" onchange="formatCurrency(this.value,'txt_horoIni')" />
				</td>
				
				<td align="right">*Hor&oacute;metro Final</td>
				<td>
				  	<input type="text" name="txt_horoFin" id="txt_horoFin" class="caja_de_texto" size="7" maxlength="15" onkeypress="return permite(event,'num',2);"
					onblur="calcularHorasTotales('txt_horoIni','txt_horoFin','txt_horasTotales');" tabindex="6" onchange="formatCurrency(this.value,'txt_horoFin')" />
				</td>
				<td align="right">Total de Horas</td>
				<td><input type="text" name="txt_horasTotales" id="txt_horasTotales" class="caja_de_texto" size="7" readonly="readonly" /></td>
            </tr>
            <tr>
              	<td colspan="6" class="titulo_etiqueta">Tepetate</td>
            </tr>
            <tr>
              	<td align="right">Lugar Amacizado</td>
           	  	<td colspan="2"><?php	
					//Obtener las obras del Catálogo de Obras y el campo de Lugar Amacizado
					$obras = obtenerObrasBitUtilitario("lugar_amacizado");
					if(count($obras)>0){?>
						<select name="cmb_lugarAmacizado" id="cmb_lugarAmacizado" class="combo_box" onchange="agregarNvaUbicacion(this);">
							<option value="">Origen</option><?php
							foreach($obras as $ind => $nomObra){
								echo "<option value='$nomObra'>$nomObra</option>";
							}?>
							<option value="NUEVA">Nueva Ubicaci&oacute;n</option>
						</select><?php						
					}
					else{?>
						<span class="msje_correcto">No Hay Obras Registradas</span>
						<input type="hidden" name="cmb_lugarAmacizado" id="cmb_lugarAmacizado" value="" /><?php
					}?>									
				</td>
				<td align="right">Limpia de Acequia</td>
			  	<td colspan="2"><?php	
					//Obtener las obras del Catálogo de Obras y el campo de Limpia Acequia
					$obras = obtenerObrasBitUtilitario("limpia_acequia");
					if(count($obras)>0){?>
						<select name="cmb_limpiaAcequia" id="cmb_limpiaAcequia" class="combo_box" onchange="agregarNvaUbicacion(this);">
							<option value="">Origen</option><?php
							foreach($obras as $ind => $nomObra){
								echo "<option value='$nomObra'>$nomObra</option>";
							}?>
							<option value="NUEVA">Nueva Ubicaci&oacute;n</option>
						</select><?php						
					}
					else{?>
						<span class="msje_correcto">No Hay Obras Registradas</span>
						<input type="hidden" name="cmb_limpiaAcequia" id="cmb_limpiaAcequia" value="" /><?php
					}?>			  			  			 																
				</td>
            </tr>
			<tr>
				<td align="right">Lugar Balastreado</td>
				<td colspan="2"><?php	
					//Obtener las obras del Catálogo de Obras y el campo de Lugar Balastreado
					$obras = obtenerObrasBitUtilitario("lugar_balastreado");
					if(count($obras)>0){?>
						<select name="cmb_lugarBalastreo" id="cmb_lugarBalastreo" class="combo_box" onchange="agregarNvaUbicacion(this);">
							<option value="">Origen</option><?php
							foreach($obras as $ind => $nomObra){
								echo "<option value='$nomObra'>$nomObra</option>";
							}?>
							<option value="NUEVA">Nueva Ubicaci&oacute;n</option>
						</select><?php						
					}
					else{?>
						<span class="msje_correcto">No Hay Obras Registradas</span>
						<input type="hidden" name="cmb_lugarBalastreo" id="cmb_lugarBalastreo" value="" /><?php
					}?>												
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
            <tr>
              	<td align="right">Observaciones</td>
              	<td colspan="2">
				  	<textarea name="txa_observaciones" onkeyup="return ismaxlength(this)" maxlength="120" class="caja_de_texto" rows="3" cols="40" 
					onkeypress="return permite(event,'num_car',0);" tabindex="10"></textarea>				
				</td>
              	<td>&nbsp;</td>
              	<td>&nbsp;</td>
              	<td>&nbsp;</td>
            </tr>
            <tr>
              	<td>&nbsp;</td>
              	<td colspan="3" class="titulo_etiqueta">*Los datos marcados con asterisco  (*) son obligatorios</td>
              	<td>&nbsp;</td>
              	<td>&nbsp;</td>
            </tr>
            <tr>
              	<td colspan="6" align="center"><?php 
					/*Estas variables ayudan a identificar cual de las Bitácoras (Avance y Retro-Bull) será registrada en las Bitacoras de Fallas y Consumos
					asi como el tipo de registro (Bitacora de Barrenación(Jumbo y MP), Voladura y Rezagado*/ ?>
					<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $idBitUtilitario; ?>" />
					<input type="hidden" name="hdn_tipoBitacora" id="hdn_tipoBitacora" value="bitRetroBull" />
					<input type="hidden" name="hdn_tipoRegistro" id="hdn_tipoRegistro" value="RETRO-BULL" />
					
					<?php //Esta variable ayudara a determinar el tipo de Falla (Operativa, Mecánica y Eléctrica) que sera registrada en la Bitacora de Fallas?>
					<input type="hidden" name="hdn_tipoEquipo" id="hdn_tipoEquipo" value="RETRO-BULL" />
					
					<?php //Esta variable indica si fueron agregados los cosumos del equipo, en el caso que no se notifica al usuario sobre este hecho?>
					<input type="hidden" name="hdn_regBitConsumos" id="hdn_regBitConsumos" value="no" />
					
					<?php //Esta variable indica sobre cual equipo se registraron fallas y ayuda a que el usuario no cambie el equipo seleccionado antes de guardar?>
					<input type="hidden" name="hdn_fallasEquipo" id="hdn_fallasEquipo" value="" />
					
					
					<input type="submit" name="sbt_guardar" value="Guardar" class="botones" title="Guardar Datos en la Bit&aacute;cora" 
					onmouseover="window.status='';return true" tabindex="11" />
					&nbsp;&nbsp;
					<input name="btn_regFallas" id="btn_regFallas" type="button" class="botones_largos" value="Registrar Fallas" 
					title="Registrar Fallas de los Equipos" onmouseover="window.status='';return true" onclick="abrirVentana('fallas','agregar');" tabindex="12" />
					&nbsp;&nbsp;
					<input name="btn_regConsumos" id="btn_regConsumos" type="button" class="botones_largos" value="Registrar Consumos" 
					title="Registrar Consumos Realizados" onmouseover="window.status='';return true" onclick="abrirVentana('consumos','agregar');" tabindex="13" />
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Limpiar los Campos del Formulario" tabindex="14" />
					&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Cancelar el Registro de la Bit&aacute;cora de Retro y Bulldozer" 
					onclick="cancelarOperacion(hdn_idBitacora.value,hdn_tipoBitacora.value,hdn_tipoRegistro.value,'menu_bitUtilitario.php');" tabindex="15" />				
				</td>
            </tr>
		</table>			
		</form>		
		</fieldset>
    	
		<div id="calendario">
    	  	<input type="image" name="img_calendario" id="img_calendario" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_registroBitUtilitario.txt_fechaRegistro,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Registro" tabindex="3" />
		</div><?php
	}
	else{
		//Guardar los datos de la Bitácora de Retro-Bull
		guardarBitUtilitario();	
	}?>
					
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
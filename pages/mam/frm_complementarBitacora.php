<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		
		include ("op_registrarBitacora.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar{position:absolute;left:30px;top:146px;width:212px;height:20px;z-index:11;}
		#tabla-registrarBitacora{position:absolute;left:30px;top:190px;width:904px;height:50px;z-index:12;padding:15px;padding-top:0px;}
		#tabla-registrarActividades{position:absolute;left:30px;top:265px;width:904px;height:148px;z-index:13;padding:15px;	padding-top:0px;}
		#tabla-mostrarActividades{position:absolute;left:30px;top:440px;width:904px;height:185px;z-index:14;overflow:scroll;}
		#btns-regpdf{position:absolute;	left:30px;top:660px;width:900px;height:35px;z-index:12;padding:16px	padding-top:0px;}						
		-->
    </style>
</head>
<body><?php

	//Cuando se entra a esta página desde el registro de Bitácora de Mtto Preventivo o Correctivo, obtenemos el No. de Actividad de la SESSION o 
	//colocamos 1 en el caso de que no exista
	$cont = 0;
	if(!isset($_POST["sbt_agregarAct"])){
		if(!isset($_SESSION["actividades"]))
			$cont = 1;
		else//De lo contrario si el arreglo viene definido, es contado y se agrega uno mas, para formar el sig. numero de partida
			$cont = count($_SESSION["actividades"])+1;
	}
	
	//Verificamos que el boton este definido; cada vez que se presione aumentara la partida
	if(isset($_POST["sbt_agregarAct"])){
		$cont = $_POST["txt_partida"]+1;
	}?>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Complementar Bit&aacute;cora</div>
	
	
	<form onSubmit="return valFormComplementarBitacora(this);" name="frm_complementarBitacora" method="post">
	<fieldset class="borde_seccion" id="tabla-registrarBitacora" name="tabla-registrarBitacora">
	<legend class="titulo_etiqueta">Registrar Bit&aacute;cora</legend>	
	<table width="879" height="42" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr><?php //Decidimos que tipo de mantenimiento para mostrar los datos clave bitacora, clave equipo y orden de trabajo 
			if(($_POST["txt_tipoMant"])=="CORRECTIVO"){?>
				<td width="107"><div align="right">Clave Bit&aacute;cora </div></td>
            	<td width="88">
					<input name="txt_claveBitacora" id="txt_claveBitacora" type="text" class="caja_de_texto" size="15" maxlength="13" 
					value="<?php echo $_POST["txt_claveBitacora"]; ?>" readonly="readonly" />
				</td>
            	<td width="158"><div align="right">Clave del Equipo </div></td>
            	<td width="109">
					<input name="cmb_claveEquipo" id="cmb_claveEquipo" type="text" class="caja_de_texto" size="15" maxlength="13" 
					value="<?php echo $_POST['cmb_claveEquipo'];?>" readonly="readonly"/>
				</td>
            	<td width="166"><div align="right">Clave Orden de Trabajo </div></td>
				<td width="109">
					<input name="txt_claveOrdenTrabajo" id="txt_claveOrdenTrabajo" type="text" class="caja_de_texto" size="15" maxlength="13" 
					value="<?php echo $_POST['txt_claveOrdenTrabajo'];?>" readonly="readonly"/>
				</td><?php 
			}
			else{//De lo contrario selecciona del mantenimiento preventivo ?>
				<td width="107"><div align="right">Clave Bit&aacute;cora </div></td>
            	<td width="88">
					<input name="txt_claveBitacora" id="txt_claveBitacora" type="text" class="caja_de_texto" size="15" maxlength="13" 
					value="<?php echo $_POST["txt_claveBitacora"]; ?>" readonly="readonly" />
				</td>
            	<td width="158"><div align="right">Clave del Equipo </div></td>
            	<td width="109">
					<input name="txt_claveEquipo" id="txt_claveEquipo" type="text" class="caja_de_texto" size="15" maxlength="13" 
					value="<?php echo $_POST['txt_claveEquipo'];?>" readonly="readonly" />
				</td>
            	<td width="166"><div align="right">Clave Orden de Trabajo </div></td>
          		<td width="109">
					<input name="txt_ot" id="txt_ot" type="text" class="caja_de_texto" size="15" maxlength="13"  value="<?php echo $_POST['txt_ot'];?>" 
					readonly="readonly" />
				</td><?php 
			}?>
		</tr>
	</table>
  	</fieldset>


    <fieldset class="borde_seccion" id="tabla-registrarActividades" name="tabla-registrarActividades">	
    <legend class="titulo_etiqueta">Registrar Acciones o Actividades Correctivas</legend>
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="46" height="50" valign="top"><div align="right">Partida</div></td>
			<td width="10" valign="top">
				<input name="txt_partida" id="txt_partida" type="text" class="caja_de_num" size="2" value="<?php echo $cont;?>" maxlength="50" 
				onkeypress="return permite(event,'num_car', 0);" readonly="readonly"/>			
			</td>
			<td width="53" valign="top"><div align="right">*Sistema</div></td>
			<td width="196" valign="top">
				<input name="txt_sistema" id="txt_sistema" type="text" class="caja_de_texto" size="30" maxlength="50" onkeypress="return permite(event,'num_car', 0);"/>
			</td>
			<td width="84" valign="top" rowspan="2"><div align="right">*Aplicaci&oacute;n</div></td>
			<td width="147" valign="top" rowspan="2">			      	    
				<input name="txt_aplicacion" id="txt_aplicacion" type="text" class="caja_de_texto" size="17" maxlength="30" onkeypress="return permite(event,'num_car', 0);"/>
			</td>	      	 
			<td width="57" valign="top" rowspan="2">*Actividad</td>
      	    <td width="184" valign="top" rowspan="2">
				<textarea name="txa_actividad" cols="35" rows="4" class="caja_de_texto"  maxlength="120" id="txa_actividad"  onkeyup="return ismaxlength(this)" 
				onkeypress="return permite(event,'num_car', 0);"></textarea>			
			</td>
  	  	</tr>
	  	<tr>
	  	  	<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
  	  	</tr>
      	<tr>
      		<td colspan="8" align="center">
            	<input type="hidden" name="txt_tipoMant" value="<?php echo $_POST["txt_tipoMant"];?>"/>  
				
           	  	<input name="sbt_agregarAct" type="submit" class="botones"  value="Agregar" title="Complementar Bit&aacute;cora"
            	onmouseover="window.status='';return true" />    
				&nbsp;&nbsp;      
       	 		<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/><?php 
				if(($_POST["txt_tipoMant"])=="CORRECTIVO"){?>
                	&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar Registro de Bit&aacute;cora" 
                	onmouseover="window.status='';return true" onclick="location.href='frm_bitacoraMttoCorrectivo.php?cancelar=si'" /><?php 
				}
				else{?>
                	&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar Registro de Bit&aacute;cora" 
                	onmouseover="window.status='';return true" onclick="location.href='frm_bitacoraMttoPreventivo.php?cancelar=si'" /> <?php 
				}?>			
			</td>
		</tr>
	</table>
  	</fieldset><?php
	
	
	//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
	if (isset($_POST["txa_actividad"])&&isset($_POST["txt_aplicacion"])&&isset($_POST["txt_sistema"])){
		//Si ya esta definido el arreglo $actividades, entonces agregar el siguiente registro a el
		if(isset($_SESSION['actividades'])){			
			//Guardar los datos en el arreglo
			$actividades[] = array("partida"=>($txt_partida), "actividad"=>strtoupper($txa_actividad), "aplicacion"=>strtoupper($txt_aplicacion), 
			"sistema"=>strtoupper($txt_sistema));
		}
		//Si no esta definido el arreglo $actividades definirlo y agregar el primer registro
		else{			
			//Guardar los datos en el arreglo
			$actividades = array(array("partida"=>($txt_partida),"actividad"=>strtoupper($txa_actividad), "aplicacion"=>strtoupper($txt_aplicacion), 
			"sistema"=>strtoupper($txt_sistema)));
			$_SESSION['actividades'] = $actividades;	
		}	
	}
	
	//Verificar que este definido el Arreglo de actividades, si es asi, lo mostramos en el formulario
	if (isset($_SESSION["actividades"])){
		echo "<div id='tabla-mostrarActividades' class='borde_seccion2'>";
		mostrarActividades($actividades);
		echo "</div>";
	}?>
	</form>
    
	
	<div id="btns-regpdf">
		<table width="396" height="69" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
   				<td align="center">
					<input type="hidden" name="hdn_bandera" id="hdn_bandera" value="si"/><?php 
					
					if(($_POST["txt_tipoMant"])=="CORRECTIVO"){?>
						<input name="btn_regMatMant" type="button" class="botones"  value="Finalizar" title="Registrar Materiales de Mantenimiento" 
						onclick="location.href='frm_bitacoraMttoCorrectivo.php'" onmouseover="window.status='';return true" /><?php
					}
					else{?>
						<input name="btn_regMatMant" type="button" class="botones"  value="Finalizar"
						title="Registrar Materiales de Mantenimiento" onclick="location.href='frm_bitacoraMttoPreventivo.php'"
						onmouseover="window.status='';return true"/><?php 
					}?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_regMec" type="submit" class="botones_largos" value="Agregar Mec&aacute;nico"
					onclick="hdn_bandera.value='no'; document.frm_complementarBitacora.action='frm_complementarMecanico.php'; document.frm_complementarBitacora.submit();"
					onMouseOver="window.status='';return true"/>
				</td>
	 		</tr>
		</table>
  	</div><?php


	//Agregamos los datos del post Bitacora Mtto Preventivo al arreglo de SESSION(el campo txt_ot es exclusivo del formulario de registro de la Bitácora de Mtto Preventivo)
	if(isset($_POST["txt_ot"]) && isset($_POST['txt_tiempoTotal'])){
		//Guardar los datos de la Bitácora de Mtto. Preventivo en el arreglo de SESSION
		$_SESSION['bitacoraPrev'] = array("txt_claveBitacora"=>$txt_claveBitacora, "txt_turno"=>$txt_turno, "txt_ot"=>$txt_ot, 
		"txt_costoMant"=>str_replace(",","",$txt_costoMant), "txt_claveEquipo"=>$txt_claveEquipo, "txt_costoManoObra"=>str_replace(",","",$txt_costoManoObra), 
		"txt_fechaMant"=>$txt_fechaMant, "txt_costoTotal"=>str_replace(",","",$txt_costoTotal), "txt_tipoMant"=>$txt_tipoMant, "txt_noFactura"=>strtoupper($txt_noFactura),
		"txa_comentarios"=>strtoupper($txa_comentarios), "txt_horometro"=>str_replace(",","",$txt_horometro), "txt_odometro"=>str_replace(",","",$txt_odometro),
		"txt_tiempoTotal"=>$txt_tiempoTotal, "txt_proxMant"=>$txt_proxMant, "cmb_ordenExterna"=>$cmb_ordenExterna);
	}
	
	//Agregamos los datos del post Bitacora Mtto Correctivo al arreglo de SESSION(el campo cmb_area es exclusivo del formulario de registro de la Bitácora de Mtto Correctivo)
	if(isset($_POST["txt_claveOrdenTrabajo"]) && isset($_POST["cmb_area"])){
		//Guardar los datos de la bitácora de Mtto. Correctivo en el arreglo de SESSION
		$_SESSION['bitacoraCorr'] = array("txt_claveBitacora"=>$txt_claveBitacora, "cmb_familia"=>$cmb_familia, "cmb_area"=>$cmb_area, "cmb_claveEquipo"=>$cmb_claveEquipo, 
		"cmb_turno"=>$cmb_turno, "txt_claveOrdenTrabajo"=>$txt_claveOrdenTrabajo, "txt_costoMant"=>str_replace(",","",$txt_costoMant), 
		"txt_costoTotal"=>str_replace(",","",$txt_costoTotal), "txt_costoManoObra"=>str_replace(",","",$txt_costoManoObra), "txt_fechaMant"=>$txt_fechaMant, 
		"txt_tipoMant"=>$txt_tipoMant, "txt_noFactura"=>strtoupper($txt_noFactura), "txa_comentarios"=>strtoupper($txa_comentarios), "txt_metrica"=>$txt_metrica, 
		"txt_cantMet"=>str_replace(",","",$txt_cantMet), "txt_tiempoTotal"=>$txt_tiempoTotal);				
	}?>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
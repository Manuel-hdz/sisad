<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarProduccion.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionProduccion.js" ></script>
	<script type="text/javascript" src="includes/ajax/obtenerDatoPresupuesto.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-registrar {	position:absolute;	left:30px;	top:146px;	width:262px;	height:20px;	z-index:11;}
			#tabla-escogerRegistro {position:absolute;left:30px;top:190px;width:450px;height:149px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-registroProduccion {position:absolute;left:30px;top:190px;width:804px;height:249px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-registroEquipos {position:absolute;left:30px;top:190px;width:934px;height:371px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-encabezadoEquipos {position:absolute;left:30px;top:30px;width:934px;height:371px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-registroSeguridad {position:absolute;left:30px;top:190px;width:600px;height:216px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-resultadosSeguridad {position:absolute;left:30px;top:450px;width:600px;height:200px;z-index:12;padding:15px;padding-top:0px; overflow:scroll}
			#botones-equipos {position:absolute;left:200px;top:414px;width:506px;height:30px;z-index:12;padding:15px;padding-top:0px;}
			#calendar{position:absolute; left:478px; top:245px; width:30px; height:26px; z-index:18; }	
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8; overflow:scroll}
		-->
    </style>
</head>
<body>
<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-registrar">Agregar Registro de Producci&oacute;n </div>
<?php
//Verificamos si Viene el boron guardar Registro de la Bitacora
if(isset($_POST["sbt_guardar"])){
	guardarRegistroBitacora();
}
	if(!isset($_POST["cmb_tipoRegistro"])){
		//Liberamos sessiones en caso de que existan
		if(isset($_SESSION["produccion"]))
			unset($_SESSION["produccion"]);
		if(isset($_SESSION["seguridad"]))
			unset($_SESSION["seguridad"]);?>
        <fieldset class="borde_seccion" id="tabla-escogerRegistro" name="tabla-escogerRegistro">
        <legend class="titulo_etiqueta">Seleccionar Informaci&oacute;n del Registro</legend>	
        <br>
        <form name="frm_registro" method="post"  id="frm_registro" >
        <table width="491" height="108" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
              <td width="102"><div align="right">Registrar</div></td>
                <td width="185">
                 	<div align="left">
                    <p>
                      <select name="cmb_tipoRegistro" id="cmb_tipoRegistro" onchange="javascript:document.frm_registro.submit();" >
                        <option selected="selected" value="">Tipo de Registro</option>
                        <option value="produccion">PRODUCCI&Oacute;N</option>
                        <option value="equipos">EQUIPOS</option>
                        <option value="seguridad">SEGURIDAD</option>
                      </select>
                    </p>
               	  </div>				
				</td>
				 <td width="102"><div align="right">Fecha</div></td>
				<!--<td width="209"><input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text" 	value="<?php echo date("d/m/Y"); ?>" size="10"  width="90"/> </td>-->
				<td width="209"><input name="txt_fecha" id="txt_fecha" type="text" 	value="<?php echo date("d/m/Y"); ?>" size="10"  width="90"/> </td>
			</tr>
          	<tr>
            	<td colspan="4">
                    <div align="center">       	    	
                        <input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
                        title="Regresar al Men&uacute; Bit&aacute;cora"
                        onmouseover="window.status='';return true" onclick="location.href='menu_bitacora.php';"/>					
                    </div>				
				</td>
			</tr>
        </table>
        </form>
		</fieldset>
		<div id="calendar">
			<input name="fechaRegistro" type="image" id="fechaRegistro" onclick="displayCalendar(document.frm_registro.txt_fecha,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"  title="Seleccionar Fecha"
			width="25" height="25" border="0" />
</div>
<?php }
	else{
		if(isset($_POST["cmb_tipoRegistro"])&& $_POST["cmb_tipoRegistro"]=="produccion"){?>
			<fieldset class="borde_seccion" id="tabla-registroProduccion" name="tabla-registroProduccion">
			<legend class="titulo_etiqueta">Ingresar los Datos del Registro</legend>	
			<br>
			<form name="frm_registroProduccion" method="post"  id="frm_registroProduccion" onsubmit="return valFormRegistroProduccion(this);">
			<table width="812" height="201" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="130"><div align="right">*Volumen Producido</div></td>
					<td width="217">
						<input type="text" name="txt_volProducido" id="txt_volProducido" maxlength="10" size="10" class="caja_de_texto" 
						value="" onchange="formatCurrency(value,'txt_volProducido');" onkeypress="return permite(event,'num',2);" readonly="readonly"/> m&sup3;
					</td>
					<td width="156"><div align="right">Fecha </div></td>
					<td width="242"> 
						<input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text" value="<?php echo $_POST["txt_fecha"];?>" size="10"  
						width="90"/>					</td>
				</tr>
				<tr>
					<td width="130"><div align="right">*Destino</div></td>
					<td width="217">
					<?php $cmb_destino=""; //obtenerDatoPresupuesto(this.value,'bd_produccion','presupuesto','vol_ppto_dia','catalogo_destino_id_destino','txt_volPresupuestado');
							$conn = conecta("bd_produccion");
							$result=mysql_query("SELECT DISTINCT id_destino, destino FROM catalogo_destino ORDER BY destino");
							if($destino=mysql_fetch_array($result)){?>
					<select name="cmb_destino" id="cmb_destino" size="1" class="combo_box" 
								onchange="activarBoton(this);obtenerPresupuesto(cmb_destino.value,txt_fecha.value,'txt_volPresupuestado');">
                      <option value="">Destino</option>
                      <?php 
								  do{
									if ($destino['destino'] == $cmb_destino){
										echo "<option value='$destino[id_destino]' selected='selected'>$destino[destino]</option>";
									}
									else{
										echo "<option value='$destino[id_destino]'>$destino[destino]</option>";
									}
								}while($destino=mysql_fetch_array($result)); 
								//Cerrar la conexion con la BD		
								mysql_close($conn);
								?>
                    </select>
					<?php }
						else{
							echo "<label class='msje_correcto'> No hay Destinos Registrados</label>
							<input type='hidden' name='cmb_destino' id='cmb_destino'/>";
						}?>					</td>
					<td width="156"><div align="right">Volumen Presupuestado Diario</div></td>
					<td>
						  <input type="text" name="txt_volPresupuestado" id="txt_volPresupuestado"  value=""readonly="readonly" 
						maxlength="20" size="20" class="caja_de_texto" onkeypress="return permite(event,'num',2);" /> 
				      m&sup3;</td>
				</tr>
				<tr>
					<td><div align="right">Observaciones</div></td>
					<td>
						<textarea name="txa_observaciones" id="txa_observaciones" class="caja_de_texto" cols="40" rows="3" maxlength="120" 
						onkeyup="return ismaxlength(this)"></textarea>					</td>			
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="5"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
				<tr>
					<td colspan="4">
						<div align="center">
							<input type="hidden" name="hdn_cmbTipo" id="hdn_cmbTipo"/>
							<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Registro" 
							onmouseover="window.status='';return true"/>   
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
							onMouseOver="window.status='';return true" onclick="btn_detalles.style.visibility='hidden';resetearFomulario();"/>    	    	
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
							title="Regresar a P&aacute;gina Principal Registro de Producci&oacute;n" 
							onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_registrarProduccion.php')" />	
							&nbsp;&nbsp;&nbsp;
							<input type="button" name="btn_detalles" id="btn_detalles" class="botones" value="Agregar Detalles" 
							onMouseOver="window.estatus='';return true"  style="visibility:hidden"
							title="Registrar Detalles de la Producci&oacute;n" 
							onClick="envioDatosGet();"/>																
						</div>					</td>
				</tr>
			</table>
			</form>
		</fieldset>			
	<?php }
		  elseif(isset($_POST["cmb_tipoRegistro"])&& $_POST["cmb_tipoRegistro"]=="equipos"){?>
			<div id="tabla-registroEquipos" name="tabla-registroEquipos">
			<br>
			<form name="frm_registroEquipos" method="post"  id="frm_registroEquipos" onsubmit="return valFormEquipos(this);">
			<table width="812" height="41" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="682" align="right"><div align="right">Fecha</div></td>
				  <td width="93" align="right"> 
					<input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text" value="<?php echo $_POST["txt_fecha"];?>" size="10"  width="90"/>
				</td>
				</tr>
			</table>
				<div align="center" id="tabla-encabezadoEquipos">
					<?php 
						// Llamada a la funcion donde solo se desplega el titulo de la tabla
						mostrarEncabezado();
					?>
				</div>
        		<div id="titulo-tabla" align="center" class="borde_seccion2"> 
            		 <?php 
						mostrarEquipos();
					 ?>
       		 	</div>
			<table width="812" height="41" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="50%" align="center">
						<div align="center" id="botones-equipos">
							<input name="sbt_guardarEquipo" type="submit" class="botones" id="sbt_guardarEquipo"  value="Guardar" title="Guardar Registro" 
							onmouseover="window.status='';return true"/>   
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
							onMouseOver="window.status='';return true" onclick="desabilitar();"/>    	    	
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar a P&aacute;gina Principal Registro de Producci&oacute;n" 
							onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_registrarProduccion.php')" />	
						</div>			
					</td>
				</tr>
			</table>
			</form>
	</div>
	<?php }
		elseif(isset($_POST["cmb_tipoRegistro"])&& $_POST["cmb_tipoRegistro"]=="seguridad"){
			//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
			if (isset($_POST["sbt_guardarSeguridad"])){
				//Si ya esta definido el arreglo $seguridad, entonces agregar el siguiente registro a el
				if(isset($_SESSION['seguridad'])){			
					//Guardar los datos en el arreglo
					$seguridad[] = array("partida"=>$_POST["txt_partida"], "tipo"=>$_POST["cmb_tipo"],"observaciones"=>strtoupper($_POST["txa_observaciones"]));
				}
				//Si no esta definido el arreglo $seguridad definirlo y agregar el primer registro
				else{			
					//Guardar los datos en el arreglo
					$seguridad = array(array("partida"=>$_POST["txt_partida"], "tipo"=>$_POST["cmb_tipo"],"observaciones"=>strtoupper($_POST["txa_observaciones"])));
					$_SESSION['seguridad'] = $seguridad;	
				}	
			}
		
			//Verificar que este definido el Arreglo de seguridad, si es asi, lo mostramos en el formulario
			if(isset($_SESSION["seguridad"])){
				echo "<div id='tabla-resultadosSeguridad' class='borde_seccion2'>";
					mostrarResultados($seguridad);
				echo "</div>";
		}
			//Verificamos que el arreglo de sesion no venga definido si es asi el contador tomara el valor de 1
			if(!isset($_SESSION["seguridad"]))
				$cont=generarIdSeguridad($_POST["txt_fecha"]);	
			else
				//De lo contrario si se vuelve a entrar a agregar otro mecanico, para conservar la partida se cuenta el arreglo y se le agrega uno
				$cont=count($_SESSION["seguridad"])+1;
			//Si el boton viene definido se incrementa la partida
			if(isset($_POST["sbt_guardarSeguridad"])){
				$cont=$_POST["txt_partida"]+1;
			}?>
			<fieldset class="borde_seccion" id="tabla-registroSeguridad" name="tabla-registroSeguridad">
			<legend class="titulo_etiqueta">Ingresar los Datos Referentes a la Seguridad</legend>	
			<br>
			<form name="frm_registroSeguridad" method="post"  id="frm_registroSeguridad" onsubmit="return valFormSeguridad(this);" >
			<table width="100%" height="201" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="130"><div align="right">N&uacute;mero</div></td>
					<td width="217">
						<input type="text" name="txt_partida"  readonly="readonly" id="txt_partida" maxlength="3" size="3" class="caja_de_texto" 
						value="<?php echo $cont;?>"/>
					</td>
					<td width="156"><div align="right">Fecha </div></td>
					<td width="242">
						<input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text" value="<?php echo $_POST["txt_fecha"];?>" size="10"  width="90"/>
					</td>
				</tr>
				<tr>
					<td width="130"><div align="right">*Tipo</div></td>
					<td width="217">
						<select name="cmb_tipo" id="cmb_tipo" size="1" class="combo_box">
							<option value="">Tipo</option>
							<option value="ACCIDENTE A" title="Accidente Menor que no Causa Incapacidad">A</option>
							<option value="ACCIDENTE B" title="Accidente que Causa una Lesi&oacute;n Menor y no Genera Incapacidad">B</option>
							<option value="ACCIDENTE C" title="Causan 1 D&iacute;a de Incapacidad (Tiempo Perdido Ante el IMSS)">C</option>
							<option value="ACCIDENTE D" title="Amputaci&oacute;n, Perdida de Alg&uacute;n Miembro, Perdida Parcial o Permanente">D</option>
							<option value="ACCIDENTE F" title="Fatal">F</option>
						</select>
					</td>
					<td ro><div align="right">*Observaciones</div></td>
					<td>
						<textarea name="txa_observaciones" id="txa_observaciones" class="caja_de_texto" cols="40" rows="3" maxlength="120"
						onkeyup="return ismaxlength(this)"></textarea>
					</td>
				<tr>
					<td colspan="5"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
				<tr>
					<td colspan="5">
						<div align="center">
							<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
							<?php if(isset($_SESSION['seguridad'])){?>
								<input name="sbt_finalizarRegistro" type="submit" class="botones" id="sbt_finalizarRegistro"  value="Finalizar" title="Guardar Registros" 
								onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_finalizar'"/>   
							&nbsp;&nbsp;&nbsp;
							<?php }?>
							<input type="hidden" name="cmb_tipoRegistro" id="cmb_tipoRegistro" value="seguridad"/>
		             	    <input name="sbt_guardarSeguridad" type="submit" class="botones" id="sbt_guardarSeguridad"  value="Agregar" title="Agregar Registros" 
							onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_agregar'" />   
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
							onMouseOver="window.status='';return true"/>    	    	
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar a P&aacute;gina Principal Registro de Producci&oacute;n" 
							onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_registrarProduccion.php')" />																	
						</div>			
					</td>
				</tr>
			</table>
			</form>
</fieldset>			
	<?php }
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
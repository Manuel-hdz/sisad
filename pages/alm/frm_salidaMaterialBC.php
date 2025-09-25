<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Manejo de la funciones para registrar la Salida de Materiales en la BD
		include ("op_salidaMaterial.php");					
		
		//Liberar los valores de la SESSION para Salida de Material, en el caso que se haya dado click en boton de Cancelar en la pagina donde se pide la información complementaria de la Salida
		if(isset($_GET['lmp']) && $_GET['lmp']=="si"){
			unset($_SESSION['datosSalida']);
			unset($_SESSION['id_salida']);
			//unset($_SESSION['id_equipo']);
		}
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarDatosMateriales.js"></script>
	
	<script type="text/javascript" language="javascript">
		setTimeout("document.getElementById('txt_cantSalida').focus();",100);
	</script>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#titulo-salida { position:absolute; left:30px; top:146px; width:141px; height:19px; z-index:11; }
		#form-salida {	position:absolute; left:30px; top:190px; width:466px; height:100px; z-index:12; }
		#material-agregado { position:absolute; left:30px; top:320px; width:952px; height:370px; z-index:14; overflow:scroll}
		#boton-terminar { position:absolute; left:173px; top:462px; width:141px; height:37px; z-index:15; }
		-->
    </style>
</head>
<body>
	<audio id="sonido_alertas_correcto" preload>
		<source src="includes/sounds/correct.mp3" type="audio/mpeg" />
	</audio>
	<audio id="sonido_alertas_incorrecto" preload>
		<source src="includes/sounds/wrong.mp3" type="audio/mpeg" />
	</audio>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-salida">Salida Material </div>     
		
	<form name="frm_salidaDetalle" id="frm_salidaDetalle" action="frm_salidaMaterialBC.php" method="post" onsubmit="return valFormSalidaBC(this);">
	<fieldset class="borde_seccion" id="form-salida" name="form-salida">	
	<legend class="titulo_etiqueta">Seleccionar Material para Registrar en la Salida</legend>
	<br>
	<table width="100%" border="0" cellpadding="5" class="tabla_frm">
		<tr>
			<td><div align="center">Cant. Salida</div></td>
			<td><div align="center">Id Equipo</div></td>
			<!--<td><div align="center">Tipo Moneda</div></td>-->
			<td><div align="center">C&oacute;digo</div></td>
		</tr>
		<tr>
			<td align="center">
				<!-- <input name="txt_cantSalida" type="text" class="caja_de_num" id="txt_cantSalida" onfocus="this.oldvalue = this.value" onchange="validadCantidadEquipo(this.value,this.oldvalue)" onkeypress="return permite(event,'num');" size="15" maxlength="20" value="" tabindex="1" onblur="formatCurrency(this.value,'txt_cantSalida');"/> -->
				<input name="txt_cantSalida" type="text" class="caja_de_num" id="txt_cantSalida" onfocus="this.oldvalue = this.value" onchange="validadCantidadEquipo(this.value,this.oldvalue)" size="15" maxlength="20" value="" tabindex="1"/>
		  </td>
			<!-- <td>
				<div align="center">
				<?php //$conn_mtto = conecta("bd_mantenimiento");//Conectarse a la BD de Mantenimiento?>
				<select name="cmb_idEquipo" id="cmb_idEquipo" size="1" class="combo_box" title="Seleccionar Id del Equipo al que va Destinado el Material"  tabindex="2">
				  <option value="" title="Seleccionar Id del Equipo">Id Equipo</option>
				  <?php /*$result_mtto = mysql_query("SELECT id_equipo,nom_equipo FROM equipos ORDER BY id_equipo");		
						$band = 0;
						echo "<option value='N/A' title='Material que no Aplica para un Equipo'>NO APLICA</option>";
						while ($datos_equipos=mysql_fetch_array($result_mtto)){
							echo "<option value='$datos_equipos[id_equipo]' title='$datos_equipos[nom_equipo]'>$datos_equipos[id_equipo]</option>";
						}
						if ( (isset($_SESSION["id_equipo"])) ){
							echo "<script type='text/javascript' language='javascript'>
								document.getElementById('cmb_idEquipo').value='$_SESSION[id_equipo]';
								document.getElementById('cmb_idEquipo').disabled='disabled';
							  </script>";
						}*/
					?>
				</select>
				<?php		
				//Cerrar la conexion con la BD		
				//mysql_close($conn_mtto); ?>
				</div>
			</td> -->
			<td>
				<div align="center">
				  <input type="text" name="cmb_idEquipo" id="cmb_idEquipo" class="caja_de_texto" size="10" maxlength="20" onfocus="this.oldvalue = this.value;" onchange="extraerInfoEquipo(this.value,this.oldvalue);"  tabindex="3"/>
				</div>
			</td>
			<!--<td>
				<div align="center">
				  <input type="text" name="cmb_tipoMoneda" id="cmb_tipoMoneda" class="caja_de_texto" size="10" maxlength="20" onfocus="this.oldvalue = this.value;" onchange="procesarDatosMoneda(this.value,this.oldvalue);"  tabindex="3"/>
				</div>
			</td>-->
			<input type="hidden" name="cmb_tipoMoneda" id="cmb_tipoMoneda" value="PESOS"/>
			<td>
				<div align="center">
				  <input type="text" name="txt_codBar" id="txt_codBar" class="caja_de_texto" size="10" maxlength="20" onfocus="this.oldvalue = this.value;" onchange="extraerInfoSalida(this.value,txt_cantSalida.value,cmb_idEquipo.value,this.oldvalue);"  tabindex="3"/>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="3" valign="middle" align="center">
				<span id="mensaje" class="msje_correcto" style="visibility:hidden;">No Se Encontr&oacute; Ning&uacute;n Material</span>			</td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				
				<input type="hidden" name="hdn_material" id="hdn_material" value=""/>
				<input type="hidden" name="hdn_existencia" id="hdn_existencia" value=""/>
				<input type="hidden" name="hdn_unidadMedida" id="hdn_unidadMedida" value=""/>
				<input type="hidden" name="hdn_costoUnidad" id="hdn_costoUnidad" value=""/>
				<input type="hidden" name="hdn_clave" id="hdn_clave" value=""/>
				
				<input type="hidden" name="hdn_validar" id="hdn_validar" value="1" />
				<!--<input type="submit" name="btn_agregarOtro" class="botones" value="Agregar Otro" onMouseOver="window.status='';return true"
				title="Agregar Material al Registro de la Entrada" tabindex="4"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<?php if(isset($_SESSION['datosSalida']) || isset($_POST["txt_codBar"])){?>
				<input name="sbt_terminar" type="submit" value="Continuar" class="botones" title="Registrar Datos Complementarios de la Salida" 
				onmouseover="window.status='';return true" onclick="hdn_validar.value=0;frm_salidaDetalle.action='frm_salidaMaterial2.php?cb=1';frm_salidaDetalle.submit();"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<?php }?>
				<input name="btn_Cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Regresar al Men&uacute; de Salida de Material" 
				onclick="location.href='menu_entrada_salida.php'"/>			</td>-->
		</tr>
	</table>
	</fieldset>
  	</form>
	 
	<?php
	//Si las siguientes variables se encuentran definidas en el arreglo POST, procesder a guardar los datos en el arreglo datosSalida 			
	if(isset($_POST['txt_codBar'])){			
		$conexion = conecta("bd_almacen");
		$stm_sql_ent = "SELECT T1.`fecha_entrada` , T2.`materiales_id_material` , T2.`nom_material` , T2.`unidad_material` , T2.`costo_unidad` , T2.`tipo_moneda` , SUM(  `cant_restante` ) AS cantidad_existente, GROUP_CONCAT( CAST(  `entradas_id_entrada` AS CHAR ) ) AS entradas, GROUP_CONCAT( CAST(  `cant_restante` AS CHAR ) ) AS cantidades_restantes
						FROM  `entradas` AS T1
						JOIN  `detalle_entradas` AS T2 ON  `id_entrada` =  `entradas_id_entrada` 
						WHERE  `materiales_id_material` = '$hdn_clave'
						AND  `cant_restante` >0
						GROUP BY  `tipo_moneda` ,  `costo_unidad` ,  `unidad_material` 
						ORDER BY cantidad_existente DESC ,  `T1`.`fecha_entrada` ASC ";
		$rs_ent = mysql_query($stm_sql_ent);
		if($rs_ent){
			while($datos_ent = mysql_fetch_array($rs_ent)){
				$cant_intro = 0;
				if($datos_ent['cantidad_existente'] <= $txt_cantSalida){
					$cant_intro = $datos_ent['cantidad_existente'];
					$txt_cantSalida -= $cant_intro;
				}
				else{
					$cant_intro = $txt_cantSalida;
					$txt_cantSalida = 0;
				}
				if($cant_intro > 0){
					if(isset($_SESSION['datosSalida'])){
						//Obtener el nombre del material para agregarlo al arreglo
						$nombre = obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $hdn_clave, "costo_unidad");
						//Verificar que las cantidads registradas de salidad de un solo material no excedan su existencia, en el caso de que no se alcance a cubrir la demanda, no se agrega el registro y se manda un msg de alerta
						if(revExistenciaMaterial($datosSalida, "clave", $hdn_clave, $cant_intro, $nombre)){
							$band = 0;
							$cont = 0;
							foreach ($_SESSION['datosSalida'] as $ind => $materiales) {
								if($materiales["clave"]==$hdn_clave && $materiales["tipoMoneda"]==$datos_ent['tipo_moneda'] && $materiales["costoUnidad"]==$datos_ent['costo_unidad']){
									$band = 1;
									if($datos_ent['cantidad_existente'] != $_SESSION['datosSalida'][$cont]["cantSalida"]){
										$txt_cantSalida += $cant_intro - ($datos_ent['cantidad_existente'] - $_SESSION['datosSalida'][$cont]["cantSalida"]);
										if($cant_intro + $_SESSION['datosSalida'][$cont]["cantSalida"] > $datos_ent['cantidad_existente'])
											$cant_intro = $datos_ent['cantidad_existente'] - $_SESSION['datosSalida'][$cont]["cantSalida"];
										$_SESSION['datosSalida'][$cont]["cantSalida"] = $cant_intro + $_SESSION['datosSalida'][$cont]["cantSalida"];
										$_SESSION['datosSalida'][$cont]["costoTotal"] = number_format($_SESSION['datosSalida'][$cont]["cantSalida"] * $_SESSION['datosSalida'][$cont]["costoUnidad"],2);
									} else {
										$txt_cantSalida += $cant_intro;
									}
								}
								$cont++;
							}
							if($band == 0){
								//Guardar los datos en el arreglo
								$datosSalida[] = array("clave"=>$hdn_clave, "nombre"=>$nombre, "existencia"=>$hdn_existencia, "cantSalida"=>$cant_intro, "costoUnidad" => number_format($datos_ent['costo_unidad'],2), 
													"costoTotal"=>number_format(($cant_intro*$datos_ent['costo_unidad']),2),"idEquipo"=>$cmb_idEquipo,"tipoMoneda"=>$datos_ent['tipo_moneda'], 
													"cantRestante"=>$datos_ent['cantidad_existente'], "idEntradas"=>$datos_ent['entradas'], "cantidadEntradas"=>$datos_ent['cantidades_restantes']);
								$_SESSION['datosSalida'] = $datosSalida;
							}
						}
					}
					//Si no esta definido el arreglo $datosEntrada definirlo y agregar el primer registro
					else{
						//Obtener el nombre del material para agregarlo al arreglo
						$nombre = obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $hdn_clave, "costo_unidad");
						$datosSalida = array(array("clave"=>$hdn_clave, "nombre"=>$nombre, "existencia"=>$hdn_existencia, "cantSalida"=>$cant_intro, "costoUnidad" => number_format($datos_ent['costo_unidad'],2), 
													"costoTotal"=>number_format(($cant_intro*$datos_ent['costo_unidad']),2),"idEquipo"=>$cmb_idEquipo,"tipoMoneda"=>$datos_ent['tipo_moneda'], 
													"cantRestante"=>$datos_ent['cantidad_existente'], "idEntradas"=>$datos_ent['entradas'], "cantidadEntradas"=>$datos_ent['cantidades_restantes']));
						$_SESSION['datosSalida'] = $datosSalida;	
						//Crear el ID de la Entrada de Material
						$_SESSION['id_salida'] = obtenerIdSalida();							
					}
				}
			}
		}			
	}
	
	//Verificar que el arreglo de datos haya sido definido
	if( (isset($_SESSION['datosSalida']) && count($_SESSION['datosSalida'])>0) && isset($_SESSION['id_salida'])){
		?>?><div id="material-agregado" class="borde_seccion" align="center">
    		<!-- <p align="center" class="titulo_etiqueta">Registro de la Salida de Material No. <?php echo $_SESSION['id_salida']; ?></p> -->
			<p align="center" class="titulo_etiqueta">Registro de la Salida de Material</p><?php
    		mostrarRegistros($datosSalida,0);
		?></div><?php
    }
	?>	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>

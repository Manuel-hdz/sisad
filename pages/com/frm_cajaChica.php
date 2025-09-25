<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml">

	<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para manejar los Movimientos en la Caja Chica
		include ("op_cajaChica.php");
	
?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<script type="text/javascript" src="../../includes/validacionCompras.js"></script>
		<script type="text/javascript" src="../../includes/maxLength.js"></script>
	
		<script type="text/javascript" src="jquery-3.4.1.js"></script>
		<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			#barra-titulo {
				position: absolute;
				left: 30px;
				top: 145px;
				width: 93px;
				height: 20px;
				z-index: 11;
			}

			#tablas {
				position: absolute;
				left: 30px;
				top: 190px;
				width: 578px;
				height: 240px;
				z-index: 12;
			}

			#detalle-cajaChica {
				position: absolute;
				left: 30px;
				top: 500px;
				width: 940px;
				height: 200px;
				z-index: 14;
				overflow: scroll;
			}

			#manejo-incremento {
				position: absolute;
				left: 652px;
				top: 190px;
				width: 322px;
				height: 240px;
				z-index: 15;
			}
		</style>
	</head>

	<body style="cursor:url('../../images/cursor.cur');">
		<?php
	//Guardar los datos de la Caja Chica en la SESSION cuando se ingresa a esta pagina por primera vez al iniciar Sesion como AdminCompras
	if(!isset($_SESSION['datosCajaChica'])){		
		//Obtener la conexion con la BD de Compras
		$conn = conecta("bd_compras");
		
		//Obtener la cantidad de registros de Caja Chica en la BD
		$noFilas = mysql_fetch_array(mysql_query("SELECT COUNT(id_caja_chica) as cant FROM caja_chica"));
		//Si no hay ninguno registro de Caja Chica en la BD, solicitar el presupuesto para el primer registro de la Primera Caja Chica
		if($noFilas['cant']==0){
			//Enviar a una pagina que solicite el monto inicial de la Caja Chica
			echo "<meta http-equiv='refresh' content='0;url=frm_preCajaChica.php?origen=preInicial'>";			
		}
		else{
			//Obtener el a�o actual y convertirlo en numero
			$anio = intval(date("y"));			
			//Obtener la ultima clave de caja chica registrada de acuerdo al a�o en curso
			$rs = mysql_query("SELECT MAX(id_caja_chica) AS id_cc FROM caja_chica WHERE id_caja_chica LIKE '%$anio'");			
									
			$clave_cajaChicaRegBD = "";
			//Obtener los datos de la consulta realizada
			if($datos = mysql_fetch_array($rs)){				
				if($datos['id_cc']!=""){//Verificar que la consulta no regrese un resultado vacio
					//Obtener el ID de la Ultima Caja Chica registrada en la BD
					$clave_cajaChicaRegBD = $datos['id_cc'];
				}				
				else{
					//En el caso de que el a�o en curso no aroje ningun resultado y que el mes en curso sea ENERO, reducir en 1 el a�o y comprobar resultados otra vez
					if(intval(date("m"))==1) $anio--;
					//Obtener la ultima clave de caja chica registrada en la BD de un a�o anterior al actual
					$rs = mysql_query("SELECT MAX(id_caja_chica) AS id_cc FROM caja_chica WHERE id_caja_chica LIKE '%$anio'");
					if($datos = mysql_fetch_array($rs))
						$clave_cajaChicaRegBD = $datos['id_cc']; //Obtener el ID de la Ultima Caja Chica registrada en la BD
				}
			}//Cierre if($datos = mysql_fetch_array($rs))
												
			//Obtener la clave actual			
			$claveActual = obtenerIdCCH();

			if($clave_cajaChicaRegBD!=$claveActual){			
				echo "<meta http-equiv='refresh' content='0;url=frm_preCajaChica.php?origen=preMensual&claveActual=$claveActual&claveAnterior=$clave_cajaChicaRegBD'>";				
			}
			else{									
				//Obtener el presupuesto registrado en al BD
				$presupuesto = obtenerDato("bd_compras","caja_chica","presupuesto","id_caja_chica","$clave_cajaChicaRegBD");
				
				if($datos=mysql_fetch_array(mysql_query("SELECT MAX(movimiento) AS cant FROM detalle_caja_chica WHERE 
				caja_chica_id_caja_chica='$clave_cajaChicaRegBD'")))
					$noMov = $datos['cant'] + 1;
				else
					$noMov = 1;			
					
				$datosCajaChica = array("id_cajaChica"=>$clave_cajaChicaRegBD,"presupuesto"=>$presupuesto,"noMovimiento"=>$noMov);
				//Guardar datos iniciales en la SESSION
				$_SESSION['datosCajaChica'] = $datosCajaChica;				
			}
		}				
		//Cerrar la conexion con la BD
		mysql_close($conn);				
	}//Cierre de if(!isset($_SESSION['datosCajaChica']))
	
	
	if(isset($_SESSION['datosCajaChica'])){
		//Variable para manejar el mensaje de registro agregado con exito		
		$msg = "";
		//Guardar el monto del incremento al presupuesto del mes actual
		if(isset($_POST['txt_inPresupuesto'])){ 		
			//Quitar la coma al incremento del presupusto, para poder realziar la operaciones requeridas.
			$txt_inPresupuesto=str_replace(",","",$txt_inPresupuesto);
			//Guardar los datos del movimiento en la BD
			$msg = guardarIncremento($_SESSION['datosCajaChica']['id_cajaChica'],$txt_inPresupuesto,$hdn_fechaMov);		
		}
		
		//Guardar los datos del movimiento
		if(isset($_POST['txa_descripcion'])){
			if (!verificarRegCCH($txt_NoMov,$_SESSION['datosCajaChica']['id_cajaChica'])){		
				//Quitar la coma al total de gastos del movimiento, para poder realziar la operaciones requeridas.
				$cant_entregada=str_replace(",","",$cant_entregada);									
				//Guardar los datos del movimiento en la BD
				$msg = guardarMovimiento($_SESSION['datosCajaChica']['id_cajaChica'],$txt_NoMov,$hdn_fechaMov,$txt_factura,$txt_responsable,$txa_descripcion,
				$cant_entregada, $cmb_depto);                                   
			}
		}
		
		//Guardar los cambio del movimiento seleccionado en el detalle de la Caja Chica, esto es cuando un registro se complementa o se borra y el dinero destinado se regresa o se resta segun sea el caso
		if(isset($_POST['hdn_cont'])){
			$num = $_POST['hdn_cont'];
			//Quitar la coma a la diferencia y al total de gastos del movimiento, para poder realziar la operaciones requeridas.
			$txt_dif=str_replace(",","",$_POST["txt_dif".$num]);
			$txt_totalGastos=str_replace(",","",$_POST["txt_totalGastos".$num]);
			//Actuzalizar el movimiento seleccionado
			$msg = actualizarMovimiento($_SESSION['datosCajaChica']['id_cajaChica'],$_POST["hdn_NoMov".$num],$_POST["txt_factura".$num],$_POST["txa_descripcion".$num],
			$txt_dif,$txt_totalGastos);
		}
	
		//Desplegar los movimientos registrados en la BD
		if($_SESSION['datosCajaChica']['noMovimiento']>1){?>
		<div id="detalle-cajaChica" class="borde_seccion2" align="center"><?php
				echo "<label class='msje_correcto'>$msg</label>";
				verDetalleCajaChica($_SESSION['datosCajaChica']['id_cajaChica']);?>
		</div><?php
		}?>

		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="barra-titulo">Caja Chica</div>

		<fieldset class="borde_seccion" id="tablas">
			<legend class="titulo_etiqueta">Registrar Movimiento en la Caja Chica</legend>
			<br>
			<form onSubmit="return verContFormCajaChica(this);" name="frm_cajaChica" method="post"
				action="frm_cajaChica.php">
				<!-- RESULTADO DE LA BUSQUEDA DE PERSONAL DE ABAJO -->
				<div id="res-spider">
					<div align="left" class="suggestionsBox" id="suggestions1" style="display: none; position: absolute;">
						
						<div class="suggestionList" id="autoSuggestionsList1" >&nbsp;</div>
					</div>
				</div>
				<!-- --------------------------------------------- -->
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td width="82">
							<div align="right">Presupuesto</div>
						</td>
						<td width="150">
							$
							<input name="txt_presupuestoInicial" id="txt_presupuestoInicial" type="text"
								class="caja_de_texto"
								value="<?php echo number_format($_SESSION['datosCajaChica']['presupuesto'],2,".",","); ?>"
								size="15" maxlength="20" readonly="true" /> </td>
						<td width="68">
							<div align="right">Descripcion</div>
						</td>
						<td width="213">
							<textarea name="txa_descripcion" maxlength="60" onkeyup="return ismaxlength(this);"
								class="caja_de_texto" rows="2" cols="30"
								onkeypress="return permite(event,'num_car', 0);"></textarea>
						</td>
					</tr>
					<tr>
						<td>
							<div align="right">No. de Movimiento</div>
						</td>
						<td>
							<input name="txt_NoMov" type="text" class="caja_de_num" size="5" maxlength="3"
								readonly="true" value="<?php echo $_SESSION['datosCajaChica']['noMovimiento'];?>" />
						</td>
						<td>
							<div align="right">Cant. Entregada </div>
						</td>
						<td>
							$<input name="cant_entregada" id="cant_entregada" type="text" class="caja_de_texto"
								onkeypress="return permite(event,'num', 2);" onchange="verificarCant(this);" size="15"
								maxlength="20" />
						</td>
					</tr>
					<tr>
						<td>
							<div align="right">Fecha</div>
						</td>
						<td>
							<input name="txt_fechaMov" type="text" class="caja_de_texto"
								value=<?php echo verFecha(4); ?> size="10" maxlength="10" readonly=true />
							<input name="hdn_fechaMov" type="hidden" value=<?php echo verFecha(3); ?> />
						</td>
						<td>
							<div align="right">Factura</div>
						</td>
						<td><input name="txt_factura" type="text" class="caja_de_texto" size="12" maxlength="10"
								onkeypress="return permite(event,'num_car', 3);" /></td>
					</tr>
					<tr>
						<td>
							<div align="right">Responsable</div>
						</td>
						<!-- BUSQUEDA DE PERSONAL PARA RESPONSABLE -->
						<td>

							<input type="text" name="txt_responsable" id="txt_responsable" class="caja_de_texto" onkeyup="lookup(this,'empleados','todo','1');" 
							value="" size="30" maxlength="40" onkeypress="return permite(event,'num_car',2);"/>
							
						<script>
							/*Esta funci�n recoje los datos necesarios para realizar la opetici�n al servidor y buscar los datos de acuerdo al texto ingresado por el usuario*/
							function lookup(cajaTexto,nomTabla,depto,num) { 
								if(depto=="")
									depto="todo";
								
								//Obtener el dato a buscar
								var inputString = cajaTexto.value;
								if(inputString.length == 0) {
									//Si el dato a buscar esta vac�o, no mostrar el mensaje de sugerencias
									$('#suggestions'+num).hide();
								} 
								else{
									//Obtener el nombre de la caja de texto que contendr� el valor seleccionado
									var nomCajaTexto = cajaTexto.name;
									//Enviar la petici�n al servidor para realizar la consulta de los datos que ser�n mostrados en el Layer de sugerencias
									

									$.ajax({
										url: 'includes/ajax/busq_spider_personal.php',
										type: 'GET',
										data: {
											nomCajaTexto:nomCajaTexto,
											nomTabla:nomTabla,
											depto:depto,
											num:num,
											queryString: ""+inputString+""
										}, 
										success:function(data){
											$('#suggestions'+num).show();
											$('#autoSuggestionsList'+num).html(data);
										}
									});
								}
							}//Fin de la funcion lookup(cajaTexto,nomTabla,nomCampo,num)

							/*Esta funci�n se encarga de asignar el valor seleccionado a la caja de texto correspondiente*/	
							function fill(nomCampo,thisValue,num) {
								//Asignar el valor seleccionado a la caja de texto correspondiente
								$('#'+nomCampo).val(thisValue);
								//Ocultar el layer que muestra las sugerencias
								$('#suggestions'+num).hide();
							}
						</script>
						<!-- FIN DE BUSQUEDA DE PERSONAL PARA RESPONSABLE -->
						</td>
						<td>
							<div align="right">*Depto</div>
						</td>
						<td>
							<select name="cmb_depto" id="cmb_depto" class="combo_box">
								<option value="">Departamento</option>
								<option value="ALMACEN">ALMAC&Eacute;N</option>
								<option value="AUDITORIA">AUDITORIA</option>
								<option value="CALIDAD">CALIDAD</option>
								<option value="COMPRAS">COMPRAS</option>
								<option value="CLINICA">CL&Iacute;NICA</option>
								<option value="DESARROLLO">DESARROLLO</option>
								<option value="GERENCIA">GERENCIA T&Eacute;CNICA</option>
								<option value="INDIRECTOS">INDIRECTOS</option>
								<option value="LABORATORIO">LABORATORIO</option>
								<option value="MTTO CONCRETO">MTTO CONCRETO</option>
								<option value="PRODUCCION">PRODUCCI&Oacute;N</option>
								<option value="RECURSOS HUMANOS">RECURSOS HUMANOS</option>
								<option value="TOPOGRAFIA">TOPOGRAF&Iacute;A</option>
								<option value="SEGURIDAD">SEGURIDAD</option>
								<option value="SISTEMAS">SISTEMAS</option>
								<option value="NO APLICA">NO APLICA</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="4" align="center">
							<input name="sbt_guardar" type="submit" class="botones_largos" value="Guardar Movimiento"
								title="Agregar Movimiento en el Registro de la Caja Chica"
								onmouseover="window.status='';return true" />
							&nbsp;&nbsp;&nbsp;
							<input name="btn_limpiar" type="reset" class="botones" value="Limpiar"
								title="Limpiar Formulario" />
							&nbsp;&nbsp;&nbsp;
							<input name="btn_regresar" type="button" value="Regresar" class="botones"
								title="Regresar al Men&uacute; de Caja Chica"
								onclick="location.href='menu_cajaChica.php'" />
						</td>
					</tr>
				</table>
			</form>
		</fieldset>

		<fieldset class="borde_seccion" id="manejo-incremento" name="manejo-incremento">
			<legend class="titulo_etiqueta">Incrementar Presupuesto</legend>
			<br /><br /><br /><br />
			<form onsubmit="return valFormIncrementar(this);" name="frm_incrementar" method="post"
				action="frm_cajaChica.php">
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" align="center">
					<tr>
						<td width="180">
							<div align="center">Incremento al Presupuesto</div>
						</td>
					</tr>
					<tr>
						<td>
							<div align="center">$
								<input name="txt_inPresupuesto" id="txt_inPresupuesto" type="text" class="caja_de_num"
									onkeypress="return permite(event,'num', 2);"
									onchange="formatCurrency(value,'txt_inPresupuesto');" size="15" maxlength="20" />
								<input name="hdn_fechaMov" type="hidden" value=<?php echo verFecha(3); ?> />
							</div>
						</td>
					</tr>
					<tr>
						<td align="center">
							<div align="center">
								<input type="submit" name="sbt_incrementar" class="botones" value="Incrementar"
									title="Incrementar Presupuesto" onmouseover="window.status='';return true" />
							</div>
						</td>
					</tr>
				</table>
			</form>
		</fieldset>
		<?php
	}//Cierre de if(isset($_SESSION['datosCajaChica'])) ?>
	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Almac�n
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Manejo de la funciones para registrar la Entrada de Materiales en la BD 
		include ("op_entradaMaterial.php");
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>	
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarDatosMateriales.js"></script>
	<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_material.js"></script>

    <style type="text/css">
		<!--
		#titulo-entrada { position:absolute; left:30px; top:146px; width:200px; height:21px; z-index:11; }
		#form-entrada-material { position:absolute; left:4px; top:190px; width:467px; height:270px; z-index:13; }
		#form-origen-material { position:absolute; left:30px; top:190px; width:540px; height:170px; z-index:12; }
		#material-agregado { position:absolute; left:4px; top:480px; width:1000px; height:100px; z-index:16; }
		#boton-terminar { position:absolute; left:186px; top:445px; width:141px; height:37px; z-index:15; }
		#cargar-datos { position:absolute; left:30px; top:190px; width:913px; height:430px; z-index:17; overflow:scroll;}
		#devolucion-equipo {position:absolute;left:680px;top:190px;width:300px;height:82px;z-index:18;}
		#botones { position: absolute; left:30px; top:670px; width:900px; height:40px; z-index:23; }
		#res-spider {position:fixed;left:70px;z-index:10;}
		#res-spider2 {position:fixed;left:300px;z-index:30;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-entrada">Entrada Material</div>
	
	<?php
		//Verificar el primer boton para verificar si se llego aqui desde la pantalla de frm_entradaMaterial.php
		//Verificar el segundo boton para verificar si es una Entrada Directa y agregar los Materiales, para seguir mostrando el formulario y el arreglo de Session
		//donde se muestran los datos de entrada
		//Verificar el arreglo de Sesion permite regresar aqui despues de editar un registro agregado al Arreglo de Materiales
		if(isset($_POST["sbt_entradaMateriales"]) || isset($_POST["btn_agregarOtro"]) || isset($_SESSION["datosEntrada"])){
			if(isset($_POST["cmb_param"]))
				$parametro=$_POST["cmb_param"];
			else
				$parametro="compra_directa";
			switch($parametro){
				case "compra_directa":
					/**********FORMULARIO PARA ELEGIR LOS MATERIALES A DARLES ENTRADA************/
					if(isset($_POST["sbt_continuar"]))
						echo "<meta http-equiv='refresh' content='0;url=frm_entradaMaterial3A.php?in=cd'>";
					else{
						?>
						<fieldset class="borde_seccion" id="form-entrada-material" name="form-entrada-material">
						<legend class="titulo_etiqueta">Seleccionar Material para Registrar en la Entrada</legend> 		
						<br>
						<form name="frm_entradaMatDirecta" action="frm_entradaMaterial2A.php" method="post" onSubmit="return valFormEntradaDetalle(this);">
						<table border="0" cellpadding="5" class="tabla_frm" width="100%">
							<tr>
							  <td width="22%"><div align="right">Categor&iacute;a</div></td>
								<td width="78%"><?php  		
									$res = cargarComboConId("cmb_categoria","linea_articulo","linea_articulo","materiales","bd_almacen","Categor&iacute;a","",
									"limpiarCamposEntrada2(this,'E');cargarComboIdNombreOrd(this.value,'bd_almacen','materiales','nom_material','id_material','linea_articulo','cmb_material','Material','nom_material','')");
									if($res==0){?>
										<label class="msje_correcto"> No hay Categor&iacute;as Registradas</label>
										<input type="hidden" name="cmb_categoria" id="cmb_categoria"/><?php
									}?>
							  </td>
							</tr>			
							<!--
							<tr>
							  <td><div align="right">Material</div></td>
								<td>
									<select name="cmb_material" id="cmb_material" onchange="extraerDatosMaterialCombo(this.value,'E')" size="1" class="combo_box">
										<option value="" selected="selected">Material</option>					
									</select>
								</td>
							</tr>
							-->
							<tr>
								<td><div align="right">Material</div></td>
								<td>
									<input type="text" name="cmb_material" id="cmb_material" class="caja_de_texto" size="60" onkeyup="lookup2(this,'1',cmb_categoria.value);" 
									value="" maxlength="60" autocomplete="off"/>
									<div id="res-spider">
										<div align="left" class="suggestionsBox" id="suggestions1" style="display: none; width:380px;">
											<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
										</div>
									</div>
								</td>
							</tr>
						</table>
	
						<table width="100%" border="0" cellpadding="5" class="tabla_frm">
							<tr>			
								<td><div align="center">Clave</div></td>
								<td><div align="center">Existencia</div></td>
								<td><div align="center">Unidad de Medida </div></td>		    
							</tr>
							<tr>
								<td>
									<div align="center">
									<input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="10" maxlength="10" onblur="return buscarMaterialBD(this,1);" 
									onkeypress="return permite(event,'num_car');" value=""/>
									</div>			
								</td>
								<td>
									<div align="center">
									<input name="txt_existencia" type="text" readonly="readonly" class="caja_de_num" id="txt_existencia" size="15" maxlength="20" value="" />
									</div>			
								</td>
								<td>
									<div align="center">		     
									<input name="txt_unidadMedida" type="text" readonly="readonly" class="caja_de_num" id="txt_unidadMedida" size="15" maxlength="20" value=""/>
									</div>
								</td>		    
							</tr>
							<tr>
								<td><div align="center">Cant. Entrada</div></td>
								<td><div align="center">Costo Unidad</div></td>
								<td><div align="center">Moneda</div></td>
								<td>&nbsp;</td>		  		  	    
							</tr>
							<tr>
								<td align="center"><input name="txt_cantEntrada" type="text" class="caja_de_num" id="txt_cantEntrada" onkeypress="return permite(event,'num');" size="15" maxlength="20" /></td>								
								<td align="center">
									$<input name="txt_costoUnidad" type="text" class="caja_de_num" id="txt_costoUnidad" onchange="formatCurrency(value,'txt_costoUnidad');" 
									onkeypress="return permite(event,'num');" size="15" maxlength="20" />		  		
								</td>
								<td align="center">
									<select name="cmb_tipoMoneda" id="cmb_tipoMoneda" size="1" class="combo_box">
										<option value="">Moneda</option>
										<option value="PESOS">PESOS</option>
										<option value="DOLARES">DOLARES</option>
										<option value="EUROS">EUROS</option>
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3"><span id="mensaje" class="msje_correcto" style="visibility:hidden;">No Se Encontr&oacute; Ning&uacute;n Material</span></td>
							</tr>
							<tr>
								<td colspan="3" align="center">
									<input type="hidden" id="hdn_validar" name="hdn_validar" value="1"/>
									<input type="hidden" id="cmb_param" name="cmb_param" value="<?php echo $parametro;?>"/>
									<input type="submit" name="btn_agregarOtro" class="botones" value="Agregar Otro" onMouseOver="window.status='';return true"
									title="Agregar Material al Registro de la Entrada"/>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<?php if(isset($_SESSION['datosEntrada']) || isset($_POST["btn_agregarOtro"])){?>
									<input name="sbt_continuar" type="submit" value="Continuar" class="botones" title="Registrar Datos Complementarios de la Entrada" 
									onmouseover="window.status='';return true" onclick="hdn_validar.value=0"/>
									&nbsp;&nbsp;&nbsp;&nbsp;
									<?php }?>
									<input name="btn_Cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Regresar al Formulario de Entrada de Material" 
									onclick="location.href='frm_entradaMaterial.php?lmp=si'"/>
								</td>
							</tr>
						</table>
						</form>				  
						</fieldset>	
					
						<fieldset id="devolucion-equipo" class="borde_seccion">
							<legend class="titulo_etiqueta">Registrar Devoluci&oacute;n de Equipo de Seguridad</legend>
							<br />
							<p align="center">
								<input type="button" name="btn_registrar" onclick="location.href='frm_entradaEquipoSeguridad.php'" class="botones" value="Registrar" title="Registrar Material de Seguridad Devuelto"/>
							</p>
						</fieldset>
						
						<?php
						/**********SECCION DONDE SE GUARDAN LOS DATOS EN LA SESION PARA REGISTRAR LA ENTRADA************/
						//Agregar los datos de la entrada al arreglo y despues mostrarlos en la tabla que aparece a la derecha
						if(isset($_POST["btn_agregarOtro"])){			
							//Quitar la coma en el costo unitario del material, para poder realizar la operaciones requeridas
							$txt_costoUnidad=str_replace(",","",$txt_costoUnidad);
							//Pasar la unidad de Medida a la variable Unidad para no modificar el proceso de guardado
							$unidad = $txt_unidadMedida;
							//Si ya esta definido el arreglo $datosEntrada en la SESSION, entonces agregar el siguiente registro a �l
							if(isset($_SESSION['datosEntrada'])){						
								//Verificar que el registro que se quiere agregar no exista en el arreglo
								if(!verRegDuplicado($datosEntrada, "clave", $txt_clave)){
									//Si el cmb_material se encuentra vacio, quiere decir que los datos del Material fueron extraidos mediante AJAX con la clave del mismo
									//con la funcion obtenerDato, extraer los datos del Material
									$nombre = obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $txt_clave);
									//Guardar los datos en el arreglo
									$datosEntrada[] = array("clave"=>$txt_clave, "nombre"=>$nombre, "unidad"=>$unidad,"existencia"=>$txt_existencia, 
															"cantEntrada"=>$txt_cantEntrada, "costoUnidad"=>$txt_costoUnidad, "costoTotal"=>($txt_cantEntrada*$txt_costoUnidad),
															"tipoMoneda"=>$cmb_tipoMoneda);						
								}
							}
							//Si no esta definido el arreglo $datosEntrada en la SESSION definirlo y agregar el primer registro
							else{
								//Si el cmb_material se encuentra vacio, quiere decir que los datos del Material fueron extraidos mediante AJAX con la clave del mismo
								//con la funcion obtenerDato, extraer los datos del Material
								$nombre = obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $txt_clave);
								//Crear el arreglo con el primer registro
								$datosEntrada = array(array("clave"=>$txt_clave, "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>$txt_existencia, 
															"cantEntrada"=>$txt_cantEntrada,"costoUnidad"=>$txt_costoUnidad, "costoTotal"=>($txt_cantEntrada*$txt_costoUnidad),
															"tipoMoneda"=>$cmb_tipoMoneda));
								//Guardar el arreglo en la SESSION
								$_SESSION['datosEntrada'] = $datosEntrada;	
								//Crear el ID de la Entrada de Material
								//$_SESSION['id_entrada'] = obtenerIdEntrada();
							}
						}
						
						/**********SECCION DONDE SE MUESTRA EL ARREGLO DE SESION CON LOS MATERIALES A LOS QUE SE LES DARA ENTRADA************/
						//Verificar que el arreglo de datos haya sido definido en la SESSION
						if(isset($_SESSION['datosEntrada'])){
							?>
							<div id="material-agregado">
								<p align="center" class="titulo_etiqueta">Registro de la Entrada de Material</p>
								<?php
									mostrarRegistros($datosEntrada,1);
								?>
							</div>
						<?php	
						}
					}
					//Agregar a la Session los datos de la Entrada solo la primer vez
					if (!isset($_SESSION["origen"])){
						$_SESSION['origen'] = "Compra Directa";	
						$_SESSION['no_origen'] = "N/A";
						$_SESSION["bd"] = "CompraDirecta";
					}
				break;
				case "id_orden_compra":
					$ordenCompra=$_POST["cmb_opciones"];
					//Agregar a la Session los datos de la Entrada
					$_SESSION['origen'] = "Orden de Compra";	
					$_SESSION['no_origen'] = $ordenCompra;
					$_SESSION["bd"] = "bd_almacen";
					//Mostrar los materiales incluidos en la Orden de Compra	
					mostrarMaterialesOC($ordenCompra,"bd_almacen");
				break;
				case "id_requisicion":
					$base=obtenerNomBD($_POST["cmb_opciones"]);
					$requisicion=$_POST["txt_req"];
					if($_POST["hdn_valPal"]=="stock"){
						//Agregar a la Session los datos de la Entrada
						$_SESSION['origen'] = "Requisicion";	
						$_SESSION['no_origen'] = $requisicion;
						$_SESSION["bd"] = $base;
						//Mostrar los materiales incluidos en la Requisicion
						mostrarMaterialesReq($requisicion,$base);
					}else{
						//Mostrar los materiales incluidos en la Requisicion
						mostrarMaterialesReqPaileria($requisicion);
					}
				break;
				case "pedido":
					//Obtener el numero de Pedido
					$pedido=$_POST["txt_pedido"];
					//Obtener la Requisicion asociada al Pedido
					$req=obtenerDato("bd_compras","pedido","requisiciones_id_requisicion","id_pedido",$pedido);
					if($_POST["hdn_valPal"]=="stock"){
						//Agregar a la Session los datos de la Entrada
						$_SESSION['origen'] = "Pedido";	
						$_SESSION['no_origen'] = $pedido;
						$_SESSION['no_req'] = $req;
						$_SESSION["bd"] = "bd_compras";
						//Mostrar los materiales incluidos en el Pedido
						mostrarMaterialesPedido($pedido);
					}
					else{
						//Mostrar los materiales incluidos en el Pedido de Paileria
						mostrarMaterialesPedidoPaileria($pedido);
					}
				break;
			}
		}
		//Verificar si en el GET esta definido el parametro IN y verificar su valor, este parametro se envia desde los formularios de cargarMateriales de la OC y la REQ
		if(isset($_GET["in"]) || isset($_GET["ped"])){
			//Variable que indica el origen de la Entrada
			if(isset($_GET["in"])){
				$in=$_GET["in"];
				switch($in){
					case "oc":
						//Cargar los Materiales al arreglo de Sesion
						cargarDatosArr();
						echo "<meta http-equiv='refresh' content='0;url=frm_entradaMaterial3A.php?in=oc'>";		
					break;
					case "req":
						//Cargar los Materiales al arreglo de Sesion
						cargarDatosArr();
						//echo "<meta http-equiv='refresh' content='0;url=frm_entradaMaterial3A.php?in=req'>";		
					break;
					case "reqPai":
						$req=$_POST["hdn_req"];
						//Cargar los Materiales al arreglo de Sesion para darles Entrada/Salida
						cargarDatosArrReqPai();
						echo "<meta http-equiv='refresh' content='0;url=frm_entradaMaterial3A.php?in=reqPaiES&req=$req'>";
					break;
				}
			}
			if(isset($_GET["ped"])){
				//Cargar los Materiales al arreglo de Sesion
				cargarDatosArr();
				//echo "<meta http-equiv='refresh' content='0;url=frm_entradaMaterial3A.php?ped'>";
			}
		}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
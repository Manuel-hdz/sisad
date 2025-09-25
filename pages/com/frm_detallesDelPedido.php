<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion html xmlns="http://www.w3.org/1999/xhtml"><?php
	
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
		//Este archivo realiza el registro de los detalles de Pedido
		include ("op_registrarPedido.php");?>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="../../includes/validacionCompras.js"></script>
		<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
		<script type="text/javascript" src="includes/ajax/validarEstado.js"></script>
		<!-- se anexa este archivo para obtener las funciones necesarias para el control de costos -->
		<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js"></script>
		<!-- Esta linea se agrego para llamar los scripts creados para cargar datos en el combo txa_descripcion y llenar sus detalles -->
		<script type="text/javascript" src="includes/ajax/cargarComboDescripcion.js"></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
		<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
		<script type="text/javascript" src="../../includes/ajax/cargarRequisicion.js"></script>
		<script type="text/javascript" language="javascript">
			setTimeout("cargarRequisicion(1);", 500);
		</script>
		<?php 
		
		if(isset($_GET["depto"])) {?>
		//Esta linea colocara el cursor en el campo de desripci�n cada vez que se cargue la pagina
		setTimeout("document.getElementById('ckb_pieza1').focus()",500);<?php 
		}
		else{?>
		//Esta linea colocara el cursor en el campo de desripci�n cada vez que se cargue la pagina
		setTimeout("document.getElementById('txa_descripcion').focus()",500);<?php 
		}?>
		</script>

		<style type="text/css">
			#titulo-detalle {
				position: absolute;
				left: 30px;
				top: 146px;
				width: 194px;
				height: 23px;
				z-index: 11;
			}

			#tabla-pedido-detalles {
				position: absolute;
				left: 30px;
				top: 525px;
				width: 900px;
				height: 150px;
				z-index: 12;
				overflow: scroll;
			}

			#botones {
				position: absolute;
				left: 30px;
				top: 680px;
				width: 900px;
				height: 37px;
				z-index: 13;
			}

			#detalles_pedido {
				position: absolute;
				left: 30px;
				top: 190px;
				width: 940px;
				height: 440px;
				z-index: 15;
				overflow: auto;
			}

			#tabla-pedido {
				position: absolute;
				left: 30px;
				top: 190px;
				width: 900px;
				height: 308px;
				z-index: 16;
			}

			#lista-proveedores {
				position: absolute;
				left: 540px;
				top: 210px;
				width: 321px;
				height: 104px;
				z-index: 17;
			}
		</style>
	</head>

	<body>

		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-detalle">Detalles del Pedido</div><?php
	
	//Si no viene la variable $hdn_numero en el POST, significa que hay que iniciar a registrar manualmente las Partidas del Pedido
	if (!isset($_POST["hdn_numero"])){
		if (isset($_POST["btn_registrar"])){
			$pedido = $_POST["txt_pedido"];
			$cont = $_POST["txt_partida"]+1;
			$descto=$_POST["txt_descto"];
			//Se comprueba la partida actual en caso de de ser la segunda partida se guarda en la sesion la requisicion
			//para que esta no se este modifcando
			if($cont <=2){
				$_SESSION['aux_req']=$_POST["txt_requisicion"];
			}
			$req = $_SESSION['aux_req'];
			//////////////////////////////////
		}
		
		else{
			//Se reinician las sesiones de estas dos variables cuando se carga el modulo para que estas no generen conflictos
			unset($_SESSION["aplica"]);
			unset($_SESSION["aux_req"]);
			/////////////////////////////
			
			$pedido = obtenerIdPedido();
			$req = "";
			$cont = 1;
			$descto="0.00";

			//Si los datos del pedido estan en la SESSION, eso indica que el numero de req, el id pedido y el numero de partida tambien lo estan.
			if(isset($_SESSION['detallespedido'])){
				$pedido=$_SESSION['detallespedido'][0]['id_pedido'];
				$req = $_SESSION['detallespedido'][0]['req'];
				//Obtener la Partida del Ultimo registro agregado al Detalle del Pedido y sumarle 1
				$cont = $_SESSION['detallespedido'][count($_SESSION['detallespedido'])-1]['partida'] + 1;
			}

		}
		//Verificamos si se ha presionado el boton Registrar Detalle Pedido, de ser as� realizar el proceso de carga de datos en el Arreglo de Session
		if(isset($_POST['btn_registrar'])){
			$txt_preciouni=str_replace(",","",$txt_preciouni);
			//Obtener el valor a descontar
			$descuento=$txt_preciouni*($txt_descto/100);
			//Reasignar al precio unitario el valor con el descuento incluido
			$txt_preciouni=$txt_preciouni-$descuento;
			//Recalcular el importe
			$txt_importe=$txt_preciouni*$txt_cantidad;
			//Si ya esta definido el arreglo $detallespedido, entonces agregar el siguiente registro a el
			if(isset($_SESSION['detallespedido'])){
				if(!verRegDuplicadoArr($_SESSION['detallespedido'],"partida",$txt_partida)){
					//Guardar los datos en el arreglo
					$detallespedido[] = array("partida"=>$txt_partida,"unidad"=>$cmb_unidad,"cantidad"=>$txt_cantidad,
					"descripcion"=>strtoupper($txa_descripcion),"equipo"=>$cmb_equipos,"precioU"=>$txt_preciouni,"importe"=>$txt_importe,
					"pedido"=>strtoupper($txt_pedido),"requisicion"=>$txt_requisicion);
				}
			}
			//Si no esta definido el arreglo $datosEntrada definirlo y agregar el primer registro
			else{
				//Guardar los datos en el arreglo
				$detallespedido = array(array("partida"=>$txt_partida,"unidad"=>$cmb_unidad,"cantidad"=>$txt_cantidad,
				"descripcion"=>strtoupper($txa_descripcion),"equipo"=>$cmb_equipos,"precioU"=>$txt_preciouni,"importe"=>$txt_importe,"pedido"=>strtoupper($txt_pedido),
				 "requisicion"=>$txt_requisicion, "id_pedido"=>$pedido, "req"=>$req));
				$_SESSION['detallespedido'] = $detallespedido;
			}
		}//Fin del IF que comprueba la variable btn_registrar?>

		<fieldset class="borde_seccion" id="tabla-pedido" name="tabla-pedido">
			<legend class="titulo_etiqueta">Registrar Detalles del Pedido</legend>
			<form id="frm_detallePedido" name="frm_detallePedido" method="post"
				onsubmit="return valFormRegistrarDetallePedido(this)" action="">
				<table width="100%" cellpadding="5" class="tabla_frm">
					<tr>
						<td width="15%">
							<div align="right">Pedido</div>
						</td>
						<td width="30%">
							<input name="txt_pedido" id="txt_pedido" type="text" class="caja_de_texto"
								readonly="readonly" size="10" maxlength="10" value="<?php echo $pedido;?>" /></td>
						<td width="15%">
							<div align="right">*Descripcion</div>
						</td>
						<td width="40%">
							<!--<textarea name="txa_descripcion" id="txa_descripcion" onkeypress="return permite(event,'num_car', 0);" cols="50" rows="3"
					onkeyup="return ismaxlength(this)" class="caja_de_texto" maxlength="300" tabindex="1"></textarea>-->

							<span id="datosDescripcion"
								onchange="cargarDetalles(txa_descripcion.value,txt_requisicion.value,'cmb_unidad',txt_cantidad.id)">
								<!--<input name="txt_solicitante" type="text" class="caja_de_texto" size="30" maxlength="60" onkeypress="return permite(event,'num_car');" />-->
								<select name="txa_descripcion" id="txa_descripcion" class="combo_box" tabindex="1">
									<option value="">Descripcion</option>
								</select>
							</span>
						</td>
					</tr>
					<tr>
						<td>
							<div align="right">*Requisicion</div>
						</td>
						<td>
							<?php
	            	if ( (!isset($_POST["txt_requisicion"])) && $req==""){?>
							<!--<input name="txt_requisicion" onkeypress="return permite(event,'num_car', 5);" id="txt_requisicion" type="text"
        	            class="caja_de_texto" size="12" maxlength="12" value="<?php echo $req;?>"
                        onblur="verificarEstado(this);" onchange="verificarDatoBD(this,'bd_compras','pedido','requisiciones_id_requisicion','id_pedido');" tabindex="2" />
						<span id="error" class="msj_error">Requisici&oacute;n Duplicada</span>-->
							<?php
					}
					else{ ?>
							<!--<input name="txt_requisicion" id="txt_requisicion" type="text" class="caja_de_texto" size="12"
                        readonly="readonly" value="<?php echo $req;?>" />--><?php
					} ?>

							<!--<select name="cmb_estadoRequisicion" id="cmb_estadoRequisicion" class="combo_box" onchange="cargarTablaRequisicion(this.value,1);">
              		<option value="">Requisici&oacute;n</option>
            	</select>-->
							<?php 
					$conn = conecta("bd_almacen");		
					$stm_sql = "SELECT DISTINCT id_requisicion FROM  requisiciones WHERE estado='enviada' ORDER BY id_requisicion";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
							<span id="datosRequisicion">
								<select name="txt_requisicion" id="txt_requisicion" class="combo_box"
									onchange="cargarDetalleReq(this.value,'txa_descripcion',txt_partida.value,'')"
									tabindex="2">
									<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value=''>Requisici&oacute;n</option>";
							do{
								echo "<option value='$datos[id_requisicion]'>$datos[id_requisicion]</option>";
							}while($datos = mysql_fetch_array($rs));?>
									<option value="No aplica">No aplica</option>
								</select>
							</span><?php
					}
					else{
						echo "<label class='msje_correcto'> No hay requisiciones pendientes</label>
							<input type='hidden' name='cmb_area' id='cmb_area'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);
					if((isset($_SESSION["aplica"]))){
						echo "<script type='text/javascript' language='javascript'>
								cargarDetalleReq('No aplica','txa_descripcion',$cont,'$req');
							  </script>";
					}
					else if ( (isset($_SESSION["aux_req"])) && $req!=""){
						echo "<script type='text/javascript' language='javascript'>
								document.getElementById('txt_requisicion').value='$req'; 
								cargarDetalleReq('$req','txa_descripcion');
								document.getElementById('txt_requisicion').disabled='disabled';
							  </script>";
					}
				?>
						</td>
						<td>
							<div align="right">*Cantidad</div>
						</td>
						<td>
							<input name="txt_cantidad" id="txt_cantidad" type="text"
								onkeypress="return permite(event,'num', 2)" class="caja_de_texto" size="5"
								maxlength="10"
								onchange="formatCurrency(value*txt_preciouni.value.replace(/,/g,''),'txt_importe');"
								tabindex="3" value="1" ondblclick="this.value='';" />
						</td>
					</tr>
					<tr>
						<td>
							<div align="right">Partida</div>
						</td>
						<td>
							<input name="txt_partida" id="txt_partida" type="text" class="caja_de_num"
								value="<?php echo $cont;?>" readonly="true" size="1" maxlength="3" />
						</td>
						<td>
							<div align="right">*Precio Unitario </div>
						</td>
						<td>
							$<input name="txt_preciouni" type="text" class="caja_de_texto" id="txt_preciouni" onchange="formatCurrency(value,'txt_preciouni');
                   	formatCurrency(value.replace(/,/g,'')*txt_cantidad.value,'txt_importe');"
								onkeypress="return permite(event,'num', 2)" size="15" maxlength="20" tabindex="4" />
						</td>
					</tr>
					<tr>
						<td>
							<div align="right">*Unidad</div>
						</td>
						<td><?php 
                    $conn = conecta("bd_compras");//Conectarse con la BD de Compras
					//Ejecutar la Sentencia para Obtener las Unidades de Medida de la Tabla de Detalles del Pedido de la BD de Compras
					$rs_unidades = mysql_query("SELECT DISTINCT unidad FROM detalles_pedido ORDER BY unidad");
					if($unidades=mysql_fetch_array($rs_unidades)){?>
							<select name="cmb_unidad" id="cmb_unidad" class="combo_box"
								onchange="agregarNvaUnidad(this)" tabindex="5">
								<option value="">Unidad</option><?php
						//Colocar las unidades encontradas
						do{
							echo "<option value='$unidades[unidad]'>$unidades[unidad]</option>";
							
						}while($unidades=mysql_fetch_array($rs_unidades));?>
								<option value="NUEVA">Agregar Nueva</option>
							</select><?php
					}
					else
						 echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Unidades Registradas</label>";
					mysql_close($conn);	
					?>
						</td>
						<td>
							<div align="right">Importe</div>
						</td>
						<td>$<input name="txt_importe" type="text" class="caja_de_texto" id="txt_importe" size="15"
								maxlength="20" readonly="true" /></td>
					</tr>
					<tr>
						<td>
							<div align="right">Equipo</div>
						</td>
						<td><?php 
					$conn = conecta("bd_mantenimiento");//Conectarse con la BD de Mantenimiento
					//Ejecutar la Sentencia para Obtener los Equipos de la Base de Datos de Mantenimiento
					$rs_equipos = mysql_query("SELECT DISTINCT id_equipo FROM equipos WHERE estado='ACTIVO' ORDER BY id_equipo");
					if($equipos=mysql_fetch_array($rs_equipos)){?>
							<select name="cmb_equipos" id="cmb_equipos" class="combo_box" tabindex="6"
								onchange="agregarNvoEquipo(this);">
								<option value="">Equipos</option><?php
						//Colocar las unidades encontradas
						do{
							echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
							
						}while($equipos=mysql_fetch_array($rs_equipos));?>
								<option value="NUEVO">Equipo Nuevo</option>
							</select><?php
					}
					else
						 echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Equipos Registrados</label>";
					mysql_close($conn);
				?>
						</td>
						<td align="right">Proveedor</td><?php
				if(!isset($_POST['txt_nomProveedor'])){?>
						<td>
							<input type="text" name="txt_nomProveedor" id="txt_nomProveedor"
								onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');" value="" size="50"
								maxlength="80" onkeypress="return permite(event,'num_car', 0);" tabindex="7" />
							<div id="lista-proveedores">
								<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
									<img src="../../images/upArrow.png"
										style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
									<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
								</div>
							</div>
						</td><?php
				}
				else if(isset($_POST['txt_nomProveedor'])){?>
						<td>
							<input type="text" name="txt_nomProveedor" id="txt_nomProveedor" class="caja_de_texto"
								readonly="readonly" value="<?php echo $_POST['txt_nomProveedor']; ?>" size="50"
								maxlength="80" />
						</td><?php
				}?>
					</tr>
					<tr>
						<td align="right">*Descuento</td>
						<td>
							<input type="text" name="txt_descto" id="txt_descto" class="caja_de_num"
								value="<?php echo number_format($descto,2,".",",");?>" size="6" maxlength="6"
								onkeypress="return permite(event,'num_car', 0);"
								onchange="validarDescto(this);formatCurrency(value.replace(/,/g,''),'txt_descto');"
								<?php if(isset($_POST["btn_registrar"])) echo " readonly='readonly'";?> />%
						</td>
					</tr>
					<tr>
						<td colspan="4"><strong>*Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
					</tr>
					<tr>
						<td colspan="4" align="center">
							<input type="hidden" name="hdn_iva" id="hdn_iva" />
							<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
							<input name="btn_registrar" type="submit" class="botones_largos" id="btn_registrar"
								value="Registrar Detalle Pedido" onmouseover="window.status='';return true;"
								title="Registrar Detalle del Pedido" tabindex="7" />
							&nbsp;
							<?php
					if (isset($_SESSION["detallespedido"])){?>
							<input name="btn_finalizar" type="button" class="botones" value="Finalizar"
								onmouseover="window.status='';return true;" onclick="verIVA();"
								title="Registrar Informaci&oacute;n Complementaria" tabindex="8" />
							&nbsp;<?php
					}?>
							<input name="rst_limpiar" type="reset" class="botones" value="Limpiar"
								title="Limpiar Formulario" tabindex="9"
								<?php if(!isset($_POST["btn_registrar"])) echo " onclick='txt_descto.readOnly=false;'";?> />&nbsp;
							<input name="btn_cancelar" type="button" class="botones" id="boton-cancelar"
								value="Cancelar" title="Regresar al Men&uacute; de Pedidos"
								onclick="location.href='menu_pedidos.php'" tabindex="10" />
						</td>
					</tr>
				</table>
			</form>
		</fieldset><?php

		//Si esta inicializado el arreglo de Sesion, mostrar los conceptos agregados
		if (isset($_SESSION["detallespedido"])){?>
		<div id="tabla-pedido-detalles" class="borde_seccion2"><?php
				mostrarDetallesPedido($detallespedido);?>
		</div><?php
		}
	}//Fin del IF que comprueba que hdn_numero no se encuentre declarado

	//Si hdn_numero estan declarado, se llego aqui desde una requisicion, mandar llamar la funcion que muestra los datos para completarse
	else{
		//Comprobar si detallespedido esta declarado, si es asi, quitarlo de la sesion
		if(isset($_SESSION["detallespedido"]))
			unset($_SESSION["detallespedido"]);

		if($_POST["hdn_estado"]!="PEDIDOS"){?>
		<form name='frm_pedido_requisicion' onSubmit="return valFormDetallesPedido1(this);" method='post'
			action='frm_registrarPedido.php'>
			<div id="detalles_pedido" class="borde_seccion2"><?php
				?>
				<input type="hidden" name="hdn_editable" id="hdn_editable" value="NO">
				<?php
					agregarDetallesPedido(); ?>
			</div>

			<div id='botones' align='center'>
				<?php //Declaramos este Hidden que identificara su el Precio ya incluye IVA o NO?>
				<input type="hidden" name="hdn_iva" id="hdn_iva" />
				<input name="btn_continuar" type="submit" class="botones" value="Continuar"
					onmouseover="window.status='';return true;" title="Registrar Pedido" />
				&nbsp;
				<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Los Campos"
					onclick="restablecerCamposPedidoRead(cant_ckbs);" />&nbsp;<?php 
					if(isset($_GET["origen"])){
						if (isset($_POST["txt_fechaIni"])){?>
				<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST["txt_fechaIni"];?>" />
				<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST["txt_fechaFin"];?>" /><?php
							$bus = "fecha";
						}
						else{?>
				<input type="hidden" name="cmb_estadoBuscar" value="<?php echo $_POST["cmb_estadoBuscar"];?>" /><?php
							$bus="combo";
						}?>
				<input type="hidden" name="rdb_req" id="rdb_req" value="<?php echo $_POST["hdn_numero"]?>" />
				<input type="hidden" name="origen" id="origen" value="detallePedido" />
				<input type="hidden" name="cmb_estado<?php echo $_POST["hdn_numero"]?>" value="" />
				<input name="btn_cancelar" type="button" class="botones" value="Regresar"
					title="Cancelar Registro del Pedido"
					onclick="frm_pedido_requisicion.action='frm_consultarRequisiciones.php?depto=<?php 
						echo $_GET["deptto"];?>&req=<?php echo $_POST["hdn_numero"]?>&bus=<?php echo $bus;?>'; frm_pedido_requisicion.submit()" />
				&nbsp;
				<input name="btn_verPDF" type="button" class="botones" value="Ver PDF"
					title="Ver Archivo PDF de la Requisici�n Seleccionada" onmouseover="window.status='';return true"
					onclick="window.open('../../includes/generadorPDF/requisicion.php?id=<?php echo $_POST["hdn_numero"]?>','_blank',
						'top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no,location=no, directories=no')" />
				<?php
					}//Cierre if(isset($_GET["origen"])) 
					else{?>
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar"
					title="Cancelar Registro del Pedido" onclick="location.href='menu_requisiciones.php'" />
				&nbsp;
				<input name="btn_verPDF" type="button" class="botones" value="Ver PDF"
					title="Ver Archivo PDF de la Requisici�n Seleccionada" onmouseover="window.status='';return true"
					onclick="window.open('../../includes/generadorPDF/requisicion.php?id=<?php echo $_POST["hdn_numero"]?>','_blank',
						'top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no,location=no, directories=no')" />
				<?php 
					}?>
			</div>
		</form><?php
		}//Cierre if($_POST["hdn_estado"]!="PEDIDO")
		else{?>
		<script type='text/javascript' language='javascript'>
			setTimeout("alert('El Pedido de la Requisici�n seleccionada ya se Registr�')", 0);
		</script><?php
			
			echo "<meta http-equiv='refresh' content='1;url=menu_requisiciones.php'>";
		}
	}
	
	//EL Siguiente Formulario nos ayudara a mandar los datos necesarios para registrar los precios de una Requisicion Existente, la cual se accesa
	//desde esta pagina y no desde consultar Requisiones?>
		<form name="frm_datosRequisicionPedido" action="" method="post">
			<input type="hidden" name="hdn_numero" id="hdn_numero" value="" />
			<input type="hidden" name="hdn_bd" id="hdn_bd" value="" />
			<input type="hidden" name="hdn_estado" id="hdn_estado" value="" />
		</form>

	</body>
	<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

	</html>
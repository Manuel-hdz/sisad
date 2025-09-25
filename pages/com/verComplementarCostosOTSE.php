<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 19/abril/2012
	  * Descripción: Este archivo contiene se utiliza para complementar el costo de las OTSE en Compras
	  **/ 

	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("op_consultarOTSE.php"); ?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>	
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js" ></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js"></script>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_proveedores_otse.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;						
		//-->
		
		function reiniciarSubC(){
			//Obtener la referencia del comboBox que será cargado con los datos
			objeto = document.getElementById("cmb_subcuenta");					
			//Vaciar el comboBox Antes de llenarlo
			objeto.length = 0;
			//Agregar el Primer Elemento Vacio
			objeto.length++;
			objeto.options[objeto.length-1].text = "SubCuentas";
			objeto.options[objeto.length-1].value = "";
		}
	</script>	
	
	<style type="text/css">
		<!--
		#form-registrarCostos {position:absolute; left:20px; top:10px; width:780px; height:570px; z-index:1; overflow:auto; }
		#mensaje-img {position:absolute; left:243px; top:141px; width:376px; height:369px; z-index:2; }
		#res-spider2 { position:absolute; width:10px; height:10px; z-index:16; }
		-->
    </style>
</head>
<body><?php

	//Cuando se entra a la página, desplegar los registros de la OTSE para registrar los costos
	if(!isset($_POST['sbt_guardar'])){
		session_start();
		//Obtener ID de la Orden de la URL
		$idOrden = $_GET['idOrden'];
		$noFactura = obtenerDato("bd_mantenimiento","orden_servicios_externos","factura","id_orden",$idOrden);
		$usuarioCompras = obtenerDato("bd_usuarios","credenciales","nombre","usuarios_usuario",$_SESSION['usr_reg']); 
		$proveedor = obtenerDato("bd_mantenimiento","orden_servicios_externos","nom_proveedor","id_orden",$idOrden);
		$rfcProveedor = obtenerDato("bd_compras","proveedores","rfc","razon_social",$proveedor);
		$direccion = obtenerDato("bd_mantenimiento","orden_servicios_externos","direccion","id_orden",$idOrden);
		$repProveedor = obtenerDato("bd_mantenimiento","orden_servicios_externos","rep_proveedor","id_orden",$idOrden);
		?>
								
		<div class="borde_seccion2" id="form-registrarCostos" name="form-registrarCostos" align="center">
		<?php //Dejar el atributo 'action' vacio para que al momento de guardar la url se pase tal cual ya que ahí viene el ID de la OTSE que esta siendo complementada ?>		
		<form onSubmit="return valFormRegCostosActividades(this);" name="frm_regCostoActividades" method="post" action=""><?php
			mostrarActividades($idOrden);?>			
			<table width="100%" cellpadding="5" cellspacing="5">
				<tr>
					<td colspan="4">
						<div align="left">Moneda</div>
						<select name="txt_moneda" id="txt_moneda" required="required">
							<option value="">MONEDA</option>
							<option value="PESOS">PESOS</option>
							<option value="DOLARES">DOLARES</option>
							<option value="EUROS">EUROS</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<div align="left">Proveedor</div>
						<input name="txt_proveedor" id="txt_proveedor" type="text" class="caja_de_texto" size="120" maxlength="100" autocomplete="off" required="required" onkeyup="lookupProv(this,'2');" value="<?php echo $proveedor ?>"/>
						<div id="res-spider2">
							<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
							</div>
						</div>
						<input type="hidden" name="hdn_rfc" id="hdn_rfc" value="<?php echo $rfcProveedor ?>"/>
					</td>			
				</tr>
				<tr>
					<td colspan="4">
						<div align="left">Direcci&oacute;n </div>
						<input type="text" name="txt_direccion" id="txt_direccion" class="caja_de_texto" size="120" value="<?php echo $direccion; ?>" readonly="readonly" required="required"/>
					</td>						
				</tr>
				<tr>
					<td colspan="2">
						<div align="left">Representante Proveedor</div>
						<input type="text" name="txt_repProveedor" id="txt_repProveedor" class="caja_de_texto" onkeypress="return permite(event,'num_car',1);" size="50" maxlength="60"
						value="<?php echo $repProveedor; ?>" autocomplete="off" required="required"/>
					</td>
					<td colspan="2">
						<div align="left">Representante Compras</div>
						<input type="text" name="txt_repCompras" id="txt_repCompras" class="caja_de_texto" onkeypress="return permite(event,'num_car',1);" size="50" maxlength="60"
						value="<?php echo $usuarioCompras; ?>" readonly="readonly"/>
					</td>
				</tr>
				<tr>
					<td>
						N&deg; Factura
						<br>
						<input type="text" name="txt_factura" id="txt_factura" class="caja_de_texto" onkeypress="return permite(event,'num_car', 0);" size="15" maxlength="20"
						value="<?php echo $noFactura; ?>" autocomplete="off" required="required"/>
					</td>
					<td>
						N&deg; Centro de Costos
						<br>
					<?php 
						$conn_rec = conecta("bd_recursos");		
						$stm_sql_rec = "SELECT * FROM control_costos ORDER BY descripcion";
						$rs_rec = mysql_query($stm_sql_rec);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos_rec = mysql_fetch_array($rs_rec)){?>
							<select name="cmb_con_cos" id="cmb_con_cos" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta'); reiniciarSubC()" required="required">
								<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
								echo "<option value=''>Control de Costos</option>";
								do{
									echo "<option value='$datos_rec[id_control_costos]'>$datos_rec[descripcion]</option>";
								}while($datos_rec = mysql_fetch_array($rs_rec));?>
							</select>
						<?php
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn_rec);
					?>
					<?php 
						echo "<input type='hidden' name='hdn_control' id='hdn_control' value=''/>";
						echo "<input type='hidden' name='hdn_cuentas' id='hdn_cuentas' value=''/>";
					?>
					</td>
					<td>
						N&deg; Cuenta
						<br>
						<span id="datosCuenta">
							<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box" onchange="cargarSubCuentas(cmb_con_cos.value,this.value,'cmb_subcuenta')" required="required">
								<option value="">Cuentas</option>
							</select>
						</span>
					</td>
					<td>
						N&deg; Subcuenta
						<br>
						<span id="datosSubCuenta">
							<select name="cmb_subcuenta" id="cmb_subcuenta" class="combo_box" required="required">
								<option value="">SubCuentas</option>
							</select>
						</span>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="4">
						<input type="submit" name="sbt_guardar" value="Guardar" class="botones" title="Guardar Registro" onmouseover="window.status='';return true"/>						
						&nbsp;&nbsp;
						<input type="button" name="btn_cerrar" value="Cerrar" class="botones" title="Cerrar Ventana de Registro" onclick="window.close();" />
					</td>			
				</tr>
			</table>
		</form>
		</div><?php		
	}//Cierre if(!isset($_POST['sbt_guardar']))
	else if(isset($_POST['sbt_guardar'])){
		//Guardar registros seleccionados
		$res = guardarCostoActividades();
		
		//Desplegar Mensaje de Registro Guardados con Exito
		if($res==1){?>
			<div id="mensaje-img">
				<img src="../../images/ok.png" width="376" height="369" />			
			</div><?php
		}
		else if($res==0){?>
			<div id="mensaje-img">
				<img src="../../images/error.png" width="376" height="369" />			
			</div><?php			
		}
				
				
		//Cerrar la Ventana despues de 3 segundos?>
		<script type="text/javascript" language="javascript">
			setTimeout("window.close();",3000);
		</script><?php
		
	}//Cierre else if(isset($_POST['sbt_guardar']))?>
	
</body>
</html>
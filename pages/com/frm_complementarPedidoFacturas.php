<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones que registran los Pedidos, en este caso, permite complementar los detalles de Pedido
		include ("op_recibirPedidos.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:285px; height:25px; z-index:11; }
		#tabla-complementar { position:absolute;left:30px; top:190px;width:480px;height:200px;z-index:12; }
		#calendario-pago {position:absolute; left:495px; top:278px; width:29px; height:25px; z-index:13; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Complementar Pedido</div>
	<?php 
	if (!isset($_POST["sbt_guardar"])){
	?>
		<form name="frm_complementarDatos" method="post" onsubmit="return valComplementarDatos(this);" action="frm_recibirPedidos.php">
			<fieldset class="borde_seccion" id="tabla-complementar" name="tabla-complementar">
				<legend class="titulo_etiqueta">Complementar los Datos del Pedido <?php echo $_POST["rdb_idPedido"];?> Factura <?php echo $_POST["txt_factura"];?></legend>
				<br/>
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<?php 
				//$fechaE=obtenerDatoEntrada("pedido","fecha_entrega","id_pedido",$_POST["rdb_idPedido"]);
				//$horaE=obtenerDato2("pedido","hora_entrega","id_pedido",$_POST["rdb_idPedido"]);
				//$costoTotal=obtenerDato2("pedido","total","id_pedido",$_POST["rdb_idPedido"]);
				
				echo "<input type='hidden' name='txt_pedido' id='txt_pedido' value='$_POST[rdb_idPedido]'/>";
				echo "<input type='hidden' name='txt_factura' id='txt_factura' value='$_POST[txt_factura]'/>";
				
				$fechaE=obtenerDatoEntrada("fecha_entrada",$_POST["txt_factura"],$_POST["rdb_idPedido"]);
				$horaE=obtenerDatoEntrada("hora_entrada",$_POST["txt_factura"],$_POST["rdb_idPedido"]);
				
				if(substr($_POST["rdb_idPedido"],0,3) == "PED"){
					$costoTotal=obtenerTotal(tieneIVA($_POST["rdb_idPedido"]),$_POST["txt_factura"],$_POST["rdb_idPedido"]);
				} else {
					$costoTotal = obtenerDato("bd_mantenimiento","orden_servicios_externos","costo_total","id_orden",$_POST["rdb_idPedido"]);
				}
				
				if ($fechaE==""){
					$fechaE=date("d/m/Y");
					$horaE=date("h:i");
					$mer=date("A");
				}
				else{
					//Extraer la Fecha y convertirla de formato de MySQl a formato legible para usuario
					$fechaE=modFecha($fechaE,1);
					//Extraer la Hora y modificarla de Formato 24 Hrs a 12 Hrs
					$horaE=modHora($horaE);
					//Convertir el Meridiano a Mayusculas
					$mer=strtoupper(substr($horaE,-2));
					//Extraer la Hora y los minutos para colocarlos en el formulario
						$horaE=substr($horaE,0,5);
					//Dividir la Hora para verificar la seccion de Horas exclusivamente
					$dividirHora=explode(":",$horaE);
					//Verificar si la hora es Menor a 10, para ver hasta donde se debe recortar la hora
					if($dividirHora[0]<10)
						$horaE="0".substr($horaE,0,4);
					/*?>
						<script type="text/javascript" language="javascript">
						setTimeout("mostrarAviso()",1000);
						function mostrarAviso(){
							alert("AVISO:\nAlmacén Recibió el Pedido el <?php echo $fechaE;?> a las <?php echo $horaE." ".$mer;?>");
						}
						</script>
					<?php*/
				}
				?>
					<tr>
						<td>
							Fecha Almacen
						</td>
						<td>
							<input type="text" name="txt_fechaE" id="txt_fechaE" size="10" readonly="true" value="<?php echo $fechaE; ?>" />
						</td>
						<td>
							Hora Almacen
						</td>
						<td>
							<input type="text" name="txt_horaE" id="txt_horaE" size="10" readonly="true" value="<?php echo $horaE.' '.$mer; ?>" class="caja_de_num"/>
						</td>
					</tr>
					<tr>
						<td>
							Cantidad
						</td>
						<td>
							$&nbsp;<input type="text" name="txt_costoTotal" id="txt_costoTotal" class="caja_de_num" onkeypress="return permite(event,'num',0);" 
							maxlength="10" size="10" autocomplete="off" required="required"/ value="<?php echo number_format($costoTotal,2,".",",");?>" onChange="formatCurrency(this.value.replace(/,/g,''),this.name);">
						</td>
						<!--
						<td>
							Forma de Pago
						</td>
						<td>
							<select name="cmb_formaPago" id="cmb_formaPago" class="combo_box">
								<option value="EFECTIVO">EFECTIVO</option>
								<option value="ELECTRONICA">EL&Eacute;CTRONICA</option>                                   
								<option value="CHEQUE">CHEQUE</option>
							</select>
						</td>
						-->
						<input type="hidden" id="cmb_formaPago" name="cmb_formaPago" value="NINGUNA" />
						<td>
							Fecha de Entrega
						</td>
						<td>
							<input type="text" name="txt_fechaP" id="txt_fechaP" size="10" readonly="true" value="<?php echo date("d/m/Y");?>"/>
						</td>
					</tr>
					<tr>
						<td>
							Factura
						</td>
						<td>
							<input type='text' name='txt_factura_rec' id='txt_factura_rec' value='<?php echo $_POST['txt_factura']; ?>' maxlength="20" required="required"/>
						</td>
					</tr>
					<tr>
						<td colspan="4" align="center">
							<input type="submit" name="sbt_guardarRecibido" value="Guardar" class="botones" onmouseover="window.status='';return true;" 
							title="Guardar Informaci&oacute;n"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="reset" type="reset" class="botones" title="Restablecer Datos del Formulario" value="Limpiar" />
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" name="btn_regresar" value="Regresar" class="botones" onclick="location.href='frm_recibirPedidos.php'"
							title="Regresar"/>
						</td>
					</tr>
				</table>
			</fieldset>
		</form>
		<?php if ($fechaE==""){?>
		<div id="calendario-entrega">
	   		<input name="calendario_ini" type="image" id="calendario_ini" onclick="displayCalendar(document.frm_complementarDatos.txt_fechaE,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<?php }?>
		<div id="calendario-pago">
    		<input name="calendario_fin" type="image" id="calendario_fin" onclick="displayCalendar(document.frm_complementarDatos.txt_fechaP,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
	<?php 
	}else
		echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>recibido";
		//SI ya esta definido el boton de guardar, enviar al proceso que guarda los datos complementarios
		//complementarDetalles();
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
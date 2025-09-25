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
		//Este archivo contiene las funciones para mostrar la informacion del pedido seleccionado
		include ("op_consultarPedido.php");
		//Este archivo contiene las funciones que registran los Pedidos, en este caso, permite complementar los detalles de Pedido
		include ("op_registrarPedido.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<?php /*Funciones para Paginar Tablas*/?>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
			$("#tabla-resultadosPedidos").dataTable({
				"sPaginationType": "scrolling"
			});
	});
	</script>
	<?php /*Fin de Funciones para Paginar Tablas*/?>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:285px; height:25px; z-index:11; }
		#botones { position:absolute; left:30px; top:640px; width:974px; height:25px; z-index:15;}
		#pedidos{position:absolute; left:25px; top:190px; width:940px; height:400px; z-index:15; overflow:scroll;}
		#tabla-complementar { position:absolute;left:30px; top:190px;width:450px;height:220px;z-index:12; }
		#calendario-entrega {position:absolute; left:297px; top:235px; width:29px; height:25px; z-index:13; }
		#calendario-pago {position:absolute; left:480px; top:336px; width:29px; height:25px; z-index:13; }
		#calendar-uno { position:absolute; left:225px; top:225px; width:30px; height:26px; z-index:17; }
		#calendar-dos { position:absolute; left:225px; top:258px; width:30px; height:26px; z-index:18; }
		-->
    </style>
	<?php /*Estilo Paginacion*/?>
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
	<?php /*Fin Estilo Paginacion*/?>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Complementar Pedido</div>	
	<?php 
		if (!isset($_POST["sbt_detalle"])&&!isset($_POST["sbt_guardar"])){
			if(isset($_POST["sbt_continuar"])){?>
				<form name="frm_complementarPedido" method="post" action="frm_complementarPedido.php">
					<div id="pedidos" class="borde_seccion2">
						<br>
						<?php $boton=mostrarPedidos(3);?>
					</div>	
					<div id="botones" align="center">
						<?php if ($boton){?>
						<input type="submit" name="sbt_detalle" value="Complementar" class="botones" onmouseover="window.status='';return true;"
						title="Complementar Datos del Pedido" disabled="disabled"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Seleccionar Otras Fechas" 
						onclick="location.href='frm_complementarPedido.php'" />
					</div>
				</form>
	<?php 
			}
			else{
			?>
				<form name="frm_fechasPedido" method="post" action="frm_complementarPedido.php">
					<fieldset class="borde_seccion" id="tabla-complementar" name="tabla-complementar">
					<legend class="titulo_etiqueta">Seleccionar Fechas del Pedido</legend>
					<br />
					<table class="tabla_frm" cellpadding="5" width="100%">
						<tr>
						  <td width="22%"><div align="right">Fecha Inicio</div></td>
						  <td width="78%"><input name="txt_fechaIni" type="text" class="caja_de_texto" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10" 
							maxlength="15" readonly=true width="90" /></td>
						</tr>
						<tr>
							<td><div align="right">Fecha Fin </div></td>
							<td><input name="txt_fechaFin" type="text" class="caja_de_texto" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15" 
							readonly=true width="90" /></td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
							<td colspan="2" align="center">
							<input type="submit" name="sbt_continuar" value="Continuar" class="botones" onmouseover="window.status='';return true;"
							title="Seleccionar Fechas del Pedido"/>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Men&uacute; de Pedidos" 
							onclick="location.href='menu_pedidos.php'" />
							</td>
						</tr>
					</table>
					</fieldset>
				</form>
				
				<div id="calendar-uno">
					<input type="image" name="iniRepProv" id="iniRepProv" src="../../images/calendar.png" 
					align="absbottom" width="25" height="25" border="0" onclick="displayCalendar (document.frm_fechasPedido.txt_fechaIni,'dd/mm/yyyy',this)" 
					onmouseover="window.status='';return true" />
				</div>
				
				<div id="calendar-dos">
					<input type="image" name="finRepProv" id="finRepProv" src="../../images/calendar.png" 
					align="absbottom" width="25" height="25" border="0" onclick="displayCalendar (document.frm_fechasPedido.txt_fechaFin,'dd/mm/yyyy',this)" 
					onmouseover="window.status='';return true" />
				</div>
			<?php
			}
		}
		else{
			if (!isset($_POST["sbt_guardar"])){
			?>
			<form name="frm_complementarDatos" method="post" onsubmit="return valComplementarDatos(this);">
				<fieldset class="borde_seccion" id="tabla-complementar" name="tabla-complementar">
				<legend class="titulo_etiqueta">Complementar los Datos del Pedido <?php echo $_POST["rdb_idPedido"];?></legend>
				<br/>
					<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
					<?php 
					$fechaE=obtenerDato2("pedido","fecha_entrega","id_pedido",$_POST["rdb_idPedido"]);
					$horaE=obtenerDato2("pedido","hora_entrega","id_pedido",$_POST["rdb_idPedido"]);
					$costoTotal=obtenerDato2("pedido","total","id_pedido",$_POST["rdb_idPedido"]);
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
						?>
							<script type="text/javascript" language="javascript">
							setTimeout("mostrarAviso()",1000);
							function mostrarAviso(){
								alert("AVISO:\nAlmacén Recibió el Pedido el <?php echo $fechaE;?> a las <?php echo $horaE." ".$mer;?>");
							}
							</script>
						<?php
					}
					?>
						<tr>
							<td width="105" align="right">Fecha de Entrega</td>
							<td width="260" colspan="3"><input type="text" name="txt_fechaE" id="txt_fechaE" size="10" readonly="true" value="<?php echo $fechaE; ?>"/></td>
						</tr>
						<tr>
							<td align="right">Hora de Entrega</td>
							<td colspan="3">
								<input type="text" name="txt_horaE" id="txt_horaE" size="5" onchange="formatHora(this,'cmb_hora');" maxlength="5"
                                onkeypress="return permite(event,'num',0);" value="<?php echo $horaE; ?>" class="caja_de_num"/>&nbsp;
								<select name="cmb_hora" id="cmb_hora" class="combo_box">
									<option value="AM"<?php if($mer=="AM") echo " selected='selected'";?>>a.m.</option>
									<option value="PM"<?php if($mer=="PM") echo " selected='selected'";?>>p.m.</option>
								</select>						
                             </td>
 						</tr>
						<tr>
							<td align="right">Estado</td>
							<td>
                            	<select name="cmb_estado" id="cmb_estado" class="combo_box" onchange="mostrarOcultar();habilitarEnviar(this.form);">
                                    <option value="NO PAGADO">NO PAGADO</option>
                                    <option value="PAGADO">PAGADO</option>
                                </select>
								<input type="hidden" id="hdn_control" name="hdn_control" value="<?php echo $fechaE;?>"/>
								<input type="hidden" id="hdn_fecha" name="hdn_fecha" value="<?php echo date("d/m/Y");?>"/>
							</td>
							<td align="right">Cantidad</td>
							<td>
								$<input type="text" name="txt_costoTotal" id="txt_costoTotal" class="caja_de_num" maxlength="10" onkeypress="return permite(event,'num',0);" 
								value="<?php echo number_format($costoTotal,2,".",",");?>" size="10" readonly="readonly"/>
                            </td>
						</tr>
						<tr>
							<td align="right">Forma de pago</td>
							<td>
                            	<select name="cmb_formaPago" id="cmb_formaPago" class="combo_box">
									<option value="EFECTIVO">EFECTIVO</option>
									<option value="ELECTRONICA">EL&Eacute;CTRONICA</option>                                   
									<option value="CHEQUE">CHEQUE</option>
                                </select>
							</td>
							<td align="right"><label id="fecha_pago" style="visibility:hidden">Fecha de pago</label></td>
							<td>
                                <input  type="text" name="txt_fechaP" id="txt_fechaP" size="10" readonly="true" value="" style="visibility:hidden"/>
                            </td>
						</tr>
						<tr>
							<td colspan="4" align="center">
                                <input type="hidden" name="hdn_pedido" value="<?php echo $_POST["rdb_idPedido"];?>"/>
                                <input type="submit" name="sbt_guardar" value="Guardar" class="botones" onmouseover="window.status='';return true;" 
                                title="Guardar Informaci&oacute;n" disabled="disabled"/>									
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="reset" type="reset" class="botones" title="Restablecer Datos del Formulario" value="Limpiar" onclick="fecha_pago.style.visibility='hidden';calendario_fin.style.visibility='hidden';txt_fechaP.style.visibility='hidden';"/>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="button" name="btn_regresar" value="Regresar" class="botones" onclick="location.href='frm_complementarPedido.php'"
                                title="Regresar a la Lista de Pedidos"/>
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
			<div id="calendario-pago" style="visibility:hidden">
    			<input name="calendario_fin" type="image" id="calendario_fin" onclick="displayCalendar(document.frm_complementarDatos.txt_fechaP,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
			</div>
	<?php 
			}else
				//SI ya esta definido el boton de guardar, enviar al proceso que guarda los datos complementarios
				complementarDetalles();
		}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
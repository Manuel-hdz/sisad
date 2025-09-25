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
		//Este archivo realiza el registro de los detalles de Pedido
		include ("op_registrarVenta.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	   <style type="text/css">
		<!--
		#titulo-registrarVenta { position:absolute; left:30px; top:146px;	width:153px; height:20px; z-index:11; }
		#registrar-venta {position:absolute;left:30px;top:190px;width:631px;height:236px;z-index:12;}
		#detalle-venta {position:absolute;left:30px;top:474px;width:877px;height:150px;z-index:13;overflow:scroll;}
		-->
       </style>
</head>
<body>	
	<?php
	
				
	//Verificamos que se haya pulsado el boton de registrar detalles de venta para proceder a cargar los datos en el arreglo de      sesion
	if(isset($_POST['sbt_registrar'])){
		//Si esta definido el arreglo
		if(isset($_SESSION['detalleVenta'])){
			if(!verRegDuplicadoArr($_SESSION['detalleVenta'],"partida",$txt_partida)){
				//Guardar los datos en el arreglo
				$detalleVenta[] = array("partida"=>$txt_partida,"unidad"=>strtoupper($txt_unidad),"cantidad"=>$txt_cantidad,
				"descripcion"=>strtoupper($txa_descripcion),"precio"=>$txt_precio,"importe"=>$txt_importe);
				$totalVenta += intval(str_replace(",","",$txt_importe));
			}
		}				
		else{//Si no esta definido el arreglo, definirlo
			//Crear el arreglo con los datos del Detalle de la Venta
			$detalleVenta = array(array("partida"=>$txt_partida,"unidad"=>strtoupper($txt_unidad),"cantidad"=>$txt_cantidad,
			"descripcion"=>strtoupper($txa_descripcion),"precio"=>$txt_precio,"importe"=>$txt_importe,
			"clave_venta"=>strtoupper($txt_claveVenta)));
			//Guardar los datos en la SESSION
			$_SESSION['detalleVenta'] = $detalleVenta;
			$_SESSION['totalVenta'] = intval(str_replace(",","",$txt_importe));
		}	
	}//Fin del if(isset($_POST['sbt_registrar']))
		
	
	//Verificar si existe el detalle de la venta en la SESSION y obtener los datos del Detalle de Venta	
	if (isset($_SESSION['detalleVenta'])){
		$venta = $_SESSION['detalleVenta'][0]['clave_venta'];
		$cont = $_SESSION['detalleVenta'][count($_SESSION['detalleVenta'])-1]['partida'] + 1;
	}
	else{
		$venta = obtenerIdVenta();
		$cont = 1;
	}
	?>
    
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrarVenta"> Registrar Venta </div>
	
	<fieldset class="borde_seccion" id="registrar-venta" name="registrar-venta">
	<legend class="titulo_etiqueta"> Registrar Detalles de la Venta</legend>	
	<form name="frm_detallesVenta" method="post" onsubmit="return valFormRegistrarDetalleVenta(this);" 
    action="frm_detallesVenta.php" >
        <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td><div align="right">Clave Venta </div></td>
                <td>
                    <input name="txt_claveVenta" id="txt_claveVenta" type="text" class="caja_de_texto"  value="<?php echo $venta; ?>" 
                    size="10" maxlength="10" readonly="readonly" />			</td>
                <td><div align="right">*Descripci&oacute;n</div></td>
                <td>
                    <textarea name="txa_descripcion" id="txa_descripcion" cols="30" rows="2" class="caja_de_texto" 
                    maxlength="120" onkeyup="return ismaxlength(this);"  onkeypress="return permite(event,'num_car',0);" ></textarea>                </td>
            </tr>
            <tr>
                <td><div align="right">Partida </div></td>
                <td>
                    <input name="txt_partida" id="txt_partida" type="text" class="caja_de_num" value="<?php echo $cont; ?>"
                    readonly="readonly" size="1" maxlength="3" /></td>
                <td><div align="right">*Precio Unitario </div></td>
                <td>
                    $<input name="txt_precio" type="text" class="caja_de_texto" id="txt_precio"
                    onchange="formatCurrency(value,'txt_precio');formatCurrency(value.replace(/,/g,'')*txt_cantidad.value,'txt_importe');"
                    onkeypress="return permite(event,'num', 2);" size="15" maxlength="20" />                </td>
            </tr>
            <tr>
                <td><div align="right">*Unidad</div></td>
                <td>
                    <input name="txt_unidad" id="txt_unidad" type="text" class="caja_de_texto" size="6" maxlength="10" onkeypress="return permite(event,'num_car',3)"/>                </td>
                <td><div align="right">Importe Total </div></td>
                <td>
                    $<input name="txt_importe"  type="text" class="caja_de_texto" id="txt_importe" size="15" maxlength="20"
                    readonly="readonly" />                </td>
            </tr>
            <tr>
                <td><div align="right">*Cantidad</div></td>
                <td>
                    <input name="txt_cantidad" id="txt_cantidad" type="text" class="caja_de_texto"
                    onchange="formatCurrency(value*txt_precio.value.replace(/,/g,''),'txt_importe');" size="5" 
                    maxlength="10"   onkeypress="return permite(event,'num',2);"/>                </td>
                <td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
            </tr>
            <tr>
                <td colspan="4" align="center">
                    <input name="sbt_registrar" type="submit" class="botones_largos" id="sbt_registrar" 
                    title="Registrar los Detalles de la Venta" onmouseover="window.status='';return true" 
                    value="Registrar Detalles Venta" />
                     <?php
                    if (isset($_SESSION["detalleVenta"])){?>
                        <input name="btn_finalizar" type="button" class="botones" value="Finalizar" onclick="location.href='frm_registrarVenta.php'" title="Complementar los Datos de la Venta" />
                        <?php } ?>
                        <input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
                        <input name="sbt_cancelar" type="button" value="Cancelar" class="botones" title="Cancelar" 
                        onclick="location.href='menu_ventas.php'" />                </td>        			
            </tr>
        </table>
    </form>
	</fieldset>

<?php
	//Si esta inicializado el arreglo de Sesion, mostrar los conceptos agregados
	if(isset($_SESSION['detalleVenta'])){?>
		<div id='detalle-venta' class='borde_seccion2'><?php
			mostrarDetallesVenta();
		?></div>
<?php
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>

</html>
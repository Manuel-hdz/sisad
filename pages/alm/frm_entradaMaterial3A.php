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
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>	
    <style type="text/css">
		<!--
		#titulo-entrada { position:absolute; left:30px; top:146px; width:180px; height:21px; z-index:11; }
		#form-datos-entrada { position:absolute; left:30px; top:190px; width:670px; height:290px; z-index:12; }
		#registro-material { position:absolute; left:31px; top:520px; width:950px; height:175px; z-index:14; overflow:auto;}
		#img-calendario { position:absolute; left:635px; top:260px; width:35px; height:32px; z-index:15; }
		#calendario {position:absolute; left:589px; top:259px; width:29px; height:25px; z-index:14; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-entrada">Entrada Material</div><?php 
	
	//Si la variable $cmb_provedor no esta definida en el arreglo $_POST, entonces mostrar el formulario que solicita los datos de Entrada del Material
	if(!isset($_POST['cmb_provedor'])){
		if(isset($_GET["PAI"])){
			//Crear el Arreglo de Sesion que permite cambiar el Estado de los Materiales del Pedido, se emplea de esta manera para hacer reuso de la funcion "actualizarDetallePedido()"
			$_SESSION['nomMaterialesPedido'] = array();
			$cantReg = $_POST["cant_ckbs"]-1;
			$cont = 1;
			while($cont<=$cantReg){
				if (isset($_POST["ckb_mat".$cont])){
					$nombre = $_POST["txt_material".$cont];
					//Guardar el nombre real de los materiales seleccionados del PEDIDO
					$_SESSION['nomMaterialesPedido'][] = $_POST["txt_material".$cont];
					$unidad = $_POST["txt_unidad".$cont];
					$cantEntrada = $_POST["txt_cant".$cont];
					$costoUnidad = $_POST["txt_cost".$cont];
					//Quitar la coma en el costo unitario del material, para poder realziar la operaciones requeridas.
					$costoUnidad=str_replace(",","",$costoUnidad);
		
					if($cont==1){
						$datosEntrada = array(array("clave"=>"�NOVALE", "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>"N/A", 
														"cantEntrada"=>$cantEntrada,"costoUnidad"=>$costoUnidad, "costoTotal"=>($cantEntrada*$costoUnidad)));
					}	
					else{
						$datosEntrada[] = array("clave"=>"�NOVALE", "nombre"=>$nombre, "unidad"=>$unidad, "existencia"=>"N/A", 
														"cantEntrada"=>$cantEntrada,"costoUnidad"=>$costoUnidad, "costoTotal"=>($cantEntrada*$costoUnidad));
					}
				}//Cierre if (isset($_POST["ckb_mat".$cont]))
				$cont++;
			}
			//Guardar el arreglo datosEntrada en una variable de Sesion que se enviar� al formulario para registrar la entrada de material
			$_SESSION['datosEntrada'] = $datosEntrada;
			$_SESSION['origen'] = "PEDIDO PAILERIA";
			$_SESSION['no_origen'] = $_GET["PAI"];
		}
		
		if(isset($_GET["in"]) && $_GET["in"]=="reqPaiES"){
			$_SESSION['origen'] = "REQUISICION PAILERIA";
			$_SESSION['no_origen'] = $_GET["req"];
		}
	?>
	
	<fieldset class="borde_seccion" id="form-datos-entrada" name="form-datos-entrada"> 		
	<legend class="titulo_etiqueta">Complementar Informaci&oacute;n de la Entrada de Material</legend>	
	<br>
	<form onSubmit="return verContFormDatosEntrada(this);" name="frm_datosEntrada" action="frm_entradaMaterial3A.php" method="post" >
	<?php 
		if(isset($_GET["PAI"]))
			echo "<input type='hidden' name='hdn_bandera'/>";
		if(isset($_GET["in"]) && $_GET["in"]=="reqPaiES")
			echo "<input type='hidden' name='hdn_banderaPaiES'/>";
	?>
	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
	  	  <td width="90"><div align="right">Proveedor</div></td>
	  	  	<td colspan="3"><?php 																									
				$proveedor = ""; 					
				if(isset($_GET['prov']))//La variable vendra en el arrelo GET cuando se registrete el material al Catalogo de Almacen desde Agregar Material
					$proveedor = $_GET['prov'];					
				else{//La variable vendra en el arreglo POST desde las opciones de compra directa y dar entreda a materiales registrados en el catalogo de almacen
					//Obtener la clave del ultimo elemento agregado al arreglo de datosEntrada
					$clave = $_SESSION['datosEntrada'][count($_SESSION['datosEntrada'])-1]['clave'];
					$proveedor = obtenerDato("bd_almacen","materiales", "proveedor", "id_material", $clave);						
				}
				$comprobar = cargarCombo("cmb_provedor","razon_social","proveedores","bd_compras","Proveedor",$proveedor);?>				
			</td>
		</tr>
        <tr>
	  	  	<td><div align="right">Origen</div></td>
	  	  	<td width="180">
				<?php
		  		//Si el origen es una Compra Directa, colocar en la SESSION la variable $_SESSION["bd"]="CompraDirecta" para indicar que es una compra directa y evitar
				//la modificaci?n de las partidas registradas en la Requisiciones y Pedidos de los otros departamentos.
				if($_SESSION['origen']=="Compra Directa"){ $_SESSION["bd"] = "CompraDirecta"; }?>
		  		<input name="txt_origen" type="text" class="caja_de_texto" id="txt_origen" value="<?php echo $_SESSION['origen'];?>" size="30" 
				maxlength="30" readonly="readonly" />
			</td>
			<td width="120"><div align="right">Fecha</div></td>
		    <td width="170">
				<input name="txt_fechaEntrada" id="txt_fechaEntrada" type="text" class="caja_de_texto" value="<?php echo verFecha(4);?>" readonly="readonly" 
				size="10" maxlength="10" />
			</td>
        </tr>
		<tr>
	  	  	<td><div align="right">N&deg; Origen </div></td>
			<td>
				<?php
				if($_SESSION['origen'] == "Compra Directa"){
				?>
					<input name="txt_noOrigen" type="text" class="caja_de_texto" id="txt_noOrigen" size="10" 
					maxlength="10" required="required" style="text-transform:uppercase" autocomplete="off"/>
				<?php
				} else {
				?>
					<input name="txt_noOrigen" type="text" class="caja_de_texto" id="txt_noOrigen" value="<?php echo $_SESSION['no_origen'];?>" size="10" 
					maxlength="11" readonly="readonly"/>
				<?php
				}
				?>
			</td>
			<td><div align="right">Aceptado</div></td>
		    <td>
				<select name="cmb_aceptado" class="combo_box">
              		<option value="SI">Si</option>
              		<option value="NO">No</option>
            	</select>
			</td>
		</tr>
		<tr>
		  	<td><div align="right">N� Factura</div></td>
		  	<td><input type="text" name="txt_noFactura" class="caja_de_texto" size="50" maxlength="20" onkeypress="return permite(event,'num_car');"  required="required" autocomplete="off"/></td>
	  	  	<td><div align="right">Comentarios</div></td>
		  	<td>
				<textarea name="txa_comentarios" maxlength="120"onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30" 
				onkeypress="return permite(event,'num_car');" ></textarea>
			</td>
		</tr>
		<tr>
			<td><div align="right">Costo Total</div></td>
		  	<td>
				<?php $costoTotalEntrada = obtenerSumaRegistrosES($_SESSION['datosEntrada'],"costoTotal");?>
				$<input name="txt_costoTotal" type="text" readonly="readonly" class="caja_de_num" value="<?php echo number_format($costoTotalEntrada,2,".",",");?>" 
				size="15" maxlength="20" />
			</td>
			<td>&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
		  	<td colspan="4" align="center">
				<input name="btn_guardar" type="submit" class="botones" id="btn_guardar" value="Guardar" onmouseover="window.status='';return true" 
				title="Guardar Datos de la Entrada de Material" />
				&nbsp;&nbsp;
		      	<input name="btn_limpiar" type="reset" class="botones" id="btn_limpiar" value="Limpiar" title="Limpiar Formulario" />
				&nbsp;&nbsp;
				<?php
				//Verificar si la Entrada es una compra directa
				if(isset($_GET["in"]) && $_GET["in"]=="cd"){
				?>
					<input name="btn_volver" type="button" class="botones" id="btn_volver" value="Regresar" title="Regresar a la Secci&oacute;n Anterior" 
					onclick="location.href='frm_entradaMaterial2A.php'" />&nbsp;&nbsp;
				<?php 
				}
				?>
				<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar toda la Entrada" 
				onclick="location.href='frm_entradaMaterial.php?lmp=si'" />
			</td>
			<!--<h4>* Las facturas deben ir separadas por punto y coma ;</h4>-->
		</tr>
	</table>
	</form>				  
	</fieldset>
	
	<?php //El DIV del calendario se comenta ya que se puede volver a requerir en algun otro momento?>
	<div id="img-calendario">
		<input type="image" onclick="displayCalendar(document.frm_datosEntrada.txt_fechaEntrada,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0"/>
	</div>
	
	<div id="registro-material" align="center" class="borde_seccion"><?php
		//Verificar que el arreglo de datos haya sido definido
		if(isset($_SESSION['datosEntrada'])){?>
			<p align="center" class="titulo_etiqueta">Registro de la Entrada de Material</p><?php 
			mostrarRegistros($datosEntrada,2);
		}		
	}//Cierre del if(!isset($_POST['$txt_proveedor']))
	else{
		if(!isset($_POST["hdn_bandera"]) && !isset($_POST["hdn_banderaPaiES"])){
			//Guardar los datos de la entrada de material
			guardarCambios($cmb_provedor,$txt_noFactura,$txt_fechaEntrada,$cmb_aceptado,$txa_comentarios);
		}
		if(isset($_POST["hdn_bandera"])){
			//Guardar los datos de la entrada de material
			guardarEntradaSalida($cmb_provedor,$txt_noFactura,$txt_fechaEntrada,$cmb_aceptado,$txa_comentarios);
		}
		if(isset($_POST["hdn_banderaPaiES"])){
			//Guardar los datos de la entrada de material
			guardarEntradaSalida($cmb_provedor,$txt_noFactura,$txt_fechaEntrada,$cmb_aceptado,$txa_comentarios);
		} 
		/*   BLOQUEAR CAMBIAR LA FECHAS
		
		if ($txt_fechaEntrada > date("d/m/Y")) {
			echo '<script>
			alert("fecha incorrecta '.$txt_fechaEntrada.' - fecha actual: '.date("d/m/Y").'");
			history.go(-1);
		</script>';
		} else {
			if(!isset($_POST["hdn_bandera"]) && !isset($_POST["hdn_banderaPaiES"])){
				//Guardar los datos de la entrada de material
				guardarCambios($cmb_provedor,$txt_noFactura,$txt_fechaEntrada,$cmb_aceptado,$txa_comentarios);
			}
			if(isset($_POST["hdn_bandera"])){
				//Guardar los datos de la entrada de material
				guardarEntradaSalida($cmb_provedor,$txt_noFactura,$txt_fechaEntrada,$cmb_aceptado,$txa_comentarios);
			}
			if(isset($_POST["hdn_banderaPaiES"])){
				//Guardar los datos de la entrada de material
				guardarEntradaSalida($cmb_provedor,$txt_noFactura,$txt_fechaEntrada,$cmb_aceptado,$txa_comentarios);
			} 
		}
		*/
		
	}?>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
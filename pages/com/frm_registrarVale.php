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
		include ("op_gestionVales.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	
    <style type="text/css">
		<!--
		#material-vale { position:absolute; left:30px; top:470px; width:901px; height:194px; z-index:14; overflow:scroll;}
		#tabla-material { position:absolute; left:30px; top:190px; width:901px;	height:257px; z-index:16;}
		#datos-gral { position:absolute; left:20px; top:455px; width:940px; height:120px; z-index:15; }
		#titulo-generar { position:absolute; left:30px; top:146px; width:187px; height:19px; z-index:11; }
		#tabla-complementar{position:absolute; left:30px; top:190px; width:600px;height:215px; z-index:16;}
		#lista-proveedores { position:absolute; width:321px; height:104px; z-index:17; }
		#calendario { position:absolute; left:625px; top:233px; width:30px; height:26px; z-index:17; }
		#res-spider {position:absolute;z-index:15;}
		-->
    </style>
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Generar Vale </div>
	
	<?php
	if(isset($_POST["sbt_guardar"]))
		registrarVale();
	
	if(!isset($_GET["continuar"])){
		//Verificamos si se ha presionado el boton Registrar Detalle Pedido, de ser así realizar el proceso de carga de datos en el Arreglo de Session
		if(isset($_POST['sbt_agregar'])){
			$numPartida=$_POST["hdn_partida"];
			//Verificar si el Material es del Almacén
			if($_POST["rdb_material"]=="true"){
				$idMaterial=$_POST["cmb_material"];
				$cantidad=$_POST["txt_cantidad"];
				$concepto=obtenerDato("bd_almacen","materiales","nom_material","id_material",$idMaterial);
				$medida=obtenerDato("bd_almacen","unidad_medida","unidad_medida","materiales_id_material",$idMaterial);
			}
			else{
				$idMaterial="N/A";
				$cantidad=$_POST["txt_cantidadNuevo"];
				$concepto=strtoupper($_POST["txt_matNuevo"]);
				$medida=strtoupper($_POST["cmb_unidad"]);
			}
			//Si ya esta definido el arreglo $detallespedido, entonces agregar el siguiente registro a el
			if(isset($_SESSION['detallesVale'])){
				//Guardar los datos en el arreglo
				$detallesVale[] = array("idMaterial"=>$idMaterial,"partida"=>$numPartida,"cantidad"=>$cantidad,"concepto"=>$concepto,"medida"=>$medida);
			}
			//Si no esta definido el arreglo $datosEntrada definirlo y agregar el primer registro
			else{
				//Guardar los datos en el arreglo
				$detallesVale = array(array("idMaterial"=>$idMaterial,"partida"=>$numPartida,"cantidad"=>$cantidad,"concepto"=>$concepto,"medida"=>$medida));
				$_SESSION['detallesVale'] = $detallesVale;
			}
			$partida=$_POST["hdn_partida"]+1;
		}//Fin del IF que comprueba la variable btn_registrar
		else
			$partida=1;
		?>
		
		<fieldset id="tabla-material" class="borde_seccion">
		<legend class="titulo_etiqueta">Seleccionar/Ingresar Material</legend>
		<form name="frm_generarVale" method="post" action="frm_registrarVale.php" onsubmit="return valFormMaterialVale(this);">
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
				<td colspan="2"><strong><input type="radio" name="rdb_material" id="rdb_material1" value="true"/>Material de Almac&eacute;n</strong></td>
			</tr>
			<tr>
				<td width="98"  class="tabla_frm"><div align="right">Categor&iacute;a</div></td>
				<td width="633"  class="tabla_frm">
					<?php  		
					$res = cargarComboConId("cmb_categoria","linea_articulo","linea_articulo","materiales","bd_almacen","Categor&iacute;a","",
					"cargarComboIdNombreOrd(this.value,'bd_almacen','materiales','nom_material','id_material','linea_articulo','cmb_material','Material','nom_material','')");
					if($res==0){?>
						<label class="msje_correcto"> No hay Categor&iacute;as Registradas, Contacte Administrador Almac&eacute;n</label>
						<input type="hidden" name="cmb_categoria" id="cmb_categoria"/><?php
					}?>
				</td>
				
			</tr>
			<tr>
			  <td width="98"><div align="right">Material</div></td>
				<td>
					<select name="cmb_material" id="cmb_material" size="1" class="combo_box">
						<option value="" selected="selected">Material</option>					
					</select>
				</td>	
			</tr>
			<tr>
				<td><div align="right">Cantidad</div></td>
				<td>
					<input name="txt_cantidad" type="text" class="caja_de_texto" id="txt_cantidad" onkeypress="return permite(event,'num',2);" size="6" maxlength="10" />
				</td>
			</tr>
			<tr>
				<td colspan="2"><strong><input type="radio" name="rdb_material" id="rdb_material2" value="false"/>Material Nuevo</strong></td>
			</tr>
			<tr>
				<td width="98"><div align="right">Material</div></td>
				<td width="633">
					<input name="txt_matNuevo" type="text"  class="caja_de_texto" id="txt_matNuevo" onkeypress="return permite(event,'num_car',0);" size="30" maxlength="60"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cantidad
					<input name="txt_cantidadNuevo" type="text" class="caja_de_texto" id="txt_cantidadNuevo" onkeypress="return permite(event,'num',2);" size="6" maxlength="10" />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Unidad Medida
					<?php 
						$conn = conecta("bd_compras");//Conectarse con la BD de Compras
						//Ejecutar la Sentencia para Obtener las Unidades de Medida de la Tabla de Detalles del Pedido de la BD de Compras
						$rs_unidades = mysql_query("SELECT DISTINCT unidad FROM detalles_pedido ORDER BY unidad");
						if($unidades=mysql_fetch_array($rs_unidades)){?>
							<select name="cmb_unidad" id="cmb_unidad" class="combo_box" onchange="agregarNvaUnidad(this)">
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
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="hidden" name="hdn_partida" id="hdn_partida" value="<?php echo $partida?>"/>
					<?php
					if(isset($_SESSION["detallesVale"])){
					?>
						<input type="button" name="btn_continuar" id="btn_continuar" value="Continuar" class="botones" title="Continuar a Complementar el Vale" onclick="location.href='frm_registrarVale.php?continuar=true';"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
					<?php
					}
					?>
					<input type="submit" name="sbt_agregar" id="sbt_agregar" onmouseover="window.status='';return true;" class="botones" value="Agregar" title="Agregar el Material al Vale"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" value="Limpiar" title="Limpia los Campos y Restablece el Formulario"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" class="botones" value="Cancelar" title="Cancela el Proceso de Registro de Vale y Regresa a la Secci&oacute;n Anterior"
					onclick="confirmarSalida('menu_vales.php');"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<?php 
		if(isset($_SESSION["detallesVale"])){?>
			<div class="borde_seccion2" id="material-vale">
			<?php 
				mostrarDetallesVale($detallesVale);
			?>
			</div>
		<?php 
		}	
	}//Fin de if(!isset($_GET["continuar"])), para mostrar el formulario final de guardado de datos
	else{
		?>
		<fieldset id="tabla-complementar" class="borde_seccion">
		<legend class="titulo_etiqueta">Complementar el Vale</legend>
		<br />
		<form name="frm_guardarVale" method="post" action="frm_registrarVale.php" onsubmit="return valFormComplementarBit(this);">
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td width="12%"><div align="right">*No. Vale</div></td>
				<td width="34%">
					<input type="text" name="txt_noVale" id="txt_noVale" onkeypress="return permite(event,'num_car', 0);" size="10" maxlength="10" class="caja_de_texto" tabindex="1"/>
			  </td>
			  <td width="34%"><div align="right">Fecha</div></td>
				<td width="20%">
					<input name="txt_fecha" type="text" readonly="readonly" class="caja_de_texto" value="<?php echo date("d/m/Y");?>" size="10" maxlength="10"/>
			  </td>
			</tr>
			<tr>
				<td><div align="right">Proveedor</div></td>
				<td colspan="2">
					<input type="text" name="txt_nomProveedor" id="txt_nomProveedor" onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');" 
					value="" size="50" maxlength="80" onkeypress="return permite(event,'num_car', 0);" tabindex="3"/>
					<div id="lista-proveedores">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Obra</div></td>
				<td colspan="2">
					<input type="text" name="txt_obra" id="txt_obra" value="" size="50" maxlength="80" onkeypress="return permite(event,'num_car', 0);" tabindex="4"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Autoriz&oacute;</div></td>
				<td colspan="2">
					<input name="txt_autorizo" type="text" class="caja_de_texto" id="txt_autorizo" tabindex="5" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookupEmp(this,'2');" value="" size="40" maxlength="75"/>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="submit" name="sbt_guardar" id="sbt_guardar" title="Guardar el Registro del Vale" class="botones" onmouseover="window.status='';return true;" value="Guardar" tabindex="6"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" name="btn_limpiar" id="btn_limpiar" title="Restablecer el Formulario" class="botones" value="Limpiar" tabindex="7" />
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" id="btn_regresar" title="Regresar a la Secci&oacute;n Anterior" class="botones" onclick="location.href='frm_registrarVale.php'" value="Regresar" tabindex="8"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" id="btn_cancelar" title="Cancelar y Volver al Men&uacute;" class="botones" onclick="location.href='menu_vales.php'" value="Cancelar" tabindex="9"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<div id="calendario">
			<input type="image" name="iniRepProv" id="iniRepProv" src="../../images/calendar.png" onclick="displayCalendar(document.frm_guardarVale.txt_fecha,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
		</div>
		
		<?php
	}
	?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
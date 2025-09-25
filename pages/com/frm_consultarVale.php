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
		//Este archivo contiene las funciones para generar el Reporte Compra/Venta
		include ("op_gestionVales.php");	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:143px; width:188px; height:21px; z-index:11; }
		#fechas { position:absolute; left:30px; top:180px; width:440px; height:160px; z-index:12; }
		#calendar-uno { position:absolute; left:285px; top:210px; width:30px; height:26px; z-index:13; }
		#calendar-dos { position:absolute; left:285px; top:245px; width:30px; height:26px; z-index:14; }
		#resultados { position:absolute; left:30px; top:190px; width:940px; height:430px; z-index:15; overflow:scroll; }		
		#btns-regpdf { position: absolute; left:30px; top:670px; width:940px; height:40px; z-index:16; }
		
		#vale {position:absolute;left:30px;top:190px;width:901px;height:150px;z-index:17;}
		#material-vale { position:absolute; left:30px; top:370px; width:901px; height:250px; z-index:0; overflow:scroll;}
		#calendario {position:absolute; left:728px; top:218px; width:30px; height:26px; z-index:19; }
		#res-spider {position:absolute;z-index:20;}
		#lista-proveedores { position:absolute; width:321px; height:104px; z-index:20;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Consulta/Gesti&oacute;n de Vales</div><?php
	
	if(isset($_POST["sbt_actualizar"]))
		guardarModificacion();
	
	if(!isset($_POST["ckb_vale"])){
		$band = 0;
		if(isset($_POST['sbt_consultar']) || isset($_POST["sbt_regresar"])){?>				
			<div id="resultados" align="center" class="borde_seccion2"><?php
				$band = 1;		
				mostrarVales();?>
			</div>
			<div id="btns-regpdf" align="center">
				<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar a la P&aacute;gina de Reportes de Compra/Venta" 
				onclick="location.href='frm_consultarVale.php'" />
			</div><?php		
		}		
				
		if($band==0){ ?>
		<fieldset class="borde_seccion" id="fechas" name="fechas">
		<legend class="titulo_etiqueta">seleccionar Fechas</legend>
			<form onsubmit="return verContFormCompraVenta(this);" name="frm_consultarVales" method="post" action="frm_consultarVale.php">
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">		
					<tr>
						<td width="30%"><div align="right">Fecha Inicio</div></td>
						<td>
							<input name="txt_fechaIni" type="text" class="caja_de_texto" id="txt_fechaIni" value=<?php echo date("d/m/Y", strtotime("-30 day")); ?> size="10"readonly="true"></td>
					</tr>
					<tr>
						<td><div align="right">Fecha Fin </div></td>
						<td><input name="txt_fechaFin" type="text" class="caja_de_texto" id="txt_fechaFin" value=<?php echo date("d/m/Y"); ?> size="10" readonly="true"/></td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input name="sbt_consultar" id="sbt_consultar" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
							title="Consultar Vales en las Fechas Seleccionadas" />
							&nbsp;
							<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />		  
							&nbsp;
							<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Vales" 
							onclick="location.href='menu_vales.php'" />
						</td>
					</tr>
				</table>
			</form>
		</fieldset>
		
		<div id="calendar-uno">
			<input name="iniRepFecha" id="iniRepFecha" type="image" src="../../images/calendar.png"
			 onclick="displayCalendar(document.frm_consultarVales.txt_fechaIni,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
			width="25" height="25" border="0" align="absbottom" />
		</div>
		
		<div id="calendar-dos">
			<input name="finRepFecha" id="finRepFecha" type="image" src="../../images/calendar.png" onclick=
			"displayCalendar(document.frm_consultarVales.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"
			width="25" height="25" border="0" align="absbottom" />
		</div>		
		
	<?php }//Cierre if($band==0)
	}
	else{
		$idVale=$_POST["ckb_vale"];
		$noVale=obtenerDato("bd_compras","vale","no_vale","id_vale",$idVale);
		$proveedor=obtenerDato("bd_compras","vale","proveedores_rfc","id_vale",$idVale);
		$proveedor=obtenerDato("bd_compras","proveedores","razon_social","rfc",$proveedor);
		$fecha=obtenerDato("bd_compras","vale","fecha","id_vale",$idVale);
		$obra=obtenerDato("bd_compras","vale","obra","id_vale",$idVale);
		$autorizo=obtenerDato("bd_compras","vale","autorizo","id_vale",$idVale);
		$moneda=obtenerDato("bd_compras","vale","moneda","id_vale",$idVale);
		$estado=obtenerDato("bd_compras","vale","estado","id_vale",$idVale);
		?>
		<form name="frm_modificarVale" method="post" action="frm_consultarVale.php" >
		<fieldset id="vale" class="borde_seccion">
		<legend class="titulo_etiqueta">Seleccionar/Ingresar Material</legend>
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
				<td width="12%"><div align="right">*No. Vale</div></td>
				<td width="35%">
					<input type="text" name="txt_noVale" id="txt_noVale" onkeypress="return permite(event,'num_car', 0);" size="10" maxlength="10" class="caja_de_texto" tabindex="1" value="<?php echo $noVale?>"/>
			  </td>
				<td width="17%"><div align="right">Fecha</div></td>
				<td width="36%">
					<input name="txt_fecha" type="text" readonly="readonly" class="caja_de_texto" value="<?php echo modFecha($fecha,1);?>" size="10"/>
			  </td>
			</tr>
			<tr>
				<td><div align="right">Proveedor</div></td>
				<td>
					<input type="text" name="txt_nomProveedor" id="txt_nomProveedor" onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');" 
					value="<?php echo $proveedor?>" size="50" maxlength="80" onkeypress="return permite(event,'num_car', 0);" tabindex="3"/>
					<div id="lista-proveedores">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>
				</td>
			  <td width="17%"><div align="right">Tipo Moneda</div></td>
				<td width="36%">
					<select name="cmb_tipoMoneda" id="cmb_tipoMoneda" class="combo_box">
						<option value="" <?php if($moneda=="") echo "selected='selected'";?>>Moneda</option>
						<option value="PESOS" <?php if($moneda=="PESOS") echo "selected='selected'";?>>PESOS</option>
						<option value="DOLARES" <?php if($moneda=="DOLARES") echo "selected='selected'";?>>DOLARES</option>
					</select>
			  </td>
			</tr>
			<tr>
				<td><div align="right">Obra</div></td>
				<td>
					<input type="text" name="txt_obra" id="txt_obra" value="<?php echo $obra?>" size="50" maxlength="80" onkeypress="return permite(event,'num_car', 0);" tabindex="4"/>
				</td>
			  <td width="17%"><div align="right">Estado</div></td>
				<td width="36%">
					<select name="cmb_estado" id="cmb_estado" class="combo_box">
						<option value="1" <?php if($estado=="1") echo "selected='selected'";?>>NO COMPLEMENTADA</option>
						<option value="2" <?php if($estado=="2") echo "selected='selected'";?>>COMPLEMENTADA</option>
						<option value="3" <?php if($estado=="3") echo "selected='selected'";?>>CANCELADA</option>
					</select>
			  </td>
			</tr>
			<tr>
				<td><div align="right">Autoriz&oacute;</div></td>
				<td>
					<input name="txt_autorizo" type="text" class="caja_de_texto" id="txt_autorizo" tabindex="5" onkeypress="return permite(event,'car',0);" 
					onkeyup="lookupEmp(this,'2');" value="<?php echo $autorizo?>" size="40" maxlength="75"/>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
						</div>
					</div>
				</td>
			</tr>
		</table>
		</fieldset>

		<div class="borde_seccion2" id="material-vale">
			<?php 
				mostrarMaterialesVale($idVale);
			?>
		</div>
		
		<div id="btns-regpdf" align="center">
		<tr>
			<td colspan="4" align="center">
				<input type="hidden" name="hdn_vale" id="hdn_vale" value="<?php echo $idVale;?>"/>
				<input type="hidden" name="txt_fechaIni" id="txt_fechaIni" value="<?php echo $_POST["txt_fechaIni"]?>"/>
				<input type="hidden" name="txt_fechaFin" id="txt_fechaFin" value="<?php echo $_POST["txt_fechaFin"]?>"/>
				<input type="hidden" name="hdn_validar" id="hdn_validar" value="si"/>
				<input type="submit" name="sbt_actualizar" id="sbt_actualizar" title="Actualizar el Registro del Vale" class="botones" onmouseover="window.status='';return true;" value="Actualizar" tabindex="6"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" name="btn_limpiar" id="btn_limpiar" title="Restablecer el Formulario" class="botones" value="Restablecer" tabindex="7" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="submit" name="sbt_regresar" id="sbt_regresar" title="Regresar a la Secci&oacute;n Anterior" class="botones" onclick="hdn_validar.value='no'" value="Regresar" tabindex="8"
				onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_cancelar" id="btn_cancelar" title="Cancelar y Volver al Men&uacute;" class="botones" onclick="location.href='frm_consultarVale.php'" value="Cancelar" tabindex="9"/>
			</td>
		</tr>
		</div>
		</form>
		
		<div id="calendario">
			<input type="image" name="iniRepProv" id="iniRepProv" src="../../images/calendar.png" onclick="displayCalendar(document.frm_modificarVale.txt_fecha,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" />
		</div>
		<?php
	}
	?>	   	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
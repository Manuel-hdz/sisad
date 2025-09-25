<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	include ("op_agregarbitacora.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionComaro.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarInfoEmpleado.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarPlatillos.js" ></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#agregar-bitacora {position:absolute;left:30px;top:190px;width:600px;height:250px;z-index:12;}
		#tabla-platillos {position:absolute;left:30px;top:375px;width:920px;height:300px;z-index:12;}
		#res-spider3 { position:absolute; width:10px; height:10px; z-index:13; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg-comaro.png" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Agregar Bitacora</div>
		
	<?php
	//Verificar si se debe guardar un registro
	if(isset($_POST["sbt_guardar"])){
		registrarBitacora();
	}
	?>
	<fieldset class="borde_seccion" id="agregar-bitacora" name="agregar-bitacora">
	<legend class="titulo_etiqueta">Bitacora de personal atendido</legend>	
	<br>
	<form name="frm_aregarBitacora" method="post" action="frm_aregarBitacora.php" onsubmit="return valFormAgregarBitacora(this)">
		<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
		
		<tr>
			<td><div align="right">*C&oacute;digo Empleado</div></td>
			<td>
				<input type="text" name="txt_codBarTrabajador" id="txt_codBarTrabajador" class="caja_de_texto" size="10" maxlength="20" 
				onkeypress="return permite(event, 'num', 3);" readonly="readonly"/>
			</td>
			<td><div align="right">*Empleado</div></td>
			<td>
				<input name="txt_nombre" type="text" class="caja_de_texto"  size="50" id="txt_nombre" value="" autocomplete="off" onkeyup="lookup(this,'3');"/>
				<div id="res-spider3">
					<div align="left" class="suggestionsBox_comaro" id="suggestions3" style="display: none;">
						<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
						<div class="suggestionList_comaro" id="autoSuggestionsList3">&nbsp;</div>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td><div align="right">*Turno</div></td>
        	<td>
				<?php
					$horaActual=date("H");
				?>
				<select name="cmb_turno" id="cmb_turno" class="combo_box" onchange="cargarPlat(cmb_turno.value,'cmb_plat')">
            		<option value="">Seleccionar Turno</option>
            		<option value="PRIMERA"<?php if($horaActual>=5 && $horaActual<=10) echo " selected='selected'";?>>Turno de Primera</option>
            		<option value="SEGUNDA"<?php if($horaActual>=14 && $horaActual<=17) echo " selected='selected'";?>>Turno de Segunda</option>
					<option value="TERCERA"<?php if($horaActual>=18 && $horaActual<=20) echo " selected='selected'";?>>Turno de Tercera</option>
   		  	  </select>			
			</td>
			<td><div align="right">*Platillo</div></td>
			<td>
				<span id="datosPlatillos">
					<select name="cmb_plat" id="cmb_plat" class="combo_box">
						<option value="">Seleccionar Platillo</option>
					</select>
				</span>
			</td>
		</tr>
		<tr>
			<td><div align="right">*Estado</div></td>
			<td>
				<select name="cmb_estado" id="cmb_estado" class="combo_box">
            		<option value="">Seleccionar Estado</option>
            		<option value="A">Apartado</option>
            		<option value="E">Entregado</option>
				</select>			
			</td>
        	<td><div align="right">*Pagado</div></td>
			<td>
				<select name="cmb_pag" id="cmb_pag" class="combo_box">
            		<option value="">Seleccionar</option>
            		<option value="NO">NO</option>
            		<option value="SI">SI</option>
				</select>			
			</td>
		</tr>
		<tr>
			<td><div align="right">*Descuento</div></td>
			<td>
				<input type='text' name='txt_descuento' id='txt_descuento' class='caja_de_num' size='10' value="0.00" maxlength="10"
				onkeypress="return permite(event, 'num', 2);" onChange="formatCurrency(value.replace(/,/g,''),'txt_descuento');"/> %
			</td>
		</tr>
		<tr>
			<td colspan="6"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
		</tr>
		<tr>
			<td colspan="6" align="center">
				<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Guardar" title="Registrar el Platllo en el Men&uacute;" onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; de Platillos" onclick="location.href='menu_bitacoras.php'"/>
			</td>
		</tr>
		</table>
	</form>
	</fieldset>
	<!--<div id="tabla-platillos" class="borde_seccion2" align="center"> 
		<?php
		//mostrarPlatillos();
		?>
	</div>-->
	<script>cargarPlat(cmb_turno.value,'cmb_plat');txt_codBarTrabajador.focus();</script>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
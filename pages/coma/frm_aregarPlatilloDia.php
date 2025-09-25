<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	include ("op_agregarplatillodia.php"); 
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
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#agregar-platillos {position:absolute;left:30px;top:190px;width:600px;height:160px;z-index:12;}
		#tabla-platillos {position:absolute;left:30px;top:375px;width:920px;height:300px;z-index:12; overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg-comaro.png" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Platillos del D&iacute;a</div>
		
	<?php
	//Verificar si se debe guardar un registro
	if(isset($_POST["sbt_guardar"])){
		agregarPlatilloDia();
	}
	?>
	<fieldset class="borde_seccion" id="agregar-platillos" name="agregar-platillos">
	<legend class="titulo_etiqueta">Seleccionar Platillo del D&iacute;a</legend>	
	<br>
	<form name="frm_aregarPlatilloDia" method="post" action="frm_aregarPlatilloDia.php" onsubmit="return valFormAgregarPlatilloDia(this)">
		<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<tr>
			<td><div align="right">*Platillo</div></td>
			<td>
				<?php 
					$conn = conecta("bd_comaro");		
					$stm_sql = "SELECT * FROM menu ORDER BY descripcion";
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){?>
						<select name="cmb_plat" id="cmb_plat" class="combo_box">
							<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value=''>Seleccionar un Platillo</option>";
							do{
								echo "<option value='$datos[id_menu]'>$datos[descripcion]</option>";
							}while($datos = mysql_fetch_array($rs));?>
						</select>
					<?php
					}
					else{
						echo "<label class='msje_correcto'>No hay platillos registrados en el menu</label>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);
				?>
			</td>
			<td><div align="right">*Turno</div></td>
        	<td colspan="2">
				<?php
					$horaActual=date("H");
				?>
				<select name="cmb_turno" id="cmb_turno" class="combo_box">
            		<option value="">Seleccionar Turno</option>
            		<option value="PRIMERA"<?php if($horaActual>=5 && $horaActual<=10) echo " selected='selected'";?>>Turno de Primera</option>
            		<option value="SEGUNDA"<?php if($horaActual>=14 && $horaActual<=17) echo " selected='selected'";?>>Turno de Segunda</option>
					<option value="TERCERA"<?php if($horaActual>=18 && $horaActual<=20) echo " selected='selected'";?>>Turno de Tercera</option>
   		  	  </select>			
			</td>
			<td><div align="right">*Cantidad</div></td>
			<td>
				<input type="text" name="txt_cantidad" id="txt_cantidad" class="caja_de_texto" size="10" maxlength="5" onkeypress="return permite(event, 'num', 3);"/>
			</td>
		</tr>
		<tr>
			<td colspan="6"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
		</tr>
		<tr>
			<td colspan="6" align="center">
				<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Guardar" title="Registrar el Platllo en el Men&uacute;" onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario" onclick="txa_descripcion.focus();txa_descripcion.value='';txt_costo.value='0.00';"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; de Platillos" onclick="location.href='menu_platillos.php'"/>
			</td>
		</tr>
		</table>
	</form>
	</fieldset>
	<div id="tabla-platillos" class="borde_seccion2" align="center"> 
		<?php
		mostrarPlatillosDia();
		?>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	 	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo con las opciones del catalogo de Sueldos
		include ("op_catalogoTurnos.php");
		?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
   	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarCatalogoTurnos.js"></script>
    <style type="text/css">
		<!--
		#titulo-ingresar { position:absolute; left:30px; top:146px; width:248px; height:19px; z-index:11; }	
		#registrar-turno {position:absolute; left:30px; top:190px; width:690px; height:250px; z-index:12; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-ingresar">Actualizar Cat&aacute;logo de Turnos</div>

	<?php
		$resultado=-1;
		//Verificar si se ha presionado el botón de Guardar
		if(isset($_POST["sbt_guardar"])){
			//Verificar el tipo de accion que se debe tomar, si es para Agregar un nuevo Turno o
			//modificar los que ya se tienen registrados
			if ($_POST["hdn_estado"]=="Agregar")
				$resultado=agregarTurno();
			else
				$resultado=modificarTurno();
		}
		if(isset($_GET["borrar"]))
			$resultado=borrarTurno();

	//Valores de Resultado
	//0	->	Consulta con errores
	//1	->	Resultado AGREGADO correctamente
	//2	->	Resultado MODIFICADO correctamente
	//3	->	Resultado BORRADO correctamente
	if ($resultado==1){?>
	<script type="text/javascript" language="javascript">
		setTimeout("alert('Turno Agregado Correctamente');",500);
	</script>
	<?php }
	if ($resultado==2){
	?>
	<script type="text/javascript" language="javascript">
		setTimeout("alert('Turno Modificado Correctamente');",500);
	</script>
	<?php }
	if ($resultado==3){
	?>
	<script type="text/javascript" language="javascript">
		setTimeout("alert('Turno Borrado Correctamente');",500);
	</script>
	<?php }
	if ($resultado==0){
	?>
	<script type="text/javascript" language="javascript">
		setTimeout("alert('La Operación tuvo un Error');",500);
	</script>
	<?php }
	?>

	<fieldset class="borde_seccion" id="registrar-turno" name="registrar-turno">
    <legend class="titulo_etiqueta">Informaci&oacute;n de Turnos</legend>	
    <br>
	<form name="frm_catalogoTurnos" method="post" onsubmit="return valFormCatalogoTurnos(this);" action="frm_catalogoTurnos.php">
		<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<tr>
		  <td width="15%"><div align="right">*Turno</div></td>
			<td width="31%">
				<?php 
				$res=cargarComboTotal("cmb_turnos","nom_turno","nom_turno","turnos","bd_recursos","Turno","","cargarTurno(this.value);activarBotonBorrarTurno(this.value);","id_turno","","");
				if ($res==""){
					echo "<label class='msje_correcto'>Ingrese Un Turno</label>";
					echo "<input type='hidden' name='cmb_turnos' id='cmb_turnos' value=''";
				}
				?>			</td>
		  <td width="28%">
				<div align="right"><input type="checkbox" name="ckb_nuevoTurno" id="ckb_nuevoTurno" onclick="agregarNuevoTurno();" title="Seleccione para escribir el nombre de un Turno que no exista"/>
				Agregar Nuevo Turno </div>		  </td>
		  <td width="26%"><input type="text" name="txt_nuevoTurno" id="txt_nuevoTurno" class="caja_de_texto" readonly="readonly" size="30"/></td>
		</tr>
		<tr>
			<td><div align="right">*Hora Entrada</div></td>
			<td>
				<input type="text" name="txt_horaE" id="txt_horaE" size="5" onchange="formatHora(this,'cmb_horaE');" maxlength="5" onkeypress="return permite(event,'num',0);" value="<?php echo date("h:i"); ?>"/>&nbsp;
				<select name="cmb_horaE" id="cmb_horaE" class="combo_box">
					<option value="AM" <?php if (date("A")=="AM") echo "selected='selected'";?>>a.m.</option>
					<option value="PM" <?php if (date("A")=="PM") echo "selected='selected'";?>>p.m.</option>
				</select>
			</td>
			<td><div align="right">*Hora Salida</div></td>
			<td>
				<input type="text" name="txt_horaS" id="txt_horaS" size="5" onchange="formatHora(this,'cmb_horaS');" maxlength="5" onkeypress="return permite(event,'num',0);" value="<?php echo date("h:i", strtotime("+8 hours")); ?>"/>&nbsp;
				<select name="cmb_horaS" id="cmb_horaS" class="combo_box">
					<option value="AM" <?php if (date("A")=="AM") echo "selected='selected'";?>>a.m.</option>
					<option value="PM" <?php if (date("A")=="PM") echo "selected='selected'";?>>p.m.</option>
				</select>
			</td>
		</tr>
		<tr>
			<td><div align="right">*Comentarios</div></td>
			<td colspan="3"><textarea name="txa_comentarios" id="txa_comentarios" class="caja_de_texto" maxlength="120" onkeyup="return ismaxlength(this)" rows="3" cols="40"></textarea></td>
		</tr>
		<tr>
			<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<input type="hidden" name="hdn_estado" id="hdn_estado" value="Agregar"/>
				<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Guardar" title="Guardar el Registro de Turno" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario" onclick="cmb_turnos.disabled=false;btn_eliminar.disabled=true;btn_eliminar.title='Seleccione un Turno de la Lista para poder Borrarlo';"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php if ($res!=""){?>
				<input type="button" class="botones" name="btn_eliminar" id="btn_eliminar" value="Eliminar" title="Seleccione un Turno de la Lista para poder Borrarlo" onmouseover="window.status='';return true;" onclick="borrarTurno();" disabled="disabled"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php }?>
				<input type="button" class="botones" name="btn_cancelar" id="btn_cancelar" value="Cancelar" title="Cancelar y Volver al Men&uacute; de Turnos" onclick="location.href='menu_turnos.php'"/>
			</td>
		</tr>
		</table>
	</form>
</fieldset>
	
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
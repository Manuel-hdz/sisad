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
		include ("op_catSueldos.php");?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
   	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarCatalogoSueldos.js"></script>
    <style type="text/css">
		<!--
		#titulo-ingresar { position:absolute; left:30px; top:146px; width:248px; height:19px; z-index:11; }	
		#registrar-sueldo {position:absolute; left:30px; top:190px; width:690px; height:270px; z-index:12; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-ingresar">Ingresar Sueldos por Puestos</div><?php
	
	
		//Verificar si se ha presionado el botón de Guardar
		if(isset($_POST["sbt_guardar"])){
			//Verificar el tipo de accion que se debe tomar, si es para Agregar un nuevo sueldo y puestos o solamente
			//modificar los que ya se tienen registrados
			if ($_POST["hdn_estado"]=="Agregar")
				agregarSueldo();
			else
				modificarSueldo();
		}
	?>
	
	<fieldset class="borde_seccion" id="registrar-sueldo" name="registrar-sueldo">
    <legend class="titulo_etiqueta">Ingresar Sueldos por Puestos</legend>	
    <br>
	<form name="frm_catalogoSueldos" method="post" action="" onsubmit="return valFormSueldos(this);">
		<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<tr>
		  <td width="15%"><div align="right">*&Aacute;rea</div></td>
			<td width="31%">
				<?php 
				$res=cargarComboTotal("cmb_area","area","area","catalogo_salarios","bd_desarrollo","Área","","txt_sueldoBase.value='',txt_porcActividad.value='',txt_porcMetro.value='';cargarCombo(this.value,'bd_desarrollo','catalogo_salarios','puesto','area','cmb_puestos','Puestos','');activarDesactivarCampos(this);","area","","");
				if ($res==""){
					echo "<label class='msje_correcto'>Ingrese Un &Aacute;rea</label>";
					echo "<input type='hidden' id='cmb_area' name='cmb_area'/>";
					echo "<input type='hidden' id='cmb_puestos' name='cmb_puestos'/>";
				}
				?>			</td>
		  <td width="28%">
				<div align="right"><input type="checkbox" name="ckb_nuevaArea" id="ckb_nuevaArea" onclick="agregarNuevaArea();" title="Seleccione para escribir el nombre de un &Aacute;rea que no exista"/>Agregar Nueva &Aacute;rea</div>
		  </td>
		  <td width="26%"><input type="text" name="txt_nuevaArea" id="txt_nuevaArea" class="caja_de_texto" readonly="readonly" size="30"/></td>
		</tr>
		<tr>
			<td><div align="right">*Puesto</div></td>
			<td>
			<?php if ($res!="") {?>
			<select name="cmb_puestos" id="cmb_puestos" onchange="obtenerSueldo(this,cmb_area);" class="combo_box">
				<option value="">Puestos</option>
			</select>
			<?php }
			else
				echo "<label class='msje_correcto'>Ingrese Un Puesto</label>";
			?>
			</td>
			<td>
				<div align="right"><input type="checkbox" name="ckb_nuevoPuesto" id="ckb_nuevoPuesto" onclick="agregarNuevoPuesto();" title="Seleccione para escribir el nombre de un Puesto que no exista"/>Agregar Nuevo Puesto</div>
			</td>
			<td><input type="text" name="txt_nuevoPuesto" id="txt_nuevoPuesto" class="caja_de_texto" readonly="readonly" size="30"/></td>
		</tr>
		<tr>
			<td><div align="right">*Sueldo Base</div></td>
			<td>$<input name="txt_sueldoBase" id="txt_sueldoBase" type="text" class="caja_de_num" size="10" maxlength="10" onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_sueldoBase');"/></td>
			<td><div align="right">*Incentivo por Actividad</div></td>
			<td>
			<input name="txt_porcActividad" id="txt_porcActividad" type="text" class="caja_de_num" size="10" maxlength="3" onkeypress="return permite(event,'num',2);" onchange="validarPorcentaje(this);formatCurrency(this.value,'txt_porcActividad');" title="Porcentaje que le Corresponde al Puesto por Realizar las Actividades de la Libreta de Tr&aacute;nsito"/>%
			</td>
		</tr>
		<tr>
			<td><div align="right">*Incentivo por Metro</div></td>
			<td colspan="3">
			<input name="txt_porcMetro" id="txt_porcMetro" type="text" class="caja_de_num" size="10" maxlength="3" onkeypress="return permite(event,'num',2);" onchange="validarPorcentaje(this);formatCurrency(this.value,'txt_porcMetro');" title="Porcentaje que le Corresponde al Puesto por Cumplir con m&aacute;s de los 18 Metros Requeridos como M&iacute;nimo"/>%
			</td>
		</tr>
		<tr>
			<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<input type="hidden" name="hdn_estado" id="hdn_estado" value="Agregar"/>
				<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Guardar" title="Guardar el Sueldo Registrado" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario" onclick="restablece();"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" name="btn_cancelar" id="btn_cancelar" value="Cancelar" title="Cancelar y Volver al Men&uacute; de Sueldos" onclick="location.href='menu_sueldos.php'"/>
			</td>
		</tr>
		</table>
	</form>
	</fieldset>
	
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
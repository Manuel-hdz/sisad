<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include ("op_gestionarBonos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="includes/ajax/obtenerDatosBonoNomina.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarBono {position:absolute;left:30px;top:190px;width:923px;height:270px;z-index:14;}
		#calendarioIni {position:absolute;left:804px;top:283px;width:30px;height:26px;z-index:13;}
		#bonosAgregados {position:absolute;left:32px;top:493px;width:914px;height:161px;z-index:12; overflow:scroll}
		#res-spider{position:absolute; z-index:15;}
		-->
    </style>
</head>
<body><?php		
	
	//Variable que indica si el formulario debe ser mostrado
	$verForm = 1;
	
	//Si esta se ha presionado el boton Guardar proceder a guardar los datos
	if(isset($_POST['sbt_guardarBono'])){
		guardarBono();
		$verForm = 0;
	}
	//Si esta se ha presionado el boton Guardar proceder a guardar los datos
	if(isset($_POST['sbt_modificarBono'])){
		modificarBono();
		$verForm = 0;
	}
	//Si esta se ha presionado el boton Guardar proceder a guardar los datos
	if(isset($_POST['sbt_eliminarBono'])){
		eliminarBonoSeleccionado();
		$verForm = 0;
	}
	
	//Verificar si el formnulario debe ser mostrado o no
	if($verForm==1){?>

		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-agregar">Gestionar Bonos</div>
							   
		
		<fieldset class="borde_seccion" id="tabla-agregarBono" name="tabla-agregarBono">
		<legend class="titulo_etiqueta">Agregar Bono a Empleados</legend>	
		<br>
		<form onSubmit="return valFormGestionarBonos(this);" name="frm_gestionarBonos" method="post" action="frm_gestionarBonos.php">
		<table width="923" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="175" align="right">*Nombre del Bono</td>
				<td><?php 				
					$res = cargarComboTotal("cmb_bono","nom_bono","id","bonos","bd_recursos","Seleccionar Bono","","verificarOpcSelect(this);","nom_bono","NUEVO",
											"Agregar Nuevo Bono");				
					if($res==0){?>
						<select name="cmb_bono" id="cmb_bono" class="combo_box" onchange="verificarOpcSelect(this);">
							<option value="">Seleccionar Bono</option>
							<option value="NUEVO">Agregar Nuevo Bono</option>						
						</select><?php
					}?>
					<input type="hidden" name="hdn_nomBonoNvo" id="hdn_nomBonoNvo" value="" />
				</td>  
				<td width="175" align="right">*Descripci&oacute;n del Bono</td>
				<td>
					<textarea name="txa_descripcion" id="txa_descripcion" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="38" 
					onkeypress="return permite(event,'num_car', 0);" maxlength="120" ></textarea>
				</td>
			</tr>
			<tr>
				<td align="right">*Cantidad</td>
				<td>$
					<input name="txt_cantidadBono" id="txt_cantidadBono" type="text" class="caja_de_texto" size="10" maxlength="10" 
					onkeypress="return permite(event,'num',2);" value="" onchange="formatCurrency(this.value,'txt_cantidadBono');"/>
				</td>
				<td width="175" align="right">Fecha</td>
				<td>
					<input name="txt_fecha" id="txt_fecha" type="text" class="caja_de_texto" size="10" value="" readonly="readonly" />
				</td>
			</tr>
			<tr>
				<td align="right">*Autoriz&oacute;</td>
				<td>
					<input name="txt_autorizo" id="txt_autorizo" type="text" class="caja_de_texto" size="40" maxlength="60" 
					onkeypress="return permite(event,'num_car',3);" value="ING. GUILLERMO MARTÍNEZ ROMÁN" ondblclick="this.value='';" />
				</td> 
				<td>&nbsp;</td>
				<td>&nbsp;</td>                        
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>  
			<tr>
				<td colspan="4" align="center">
					<input type="hidden" name="hdn_botonSelect" id="hdn_botonSelect" value="" />
					
					<input name="sbt_guardarBono" id="sbt_guardarBono" type="submit" class="botones" value="Guardar Bono" title="Guardar Datos del Bono" 
					onmouseover="window.status='';return true" onclick="hdn_botonSelect.value='guardar'" disabled="disabled" />
					&nbsp;&nbsp;&nbsp;
					<input name="sbt_modificarBono" id="sbt_modificarBono" type="submit" class="botones" value="Modificar Bono" title="Modificar Datos del Bono Seleccionado" 
					onmouseover="window.status='';return true" onclick="hdn_botonSelect.value='modificar'" disabled="disabled" />
					&nbsp;&nbsp;&nbsp;
					<input name="sbt_eliminarBono" id="sbt_eliminarBono" type="submit" class="botones" value="Eliminar Bono" title="Eliminar Bono Seleccionado" 
					onmouseover="window.status='';return true" onclick="hdn_botonSelect.value='eliminar'" disabled="disabled" />
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" id="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" onclick="limpiarFrm();" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" id="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Administrativo"
					onclick="confirmarSalida('menu_administrativo.php');" />
				</td>
			</tr>        
		</table>
		</form>
		</fieldset>
		
		<div id="calendarioIni">
			<input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png" onclick="displayCalendar(document.frm_gestionarBonos.txt_fecha,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom"  title="Seleccionar la Fecha de Registro del Bono" /> 
		</div><?php
	
	}//Cierre if($verForm==1) ?>
       
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
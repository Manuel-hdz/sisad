<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarObra.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;	width:362px;height:20px;z-index:11;}
		#tabla-seleccionarObra {position:absolute;left:30px;top:190px;width:451px;height:176px;z-index:12;}
		#calendarioObra {position:absolute;left:733px;top:268px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>	
	
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Seleccionar el Nombre y Tipo de Obra a Modificar</div>
		
	<fieldset class="borde_seccion" id="tabla-seleccionarObra" name="tabla-seleccionarObra">
	<legend class="titulo_etiqueta">Seleccionar la Obra a Modificar</legend>	
	<br>
	<form onSubmit="return valFormSeleccionarObra(this);" name="frm_modificarObra" method="post" action="frm_modificarObra.php">
	<table cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="139"><div align="right">*Tipo de Obra</div></td>
			<td width="284"><?php									
				$res = cargarComboConId("cmb_tipoObra","tipo_obra","tipo_obra","obras","bd_topografia","Tipo Obra","",
										"cargarCombo(this.value, 'bd_topografia', 'obras', 'nombre_obra', 'tipo_obra', 'cmb_nomObra', 'Obras', '')");									
				if($res==0){?>
					<label class="msje_correcto"><u><strong>NO</strong></u> Hay Tipos de Obras Registradas</label>
					<input type="hidden" name="cmb_tipoObra" id="cmb_tipoObra" value="" /><?php 
				} ?>		  	</td>
		</tr>
		<tr>
			<td><div align="right">*Nombre de la Obra</div></td>
			<td>
				<select name="cmb_nomObra" id="cmb_nomObra" class="combo_box" >
					<option value="">Obras</option>
				</select>			</td>
		</tr>
				<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>

		</tr>
		<tr>
		<!--onchange="document.frm_modificarObra.submit();"-->
			<td colspan="4" align="center">
				<input name="sbt_seleccionarObra" type="submit" class="botones" id="sbt_seleccionarObra"  value="Seleccionar" 
                title="Seleccionar la Información de la Obra a ser Modificada" 
				onmouseover="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Obra " 
				onmouseover="window.status='';return true" onclick="confirmarSalida('menu_Obras.php');"/>
                &nbsp;&nbsp;&nbsp;
		</td>
		</tr>
	</table>
	</form>
</fieldset>
	
	
	<?php /*  //Si esta definida la variable $cmb_nomObra y es diferente de vacia, mandar llamar la funcion que carga el combo cuando la pagina se recarga 
	if(isset($_POST['cmb_nomObra']) && $_POST['cmb_nomObra']!="") {?>
		<script type="text/javascript" language="javascript">
			cargarCombo("<?php echo $cmb_tipoObra; ?>", "bd_topografia", "obras", "nombre_obra", "tipo_obra", "cmb_nomObra", "Obras", "<?php echo $cmb_nomObra?>");
		</script><?php
	} */?>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
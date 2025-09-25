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
		include ("op_modificarObraeqPesado.php");?><head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />.
	<script type="text/javascript" src="../../includes/ajax/validarCambioDato.js"></script>

    

<style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:362px;height:20px;z-index:11;}
		#tabla-seleccionarObra {position:absolute;left:30px;top:190px;width:676px;height:160px;z-index:12;}
		#calendarioObra {position:absolute;left:733px;top:268px;width:30px;height:26px;z-index:13;}
		#tabla-registrarObra {position:absolute;left:30px;top:190px;width:723px;height:263px;z-index:14;}
		-->
    </style>
</head>
<body>	
	
	<?php
	if(!isset($_POST["sbt_seleccionarObra"])){
	?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-modificar">Seleccionar el Nombre y Tipo de Obra a Modificar</div>
			
		<fieldset class="borde_seccion" id="tabla-seleccionarObra" name="tabla-seleccionarObra">
		<legend class="titulo_etiqueta">Seleccionar la Obra de Equipo a Modificar</legend>	
		<br>
		<form onSubmit="return valFormSeleccionarObraEq(this);" name="frm_modificarObraEqP" method="post" action="frm_modificarEqPesado.php">
		<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td width="122"><div align="right">*Tipo de Equipo </div></td>
				<td width="517"><?php									
					$res = cargarComboConId("cmb_tipoObraEqP","fam_equipo","fam_equipo","equipo_pesado","bd_topografia","Tipo Equipo","",
											"cargarCombo(this.value, 'bd_topografia', 'equipo_pesado', 'concepto', 'fam_equipo', 'cmb_nomObraEq', 'Obras Equipo Pesado', '')");									
					if($res==0){?>
						<label class="msje_correcto"><u><strong>NO</strong></u> Hay Tipos de Obras Registradas</label>
			  <input type="hidden" name="cmb_tipoObraEqP" id="cmb_tipoObraEqP" value="" /><?php 
					} ?>		  	</td>
			</tr>
			<tr>
				<td><div align="right">*Nombre de la Obra de Equipo </div></td>
				<td>
					<select name="cmb_nomObraEq" id="cmb_nomObraEq" class="combo_box" >
						<option value="">Obras Equipo Pesado</option>
					</select>
				</td>
			</tr>
	
			<tr>
				<td colspan="4" align="center">
					<input name="sbt_seleccionarObra" type="submit" class="botones" id="sbt_seleccionarObra"  value="Seleccionar" 
					title="Seleccionar la Información de la Obra a ser Modificada" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Obras con Equipo Pesado" 
					onmouseover="window.status='';return true" onclick="location.href='menu_equipos.php';"/>
					&nbsp;&nbsp;&nbsp;
			</td>
			</tr>
		</table>
		</form>
		</fieldset>
	<?php
	}
	else{?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-modificar">Modificar Obra de Equipo Pesado</div>
	<?php 
		mostrarObraEqPesado();
	}?>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
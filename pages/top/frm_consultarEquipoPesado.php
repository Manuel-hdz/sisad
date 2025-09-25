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
		include("head_menu.php");
		include("op_consultarEquipoPesado.php");
		
	?>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:362px;height:20px;z-index:11;}
		#tabla-seleccionarObra {position:absolute;left:30px;top:190px;width:676px;height:160px;z-index:12;}
		#detalle_traspaleo {position:absolute;left:30px;top:190px;width:900px;height:420px;z-index:13;overflow:scroll;}
		#btn-regresar {position:absolute;left:30px;top:660px;width:930px;height:40px;z-index:14;}
		-->
    </style>
</head>
<body>	
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Consulta de Obras con Equipo Pesado </div>
	
	<?php
	if(!isset($_POST["sbt_seleccionarObra"])){
	?>
		<fieldset class="borde_seccion" id="tabla-seleccionarObra" name="tabla-seleccionarObra">
		<legend class="titulo_etiqueta">Seleccionar la Obra de Equipo a Consultar</legend>	
		<br>
		<form onSubmit="return valFormSeleccionarObraEq(this);" name="frm_modificarObraEqP" method="post" action="frm_consultarEquipoPesado.php">
		<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
			  <td width="122"><div align="right">*Tipo de Equipo</div></td>
				<td width="517"><?php									
					$res = cargarComboConId("cmb_tipoObraEqP","fam_equipo","fam_equipo","equipo_pesado","bd_topografia","Tipo Equipo","",
											"cargarComboConId(this.value,'bd_topografia','equipo_pesado','concepto','id_registro','fam_equipo','cmb_nomObraEq','Obras Equipo Pesado','')");									
					if($res==0){
					?>
						<label class="msje_correcto"><u><strong>NO</strong></u> Hay Tipos de Obras Registradas</label>
						<input type="hidden" name="cmb_tipoObraEqP" id="cmb_tipoObraEqP" value="" />
					<?php 
					}?>
				</td>
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
					title="Seleccionar la Información de la Obra para ver sus Registros" onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Obras con Equipo Pesado" 
					onmouseover="window.status='';return true" onclick="location.href='menu_equipoPesado.php';"/>
					&nbsp;&nbsp;&nbsp;
			</td>
			</tr>
		</table>
		</form>
		</fieldset>
	<?php
	}
	else{
		?>
		<form name="frm_consultarEquipoPesado" method="post" action="frm_consultarEquipoPesado.php">
			<div id='detalle_traspaleo' class='borde_seccion2' align="center">
				<?php $res=mostrarRegistros();?>
			</div>
			<div id='btn-regresar' align="center">
			<table cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td>
						<input name="btn_regresar2" type="button" class="botones" value="Regresar" onclick="location.href='frm_consultarEquipoPesado.php';"
						title="Regresar a la Consulta de Traspaleos"/>
						             
					</td>
				</tr>
			</table>
			</div>
		</form>
		<?php
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografías
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include ("op_consultarEqPesado.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>	
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
		<!--
			#titulo-consultarObra  {position:absolute;left:30px;top:146px;width:253px;height:20px;z-index:11;}
			#tabla-consultarObraFechas {position:absolute;left:30px;top:190px;width:409px;height:171px;z-index:12;}
			#tabla-seleccionarObra {position:absolute;left:30px;top:400px;width:676px;height:160px;z-index:12;}
			#calendario-Ini {position:absolute;left:299px;top:232px;width:30px;height:26px;z-index:13;}
			#calendario-Fin {position:absolute;left:298px;top:270px;width:30px;height:26px;z-index:14;}
			#detalle-consultarObra {position:absolute;left:28px;top:200px;width:921px;height:370px;z-index:17;overflow:scroll;}
			#btns-regpdf { position: absolute; left:30px; top:630px; width:940px; height:35px; }
		-->
    </style>
</head>
<body>
	<?php
	//Obtener la fecha del sistema para la fecha inicio y fecha fin
	$txt_fechaIni = date("d/m/Y", strtotime("-30 day"));
	$txt_fechaFin = date("d/m/Y");?>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultarObra">Consultar Obras de Equipo Pesado </div>
    
    <?php if(!isset($_POST['sbt_buscarObraFecha']) && !isset($_POST['sbt_seleccionarObra'])){?>
	<fieldset class="borde_seccion" id="tabla-consultarObraFechas" name="tabla-consultarObraFechas">
	<legend class="titulo_etiqueta">Seleccione Fechas en las que se Registro la Obra de Equipo </legend>	
	<br>
	<form onSubmit="return valFormFechasObras(this);" name="frm_consultarObraFecha" method="post" action="frm_consultarEqPesado.php">
    <table width="418" height="36" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  <td width="142"><div align="right">Fecha Inicio</div></td>
			<td width="239"><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10"value="<?php echo $txt_fechaIni;?>" readonly="readonly"/></td>
		</tr>
		<tr>
			<td><div align="right">Fecha Fin </div></td>
			<td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaFin;?>" readonly="readonly"/></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input name="sbt_buscarObraFecha" type="submit" class="botones" id="sbt_buscarObraFecha" 
				title="Buscar Informacion de Obras Registradas" onmouseover="window.status='';return true" value="Buscar"/>
				&nbsp;&nbsp;			
				<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Restablecer" title="Limpiar Formulario" 
				onmouseover="window.status='';return true"/>
				&nbsp;&nbsp;
				<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_equipos.php';"
				title="Regresar al men&uacute; de Obra"/>
			</td>
		</tr>
    </table>
    </form>   
	</fieldset>
	
	<fieldset class="borde_seccion" id="tabla-seleccionarObra" name="tabla-seleccionarObra">
	<legend class="titulo_etiqueta">Seleccionar la Obra de Equipo a Consultar </legend>	
	<br>
	<form onSubmit="return valFormSeleccionarObraEq(this);" name="frm_modificarObraEqP" method="post" action="frm_consultarEqPesado.php">
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
				<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Restablecer" title="Limpiar Formulario" 
				onmouseover="window.status='';return true"/>
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Obras con Equipo Pesado" 
				onmouseover="window.status='';return true" onclick="location.href='menu_equipos.php';"/>
		</td>
		</tr>
	</table>
	</form>
	</fieldset>


	<div id="calendario-Ini">
		<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_consultarObraFecha.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
		title="Seleccionar Fecha de Inicio"/> 
	</div>
    
	<div id="calendario-Fin">
		<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_consultarObraFecha.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
		title="Seleccionar Fecha de Fin"/> 
	</div>
	<?php
	}
	
	//Si esta definido sbt_buscarObraFecha, sbt_buscarObraTipo se muestran las tipos de obras registradas 
	if(isset($_POST['sbt_buscarObraFecha']) || isset ($_POST['sbt_seleccionarObra'])){?> 
		<div id='detalle-consultarObra' class='borde_seccion2' align="center">
			<?php
			mostrarObrasEq();
			?>
		</div>
	<?php }?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
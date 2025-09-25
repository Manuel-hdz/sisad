<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Este archivo contiene las funciones para mostrar las Ordenes de Compra registradas y el detalle de las mismas
		include ("op_cargarInventario.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>	
    <style type="text/css">
		<!--
		#titulo-salida { position:absolute; left:15px; top:146px; width:141px; height:19px; z-index:11; }
		#form-exp-inv { position:absolute; left:30px; top:190px; width:370px; height:120px; z-index:13; }
		#form-imp-inv { position:absolute; left:500px; top:190px; width:370px; height:120px; z-index:14; }
		#form-imp-inv-total { position:absolute; left:30px; top:350px; width:370px; height:120px; z-index:15; }

		#titulo-reporteInventario { position:absolute; left:30px; top:146px; width:236px; height:19px; z-index:11; }
		#tabla-inventario { position:absolute; left:33px; top:340px; width:920px; height:320px; z-index:12; overflow:scroll; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-reporteInventario">Reporte de Inventario </div>
	
	<fieldset id="form-exp-inv" class="borde_seccion">	
	<legend class="titulo_etiqueta">Seleccionar la Categor&iacute;a a Exportar </legend>
	<br>
	<form name="frm_exportarCSV" action="guardar_reporte.php" method="post" onsubmit="return valFormExportarCSV(this);">
	<table class="tabla_frm" width='100%' cellpadding="5">
		<tr>
		  <td width="24%"><div align="right">Categor&iacute;a</div></td>
			<td width="76%">
			  <?php $lnArt= cargarCombo("cmb_lineaArticulo","linea_articulo","materiales","bd_almacen","Categor&iacute;a","");?>			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<br/>
				<input name="sbt_exportarCSV" id="sbt_exportarCSV" type="submit" class="botones" value="Exportar CSV" 
				onMouseOver="window.status='';return true" title="Exportar Archivo con los Datos de la L&iacute;nea Seleccionada" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Materiales" onClick="location.href='menu_material.php'" />
	      </td>
		</tr>
	</table>    
	</form>
</fieldset>
	
	<fieldset id="form-imp-inv" class="borde_seccion">	
	<legend class="titulo_etiqueta">Ingrese el Archivo a Importar</legend>
	<br>
	<form name="frm_importarCSV" action="frm_inventario.php" method="post" enctype="multipart/form-data" onsubmit="return valFormImportarCSV(this);">
	<table class="tabla_frm" width='100%' cellpadding="5">
		<tr>
		  <td width="24%"><div align="right">Archivo</div></td>
			<td width="76%">
				<input type="file" name="txt_archivo" id="txt_archivo" onchange="validarCSV(this);"/>
				<input type="hidden" name="hdn_docValido" id="hdn_docValido" value="no"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<br/>
				<input name="sbt_importarCSV" id="sbt_importarCSV" type="submit" class="botones" value="Importar CSV" 
				onMouseOver="window.status='';return true" title="Importar Archivo para Actualizar el Inventario" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Materiales" onClick="location.href='menu_material.php'" />
	      </td>
		</tr>
	</table>    
	</form>
	</fieldset>
	
	<fieldset id="form-imp-inv-total" class="borde_seccion">	
	<legend class="titulo_etiqueta">Ingrese el Archivo a Importar</legend>
	<br>
	<form name="frm_importarCSV" action="frm_inventario.php" method="post" enctype="multipart/form-data" onsubmit="return valFormImportarCSV(this);">
	<table class="tabla_frm" width='100%' cellpadding="5">
		<tr>
		  <td width="24%"><div align="right">Archivo</div></td>
			<td width="76%">
				<input type="file" name="txt_archivo" id="txt_archivo" onchange="validarCSV(this);"/>
				<input type="hidden" name="hdn_docValido" id="hdn_docValido" value="no"/>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<br/>
				<input name="sbt_importarCSV2" id="sbt_importarCSV2" type="submit" class="botones_largos" value="Importar Inventario" 
				onMouseOver="window.status='';return true" title="Importar Archivo para Renovar el Inventario"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Materiales" onClick="location.href='menu_material.php'" />
	      </td>
		</tr>
	</table>    
	</form>
	</fieldset>
	<?php
	if(isset($_POST["sbt_importarCSV"])){
		$res=cargarInventario();
		$resultados=explode("¬",$res);
		$ok=$resultados[0];
		$error=$resultados[1];
		?>
		<script type="text/javascript" language="javascript">
			setTimeout("mensaje()",1000);
			function mensaje(){
				alert("Materiales Actualizados: '<?php echo $ok?>' \nMateriales No Actualizados: '<?php echo $error?>'");
			}
		</script>
		<?php
		
	}
	if(isset($_POST["sbt_importarCSV2"])){
		$res=cargarInventarioCompleto();
		$resultados=explode("¬",$res);
		$ok=$resultados[0];
		$error=$resultados[1];
		?>
		<script type="text/javascript" language="javascript">
			setTimeout("mensaje()",1000);
			function mensaje(){
				alert("Materiales Actualizados: '<?php echo $ok?>' \nMateriales No Actualizados: '<?php echo $error?>'");
			}
		</script>
		<?php
		
	}
	?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
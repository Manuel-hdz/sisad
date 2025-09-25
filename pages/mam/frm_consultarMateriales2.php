<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Archivo con la operacion de Consultar Equipo
		include ("op_consultasExternas.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider_materialDock.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	
	<script type="text/javascript" language="javascript">
		function limpiarCapas(){
			var cant=<?php echo $_GET["cant"]?>;
			var cont=1;
			do{
				document.getElementById("capa"+cont).innerHTML="";
				document.getElementById("cmb_materialP"+cont).value="";
				cont++;
			}while(cont<=cant);
		}
	</script>

    <style type="text/css">
		<!--
		#titulo-consultar { position:absolute; left:30px; top:146px; width:187px; height:20px; z-index:11; }
		#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
		#tabla-consultarMateriales{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; padding:15px; padding-top:0px; overflow:scroll;}
		#res-spider {position:fixed;left:250px;z-index:30;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Consultar Materiales</div>
	
	<div class="borde_seccion2" id="tabla-consultarMateriales" >
	<p class="titulo_etiqueta" align="center">Materiales</p>
	<table class="tabla_frm" cellpadding="5" width="100%">
	<?php
		$cant=$_GET["cant"];
		$cont=0;
		do{
			?>
			<tr>
				<td width="10%" valign="bottom">
					Material <?php echo $cont+1;?><br> 
					<input type="text" name="cmb_materialP<?php echo $cont+1?>" id="cmb_materialP<?php echo $cont+1?>" onkeyup="lookup(this,<?php echo $cont+1;?>,'1');" 
						value="" size="30" maxlength="60" onkeypress="return permite(event,'num_car',0);" tabindex="<?php echo $cont+1;?>"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
								<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>
				</td>
				<td valign="bottom">
					<div id='capa<?php echo $cont+1;?>'></div>
				</td>
			</tr>
			<?php
			$cont++;
		}while($cont<$cant);
	?>
	</table>
	</div>
	
	<div id="botones" align="center">
		<input type="reset" name="btn_limpiar" title="Restablecer los Resultados" value="Restablecer" onclick="limpiarCapas();" class="botones"/>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" name="btn_regresar" title="Regresar a Consultar el Stock" onclick="location.href='frm_consultarMateriales.php';" value="Regresar" class="botones" onmouseover="window.status='';return true;"/>
	</div>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
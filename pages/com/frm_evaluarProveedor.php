<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo muestra los datos históricos de la Evaluación de Proveedores
		include ("op_evaluarProveedor.php");
	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
		<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
		<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
		<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
		<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
		<script type="text/javascript" src="../../includes/maxLength.js" ></script>
    	
		<style type="text/css">
			<!--
			#titulo-evaluar { position:absolute; left:30px; top:146px; width:155px;	height:21px; z-index:11; }
			#tabla-evaluar-prov { position:absolute; left:30px; top:190px; width:565px; height:143px; z-index:12;}
			#botones{position:absolute;left:30px;top:650px;width:999px;height:37px;z-index:13;}
			#mostrar-evaluacion {position:absolute; left:30px; top:190px; width:900px; height:400px; z-index:12; overflow:scroll;}
			 -->
    	</style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-evaluar">Evaluar Proveedor  </div>
    <?php if (!isset($_POST["sbt_consultar"])) {?>	
		<fieldset class="borde_seccion" id="tabla-evaluar-prov" name="tabla-evaluar-prov">
		<legend class="titulo_etiqueta">Evaluación de Proveedores</legend>	
		<br>
    	<form onSubmit="return valFormevaluarProveedor(this);" name="frm_evaluarProveedor" method="post" action=""> 
		<p class="titulo_etiqueta">Buscar Proveedor por Nombre</p>
    		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
       			<tr valign="top">
        			<td><div align="right">Nombre</div></td>
	        		<td>
                    	<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');" value=""
                    	size="30" maxlength="80" onkeypress="return permite(event,'num_car',0);" />
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
		    			</div>
	   		    	<td>
                    	<input name="sbt_consultar" type="submit" value="Buscar" class="botones" title="Buscar Información del Proveedor Seleccionado"
                        onmouseover="window.status='';return true" />
                    </td>
       				<td>
                    	<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar a la página de Inicio de Compras" 
                    	onclick="location.href='inicio_compras.php'" />
                    </td>
				</tr>
			</table>   		
		</form>
		</fieldset>	
	<?php }
	else{
		echo "<div id='mostrar-evaluacion' align='center' class='borde_seccion2'>";
		$existe=mostrarEvaluacion();
		echo "</div>";
		?>
			<div id="botones" align="center">
			<form method="post" action="frm_evaluarProveedor2.php">
				<input type="hidden" name="hdn_proveedor" value="<?php echo $_POST["txt_nombre"];?>"/>
				<?php //Si el valor de existe es igual a 1, el proveedor esta registrado en el sistema y se muestra el boton que permite hacer la evaluacion?>
				<?php if ($existe==1){?>
					<input name="sbt_evaluar" type="submit" value="Evaluar" class="botones" title="Evaluar al Proveedor Seleccionado" 
    	            onmouseover="window.status='';return true"/>&nbsp;&nbsp;&nbsp;&nbsp;
				<?php }?>
    	   		<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar a la página de Inicio de Compras" 
                onclick="location.href='frm_evaluarProveedor.php'"/>
			</form>
			</div>
		<?php
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
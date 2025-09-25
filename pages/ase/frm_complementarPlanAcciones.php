<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento de Calidad
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_complementarPlanAcciones.php");?>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
			<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
			<script type="text/javascript" src="../../includes/validacionAseguramiento.js" ></script>
			<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
			<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
			<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	
		<style type="text/css">
			<!--
				#titulo-modificar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
				#tabla-resultados1 { position:absolute; left:30px; top:190px; width:945px; height:340px; z-index:21; overflow:scroll; }
				#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
				#tabla-modificarDocumento {position:absolute;left:30px;top:190px;width:546px;height:128px;z-index:12;}
				#titulo-registrar {position:absolute;left:30px;top:146px;	width:352px;height:20px;z-index:11;}
				#tabla-agregarRegistro {position:absolute;left:30px;top:190px;width:764px;height:121px;z-index:12;}
				#tabla-agregarRegistro2 {position:absolute;left:32px;top:349px;width:764px;height:170px;z-index:12;}
			-->
		</style>
	</head>
<body>
 
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Complementar Plan Acciones </div>
	<?php 
		//Verificamos la procedencia de la pagina y verificar la validacion correspondiente
		if(isset($_GET['band'])&&!isset($_POST['rdb_id'])){
			$onsubmit="return valFormReferenciasConGet(this)";
			$url='inicio_aseguramiento.php';
		}
		if(isset($_POST['rdb_id'])){
			$onsubmit="return valFormReferencias(this)";
			$url='frm_modificarPlanAcciones.php?depto='.$_POST['hdn_depto'];	
		}
	?>
	<form onsubmit="<?php echo $onsubmit;?>" name="frm_complementarPA" id="frm_complementarPA" method="post" action="op_complementarPlanAcciones.php" 
		enctype="multipart/form-data"><?php 
		echo"<div id='tabla-resultados1' class='borde_seccion2' align='center'>";
		//Si $ band regresa 0 la consulta no trajo y saber que botones se deben de mostrar	
		if((isset($_GET['band'])||isset($_GET['url']))&&!isset($_POST['rdb_id'])){
			$band=mostrarRegistrosRegistrarSinRDB();
		}
		if((isset($_GET['band'])||isset($_GET['url']))&&isset($_POST['rdb_id'])){
			$band=mostrarRegistrosRegistrar();
		}?>
		</div>
		<div id="btns-regpdf" align="center">
		<?php if($band!=0){?>
			<input type="submit" name="sbt_guardar" id="sbt_guardar" value="Guardar" class="botones" title="Complementar Registro Plan Acciones" 
			onMouseOver="window.estatus='';return true"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onclick="desabilitar();"  
            onmouseover="window.status='';return true"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la Pantalla Anterior" 
			onMouseOver="window.status='';return true" onclick="location.href='<?php echo $url;?>'" />
  </div>
	<?php }
		else{?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Auditoria" 
			onMouseOver="window.status='';return true" onclick="location.href='<?php echo $url;?>'" />
			</div>
		<?php 
		}?>
</form>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
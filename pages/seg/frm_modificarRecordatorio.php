<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarRecordatorio.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript">
	$(document).ready(function(){
			$("#tabla-resultados").dataTable({
				"sPaginationType": "scrolling"
			});
	});
	</script>
    <style type="text/css">
		<!--
			#titulo-modificar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
			#tabla-resultados1 { position:absolute; left:30px; top:190px; width:945px; height:340px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
			#tabla-modificarRecordatorio {position:absolute;left:30px;top:190px;width:452px;height:139px;z-index:12;}
			#titulo-modificar {position:absolute;left:30px;top:146px;	width:352px;height:20px;z-index:11;}
			#calendario{position:absolute;left:687px;top:284px;width:30px;height:26px;z-index:13;}

		-->
    </style>
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Recordatorios </div>
	<form  onsubmit="return valFormRecordatorio(this);" name="frm_modificarRec" id="frm_modificarRec" method="post" action="frm_modificarRecordatorio2.php"><?php 
		echo"<div id='tabla-resultados1' class='borde_seccion2' align='center'>";
		//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de eliminar
		$band=mostrarRecordatorios();
		echo "</div>";?>
		<div id="btns-regpdf" align="center">
		<?php if($band!=0){?>
			<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="" />
			<input type="submit" name="sbt_eliminar" id="sbt_eliminar" value="Eliminar" class="botones" title="Eliminar Recordatorio" 
			onMouseOver="window.estatus='';return true" onclick="hdn_botonSel.value='eliminar'"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="sbt_modificar"  id="sbt_modificar" value="Modificar" class="botones" title="Modificar Recordatorio" 
			onMouseOver="window.estatus='';return true" onclick="hdn_botonSel.value='modificar'"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Recordatorios" 
			onMouseOver="window.status='';return true" onclick="location.href='menu_recordatorios.php'" />
  </div>
	<?php }
		else{?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Recordatorios" 
			onMouseOver="window.status='';return true" onclick="location.href='menu_recordatorios.php'" />
			</div>
		<?php 
		}
	?>
</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
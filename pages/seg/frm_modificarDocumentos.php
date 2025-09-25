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
		include ("op_modificarDocumentos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
			$("#tabla-resultadosDocumentos").dataTable({
				"sPaginationType": "scrolling"
			});
	});
	</script>

    <style type="text/css">
		<!--
			#titulo-modificar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
			#tabla-documentos { position:absolute; left:30px; top:190px; width:945px; height:340px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
			#tabla-modificarDocumento {position:absolute;left:30px;top:190px;width:904px;height:339px;z-index:12;}
			#calendario{position:absolute;left:474px;top:235px;width:30px;height:26px;z-index:13;}

		-->
    </style>
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Documentos </div>
	<?php 
	if(!isset($_POST["sbt_modificar"])&&!isset($_POST["sbt_guardar"])){?>
    <form  onsubmit="return valFormArchivo(this);"name="frm_modificarDocumento" id="frm_modificarDocumento" method="post" ><?php 
		echo"<div id='tabla-documentos' class='borde_seccion2' align='center'>";
		//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de eliminar
		$band=mostrarDocumentos();
		echo "</div>";?>
		<div id="btns-regpdf" align="center">
		<?php if($band!=0){?>
			<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Documento" 
			onMouseOver="window.estatus='';return true"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Repositorio" 
			onMouseOver="window.status='';return true" onclick="location.href='menu_repositorio.php'" />
			</div>
	<?php }
		else{?>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Repositorio" 
			onMouseOver="window.status='';return true" onclick="location.href='menu_repositorio.php'" />
			</div>
		<?php 
		}
	}
	else{
		modificarRegistroSeleccionado();
	}
	?>
	</form>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
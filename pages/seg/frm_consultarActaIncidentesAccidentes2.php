<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_consultarActaIncidentesAccidentes.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	
	<script type="text/javascript">
	$(document).ready(function(){
			$("#tabla-resultadosDocumentos").dataTable({
				"sPaginationType": "scrolling"
			});
	});
	</script>

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:382px;height:20px;z-index:11;}
		#tabla-mostrarActas {position:absolute;left:30px;top:190px;width:937px;height:351px;z-index:12; overflow:scroll}
		#tabla-mostrarDetalleActas {position:absolute;left:30px;top:190px;width:937px;height:130px;z-index:12;}
		#btns-regpdf { position: absolute; left:30px; top:600px; width:979px; height:40px; z-index:13; }
		#botonesConsultas { position:absolute; left:30px; top:360px; width:945px; height:40px; z-index:25;}	
		#detalles { position:absolute; left:30px; top:400px; width:935px; height:230px; z-index:21; overflow:scroll; }	
		#btn-regresar { position: absolute; left:290px; top:590px; width:400px; height:40px; z-index:23; }
	-->
    </style>
	
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
</head>
<body>
	<?php if(isset($_GET['sbt_consultar'])){
		$_POST['sbt_consultar'] =$_GET['sbt_consultar'];
	}?>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Consultar Informe de Accidentes e Incidentes </div>
	<?php if(isset($_POST['sbt_consultar'])){?>
	    <form name="frm_consultarActa" id="frm_consultarActa" method="post" action="frm_consultarActaSeguridadHigiene2.php">
    	 <div id='tabla-mostrarActas' class='borde_seccion2' align='center'><?php 
			//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de eliminar
			$band=mostrarInforme();?>
		</div>
	     <div id="btn-regresar" align="center">
	         <input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Nuevos Parámetros de Consulta" 
			onMouseOver="window.status='';return true" onclick="location.href='frm_consultarActaIncidentesAccidentes.php'" />
          </div></form>
	<?php 
	}?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		//Manejo de funciones que permiten mostrar los recorridos registrados dependiendo de cada ddepartamento
		include ("op_consultarRecSeg.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

    <style type="text/css">
		<!--
		#titulo-regRecorrido{position:absolute; left:30px; top:146px; width:268px; height:19px; z-index:11; }
		#tabla-recorridos{position:absolute; left:30px; top:180px; width:940px; height:340px; z-index:12; overflow:scroll;}
		#botones{position:absolute;left:30px;top:569px;width:984px;height:37px;z-index:17;}
		#botoneAprobar{position:absolute;left:30px;top:569px;width:984px;height:37px;z-index:17;}
		-->
    </style>
</head>
<body>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-regRecorrido">Recorridos Seguridad </div>
	
	<form name="frm_recorrido" id="frm_recorridos" action="op_consultarRecSeg.php" method="post"><?php 
		if(isset($_GET['idAlerta'])){?>				
			<div id="tabla-recorridos" class="borde_seccion2"><?php 
				mostrarRegistros($_GET['idAlerta']);?>
			</div>
			<div id="botones" align="center">
				<input name="sbt_desactivar" type="submit" class="botones_largos" value="Desactivar Alerta (s)" 
				title="Desactivar Alertas" onclick="location.href='inicio_seguridad.php'" onmouseover="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_regresar" type="button" class="botones" value="Regresar" 
				title="Regresar Al Inicio de Almac&eacute;n" onclick="location.href='inicio_paileria.php'" onmouseover="window.status='';return true" />
			</div>
			</div><?php 
		}?>
	</form>	
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
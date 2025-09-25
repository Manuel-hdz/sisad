<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_consultarPlanAcciones.php");?>

<head>

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
		<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="../../includes/validacionAseguramiento.js" ></script>

    <style type="text/css">
		<!--
			#titulo-modificar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
			#tabla-resultados { position:absolute; left:30px; top:190px; width:945px; height:340px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>
<?php if(isset($_GET['url'])){
		$url="inicio_aseguramiento.php";
	}?>
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Plan de Acci&oacute;n Registrado</div>
	<form onsubmit="return valFormPlanAlertas(this);" name="frm_verRec" method="post" action="frm_complementarPlanAcciones.php?band=1&url=<?php echo $url;?>"><?php 
		echo"<div id='tabla-resultados' class='borde_seccion2' align='center'>";
		//Mostramos los Recordatorios
		$res=mostrarPlanAcciones();
		echo "</div>";?>
		<div id="btns-regpdf" align="center">
			<?php if($res!=0){?>
 				<input type="submit" name="sbt_modificar" id="sbt_modificar" value="Complementar" class="botones" title="Complementar Registro Plan Acciones" 
				onMouseOver="window.estatus='';return true"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php }?>
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar Men&uacute; Inicio" 
			onMouseOver="window.status='';return true" onclick="location.href='inicio_aseguramiento.php'" />
	  </div>
	</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
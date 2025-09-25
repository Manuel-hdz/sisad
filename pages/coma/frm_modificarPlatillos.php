<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	include ("op_modificarplatillo.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionComaro.js" ></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#modificar-platillos {position:absolute;left:30px;top:190px;width:600px;height:160px;z-index:12;}
		#tabla-platillos {position:absolute;left:30px;top:190px;width:920px;height:400px;z-index:12; overflow:scroll;}
		#botones-platillos {position:absolute;left:30px;top:640px;width:920px;height:30px;z-index:12;}
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg-comaro.png" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Modificar Platillos del Men&uacute;</div>
		
	<?php
	//Verificar si se debe guardar un registro
	if(isset($_POST["sbt_guardar"])){
		modificarPlatillo();?>
		<div class="titulo_etiqueta" id="procesando">
    		<div align="center">
        		<p><img src="../../images/loading-comaro.gif" width="140" height="140"  /></p>
        		<p>Procesando...</p>
	      	</div>
		</div><?php 
	} else if(isset($_POST["sbt_continuar"])){
	?>
		<fieldset class="borde_seccion" id="modificar-platillos" name="modificar-platillos">
		<legend class="titulo_etiqueta">Ingresar Datos del Platillo</legend>	
		<br>
		<form name="frm_modificarPlatillos" method="post" action="frm_modificarPlatillos.php" onsubmit="return valFormModificarPlatillo(this)">
			<?php consultarPlatillo($_POST["rdb_idPlatillo"]); ?>
		</form>
		</fieldset>
		<script>txa_descripcion.focus();</script>
	<?php
	} else {
	?>
		<form name="frm_modificarPlatillos" method="post" action="frm_modificarPlatillos.php" onsubmit="return valFormConsultaPlatillos(this)">
			<div id="tabla-platillos" class="borde_seccion2" align="center"> 
				<?php
				mostrarPlatillos();
				?>
			</div>
			<div id="botones-platillos" align="center">
				<input type="submit" class="botones" name="sbt_continuar" id="sbt_continuar" value="Continuar" title="Registrar el Platllo en el Men&uacute;" onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; de Platillos" onclick="location.href='menu_platillos.php'"/>
			</div>
		</form>
	<?php
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
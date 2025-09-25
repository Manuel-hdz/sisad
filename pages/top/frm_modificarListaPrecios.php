<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php"); 
		include ("op_listaPrecios.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/jsColor/jscolor.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:210px;height:20px;z-index:11;}
		#mostrarLista {position:absolute;left:30px;top:190px;width:923px;height:415px;z-index:14; overflow:scroll}
		#btns {position:absolute;left:30px;top:669px;width:923px;height:39px;z-index:14; }
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Lista de Precios</div>
	<br><?php
	if(isset($_POST['sbt_modificarPrecios']))
		modificarPrecios();
	else{?>
		<form onSubmit="return valFormModificarPrecios(this);" name="frm_modificarListaPrecios" method="post" action="frm_modificarListaPrecios.php">
			<div id='mostrarLista' class='borde_seccion2' align="center"><?php 
				if(!isset($_POST['sbt_modificarPrecios']))
					modificarListaPrecios();?>		
			</div>
			<div id='btns' align="center"> 
				<input name="sbt_modificarPrecios" type="submit" class="botones" id="sbt_modificarPrecios"  value="Modificar" 
				title="Modificar los Precios del Traspaleo Seleccionado" 
				onmouseover="window.status='';return true"/>
				<input type="hidden" name="hdn_idTraspaleo" id="hdn_idTraspaleo" value="<?php echo $_POST['cmb_tipoTraspaleo'];?>"/>
				&nbsp;&nbsp;&nbsp;
				<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='frm_listaPrecios.php';"
				title="Regresar al Formulario de Lista Precios"/></div>
			</div>  
		</form><?php
	} ?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
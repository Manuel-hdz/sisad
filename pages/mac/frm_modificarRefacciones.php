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
		//Archivo con la Operacionn de Modificar las refacciones del Equipo
		include ("op_modificarEquipo.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:254px;height:20px;z-index:11;}
		#tabla-modificarRefacciones { position:absolute; left:30px; top:190px; width:900px; height:200px; z-index:12; overflow:scroll;}
		#botones{position:absolute;left:30px;top:435px;width:900px;height:37px;z-index:13;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Refacciones de Equipo</div><?php 
	if(isset($_GET['cancelarRefac']) && (isset($_SESSION['refacciones'])))
		unset($_SESSION['refacciones']); 
	
	//Si la variable id_equipo esta definida en el GET, mostrar los documentos
	if(isset($_GET['id_equipo'])){?>
		<form name='frm_modificarRefacciones' method='post' action='frm_modificarRefacciones.php' onsubmit="return valFormModificaRefacciones(this);">
		<div id='tabla-modificarRefacciones' class='borde_seccion2'><?php
			 
			$refacciones=0;
			//Mandamos llamar la funcion que muestra las refacciones registrados del Equipo seleccionado, en refacciones atrapamos el valor de Retorno y
			// comprobar que botones se mostrarán
			$refacciones=mostrarRefacciones();?>
		</div>
		<div id="botones" align="center"><?php
			 //Este elemento se encarga de verificar si se llevara o no a cabo la valdacion, en su valor SI, si va a validar, en su valor NO, no lo hace ?>
			<input type="hidden" name="hdn_bandera" value="si"/><?php 
			//Este elemento se encarga de preservar el ID del Equipo, se le coloca un nombre fuera del estandar para preservarlo correctamente ?>
			<input type="hidden" name="id_equipo" value="<?php echo $_GET["id_equipo"];?>"/>
            <input name="btn_agregar" type="button" class="botones" value="Agregar" 
                title="Agregar Refacciones  <?php echo $_GET["id_equipo"];?>" 
                onclick="location.href='frm_agregarRefacciones.php?id=<?php echo $_GET["id_equipo"];?>&addmod';" />
            
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php
			if ($refacciones==1){?>
                <input type="submit" name="sbt_eliminar" class="botones" value="Eliminar" title="Eliminar Refacción Seleccionada" 
                onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php 
			}?>
			<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Cancelar operación de Modificar Refacciones" 
            onmouseover="window.status='';return true;"
			onclick="location.href='frm_modificarEquipo.php?clave=<?php echo $_GET["id_equipo"];?>'"/>
		</div>
		</form><?php
	}
	//Si se detecta el boton de Agregar en el POST, solicitar la operacion para Agregar Nuevos refacciones
	if (isset($_POST["sbt_agregar"])){
		//Verificamos que el arreglo de refacciones no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["refacciones"]))
			unset($_SESSION["refacciones"]);
		agregarRefacciones();
	}
	//Si se detecta el boton de Eliminar en el POST, entonces eliminar la refaccion seleccionado
	if (isset($_POST["sbt_eliminar"]))
		eliminarRefaccion();
	?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
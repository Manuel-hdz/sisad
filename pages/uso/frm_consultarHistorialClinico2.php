<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de la Unidad de Salud Ocupacional
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_consultarHistorialClinico.php");
		include ("op_modificarHistorialClinico.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:298px;height:20px;z-index:11;}
		#tabla-mostrarHistoriales {position:absolute;left:30px;top:190px;width:937px;height:420px;z-index:12; overflow:auto;}
		#tabla-mostrarDetalleHistoriales {position:absolute;left:30px;top:170px;width:937px;height:170px;z-index:12; overflow:auto;}
		#btns-regpdf { position: absolute; left:33px; top:660px; width:979px; height:40px; z-index:13; }
		#botonesConsultas { position:absolute; left:30px; top:360px; width:945px; height:40px; z-index:25;}	
		#botonesModificar { position:absolute; left:30px; top:680px; width:945px; height:40px; z-index:25;}	
		#detalles { position:absolute; left:30px; top:437px; width:935px; height:209px; z-index:21; overflow:auto; }	
		#btn-regresar { position: absolute; left:290px; top:480px; width:400px; height:40px; z-index:23; }
	-->
    </style>
</head>
<body>
	<?php if(isset($_GET['sbt_consultarTipo'])){
			$_POST['sbt_consultarTipo'] =$_GET['sbt_consultarTipo'];
		}
			else{
				if(isset($_GET['sbt_consultarFechas'])){
				$_POST['sbt_consultarFechas'] =$_GET['sbt_consultarFechas'];
			}	
	}?>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Consultar Historial Clinico </div>
	<?php if(isset($_POST['sbt_consultarTipo']) || isset($_POST['sbt_consultarFechas'])){?>
	    <form name="frm_consultarExamen" id="frm_consultarExamen" method="post" action="frm_consultarHistorialClinico2.php">
    	 <div id='tabla-mostrarHistoriales' class='borde_seccion2' align='center'><?php 
			//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de eliminar
			$band=mostrarHistorialClinico();?>
	</div>
	     <div id="btns-regpdf" align="center">&nbsp;&nbsp;&nbsp;&nbsp;

	       <div align="center">
	         <input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar para Seleccionar Otro Rango de Fechas" 
			onMouseOver="window.status='';return true" onclick="location.href='frm_consultarHistorialClinico.php'" />
           </div>
          </div></form>
	<?php 
	}
	
	if(isset($_POST['sbt_guardarHisFam'])){
		$claveSecc=explode(".",$_POST['rdb_id']);
		$clave=$claveSecc[0];
		$tipo = $_POST['tipo'];
		$existe = $_POST['existe'];
		
		if($tipo == "AntecedentesFamiliares")
			modificarAntecedentesFamiliares($clave,$existe);
		else if($tipo == "AspectosGenerales1")
			modificarAspectosGrales1($clave,$existe);
		else if($tipo == "AspectosGenerales2")
			modificarAspectosGrales2($clave,$existe);
		else if($tipo == "AntecedentesPatologicos")
			modificarAntecedentesPatologicos($clave,$existe);
		else if($tipo == "PruebaLaboratorio")
			modificarPruebaLaboratorio($clave,$existe);
		else if($tipo == "PruebaEsfuerzo")
			modificarPruebaEsfuerzo($clave,$existe);
		else if($tipo == "HistorialTrabajo")
			modificarHistorialTrabajo($clave,$existe);
	}
	
	if(isset($_POST['rdb_id'])){?>
		<div id="tabla-mostrarDetalleHistoriales" class="borde_seccion" align="center">
		<form action="frm_consultarHistorialClinico2.php" name="frm_detalleHistorial" method="post"><?php 
			//Se crea la variable hidden para conservar el detalle y poder mostrar las consultas en la misma pagina
			//Particionamos el post con el radio para tomar iunicamete el valor del id
			$claveSecc=explode(".",$_POST['rdb_id']);
			$clave=$claveSecc[0];?>
			<input type="hidden" name="id_hc" value="<?php echo $clave; ?>"/>
			<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/><?php 
			//Mostrar el detalle del Registro del historial seleccionado
			verDetalleHistorialClinico($clave);?>
		</form><?php
		
		
		//Verificamos que vengan definidos los botones
		if(isset($_POST["sbt_historialFam"])|| isset($_POST["sbt_aspGrales1"])|| isset($_POST["sbt_aspGrales2"])||(isset($_POST["sbt_antPato"]))||(isset($_POST["sbt_hisTrab"]))||
		(isset($_POST["sbt_prueEsfzo"]))||(isset($_POST["sbt_prueLab"]))){
			?><div id="detalles" class="borde_seccion" align="center"><?php
			$link = "";
			$tipo = "";
			//Mostramos la consulta dependiendo del boton presionado
			if(isset($_POST["sbt_historialFam"])){
				mostrarAntecedentesHistorial($clave);
				$link = "frm_modificarHistorialFamiliar.php";
				$tipo = "AntecedentesFamiliares";
			}
			if(isset($_POST["sbt_aspGrales1"])){
				mostrarAspectosGrales1($clave);
				$link = "frm_modificarAspectosGenerales1.php";
				$tipo = "AspectosGenerales1";
			}
			if(isset($_POST["sbt_aspGrales2"])){
				mostrarAspectosGrales2($clave);
				$link = "frm_modificarAspectosGenerales2.php";
				$tipo = "AspectosGenerales2";
			}
			if(isset($_POST["sbt_prueEsfzo"])){
				mostrarPruebasEsfuerzo($clave);
				$link = "frm_modificarPruebasEsfuerzo.php";
				$tipo = "PruebaEsfuerzo";
			}
			if(isset($_POST["sbt_prueLab"])){
				mostrarPruebasLaboratorio($clave);
				$link = "frm_modificarPruebasLaboratorio.php";
				$tipo = "PruebaLaboratorio";
			}
			if(isset($_POST["sbt_antPato"])){
				mostrarAntecedentesPatologicos($clave);
				$link = "frm_modificarAntNoPatologicos.php";
				$tipo = "AntecedentesPatologicos";
			}
			if(isset($_POST["sbt_hisTrab"])){
				mostrarHistorialTrabajo($clave);
				$link = "frm_modificarHistorialTrabajo.php";
				$tipo = "HistorialTrabajo";
			}
			?>
			</div>
			<div id="botonesModificar" align="center">
				<form action="<?php echo $link; ?>" name="frm_modificarHC" method="post">
					<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
					<input type="hidden" name="tipo" value="<?php echo $tipo; ?>"/>
					<input type="submit" class="botones" value="Modificar" title="Modificar los Antecedentes del Historial Clinico <?php echo $_POST['id_hc']; ?>" onMouseOver="window.status='';return true"/>
				</form>
			</div>
		<?php
		}
	}//cierreif(isset($_POST['rdb_id']))?>	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
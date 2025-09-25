<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion 
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para Consultar La Bitacora seleccionada
		include("op_consultarBitacora.php")
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
		<!--
		#titulo-reporte { position:absolute; left:30px; top:146px; width:216px; height:20px; z-index:11; }				
		#consultar {position:absolute;	left:30px;	top:190px;	width:320px;height:150px;	z-index:15;}
		#reporte { position:absolute; left:30px; top:190px; width:921px; height:430px; z-index:21; overflow:scroll; }
		#reporte2 { position:absolute; left:30px; top:190px; width:945px; height:80px; z-index:21;}
		#detalles { position:absolute; left:30px; top:375px; width:945px; height:250px; z-index:21; overflow:scroll; }
		#btn-cancelar {	position:absolute;	left:472px;	top:411px;	width:93px;	height:37px;	z-index:22;}
		#btns-regpdf { position: absolute; left:319px; top:670px; width:400px; height:40px; z-index:23; }
		#botonesConsultas { position:absolute; left:30px; top:320px; width:945px; height:40px; z-index:25;}		
		#calendar-uno {position:absolute; left:230px; top:233px; width:30px; height:26px; z-index:18; }
		#calendar-dos {position:absolute; left:230px; top:270px; width:30px; height:26px; z-index:19; }
		-->
    </style>
</head>
<body>

	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-reporte">Consulta Bit&aacute;cora Correctiva </div><?php
	
	//Definimos variable para control de lo que se debe mostrar
	$band = 0;
	
	//Mostrar el Detalle de la bitacora
	if(isset($_POST['verDetalle'])){					
		//Esta variable indica que no debe mostrarse el formulario donde se solicitan las fechas para consultar la Bitacora de Mttos Correctivos
		$band = 1;
		if(!isset($_SESSION["datosConsBitacoraCorr"]["idBitacora"])){
			//Obtener el valor de la clave de la Entrada seleccionada
			$id_bitacora = "";
			$tam = count($_POST);
			$cont = 1;
			foreach($_POST as $nombre_campo => $valor){								
				if($cont==$tam)
					$id_bitacora = $valor;				
				$cont++;
			}
			
			//Guardar en la SESSION el ID de la Bitacora que se esta consultando
			$_SESSION["datosConsBitacoraCorr"]["idBitacora"] = $id_bitacora;
		}?>
		
		<form action="frm_consultarBitacoraCorr.php" method="post" name="frm_consultarBitacora" ><?php 
			//Se crea la variable hidden para conservar el detalle y poder mostrar las consultas en la misma pagina?>
			<input type="hidden" name="verDetalle"/><?php 
			//Mostrar el detalle del Registro de la Bitacora Seleccionado
			mostrarDetalleCorr();?>
		</form><?php
		
		//Verificamos que vengan definidos los botones
		if(isset($_POST["sbt_consActCorr"])|| isset($_POST["sbt_consMecCorr"])|| isset($_POST["sbt_consMatCorr"])||(isset($_POST["sbt_consGamCorr"]))||(isset($_POST["sbt_consFot"]))){
			?><div id="detalles" class="borde_seccion2" align="center"><?php
			//Mostramos la consulta dependiendo del boton presionado
			if(isset($_POST["sbt_consActCorr"])){
				mostrarDetalleActividadesCorr();
			}
			if(isset($_POST["sbt_consMecCorr"])){
				mostrarDetalleMecanicoCorr();
			}
			if(isset($_POST["sbt_consMatCorr"])){
				mostrarDetalleMaterialesCorr();
			}
			if(isset($_POST["sbt_consFot"])){
				mostrarRegistroFotos($_SESSION["datosConsBitacoraCorr"]["idBitacora"]);
			}
			?></div> <?php
		}
	}//cierreif(isset($_POST['verDetalle']))
	
	
	//Si viene definido el boton sbtConsultarCorr entonces se genera la consulta General
	if(isset($_POST['sbt_consultarCorr'])){
		$band = 1;	
		generarConsultaCorr();
		//Liberar los datos de la SESSION para seleccionar un Nuevo Equipo
		unset($_SESSION["datosConsBitacoraCorr"]["idBitacora"]);
	}
	//Si viene definido el boton sbtConsultarCorr entonces se genera la consulta General
	if(isset($_GET['cancelar'])){
		$band = 1;	
		generarConsultaCorr();
		//Liberar los datos de la SESSION para seleccionar un Nuevo Equipo
		unset($_SESSION["datosConsBitacoraCorr"]["idBitacora"]);
	}		
	
	
	//Si la bandera viene en 0 mostrar las ordenes de trabajo
	if($band==0){
		//Si viene definido el arreglo datosConsBitacora => Correctiva lo damos de baja de la sesión
		if(isset($_SESSION["datosConsBitacoraCorr"]))
			unset($_SESSION["datosConsBitacoraCorr"]);?>
				
		<fieldset class="borde_seccion" id="consultar" name="consultar">
		<legend class="titulo_etiqueta">Consulta Mantenimiento Correctivo</legend>
		<br />		
		<form name="frm_consultarBitacora"  onsubmit="return valFormFechasBit(this);" action="frm_consultarBitacoraCorr.php" method="post">
		<table width="100%" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td width="94"><div align="right">Fecha Inicio</div></td>
				<td width="301">
					<input name="txt_fechaIni" readonly="readonly" type="text" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10" maxlength="15"
					width="90" /> 
			  </td>
			</tr>
			<tr>
				<td><div align="right">Fecha Fin </div></td>
				<td><input name="txt_fechaFin" type="text"  readonly="readonly" value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15"  width="90" /></td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<input name="sbt_consultarCorr" type="submit" class="botones" value="Consultar" 
					onMouseOver="window.estatus='';return true" title="Consultar Bit&aacute;cora" />
					&nbsp;&nbsp;
					<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Regresar a Seleccionar Bit&aacute;cora" 
					onclick="location.href='frm_consultarBitacora.php'" onMouseOver="window.estatus='';return true" />   
			   </td>
			</tr>
		</table>		
		</form>
		</fieldset>
		
		
		<div id="calendar-uno">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_consultarBitacora.txt_fechaIni,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<div id="calendar-dos">
			<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
			onclick="displayCalendar(document.frm_consultarBitacora.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />	
		</div><?php 
	}//Cierre if($band==0)?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
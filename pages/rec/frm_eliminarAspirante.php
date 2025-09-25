<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Manejo de la funciones para Registrar los datos del Aspirante en la BD 
		include ("op_eliminarAspirante.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-eliminar {position:absolute; left:30px; top:146px; width:247px; height:20px; z-index:11; }
		#tabla-eliminar-aspirante-puesto { position:absolute; left:30px; top:190px; width:433px; height:181px; z-index:12; padding:15px; padding-top:0px;}
		#tabla-eliminar-aspirante { position:absolute; left:518px; top:188px; width:433px; height:184px; z-index:15; padding:15px; padding-top:0px;}
		#calendarioPuestoIni {position:absolute; left:356px; top:232px; width:30px; height:26px; z-index:13;}
		#calendarioPuestoFin {position:absolute; left:356px; top:269px; width:30px; height:26px; z-index:14;}
		#resultados-aspirante { position:absolute; left:32px; top:405px; width:921px; height:232px; z-index:22; overflow: scroll }
		#btn-eliminar {position:absolute;left:35px;top:681px;width:946px;height:31px;z-index:9;}
		-->
    </style>
</head>
<body><?php
if(!isset($_POST['sbt_eliminar'])){
	//Obtener la fecha del sistema para la fecha inicio y fecha fin
	$txt_fechaPuestoIni = date("d/m/Y", strtotime("-30 day"));
	$txt_fechaPuestoFin = date("d/m/Y");?>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-eliminar">Eliminar Aspirantes a Empleo</div>	
	
		<fieldset class="borde_seccion" id="tabla-eliminar-aspirante-puesto">
		<legend class="titulo_etiqueta">Eliminar Aspirante por Puesto Recomendado</legend>	
		<br>
		<form onSubmit="return valFormEliminarAspirantePuesto(this);" name="frm_eliminarAspirantePuesto" method="post" action="frm_eliminarAspirante.php" >
			<table width="100%"cellpadding="5" cellspacing="5"  align="center" class="tabla_frm">
				<tr>
				  <td width="50%"><div align="right">Fecha de Inicio </div></td>
					<td width="50%">
						<input type="text" name="txt_fechaPuestoIni" id="txt_fechaPuestoIni" size="10" maxlength="10" class="caja_de_texto" onkeypress="return permite(event,'car',0);" 
						 value="<?php echo $txt_fechaPuestoIni; ?>" readonly="readonly"/>	
				  </td>
				</tr>						
				<tr>
					<td><div align="right">Fecha de Fin </div></td>
					<td>
						<input type="text" name="txt_fechaPuestoFin" id="txt_fechaPuestoFin" size="10" maxlength="10" class="caja_de_texto"  onkeypress="return permite(event,'car',0);" 
						 value="<?php echo $txt_fechaPuestoFin; ?>" readonly="readonly"/>
					</td>				
				</tr>
				<tr>
					<td><div align="right">Puesto Recomendado 	</div></td>
					<td><?php 
						if(cargarCombo("cmb_puesto","puesto","area_puesto","bd_recursos","Puesto Recomendado","")==0)
							echo "<label class='msje_correcto'>No Hay Puestos Registrados</label>
							<input type='hidden' name='cmb_puesto' id='cmb_puesto'/>";
						/*En caso de que no se encuentren datos en la BD ó en este caso en las tablas donde se consultan colocar un mensaje en los combos que se muestran dentrop de los formularios*/
					 ?>
					</td>
				</tr>
				<tr>
					<td colspan="4" align="center">
						<input name="sbt_consultarPuesto" type="submit" class="botones" id="sbt_consultarPuesto" title="Consultar Aspirante a Empleo por Puesto" 
							onmouseover="window.status='';return true"  value="Consultar" />
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Bolsa de Trabajo" onmouseover="window.status='';return true" 
							onclick="location.href='menu_bolsaTrabajo.php'" />
					</td>
			  </tr>
			</table>
		</form>
	</fieldset>

	<fieldset id="tabla-eliminar-aspirante" class="borde_seccion">
		<legend class="titulo_etiqueta">Consultar los Aspirantes para ser Eliminados </legend>	
		<br>
		<form name="frm_eliminarAspirante" method="post" action="frm_eliminarAspirante.php">
			<table align="center" border="0" width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td align="center"><input name="sbt_consultarAspirante" type="submit" class="botones" id="sbt_consultarAspirante" 
						title="Consultar Informaci&oacute;n de los Aspirantes Registrados"  onmouseover="window.status='';return true" value="Consultar" />
					</td>
				</tr>
			</table>
		</form>	   
	</fieldset>

	<div id="calendarioPuestoIni">
		<input type="image" name="fechaPuestoIni" id="fechaPuestoIni" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_eliminarAspirantePuesto.txt_fechaPuestoIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" title="Seleccionar la fecha de Inicio de Registro de Aspirantes"/>
	</div>


	<div id="calendarioPuestoFin">
		<input type="image" name="fechaPuestoFin" id="fechaPuestoFin" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_eliminarAspirantePuesto.txt_fechaPuestoFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" title="Seleccionar la fecha de Fin de Registro de Aspirantes"/>
	</div>

<?php // COLOQUE NUEVAMENTE LA LINEA DEL cmb_combo <input type="hidden" name="cmb_puesto" value="">
	//Si esta definido sbt_consultarAspirante o sbt_consultarPuesto se muestran los Aspirantes registrados 
	if(isset($_POST["sbt_consultarPuesto"]) || isset($_POST['sbt_consultarAspirante'])){?>
        <form onSubmit="return valFormEliminarAsp(this);" name="frm_eliminarAsp" method="post" action="frm_eliminarAspirante.php">
        	<?php if(isset($_POST["sbt_consultarPuesto"])){ ?>
				<input type="hidden" name="txt_fechaPuestoIni" value="<?php echo $_POST['txt_fechaPuestoIni'];?>" />
                <input type="hidden" name="txt_fechaPuestoFin" value="<?php echo $_POST['txt_fechaPuestoFin'];?>" />
				<input type="hidden" name="cmb_puesto" value="<?php echo $_POST['cmb_puesto'];?>" /> 
                <input type="hidden" name="sbt_consultarPuesto" value="Consultar" /><?php 
            }?>
		</form><?php 
		if(isset($_POST["sbt_consultarAspirante"])){ ?>
			<input type="hidden" name="sbt_consultarAspirante" value="Consultar" /><?php 
		}?>
		<form onsubmit="return valFormResultadosAspirante(this);" name='frm_resultadosAspirante' method='post' action='frm_eliminarAspirante.php'>
			<div id="resultados-aspirante" class='borde_seccion2' align="center"><?php
				mostrarListadoAspirantes();?>
			</div>
			<div id="btn-eliminar" align="center">
				<input type="submit" class="botones" name="sbt_eliminar" value="Eliminar"  title="Eliminar Aspirantes Seleccionados" onmouseover="window.status=''; return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="sbt_cancelar" type="button" class="botones" id="btn_cancelar"  title="Cancelar la Eliminación de los  Aspirantes Seleccionados" 
					onmouseover="window.status='';return true" value="Cancelar" onclick="location.href='menu_bolsaTrabajo.php'"/>
		  </div>
		</form><?php }
	}//Fin del if(!isset($_POST['sbt_eliminar'])){
	else{
		eliminarAspirante();
	}?>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
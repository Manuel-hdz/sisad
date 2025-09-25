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
		//Este archivo contiene las funciones para modificar datos de una Gama en la Bd de Mantenimiento		
		include ("op_modificarGamas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
    <style type="text/css">
		<!--
		#titulo-modificarActividad { position:absolute; left:30px; top:146px; width:306px; height:20px; z-index:11; }
		#agregar-actividad { position:absolute; left:30px; top:190px; width:940px; height:129px; z-index:12; }		
		#detalle-actividades { position:absolute; left:30px; top:375px; width:940px; height:235px; z-index:13; overflow:scroll; }
		#botones-detAct { position:absolute; left:30px; top:670px; width:980px; height:50px; z-index:14; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>	
    <div id="titulo-modificarActividad" class="titulo_barra">Editar Aplicaci&oacute;n Agregada al Sistema </div>
	<?php
	
	//Esta variable ayuda a mostrar el mensaje cuando una Actividad ya ha sido agregada
	$msg_tabla = "";	
	
	//Agregar una Actividad al Registro dentro de la Aplicación correspondiente
	if(isset($_POST['sbt_agregarAct'])){
		//Obtener el nombre de la Actividad desde El ComboBox o la Caja de Texto
		if(isset($_POST['cmb_actividadesApp'])) $nom_act = $_POST['cmb_actividadesApp']; else $nom_act = strtoupper($_POST['txa_nuevaAct']);
		
		//Vetrificar que la Actividad no haya sido registrada previamente
		if(!in_array($nom_act,$_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$_SESSION['appEditar']])){
			//Obtener la clave de la Actividad de acuerdo al numero de registros existentes
			$pos = "NA".(count($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$_SESSION['appEditar']])+1);
			//Guardar de forma individual cada Actividad dentro de la Aplicación
			$_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$_SESSION['appEditar']][$pos] = $nom_act;
		}
		else//En el caso de que la Actividad ya este registrada, desplegar mensaje
			$msg_tabla = "La Actividad $nom_act ya esta agregada a la Aplicaci&oacute;n";
	}
	
	
	//Eliminar una Actividad del Registro
	if(isset($_POST['sbt_eliminarAct']) && isset($_POST['rdb_actividad'])){												
		//Eliminar una Aplicacion del registro del Sistema
		unset($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$_SESSION['appEditar']][$_POST['rdb_actividad']]);		
		//Desplegar mensaje cuando se elimina una Actividad del Registro
		$msg_tabla = "La Actividad $_POST[rdb_actividad] fue eliminada de la Aplicaci&oacute;n";
	}?>
	
	
    <fieldset class="borde_seccion" id="agregar-actividad" name="agregar-actividad">
	<legend class="titulo_etiqueta">Seleccionar las Actividades Correspondiente a la Aplicaci&oacute;n <em><u><?php echo $_SESSION['appEditar'];?></u></em></legend>
	<br />
	<form onsubmit="return valFormAgregarDatoGama(this,'Seleccionar o Ingresar una Actividad para ser Agregada a la Aplicación');" name="frm_modificarAppSistema" method="post" action="frm_modificarGamasActs.php">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<tr>
	  	  	<td width="10%">Sistema</td>
			<td width="20%"><?php
				//Obtener los Sistemas Registrados en la BD
				$conn = conecta("bd_mantenimiento");
				$rs_sistemas = mysql_query("SELECT DISTINCT sistema FROM actividades ORDER BY sistema");
				if($sistema=mysql_fetch_array($rs_sistemas)){?>
					<select name="cmb_sistema" id="cmb_sistema" class="combo_box" onchange="cargarCombo(this.value,'bd_mantenimiento','actividades','aplicacion','sistema','cmb_aplicacionSistema','Aplicación','');">
						<option value="">Sistema</option><?php
					do{
						echo "<option value='$sistema[sistema]'>$sistema[sistema]</option>";
					}while($sistema=mysql_fetch_array($rs_sistemas));
					?></select><?php
				}
				else
					echo "<label class='msje_correcto'>No Hay Sistemas Registrados</label>";				
				mysql_close($conn);?>
			</td>			
			<td width="10%">Aplicaci&oacute;n</td>
			<td width="25%">
				<select name="cmb_aplicacionSistema" id="cmb_aplicacionSistema" class="combo_box"
				onchange="cargarCombo(this.value,'bd_mantenimiento','actividades','descripcion','aplicacion','cmb_actividadesApp','Actividad','');" >
					<option value="">Aplicaci&oacute;n</option>
				</select>
			</td>
			<td width="35%"><input name="ckb_nuevaAct" type="checkbox" id="ckb_nuevaAct" onclick="activarCkbNuevo(this,'cmb_actividadesApp','txa_nuevaAct');" />
			  Nueva Actividad
		    <textarea name="txa_nuevaAct" id="txa_nuevaAct" maxlength="120" onkeyup="return ismaxlength(this);" class="caja_de_texto" rows="2" cols="30"
                onkeypress="return permite(event,'num_car', 0);" disabled="disabled" ></textarea></td>
		</tr>
		<tr>
	  	  	<td>Actividad</td>
		  	<td colspan="3">
				<select name="cmb_actividadesApp" id="cmb_actividadesApp" class="combo_box"	>		
					<option value="">Actividad</option>
				</select>
			</td>
			<td><div align="center">
			  <input type="submit" name="sbt_agregarAct" value="Agregar Actividad" class="botones_largos" title="Agregar Actividad a la Aplicaci&oacute;n" onmouseover="window.status='';return true" />
		    </div></td>		  
	  	</tr>
	</table>	
	</form>
</fieldset>
	
	
	<form onsubmit="return valFormTablaActividades(this);" name="frm_tablaActividades" method="post" action="frm_modificarGamasActs.php">
		<div id="detalle-actividades" class="borde_seccion2" align="center">
			<?php //Si existen Aplicaciones en el Sistema Desplegarlas, de lo contrario no hacerlo			
			if(count($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$_SESSION['appEditar']])!=0){				
				verActividadesApp($msg_tabla);
			}?>
		</div>
		<div id="botones-detAct" align="center">
			<input type="submit" name="sbt_eliminarAct" value="Eliminar Actividad" class="botones_largos" title="Eliminar Actividad Seleccionada" onmouseover="window.status='';return true"
			<?php if(count($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$_SESSION['appEditar']])==0){ ?> disabled="disabled" <?php } ?> />						
			&nbsp;&nbsp;			
			<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Registro de Aplicaciones" onclick="location.href='frm_modificarGamasApp.php';" />
			&nbsp;&nbsp;			
			<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Cancelar el Registro de la Gama" onclick="confirmarSalida('menu_gamas.php');" />
	  </div>
	</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
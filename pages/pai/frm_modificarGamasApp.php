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
	<script type="text/javascript" src="../../includes/validacionPaileria.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
    <style type="text/css">
		<!--
		#titulo-modificarAplicacion { position:absolute; left:30px; top:146px; width:306px; height:20px; z-index:11; }
		#agregar-aplicacion { position:absolute; left:30px; top:190px; width:940px; height:112px; z-index:12; }		
		#detalle-aplicaciones { position:absolute; left:30px; top:352px; width:940px; height:258px; z-index:13; overflow:scroll; }
		#botones-detApp { position:absolute; left:30px; top:670px; width:980px; height:50px; z-index:14; }		
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg-gomar.png" width="999" height="30" /></div>	
    <div id="titulo-modificarAplicacion" class="titulo_barra">Modificar Aplicaciones del Sistema </div>
	<?php
	
	//Esta variable ayuda a mostrar el mensaje cuando una Aplicacion ya ha sido agregada
	$msg_tabla = "";	
	
	//Agregar ua Aplicacion al Registro dentro del Sistema correspondiente
	if(isset($_POST['sbt_agregarApp'])){
		//Obtener el nombre de la Aplicacion desde El ComboBox o la Caja de Texto
		if(isset($_POST['cmb_aplicacionSistema'])) $nom_app = $_POST['cmb_aplicacionSistema']; else $nom_app = strtoupper($_POST['txt_nuevaApp']);
		
		//Vetrificar que la Aplicacion no haya sido registrada previamente
		if(!isset($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$nom_app])){
			//Realizar la conexion a la BD de Compras
			$conn = conecta("bd_mantenimiento");
			//Cargar las Actividades de la Aplicación Seleccionada en la SESSION
			$_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$nom_app] = cargarActividadesApps($nom_app,$cmb_sistema,$_SESSION['datosGamaModificada']['idGamaAnt'],1);
			
			//Cerrar la Conexion con la BD
			mysql_close($conn);			
		}
		else
			$msg_tabla = "La Aplicaci&oacute;n $nom_app ya esta agregada al Sistema";//En el caso de que la Aplicacion ya este registrada, desplegar mensaje
	}
	
	
	//Eliminar una Aplicacion del Registro
	if(isset($_POST['sbt_eliminarApp']) && isset($_POST['rdb_aplicacion'])){												
		//Eliminar una Aplicacion del registro del Sistema
		unset($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']][$_POST['rdb_aplicacion']]);
		
		//Desplegar mensaje cuando se elimina una Actividad del Registro
		$msg_tabla = "La Aplicacion $_POST[rdb_aplicacion] fue eliminada del Sistema";				
	}	
	
	
	if(isset($_POST['sbt_editarApp'])  && isset($_POST['rdb_aplicacion'])){
		//Obtener el nombre de la Aplicación que Será editada
		$_SESSION['appEditar'] = $_POST['rdb_aplicacion'];
		//Redireccionar a la Pagina donde serán agreados los Sistemas a la Gama
		echo "<meta http-equiv='refresh' content='0;url=frm_modificarGamasActs.php'>";		
	}?>
	
	
    <fieldset class="borde_seccion" id="agregar-aplicacion" name="agregar-aplicacion">
	<legend class="titulo_etiqueta">Modificar las Aplicaciones Correspondiente al Sistema <em><u><?php echo $_SESSION['sistemaEditar'];?></u></em></legend>
	<br />
	<form onsubmit="return valFormAgregarDatoGama(this,'Seleccionar o Ingresar una Aplicación para ser Agregada al Sistema',4,2);" name="frm_modificarAppSistema" method="post" action="frm_modificarGamasApp.php">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<tr>
		  	<td width="10%"><div align="right">Sistema</div></td>
			<td width="30%"><?php
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
			<td width="40%" rowspan="2">
				<div align="right">
				<input type="checkbox" name="ckb_nuevaApp" onclick="activarCkbNuevo(this,'cmb_aplicacionSistema','txt_nuevaApp');"  />
				 Nueva Aplicaci&oacute;n &nbsp;
				<input type="text" name="txt_nuevaApp" id="txt_nuevaApp" class="caja_de_texto" value="" size="30" maxlength="50" disabled="disabled" onkeypress="return permite(event,'num_car', 0);" />
				</div>
		  	</td>
			<td width="20%" rowspan="2">
				<div align="center">
				  <input type="submit" name="sbt_agregarApp" value="Agregar Aplicaci&oacute;n" class="botones_largos" title="Agregar Aplicaci&oacute;n al Sistema" onmouseover="window.status='';return true" />
		        </div>
			</td>
		</tr>
		<tr>
			<td><div align="right">Aplicaci&oacute;n</div></td>
			<td>
				<select name="cmb_aplicacionSistema" id="cmb_aplicacionSistema" class="combo_box" >
					<option value="">Aplicaci&oacute;n</option>
				</select>
			</td>
		</tr>
	</table>	
	</form>
</fieldset>
	
	
	<form onsubmit="return valFormTablaAplicaciones(this);" name="frm_tablaAplicaciones" method="post" action="frm_modificarGamasApp.php">
		<div id="detalle-aplicaciones" class="borde_seccion2" align="center">
			<?php //Si existen Aplicaciones en el Sistema Desplegarlas, de lo contrario no hacerlo
			if(count($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']])!=0){				
				verAplicacionesSistema($msg_tabla);
			}?>
		</div>
		<div id="botones-detApp" align="center">
			<input type="hidden" name="hdn_boton" id="hdn_boton" value="" />
			<input type="submit" name="sbt_eliminarApp" value="Eliminar Aplicaci&oacute;n" class="botones_largos" title="Eliminar Aplicaci&oacute;n Seleccionada" onmouseover="window.status='';return true"
			<?php if(count($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']])==0){ ?> disabled="disabled" <?php } ?> onclick="hdn_boton.value='Eliminada'" />
			&nbsp;&nbsp;
			<input type="submit" name="sbt_editarApp" value="Editar Aplicaci&oacute;n" class="botones_largos" title="Editar Aplicaci&oacute;n Seleccionada" onmouseover="window.status='';return true" 
			<?php if(count($_SESSION['sistemasGamaModificada'][$_SESSION['sistemaEditar']])==0){ ?> disabled="disabled" <?php } ?> onclick="hdn_boton.value='Editada'" />
			&nbsp;&nbsp;			
			<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Registro de Sistemas" onclick="location.href='frm_modificarGamasSistema.php';" />
			&nbsp;&nbsp;			
			<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Cancelar el Registro de la Gama" onclick="confirmarSalida('menu_gamas.php');" />
	  </div>
	</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
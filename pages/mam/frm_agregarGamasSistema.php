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
		//Este archivo contiene las funciones para agregar los datos de una Gama a la Bd de Mantenimiento		
		include ("op_agregarGamas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js"></script>
	<script type="text/javascript" src="includes/ajax/verificarGama.js"></script>
    <style type="text/css">
		<!--
		#titulo-agregarSistema { position:absolute; left:30px; top:146px; width:306px; height:20px; z-index:11; }
		#agregar-sistema { position:absolute; left:30px; top:190px; width:792px; height:85px; z-index:12; }		
		#detalle-sistemas { position:absolute; left:30px; top:310px; width:940px; height:300px; z-index:13; overflow:scroll; }
		#botones-detSistemas { position:absolute; left:30px; top:670px; width:980px; height:50px; z-index:14; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>	
    <div id="titulo-agregarSistema" class="titulo_barra">Agregar Sistemas a la Gama</div>
	<?php
	//Esta variable ayuda a mostrar el mensaje cuando un Sistema ya ha sido agregado
	$msg_tabla = "";
	
	/*Para el manejo de las gamas nuevas, los Sistemas serán almacenados en un arreglo llamado sistemasGamaNueva, el cual contendra un arreglo por cada Sistema, los cuales a su vez contendran
	 *un arreglo por cada Aplicacion y por ultimo estos albergaran las activiades que conformarán la Gama Nueva*/
	if(isset($_POST['sbt_agregarSistema'])){
		//Obtener el nombre del Sistema desde El ComboBox o la Caja de Texto
		if(isset($_POST['cmb_sistemaGama'])) $nom_sistema = $_POST['cmb_sistemaGama']; else $nom_sistema = strtoupper($_POST['txt_nuevoSistema']);
		
		//Agrear datos del Sistema a la SESSION para su posterior almacenamiento en la BD		
		if(isset($_SESSION['sistemasGamaNueva'])){
			//Vetrificar que el Sistema no haya sido registrado previamente
			if(!isset($_SESSION['sistemasGamaNueva'][$nom_sistema]))
				$_SESSION['sistemasGamaNueva'][$nom_sistema] = cargarApps($nom_sistema);//Cargar la Aplicaciones del Sistema Seleccionado en la SESSION
			else
				$msg_tabla = "El Sistema $nom_sistema ya esta agreago a la Gama";//En el caso de que es Sistema ya este registrado, desplegar mensaje
		}
		else //Si el arreglo "sistemasGamaNueva" no esta definido en la SESSION, definirlo y guardar el primer Sistema						
			$_SESSION['sistemasGamaNueva'] = array($nom_sistema=>cargarApps($nom_sistema));//Cargar la Aplicaciones del Sistema Seleccionado en la SESSION		
	}
	
	
	//Eliminar un Sistema del Registro
	if(isset($_POST['sbt_eliminarSistema']) && isset($_POST['rdb_sistema'])){
		//Eliminar la clave del Sistema Selecciondo, y por consiguinte las aplicaciones y actividades asociadas a él
		unset($_SESSION['sistemasGamaNueva'][$_POST['rdb_sistema']]);		
		
		//Desplegar mensaje cuando se elimina un Sistema del Registro
		$msg_tabla = "El Sistema $_POST[rdb_sistema] fue eliminado de la Gama";
		
		//Si fueron eliminados todos los datos del arreglo de SESSION[sistemasGamaNueva], quitarlo de la SESSION
		if(count($_SESSION['sistemasGamaNueva'])==0)
			unset($_SESSION['sistemasGamaNueva']);
	}	
	
	
	//Editar un Sistema Seleccionado
	if(isset($_POST['sbt_editarSistema']) && isset($_POST['rdb_sistema'])){
		//Obtener el nombre del Sistema que Será editado
		$_SESSION['sistemaEditar'] = $_POST['rdb_sistema'];
		//Redireccionar a la Pagina donde serán agreados los Sistemas a la Gama
		echo "<meta http-equiv='refresh' content='0;url=frm_agregarGamasApp.php'>";	
	}?>
	
	
    <fieldset id="agregar-sistema" class="borde_seccion">
	<legend class="titulo_etiqueta">Seleccionar los Sistemas Correspondientes a la Gama <em><u><?php echo $_SESSION['datosGamaNueva']['idGama'];?></u></em></legend>
	<br />
	<form onsubmit="return valFormAgregarDatoGama(this,'Seleccionar o Ingresar un Sistema para ser Agregado a la Gama',0,2);" name="frm_agregarSistemaGama" method="post" action="frm_agregarGamasSistema.php">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">		
		<tr>
			<td width="10%"><div align="right">Sistema</div></td>
			<td width="25%"><?php 
				$resultado = cargarCombo("cmb_sistemaGama","sistema","actividades","bd_mantenimiento","Sistema",""); 
				if($resultado==0){?>
					<label class="msje_correcto">Ning&uacute;n Sistema Registrado, Agregar Sistemas</label>
					<input type="hidden" name="cmb_sistemaGama" id="cmb_sistemaGama" value="" /><?php
				}?>
			</td>
			<td width="45%">
				<div align="right">
				<input type="checkbox" name="ckb_nuevoSistema" onclick="activarCkbNuevo(this,'cmb_sistemaGama','txt_nuevoSistema');"  />
				 Nuevo Sistema &nbsp;
				<input type="text" name="txt_nuevoSistema" id="txt_nuevoSistema" class="caja_de_texto" value="" size="20" maxlength="30" disabled="disabled" onkeypress="return permite(event,'num_car', 0);" />
				</div>
		  	</td>
			<td width="20%">
				<div align="center">
				  <input type="submit" name="sbt_agregarSistema" value="Agregar Sistema" class="botones" title="Agregar Sistema a la Gama" onmouseover="window.status='';return true" />
		        </div></td>
		</tr>
	</table>	
	</form>
	</fieldset>
	
	
	<form onsubmit="return valFormTablaSistema(this);" name="frm_tablaSistemas" method="post" action="frm_agregarGamasSistema.php">
		<div id="detalle-sistemas" class="borde_seccion2" align="center">
			<?php if(isset($_SESSION['sistemasGamaNueva'])){								
				verSistemasGama($msg_tabla);
			}?>
		</div>
		<div id="botones-detSistemas" align="center">
			<input type="hidden" name="hdn_boton" id="hdn_boton" value="" />
			<?php if(isset($_SESSION['sistemasGamaNueva'])) {?>
			<input type="button" name="btn_guardar" value="Guardar Gama" class="botones" onclick="verficarSistemasGama('frm_agregarGamas.php','sistemasGamaNueva');" title="Guardar Gama Creada" />
			&nbsp;&nbsp;
			<?php } ?>
			<input type="submit" name="sbt_eliminarSistema" value="Eliminar Sistema" class="botones_largos" title="Eliminar Sistema Seleccionado" onmouseover="window.status='';return true"
			<?php if(!isset($_SESSION['sistemasGamaNueva'])){ ?> disabled="disabled" <?php } ?> onclick="hdn_boton.value='Eliminado'" />
			&nbsp;&nbsp;
			<input type="submit" name="sbt_editarSistema" value="Editar Sistema" class="botones" title="Editar Sistema Seleccionado" onmouseover="window.status='';return true" 
			<?php if(!isset($_SESSION['sistemasGamaNueva'])){ ?> disabled="disabled" <?php } ?> onclick="hdn_boton.value='Editado'" />			
			&nbsp;&nbsp;			
			<input type="button" name="btn_cancelar" value="Cancelar" class="botones" title="Cancelar el Registro de la Gama" onclick="confirmarSalida('menu_gamas.php');" />
  	  </div>
	</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para mostrar la informacion del proveedor que se esta consultando
		include ("op_consultarConvenio.php");
		//Este archivo contiene las operaciones den la funciones que permiten agregar y eliminar términos de convenios, ademas, de poder modificar el estado
		include ("op_modificarConvenio.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
    <style type="text/css">
		<!--
		#titulo-barra {position:absolute;left:25px;top:132px;width:333px;height:21px;z-index:11;}
		#contenido {position:absolute;left:30px;top:190px;width:900px;height:135px;z-index:12;}
		#tabla-mostrarConvenioDetalle {position:absolute;left:30px;top:360px;width:900px;height:35%;z-index:11;overflow:scroll;}
		#calendario-fin {position:absolute; left:580px; top:217px; width:29px; height:25px; z-index:13; }
		#operaciones {position:absolute; left:30px; top:191px; width:940px; height:400px; z-index:15;overflow:scroll;}
		#botones { position:absolute; left:30px; top:660px; width:940px; height:25px; z-index:15;}
		#operacionAgregar{position:absolute; left:30px; top:190px; width:696px; height:265px; z-index:12;}
		#tabla-detallesconvenio{position:absolute;left:30px;top:490px;width:696px;height:150px;z-index:12;overflow:scroll;}
		-->
   	 </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    
<?php if ( !isset($_POST["sbt_guardar"]) && !isset($_POST["sbt_agregar"]) && !isset($_POST["sbt_eliminar"]) ){	
		$estado=$_POST["txt_estado"];?>
		<div id="titulo-barra">	<p class="titulo_barra">Modificar Convenios</p></div>
		<form name="frm_modificarProvTerminCon" onsubmit="return valEstadoModConvenio(this);" method="post" action="">
			<fieldset class= "borde_seccion" id="contenido" name="contenido">
			<legend class="titulo_etiqueta">Convenio <?php echo $_POST["hdn_conv"];?></legend>
				<table cellpadding="5" cellspacing="5" class="tabla_frm">
                    <tr>
                        <td><div align="right">Estado del Convenio</div></td>
                        <td><select name="cmb_estado" id="cmb_estado" class="combo_box">
                                <option value="">Seleccionar</option>
                                <option <?php if ($estado=="POR INICIAR") echo "selected='selected' ";?>value="POR INICIAR">POR INICIAR</option>
                                <option <?php if ($estado=="VIGENTE") echo "selected='selected' ";?>value="VIGENTE">VIGENTE</option>
                                <option <?php if ($estado=="TERMINADO") echo "selected='selected' ";?>value="TERMINADO">TERMINADO</option>
                                <option <?php if ($estado=="PROXIMO A TERMINAR") echo "selected='selected' ";?>value="PROXIMO A TERMINAR">PR&Oacute;XIMO A TERMINAR</option>
                                <option <?php if ($estado=="RENNOVADO") echo "selected='selected' ";?>value="RENNOVADO">RENNOVADO</option>
                                <option <?php if ($estado=="CANCELADO") echo "selected='selected' ";?>value="CANCELADO">CANCELADO</option>
                            </select>
                        </td>
                        <td><div align="right">Nueva Fecha Fin</div></td>
                        <td><input name="txt_fechaFin" type="text" value="<?php echo $_POST["txt_fechaFin"]?>" size="10" maxlength="15" readonly=true width="50"/></td>
                    </tr>
                    <tr>
                        <td valign="top">
							<input type="hidden" name="hdn_conv" id="hdn_conv" value="<?php echo $_POST["hdn_conv"]?>"/>
                            Comentarios
                        </td>
                        <td>
                            <textarea name="txa_comentarios" onkeypress="return permite(event,'num_car', 0);" 
                             id="txa_comentarios" cols="30" rows="3" maxlength="120" class="caja_de_texto"><?php echo 
                             obtenerDato("bd_compras","convenios","comentarios","id_convenio",$_POST["hdn_conv"])?></textarea>
                        </td>
                    </tr>
    			</table>  
			</fieldset>

			<div id="botones" align="center">
				<input name="sbt_guardar" type="submit" class="botones" value="Finalizar" title="Guardar los cambios y regresa al Men&uacute; de Proveedores" 
        	    onMouseOver="window.status='';return true"/>&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="sbt_agregar" type="submit" class="botones" value="Agregar" title="Agregar T&eacute;rminos al Convenio" 
	            onMouseOver="window.status='';return true"/>&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="sbt_eliminar" type="submit" class="botones" value="Eliminar" title="Eliminar T&eacute;rminos del Convenio" 
        	    onMouseOver="window.status='';return true"/>&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Proveedores"
    	         onclick="location.href='menu_proveedores.php'" onMouseOver="window.status='';return true"/>
			</div>
	   	</form>

		<div id="tabla-mostrarConvenioDetalle" class="borde_seccion2">
			<?php mostrarConvenioDetalle()?>
		</div>

		<div id="calendario-fin">
    		<input name="calendario_fin" type="image" id="calendario_fin" onclick="displayCalendar(document.frm_modificarProvTerminCon.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
	<?php }//Fin del IF que compara si se presiono alguno de los botones del formulario
	else{	
		//Si el boton Finalizar esta definido, realizar la funcion de Guardar los cambios
		if (isset($_POST["sbt_guardar"])){
			?><div id="operaciones" class="borde_seccion2"><?php
				guardarCambios();
			?></div><?php
		}
		
		//Si el botón Agregar esta definido, realizar la funcion de Agregar Termino
		if (isset($_POST["sbt_agregar"])){
			$band=0;
			$convenio="";
			$estado_convenio = "";
			$fechaFin = "";
			if(!isset($_POST["cmb_estado"])){
				$band=insertarTermino();
				$convenio=$_POST["txt_convenio"];
				$estado_convenio = $_POST['hdn_estado'];
				$fechaFin = $_POST['hdn_fechaFin'];				
			}
			else{//Obtener los datos cuando vienen del Formulario de Detalle del Convenio
				$convenio=$_POST["hdn_conv"];
				$estado_convenio = $_POST['cmb_estado'];
				$fechaFin = $_POST['txt_fechaFin'];
			}
			?>			
			<form name='frm_agregaConv' method='post' onsubmit="return valModConvenioAgregar(this);" action="frm_modificarProvTerminCon.php">
			<fieldset id="operacionAgregar" class="borde_seccion">
			<legend class="titulo_etiqueta">Agregar Detalles al Convenio  <?php echo $convenio;?> </legend>	
				<input type="hidden" name="hdn_estado" value="<?php echo $estado_convenio; ?>" />
				<input type="hidden" name="hdn_fechaFin" value="<?php echo $fechaFin; ?>" />
				<?php agregarTermino($convenio); ?>
				<div align="center">
					<?php if ($band==1){//Colocar el Boton de Finalizar y los datos necesarios para regresar al Formulario de Editar el Detalle del Convenio ?>
						<label class="msje_correcto">¡T&eacute;rmino Agregado con &Eacute;xito!</label><br/>						
						<input type="hidden" name="hdn_conv" value="<?php echo $convenio?>" />
						<input type="hidden" name="cmb_convenios" value="<?php echo $convenio?>" />
						<input type="hidden" name="txt_estado" value="<?php echo $estado_convenio?>" />
						<input type="hidden" name="txt_fechaFin" value="<?php echo $fechaFin?>" />						
						<input name="sbt_Finalizar" type="submit" class="botones" value="Finalizar" title="Guardar los cambios y regresa al Men&uacute; de Proveedores" 
        	   			 onclick="hdn_bandera.value='finalizar';" onMouseOver="window.status='';return true"/>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input type="hidden" name="hdn_bandera" id="hdn_bandera" value=""/>
					<input name="sbt_agregar" type="submit" class="botones" value="Agregar" title="Agregar T&eacute;rminos al Convenio" onMouseOver="window.status='';return true"/>&nbsp;&nbsp;&nbsp;&nbsp;
					<?php if ($band!=1){//Colocar el Boton de Regresar y los datos necesarios para regresar al Formulario de Editar el Detalle del Convenio ?>
						<input name="sbt_regresar" type="submit" value="Regresar" class="botones" title="Regresar al Detalle del Convenio" onMouseOver="window.status='';return true" onclick="hdn_bandera.value='finalizar';"/>						
						<input type="hidden" name="hdn_conv" value="<?php echo $convenio?>" />
						<input type="hidden" name="cmb_convenios" value="<?php echo $convenio?>" />
						<input type="hidden" name="txt_estado" value="<?php echo $estado_convenio?>" />
						<input type="hidden" name="txt_fechaFin" value="<?php echo $fechaFin?>" />
					<?php }?>						
				</div>
			</fieldset>
			</form>
			<?php
		}//Cierre if(isset($_POST["sbt_agregar"]))
		
		//Si el boton de Eliminar esta definido, eliminar el Termino Correspondiente
		if(isset($_POST["sbt_eliminar"])){
			if(isset($_POST["cmb_estado"])){//Obtener los datos cuando vienen del Formulario de Detalle del Convenio
				$convenio=$_POST["hdn_conv"];
				$estado_convenio = $_POST['cmb_estado'];
				$fechaFin = $_POST['txt_fechaFin'];
			}
			else{//Recuperar los datos de las cajas de Texto Ocultas declaradas en el Formulario de frm_eliminaDetalleConv
				$convenio=$_POST["hdn_conv"];
				$estado_convenio = $_POST['txt_estado'];
				$fechaFin = $_POST['txt_fechaFin'];
			}?>									
			<form name="frm_eliminaDetalleConv" onsubmit="return valTerminoSeleccionadoConvenio(this);" method="post" action="op_modificarConvenio.php?del">
				<input type="hidden" name="hdn_conv" value="<?php echo $convenio?>" />
				<input type="hidden" name="cmb_convenios" value="<?php echo $convenio?>" />
				<input type="hidden" name="txt_estado" value="<?php echo $estado_convenio?>" />
				<input type="hidden" name="txt_fechaFin" value="<?php echo $fechaFin?>" />				
				<input type="hidden" name="hdn_bandera" id="hdn_bandera" value=""/>
				<div id="operaciones" class="borde_seccion2">
					<?php mostrarTermino(); ?>
				</div>
				<div align="center" id="botones">
					<input name="sbt_eliminar" type="submit" class="botones" value="Eliminar" title="Eliminar T&eacute;rminos del Convenio" onMouseOver="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Detalle del Convenio" onMouseOver="window.status='';return true"
					onclick="hdn_bandera.value='regresar'; document.frm_eliminaDetalleConv.action='frm_modificarProvTerminCon.php'; document.frm_eliminaDetalleConv.submit();" />
				</div>
			</form><?php
		}//Cierre if (isset($_POST["sbt_eliminar"]))
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
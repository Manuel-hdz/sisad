<?php

	/**
	  * Nombre del Módulo: Seguridad Industrial                                                
	  * Nombre Programador: Daisy Adriana Martínez Fernández - Nadia Madahi Lopez Henrndez
	  * Fecha: 29/Febrero/2012
	  * Descripción: Archivo que permite cargar el registro de las nuevas condiciones de seguridad
	  **/  
	    
	//Titulo de la ventana emergente
	echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
	//Inlcuimos archivo que contiene las operaciones necesarias para el registro
	include ("op_seleccionarPermiso.php");
	//Archivo de validacion
	echo "<script type='text/javascript' src='../../includes/validacionSeguridad.js'></script>";
	//Archivo de Estilo
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";

	//Archivo que permite controlar el numero de caracteres ingresados en las cajas de texto (textarea)
	echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";

	//Incluimos archivo para modificar fechas segun sea requerido	
	include_once("../../includes/func_fechas.php");?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
	<style type="text/css">
		<!--
		#titulo-agregar-registros { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
		#tabla-agregarRegistro {position:absolute;left:30px;top:10px;width:707px;height:84px;z-index:12;}
		.Estilo1 {
			font-family: Arial, Helvetica, sans-serif;
			font-size: 12px;
		}
		-->
	</style>
	<?php $bandera = "";?>
	<script language="javascript" type="text/javascript">
		if(window.closed){
			function actualizarPag(){
				alert("Registro Guardado con Éxito, para las nuevas condiciones");
				//permisoAlturas();
				<?php $bandera = 1;?>		
			}
		}
	</script><?php 
	
	if(isset($_GET['clave'])){
		$clave = $_GET['clave'];
	}?>
	
	<body onUnload="actualizarPag();">	
	
	<p>&nbsp;</p>
	<form onSubmit="return valFormRegNvasCondiciones(this);" method="post" name="frm_agregarRegistro" id="frm_agregarRegistro" action="verRegNuevasCondiciones.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar Condici&oacute;n de Seguridad </legend>
    <br />
    	<table width="689" height="43"  cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
          		<td width="85" height="-7"><div align="right">          		  
       		    *Condición</div></td>
       		  <td width="567"><input type="text" name="txt_actividades" id="txt_actividades" value="" size="90" maxlength="340"
			  onkeypress="return permite(event,'num_car',7);" 
					class="caja_de_texto"/></td>
        	</tr>
			<tr>
          		<td colspan="2">
					<div align="center">
						<input name="sbt_guardarActividad" type="submit" class="botones" id= "sbt_guardarActividad" value="Guardar" title="Guardar Registro de la Nueva Condición" 
						onmouseover="window.status='';return true"/>
						<input type="hidden" name="hdn_clave" id="hdn_clave" value="<?php echo $clave; ?>"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cerrar" title="Regresar a Complementar el Permiso Alturas" 
						onmouseover="window.status='';return true" onClick="confirmarSalida('verComplementoPermisoAlturas.php'); window.close();"/>

					</div>				</td>
	        </tr>
    	</table>
	</fieldset>
	</form>

	</body>
	
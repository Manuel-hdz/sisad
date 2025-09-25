<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional                                               
	  * Nombre Programador:Nadi Madahí López Hernández
	  * Fecha: 26/Septiembre/2012
	  * Descripción: Este archivo contiene el formulario para registraruna nueva empresa externa
	  **/ 

	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("op_gestionarSolicitud.php");?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />	
	<script type="text/javascript" src="../../includes/validacionClinica.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" language="javascript">
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
		#form-registro {position:absolute; left:20px; top:10px; width:697px; height:289px; z-index:1; }		
		#resultado-opr { position:absolute; left:21px; top:11px; width:730px; height:320px; z-index:2; }
		-->
    </style>
		
</head>

	<script language="javascript" type="text/javascript">
		function cerrarVentana(){
			if(document.getElementById("hdn_msje").value=="si"){
				window.opener.focus();
				window.opener.location="frm_gestionarSolicitud.php";
				window.close();
			}
		}
		
	</script>
	
	<?php
	//Función que se encarga de verificar y validar en caso de que el usuario haya cerrado la ventana emergente desde la X
	 //Definimos una variable bandera
	 $bandera = "";?>
	<script language="javascript" type="text/javascript">
		//Dentro de un Script se define que si la ventana emergente se ha cerrado que se ejecute la siguiente funcion
		if(window.closed){
			//Se declara la funcion correspondiente
			function actualizarPag(){
				//Dentro de la funcion se declara una variable la cual contendra el valor del CKB
				var nomCkb = document.getElementById("hdn_nomCheckBox").value;
				window.opener.document.getElementById(nomCkb).checked = false;
				<?php $bandera = 1;?>		
			}
		}
	</script>
	
	
		
	<body onunload="cerrarVentana();actualizarPag();">
	<?php

	//Cuando sea seleccionado el boton guardar, guardar los datos de la Empresa en la BD	
	if(isset($_POST['sbt_guardar']))
		guardarNvaEmpresaExt();
	
	//Obtener el ID de la Nueva Empresa que será registrada
	$id = obtenerIdEmpresa();?>	
	
	<fieldset class="borde_seccion" id="form-registro" name="form-registro">
	<legend class="titulo_etiqueta">Registrar Nueva Empresas Externas</legend>
	<form onSubmit="return valFormRegNvaEmpExt(this);" name="frm_nvaEmpExt" id="frm_nvaEmpExt" method="post" action="verNuevaEmpresa.php"><br />
	<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
		<tr>
			<td><div align="right">* Nombre Empresa </div></td>
			<td><input type="text" name="txt_nomEmpresa" id="txt_nomEmpresa" maxlength="100" size="30" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num_car',1);"/>
			</td>
			<td><div align="right">No. Externo</div></td>
			<td>
				<input type="text" name="txt_numExt" id="txt_numExt" maxlength="10" size="5" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num',1);"/>				
			</td>
		</tr>
		<td width="19%"><div align="right">*Raz&oacute;n Social </div></td>
			<td>
				<input type="text" name="txt_razonSocial" id="txt_razonSocial" maxlength="80" size="30" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num_car',1);" />				
			</td>
			<td><div align="right">Ciudad</div></td>
			<td>
				<input type="text" name="txt_ciudad" id="txt_ciudad" maxlength="60" size="30" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num_car',1);"/>				
			</td>
		</tr>
		<tr>
			<td><div align="right">Tipo Empresa </div></td>
			<td>
				<input type="text" name="txt_tipoEmpresa" id="txt_tipoEmpresa" maxlength="60" size="30" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num_car',1);"/>				
			</td>
			<td><div align="right">Estado</div></td>
			<td>
				<input type="text" name="txt_estado" id="txt_estado" maxlength="60" size="30" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num_car',1);" />				
			</td>
		</tr>
		<tr>
			<td><div align="right">Calle</div></td>
			<td>
				<input type="text" name="txt_calle" id="txt_calle" maxlength="60" size="30" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num_car',1);"/>				
			</td>
			<td><div align="right">Colonia</div></td>
			<td>
				<input type="text" name="txt_colonia" id="txt_colonia" maxlength="100" size="30" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num_car',1);"/>				
			</td>
		</tr>
		<tr>
			<td><div align="right">No. Interno</div></td>
			<td><input type="text" name="txt_numInt" id="txt_numInt" maxlength="10" size="5" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num',1);"/>				
			</td>
			<td><div align="right">Tel&eacute;fono</div></td>
			<td>
				<input type="text" name="txt_tel" id="txt_tel" maxlength="60" size="30" class="caja_de_texto" 
				value="" onkeypress="return permite(event,'num',3);"  onblur="validarTelefono(this);"  />				
			</td>
		</tr>
		<tr> 
			<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>
		<tr>
			<td colspan="5" align="center">
				<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_nuevaEmpresa"/>			
				<input type="hidden" name="hdn_nomCmb" id="hdn_nomCmb" value="cmb_empresa"/>			

				<input type="hidden" name="hdn_msje" id="hdn_msje" value="no"/>
				<input type="submit" name="sbt_guardar" value="Guardar" class="botones" title="Guardar Registro" onmouseover="window.status='';return true" onclick="hdn_msje.value='si'"/>
				&nbsp;&nbsp;
				<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Limpiar los Campos del Formulario"/>
				&nbsp;&nbsp;
				<input type="button" name="btn_cerrar" value="Cerrar" class="botones" title="Cerrar Ventana de Registro" onclick="window.close();" />
			</td>
		</tr>
	</table>	
	</form>
</fieldset>
	
</body>
</html>
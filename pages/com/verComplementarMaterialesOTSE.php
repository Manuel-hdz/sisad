<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 19/Abril/2012
	  * Descripción: Este archivo contiene el formulario para registrar los Materiales en la OTSE
	  **/ 

	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("op_consultarOTSE.php"); ?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>	
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js" ></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js"></script>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
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
		#tabla-registrarActividades{position:absolute;left:30px;top:30px;width:620px;height:160px;z-index:13;padding:15px;	padding-top:0px;}
		#tabla-mostrarActividades{position:absolute;left:30px;top:224px;width:620px;height:320px;z-index:14;overflow:scroll;}
		-->
    </style>
</head>
<body><?php	
	

	//Agregar los datos de los Materiales cuando se le de clic al boton de Agregar (sbt_agregarMat)
	$msg = "";
	if(isset($_POST["sbt_agregarMat"])){
		$msg = registrarMaterial();		
	}//Cierre if(isset($_POST["sbt_agregarMat"]))?>
	
		
	<fieldset class="borde_seccion" id="tabla-registrarActividades" name="tabla-registrarActividades">
	<legend class="titulo_etiqueta">Registrar Materiales</legend>
	
	
	<?php //Dejar el atributo 'action' vacio para que al momento de guardar la url se pase tal cual ya que ahí viene el ID de la OTSE que esta siendo complementada ?>		
	<form onSubmit="return valFormMaterialesUtilizar(this);" name="frm_materialesUtilizar" method="post" action="">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  	<td width="20%" align="right" valign="top">*Material</td>
			<td width="40%" rowspan="2">
			  	<textarea name="txa_material" id="txa_material" cols="50" rows="3" class="caja_de_texto"  maxlength="120" onkeyup="return ismaxlength(this)" 
				onkeypress="return permite(event,'num_car', 0);"></textarea>
			</td>      	    
      	    <td width="20%" valign="top" align="right">Cantidad</td>
      	    <td width="20%" valign="top" rowspan="1">
				<input type="text" name="txt_cantidad" id="txt_cantidad" class="caja_de_texto" onkeypress="return permite(event,'num', 2);" size="5" maxlength="10" />
			</td>
  	  	</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" align="center" class="msje_incorrecto"><?php echo $msg; ?></td>
		</tr>
      	<tr>
      		<td colspan="4" align="center">						
				<input name="sbt_agregarMat" type="submit" class="botones" value="Agregar" title="Registrar Material" onmouseover="window.status='';return true" />       	 		
				&nbsp;&nbsp;
                <input name="btn_cerrar" type="button" class="botones" value="Cerrar" title="Cerrar Ventana" onclick="window.close();" />			
			</td>
		</tr>
	</table>
	</form>
	</fieldset>


	<div id="tabla-mostrarActividades" class="borde_seccion2" align="center"><?php
		mostrarMaterialesRegistrados();?>
	</div>

	
</body>
</html>
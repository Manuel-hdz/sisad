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
		//Archivo que incluye la operación para importar un archivo CSV a la BD
		include ("op_importarCSV.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="includes/ajax/validarArchivo.js" ></script>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
		#titulo-importarCSV {position:absolute; left:30px; top:146px; width:173px; height:25px; z-index:11; }
		#tabla-importar-csv {position:absolute; left:30px; top:191px; width:592px; height:125px; z-index:14;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-importarCSV">Importar CSV</div>	

<fieldset class="borde_seccion" id="tabla-importar-csv">
<legend class="titulo_etiqueta">Seleccionar Archivo a Importar</legend>	
<br>		
<form onSubmit="return validarArchivo(this);" method="post" name="frm_importarCSV" id="frm_importarCSV" enctype="multipart/form-data">
	<table align="center">
    	<tr>
        	<td><p>Elegir archivo </p></td>
	        <td><input name="upfile" type="file" onchange="validarCSV(this);archivoCSV('<?php echo date("Y-m-d");?>');" size="36" /></td>
				<input type="hidden" name="hdn_docValido" id="hdn_docValido"value="no"/>
				<input type="hidden" name="hdn_sentencia" id="hdn_sentencia"value="I"/>
        </tr>
        <tr>
			<td colspan="2">
				<div align="center">
          			<input name="sbt_subir" type="submit" class="botones" id="sbt_subir" value="Subir"
            		onmouseover="window.status='';return true;" title="Subir Archivo CSV"/>
					&nbsp;
          			<input name="btn_cancelar" type="button" class="botones"  value="Cancelar" title="Cancelar Subir Archivo CSV" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_nominaBancaria.php'" />		
				</div>
			</td>
		</tr>
	</table>
</form> 		
</fieldset>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
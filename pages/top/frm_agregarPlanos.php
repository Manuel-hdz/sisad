<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_agregarPlanos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarPlano {position:absolute;left:30px;top:190px;width:750px;height:216px;z-index:12;}
		#calendario{position:absolute;left:532px;top:232px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Agregar Planos </div>
	<fieldset class="borde_seccion" id="tabla-agregarPlano" name="tabla-agregarPlano">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Plano </legend>	
	<br>
	<?php if(isset($_GET["error"])){?>
		<script>
			setTimeout("alert('El Formato del Archivo no es Válido. Sólo se Permiten Archivos DWG');",500);
		</script><?php }?>
	<form  name="frm_agregarPlano" method="post" action="frm_agregarPlanos.php"  onsubmit="return valFormRegPlanos(this);" enctype="multipart/form-data">
	  <table  cellpadding="5" cellspacing="5" class="tabla_frm">
      	<tr>
   			<td width="120"><div align="right">Id Plano </div></td>
          	<td>
				<input name="txt_idPlano" id="txt_idPlano" type="text" class="caja_de_texto" size="15" maxlength="13" 
				value="<?php echo $id_plano=obtenerIdPlano();?>" readonly="readonly" />
			</td>
          	<td width="190"><div align="right">Fecha</div></td>
          	<td><input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
        </tr>
       	<tr>
			<td><div align="right">*Nombre Plano </div></td>
			<td><input name="txt_nomPlano" id="txt_nomPlano" type="text" class="caja_de_texto" size="40" maxlength="60" onkeypress="return permite(event,'num_car',0);"/></td>
			<td><div align="right">*Plano</div></td>
			<td><input type="file" name="file_documento" id="file_documento" size="36" value=""/></td>
       	</tr>
        <tr>
         	<td><div align="right">Descripci&oacute;n</div></td>
          	<td>
				<textarea name="txa_descripcion" id="txa_descripcion" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea>         
			</td>
			<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>
		<tr>
       	  	<td colspan="4">
				<div align="center"> 
					<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Plano"
					onMouseOver="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/> 
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Planos" 
					onmouseover="window.status='';return true" onclick="location.href='menu_planos.php'" />
				</div>			
			</td>
		</tr>
   	  </table>
	</form>
</fieldset><?php
	//Variable que almacena el nombre del Archivo Fisico en caso que este haya sido cargado
	$archivo="";
	//Variable que indica si el archivo fue subido con éxito
	$resSubirArch="";
	
	//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado
	if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
		$archivo=$_FILES["file_documento"]["name"];
		$resSubirArch = subirPlanos($archivo);
	}?>
    <div id="calendario">
   	  <input name="calendario_planos" type="image" id="calendario_planos" onclick="displayCalendar (document.frm_agregarPlano.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />
    </div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="includes/ajax/buscarClausula.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:429px;height:20px;z-index:11;}
		#tabla-seleccionarConsulta {position:absolute;left:30px;top:190px;width:546px;height:107px;z-index:12;}
		-->
    </style>
</head>
<body>
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Seleccionar Tipo de Consulta </div>
	<form action="frm_seleccionarConsulta.php" method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" onsubmit="return valFormRegFormLista(this);">
	<fieldset class="borde_seccion" id="tabla-seleccionarConsulta" name="tabla-seleccionarConsulta">
    <legend class="titulo_etiqueta">Seleccionar Consulta </legend>
    <table width="530" height="94"  cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
        	<td width="121" height="31"><div align="right">*Tipo Consulta </div></td>
         	<td width="610">
			<select name="cmb_consulta" id="cmb_consulta" size="1" class="combo_box" onchange="selConsulta();">
                <option value="">Consultas</option>
				<option value="ALMACEN">ALMAC&Eacute;N</option>
				<option value="RECURSOS">RECURSOS HUMANOS</option>
             </select>
			</td>
       	</tr>
		<tr>
        	<td colspan="6"><div align="center">
            	<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Inicio" 
				onmouseover="window.status='';return true" onclick="location.href='inicio_seguridad.php'" />
          </div></td>
	  </tr>
    </table>
	</fieldset>  
	</form>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
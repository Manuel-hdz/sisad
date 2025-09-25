<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include ("op_generarRepMuestras.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
		<!--
		#titulo-generar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-generarNombramiento {position:absolute;left:30px;top:190px;width:908px;height:340px;z-index:12;}
		#res-spider{position:absolute; z-index:13;}
		#calendarioIni {position:absolute;left:839px;top:233px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-generar">Generar Reporte</div>
	<fieldset class="borde_seccion" id="tabla-generarNombramiento" name="tabla-generarNombramiento">
	<legend class="titulo_etiqueta">Generar </legend>	
	<br>

	<form onSubmit="return valFormGenerarRepMuestras(this);" name="frm_generarRepMuestras" method="post" action="frm_generarRepMuestras.php">
        
	<?php //Aqui comienza otra tabla para poder tener una alineacion distinta a la anterior ?>
    <table width="923"  cellpadding="5" cellspacing="5" class="tabla_frm">
       
        <tr>        
        </tr>
        <tr>
            <td colspan="4"><div align="center">
			
			<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" title="Ver Archivo PDF de la Requisición Seleccionada" onmouseover="window.status='';return true" 
					onclick="window.open('../../includes/generadorPDF/reportePruebas.php?id=PBA0511002', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>
				
                &nbsp;&nbsp;&nbsp;
                </div>
            </td>        
        </tr>
	</table>    
    </form>
    </fieldset><?php
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
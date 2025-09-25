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
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" language="javascript">
		setTimeout("document.getElementById('txt_idMezcla').focus();",500);
	</script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarMezcla {position:absolute;left:30px;top:190px;width:690px;height:250px;z-index:14;}
		#div-calendario { position:absolute; left:674px; top:291px; width:30px; height:26px; z-index:15; }
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Mezcla</div>
	<fieldset class="borde_seccion" id="tabla-agregarMezcla" name="tabla-agregarMezcla">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n de la Mezcla</legend>	
	<form onSubmit="return valFormAgregarMezcla(this);" name="frm_agregarMezcla" method="post" action="frm_agregarMezcla2.php">
    <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td><div align="right"> *Id Mezcla</div></td>
            <td colspan="3">
				<input name="txt_idMezcla" type="text" class="caja_de_texto" id="txt_idMezcla"  size="30" maxlength="30" 
            	onkeypress="return permite(event,'num_car',4);" onblur="return verificarDatoBD(this,'bd_laboratorio', 'mezclas', 'id_mezcla', 'nombre');" tabindex="1" />
	            <span id='error' class="msj_error">Id Duplicada</span>
            </td>
      	</tr>
        <tr>
            <td><div align="right">*Nombre de Mezcla</div></td>
            <td colspan="3">
				<input type="text" name="txt_nombreMezcla" id="txt_nombreMezcla" value="" size="40" maxlength="90" 
                onkeypress="return permite (event, 'num_car',4);" tabindex="2" />            
			</td>
        </tr>
        <tr>
            <td width="25%"><div align="right">*Expediente</div></td>
            <td width="25%">
				<input type="text" name="txt_expediente" id="txt_expediente" value="" size="5" maxlength="4" 
                onkeypress="return permite (event, 'num',2);" tabindex="3" />            
			</td>
            <td width="25%" align="right">Fecha de Registro</td>
            <td width="25%">
				<input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" class="caja_de_texto" value="<?php echo date("d/m/Y"); ?>" readonly="readonly" size="10" />
			</td>
        </tr>
        <tr>
            <td><div align="right">*Equipo de Mezclado</div></td>
            <td>
				<input type="text" name="txt_eqMezclado" id="txt_eqMezclado" value="" size="30" maxlength="30"
                onkeypress="return permite (event, 'num_car',0);" tabindex="5"/>            
			</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>      
        <tr>
            <td colspan="4"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>
        <tr>
            <td colspan="4">
                <div align="center">
                	<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/>
                    <input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar"  value="Continuar" title="Continuar para Registrar Materiales" 
                    onmouseover="window.status='';return true" tabindex="6"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
                    onmouseover="window.status='';return true" onclick="error.style.visibility='hidden';" tabindex="7" />
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
                    title="Cancelar y Regresar al Men&uacute; de Mezclas " 
                    onmouseover="window.status='';return true" onclick="confirmarSalida('menu_mezclas.php');" tabindex="8"/>
                </div>          
            </td>
        </tr>
    </table>
	</form>
	</fieldset>
	
    <div id="div-calendario">
      <input type="image" name="txt_fechaRegistro" id="txt_fechaRegistro" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_agregarMezcla.txt_fechaRegistro,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
		title="Seleccionar Fecha de Colado" tabindex="4" />
	</div>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
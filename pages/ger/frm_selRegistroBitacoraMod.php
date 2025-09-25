<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
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
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-seleccionar {position:absolute;left:30px;top:146px;	width:262px;height:20px;z-index:11;}
		#tabla-seleccionarBit {position:absolute;left:30px;top:190px;width:290px;height:125px;z-index:14;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-seleccionar">Modificar Registro de la Bit&aacute;cora</div>
	<fieldset class="borde_seccion" id="tabla-seleccionarBit" name="tabla-seleccionarBit">
	<legend class="titulo_etiqueta">Seleccionar el Tipo de Registro a Modificar</legend>	
	<br>
	<form onSubmit="return valFormSeleccionarBitModificar(this);" name="frm_selRegistroBitacoraMod" method="post" action="">
    <table cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
            <td><div align="right">Tipo de Registro</div></td>
            <td>
            	<select name="cmb_tipoBit" id="cmb_tipoBit" class="combo_box">
                	<option value="">Tipo Registro</option>
                    <option value="TRANSPORTE">TRANSPORTE</option>
                    <option value="ZARPEO">ZARPEO</option>
                </select>
            </td>
		</tr>        
		<tr>		
			<td colspan="2">
				<div align="center">       	    	
				<input name="sbt_continuarReg" type="submit" class="botones"  value="Continuar" title="Continuar con el Registro de la Bitacora" 
				onMouseOver="window.status='';return true"/>
				&nbsp;&nbsp;&nbsp;
                <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar" 
                onMouseOver="window.status='';return true" onclick="location.href='menu_bitacora.php';"/>
				</div>
            </td>
        </tr>
    </table>
    </form>
    </fieldset>
</body>

<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
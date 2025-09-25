<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php


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
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:338px;height:20px;z-index:11;}
		#tabla-registrarPruebas {position:absolute;left:30px;top:190px;width:487px;height:120px;z-index:14;}
		-->
    </style>
</head>
<body><?php

	if(isset($_GET['cancelar'])){
		if(isset($_SESSION['resPruebas']))
			unset($_SESSION['resPruebas']);	
		if(isset($_SESSION['fotosPruebas'])){
			include_once ("op_registrarPruebas.php");
			borrarFotosLab();
			unset($_SESSION['fotosPruebas']);	
		}
	}
	if(isset($_SESSION['pruebasEjecutadas']))
		unset($_SESSION['pruebasEjecutadas']);?>
	

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar Resultado de Pruebas</div>
    <fieldset class="borde_seccion" id="tabla-registrarPruebas" name="tabla-registrarPruebas">
    <legend class="titulo_etiqueta">Seleccionar Origen del Resultado de Pruebas</legend>	
	<br>
	<form onSubmit="return valFormRegistrarPruebas(this);" name="frm_registrarPruebas" method="post">
    <table width="484" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
          <td width="38"><div align="right">Origen</div></td>
            <td colspan="2">
                <select name="cmb_origen" id="cmb_origen" class="combo_box" onchange="activarCampo(this);">
                	<option value="">Origen</option>
                    <option value="AGREGADOS">AGREGADOS</option>
                    <option value="MEZCLAS">MEZCLAS</option>
                </select>         
			</td>
			  <td width="103"><div id="div_registro" style="visibility:hidden" align="right">Tipo Registro</div></td>
			<td width="167">
                <select name="cmb_registro" id="cmb_registro" class="combo_box" style="visibility:hidden" >
                	<option value="">Tipo Registro</option>
                    <option value="RENDIMIENTO">RENDIMIENTO</option>
                    <option value="RESISTENCIAS">RESISTENCIAS</option>
                </select>          
			</td>
        </tr>
        <tr>
         	<td colspan="5">
                <div align="center">
  					<input type="submit" name="sbt_continuar" id="sbt_continuar" value="Continuar" title="Continuar" class="botones" 
               		onmouseover="window.status='';return true" />
  					&nbsp;&nbsp;&nbsp;
                  	<input type="button" name="btn_regresar"  id="btn_regresar" value="Regresar" title="Regresar al menú de Mezclas"
                	onmouseover="window.status='';return true" onclick="location.href='menu_mezclas.php';" class="botones"/>            
                </div>
			</td>
      </tr>
    </table>
    </form>
    </fieldset>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
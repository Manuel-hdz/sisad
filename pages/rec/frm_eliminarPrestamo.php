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
		include ("op_eliminarPrestamo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
		<!--
		#titulo-eliminar {position:absolute;left:30px;top:146px;width:299px;height:20px;z-index:11;}
		#tabla-eliminarPrestamo {position:absolute;left:30px;top:190px;width:760px;height:159px;z-index:14;}
		#res-spider{position:absolute; z-index:15;}
		#mostrarBonos {position:absolute;left:30px;top:371px;width:954px;height:201px;z-index:12;overflow:scroll}
		#btnEliminar {position:absolute;left:30px;top:624px;width:954px;height:29px;z-index:12;}
		-->
    </style>
</head>
<body>
	
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-eliminar">Eliminar Pr&eacute;stamos de Empleados</div>
    <fieldset class="borde_seccion" id="tabla-eliminarPrestamo" name="tabla-eliminarPrestamo">
    <legend class="titulo_etiqueta">Eliminar Pr&eacute;stamos</legend>	
	<br>
	<form onSubmit="return valFormEliminarPrestamo(this);" name="frm_eliminarPrestamo" method="post" action="frm_eliminarPrestamo.php">
    <table width="760"  cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="152"><div align="right">&Aacute;rea</div></td>
            <td width="256"><?php $dat=cargarComboConId("cmb_area","area","area","empleados","bd_recursos","&Aacute;rea","",		
				"txt_nomEmpleado.value='';lookup(txt_nomEmpleado,'empleados',cmb_area.value,'1');");
				if($dat==0){
					echo "<label class='msje_correcto'><u><strong> NO</u></strong> hay &Aacute;reas Registradas</label>
					<input type='hidden' name='cmb_area' id='cmb_area'/>";
				}?>            
            </td>
        </tr>
        <tr>
            <td><div align="right">Nombre del Empleado</div></td>
            <td>
                <input type="text" name="txt_nomEmpleado" id="txt_nomEmpleado" onkeyup="obtenerNombreRFCEmpleado(this,'empleados',cmb_area.value,'1');" 
                value="" size="50" maxlength="70" onkeypress="return permite(event,'car',0);" class="caja_de_texto" 
                onblur="obtenerRFCEmpleado(this.value, 'txt_RFCEmpleado');"/>
                <div id="res-spider">
                    <div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
                        <img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                      <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
                    </div>
                </div>            
            </td>  
            <td width="144"><div align="right">RFC del Empleado</div></td>
            <td width="141">
                <input name="txt_RFCEmpleado" id="txt_RFCEmpleado" type="text" class="caja_de_texto" size="20" readonly="readonly"/></td>
        </tr>
        <tr>
            <td colspan="4"><div align="center">
              <input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar"  value="Consultar" title="Consultar Préstamos del Empleado Seleccionado" 
                onmouseover="window.status='';return true" />
                &nbsp;&nbsp;&nbsp;
              <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_prestamos.php';"
                title="Regresar al men&uacute; de Préstamos"/></div>
            </td>   	
		</tr>        
	</table>
    </form>
    </fieldset><?php
	
	if (isset ($_POST['sbt_eliminar'])){
		eliminarPrestamoSeleccionado();
	}

	//Si esta definido sbt_consultar se muestran los bonos del empleado seleccionado 
	if(isset($_POST["sbt_consultar"])){?>
        <form onSubmit="return valFormEliminarPrestamo2(this);" name="frm_eliminarPrestamo2" method="post" action="frm_eliminarPrestamo.php">
            <input type="hidden" name="txt_RFCEmpleado" value="<?php echo $_POST['txt_RFCEmpleado'];?>" />
            <input type="hidden" name="sbt_consultar" value="Consultar" />
            <div id='mostrarBonos' class='borde_seccion2' align="center"><?php
				$control= mostrarPrestamos();?>
            </div><?php
			if ($control==1){?>
                <div id='btnEliminar' align="center">
                    <input name="sbt_eliminar" type="submit" class="botones" id="sbt_eliminar"  value="Eliminar" title="Eliminar el Préstamo Seleccionado" 
                    onmouseover="window.status='';return true" />
                    <input type="hidden" name="txt_justificacion" id="txt_justificacion" value=""/>
                </div><?php 
            } ?>
        </form><?php
	}?>		
</body>

<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
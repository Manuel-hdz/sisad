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
		include ("op_generarNombramiento.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/obtenerNombramiento.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
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
<body><?php 

	//Obtener la fecha del sistema para el nombramiento
	$txt_fechaNombramiento = date("d/m/Y");?>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-generar">Generar Nombramiento</div>
	<fieldset class="borde_seccion" id="tabla-generarNombramiento" name="tabla-generarNombramiento">
	<legend class="titulo_etiqueta">Nombramiento Oficial </legend>	
	<br>

	<form onSubmit="return valFormGenerarNombramiento(this);" name="frm_generarNombramiento" method="post" action="frm_generarNombramiento.php">
    <table width="923" height="110" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="37"><div align="right">*Nombre</div></td>
            <td>
                <input type="text" name="txt_nombre" id="txt_nombre" onkeyup="obtenerNombreRFCEmpleado(this,'empleados','todo','1');" 
                value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" onblur="obtenerRFCEmpleado(this.value, 'txt_RFCEmpleado');"/>
                <div id="res-spider">
                    <div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
                        <img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                      <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
                    </div>
                </div>            
            </td>
            <td><div align="right">Fecha Nombramiento:</div></td>
            <td ><input name="txt_fechaNombramiento" id="txt_fechaNombramiento" type="text" class="caja_de_texto" size="10" maxlength="10" 
                onkeypress="return permite(event,'car',0);" value="<?php echo $txt_fechaNombramiento;?>" readonly="readonly"/>
            </td>
        </tr>
        <tr>
	        <td><div align="right">*&Aacute;rea</div></td>
            <td><?php 
				$cmb_area="";
				$conn = conecta("bd_recursos");
				$result=mysql_query("SELECT DISTINCT area FROM empleados WHERE  `estado_actual` =  'ALTA' ORDER BY area");?>
				<select name="cmb_area" id="cmb_area" size="1" class="combo_box" 
                onchange="cargarCombo(this.value,'bd_recursos','empleados','puesto','area','cmb_puesto','Puesto',''); txa_objetivo.value=''">
					<option value="">&Aacute;rea</option><?php 
						while ($row=mysql_fetch_array($result)){
							if ($row['area'] == $cmb_area){
								echo "<option value='$row[area]' selected='selected'>$row[area]</option>";
							}
							else{
								echo "<option value='$row[area]'>$row[area]</option>";
							}
						} 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
				</select>            
            </td>
            <td>
            	<div align="right">
                    <input type="checkbox" name="ckb_nuevaArea" id="ckb_nuevaArea" 
                    onclick="agregarNuevaArea(this, 'ckb_nuevoPuesto', 'txt_nuevaArea', 'txt_nuevoPuesto', 'cmb_area', 'cmb_puesto');" 
                    title="Seleccione para escribir el nombre de un &Aacute;rea que no exista"/>Agregar Nueva &Aacute;rea 
          		</div>
            </td>
            <td><input name="txt_nuevaArea" id="txt_nuevaArea" type="text" class="caja_de_texto" readonly="readonly" size="30" maxlength="30"/></td>
        </tr>
    	<tr>
            <td><div align="right">*Puesto</div></td>
            <td>
                <select name="cmb_puesto" id="cmb_puesto" onchange="cargarNombramiento(cmb_area.value,this.value);">
                    <option value="">Puesto</option>
                </select>
            </td>  
            <td>
            	<div align="right">
                    <input type="checkbox" name="ckb_nuevoPuesto" id="ckb_nuevoPuesto" 
                    onclick="agregarNuevoPuesto(this, 'ckb_nuevaArea', 'txt_nuevaArea', 'txt_nuevoPuesto', 'cmb_area', 'cmb_puesto');" 
                    title="Seleccione para escribir el nombre de un Puesto que no exista"/> Agregar Nuevo Puesto 
                </div>
            </td>
            <td><input name="txt_nuevoPuesto" id="txt_nuevoPuesto" type="text" class="caja_de_texto" readonly="readonly" size="30" maxlength="30"/></td>      
        </tr>
	</table>
	<?php //Aqui comienza otra tabla para poder tener una alineacion distinta a la anterior ?>
    <table width="923"  cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
	        <td width="353"  valign="top"><div align="left">*Objetivo B&aacute;sico del Puesto:</div></td>
            <td rowspan="1" valign="baseline" ><textarea name="txa_objetivo" id="txa_objetivo"  onkeyup="return ismaxlength(this)" 
                class="caja_de_texto" rows="6" cols="127" onkeypress="return permite(event,'num_car', 0);" ></textarea>
            </td>  
        </tr>
        <tr>
           <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>        
        <tr>        
        </tr>
        <tr>
            <td colspan="4"><div align="center">
                <input type="hidden" name="txt_RFCEmpleado" id="txt_RFCEmpleado" value=""/>
                <input name="sbt_generar" type="submit" class="botones" id="sbt_generar"  value="Generar" title="Generar Nombramiento" 
                onmouseover="window.status='';return true"/>
                &nbsp;&nbsp;&nbsp;
                <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true" 
                onclick="cmb_area.disabled=false;cmb_puesto.disabled=false"/>
                &nbsp;&nbsp;&nbsp;
                <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar Nombramiento y regresar al Men&uacute; Administrativo" 
                onmouseover="window.status='';return true" onclick="confirmarSalida('menu_administrativo.php');"/>
                &nbsp;&nbsp;&nbsp;
                <input name="btn_consultar" type="button" class="botones_largos" value="Consultar Nombramientos" 
                title="Consultar Nombramientos Registrados" onmouseover="window.status='';return true" 
                onclick="location.href='frm_consultarNombramientos.php? btn_consultar  '"/></div>
            </td>        
        </tr>
	</table>    
    </form>
    </fieldset>
	<div id="calendarioIni">
        <input type="image" name="txt_fechaNombramiento" id="txt_fechaNombramiento" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_generarNombramiento.txt_fechaNombramiento,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha del Nombramiento"/> 
    </div></body><?php

	// si viene definido sbt_generar quiere decir que se han agregado los datos para proceder a guardar el nombramiento y generar el respectivo pdf
	if (isset($_POST['sbt_generar'])) {
		guardarNombramiento();
	}
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		include ("op_agregarDeduccion.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
    <script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarDeduccion {position:absolute;left:30px;top:190px;width:840px;height:275px;z-index:14;}
		#res-spider{position:absolute; z-index:15;}
		#deduccionesAgregadas {position:absolute;left:32px;top:493px;width:914px;height:161px;z-index:12; overflow:scroll}
		-->
    </style>
</head>
<body><?php //Obtener la fecha del sistema
	$fecha = date("d/m/Y");
	//Verificamos que se haya pulsado el boton de agregar otro para proceder a cargar los datos en el arreglo de sesion
	if(isset($_POST['sbt_agregar'])){
		//darle formato a la cantidad 
		$cantidad= floatval(str_replace(",","",$txt_totalDed));	
		//Verificamos si viene el combo Activo de deduccion o viene de la caja de texto para proceder a almacenarlo al arreglo de session
		if (isset($_POST["cmb_tipoDeduccion"]))
			$deduccion=$_POST["cmb_tipoDeduccion"];
		else
			$deduccion=strtoupper($_POST["txt_nuevaDeduccion"]);

		//Si esta definido el arreglo, añadir el siguiente elemento a el	
		if(isset($_SESSION['deducciones'])){	
			$deducciones[] = array ("rfc"=>$_POST['txt_RFCEmpleado'], "nom_empleado"=>$_POST['txt_nomEmpleado'], "claveDed"=>strtoupper($_POST['txt_claveDed']),
			"tipoDeduccion"=>$deduccion, "cantidad"=>$cantidad, "descripcion"=>strtoupper($_POST['txa_descripcion']), "fecha"=>$fecha);
			//Guardar los datos en la SESSION
			$_SESSION['deducciones'] = $deducciones;	
		}				
		else{//Si no esta definido el arreglo, definirlo
			//Crear el arreglo con los datos del bono
			$deducciones = array(array ("rfc"=>$_POST['txt_RFCEmpleado'], "nom_empleado"=>$_POST['txt_nomEmpleado'], "claveDed"=>strtoupper($_POST['txt_claveDed']),
			"tipoDeduccion"=>$deduccion, "cantidad"=>$cantidad, "descripcion"=>strtoupper($_POST['txa_descripcion']), "fecha"=>$fecha));
			//Guardar los datos en la SESSION
			$_SESSION['deducciones'] = $deducciones;
		}
	}?>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Alta de  Deducciones</div>
	<fieldset class="borde_seccion" id="tabla-agregarDeduccion" name="tabla-agregarDeduccion">
	<legend class="titulo_etiqueta">Alta de Deducci&oacute;n </legend>	
	<br>
	<form onSubmit="return valFormAgregarDeduccion(this);" name="frm_agregarDeduccion" method="post" action="frm_agregarDeduccion.php">
    <table width="816"  cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="147"><div align="right">&Aacute;rea</div></td>
            <td width="300"><?php $dat=cargarComboConId("cmb_area","area","area","empleados","bd_recursos","&Aacute;rea","",		
                                    "txt_nomEmpleado.value='';lookup(txt_nomEmpleado,'empleados',cmb_area.value,'1');");
                if($dat==0){
                    echo "<label class='msje_correcto'><u><strong> NO</u></strong> hay &Aacute;reas Registradas</label>
                    <input type='hidden' name='cmb_area' id='cmb_area'/>";
                }?>          
            </td>
            <td><div align="right">*Clave Deducci&oacute;n</div></td>
            <td><input name="txt_claveDed" id="txt_claveDed" type="text" class="caja_de_texto" size="10" maxlength="20" 
                onkeypress="return permite(event,'num_car',2);" />            
            </td>
	    </tr>
        <tr>
            <td><div align="right">*Nombre del Empleado</div></td>
            <td>
                <input type="text" name="txt_nomEmpleado" id="txt_nomEmpleado" onkeyup="obtenerNombreRFCEmpleado(this,'empleados',cmb_area.value,'1');" 
                value="" size="50" maxlength="80" onkeypress="return permite(event,'car',0);" onblur="obtenerRFCEmpleado(this.value,'txt_RFCEmpleado');"/>
                <div id="res-spider">
                    <div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
                        <img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                      <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
                    </div>
                </div>            
            </td>  
            <td width="135"><div align="right">RFC del Empleado</div></td>
            <td width="167">
              <input name="txt_RFCEmpleado" id="txt_RFCEmpleado" type="text" class="caja_de_texto" size="20" readonly="readonly"/>        
            </td>
        </tr>
        <tr>
            <td><div align="right">*Tipo de Deducci&oacute;n</div></td>
            <td><?php 
				$cmb_tipoDeduccion="";
				$result=mysql_query("SELECT DISTINCT nom_deduccion FROM deducciones WHERE id_deduccion NOT LIKE 'CLF%' ORDER BY nom_deduccion");?>
				<select name="cmb_tipoDeduccion" id="cmb_tipoDeduccion" class="combo_box">
					<option value="">Seleccionar Deducci&oacute;n</option>
						<?php while ($row=mysql_fetch_array($result)){
							if ($row['nom_deduccion'] == $cmb_tipoDeduccion){
								echo "<option value='$row[nom_deduccion]' selected='selected'>$row[nom_deduccion]</option>";
							}
							else{
								echo "<option value='$row[nom_deduccion]'>$row[nom_deduccion]</option>";
							}
						}?>
				</select>
			</td>
            <td>
            	<div align="right">
                    <input type="checkbox" name="ckb_nuevaDed" id="ckb_nuevaDed" 
                    onclick="agregarNuevaDed(this, 'txt_nuevaDeduccion', 'cmb_tipoDeduccion');" 
                    title="Seleccione para Escribir el Nombre de una Deducción que no Exista"/>Nueva Deducci&oacute;n
                </div>            
            </td>
            <td><input name="txt_nuevaDeduccion" id="txt_nuevaDeduccion" type="text" class="caja_de_texto" size="20"readonly="readonly"/> </td>                        
        </tr>
        <tr>
            <td><div align="right">*Total Deducci&oacute;n</div></td>
            <td>$<input name="txt_totalDed" id="txt_totalDed" type="text" class="caja_de_texto" size="10" maxlength="10" 
                onkeypress="return permite(event,'num',2);" value="<?php echo number_format('txt_totalDed',2,".",",");?>" 
                onchange="formatCurrency(this.value,'txt_totalDed');"/>            
            </td>
            <td><div align="right">Descripci&oacute;n</div></td>
            <td><textarea name="txa_descripcion" id="txa_descripcion" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
                onkeypress="return permite(event,'num_car', 0);" ></textarea> 
            </td>
        </tr>
        <tr>
            <td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>
        <tr>
            <td colspan="4"><div align="center">
                <input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar otra Deducción" 
                onmouseover="window.status='';return true" />
                &nbsp;&nbsp;&nbsp;<?php
                if (isset($_SESSION["deducciones"])){?>
                    <input name="btn_finalizar" type="button" class="botones" id="btn_finalizar"  value="Finalizar" title="Finalizar Registro de Deducciones" 
                    onmouseover="window.status='';return true" onclick="location.href='frm_agregarDeduccion.php?btn_finalizar'"/>
                    &nbsp;&nbsp;&nbsp;<?php 
				} ?>
                <input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
                onmouseover="window.status='';return true" onclick="cmb_tipoDeduccion.disabled=false;"/>
                &nbsp;&nbsp;&nbsp;
              <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Deducciones " 
                onmouseover="window.status='';return true" onclick="confirmarSalida('menu_deducciones.php');"/></div>            
            </td>   	
        </tr>        
    </table>
    </form>
    </fieldset><?php 
	//Si esta inicializado el arreglo de Sesion, mostrar los conceptos agregados
	if(isset($_SESSION['deducciones'])){?>
        <div id='deduccionesAgregadas' class='borde_seccion2'><?php
                    mostrarDeduccionesAdd();?>
        </div><?php
	}
	//Si esta se ha presionado el boton finalizar proceder a guardar los datos almacenados en la sesion
	if(isset($_GET['btn_finalizar'])){
		guardarDeducciones();
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
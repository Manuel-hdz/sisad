<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Manejo de la funciones para Registrar los datos de los Convenios en la BD 
		include("op_registrarConvenio.php");
		
?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>	
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>
	<script type="text/javascript" src="../../includes/ajax/actualizarIVA.js"></script>
    <style type="text/css">
	<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:179px; height:22px; z-index:11;	}
		#tabla1 { position:absolute; left:30px; top:190px; width:720px; height:80px; z-index:17;}
		#tabla2 { position:absolute; left:30px; top:300px; width:720px; height:320px; z-index:12;}
		#calendario-inicio {position:absolute; left:253px; top:365px; width:29px; height:25px; z-index:13; }
		#calendario-fin {position:absolute; left:252px; top:402px; width:29px; height:25px; z-index:14; }
		#calendario-elaboracion {position:absolute; left:252px; top:442px; width:29px; height:25px; z-index:15; }
		#editar-iva { position:absolute; left:730px; top:360px; width:35px; height:30px; z-index:16; }
		-->
   	</style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div id="titulo-barra" class="titulo_barra">Registrar  Convenio</div>
	<?php 
	//Variables para amnejar los montos de l importe, iva y total
	$importe = 0;
	$iva = 0;
	$total = 0;
	
	if (!isset($_GET["sbt_registrar"])){
		//Si se detecta cancela en la URL, quitar de la session el arreglo detallesconvenio
		if (isset($_GET["cancela"]))
			unset($_SESSION["detallesconvenio"]);
		//Si esta definido en el GET btn, pasar a $proveedor el valor de $txt_nombre y a $convenio el de $txt_convenio
		if (isset($_GET["btn"])){
			$proveedor=$txt_nombre;
			$convenio=$txt_convenio;
						
			//Si esta definido el arreglo detallesconvenio en la Session, obtener el importe y pasarlo a la caja de Texto TOTAL
			if (isset($_SESSION["detallesconvenio"])){
				$importe = $hdn_imp;
				$iva = ($importe*$_SESSION['porcentajeIVA'])/100;
				$total = $importe + $iva;
			}
						
			//Pasar a las variables el valor de los campos hidden para posteriormente asignarles el valor a los elementos del formulario
			$resp=$hdn_resp;
			$coment=$hdn_coment;
			$autor=$hdn_auto;
			$estado=$hdn_estado;
			$fechaI=$hdn_fechaI;
			$fechaF=$hdn_fechaF;
			$fechaE=$hdn_fechaE;
		}
		else{
			//Si no se detecto ningun botón presionado, dejar las variables de los valor de formulario en los valores de inicio
			$proveedor="";
			$convenio="";
			$importe=0;
			$valor="value=0.00";
			$resp="";
			$coment="";
			$autor="";
			$estado="";
			$fechaI=date("d/m/Y");
			$fechaF=date("d/m/Y",strtotime("+30 day"));
			$fechaE=date("d/m/Y");
		}?>
		
	<form onSubmit="return valFormRegistrarConvenio(this);" name="frm_registrarConvenio" method="post" action="frm_registrarConvDetConv.php">
        <fieldset id="tabla1" class="borde_seccion">
        <legend class="titulo_etiqueta">Seleccionar Proveedor</legend>	
        <br>	
            <table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
       		  <tr>
                <td valign="top" align="right">*Proveedor</td>
                <td colspan="3">
                    <input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'bd_compras','proveedores','razon_social','1');"
                     value="<?php echo $proveedor ?>" size="60" maxlength="80" />
                    <div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
                        <img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                        <div class="suggestionList" id="autoSuggestionsList1" title="Seleccionar Proveedor">&nbsp;</div>
                    </div>			
                </td>
              </tr>
            </table>
        </fieldset>
	
        <fieldset id="tabla2" class="borde_seccion">
        <legend class="titulo_etiqueta">Registrar Convenio</legend>	
            <table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
                <tr>
                    <td><div align="right">*Convenio</div></td>
                    <td>
                        <input name="txt_convenio" type="text" class="caja_de_texto" value="<?php echo $convenio; ?>" onkeypress="return permite(event,'num_car');" 
                        size="10" maxlength="10"/></td>
                    <td align="right" valign="top">Subtotal</td>
                    <td>$
                        <input name="txt_subtotal" id="txt_subtotal" type="text" class="caja_de_texto" title="Valor obtenido con los detalles" 
                        onclick="alert ('Este valor se obtiene a partir de los detalles de Convenio');" 
                        onkeypress="return permite(event,'num');" value="<?php echo number_format($importe,2,".",",")?>" size="15" maxlength="20" readonly="true"/>                    </td>
                </tr>
                <tr>
                    <td><div align="right">Fecha Inicio </div></td>
                    <td><input name="txt_fechaInicio" type="text" value=<?php echo $fechaI; ?> size="10" maxlength="15" readonly=true width="50" /></td>
                    <td><div align="right">IVA</td>
                    <td>$
                        <input name="txt_iva" id="txt_iva" type="text" class="caja_de_texto" onkeypress="return permite(event,'num');" 
                        value="<?php echo number_format($iva,2,".",",")?>" size="15" maxlength="20" readonly="true" />
                        <input type="text" name="txt_lblIVA" id="txt_lblIVA" class="caja_de_num" onclick="alert ('IVA calculado en base al '+this.value);"
                        value="<?php echo $_SESSION['porcentajeIVA'];?>%" size="4" maxlength="10" 
                        readonly="true" />                    </td>
                </tr>
                <tr>
                    <td><div align="right">Fecha Fin </div></td>
                    <td><input name="txt_fechaFin" type="text" value=<?php echo $fechaF; ?> size="10" maxlength="15" readonly=true width="50" /></td>
                    <td><div align="right">Total</div></td>
                    <td>$
                        <input name="txt_total" id="txt_total" type="text" class="caja_de_texto" onclick="alert ('Valor obtenido mediante Subtotal + IVA');" 
                        onkeypress="return permite(event,'num');" 
                        size="15" maxlength="20" readonly="true" value="<?php echo number_format($total,2,".",",")?>" />                    </td>
                </tr>
                <tr>
                    <td><div align="right">Fecha Elaboraci&oacute;n </div></td>
                    <td><input name="txt_fechaElaboracion" type="text" value=<?php echo $fechaE; ?> size="10" maxlength="15" readonly="true" width="50" /></td>
                    <td><div align="right">*Estado</div></td>
                    <td>
                        <select name="cmb_estado" id="cmb_estado" class="combo_box">
                            <option <?php if ($estado=="") echo "selected='selected' ";?>value="">Seleccionar</option>
                            <option <?php if ($estado=="POR INICIAR") echo "selected='selected' ";?>value="POR INICIAR">POR INICIAR</option>
                            <option <?php if ($estado=="VIGENTE") echo "selected='selected' ";?>value="VIGENTE">VIGENTE</option>
                            <option <?php if ($estado=="TERMINADO") echo "selected='selected' ";?>value="TERMINADO">TERMINADO</option>
                            <option <?php if ($estado=="PROXIMO A TERMINAR") echo "selected='selected' ";?>value="PROXIMO A TERMINAR">PR&Oacute;XIMO A TERMINAR</option>
                            <option <?php if ($estado=="RENNOVADO") echo "selected='selected' ";?>value="RENNOVADO">RENNOVADO</option>
                            <option <?php if ($estado=="CANCELADO") echo "selected='selected' ";?>value="CANCELADO">CANCELADO</option>
                   		 </select>                    </td>
                </tr>
                <tr>
                    <td><div align="right">*Responsable</div></td>
                    <td><input name="txt_responsable" type="text" onkeypress="return permite(event,'car',2);" value="<?php echo $resp; ?>" size="40" maxlength="60"/></td>
                    <td><div align="right">*Comentarios</div></td>
                    <td><textarea name="txa_comentarios" class="caja_de_texto" cols="30" rows="2" id="txt_comentarios"><?php echo $coment; ?></textarea></td>
                </tr>
                <tr>
                    <td><p align="right">*Autoriza</p></td>
                    <td><input name="txt_autoriza" type="text" size="40" onkeypress="return permite(event,'car',2);" value="<?php echo $autor; ?>" maxlength="60"/></td>
                    <td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div align="center">
                            <?php
                            //Si no se encuentra botón declarado en el GET, mostrar el boton de Detalles Convenio
                            if(!isset($_GET["btn"])||$_GET["btn"]!="sbt_finalizar"){?>
                                <input name="sbt_detConv" type="submit" class="botones_largos" value="Detalles Convenio" title="Detalles del Convenio"
                                onmouseover="window.status='';return true" />
                            &nbsp;&nbsp;&nbsp;
                            <?php }?>
                            <?php if(isset($_SESSION['detallesconvenio'])){ ?>
                                    <input name="sbt_registrar" type="submit" class="botones" value="Registrar" title="Registrar el Convenio" 
                                    onclick="document.frm_registrarConvenio.action='frm_registrarConvenio.php?sbt_registrar=1';" onmouseover="window.status='';return 
                                    true"/>
                                    &nbsp;&nbsp;&nbsp; <?php 
                                  }?>
                            <input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar los Campos que Contienen Informaci&oacute;n" 
                            onmouseover="window.status='';return true" />
                            &nbsp;&nbsp;&nbsp;
                            <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar a la Página de Menú de Proveedores" 
                            onclick="location.href='menu_proveedores.php'" 
                            onmouseover="window.status='';return true" />
                        </div>                    </td>
                </tr>
            </table>	
		</fieldset>
	</form>
	
	<div id="calendario-inicio">
    	<input name="calendario_ini" type="image" id="calendario_ini" onclick="displayCalendar(document.frm_registrarConvenio.txt_fechaInicio,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
</div>
    
	<div id="calendario-fin">
    	<input name="calendario_fin" type="image" id="calendario_fin" onclick="displayCalendar(document.frm_registrarConvenio.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
</div>
    
	<div id="calendario-elaboracion">
    	<input name="calendario_elaboracion" type="image" id="calendario_elaboracion"
        onclick=   		"displayCalendar(document.frm_registrarConvenio.txt_fechaElaboracion,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
</div>
    
	<div id="editar-iva">
		<input type="image" src="../../images/editar.png" width="30" height="25" border="0" onclick="actualizarIVA('txt_subtotal','txt_iva','txt_total');"
        title="Modificar la Tasa de IVA" />
</div>
    
	<?php }
	else{
		guardarConvenio();
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
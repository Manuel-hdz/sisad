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
		include ("op_generarEstimacion.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
    <script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="includes/ajax/verificarQuincena.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_obras.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:210px;height:20px;z-index:11;}
		#tabla-buscarObra {position:absolute;left:30px;top:190px;width:428px;height:150px;z-index:12;}
		#tabla-buscarObraSpider {position:absolute;left:530px;top:190px;width:428px;height:150px;z-index:13;}
		#tabla-registrarEstimacion {position:absolute;left:30px;top:190px;width:847px;height:425px;z-index:14;}
		#calendarioElaboracion {position:absolute;left:750px;top:237px;width:30px;height:26px;z-index:15;}
		#res-spider {position:absolute;z-index:16;}
		-->
    </style>
</head>
<body><?php

	if(isset($_GET["tipoObra"])){
		$tipoObra=$_GET["tipoObra"];
		$nomObra=$_GET["nomObra"];
		?>
		<script type="text/javascript" language="javascript">
			setTimeout("cargarCombo('<?php echo $tipoObra?>','bd_topografia','obras','nombre_obra','tipo_obra','cmb_nomObra','Obra','<?php echo $nomObra?>');",500);
		</script>
		<?php
	}
	else{
		$tipoObra="";
	}

	// Obtener las fechas para cada caja de texto
	$txt_fechaElaborado= date("d/m/Y");?>
	
    <div class="titulo_barra" id="titulo-registrar">Registrar Estimaci&oacute;n</div><?php
	
	if(!isset($_POST['sbt_registrar'])){?>
        <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	
        <fieldset class="borde_seccion" id="tabla-buscarObra" name="tabla-buscarObra">
        <legend class="titulo_etiqueta">Seleccione la Obra para Registrar la Estimaci&oacute;n</legend>	
        <br>
        <form onSubmit="return valFormBuscarObra(this);" name="frm_generarEstimacion" method="post" action="frm_generarEstimacion.php">
        <table width="417" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td width="82"><div align="right">Tipo Obra</div></td>
                <td width="298">
				<?php
                    $res = cargarComboConId("cmb_obra","tipo_obra","tipo_obra","obras","bd_topografia","Tipo Obra",$tipoObra,
					"cargarCombo(this.value,'bd_topografia','obras','nombre_obra','tipo_obra','cmb_nomObra','Obra','');");														
                    if($res==0){?>
                        <label class="msje_correcto"><u><strong>NO</strong></u> Hay Obras Registradas</label>
                        <input type="hidden" name="cmb_obra" id="cmb_obra" value="" /><?php 
                    } ?> 
                </td>
            </tr>
            <tr>
                <td><div align="right">Nombre Obra</div></td>
                <td><?php
					if($res==1){?>
                        <select name="cmb_nomObra" id="cmb_nomObra" class="combo_box">
                            <option value="">Obra</option>
                        </select><?php
					}
					else{?>	
                        <label class="msje_correcto"><u><strong>NO</strong></u> Hay Nombres de Obras Registradas</label>
                        <input type="hidden" name="cmb_nomObra" id="cmb_nomObra" value="" /><?php 
                    } ?>  
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div align="center"><?php
                        if($res==1){?>
                            <input name="sbt_registrar" type="submit" class="botones" id="sbt_registrar"  value="Registrar" title="Registrar Estimación" 
                            onmouseover="window.status='';return true" /><?php
                        }?>
                        &nbsp;&nbsp;&nbsp;
                        <input name="btn_regresar" type="button" class="botones" id="btn_regresar" value="Regresar" title="Regresar al Men&uacute; de Estimaciones " 
                        onmouseover="window.status='';return true" onclick="location.href='menu_estimaciones.php'"/>
                    </div>
                </td>
            </tr>
        </table>
        </form>
        </fieldset>
		
		<fieldset class="borde_seccion" id="tabla-buscarObraSpider" name="tabla-buscarObraSpider">
        <legend class="titulo_etiqueta">Ingresar la Obra para Registrar la Estimaci&oacute;n</legend>	
        <br>
        <form onSubmit="return valFormBuscarObraSpider(this);" name="frm_generarEstimacion" method="post" action="frm_generarEstimacion.php">
        <table width="417" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td><div align="right">Nombre Obra</div></td>
                <td>
					<input type="text" name="txt_nombreObra" id="txt_nombreObra" onkeyup="lookup(this,'1');" 
					value="" size="40" maxlength="40" onkeypress="return permite(event,'num_car',0);"/>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>
                </td>
            </tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
            <tr>
                <td colspan="2">
                    <div align="center"><?php
                        if($res==1){?>
                            <input name="sbt_registrar" type="submit" class="botones" id="sbt_registrar"  value="Registrar" title="Registrar Estimación" 
                            onmouseover="window.status='';return true" /><?php
                        }?>
                        &nbsp;&nbsp;&nbsp;
                        <input name="btn_regresar" type="button" class="botones" id="btn_regresar" value="Regresar" title="Regresar al Men&uacute; de Estimaciones " 
                        onmouseover="window.status='';return true" onclick="location.href='menu_estimaciones.php'"/>
                    </div>
                </td>
            </tr>
        </table>
        </form>
        </fieldset>
		<?php
	}
	
	if(isset($_POST['sbt_registrar'])){
		if(isset($_POST['cmb_obra']))
			$nomObra=$_POST["cmb_nomObra"];
		if(isset($_POST['txt_nombreObra']))
			$nomObra=$_POST["txt_nombreObra"];
		//Relizar la consulta con el id de la obra seleccionada para poder precargar los datos 
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");

		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM obras WHERE nombre_obra='$nomObra'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs)?>
    
        <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
        <fieldset class="borde_seccion" id="tabla-registrarEstimacion" name="tabla-registrarEstimacion">
        <legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n de la Estimaci&oacute;n</legend>	
        <br>
        <form onSubmit="return valFormRegEstimacion(this);" name="frm_generarEstimacion" method="post" action="frm_generarEstimacion.php">
		<table width="843" height="411"  cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td><div align="right">Tipo Obra</div></td>
                <td><input name="txt_tipo" id="txt_tipo" type="text" class="caja_de_texto" size="32" value="<?php echo $datos['tipo_obra'] ?>" 
                	readonly="readonly"/>                
                </td>
                <td width="171"><div align="right">Fecha Elaboraci&oacute;n</div></td>
                <td colspan="3"><input name="txt_fechaElaborado" id="txt_fechaElaborado" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly"
                    value="<?php echo $txt_fechaElaborado;?>"/>
                </td>
            </tr>
            <tr>
                <td><div align="right">Nombre Obra</div></td>
                <td><input name="txt_nombreObra" id="txt_nombreObra" type="text" class="caja_de_texto" size="40" readonly="readonly" 
                    value="<?php echo $datos['nombre_obra'] ?>"/>
                </td>
                <td align="right">*No. Quincena</td>
                <td>
                    <select name="cmb_noQuincena" id="cmb_noQuincena" class="combo_box" onchange="verificarQuincena('ESTIMACION');">
                        <option value="">Num.</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>						
                    <select name="cmb_Mes" id="cmb_Mes" class="combo_box" onchange="verificarQuincena('ESTIMACION');">
                        <option value="">Mes</option>
                        <option value="ENERO">Enero</option>
                        <option value="FEBRERO">Febrero</option>
                        <option value="MARZO">Marzo</option>
                        <option value="ABRIL">Abril</option>
                        <option value="MAYO">Mayo</option>
                        <option value="JUNIO">Junio</option>
                        <option value="JULIO">Julio</option>
                        <option value="AGOSTO">Agosto</option>
                        <option value="SEPTIEMBRE">Septiembre</option>
                        <option value="OCTUBRE">Octubre</option>
                        <option value="NOVIEMBRE">Noviembre</option>
                        <option value="DICIEMBRE">Diciembre</option>
                    </select>
                    <select name="cmb_Anio" id="cmb_Anio" class="combo_box" onchange="verificarQuincena('ESTIMACION');">
                        <option value="">A&ntilde;o</option><?php
                        //Obtener el Año Actual
                        $anioInicio = intval(date("Y")) - 10;
                        for($i=0;$i<21;$i++){
                            echo "<option value='$anioInicio'>$anioInicio</option>";
                            $anioInicio++;
                        }?>							
                    </select>
              </td>				
            </tr>
            <tr>
                <td><div align="right">Secci&oacute;n</div></td>
                <td><input name="txt_seccion" id="txt_seccion" type="text" class="caja_de_texto" size="10" readonly="readonly" 
                    value="<?php echo $datos['seccion'] ?>"/>
                </td>
                <td><div align="right">Unidad</div></td>
                <td colspan="3"><input name="txt_unidad" id="txt_unidad" type="text" class="caja_de_texto" size="10" readonly="readonly" 
                	value="<?php echo $datos['unidad'] ?>"/>
                </td>			
            </tr>
            <tr>
                <td><div align="right">*Cantidad</div></td>
                <td><input name="txt_cantidad" id="txt_cantidad" type="text" class="caja_de_texto" size="10" maxlength="10" value="" 
                    onkeypress="return permite(event,'num',2);" 
                    onchange="formatCurrency(this.value,'txt_cantidad'); txt_totalMN.value = parseFloat(txt_cantidad.value.replace(/,/g,''))*parseFloat(txt_precioMN.value.replace(/,/g,''));
					formatCurrency(txt_totalMN.value,'txt_totalMN'); 
					txt_totalUSD.value = parseFloat(txt_cantidad.value.replace(/,/g,''))*parseFloat(txt_precioUSD.value.replace(/,/g,''))*parseFloat(txt_tasaCambio.value.replace(/,/g,'')); 
					formatCurrency(txt_totalUSD.value,'txt_totalUSD'); 
					txt_importe.value = parseFloat(txt_totalMN.value.replace(/,/g,''))+parseFloat(txt_totalUSD.value.replace(/,/g,'')); formatCurrency(txt_importe.value,'txt_importe');"/>
                </td>			
                <td><div align="right">Total MN</div></td>
                <td colspan="3">$
                    <input name="txt_totalMN" id="txt_totalMN" type="text" class="caja_de_texto" size="10" readonly="readonly" value=""/>
                </td>
            </tr>	
            <tr>
                <td width="157"><div align="right">Precio Unitario M.N.</div></td>
                <td>$
                    <input name="txt_precioMN" id="txt_precioMN" type="text" class="caja_de_texto" size="10" value="<?php echo $datos['pumn_estimacion'] ?>" 
                    readonly="readonly"/>
                </td>
                <td><div align="right">Total USD</div></td>
                <td colspan="3">$
                    <input name="txt_totalUSD" id="txt_totalUSD" type="text" class="caja_de_texto" size="10" readonly="readonly" value=""/>
                </td>
                
            </tr>
            <tr>
                <td><div align="right">Precio Unitario USD.</div></td>
                <td>$
                    <input name="txt_precioUSD" id="txt_precioUSD" type="text" class="caja_de_texto" size="10" value="<?php echo $datos['puusd_estimacion']?>" 
                    readonly="readonly"/>
                </td>
                <td><div align="right">Importe</div></td>
                <td colspan="3">$
                    <input name="txt_importe" id="txt_importe" type="text" class="caja_de_texto" size="10" readonly="readonly" value=""
                    onchange="formatCurrency(value,'txt_importe');"/>
                </td>
                </tr>
            <tr>
                <td><div align="right">*Tasa de Cambio</div></td>
				<?php $tCambio=obtenerdato("bd_topografia","tasa_cambio","t_cambio","id",1);?>
                <td>$
                    <input name="txt_tasaCambio" id="txt_tasaCambio" type="text" class="caja_de_texto" size="10" maxlength="10" 
                    onkeypress="return permite(event,'num',2);" value="<?php echo $tCambio?>"
                    onchange="formatTasaCambio(this.value,'txt_tasaCambio'); txt_totalUSD.value= parseFloat(txt_cantidad.value.replace(/,/g,''))*parseFloat(txt_precioUSD.value.replace(/,/g,''))*parseFloat(txt_tasaCambio.value.replace(/,/g,'')); formatCurrency(txt_totalUSD.value,'txt_totalUSD'); txt_importe.value= parseFloat(txt_totalMN.value.replace(/,/g,''))+parseFloat(txt_totalUSD.value.replace(/,/g,'')); formatCurrency(txt_importe.value,'txt_importe');" />
                </td>	
            </tr>
            <tr>
                <td colspan="6"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
            </tr>
            <tr>
                <td colspan="6">
                    <div align="center">
                        <input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Estimación" 
                        onmouseover="window.status='';return true" />
                        <input type="hidden" name="hdn_idObra" id="hdn_idObra" value="<?php echo $datos['id_obra']?>"/>
                        <input type="hidden" name="hdn_idEstimacion" id="hdn_idEstimacion" value="<?php echo obtenerIdEstimacion();?>" />
                        &nbsp;&nbsp;&nbsp;
                        <input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
                        onmouseover="window.status='';return true"/>
                        &nbsp;&nbsp;&nbsp;
                        <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
                        title="Cancelar y Regresar al Men&uacute; de Estimaciones " 
                        onmouseover="window.status='';return true" onclick="confirmarSalida('frm_generarEstimacion.php');"/>
                    </div>
                </td>
            </tr>
        </table>
        </form>
        </fieldset><?php
        
        //Calendario  para la fecha de elaboración ?> 
        <div id="calendarioElaboracion">
          <input type="image" name="txt_fechaElaborado" id="txt_fechaElaborado" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_generarEstimacion.txt_fechaElaborado,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar la Fecha de la Estimación"/> 
        </div><?php
        
	}// fin del isset donde se comprueba que este definido cmb_obra?>		
</body>

<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
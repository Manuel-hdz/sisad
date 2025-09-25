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
		include ("op_listaPrecios.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/jsColor/jscolor.js" ></script>
   	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-precios {position:absolute;left:30px;top:146px;width:210px;height:20px;z-index:11;}
		#tabla-listaPrecios {position:absolute;left:30px;top:190px;width:923px;height:200px;z-index:14;}
		#tabla-listaDetPrecios {position:absolute;left:30px;top:190px;width:700px;height:156px;z-index:15;}
		#preciosAgregados {position:absolute;left:30px;top:190px;width:914px;height:394px;z-index:12;overflow:scroll;}
		#btnReg {position:absolute;left:30px;top:642px;width:952px;height:41px;z-index:15;}
		-->
    </style>
</head>
<body><?php 

	//Verificamos que se haya pulsado el boton de agregar para proceder a cargar los datos en el arreglo de sesion
	if(isset($_POST['sbt_agregar'])){

		$tipoTraspaleo=strtoupper($_POST["txt_nuevoTipoTraspaleo"]);
		$descripcion = strtoupper($_POST["txa_descripcion"]);
		$id_precios= obtenerIdPrecio();		

		//Si no esta definido el arreglo, definirlo
		//Crear el arreglo con los datos generales
		$preciosGral = array("id_precios"=>$id_precios, "tipoTraspaleo"=>$tipoTraspaleo ,"descripcion"=>$descripcion);
		//Guardar los datos en la SESSION
		$_SESSION['preciosGral'] = $preciosGral;
	}?>
    
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-precios">Lista de Precios</div><?php
	
	//si no esta definida la distancia de inicio mostrar los siguientes formularios de lo contrario desplegar la tabla con los rangos  
	if(!isset($_POST['txt_distancia_inicio'])){
		//Verificar si no viene definido el arreglo de sesion detallePrecios mostrar este formulario 
		if(!isset($_SESSION['preciosGral'])){?>
			<fieldset class="borde_seccion" id="tabla-listaPrecios" name="tabla-listaPrecios">
			<legend class="titulo_etiqueta">Agregar Lista de Precios</legend>	
			<br>
			<form onSubmit="return valFormAgregarPrecios(this);" name="frm_listaPrecios" method="post" action="">
			<table width="923" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">*Tipo de Traspaleo</div></td>
					<td><?php 
						//Desplegar la información en el combo	
						$conn = conecta("bd_topografia");
						$result=mysql_query("SELECT DISTINCT tipo, id_precios FROM precios_traspaleo");
						if($row=mysql_fetch_array($result)){?>
							<select name="cmb_tipoTraspaleo" id="cmb_tipoTraspaleo" class="combo_box" onchange="activarBotones(this);">
								<option value="">Seleccionar Tipo</option>
								<option value="NuevoRegistro">NUEVA LISTA</option><?php
								do{?>
									<option value="<?php echo $row['id_precios'];?>" title="<?php echo $row['tipo']?>"><?php echo $row['tipo']?></option>
									<?php
								}while ($row=mysql_fetch_array($result));
								//Cerrar la conexion con la BD		
								mysql_close($conn);?>
							</select><?php                   
						 }
						 else{
							//<label class="msje_correcto"><u><strong>NO</strong></u> Hay Precios de Traspaleos Registrados</label>?>
							<select name="cmb_tipoTraspaleo" id="cmb_tipoTraspaleo" class="combo_box" onchange="activarBotones(this);">
								<option value="">Agregar Nueva</option>
                                <option value="NuevoRegistro">NUEVA LISTA</option>
                            </select>
							<input type="hidden" name="cmb_tipoTraspaleo" id="cmb_tipoTraspaleo" value="" /><?php 
						}?>        
					</td> 
					<td> <div align="right"> Agregar Nombre de Nueva Lista</div></td>
					<td><input name="txt_nuevoTipoTraspaleo" id="txt_nuevoTipoTraspaleo" type="text" class="caja_de_texto" size="20" readonly="readonly"
                    onblur="return verificarDatoBD(this,'bd_topografia','precios_traspaleo','tipo','tipo');"/>
                    <span id="error" class="msj_error">Lista Duplicada</span></td>
				</tr> 
				<tr>
                    <td><div align="right">*Descripci&oacute;n</div></td>
                    <td  colspan="2"rowspan="1" valign="baseline" ><textarea name="txa_descripcion" id="txa_descripcion"  onkeyup="return ismaxlength(this)" 
						class="caja_de_texto" rows="2" cols="70" onkeypress="return permite(event,'num_car', 0);" disabled="disabled" ></textarea>            
                    </td>         
				</tr>
				<tr>
					<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>  
				<tr>
					<td colspan="4"><div align="center">
						<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value=""/>
               			<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
						<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar Nueva Lista de Precios" 
						onmouseover="window.status='';return true" disabled="disabled" onclick="hdn_botonSel.value='Agregar'"/>
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_modificar" type="submit" class="botones" id="sbt_modificar"  value="Modificar" 
						title="Modificar los Precios del Traspaleo Seleccionado" 
						onmouseover="window.status='';return true" disabled="disabled" onclick="hdn_botonSel.value='Modificar'"/>
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar"  value="Consultar" title="Consultar el Traspaleo Seleccionado" 
						onmouseover="window.status='';return true" disabled="disabled" onclick="hdn_botonSel.value='Consultar'"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
						title="Cancelar y Regresar al Men&uacute; de Traspaleos " 
						onmouseover="window.status='';return true" onclick="confirmarSalida('menu_traspaleo.php');"/></div>            
					</td>   	
				</tr>        
			</table>
			</form>
			</fieldset><?php 
		} //Fin	if(isset($_SESSION['preciosGral']))
		else if(isset($_SESSION['preciosGral'])){
			//Aqui cominenza el formulario donde se pide la distancia inicial, distancia final y  la distancia de cada intervalo para 
			//posteriormente realizar automaticamente los calculos necesarios?>
			<fieldset class="borde_seccion" id="tabla-listaDetPrecios" name="tabla-listaDetPrecios">
			<legend class="titulo_etiqueta">Registrar Detalle</legend>	
			<br>
			<form onSubmit="return valFormAgregarDetPrecios(this);" name="frm_listaPrecios" method="post" action="frm_listaPrecios.php">
			<table width="689"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="122"><div align="right">*Distancia Inicial</div></td>
					<td width="54">
						<input name="txt_distancia_inicio" id="txt_distancia_inicio" type="text" class="caja_de_texto" size="10" maxlength="10" 
						onkeypress="return permite(event,'num',3);" onchange="formatCurrency(this.value,'txt_distancia_inicio'); validaDistanciaFinal();" />
					</td> 
					<td width="122"><div align="right">*Distancia Final</div></td>
					<td width="54">
						<input name="txt_distancia_fin" id="txt_distancia_fin" type="text" class="caja_de_texto" size="10" maxlength="10" 
						onkeypress="return permite(event,'num',3);" onchange="formatCurrency(this.value,'txt_distancia_fin'); validaDistanciaFinal();"/>
					</td> 
					<td width="168"><div align="right">*Distancia de Intervalo</div></td>
					<td width="72">
						<input name="txt_distanciaIntervalo" id="txt_distanciaIntervalo" type="text" class="caja_de_texto" size="10" maxlength="10" 
						onkeypress="return permite(event,'num',3);" onchange="formatCurrency(this.value,'txt_distanciaIntervalo'); validaIntervalo();"/>
					</td> 
				</tr>
				<tr>	
					<td colspan="6"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
				</tr>
				<tr>
					<td colspan="6"><div align="center">
						<input name="sbt_agregarPrecios" type="submit" class="botones" id="sbt_agregarPrecios"  value="Agregar" title="Agregar otro Precio" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
						title="Cancelar y Regresar al Men&uacute; de Traspaleos " 
						onmouseover="window.status='';return true" onclick="confirmarSalida('menu_traspaleo.php');"/></div>            
					</td>   	
				</tr>        
			</table>
			</form>
			</fieldset>
			</div><?php 
		}// fin else if(isset($_SESSION['preciosGral']))
	}// FIN if(!isset($_POST['txt_distancia_inicio'])){
	
	//si esta definida en el $_POST la distancia de inicio, tambien viene la distancia final y los rangos por lo cual se muestra la tabla generada
	if(isset($_POST['txt_distancia_inicio'])){?>
        <form onSubmit="return valFormPrecios(this);" name="frm_listaPrecios" method="post" action="frm_listaPrecios.php">
            <div id='preciosAgregados' class='borde_seccion2'><?php
                generarRangos();?>
            </div>
            <div id='btnReg' align="center">  
                <input name="sbt_registrar" type="submit" id="sbt_registrar" class="botones" value="Registrar" title="Registrar la Nueva Lista Precios"/>
                &nbsp;&nbsp;&nbsp;
                <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
                title="Cancelar y Regresar al Men&uacute; de Traspaleos " 
                onmouseover="window.status='';return true" onclick="confirmarSalida('menu_traspaleo.php');"/></div>            
            </div>
		</form><?php
	}?>
</body>

<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado  ?>
</html>
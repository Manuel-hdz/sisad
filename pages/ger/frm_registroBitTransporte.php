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
		include ("head_menu.php");
		include ("op_agregarRegistroBitacora.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="includes/ajax/verificarDatos.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>     
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:383px;height:20px;z-index:11;}
		#tabla-registrarBit {position:absolute;left:30px;top:190px;width:786px;height:242px;z-index:14;}
		#calendario {position:absolute;left:250px;top:232px;width:30px;height:26px;z-index:13;}
		#res-spiderChofer{position:absolute; z-index:15;}
		#detalleRegBit { position:absolute; left:29px; top:456px; width:903px; height:222px; z-index:5; overflow:scroll}

		-->
    </style>
</head>
<body><?php
		
	//Si se ha presionado el boton de finalizar proceder a llamar la funcion que ser encarga de guardar los datos en la bd
	if(isset($_GET['btn_finalizar'])){
		guardarBitTrans();
	}
		
	if(!isset($_GET['btn_finalizar'])){

		//Recuperar la informacion que viene en el post al ser presionado el boton de guardar, para poder almacenarlo en el arreglo de session correspondiente
		if(isset($_POST['sbt_guardar'])){
			
			//Recuperar los Datos del Formulario para ser agregados en la SESSION	
			$fecha = modFecha($_POST['txt_fecha'],3);
			$nombre= $_POST['txt_nombre'];
			$cantidad= str_replace(",","",$_POST['txt_cantidad']);				
			$ubicacion= $_POST['cmb_ubicacion'];
			$cargo= $_POST['cmb_choferSup'];
			$comentarios= strtoupper($_POST['txa_comentarios']);
			$verComent = 0;
			if(isset($_POST['ckb_verComentario'])){
				$verComent = 1;
			}
						
			//Si esta definido el arreglo, añadir el siguiente elemento a el	
			$RegBitTransp[] = array ("fecha"=>$fecha,"nombre"=>$nombre,"cantidad"=>$cantidad,"ubicacion"=>$ubicacion,"cargo"=>$cargo,
			"comentarios"=>$comentarios, "verComent"=>$verComent);
			//Guardar los datos en la SESSION
			$_SESSION['RegBitTransp'] = $RegBitTransp;				
						
		}//FIN if(isset($_POST['sbt_guardar']))?>

    	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    	<div class="titulo_barra" id="titulo-registrar">Agregar Registro a la Bit&aacute;cora de Transporte</div>
	
		<fieldset class="borde_seccion" id="tabla-registrarBit" name="tabla-registrarBit">
		<legend class="titulo_etiqueta">Ingresar la Informaci&oacute;n del Registro de Transporte</legend>	
		<br>
		<form onSubmit="return valFormRegBitTrans(this);" name="frm_registroBitTransporte" method="post" action="frm_registroBitTransporte.php">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
                <td width="98"><div align="right">Fecha</div></td>
                <td width="123">
					<input name="txt_fecha" id="txt_fecha" type="text" class="caja_de_texto" size="10" readonly="readonly"
	                value="<?php if(!isset($_SESSION['RegBitTransp'])){ echo date("d/m/Y");} else {echo modFecha($_SESSION['RegBitTransp'][0]['fecha'],1);}?>" />                
				</td>
				<td width="247"><div align="right">*Ubicaci&oacute;n</div></td>
                <td colspan="3"><?php
					$res=cargarCombo("cmb_ubicacion","ubicacion","catalogo_ubicaciones","bd_gerencia","Ubicaci&oacute;n","");
					if($res==0){
						echo "<label class='msje_correcto'>No hay Ubicaciones Registradas</label>
						<input type='hidden' name='cmb_ubicacion' id='cmb_ubicacion' disabled='disabled'/>";
					}?>                
				</td>
            </tr>   
			<tr>
				<td><div align="right">*Nombre</div></td>
			  	<td colspan="2">
					<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados','1');" 
					value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" />
					<div id="res-spiderChofer">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>
				    <input type="hidden" name="hdn_rfc" id="hdn_rfc" value="" />
				</td>
			  	<td ><div align="right">*Cargo</div></td>
				<td width="123">
					<select name="cmb_choferSup" id="cmb_choferSup" class="combo_box">
						<option value="">Chofer/Suplente</option>
						<option value="CHOFER">CHOFER</option>
						<option value="SUPLENTE">SUPLENTE</option>
					</select>			  </td>
		  </tr>
			<tr>
				<td><div align="right">*Cantidad</div></td>
				<td>
                    <input type="text" name="txt_cantidad" id="txt_cantidad" class="caja_de_texto" value="" onkeypress="return permite(event,'num',2);"
					size="7" maxlength="7" onchange="formatCurrency(txt_cantidad.value,'txt_cantidad');"/> m&sup3;				</td>
                
                <td><div align="right">Observaciones</div></td>
				<td colspan="2"><textarea name="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="40" 
					onkeypress="return permite(event,'num_car');" ></textarea>				
				</td>                
			</tr>

			<tr>
				<td colspan="3"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
				<td colspan="2">
					<input type="checkbox" name="ckb_verComentario" value="si" />
					Mostrar Comentario en Reporte
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<div align="center"><?php
						if(isset($_SESSION['RegBitTransp'])){?>    	    	
							<input name="btn_finalizarReg" id="btn_finalizarReg" type="button" class="botones" value="Finalizar"   
							title="Guardar el Registro de Transporte en la Bitacora" onMouseOver="window.status='';return true"
							onclick="location.href='frm_registroBitTransporte.php?btn_finalizar'"/><?php
						}?>
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Registro" 
						onmouseover="window.status='';return true" onclick="verificaRegistros(txt_nombre.value,txt_fecha.value);"/>
                        &nbsp;&nbsp;&nbsp;
                        <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar" 
                        onMouseOver="window.status='';return true" onclick="location.href='frm_selRegistroBitacora.php';" />
					</div>				</td>
			</tr>
		</table>
		</form>
		</fieldset><?php
       	
		//Condicionar este calendario si ya se ha agregado un registro desaparecer este calendario para obligarlo a registrar todos los registros con la misma fecha
		if(!isset($_SESSION['RegBitTransp'])){?>
			<div id="calendario">
				<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
				onclick="displayCalendar(document.frm_registroBitTransporte.txt_fecha,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
				title="Seleccionar Fecha"/> 
			</div><?php
		}
        //Si esta inicializado el arreglo de Sesion, mostrar los conceptos agregados
		if(isset($_SESSION['RegBitTransp'])){?>
			<div id='detalleRegBit' class='borde_seccion2'><?php
				mostrarRegBitTransp();?>
			</div><?php
		}//FIN if(isset($_SESSION['RegBitTransp']))
	}//FIN 	if(!isset($_GET['btn_finalizar']))?>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
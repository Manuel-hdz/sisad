<!DOCTYPE  html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion 

html xmlns="http://www.w3.org/1999/xhtml"><?php


	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarBitacora.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" language="javascript"> 
		setTimeout("valFormFecha('txt_fechaMant');",500);
		
		function calcularCostoTotal(){
			var manoObra = parseFloat(document.getElementById("txt_costoManoObra").value.replace(/,/g,''));
			var costoMtto = parseFloat(document.getElementById("txt_costoMant").value.replace(/,/g,''));
			var costoTotal = manoObra + costoMtto;
			formatCurrency(costoTotal,"txt_costoTotal");
		}					
	</script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-registrar {position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
			#tabla-registrarBitacora {position:absolute;	left:30px;	top:190px;	width:794px;	height:430px;	z-index:12;	padding:15px;	padding-top:0px;}
			#calendario_mant {	position:absolute;	left:337px;	top:342px;	width:30px;	height:26px;	z-index:13;}						
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Registrar Bit&aacute;cora</div><?php	
	
	
	//Guardamos los datos de la sesion en variables para recuperar datos y moestrarlos
	if(isset($_SESSION['bitacoraPrev'])){
		$id_bitacora = $_SESSION['bitacoraPrev']['txt_claveBitacora'];
		$txt_turno = $_SESSION['bitacoraPrev']['txt_turno'];
		$txt_ot = $_SESSION['bitacoraPrev']['txt_ot'];
		$txt_costoMant = $_SESSION['bitacoraPrev']['txt_costoMant'];
		$txt_claveEquipo = $_SESSION['bitacoraPrev']['txt_claveEquipo'];
		$txt_costoManoObra = $_SESSION['bitacoraPrev']['txt_costoManoObra'];
		$txt_fechaMant = $_SESSION['bitacoraPrev']['txt_fechaMant'];
		$txt_costoTotal = $_SESSION['bitacoraPrev']['txt_costoTotal'];
		$txt_tipoMant = $_SESSION['bitacoraPrev']['txt_tipoMant'];
		$txt_noFactura = $_SESSION['bitacoraPrev']['txt_noFactura'];
		$txa_comentarios = $_SESSION['bitacoraPrev']['txa_comentarios'];
		$txt_horometro = $_SESSION['bitacoraPrev']['txt_horometro'];
		$txt_odometro = $_SESSION['bitacoraPrev']['txt_odometro'];
		$txt_tiempoTotal = $_SESSION['bitacoraPrev']['txt_tiempoTotal'];
		$txt_proxMant = $_SESSION['bitacoraPrev']['txt_proxMant'];
		$cmb_ordenExterna=$_SESSION['bitacoraPrev']['cmb_ordenExterna'];					
	}
	else{		
		//Definimos las variables como vacias para evirar problemas al no encontrarlas 
		$id_bitacora = obtenerDato("bd_mantenimiento", "bitacora_mtto", "id_bitacora", "orden_trabajo_id_orden_trabajo", $_POST["cmb_OT"]);
		$txt_turno = obtenerDato("bd_mantenimiento", "orden_trabajo", "turno", "id_orden_trabajo", $_POST["cmb_OT"]);
		$txt_ot = $_POST["cmb_OT"];
		$txt_costoMant = "";
		$txt_claveEquipo = obtenerDato("bd_mantenimiento", "bitacora_mtto", "equipos_id_equipo", "orden_trabajo_id_orden_trabajo", $_POST["cmb_OT"]);
		$txt_costoManoObra = "";
		$txt_fechaMant = date("d/m/Y");
		$txt_costoTotal = "";
		$txt_tipoMant = "";
		$txt_noFactura = "";
		$txa_comentarios = "";
		$txt_horometro = obtenerDato("bd_mantenimiento", "orden_trabajo", "horometro", "id_orden_trabajo", $_POST["cmb_OT"]);
		$txt_odometro = obtenerDato("bd_mantenimiento", "orden_trabajo", "odometro", "id_orden_trabajo", $_POST["cmb_OT"]);
		$txt_tiempoTotal = "";
		$txt_proxMant = date("d/m/Y");
		$cmb_ordenExterna="";
	}
	
	//Liberamos de la sesion el arreglo actividades cuando el usuario de click en el boton cancelar de la pagina de registrar actividades correctivas
	if (isset($_GET["cancelar"])){
		unset($_SESSION["actividades"]);
	}
	
	//Liberamos de la sesion el arreglo mecanicos cuando el usuario de click en el boton cancelar de la pagina de registrar mecanicos
	if (isset($_GET["cancel"])){
		unset($_SESSION["mecanicos"]);
	}?>			
	
	
	<fieldset class="borde_seccion" id="tabla-registrarBitacora" name="tabla-registrarBitacora">
	<legend class="titulo_etiqueta">Registrar Actividades del Mantenimiento Preventivo</legend>	
	<br>
	
	<?php //El atributo 'action' del formulario se define cuando se le da clic a los botones del mismo (Guardar, Complementar y Registrar Materiales) ?>
	<form onSubmit="return valFormRegistrar(this);" name="frm_registrarBitacora" method="post" action="">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  	<td><div align="right">Clave Bitacora</div></td>
  		  	<td>
		  		<input name="txt_claveBitacora" id="txt_claveBitacora" type="text" class="caja_de_texto" size="10" maxlength="10" 
            	value="<?php echo $id_bitacora; ?>" readonly="readonly" />            
			</td>
			<td align="right">Turno</td>
          	<td><input name="txt_turno" id="txt_turno" type="text" maxlength="40" class="caja_de_texto"  readonly="readonly" value="<?php echo $txt_turno;?>"/></td>
		</tr>
		<tr>
			<td><div align="right">Clave Orden de Trabajo</div></td>
			<td><input name="txt_ot" id="txt_ot" type="text" class="caja_de_texto"  readonly="readonly"  value="<?php echo $txt_ot;?>"/></td>	
			<td><div align="right">Costo Material</div></td>
			<td>$<?php //Obtener el costo total de los materiales registrados en la SESSION
				$importe = 0.00;
				if(isset($_SESSION['valesMtto'])){
					foreach($_SESSION["valesMtto"] as $key=> $material){
						foreach ($material as $key => $value) {
							switch($key){
								case "total":
									if(strlen($value>6))
										$importe+=str_replace(",","",$value);
									else
										$importe+=$value;
								break;
							}//Cierre Switch
						}				
					}
				}//Cierre if(isset($_SESSION['valesMtto']))?> 
          		<input name="txt_costoMant" id="txt_costoMant" type="text" class="caja_de_texto"  readonly="readonly" value="<?php echo number_format($importe,2,".",",");?>"/>			
			</td>
		</tr>
	  	<tr>
			<td><div align="right">Clave del Equipo</div></td>
			<td>
				<input name="txt_claveEquipo" id="txt_claveEquipo" type="text" class="caja_de_texto" size="15" maxlength="15" 
            	value="<?php echo $txt_claveEquipo;?>"  readonly="readonly" />            
			</td>
   	   		<td><div align="right">*Costo Mano de Obra</div></td>
			<td>$
			  	<input name="txt_costoManoObra" id="txt_costoManoObra" type="text" class="caja_de_texto" 
            	onchange="formatCurrency(value,'txt_costoManoObra'); calcularCostoTotal();" size="10" maxlength="15" onkeypress="return permite(event,'num',2);" 
				value="<?php echo number_format($txt_costoManoObra,2,".",",");?>"/>			
			</td>    
		</tr>
		<tr>
			<td><div align="right">Fecha Mantenimiento</div></td>
			<td>
				<input name="txt_fechaMant" type="text" id="txt_fechaMant" size="10" maxlength="15" value="<?php echo $txt_fechaMant;?>" readonly="readonly" 
            	onchange="valFormFecha('txt_fechaMant');" />			
			</td>
            <td><div align="right">Costo Total</div></td>
			<td>$<?php 
				if(isset($_SESSION["bitacoraPrev"])){?>
					<input name="txt_costoTotal" id="txt_costoTotal" type="text" class="caja_de_texto" size="15" maxlength="13" 
					value="<?php echo number_format($txt_costoTotal,2,".",",");?>" readonly="readonly" /><?php 
				}
				else{?>
				 	<input name="txt_costoTotal" id="txt_costoTotal" type="text" class="caja_de_texto"  readonly="readonly" 
                    value="<?php echo number_format($txt_costoTotal,2,".",",");?>"/><?php 
				}?>			
			</td>
	  	</tr>
        <tr>
       		<td><div align="right">Tipo Mantenimiento</div></td>
          	<td><input name="txt_tipoMant" id="txt_tipoMant" type="text" class="caja_de_texto" size="15" maxlength="13" value="PREVENTIVO" readonly="readonly"/></td>
       	  	<td><div align="right">No. Factura </div></td>
			<td>
 		  		<input name="txt_noFactura" id="txt_noFactura" type="text" class="caja_de_texto" size="10" maxlength="10" 
                value="<?php echo $txt_noFactura;?>"	onkeypress="return permite(event,'num_car', 3);"/>			
			</td>		
		</tr>
        <tr>
			<td><div align="right">Hor&oacute;metro</div></td>
          	<td><?php 
				if($txt_horometro==0){?>
          			<input name="txt_horometro" id="txt_horometro" type="text" class="caja_de_texto" size="15" maxlength="15" value="NO APLICA"	readonly="readonly"/><?php 
				} 
				else{?>
			  		<input name="txt_horometro" id="txt_horometro2" type="text" class="caja_de_texto" size="15" maxlength="15" 
					value="<?php echo number_format($txt_horometro,2,".",",");?>" onkeypress="return permite(event,'num',2);" 
					onchange="formatCurrency(value,'txt_horometro')" /><?php 
				}?>            
			</td>
          	<td><div align="right">Comentarios</div></td>
   	  	  	<td colspan="2">
				<textarea name="txa_comentarios" id="txa_comentarios" maxlength="200" onkeyup="return ismaxlength(this)" 
            	class="caja_de_texto" rows="2" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $txa_comentarios;?></textarea>			
			</td>
        </tr>
        <tr>
        	<td><div align="right">Od&oacute;metro</div></td>
            <td><?php 
				if($txt_odometro!=0){?>
       	  	 		<input name="txt_odometro" id="txt_odometro" type="text" class="caja_de_texto" size="10" maxlength="13" 
					value="<?php echo number_format($txt_odometro,2,".",",");?>" onkeypress="return permite(event,'num',2);" onchange="formatCurrency(value,'txt_odometro')" /><?php
				} 
				else{?>          			
					<input name="txt_odometro" id="txt_odometro" type="text" class="caja_de_texto" size="10" maxlength="13" value="NO APLICA" 
                	readonly="readonly"/> <?php 
				}?>			
			</td>
			<td valign="middle"><div align="right">Cargar Registro Fotogr&aacute;fico </div></td>
			<td valign="top"><?php 
				if(isset($_SESSION["bitacoraPrev"])){?>
					<input name="btn_addFoto" type="button" class=" botones_largos"  value="Registro Fotogr&aacute;fico" 
					title="Registro Fotogr&aacute;fico de Mantenimiento"  onclick="location.href='frm_agregarFotoEquipo.php'"/><?php 
				}
				else{?>
					<input name="btn_addFoto" type="button" class=" botones_largos"  value="Registro Fotogr&aacute;fico" disabled="disabled" 
					title="Es necesario Complementar la Bit&aacute;cora"  onclick="location.href='frm_agregarFotoEquipo.php'"/><?php 
				}?>						
			</td>
      	</tr>
       	<tr>
			<td><div align="right">Fecha Aprox Pr&oacute;ximo Mtto.</div></td>
		 	<td><input name="txt_proxMant" id="txt_proxMant" type="text"  size="10" maxlength="15" value="<?php echo $txt_proxMant;?>" readonly="readonly"/></td>
		    <td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
  	  	</tr>
	 	<tr>
			<td><div align="right">*Tiempo Total </div></td>
			<td>
				<input name="txt_tiempoTotal" id="txt_tiempoTotal" type="text" class="caja_de_texto" size="15" maxlength="5" onblur="validarCantHoras(this);"
            	value="<?php echo $txt_tiempoTotal;?>" onkeypress="return permite(event,'num', 3);"/> Hrs:Min          	
			</td>
      		<td><div align="right">&Oacute;rden Externa Asociada</div></td>
			<td>
				<?php
					$conn = conecta("bd_mantenimiento");
					$result=mysql_query("SELECT id_orden FROM orden_servicios_externos WHERE depto='MttoConcreto'");?>
					<select name="cmb_ordenExterna" id="cmb_ordenExterna" size="1" class="combo_box">
						<option value="">&Oacute;rden Trabajo Externa</option><?php
							while ($row=mysql_fetch_array($result)){
								if ($row['id_orden'] == $cmb_ordenExterna){
									echo "<option value='$row[id_orden]' selected='selected'>$row[id_orden]</option>";
								}
								else{
									echo "<option value='$row[id_orden]'>$row[id_orden]</option>";
								}
							} 
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
					</select>
					<img src="../../images/lupa.png" width="25" height="25" title="Consultar &Oacute;rden de Servicio Externa Seleccionada" style="cursor:pointer" onclick="consultarOTEBitacora(cmb_ordenExterna.value);"/>
			</td>
 	  	</tr>
	  	<tr>
       	  	<td colspan="4" align="center">
		        <input type="hidden" name="hdn_tipoMtto" value="PREVENTIVO"/><?php 
				
				if(isset($_SESSION["mecanicos"]) && (isset($_SESSION["valesMtto"]) || isset($_SESSION['regSinValeMtto']))){?>
					<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Datos de la Bit&aacute;cora"
					onMouseOver="window.status='';return true" onclick="document.frm_registrarBitacora.action='op_registrarBitacora.php'" /><?php 
				}
				else{?>	
					<input name="btn_guardar" type="button" class="botones"  value="Guardar" title="Es necesario Registrar los Mec&aacute;nicos y/o Material"
					onmouseover="window.status='';return true" disabled="disabled"/><?php 
				}?>	
				&nbsp;&nbsp;&nbsp;
				<input name="sbt_complementar" type="submit" class=" botones"  value="Complementar " title="Complementar Bit&aacute;cora" 
				onclick="document.frm_registrarBitacora.action='frm_complementarBitacora.php';" onMouseOver="window.status='';return true" /><?php 
				if(isset($_SESSION["mecanicos"])){?>				
                	&nbsp;&nbsp;&nbsp;
       	     		<input name="sbt_regMatMant" type="submit" class=" botones_largos"  value="Registrar Material" title="Registrar Materiales de Mantenimiento" 
                	onclick="document.frm_registrarBitacora.action='frm_regMatMtto.php';" onMouseOver="window.status='';return true"/><?php 
				}?>
                &nbsp;&nbsp;&nbsp;
                <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true" /> 
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Bit&aacute;cora" 
                onmouseover="window.status='';return true" 
				onclick="confirmarSalida('frm_registrarBitacora.php?cmb_tipoMtto=preventivo&id_bit=<?php echo $id_bitacora;?>&cancelar');" />
			</td>
		</tr>
	</table>		
	</form>
	</fieldset>

    <div id="calendario_mant">
 	  	<input name="calendario_mantenimiento" type="image" id="calendario_mantenimiento" 
		onclick="displayCalendar (document.frm_registrarBitacora.txt_fechaMant,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
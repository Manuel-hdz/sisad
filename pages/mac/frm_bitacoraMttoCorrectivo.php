<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
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
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquipoMtto.js"></script>
   	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>	
	<script type="text/javascript" language="javascript">
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
			#tabla-registrarBitacora {position:absolute;	left:30px;	top:190px;	width:794px;	height:444px;	z-index:12;	padding:15px;	padding-top:0px;}
			#calendario_mant {	position:absolute;	left:300px;	top:413px;	width:30px;	height:26px;	z-index:13;}				
		-->
    </style>
</head>
<body><?php
	
	//Declaramos las variables con valores por defecto para cuando se entre por primera vez a esta página
	$id_bitacora = obtenerIdRegBitacora(); $cmb_area = ""; $cmb_familia = ""; $cmb_claveEquipo = ""; $cmb_turno = ""; $txt_claveOrdenTrabajo = "";
	$txt_costoMant = ""; $txt_costoTotal = ""; $cmb_claveEquipo = ""; $txt_costoManoObra = ""; $txt_fechaMant = date("d/m/Y"); $txt_costoTotal = "";
	$txt_tipoMant = "";	$txt_noFactura = ""; $txa_comentarios = ""; $txt_metrica = ""; $txt_cantMet = ""; $txt_tiempoTotal = "";
	
	
	//Verificar si los datos de la Bitácora se encuentran en la SESSION, para recuperar los datos despues de complementar los datos y registrar los materiales
	if(isset($_SESSION['bitacoraCorr'])){
		$id_bitacora = $_SESSION['bitacoraCorr']['txt_claveBitacora'];
		$cmb_area = $_SESSION['bitacoraCorr']['cmb_area'];
		$cmb_familia = $_SESSION['bitacoraCorr']['cmb_familia'];
		$cmb_claveEquipo = $_SESSION['bitacoraCorr']['cmb_claveEquipo'];
		$cmb_turno = $_SESSION['bitacoraCorr']['cmb_turno'];
		$txt_claveOrdenTrabajo = $_SESSION['bitacoraCorr']['txt_claveOrdenTrabajo'];
		$txt_costoMant = $_SESSION['bitacoraCorr']['txt_costoMant'];
		$txt_costoTotal = $_SESSION['bitacoraCorr']['txt_costoTotal'];
		$cmb_claveEquipo = $_SESSION['bitacoraCorr']['cmb_claveEquipo'];
		$txt_costoManoObra = $_SESSION['bitacoraCorr']['txt_costoManoObra'];
		$txt_fechaMant = $_SESSION['bitacoraCorr']['txt_fechaMant'];
		$txt_costoTotal = $_SESSION['bitacoraCorr']['txt_costoTotal'];
		$txt_tipoMant = $_SESSION['bitacoraCorr']['txt_tipoMant'];
		$txt_noFactura = $_SESSION['bitacoraCorr']['txt_noFactura'];
		$txa_comentarios = $_SESSION['bitacoraCorr']['txa_comentarios'];
		$txt_metrica = $_SESSION['bitacoraCorr']['txt_metrica'];
		$txt_cantMet = $_SESSION['bitacoraCorr']['txt_cantMet'];
		$txt_tiempoTotal = $_SESSION['bitacoraCorr']['txt_tiempoTotal'];		
	}	
	
	
	//Liberamos de la sesion el arreglo actividades cuando el usuario de click en el boton cancelar de la pagina de registrar actividades correctivas
	if(isset($_GET["cancelar"])){
		unset($_SESSION["actividades"]);
	}
	
	//Liberamos de la sesion el arreglo mecanicos cuando el usuario de click en el boton cancelar de la pagina de registrar mecanicos
	if(isset($_GET["cancel"])){
		unset($_SESSION["mecanicos"]);
	}

	//Codigo que nos permite recuperar los valores preseleccionados en cada uno de los combos; esta en esta ubicacion porque en caso de no existir el arreglo de 
	//SESSION toma como vacios los combos en la declaracion anterior?>
	<script type="text/javascript" language="javascript">
		window.onload = function(){<?php 
			if(!isset($_SESSION['valesMtto'])){
				if($cmb_area!=""){ ?>
					cargarCombo("<?php echo $cmb_area; ?>","bd_mantenimiento","equipos","familia","area","cmb_familia","Familia","<?php echo $cmb_familia; ?>");<?php 
				}
				if($cmb_familia!=""){?>
					setTimeout("cargarCombo('<?php echo $cmb_familia; ?>','bd_mantenimiento','equipos','id_equipo','familia','cmb_claveEquipo','Clave Equipo','<?php echo $cmb_claveEquipo; ?>');",500);<?php 
				} 
			}?>
		}
	</script>		
		
		
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Registrar Bit&aacute;cora</div><?php 

	
	/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
	$atributo = "";
	$area = "";
	$estado = 1;//El estado 1 indica que el departamento registrados en la SESSION tiene acceso a la información de MINA y CONCRETO
	if($_SESSION['depto']=="MttoConcreto"){
		$area = "CONCRETO";
		$atributo = "disabled='disabled'";
		$estado = 0;
	}
	else if($_SESSION['depto']=="MttoMina"){
		$area = "MINA";
		$atributo = "disabled='disabled'";
		$estado = 0;
	}
	
	if($estado==0){ 
		if(!isset($_SESSION['valesMtto'])){?>		
			<script type="text/javascript" language="javascript">
				cargarCombo('<?php echo $area;?>','bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');
			</script><?php 
		}
	}?>
	
	
	<fieldset class="borde_seccion" id="tabla-registrarBitacora" name="tabla-registrarBitacora">
	<legend class="titulo_etiqueta">Registrar Actividades del Mantenimiento Correctivo</legend>	
	<br>
	
	<?php //El atributo 'action' del formulario se define cuando se le da clic a los botones del mismo (Guardar, Complementar y Registrar Materiales) ?>
	<form onSubmit="return valFormBitacoraMttoCorr(this);" name="frm_registrarBitacora" method="post" action="">
	<table width="795" height="363" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="141"><div align="right">Clave Bit&aacute;cora </div></td>
			<td width="192">
				<input name="txt_claveBitacora" id="txt_claveBitacora" type="text" class="caja_de_texto" size="15" maxlength="13" 
            	value="<?php echo $id_bitacora; ?>" readonly="readonly" />
			</td>
		  	<td width="163" align="right">Turno</td>
  	  		<td width="232">
				<select name="cmb_turno" id="cmb_turno">
                    <option selected="selected" value="">Turno</option>
                    <option <?php if($cmb_turno=='TURNO DE PRIMERA') echo "selected='selected'"?> value="TURNO DE PRIMERA">TURNO DE PRIMERA</option>
                    <option <?php if($cmb_turno=='TURNO DE SEGUNDA') echo "selected='selected'"?> value="TURNO DE SEGUNDA">TURNO DE SEGUNDA</option>
                    <option <?php if($cmb_turno=='TURNO DE TERCERA') echo "selected='selected'"?> value="TURNO DE TERCERA">TURNO DE TERCERA</option>
				</select>			
			</td>
	  	</tr>
		<tr>
			<td><div align="right">Clave Orden de Trabajo </div></td>
			<td width="192">
				<input name="txt_claveOrdenTrabajo" id="txt_claveOrdenTrabajo" type="text" class="caja_de_texto" size="15" 
            	maxlength="13" readonly="readonly" value="NO APLICA"/>			
			</td>
		  	<td><div align="right">Costo Material </div></td>
			<td width="232">$<?php 
				//Comprobamos que venga definido en la session txt_costoMant y que se haya entrado a registrar material
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
							}
						}				
					}
				}?> 
            	<input name="txt_costoMant" id="txt_costoMant" type="text" class="caja_de_texto" readonly="readonly" value="<?php echo number_format($importe,2,".",",");?>"/>			
			</td>
        </tr>
	  	<tr>
			<td align="right"><div align="right">&Aacute;rea</div></td>
          	<td><?php 
				 if($estado==1){//Mostrar el comboBox cuando el usuario registrado es AuxMtto
				 	if(isset($_SESSION["valesMtto"])){?>
				 		<input type="text" name="cmb_area" id="cmb_area" size="20" readonly="readonly" value="<?php echo $cmb_area; ?>" /><?php 
					} 
					else { ?>
						<!--<select name="cmb_area" class="combo_box" 
							onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');">
							<option value="">&Aacute;rea</option>						
							<option value="CONCRETO" <?php if($cmb_area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
							<option value="MINA" <?php if($cmb_area=="MINA") echo "selected='selected'"; ?>>MINA</option>
						</select>-->
						<input type='text' name="cmb_area" id="cmb_area" class="caja_de_texto" value="<?php echo $cm_area; ?>" readonly="readonly"/><?php 
					}//Cierre Else del if(isset($_SESSION["valesMtto"]))
				} 
				else {//Mostrar el comboBox cuando el usuario registrado es AdminMttoMina y AdminMttoSuperficie ?>
               		<!--<select name="cmb_area" class="combo_box" 
					onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');" <?php echo $atributo; ?>>
                		<option value="">&Aacute;rea</option>						
						<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
						<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
					</select>-->		
					<input type="text" name="cmb_area" id="cmb_area" value="<?php echo $area; ?>" readonly="readonly"/><?php 
				}?>          
			</td>
          	<td><div align="right">*Costo Mano de Obra</div></td>
		  	<td>$
			  	<input name="txt_costoManoObra" id="txt_costoManoObra" type="text" class="caja_de_texto" onchange="formatCurrency(value,'txt_costoManoObra'); calcularCostoTotal();"
				size="10" maxlength="15" onkeypress="return permite(event,'num',2);" value="<?php echo number_format($txt_costoManoObra,2,".",","); ?>" />
			</td>      	 
		</tr>
		<tr>
        	<td align="right"><div align="right">Familia</div></td>
          	<td><?php
				if(isset($_SESSION["valesMtto"])){ ?>
					<input type="text" name="cmb_familia" id="cmb_familia" size="20" readonly="readonly" value="<?php echo $cmb_familia; ?>" /><?php
				}
				else {?>				
					<select name="cmb_familia" id="cmb_familia" 
						onchange="cargarEquiposFamilia(this.value,'<?php echo $area; ?>','cmb_claveEquipo','Clave Equipo','');"
						onblur="obtenerDatoBD(this.value,'bd_mantenimiento','equipos','metrica','familia','txt_metrica');">
						<option value="">Familia</option>
					</select><?php
				} ?>
			</td>
            <td><div align="right">Costo Total</div></td>
			<td>$<?php 
				if(isset($_SESSION["bitacoraCorr"])){?>
					<input name="txt_costoTotal" id="txt_costoTotal" type="text" class="caja_de_texto" size="15" maxlength="13" 
					value="<?php echo number_format($txt_costoTotal,2,".",","); ?>" readonly="readonly" /><?php 
				}
				else{ ?>
					<input name="txt_costoTotal" id="txt_costoTotal" type="text" class="caja_de_texto"  readonly="readonly" 
                    value="<?php echo number_format($txt_costoTotal,2,".",",");?>"/><?php 
				}?>
			</td>	    
		</tr>
        <tr>
        	<td><div align="right">Clave del Equipo </div></td>
          	<td><?php
				if(isset($_SESSION["valesMtto"])){ ?>
					<input type="text" name="cmb_claveEquipo" id="cmb_claveEquipo" size="20" readonly="readonly" value="<?php echo $cmb_claveEquipo; ?>" /><?php
				}
				else {?>
					<select name="cmb_claveEquipo" id="cmb_claveEquipo" >
						<option value="">Clave Equipo</option>
					</select><?php
				} ?>				
			</td>
            <td><div align="right">Tipo Mantenimiento</div></td>
			<td width="232"><input name="txt_tipoMant" id="txt_tipoMant" type="text" class="caja_de_texto" size="15" maxlength="13"value="CORRECTIVO" readonly="readonly"/></td>		
	  	</tr>
        <tr>
       		<td><div align="right">Fecha Mantenimiento</div></td>
			<td><input name="txt_fechaMant" type="text" id="txt_fechaMant" size="10" maxlength="15" value="<?php echo $txt_fechaMant; ?>" readonly="readonly" /></td>
            <td><div align="right">No. Factura </div></td>
			<td width="232">
				<input name="txt_noFactura" id="txt_noFactura" type="text" class="caja_de_texto" size="10" maxlength="10" 
            	value="<?php echo $txt_noFactura;?>" onkeypress="return permite(event,'num_car', 3);"/>
			</td>	
		</tr>
		<tr>
			<td><div align="right">M&eacute;trica</div></td>
			<td width="192">
				<input name="txt_metrica" id="txt_metrica" type="text" class="caja_de_texto" size="15" maxlength="13" 
            	value="<?php echo $txt_metrica;?>" onkeypress="return permite(event,'num',2);" readonly="readonly"/>
			</td>
            <td><div align="right">Comentarios</div></td>
            <td>
				<textarea name="txa_comentarios" id="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
            	onkeypress="return permite(event,'num_car', 0);"><?php echo $txa_comentarios;?></textarea>
			</td>
		</tr>
        <tr>
			<td><div align="right">Cantidad de M&eacute;trica</div></td>
			<td width="192">
				<input name="txt_cantMet" id="txt_cantMet" type="text" class="caja_de_texto" size="15" maxlength="13" 
            	value="<?php echo number_format($txt_cantMet,2,".",","); ?>" onkeypress="return permite(event,'num',2);" onchange="formatCurrency(value,'txt_cantMet')" />
			</td>
			<td  valign="middle"><div align="right">Cargar Registro Fotogr&aacute;fico </div></td>
			<td valign="top"><?php 
				if(isset($_SESSION["bitacoraCorr"])){?>
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
			<td><div align="right">*Tiempo Total </div></td>
		 	<td width="192">
				<input name="txt_tiempoTotal" id="txt_tiempoTotal" type="text" class="caja_de_texto" size="15" maxlength="5" onblur="validarCantHoras(this);"
            	value="<?php echo $txt_tiempoTotal;?>" onkeypress="return permite(event,'num', 3);"/> Hrs:Min							
			</td>
			<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  	</tr>
		<tr>
       	  	<td colspan="4"><div align="center">
              	<input type="hidden" name="hdn_tipoMtto" value="CORRECTIVO"/><?php 
				
				if(isset($_SESSION["mecanicos"]) && (isset($_SESSION["valesMtto"]) || isset($_SESSION['regSinValeMtto'])) && isset($_SESSION["actividades"])){?>
                	<input name="sbt_guardarCorr" type="submit" class="botones"  value="Guardar" title="Guardar Datos de la Bitacora"
                	onmouseover="window.status='';return true" onclick="document.frm_registrarBitacora.action='op_registrarBitacora.php'" /><?php 
				}
				else{?>	
                	<input name="sbt_guardarCorr" type="submit" class="botones"  value="Guardar" title="Es necesario Registrar los Mec&aacute;nicos, Material y/o Actividades"
                	onmouseover="window.status='';return true" disabled="disabled" /><?php 
				}?>	
       	  	  	&nbsp;&nbsp;&nbsp;
       	  	  	<input name="sbt_complementar" type="submit" class=" botones"  value="Complementar " title="Complementar Bitacora" 
                onclick="document.frm_registrarBitacora.action='frm_complementarBitacora.php';" onmouseover="window.status='';return true"/><?php 
				if(isset($_SESSION["mecanicos"])){?>
                	&nbsp;&nbsp;&nbsp;
   	     	 		<input name="sbt_regMatMant" type="submit" class=" botones_largos"  value="Registrar Material" title="Registrar Materiales de Mantenimiento" 
                	onclick="document.frm_registrarBitacora.action='frm_regMatMtto.php';" /><?php 
				}?>				
                &nbsp;&nbsp;&nbsp;
                <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/> 
				&nbsp;&nbsp;&nbsp;
                <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Bitacora" 
                onMouseOver="window.status='';return true" onclick="confirmarSalida('frm_registrarBitacora.php?id_bit=<?php echo $id_bitacora;?>&cancelar');" />
				</div>			
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
	
    <div id="calendario_mant">
  	  	<input name="calendario_mantenimiento" type="image" id="calendario_mantenimiento" onclick="displayCalendar(document.frm_registrarBitacora.txt_fechaMant,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
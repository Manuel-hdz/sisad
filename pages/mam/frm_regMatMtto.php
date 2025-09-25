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
		
		include ("op_registrarBitacora.php");
		
	//Agregamos los datos del post Bitacora Mtto Preventivo al arreglo de SESSION(el campo txt_ot es exclusivo del formulario de registro de la Bitácora de Mtto Preventivo)
	if(isset($_POST["txt_ot"])){
		//Guardar los datos de la Bitácora de Mtto. Preventivo en el arreglo
		$_SESSION['bitacoraPrev'] = array("txt_claveBitacora"=>$txt_claveBitacora, "txt_turno"=>$txt_turno, "txt_ot"=>$txt_ot, 
		"txt_costoMant"=>str_replace(",","",$txt_costoMant), "txt_claveEquipo"=>$txt_claveEquipo, "txt_costoManoObra"=>str_replace(",","",$txt_costoManoObra), 
		"txt_fechaMant"=>$txt_fechaMant, "txt_costoTotal"=>str_replace(",","",$txt_costoTotal), "txt_tipoMant"=>$txt_tipoMant, "txt_noFactura"=>strtoupper($txt_noFactura),
		"txa_comentarios"=>strtoupper($txa_comentarios), "txt_horometro"=>str_replace(",","",$txt_horometro), "txt_odometro"=>str_replace(",","",$txt_odometro),
		"txt_tiempoTotal"=>$txt_tiempoTotal, "txt_proxMant"=>$txt_proxMant, "cmb_ordenExterna"=>$cmb_ordenExterna);				
	}	
	
	//Agregamos los datos del post Bitacora Mtto Correctivo al arreglo de SESSION(el campo cmb_area es exclusivo del formulario de registro de la Bitácora de Mtto Correctivo)
	if(isset($_POST["txt_claveOrdenTrabajo"]) && isset($_POST["cmb_area"])){
		//Guardar los datos de la bitácora de Mtto. Correctivo en el arreglo de SESSION
		$_SESSION['bitacoraCorr'] = array("txt_claveBitacora"=>$txt_claveBitacora, "cmb_familia"=>$cmb_familia, "cmb_area"=>$cmb_area, "cmb_claveEquipo"=>$cmb_claveEquipo, 
		"cmb_turno"=>$cmb_turno, "txt_claveOrdenTrabajo"=>$txt_claveOrdenTrabajo, "txt_costoMant"=>str_replace(",","",$txt_costoMant), 
		"txt_costoTotal"=>str_replace(",","",$txt_costoTotal), "txt_costoManoObra"=>str_replace(",","",$txt_costoManoObra), "txt_fechaMant"=>$txt_fechaMant, 
		"txt_tipoMant"=>$txt_tipoMant, "txt_noFactura"=>strtoupper($txt_noFactura), "txa_comentarios"=>strtoupper($txa_comentarios), "txt_metrica"=>$txt_metrica, 
		"txt_cantMet"=>str_replace(",","",$txt_cantMet), "txt_tiempoTotal"=>$txt_tiempoTotal);		
	}?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
	
	<script type="text/javascript">
		function activarMaterialMtto(check,cajaTexto){
			if (check.checked){
				cajaTexto.readOnly=true;
				cajaTexto.value="N/A";
			}
			else{
				cajaTexto.readOnly=false;
				cajaTexto.value="";
			}
		}
	</script>
   
    <style type="text/css">
		<!--		
			#titulo-detalle {position:absolute;	left:30px; top:146px; width:329px; height:23px; z-index:10; }
			#tabla-registrar-MatMtto {position:absolute; left:30px; top:190px; width:360px; height:200px; z-index:11; }
			#tabla-materiales{position:absolute; left:464px; top:190px; width:500px; height:200px; z-index:14; overflow:scroll; }
			#tabla-botones-Materiales{	position:absolute;	left:15px;	top:492px;	width:900px;	height:53px;	z-index:16;}				
		-->
    </style>
</head>
<body><?php 
	if(isset($_GET["noRegistro"])){
		unset($_SESSION["valesMtto"][$_GET["noRegistro"]]);
		$_SESSION['valesMtto'] = array_values($_SESSION['valesMtto']);//Rectificar los indices
		
		//Si el arreglo de valesMtto esta vacio, retirarlo de la SESSION
		if(count($_SESSION["valesMtto"])==0)
			unset($_SESSION["valesMtto"]);
	}?>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-detalle">Materiales Utilizados en el Matenimiento</div>
	
	
    <fieldset class="borde_seccion" id="tabla-registrar-MatMtto" name="tabla-registrar-MatMtto">
	<legend class="titulo_etiqueta"> Materiales Utilizados en el Matenimiento</legend><br /><?php 
	
	//Obtener el Id del equipo, segun el tipo de Bitácora que será registrada(Preventiva o Correctiva)				
	if(isset($_SESSION['bitacoraCorr']))
		$idEquipo = $_SESSION['bitacoraCorr']['cmb_claveEquipo'];
	else if(isset($_SESSION['bitacoraPrev']))
		$idEquipo = $_SESSION['bitacoraPrev']['txt_claveEquipo'];?>
		
		
    <form name="frm_materiales" onsubmit="return valFormRegMatMtto(this);" method="post" action="frm_regMatMtto.php" >
	<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  	<td width="21%"><div align="right">Id del Equipo</div></td>
   		  	<td width="25%" class="titulo_etiqueta"><?php echo $idEquipo; ?></td>
		</tr>
		<tr>
		  	<td><div align="right">Clave del Vale</div></td>
   		  	<td><?php 
				if(isset($_SESSION['regSinValeMtto'])){?>			
					<input name="txt_claveVale" id="txt_claveVale" type="text" class="caja_de_texto" size="15" maxlength="10" value="N/A" readonly="readonly" /><?php
				}
				else{?>
					<input name="txt_claveVale" id="txt_claveVale" type="text" class="caja_de_texto" value=""  size="15" maxlength="10" 
                	onkeypress="return permite(event,'num_car', 1);" onblur="return verificarIdVale(this,'<?php echo $idEquipo; ?>');" /><?php
				}?>
			</td>
  	  	</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="checkbox" name="ckb_noMaterial" id="ckb_noMaterial" onclick="activarMaterialMtto(this,txt_claveVale);"
				<?php if(isset($_SESSION['regSinValeMtto'])){ echo "checked='checked'"; }?> />Seleccionar Si No Se Necesita Material
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"><span id="error" class="msj_error">Vale Ya Registrado en Este Equipo</span></td>
		</tr>
      	<tr>
      		<td colspan="2" align="center">
        		<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
				<input name="sbt_registrar" type="submit" class="botones" id="btn_registrar" value="Registrar"
            	onmouseover="window.status='';return true;" title="Registrar Material Mantenimiento"/><?php 
				if(isset($_SESSION["bitacoraPrev"])){?>
					<input name="btn_finalizar" type="button" class="botones" id="btn_finalizar" value="Finalizar" onmouseover="window.status='';return true;" 
					title="Finalizar Registro de Materiales Mantenimiento" onclick="location.href='frm_bitacoraMttoPreventivo.php'"/><?php 
				}
				else{?>
        			<input name="btn_finalizar" type="button" class="botones" id="btn_finalizar" value="Finalizar" onmouseover="window.status='';return true;" 
					title="Finalizar Registro de Materiales Mantenimiento" onclick="location.href='frm_bitacoraMttoCorrectivo.php'"/><?php 
				}?> 
			</td>
      	</tr>
   	</table> 
	</form>  
</fieldset><?php

   	$claveVale = "";
	
   	if(isset($_POST["txt_claveVale"]))  
		$claveVale = $_POST["txt_claveVale"];
	
	if(isset($_GET["vale"])){
		$claveVale = $_GET["vale"];
	}
	
	if(isset($_POST["sbt_registrar"])){
	   	cargarMateriales($claveVale);
	}
	
	if(isset($_SESSION["valesMtto"])){?>
	    <div id='tabla-materiales' align="center" class="borde_seccion2"><?php 
			mostrarVales($claveVale);?>
		</div><?php   
	}?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
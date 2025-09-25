<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarPruebas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/validarEdad.js"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;width:357px;height:20px;z-index:11;}
		#tabla-agregarPrueba {position:absolute;left:30px;top:190px;width:866px;height:300px;z-index:14;}
		#calendario-FechaProg {position:absolute;left:635px;top:231px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body><?php

	//Cuando se provenga de la Pagina de Seleccionar Muestra, guardar el Id de la Muestra seleccionada en la SESSION
	if(isset($_POST['sbt_continuar']))
		$_SESSION['idMuestraSel']=$_POST['rdb_idMuestra'];
	
	//Cuando se llegue a esta pagina desde una Alerta, guardar el Id de la Muestra seleccionada en la SESSION	
	if(isset($_SESSION['datosPruebaAlerta']['idMuestra']))
		$_SESSION['idMuestraSel'] = $_SESSION['datosPruebaAlerta']['idMuestra'];
	
	
	if(isset($_POST['sbt_guardar'])){		
		if(!isset($_SESSION['idCarpeta'])){
			//Verificar si ya hay un registro existente de la prueba
			$result = obtenerDato("bd_laboratorio","prueba_calidad","muestras_id_muestra","muestras_id_muestra",$_SESSION['idMuestraSel']);
			if($result==""){
				$_SESSION['idCarpeta'] = obtenerIdPruebaCalidad();
			}
			else{
				$_SESSION['idCarpeta'] = obtenerDato("bd_laboratorio","prueba_calidad","id_prueba_calidad","muestras_id_muestra",$_SESSION['idMuestraSel']);
			}
		}
		
		if (isset($_SESSION['resPruebas'])){
			//Crear el arreglo con los datos 
			$resPruebas[] = array("idMuestra"=>$_POST['txt_idMuestra'], "fc"=>$_POST['txt_fc'], "cargaRuptura"=>$_POST['txt_cargaRuptura'], 
			"porcentaje"=>$_POST['txt_porcentaje'], "fechaRuptura"=>$_POST['txt_fechaRuptura'], "edad"=>$_POST['txt_edad'], "kgCm"=>$_POST['txt_kgcm'],
			"observaciones"=> strtoupper($_POST['txa_observaciones']), "diametro"=>$_POST['txt_diametro'], "area"=>$_POST['txt_area']);
		}	 
		else{
			//Crear el arreglo con los datos 
			$resPruebas = array(array("idMuestra"=>$_POST['txt_idMuestra'], "fc"=>$_POST['txt_fc'], "cargaRuptura"=>$_POST['txt_cargaRuptura'], 
			"porcentaje"=>$_POST['txt_porcentaje'], "fechaRuptura"=>$_POST['txt_fechaRuptura'], "edad"=>$_POST['txt_edad'], "kgCm"=>$_POST['txt_kgcm'], 
			"observaciones"=>strtoupper($_POST['txa_observaciones']), "diametro"=>$_POST['txt_diametro'], "area"=>$_POST['txt_area']));
			//Guardar los datos en la SESSION
			$_SESSION['resPruebas'] = $resPruebas;
		}
		
		if (isset($_SESSION['resPruebas'])){?>
			<script type="text/javascript" language="javascript">
				setTimeout("window.open('mostrarDatos.php?mostrar', '_blank','top=100, left=100, width=1222, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no')",0);  
			</script><?php
		}
		guardarRegPruebas();
	}//FIN if(isset($_POST['sbt_guardar'])
	
	
	//Aplicar la funcion de obtener dato para obtener la f'c  el diametro y el área pero pimero debemos saber el id de la prueba de calidad
	//$idPbaCalidad = obtenerDato("bd_laboratorio","prueba_calidad","id_prueba_calidad","muestras_id_muestra",$_SESSION['idMuestraSel']);
	//$fc = obtenerDato("bd_laboratorio","detalle_prueba_calidad","fprima_c","prueba_calidad_id_prueba_calidad",$idPbaCalidad);	
	/*Preguntar si el valor de F'c se obtiene de la muestra o de los registros previos de rupturas*/
	
	
	$fc = obtenerDato("bd_laboratorio","muestras","fprimac_proyecto","id_muestra",$_SESSION['idMuestraSel']);	
	$fechaColado = obtenerDato("bd_laboratorio","muestras","fecha_colado","id_muestra",$_SESSION['idMuestraSel']);
	$diametro = obtenerDato("bd_laboratorio","muestras","diametro","id_muestra",$_SESSION['idMuestraSel']);	
	$area = obtenerDato("bd_laboratorio","muestras","area","id_muestra",$_SESSION['idMuestraSel']);?> 
	
	
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Registrar Resultados de Pruebas a Mezclas</div>
    <fieldset class="borde_seccion" id="tabla-agregarPrueba" name="tabla-agregarPrueba">
	<legend class="titulo_etiqueta">Ingrese el Resultado de la Prueba</legend>	
	<br>
	<form onSubmit="return valFormAgregarPruebaLab(this);" name="frm_registrarPruebasMuestras2" method="post" action="frm_registrarPruebasMuestras2.php" 
    enctype="multipart/form-data">
    <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="15%"><div align="right">Id Muestra </div></td>
            <td width="25%">
				<input type="text" name="txt_idMuestra" id="txt_idMuestra" value="<?php echo $_SESSION['idMuestraSel']; ?>" readonly="readonly" 
            	size="40" maxlength="40"/>						
			</td>
            <td width="15%"><div align="right">Fecha Ruptura</div></td>
            <td width="15%">
                <input type="text" name="txt_fechaRuptura" id="txt_fechaRuptura" 
				value="<?php if(isset($_SESSION['datosPruebaAlerta']))  echo modFecha($_SESSION['datosPruebaAlerta']['fechaPrograma'],1); else echo date("d/m/Y");?>" 
				size="10" maxlength="10" readonly="readonly"/>			
			</td>  
            <td width="13%" align="right">Fecha colado</td>
            <td width="17%">&nbsp; 
            	<input type="text" name="txt_fechaColado" id="txt_fechaColado" value="<?php echo modFecha($fechaColado,1);?>" size="10" maxlength="10" readonly="readonly" />
			</td>     
        </tr> 
        <tr>
            <td><div align="right">*F' c</div></td>
          	<td>
				<input type="text" name="txt_fc" id="txt_fc" value="<?php echo $fc;?>" size="13" maxlength="10"
                onkeypress="return permite(event, 'num',2);" 
                onchange="formatCurrency(this.value,'txt_fc'); calcularPorcentaje(txt_cargaRuptura.value,txt_area.value,this.value);"/>
			</td>  
            <td><div align="right">*Edad</div></td>
            <td colspan="3">
				<input type="text" name="txt_edad" id="txt_edad" value="" size="5" maxlength="2"
           		onkeypress="return permite(event, 'num',2);" onchange="activarFotos(this);" onblur="verificarEdad(this,txt_idMuestra);"/>
            	&nbsp;D&iacute;as
				<span id='error' class="msj_error">Edad Repetida</span>			
			</td>
        </tr>        
        <tr>
            <td><div align="right">*Carga Ruptura</div></td>
            <td>
				<input type="text" name="txt_cargaRuptura" id="txt_cargaRuptura" value="" size="13" maxlength="10"
            	onkeypress="return permite(event, 'num',2);" 
         		onchange="calcularKgCm(this.value,txt_area.value);calcularPorcentaje(this.value,txt_area.value,txt_fc.value);formatCurrency(this.value,'txt_cargaRuptura');"/>
				&nbsp;Kg			
			</td>  
            <td><div align="right">*Kg/cm&sup2;</div></td>
            <td colspan="3">
				<input type="text" name="txt_kgcm" id="txt_kgcm" value="" size="8" onchange="formatCurrency(this.value,'txt_kgcm');"
            	readonly="readonly"/>			
			</td> 
        </tr> 
        <tr>
            <td><div align="right">*Porcentaje</div></td>
            <td>
				<input type="text" name="txt_porcentaje" id="txt_porcentaje" value="" size="10" readonly="readonly"
           		onchange="formatCurrency(this.value,'txt_porcentaje');"/>&nbsp;%			
			</td> 
            <td><div align="right">*Di&aacute;metro</div></td>
            <td>
            	<input type="text" name="txt_diametro" id="txt_diametro" value="<?php echo $diametro;?>" size="5" maxlength="5" 
                onkeypress="return permite(event, 'num',2)" 
                onchange="calcularArea(this.value);formatCurrency(this.value,'txt_diametro');"/>&nbsp;cm			
			</td>
            <td><div align="right">*Area</div></td>
            <td><input type="text" name="txt_area" id="txt_area" value="<?php echo $area;?>" size="10" readonly="readonly"/>&nbsp;cm&sup2;</td>
        </tr> 
        <tr>
            <td> <div align="right">Observaciones</div></td>
            <td valign="top" rowspan="2">
				<textarea class="caja_de_texto" cols="40" rows="3" maxlength="120" name="txa_observaciones" id="txa_observaciones" 
            	onkeyup="return ismaxlength(this);"></textarea>			
			</td>
            <td colspan="4"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr> 
        <tr>
        	<td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="6">
                <div align="center">
					<input type="hidden" name="hdn_edadValida" id="hdn_edadValida" value="si"/>
					<input type="hidden" name="hdn_pruebasCargadas" id="hdn_pruebasCargadas" value="no"/>
					
                    <input name="btn_verPruebasLab" id="btn_verPruebasLab" type="button" class="botones" value="Cargar Pruebas" title="Agregar Pruebas Aplicadas" 
                    onmouseover="window.status='';return true" 
                    onclick="window.open('verPruebasLab.php?accion=mostrarPruebas', 
					'_blank','top=100, left=100, width=800, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no')" />
                    &nbsp;&nbsp;&nbsp;
                    <input type="button" name="btn_cargaFotos" id="btn_cargaFotos" value="Cargar Fotos" onclick="envioDatosGet();" class="botones" 
					title="Para cargar Fotos indique la Edad" disabled="disabled"/>
					&nbsp;&nbsp;&nbsp;
                    <input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Ingrese las Fotos para Guardar" 
                    onmouseover="window.status='';return true" disabled="disabled"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
                    onmouseover="window.status='';return true" onclick="error.style.visibility='hidden';"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
                    title="Cancelar y Regresar" 
                    onmouseover="window.status='';return true" onclick="confirmarSalida('frm_registrarPruebas.php?cancelar');"/> 
                </div>			
			</td>
        </tr>
    </table>
    </form>
	</fieldset>
    <div id="calendario-FechaProg">
      	<input type="image" name="txt_fechaRuptura" id="txt_fechaRuptura" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_registrarPruebasMuestras2.txt_fechaRuptura,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Inicio"/> 
	</div>

</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
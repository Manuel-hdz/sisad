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
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:367px;height:20px;z-index:11;}
		#tabla-registrarAgregados {position:absolute;left:30px;top:190px;width:940px;height:490px;z-index:14;}
		#div-calendario { position:absolute; left:910px; top:227px; width:30px; height:26px; z-index:15; }
		-->
    </style>
</head>
<body><?php


	if(isset($_POST['rdb_nomMat'])){
		$fecha=date("Y-m-d");
		$nomMat=$_POST['rdb_nomMat'];
		$band="";
		//Obtenemos el id del material con la funcion obtenerDato()	
		$idMaterial=obtenerDato('bd_almacen', 'materiales', 'id_material','nom_material', $nomMat);
		$conn = conecta("bd_laboratorio");
		$numeroRegistros = mysql_num_rows(mysql_query("SELECT * FROM pruebas_agregados WHERE catalogo_materiales_id_material='$idMaterial' AND fecha= '$fecha'"));
		mysql_close($conn);
	}	
	if(isset($_GET['regresar']) && isset($_SESSION['pruebas']))	
		unset($_SESSION['pruebas']);
		
	//El boton continuar 'sbt_continuar' viene desde el formulario frm_registrarPruebasAgregados.php
	if(isset($_POST['sbt_continuar'])){
		//Guardar los datos en la SESSION
		$_SESSION['nomAgregado'] = $_POST['rdb_nomMat'];
	}//FIN if(isset($_POST['sbt_continuar']))

	if(isset($_SESSION['nomAgregado']))
		$nomMat=$_SESSION['nomAgregado'];
	else
		$nomMat="";	
		
	//Recueperar del arreglo de sesion los valores en caso de que desde la pag frm_registrarPruebasAgregados2.php se decida regresar a esta
	if(isset($_SESSION['infoAgregado'])){	
		$origenMat=$_SESSION['infoAgregado']['origenMat'];
		$pvss=$_SESSION['infoAgregado']['pvss'];
		$pvsc=$_SESSION['infoAgregado']['pvsc'];
		$densidad=$_SESSION['infoAgregado']['densidad'];
		$absorcion=$_SESSION['infoAgregado']['absorcion'];
		$finura=$_SESSION['infoAgregado']['finura'];
		$fecha=$_SESSION['infoAgregado']['fecha'];
		$granulometria=$_SESSION['infoAgregado']['granulometria'];
		$wmPvss=$_SESSION['infoAgregado']['wmPvss'];
		$wmPvsc=$_SESSION['infoAgregado']['wmPvsc'];
		$msssDensidad=$_SESSION['infoAgregado']['msssDensidad'];
		$msssAbsorcion=$_SESSION['infoAgregado']['msssAbsorcion'];
		$vmPvss=$_SESSION['infoAgregado']['vmPvss'];
		$vmPvsc=$_SESSION['infoAgregado']['vmPvsc'];
		$va=$_SESSION['infoAgregado']['va'];
		$ws=$_SESSION['infoAgregado']['ws'];
		$cmb_pruebaEjecutada=$_SESSION['infoAgregado']['cmb_pruebaEjecutada'];
		$cmb_norma=$_SESSION['infoAgregado']['cmb_norma'];	
		$pl=$_SESSION['infoAgregado']['pl'];
		$wspl=$_SESSION['infoAgregado']['wspl'];
		$wsc=$_SESSION['infoAgregado']['wsc'];	
	}	//FIN if(isset($_SESSION['infoAgregado']))	
	else{
		$origenMat="";
		$pvss="";
		$pvsc="";
		$densidad="";
		$absorcion="";
		$finura="";
		$fecha="";
		$granulometria="";
		$wmPvss="";
		$wmPvsc="";
		$msssDensidad="";
		$msssAbsorcion="";
		$vmPvss="";
		$vmPvsc="";
		$va="";
		$ws="";
		$cmb_pruebaEjecutada="";
		$cmb_norma="";
		$pl="";
		$wspl="";
		$wsc="";
	}// FIN else ?>


    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar Resultados de Pruebas a Agregados </div>
    <fieldset class="borde_seccion" id="tabla-registrarAgregados" name="tabla-registrarAgregados">
    <legend class="titulo_etiqueta">Ingrese el Resultado de la Prueba</legend>	
	<br>
	<form onSubmit="return valFormRegAgregados(this);" name="frm_registrarAgregados" method="post" action="frm_registrarAgregados2.php">
    <table width="100%" height="471" cellpadding="2" cellspacing="2" class="tabla_frm">
		<tr>
          	<td width="154"><div align="right">*Agregado</div></td>
            <td><input type="text" name="txt_agregado" id="txt_agregado" value="<?php echo $nomMat;?>" size="40" readonly="readonly"/></td>
          	<td width="58"><div align="right">*Norma</div></td>
            <td width="171"><?php 
				$grupo=cargarComboExcluyente("cmb_norma","norma","catalogo_pruebas","bd_laboratorio","N/A","norma","Seleccionar Norma",$cmb_norma); 
				if($grupo==0){ 
					echo "<label class='msje_correcto'>Es Necesario Agregar una Norma</label>";
				}?>
			</td>
		  	<td width="61"><div align="right">*Fecha</div></td>
          	<td width="173">
				<input name="txt_fecha" id="txt_fecha" type="text" class="caja_de_texto" size="10"
            	value="<?php echo date("d/m/Y");?>" readonly="readonly"/>            
			</td>
        </tr>        
        <tr>
          	<td width="154"><div align="right">*Origen Material</div></td> 
            <td colspan="2">
				<input type="text" name="txt_origen" id="txt_origen" value="<?php echo $origenMat;?>" onkeypress="return permite(event,'num_car',2);" 
            	size="45" maxlength="50"/> 
			</td>
          	<td width="171"><div align="right">*Granulometr&iacute;a</div></td> 
            <td colspan="2">
				<input type="text" name="txt_granulometria" id="txt_granulometria" value="<?php echo $granulometria; ?>" 
            	onkeypress="return permite(event,'num_car',2);" size="30" maxlength="30"/>            
			</td>
        </tr>        
        <tr>
          	<td width="154"><div align="right">*PVSS (kg/m&sup3;)</div></td>
          	<td width="239"><input type="text" name="txt_pvss" id="txt_pvss" value="<?php echo $pvss;?>" size="18" readonly="readonly"/> </td>
          	<td width="58"><div align="right">*Wm</div></td>
            <td>
				<input type="text" name="txt_wmPvss" id="txt_wmPvss" value="<?php echo $wmPvss;?>" onkeypress="return permite(event,'num',2);" size="18"  
                onchange="calculosAgregados(this.value,txt_vmPvss.value,1,txt_pvss);formatCurrency(txt_wmPvss.value,'txt_wmPvss');" maxlength="10"/>kg            
			</td>
          	<td width="61"><div align="right">*Vm</div></td>
          	<td width="173">
				<input type="text" name="txt_vmPvss" id="txt_vmPvss" value="<?php echo $vmPvss;?>" onkeypress="return permite(event,'num',2);" size="18"
             	onchange="calculosAgregados(txt_wmPvss.value,this.value,1,txt_pvss);formatCurrency(txt_vmPvss.value,'txt_vmPvss');" maxlength="10"/>lts
			</td>
        </tr>        
        <tr>
          	<td width="154"><div align="right">*PVSC (kg/m&sup3;)</div></td>
            <td><input type="text" name="txt_pvsc" id="txt_pvsc" value="<?php echo $pvsc;?>" size="18" readonly="readonly"/> </td>
          	<td width="58"><div align="right">*Wm</div></td>
            <td>
				<input type="text" name="txt_wmPvsc" id="txt_wmPvsc" value="<?php echo $wmPvss;?>" onkeypress="return permite(event,'num',2);" size="18"
                onchange="calculosAgregados(this.value,txt_vmPvsc.value,2,txt_pvsc);formatCurrency(txt_wmPvsc.value,'txt_wmPvsc');" maxlength="10"/>kg 
			</td>
          	<td width="61"><div align="right">*Vm</div></td>
          	<td width="173">
				<input type="text" name="txt_vmPvsc" id="txt_vmPvsc" value="<?php echo $vmPvss;?>" onkeypress="return permite(event,'num',2);" size="18"
            	onchange="calculosAgregados(txt_wmPvsc.value,this.value,2,txt_pvsc);formatCurrency(txt_vmPvsc.value,'txt_vmPvsc');" maxlength="10"/>lts
			</td>
        </tr>        
        <tr>
          	<td width="154"><div align="right">*Densidad (gr/cm&sup3;)</div></td>
            <td><input type="text" name="txt_densidad" id="txt_densidad" value="<?php echo $densidad;?>" size="18" readonly="readonly"/> </td>
          	<td width="58"><div align="right">*Msss</div></td>
            <td>
				<input type="text" name="txt_msssDensidad" id="txt_msssDensidad" value="<?php echo $msssDensidad; ?>" onkeypress="return permite(event,'num',2);" 
            	size="18" onchange="calculosAgregados(this.value,txt_va.value,3,txt_densidad);formatCurrency(txt_msssDensidad.value,'txt_msssDensidad');" maxlength="10"/>
				gr 
			</td>
          	<td width="61"><div align="right">*Va</div></td>
          	<td width="173">
				<input type="text" name="txt_va" id="txt_va" value="<?php echo $va; ?>" onkeypress="return permite(event,'num',2);" size="18"
            	onchange="calculosAgregados(txt_msssDensidad.value,this.value,3,txt_densidad);formatCurrency(txt_va.value,'txt_va');" maxlength="10"/>cm&sup3;
			</td>
        </tr>        
        <tr>
          	<td width="154"><div align="right">*Absorci&oacute;n (%)</div></td>
            <td><input type="text" name="txt_absorcion" id="txt_absorcion" value="<?php echo $absorcion;?>" size="18" readonly="readonly"/> </td>
          	<td width="58"><div align="right">*Msss</div></td>
            <td>
				<input type="text" name="txt_msssAbosrcion" id="txt_msssAbosrcion" value="<?php echo $msssAbsorcion;?>" onkeypress="return permite(event,'num',2);" 
            	size="18"  onchange="calculosAgregados(this.value,txt_ws.value,4,txt_absorcion);formatCurrency(txt_msssAbosrcion.value,'txt_msssAbosrcion');" 
            	maxlength="10"/>
            	gr 
			</td>
          	<td width="61"><div align="right">*Ws</div></td>
          	<td width="173">
				<input type="text" name="txt_ws" id="txt_ws" value="<?php echo $ws; ?>" onkeypress="return permite(event,'num',2);" size="18"
            	onchange="calculosAgregados(txt_msssAbosrcion.value,this.value,4,txt_absorcion);formatCurrency(txt_ws.value,'txt_ws');" maxlength="10"/>gr
			</td>
        </tr> 
		<tr>
          	<td width="154"><div align="right">*P&eacute;rdida por Lavado(%)</div></td>
            <td><?php
				//La finura solo se activa en caso de que el agregado contenga la palabra arena, por lo cual se realiza la siguiente comprobacion
				$cadena = $nomMat;
				$cadenaBusq = "arena";
				if(stristr($cadena, $cadenaBusq) === FALSE) {?>
					<input type="text" name="txt_pl" id="txt_pl" value="N/A" size="18" readonly="readonly"/><?php
				}
				else {?>
					<input type="text" name="txt_pl" id="txt_pl" value="<?php echo $pl;?>" size="18" readonly="readonly"/><?php
				}?> 
		  	</td>
          	<td width="58"><div align="right">*Wsc</div></td>
          	<td><?php
				//La finura solo se activa en caso de que el agregado contenga la palabra arena, por lo cual se realiza la siguiente comprobacion
				$cadena = $nomMat;
				$cadenaBusq = "arena";
				if(stristr($cadena, $cadenaBusq) === FALSE) {?>
					<input type="text" name="txt_wsc" id="txt_wsc" value="N/A"	size="18" maxlength="10" readonly="readonly"/> <?php
				}
				else {?>
					<input type="text" name="txt_wsc" id="txt_wsc" value="<?php echo $wsc;?>" onkeypress="return permite(event,'num',2);" 
            		size="18"  onchange="calculosAgregados(this.value,txt_wspl.value,5,txt_pl);formatCurrency(txt_wsc.value,'txt_wsc');" 
            		maxlength="10"/> <?php
				}?>            
          		gr 
			</td>
          	<td width="61"><div align="right">*Ws</div></td>
          	<td width="173"><?php
				//La finura solo se activa en caso de que el agregado contenga la palabra arena, por lo cual se realiza la siguiente comprobacion
				$cadena = $nomMat;
				$cadenaBusq = "arena";
				if(stristr($cadena, $cadenaBusq) === FALSE) {?>
					<input type="text" name="txt_wspl" id="txt_wspl" value="N/A" size="18" readonly="readonly"	maxlength="10"/> <?php
				}
				else {?>
					<input type="text" name="txt_wspl" id="txt_wspl" value="<?php echo $wspl; ?>" onkeypress="return permite(event,'num',2);" size="18"
           			onchange="calculosAgregados(txt_wsc.value,this.value,5,txt_pl);formatCurrency(txt_wspl.value,'txt_wspl');" maxlength="10"/> <?php
				}?>    
				gr
			</td>
        </tr>            
        <tr>
          	<td width="154" height="53"><div align="right">*M&oacute;dulo de Finura</div></td>
            <td><?php
				//La finura solo se activa en caso de que el agregado contenga la palabra arena, por lo cual se realiza la siguiente comprobacion
				$cadena = $nomMat;
				$cadenaBusq = "arena";
				if(stristr($cadena, $cadenaBusq) === FALSE) {?>
					<input type="text" name="txt_finura" id="txt_finura" value="N/A" size="20" readonly="readonly"/><?php
				}
				else {?>
					<input type="text" name="txt_finura" id="txt_finura" value="<?php echo $finura;?>" onkeypress="return permite(event,'num',2);" size="18" /><?php
				}?>          
			</td>
            <td><div align="right">*Prueba Ejecutada</div></td>
            <td colspan="3"><?php
			 	$result=cargarComboEspecifico("cmb_pruebaEjecutada","nombre","catalogo_pruebas","bd_laboratorio","ESTUDIO DE AGREGADOS","tipo","Prueba",$cmb_pruebaEjecutada);
				if($result==0){
	            	echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Pruebas Registradas</label>";
	            }?>		  
			</td>
        </tr>		 
        <tr>
            <td height="26" colspan="6"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>
        <tr>
            <td colspan="6"><strong>** Dastos marcados con doble asterisco son <u>obligatorios</u> dependiendo del tipo de Agregado</strong></td>
        </tr>
        <tr>
			<td colspan="6">
                <div align="center">
					<input name="sbt_continuarAg" type="submit" class="botones" id="sbt_continuarAg"  value="Continuar" title="Continuar para Registrar Materiales" 
                    onmouseover="window.status='';return true"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Restablecer" title="Limpiar Formulario" 
                    onmouseover="window.status='';return true"/>
                    &nbsp;&nbsp;&nbsp;
                    <input type="button" name="btn_regresar" id="btn_regresar" class="botones" value="Regresar" title="Regresar" 
                    onmouseover="window.status='';return true" onclick="location.href='frm_registrarPruebasAgregados.php'"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
                    title="Cancelar y Regresar al Men&uacute; de Mezclas " 
                    onmouseover="window.status='';return true" onclick="confirmarSalida('menu_mezclas.php');"/>
					<input type="hidden" name="hdn_material" id="hdn_material" value="<?php echo $nomMat; ?>"/>
                </div>            
			</td>
        </tr>
    </table>
    </form>
	</fieldset>
	
	<div id="div-calendario">
     	<input type="image" name="txt_fechaMuestreo" id="txt_fechaMuestreo" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_registrarAgregados.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
		title="Seleccionar Fecha de Muestreo" />
	</div>
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
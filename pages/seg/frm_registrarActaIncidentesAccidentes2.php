<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarActaIncidentesAccidentes.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:347px;height:20px;z-index:11;}
		#tabla-agregarActa {position:absolute;left:32px;top:332px;width:378px;height:94px;z-index:12;}
		#tabla-agregarActa2 {position:absolute;left:30px;top:190px;width:380px;height:98px;z-index:12;}
		#tabla-agregarActa3 {position:absolute;left:468px;top:192px;width:380px;height:98px;z-index:12;}
		#tabla-agregarActa4 {position:absolute;left:467px;top:333px;width:388px;height:232px;z-index:12;}
		#tabla-agregarActa5 {position:absolute;left:27px;top:465px;width:389px;height:98px;z-index:12;}
		#res-spider {position:absolute;z-index:15;}
#botones {position:absolute;left:23px;top:590px;width:880px;height:26px;z-index:17;}
		-->
    </style>
</head>
<body>
	<?php
	if(isset($_POST['sbt_continuar'])){
		if(isset($_SESSION['actaIncAcc']['descripcion'])){
			$actaIncAcc = array('idActa'=>$_POST['txt_idActa'],'lugar'=>strtoupper($_POST['txt_lugar']),'turno'=>$_POST['cmb_turno'], 
				'tipoAcc'=>$_POST['cmb_tipoAccidente'], 'nivel'=>strtoupper($_POST['txt_nivel']),'horaAcc'=>$_POST['txt_horaIncidente'], 'area'=>$_POST['cmb_area'], 
				'areaAcc'=>strtoupper($_POST['txt_areaAcc']),'horaAviso'=>$_POST['txt_horaAviso'], 'fecha'=>$_POST['txt_fecha'],
				'nomFacilitador'=>strtoupper($_POST['txt_nombreFacilitador']),'horaLaborar'=>$_POST['txt_horaLaborar'], 'nomAcc'=>strtoupper($_POST['txt_nombreAcc']),
				'puesto'=>$_POST['cmb_puesto'], 'ficha'=>strtoupper($_POST['txt_ficha']), 'edad'=>$_POST['txt_edad'],'equipo'=>$_POST['cmb_equipo'], 
				'antEm'=>$_POST['txt_antEmp'], 'antPue'=>$_POST['txt_antPue'], 'noAcc'=>$_POST['txt_noAcc'], 
				'actividadMomAcc'=>strtoupper($_POST['txa_actividadMomAcc']),'actHab'=>strtoupper($_POST['txa_actHab']),
				'descripcion'=>$_SESSION['actaIncAcc']['descripcion'], 'lesion'=> $_SESSION['actaIncAcc']['lesion'],
				'porque'=>$_SESSION['actaIncAcc']['porque'],'actosInseguros'=>$_SESSION['actaIncAcc']['actosInseguros'],
				'condicionesInseguras'=>$_SESSION['actaIncAcc']['condicionesInseguras'] );
			
		}
		else{
			$actaIncAcc = array('idActa'=>$_POST['txt_idActa'],'lugar'=>strtoupper($_POST['txt_lugar']),'turno'=>$_POST['cmb_turno'], 
			'tipoAcc'=>$_POST['cmb_tipoAccidente'], 'nivel'=>strtoupper($_POST['txt_nivel']),'horaAcc'=>$_POST['txt_horaIncidente'], 'area'=>$_POST['cmb_area'], 
			'areaAcc'=>strtoupper($_POST['txt_areaAcc']),'horaAviso'=>$_POST['txt_horaAviso'], 'fecha'=>$_POST['txt_fecha'],
			'nomFacilitador'=>strtoupper($_POST['txt_nombreFacilitador']),'horaLaborar'=>$_POST['txt_horaLaborar'], 'nomAcc'=>strtoupper($_POST['txt_nombreAcc']),
			'puesto'=>$_POST['cmb_puesto'], 'ficha'=>strtoupper($_POST['txt_ficha']), 'edad'=>$_POST['txt_edad'],'equipo'=>$_POST['cmb_equipo'], 
			'antEm'=>$_POST['txt_antEmp'], 'antPue'=>$_POST['txt_antPue'], 'noAcc'=>$_POST['txt_noAcc'], 'actividadMomAcc'=>strtoupper($_POST['txa_actividadMomAcc']),
			'actHab'=>strtoupper($_POST['txa_actHab']));
		}
		$_SESSION['actaIncAcc'] = $actaIncAcc;
	}
	if(!isset($_GET['regresar'])&&!isset($_SESSION['actaIncAcc']['descripcion'])){
		$descripcion = "";
		$lesion ="";
		$porque = "";
		$actosInseguros = "";
		$condicionesInseguras = "";
	}
	else{
		$descripcion = $_SESSION['actaIncAcc']['descripcion'];
		$lesion = $_SESSION['actaIncAcc']['lesion'];
		$porque = $_SESSION['actaIncAcc']['porque'];
		$actosInseguros = $_SESSION['actaIncAcc']['actosInseguros'];
		$condicionesInseguras = $_SESSION['actaIncAcc']['condicionesInseguras'];
	}
	?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-registrar">Registrar Informe Incidentes Accidentes 2/3 </div>
		
		
		<form onsubmit="return valFormActaIncAcc2(this);" name="frm_agregarActa" id="frm_agregarActa" method="post" action="">
		<fieldset class="borde_seccion" id="tabla-agregarActa2" name="tabla-agregarActa2">
		<legend class="titulo_etiqueta">III. Descripci&oacute;n de los Hechos </legend>	
			<table width="289" height="80"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="92"><div align="right">*Descripci&oacute;n </div></td>
					<td width="156">
						<textarea name="txa_descripcion"  id="txa_descripcion"  maxlength="250" cols="50" rows="3" class="caja_de_texto"   
						onkeypress="return permite(event,'num_car',0);"  type="text" onkeyup="return ismaxlength(this)"><?php echo $descripcion;?></textarea>
				 	</td>
			    </tr>
		</table>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-agregarActa3" name="tabla-agregarActa3">
		<legend class="titulo_etiqueta">IV. Tipo de Lesi&oacute;n </legend>	
			<table width="289" height="80"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="92"><div align="right">*Lesi&oacute;n</div></td>
					<td width="156">
						<textarea name="txa_lesion"   maxlength="250" cols="50" rows="3" class="caja_de_texto" id="txa_lesion"  
						onkeypress="return permite(event,'num_car',0);"  type="text" onkeyup="return ismaxlength(this)"><?php echo $lesion;?></textarea>
				  </td>
			    </tr>
		</table>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-agregarActa" name="tabla-agregarActa">
		<legend class="titulo_etiqueta">V. An&aacute;lisis del Accidente </legend>	
			<table width="381" height="80"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="92"><div align="right">*&iquest;Por qu&eacute; Paso? </div></td>
					<td width="156">
						<textarea name="txa_porque"   maxlength="250" cols="50" rows="3" class="caja_de_texto" id="txa_porque"  
						onkeypress="return permite(event,'num_car',0);"  type="text" onkeyup="return ismaxlength(this)"><?php echo $porque;?></textarea>
				 	</td>
			    </tr>
		</table>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-agregarActa4" name="tabla-agregarActa4">
		<legend class="titulo_etiqueta">VI. Clausulas del Accidente/Incidente </legend>	
			<table width="397" height="213"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="107"><div align="right">*Actos Inseguros</div></td>
					<td width="267">
						<textarea name="txa_actosInseguros"   maxlength="250" cols="50" rows="3" class="caja_de_texto" id="txa_actosInseguros"  
						onkeypress="return permite(event,'num_car',0);"  type="text" onkeyup="return ismaxlength(this)"><?php echo $actosInseguros;?></textarea>
					</td>
		        </tr>
				<tr>
				  <td width="107"><div align="right">*Condiciones Inseguras </div></td>
				  <td>
				  	<textarea name="txa_condicionesInseguras"   maxlength="250" cols="50" rows="3" class="caja_de_texto" id="txa_condicionesInseguras"  
					onkeypress="return permite(event,'num_car',0);"  type="text" onkeyup="return ismaxlength(this)"><?php echo $condicionesInseguras;?></textarea>
				</td>
			  </tr>
		</table>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-agregarActa5" name="tabla-agregarActa5">
		<legend class="titulo_etiqueta">VII. Ingresar Acciones Preventivas y Correctivas </legend>	
			<table width="389" height="84"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td>
				    	<div align="center">
				    	  <input name="btn_acciones" type="button" class="botones_largos" value="Registrar Acciones" title="Registrar Acciones Correctivas/Preventivas" 
						onmouseover="window.status='';return true" onclick="abrirModRegAcc();" />
		    	        </div></td>
				</tr>
		</table>
		</fieldset>
		<div align="center" id="botones">
			<input type="hidden" name="hdn_boton"  id="hdn_boton" value="" />
        	<input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar" value="Continuar" title="Continuar Registro Acta Incidentes Accidentes"
			onmouseover="window.status='';return true" 
			<?php if(!isset($_GET['regresar'])&&!isset($_SESSION['accionesPrevCorr'])){echo "disabled='disabled'";}?> 
			onclick="hdn_boton.value = 'continuar';"/>
  			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       		<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Pantalla Anterior" 
			onmouseover="window.status='';return true" 
			onclick="hdn_boton.value = 'regresar';"/>
		</div>
		</form>
        
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
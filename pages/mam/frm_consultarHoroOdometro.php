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
		include ("op_consultarHoroOdometro.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquipoMtto.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-consultar {position:absolute;left:30px;top:146px;width:380px;height:20px;z-index:11;}
		#tabla-consultarMetrica {position:absolute;left:30px;top:190px;width:465px;height:180px;z-index:12;}
		#verMetrica{position:absolute;left:30px;top:190px;width:940px;height:420px;z-index:13;overflow:scroll}
		#calendario-Fin {position:absolute;left:492px;top:303px;width:30px;height:26px;z-index:14;}
		#calendario-Ini {position:absolute;left:492px;top:264px;width:30px;height:26px;z-index:15;}
		#btn_reg{position:absolute;left:30px;top:660px;width:945px;height:37px;z-index:16;}
		-->
    </style>
</head>
<body>
    
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Consultar Hor&oacute;metro u Od&oacute;metro de los Equipos</div><?php
	
	if(!isset($_POST['sbt_consultar'])){
		
		$cmb_area = "";
		$cmb_familia = "";
		$cmb_claveEquipo = "";
		
		//Obtener la fecha del sistema para la fecha inicio y fecha fin
		$txt_fechaIni = date("d/m/Y", strtotime("-30 day"));
		$txt_fechaFin = date("d/m/Y");
	
		/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
		$atributo = "";
		$area = $cmb_area;
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
		if($estado==0){ ?>		
			<script type="text/javascript" language="javascript">
				setTimeout("cargarCombo('<?php echo $area;?>','bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','')",500);
			</script><?php
		 } ?>
		
		
		
		<fieldset class="borde_seccion" id="tabla-consultarMetrica" name="tabla-consultarMetrica">
		<legend class="titulo_etiqueta">Consultar M&eacute;tricas de Equipos</legend>	
		<br>
		<form onSubmit="return valFormConsultarMetrica(this);" name="frm_consultarHoroOdometro" method="post" action="frm_consultarHoroOdometro.php">
		<table width="451" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="42" align="right"><div align="right">&Aacute;rea</div></td>
				<td width="160"><?php
					if($estado==1) {?>
						<select name="cmb_area" class="combo_box" 
							onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');">
							<option value="">&Aacute;rea</option>						
							<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
							<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
						</select><?php 
					} 
					else { ?>
						<select name="cmb_area" class="combo_box" 
							onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');" 
							<?php echo $atributo; ?>>
							<option value="">&Aacute;rea</option>						
							<option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
							<option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
						</select>		
						<input type="hidden" name="cmb_area" value="<?php echo $area; ?>" /><?php 
					} ?>                   
				</td>
			</tr>
            <tr>
				<td><div align="right">Familia</div></td>            
				<td>
					<select name="cmb_familia" id="cmb_familia" onchange="cargarEquiposFamilia(this.value,'<?php echo $area; ?>','cmb_claveEquipo','Clave','');">
						<option value="">Familia</option>
					</select>
				</td>
				<td width="104"><div align="right">Fecha Inicio</div></td>
				<td width="78"><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaIni;?>" 
					readonly="readonly"/>
                </td>
			</tr>
            <tr> 
			<td><div align="right">Equipo</div></td>            
				<td>
					<select name="cmb_claveEquipo" id="cmb_claveEquipo">
						<option value="">Clave Equipo</option>
					</select> 
                </td> 
				<td width="104"><div align="right">Fecha Fin</div></td>
				<td width="78"><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaFin;?>" 
					readonly="readonly"/>
			</td>
			</tr>
			<tr>
			  <td colspan="4" align="center">
					<input type="submit" name="sbt_consultar" id="sbt_consultar" class="botones" value="Consultar" title="Consultar Métrica del Equipo Seleccionado"
					 onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input type="button" name="btn_regresar" id="btn_regresar" class="botones" value="Regresar" title="Regresar al Menú Métricas"
					onclick="location.href='menu_metricas.php';"/>
			  </td>
			</tr>            	
		</table>
		</form>
		</fieldset>
        <div id="calendario-Ini">
            <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_consultarHoroOdometro.txt_fechaIni,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Inicio"/> 
        </div>
			
        <div id="calendario-Fin">
            <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_consultarHoroOdometro.txt_fechaFin,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Fin"/> 
        </div><?php
	}//FIN 	if(!isset($_POST['sbt_consultar']))	
	
	if(isset($_POST['sbt_consultar'])){?>
    	<div id="verMetrica" align="center" class="borde_seccion2"><?php
			mostrarMetrica()?>
        </div>
        <div id="btn_reg" align="center">
            <input type="button" name="btn_regresar2" id="btn_regresar2" class="botones" value="Regresar" title="Regresar a Consultar Métricas"
            onclick="location.href='frm_consultarHoroOdometro.php';"/>
        </div><?php	
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
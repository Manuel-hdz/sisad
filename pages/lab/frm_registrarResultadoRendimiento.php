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
		include ("op_registrarResultadoRendimiento.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
		<!--
		#titulo-buscar {position:absolute;left:30px;top:146px;width:331px;height:20px;z-index:11;}
		#tabla-buscarMezclaFecha {position:absolute;left:40px;top:190px;width:360px;height:151px;z-index:12;}
		#tabla-buscarMezclaClave {position:absolute;left:478px;top:190px;width:369px;height:151px;z-index:12;}
		#calendario-Ini {position:absolute;left:277px;top:232px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:277px;top:270px;width:30px;height:26px;z-index:14;}
		#detalle_mezcla {position:absolute;left:40px;top:371px;width:906px;height:177px;z-index:17;overflow:scroll;}
		#btn_continuar {position:absolute;left:47px;top:600px;width:946px;height:40px;z-index:9;}
		#btns_RegReg {position:absolute;left:47px;top:600px;width:946px;height:40px;z-index:9;}
		-->
    </style>
</head>
<body>


    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-buscar">Registrar Resultados de Rendimiento </div><?php
	
	
	if(!isset($_POST['rdb_idMezcla'])){
	
		// fieldset para manipulacion de mezclas buscadas por fecha?>	
		<fieldset class="borde_seccion" id="tabla-buscarMezclaFecha">
		<legend class="titulo_etiqueta">Consultar por Fecha de Registro </legend>	
		<br>
		<form onSubmit="return valFormBuscarMezclaFecha(this);" name="frm_buscarMezclaFecha" method="post" action="frm_registrarResultadoRendimiento.php">
		<table width="372" height="36" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="106"><div align="right">Fecha Inicio</div></td>
				<td width="229"><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10"
				value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" 
					readonly="readonly"/>
				</td>
			</tr>
			<tr>
				<td><div align="right">Fecha Fin </div></td>
				<td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y");?>" 
				readonly="readonly"/></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="sbt_consultar" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
					title="Consultar Mezclas"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='frm_registrarPruebas.php';"
					title="Regresar a la Pantalla Anterior"/>
				</td>
			</tr>
		</table>
		</form>   
		</fieldset>		
		<div id="calendario-Ini">
			<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_buscarMezclaFecha.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Inicio"/> 
		</div>		
		<div id="calendario-Fin">
			<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_buscarMezclaFecha.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Fin"/> 
		</div>
		
		
		
		<fieldset class="borde_seccion" id="tabla-buscarMezclaClave" name="tabla-buscarMezclaClave">
        <legend class="titulo_etiqueta">Consultar  por Clave </legend>	
        <br>    
		<form onSubmit="return valFormBuscarMezclaClave(this);" name="frm_buscarMezclaClave" method="post" action="frm_registrarResultadoRendimiento.php">
		<table width="371" height="132" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="140" height="50"><div align="right">Seleccione Id Mezcla</div></td>
				<td width="194"><?php 
					$result=cargarComboEspecifico("cmb_claveMezcla","id_mezcla","mezclas","bd_laboratorio","1","estado","Mezcla","");
					if($result==0){
						echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Mezclas Registradas</label>";
					}?>            
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><?php 
					if($result==1){?>
						<input name="sbt_consultar2" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
						title="Consultar Mezclas"/>
						&nbsp;&nbsp;&nbsp;<?php 
					}?>
					<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='frm_registrarResultadoRendimiento.php';"
					title="Regresar a la Pantalla Anterior"/>
				</td>
			</tr>
		</table>
		</form>
		</fieldset><?php
            
		//Si esta definido sbt_consultar2 o sbt_consultar se muestran las mezclas 
		if(isset($_POST['sbt_consultar2']) || isset($_POST['sbt_consultar'])){?>
			<form onSubmit="return valFormSeleccionarMezcla(this);" name="frm_seleccionarMezcla" method="post" action="frm_registrarResultadoRendimiento2.php"><?php
			
				if(isset($_POST["sbt_consultar2"])){ ?>
					<input type="hidden" name="cmb_claveMezcla" value="<?php echo $_POST['cmb_claveMezcla'];?>" />
					<input type="hidden" name="sbt_consultar2" value="Consultar"/><?php
				 }
				if(isset($_POST["sbt_consultar"])){ ?>
					<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni'];?>" />
					<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin'];?>" />
					<input type="hidden" name="sbt_consultar" value="Consultar" /><?php
				 }?>
				<div id='detalle_mezcla' class='borde_seccion2' align="center"><?php
					$control = mostrarMezclas();?>
				</div><?php
				if($control==1){?>
					<div id="btn_continuar" align="center">
						<input name="sbt_continuar" type="submit" class="botones" value="Continuar" onmouseover="window.status='';return true" 
						title="Continuar el Registro"/>
					</div><?php
				}?>
				
			</form><?php
		}// FIN if(isset($_POST['sbt_consultar2']) || isset($_POST['sbt_consultar']))
	}//FIN if(!isset($_POST['rdb_idMezcla']))?>
		
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
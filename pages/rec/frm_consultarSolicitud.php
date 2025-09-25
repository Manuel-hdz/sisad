<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion
html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");

	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de la Clinica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_elaborarSolicitud.php");
		?>
		
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>   
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
  

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:313px;	height:20px;	z-index:11;}
			#tabla-exaMedico {position:absolute;left:30px;top:193px;width:786px;height:142px;z-index:12;padding:15px;padding-top:0px;}
			#titulo-tabla {	position:absolute; left:30px; top:69px;	width:919px; height:295px; z-index:8;}
			#botones-TablaDatCat {position:absolute;left:892px;top:470px;width:204px;height:35px;z-index:12;padding:15px;padding-top:0px;}
			#calendario { position:absolute; left:286px; top:222px; width:30px; height:26px; z-index:14; }
			#resultados {position:absolute;left:32px;top:364px;width:784px;height:304px;z-index:12;padding:15px;padding-top:0px;overflow:scroll;}
			#tabla-exaMedico2 {position:absolute;left:342px;top:485px;width:104px;height:33px;z-index:12;}

		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Consultar Solicitud Ex&aacute;men M&eacute;dico</div>

<?php
	//verificar que este definido el ID del tipo de reporte a mostrar
	if (isset($_GET["id_nomEmp"]))
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente, es decir las variables que vienen en la url
		$idEmpresa = $_GET["id_nomEmp"];

	?>
	
	<fieldset class="borde_seccion" id="tabla-exaMedico" name="tabla-exaMedico">
	<legend class="titulo_etiqueta">Consultar Solicitud del Examen Medico</legend>
	<form onsubmit="return valFormConsultarSolicitud(this);"  name="frm_consultarSolExaMed" method="post"  id="frm_consultarSolExaMed" >
	<table width="106%"  cellpadding="5" cellspacing="5"  class="tabla_frm">
		<tr>
			<td width="130"><div align="right">Fecha</div></td>
			<td width="161">
				<input name="txt_fecha" type="text" id="txt_fecha" value=<?php echo date("d/m/Y");?> size="10" maxlength="15" readonly="readonly" class="caja_de_texto"/>
		  	</td>
		  	<td width="169"><div align="right">*Nombre Empresa</div></td>
		  	<td width="306"><?php 
					$result=cargarComboConId("cmb_empresa","nom_empresa","id_empresa","catalogo_empresas","bd_clinica","Nombre Empresa",$idEmpresa,"");
						if($result==0) {
							echo "<label class='msje_correcto'>No hay Cliente Registrados</label>
							<input type='hidden' name='cmb_empresa' id='cmb_empresa'/>";
						}
					?>		  
			</td>
		</tr>
		<tr> 
			<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>
		<tr>
			<td colspan="4" align="center">
				<input type="hidden" name="cmb_examen" id="cmb_examen"/>
				<input name="sbt_consultar" type="submit" class="botones" id="sbt_guardar"  value="Consultar" title="Consultar la Solicitud de Ex&aacute;men M&eacute;dico" 
				onmouseover="window.status='';return true"/>
				&nbsp;&nbsp;&nbsp;
				<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
				onmouseover="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" 
				title="Regresar a la secci&oacute;n Anterior" onmouseover="window.status='';return true" onclick="location.href='frm_gestionarSolicitud.php'"/>
			</td>
		</tr>
	</table>
	</form>
</fieldset>	
	
	<div id="calendario">
		<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_consultarSolExaMed.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>

<?php
		if(isset($_POST["sbt_consultar"])){
			echo "<form name='frm_enviarDatosHCExterno'>";
			echo "<div id='resultados' class='borde_seccion2'>";
				consultarSolicitud();
			echo "</div>";?>
			<?php 		
			echo "</form>";
		} ?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
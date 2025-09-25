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
		include ("op_gestionarSolicitud.php");
		?>	
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="includes/ajax/verificarTipoRegistroExaMedico.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>   
	
	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:545px;	height:20px;	z-index:11;}
			#tabla-exaMedico {position:absolute;left:24px;top:193px;width:599px;height:174px;z-index:12;padding:15px;padding-top:0px;}
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8;}
			#botones-TablaDatCat {position:absolute;left:892px;top:470px;width:204px;height:35px;z-index:12;padding:15px;padding-top:0px;}
		-->
    </style>
</head>

<body>
	<?php
	if (isset($_SESSION["datosSolicitudMedica"]))
		unset($_SESSION["datosSolicitudMedica"]);
	?>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-seleccionar">Seleccionar la Opci&oacute;n de las Solicitudes de los Ex&aacute;menes M&eacute;dicos</div>

	<fieldset class="borde_seccion" id="tabla-exaMedico" name="tabla-exaMedico">
	<legend class="titulo_etiqueta">Seleccionar los Datos de la Solicitud para Examen Medico</legend>
	<form onsubmit="return valFormSolicitudExaMedico(this);" name="frm_solicitudExamenMedico" method="get" id="frm_solicitudExamenMedico">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">*Nombre Empresa</div></td>
				<td><?php
					$cmb_empresa="";
					$conn = conecta("bd_clinica");
					$result=mysql_query("SELECT id_empresa, nom_empresa FROM catalogo_empresas ORDER BY id_empresa");?>
						<select name="cmb_empresa" id="cmb_empresa" size="1" class="combo_box" onchange="activarOpcionTipoCon(this);">				
							<option value="">Empresas</option><?php
								while ($row=mysql_fetch_array($result)){
									if ($row['id_empresa'] == $cmb_empresa){
										echo "<option value='$row[id_empresa]' selected='selected'>$row[nom_empresa]</option>";
									}
									else{
										echo "<option value='$row[id_empresa]'>$row[nom_empresa]</option>";
									}
								}
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
						</select>	
				</td>
				<td><div align="center">
					<input type="checkbox" name="ckb_nuevaEmpresa" id="ckb_nuevaEmpresa" 
					onclick="regNuevaEmpresa(this);"/><strong><u>Registrar Nueva Empresa Externa</u></strong></div>				
				
				</td>
			</tr>
	  		<tr> 
				<td align="right"><span id="tipoConsulta"></span></td>
				<td>
					<select name="cmb_tipoConsulta" id="cmb_tipoConsulta" class="combo_box" style="visibility:hidden" 
						onchange="javascript:document.frm_solicitudExamenMedico.submit();" >
						<option value="">Seleccionar</option>
						<option value="CONSULTAR">CONSULTAR SOLICITUD</option>
						<option value="ELABORAR">ELABORAR SOLICITUD</option>
					</select>
				</td>
			</tr>
				<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  		</tr>
	  		<tr>
				<td colspan="3"><div align="center">
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					title="Regresar al Inicio de Recursos Humanos" onmouseover="window.status='';return true" onclick="location.href='inicio_recursos.php'" />
				</div></td>
			</tr>
		</table>
	</form>
	</fieldset>	

<?php
if(isset($_GET['cmb_tipoConsulta'])){
	//Variable que se utiliza para guardar el nombre de la empresa que haya sido seleccionada por el usuario
	$idEmpresa = $_GET['cmb_empresa'];
	
	if($_GET["cmb_tipoConsulta"]=="CONSULTAR"){
		echo "<meta http-equiv='refresh' content='0;url=frm_consultarSolicitud.php?id_tipoCon=CONSULTAR&id_nomEmp=$idEmpresa'>";			
	}
	
	if($_GET["cmb_tipoConsulta"]=="ELABORAR"){
		echo "<meta http-equiv='refresh' content='0;url=frm_elaborarSolicitud.php?id_tipoCon=ELABORAR&id_nomEmp=$idEmpresa'>";			
	}
}?>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
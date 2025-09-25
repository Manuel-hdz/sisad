<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo USO
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	
	<script type="text/javascript" language="javascript">
		function valFormSelTipoConsulta(frm_seleccionarTipoConsulta){
			res=1;
			if (frm_seleccionarTipoConsulta.cmb_clasificacion.value==""){
				res=0;
				alert("Seleccionar si la Consulta es Interna o Externa");
				frm_seleccionarTipoConsulta.cmb_clasificacion.focus();
			}
			if (res==1 && frm_seleccionarTipoConsulta.cmb_tipo.value==""){
				res=0;
				alert("Seleccionar si es Consulta de tipo General o por Accidente");
				frm_seleccionarTipoConsulta.cmb_tipo.focus();
			}
			if(res==1)
				return true;
			else
				return false;
		}
	</script>
	
    <style type="text/css">
		<!--
		#titulo-generar{ position:absolute; left:30px; top:146px; width:285px; height:19px; z-index:11;}
		#tabla-consulta{ position:absolute; left:30px; top:190px; width:499px;	height:147px; z-index:12; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Bit&aacute;cora de Consultas M&eacute;dicas</div>

	<?php
		//Si estan definidos los arreglos de medicamentos y de los datos de la consulta medica, darlos de baja cada cual
		if(isset($_SESSION["medicamento"]))
			unset($_SESSION["medicamento"]);
		if(isset($_SESSION["datosConsMedica"]))
			unset($_SESSION["datosConsMedica"]);
	?>

	<fieldset id="tabla-consulta" class="borde_seccion">
	<legend class="titulo_etiqueta">Seleccionar Datos de la consulta</legend>
	<br>	
	<form onsubmit="return valFormSelTipoConsulta(this);" name="frm_seleccionarTipoConsulta" method="post" action="frm_regBitacoraConsultasMed2.php" >
	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
		<tr>
		  <td width="127"><div align="right">*Consulta</div></td>
			<td width="337">
				<select name="cmb_clasificacion" class="combo_box" id="cmb_clasificacion">
					<option value="" selected="selected">Consulta</option>
					<option value="INTERNA">INTERNA</option>
					<option value="EXTERNA">EXTERNA</option>
				</select>
		  </td>			
		</tr>
		<tr>
			<td><div align="right">*Tipo de Consulta</div></td>
			<td>
				<select name="cmb_tipo" class="combo_box" id="cmb_tipo">
					<option value="" selected="selected">Tipo Consulta</option>
					<option value="GENERAL">GENERAL</option>
					<option value="ACCIDENTE">ACCIDENTE</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" name="sbt_continuar" id="sbt_continuar" class="botones" title="Continuar con el Registro en la Bit&aacute;cora de Consultas M&eacute;dicas" value="Continuar" onmouseover="window.status='';return true;"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Volver al Men&uacute; de Consultas M&eacute;dicas" value="Regresar" onclick="location.href='menu_bitacoraConsultasMed.php'"/>
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
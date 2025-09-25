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
		include ("op_gestionEmpExternas.php");
		?>
		
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="includes/ajax/verificarTipoRegistroEmpExt.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/jsColor/jscolor.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

	<script type="text/javascript" language="javascript">
		function restablecerColor(opc){
			if(opc==1){
				if(!document.getElementById("ckb_nuevaEmpresa").checked){
					document.getElementById("txt_color").style.background="FFF";
					document.getElementById("txt_color").disabled=true;
					document.getElementById("txt_color").value="FFFFFF";
				}
				else
					document.getElementById("txt_color").disabled=false;
			}
			if(opc==2){
				document.getElementById("txt_color").style.background="FFF";
				document.getElementById("txt_color").disabled=true;
				document.getElementById("txt_color").value="FFFFFF";
			}
		}
	</script>

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:313px;	height:20px;	z-index:11;}
			#tabla-empresasExt {position:absolute;left:23px;top:177px;width:786px;height:379px;z-index:12;padding:15px;padding-top:0px;}
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8;}
			#botones-TablaDatCat {position:absolute;left:892px;top:470px;width:204px;height:35px;z-index:12;padding:15px;padding-top:0px;}
		
		
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Registrar Catálogo de Empresas Externas</div>

	<fieldset class="borde_seccion" id="tabla-empresasExt" name="tabla-empresasExt">
	<legend class="titulo_etiqueta">Seleccionar o Ingresar los Datos de la Empresa</legend>
	<form onsubmit="return valFormgestionEmpExternas(this);"  name="frm_gesEmpresasExternas" method="post"  id="frm_gesEmpresasExternas" >
		<table width="100%"  cellpadding="5" cellspacing="5"  class="tabla_frm">
			<tr>
			  <td width="19%"><div align="right">Nombre Empresa</div></td>
				<td width="23%"><?php 
						$result=cargarComboConId("cmb_empresa","nom_empresa","id_empresa","catalogo_empresas","bd_clinica","Seleccionar","","verificarRegistroEmpresasExt(this.value)");
							if($result==0){
								echo "<label class='msje_correcto'>No hay Empresas Registradas</label>
								<input type='hidden' name='cmb_empresa' id='cmb_empresa'/>";
							}
						?></td>	
				<td colspan="2"><div align="center">
					<input type="checkbox" name="ckb_nuevaEmpresa" id="ckb_nuevaEmpresa" 
					onclick="agregarNuevaEmpresaExt(this);restablecerColor(1);"/><strong><u>Registrar Empresa Externa</u></strong></div>				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>	
				<td>&nbsp;</td>
				<td>&nbsp;</td>				
			</tr>
			<tr>
				<td colspan="2"><strong>Datos de la Empresa Externa</strong></td>
				<td><input type="hidden" name="hdn_claveEmpresa" id="hdn_claveEmpresa" value="" /></td>	
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">* Nombre Empresa </div></td>
				<td><input type="text" name="txt_nomEmpresa" id="txt_nomEmpresa" maxlength="80" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" readonly="readonly"/>				</td>
				<td><div align="right">Ciudad</div></td>
				<td><input type="text" name="txt_ciudad" id="txt_ciudad" maxlength="40" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" readonly="readonly" /></td>
			</tr>
			<tr><td width="19%"><div align="right">*Raz&oacute;n Social </div></td>
				<td>
					<input type="text" name="txt_razonSocial" id="txt_razonSocial" maxlength="80" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" onkeyup="return ismaxlength(this)" readonly="readonly" />				</td>
				<td><div align="right">Estado</div></td>
				<td><input type="text" name="txt_estado" id="txt_estado" maxlength="40" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" readonly="readonly" /></td>
			</tr>
			<tr>
				<td><div align="right">Tipo Empresa </div></td>
				<td>
					<input type="text" name="txt_tipoEmpresa" id="txt_tipoEmpresa" maxlength="50" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" readonly="readonly" />				</td>
				<td><div align="right">Colonia</div></td>
				<td><input type="text" name="txt_colonia" id="txt_colonia" maxlength="40" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" readonly="readonly" /></td>
			</tr>
			<tr>
				<td><div align="right">Calle</div></td>
				<td>
					<input type="text" name="txt_calle" id="txt_calle" maxlength="40" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);"  readonly="readonly"/>				</td>
				<td><div align="right">Tel&eacute;fono</div></td>
				<td><input type="text" name="txt_tel" id="txt_tel" maxlength="20" size="20" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num',3);" readonly="readonly" onblur="validarTelefono(this);"></td>
			</tr>
			<tr>
				<td><div align="right">No. Int</div></td>
			  <td><input type="text" name="txt_numInt" id="txt_numInt" maxlength="10" size="5" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num',1);" readonly="readonly" />
			    	&nbsp;No. Ext
				<input type="text" name="txt_numExt" id="txt_numExt" maxlength="10" size="5" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num',1);" readonly="readonly" /></td>
				<td><div align="right">*Color</div></td>
				<td>
					<input type="text" name="txt_color" id="txt_color" size="6" maxlength="6" disabled='disabled'
					onkeypress="return permite(event,'num_car', 3);" value="FFFFFF" class="color" title="Seleccionar Color"/>
				</td>
			</tr>
	  		<tr> 
				<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
	  		</tr>
	  		<tr>
				<td colspan="6"><div align="center">
					<input type="hidden" name="cmb_empresa" id="cmb_empresa"/>
					<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar los Datos de la Nueva Empresa Externa" 
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
					onmouseover="window.status='';return true" onclick="restablecerEmpresaExt(this.value);restablecerColor(2)" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					title="Regresar al Men&uacute de Cat&aacute;logos" onmouseover="window.status='';return true" onclick="confirmarSalida('menu_catalogos.php')" />
					&nbsp;&nbsp;&nbsp;					
					<?php //Si no existen datos dentro de la BD, entonces que el boton de exportar a excel NO se muestre
					if($result==0){?>
						<input type="button" class="botones_largos" value="Exportar a Excel" name="btn_exportar" id="btn_exportar" 
						title="Exportar a Excel los Registros de las Empresas Externas"
						onclick="location.href='guardar_reporte.php?&tipoRep=catalogoEmpExt'" disabled="disabled"/>
					<?php }else{ ?>
						<input type="button" class="botones_largos" value="Exportar a Excel" name="btn_exportar" id="btn_exportar" 
						title="Exportar a Excel los Registros de las Empresas Externas"
						onclick="location.href='guardar_reporte.php?&tipoRep=catalogoEmpExt'" /> 
					<?php } ?>
				</div></td>
			</tr>
	  </table>
	</form>
</fieldset>	

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
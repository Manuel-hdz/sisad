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
		include ("op_gestionCatRadiografias.php");
		?>
		
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionClinica.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="includes/ajax/verificarTipoRegistroCatRadiografias.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:313px;	height:20px;	z-index:11;}
			#tabla-catRad {position:absolute;left:24px;top:193px;width:786px;height:291px;z-index:12;padding:15px;padding-top:0px;}
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8;}
			#botones-TablaDatCat {position:absolute;left:892px;top:470px;width:204px;height:35px;z-index:12;padding:15px;padding-top:0px;}
		
		
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Registrar Catálogo de Radiograf&iacute;a</div>

	<fieldset class="borde_seccion" id="tabla-catRad" name="tabla-catRad">
	<legend class="titulo_etiqueta">Seleccionar o Ingresar los Datos de la Radiograf&iacute;as</legend>
	<form onsubmit="return valFormgestionCatRadiografias(this);"  name="frm_gesCatRadiografias" method="post"  id="frm_gesExamenMedico" >
	  <table width="106%"  cellpadding="5" cellspacing="5"  class="tabla_frm">
        <tr>
          <td width="19%"><div align="right">Nombre Radiograf&iacute;a</div></td>
          <td width="23%"><?php 
						$result=cargarComboConId("cmb_proyeccion","nom_proyeccion","id_proyeccion","catalogo_radiografias","bd_clinica","Seleccionar","","verificarRegistroProyecciones(this.value)");
							if($result==0){
								echo "<label class='msje_correcto'>No hay Radiograf&iacute;as Registradas</label>
								<input type='hidden' name='cmb_proyeccion' id='cmb_proyeccion'/>";
							}	
						?></td>
          <td colspan="2"><div align="center">
              <input type="checkbox" name="ckb_nuevaProyeccion" id="ckb_nuevaProyeccion" 
					onclick="agregarNuevaProyeccion(this);"/>
            <strong><u>Registrar Nueva Radiograf&iacute;as</u></strong></div></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr>
			<td colspan="2"><strong>Datos de las Radiograf&iacute;as</strong></td>
			<td><input type="hidden" name="hdn_claveProyeccion" id="hdn_claveProyeccion" value="" /></td>
			<td>&nbsp;</td>
        </tr>
        <tr>
			<td><div align="right">*Nombre Proyecci&oacute;n</div></td>
			<td><input type="text" name="txt_nomProyeccion" id="txt_nomProyeccion" maxlength="50" size="30" class="caja_de_texto" 
					value="" onkeypress="return permite(event,'num_car',1);" readonly="readonly"/>
			</td>
			<td><div align="right">Comentarios</div></td>
			<td><textarea name="txa_comentarios" id="txa_comentarios" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
					rows="2" cols="40"	onkeypress="return permite(event,'num_car', 0);" readonly="readonly"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>
		<tr>
			<td colspan="6"><div align="center">
			  <input type="hidden" name="cmb_proyeccion" id="cmb_proyeccion"/>
			  <input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar el Registro de las Radiografías" 
							onmouseover="window.status='';return true"/>
			  &nbsp;&nbsp;&nbsp;
			  <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
							onmouseover="window.status='';return true" onclick="restablecerCatRadiografias(this.value)" />
			  &nbsp;&nbsp;&nbsp;
			  <input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
							title="Regresar para Seleccionar otra Opción" onmouseover="window.status='';return true" onclick="confirmarSalida('menu_catalogos.php')" />
			  &nbsp;&nbsp;&nbsp;
			  <?php //Si no existen datos dentro de la BD, entonces que el boton de exportar a excel NO se muestre
							if($result==0){?>
			  <input type="button" class="botones_largos" value="Exportar a Excel" name="btn_exportar" id="btn_exportar" 
								title="Exportar a Excel los Registros de las Radiografías"
								onclick="location.href='guardar_reporte.php?&tipoRep=catalogoRadiografias'" disabled="disabled"/>
			  <?php }else{ ?>
			  <input type="button" class="botones_largos" value="Exportar a Excel" name="btn_exportar2" id="btn_exportar2" 
								title="Exportar a Excel los Registros de las Radiografías"
								onclick="location.href='guardar_reporte.php?&tipoRep=catalogoRadiografias'" />
			  <?php } ?>
			</div></td>
		</tr>
	</table>
	</form>
</fieldset>	

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
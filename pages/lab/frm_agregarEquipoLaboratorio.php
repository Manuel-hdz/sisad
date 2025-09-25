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
		include ("op_agregarEquipoLaboratorio.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarEquipo {position:absolute;left:30px;top:190px;width:750px;height:316px;z-index:12;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Agregar Equipo </div>
	<fieldset class="borde_seccion" id="tabla-agregarEquipo" name="tabla-agregarEquipo">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Equipo </legend>	
	<br>
	<form  name="frm_agregarEquipo" method="post" action="frm_agregarEquipoLaboratorio.php" onsubmit="return valFormAgregarEquipo(this);" >
	  <table  cellpadding="5" cellspacing="5" class="tabla_frm">
      	<tr>
   			<td width="120"><div align="right">*No Interno </div></td>
          	<td>
				<input name="txt_noInterno" id="txt_noInterno" type="text" class="caja_de_texto" size="6" maxlength="3" value="<?php echo obtenerIdEquipo();?>" 
				 readonly="readonly" />
				<span id='error' class="msj_error">N&uacute;mero Duplicado</span>
			</td>
          	<td width="190"><div align="right">No Serie </div></td>
          	<td><input name="txt_noSerie" type="text" id="txt_noSerie" size="10" maxlength="15" onkeypress="return permite(event,'num_car', 1);" /></td>
        </tr>
       	<tr>
			<td><div align="right">Marca </div></td>
			<td>
				<input name="txt_marca" id="txt_marca" type="text" class="caja_de_texto" size="20" maxlength="30" 
				onkeypress="return permite(event,'num_car', 1);"/>
			</td>
			<td><div align="right">Resoluci&oacute;n </div></td>
			<td>
				<input name="txt_resolucion" id="txt_resolucion" type="text" class="caja_de_texto" size="20" maxlength="30" 
				onkeypress="return permite(event,'num_car', 1);"/>
			</td>
       	</tr>
		<tr>
			<td><div align="right">*Instrumento</div></td>
			<td>
				<input name="txt_nomEquipo" id="txt_nomEquipo" type="text" class="caja_de_texto" size="40" maxlength="50"
			 	onkeypress="return permite(event,'num_car', 1);"/>
			</td>
			<td><div align="right">Escala</div></td>
			<td>
				<input name="txt_escala" id="txt_escala" type="text" class="caja_de_texto" size="10" maxlength="15" 
				onkeypress="return permite(event,'num_car', 0);"/>
			</td>
		</tr>
		<tr>
			<td><div align="right">Exactitud</div></td>
			<td>
				<input name="txt_exactitud" id="txt_exactitud" type="text" class="caja_de_texto" size="10" maxlength="15" 
				onkeypress="return permite(event,'num_car', 0);"/>
			</td>
			<td><div align="right">*Asignado a </div></td>
			<td>
				<input name="txt_responsable" id="txt_responsable" type="text" class="caja_de_texto" size="30" maxlength="35" 
				onkeypress="return permite(event,'num_car', 2);"/>
			</td>
		</tr>
        <tr>
         	<td><div align="right">*Aplicaci&oacute;n</div></td>
          	<td>
				<textarea name="txa_aplicacion" id="txa_aplicacion" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea>         
			</td>
			<td><div align="right">*Calibrable</div></td>
			<td>
				<select name="cmb_calibrable" id="cmb_calibrable" size="1" class="combo_box">
			  		<option value="">Calibrable</option>
			  		<option value="SI">SI</option>
			  		<option value="NO">NO</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>
		<tr>
       	  	<td colspan="4">
				<div align="center">
					<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/> 
					<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Equipo"
					onMouseOver="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones" onclick="error.style.visibility='hidden'"  value="Limpiar" title="Limpiar Formulario" 
					onMouseOver="window.status='';return true"/> 
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Equipo" 
					onmouseover="window.status='';return true" onclick="confirmarSalida('menu_equipoLaboratorio.php')" />
				</div>			
			</td>
		</tr>
   	  </table>
	</form>
</fieldset>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:356px;height:20px;z-index:11;}
		#tabla-agregarActa {position:absolute;left:32px;top:332px;width:857px;height:139px;z-index:12;}
		#tabla-agregarActa2 {position:absolute;left:30px;top:190px;width:380px;height:98px;z-index:12;}
		#tabla-agregarActa3 {position:absolute;left:468px;top:192px;width:380px;height:98px;z-index:12;}
		#tabla-agregarActa4 {position:absolute;left:467px;top:333px;width:388px;height:232px;z-index:12;}
		#tabla-agregarActa5 {position:absolute;left:27px;top:465px;width:389px;height:98px;z-index:12;}
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		#botones {position:absolute;left:44px;top:509px;width:880px;height:26px;z-index:17;}
		-->
    </style>
</head>
<body>
	<?php
	if(!isset($_POST['sbt_guardar'])){ 
		//Verificamos que venga ene l post el boton de continuar para guardar los registros en la sesion
		if(isset($_POST['hdn_boton'])){
  			$_SESSION['actaIncAcc']['descripcion'] = strtoupper($_POST['txa_descripcion']);
			$_SESSION['actaIncAcc']['lesion'] = strtoupper($_POST['txa_lesion']);
			$_SESSION['actaIncAcc']['actosInseguros'] = strtoupper($_POST['txa_actosInseguros']);
			$_SESSION['actaIncAcc']['porque'] = strtoupper($_POST['txa_porque']);
			$_SESSION['actaIncAcc']['condicionesInseguras'] = strtoupper($_POST['txa_condicionesInseguras']);
		}?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-registrar">Registrar Informe Incidentes Accidentes 3/3</div>
		
		<form  onsubmit="return valFormActaIncAcc3(this);"name="frm_agregarActa" id="frm_agregarActa" method="post" action="frm_registrarActaIncidentesAccidentes3.php">
		<fieldset class="borde_seccion" id="tabla-agregarActa2" name="tabla-agregarActa2">
		<legend class="titulo_etiqueta">VIII. Observaciones </legend>	
			<table width="289" height="80"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="92"><div align="right">*Observaciones</div></td>
					<td width="156">
						<textarea name="txa_observaciones"  id="txa_observaciones"  maxlength="250" cols="50" rows="3" class="caja_de_texto"   
						onkeypress="return permite(event,'num_car',0);"  type="text" onkeyup="return ismaxlength(this)"></textarea>
				  	</td>
			    </tr>
			</table>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-agregarActa" name="tabla-agregarActa">
		<legend class="titulo_etiqueta">Atentamente</legend>	
			<table width="867" height="80"  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="192"><div align="right">*Jefe de Seguridad </div></td>
					<td width="177">
						<input name="txt_jefeSeguridad" type="text" id="txt_jefeSeguridad" size="40" maxlength="40" class="caja_de_texto"
						onkeypress="return permite(event,'num_car', 3);"/></td>
			        <td width="172"><div align="right">*Coordinador CSH </div></td>
			        <td width="259">
						<input name="txt_coordinadorCSH" type="text" id="txt_coordinadorCSH" size="40" maxlength="40" class="caja_de_texto"
						onkeypress="return permite(event,'num_car', 3);"/>
					</td>
		        </tr>
				<tr>
					<td><div align="right">*Departamento Seguridad</div></td>
			     	<td width="177">
						<input name="txt_deptoSeguridad" type="text" id="txt_deptoSeguridad" size="40" maxlength="40" class="caja_de_texto"
						onkeypress="return permite(event,'num_car', 3);"/>
					</td>
			      	<td width="172"><div align="right">*Secretario CSH </div></td>
			      	<td width="259">
						<input name="txt_secretarioCSH" type="text" id="txt_secretarioCSH" size="40" maxlength="40" class="caja_de_texto"
						onkeypress="return permite(event,'num_car', 3);"/>
					</td>
			  	</tr>
				<tr>
					<td><div align="right">*Testigo</div></td>
			      	<td width="177">
						<input name="txt_testigo" type="text" id="txt_testigo" size="40" maxlength="40" class="caja_de_texto" 
						onkeypress="return permite(event,'num_car', 3);"/>
					</td>
			      	<td width="172">&nbsp;</td>
			      	<td width="259">&nbsp;</td>
			  	</tr>
			</table>
		</fieldset>
		<div align="center" id="botones">
        	<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar" value="Guardar" title="Guardar Registro Acta Incidentes Accidentes"
			onmouseover="window.status='';return true" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_limpiar" type="reset" class="botones" id="btn_limpiar" value="Limpiar" title="Limpiar Registro Acta Incidentes Accidentes"
			onmouseover="window.status='';return true" />
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar a la Página Anterior" 
			onmouseover="window.status='';return true"  onclick="location.href='frm_registrarActaIncidentesAccidentes2.php?regresar=1'" />
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       		<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar Registro y Regresar al Men&uacute; Informe Incidentes/Accidentes" 
			onmouseover="window.status='';return true"  onclick="confirmarSalida('menu_actaSeguridadHigiene.php')" /> </div>
		</div>
		</form>
	<?php }
	else{
		//Enviamos a la fncin que permite el registro del informe
		registrarInformeIncAcc();?>
		<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div>
      <?php 
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
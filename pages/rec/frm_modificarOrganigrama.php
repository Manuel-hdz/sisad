<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include("head_menu.php");
		include("op_modificarOrganigrama.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
    <style type="text/css">
		<!--
		#titulo-modificarOrg { position:absolute; left:30px; top:146px; width:211px; height:20px; z-index:11; }
		#modificar-organigrama { position:absolute; left:30px; top:196px; width:940px; height:235px; z-index:12; }		
		#mostrar-listado { position:absolute; left:200px; top:135px; width:273px; height:160px; z-index:13; }
		#verDepartamentos { position:absolute; left:30px; top:190px; width:940px; height:288px; z-index:13; overflow:scroll; }
		#btn-regresar { position:absolute; left:30px; top:550px; width:960px; height:50px; z-index:14; }
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>    
	<div id="titulo-modificarOrg" class="titulo_barra">Modificar Organigrama</div><?php
	
	//Desplegar los departamentos cuando se le de click al boton de ver Organigrama
	if(isset($_POST['sbt_verOrganigrama'])){ ?>	
		<div id="verDepartamentos" class="borde_seccion2"><?php verDepartamentos(); ?></div>
    	<div id="btn-regresar" align="center">
			<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar a Modificar el Organigrama" onclick="location.href='frm_modificarOrganigrama.php'" />
		</div><?php 
		$ctrlAparacionVentana = 0;
	} 
	
	if($ctrlAparacionVentana==1){ ?>
		<fieldset class="borde_seccion" id="modificar-organigrama" name="modificar-organigrama">	
		<legend class="titulo_etiqueta">Modificar Organigrama de la Organizaci&oacute;n</legend><br>
		<form onsubmit="return valFormModificarOrganigrama(this);" name="frm_modificarOrganigrama" action="frm_modificarOrganigrama.php" method="post">
		<table class="tabla_frm" width="100%" cellpadding="5" cellspacing="5">
			<tr>
				<td width="20%"><div align="right">Departamento</div></td>
				<td width="30%"><?php 
					$paramOnChange ="obtenerNombreCompleto(this.value,'rfc_empleado','nombre','ape_pat','ape_mat','txt_encargadoDepto','bd_recursos','empleados');";
					$datos=cargarComboConId("cmb_departamento","departamento","empleados_rfc_empleado","organigrama","bd_recursos","Departamento","",$paramOnChange);
					if($datos==0){
						echo "<label class='msje_correcto'><u><strong> NO</u></strong> hay Departamentos Registrados</label>
						<input type='hidden' name='cmb_departamento' id='cmb_departamento'/>";
						}  ?>			
				</td>
				<td width="25%" align="right">				
					<input type="checkbox" name="ckb_agregarDepto" id="ckb_agregarDepto" onclick="solicitarNuevoDepto(this,'txt_departamento','cmb_departamento');" />
					Agregar Nuevo Departamento			
			  </td>
				<td width="25%">
					<input type="text" name="txt_departamento" id="txt_departamento" class="caja_de_texto" size="30" maxlength="60" onkeypress="return permite(event,'num_car',0);" readonly="readonly" />			
				</td>
			</tr>
			<tr>
				<td><div align="right">Encargado del Departamento </div></td>
				<td><input type="text" name="txt_encargadoDepto" id="txt_encargadoDepto" class="caja_de_texto" size="45" maxlength="60" onkeypress="return permite(event,'num_car',0);" readonly="readonly" /></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td><div align="right">Asignar Nuevo Encargado</div></td>
				<td>
					<input type="text" name="txt_nomEmpleado" id="txt_nomEmpleado" onkeyup="obtenerNombreRFCEmpleado(this,'empleados','todo','1');" value="" size="45" maxlength="60" 
					onkeypress="return permite(event,'car',0);" class="caja_de_texto" onchange="limpiarCampos(this);" />
					<div id="mostrar-listado">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
				  </div>			
				</td>
				<td><div align="right">RFC Nuevo Encargado </div></td>
				<td>
					<input name="txt_RFCEmpleado" id="txt_RFCEmpleado" type="text" class="caja_de_texto" size="20" maxlength="60" readonly="readonly"/>			
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="2">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="hidden" name="hdn_tipoSentencia" id="hdn_tipoSentencia" value="UPDATE" />
					<input type="submit" name="sbt_modificar" id="sbt_modificar" value="Modificar" class="botones" title="Modificar la Persona Encargada del Departamento" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;
					<input type="submit" name="sbt_eliminar" value="Eliminar" class="botones" title="Eliminar Departamento y Encargado" 
					onmouseover="window.status='';return true" onclick="hdn_tipoSentencia.value='DELETE'" />
					&nbsp;&nbsp;
					<input type="reset" name="rst_limpiar" value="Limpiar" class="botones" title="Borrar los Datos Ingresados en el Formulario" />
					&nbsp;&nbsp;
					<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar al Men&uacute; Administrativo" onclick="location.href='menu_administrativo.php'" />
					&nbsp;&nbsp;
					<input type="submit" name="sbt_verOrganigrama" value="Ver Organigrama" class="botones_largos" title="Ver Organigrama de la Organizaci&oacute;n" 
					onclick="hdn_tipoSentencia.value='SHOW'" />
				</td>			
			</tr>
		</table>
		</form>
		</fieldset>
    <?php } ?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que incluye la operaci�n de consultar y modificar Empleado
		include ("op_modificarEmpleado.php")?>;

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<!-- se anexa este archivo para obtener las funciones necesarias para el control de costos -->
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="includes/ajax/validarCveEmpleado.js"></script>
	
	<script type="text/javascript" src="../../includes/ajax/validarCambioDato.js"></script>
	
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:173px; height:25px; z-index:11; }
		#consultar-empleado {position:absolute; left:30px; top:191px; width:592px; height:150px; z-index:14;}
		#consultar-empleado-baja {position:absolute; left:30px; top:380px; width:592px; height:150px; z-index:13;}
		#res-spider {position:absolute;z-index:15;}
		#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
		#tabla-modificarEmpleado { position:absolute; left:30px; top:190px; width:933px;height:505px; z-index:12; padding:15px; padding-top:0px;}
		#tabla-modificarEmpleado2 { position:absolute; left:30px; top:190px; width:908px; height:403px; z-index:12; padding:15px; padding-top:0px;}
		#calendario {position:absolute;left:590px;top:415px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Modificar Empleado</div>	

	<?php 
	//Verificar que no se haya presionado ningun boton para mostrar el primero formulario
	if (!isset($_POST["sbt_consultar"]) && !isset($_POST["sbt_continuar"]) && !isset($_GET["rfc"]) && !isset($_POST["sbt_modificarEmpleado"]) && !isset($_POST["sbt_consultar_baja"])){
		//Verificar si cancelar esta definido en el GET, de ser asi, se debe verificar que el arreglo datosPersonalesMod este declarado para eliminarlo
		if (isset($_GET["cancelar"])){
			//Si esta denifido el arreglo datosPersonalesMod, eliminarlo, ya que debe desaparecer al cancelar la operacion
			if (isset($_SESSION["datosPersonalesMod"]))
				unset($_SESSION["datosPersonalesMod"]);
		}?>
	
	<fieldset class="borde_seccion" id="consultar-empleado">
		<legend class="titulo_etiqueta">Consultar Trabajador por Nombre</legend>	
		<br>		
		<form onSubmit="return valFormconsultarEmpleadoMod(this);" name="frm_modificarEmpleado" method="post" action="frm_modificarEmpleado.php">
			<table width="587" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="90"><div align="right">&Aacute;rea</div></td>
					<td><?php
						$conn = conecta("bd_recursos");		
						$stm_sql = "SELECT DISTINCT area FROM empleados WHERE estado_actual = 'ALTA' ORDER BY area";
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){?>			
							<select name="cmb_area" id="cmb_area" class="combo_box" onchange="txt_nombreBuscar.value='';lookup(txt_nombreBuscar,'empleados',cmb_area.value,'1');"><?php
							//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value='todo'>Seleccionar</option>";
							do{
								echo "<option value='$datos[area]'>$datos[area]</option>";
							}while($datos = mysql_fetch_array($rs));?>
							</select><?php
						}
						else{
							echo "<label class='msje_correcto'> No hay &Aacute;reas Registradas</label>
							<input type='hidden' name='cmb_area' id='cmb_area'/>";
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>	
					</td>
				</tr>
				<tr valign="top">
				  <td width="90"><div align="right">Trabajador</div></td>
					<td width="462">
						<input type="text" name="txt_nombreBuscar" id="txt_nombreBuscar" onkeyup="lookup(this,'empleados',cmb_area.value,'1');" 
						value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>
				  </td>
				</tr>
				<tr> 
					<td align="center" colspan="2">
						<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" 
						title="Continuar a Llenar el Formulario de Baja"  onmouseover="window.status='';return true" value="Consultar" />&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Empleados"
						onclick="location.href='menu_empleados.php'" />&nbsp;&nbsp;&nbsp;
						<input type="reset" class="botones" value="Reestablecer" title="Borra los criterios de b&uacute;squeda y reestablece el formulario"/>
					</td>
                </tr>
            </table>    
		</form>    			 		
	</fieldset>
	
	<fieldset class="borde_seccion" id="consultar-empleado-baja">
		<legend class="titulo_etiqueta">Consultar Trabajador a dar de ALTA por Nombre</legend>	
		<br>		
		<form onSubmit="return valFormconsultarEmpleadoMod(this);" name="frm_modificarEmpleadoBaja" method="post" action="frm_modificarEmpleado.php">
			<table width="587" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="90"><div align="right">&Aacute;rea</div></td>
					<td><?php
						$conn = conecta("bd_recursos");		
						$stm_sql = "SELECT DISTINCT area FROM empleados WHERE estado_actual = 'BAJA' ORDER BY area";
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){?>			
							<select name="cmb_area_baja" id="cmb_area_baja" class="combo_box" onchange="txt_nombreBuscar_baja.value='';lookup(txt_nombreBuscar_baja,'empleados_baja',cmb_area_baja.value,'2');"><?php
							//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value='todo'>Seleccionar</option>";
							do{
								echo "<option value='$datos[area]'>$datos[area]</option>";
							}while($datos = mysql_fetch_array($rs));?>
							</select><?php
						}
						else{
							echo "<label class='msje_correcto'> No hay &Aacute;reas Registradas</label>
							<input type='hidden' name='cmb_area_baja' id='cmb_area_baja'/>";
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>	
					</td>
				</tr>
				<tr valign="top">
				  <td width="90"><div align="right">Trabajador</div></td>
					<td width="462">
						<input type="text" name="txt_nombreBuscar_baja" id="txt_nombreBuscar_baja" onkeyup="lookup(this,'empleados_baja',cmb_area_baja.value,'2');" 
						value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions2" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList" id="autoSuggestionsList2">&nbsp;</div>
							</div>
						</div>
				  </td>
				</tr>
				<tr>
					<td align="center" colspan="2">
						<input name="sbt_consultar_baja" type="submit" class="botones" id="sbt_consultar_baja" 
						title="Continuar a Llenar el Formulario de Baja"  onmouseover="window.status='';return true" value="Consultar" />&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar al Men&uacute; de Empleados"
						onclick="location.href='menu_empleados.php'" />&nbsp;&nbsp;&nbsp;
						<input type="reset" class="botones" value="Reestablecer" title="Borra los criterios de b&uacute;squeda y reestablece el formulario"/>
					</td>
                </tr>
            </table>    
		</form>    			 		
	</fieldset><?php		
	
	}else{
		//Verificar que txt_nombreBuscar este definido en el POST o que el rfc este definido en el GET
		if (isset($_POST["txt_nombreBuscar"]) || isset($_GET["rfc"]))
			//Mostrar el formulario con los datos personales de Empleado
			mostrarEmpleados1(1);
		else if (isset($_POST["txt_nombreBuscar_baja"]) || isset($_GET["rfc"]))
			//Mostrar el formulario con los datos personales de Empleado
			mostrarEmpleados1(2);
		if (isset($_POST["sbt_continuar"]) || isset($_POST["sbt_continuar_baja"])){
			//Verificar si el boton sbt_continuar esta definido en el POST, de ser asi cargar los datos en la Sesion
			$_SESSION["datosPersonalesMod"]["rfc"]=$_POST["txt_rfc"];
			$_SESSION["datosPersonalesMod"]["rfcOriginal"]=$_POST["hdn_rfc"];
			$_SESSION["datosPersonalesMod"]["nombre"]=$_POST["txt_nombre"];
			$_SESSION["datosPersonalesMod"]["apPat"]=$_POST["txt_apePat"];
			$_SESSION["datosPersonalesMod"]["apMat"]=$_POST["txt_apeMat"];
			$_SESSION["datosPersonalesMod"]["sangre"]=$_POST["txt_sangre"];
			$_SESSION["datosPersonalesMod"]["curp"]=$_POST["txt_curp"];
			$_SESSION["datosPersonalesMod"]["calle"]=$_POST["txt_calle"];
			$_SESSION["datosPersonalesMod"]["num_ext"]=$_POST["txt_numExt"];
			$_SESSION["datosPersonalesMod"]["num_int"]=$_POST["txt_numInt"];
			$_SESSION["datosPersonalesMod"]["cp"]=$_POST["txt_cp"];
			$_SESSION["datosPersonalesMod"]["fecha"]=$_POST["txt_fechaIngreso"];
			$_SESSION["datosPersonalesMod"]["col"]=$_POST["txt_colonia"];
			$_SESSION["datosPersonalesMod"]["estado"]=$_POST["txt_estado"];
			$_SESSION["datosPersonalesMod"]["pais"]=$_POST["txt_pais"];
			$_SESSION["datosPersonalesMod"]["nac"]=$_POST["txt_nacionalidad"];
			$_SESSION["datosPersonalesMod"]["nss"]=$_POST["txt_nss"];
			$_SESSION["datosPersonalesMod"]["edoCivil"]=$_POST["cmb_estado"];
			$_SESSION["datosPersonalesMod"]["obs"]=$_POST["txa_observaciones"];
			$_SESSION["datosPersonalesMod"]["contactoAcc"]=$_POST["txt_contactoAcc"];
			$_SESSION["datosPersonalesMod"]["telCasa"]=$_POST["txt_telCasa"];
			$_SESSION["datosPersonalesMod"]["celular"]=$_POST["txt_celular"];
			$_SESSION["datosPersonalesMod"]["mun_loc"]=$_POST["txt_munLoc"];
			$_SESSION["datosPersonalesMod"]["telTrabajador"]=$_POST["txt_telTrabajador"];
			$_SESSION["datosPersonales"]["lugarNac"]=$_POST["txt_lugarNac"];
			//Datos Formato DC-4
			$_SESSION["datosPersonalesMod"]["discapacidad"]=$_POST["cmb_tipoDisc"];
			$_SESSION["datosPersonalesMod"]["hijosDepEco"]=$_POST["txt_depEco"];
			//Datos Academicos
			$_SESSION["datosPersonalesMod"]["nivEstudios"]=$_POST["cmb_nivEstudios"];
			$_SESSION["datosPersonalesMod"]["titulo"]=$_POST["cmb_docObtenido"];
			$_SESSION["datosPersonalesMod"]["carrera"]=$_POST["txt_carrera"];
			$_SESSION["datosPersonalesMod"]["tipoEscuela"]=$_POST["cmb_institucion"];
			//Control de costos
			$_SESSION["datosPersonales"]["control_cos"]=$_POST["cmb_con_cos"];
			$_SESSION["datosPersonales"]["cuentas"]=$_POST["cmb_cuenta"];
			//Datos de alimentos
			$_SESSION["datosPersonales"]["alimento"]=$_POST["cmb_alimento"];
			//Mostrar el formulario con los datos laborales de Empleado
			mostrarEmpleados2();
		}
		//Verificar que se haya presionado el boton que indica que los cambios se realizaran en la funcion modificarEmpleado()
		if (isset($_POST["sbt_modificarEmpleado"])){
			modificarEmpleado();
		}
	}?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
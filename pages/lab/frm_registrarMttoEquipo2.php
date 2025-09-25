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
		//Manejo de la funciones para Registrar los datos de los equipos que se manejan en el Laboratorio
		include ("op_registrarMttoEquipo.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarInformacionEquipo {position:absolute;left:30px;top:190px;width:847px;height:323px;z-index:14;}
		#calendario_fechaColado {position:absolute;left:968px;top:305px;width:30px;height:26px;z-index:14;}
		#detalle-registroMtto {position:absolute;left:36px;top:544px;width:834px;height:128px;z-index:17;overflow:scroll;}
		#btn-finalizar {position:absolute;left:32px;top:680px;width:987px;height:40px;z-index:9;}
		#calendario_fechaRegistro {position:absolute;left:246px;top:294px;width:30px;height:26px;z-index:14;}

		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Registrar  Servicios</div><?php
	

	//Mensaje que se muestra cuando un registro se agrega por segunda ocasion 
	$msgMttoDuplicado = "";
	//Cuando se de clic en el boton de finalizar que se guarden los registros seleccionados
	$aparicionForm = 0;
	
	//Cuando se de clic en el boton de finalizar que se guarden los registros seleccionados
	if(isset($_POST['sbt_finalizar'])){		
		
		echo "<meta http-equiv='refresh' content='0;url=frm_cargarFotoEquipoLab.php'>";

		//Valida la  aparicion del formulario 
		$aparicionForm = 1;
	}

	//Si esta definido los nombres de los siguientes botones que muestre cada uno de los mantenimientos
	if(isset($_POST['sbt_agregar'])){			
		//Cargar datos a la SESSION
		if(isset($_SESSION['datosRegistroMtto'])){
	
			//Comprobar que dentro de la tabla donde se registran servicios de mantenimiento no se observen registros duplicados dentro de dicha tabla para el mismo registro de equipo
			$regDuplicado = 0;
			foreach($_SESSION['datosRegistroMtto'] as $ind => $registro){
				$fechaRegistro = $_POST['txt_fechaRegistro'];
				if($fechaRegistro==$registro['fechaRegistro'] && $_POST['cmb_servicioMtto']==$registro['servicioMtto']){
					$regDuplicado = 1;
					break;	
				}
			}
			
			if($regDuplicado==0){
				$_SESSION['datosRegistroMtto'][] = array("fechaRegistro"=>$_POST['txt_fechaRegistro'],"detalleServicio"=>$_POST['txa_detalleServicio'],
														"encargadoMtto"=>$_POST['txt_encargadoMtto'],"servicioMtto"=>$_POST['cmb_servicioMtto']);							
			}
			else{
				//Declarar variable que va a almacenar el mensaje cuando ya exista un registro de Mtto para ese Equipo
				$msgMttoDuplicado = "El Equipo $_POST[txt_instrumento] ya tiene un Servicio Registrado de $_POST[cmb_servicioMtto] en la Fecha ".$_POST['txt_fechaRegistro'];
			}					
		}//Cierre if(isset($_SESSION['datosRegistroMtto']))											
		else{//Si no esta definido el Arreglo de datosRegistroMtto, definirlo y agregar el primer elemento						
			$_SESSION['datosRegistroMtto'] = array(array("fechaRegistro"=>$_POST['txt_fechaRegistro'],"detalleServicio"=>$_POST['txa_detalleServicio'],
														"encargadoMtto"=>$_POST['txt_encargadoMtto'],"servicioMtto"=>$_POST['cmb_servicioMtto']));
		}			
	}//Cierre if(isset($_POST['sbt_agregar']))
	
	
	//Verificamos que se haya pulsado el boton de continuar para proceder a Extraer los datos de la BD y subirlos a la SESSION
	if(isset($_POST['sbt_continuar'])){
		//Liberar los datos de la SESSION en el Caso que aun existan
		if(isset($_SESSION['datosEquiposLab'])){
			unset($_SESSION['datosEquiposLab']);
		}
		
		//Obtener los Datos del Equipo y subirlos a la SESSION
		$_SESSION['datosEquiposLab'] = obtenerDatosEquipo();
	}
	

	if($aparicionForm==0){?>
		<fieldset class="borde_seccion" id="tabla-agregarInformacionEquipo" name="tabla-agregarInformacionEquipo">
		<legend class="titulo_etiqueta">Agregar Información del Servicio de Mantenimiento
		</legend>	
		<form onSubmit="return valFormRegistrarInformacionMtto(this);" name="frm_registrarInformacionMtto" method="post" action="frm_registrarMttoEquipo2.php">
		<table width="862" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">N&deg; Interno</div></td>
				<td width="180">
					<input type="text" name="txt_numInterno" id="txt_numInterno" 
					value="<?php 
						if(isset($_SESSION['datosEquiposLab'])){ 
							echo $_SESSION['datosEquiposLab']['no_interno'];
						}
						else{ 
							 echo $_SESSION['datosEquipoAlerta']['idEquipo'];
						}?>" size="5" maxlength="4" readonly="readonly" />		  
				</td>
				<td><div align="right">N&deg; Serie </div></td>
				<td width="351">
					<input type="text" name="txt_numSerie" id="txt_numSerie" value="<?php 
						if(isset($_SESSION['datosEquiposLab'])){ 
							echo $_SESSION['datosEquiposLab']['no_serie'];
						}
						else{ 
							 echo $_SESSION['datosEquipoAlerta']['noSerie'];
						}?>" size="15" maxlength="4" readonly="readonly" />		  
				</td>
			</tr>
			<tr>
				<td><div align="right">Marca </div></td>
				<td><input type="text" name="txt_marca" id="txt_marca" value="<?php 
					if(isset($_SESSION['datosEquiposLab'])){ 
							echo $_SESSION['datosEquiposLab']['marca'];
						}
						else{ 
							 echo $_SESSION['datosEquipoAlerta']['marca'];
						}?>" size="30" maxlength="30" readonly="readonly" />
				</td>
				<td><div align="right">Instrumento</div></td>
				<td>
					<input type="text" name="txt_instrumento" id="txt_instrumento" value="<?php 
						if(isset($_SESSION['datosEquiposLab'])){ 
							echo $_SESSION['datosEquiposLab']['nombre'];
						}
						else{ 
							 echo $_SESSION['datosEquipoAlerta']['nombre'];
						}?>" 
					size="30" maxlength="30" readonly="readonly"/>			
				</td>
			</tr>
			<tr>
				<td><div align="right">*Fecha Registro </div></td>
				<td><input type="text" name="txt_fechaRegistro" id="txt_fechaRegistro" value="<?php echo date("d/m/Y");?>"  size="10" readonly="readonly"/></td>
				<td width="143"><div align="right">Tipo de Servicio Programado </div></td>
				<td>
					<input type="text" name="txt_servicioProgramado" id="txt_servicioProgramado" size="40" maxlength="60" 
					value="<?php 
					if(isset($_SESSION['datosEquiposLab'])){ 
						echo $_SESSION['datosEquiposLab']['tipo_servicio'];
					}
					else{ 
						echo $_SESSION['datosEquipoAlerta']['tipoServicio'];
					}?>" 
					readonly="readonly" />
				</td>
			</tr>
			<tr>
				<td><div align="right">*Encargado de Mantenimiento </div></td>
				<td><input type="text" name="txt_encargadoMtto" id="txt_encargadoMtto" size="32" maxlength="60" 
					onkeypress="return permite(event,'num_car',0);" value="ING. EDGAR ALAN GARCÍA CRUZ" ondblclick="this.value='';" /></td>
				<td><div align="right">*Detalle de Servicio </div></td>
				<td>
					<textarea name="txa_detalleServicio" id="txa_detalleServicio" class="caja_de_texto" cols="60" rows="3" maxlength="300" 
					onkeyup="return ismaxlength(this)" onkeypress="return permite(event,'num_car',0);"></textarea>
				</td>
			</tr>  
			<tr>
				<td><div align="right">*Tipo Servicio</div></td>
				<td>
					<select name="cmb_servicioMtto" id="cmb_servicioMtto" class="combo_box">
						<option value="" selected="selected">Tipo Servicio</option>
						<option value="CAMBIO DE PIEZAS">CAMBIO DE PIEZAS</option>
						<option value="ENGRASADO">ENGRASADO</option>
						<option value="LIMPIEZA GENERAL">LIMPIEZA GENERAL</option>			
						<option value="FUNCIONAMIENTO">FUNCIONAMIENTO</option>
						<option value="FUNCIONES DAÑADAS">FUNCIONES DAÑADAS</option>
						<option value="SISTEMA ELECTRICO">SISTEMA EL&Ecirc;CTRICO</option>
						<option value="OBSERVACIONES">OBSERVACIONES</option>
					</select>			
				</td>
				<td colspan="2" rowspan="2"><label class="msje_correcto"><?php echo $msgMttoDuplicado; ?></label></td>								
			</tr>     
			<tr>
				<td colspan="2"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>       
			</tr>
			<tr>
				<td colspan="6">
					<div align="center">
					<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
					<?php 
					if(isset($_SESSION['datosRegistroMtto'])){?>
						<input name="sbt_finalizar" type="submit" class="botones" value="Finalizar" 
						title="Al Terminar el registro del Servicio de la Información Cargar las Fotografias Correspondientes"
						onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_finalizar'"   />
					&nbsp;&nbsp;&nbsp;<?php // onclick="location.href='menu_mezclas.php';"
					}?>					
					<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar para Registrar Materiales" 
					onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_agregar'"  />
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
					onmouseover="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Equipos de Laboratorio " 
					onmouseover="window.status='';return true" onclick="confirmarSalida('menu_equipoLaboratorio.php');" />
					</div>			
				</td>
			</tr>
		</table>
		</form>
</fieldset><?php		
	}//Cierre de if($aparicionForm==0)?>

	<div id="calendario_fechaRegistro">
		<input type="image" name="txt_fechaRegistro" id="txt_fechaRegistro" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_registrarInformacionMtto.txt_fechaRegistro,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
		title="Seleccionar Fecha de Registro"/>
</div><?php					
	
	
				
	//Si el arreglo de datosRegistroMtto esta en la SESSION, entonces mostramos su contenido		
	if(isset($_SESSION['datosRegistroMtto'])){?>
		<div id='detalle-registroMtto' class='borde_seccion2' align="center"><?php
			mostrarRegistroMtto();?>
		</div>
			<?php	
	}?>
			
</body>
<?php 
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		include ("op_programarMttoEquipo.php");?>

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
		#tabla-agregarInformacionEquipo {position:absolute;left:30px;top:190px;width:823px;height:271px;z-index:14;}
		#calendario_fechaColado {position:absolute;left:968px;top:305px;width:30px;height:26px;z-index:14;}
		#detalle-mantenimiento {position:absolute;left:35px;top:501px;width:819px;height:171px;z-index:17;overflow:scroll;}
		#btn-finalizar {position:absolute;left:32px;top:680px;width:987px;height:40px;z-index:9;}

		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Programar Servicios</div><?php
	//Mensaje que se muestra cuando un registro se agrega por segunda ocasion 
	$msgMttoDuplicado = "";
	//Cuando se de clic en el boton de finalizar que se guarden los registros seleccionados
	$aparicionForm = 0;
	
	/*Guardar los Servicios Programados en la BD*/
	if(isset($_POST['sbt_finalizar'])){
		//Guardar los registros en la BD
		guardarProgramaMtto();	
		//Valida la  aparicion del formulario 
		$aparicionForm = 1;
	}
	
	
	
	//Si esta definido los nombres de los siguientes botones que muestre cada uno de los mantenimientos
	if(isset($_POST['sbt_agregar'])){		
		//Dentro de esta comprobación se revisa que no se asignen servicios de mantenimientos y fechas duplicadas a un equipo de laboratorio en particular
		if(revisarMttoAsignados()){
			//Cargar datos a la SESSION
			if(isset($_SESSION['datosMtto'])){
				//Comprobar que dentro de la tabla donde se muestra el programa de servicios de mantenimiento no se observen registros duplicados dentro de dicha tabla para el mismo registro de equipo
				$regDuplicado = 0;
				foreach($_SESSION['datosMtto'] as $ind => $registro){
					$fechaRegistro = $_POST['cmb_Mes']." DE ".$_POST['cmb_Anio'];
					if($fechaRegistro==$registro['fechaMtto'] && $_POST['rdb_tipoServicio']==$registro['tipoServicio']){
						$regDuplicado = 1;
						break;	
					}
					
				}
				
				if($regDuplicado==0){
					$_SESSION['datosMtto'][] = array("fechaMtto"=>$_POST['cmb_Mes']." DE ".$_POST['cmb_Anio'],"tipoServicio"=>$_POST['rdb_tipoServicio']);
				}
				else{
					//Declarar variable que va a almacenar el mensaje cuando ya exista un registro de Mtto para ese Equipo
					$msgMttoDuplicado = "El Equipo $_POST[txt_numInterno] ya tiene un Servicio de $_POST[rdb_tipoServicio] en la Fecha ".$_POST['cmb_Mes']." DE ".$_POST['cmb_Anio'];
				}
			}//Cierre if(isset($_SESSION['datosMtto']))
			else{//Si no esta definido el Arreglo de datosMtto, definirlo y agregar el primer elemento						
				$_SESSION['datosMtto'] = array(array("fechaMtto"=>$_POST['cmb_Mes']." DE ".$_POST['cmb_Anio'],"tipoServicio"=>$_POST['rdb_tipoServicio']));			
			}	
		}//Cierre if(revisarMttoAsignados())
		else{
			//Declarar variable que va a almacenar el mensaje cuando ya exista un registro de Mtto para ese Equipo
			$msgMttoDuplicado = "El Equipo $_POST[txt_numInterno] ya tiene un Servicio de $_POST[rdb_tipoServicio] en la Fecha ".$_POST['cmb_Mes']." DE ".$_POST['cmb_Anio'];
		}
	}//if(isset($_POST['sbt_agregar']))
	
	
	
	//Verificamos que se haya pulsado el boton de continuar para proceder a Extraer los datos de la BD y subirlos a la SESSION
	if(isset($_POST['sbt_continuar'])){
		$_SESSION['datosEquiposLab'] = obtenerDatosEquipo();
	}
	
	
	
	if($aparicionForm==0){?>
		<fieldset class="borde_seccion" id="tabla-agregarInformacionEquipo" name="tabla-agregarInformacionEquipo">
		<legend class="titulo_etiqueta">Agregar Información del Equipo</legend>	
		<br>
		<form onSubmit="return valFormAgregarInformacionEquipo(this);" name="frm_agregarInformacionEquipo" method="post" action="frm_programarMttoEquipo2.php">
		<table width="786" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="84"><div align="right">N&deg; Interno</div></td>
				<td width="248"><input type="text" name="txt_numInterno" id="txt_numInterno" value="<?php echo $_SESSION['datosEquiposLab']['no_interno'];?>" size="5" maxlength="4" readonly="readonly"/></td>
				<td><div align="right">N&deg; Serie </div></td>
				<td width="274"><input type="text" name="txt_numSerie" id="txt_numSerie" value="<?php echo $_SESSION['datosEquiposLab']['no_serie'];?>" size="20" maxlength="20" readonly="readonly"/></td>
			</tr>
			<tr>
				<td><div align="right">Marca </div></td>
				<td><input type="text" name="txt_marca" id="txt_marca" value="<?php echo $_SESSION['datosEquiposLab']['marca'];?>" size="30" maxlength="30" readonly="readonly"/></td>
				<td><div align="right">Instrumento</div></td>
				<td><input type="text" name="txt_instrumento" id="txt_instrumento" value="<?php echo $_SESSION['datosEquiposLab']['nombre'];?>" size="50" maxlength="50" readonly="readonly"/></td>
			</tr>
			<tr>
				<td><div align="right">*Mes</div></td>
				<td>
					<select name="cmb_Mes" class="combo_box">
						<option value="">Mes</option>
						<option value="ENERO">Enero</option>
						<option value="FEBRERO">Febrero</option>
						<option value="MARZO">Marzo</option>
						<option value="ABRIL">Abril</option>
						<option value="MAYO">Mayo</option>
						<option value="JUNIO">Junio</option>
						<option value="JULIO">Julio</option>
						<option value="AGOSTO">Agosto</option>
						<option value="SEPTIEMBRE">Septiembre</option>
						<option value="OCTUBRE">Octubre</option>
						<option value="NOVIEMBRE">Noviembre</option>
						<option value="DICIEMBRE">Diciembre</option>
					</select>
				</td>
				<td width="113"><div align="right">*A&ntilde;o</div></td>
			  	<td>
					<select name="cmb_Anio" class="combo_box">
				  		<option value="">A&ntilde;o</option><?php
						//Obtener el Año Actual
						$anioInicio = intval(date("Y")) - 10;
							for($i=0;$i<21;$i++){
								echo "<option value='$anioInicio'>$anioInicio</option>";
								$anioInicio++;
							}?>
					</select>
				</td>
			</tr>  
			<tr>
			  	<td><div align="right">*Tipo de Servicio </div></td>
				<td>
					<input name="rdb_tipoServicio" type="radio" id="rdb_tipoServicio" value="CALIBRACION" <?php $flag=obtenerDato("bd_laboratorio","equipo_lab","calibrable","no_interno",
						$_SESSION['datosEquiposLab']['no_interno']); if($flag==0) echo "disabled='disabled'"; ?>/>CALIBRACI&Oacute;N
					<br />
					<input name="rdb_tipoServicio" type="radio" id="rdb_tipoServicio" value="MANTENIMIENTO" />MANTENIMIENTO
				</td>
				<td colspan="2" align="left" valign="top">
					<strong>* Datos marcados con asterisco son <u>obligatorios</u></strong>
					<br />
					<label class="msje_correcto"><?php echo $msgMttoDuplicado; ?></label>
				</td>
			</tr> 
			<tr>
				<td height="53" colspan="6">
					<div align="center">
					<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" /><?php 
					if(isset($_SESSION['datosMtto'])){?>
						<input name="sbt_finalizar" type="submit" id="sbt_finalizar" class="botones" value="Finalizar" title="Terminar de Registrar Información de los Equipos de Laboratorio"
						onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='sbt_finalizar'" />
						&nbsp;&nbsp;&nbsp;<?php
					}?>					
					<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar para Programar el Servicio de Mantenimiento" 
					onmouseover="window.status='';return true"  onclick="hdn_botonSeleccionado.value='sbt_agregar'" />
					 
					
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
	}//Cierre de if($aparicionForm==0)	
		
	//Si el arreglo de datosMtto esta en la SESSION, entonces mostramos su contenido		
	if(isset($_SESSION['datosMtto'])){?>
		<div id='detalle-mantenimiento' class='borde_seccion2' align="center"><?php
			mostrarProgramaMtto();?>
		</div><?php 
	}?>
	
		
</body><?php 
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
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
		include ("op_modificarPlanContingencia.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>

	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:270px;	height:20px;	z-index:11;}
		#tabla-archivoContingencia{position:absolute;left:28px;top:360px;width:936px;height:127px;z-index:20;}
		#tabla-plaContingencia{position:absolute;left:26px;top:190px;width:941px;height:135px;z-index:12;}
		#calendario-Ini {position:absolute;left:552px;top:228px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:887px;top:229px;width:30px;height:26px;z-index:14;}
		#archivoPlanContingencia{position:absolute; left:883px; top:218px; width:30px; height:26px; z-index:19; }	
		#tabla-resultados {position:absolute;left:32px;top:193px;width:896px;height:336px;z-index:15;overflow:scroll;}
		
		#tabla-pasos { position:absolute; left:32px; top:522px; width:928px; height:145px; z-index:17; overflow:scroll; }
		#pasosPlanContingencia{position:absolute; left:69px; top:292px; width:1071px; height:154px; z-index:18;  }	
		#tabla-pasosContingencia{position:absolute;left:28px;top:360px;width:936px;height:127px;z-index:20; }	
	-->
    </style>
</head>
<body><?php

	/*Recuperamos el id del Plan de Contingencia Seleccionado, el cual puede venir en el POST o en el GET
	 * 1. $_POST['rdb_idPlanContingencia'] => Viene de la Pagina donde se selecciona el Plan
	 * 2. $_POST['txt_idPlan'] => Viene cuando se agrega un nuevo paso al plan
	 * 3. $_GET['clavePlan'] => Viene cuando se borro un registro de la SESSION (Pasos del Plan)*/
	
	$clavePlan = "";
	if(isset($_POST['rdb_idPlanContingencia'])){
		$clavePlan = $_POST['rdb_idPlanContingencia'];
	}
	if(isset($_POST['txt_idPlan'])){
		$clavePlan = $_POST['txt_idPlan'];
	}
	if(isset($_GET['clavePlan'])){
		//Obtener la Clave del Plan
		$clavePlan = $_GET['clavePlan'];
		
		//Obtener el No de Registro que será borrado de la SESSION
		$noReg = $_GET['noRegistro'];		
		//Vaciar la posicion
		unset($_SESSION['datosPlanContingencia'][$noReg]);
		//Rectificar los indices
		$_SESSION['datosPlanContingencia'] = array_values($_SESSION['datosPlanContingencia']);		
	}		
	
	
	
	//Si no hay registros de los pasos del Plan y de los Datos Generales del Plan en la SESSION, cargar los obtenidos de la Base de Datos	
	if(!isset($_SESSION["datosPlanContingencia"]) && !isset($_SESSION["datosGralPlan"])){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
				
		//Extraemos el primer Registro del ResultSet para obtener los datos generales del PLAN 
		$datos_gralPlan = mysql_fetch_array(mysql_query("SELECT * FROM planes_contingencia WHERE id_plan = '$clavePlan'"));
		
		//Creamos el arreglo para guardar los Datos Generales del Plan
		$datosGralPlan = array();
		//Guardamos los valores resultantes de la primer consulta para trabajar posteriormente con ellos, estos resultados se obtienen directamente desde la BD		
		$datosGralPlan['resSim'] = $datos_gralPlan['responsable'];
		$datosGralPlan['area'] = $datos_gralPlan['area'];
		$datosGralPlan['lugar'] = $datos_gralPlan['lugar'];
		$datosGralPlan['nomSim'] = $datos_gralPlan['nom_simulacro'];
		$datosGralPlan['tipoSim'] = $datos_gralPlan['tipo_simulacro'];
		$datosGralPlan['fechaReg'] = modFecha($datos_gralPlan['fecha_reg'],1);
		$datosGralPlan['fechaProg'] = modFecha($datos_gralPlan['fecha_programada'],1);			
		$datosGralPlan['datosMod'] = "no";
		
		//Guardar Datos Generale del Plan en la SESSION
		$_SESSION['datosGralPlan'] = $datosGralPlan;			
								
		
		//Crear consulta para verificar si el plan de contingencia tiene asociado un archivo o tienen pasos registrados
		$documento = mysql_fetch_array(mysql_query("SELECT id_documento FROM detalle_contingencia WHERE planes_contingencia_id_plan = '$clavePlan'"));
		//Verificar si el plan seleccionado tiene un documento asociado	
		if($documento['id_documento']=="N/A"){
			
			//Creamos el arreglo para guardar el Detalle de los Pasos del Plan
			$datosPlanContingencia = array();
			
			//Extraemos el primer Registro del ResultSet para obtener los datos generales del PLAN 
			$rs = mysql_query("SELECT * FROM detalle_contingencia WHERE planes_contingencia_id_plan = '$clavePlan'");
			$detalle_gralPlan = mysql_fetch_array($rs);
			
			//Recorremos para guardar los registros en las posiciones indicadas
			do{	
				$datosPlanContingencia[]=array("paso"=>$detalle_gralPlan['paso'], "accion"=>$detalle_gralPlan['accion'], "resAccion"=>$detalle_gralPlan['responsable'], 
												"simulacro"=>$detalle_gralPlan['simulacro'], "comentarios"=>$detalle_gralPlan['comentarios']);		
			}while($detalle_gralPlan=mysql_fetch_array($rs));
			//Guardamos en la session el arreglo previamente creado
			$_SESSION["datosPlanContingencia"] = $datosPlanContingencia;//Cierre if($datos=mysql_fetch_array($rs)						
		}			
		else{	
			//Extraemos la clave del documento que esta vinculado al plan de contingencia y el cual puede ser modificado
			$rs = mysql_query("SELECT id_documento FROM detalle_contingencia WHERE planes_contingencia_id_plan = '$clavePlan'");
			$detalle_gralPlan = mysql_fetch_array($rs);
			$_SESSION['nomArchivoCargado'] = $detalle_gralPlan['id_documento'];
			
		}						
		
	}//Cierre if(!isset($_SESSION["datosPlanContingencia"]))

	
	//Cuando se de click al boton de modificar, guardar los datos generales del Plan en la SESSION
	if(isset($_POST['sbt_modificarPlan'])){
		//Recuperar los datos del POST y almacenarlos en la SESSION
		$_SESSION['datosGralPlan']['resSim'] = strtoupper($_POST['txt_resSim']);
		$_SESSION['datosGralPlan']['area'] = strtoupper($_POST['txt_area']);
		$_SESSION['datosGralPlan']['lugar'] = strtoupper($_POST['txt_lugar']);
		$_SESSION['datosGralPlan']['nomSim'] = strtoupper($_POST['txt_nomSimulacro']);				
		$_SESSION['datosGralPlan']['tipoSim'] = strtoupper($_POST['txt_tipoSimulacro']);
		$_SESSION['datosGralPlan']['fechaReg'] = $_POST['txt_fechaReg'];
		$_SESSION['datosGralPlan']['fechaProg'] = $_POST['txt_fechaProg'];
		//Inidicar que los datos de la SESSION fueron modificados
		$_SESSION['datosGralPlan']['datosMod'] = "si";
		
	}

	//Verificamos si fue presionado el boton  'sbt_modificarPlan' para que se actualice la información
	if(isset($_POST['sbt_finalizar'])){
		modificarPlanContingencia();
		unset($_SESSION['datosPlanContingencia']);
	}

		if(isset($_POST['sbt_finalizarArchivo'])){
			modificarArchivoPlanContingencia();
	}
	
	
					
	//Agregar pasos al Plan, los cuales se encuentran almacenados en la SESSION
	if(isset($_POST["sbt_agregar"])){	
	/***************************************Validar que el Usuario NO pueda agregar un registro igual************************************************/
		//Esta variable indica si el registro esta repetido o no
			$datoRep = 0;
			if(isset($_SESSION['datosPlanContingencia'])){
				//Verificar que el registro no este repetido en el Arreglo de SESSION
				foreach($_SESSION["datosPlanContingencia"] as $ind => $registro){
					if(strtoupper($_POST["txt_paso"])==$registro["paso"]){
						$datoRep = 1;
						break;
					}//Cierre if(strtoupper($_POST["txt_paso"])==$registro["paso"]){
				}//foreach($_SESSION["datosPlanContingencia"] as $ind => $registro){
			}		
			if($datoRep!=1){	
				/******************************************************************************************************************/
				//Si ya esta definido el arreglo, entonces agregar el siguiente registro a el
				if(isset($_SESSION['datosPlanContingencia'])){				
					//Guardar los datos en el arreglo
					$_SESSION['datosPlanContingencia'][] = array("paso"=>strtoupper($_POST['txt_paso']),
													"accion"=>strtoupper($_POST['txt_accion']),
													"simulacro"=>strtoupper($_POST['txt_simulacro']), 
													"resAccion"=>strtoupper($_POST['txt_resAccion']), 
													"comentarios"=>strtoupper($_POST['txa_comentarios']));			
					//Contamos el arreglo para conocer el numero de actividad
					$tam = count($_SESSION['datosPlanContingencia']);	
				}//Cierre de if(isset($_SESSION['datosPlanContingencia']))			
				//Si no esta definido el arreglo definirlo y agregar el primer registro
				else{	
					$tam = 1;
					$cont = 0;		
					//Guardar los datos en el arreglo
					$datosPlanContingencia = array( array("paso"=>strtoupper($_POST['txt_paso']),
							"accion"=>strtoupper($_POST['txt_accion']),
							"simulacro"=>strtoupper($_POST['txt_simulacro']), 
							"resAccion"=>strtoupper($_POST['txt_resAccion']), 
							"comentarios"=>strtoupper($_POST['txa_comentarios'])));
					
					$_SESSION['datosPlanContingencia'] = $datosPlanContingencia;	
				}//Cierre del else
		} // Cierre de if($datoRep!=1){	
		else{?>
			<script type="text/javascript" language="javascript">
				setTimeout("alert('La Descripción del Paso Ingresado ya se encuentra Registrado; Agregue uno Diferente')", 500);
			</script><?php
			}	
	}//Cierre if(isset($_POST["sbt_agregar"]))
				
	//Desplegar los Registros de los Pasos del Plan de Contingencia Asociados cuando al menos uno haya sido agregado a la SESSION
	if(isset($_SESSION['datosPlanContingencia'])){
		echo "<div id='tabla-pasos' class='borde_seccion2' align='center'>";
			mostrarRegPlanContingencia($clavePlan);
			
		echo "</div>";
	}?>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-seleccionar">Modificar Planes Contingencia </div>
 	
	
	
	<fieldset class="borde_seccion" id="tabla-plaContingencia" name="tabla-plaContingencia">
		<legend class="titulo_etiqueta">Ingresar los Datos del Plan de Contingencia</legend><br />
		<form onsubmit="return valFormDatosPlan(this);" name="frm_modificarDatosPlan" method="post" action="frm_modificarPlanContingencia2.php">		
			<table width="100%" class="tabla_frm" cellpadding="4">
				<tr>
				  <td width="16%"><div align="right">Id Plan</div></td>
					<td width="18%">
						<input type="text" name="txt_idPlan" id="txt_idPlan" maxlength="10" size="10" class="caja_de_texto" 
						value="<?php echo $clavePlan?>" onkeypress="return permite(event,'num',1);" readonly="readonly" />
				  </td>
					<td width="14%"><div align="right">Fecha Registro</div></td>
					<td width="17%">
						<input name="txt_fechaReg" id="txt_fechaReg" readonly="readonly" type="text" value="<?php echo $_SESSION['datosGralPlan']['fechaReg']; ?>" 
						size="10"  width="90"/>
				  </td>
				  <td width="18%"><div align="right">*Fecha Programada </div></td>
					<td width="17%">
						<input name="txt_fechaProg" id="txt_fechaProg" readonly="readonly" type="text" value="<?php echo $_SESSION['datosGralPlan']['fechaProg']; ?>" 
						size="10"  width="90"/>
				  </td>	 
				</tr>
				<tr>
					<td><div align="right">*Responsable</div></td>
					<td>
						<input type="text" name="txt_resSim" id="txt_resSim" maxlength="60" size="30" class="caja_de_texto" 
						value="<?php echo $_SESSION['datosGralPlan']['resSim']; ?>" onkeypress="return permite(event,'num_car',7);"
						<?php if($_SESSION['datosGralPlan']['datosMod']=="si"){?> readonly="readonly" <?php }?>  />
					</td>
					<td><div align="right">*&Aacute;rea</div></td>
					<td>
						<input name="txt_area" type="text" class="caja_de_texto" id="txt_area" 
						onkeypress="return permite(event,'num_car',8);" value="<?php echo $_SESSION['datosGralPlan']['area']; ?>" size="30" maxlength="80" 
						<?php if($_SESSION['datosGralPlan']['datosMod']=="si"){?>  readonly="readonly"<?php }?> />
					</td>
					<td><div align="right">* Lugar</div></td>
					<td>
						<input type="text" name="txt_lugar" id="txt_lugar" maxlength="80" size="30" class="caja_de_texto" 
						value="<?php echo $_SESSION['datosGralPlan']['lugar']; ?>" onkeypress="return permite(event,'num_car',8);"
						<?php if($_SESSION['datosGralPlan']['datosMod']=="si"){?> readonly="readonly" <?php }?> />
					</td>					
				</tr>
				<tr>
					<td><div align="right">*Nombre Simulacro</div></td>
					<td>
						<input type="text" name="txt_nomSimulacro" id="txt_nomSimulacro" maxlength="60" size="30" class="caja_de_texto" 
						value="<?php echo $_SESSION['datosGralPlan']['nomSim']; ?>" onkeypress="return permite(event,'num_car',7);"
						<?php if($_SESSION['datosGralPlan']['datosMod']=="si"){?> readonly="readonly" <?php }?> />
					</td>
					<td><div align="right">*Tipo Simulacro</div></td>
					<td>
						<input type="text"  name="txt_tipoSimulacro" id="txt_tipoSimulacro" maxlength="60" size="30" class="caja_de_texto" 
						value="<?php echo $_SESSION['datosGralPlan']['tipoSim']; ?>" onkeypress="return permite(event,'num_car',7);"
						<?php if($_SESSION['datosGralPlan']['datosMod']=="si"){?> readonly="readonly" <?php }?> />
					</td>
					<td colspan="2" align="center"><?php
						if($_SESSION['datosGralPlan']['datosMod']=="no"){?>
							<input name="sbt_modificarPlan" type="submit" class="botones" id="sbt_modificarPlan"  value="Modificar" 
							title="Modificar Datos del Plan" onmouseover="window.status='';return true" /><?php
						}?>						
					</td>
				</tr>
			</table>
		</form>
	</fieldset><?php 
		
	
	if($_SESSION['datosGralPlan']['datosMod']=="no"){?>	
		<div id="calendario-Ini">
			<input type="image" name="txt_fechaReg" id="txt_fechaReg" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modificarDatosPlan.txt_fechaReg,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Registro"/> 
		</div>	
		<div id="calendario-Fin">
			<input type="image" name="txt_fechaProg" id="txt_fechaProg" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modificarDatosPlan.txt_fechaProg,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Programaci&oacute;n"/> 
		</div><?php 
	} 
	
	
	if(isset($_SESSION["datosGralPlan"]) && isset($_SESSION["datosPlanContingencia"])){ ?>	
		<fieldset class="borde_seccion" id="tabla-pasosContingencia" name="tabla-pasosContingencia">
		<legend class="titulo_etiqueta">Acciones ó Pasos a Realizar</legend>
		<form onsubmit="return valFormPlanContingencia(this);" name="frm_modificarPlanContingencia" method="post" action="frm_modificarPlanContingencia2.php">
			<input type="hidden" name="txt_idPlan" id="txt_idPlan" value="<?php echo $clavePlan?>" />
			<table width="100%" class="tabla_frm">
				<tr>
					<td><div align="right">*Paso</div></td>
					<td>
						<input type="text" name="txt_paso" id="txt_paso" maxlength="120" size="30" class="caja_de_texto" 
						value="" onkeypress="return permite(event,'num_car',0);"/>					</td>
					<td><div align="right">*Acci&oacute;n</div></td>
					<td>
						<input type="text" name="txt_accion" id="txt_accion" maxlength="120" size="30" class="caja_de_texto" 
						value="" onkeypress="return permite(event,'num_car',7);" />					</td>
					<td><div align="right">* Simulacro</div></td>
					<td>
						<input type="text" name="txt_simulacro" id="txt_simulacro" maxlength="160" size="30" class="caja_de_texto" 
						value="" onkeypress="return permite(event,'num_car',7);" />					</td>
				</tr>
				<tr>
					<td><div align="right">*Responsable Acci&oacute;n</div></td>
					<td><input type="text" name="txt_resAccion" id="txt_resAccion" maxlength="60" size="30" class="caja_de_texto" 
						value="" onkeypress="return permite(event,'num_car',7);"/></td>		
					<td><div align="right">Comentarios</div></td>
					<td>
						<textarea name="txa_comentarios" cols="40" rows="3" class="caja_de_texto" id="txa_comentarios"  
						onkeypress="return permite(event,'num_car',0);" maxlength="120" onkeyup="return ismaxlength(this)"  ></textarea>					</td>   
				</tr>
				<tr>
					<td colspan="6" align="center">
						<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value=""/>
						
						<input name="sbt_finalizar" type="submit" class="botones" id="sbt_finalizar"  value="Finalizar" 
						title="Finalizar el Registro del Plan de Contingencia Generado" onmouseover="window.status='';return true" onclick="hdn_botonSel.value='finalizar'" />
						&nbsp;&nbsp;&nbsp;						
						<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" 
						title="Agregar Pasos al Plan de Contingencia" onmouseover="window.status='';return true" onclick="hdn_botonSel.value='agregar'"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_limpiar" type="reset" class="botones" value="Limpiar" id="btn_limpiar" title="Limpia el Formulario" 
						onmouseover="window.status='';return true"/><?php 
						if(isset($_SESSION['datosPlanContingencia'])){?>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_regresar" type="button" class="botones" value="Cancelar" 
							title="Cancelar Registro  y Regresar Al Men&uacute; de Plan de Contingencia" 
							onclick="confirmarSalida('frm_modificarPlanContingencia.php?cancel=<?php echo $clavePlan;?>');" onmouseover="window.status='';return true" /><?php 
						}?>					
					</td>
				</tr>
			</table>
		</form>
		</fieldset><?php
	} 
	else if($_SESSION['nomArchivoCargado']!=""){ ?>
		<fieldset class="borde_seccion" id="tabla-archivoContingencia" name="tabla-archivoContingencia">
			<legend class="titulo_etiqueta">Cargar el Archivo para el Plan de Contingencia</legend>
			<form onsubmit="return valFormCargarArchivoPlanContingencia(this);" name="frm_modificarArchivoPlanContingencia" 
			method="post" action="frm_modificarPlanContingencia2.php">
			<input type="hidden" name="txt_idPlan" id="txt_idPlan" value="<?php echo $clavePlan?>" />

				<br />
				<table width="100%" class="tabla_frm">
					<tr>
						<td><div align="right" id="div_agrArc">Agregar Archivos </div></td>
						<td colspan="6">
							<input name="txt_archivos" id="txt_archivos" type="text" class="caja_de_texto" size="40" readonly="readonly" 
							value="<?php echo $_SESSION['nomArchivoCargado']; ?>" 
							onclick="window.open('verDctoVinculadoPlanContingencia.php','_blank','top=50, left=50, width=680, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar un Archivo"/>
						</td>					
					</tr>
					<tr>
						<td colspan="6">&nbsp;&nbsp;</td>			
					</tr>
					<tr>
						<td colspan="6">&nbsp;&nbsp;</td>			
					</tr>
					</tr>
					<tr>
						<td colspan="6"><div align="center">
							<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="" />
							<?php //if() ?>
							<input name="sbt_finalizarArchivo" type="submit" class="botones" value="Finalizar" 
							title="Finalizar la Carga del Archivo del Plan de Contingencia Generado"
							onmouseover="window.status='';return true" onclick="hdn_botonSel.value='finalizarArchivo';" />
							&nbsp;&nbsp;&nbsp;	
							<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" 
							onmouseover="window.status='';return true" />
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
							title="Regresar al Men&uacute; del Plan de Contingencia" onmouseover="window.status='';return true" 
							onclick="confirmarSalida('menu_PlanContingencia.php');" />
						</div></td>
					</tr>
				</table>
			</form>
		</fieldset><?php 
	}//Cierre else if($nomArchivoCargado!="")?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>	


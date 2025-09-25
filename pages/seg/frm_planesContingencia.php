<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion
html xmlns="http://www.w3.org/1999/xhtml">
<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_planesContingencia.php");
		?>	
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>
    <style type="text/css">
		<!--
			#titulo-seleccionar {	position:absolute;	left:30px;	top:146px;	width:167px;	height:20px;	z-index:11;}
			#tabla-plaContingencia {position:absolute;left:28px;top:179px;width:931px;height:141px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-planGenerado {position:absolute;left:73px;top:88px;width:911px;height:141px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-pasosContingencia {position:absolute;left:32px;top:350px;width:925px;height:139px;z-index:12;padding:15px;padding-top:0px; visibility:hidden;}
			#tabla-archivoContingencia {position:absolute;left:31px;top:359px;width:932px;height:139px;z-index:12;padding:15px;padding-top:0px; visibility:hidden;}
			#periodo1{position:absolute; left:883px; top:218px; width:30px; height:26px; z-index:18; }	
			#tabla-accionesSim{position:absolute;left:37px;top:521px;width:918px;height:132px;z-index:12; overflow:scroll;}
			#pasosPlanContingencia{position:absolute; left:69px; top:292px; width:1071px; height:154px; z-index:18; }	
			#archivoPlanContingencia{position:absolute; left:883px; top:218px; width:30px; height:26px; z-index:19; }	
			#botones-TablaRes {position:absolute;left:30px;top:374px;width:968px;height:44px;z-index:16;}
		-->
    </style>
</head>
<body>

<?php
	//Verificar que el boton agregar esta definido
	if(isset($_POST["sbt_agregar"])){	
		//Esta variable indica si el registro esta repetido o no
			$datoRep = 0;
			if(isset($_SESSION['datosPlanContingencia'])){
				//Verificar que el registro no este repetido en el Arreglo de SESSION
				foreach($_SESSION["datosPlanContingencia"] as $ind => $registro){
					if(strtoupper($_POST["txt_paso"])==$registro["paso"]){
						$datoRep = 1;
						break;
					}
				}	
			}		
			if($datoRep!=1){
				//Si ya esta definido el arreglo, entonces agregar el siguiente registro a el
				if(isset($_SESSION['datosPlanContingencia'])){			
						//Guardar los datos en el arreglo
						$datosPlanContingencia[] = array("paso"=>strtoupper($_POST['txt_paso']),
						"accion"=>strtoupper($_POST['txt_accion']), 
						"resAccion"=>strtoupper($_POST['txt_resAccion']), 
						"simulacro"=>strtoupper($_POST['txt_simulacro']),						
						"comentarios"=>strtoupper($_POST['txa_comentarios']));
				}
				//Si no esta definido el arreglo definirlo y agregar el primer registro
				else{			
						$cont = 0;
						//Guardar los datos en el arreglo
						$datosPlanContingencia = array( array("paso"=>strtoupper($_POST['txt_paso']), 
						"accion"=>strtoupper($_POST['txt_accion']),
						"resAccion"=>strtoupper($_POST['txt_resAccion']), 						
						"simulacro"=>strtoupper($_POST['txt_simulacro']),
						"comentarios"=>strtoupper($_POST['txa_comentarios'])));
						$_SESSION['datosPlanContingencia'] = $datosPlanContingencia;	
					}	
			}
			else{?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('La Descripción del Paso Ingresado ya se encuentra Registrado; Agregue uno Diferente')", 500);
				</script><?php
				}	
	} //Fin del if(isset($_POST["sbt_agregar"])){
	
		//Verificar que este definido la SESSION, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["datosPlanContingencia"])){?><?php
			echo "<div id='tabla-accionesSim' class='borde_seccion2'>";
				mostrarPasosPlanContingencia($datosPlanContingencia);
			echo "</div>"; 
		}
		?><?php
		
	//Comprobamos si existe la sesion para asignar los datos generales de lainformacion del Plan de Contingencia Registrados
	if(!isset($_SESSION['datosPlanContingencia'])){
		$idPlan = obtenerIdPlanContingencia();
		$resSim = "";
		$area = "";
		$lugar = "";
		$nomSim = "";	
		$tipoSim = "";
		$atributoExt = "";
		$fechaProg = date("d/m/Y", strtotime("+30 day"));					
	}
	if(isset($_POST['sbt_agregar'])&&!isset($_SESSION['datosPlanPrincipal'])){
		$idPlan = strtoupper($_POST["txt_idPlan"]);
		$nomSim = strtoupper($_POST["txt_nomSimulacro"]);
		$tipoSim = strtoupper($_POST["txt_tipoSimulacro"]);
		$atributoExt = "readonly='readonly'";
		$resSim = strtoupper($_POST["txt_resSim"]);
		$area = strtoupper($_POST["txt_area"]);
		$lugar = strtoupper($_POST["txt_lugar"]);
		$fechaProg = strtoupper($_POST["txt_fechaProg"]);
	
	$_SESSION['datosPlanPrincipal'] = array("idPlan"=>$idPlan,"nomSim"=>$nomSim,"tipoSim"=>$tipoSim,"atributoExt"=>$atributoExt,
		"resSim"=>$resSim, "area"=>$area, "lugar"=>$lugar, "fechaProg"=>$fechaProg);
	} 
		if(isset($_SESSION['datosPlanPrincipal'])){

		$idPlan = $_SESSION['datosPlanPrincipal']['idPlan'];
		$nomSim = strtoupper($_SESSION['datosPlanPrincipal']['nomSim']);
		$tipoSim = strtoupper($_SESSION['datosPlanPrincipal']['tipoSim']);
		$atributoExt = $_SESSION['datosPlanPrincipal']['atributoExt'];
		$resSim = strtoupper($_SESSION['datosPlanPrincipal']['resSim']);
		$area = strtoupper($_SESSION['datosPlanPrincipal']['area']);
		$lugar = strtoupper($_SESSION['datosPlanPrincipal']['lugar']);
		$fechaProg = strtoupper($_SESSION['datosPlanPrincipal']['fechaProg']);
		
		}?>
		
<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
<div class="titulo_barra" id="titulo-seleccionar">Plan Contingencia</div>
	
	<form onsubmit="return valFormPlanesContingenciaGenerados(this);" name="frm_planesContingencia" method="post" id="frm_planesContingencia" >
		<fieldset class="borde_seccion" id="tabla-plaContingencia" name="tabla-plaContingencia">
		<legend class="titulo_etiqueta">Ingresar los Datos del Plan de Contingencia</legend><br />
			<table width="100%" class="tabla_frm" cellpadding="5">
				<tr>
					<td width="15%"><div align="right">Id Plan</div></td>
					<td width="17%">
						<input type="text" name="txt_idPlan" id="txt_idPlan" maxlength="10" size="10" class="caja_de_texto" 
						value="<?php echo $idPlan;?>" onkeypress="return permite(event,'num',1);" readonly="readonly" />					
					</td>
					<td width="16%"><div align="right">Fecha Registro</div></td>
					<td width="17%">
						<input name="txt_fechaReg" id="txt_fechaReg" readonly="readonly" type="text" value="<?php echo date("d/m/Y")?>" size="10"  width="90"/>					
					</td>
					<td width="18%"><div align="right">*Fecha Programada </div></td>
					<td width="17%">
						<input name="txt_fechaProg" id="txt_fechaProg" readonly="readonly" type="text" value="<?php echo $fechaProg;?>" 
						size="10"  width="90"  />					
					</td>	 
				</tr>
				<tr>
					<td><div align="right">*Responsable</div></td>
					<td><input type="text" name="txt_resSim" id="txt_resSim" maxlength="60" size="30" class="caja_de_texto" 
						value="<?php echo $resSim;?>" onkeypress="return permite(event,'num_car',7);" <?php echo $atributoExt;?> />
					</td>
					<td><div align="right">*&Aacute;rea</div></td>
					<td>
						<input name="txt_area" type="text" class="caja_de_texto" id="txt_area" 
						onkeypress="return permite(event,'num_car',8);" value="<?php echo $area; ?>" size="30" maxlength="80" <?php echo $atributoExt;?> />				
					</td>
					<td><div align="right">* Lugar</div></td>
					<td>
						<input type="text" name="txt_lugar" id="txt_lugar" maxlength="80" size="30" class="caja_de_texto" 
						value="<?php echo $lugar; ?>" onkeypress="return permite(event,'num_car',8);"  <?php echo $atributoExt;?>/>					
					</td>					
				</tr>
				<tr>
					<td><div align="right">*Nombre Simulacro</div></td>
					<td>
						<input type="text" name="txt_nomSimulacro" id="txt_nomSimulacro" maxlength="60" size="30" class="caja_de_texto" 
						value="<?php echo $nomSim; ?>" onkeypress="return permite(event,'num_car',7);" <?php echo $atributoExt;?>/>					
					</td>
					<td><div align="right">*Tipo Simulacro</div></td>
					<td>
						<input type="text"  name="txt_tipoSimulacro" id="txt_tipoSimulacro" maxlength="60" size="30" class="caja_de_texto" 
						value="<?php echo $tipoSim; ?>" onkeypress="return permite(event,'num_car',7);" <?php echo $atributoExt;?> />					
					</td>
					<td colspan="2"><?php 
						/*Si si se da clic al boton de sbt_agregar significa que el usuario ha seleccionado la opcion registrar los pasos 
						y por lo tanto las opciones de radio no se deben de mostrar*/
						if(!isset($_POST['sbt_agregar'])){?>
							<input type="radio" name="rdb_opcPlanContingencia" id="rdb_opcPlanContingencia" value="pasosPlan" 
							onclick="activarOpcionPlanContingencia(this.value);valorRadio(this.value);" />
							<strong>Registrar Pasos al Plan de Contingencia</strong><br />
							<input type="radio" name="rdb_opcPlanContingencia" id="rdb_opcPlanContingencia" value="archivoPlan" 
							onclick="activarOpcionPlanContingencia(this.value);valorRadio(this.value);"  />
							<strong>Seleccionar Archivo Plan de Contingencia</strong><?php							
							
						}
						else if(isset($_POST['sbt_agregar'])){
							//Modificar la visibilidad de DIV donde se regitran los pasos del plan?>
							<script type="text/javascript" language="javascript">
								setTimeout("activarOpcionPlanContingencia('pasosPlan');",500); 
							</script><?php
						}?>
					</td>
				</tr>
		</table>
	  </fieldset>
	  
		<fieldset class="borde_seccion" id="tabla-pasosContingencia" name="tabla-pasosContingencia">
			<legend class="titulo_etiqueta">Acciones ó Pasos a Realizar</legend>
				<table width="100%" class="tabla_frm">
					<tr>
						<td><div align="right">*Paso</div></td>
						<td>
							<input type="text" name="txt_paso" id="txt_paso" maxlength="120" size="30" class="caja_de_texto" 
							value="" onkeypress="return permite(event,'num_car',0);"/>
						</td>
						<td><div align="right">*Acci&oacute;n</div></td>
						<td>
							<input type="text" name="txt_accion" id="txt_accion" maxlength="120" size="30" class="caja_de_texto" 
							value="" onkeypress="return permite(event,'num_car',7);" />
						</td>
						<td><div align="right">* Simulacro</div></td>
						<td>
							<input type="text" name="txt_simulacro" id="txt_simulacro" maxlength="160" size="30" class="caja_de_texto" 
							value="" onkeypress="return permite(event,'num_car',7);" />
						</td>
					</tr>
					<tr>
						<td><div align="right">*Responsable Acci&oacute;n</div></td>
						<td>
							<input type="text" name="txt_resAccion" id="txt_resAccion" maxlength="60" size="30" class="caja_de_texto" 
							value="" onkeypress="return permite(event,'num_car',7);"/>
						</td>		
						 <td><div align="right">Comentarios</div></td>
						<td>
							<textarea name="txa_comentarios" cols="40" rows="3" class="caja_de_texto" id="txa_comentarios"  
							onkeypress="return permite(event,'num_car',0);" maxlength="120" onkeyup="return ismaxlength(this)"></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="6"><div align="center" >
							<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
							<?php 
							if(isset($_SESSION['datosPlanContingencia'])) { //Si al menos un Paso se ha agregado al Plan de Contingencia que se muestre el boton de finalizar?>
								<input name="sbt_finalizar" type="submit" class="botones" value="Finalizar" 
								title="Finalizar el Registro de los Pasos del Plan de Contingencia Generado"
								onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='finalizar';" />
							&nbsp;&nbsp;&nbsp;
							<?php } ?>	
							<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar Pasos al Plan de Contingencia" 
								onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='agregar';"   />
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
	  </fieldset>

		<fieldset class="borde_seccion" id="tabla-archivoContingencia" name="tabla-archivoContingencia">
			<legend class="titulo_etiqueta">Cargar el Archivo para el Plan de Contingencia</legend>
				<br />
				<table width="100%" class="tabla_frm">
					<tr>
						<td><div align="right" id="div_agrArc">Agregar Archivo</div></td>
						<td colspan="6">
							<input name="txt_archivos" id="txt_archivos" type="text" class="caja_de_texto" size="40" readonly="readonly" 
							onclick="window.open('verDctoVinculadoPlanContingencia.php','_blank','top=50, left=50, width=680, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar un Archivo"/>
						</td>
					<tr>
						<td colspan="6">&nbsp;&nbsp;</td>			
					</tr>
					<tr>
						<td colspan="6">&nbsp;&nbsp;</td>			
					</tr>
					</tr>
					<tr>
						<td colspan="6"><div align="center">
							<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
							
							<input name="sbt_finalizarArchivo" type="submit" class="botones" value="Finalizar" 
							title="Finalizar la Carga del Archivo del Plan de Contingencia Generado"
							onmouseover="window.status='';return true" onclick="hdn_botonSeleccionado.value='finalizarArchivo';" />
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
	</fieldset>
</form>
<?php ?>
	
		<div align="center" id="botones-TablaRes">
			<?php if(!isset($_SESSION['datosPlanContingencia'])){ ?>
				<input name="sbt_cancelar" type="submit" class="botones" value="Cancelar" 
					title="Regresar al Men&uacute; del Plan de Contingencia" onmouseover="window.status='';return true" 
					onclick="location.href = 'menu_PlanContingencia.php'"  />	
					<input type="hidden" id="hdn_cancelar" name="hdn_cancelar" value="" style="visibility:visible"/>
			<?php } ?>		
		</div>	

<?php 	
	if(!isset($_SESSION['datosPlanContingencia'])){?>
		<div id="periodo1">
       		<input name="fechaProg" type="image" id="fechaProg" onclick="displayCalendar(document.frm_planesContingencia.txt_fechaProg,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"  title="Seleccionar Fecha"
				width="25" height="25" border="0" />
		</div>
<?php } //Fin del if(!isset($_SESSION['datosPlanContingencia']))?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>


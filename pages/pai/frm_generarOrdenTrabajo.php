
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include ("op_generarOrdenTrabajo.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionPaileria.js" ></script>
   	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
   	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquipoMtto.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#titulo-generarOrdenTrabajo {position:absolute;left:30px;top:146px;width:215px;height:20px;z-index:11;}
		#tabla-generarOrdenTrabajo {position:absolute;left:30px;top:190px;width:908px;height:390px;z-index:12;padding:15px;padding-top:0px;}
		#calendario {position:absolute;left:530px;top:320px;width:30px;height:26px;z-index:13;}
		#res-spider1 { position:absolute; width:10px; height:10px; z-index:13; }
		#res-spider2 { position:absolute; width:10px; height:10px; z-index:13; }
		#res-spider3 { position:absolute; width:10px; height:10px; z-index:13; }
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		-->
    </style>
    
</head>
<body><?php 
	if(isset ($_GET['cancelar'])){
		if(isset ($_SESSION['gamasOT']))
			unset ($_SESSION['gamasOT']);
	}

	if(!isset($_POST['sbt_generarOrdenTrabajo'])){
		$cmb_servicio = "";
		$cmb_area = "";
		$cmb_familia = "";
		$cmb_claveEquipo = "";
		$txt_ordenTrabajo = obtenerIdOrdenTrabajo();
		$txt_fechaOrdenTrabajo = date("d/m/Y");
		$txt_fechaProgramada = date("d/m/Y");
		$cmb_metrica = "";
		$txt_cantidadMetrica = "";
		$txt_operadorEquipo = "";
		$cmb_turno = "";
		$cmb_claveGama = "";
		$txa_comentarios = "";
		$cmb_autorizoOT	= "";
		$ast="*";
		$txt_proveedor="";
		$supervisor="";
		$generador=obtenerDato("bd_usuarios","credenciales","nombre","usuarios_usuario",$_SESSION["usr_reg"]);
		$revisor="";
		
		
		
		//Recuperar todos los valores del arreglo de session datosOT
		if(isset($_SESSION['datosOT'])){
			$cmb_servicio = $_SESSION['datosOT']['servicio'];
			$cmb_area = $_SESSION['datosOT']['area'];
			$cmb_familia = $_SESSION['datosOT']['familia'];
			$cmb_claveEquipo = $_SESSION['datosOT']['claveEquipo'];
			$txt_ordenTrabajo = $_SESSION['datosOT']['orden_trabajo'];
			$txt_fechaOrdenTrabajo = modFecha ($_SESSION['datosOT']['fechaOrdenTrabajo'],1);
			$txt_fechaProgramada = modFecha ($_SESSION['datosOT']['fechaProgramada'],1);
			$cmb_metrica = $_SESSION['datosOT']['metrica'];
			$txt_cantidadMetrica = $_SESSION['datosOT']['cantidadMetrica'];
			$txt_operadorEquipo = $_SESSION['datosOT']['operadorEquipo'];
			$cmb_turno = $_SESSION['datosOT']['turno'];
			$txa_comentarios = $_SESSION['datosOT']['comentarios'];	
			$cmb_autorizoOT = $_SESSION['datosOT']['autorizoOT'];	
			$ast="";
			$txt_proveedor =  $_SESSION['datosOT']['proveedor'];
			$supervisor = $_SESSION['datosOT']['supervisor'];
			$generador = $_SESSION['datosOT']['generador'];
			$revisor = $_SESSION['datosOT']['revisor'];
		}
		
		
		//Recuperar los valores cuando se proviene de una Alerta desde la Pagina donde se selecciona el Equipo
		if(isset($_SESSION['datosEquipoAlerta'])){			
			$cmb_area = $_SESSION['datosEquipoAlerta']['area'];
			$cmb_familia = $_SESSION['datosEquipoAlerta']['familia'];
			$cmb_claveEquipo = $_SESSION['datosEquipoAlerta']['claveEquipo'];
			$cmb_metrica = $_SESSION['datosEquipoAlerta']['metrica'];
			$txt_cantidadMetrica = $_SESSION['datosEquipoAlerta']['cantidadMetrica'];	
			
			unset($_SESSION['datosEquipoAlerta']);
		}
		
		//Recuperar los valores cuando se proviene directamente de una Alerta
		if(isset($_POST['sbt_aceptar'])){
			$cmb_area = $_POST['hdn_area'];
			$cmb_familia = $_POST['hdn_familia'];
			$cmb_claveEquipo = $_POST['hdn_idEquipo'];
			$cmb_metrica = $_POST['hdn_metrica'];
			$txt_cantidadMetrica = $_POST['hdn_ultimoReg'];
		}
		
		//Ejecutar este codigo cuando no este defindo el arreglo gamasOT en la SESSION
		$ctrl_alerta = 0;
		if(isset($_GET['cancelar']) || isset($_POST['sbt_aceptar'])){
			//Codigo que nos permite recuperar los valores preseleccionados en cada uno de los combos?> 
			<script type="text/javascript" language="javascript" >
				window.onload = function(){ 
					<?php if($cmb_area!=""){ ?>              
						//cargarCombo("<?php echo $cmb_area; ?>","bd_mantenimiento","equipos","familia","area","cmb_familia","Familia","<?php echo $cmb_familia; ?>");
						cargarCombo('<?php echo $cmb_area;?>','bd_paileria','gama','familia_aplicacion','area_aplicacion','cmb_familia','Familia',"<?php echo $cmb_familia; ?>");<?php 
						$ctrl_alerta = 1;
					}
					if($cmb_familia!=""){?>
						setTimeout("cargarEquiposFamilia('<?php echo $cmb_familia; ?>','<?php echo $cmb_area; ?>','cmb_claveEquipo','Clave','<?php echo $cmb_claveEquipo; ?>');",500);<?php 
					} ?>
				}
			</script><?php	
		}//Cierre if(!isset($_SESSION['datosOT']))
		
		if(!isset($_POST['sbt_continuar'])){ ?>		 
		  
			<div id="barra"><img src="../../images/title-bar-bg-gomar.png" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-generarOrdenTrabajo">Registrar Orden de Trabajo</div>        
			 
			<fieldset class="borde_seccion" id="tabla-generarOrdenTrabajo" name="tabla-generarOrdenTrabajo">
			<legend class="titulo_etiqueta">Seleccionar los Equipos que ser&aacute;n  Programados para el Servicio de Mantenimiento</legend>	
			<br>
			<form onSubmit="return valFormGenerarOrdenTrabajo(this);" name="frm_generarOrdenTrabajo" method="post" action="frm_generarOrdenTrabajo.php" 
			onmouseover="window.status='';return true">
            
			<?php 
			/*Determinar cual usuario esta logeado y en base a ello permitir la Manipulacion de la Información que le Corresponde*/
			$atributo = "";
			$area = $cmb_area;
			$estado = 1;//El estado 1 indica que el departamento registrados en la SESSION tiene acceso a la información de MINA y CONCRETO
			if($_SESSION['depto']=="MttoConcreto"){
				$area = "CONCRETO";
				$atributo = "disabled='disabled'";
				$estado = 0;
			}
			else if($_SESSION['depto']=="MttoMina"){
				$area = "MINA";
				$atributo = "disabled='disabled'";
				$estado = 0;
			}
			else if($_SESSION['depto']=="Paileria"){
				$area = "GOMAR";
				$atributo = "disabled='disabled'";
				$estado = 0;
			}
			
			//Ejecutar este codigo cuando no este defindo el arreglo datosOT en la SESSION
			if(!isset($_SESSION['datosOT'])){	
				if($estado==0 && $ctrl_alerta == 0){ ?>		
					<script type="text/javascript" language="javascript">
						cargarCombo('<?php echo $area;?>','bd_paileria','gama','familia_aplicacion','area_aplicacion','cmb_familia','Familia');
					</script>
				<?php } 
			}//Cierre if(!isset($_SESSION['datosOT']))?>
            
			<table width="923" height="359" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
				  <td width="120"><div align="right"><?php echo $ast;?>Servicio</div></td>
                    <td width="144"><?php
						if (!isset($_SESSION['gamasOT'])) {?>
							<select name="cmb_servicio" id="cmb_servicio" class="combo_box" onchange="varificarTipoServicio();">
                                <option value="">Servicio</option>                 
                                <option <?php if($cmb_servicio=="INTERNO") echo "selected='selected'"; ?> value="INTERNO">INTERNO</option>
                                <option <?php if($cmb_servicio=="EXTERNO") echo "selected='selected'"; ?> value="EXTERNO">EXTERNO</option>
							</select><?php 
						}
						else{?>
								<input name="cmb_servicio" type="text" class="caja_de_texto"  size="15" readonly="readonly" id="cmb_servicio"
								value="<?php echo $cmb_servicio;  ?>"/>	
						<?php } ?>                    </td>
					<td width="131"><div align="right">Id Orden de Trabajo</div></td>
					<td width="157">
						<input name="txt_ordenTrabajo" type="text" class="caja_de_texto" readonly="readonly" id="txt_ordenTrabajo"
						onkeypress="return permite(event,'num_car',3);" size="11" maxlength="11" 
						value="<?php echo $txt_ordenTrabajo; ?>"/>				
                    </td>   
					<td width="100"><div align="right">M&eacute;trica</div></td>
					<td width="174">
                    	<input name="cmb_metrica" type="text" class="caja_de_texto"  size="18" readonly="readonly" id="cmb_metrica" 
                    	value="<?php echo $cmb_metrica;?>">                    
                    </td>
				</tr>
				<tr>
					<td align="right"><div align="right"><?php echo $ast;?>&Aacute;rea</div></td>
					<td>
                       <?php if (!isset($_SESSION['gamasOT'])) {?>
							<?php if($estado==1) {?>
                                <select name="cmb_area" class="combo_box" 
                                    onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');">
                                    <option value="">&Aacute;rea</option>						
                                    <option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
                                    <option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
									<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'"; ?>>GOMAR</option>
                                </select>
								<input type="hidden" name="cmb_area" value="<?php echo $area; ?>" />
                            <?php } 
							else { ?>
                                <select name="cmb_area" class="combo_box" 
                                    onchange="cargarCombo(this.value,'bd_mantenimiento','equipos','familia','area','cmb_familia','Familia','');" 
									<?php echo $atributo; ?>>
                                    <option value="">&Aacute;rea</option>						
                                    <option value="CONCRETO" <?php if($area=="CONCRETO") echo "selected='selected'"; ?>>CONCRETO</option>
                                    <option value="MINA" <?php if($area=="MINA") echo "selected='selected'"; ?>>MINA</option>
									<option value="GOMAR" <?php if($area=="GOMAR") echo "selected='selected'"; ?>>GOMAR</option>
                                </select>		
                                <input type="hidden" name="cmb_area" value="<?php echo $area; ?>" />
                            <?php } ?>
						<?php } 
						else {?> 
                            <input name="cmb_area" type="text" class="caja_de_texto"  size="15" readonly="readonly" id="cmb_area"
                            value="<?php echo $cmb_area;  ?>"/>	
						<?php } ?>                    </td>            
				  	<td><div align="right">Fecha</div></td>
					<td><input type="text" name="txt_fechaOrdenTrabajo" id="txt_fechaOrdenTrabajo" size="11" maxlength="10" class="caja_de_texto" readonly="readonly" 
					   value="<?php echo $txt_fechaOrdenTrabajo; ?>"/></td>
					<td><div align="right"><?php echo $ast;?>Cantidad M&eacute;trica</div></td>
			 		<td width="174"><?php
						if (!isset($_SESSION['gamasOT'])) {?>
                            <input name="txt_cantidadMetrica" id="txt_cantidadMetrica" type="text" class="caja_de_texto" size="10" maxlength="10" 
                            onkeypress="return permite(event,'num',2);" value="<?php echo number_format($txt_cantidadMetrica,2,".",","); ?>" 
							onchange="formatCurrency(this.value,'txt_cantidadMetrica');" /><?php 
						}
						else{?>
								<input name="txt_cantidadMetrica" type="text" class="caja_de_texto"  size="18" readonly="readonly" id="txt_cantidadMetrica"
								value="<?php echo number_format($txt_cantidadMetrica,2,".",",");  ?>"/>	
						<?php } ?>                    </td>
                </tr>
				<tr>
					<td height="43" align="right"><div align="right"><?php echo $ast;?>Familia</div></td>
					<td><?php
						if (!isset($_SESSION['gamasOT'])) {?>
                            <select name="cmb_familia" id="cmb_familia" 
                                onchange="cargarEquiposFamilia(this.value,'<?php echo $area; ?>','cmb_claveEquipo','Clave','');">
                                <option value="">Familia</option>
                            </select><?php 
						}
						else{?>
								<input name="cmb_familia" type="text" class="caja_de_texto"  size="15" readonly="readonly" id="cmb_familia"
								value="<?php echo $cmb_familia;  ?>"/>	
						<?php } ?>                    </td>
					<td><div align="right">Fecha Programada</div></td>
					<td><input type="text" name="txt_fechaProgramada" id="txt_fechaProgramada" size="11" maxlength="10" class="caja_de_texto" readonly="readonly" 
						 value="<?php echo $txt_fechaProgramada; ?>">					</td>
					<td><div align="right"><?php echo $ast;?>Turno</div></td>
					<td><?php
						if (!isset($_SESSION['gamasOT'])) {?>
                            <select name="cmb_turno" id="cmb_turno">
                            <option selected="selected" value="">Turno</option>
                            <option <?php if($cmb_turno=='TURNO DE PRIMERA') echo "selected='selected'"?> value="TURNO DE PRIMERA">TURNO DE PRIMERA</option>
                            <option <?php if($cmb_turno=='TURNO DE SEGUNDA') echo "selected='selected'"?> value="TURNO DE SEGUNDA">TURNO DE SEGUNDA</option>
                            <option <?php if($cmb_turno=='TURNO DE TERCERA') echo "selected='selected'"?> value="TURNO DE TERCERA">TURNO DE TERCERA</option>
                            </select><?php 
						}
						else{?>
								<input name="cmb_turno" type="text" class="caja_de_texto"  size="25" readonly="readonly" id="cmb_turno"
								value="<?php echo $cmb_turno;  ?>"/>	
						<?php } ?>	</td>
                </tr>
				<tr>
					<td><div align="right"><?php echo $ast;?>Clave del Equipo </div></td>
					<td>
					<script type="text/javascript" language="javascript">
						function obtenerDatosEquipo(claveEquipo){
							obtenerMetricaEquipo(claveEquipo,'txt_cantidadMetrica');
						}	
					</script>
					
					<?php
						if (!isset($_SESSION['gamasOT'])) {?>
                            <select name="cmb_claveEquipo" id="cmb_claveEquipo" onchange="obtenerDatosEquipo(this.value);">
                            	<option value="">Clave Equipo</option>
                            </select><?php 
						}
						else{?>
								<input name="cmb_claveEquipo" type="text" class="caja_de_texto"  size="15" readonly="readonly" id="cmb_claveEquipo"
								value="<?php echo $cmb_claveEquipo;  ?>"/>	
						<?php } ?>                  </td>
					<td><div align="right"><?php //echo $ast;?>Operador del equipo </div></td>
					<td><?php
						if (!isset($_SESSION['gamasOT'])) {?>
                            <input name="txt_operadorEquipo" id="txt_operadorEquipo" type="text" class="caja_de_texto" size="30" maxlength="40" 
                            onkeypress="return permite(event,'num_car',0);" value="<?php echo $txt_operadorEquipo; ?>" onkeyup="lookup(this,'3');"/><?php 
						}
						else{?>
							<input name="txt_operadorEquipo" type="text" class="caja_de_texto"  size="18" readonly="readonly" id="txt_operadorEquipo"
							value="<?php echo $txt_operadorEquipo;?>" onkeyup="lookup(this,'3');"/>
						<?php } ?>
						<div id="res-spider3">
							<div align="left" class="suggestionsBox_gomar" id="suggestions3" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList_gomar" id="autoSuggestionsList3">&nbsp;</div>
							</div>
						</div>
					</td>
					<td><div align="right">Comentarios</div></td>
					<td><?php
						if (!isset($_SESSION['gamasOT'])) {?>	
                            <textarea name="txa_comentarios" id="txa_comentarios"   maxlength="120" 
                            onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
                            onkeypress="return permite(event,'num_car', 0);" ><?php echo $txa_comentarios; ?></textarea><?php 
						}
						else{?>
								<textarea name="txa_comentarios" id="txa_comentarios"   maxlength="120" 
	                            class="caja_de_texto" rows="2" readonly="readonly" cols="30"><?php echo $txa_comentarios; ?></textarea>
						<?php } ?>                    </td>
				</tr>
				<tr>
					<td><div align="right"><?php echo $ast;?>Autoriz&oacute; OT</div></td>
					<td colspan="3"><?php
						if (!isset($_SESSION['gamasOT'])) {?><?php
                            $conn = conecta("bd_recursos");
                            $result=mysql_query("SELECT departamento,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS administrador FROM empleados 
							JOIN organigrama ON empleados_rfc_empleado=rfc_empleado ORDER BY departamento");?>
                            <select name="cmb_autorizoOT" size="1" class="combo_box">
                            <option value="">Autoriz&oacute; Orden Trabajo</option><?php
                            while ($row=mysql_fetch_array($result)){
                                if ($row['administrador'] == $cmb_autorizoOT){
                                    echo "<option value='$row[administrador]' selected='selected'>$row[departamento] - $row[administrador]</option>";
                                }
                                else{
                                    echo "<option value='$row[administrador]'>$row[departamento] - $row[administrador]</option>";
                                }
                            } 
                            //Cerrar la conexion con la BD		
                            // mysql_close($conn);?>
                            </select><?php 
						}
						else{?>
								<input name="cmb_autorizoOT" type="text" class="caja_de_texto"  size="55" readonly="readonly" id="cmb_autorizoOT"
								value="<?php echo $cmb_autorizoOT;  ?>"/>	
						<?php } ?>	                </td>
						
						<td><div align="right"><?php echo $ast;?>Proveedor</div></td>
						<td>
							<?php if(!isset($_SESSION['gamasOT'])){?>
								<input name="txt_proveedor" id="txt_proveedor" type="text" class="caja_de_texto" size="30" maxlength="60" 
                           		onkeypress="return permite(event,'num_car',0);" value="<?php echo $txt_proveedor; ?>" readonly="readonly" />
							<?php } 
							else{ ?>
								<input name="txt_proveedor" id="txt_proveedor" type="text" class="caja_de_texto" size="30" maxlength="60" 
                           		readonly="readonly" value="<?php echo $txt_proveedor; ?>" />
							<?php }?>
						</td>
				</tr>
				<tr>
					<td><div align="right">*Supervisor Responsable</div></td>
					<td colspan="2">
						<input name="txt_supervisor" type="text" class="caja_de_texto" id="txt_supervisor" tabindex="1" onkeypress="return permite(event,'car',0);" 
						onkeyup="lookup(this,'1');" value="<?php echo $supervisor?>" size="40" <?php if (isset($_SESSION['gamasOT'])) echo "readonly='readonly'";?> maxlength="75"/>
						<div id="res-spider1">
							<div align="left" class="suggestionsBox_gomar" id="suggestions1" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList_gomar" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>
					</td>
					<td><div align="right">*Generador &Oacute;rden Trabajo</div></td>
					<td colspan="2">
						<?php
						?>
						<input type="text" name="txt_generador" id="txt_generador" class="caja_de_texto" size="40" maxlength="75" <?php if (isset($_SESSION['gamasOT'])) echo "readonly='readonly'";?>
						onkeypress="return permite(event,'num_car',0);" value="<?php echo $generador?>"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">*Revisada Por</div></td>
					<td colspan="2">
						<input name="txt_revisor" type="text" class="caja_de_texto" id="txt_revisor" tabindex="1" onkeypress="return permite(event,'car',0);" 
						onkeyup="lookup(this,'2');" value="<?php echo $revisor?>" size="40" <?php if (isset($_SESSION['gamasOT'])) echo "readonly='readonly'";?> maxlength="75"/>
						<div id="res-spider2">
							<div align="left" class="suggestionsBox_gomar" id="suggestions2" style="display: none;">
								<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
								<div class="suggestionList_gomar" id="autoSuggestionsList2">&nbsp;</div>
							</div>
						</div>
					</td>
				</tr>
				<tr>
				<?php if($ast=="0"){?>
					<td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
					<?php }?>
				</tr>
				<tr>
				  <td colspan="6"><div align="center">
						<?php if (isset($_SESSION['gamasOT'])){?>
							<input name="sbt_generarOrdenTrabajo" type="submit" class="botones"  value="Generar Orden" title="Generar Orden de Trabajo"
							onmouseover="window.status='';return true"/>
						<?php 
						} ?> 
						<?php if (!isset($_SESSION["datosOT"]) || !isset($_SESSION['gamasOT'])){?>
                            <input name="sbt_continuar" type="submit" class="botones"  value="Continuar" title="Continuar Orden de Trabajo" 
							onmouseover="window.status='';return true"/><?php } ?>						
						<?php if (!isset($_SESSION["datosOT"]) || !isset($_SESSION['gamasOT'])){?>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
						<?php } ?>
                        <?php if (!isset($_SESSION['gamasOT'])) {?> 
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Orden de Trabajo " 
						onmouseover="window.status='';return true" onclick="location.href='menu_ordenTrabajo.php'" /></div>	
                        <?php } 
						else { ?>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar Orden de Trabajo " 
						onmouseover="window.status='';return true" onclick="confirmarSalida('menu_ordenTrabajo.php');" /></div>	
                        <?php }?>				  </td>
				</tr>
			</table>
			</form>
			</fieldset><?php
            
			if (!isset($_SESSION['gamasOT'])){?> 
                <div id="calendario">
                  <input type="image" name="txt_fechaProgramada" id="txt_fechaProgramada" src="../../images/calendar.png"
                    onclick="displayCalendar(document.frm_generarOrdenTrabajo.txt_fechaProgramada,'dd/mm/yyyy',this)" 
                    onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
                    title="Seleccionar Fecha de Fabricaci&oacute;n de Equipo"/> 
</div><?php 
			}
		}//Cierre if(!isset($_POST['sbt_continuar']))
		else{
			$id_orden_trabajo = $_POST['txt_ordenTrabajo']; 
			$operador_equipo = strtoupper($_POST["txt_operadorEquipo"]); 
			$comentarios = strtoupper($_POST['txa_comentarios']);
			
			//Guardar los datos de la Gama en la SESSION 	
			$_SESSION['datosOT'] = array ("servicio"=>$_POST['cmb_servicio'], "area"=>$_POST['cmb_area'], "familia"=>$_POST['cmb_familia'],
			 "claveEquipo"=>$_POST['cmb_claveEquipo'], "orden_trabajo"=> $id_orden_trabajo , "fechaOrdenTrabajo"=> modfecha ($_POST['txt_fechaOrdenTrabajo'],3), 
			 "fechaProgramada"=> modfecha ($_POST['txt_fechaProgramada'],3),"metrica"=>$_POST['cmb_metrica'], "cantidadMetrica"=>str_replace(",","",$_POST['txt_cantidadMetrica']), 
			 "operadorEquipo"=> strtoupper($_POST['txt_operadorEquipo']), "turno"=>$_POST['cmb_turno'],
			 "comentarios"=> strtoupper($_POST['txa_comentarios']), "autorizoOT"=>$_POST['cmb_autorizoOT'], "supervisor"=>strtoupper($_POST['txt_supervisor']),  "generador"=>strtoupper($_POST['txt_generador']), 
			 "revisor"=>strtoupper($_POST['txt_revisor']), "proveedor"=>strtoupper($_POST["txt_proveedor"]));
			
			//Redireccionar a la Pagina de generar vale mantenimiento
			echo "<meta http-equiv='refresh' content='0;url=frm_gamaOT.php'>";	
		}
	}//Cierre if(!isset($_POST['sbt_generarOrdenTrabajo']))
	else{

		/*Para generar la Orden de Trabajo, primero guaradamos el detalle del Vale, después la Info complementaría del Vale y por último registramos
		 *la Info de la Orden de Trabajo */
		guardarGamas();?>
        
        <div class="titulo_etiqueta" id="procesando">
            <div align="center">
                <p><img src="../../images/loading-gomar.gif" width="70" height="70"  /></p>
                <p>Procesando...</p>
            </div>
        </div><?php
		
	}?>          
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
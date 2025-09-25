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
		//Manejo de la funciones para registrar la Entrada de Materiales en la BD 
		include ("op_generarRequisicion.php");
		//Archivo que permite editar los registros de la requisicion
		include("op_editarRegistros.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js" ></script>
	
	<script type="text/javascript" src="../../includes/ajax/busq_spider_material_req.js"></script>
	<script type="text/javascript" src="../../includes/ajax/verificarMatRequi.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	
    <style type="text/css">
		<!--
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		#material-requisicion { position:absolute; left:21px; top:580px; width:978px; height:143px; z-index:14; overflow:auto;}
		#boton-agregar { position:absolute; left:21px; top:150px; width:920px; height:30px; z-index:17;}
		#tabla-material { position:absolute; left:20px; top:180px; width:940px;	height:180px; z-index:16; }
		#datos-gral { position:absolute; left:20px; top:380px; width:940px; height:180px; z-index:15; }
		#titulo-generar { position:absolute; left:30px; top:146px; width:187px; height:19px; z-index:11; }
		#tabla-otrosMat { position:absolute; left:567px; top:190px; width:394px; height:240px; z-index:14; }
		#res-spider {position:fixed;left:110px;z-index:30;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Generar Requisici&oacute;n </div><?php 
	
	
	//Si la variable de txt_areaSolicitante esta definida en el arreglo $_POST, proceder a guardar la informacion de la BD.
	if(!isset($_POST['txt_areaSolicitante'])){
		/*$area="MANTENIMIENTO DESARROLLO";
		//Extraer el RFC del encargado de departamento
		$usuario=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento",$area);
		//Con el RFC, traer el nombre completo del encargado del depto
		$usuario=obtenerNombreEmpleado($usuario);
		//Obtener el nombre del empleado con el Usuario adjudicado*/
		$elaborador=obtenerDato("bd_usuarios","credenciales","nombre","usuarios_usuario",$_SESSION["usr_reg"]);
	?>
		
	<fieldset id="tabla-material" class="borde_seccion">
	<legend class="titulo_etiqueta">Seleccionar Material del Cat&aacute;logo de Almac&eacute;n</legend>
	<br>	
	<form onsubmit="return valFormGenerarRequisicion(this);" name="frm_generarRequisicion" method="post" action="frm_generarRequisicion.php" >
	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" >		
		<tr>
			<td width="80"><div align="right">Material</div></td>
			<td>
            	<input type="text" name="cmb_material" id="cmb_material" class="caja_de_texto" size="30" onkeyup="lookup(this,'1');" 
				value="" maxlength="60" autocomplete="off" required="required" onchange="validarMaterialRequi(txt_clave.value, cmb_material, cmb_material.value);"/>
				<div id="res-spider">
					<div align="left" class="suggestionsBox" id="suggestions1" style="display: none; width:380px;">
						<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
					</div>
				</div>
			</td>
			<td align="right">Clave</td>
			<td align="left">
				<input type="text" name="txt_clave" id="txt_clave" size="10" maxlength="10" readonly="true" style="text-align:center;" required="required"/>
			</td>
			<td align="right">Existencia</td>
			<td align="left">
				<input type="text" name="txt_existencia" id="txt_existencia" size="3" maxlength="5" readonly="true" style="text-align:center;" required="required"/>
			</td>
		</tr>
		<tr>
			<td align="right">Unidad Medida</td>
			<td align="left">
				<input type="text" name="txt_unidadMedida" id="txt_unidadMedida" size="5" maxlength="10" style="text-align:center;" required="required"/>
				<input type="hidden" name="txt_costoUnit" id="txt_costoUnit" size="5" maxlength="10"/>
				<input type="hidden" name="txt_moneda" id="txt_moneda" size="5" maxlength="10"/>
				<input type="hidden" name="txt_cat" id="txt_cat" size="5" maxlength="10"/>
			</td>
			<td>
				<div align="right">Cantidad</div>
			</td>
			<td>
				<input name="txt_cantReq" type="text" class="caja_de_texto" id="txt_cantReq"  size="5" maxlength="10" onkeypress="return permite(event,'num',2);" required="required" autocomplete="off"/>
			</td>
			<td><div align="right">Equipo</div></td>
			<td>
				<?php
				$conn1 = conecta("bd_mantenimiento");//Conectarse con la BD de Mantenimiento
				$rs_equipos = mysql_query("SELECT DISTINCT id_equipo FROM equipos WHERE estado='ACTIVO' ORDER BY id_equipo");
				if($equipos=mysql_fetch_array($rs_equipos)){?>
					<select name="cmb_equipos" id="cmb_equipos" class="combo_box" required="required"
					onchange="cargarCuentas_Equipo(this.value,'cmb_con_cos','cmb_cuenta','cmb_subcuenta');">
						<option value="">Equipos</option>
						<option value="N/A">N/A</option><?php
						do{
							echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
						}while($equipos=mysql_fetch_array($rs_equipos));?>
						</select><?php
				}
				else
					echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Equipos Registrados</label>";
				mysql_close($conn1);
				?>
			</td>
		</tr>
		<tr>
        	<td><div align="right">Centro de Costos</div></td>
			<td>
				<?php 
				$conn = conecta("bd_recursos");		
				$stm_sql = "SELECT * FROM control_costos WHERE habilitado = 'SI' ORDER BY descripcion";
				$rs = mysql_query($stm_sql);
				//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
				if($datos = mysql_fetch_array($rs)){?>
					<select name="cmb_con_cos" id="cmb_con_cos" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta')" required="required">
						<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
						echo "<option value=''>Centro de Costos</option>";
						do{
							echo "<option value='$datos[id_control_costos]'>$datos[descripcion]</option>";
						}while($datos = mysql_fetch_array($rs));?>
					</select>
				<?php
				}
				else{
					echo "<label class='msje_correcto'> No actualmente centro de costos</label>
						<input type='hidden' name='cmb_area' id='cmb_area'/>";
				}
				//Cerrar la conexion con la BD		
				mysql_close($conn);
				?>
			</td>
			<td><div align="right">Cuenta</div></td>
    		<td>
				<span id="datosCuenta">
					<!--
					<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box" onchange="cargarSubCuentas(cmb_con_cos.value,cmb_cuenta.value,cmb_subcuenta.name); setTimeout('cargarCategroias(cmb_con_cos.value,cmb_cuenta.value,cmb_cat.name)',200)" required="required">
					-->
					<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box" onchange="cargarSubCuentas(cmb_con_cos.value,cmb_cuenta.value,cmb_subcuenta.name);" required="required">
						<option value="">Cuentas</option>
					</select>
				</span>
			</td>
		<!-- </tr>
		<tr> -->
			<td><div align="right">Subcuenta</div></td>
    		<td>
				<span id="datosSubCuenta">
					<select name="cmb_subcuenta" id="cmb_subcuenta" class="combo_box" onchange="hdn_subcuenta.value = this.value" required="required">
						<option value="">SubCuentas</option>
					</select>
				</span>
			</td>
			<input type="hidden" name="hdn_subcuenta" id="hdn_subcuenta" value=""/>
			<!-- <td>
				<div align="right">Categoria</div>
			</td>
    		<td colspan="2">
				<span id="datosCategoria">
					<select name="cmb_cat" id="cmb_cat" class="combo_box" required="required">
						<option value="">Categorias</option>
					</select>
				</span>
			</td> -->
		</tr>
		<div id="boton-agregar" align="center">
			<input type="submit" name="btn_agregarOtro" class="botones" value="Agregar Otro" onMouseOver="window.status='';return true" title="Agregar Material al Registro de la Requisici&oacute;n"/>
		</div>
	</table>
	</form>
	</fieldset>
	
	<fieldset class="borde_seccion" id="datos-gral" name="datos-gral">
	<legend class="titulo_etiqueta">Informaci&oacute;n Complementaria de la Requisici&oacute;n</legend>
	<br>
	<form onsubmit="return valFormInformacionRequisicion(this);" name="frm_InformacionRequisicion" method="post" action="frm_generarRequisicion.php">
  	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td>
				<div align="right">Justificaci&oacute;n</div>
			</td>
      		<td>
				<textarea name="txa_justificacionReq" maxlength="120" onkeyup="return ismaxlength(this)" cols="30" rows="2" class="caja_de_texto" id="txa_justificacionReq" 
				onkeypress="return permite(event,'num_car');" required="required" style="resize: none;"></textarea>
			</td>
		    <td>
				<div align="right">Elabor&oacute;</div>
			</td>
		    <td>
				<input name="txt_elaboradorReq" type="text" id="txt_elaboradorReq" onkeypress="return permite(event,'num_car');" size="40" maxlength="60" readonly="readonly" value="<?php echo $elaborador;?>"/>
		  		<input name="hdn_fecha" type="hidden" id="hdn_fecha" value="<?php echo verFecha(3);?>"  />
			</td>          
		    <td>
				<div align="right">Prioridad</div>
			</td>
            <td>
				<select name="cmb_prioridad" id="cmb_prioridad" size="1" class="combo_box" required="required">
					<?php //Evitar que la variable $cmb_prioridad marque un error por no estar definida			
					if(!isset($_POST['cmb_prioridad']))
						$cmb_prioridad = "";
					?>
					<option value="" selected="selected">Prioridad</option>
					<option <?php if($cmb_prioridad=="BAJA") echo "selected='selected'"; ?> value="BAJA">BAJA</option>
					<option <?php if($cmb_prioridad=="MEDIA") echo "selected='selected'"; ?> value="MEDIA">MEDIA</option>
					<option <?php if($cmb_prioridad=="URGENTE") echo "selected='selected'"; ?> value="URGENTE">URGENTE</option>
            	</select>
            </td>          
		</tr>
    	<tr>
			<td>
				<div align="right">&Aacute;rea Solicitante</div>
			</td>
      		<td>
				<input name="txt_areaSolicitante" type="text" id="txt_areaSolicitante" onkeypress="return permite(event,'num_car');" size="25" maxlength="45" value="MANTENIMIENTO DESARROLLO" required="required"/>
			</td>
			<td>
				<div align="right">Solicit&oacute;</div>
			</td>
		    <td>
				<input name="txt_solicitanteReq" type="text" id="txt_solicitanteReq" onkeypress="return permite(event,'num_car');" size="40" maxlength="60" value="" required="required"/>
			</td>
		</tr>
		<tr>
			<td>
				<div align="right">Correo</div>
			</td>
			<td colspan="5">
				<input type="text" id="txt_correo" name="txt_correo" size="110" maxlength="300" required="required" 
				placeholder="Poner correo al que se notificara cuando el material entre a almacen, si es mas de uno favor de separarlos con punto y coma">
			</td>
		</tr>
		<tr>
			<td colspan="6" align="center">
				<input type="hidden" name="hdn_materialAgregado" id="hdn_materialAgregado" 
				<?php 
				if(isset($_POST['txt_cantReq']) || isset($_POST['txt_cantReq2']) || (isset($_SESSION['datosRequisicion']) && count($_SESSION['datosRequisicion'])>0) ) 
					echo "value='si'"; 
				else 
					echo "value='no'"; 
				?> />
				<input name="sbt_generar" type="submit" class="botones" id="sbt_generar" value="Generar" onMouseOver="window.status='';return true" title="Generar Requisici&oacute;n" />
				<?php
				$id_req = obtenerIdRequisicion();
				if (isset($_SESSION["id_requisicion"])){
					$estado = "";
					$id_req = $_SESSION["id_requisicion"];
				}?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_comentario" type="button" class="botones" value="Comentario" title="Ingresar Comentario a la Requisici&oacute;n"
				onclick="window.open('verComentarioReq.php?id_requisicion=<?php echo $id_req;?>', 
				'_blank','top=100, left=100, width=500, height=200, status=no, menubar=no, resizable=no, scrollbars=no, toolbar=no, location=no, directories=no');" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Requisiciones" 
				onclick="location.href='menu_requisiciones.php?cancel'" />
			</td>
		</tr>
	</table>
	</form>
</fieldset>
	
	<div id="material-requisicion">
		<?php 			
		if(isset($_POST["btn_agregarOtro"])){
			$id_material = $_POST['txt_clave'];
			$nombre = strtoupper($_POST['cmb_material']);
			$unidad = strtoupper($_POST['txt_unidadMedida']);
			$cantReq = $_POST['txt_cantReq'];
			$aplicacion = $_POST['cmb_equipos'];
			$equipo = $_POST['cmb_equipos'];
			$cc = $_POST['cmb_con_cos'];
			$cuenta = $_POST['cmb_cuenta'];
			$subcuenta = $_POST['cmb_subcuenta'];
			$costoU = $_POST['txt_costoUnit'];
			$moneda = $_POST['txt_moneda'];
			if(isset($_SESSION['datosRequisicion'])){
				if(!verRegDuplicado($datosRequisicion, "clave", $id_material)){
					$datosRequisicion[] = array(
											"clave"=>$id_material, 
											"material"=>$nombre, 
											"unidad"=>$unidad, 
											"cantReq"=>$cantReq, 
											"aplicacionReq"=>$aplicacion,
											"cc"=>$cc,
											"cuenta"=>$cuenta,
											"subcuenta"=>$subcuenta,
											"costoU"=>$costoU,
											"moneda"=>$moneda,
											"nuevo_con_clave"=>1
										  );
				} else {
					?>
					<script type="text/javascript" language="javascript">
						setTimeout("alert('El Material ya fue Agregado a la Requisición');",500);
					</script>
					<?php
				}
			} else {
				$datosRequisicion = array(
										array(
											"clave"=>$id_material,
											"material"=>$nombre, 
											"unidad"=>$unidad, 
											"cantReq"=>$cantReq, 
											"aplicacionReq"=>$aplicacion,
											"cc"=>$cc,
											"cuenta"=>$cuenta,
											"subcuenta"=>$subcuenta,
											"costoU"=>$costoU,
											"moneda"=>$moneda,
											"nuevo_con_clave"=>1
										)
									);
				$_SESSION['datosRequisicion'] = $datosRequisicion;	
				$_SESSION['id_requisicion'] = obtenerIdRequisicion();
			}
		}
		//Verificar que el arreglo de datos haya sido definido en la SESSION
		if( (isset($_SESSION['datosRequisicion']) && count($_SESSION['datosRequisicion'])>0) && isset($_SESSION['id_requisicion'])){
			?><!-- <p align="center" class="titulo_etiqueta">Registro de la Requisici&oacute;n No. <?php echo $_SESSION['id_requisicion']; ?> --></p>
      		<?php mostrarRegistros($datosRequisicion);				
		}
	
	
	}//Cierre if if(!isset($_POST['txt_areaSolicitante']))
	else{
		guardarRequisicion($txa_justificacionReq,$hdn_fecha,$txt_areaSolicitante,$txt_solicitanteReq,$txt_elaboradorReq);
		?>
		<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div>
		<?php
	}?>	
</div>	    	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
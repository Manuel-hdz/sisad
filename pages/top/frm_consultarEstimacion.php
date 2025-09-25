	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografías
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include ("op_consultarEstimacion.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboQuincena.js"></script>	
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    
	<script type="text/javascript" language="javascript">
	function mostrarGrafica(grafica){
		window.open('verRepGrafico.php?grafica='+grafica, '_blank','top=100, left=100, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');
	}
	</script>
	
    <style type="text/css">
		<!--
		#titulo-consultar {position:absolute;left:30px;top:146px;width:210px;height:20px;z-index:11;}
		#tabla-consultarEstimacionObra {position:absolute;left:30px;top:190px;width:450px;height:170px;z-index:12;}
		#tabla-consultarEstimacionMes {position:absolute;left:545px;top:190px;width:350px;height:170px;z-index:12;}
		#tabla-consultarEstimacionQuincena {position:absolute;left:32px;top:400px;width:450px;height:200px;z-index:12;}
		#detalle_estimacion {position:absolute;left:30px;top:190px;width:900px;height:400px;z-index:21;overflow:scroll;}
		#detalle_estimacionSel {position:absolute;left:30px;top:190px;width:900px;height:400px;z-index:21;overflow:scroll;}
		#btn-regresar {position:absolute;left:30px;top:640px;width:930px;height:40px;z-index:19;}
		#btn-regresardetalle {position:absolute;left:30px;top:640px;width:930px;height:40px;z-index:19;}
		-->
    </style>
</head>
<body>


	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Consultar Estimaciones</div>
	<?php
	
	if(!isset($_POST['ckb'])){
		if(!isset($_POST['sbt_consultarObra']) && !isset($_POST['sbt_consultarMes']) && !isset($_POST['sbt_consultarQuincena'])){
			borrarHistorial();
			//Fieldset para consultar Estimacion por quincena?>
			<fieldset class="borde_seccion" id="tabla-consultarEstimacionQuincena" name="tabla-consultarEstimacionQuincena">
			<legend class="titulo_etiqueta">Consultar Estimaci&oacute;n por Quincena</legend>	
				<br>
				<form onSubmit="return valFormConsultarTraspQuin(this);" name="frm_consultarEstimacion" method="post" action="frm_consultarEstimacion.php">
				<table cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td width="27%"><div align="right">Tipo Obra</div></td>
						<td width="73%"><?php 
							$res = cargarComboConId("cmb_tipoObra","tipo_obra","tipo_obra","obras","bd_topografia","Tipo Obra","",
							"cargarComboConId(this.value,'bd_topografia','obras','nombre_obra','id_obra','tipo_obra','cmb_nomObra','Obras','');"); 
							if($res==0){?>
								<label class="msje_correcto">No Hay Datos Registrados</label>
								<input type="hidden" name="cmb_tipoObra" value="" /><?php
							}?>
					  </td>
                    </tr>
					<tr>
						<td><div align="right">Nombre Obra</div></td>
						<td>
							<select name="cmb_nomObra" id="cmb_nomObra" class="combo_box" onchange="cargarComboQuincena(this.value,'estimaciones','cmb_numQuincena');">
								<option value="">Obras</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><div align="right">No Quincena</div></td>
						<td>
							<select name="cmb_numQuincena" id="cmb_numQuincena" class="combo_box">
							<option value="">No. Quincena</option>
							</select>
						</td>
					</tr>
					</tr>&nbsp;<tr>
					<tr>
						<td colspan="2">
							<div align="center"><?php
								if($res==1){?>
									<input name="sbt_consultarQuincena" type="submit" class="botones" id="sbt_consultarQuincena"  
									value="Consultar" title="Consultar Estimacion de la Quincena Seleccionada" onmouseover="window.status='';return true" /><?php
								}?>
                                <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_estimaciones.php';"
                                title="Regresar a la Consulta de Estimaciones"/>
							</div>
						</td>
					</tr>
				</table>
				</form>   
			</fieldset><?php
			
			//Fieldset para consultar Estimacion por obra?>
			<fieldset class="borde_seccion" id="tabla-consultarEstimacionObra" name="tabla-consultarEstimacionObra">
			<legend class="titulo_etiqueta">Consultar Estimacion por Obra</legend>	
				<br>
				<form onSubmit="return valFormConsultarTraspObra(this);" name="frm_consultarEstimacion" method="post" action="frm_consultarEstimacion.php">
				<table  cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
                    <td width="89"><div align="right">Tipo Obra</div></td>
					<td width="246"><?php
						$res = cargarComboConId("cmb_obra","tipo_obra","tipo_obra","obras","bd_topografia","Tipo Obra","",
						"cargarCombo(this.value,'bd_topografia','obras','nombre_obra','tipo_obra','cmb_nombreObra','Obra','');");														
						if($res==0){?>
							<label class="msje_correcto"><u><strong>NO</strong></u> Hay Obras Registradas</label>
							<input type="hidden" name="cmb_obra" id="cmb_obra" value="" /><?php 
						} ?>                    </td>
				</tr>
				<tr>
					<td><div align="right">Nombre Obra</div></td>
					<td><?php
						if($res==1){?>
							<select name="cmb_nombreObra" id="cmb_nombreObra" class="combo_box">
								<option value="">Obra</option>
							</select><?php
						}
						else{?>	
							<label class="msje_correcto"><u><strong>NO</strong></u> Hay Nombres de Obras Registradas</label>
							<input type="hidden" name="cmb_nombreObra" id="cmb_nombreObra" value="" /><?php 
						} ?>					</td>
				</tr>&nbsp;<tr>
				</tr>
				<tr>
					<td colspan="2">
					  <div align="center"><?php
							if($res==1){?>
								<input name="sbt_consultarObra" type="submit" class="botones" id="sbt_consultarObra"  value="Consultar" 
								title="Consultar Estimacion de la Obra Seleccionada" onmouseover="window.status='';return true" /><?php
							}?>
						        <input name="btn_regresar3" type="button" class="botones" value="Regresar" onclick="location.href='menu_estimaciones.php';"
                            title="Regresar al Menú de Estimaciones"/>
					  </div>					</td>
				</tr>
				</table>
				</form>   
			</fieldset><?php
			
			//Fieldset para consultar Estimacion por mes?>
			<fieldset class="borde_seccion" id="tabla-consultarEstimacionMes" name="tabla-consultarEstimacionMes">
			<legend class="titulo_etiqueta">Consultar Estimacion por Mes</legend>	
				<br>
				<form onSubmit="return valFormConsultarTraspMes(this);" name="frm_consultarEstimacion" method="post" action="frm_consultarEstimacion.php">
				<table cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td width="19"><div align="right">Mes</div></td> 
						<td width="246">                   
							<select name="cmb_mes" class="combo_box">
								<option value="">Seleccionar Mes</option>
								<option value="ENERO">Enero</option>
								<option value="FEBRERO">Febrero</option>
								<option value="MARZO">Marzo</option>
								<option value="ABRIL">Abril</option>
								<option value="MAYO">Mayo</option>
								<option value="JUNIO">Junio</option>
								<option value="JULIO">Julio</option>
								<option value="AGOSTO">Agosto</option>
								<option value="SEPTIEMBRE">Septiembre</option>
								<option value="OCTUBRE">Octrube</option>
								<option value="NOVIEMBRE">Noviembre</option>
								<option value="DICIEMBRE">Diciembre</option>
							</select>						</td>
					</tr>
					<tr>
						<td><div align="right">Año</div></td>
                        <td><?php cargarAniosDisponible(); ?></td>
					</tr>  
					<tr>
						<td colspan="2">
							<div align="center"><?php
								if($res==1){?>
							  <input name="sbt_consultarMes" type="submit" class="botones" id="sbt_consultarMes"  
									value="Consultar" title="Consultar Estimacion de Acuerdo al Mes y Año Seleccionado" onmouseover="window.status='';return true" />
							  <?php
								}?>
                                <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_estimaciones.php';"
                                title="Regresar al Menú de Estimaciones"/>
						  </div>						</td>
					</tr>
				</table>
				</form>   
			</fieldset><?php
					
		}//	if(!isset($_POST['sbt_consultarObra']) || !isset($_POST['sbt_consultarMes']) || !isset($_POST['sbt_consultarQuincena'])){
	
		//Si esta definido  sbt_consultar se muestran los Estimacions 
		if(isset($_POST['sbt_consultarObra']) || isset($_POST['sbt_consultarMes']) || isset($_POST['sbt_consultarQuincena'])){?>	
			<div id='detalle_estimacion' class='borde_seccion2' align="center">
				<form onSubmit="return valFormDetTrasp(this);" name="frm_consultarDetEstimacion" method="post" action="frm_consultarEstimacion.php"><?php 
					$datos=mostrarEstimaciones();?>
				 </form>   
			</div> 
			
			<div id='btn-regresar' align="center">
                <table cellpadding="5" cellspacing="5" class="tabla_frm">
                    <tr>
                        <td >
						<?php if(isset($_POST['sbt_consultarMes']) && substr($datos,0,3)=="tmp"){?>
						<input type="button" name="btn_grafico" id="btn_grafico" value="Ver Gr&aacute;fico" title="Ver Gr&aacute;fico de Relaci&oacute;n de Estimaciones" class="botones" 
						onclick="mostrarGrafica('<?php echo $datos?>')"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?php }?>
                        <input name="btn_regresar2" type="button" class="botones" value="Regresar" onclick="location.href='frm_consultarEstimacion.php';"
                        title="Regresar a la Consulta de Estimaciones"/>
                      </td>
                    </tr>
                </table>
			</div><?php
		}// if(isset($_POST['sbt_consultarObra']) || isset($_POST['sbt_consultarMes']) || isset($_POST['sbt_consultarQuincena'])){
	}//fin if(isset($_POST['ckb'])){	
	
	else {?>    
        <div id="detalle_estimacionSel" class="borde_seccion2" align="center"><?php
			//Mostrar el detalle  Seleccionado
			mostrarDetalleTrasp($ckb);?>   
		</div><?php		
		 // recuperar los valores de la consulta para que el boton que nos permite regresar a la consulta de los traspaleos?>
		<div id="btn-regresardetalle">
            <table align="center">
				<tr>
					<td>
					<form name="" action="frm_consultarEstimacion.php" method="post"><?php
						if (isset($_POST['hdn_consultarObra'])){?>
                            <input type="hidden" name="cmb_obra" value="<?php echo $_POST['hdn_tipoObra'] ?>"/>
                            <input type="hidden" name="cmb_nombreObra" value="<?php echo $_POST['hdn_nombreObra'] ?>"/>
                            <input type="hidden" name="sbt_consultarObra" value="<?php echo $_POST['hdn_consultarObra'] ?>"/><?php
						}
						else if (isset($_POST['hdn_consultarMes'])){?>
                            <input type="hidden" name="cmb_mes" value="<?php echo $_POST['hdn_mes'] ?>"/>
                            <input type="hidden" name="cmb_anios" value="<?php echo $_POST['hdn_anios'] ?>"/>
                            <input type="hidden" name="sbt_consultarMes" value="<?php echo $_POST['hdn_consultarMes'] ?>"/><?php
						}
						else if (isset($_POST['hdn_consultarQuincena'])){?>
                            <input type="hidden" name="cmb_tipoObra" value="<?php echo $_POST['hdn_tipo_obra'] ?>"/>
                            <input type="hidden" name="cmb_nomObra" value="<?php echo $_POST['hdn_idObra'] ?>"/>
                            <input type="hidden" name="cmb_numQuincena" value="<?php echo $_POST['hdn_noQuincena'] ?>"/>
                            <input type="hidden" name="sbt_consultarQuincena" value="<?php echo $_POST['hdn_consultarQuincena'] ?>"/><?php
						}?>

						<input name="sbt_regresar" type="submit" class="botones" value="Regresar" title="Regresar a la Consulta de Estimaciones" 
						onmouseover="window.estatus='';return true" id="sbt_regresar" />
					</form>
					</td>
				</tr>
			</table>
        </div><?php
	}?>
	
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
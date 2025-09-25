<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que incluye las operaciones para realizar el reporte de Asistencia
		include ("op_regBonoProd.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="includes/ajax/cargarComboCuentas.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consultar-empleado {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
			#tabla-consultar-empleados {position:absolute; left:30px; top:198px; width:436px; height:170x; z-index:14;}
			#tabla-empleados { position:absolute; left:30px; top:190px; width:945px; height:450px; z-index:21; overflow:scroll; }
			#botones{position:absolute;left:30px;top:675px;width:950px;height:37px;z-index:15;}
			#calendar-uno {position:absolute; left:204px; top:275px; width:30px; height:26px; z-index:18; }
			#calendar-dos {position:absolute; left:460px; top:275px; width:30px; height:26px; z-index:19; }
			#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-empleado">Registrar Bono de Productividad</div>
		<?php 
		if(isset($_POST["sbt_guardar"])){
			guardarBonoProductividad();
			?>
			<div class="titulo_etiqueta" id="procesando">
				<div align="center">
					<p><img src="../../images/loading.gif" width="150" height="150"  /></p>
					<p>Procesando...</p>
				</div>
			</div>
			<?php
		}
		//Verificamos si viene definido en el post el boton consultar
		else if(isset($_POST["sbt_generar"])){
			echo"<form name='frmRegistrarBonos' method='post' action='frm_registroBonos.php'>";
			echo"<div align='center' id='tabla-empleados' class='borde_seccion' width='100%' >";
				//Si viene definido el boton; mostrar el reporte de Asistencias
				mostrarEmpleadosBonoProd();					
			echo "</div>";?>	
			<div id="botones" align="center">
				<input name="sbt_guardar" type="submit" class="botones_largos" id="sbt_guardar" value="Registrar Bonos"
				onmouseover="window.status='';return true;" title="Registrar Bonos de Productividad"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar" 
				onMouseOver="window.status='';return true" onclick="location.href='frm_registroBonos.php'" />
			</div>
			</form>
<?php }
	  else{ ?> 
			<fieldset class="borde_seccion" id="tabla-consultar-empleados">
			<legend class="titulo_etiqueta">Registrar Bono de Productividad</legend>	
			<br>
			<form  method="post" name="frm_regBonProd" id="frm_regBonProd" onsubmit="return valFormRegBonoProd(this);" >
			<table width="444" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">Semana</div></td>
					<td>
						<input type="text" class="caja_de_num" name="txt_semana" id="txt_semana" value="" size="5" maxlength="2" onkeypress="return permite(event,'num',3);"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">Fecha Inicio</div></td>
				  	<td>
						<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
						value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10"/>
					</td>
					<td><div align="right">Fecha Fin </div></td>
					<td>
						<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly"
						value="<?php echo date("d/m/Y"); ?>" size="10"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">*Control de Costos</div></td>
					<td>
						<?php 
						$conn = conecta("bd_recursos");		
						$stm_sql = "SELECT * FROM control_costos WHERE habilitado = 'SI' ORDER BY descripcion";
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){?>
							<select name="cmb_con_cos" id="cmb_con_cos" class="combo_box" onchange="cargarCuentas(this.value,'cmb_cuenta')">
								<?php //Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
								echo "<option value=''>Control de Costos</option>";
								do{
									if ($datos['id_control_costos'] == $cmb_con_cos){
										echo "<option value='$datos[id_control_costos]' selected='selected'>$datos[descripcion]</option>";
									}else{
										echo "<option value='$datos[id_control_costos]'>$datos[descripcion]</option>";
									}
								}while($datos = mysql_fetch_array($rs));
								echo "<script type='text/javascript'>
										cargarCuentas(cmb_con_cos.value,'cmb_cuenta');
									</script>";
								?>
								<script type="text/javascript">
									setTimeout("document.getElementById('cmb_cuenta').value='<?php echo $cmb_cuenta ?>'",500);
								</script>
							</select>
						<?php
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn);
						?>
					</td>
					<td><div align="right">*Cuenta</div></td>
					<td>
						<span id="datosCuenta">
							<select name="cmb_cuenta" id="cmb_cuenta" class="combo_box">
								<option value="">Cuentas</option>
							</select>
						</span>
					</td>
				</tr>
			</table>
			<div align="center">
				<p>
					<input name="sbt_generar" type="submit" class="botones_largos" id="sbt_generar" value="Registrar Bonos"
					onmouseover="window.status='';return true;" title="Registrar Bonos de Productividad"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_bonos.php'" />
				</p>
			</div>
			</form>
			</fieldset>	
			
			<div id="calendar-dos">
				<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_regBonProd.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
				width="25" height="25" border="0" align="absbottom" />
			</div>
			<div id="calendar-uno">
				<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_regBonProd.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
				width="25" height="25" border="0" />
			</div>
	<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
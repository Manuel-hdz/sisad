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
		//Archivo que incluye las operaciones para realizar el reporte de Capacitaciones
		include ("op_reporteCapacitaciones.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consultar-empleado {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
			#tabla-consultar-empleados {position:absolute; left:30px; top:198px; width:436px; height:163px; z-index:14;}
			#tabla-consultar-empleados2 {position:absolute; left:530px; top:198px; width:436px; height:163px; z-index:14;}
			#tabla-consultar-capacitacion {position:absolute; left:28px; top:399px; width:600px; height:163px; z-index:14;}
			#tabla-empleados { position:absolute; left:30px; top:190px; width:945px; height:380px; z-index:21; overflow:scroll; }
			#tabla-datos { position:absolute; left:404px; top:-10px; width:157px; height:56px; z-index:21;}
			#btns-regpdf { position: absolute; left:30px; top:620px; width:945px; height:40px; z-index:23; }
			#calendar-uno {position:absolute; left:236px; top:240px; width:30px; height:26px; z-index:18; }
			#calendar-dos {position:absolute; left:444px; top:240px; width:30px; height:26px; z-index:19; }
			#calendar-tres {position:absolute; left:735px; top:241px; width:30px; height:26px; z-index:18; }
			#calendar-cuatro {position:absolute; left:943px; top:241px; width:30px; height:26px; z-index:18; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-empleado">Reporte de Capacitaciones </div>
		<?php 
		//Verificamos si viene definido en el post el boton consultar
		if(isset($_POST["sbt_consultar"])){
			echo"<div align='center' id='tabla-empleados' class='borde_seccion2' width='100%' >";
				//Si viene definido el boton; mostrar el reporte de Capacitaciones
				reporteCapacitaciones();					
			echo "</div>";?>	
 <?php }
	  else{ ?> 
			</form> 
			<fieldset class="borde_seccion" id="tabla-consultar-capacitacion">
			<legend class="titulo_etiqueta">Reporte de Capacitaciones por Nombre</legend>	
			<br>
			<form  method="post" name="frm_reporteCapacitacion" id="frm_reporteCapacitacion"  onsubmit="return valFormRptCapacitacionesCap(this);" >
			<table width="444" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="63"><div align="right">Capacitaci&oacute;n</div></td>
					<td>
						<?php 
						if(isset($_SESSION["datosGrapCapacitaciones"])){
							unset($_SESSION["datosGrapCapacitaciones"]);
						} 
						//Conectarse con la BD indicada
						$conn = conecta("bd_recursos");		
						//Sentencia SQL para extraer los datos de la capacitacion
						$stm_sql = "SELECT id_capacitacion,nom_capacitacion,fecha_inicio FROM capacitaciones ORDER BY fecha_inicio  DESC,nom_capacitacion";
						//Ejecutar sentencia SQL
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){			
							//Declarar el ComboBox
							echo "<select name='cmb_capacitaciones' id='cmb_capacitaciones' class='combo_box'>";
							//Colocar el mensaje inicial
							echo "<option value=''>Capacitaciones</option>";
							//Obtener el a�o de las capacitaciones
							$anio = substr($datos['fecha_inicio'],0,4);
							echo "
								<option value=''></option>
								<option value=''>----- $anio -----</option>
								<option value=''></option>
							";
							do{
								//Verificar cuando se cambia de a�o y mostrarlo en el combo
								if($anio!=substr($datos['fecha_inicio'],0,4)){
									$anio = substr($datos['fecha_inicio'],0,4);
									echo "
										<option value=''></option>
										<option value=''>----- $anio -----</option>
										<option value=''></option>
									";
								}
								echo "<option value='$datos[id_capacitacion]'>$datos[nom_capacitacion]</option>";
							}while($datos = mysql_fetch_array($rs));
							echo "</select>";
						}
						else{
							echo "<label class='msje_correcto'><u><strong>NO</u></strong> hay Capacitaciones Registradas</label>
							<input type='hidden' name='cmb_claveCapacitacion' id='cmb_claveCapacitacion'/>";
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn);	
						?>
					</td>
				</tr>
			</table>
			<div align="center">
				<p>
					<input name="sbt_consultar" type="submit" class="botones_largos" id="sbt_consultar" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte Capacitaciones"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" />
				</p>
			</div>
			</form>
			</fieldset>
						
			<fieldset class="borde_seccion" id="tabla-consultar-empleados">
			<legend class="titulo_etiqueta">Reporte de Capacitaciones por &Aacute;rea</legend>	
			<br>
			<form  method="post" name="frm_reporteFechaArea" id="frm_reporteFechaArea" onsubmit="return valFormRptCapacitaciones(this);"  >
			<table width="444" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td colspan="2"><div align="right">Fecha Inicio</div></td>
				  	<td width="95">
						<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
						value="<?php echo date("d/m/Y", strtotime("-15 day")); ?>"size="10" width="90"/>
					</td>
					<td width="85"><div align="right">Fecha Fin </div></td>
					<td width="122">
						<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
						value="<?php echo date("d/m/Y"); ?>" size="10"  width="90" />
					</td>
				</tr>
				<tr>
					<td width="63"><div align="right">&Aacute;rea</div></td>
					<td colspan="4">
						<?php 
						$cmb_area="";
						$conn = conecta("bd_recursos");
						$result=mysql_query("SELECT DISTINCT area FROM empleados ORDER BY area");
						if($areas=mysql_fetch_array($result)){?>
							<select name="cmb_area" id="cmb_area" size="1" class="combo_box">
							  <option value="">&Aacute;rea</option>
							  <?php 
							  do{
								if ($areas['area'] == $cmb_area){
									echo "<option value='$areas[area]' selected='selected'>$areas[area]</option>";
								}
								else{
									echo "<option value='$areas[area]'>$areas[area]</option>";
								}
							}while($areas=mysql_fetch_array($result)); 
							//Cerrar la conexion con la BD		
							mysql_close($conn);
							?>
							</select>
				<?php }
					else{
						echo "<label class='msje_correcto'> No hay &Aacute;reas Registradas</label>
						<input type='hidden' name='cmb_area' id='cmb_area'/>";
				  }?>
					</td>
				</tr>
			</table>
			<div align="center">
				<p>
					<input name="sbt_consultar" type="submit" class="botones_largos" id="sbt_consultar" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte Capacitaciones"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" />
				</p>
			</div>
			</form>
			</fieldset>	
			<fieldset class="borde_seccion" id="tabla-consultar-empleados2">
			<legend class="titulo_etiqueta">Reporte de Capacitaciones por Fecha </legend>	
			<br>
			<form  method="post" name="frm_reporteFecha" id="frm_reporteFecha"  onsubmit=" return valFormRptCapacitacionesFecha(this);" >
			<table width="444" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td><div align="right">Fecha Inicio</div></td>
				 	<td width="95">
						<input name="txt_fechaIni" id="txt_fechaIni2" readonly="readonly" type="text" 
						value="<?php echo date("d/m/Y", strtotime("-15 day")); ?>" size="10" width="90"/>			     
					</td>
					<td width="85"><div align="right">Fecha Fin </div></td>
					<td width="122">
						<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
						value="<?php echo date("d/m/Y"); ?>" size="10" width="90"/>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<div align="center">
				<p>
					<input name="sbt_consultar" type="submit" class="botones_largos" id="sbt_consultar" value="Generar Reporte"
					onmouseover="window.status='';return true;" title="Generar Reporte Capacitaciones"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_reportes.php'" />
				</p>
			</div>
			</form>
			</fieldset>
			<div id="calendar-dos">
				<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_reporteFechaArea.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
				width="25" height="25" border="0" align="absbottom" />
			</div>
			<div id="calendar-uno">
				<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteFechaArea.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
				width="25" height="25" border="0" />
			</div>
			<div id="calendar-tres">
				<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
				width="25" height="25" border="0" />
			</div>
            <div id="calendar-cuatro">
				<input name="fechaFin" type="image" id="fechaFin" onclick="displayCalendar(document.frm_reporteFecha.txt_fechaFin,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
				width="25" height="25" border="0" />
			</div>
	<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
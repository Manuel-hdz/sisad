<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que incluye la operación de consultar Empleado
		include ("op_consultarEmpleados.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script  type="text/javascript" src="../../includes/validacionCompras.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/js/jquery.dataTables.js"></script>
	<script type="text/javascript" src="../../includes/jquery/dataTable/paginarTabla.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
				$("#tabla-resultados-empleado").dataTable({
					"sPaginationType": "scrolling"
				});
		});
	</script>		
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
		#titulo-consultar-empleado {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
		#tabla-consultar-empleados {position:absolute; left:30px; top:191px; width:730px; height:190px; z-index:14;}
		#tabla-empleados { position:absolute; left:30px; top:190px; width:945px; height:435px; z-index:21; overflow:scroll; }
		#tabla-datos { position:absolute; left:404px; top:-10px; width:157px; height:56px; z-index:21;}
		#btns-regpdf { position: absolute; left:30px; top:675px; width:945px; height:40px; z-index:23; }
		#calendar-uno {position:absolute; left:251px; top:295px; width:30px; height:26px; z-index:18; }
		#calendar-dos {position:absolute; left:607px; top:296px; width:30px; height:26px; z-index:19; }
		-->
    </style>
	<style type="text/css" title="currentStyle">
		@import "../../includes/jquery/dataTable/css/tabla.css";
	</style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-empleado">Consultar Empleados </div>
		<?php 
		//Verificamos si viene definido en el post el boton consultar
		if(isset($_POST["sbt_consultar"])){
			echo"<div id='tabla-empleados' class='borde_seccion2' width='100%' >";
				//Si viene definido el boton y cmb_consulta en opcion bajas; mostrar los empleados dados de baja
				if($_POST["cmb_consulta"]=="BAJAS")
					mostrarBajasEmpleados();
				//Si viene definido el boton y cmb_consulta en opcion Altas; mostrar los empleados dados de Alta
				if($_POST["cmb_consulta"]=="ALTAS")
					mostrarAltasEmpleados();
				//Si viene definido el boton y cmb_consulta en opcion Incapacidades; mostrar los empleados con Incapacidades
				if($_POST["cmb_consulta"]=="INCAPACIDADES")
					mostrarIncapacidadesEmpleados();
				//Si viene definido el boton y cmb_consulta en opcion Prestamos y financiamientos; mostrar los empleados con estos derechos
				if($_POST["cmb_consulta"]=="PRESTAMOS/FINANCIAMIENTOS")
					mostrarPresFinEmpleados();
				//Si viene definido el boton y cmb_consulta en opcion kARDEX; mostrar el Kardex de los Empleados
				if($_POST["cmb_consulta"]=="KARDEX")
					mostrarKardex();
			echo "</div>";?>	
			<div id="btns-regpdf" align="center">
				<input type="button" name="btn_regresar" value="Regresar"  class="botones" title="Regresar a Seleccionar Nuevos Parametros de Consulta" 
				onMouseOver="window.status='';return true;" onclick="location.href='frm_consultarEmpleados.php'"/>
			</div>
	<?php
		}
	 	else{ ?> 
			<fieldset class="borde_seccion" id="tabla-consultar-empleados">
			<legend class="titulo_etiqueta">Consultar Empleados </legend>	
			<br>
			<form  method="post" name="frm_consultarEmpleados" id="frm_consultarEmpleados" onsubmit="return  valFormConsultarEmpleado(this);" >
			<table width="723" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td colspan="2"><div align="right">&Aacute;rea</div></td>
					<td width="223"  valign="top" rowspan="2"><?php 
						$cmb_area="";
						$conn = conecta("bd_recursos");
						$result=mysql_query("SELECT DISTINCT area FROM empleados ORDER BY area");
						if($result!=""){?>
					 	<select name="cmb_area" id="cmb_area" size="1" class="combo_box">
							<option value="">&Aacute;rea</option>
						  	<?php while ($row=mysql_fetch_array($result)){
								if ($row['area'] == $cmb_area){
									echo "<option value='$row[area]' selected='selected'>$row[area]</option>";
								}
								else{
									echo "<option value='$row[area]'>$row[area]</option>";
								}
							} 
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
					  	</select>			
						<?php }
						else{
						echo"<label  class='msje_correcto'>No hay Trabajadores, Consulte Administrador Recursos Humanos</label>";?>	
						<input type="hidden" name="cmb_area" id="cmb_area" /><?php }?>					</td>
					<td width="105"><div align="right">Tipo de Consulta </div></td>
					<td width="229" rowspan="2" valign="top">
						<select name="cmb_consulta" id="cmb_consulta">    	
							<option value="">Seleccionar</option>
							<option value="BAJAS">BAJAS</option>
							<option value="ALTAS">ALTAS</option>
							<option value="INCAPACIDADES">INCAPACIDADES</option>
							<option value="PRESTAMOS/FINANCIAMIENTOS">PRESTAMOS/FINANCIAMIENTOS</option>
							<option value="KARDEX">KARDEX</option>
						</select>
					</td>
				</tr>
				<tr>
				  <td colspan="2">&nbsp;</td>
			      <td width="105">&nbsp;</td>
			  </tr>
				<tr>
					<td width="96"><div align="right">Fecha Inicio</div></td>
					<td colspan="2">
						<input name="txt_fechaIni" id="txt_fechaIni2" readonly="readonly" type="text" 
						value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" 
						size="10" maxlength="15" width="90"/>					
					</td>
					<td width="105"><div align="right">Fecha Fin </div></td>
					<td width="229">
						<input name="txt_fechaFin" id="txt_fechaFin2" type="text"  readonly="readonly" 
						value="<?php echo date("d/m/Y"); ?>" size="10" maxlength="15"
						width="90" />				  
					</td>
			  	</tr>
			</table>
			<div align="center">
				<p>
					<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar"
					onmouseover="window.status='';return true;" title="Consultar Empleados"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Inicio" 
					onMouseOver="window.status='';return true" onclick="location.href='inicio_compras.php'" />
				</p>
			</div>
			</form>
</fieldset>	
			<div id="calendar-dos">
				<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
				onclick="displayCalendar(document.frm_consultarEmpleados.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
				width="25" height="25" border="0" align="absbottom" />
</div>
			<div id="calendar-uno">
				<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_consultarEmpleados.txt_fechaIni,'dd/mm/yyyy',this)"
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
				width="25" height="25" border="0" />
</div>
	<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
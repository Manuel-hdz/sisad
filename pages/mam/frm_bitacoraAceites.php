<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">


<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento Concreto
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para Generar el Reporte de Mnatenimientos Correctivos de Acuerdo a los Parametros Seleccionados
		include ("op_gestionAceites.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js"></script>

    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:30px; top:146px; width:264px; height:24px; z-index:11; }
		#bitacoraAceites { position:absolute; left:30px; top:190px; width:405px; height:175px; z-index:12; }
		#calendario { position:absolute; left:220px; top:233px; width:30px; height:26px; z-index:13; }
		#equipos { position:absolute; left:30px; top:190px; width:921px; height:450px; z-index:22; overflow: scroll; z-index:14;}
		#botones{position:absolute;left:30px;top:675px;width:950px;height:37px;z-index:15;}
		-->
    </style>	
	
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Bit&aacute;cora de Consumo de Aceite </div>
	
	<?php if(!isset($_POST["sbt_continuar"]) && !isset($_POST["sbt_guardar"])){?>
	<fieldset class="borde_seccion" id="bitacoraAceites" name="bitacoraAceites">	
	<legend class="titulo_etiqueta">Registrar seleccionando Fecha</legend>
	<br />
	<form name="frm_bitacoraAceites" action="frm_bitacoraAceites.php" method="post" onsubmit="return valFormSelEquipoAceite(this);">
		<table width="100%" border="0" align="center"  cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
				<td><div align="right">Fecha </div></td>
				<td width="275">
					<input name="txt_fecha" id="txt_fecha" type="text" value=<?php echo date("d/m/Y");?> size="10" maxlength="15"
					readonly=true width="90" />
				</td>
				<td><div align="right">Turno</div></td>
				<td width="275">
					<select name="cmb_turno" id="cmb_turno" class="combo_box" title="Seleccionar el Turno del Registro">
						<option value="">Turno</option>
						<option value="PRIMERA">PRIMERA</option>
						<option value="SEGUNDA">SEGUNDA</option>
						<option value="TERCERA">TERCERA</option>
					</select>
				</td>
			</tr>
			<tr>
				<td><div align="right">Supervisor</div></td>
				<td colspan="3">
					<?php 
						//Conectarse con la BD indicada
						$conn = conecta("bd_recursos");		
						$stm_sql = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombreEmpleado,puesto FROM empleados WHERE area='MANTENIMIENTO' AND puesto='SUPERVISOR' ORDER BY nombreEmpleado";
						$rs = mysql_query($stm_sql);
						//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
						if($datos = mysql_fetch_array($rs)){			
							//Declarar el ComboBox con el nombre especificado en el parametro $nom_combo
							echo "<select name='cmb_supervisor' id='cmb_supervisor' class='combo_box'>";
							//Colocar el mensaje en definido en el parametro $msj para ser desplegado en el ComboBox
							echo "<option value=''>Supervisor</option>";
							do{
								echo "<option value='$datos[nombreEmpleado]' title='$datos[puesto]'>$datos[nombreEmpleado]</option>";
							}while($datos = mysql_fetch_array($rs));
							echo "</select>";
						}
						//Cerrar la conexion con la BD		
						mysql_close($conn);	
					?>
				</td>
			</tr>
			<tr>
		</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="hidden" name="cmb_familia" id="cmb_familia" value="dummy"/>
					<input name="sbt_continuar" type="submit" class="botones" value="Continuar" onmouseover="window.status='';return true"
					title="Continuar a Bit&aacute;cora de Aceites"/> 
					&nbsp;
					<input name="rst_Limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
					&nbsp;
					<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar al Men&uacute; de Aceites" onclick="location.href='menu_aceites.php'"/>
				</td>
			</tr>
	  </table>
		</form>
		</fieldset>
			
		<div id="calendario">
			<input name="fechaRegistro" id="fechaRegistro" type="image" src="../../images/calendar.png" title="Seleccionar la Fecha de Registro de Consumo de Aceite"
			onclick="displayCalendar(document.frm_bitacoraAceites.txt_fecha,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
			width="25" height="25" border="0" align="absbottom" />
		</div>
		
	<?php
	}
	if(isset($_POST["sbt_continuar"])){
		echo "<form method='post' action='frm_bitacoraAceites.php' name='frm_regBitAceite' onsubmit='return valFormGastoAceite(this)';>";
		echo "<div id='equipos' class='borde_seccion' align='center'/>";
			mostrarEquipos($txt_fecha);
		echo "</div>";
		?>
		<div id="botones" align="center">
			<input type="submit" class="botones" value="Guardar" title="Guardar Registros de Aceites" onmouseover="window.status='';return true" name="sbt_guardar"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="reset" class="botones" value="Limpiar" title="Limpiar los datos del Formulario" name="btn_reset" onclick="restablecerBitacoraAceiteMina();"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" class="botones" value="Cancelar" title="Cancelar y volver a Seleccionar otra Familia" onclick="location.href='frm_bitacoraAceites.php'" name="btn_cancelar"/>
		</div>
		<?php
		echo "</form>";
	}
	if(isset($_POST["sbt_guardar"])){
		guardarRegistroAceites();
	}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
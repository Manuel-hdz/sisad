<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="includes/ajax/cargarFechasTxt.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-consultar{position:absolute;left:30px;top:146px; width:256px;height:20px;z-index:11;}
			#tabla-consultarFechas{position:absolute;left:30px;top:190px;width:425px;height:145px;z-index:12;}
			#calendario-uno{position:absolute;left:230px;top:260px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:445px;top:260px;width:30px;height:26px;z-index:13;}
			#botones{position:absolute;left:30px;top:640px;width:945px;height:40px;z-index:15;}
		-->
    </style>
</head>
<body>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Registrar N&oacute;mina de Desarrollo</div>

	<?php 
	if (isset($_GET["borrar"]) && isset($_SESSION["bonoNomina"])){
		unset($_SESSION["bonoNomina"]);
	}
	?>
	<fieldset class="borde_seccion" id="tabla-consultarFechas" name="tabla-consultarFechas">
	<legend class="titulo_etiqueta">Seleccione Fechas de Trabajo</legend>	
	<br>
	<form name="frm_registrarNomina" method="post" action="frm_registrarNominaInternaDesarrollo.php" onsubmit="return valSeleccionarNomina(this);">
		<table width="415"  cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="200"><div align="right">N&oacute;mina</div></td>
				<td colspan="4"><?php
					$cmb_nomina="";
					$faltan = 0;
					$conn = conecta("bd_desarrollo");
					$result=mysql_query("SELECT DISTINCT id_nomina FROM nominas WHERE finalizada = '1' ORDER BY id_nomina");
					if($nominas=mysql_fetch_array($result)){ ?>
						<select name="cmb_nomina" id="cmb_nomina" size="1" class="combo_box" onchange="cargarTxtFechas(this.value,'txt_fechaIni','txt_fechaFin','bd_desarrollo')">
						  <option value="">N&oacute;mina</option><?php 
							do{
								$conn_rec = conecta("bd_recursos");
								$result_rec=mysql_query("SELECT DISTINCT id_nomina FROM nominas WHERE id_nomina = '$nominas[id_nomina]'");
								if(!$nominas_rec=mysql_fetch_array($result_rec)){
									$faltan = 1;
									if ($nominas['id_nomina'] == $cmb_nomina){
										echo "<option value='$nominas[id_nomina]' selected='selected'>$nominas[id_nomina]</option>";
									}
									else{
										echo "<option value='$nominas[id_nomina]'>$nominas[id_nomina]</option>";
									}
								}
								mysql_close($conn_rec);
							}while($nominas=mysql_fetch_array($result)); 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
						</select> <?php 
						if($faltan == 0){
							echo "<label class='msje_correcto'> No hay N&oacute;minas faltantes</label>
							<input type='hidden' name='cmb_nomina' id='cmb_nomina'/>";
						}
					}
					else{
						echo "<label class='msje_correcto'> No hay N&oacute;minas Registradas</label>
						<input type='hidden' name='cmb_nomina' id='cmb_nomina'/>";
					}
					?>
				</td>
			</tr>
			<tr>
				<td width="200"><div align="right">Fecha Inicio</div></td>
				<td width="276">
					<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" 
					value="<?php echo date("d/m/Y", strtotime("-7 day"));?>" readonly="readonly"/>
				</td>
				
				<td width="150"><div align="right">Fecha Fin</div></td>
				<td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
			</tr>
			<tr>
				<td colspan="5">
					<div align="center">
						<input name="sbt_consultar" type="submit" class="botones" id= "sbt_consultar" value="Continuar" title="Continuar a Registrar N&oacute;mina de Desarrollo"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;
						<input name="btn_reset" type="reset" class="botones" id="btn_reset" value="Restablecer" title="Restablecer el Formulario"/>
						&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Registrar Nomina" 
						onmouseover="window.status='';return true" onclick="location.href='menu_registrar_nomina.php'" />
					</div>
				</td>
				<input type='hidden' name='hdn_area' id='hdn_area' value=""/>
			</tr>
	  </table>
	</form>
	</fieldset>
	
	<div id="calendario-uno">
		<input name="calendario_uno" type="image" id="calendario_uno" onclick="displayCalendar (document.frm_registrarNomina.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
		title="Seleccione Fecha de Inicio" />
	</div>
	<div id="calendario-dos">
		<input name="calendario_dos" type="image" id="calendario_dos" onclick="displayCalendar (document.frm_registrarNomina.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha de Fin" />
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
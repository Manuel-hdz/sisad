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
		//Archivo que incluye la operación de consultar Empleados de la nomina Bancaria para registrarlos en la misma
		include ("op_registrarNominaBancaria.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
	<script type="text/javascript" src="../../includes/funcionesJS.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consultar-nomina {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
			#tabla-consultar-nomina {position:absolute; left:30px; top:191px; width:402px; height:129px; z-index:14;}
			#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; overflow:scroll;}
			#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
			#calendar-uno {position:absolute; left:203px; top:41px; width:30px; height:26px; z-index:18; }
			#calendar-dos {position:absolute; left:203px; top:75px; width:30px; height:26px; z-index:19; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-nomina">Registrar N&oacute;mina Bancaria</div><?php	
	 
		//Verificamos que sea presionado el boton registrar
		if(isset($_POST["sbt_generar"])){?>
		<form name="frm_resultadosNomina" method="post" onsubmit="return valFormResultadosNomBanc(this);"/><?php 
			echo"<div align'center' id='tabla-resultados' class='borde_seccion2' width='100%' >";
			//Lllamamos a la funcion mostrarNomina incluida en el op
			$validar=mostrarNominaBancaria();
			echo "</div>";?>
			<div align="center" id="botones"><?php
			 if($validar==1){?>
				<input  type="hidden" name="hdn_area" id="hdn_area" value="<?php echo $_POST["cmb_area"];?>" />
				<input name="sbt_guardar" type="submit" value="Registrar" class="botones" title="Guardar Registros N&oacute;mina Bancaria" />				
				&nbsp;&nbsp;&nbsp;&nbsp;<?php
			 }?>
			<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar al Men&uacute; N&oacute;mina"
			onclick="location.href='frm_registrarNominaBancaria.php'" />
		</div>
	</form><?php	
	 	
	}
	else{?>		
	<fieldset class="borde_seccion" id="tabla-consultar-nomina">
			<legend class="titulo_etiqueta">Seleccionar &Aacute;rea </legend>	
			<br>
			<form  method="post" name="frm_consultarEmpleados" id="frm_consultarEmpleados" onsubmit="return valFormConsNom(this);" >
			<table width="434" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td width="63"><div align="right">&Aacute;rea</div></td>
					<td width="302"><?php
						$cmb_area="";
						$conn = conecta("bd_recursos");
						$result=mysql_query("SELECT DISTINCT area FROM empleados ORDER BY area");
						if($areas=mysql_fetch_array($result)){?>
							<select name="cmb_area" id="cmb_area" size="1" class="combo_box">
							  <option value="">&Aacute;rea</option><?php
							  do{
									if ($areas['area'] == $cmb_area){
										echo "<option value='$areas[area]' selected='selected'>$areas[area]</option>";
									}
									else{
										echo "<option value='$areas[area]'>$areas[area]</option>";
									}
								}while($areas=mysql_fetch_array($result)); 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
							</select><?php
			 }
				else{
					echo "<label class='msje_correcto'> No hay &Aacute;reas Registradas</label>
					<input type='hidden' name='cmb_area' id='cmb_area'/>";
				  }?>		
				 	</td>
				</tr>
			</table>
			<div align="center">
			  <p><input name="sbt_generar" type="submit" class="botones_largos" id="sbt_generar" value="Registrar N&oacute;mina Bancaria"
					onmouseover="window.status='';return true;" title="Generar Reporte Asistencia"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
				  <input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Reportes" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_nominaBancaria.php'" />
			  </p>
			</div>
			</form>
</fieldset>
	<?php }//Llave de cierre del if(isset($_POST["sbt_registrar"]))?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
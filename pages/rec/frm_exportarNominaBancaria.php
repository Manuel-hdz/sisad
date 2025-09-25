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
		//Archivo que incluye la operación de exportar Empleados de la nomina Bancaria
		include ("op_exportarNominaBancaria.php");
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>	
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-exportar-nomina {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
			#tabla-exportar-nomina {position:absolute; left:30px; top:191px; width:311px; height:177px; z-index:14;}
			#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; padding:15px; padding-top:0px; overflow:scroll;}
			#btns-regpdf{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
			#calendar-uno {position:absolute; left:203px; top:41px; width:30px; height:26px; z-index:18; }
			#calendar-dos {position:absolute; left:203px; top:75px; width:30px; height:26px; z-index:19; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-exportar-nomina">Exportar N&oacute;mina Bancaria</div>	
	<?php 
	//Verificamos que sea presionado el boton exportar
	if(isset($_POST["sbt_exportar"])){
	?>
	<form name="frm_resultadosNomina" method="post"  onsubmit="return valFormResultadosNomBancaria(this);" action="guardar_reporte.php"><?php 
		echo "<input type='hidden' name='hdn_anio' id='hdn_anio' value='$_POST[cmb_anio]'/>
		<input type='hidden' name='hdn_mes' id='hdn_mes' value='$_POST[cmb_mes]'/>
		<input type='hidden' name='hdn_semana' id='hdn_semana' value='$_POST[cmb_semana]'/>";	
		echo "<div class='borde_seccion2' id='tabla-resultados'>";
			//Lllamamos a la funcion mostrarNomina incluida en el op
			mostrarNominaBancaria();
		?>
	</form>	
	<?php 	
	}
	else{?>		
	<fieldset class="borde_seccion" id="tabla-exportar-nomina">
        <legend class="titulo_etiqueta">Exportar N&oacute;mina Fecha </legend>
        <br />
        <form  onsubmit="return valFormFechaConNomBanc(this);" method="post" name="frm_exportarNominaFecha" id="frm_exportarNominaFecha">
        <table width="302" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
        	<tr>
            	<td width="86"><div align="right">A&ntilde;o</div></td>
				<td>
				<?php
					$cmb_anio="";
					$conn = conecta("bd_recursos");
					$result=mysql_query("SELECT DISTINCT anio_insercion FROM nomina_bancaria ORDER BY anio_insercion");
					if($anios=mysql_fetch_array($result)){?>
					<select name="cmb_anio" id="cmb_anio" size="1" class="combo_box" onchange="cargarCombo(this.value,'bd_recursos','nomina_bancaria','mes','anio_insercion','cmb_mes','Mes','');">
					  <option value="">A&ntilde;o</option>
					  <?php 
						  do{
							if ($anios['anio_insercion'] == $cmb_anio){
								echo "<option value='$anios[anio_insercion]' selected='selected'>$anios[anio_insercion]</option>";
							}
							else{
								echo "<option value='$anios[anio_insercion]'>$anios[anio_insercion]</option>";
							}
						}while($anios=mysql_fetch_array($result)); 
						//Cerrar la conexion con la BD		
						mysql_close($conn);
					?>
					</select>
				<?php }
				else{
					echo "<label class='msje_correcto'> No hay A&ntilde;os Registrados</label>
					<input type='hidden' name='cmb_anio' id='cmb_anio'/>";
				  }?>
				</td>
            </tr>
            <tr>
            	<td width="86"><div align="right">Mes</div></td>
             	<td><select name="cmb_mes" id="cmb_mes" 
						onchange="cargarCombo(this.value,'bd_recursos','nomina_bancaria','semana','mes','cmb_semana','Semana','');">    	
						<option value="">Mes</option>
					</select></td>
          	</tr>
			<tr>
            	<td width="86"><div align="right">Semana</div></td>
             	<td><select name="cmb_semana" id="cmb_semana">
						<option value="">Semana</option>
					</select></td>
          	</tr>
        </table>
        <div align="center">
			<input name="sbt_exportar" type="submit" class="botones" id="sbt_exportar" value="Consultar"
			onmouseover="window.status='';return true;" title="exportar N&oacute;mina Interna"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; N&oacute;mina" 
			onMouseOver="window.status='';return true" onclick="location.href='menu_nominaBancaria.php'" />
       </div>
       </form>
</fieldset>
	<?php }//Llave de cierre del if(isset($_POST["sbt_exportar"]))?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
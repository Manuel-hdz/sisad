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
		//Archivo que incluye la operación de consultar Empleados de la nomina Bancaria
		include ("op_consultarNominaBancaria.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/op_operacionesBD.php"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>	
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
    <style type="text/css">
		<!--				
			#titulo-consultar-nomina {position:absolute; left:30px; top:146px; width:228px; height:25px; z-index:11; }
			#tabla-consultar-nomina {position:absolute; left:30px; top:193px; width:467px; height:173px; z-index:14;}
			#tabla-resultados{position:absolute; left:30px; top:190px; width:950px; height:420px; z-index:12; padding:15px; padding-top:0px; overflow:scroll;}
			#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
			#calendar-uno {position:absolute; left:249px; top:235px; width:30px; height:26px; z-index:18; }
			#calendar-dos {position:absolute; left:460px; top:235px; width:30px; height:26px; z-index:19; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar-nomina">Consultar N&oacute;mina Bancaria</div><?php 
	//Verificamos que sea presionado el boton consultar
	if(isset($_POST["sbt_consultar"])){?>
	<form name="frm_resultadosNomina" method="post"  onsubmit="return valFormNomBanc();" action="guardar_nomina.php"><?php 
		echo "<input type='hidden' name='hdn_fechaIni' id='hdn_anio' value='$_POST[txt_fechaIni]'/>
			  <input type='hidden' name='hdn_fechaFin' id='hdn_fechaFin' value='$_POST[txt_fechaFin]'/>
			  <input type='hidden' name='hdn_area' id='hdn_area' value='$_POST[cmb_area]'/>";	
		echo "<div class='borde_seccion2' id='tabla-resultados'>";
			//Lllamamos a la funcion mostrarNomina incluida en el op
			$validar=mostrarNominaBancaria();
		echo "</div>";?>
		<div align="center" id="botones"><?php
		 if($validar==1){?>	
				<input name="sbt_exportar" type="submit" value="Exportar N&oacute;mina" class="botones_largos" title="Exportar N&oacute;mina Bancaria" onMouseOver="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;&nbsp;<?php
		 }?>
			<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar al Men&uacute; N&oacute;mina"
			onclick="location.href='frm_consultarNominaBancaria.php'" onMouseOver="window.status='';return true" />
		</div>
	</form><?php 	
	}
	else{?>		
	<fieldset class="borde_seccion" id="tabla-consultar-nomina">
			<legend class="titulo_etiqueta">Consultar N&oacute;mina Bancaria  por &Aacute;rea</legend>	
			<br>
			<form  method="post" name="frm_nominaBancaria" id="frm_nominaBancaria" onsubmit="return valFormConsultarEmpleadoNom(this);" >
			<table width="459" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td colspan="2"><div align="right">Fecha Inicio</div></td>
				  	<td width="95">
						<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
						value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10"  width="90" />
					</td>
					<td width="85"><div align="right">Fecha Fin </div></td>
					<td width="122">
						<input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
						value="<?php echo date("d/m/Y"); ?>" size="10" width="90"/>
					</td>
				</tr>
				<tr>
					<td width="63"><div align="right">&Aacute;rea</div></td>
					<td colspan="4"><?php
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
				<p>
					<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar"
					onmouseover="window.status='';return true;" title="Consultar"/>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; N&oacute;mina" 
					onMouseOver="window.status='';return true" onclick="location.href='menu_nominaBancaria.php'" />
				</p>
			</div>
			</form>
			</fieldset>
			<div id="calendar-uno">
	   	  		<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_nominaBancaria.txt_fechaIni,'dd/mm/yyyy',this)"
		  		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		  		width="25" height="25" border="0" />
			</div>
        	<div id="calendar-dos">
        		<input name="fechaFin" id="fechaFin" type="image" src="../../images/calendar.png" 
		  		onclick="displayCalendar(document.frm_nominaBancaria.txt_fechaFin,'dd/mm/yyyy',this)" onmouseover="window.status='';return true"  
		  		width="25" height="25" border="0" align="absbottom" />
			</div>
	<?php }//Llave de cierre del if(isset($_POST["sbt_consultar"]))?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>	
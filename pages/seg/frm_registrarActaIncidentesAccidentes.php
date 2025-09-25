<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarActaIncidentesAccidentes.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="includes/ajax/obtenerNoAccInc.js"></script>
	<script type="text/javascript" src="includes/ajax/obtenerAntiguedadEmpleado.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:353px;height:20px;z-index:11;}
		#tabla-agregarActa {position:absolute;left:32px;top:392px;width:951px;height:280px;z-index:12;}
		#tabla-agregarActa2 {position:absolute;left:30px;top:190px;width:950px;height:167px;z-index:13;}
		#calendario4{position:absolute;left:122px;top:168px;width:30px;height:26px;z-index:17;}
		#botones{position:absolute;left:1px;top:245px;width:978px;height:26px;z-index:18;}
		#res-spider {position:absolute;z-index:19;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Registrar Informe Incidentes Accidentes 1/3 </div>
	<?php 
		//Verificamos si existe en el GET la variable regresar; esta indica si fue presionado el boton regresar de la pantalla anterior de no existir declaramos 
		//las variables en blanco 
		if(!isset($_GET['regresar'])){
			$claveInforme = obtenerIdRegAccInc();
			$lugar = "";
			$noAcc = "";
			$turno = "";
			$tipoAcc = "";
			$nivel = "";
			$horaInc = "";
			$area = "";
			$areaAcc = "";
			$horaAviso = "";
			$fecha = date("d/m/Y");
			$nomFacilitador = "";
			$horaLaborar = "";
			$nomAcc = "";
			$puesto = "";
			$ficha = "";
			$edad = "";
			$equipo = "";
			$antEmp = "";
			$antPue = "";
			$actMomAcc = "";
			$actHab ="";
		}
		else{//De lo contrario se presiono el boton y se tiene que mostrar lo contenido en la sesion
			$claveInforme = $_SESSION['actaIncAcc']['idActa'];
			$noAcc = $_SESSION["actaIncAcc"]['noAcc'];
			$lugar = $_SESSION['actaIncAcc']['lugar'];
			$turno = $_SESSION['actaIncAcc']['turno'];
			$tipoAcc = $_SESSION['actaIncAcc']['tipoAcc'];
			$nivel = $_SESSION['actaIncAcc']['nivel'];
			$horaInc = $_SESSION['actaIncAcc']['horaAcc'];
			$area = $_SESSION['actaIncAcc']['area'];
			$areaAcc = $_SESSION['actaIncAcc']['areaAcc'];
			$horaAviso = $_SESSION['actaIncAcc']['horaAviso'];
			$fecha = $_SESSION['actaIncAcc']['fecha'];
			$nomFacilitador = $_SESSION['actaIncAcc']['nomFacilitador'];
			$horaLaborar = $_SESSION['actaIncAcc']['horaLaborar'];
			$nomAcc = $_SESSION['actaIncAcc']['nomAcc'];
			$puesto = $_SESSION['actaIncAcc']['puesto'];
			$ficha = $_SESSION['actaIncAcc']['ficha'];
			$edad = $_SESSION['actaIncAcc']['edad'];
			$equipo = $_SESSION['actaIncAcc']['equipo'];
			$antEmp = $_SESSION['actaIncAcc']['antEm'];
			$antPue = $_SESSION['actaIncAcc']['antPue'];
			$actMomAcc = $_SESSION['actaIncAcc']['actividadMomAcc'];
			$actHab = $_SESSION['actaIncAcc']['actHab'];
			//Comprobamos si fue presionado el boton regresar para guardar los datos
			if(isset($_POST['sbt_regresar'])){
				$_SESSION['actaIncAcc']['descripcion'] = strtoupper($_POST['txa_descripcion']);
				$_SESSION['actaIncAcc']['lesion'] = strtoupper($_POST['txa_lesion']);
				$_SESSION['actaIncAcc']['porque'] = strtoupper($_POST['txa_porque']);
				$_SESSION['actaIncAcc']['actosInseguros'] = strtoupper($_POST['txa_actosInseguros']);
				$_SESSION['actaIncAcc']['condicionesInseguras'] = strtoupper($_POST['txa_condicionesInseguras']);
			}
		}
	?>	
	<script language="javascript" type="text/javascript">
		setTimeout("cargarCombo('<?php echo $area;?>','bd_recursos','empleados','puesto','area','cmb_puesto','Puesto','<?php echo $puesto;?>')",500);
	</script>
	
	<script>
		function mostrarCamposIncAcc(opc){
			if(opc=="INTERNO"){
				document.getElementById("ingNomAcc").innerHTML="<input type=\"text\" name=\"txt_nombreAcc\" id=\"txt_nombreAcc\" onkeyup=\"lookup(this,'empleados','1');\" size=\"60\" maxlength=\"80\" onkeypress=\"return permite(event,'car',1);\" tabindex=\"1\" value=\"<?php echo $nomAcc; ?>\"/><div id=\"res-spider\"><div align=\"left\" class=\"suggestionsBox\" id=\"suggestions1\" style=\"display: none;\"> <img src=\"../../images/upArrow.png\" style=\"position: relative; top: -12px; left: 10px;\" alt=\"upArrow\" /><div class=\"suggestionList\" id=\"autoSuggestionsList1\">&nbsp;</div></div></div>";
				document.getElementById("ingPuesto").innerHTML="<select name=\"cmb_puesto\" id=\"cmb_puesto\" class=\"combo_box\"><option value=\"\">Puesto</option></select>";
				document.getElementById("cmb_area").disabled=false;
				cargarCombo(document.getElementById('cmb_area').value,'bd_recursos','empleados','puesto','area','cmb_puesto','Puesto','<?php echo $puesto;?>');
				document.getElementById("txt_antEmp").readOnly=true;
				document.getElementById("txt_ficha").readOnly=true;
				document.getElementById("txt_antEmp").value="";
				document.getElementById("txt_ficha").value="";
			}
			if(opc=="EXTERNO"){
				document.getElementById("ingNomAcc").innerHTML="<input type=\"text\" name=\"txt_nombreAcc\" id=\"txt_nombreAcc\" size=\"60\" maxlength=\"75\" onkeypress=\"return permite(event,'car',1);\" value=\"<?php echo $nomAcc; ?>\"/>";
				document.getElementById("ingPuesto").innerHTML="<input type=\"text\" name=\"cmb_puesto\" id=\"cmb_puesto\" size=\"30\" maxlength=\"30\" onkeypress=\"return permite(event,'car',1);\"/><input type='hidden' name='cmb_area' id='cmb_area' value='EXTERNO'/>";
				document.getElementById("cmb_area").disabled=true;
				document.getElementById("txt_antEmp").readOnly=false;
				document.getElementById("txt_ficha").readOnly=false;
				document.getElementById("txt_antEmp").value="";
				document.getElementById("txt_ficha").value="";
			}
		}
	</script>
	
	<form  onsubmit="return valFormActaIncAcc(this);" name="frm_agregarActa" id="frm_agregarActa" method="post" action="frm_registrarActaIncidentesAccidentes2.php">
	<fieldset class="borde_seccion" id="tabla-agregarActa2" name="tabla-agregarActa2">
	<legend class="titulo_etiqueta">I. Ingresar Datos Generales </legend>	
	<table width="953" height="156"  cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td><div align="right">*Clave Informe </div></td>
			<td width="156">
				<input name="txt_idActa" id="txt_idActa" type="text" class="caja_de_texto" size="10" maxlength="10"  value="<?php echo $claveInforme;?>" 
				readonly="readonly"/>
			</td>
			<td width="131"><div align="right">*Lugar</div></td>
			<td width="104">
				<div align="left">
					<input name="txt_lugar" type="text" id="txt_lugar" size="20" maxlength="80" class="caja_de_texto" value="<?php echo $lugar;?>" 
					onkeypress="return permite(event,'num_car', 8);"/>
				</div>
			</td>
			<td width="218"><div align="right">*Turno</div></td>
			<td width="155">
				<select name="cmb_turno" id="cmb_turno" class="combo_box">
                   	<option <?php if($turno ==""){ echo "selected='selected'";}?>> Turno</option>
                   	<option <?php if($turno =="PRIMERA"){ echo "selected='selected'";}?> value="PRIMERA">PRIMERA</option>
                   	<option <?php if($turno =="SEGUNDA"){ echo "selected='selected'";}?> value="SEGUNDA">SEGUNDA</option>
				  	<option <?php if($turno =="TERCERA"){ echo "selected='selected'";}?> value="TERCERA">TERCERA</option>
            	</select>
			</td>
	  	</tr>
		<tr>
		  	<td width="92"><div align="right">*Tipo Informe </div></td>
			<td>
				<label>
					<select name="cmb_tipoAccidente" id="cmb_tipoAccidente" class="combo_box" onchange="obtenerNoAccInc(this.value);">
				    	<option <?php if($tipoAcc ==""){ echo "selected='selected'";}?>>Tipo Accidente</option>
				    	<option <?php if($tipoAcc =="INCIDENTE"){ echo "selected='selected'";}?>value="INCIDENTE">INCIDENTE</option>
				    	<option <?php if($tipoAcc =="ACCIDENTE"){ echo "selected='selected'";}?> value="ACCIDENTE">ACCIDENTE</option>
		       		</select>
				</label>
			</td>
			<td><div align="right">*Nivel</div></td>
			<td>
				<input name="txt_nivel" type="text" id="txt_nivel" size="20" maxlength="60" class="caja_de_texto" value="<?php echo $nivel; ?>" 
				onkeypress="return permite(event,'num_car', 8);"/>
			</td>
			<td><div align="right">* Hora Incidente/Accidente</div></td>
			<td>
				<input name="txt_horaIncidente" id="txt_horaIncidente" type="text" class="caja_de_texto" size="5" maxlength="5" value="<?php echo $horaInc; ?>" 
				onchange="formatHora(this,'cmb_horaIncidente');" onkeypress="return permite(event,'num', 5);"/>
			    <label>
                   	<select name="cmb_horaIncidente" id="cmb_horaIncidente"  class="combo_box">
                       	<option value="AM">a.m.</option>
                       	<option value="PM">p.m.</option>
               	</select>
              </label>
			</td>
		</tr>
		<tr>
			<td width="92"><div align="right">*&Aacute;rea</div></td>
			<td>
				<?php $cmb_area=$area;
					$conn = conecta("bd_recursos");
					$result=mysql_query("SELECT DISTINCT area,id_depto FROM empleados WHERE id_depto>0 ORDER BY area");
					if($areas=mysql_fetch_array($result)){?>
						<select name="cmb_area" id="cmb_area" size="1" class="combo_box"  
						onchange="cargarCombo(this.value,'bd_recursos','empleados','puesto','area','cmb_puesto','Puesto','');">
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
			<td><div align="right">*&Aacute;rea Incidente/Accidente </div></td>
			<td>
				<label>
					<input name="txt_areaAcc" type="text" id="txt_areaAcc" size="20" maxlength="30" class="caja_de_texto" value="<?php echo $areaAcc?>"
					onkeypress="return permite(event,'num_car', 7);"/>
				</label>
			</td>
			<td><div align="right">*Hora de Aviso de Facilitador </div></td>
		    <td>
				<input name="txt_horaAviso" id="txt_horaAviso" type="text" class="caja_de_texto" size="5" maxlength="5" value="<?php echo $horaAviso;?>"
				onchange="formatHora(this,'cmb_horaAviso');" onkeypress="return permite(event,'num', 5);"/>
                   	<select name="cmb_horaAviso" id="cmb_horaAviso" class="combo_box">
                       	<option value="AM">a.m.</option>
                       	<option value="PM">p.m.</option>
                  	</select>
			</td>
		</tr>
		<tr>
			<td width="92"><div align="right">*Fecha</div></td>
			<td>
				<input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15" value="<?php echo $fecha;?>" 
				readonly="readonly" class="caja_de_texto"/>
			</td>
			<td><div align="right">*Nombre Facilitador </div></td>
			<td>
				<input name="txt_nombreFacilitador" type="text" id="txt_nombreFacilitador" size="20" maxlength="60" class="caja_de_texto" 
				value="<?php echo $nomFacilitador;?>"
				onkeypress="return permite(event,'car', 3);"/>
			</td>
			<td><div align="right">*Hora en la que Dejo de Laborar </div></td>
			<td>
				<input name="txt_horaLaborar" id="txt_horaLaborar" type="text" class="caja_de_texto" size="5" maxlength="5" value="<?php echo $horaLaborar;?>"
				onchange="formatHora(this,'cmb_horaLaborar');" onkeypress="return permite(event,'num', 5);"/>
                   	<select name="cmb_horaLaborar" id="cmb_horaLaborar" class="combo_box">
                       	<option value="AM">a.m.</option>
                       	<option value="PM">p.m.</option>
                   	</select>
			</td>
		</tr>
	</table>
	</fieldset>
		
	<fieldset class="borde_seccion" id="tabla-agregarActa" name="tabla-agregarActa">
	<legend class="titulo_etiqueta">II. Ingresar Datos del Trabajador </legend>		
	<table width="954" height="195"  cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td colspan="2"><div align="right">*Tipo Registro</div></td>
			<td colspan="10">
				<select name="cmb_tipo" id="cmb_tipo" class="combo_box" onchange="mostrarCamposIncAcc(this.value);">
					<option value="INTERNO" selected="selected">INTERNO</option>
					<option value="EXTERNO">EXTERNO</option>
				</select>
			</td>
		</tr>
    	<tr>
        	<td colspan="2"><div align="right">*Nombre Accidentado </div></td>
        	<td colspan="5">
			<span id="ingNomAcc">
				<input type="text" name="txt_nombreAcc" id="txt_nombreAcc" onkeyup="lookup(this,'empleados','1');" size="60" maxlength="80" 
				onkeypress="return permite(event,'car',1);" tabindex="1" value="<?php echo $nomAcc; ?>"/>
				<div id="res-spider">
            	<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;"> <img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
                <div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
            	</div>
        		</div>
			</span>
			</td>
        	<td><div align="right">*Puesto</div></td>
        	<td colspan="4">
			<span id="ingPuesto">
				<select name="cmb_puesto" id="cmb_puesto" class="combo_box">
            		<option value="">Puesto</option>
        		</select>
			</span>
			</td>
      	</tr>
      	<tr>
        	<td width="70" height="42"><div align="right">*Ficha</div></td>
        	<td width="87">
				<input name="txt_ficha" type="text" id="txt_ficha" size="10" maxlength="10" class="caja_de_texto" value="<?php echo $ficha;?>" 
				onkeypress="return permite(event,'num', 3);" readonly="readonly"/>       
			</td>
        	<td width="48"><div align="left">*Edad </div></td>
        	<td width="17">
				<input name="txt_edad" type="text" id="txt_edad" size="2" maxlength="2" class="caja_de_texto" value="<?php echo $edad;?>"
				onkeypress="return permite(event,'num', 3);"/>
			</td>
        	<td width="59"><div align="right">*Equipo Trabajo</div></td>
        	<td width="103">
				<?php $cmb_equipo=$equipo;
					$conn = conecta("bd_mantenimiento");
					$result=mysql_query("SELECT DISTINCT id_equipo FROM equipos ORDER BY id_equipo");
					if($equipos=mysql_fetch_array($result)){?>
            		<select name="cmb_equipo" id="cmb_equipo" size="1" class="combo_box">
              			<option value="">Equipos</option>
						<option value="NO APLICA">NO APLICA</option>
              			<?php 
						  do{
							if ($equipos['id_equipo'] == $cmb_equipo){
								echo "<option value='$equipos[id_equipo]' selected='selected'>$equipos[id_equipo]</option>";
							}
							else{
								echo "<option value='$equipos[id_equipo]'>$equipos[id_equipo]</option>";
							}
						}while($equipos=mysql_fetch_array($result)); 
						//Cerrar la conexion con la BD		
						mysql_close($conn);
						?>
           	 		</select>
            		<?php }
					else{
						echo "<label class='msje_correcto'> No hay Equipos Registrados</label>
						<input type='hidden' name='cmb_equipo' id='cmb_equipo'/>";
					}?>
		  </td>
        		<td width="75"><div align="right">Antig&uuml;edad Empresa</div></td>
        		<td width="78">
					<input name="txt_antEmp" type="text" id="txt_antEmp" size="3" maxlength="3" class="caja_de_texto" value="<?php echo $antEmp;?>" 
					onkeypress="return permite(event,'num', 2);" readonly="readonly"/>
				</td>
        		<td width="83"><div align="right">*Antig&uuml;edad Puesto</div></td>
        		<td width="16">
					<input name="txt_antPue" type="text" id="txt_antPue" size="4" maxlength="4" class="caja_de_texto" value="<?php echo $antPue; ?>"
					onkeypress="return permite(event,'num', 2);"/>
				</td>
        		<td width="73"><div align="right"><span id="label_tipo"></span> al A&ntilde;o</div></td>
        		<td width="58">
					<input name="txt_noAcc" type="text" id="txt_noAcc" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo $noAcc;?>"/>
				</td>
      	</tr>
      	<tr>
       		<td height="42" colspan="2"><div align="right"> *Actividad Desempe&ntilde;ada al Momento del Accidente </div></td>
        	<td height="42" colspan="4">
				<textarea name="txa_actividadMomAcc" id="txa_actividadMomAcc"  maxlength="250" cols="50" rows="3" class="caja_de_texto"  
				onkeypress="return permite(event,'num_car',0);"  type="text" onkeyup="return ismaxlength(this)"><?php echo $actMomAcc; ?></textarea>
			</td>
        	<td height="42" colspan="2"><div align="right">*Actividad Habitual</div></td>
        	<td height="42" colspan="9">
				<textarea name="txa_actHab"  id="txa_actHab" cols="50" rows="3" class="caja_de_texto" maxlength="180" 
				onkeypress="return permite(event,'num_car',0);"  type="text" onkeyup="return ismaxlength(this)"><?php echo $actHab; ?></textarea>
			</td>
      	</tr>
      	<tr><td height="26" colspan="20"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
    </table>
	<div align="center" id="botones">
		<input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar" value="Continuar" title="Continuar Registro Acta Incidentes Accidentes"
		onmouseover="window.status='';return true" />
		&nbsp;&nbsp;&nbsp;
		<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
		&nbsp;&nbsp;&nbsp;
		<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Informe Acta Incidentes/Accidentes" 
		onmouseover="window.status='';return true"  onclick="confirmarSalida('menu_actaIncidentesAccidentes.php')" />
    </div>				
	</fieldset>
	</form>
	
	<div id="calendario4">
		<input name="calendario4" type="image" id="calendario4" onclick="displayCalendar(document.frm_agregarActa.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" 
		border="0"/>					
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
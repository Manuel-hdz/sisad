<?php

	/**
	  * Nombre del M�dulo: Unidad de Salud Ocupacional
	  * Nombre Programador:Nadia Madah� L�pez Hern�ndez
	  * Fecha: 07/Febrero/2012
	  * Descripci�n: Archivo que permite cargar dle historial familiar del trabajador dentro del Historial Clinico
	  **/  
		//Titulo de la ventana emergente
		echo "<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>"; 
		//Inlcuimos archivo que contiene las operaciones necesarias para el registro
		include ("op_generarHistorialClinico.php");
		//Archivo de validacion
		echo "<script type='text/javascript' src='../../includes/validacionClinica.js'></script>";
		//Archivo de Estilo
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
		//Archivo que permite controlar el numero de caracteres ingresados en las cajas de texto (textarea)
		echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";
		
		//Archivo para desabilitar boton regresar del teclado?>
		<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
		<script language="javascript" type="text/javascript" src="../../includes/formatoNumeros.js"></script>
		<?php 

		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");?>
		<script language="javascript" type="text/javascript">
			<!--
			function click() {
				if (event.button==2) {
					alert ('Contenido Protegido, �Concreto Lanzado de Fresnillo MARCA ');
				}
			}
			document.onmousedown=click;
			//-->
		</script>
	<?php
	//Funci�n que se encarga de verificar y validar en caso de que el usuario haya cerrado la ventana emergente desde la X
	 //Definimos una variable bandera
	 $bandera = "";?>
	<script language="javascript" type="text/javascript">
		//Dentro de un Script se define que si la ventana emergente se ha cerrado que se ejecute la siguiente funcion
		function actualizarPag(){
			var nomCkb = document.getElementById("hdn_nomCheckBox").value;
			//var boton = document.getElementById("hdn_guardarHisFam").value;
			var presionado = document.getElementById("hdn_botonCancelar").value;
		
			/*if(window.closed){
				//Dentro de la funcion se declara una variable la cual contendra el valor del CKB
				window.opener.document.getElementById(nomCkb).checked = false;
				<?php $bandera = 1;?>		
			}
			if(!boton){
				window.opener.document.getElementById(nomCkb).checked = true;
			}*/
			if(presionado == "guardar"){
				window.opener.document.getElementById(nomCkb).checked = true;
				window.opener.document.getElementById(nomCkb).disabled = true;
			} else {
				window.opener.document.getElementById(nomCkb).checked = false;
				window.opener.document.getElementById(nomCkb).disabled = false;
			}
		}
	</script>
	
		<style type="text/css">
			<!--
			#titulo-agregar-registros { position:absolute; left:30px; top:22px; width:200px; height:20px; z-index:11;}
			#tabla-agregarRegistro {position:absolute;left:30px;top:48px;width:912px;height:481px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:30px;top:220;width:715px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			#img-imc {position:absolute;left:497px;top:106px;width:492px;height:271px;z-index:14;}
			.Estilo1 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			-->
	    </style>
	<body onUnload="actualizarPag();" >
	<?php
		//Verificamos que el boton "sbt_guardarHisFam" Exista para que se guarden los registros dentro de la BD y ademas se cierre la ventana
		if(isset($_POST["sbt_guardarHisFam"])){
			$respuesta = registrarHistorialFamiliar();
			if($respuesta){
				echo "HISTORIAL FAMILIAR AGREGADO CORRECTAMENTE";
				?>
				<script>
					setTimeout("document.getElementById('hdn_botonCancelar').value = 'guardar';",600);
					setTimeout("window.close();",1000);
				</script>
				<?php
			} else {
				echo "HUBO PROBLEMAS AL REGISTRAR EL HISTORIAL FAMILIAR";
				?>
				<script>
					setTimeout("window.close();",1000);
				</script>
				<?php
			}
		} 
	?>
	<p>&nbsp;</p>
	<form  onSubmit="return valFormHistorialFamiliar(this);" method="post"name="frm_registrarHistorialFamiliar" id="frm_registrarHistorialFamiliar" 
	action="verHistorialFamiliar.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar el Historial Familiar del Trabajador</legend>
	<br>
    	<table cellpadding="2" cellspacing="2" class="tabla_frm">
        	<tr>
       		  <td width="63"><div align="right">*Peso</div></td>
          		<td width="60">
			  		<input type="text" name="txt_peso" id="txt_peso" value="" onKeyPress="return permite(event,'num',2);" class="caja_de_num"  onchange="calcularIMC();" size="5" />			
			  </td>	
				<td width="260"><div align="right">*Talla</div></td>
          		<td width="497">
				  	<input type="text" name="txt_talla" id="txt_talla" value="" size="10" onKeyPress="return permite(event,'num',2);" class="caja_de_num" 
					 onchange="calcularIMC();"/>		  
			  </td>	
        	</tr>
			<tr>
				<td colspan="2"><div align="right">Diam A.P.</div></td>
			  <td>
					<input name="txt_diamAP" type="text" class="caja_de_num" id="txt_diamAP" onKeyPress="return permite(event,'num',2);" value="" size="5" maxlength="5"/>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*Historial Familiar				</td>	
				<td>
				  <textarea name="txa_hisFam" id="txa_hisFam" onKeyUp="return ismaxlength(this)" class="caja_de_texto" rows="3" cols="80" 
					onKeyPress="return permite(event,'num_car', 0);">PADRE DE A�OS, MADRE DE A�OS, HEMANOS, HIJOS, ESPOSA DE A�OS, APARENTEMENTE SANOS</textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2"><div align="right">Diam LAT.</div></td>
				<td>
					<input name="txt_diamLAT" type="text" class="caja_de_num" id="txt_diamLAT" onKeyPress="return permite(event,'num',2);" value="" size="5" maxlength="5"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Antecedetes </td>
				<td colspan="2">
				  <textarea name="txa_ant" id="txa_ant" onKeyUp="return ismaxlength(this)" class="caja_de_texto" rows="3"
				 	cols="80" onKeyPress="return permite(event,'num_car', 0);" ></textarea>
				</td>
			</tr>
			<tr>	
				<td colspan="2"><div align="right">Circ. EXP</div></td>
			  <td>
					<input name="txt_circEXP" type="text" class="caja_de_num" id="txt_circEXP" onKeyPress="return permite(event,'num',2);" value="" size="5" maxlength="5"/>
					&nbsp;&nbsp;&nbsp;&nbsp;*Historial Medica Ant </td>
				<td>
				  <textarea name="txa_hisMedicaAnt" id="txa_hisMedicaAnt" onKeyUp="return ismaxlength(this)" class="caja_de_texto" rows="3"
				 	cols="80" onKeyPress="return permite(event,'num_car', 0);">NIEGA ENFERMEDADES CRONICODEGENERATIVAS, ALERGICOS, QUIRURGICOS Y FRACTURAS, HERNIAS, TUBERCULOSIS, HEPATITIS, TABAQUISMO, ALCOHOLISMO, NIEGA DROGAS DURAS.</textarea>
			  </td>
			</tr>	
			<tr>
				<td colspan="2"><div align="right">Circ. INSP</div></td>
				<td>
					<input name="txt_circINSP" type="text" class="caja_de_num" id="txt_circINSP" onKeyPress="return permite(event,'num',2);" value="" size="5" maxlength="5"/>				
				</td>		
			</tr>
			<tr>
				<td><div align="right">*Pulso</div></td>
				<td>
					<input name="txt_pulso" type="text" class="caja_de_num" id="txt_pulso" onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="11"/>				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Resp</div></td>
				<td>
					<input name="txt_resp" type="text" class="caja_de_num" id="txt_resp" onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="11"/>				
				</td>
				<td><div align="right">*Antecedentes P.P.</div></td>
				<td rowspan="2">
					<textarea name="txa_antPP" id="txa_antPP" onKeyUp="return ismaxlength(this)" class="caja_de_texto" rows="3"
				 	cols="70" onKeyPress="return permite(event,'num_car', 0);" ></textarea>			  
				</td>		
			</tr>
			<tr>
				<td><div align="right">*Temp</div></td>
				<td>
					<input name="txt_temp" type="text" class="caja_de_num" id="txt_temp" onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="11"/>				
				</td>
			</tr>	
			<tr>
				<td><div align="right">*Pres Art.</div></td>
				<td>
					<input name="txt_presArt" type="text" class="caja_de_num" id="txt_presArt" onKeyPress="return permite(event,'num_car',8);" value="" size="10" maxlength="11"/>				
				</td>	
			</tr>
			<tr>	
				<td><div align="right">*IMC</div></td>
				<td>
					<input type="text" name="txt_imc" id="txt_imc" value="" size="10" class="caja_de_num" readonly="readonly" />				
				</td>
				<td><div align="right">*Enf. Prof. y / o Secuelas</div></td>
				<td>
					<input name="txt_secuelas" type="text" class="caja_de_texto" id="txt_secuelas" onKeyPress="return permite(event,'num_car',0);" value="" 
					size="70" maxlength="200"/>				
				</td>	
			</tr>
			<tr>	
				<td><div align="right">*%SpO&sup2;</div></td>
				<td>
					<input name="txt_spo2" type="text" class="caja_de_num"  id="txt_spo2" onKeyPress="return permite(event,'num_car',8);" value="" 
					size="10" maxlength="10" />				
				</td>	
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
          		<td colspan="4">
				  <div align="center">
				 	<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_historialFam"/>
				  	<input type="hidden" name="hdn_botonCancelar" id="hdn_botonCancelar" value="cancelar"/>				
				  	<input type="hidden" name="hdn_guardarHisFam" id="hdn_guardarHisFam" value="sbt_guardarHisFam"/>
				
						<input name="sbt_guardarHisFam" type="submit" class="botones" id= "sbt_guardarHisFam" value="Guardar" 
						title="Guardar los Registros de los Antecedentes Familiares" onMouseOver="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_guiaIMC" type="button" class="botones" id= "sbt_guiaIMC" value="Gu�a IMC" 
						title="Guardar los Registros de los Antecedentes Familiares" onMouseOver="window.status='';return true" 
						onClick="javascript:window.open('images/clasificacion_imc.png','_blank','top=0, left=0, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes,toolbar=no, location=no,directories=no');"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar"  title="Regresar al Registro del Historial Clinico"
						onMouseOver="window.status='';return true"  onclick="hdn_botonCancelar.value='cancelar';cancelarRegistrosHistorialClinico();"/>
       			  </div>				
				</td>
        	</tr>
   	</table>
		</div>
	</fieldset>
	</form>
	</body>
<?php

	/**
	  * Nombre del M�dulo: Unidad de Salud Ocupacional
	  * Nombre Programador:Nadia Madah� L�pez Hern�ndez
	  * Fecha: 19/Julio/2012
	  * Descripci�n: Archivo que permite cargar los aspectos generales del trabajador dentro del Historial Clinico
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
		echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";?>
		<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script><?php 

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
			/*var boton = document.getElementById("hdn_guardarAspGrales2").value;
			
			if(window.closed){
				//Dentro de la funcion se declara una variable la cual contendra el valor del CKB
				window.opener.document.getElementById(nomCkb).checked = false;
				<?php $bandera = 1;?>		
			}
			if(!boton){
				window.opener.document.getElementById(nomCkb).checked = true;
			}*/
			
			var presionado = document.getElementById("hdn_botonCancelar").value;
			
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
			#tabla-agregarRegistro {position:absolute;left:30px;top:48px;width:897px;height:307px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:30px;top:220;width:715px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			.Estilo1 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			-->
	    </style>
	<?php
		
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_guardarAspGrales2"])){
			$respuesta = registrarAspectosGrales2();
			if($respuesta){
				echo "ASPECTOS GENERALES 2 AGREGADOS CORRECTAMENTE";
				?>
				<script>
					setTimeout("document.getElementById('hdn_botonCancelar').value = 'guardar';",600);
					setTimeout("window.close();",1000);
				</script>
				<?php
			} else {
				echo "HUBO PROBLEMAS AL REGISTRAR LOS ASPECTOS GENERALES 2";
				?>
				<script>
					setTimeout("window.close();",1000);
				</script>
				<?php
			}
		}
	
	?>
	<body onUnload="actualizarPag();">
	<p>&nbsp;</p>
	<form  onSubmit="return valFormAspectosGrales2(this);"method="post" name="frm_regAspGrales2" id="frm_regAspGrales2" 
	action="verAspectosGenerales2.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar los Aspectos Generales del Trabajdor /2</legend>
	<br>
    	<table cellpadding="2" cellspacing="2" class="tabla_frm">
        	<tr>
			  <td width="93"><div align="right">*Nariz</div></td>
          		<td width="240">
	  		  		<input name="txt_nariz" type="text" class="caja_de_texto" id="txt_nariz"  onKeyPress="return permite(event,'num_car',8);" value="NORMAL, SIN DESVIACI�N" 
					size="28" maxlength="40" />			  </td>	
		  	  <td width="82"><div align="right">*Obstrucci&oacute;n</div></td>
				<td width="180">
	  		  		<input name="txt_obstruccion" type="text" class="caja_de_texto" id="txt_obstruccion"  onKeyPress="return permite(event,'num_car',8);" value="NO" size="30" 
					maxlength="30" />			  
				</td>	
        	</tr>
			<tr>
				<td><div align="right">*Boca y Garganta</div></td>
          		<td><input name="txt_boca" type="text" class="caja_de_texto" id="txt_boca"   onKeyPress="return permite(event,'num_car',8);" 
					value="DE CARACTERISTICAS NORMALES" size="35" maxlength="35" />				
				</td>	
				<td><div align="right">*Encias</div></td>
				<td>
				  	<input name="txt_encias" type="text" class="caja_de_texto" id="txt_encias" 
					  onKeyPress="return permite(event,'num_car',8);" value="NORMAL" size="25" maxlength="25"/>				
				</td>
		  	  <td width="73"><div align="right">*Dientes</div></td>
				<td width="173">
		  	  		<input name="txt_dientes" type="text" class="caja_de_texto" id="txt_dientes" 
					  onKeyPress="return permite(event,'num_car',8);" value="COMPLETOS" size="25" maxlength="25"/>			  
				</td>	
			<tr>
				<td><div align="right">*Cuello</div></td>
          		<td>
	  		  		<input name="txt_cuello" type="text" class="caja_de_texto" id="txt_cuello" onKeyPress="return permite(event,'num_car',8);" value="NORMAL"   size="25" 
					maxlength="25" />				
				</td>	
				<td><div align="right">*Linfaticos</div></td>
          		<td>
		  	  		<input name="txt_linfaticos" type="text" class="caja_de_texto" id="txt_linfaticos" 
					  onKeyPress="return permite(event,'num_car',8);" value="NO SE PALPAN ADENOPATIAS" size="30" maxlength="30"/>				
				</td>	
        	</tr>
			<tr>
				<td><div align="right">*Torax</div></td>
          		<td colspan="4">
	  		  		<input name="txt_torax" type="text" class="caja_de_texto" id="txt_torax" onKeyPress="return permite(event,'num_car',8);" 
					value="DE FORMA  VOLUMEN Y ESTADO DE SUPERFICIE NORMAL"   size="65" maxlength="65" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Coraz&oacute;n</div></td>
          		<td colspan="4">
					<input name="txt_corazon" type="text" class="caja_de_texto" id="txt_corazon" onKeyPress="return permite(event,'num_car',8);" 
					value="�REA Y RUIDOS CARDIACOS DE CARACTERISTICAS NORMALES"   size="70" maxlength="70" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Pulmones</div></td>
          		<td colspan="4">
					<input name="txt_pulmones" type="text" class="caja_de_texto" id="txt_pulmones" onKeyPress="return permite(event,'num_car',8);" 
					value="BIEN VENTILADOS SIN RUIDOS AGREGADOS"   size="60" maxlength="60" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Abdomen</div></td>
       		  	<td><input name="txt_abdomen" type="text" class="caja_de_texto" id="txt_abdomen"   onKeyPress="return permite(event,'num_car',8);"
					 value="BLANDO, DEPRESIBLE NO DOLOROSO" size="40" maxlength="60" />				</td>	
				<td><div align="right">*Higado</div></td>          		
				<td>
					<input name="txt_higado" type="text" class="caja_de_texto" id="txt_higado" 
					  onKeyPress="return permite(event,'num_car',8);" value="NO PALPABLE" size="15" maxlength="15" align="absmiddle"/>				
				</td>	
			  	<td><div align="right">*Bazo</div></td>
          		<td>
				  	<input name="txt_bazo" type="text" class="caja_de_texto" id="txt_bazo" 
					  onKeyPress="return permite(event,'num_car',8);" value="NO PALPABLE" size="15" maxlength="15"/>				
				</td>
        	</tr>	
			<tr>
				<td><div align="right">*Pared Abdominal</div></td>
       		  	<td>
			  		<input name="txt_pared" type="text" class="caja_de_texto" id="txt_pared"   onKeyPress="return permite(event,'num_car',8);"
					 value="INTEGRA" size="25" maxlength="60" />				</td>	
				<td><div align="right">*Anillos</div></td>          		
				<td>
					<input name="txt_anillos" type="text" class="caja_de_texto" id="txt_anillos" 
					  onKeyPress="return permite(event,'num_car',8);" value="LIBRES" size="10" maxlength="10" align="absmiddle"/>				
				</td>	
			  	<td><div align="right">*Hernias</div></td>
          		<td><input name="txt_hernias" type="text" class="caja_de_texto" id="txt_hernias" 
					onKeyPress="return permite(event,'num_car',8);" value="NO" size="10" maxlength="10"/>
				</td>
        	</tr>	
			<tr>
				<td><div align="right">*Gen Uri.</div></td>
       		  	<td>
			  		<input name="txt_genUri" type="text" class="caja_de_texto" id="txt_genUri"   onKeyPress="return permite(event,'num_car',8);"
					 value="DE CARACTERISTICAS NORMALES" size="40" maxlength="60" />				
				</td>	
				<td><div align="right">*Hidrocele</div></td>          		
				<td>
					<input name="txt_hidro" type="text" class="caja_de_texto" id="txt_hidro" 
					  onKeyPress="return permite(event,'num_car',8);" value="NO" size="10" maxlength="10" align="absmiddle"/>				
				</td>	
			  	<td><div align="right">*Varicocele</div></td>
          		<td>
				  	<input name="txt_vari" type="text" class="caja_de_texto" id="txt_vari" 
					  onKeyPress="return permite(event,'num_car',8);" value="NO" size="10" maxlength="10"/>				
				</td>
        	</tr>
			<tr>
				<td><div align="right">*Hemorroides</div></td>
          		<td colspan="4">
	  		  		<input name="txt_hemo" type="text" class="caja_de_texto" id="txt_hemo" onKeyPress="return permite(event,'num_car',8);" value="NEGATIVOS"  
					size="60" maxlength="60" />				</td>	
			</tr>	
			<tr>
				<td><div align="right">*Extr. Suprs.</div></td>
          		<td colspan="4">
	  		  		<input name="txt_extSup" type="text" class="caja_de_texto" id="txt_extSup" onKeyPress="return permite(event,'num_car',8);"
					 value="INTEGRAS, SIMETRICAS Y ARCOS DE MOVILIDAD PALPABLES"   size="70" maxlength="70" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Extr. Infrs.</div></td>
          		<td colspan="4">
	  		  		<input name="txt_extInf" type="text" class="caja_de_texto" id="txt_extInf" onKeyPress="return permite(event,'num_car',8);" 
					value="INTEGRAS, SIMETRICAS Y ARCOS DE MOVILIDAD PALPABLES"   size="70" maxlength="70" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Reflejos O.T.</div></td>
          		<td>
	  		  		<input name="txt_reflejos" type="text" class="caja_de_texto" id="txt_reflejos" onKeyPress="return permite(event,'num_car',8);" value="NORMORREFLEXICOS"   
					size="25" maxlength="40" />				</td>
				<td><div align="right">*Psiquismo</div></td>
          		<td>
	  		  		<input name="txt_psiquismo" type="text" class="caja_de_texto" id="txt_psiquismo" onKeyPress="return permite(event,'num_car',8);" value="ESTABLE"   
					size="30" maxlength="40" />				
				</td>	
			</tr>
			<tr>
				<td><div align="right">*Sintomat. Actual</div></td>
          		<td colspan="4">
	  		  		<input name="txt_sintoma" type="text" class="caja_de_texto" id="txt_sintoma" onKeyPress="return permite(event,'num_car',8);" value="ASINTOMATICO"   
					size="60" maxlength="60" />				
				</td>	
			</tr>
			<tr>
          		<td colspan="8">
					<div align="center">
						<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_aspetosGrales2"/>
						<input type="hidden" name="hdn_botonCancelar" id="hdn_botonCancelar" value=""/>
				  		<input type="hidden" name="hdn_guardarAspGrales2" id="hdn_guardarAspGrales2" value="sbt_guardarAspGrales2"/>
						
							<input name="sbt_guardarAspGrales2" type="submit" class="botones" id= "sbt_guardarAspGrales2" value="Guardar" 
							title="Guardar los Aspectos Generales/2" onMouseOver="window.status='';return true" />
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Cerrar"  title="Regresar al Registro del Historial Clinico"
							onMouseOver="window.status='';return true"  onClick="hdn_botonCancelar.value='cancelar';cancelarRegistrosHistorialClinico();"/>
       			  </div>				
				</td>
        	</tr>
		</table>
	</fieldset>
</form>
</body>
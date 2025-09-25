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
		echo "<script type='text/javascript' src='../../includes/maxLength.js' ></script>";
		//Archivo para desabilitar boton regresar del teclado?>
		<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
		<script language="javascript" type="text/javascript" src="../../includes/formatoNumeros.js"></script><?php 
		
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
			/*var boton = document.getElementById("hdn_guardarAspGrales1").value;
			
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
			#tabla-agregarRegistro {position:absolute;left:30px;top:48px;width:690px;height:307px;z-index:12;}
			#tabla-mostrarRegistros {position:absolute;left:30px;top:220;width:715px;height:230px;z-index:16;padding:15px;padding-top:0px; overflow:scroll}
			.Estilo1 {
				font-family: Arial, Helvetica, sans-serif;
				font-size: 12px;
			}
			-->
	    </style>
	<?php
		
		//Verificamos que exista el boton agregar para poder agregar los datos en la session
		if(isset($_POST["sbt_guardarAspGrales1"])){
			$respuesta = registrarAspectosGrales1();
			if($respuesta){
				echo "ASPECTOS GENERALES 1 AGREGADOS CORRECTAMENTE";
				?>
				<script>
					setTimeout("document.getElementById('hdn_botonCancelar').value = 'guardar';",600);
					setTimeout("window.close();",1000);
				</script>
				<?php
			} else {
				echo "HUBO PROBLEMAS AL REGISTRAR LOS ASPECTOS GENERALES 1";
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
	<form  onSubmit="return valFormAspectosGrales1(this);"method="post" name="frm_regAspGrales1" id="frm_regAspGrales1" 
	action="verAspectosGenerales1.php">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingresar los Aspectos Generales del Trabajador /1</legend>
	<br>
    	<table width="100%" cellpadding="4" cellspacing="4" class="tabla_frm">
        	<tr>
				<td><div align="right">*Tipo</div></td>
          		<td>
			  		<input name="txt_tipoGral" type="text" class="caja_de_texto" id="txt_tipoGral"  onKeyPress="return permite(event,'num_car',8);" 
					value="NORMOLINEO" size="15" maxlength="20" />				</td>	
				<td><div align="right">*Nutrici&oacute;n</div></td>
				<td>
			  		<input name="txt_nutricion" type="text" class="caja_de_texto" id="txt_nutricion"  onKeyPress="return permite(event,'num_car',8);" value="REGULAR" size="10" 
					maxlength="15" />				</td>	
				<td><div align="right">*Piel</div></td>
          		<td>
					<input name="txt_piel" type="text" class="caja_de_texto" id="txt_piel" onKeyPress="return permite(event,'num_car',8);" value="NORMAL" size="10" maxlength="15"/>							
				</td>	
				<td><div align="right">*Lentes</div></td>
          		<td>
				  	<select name="cmb_lentes" class="combo_box" id="cmb_lentes" >
						<option value="" selected="selected">Lentes</option>
						<option value="SI">SI</option>
						<option value="NO">NO</option>																																																				
					</select>				
				</td>	
        	</tr>
			<tr>
				<td rowspan="4"><div align="right"><strong>*OJOS</strong></div></td>	
				<td height="37">&nbsp;</td>
				<td><div align="center">DER</div></td>
				<td><div align="center">*IZQ</div></td>
				<td>&nbsp;</td>
			  	<td><div align="center">*DER</div></td>
				<td><div align="center">*IZQ</div></td>
        	</tr>
			<tr>
				<td><div align="right">*Visi&oacute;n</div></td>
          		<td>
			  		<input name="txt_visionDer" type="text" class="caja_de_num" id="txt_visionDer" onKeyPress="return permite(event,'num_car',8);" value="" size="10" 
					maxlength="10" />				</td>	
          		<td>
					<input name="txt_visionIzq" type="text" class="caja_de_num" id="txt_visionIzq" 
					onKeyPress="return permite(event,'num_car',8);" value="" size="10" maxlength="10" align="absmiddle"/>				
				</td>	
				<td><div align="right">*Reflejos</div></td>
          		<td>
				  	<input name="txt_refDer" type="text" class="caja_de_texto" id="txt_refDer" 
					 onKeyPress="return permite(event,'num_car',1);" value="NORMALES" size="10" maxlength="10"/>				
				</td>	
				<td>
		  	  		<input name="txt_refIzq" type="text" class="caja_de_texto" id="txt_refIzq" 
					 onKeyPress="return permite(event,'num_car',1);" value="NORMALES" size="10" maxlength="10"/>				
				</td>	
        	</tr>
			<tr>
				<td><div align="right">*Pterygiones</div></td>
				<td>
					<input name="txt_pterDer" type="text" class="caja_de_texto" id="txt_pterDer"  onKeyPress="return permite(event,'num_car',1);" 
					value="NEGATIVO" size="10" maxlength="10" />				
				</td>	
          		<td>
					<input name="txt_pterIzq" type="text" class="caja_de_texto" id="txt_pterIzq" 
					onKeyPress="return permite(event,'num_car',1);" value="NEGATIVO" size="10" maxlength="10"/>				
				</td>	
			  	<td><div align="right">*Otros</div></td>
          		<td>
					<input name="txt_otrosDer" type="text" class="caja_de_texto" id="txt_otrosDer" 
					 onKeyPress="return permite(event,'num_car',1);" value="NO" size="10" maxlength="10"/>				
				</td>	
				<td>
		  	  		<input name="txt_otrosIzq" type="text" class="caja_de_texto" id="txt_otrosIzq" 
					 onKeyPress="return permite(event,'num_car',1);" value="NO" size="10" maxlength="10"/>				
				</td>	
        	</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>												
			</tr>
			<tr>
       		  	<td rowspan="4"><div align="right"><strong>*OIDOS</strong></div></td>	
        	</tr>
			<tr>
				<td><div align="right">*Audici&oacute;n</div></td>
       		  	<td>
			  		<input name="txt_audDer" type="text" class="caja_de_num" id="txt_audDer"  onKeyPress="return permite(event,'num',2);"
					 value="" size="10" maxlength="10" />%
				</td>	
       		  	<td>
					<input name="txt_audIzq" type="text" class="caja_de_num" id="txt_audIzq" 
					 onKeyPress="return permite(event,'num',2);" value="" size="10" maxlength="10"/>%
				</td>	
			  	<td><div align="right">*Canal</div></td>
          		<td>
				  	<input name="txt_canalDer" type="text" class="caja_de_texto" id="txt_canalDer" 
					onKeyPress="return permite(event,'num_car',1);" value="LIBRES" size="10" maxlength="10"/>				
				</td>
				<td>
				  	<input name="txt_canalIzq" type="text" class="caja_de_texto" id="txt_canalIzq" 
					  onKeyPress="return permite(event,'num_car',1);" value="LIBRES" size="10" maxlength="10"/>				
				</td>	
        	</tr>	
			<tr>
				<td><div align="right">*Membrana</div></td>
          		<td>
	  		  		<input name="txt_memDer" type="text" class="caja_de_texto" id="txt_memDer"  onKeyPress="return permite(event,'num_car',1);" 
					value="INTEGRA" size="10" maxlength="15" />				
				</td>	
          		<td>
				  	<input name="txt_memIzq" type="text" class="caja_de_texto" id="txt_memIzq" 
					onKeyPress="return permite(event,'num_car',1);" value="INTEGRA" size="10" maxlength="15"/>				
				</td>	
			<tr>
				<td><div align="right">*HBC</div></td>
          		<td>
	  		  		<input name="txt_hbc" type="text" class="caja_de_num" id="txt_hbc" size="5" maxlength="5" />
	  		  		%						
				</td>	
				<td><div align="right">*Tipo</div></td>
          		<td>
					<select name="cmb_tipo" class="combo_box" id="cmb_tipo" >
						<option value="" selected="selected">Tipo</option>
						<option value="NORMAL">NORMAL</option>
						<option value="SI PROFESIONAL">SI PROFESIONAL</option>	
						<option value="NO PROFESIONAL">NO PROFESIONAL</option>
						<option value="MIXTA">MIXTA</option>																																																																																																												
					</select>	
				</td>	
				<td><div align="right">*% IPP</div></td> 
				<td>
					<input name="txt_ipp" type="text" class="caja_de_num" id="txt_ipp" onKeyPress="return permite(event,'num',2);" value="0" size="5" maxlength="5" />
					%				
				</td>	
        	</tr>
			<tr>
          		<td colspan="8">
					<div align="center">
						<input type="hidden" name="hdn_nomCheckBox" id="hdn_nomCheckBox" value="ckb_aspetosGrales1"/>
						<input type="hidden" name="hdn_botonCancelar" id="hdn_botonCancelar" value=""/>
				  		<input type="hidden" name="hdn_guardarAspGrales1" id="hdn_guardarAspGrales1" value="sbt_guardarAspGrales1"/>
						
							<input name="sbt_guardarAspGrales1" type="submit" class="botones" id= "sbt_guardarAspGrales1" value="Guardar" 
							title="Guardar los Registros de los Aspectos Generales/1" onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Cerrar"  title="Regresar al Registro del Historial Familiar"
							onMouseOver="window.status='';return true"  onClick="hdn_botonCancelar.value='cancelar';cancelarRegistrosHistorialClinico();"/>
	       			</div>				
				</td>
        	</tr>
   		</table>
	</fieldset>
</form>
</body>